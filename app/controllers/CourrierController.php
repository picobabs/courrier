<?php 
/**
 * Courrier Page Controller
 * @category  Controller
 */
class CourrierController extends SecureController{
	function __construct(){
		parent::__construct();
		$this->tablename = "courrier";
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
		$fields = array("courrier.idcourrier", 
			"courrier.numero_courrier", 
			"courrier.date_courrier", 
			"courrier.date_reception", 
			"courrier.date_saisie", 
			"courrier.intitule", 
			"courrier.objet", 
			"courrier.fichier", 
			"courrier.etat", 
			"etat.etat AS etat_etat", 
			"'IMPUTATIONS' AS Imputation");
		$pagination = $this->get_pagination(1000); // get current pagination e.g array(page_number, page_limit)
		//search table record
		if(!empty($request->search)){
			$text = trim($request->search); 
			$search_condition = "(
				courrier.idcourrier LIKE ? OR 
				courrier.numero_courrier LIKE ? OR 
				courrier.date_courrier LIKE ? OR 
				courrier.date_reception LIKE ? OR 
				courrier.date_saisie LIKE ? OR 
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
				etat.idetat LIKE ? OR 
				etat.etat LIKE ? OR 
				droit_lister.iddroit_lister LIKE ? OR 
				droit_lister.roles LIKE ? OR 
				droit_lister.etat_courrier LIKE ?
			)";
			$search_params = array(
				"%$text%","%$text%","%$text%","%$text%","%$text%","%$text%","%$text%","%$text%","%$text%","%$text%","%$text%","%$text%","%$text%","%$text%","%$text%","%$text%","%$text%","%$text%","%$text%","%$text%","%$text%","%$text%"
			);
			//setting search conditions
			$db->where($search_condition, $search_params);
			 //template to use when ajax search
			$this->view->search_template = "courrier/search.php";
		}
		$db->join("etat", "courrier.etat = etat.idetat", "INNER");
		$db->join("droit_lister", "courrier.etat = droit_lister.etat_courrier", "LEFT");
		if(!empty($request->orderby)){
			$orderby = $request->orderby;
			$ordertype = (!empty($request->ordertype) ? $request->ordertype : ORDER_TYPE);
			$db->orderBy($orderby, $ordertype);
		}
		else{
			$db->orderBy("idcourrier", "DESC");
		}
		$db->groupBy("courrier.idcourrier");
		// Cloisonnement : un profil metier ne voit que les courriers qui lui
		// sont imputes. Les tables de droits ne filtrent que sur l'etat du
		// courrier, pas sur son destinataire.
		$restriction_courrier = condition_courriers_imputes();
		if ($restriction_courrier !== null) {
			$db->where($restriction_courrier);
		}
		// L'administrateur n'est pas restreint par la table des droits.
		if (!(function_exists('utilisateur_est_administrateur') && utilisateur_est_administrateur())) {
			$db->where(" droit_lister.roles='".USER_ROLE."'");
		}
		if($fieldname){
			$db->where($fieldname , $fieldvalue); //filter by a single field name
		}
		if(!empty($request->courrier_etat)){
			$val = $request->courrier_etat;
			$db->where("courrier.etat", $val , "=");
		}
		if(!empty($request->courrier_expediteur)){
			$val = $request->courrier_expediteur;
			$db->where("courrier.expediteur", $val , "=");
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
		$page_title = $this->view->page_title = get_lang('gestion_courrier');
		$this->render_view("courrier/list.php", $data); //render the full page
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
		$fields = array("courrier.idcourrier", 
			"courrier.date_courrier", 
			"courrier.date_reception", 
			"courrier.expediteur", 
			"courrier.intitule", 
			"courrier.objet", 
			"courrier.fichier", 
			"courrier.armoire", 
			"courrier.rangee", 
			"courrier.boite", 
			"courrier.sens", 
			"courrier.nature", 
			"courrier.etat", 
			"etat.idetat AS etat_idetat", 
			"etat.etat AS etat_etat", 
			"droit_lister.iddroit_lister AS droit_lister_iddroit_lister", 
			"droit_lister.roles AS droit_lister_roles", 
			"droit_lister.etat_courrier AS droit_lister_etat_courrier", 
			"courrier.numero_courrier", 
			"courrier.date_saisie");
		if($value){
			$db->where($rec_id, urldecode($value)); //select record based on field name
		}
		else{
			$db->where("courrier.idcourrier", $rec_id);; //select record based on primary key
		}
		$db->join("etat", "courrier.etat = etat.idetat", "INNER ");
		// Cloisonnement : un profil metier ne voit que les courriers qui lui
		// sont imputes. Les tables de droits ne filtrent que sur l'etat du
		// courrier, pas sur son destinataire.
		$restriction_courrier = condition_courriers_imputes();
		if ($restriction_courrier !== null) {
			$db->where($restriction_courrier);
		}
		$db->join("droit_lister", "courrier.etat = droit_lister.etat_courrier", "LEFT ");  
		$record = $db->getOne($tablename, $fields );
		if($record){
			$page_title = $this->view->page_title = get_lang('vue_courrier');
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
		return $this->render_view("courrier/view.php", $record);
	}
	/**
     * Insert new record to the database table
	 * @param $formdata array() from $_POST
     * @return BaseView
     */
	/**
     * Retrouve un expediteur a partir de la valeur saisie dans le formulaire,
     * et le cree s'il n'existe pas encore.
     * Le champ accepte desormais une saisie libre : la valeur recue est soit un
     * identifiant (choix dans la liste), soit un libelle tape par l'agent.
     * @param mixed $valeur
     * @return int|null identifiant de l'expediteur, null si la saisie est vide
     */
	private function resoudre_expediteur($valeur){
		$valeur = trim((string) $valeur);
		if($valeur === ''){
			return null;
		}
		$db = $this->GetModel();
		// Valeur numerique : c'est deja un identifiant, on verifie qu'il existe.
		if(ctype_digit($valeur)){
			$db->where('idexpediteur', (int) $valeur);
			if($db->has('expediteur')){
				return (int) $valeur;
			}
		}
		// Sinon on cherche par libelle, sans tenir compte de la casse.
		$existant = $db->rawQueryOne(
			"SELECT idexpediteur FROM expediteur WHERE LOWER(TRIM(expediteur)) = LOWER(TRIM(?)) LIMIT 1",
			array($valeur)
		);
		if(is_array($existant) && !empty($existant['idexpediteur'])){
			return (int) $existant['idexpediteur'];
		}
		// Expediteur inconnu : on le cree. Les colonnes cellulaire et ordinaire
		// sont obligatoires en base et n'ont pas de valeur par defaut.
		$nouveau = $db->insert('expediteur', array(
			'expediteur' => $valeur,
			'cellulaire' => '',
			'ordinaire'  => 0,
		));
		return $nouveau ? (int) $nouveau : null;
	}
	function add($formdata = null){
		if($formdata){
			$db = $this->GetModel();
			$tablename = $this->tablename;
			$request = $this->request;
			//fillable fields
			$fields = $this->fields = array("sens","nature","numero_courrier","date_courrier","date_reception","expediteur","intitule","objet","fichier","etat");
			$postdata = $this->format_request_data($formdata);
			$this->rules_array = array(
				'sens' => 'required',
				'nature' => 'required',
				'numero_courrier' => 'required',
				'date_courrier' => 'required',
				'date_reception' => 'required',
				'expediteur' => 'required',
				'intitule' => 'required',
				'objet' => 'required',
				'fichier' => 'required',
				'etat' => 'required',
			);
			$this->sanitize_array = array(
				'sens' => 'sanitize_string',
				'nature' => 'sanitize_string',
				'numero_courrier' => 'sanitize_string',
				'date_courrier' => 'sanitize_string',
				'date_reception' => 'sanitize_string',
				'expediteur' => 'sanitize_string',
				'intitule' => 'sanitize_string',
				'fichier' => 'sanitize_string',
				'etat' => 'sanitize_string',
			);
			$this->filter_vals = true; //set whether to remove empty fields
			$modeldata = $this->modeldata = $this->validate_form($postdata);
			//Check if Duplicate Record Already Exit In The Database
			$db->where("numero_courrier", $modeldata['numero_courrier']);
			if($db->has($tablename)){
				$this->view->page_error[] = $modeldata['numero_courrier'].get_lang('_existe_d_j_');
			} 
			// Le champ expediteur accepte une saisie libre : on convertit le libelle
			// en identifiant, en creant l'expediteur au besoin.
			if(isset($modeldata['expediteur'])){
				$id_expediteur = $this->resoudre_expediteur($modeldata['expediteur']);
				if(empty($id_expediteur)){
					$this->view->page_error[] = "L'expediteur n'a pas pu etre enregistre.";
				}
				else{
					$modeldata['expediteur'] = $this->modeldata['expediteur'] = $id_expediteur;
				}
			}
			if($this->validated()){
				// Ces colonnes sont obligatoires en base et n'ont aucune valeur par
				// defaut, alors qu'elles sont absentes du formulaire ou facultatives.
				// On fournit donc une valeur neutre quand le champ est vide, faute de
				// quoi MySQL refuse l'insertion ("doesn't have a default value").
				foreach (array('armoire' => '', 'rangee' => '', 'boite' => '') as $col => $defaut) {
					if (!isset($modeldata[$col]) || $modeldata[$col] === '') { $modeldata[$col] = $defaut; }
				}
				$rec_id = $this->rec_id = $db->insert($tablename, $modeldata);
				if($rec_id){
					Circuit::journaliser($db, $rec_id,
						"Enregistrement du courrier " . $modeldata['numero_courrier']
						. " : " . $modeldata['intitule']);

					// Tout courrier entrant remonte au Directeur General. La
					// transmission est une consequence de l'enregistrement, non une
					// action separee qu'un agent pourrait oublier.
					list($transmis, $message) = Circuit::transmettreAuDestinataireInitial(
						$db, $rec_id, $modeldata['intitule']
					);
					if ($message !== '') {
						$this->set_flash_msg($message, $transmis ? "success" : "warning");
					}
					$this->set_flash_msg(get_lang('enregistrement_ajout_avec_succ_s'), "success");
					return	$this->redirect("courrier");
				}
				else{
					$this->set_page_error();
				}
			}
		}
		$page_title = $this->view->page_title = get_lang('ajouter_un_nouveau');
		$this->render_view("courrier/add.php");
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
		$fields = $this->fields = array("idcourrier","nature","numero_courrier","date_courrier","date_reception","expediteur","intitule","objet","fichier","etat");
		if($formdata){
			$postdata = $this->format_request_data($formdata);
			$this->rules_array = array(
				'nature' => 'required',
				'numero_courrier' => 'required',
				'date_courrier' => 'required',
				'date_reception' => 'required',
				'expediteur' => 'required',
				'intitule' => 'required',
				'objet' => 'required',
				'fichier' => 'required',
				'etat' => 'required',
			);
			$this->sanitize_array = array(
				'nature' => 'sanitize_string',
				'numero_courrier' => 'sanitize_string',
				'date_courrier' => 'sanitize_string',
				'date_reception' => 'sanitize_string',
				'expediteur' => 'sanitize_string',
				'intitule' => 'sanitize_string',
				'fichier' => 'sanitize_string',
				'etat' => 'sanitize_string',
			);
			$this->filter_vals = true; //set whether to remove empty fields
			$modeldata = $this->modeldata = $this->validate_form($postdata);
			//Check if Duplicate Record Already Exit In The Database
			if(isset($modeldata['numero_courrier'])){
				$db->where("numero_courrier", $modeldata['numero_courrier'])->where("idcourrier", $rec_id, "!=");
				if($db->has($tablename)){
					$this->view->page_error[] = $modeldata['numero_courrier'].get_lang('_existe_d_j_');
				}
			} 
			if($this->validated()){
				$db->where("courrier.idcourrier", $rec_id);;
				$bool = $db->update($tablename, $modeldata);
				$numRows = $db->getRowCount(); //number of affected rows. 0 = no record field updated
				if($bool && $numRows){
		# Statement to execute after adding record
/////////////////////
$today  = datetime_now();
$action = "Modification- Transmission courrier numéro ".$modeldata['numero_courrier']." intitulé : ".$modeldata['intitule'];  
$table_data = array(
    "userid" => get_active_user('iduser'),
    "numero_courrier" => $modeldata['numero_courrier'],
    "idcourrier" => $rec_id,
    "action" => $action
);
$db->insert("evenement", $table_data);
		# End of after update statement
					$this->set_flash_msg(get_lang('enregistrement_mis_jour_avec_succ_s'), "success");
					return $this->redirect("courrier");
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
						return	$this->redirect("courrier");
					}
				}
			}
		}
		$db->where("courrier.idcourrier", $rec_id);;
		$data = $db->getOne($tablename, $fields);
		$page_title = $this->view->page_title = get_lang('modifier');
		if(!$data){
			$this->set_page_error();
		}
		return $this->render_view("courrier/edit.php", $data);
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
		$db->where("courrier.idcourrier", $arr_rec_id, "in");
		$bool = $db->delete($tablename);
		if($bool){
			$this->set_flash_msg(get_lang('enregistrement_supprim_avec_succ_s'), "success");
		}
		elseif($db->getLastError()){
			$page_error = $db->getLastError();
			$this->set_flash_msg($page_error, "danger");
		}
		return	$this->redirect("courrier");
	}
	/**
     * List page records
     * @param $fieldname (filter record by a field) 
     * @param $fieldvalue (filter field value)
     * @return BaseView
     */
	function courriersaimputer($fieldname = null , $fieldvalue = null){
		$request = $this->request;
		$db = $this->GetModel();
		$tablename = $this->tablename;
		$fields = array("courrier.idcourrier", 
			"courrier.numero_courrier", 
			"courrier.date_courrier", 
			"courrier.date_reception", 
			"courrier.intitule", 
			"courrier.objet", 
			"courrier.fichier", 
			"etat.etat AS etat_etat", 
			"CONCAT('Imputer-',idcourrier) AS Imputer", 
			"courrier.date_saisie");
		$pagination = $this->get_pagination(2000); // get current pagination e.g array(page_number, page_limit)
		//search table record
		if(!empty($request->search)){
			$text = trim($request->search); 
			$search_condition = "(
				courrier.idcourrier LIKE ? OR 
				courrier.numero_courrier LIKE ? OR 
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
				roles_courrier.id LIKE ? OR 
				roles_courrier.roles LIKE ? OR 
				roles_courrier.etat LIKE ? OR 
				etat.idetat LIKE ? OR 
				etat.etat LIKE ? OR 
				courrier.Imputer LIKE ? OR 
				courrier.date_saisie LIKE ?
			)";
			$search_params = array(
				"%$text%","%$text%","%$text%","%$text%","%$text%","%$text%","%$text%","%$text%","%$text%","%$text%","%$text%","%$text%","%$text%","%$text%","%$text%","%$text%","%$text%","%$text%","%$text%","%$text%","%$text%","%$text%","%$text%"
			);
			//setting search conditions
			$db->where($search_condition, $search_params);
			 //template to use when ajax search
			$this->view->search_template = "courrier/search.php";
		}
		// Pour le bureau du courrier, la jointure ne doit pas etre bloquante : sa
		// file d'attente est definie par l'absence d'imputation, pas par la table
		// des droits. En INNER, une table roles_courrier vide suffisait a vider
		// l'ecran, sans le moindre message.
		$jointure_droits = (function_exists('utilisateur_voit_tous_les_courriers') && utilisateur_voit_tous_les_courriers())
			? "LEFT" : "INNER";
		$db->join("roles_courrier", "courrier.etat = roles_courrier.etat", $jointure_droits);
		$db->join("etat", "courrier.etat = etat.idetat", "INNER");
		if(!empty($request->orderby)){
			$orderby = $request->orderby;
			$ordertype = (!empty($request->ordertype) ? $request->ordertype : ORDER_TYPE);
			$db->orderBy($orderby, $ordertype);
		}
		else{
			$db->orderBy("idcourrier", "DESC");
		}
		$db->groupBy("courrier.idcourrier");
		// Cloisonnement : un profil metier ne voit que les courriers qui lui
		// sont imputes. Les tables de droits ne filtrent que sur l'etat du
		// courrier, pas sur son destinataire.
		$restriction_courrier = condition_courriers_imputes();
		if ($restriction_courrier !== null) {
			$db->where($restriction_courrier);
		}
		// L'administrateur n'est pas restreint par la table des droits.
		// File d'attente du bureau du courrier : les plis qui n'ont encore ete
		// imputes a personne. C'est sa file d'attente reelle, et elle ne depend
		// d'aucun parametrage d'etats — la table roles_courrier, si elle est
		// vide pour son profil, renvoyait une liste vide sans rien expliquer.
		if (function_exists('utilisateur_voit_tous_les_courriers') && utilisateur_voit_tous_les_courriers()
			&& !utilisateur_est_administrateur()) {
			$db->where(" NOT EXISTS (SELECT 1 FROM imputation im WHERE im.idcourrier = courrier.idcourrier) ");
		}
		elseif (!utilisateur_est_administrateur()) {
			$db->where(" roles_courrier.roles='".intval(USER_ROLE)."'");
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
		$page_title = $this->view->page_title = get_lang('gestion_courrier');
		$this->view->report_filename = date('Y-m-d') . '-' . $page_title;
		$this->view->report_title = $page_title;
		$this->view->report_layout = "report_layout.php";
		$this->view->report_paper_size = "A4";
		$this->view->report_orientation = "portrait";
		$this->render_view("courrier/courriersaimputer.php", $data); //render the full page
	}
	/**
     * List page records
     * @param $fieldname (filter record by a field) 
     * @param $fieldvalue (filter field value)
     * @return BaseView
     */
	function courrier_sauve($fieldname = null , $fieldvalue = null){
		$request = $this->request;
		$db = $this->GetModel();
		$tablename = $this->tablename;
		$fields = array("courrier.idcourrier", 
			"courrier.date_courrier", 
			"courrier.date_reception", 
			"courrier.intitule", 
			"courrier.objet", 
			"courrier.fichier", 
			"etat.etat AS etat_etat", 
			"'IMPUTATIONS' AS Imputation", 
			"courrier.numero_courrier", 
			"courrier.date_saisie");
		$pagination = $this->get_pagination(1000); // get current pagination e.g array(page_number, page_limit)
		//search table record
		if(!empty($request->search)){
			$text = trim($request->search); 
			$search_condition = "(
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
				roles_courrier.id LIKE ? OR 
				roles_courrier.roles LIKE ? OR 
				roles_courrier.etat LIKE ? OR 
				etat.idetat LIKE ? OR 
				etat.etat LIKE ? OR 
				courrier.Imputation LIKE ? OR 
				courrier.numero_courrier LIKE ? OR 
				courrier.date_saisie LIKE ?
			)";
			$search_params = array(
				"%$text%","%$text%","%$text%","%$text%","%$text%","%$text%","%$text%","%$text%","%$text%","%$text%","%$text%","%$text%","%$text%","%$text%","%$text%","%$text%","%$text%","%$text%","%$text%","%$text%","%$text%","%$text%","%$text%"
			);
			//setting search conditions
			$db->where($search_condition, $search_params);
			 //template to use when ajax search
			$this->view->search_template = "courrier/search.php";
		}
		$db->join("roles_courrier", "courrier.etat = roles_courrier.etat", "INNER");
		$db->join("etat", "courrier.etat = etat.idetat", "INNER");
		if(!empty($request->orderby)){
			$orderby = $request->orderby;
			$ordertype = (!empty($request->ordertype) ? $request->ordertype : ORDER_TYPE);
			$db->orderBy($orderby, $ordertype);
		}
		else{
			$db->orderBy("idcourrier", "DESC");
		}
		$db->groupBy("courrier.idcourrier");
		// Cloisonnement : un profil metier ne voit que les courriers qui lui
		// sont imputes. Les tables de droits ne filtrent que sur l'etat du
		// courrier, pas sur son destinataire.
		$restriction_courrier = condition_courriers_imputes();
		if ($restriction_courrier !== null) {
			$db->where($restriction_courrier);
		}
		// L'administrateur n'est pas restreint par la table des droits.
		if (!(function_exists('utilisateur_est_administrateur') && utilisateur_est_administrateur())) {
			$db->where(" roles_courrier.roles='".USER_ROLE."'");
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
		$page_title = $this->view->page_title = get_lang('gestion_courrier');
		$this->view->report_filename = date('Y-m-d') . '-' . $page_title;
		$this->view->report_title = $page_title;
		$this->view->report_layout = "report_layout.php";
		$this->view->report_paper_size = "A4";
		$this->view->report_orientation = "portrait";
		$this->render_view("courrier/courrier_sauve.php", $data); //render the full page
	}
	/**
     * List page records
     * @param $fieldname (filter record by a field) 
     * @param $fieldvalue (filter field value)
     * @return BaseView
     */
	function recherche($fieldname = null , $fieldvalue = null){
		$request = $this->request;
		$db = $this->GetModel();
		$tablename = $this->tablename;
		$fields = array("courrier.idcourrier", 
			"courrier.numero_courrier", 
			"courrier.date_saisie", 
			"courrier.date_courrier", 
			"courrier.date_reception", 
			"courrier.intitule", 
			"courrier.objet", 
			"courrier.fichier", 
			"etat.etat AS etat_etat", 
			"'Suivi évènements' AS suivi");
		$pagination = $this->get_pagination(1000); // get current pagination e.g array(page_number, page_limit)
		//search table record
		if(!empty($request->search)){
			$text = trim($request->search); 
			$search_condition = "(
				courrier.idcourrier LIKE ? OR 
				courrier.numero_courrier LIKE ? OR 
				courrier.date_saisie LIKE ? OR 
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
				etat.idetat LIKE ? OR 
				etat.etat LIKE ? OR 
				droit_lister.iddroit_lister LIKE ? OR 
				droit_lister.roles LIKE ? OR 
				droit_lister.etat_courrier LIKE ?
			)";
			$search_params = array(
				"%$text%","%$text%","%$text%","%$text%","%$text%","%$text%","%$text%","%$text%","%$text%","%$text%","%$text%","%$text%","%$text%","%$text%","%$text%","%$text%","%$text%","%$text%","%$text%","%$text%","%$text%","%$text%"
			);
			//setting search conditions
			$db->where($search_condition, $search_params);
			 //template to use when ajax search
			$this->view->search_template = "courrier/search.php";
		}
		$db->join("etat", "courrier.etat = etat.idetat", "INNER");
		$db->join("droit_lister", "courrier.etat = droit_lister.etat_courrier", "LEFT");
		if(!empty($request->orderby)){
			$orderby = $request->orderby;
			$ordertype = (!empty($request->ordertype) ? $request->ordertype : ORDER_TYPE);
			$db->orderBy($orderby, $ordertype);
		}
		else{
			$db->orderBy("idcourrier", "DESC");
		}
		$db->groupBy("courrier.idcourrier");
		// Cloisonnement : un profil metier ne voit que les courriers qui lui
		// sont imputes. Les tables de droits ne filtrent que sur l'etat du
		// courrier, pas sur son destinataire.
		$restriction_courrier = condition_courriers_imputes();
		if ($restriction_courrier !== null) {
			$db->where($restriction_courrier);
		}
		// La recherche etait moins restrictive que la liste : elle joignait la
		// table des droits sans jamais filtrer dessus. Un profil pouvait donc
		// retrouver par la recherche un courrier que sa liste lui cachait.
		if (!utilisateur_est_administrateur()) {
			$db->where(" droit_lister.roles='".intval(USER_ROLE)."'");
		}
		if($fieldname){
			$db->where($fieldname , $fieldvalue); //filter by a single field name
		}
		if(!empty($request->courrier_date_reception)){
			$vals = explode("-to-", str_replace(" ", "", $request->courrier_date_reception));
			$startdate = $vals[0];
			$enddate = $vals[1];
			$db->where("courrier.date_reception BETWEEN '$startdate' AND '$enddate'");
		}
		if(!empty($request->courrier_expediteur)){
			$val = $request->courrier_expediteur;
			$db->where("courrier.expediteur", $val , "=");
		}
		if(!empty($request->courrier_etat)){
			$val = $request->courrier_etat;
			$db->where("courrier.etat", $val , "=");
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
		$page_title = $this->view->page_title = get_lang('gestion_courrier');
		$this->render_view("courrier/recherche.php", $data); //render the full page
	}
}
