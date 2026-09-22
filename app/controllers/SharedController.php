<?php 

/**
 * SharedController Controller
 * @category  Controller / Model
 */
class SharedController extends BaseController{
	
	/**
     * user_niveau_imputation_option_list Model Action
     * @return array
     */
	function user_niveau_imputation_option_list(){
		$db = $this->GetModel();
		$sqltext = "SELECT  DISTINCT idniveau AS value,niveau_imputation AS label FROM niveau_imputation ORDER BY idniveau";
		$queryparams = null;
		$arr = $db->rawQuery($sqltext, $queryparams);
		return $arr;
	}

	/**
     * user_login_value_exist Model Action
     * @return array
     */
	function user_login_value_exist($val){
		$db = $this->GetModel();
		$db->where("login", $val);
		$exist = $db->has("user");
		return $exist;
	}

	/**
     * user_emailuser_value_exist Model Action
     * @return array
     */
	function user_emailuser_value_exist($val){
		$db = $this->GetModel();
		$db->where("emailuser", $val);
		$exist = $db->has("user");
		return $exist;
	}

	/**
     * user_user_role_id_option_list Model Action
     * @return array
     */
	function user_user_role_id_option_list(){
		$db = $this->GetModel();
		$sqltext = "SELECT role_id AS value, role_name AS label FROM roles";
		$queryparams = null;
		$arr = $db->rawQuery($sqltext, $queryparams);
		return $arr;
	}

	/**
     * user_direction_option_list Model Action
     * @return array
     */
	function user_direction_option_list(){
		$db = $this->GetModel();
		$sqltext = "SELECT  DISTINCT iddirection AS value,direction AS label FROM direction ORDER BY direction";
		$queryparams = null;
		$arr = $db->rawQuery($sqltext, $queryparams);
		return $arr;
	}

	/**
     * user_division_option_list Model Action
     * @return array
     */
	function user_division_option_list($lookup_direction){
		$db = $this->GetModel();
		$sqltext = "SELECT  DISTINCT iddivision AS value,division AS label FROM division WHERE direction= ? ORDER BY iddivision" ;
		$queryparams = array($lookup_direction);
		$arr = $db->rawQuery($sqltext, $queryparams);
		return $arr;
	}

	/**
     * courrier_expediteur_option_list Model Action
     * @return array
     */
	function courrier_expediteur_option_list(){
		$db = $this->GetModel();
		$sqltext = "SELECT  DISTINCT idexpediteur AS value,expediteur AS label FROM expediteur ORDER BY expediteur";
		$queryparams = null;
		$arr = $db->rawQuery($sqltext, $queryparams);
		return $arr;
	}

	/**
     * courrier_nature_option_list Model Action
     * @return array
     */
	function courrier_nature_option_list(){
		$db = $this->GetModel();
		$sqltext = "SELECT  DISTINCT idnature AS value,nature AS label FROM nature_courrier";
		$queryparams = null;
		$arr = $db->rawQuery($sqltext, $queryparams);
		return $arr;
	}

	/**
     * courrier_sens_option_list Model Action
     * @return array
     */
	function courrier_sens_option_list(){
		$db = $this->GetModel();
		$sqltext = "SELECT  DISTINCT idsens AS value,sens AS label FROM sens";
		$queryparams = null;
		$arr = $db->rawQuery($sqltext, $queryparams);
		return $arr;
	}

	/**
     * courrier_numero_courrier_value_exist Model Action
     * @return array
     */
	function courrier_numero_courrier_value_exist($val){
		$db = $this->GetModel();
		$db->where("numero_courrier", $val);
		$exist = $db->has("courrier");
		return $exist;
	}

	/**
     * courrier_expediteur_option_list_2 Model Action
     * @return array
     */
	function courrier_expediteur_option_list_2(){
		$db = $this->GetModel();
		$sqltext = "SELECT  DISTINCT idexpediteur AS value,expediteur AS label FROM expediteur ORDER BY expediteur"  ;
		$queryparams = null;
		$arr = $db->rawQuery($sqltext, $queryparams);
		return $arr;
	}

