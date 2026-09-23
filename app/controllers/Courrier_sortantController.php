<?php 
/**
 * Courrier_sortant Page Controller
 * @category  Controller
 */
class Courrier_sortantController extends SecureController{
	function __construct(){
		parent::__construct();
		$this->tablename = "courrier_sortant";
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
		$fields = array("courrier_sortant.idcourrier", 
			"nature_courrier.nature AS nature_courrier_nature", 
			"courrier_sortant.numero_courrier", 
			"courrier_sortant.en_reponse_courrier_numero", 
			"courrier_sortant.date_saisie", 
			"courrier_sortant.date_courrier", 
			"courrier_sortant.date_envoi", 
			"expediteur.expediteur AS expediteur_expediteur", 
			"courrier_sortant.intitule", 
			"courrier_sortant.objet", 
			"courrier_sortant.fichier");
		$pagination = $this->get_pagination(MAX_RECORD_COUNT); // get current pagination e.g array(page_number, page_limit)
		//search table record
		if(!empty($request->search)){
			$text = trim($request->search); 
			$search_condition = "(
				courrier_sortant.idcourrier LIKE ? OR 
				nature_courrier.nature LIKE ? OR 
				courrier_sortant.nature LIKE ? OR 
				courrier_sortant.numero_courrier LIKE ? OR 
				courrier_sortant.en_reponse_courrier_numero LIKE ? OR 
				courrier_sortant.date_saisie LIKE ? OR 
				courrier_sortant.date_courrier LIKE ? OR 
				courrier_sortant.date_envoi LIKE ? OR 
				expediteur.expediteur LIKE ? OR 
				courrier_sortant.destinataire LIKE ? OR 
				courrier_sortant.intitule LIKE ? OR 
				courrier_sortant.objet LIKE ? OR 
				courrier_sortant.fichier LIKE ? OR 
				courrier_sortant.armoire LIKE ? OR 
				courrier_sortant.rangee LIKE ? OR 
				courrier_sortant.boite LIKE ? OR 
				courrier_sortant.sens LIKE ? OR 
				expediteur.idexpediteur LIKE ? OR 
				expediteur.cellulaire LIKE ? OR 
				nature_courrier.idnature LIKE ?
			)";
			$search_params = array(
				"%$text%","%$text%","%$text%","%$text%","%$text%","%$text%","%$text%","%$text%","%$text%","%$text%","%$text%","%$text%","%$text%","%$text%","%$text%","%$text%","%$text%","%$text%","%$text%","%$text%"
			);
			//setting search conditions
			$db->where($search_condition, $search_params);
			 //template to use when ajax search
			$this->view->search_template = "courrier_sortant/search.php";
		}
		$db->join("expediteur", "courrier_sortant.destinataire = expediteur.idexpediteur", "LEFT");
		$db->join("nature_courrier", "courrier_sortant.nature = nature_courrier.idnature", "LEFT");
		if(!empty($request->orderby)){
			$orderby = $request->orderby;
			$ordertype = (!empty($request->ordertype) ? $request->ordertype : ORDER_TYPE);
			$db->orderBy($orderby, $ordertype);
		}
		else{
			$db->orderBy("courrier_sortant.idcourrier", ORDER_TYPE);
		}
		if($fieldname && est_nom_de_colonne($fieldname)){
			$db->where($fieldname , $fieldvalue); //filter by a single field name
		}
		if(!empty($request->courrier_sortant_destinataire)){
			$val = $request->courrier_sortant_destinataire;
			$db->where("courrier_sortant.destinataire", $val , "=");
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
		$page_title = $this->view->page_title = get_lang('courrier_sortant');
		$this->render_view("courrier_sortant/list.php", $data); //render the full page
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
		$fields = array("courrier_sortant.idcourrier", 
			"courrier_sortant.date_courrier", 
			"courrier_sortant.date_envoi", 
			"courrier_sortant.destinataire", 
			"courrier_sortant.intitule", 
			"courrier_sortant.objet", 
			"courrier_sortant.fichier", 
			"courrier_sortant.armoire", 
			"courrier_sortant.rangee", 
			"courrier_sortant.boite", 
			"courrier_sortant.nature", 
			"courrier_sortant.numero_courrier", 
			"courrier_sortant.date_saisie", 
			"courrier_sortant.sens", 
			"courrier_sortant.en_reponse_courrier_numero", 
			"expediteur.idexpediteur AS expediteur_idexpediteur", 
			"expediteur.expediteur AS expediteur_expediteur", 
			"expediteur.cellulaire AS expediteur_cellulaire", 
			"nature_courrier.idnature AS nature_courrier_idnature", 
			"nature_courrier.nature AS nature_courrier_nature");
		if($value && est_nom_de_colonne($rec_id)){
			$db->where($rec_id, urldecode($value)); //select record based on field name
		}
		else{
			$db->where("courrier_sortant.idcourrier", $rec_id);; //select record based on primary key
		}
		$db->join("expediteur", "courrier_sortant.destinataire = expediteur.idexpediteur", "LEFT ");
		$db->join("nature_courrier", "courrier_sortant.nature = nature_courrier.idnature", "LEFT ");  
		$record = $db->getOne($tablename, $fields );
		if($record){
			$page_title = $this->view->page_title = get_lang('vue_courrier_sortant');
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
		return $this->render_view("courrier_sortant/view.php", $record);
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
			$fields = $this->fields = array("sens","nature","numero_courrier","destinataire","en_reponse_courrier_numero","date_courrier","date_envoi","intitule","objet","fichier");
			$postdata = $this->format_request_data($formdata);
			$this->rules_array = array(
				'sens' => 'required',
				'nature' => 'required',
				'numero_courrier' => 'required',
				'destinataire' => 'required',
				'date_courrier' => 'required',
				'date_envoi' => 'required',
				'intitule' => 'required',
				'objet' => 'required',
				'fichier' => 'required',
			);
			$this->sanitize_array = array(
				'sens' => 'sanitize_string',
				'nature' => 'sanitize_string',
				'numero_courrier' => 'sanitize_string',
				'destinataire' => 'sanitize_string',
				'en_reponse_courrier_numero' => 'sanitize_string',
				'date_courrier' => 'sanitize_string',
				'date_envoi' => 'sanitize_string',
				'intitule' => 'sanitize_string',
				'fichier' => 'sanitize_string',
			);
			$this->filter_vals = true; //set whether to remove empty fields
			$modeldata = $this->modeldata = $this->validate_form($postdata);
			if($this->validated()){
				// Ces colonnes sont obligatoires en base et n'ont aucune valeur par
				// defaut, alors qu'elles sont absentes du formulaire ou facultatives.
				// On fournit donc une valeur neutre quand le champ est vide, faute de
				// quoi MySQL refuse l'insertion ("doesn't have a default value").
				foreach (array('armoire' => '', 'rangee' => '', 'boite' => '', 'en_reponse_courrier_numero' => 0) as $col => $defaut) {
					if (!isset($modeldata[$col]) || $modeldata[$col] === '') { $modeldata[$col] = $defaut; }
				}
				$rec_id = $this->rec_id = $db->insert($tablename, $modeldata);
				if($rec_id){
					$this->set_flash_msg(get_lang('enregistrement_ajout_avec_succ_s'), "success");
					return	$this->redirect("courrier_sortant");
				}
				else{
					$this->set_page_error();
				}
			}
		}
		$page_title = $this->view->page_title = get_lang('ajouter_un_nouveau');
		$this->render_view("courrier_sortant/add.php");
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
		$fields = $this->fields = array("idcourrier","sens","nature","numero_courrier","destinataire","en_reponse_courrier_numero","date_courrier","date_envoi","intitule","objet","fichier");
		if($formdata){
			$postdata = $this->format_request_data($formdata);
			$this->rules_array = array(
				'sens' => 'required',
				'nature' => 'required',
				'numero_courrier' => 'required',
				'destinataire' => 'required',
				'date_courrier' => 'required',
				'date_envoi' => 'required',
				'intitule' => 'required',
				'objet' => 'required',
				'fichier' => 'required',
			);
			$this->sanitize_array = array(
				'sens' => 'sanitize_string',
				'nature' => 'sanitize_string',
				'numero_courrier' => 'sanitize_string',
				'destinataire' => 'sanitize_string',
				'en_reponse_courrier_numero' => 'sanitize_string',
				'date_courrier' => 'sanitize_string',
				'date_envoi' => 'sanitize_string',
				'intitule' => 'sanitize_string',
				'fichier' => 'sanitize_string',
			);
			$this->filter_vals = true; //set whether to remove empty fields
			$modeldata = $this->modeldata = $this->validate_form($postdata);
			if($this->validated()){
				$db->where("courrier_sortant.idcourrier", $rec_id);;
				$bool = $db->update($tablename, $modeldata);
				$numRows = $db->getRowCount(); //number of affected rows. 0 = no record field updated
				if($bool && $numRows){
					$this->set_flash_msg(get_lang('enregistrement_mis_jour_avec_succ_s'), "success");
					return $this->redirect("courrier_sortant");
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
						return	$this->redirect("courrier_sortant");
					}
				}
			}
		}
		$db->where("courrier_sortant.idcourrier", $rec_id);;
		$data = $db->getOne($tablename, $fields);
		$page_title = $this->view->page_title = get_lang('modifier');
		if(!$data){
			$this->set_page_error();
		}
		return $this->render_view("courrier_sortant/edit.php", $data);
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
		$fields = $this->fields = array("idcourrier","sens","nature","numero_courrier","destinataire","en_reponse_courrier_numero","date_courrier","date_envoi","intitule","objet","fichier");
		$page_error = null;
		if($formdata){
			$postdata = array();
			$fieldname = $formdata['name'];
			$fieldvalue = $formdata['value'];
			$postdata[$fieldname] = $fieldvalue;
			$postdata = $this->format_request_data($postdata);
			$this->rules_array = array(
				'sens' => 'required',
				'nature' => 'required',
				'numero_courrier' => 'required',
				'destinataire' => 'required',
				'date_courrier' => 'required',
				'date_envoi' => 'required',
				'intitule' => 'required',
				'objet' => 'required',
				'fichier' => 'required',
			);
			$this->sanitize_array = array(
				'sens' => 'sanitize_string',
				'nature' => 'sanitize_string',
				'numero_courrier' => 'sanitize_string',
				'destinataire' => 'sanitize_string',
				'en_reponse_courrier_numero' => 'sanitize_string',
				'date_courrier' => 'sanitize_string',
				'date_envoi' => 'sanitize_string',
				'intitule' => 'sanitize_string',
				'fichier' => 'sanitize_string',
			);
			$this->filter_rules = true; //filter validation rules by excluding fields not in the formdata
			$modeldata = $this->modeldata = $this->validate_form($postdata);
			if($this->validated()){
				$db->where("courrier_sortant.idcourrier", $rec_id);;
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
		$db->where("courrier_sortant.idcourrier", $arr_rec_id, "in");
		$bool = $db->delete($tablename);
		if($bool){
			$this->set_flash_msg(get_lang('enregistrement_supprim_avec_succ_s'), "success");
		}
		elseif($db->getLastError()){
			$page_error = $db->getLastError();
			$this->set_flash_msg($page_error, "danger");
		}
		return	$this->redirect("courrier_sortant");
	}
}
