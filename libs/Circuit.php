<?php
/**
 * Le circuit du courrier ANASER.
 *
 * Cette classe rassemble les regles qui etaient jusqu'ici ecrites en dur et
 * dispersees dans six fichiers : les etats du courrier et de l'imputation, les
 * transitions declenchees par chaque action, et la regle de cloture.
 *
 * Le probleme qu'elle resout : la meme regle etait ecrite plusieurs fois, sous
 * des formes qui ne concordaient pas. L'etat 6 d'une imputation, « pour
 * information », etait ainsi considere comme non traite sur un ecran, traite
 * sur un autre, et non bloquant sur un troisieme. Un nombre nu dans une
 * requete ne dit pas ce qu'il signifie, et rien ne signale qu'il faut le
 * changer partout a la fois.
 *
 * Toute regle de circulation du courrier a desormais un seul endroit ou vivre.
 *
 * @category  Metier
 */
defined('ROOT') or exit('No direct script access allowed');

class Circuit
{
	// ---------------------------------------------------------------- etats

	/**
	 * Etat du courrier, tel qu'enregistre dans la colonne courrier.etat.
	 * Les valeurs correspondent aux lignes de la table `etat`.
	 */
	const COURRIER_NOUVEAU  = 1; // enregistre au bureau du courrier, pas encore impute
	const COURRIER_TRANSMIS = 2; // transmis pour imputation
	const COURRIER_IMPUTE   = 4; // impute, non retourne
	const COURRIER_TRAITE   = 5; // traite et retourne
	const COURRIER_ARCHIVE  = 6; // classe et archive

	/**
	 * Etat d'une imputation, colonne imputation.etat_traitement.
	 * Valeurs de la table `etat_imputation`.
	 */
	const IMPUTATION_NOUVELLE    = 1; // adressee, pas encore ouverte
	const IMPUTATION_EN_COURS    = 2; // prise en charge
	const IMPUTATION_TRAITEE     = 3; // traitee et renvoyee
	const IMPUTATION_INFORMATION = 6; // pour information : a lire, mais rien a rendre

	/** Delai de traitement propose par defaut, en jours. */
	const DELAI_DEFAUT = 8;

	// ------------------------------------------------- lecture des etats

	/**
	 * Etats d'imputation qui appellent encore une action de leur destinataire.
	 * Une imputation « pour information » en fait partie : son destinataire
	 * doit la voir, meme s'il n'a rien a retourner.
	 * @return array
	 */
	public static function etatsImputationAOuvrir()
	{
		return array(self::IMPUTATION_NOUVELLE, self::IMPUTATION_EN_COURS, self::IMPUTATION_INFORMATION);
	}

	/**
	 * Etats d'imputation qui empechent la cloture du courrier.
	 * Le « pour information » n'est pas bloquant : on ne va pas retenir un
	 * dossier parce qu'une personne n'a pas accuse reception d'une copie.
	 * @return array
	 */
	public static function etatsImputationBloquants()
	{
		return array(self::IMPUTATION_NOUVELLE, self::IMPUTATION_EN_COURS);
	}

	/**
	 * Etats de courrier consideres comme clos, donc exclus des encours.
	 * @return array
	 */
	public static function etatsCourrierClos()
	{
		return array(self::COURRIER_TRAITE, self::COURRIER_ARCHIVE);
	}

	/**
	 * Fabrique une liste SQL a partir d'un tableau d'entiers.
	 * Les valeurs sont forcees en entier : aucune donnee externe ne transite.
	 * @return string
	 */
	public static function liste(array $etats)
	{
		return implode(', ', array_map('intval', $etats));
	}

	// -------------------------------------------------------- transitions