	/**
     * courrier_etat_option_list Model Action
     * @return array
     */
	function courrier_etat_option_list(){
		$db = $this->GetModel();
		// Un administrateur voit tous les etats : sans cela, un profil absent de
		// la table droit_select obtient une liste vide et ne peut pas enregistrer
		// un courrier, le champ Etat etant obligatoire.
		if (function_exists('utilisateur_est_administrateur') && utilisateur_est_administrateur()) {
			$sqltext = "SELECT idetat AS value, etat AS label FROM etat ORDER BY idetat";
			$queryparams = null;
		}
		else {
		$sqltext = "SELECT  ds.etat_courrier as value , e.etat as label  FROM etat AS e JOIN droit_select AS ds ON e.idetat=ds.etat_courrier WHERE  (ds.roles  = ? ) order by ds.etat_courrier" ;
		$queryparams = array(USER_ROLE);
		}
		$arr = $db->rawQuery($sqltext, $queryparams);
		return $arr;
	}

	/**
     * imputation_etat_traitement_option_list Model Action
     * @return array
     */
	function imputation_etat_traitement_option_list(){
		$db = $this->GetModel();
		$sqltext = "SELECT  DISTINCT idetatimputation AS value,etat_imputation AS label FROM etat_imputation ORDER BY idetatimputation ASC";
		$queryparams = null;
		$arr = $db->rawQuery($sqltext, $queryparams);
		return $arr;
	}

	/**
     * imputation_iduser_option_list Model Action
     * @return array
     */
	function imputation_iduser_option_list(){
		$db = $this->GetModel();

		$idcourrier = isset($_GET['idcourrier']) ? intval($_GET['idcourrier']) : 0;
		$moi        = intval(USER_ID);
		$mon_niveau = intval(get_active_user('niveau_imputation'));

		// Tous les echelons situes en dessous du sien, le plus proche en tete.
		//
		// La regle stricte « echelon immediatement inferieur » ne convient pas :
		// le chef du bureau du courrier transmet indifferemment a l'assistante du
		// Directeur General ou au Directeur General lui-meme, qui occupent deux
		// echelons distincts. On propose donc tout ce qui est en dessous, en
		// affichant l'echelon de chacun pour que le choix soit eclaire. Imputer
		// vers le haut ou entre pairs reste impossible.
		$base = "SELECT DISTINCT u.iduser AS value,
		                CONCAT(u.identification, ' - ', COALESCE(r.role_name, 'sans profil'),
		                       COALESCE(CONCAT(' (', n.niveau_imputation, ')'), '')) AS label,
		                COALESCE(u.niveau_imputation, 999) AS rang
		         FROM user u
		         LEFT JOIN roles r             ON r.role_id = u.user_role_id
		         LEFT JOIN niveau_imputation n ON n.idniveau = u.niveau_imputation
		         WHERE u.iduser <> ?
		           AND (u.is_deleted IS NULL OR u.is_deleted = '')
		           AND u.iduser NOT IN (
		               SELECT iduser FROM imputation
		               WHERE idcourrier = ? AND etat_traitement IN (".Circuit::liste(Circuit::etatsImputationAOuvrir())."))";

		$arr = $db->rawQuery($base . " AND u.niveau_imputation > ? ORDER BY rang ASC, label ASC",
			array($moi, $idcourrier, $mon_niveau));

		// Repli : si personne n'est positionne en dessous — echelons non encore
		// renseignes sur les fiches utilisateur — mieux vaut proposer les autres
		// utilisateurs que d'afficher une liste vide, qui bloquerait l'imputation
		// sans expliquer pourquoi.
		if (empty($arr)) {
			$arr = $db->rawQuery($base . " ORDER BY rang ASC, label ASC", array($moi, $idcourrier));
		}
		return $arr;
	}

	/**
     * imputation_idcourrier_option_list Model Action
     * @return array
     */
	function imputation_idcourrier_option_list(){
		$db = $this->GetModel();
		$sqltext = "SELECT DISTINCT idcourrier AS value , idcourrier AS label FROM imputation ORDER BY label ASC";
		$queryparams = null;
		$arr = $db->rawQuery($sqltext, $queryparams);
		return $arr;
	}

