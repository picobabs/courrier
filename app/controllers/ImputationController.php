<?php 
/**
 * Imputation Page Controller
 * @category  Controller
 */
class ImputationController extends SecureController{
	function __construct(){
		parent::__construct();
		$this->tablename = "imputation";
	}
	/**
     * List page records
     * @param $fieldname (filter record by a field) 
     * @param $fieldvalue (filter field value)
     * @return BaseView
     */
	function index($fieldname = null , $fieldvalue = null){
		$request = $this->request;
		$db = $this->GetModel();
		$tablename = $this->tablename;
		$fields = array("imputation.idimputation", 
			"imputation.date_imputation", 
			"imputation.delai_traitement", 
			"imputation.idcourrier", 
			"imputation.ftraite", 
			"imputation.instruction", 
			"etat_imputation.etat_imputation AS etat_imputation_etat_imputation", 
			"user.identification AS user_identification", 
			"user.avatar AS user_avatar", 
			"imputation.date_limite_traite");
		$pagination = $this->get_pagination(MAX_RECORD_COUNT); // get current pagination e.g array(page_number, page_limit)
	#Statement to execute before list record
	// Cette valeur est concatenee dans une clause WHERE plus bas : elle DOIT
	// etre forcee en entier, sinon l'URL permet d'injecter du SQL arbitraire.
	$idco = isset($_GET['idcourrier']) ? (int) $_GET['idcourrier'] : 0;
	# End of before list statement
		//search table record
		if(!empty($request->search)){
			$text = trim($request->search); 
			$search_condition = "(
				imputation.idimputation LIKE ? OR 
				imputation.date_imputation LIKE ? OR 
				imputation.delai_traitement LIKE ? OR 
				imputation.etat_traitement LIKE ? OR 
				imputation.idcourrier LIKE ? OR 
				imputation.iduser LIKE ? OR 
				imputation.ftraite LIKE ? OR 
				imputation.instruction LIKE ? OR 
				etat_imputation.idetatimputation LIKE ? OR 
				etat_imputation.etat_imputation LIKE ? OR 
				user.iduser LIKE ? OR 
				user.identification LIKE ? OR 
				user.login LIKE ? OR 
				user.password LIKE ? OR 
				user.emailuser LIKE ? OR 
				user.avatar LIKE ? OR 
				user.date_deleted LIKE ? OR 
				user.is_deleted LIKE ? OR 
				user.user_role_id LIKE ? OR 
				imputation.date_limite_traite LIKE ? OR 
				imputation.niveau LIKE ? OR 
				imputation.origine LIKE ?
			)";
			$search_params = array(
				"%$text%","%$text%","%$text%","%$text%","%$text%","%$text%","%$text%","%$text%","%$text%","%$text%","%$text%","%$text%","%$text%","%$text%","%$text%","%$text%","%$text%","%$text%","%$text%","%$text%","%$text%","%$text%"
			);
			//setting search conditions
			$db->where($search_condition, $search_params);
			 //template to use when ajax search
			$this->view->search_template = "imputation/search.php";
		}
		$db->join("etat_imputation", "imputation.etat_traitement = etat_imputation.idetatimputation", "INNER");
		$db->join("user", "imputation.iduser = user.iduser", "INNER");
		if(!empty($request->orderby)){
			$orderby = $request->orderby;
			$ordertype = (!empty($request->ordertype) ? $request->ordertype : ORDER_TYPE);
			$db->orderBy($orderby, $ordertype);
		}
		else{
			$db->orderBy("imputation.idimputation", ORDER_TYPE);
		}
		// Le numero de profil du Directeur etait ecrit en dur ici, ce qui empechait
		// tout nouveau profil equivalent de voir la moindre imputation.
		// On compare desormais le LIBELLE du profil de l'utilisateur connecte
		// (deja charge par l'ACL) aux libelles listes dans config.php.
		// Comparaison des libelles de profil, insensible a la casse et aux accents
		// (mb_strtolower traite correctement les caracteres accentues, ce que
		// strtolower ne fait pas).
		$abaisser = function($v){
			$v = trim((string) $v);
			$v = function_exists('mb_strtolower') ? mb_strtolower($v, 'UTF-8') : strtolower($v);
			// Comparaison insensible aux accents, sans quoi un profil nomme
			// avec accents dans l'interface ne serait jamais reconnu ici.
			return strtr($v, array('à'=>'a','â'=>'a','ä'=>'a','é'=>'e','è'=>'e','ê'=>'e','ë'=>'e','î'=>'i','ï'=>'i','ô'=>'o','ö'=>'o','ù'=>'u','û'=>'u','ü'=>'u','ç'=>'c'));
		};
		$profil_courant = $abaisser(ACL::$user_role);
		$profils_niveau = array_map($abaisser, explode(',', ROLES_NIVEAU_HIERARCHIQUE));
		$profils_transverses = array_map($abaisser, explode(',', ROLES_VUE_TRANSVERSALE));
		$est_profil_niveau = in_array($profil_courant, $profils_niveau) ? 1 : 0;
		// Vue d'ensemble : voit toutes les imputations du courrier, tous echelons
		// confondus. Remplace le profil numero 6, qui n'existait dans aucune table.
		$est_vue_transversale = in_array($profil_courant, $profils_transverses) ? 1 : 0;
		// Chacun voit, sur un courrier donne, les imputations qu'il a emises et
		// celles qui lui sont adressees. Les profils de vue transversale voient
		// l'integralite de la chaine.
		//
		// L'ancien filtre comparait imputation.niveau a « mon echelon plus un ».
		// Ce calcul devient faux des qu'un echelon est saute, et il dependait
		// d'une colonne que rien ne renseignait. La colonne « origine », elle,
		// porte deja l'auteur de l'imputation : elle n'etait pas exploitee.
		$db->where("(
 imputation.idcourrier=".$idco." AND (
   ".$est_vue_transversale."=1
   OR imputation.origine='".intval(USER_ID)."'
   OR imputation.iduser='".intval(USER_ID)."'
 )
)");
		if($fieldname){
			$db->where($fieldname , $fieldvalue); //filter by a single field name
		}
		$tc = $db->withTotalCount();
		$records = $db->get($tablename, $pagination, $fields);
		$records_count = count($records);
		$total_records = intval($tc->totalCount);
		$page_limit = $pagination[1];
		$total_pages = ceil($total_records / $page_limit);
		$data = new stdClass;
		$data->records = $records;
		$data->record_count = $records_count;
		$data->total_records = $total_records;
		$data->total_page = $total_pages;
		if($db->getLastError()){
			$this->set_page_error();
		}
		$page_title = $this->view->page_title = get_lang('imputation');
		$this->render_view("imputation/list.php", $data); //render the full page
	}
	/**
     * View record detail 
	 * @param $rec_id (select record by table primary key) 
     * @param $value value (select record by value of field name(rec_id))
     * @return BaseView
     */
	function view($rec_id = null, $value = null){
		$request = $this->request;
		$db = $this->GetModel();
		$rec_id = $this->rec_id = urldecode($rec_id);
		$tablename = $this->tablename;
		$fields = array("imputation.idimputation", 
			"imputation.date_imputation", 
			"imputation.delai_traitement", 
			"imputation.etat_traitement", 
			"imputation.ftraite", 
			"user.user_role_id AS user_user_role_id", 
			"courrier.idcourrier AS courrier_idcourrier", 
			"courrier.date_courrier AS courrier_date_courrier", 
			"courrier.date_reception AS courrier_date_reception", 
			"courrier.expediteur AS courrier_expediteur", 
			"courrier.intitule AS courrier_intitule", 
			"courrier.objet AS courrier_objet", 
			"courrier.fichier AS courrier_fichier", 
			"courrier.armoire AS courrier_armoire", 
			"courrier.rangee AS courrier_rangee", 
			"courrier.boite AS courrier_boite", 
			"courrier.sens AS courrier_sens", 
			"courrier.nature AS courrier_nature", 
			"courrier.date_deleted AS courrier_date_deleted", 
			"courrier.is_deleted AS courrier_is_deleted", 
			"courrier.etat AS courrier_etat", 
			"expediteur.idexpediteur AS expediteur_idexpediteur", 
			"expediteur.expediteur AS expediteur_expediteur", 
			"expediteur.cellulaire AS expediteur_cellulaire", 
			"imputation.instruction", 
			"etat_imputation.idetatimputation AS etat_imputation_idetatimputation", 
			"etat_imputation.etat_imputation AS etat_imputation_etat_imputation", 
			"user.iduser AS user_iduser", 
			"user.identification AS user_identification", 
			"user.login AS user_login", 
			"user.password AS user_password", 
			"user.emailuser AS user_emailuser", 
			"user.avatar AS user_avatar", 
			"user.date_deleted AS user_date_deleted", 
			"user.is_deleted AS user_is_deleted", 
			"user.user_role_id AS user_user_role_id", 
			"imputation.date_limite_traite", 
			"imputation.niveau", 
			"imputation.origine");
		if($value){
			$db->where($rec_id, urldecode($value)); //select record based on field name
		}
		else{
			$db->where("imputation.idimputation", $rec_id);; //select record based on primary key
		}
		$db->join("etat_imputation", "imputation.etat_traitement = etat_imputation.idetatimputation", "INNER ");
		$db->join("user", "imputation.iduser = user.iduser", "INNER ");  
		$record = $db->getOne($tablename, $fields );
		if($record){
			$page_title = $this->view->page_title = get_lang('vue_imputation');
		$this->view->report_filename = date('Y-m-d') . '-' . $page_title;
		$this->view->report_title = $page_title;
		$this->view->report_layout = "report_layout.php";
		$this->view->report_paper_size = "A4";
		$this->view->report_orientation = "portrait";
		}
		else{
			if($db->getLastError()){
				$this->set_page_error();
			}
			else{
				$this->set_page_error(get_lang('enregistrement_non_trouv_'));
			}
		}
		return $this->render_view("imputation/view.php", $record);
	}
	/**
     * Insert new record to the database table
	 * @param $formdata array() from $_POST
     * @return BaseView
     */
	function add($formdata = null){
		if($formdata){
			$db = $this->GetModel();
			$tablename = $this->tablename;
			$request = $this->request;
			//fillable fields
			$fields = $this->fields = array("date_imputation","date_limite_traite","delai_traitement","idcourrier","iduser","instruction","etat_traitement","niveau","origine");
			$postdata = $this->format_request_data($formdata);
			$this->rules_array = array(
				// Date butoir de traitement : colonne date obligatoire, sans valeur
				// par defaut possible. C'est elle qui alimente l'ecran des
				// imputations en retard : elle doit etre saisie.
				'date_limite_traite' => 'required',
				'date_imputation' => 'required',
				'delai_traitement' => 'required',
				'iduser' => 'required',
				'etat_traitement' => 'required',
			);
			$this->sanitize_array = array(
				'date_imputation' => 'sanitize_string',
				'date_limite_traite' => 'sanitize_string',
				'delai_traitement' => 'sanitize_string',
				'idcourrier' => 'sanitize_string',
				'iduser' => 'sanitize_string',
				'instruction' => 'sanitize_string',
				'etat_traitement' => 'sanitize_string',
				'niveau' => 'sanitize_string',
			);
			$this->filter_vals = true; //set whether to remove empty fields
			$modeldata = $this->modeldata = $this->validate_form($postdata);
			$modeldata['origine'] = USER_ID;
			if($this->validated()){
				// Ces colonnes sont obligatoires en base et n'ont aucune valeur par
				// defaut, alors qu'elles sont absentes du formulaire ou facultatives.
				// On fournit donc une valeur neutre quand le champ est vide, faute de
				// quoi MySQL refuse l'insertion ("doesn't have a default value").
				foreach (array('instruction' => '') as $col => $defaut) {
					if (!isset($modeldata[$col]) || $modeldata[$col] === '') { $modeldata[$col] = $defaut; }
				}
				// Niveau : l'echelon reel du destinataire choisi. Le formulaire porte
				// un champ cache que rien n'alimente ; la colonne partait donc a 0.
				// On ne peut pas se contenter de « mon echelon plus un » : un chef
				// du bureau du courrier qui transmet directement au Directeur
				// General saute l'echelon de l'assistante, et le niveau serait faux.
				if (empty($modeldata['niveau'])) {
					$niveau_destinataire = $db->where('iduser', intval($modeldata['iduser']))
					                          ->getValue('user', 'niveau_imputation');
					$modeldata['niveau'] = !empty($niveau_destinataire)
						? intval($niveau_destinataire)
						: intval(get_active_user('niveau_imputation')) + 1;
				}
				$rec_id = $this->rec_id = $db->insert($tablename, $modeldata);
				if($rec_id){
					// L'etat du courrier est recalcule a partir de ses imputations,
					// et non impose. La methode est rejouable : creation,
					// modification ou suppression aboutissent au meme resultat.
					Circuit::recalculerEtatCourrier($db, $modeldata['idcourrier']);

					$db->where("idcourrier", $modeldata['idcourrier']);
					$intitule = $db->getValue("courrier", "intitule");
					Circuit::journaliser(
						$db,
						$modeldata['idcourrier'],
						"Imputation du courrier : " . $intitule,
						$modeldata['iduser']
					);
					$this->set_flash_msg(get_lang('enregistrement_ajout_avec_succ_s'), "success");
					return	$this->redirect("imputation/list?idcourrier=" . ($modeldata['idcourrier']));
				}
				else{
					$this->set_page_error();
				}
			}
		}
		$page_title = $this->view->page_title = get_lang('ajouter_un_nouveau');
		$this->render_view("imputation/add.php");
	}
	/**
     * Update table record with formdata
	 * @param $rec_id (select record by table primary key)
	 * @param $formdata array() from $_POST
     * @return array
     */
	function edit($rec_id = null, $formdata = null){
		$request = $this->request;
		$db = $this->GetModel();
		$this->rec_id = $rec_id;
		$tablename = $this->tablename;
		 //editable fields
		$fields = $this->fields = array("idimputation","date_imputation","date_limite_traite","delai_traitement","idcourrier","iduser","ftraite","instruction","etat_traitement","niveau");
		if($formdata){
			$postdata = $this->format_request_data($formdata);
			$this->rules_array = array(
				'date_imputation' => 'required',
				'delai_traitement' => 'required',
				'iduser' => 'required',
				'etat_traitement' => 'required',
			);
			$this->sanitize_array = array(
				'date_imputation' => 'sanitize_string',
				'date_limite_traite' => 'sanitize_string',
				'delai_traitement' => 'sanitize_string',
				'idcourrier' => 'sanitize_string',
				'iduser' => 'sanitize_string',
				'ftraite' => 'sanitize_string',
				'instruction' => 'sanitize_string',
				'etat_traitement' => 'sanitize_string',
				'niveau' => 'sanitize_string',
			);
			$this->filter_vals = true; //set whether to remove empty fields
			$modeldata = $this->modeldata = $this->validate_form($postdata);
			if($this->validated()){
				$db->where("imputation.idimputation", $rec_id);;
				$bool = $db->update($tablename, $modeldata);
				$numRows = $db->getRowCount(); //number of affected rows. 0 = no record field updated
				if($bool && $numRows){
		# Statement to execute after adding record
			$db->where("idcourrier", $modeldata['idcourrier']);
$num = $db->getValue("courrier", "numero_courrier");
$db->where("idcourrier", $modeldata['idcourrier']);
$inti = $db->getValue("courrier", "intitule");
/////////////////////
$today  = datetime_now();
$action = "Modification Imputation courrier numéro  : ".$num." intitulé :".$inti;  
$table_data = array(
    "userid" => get_active_user('iduser'),
    "numero_courrier" => $num,
    "destinataire" =>  $modeldata['iduser'],
    "idcourrier" => $modeldata['idcourrier'],
    "action" => $action
);
$db->insert("evenement", $table_data);
		# End of after update statement
					$this->set_flash_msg(get_lang('enregistrement_mis_jour_avec_succ_s'), "success");
					return $this->redirect("imputation/list?idcourrier=" . ($modeldata['idcourrier']));
				}
				else{
					if($db->getLastError()){
						$this->set_page_error();
					}
					elseif(!$numRows){
						//not an error, but no record was updated
						$page_error = get_lang('aucun_enregistrement_mis_jour');
						$this->set_page_error($page_error);
						$this->set_flash_msg($page_error, "warning");
						return	$this->redirect("imputation/list?idcourrier=" . ($modeldata['idcourrier']));
					}
				}
			}
		}
		$db->where("imputation.idimputation", $rec_id);;
		$data = $db->getOne($tablename, $fields);
		$page_title = $this->view->page_title = get_lang('modifier');
		if(!$data){
			$this->set_page_error();
		}
		return $this->render_view("imputation/edit.php", $data);
	}
	/**
     * Update single field
	 * @param $rec_id (select record by table primary key)
	 * @param $formdata array() from $_POST
     * @return array
     */
	function editfield($rec_id = null, $formdata = null){
		$db = $this->GetModel();
		$this->rec_id = $rec_id;
		$tablename = $this->tablename;
		//editable fields
		$fields = $this->fields = array("idimputation","date_imputation","date_limite_traite","delai_traitement","idcourrier","iduser","ftraite","instruction","etat_traitement","niveau");
		$page_error = null;
		if($formdata){
			$postdata = array();
			$fieldname = $formdata['name'];
			$fieldvalue = $formdata['value'];
			$postdata[$fieldname] = $fieldvalue;
			$postdata = $this->format_request_data($postdata);
			$this->rules_array = array(
				'date_imputation' => 'required',
				'delai_traitement' => 'required',
				'iduser' => 'required',
				'etat_traitement' => 'required',
			);
			$this->sanitize_array = array(
				'date_imputation' => 'sanitize_string',
				'date_limite_traite' => 'sanitize_string',
				'delai_traitement' => 'sanitize_string',
				'idcourrier' => 'sanitize_string',
				'iduser' => 'sanitize_string',
				'ftraite' => 'sanitize_string',
				'instruction' => 'sanitize_string',
				'etat_traitement' => 'sanitize_string',
				'niveau' => 'sanitize_string',
			);
			$this->filter_rules = true; //filter validation rules by excluding fields not in the formdata
			$modeldata = $this->modeldata = $this->validate_form($postdata);
			if($this->validated()){
				$db->where("imputation.idimputation", $rec_id);;
				$bool = $db->update($tablename, $modeldata);
				$numRows = $db->getRowCount();
				if($bool && $numRows){
					return render_json(
						array(
							'num_rows' =>$numRows,
							'rec_id' =>$rec_id,
						)
					);
				}
				else{
					if($db->getLastError()){
						$page_error = $db->getLastError();
					}
					elseif(!$numRows){
						$page_error = get_lang('aucun_enregistrement_mis_jour');
					}
					render_error($page_error);
				}
			}
			else{
				render_error($this->view->page_error);
			}
		}
		return null;
	}
	/**
     * Delete record from the database
	 * Support multi delete by separating record id by comma.
     * @return BaseView
     */
	function delete($rec_id = null){
		Csrf::cross_check();
		$request = $this->request;
		$db = $this->GetModel();
		$tablename = $this->tablename;
		$this->rec_id = $rec_id;
		//form multiple delete, split record id separated by comma into array
		$arr_rec_id = array_map('trim', explode(",", $rec_id));

		// Les courriers concernes sont releves avant la suppression : ensuite, le
		// lien est perdu. Sans ce rattrapage, supprimer la derniere imputation
		// d'un courrier le laissait fige a « impute non retourne », sans aucun
		// moyen de revenir en arriere depuis l'interface.
		$courriers_touches = array();
		foreach ($arr_rec_id as $une_imputation) {
			$db->where("idimputation", intval($une_imputation));
			$idc = $db->getValue("imputation", "idcourrier");
			if (!empty($idc)) { $courriers_touches[intval($idc)] = intval($idc); }
		}

		$db->where("imputation.idimputation", $arr_rec_id, "in");
		$bool = $db->delete($tablename);

		foreach ($courriers_touches as $idc) {
			Circuit::recalculerEtatCourrier($db, $idc);
		}
		if($bool){
			$this->set_flash_msg(get_lang('enregistrement_supprim_avec_succ_s'), "success");
		}
		elseif($db->getLastError()){
			$page_error = $db->getLastError();
			$this->set_flash_msg($page_error, "danger");
		}
		return	$this->redirect("imputation");
	}
	/**
     * List page records
     * @param $fieldname (filter record by a field) 
     * @param $fieldvalue (filter field value)
     * @return BaseView
     */
	function traitement_imputation($fieldname = null , $fieldvalue = null){
		$request = $this->request;
		$db = $this->GetModel();
		$tablename = $this->tablename;
		$fields = array("imputation.idimputation", 
			"imputation.date_imputation", 
			"imputation.delai_traitement", 
			"imputation.ftraite", 
			"etat_imputation.etat_imputation AS etat_imputation_etat_imputation", 
			"user.identification AS user_identification", 
			"user.avatar AS user_avatar", 
			"courrier.idcourrier AS idcourrier", 
			"courrier.date_reception AS courrier_date_reception", 
			"courrier.intitule AS courrier_intitule", 
			"courrier.objet AS courrier_objet", 
			"courrier.fichier AS courrier_fichier", 
			"expediteur.expediteur AS expediteur_expediteur", 
			"imputation.instruction", 
			"imputation.date_limite_traite", 
			"imputation.niveau", 
			"imputation.origine");
		$pagination = $this->get_pagination(MAX_RECORD_COUNT); // get current pagination e.g array(page_number, page_limit)
		//search table record
		if(!empty($request->search)){
			$text = trim($request->search); 
			$search_condition = "(
				imputation.idimputation LIKE ? OR 
				imputation.date_imputation LIKE ? OR 
				imputation.delai_traitement LIKE ? OR 
				imputation.etat_traitement LIKE ? OR 
				imputation.idcourrier LIKE ? OR 
				imputation.iduser LIKE ? OR 
				imputation.ftraite LIKE ? OR 
				etat_imputation.idetatimputation LIKE ? OR 
				etat_imputation.etat_imputation LIKE ? OR 
				user.iduser LIKE ? OR 
				user.identification LIKE ? OR 
				user.login LIKE ? OR 
				user.password LIKE ? OR 
				user.emailuser LIKE ? OR 
				user.avatar LIKE ? OR 
				user.date_deleted LIKE ? OR 
				user.is_deleted LIKE ? OR 
				user.user_role_id LIKE ? OR 
				courrier.idcourrier LIKE ? OR 
				courrier.date_courrier LIKE ? OR 
				courrier.date_reception LIKE ? OR 
				courrier.expediteur LIKE ? OR 
				courrier.intitule LIKE ? OR 
				courrier.objet LIKE ? OR 
				courrier.fichier LIKE ? OR 
				courrier.armoire LIKE ? OR 
				courrier.rangee LIKE ? OR 
				courrier.boite LIKE ? OR 
				courrier.sens LIKE ? OR 
				courrier.nature LIKE ? OR 
				courrier.date_deleted LIKE ? OR 
				courrier.is_deleted LIKE ? OR 
				courrier.etat LIKE ? OR 
				expediteur.idexpediteur LIKE ? OR 
				expediteur.expediteur LIKE ? OR 
				expediteur.cellulaire LIKE ? OR 
				imputation.instruction LIKE ? OR 
				imputation.date_limite_traite LIKE ? OR 
				imputation.niveau LIKE ? OR 
				imputation.origine LIKE ?
			)";
			$search_params = array(
				"%$text%","%$text%","%$text%","%$text%","%$text%","%$text%","%$text%","%$text%","%$text%","%$text%","%$text%","%$text%","%$text%","%$text%","%$text%","%$text%","%$text%","%$text%","%$text%","%$text%","%$text%","%$text%","%$text%","%$text%","%$text%","%$text%","%$text%","%$text%","%$text%","%$text%","%$text%","%$text%","%$text%","%$text%","%$text%","%$text%","%$text%","%$text%","%$text%","%$text%"
			);
			//setting search conditions
			$db->where($search_condition, $search_params);
			 //template to use when ajax search
			$this->view->search_template = "imputation/search.php";
		}
		$db->join("etat_imputation", "imputation.etat_traitement = etat_imputation.idetatimputation", "INNER");
		$db->join("user", "imputation.iduser = user.iduser", "INNER");
		$db->join("courrier", "imputation.idcourrier = courrier.idcourrier", "INNER");
		$db->join("expediteur", "courrier.expediteur = expediteur.idexpediteur", "INNER");
		if(!empty($request->orderby)){
			$orderby = $request->orderby;
			$ordertype = (!empty($request->ordertype) ? $request->ordertype : ORDER_TYPE);
			$db->orderBy($orderby, $ordertype);
		}
		else{
			$db->orderBy("imputation.idimputation", ORDER_TYPE);
		}
		// Ce qui m'est adresse et qui attend encore quelque chose de moi.
		$db->where(" (imputation.iduser='".intval(USER_ID)."'
 AND imputation.etat_traitement IN (".Circuit::liste(Circuit::etatsImputationAOuvrir()).")
 AND courrier.etat = ".Circuit::COURRIER_IMPUTE.") ");
		if($fieldname){
			$db->where($fieldname , $fieldvalue); //filter by a single field name
		}
		if(!empty($request->imputation_etat_traitement)){
			$val = $request->imputation_etat_traitement;
			$db->where("imputation.etat_traitement", $val , "=");
		}
		$tc = $db->withTotalCount();
		$records = $db->get($tablename, $pagination, $fields);
		$records_count = count($records);
		$total_records = intval($tc->totalCount);
		$page_limit = $pagination[1];
		$total_pages = ceil($total_records / $page_limit);
		$data = new stdClass;
		$data->records = $records;
		$data->record_count = $records_count;
		$data->total_records = $total_records;
		$data->total_page = $total_pages;
		if($db->getLastError()){
			$this->set_page_error();
		}
		$page_title = $this->view->page_title = get_lang('imputation');
		$this->render_view("imputation/traitement_imputation.php", $data); //render the full page
	}
	/**
     * Update table record with formdata
	 * @param $rec_id (select record by table primary key)
	 * @param $formdata array() from $_POST
     * @return array
     */
	function edit_traitement($rec_id = null, $formdata = null){
		$request = $this->request;
		$db = $this->GetModel();
		$this->rec_id = $rec_id;
		$tablename = $this->tablename;
		 //editable fields
		$fields = $this->fields = array("idimputation","etat_traitement","idcourrier","iduser","ftraite","instruction");
		if($formdata){
			$postdata = $this->format_request_data($formdata);
			$this->rules_array = array(
				'etat_traitement' => 'required',
				'idcourrier' => 'required',
			);
			$this->sanitize_array = array(
				'etat_traitement' => 'sanitize_string',
				'idcourrier' => 'sanitize_string',
				'iduser' => 'sanitize_string',
				'ftraite' => 'sanitize_string',
				'instruction' => 'sanitize_string',
			);
			$modeldata = $this->modeldata = $this->validate_form($postdata);
			if($this->validated()){
				$db->where("imputation.idimputation", $rec_id);;
				$bool = $db->update($tablename, $modeldata);
				$numRows = $db->getRowCount(); //number of affected rows. 0 = no record field updated
				if($bool && $numRows){
		# Statement to execute after adding record
			$db->where("idcourrier", $modeldata['idcourrier']);
$num = $db->getValue("courrier", "numero_courrier");
$db->where("idcourrier", $modeldata['idcourrier']);
$inti = $db->getValue("courrier", "intitule");
/////////////////////
$today  = datetime_now();
$action = "Traitement imputation courrier numéro  : ".$num." intitulé :".$inti;  
$table_data = array(
    "userid" => get_active_user('iduser'),
    "numero_courrier" => $num,
    "idcourrier" => $modeldata['idcourrier'],
    "action" => $action
);
$db->insert("evenement", $table_data);
// CLOTURE DU COURRIER
// Regle d'origine : le courrier ne passait a l'etat "traite et retourne" que si
// l'utilisateur etait de niveau 1. Cette condition ne tient qu'avec deux
// echelons. Des qu'il en existe trois (DG, puis SG, puis directions et
// cellules), une direction de niveau 3 qui retourne son traitement au SG, de
// niveau 2, ne remplissait jamais la condition : le courrier restait
// indefiniment "impute non retourne" et l'arriere de suivi devenait fictif.
// On raisonne desormais sur l'etat reel du dossier, independamment du nombre de
// niveaux : le courrier est clos lorsqu'il ne reste plus aucune imputation en
// cours. Les imputations "pour information" (etat 6) ne sont pas bloquantes.
if ($modeldata['etat_traitement'] == Circuit::IMPUTATION_TRAITEE)
{
    // Quand un agent acheve son traitement, les imputations qu'il avait
    // lui-meme emises sur ce courrier n'ont plus d'objet : il vient de rendre
    // sa reponse. On les clot d'abord, puis on laisse le circuit deduire
    // l'etat du courrier.
    $db->where("origine", USER_ID);
    $db->where("idcourrier", $modeldata['idcourrier']);
    $db->where("etat_traitement", Circuit::IMPUTATION_TRAITEE, "!=");
    $db->update("imputation", array("etat_traitement" => Circuit::IMPUTATION_TRAITEE));

    Circuit::recalculerEtatCourrier($db, $modeldata['idcourrier']);
}
		# End of after update statement
					$this->set_flash_msg(get_lang('enregistrement_mis_jour_avec_succ_s'), "success");
					return $this->redirect("imputation/traitement_imputation");
				}
				else{
					if($db->getLastError()){
						$this->set_page_error();
					}
					elseif(!$numRows){
						//not an error, but no record was updated
						$page_error = get_lang('aucun_enregistrement_mis_jour');
						$this->set_page_error($page_error);
						$this->set_flash_msg($page_error, "warning");
						return	$this->redirect("imputation/traitement_imputation");
					}
				}
			}
		}
		$db->where("imputation.idimputation", $rec_id);;
		$data = $db->getOne($tablename, $fields);
		$page_title = $this->view->page_title = get_lang('modifier');
		if(!$data){
			$this->set_page_error();
		}
		return $this->render_view("imputation/edit_traitement.php", $data);
	}
	/**
     * List page records
     * @param $fieldname (filter record by a field) 
     * @param $fieldvalue (filter field value)
     * @return BaseView
     */
	function imputationstraitees($fieldname = null , $fieldvalue = null){
		$request = $this->request;
		$db = $this->GetModel();
		$tablename = $this->tablename;
		$fields = array("imputation.idimputation", 
			"imputation.date_imputation", 
			"imputation.delai_traitement", 
			"imputation.ftraite", 
			"etat_imputation.etat_imputation AS etat_imputation_etat_imputation", 
			"user.identification AS user_identification", 
			"user.avatar AS user_avatar", 
			"courrier.date_reception AS courrier_date_reception", 
			"courrier.intitule AS courrier_intitule", 
			"courrier.objet AS courrier_objet", 
			"courrier.fichier AS courrier_fichier", 
			"expediteur.expediteur AS expediteur_expediteur", 
			"imputation.instruction", 
			"imputation.date_limite_traite", 
			"imputation.niveau", 
			"imputation.origine");
		$pagination = $this->get_pagination(MAX_RECORD_COUNT); // get current pagination e.g array(page_number, page_limit)
		//search table record
		if(!empty($request->search)){
			$text = trim($request->search); 
			$search_condition = "(
				imputation.idimputation LIKE ? OR 
				imputation.date_imputation LIKE ? OR 
				imputation.delai_traitement LIKE ? OR 
				imputation.etat_traitement LIKE ? OR 
				imputation.idcourrier LIKE ? OR 
				imputation.iduser LIKE ? OR 
				imputation.ftraite LIKE ? OR 
				etat_imputation.idetatimputation LIKE ? OR 
				etat_imputation.etat_imputation LIKE ? OR 
				user.iduser LIKE ? OR 
				user.identification LIKE ? OR 
				user.login LIKE ? OR 
				user.password LIKE ? OR 
				user.emailuser LIKE ? OR 
				user.avatar LIKE ? OR 
				user.date_deleted LIKE ? OR 
				user.is_deleted LIKE ? OR 
				user.user_role_id LIKE ? OR 
				courrier.idcourrier LIKE ? OR 
				courrier.date_courrier LIKE ? OR 
				courrier.date_reception LIKE ? OR 
				courrier.expediteur LIKE ? OR 
				courrier.intitule LIKE ? OR 
				courrier.objet LIKE ? OR 
				courrier.fichier LIKE ? OR 
				courrier.armoire LIKE ? OR 
				courrier.rangee LIKE ? OR 
				courrier.boite LIKE ? OR 
				courrier.sens LIKE ? OR 
				courrier.nature LIKE ? OR 
				courrier.date_deleted LIKE ? OR 
				courrier.is_deleted LIKE ? OR 
				courrier.etat LIKE ? OR 
				expediteur.idexpediteur LIKE ? OR 
				expediteur.expediteur LIKE ? OR 
				expediteur.cellulaire LIKE ? OR 
				imputation.instruction LIKE ? OR 
				imputation.date_limite_traite LIKE ? OR 
				imputation.niveau LIKE ? OR 
				imputation.origine LIKE ?
			)";
			$search_params = array(
				"%$text%","%$text%","%$text%","%$text%","%$text%","%$text%","%$text%","%$text%","%$text%","%$text%","%$text%","%$text%","%$text%","%$text%","%$text%","%$text%","%$text%","%$text%","%$text%","%$text%","%$text%","%$text%","%$text%","%$text%","%$text%","%$text%","%$text%","%$text%","%$text%","%$text%","%$text%","%$text%","%$text%","%$text%","%$text%","%$text%","%$text%","%$text%","%$text%","%$text%"
			);
			//setting search conditions
			$db->where($search_condition, $search_params);
			 //template to use when ajax search
			$this->view->search_template = "imputation/search.php";
		}
		$db->join("etat_imputation", "imputation.etat_traitement = etat_imputation.idetatimputation", "INNER");
		$db->join("user", "imputation.iduser = user.iduser", "INNER");
		$db->join("courrier", "imputation.idcourrier = courrier.idcourrier", "INNER");
		$db->join("expediteur", "courrier.expediteur = expediteur.idexpediteur", "INNER");
		if(!empty($request->orderby)){
			$orderby = $request->orderby;
			$ordertype = (!empty($request->ordertype) ? $request->ordertype : ORDER_TYPE);
			$db->orderBy($orderby, $ordertype);
		}
		else{
			$db->orderBy("imputation.idimputation", ORDER_TYPE);
		}
		// Historique des imputations achevees.
		//
		// La condition portait sur « etat_traitement >= 3 », ce qui rangeait le
		// « pour information » (6) parmi les traitees alors que l'ecran de
		// traitement le compte comme non traite. Une meme imputation apparaissait
		// donc dans les deux listes a la fois.
		$db->where("imputation.etat_traitement", Circuit::IMPUTATION_TRAITEE);
		// Cet ecran n'etait cloisonne par personne : chacun y voyait l'historique
		// de toute l'agence.
		if (!utilisateur_voit_toutes_les_imputations()) {
			$db->where("(imputation.iduser='".intval(USER_ID)."' OR imputation.origine='".intval(USER_ID)."')");
		}
		if($fieldname){
			$db->where($fieldname , $fieldvalue); //filter by a single field name
		}
		$tc = $db->withTotalCount();
		$records = $db->get($tablename, $pagination, $fields);
		$records_count = count($records);
		$total_records = intval($tc->totalCount);
		$page_limit = $pagination[1];
		$total_pages = ceil($total_records / $page_limit);
		$data = new stdClass;
		$data->records = $records;
		$data->record_count = $records_count;
		$data->total_records = $total_records;
		$data->total_page = $total_pages;
		if($db->getLastError()){
			$this->set_page_error();
		}
		$page_title = $this->view->page_title = get_lang('imputation');
		$this->view->report_filename = date('Y-m-d') . '-' . $page_title;
		$this->view->report_title = $page_title;
		$this->view->report_layout = "report_layout.php";
		$this->view->report_paper_size = "A4";
		$this->view->report_orientation = "portrait";
		$this->render_view("imputation/imputationstraitees.php", $data); //render the full page
	}
}