	/**
	 * Recalcule l'etat d'un courrier a partir de ses imputations.
	 *
	 * C'est le coeur du circuit, et le seul endroit ou l'etat d'un courrier
	 * change du fait d'une imputation. La methode deduit l'etat plutot que de
	 * l'imposer, ce qui la rend rejouable : on peut l'appeler apres une
	 * creation, une modification ou une suppression, le resultat est le meme.
	 *
	 *   aucune imputation            -> le courrier redevient nouveau
	 *   au moins une imputation      -> impute, non retourne
	 *   en cours
	 *   toutes achevees              -> traite et retourne
	 *
	 * Un courrier archive n'est jamais reouvert : l'archivage est une decision
	 * humaine, elle ne doit pas etre defaite par un effet de bord.
	 *
	 * @param  object $db          instance PDODb
	 * @param  int    $idcourrier
	 * @return int|null nouvel etat, ou null si rien n'a change
	 */
	public static function recalculerEtatCourrier($db, $idcourrier)
	{
		$idcourrier = intval($idcourrier);
		if (!$idcourrier) {
			return null;
		}

		$db->where('idcourrier', $idcourrier);
		$etat_actuel = intval($db->getValue('courrier', 'etat'));

		if ($etat_actuel === self::COURRIER_ARCHIVE) {
			return null;
		}

		$compte = $db->rawQueryOne(
			"SELECT COUNT(*) AS total,
			        SUM(CASE WHEN etat_traitement IN (" . self::liste(self::etatsImputationBloquants()) . ")
			                 THEN 1 ELSE 0 END) AS bloquantes
			   FROM imputation WHERE idcourrier = ?",
			array($idcourrier)
		);

		$total      = (is_array($compte) && isset($compte['total']))      ? (int) $compte['total']      : 0;
		$bloquantes = (is_array($compte) && isset($compte['bloquantes'])) ? (int) $compte['bloquantes'] : 0;

		if ($total === 0) {
			$nouvel_etat = self::COURRIER_NOUVEAU;
		} elseif ($bloquantes > 0) {
			$nouvel_etat = self::COURRIER_IMPUTE;
		} else {
			$nouvel_etat = self::COURRIER_TRAITE;
		}

		if ($nouvel_etat === $etat_actuel) {
			return null;
		}

		$db->where('idcourrier', $idcourrier);
		$db->update('courrier', array('etat' => $nouvel_etat));

		return $nouvel_etat;
	}

	/**
	 * Inscrit une action au journal des evenements.
	 * Centralise ici pour que toute etape du circuit laisse la meme trace,
	 * avec les memes colonnes renseignees.
	 *
	 * @param  object $db
	 * @param  int    $idcourrier
	 * @param  string $action        libelle lisible de l'action
	 * @param  int    $destinataire  utilisateur concerne, 0 si sans objet
	 * @return void
	 */
	public static function journaliser($db, $idcourrier, $action, $destinataire = 0)
	{
		$idcourrier = intval($idcourrier);
		if (!$idcourrier) {
			return;
		}

		$db->where('idcourrier', $idcourrier);
		$numero = $db->getValue('courrier', 'numero_courrier');

		$db->insert('evenement', array(
			'userid'          => get_active_user('iduser'),
			'numero_courrier' => $numero,
			'destinataire'    => intval($destinataire),
			'idcourrier'      => $idcourrier,
			'action'          => $action,
		));
	}