	/**
     * imputation_iduser_option_list_2 Model Action
     * @return array
     */
	function imputation_iduser_option_list_2(){
		$db = $this->GetModel();
		// Tous les utilisateurs actifs, avec leur profil. La requete d'origine
		// etait verrouillee sur le profil numero 5 : avec les profils crees
		// depuis, elle ne renvoyait plus personne.
		$sqltext = "SELECT DISTINCT u.iduser AS value,
		                   CONCAT(u.identification, ' - ', COALESCE(r.role_name, 'sans profil')) AS label
		            FROM user u LEFT JOIN roles r ON r.role_id = u.user_role_id
		            WHERE (u.is_deleted IS NULL OR u.is_deleted = '')
		            ORDER BY label ASC" ;
		$queryparams = null;
		$arr = $db->rawQuery($sqltext, $queryparams);
		return $arr;
	}

	/**
     * boite_idrangee_option_list Model Action
     * @return array
     */
	function boite_idrangee_option_list($lookup_idarmoire){
		$db = $this->GetModel();
		$sqltext = "SELECT  DISTINCT idrangee AS value,rangee AS label FROM rangee WHERE idarmoire= ? ORDER BY rangee ASC" ;
		$queryparams = array($lookup_idarmoire);
		$arr = $db->rawQuery($sqltext, $queryparams);
		return $arr;
	}

	/**
     * boite_idarmoire_option_list Model Action
     * @return array
     */
	function boite_idarmoire_option_list(){
		$db = $this->GetModel();
		$sqltext = "SELECT  DISTINCT id AS value,armoire AS label FROM armoire";
		$queryparams = null;
		$arr = $db->rawQuery($sqltext, $queryparams);
		return $arr;
	}

	/**
     * rangee_idarmoire_option_list Model Action
     * @return array
     */
	function rangee_idarmoire_option_list(){
		$db = $this->GetModel();
		$sqltext = "SELECT  DISTINCT id AS value,armoire AS label FROM armoire ORDER BY armoire ASC";
		$queryparams = null;
		$arr = $db->rawQuery($sqltext, $queryparams);
		return $arr;
	}

	/**
     * roles_courrier_roles_option_list Model Action
     * @return array
     */
	function roles_courrier_roles_option_list(){
		$db = $this->GetModel();
		$sqltext = "SELECT  DISTINCT role_id AS value,role_name AS label FROM roles ORDER BY role_id ASC";
		$queryparams = null;
		$arr = $db->rawQuery($sqltext, $queryparams);
		return $arr;
	}

	/**
     * roles_courrier_etat_option_list Model Action
     * @return array
     */
	function roles_courrier_etat_option_list(){
		$db = $this->GetModel();
		$sqltext = "SELECT  DISTINCT idetat AS value,etat AS label FROM etat ORDER BY idetat ASC";
		$queryparams = null;
		$arr = $db->rawQuery($sqltext, $queryparams);
		return $arr;
	}

	/**
     * droit_lister_roles_option_list Model Action
     * @return array
     */
	function droit_lister_roles_option_list(){
		$db = $this->GetModel();
		$sqltext = "SELECT  DISTINCT role_id AS value,role_name AS label FROM roles ORDER BY role_id";
		$queryparams = null;
		$arr = $db->rawQuery($sqltext, $queryparams);
		return $arr;
	}

	/**
     * droit_lister_etat_courrier_option_list Model Action
     * @return array
     */
	function droit_lister_etat_courrier_option_list(){
		$db = $this->GetModel();
		$sqltext = "SELECT  DISTINCT idetat AS value,etat AS label FROM etat ORDER BY idetat";
		$queryparams = null;
		$arr = $db->rawQuery($sqltext, $queryparams);
		return $arr;
	}

	/**
     * droit_select_roles_option_list Model Action
     * @return array
     */
	function droit_select_roles_option_list(){
		$db = $this->GetModel();
		$sqltext = "SELECT  DISTINCT role_id AS value,role_name AS label FROM roles ORDER BY role_id";
		$queryparams = null;
		$arr = $db->rawQuery($sqltext, $queryparams);
		return $arr;
	}

	/**
     * droit_select_etat_courrier_option_list Model Action
     * @return array
     */
	function droit_select_etat_courrier_option_list(){
		$db = $this->GetModel();
		$sqltext = "SELECT  DISTINCT idetat AS value,etat AS label FROM etat ORDER BY idetat";
		$queryparams = null;
		$arr = $db->rawQuery($sqltext, $queryparams);
		return $arr;
	}

	/**
     * division_direction_option_list Model Action
     * @return array
     */
	function division_direction_option_list(){
		$db = $this->GetModel();
		$sqltext = "SELECT  DISTINCT iddirection AS value,direction AS label FROM direction ORDER BY direction";
		$queryparams = null;
		$arr = $db->rawQuery($sqltext, $queryparams);
		return $arr;
	}

	/**
     * courrier_sortant_en_reponse_courrier_numero_option_list Model Action
     * @return array
     */
	function courrier_sortant_en_reponse_courrier_numero_option_list($lookup_destinataire){
		$db = $this->GetModel();
		$sqltext = "SELECT  DISTINCT idcourrier AS value,intitule AS label FROM courrier WHERE expediteur= ? ORDER BY intitule" ;
		$queryparams = array($lookup_destinataire);
		$arr = $db->rawQuery($sqltext, $queryparams);
		return $arr;
	}

	/**
     * courrier_sortant_destinataire_option_list Model Action
     * @return array
     */
	function courrier_sortant_destinataire_option_list(){
		$db = $this->GetModel();
		$sqltext = "SELECT  DISTINCT idexpediteur AS value,expediteur AS label FROM expediteur ORDER BY expediteur";
		$queryparams = null;
		$arr = $db->rawQuery($sqltext, $queryparams);
		return $arr;
	}

	/**
     * courrier_sortant_nature_option_list Model Action
     * @return array
     */
	function courrier_sortant_nature_option_list(){
		$db = $this->GetModel();
		$sqltext = "SELECT  DISTINCT idnature AS value,nature AS label FROM nature_courrier ORDER BY nature";
		$queryparams = null;
		$arr = $db->rawQuery($sqltext, $queryparams);
		return $arr;
	}

	/**
     * user_userniveau_imputation_option_list Model Action
     * @return array
     */
	function user_userniveau_imputation_option_list(){
		$db = $this->GetModel();
		$sqltext = "SELECT  DISTINCT idniveau AS value,niveau_imputation AS label FROM niveau_imputation ORDER BY idniveau";
		$queryparams = null;
		$arr = $db->rawQuery($sqltext, $queryparams);
		return $arr;
	}

	/**
     * user_userdirection_option_list Model Action
     * @return array
     */
	function user_userdirection_option_list(){
		$db = $this->GetModel();
		$sqltext = "SELECT  DISTINCT iddirection AS value,direction AS label FROM direction ORDER BY iddirection";
		$queryparams = null;
		$arr = $db->rawQuery($sqltext, $queryparams);
		return $arr;
	}

	/**
     * user_useruser_role_id_option_list Model Action
     * @return array
     */
	function user_useruser_role_id_option_list(){
		$db = $this->GetModel();
		$sqltext = "SELECT  DISTINCT role_id AS value,role_name AS label FROM roles ORDER BY role_name";
		$queryparams = null;
		$arr = $db->rawQuery($sqltext, $queryparams);
		return $arr;
	}

	/**
     * courrier_courrieretat_option_list Model Action
     * @return array
     */
	function courrier_courrieretat_option_list(){
		$db = $this->GetModel();
		// Un administrateur voit tous les etats : sans cela, un profil absent de
		// la table droit_select obtient une liste vide et ne peut pas enregistrer
		// un courrier, le champ Etat etant obligatoire.
		if (function_exists('utilisateur_est_administrateur') && utilisateur_est_administrateur()) {
			$sqltext = "SELECT idetat AS value, etat AS label FROM etat ORDER BY idetat";
			$queryparams = null;
		}
		else {
		$sqltext = "SELECT  ds.etat_courrier as value , e.etat as label  FROM etat AS e JOIN droit_select AS ds ON e.idetat=ds.etat_courrier WHERE  (ds.roles  = ? ) order by ds.etat_courrier" ;
		$queryparams = array(USER_ROLE);
		}
		$arr = $db->rawQuery($sqltext, $queryparams);
		return $arr;
	}