	/**
	 * Transmet un courrier fraichement enregistre au profil de reception.
	 *
	 * Tout courrier entrant doit remonter au Directeur General. Laisser cette
	 * etape a la memoire de l'agent, c'est accepter qu'un pli reste
	 * indefiniment a l'etat « nouveau », invisible de tous et sans alerte.
	 *
	 * La methode ne devine jamais. Si le profil de reception ne correspond a
	 * aucun utilisateur, ou a plusieurs, elle ne fait rien et le signale : mieux
	 * vaut demander a l'agent d'imputer lui-meme que d'adresser un courrier a la
	 * mauvaise personne.
	 *
	 * @param  object $db
	 * @param  int    $idcourrier
	 * @param  string $intitule    pour le journal des evenements
	 * @return array  array(bool $transmis, string $message)
	 */
	public static function transmettreAuDestinataireInitial($db, $idcourrier, $intitule = '')
	{
		$idcourrier = intval($idcourrier);
		if (!$idcourrier || !defined('PROFIL_RECEPTION_COURRIER') || trim(PROFIL_RECEPTION_COURRIER) === '') {
			return array(false, '');
		}

		// Une imputation existe deja : on ne double pas.
		$deja = $db->rawQueryOne(
			"SELECT COUNT(*) AS n FROM imputation WHERE idcourrier = ?",
			array($idcourrier)
		);
		if (is_array($deja) && (int) $deja['n'] > 0) {
			return array(false, '');
		}

		// La comparaison des intitules de profil se fait en PHP, pas en SQL.
		//
		// S'en remettre au classement de la base pour ignorer les accents rend le
		// resultat dependant de la configuration du serveur : selon le classement
		// retenu, « Directeur General » rejoint ou ne rejoint pas « Directeur
		// Général ». Un courrier ne serait alors transmis a personne, sans que la
		// cause en soit visible. La normalisation employee ici est celle du reste
		// de l'application.
		$normaliser = function ($v) {
			$v = trim((string) $v);
			$v = function_exists('mb_strtolower') ? mb_strtolower($v, 'UTF-8') : strtolower($v);
			return strtr($v, array('à'=>'a','â'=>'a','ä'=>'a','é'=>'e','è'=>'e','ê'=>'e','ë'=>'e','î'=>'i','ï'=>'i','ô'=>'o','ö'=>'o','ù'=>'u','û'=>'u','ü'=>'u','ç'=>'c'));
		};
		$profil_attendu = $normaliser(PROFIL_RECEPTION_COURRIER);

		$candidats = $db->rawQuery(
			"SELECT u.iduser, u.identification, u.niveau_imputation, r.role_name
			   FROM user u
			   JOIN roles r ON r.role_id = u.user_role_id
			  WHERE (u.is_deleted IS NULL OR u.is_deleted = '')",
			array()
		);

		$destinataires = array();
		if (is_array($candidats)) {
			foreach ($candidats as $candidat) {
				if ($normaliser($candidat['role_name']) === $profil_attendu) {
					$destinataires[] = $candidat;
				}
			}
		}

		$nombre = count($destinataires);

		if ($nombre === 0) {
			return array(false, "Aucun utilisateur ne porte le profil « " . PROFIL_RECEPTION_COURRIER
				. " » : le courrier n'a ete transmis a personne. Imputez-le manuellement.");
		}
		if ($nombre > 1) {
			return array(false, $nombre . " utilisateurs portent le profil « " . PROFIL_RECEPTION_COURRIER
				. " » : la plateforme ne choisit pas a votre place. Imputez le courrier manuellement.");
		}

		$destinataire = $destinataires[0];
		$aujourdhui   = date('Y-m-d');
		$echeance     = date('Y-m-d', strtotime('+' . self::DELAI_DEFAUT . ' days'));

		$db->insert('imputation', array(
			'date_imputation'    => $aujourdhui,
			'delai_traitement'   => self::DELAI_DEFAUT,
			'etat_traitement'    => self::IMPUTATION_NOUVELLE,
			'idcourrier'         => $idcourrier,
			'iduser'             => intval($destinataire['iduser']),
			'instruction'        => 'Transmission automatique du bureau du courrier.',
			'date_limite_traite' => $echeance,
			'niveau'             => intval($destinataire['niveau_imputation']),
			'origine'            => intval(USER_ID),
		));

		self::recalculerEtatCourrier($db, $idcourrier);
		self::journaliser($db, $idcourrier,
			"Transmission au " . PROFIL_RECEPTION_COURRIER . " : " . $intitule,
			intval($destinataire['iduser']));

		return array(true, "Courrier transmis a " . $destinataire['identification']
			. " (" . PROFIL_RECEPTION_COURRIER . ").");
	}

	/**
	 * Echelon a inscrire sur une imputation : celui du destinataire choisi.
	 *
	 * On ne peut pas se contenter de « mon echelon plus un » : le chef du
	 * bureau du courrier transmet indifferemment a l'assistante du Directeur
	 * General ou au Directeur General lui-meme, qui occupent deux echelons
	 * distincts. Le repli sur « plus un » ne sert que si le destinataire n'a
	 * pas d'echelon renseigne sur sa fiche.
	 *
	 * @param  object $db
	 * @param  int    $iduser destinataire
	 * @return int
	 */
	public static function echelonDestinataire($db, $iduser)
	{
		$db->where('iduser', intval($iduser));
		$echelon = $db->getValue('user', 'niveau_imputation');

		return !empty($echelon)
			? intval($echelon)
			: intval(get_active_user('niveau_imputation')) + 1;
	}
}