	/**
     * courrier_courrierexpediteur_option_list Model Action
     * @return array
     */
	function courrier_courrierexpediteur_option_list(){
		$db = $this->GetModel();
		$sqltext = "SELECT  DISTINCT idexpediteur AS value,expediteur AS label FROM expediteur ORDER BY expediteur";
		$queryparams = null;
		$arr = $db->rawQuery($sqltext, $queryparams);
		return $arr;
	}

	/**
     * imputation_imputationetat_traitement_option_list Model Action
     * @return array
     */
	function imputation_imputationetat_traitement_option_list(){
		$db = $this->GetModel();
		$sqltext = "SELECT  DISTINCT idetatimputation AS value,etat_imputation AS label FROM etat_imputation ORDER BY idetatimputation";
		$queryparams = null;
		$arr = $db->rawQuery($sqltext, $queryparams);
		return $arr;
	}

	/**
     * droit_lister_droit_listerroles_option_list Model Action
     * @return array
     */
	function droit_lister_droit_listerroles_option_list(){
		$db = $this->GetModel();
		$sqltext = "SELECT  r.role_id as value , r.role_name as label  FROM roles AS r";
		$queryparams = null;
		$arr = $db->rawQuery($sqltext, $queryparams);
		return $arr;
	}

	/**
     * droit_select_droit_selectroles_option_list Model Action
     * @return array
     */
	function droit_select_droit_selectroles_option_list(){
		$db = $this->GetModel();
		$sqltext = "SELECT  DISTINCT role_id AS value,role_name AS label FROM roles ORDER BY role_id";
		$queryparams = null;
		$arr = $db->rawQuery($sqltext, $queryparams);
		return $arr;
	}

	/**
     * division_divisiondirection_option_list Model Action
     * @return array
     */
	function division_divisiondirection_option_list(){
		$db = $this->GetModel();
		$sqltext = "SELECT  DISTINCT iddirection AS value,direction AS label FROM direction ORDER BY direction";
		$queryparams = null;
		$arr = $db->rawQuery($sqltext, $queryparams);
		return $arr;
	}

	/**
     * courrier_sortant_courrier_sortantdestinataire_option_list Model Action
     * @return array
     */
	function courrier_sortant_courrier_sortantdestinataire_option_list(){
		$db = $this->GetModel();
		$sqltext = "SELECT  DISTINCT idexpediteur AS value,expediteur AS label FROM expediteur ORDER BY idexpediteur";
		$queryparams = null;
		$arr = $db->rawQuery($sqltext, $queryparams);
		return $arr;
	}

	/**
     * getcount_imputationsenattente Model Action
     * @return Value
     */
	function getcount_imputationsenattente(){
		$db = $this->GetModel();
		// Le compteur doit compter la meme chose que l'ecran qu'il ouvre.
		if (utilisateur_voit_toutes_les_imputations()) {
			$sqltext = "SELECT COUNT(*) AS num FROM imputations_en_attente";
			$queryparams = null;
		} else {
			$sqltext = "SELECT COUNT(*) AS num FROM imputations_en_attente WHERE iduser = ?";
			$queryparams = array(USER_ID);
		}
		$val = $db->rawQueryValue($sqltext, $queryparams);
		
		if(is_array($val)){
			return $val[0];
		}
		return $val;
	}

	/**
     * getcount_imputationenretard Model Action
     * @return Value
     */
	function getcount_imputationenretard(){
		$db = $this->GetModel();
		// Le compteur doit compter la meme chose que l'ecran qu'il ouvre.
		if (utilisateur_voit_toutes_les_imputations()) {
			$sqltext = "SELECT COUNT(*) AS num FROM imputation_en_retard";
			$queryparams = null;
		} else {
			$sqltext = "SELECT COUNT(*) AS num FROM imputation_en_retard WHERE iduser = ?";
			$queryparams = array(USER_ID);
		}
		$val = $db->rawQueryValue($sqltext, $queryparams);
		
		if(is_array($val)){
			return $val[0];
		}
		return $val;
	}

	/**
	* barchart_h6etatdetraitementducourrierh6 Model Action
	* @return array
	*/
	function barchart_h6etatdetraitementducourrierh6(){
		
		$db = $this->GetModel();
		$chart_data = array(
			"labels"=> array(),
			"datasets"=> array(),
		);
		
		//set query result for dataset 1
		$sqltext = "SELECT  count(c.idcourrier) as Nombre, e.etat FROM courrier AS c JOIN etat AS e ON c.etat=e.idetat group by e.etat";
		$queryparams = null;
		$dataset1 = $db->rawQuery($sqltext, $queryparams);
		$dataset_data =  array_column($dataset1, 'Nombre');
		$dataset_labels =  array_column($dataset1, 'etat');
		$chart_data["labels"] = array_unique(array_merge($chart_data["labels"], $dataset_labels));
		$chart_data["datasets"][] = $dataset_data;

		return $chart_data;
	}

	/**
	* barchart_h6etatdetraitementimputationsh6 Model Action
	* @return array
	*/
	function barchart_h6etatdetraitementimputationsh6(){
		
		$db = $this->GetModel();
		$chart_data = array(
			"labels"=> array(),
			"datasets"=> array(),
		);
		
		//set query result for dataset 1
		$sqltext = "SELECT  COUNT(idimputation) AS count_of_idimputation, etat_imputation FROM imputation, etat_imputation where imputation.etat_traitement=etat_imputation.idetatimputation GROUP BY imputation.etat_traitement";
		$queryparams = null;
		$dataset1 = $db->rawQuery($sqltext, $queryparams);
		$dataset_data =  array_column($dataset1, 'count_of_idimputation');
		$dataset_labels =  array_column($dataset1, 'etat_imputation');
		$chart_data["labels"] = array_unique(array_merge($chart_data["labels"], $dataset_labels));
		$chart_data["datasets"][] = $dataset_data;

		return $chart_data;
	}

	/**
	* piechart_h6courrierpartypeh6 Model Action
	* @return array
	*/
	function piechart_h6courrierpartypeh6(){
		
		$db = $this->GetModel();
		$chart_data = array(
			"labels"=> array(),
			"datasets"=> array(),
		);
		
		//set query result for dataset 1
		$sqltext = "SELECT  count(c.idcourrier) as Nombre, nc.nature FROM courrier AS c JOIN nature_courrier AS nc ON c.nature=nc.idnature group by nc.nature";
		$queryparams = null;
		$dataset1 = $db->rawQuery($sqltext, $queryparams);
		$dataset_data =  array_column($dataset1, 'Nombre');
		$dataset_labels =  array_column($dataset1, 'nature');
		$chart_data["labels"] = array_unique(array_merge($chart_data["labels"], $dataset_labels));
		$chart_data["datasets"][] = $dataset_data;

		return $chart_data;
	}

	/**
	* piechart_h6courrierparsensh6 Model Action
	* @return array
	*/
	function piechart_h6courrierparsensh6(){
		
		$db = $this->GetModel();
		$chart_data = array(
			"labels"=> array(),
			"datasets"=> array(),
		);
		
		//set query result for dataset 1
		$sqltext = "SELECT count(c.idcourrier) as Nombre, nc.sens FROM courrier AS c JOIN sens AS nc ON c.sens=nc.idsens group by nc.sens";
		$queryparams = null;
		$dataset1 = $db->rawQuery($sqltext, $queryparams);
		$dataset_data =  array_column($dataset1, 'Nombre');
		$dataset_labels =  array_column($dataset1, 'sens');
		$chart_data["labels"] = array_unique(array_merge($chart_data["labels"], $dataset_labels));
		$chart_data["datasets"][] = $dataset_data;

		return $chart_data;
	}

	/**
     * getcount_gestiondesutilisateurs Model Action
     * @return Value
     */
	function getcount_gestiondesutilisateurs(){
		$db = $this->GetModel();
		$sqltext = "SELECT COUNT(*) AS num FROM user";
		$queryparams = null;
		$val = $db->rawQueryValue($sqltext, $queryparams);
		
		if(is_array($val)){
			return $val[0];
		}
		return $val;
	}

	/**
     * getcount_expediteur Model Action
     * @return Value
     */
	function getcount_expediteur(){
		$db = $this->GetModel();
		$sqltext = "SELECT COUNT(*) AS num FROM expediteur";
		$queryparams = null;
		$val = $db->rawQueryValue($sqltext, $queryparams);
		
		if(is_array($val)){
			return $val[0];
		}
		return $val;
	}


	/**
	* Statistiques du courrier pour le tableau de bord.
	* Les courriers supprimes (is_deleted) sont systematiquement exclus.
	* @return int
	*/
	function getcount_courriers(){
		$db = $this->GetModel();
		$val = $db->rawQueryValue("SELECT COUNT(*) AS num FROM courrier WHERE is_deleted IS NULL OR is_deleted = ''", null);
		return is_array($val) ? $val[0] : $val;
	}

	/**
	* Courriers recus depuis le debut du mois en cours.
	* @return int
	*/
	function getcount_courriers_du_mois(){
		$db = $this->GetModel();
		$sqltext = "SELECT COUNT(*) AS num FROM courrier
			WHERE (is_deleted IS NULL OR is_deleted = '')
			AND YEAR(date_reception) = YEAR(CURDATE()) AND MONTH(date_reception) = MONTH(CURDATE())";
		$val = $db->rawQueryValue($sqltext, null);
		return is_array($val) ? $val[0] : $val;
	}

	/**
	* Courriers encore en circulation : ni traites et retournes (5), ni classes (6).
	* @return int
	*/
	function getcount_courriers_en_cours(){
		$db = $this->GetModel();
		$sqltext = "SELECT COUNT(*) AS num FROM courrier
			WHERE (is_deleted IS NULL OR is_deleted = '') AND etat NOT IN (".Circuit::liste(Circuit::etatsCourrierClos()).")";
		$val = $db->rawQueryValue($sqltext, null);
		return is_array($val) ? $val[0] : $val;
	}

	/**
	* Evolution du nombre de courriers recus sur les douze derniers mois.
	* @return array
	*/
	function barchart_courrier_par_mois(){
		$db = $this->GetModel();
		$chart_data = array("labels" => array(), "datasets" => array());
		$sqltext = "SELECT DATE_FORMAT(date_reception, '%Y-%m') AS periode, COUNT(*) AS Nombre
			FROM courrier
			WHERE (is_deleted IS NULL OR is_deleted = '')
			AND date_reception >= DATE_SUB(CURDATE(), INTERVAL 11 MONTH)
			GROUP BY periode ORDER BY periode";
		$lignes = $db->rawQuery($sqltext, null);
		if(!empty($lignes)){
			$chart_data["labels"] = array_column($lignes, 'periode');
			$chart_data["datasets"][] = array_column($lignes, 'Nombre');
		}
		else{
			// Un graphique sans serie provoque une erreur cote navigateur.
			$chart_data["labels"] = array();
			$chart_data["datasets"][] = array();
		}
		return $chart_data;
	}

	/**
	* Repartition du courrier par direction destinataire, via les imputations.
	* @return array
	*/
	function barchart_courrier_par_direction(){
		$db = $this->GetModel();
		$chart_data = array("labels" => array(), "datasets" => array());
		$sqltext = "SELECT d.direction AS libelle, COUNT(DISTINCT i.idcourrier) AS Nombre
			FROM imputation i
			JOIN user u ON u.iduser = i.iduser
			JOIN direction d ON d.iddirection = u.direction
			GROUP BY d.direction ORDER BY Nombre DESC";
		$lignes = $db->rawQuery($sqltext, null);
		if(!empty($lignes)){
			$chart_data["labels"] = array_column($lignes, 'libelle');
			$chart_data["datasets"][] = array_column($lignes, 'Nombre');
		}
		else{
			$chart_data["labels"] = array();
			$chart_data["datasets"][] = array();
		}
		return $chart_data;
	}
}
