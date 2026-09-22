<?php 
/**
 * Evenement Page Controller
 * @category  Controller
 */
class EvenementController extends SecureController{
	function __construct(){
		parent::__construct();
		$this->tablename = "evenement";
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
		$fields = array("evenement.idevenement", 
			"evenement.numero_courrier", 
			"evenement.date_evenement", 
			"user.identification AS user_identification", 
			"user.avatar AS user_avatar", 
			"evenement.action", 
			"user2.identification AS user2_identification", 
			"user2.avatar AS user2_avatar");
		$pagination = $this->get_pagination(MAX_RECORD_COUNT); // get current pagination e.g array(page_number, page_limit)
		//search table record
		if(!empty($request->search)){
			$text = trim($request->search); 
			$search_condition = "(
				evenement.idevenement LIKE ? OR 
				evenement.numero_courrier LIKE ? OR 
				evenement.date_evenement LIKE ? OR 
				evenement.userid LIKE ? OR 
				user.identification LIKE ? OR 
				user.avatar LIKE ? OR 
				evenement.action LIKE ? OR 
				evenement.destinataire LIKE ? OR 
				user.iduser LIKE ? OR 
				user.login LIKE ? OR 
				user.password LIKE ? OR 
				user.emailuser LIKE ? OR 
				user.date_deleted LIKE ? OR 
				user.is_deleted LIKE ? OR 
				user.user_role_id LIKE ? OR 
				user.niveau_imputation LIKE ? OR 
				user.direction LIKE ? OR 
				user.division LIKE ? OR 
				user.online LIKE ? OR 
				user2.iduser LIKE ? OR 
				user2.identification LIKE ? OR 
				user2.login LIKE ? OR 
				user2.password LIKE ? OR 
				user2.emailuser LIKE ? OR 
				user2.avatar LIKE ? OR 
				user2.date_deleted LIKE ? OR 
				user2.is_deleted LIKE ? OR 
				user2.user_role_id LIKE ? OR 
				user2.niveau_imputation LIKE ? OR 
				user2.direction LIKE ? OR 
				user2.division LIKE ? OR 
				user2.online LIKE ? OR 
				evenement.idcourrier LIKE ?
			)";
			$search_params = array(
				"%$text%","%$text%","%$text%","%$text%","%$text%","%$text%","%$text%","%$text%","%$text%","%$text%","%$text%","%$text%","%$text%","%$text%","%$text%","%$text%","%$text%","%$text%","%$text%","%$text%","%$text%","%$text%","%$text%","%$text%","%$text%","%$text%","%$text%","%$text%","%$text%","%$text%","%$text%","%$text%","%$text%"
			);
			//setting search conditions
			$db->where($search_condition, $search_params);
			 //template to use when ajax search
			$this->view->search_template = "evenement/search.php";
		}
		$db->join("user", "evenement.userid = user.iduser", "LEFT");
		$db->join("user AS user2", "evenement.destinataire = user2.iduser", "LEFT");
		if(!empty($request->orderby)){
			$orderby = $request->orderby;
			$ordertype = (!empty($request->ordertype) ? $request->ordertype : ORDER_TYPE);
			$db->orderBy($orderby, $ordertype);
		}
		else{
			$db->orderBy("idevenement", "DESC");
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
		$page_title = $this->view->page_title = get_lang('evenement');
		$this->render_view("evenement/list.php", $data); //render the full page
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
		$fields = array("evenement.idevenement", 
			"evenement.date_evenement", 
			"evenement.userid", 
			"evenement.action", 
			"evenement.numero_courrier", 
			"evenement.destinataire", 
			"user.iduser AS user_iduser", 
			"user.identification AS user_identification", 
			"user.login AS user_login", 
			"user.password AS user_password", 
			"user.emailuser AS user_emailuser", 
			"user.avatar AS user_avatar", 
			"user.date_deleted AS user_date_deleted", 
			"user.is_deleted AS user_is_deleted", 
			"user.user_role_id AS user_user_role_id", 
			"user.niveau_imputation AS user_niveau_imputation", 
			"user.direction AS user_direction", 
			"user.division AS user_division", 
			"user.online AS user_online", 
			"user2.iduser AS user2_iduser", 
			"user2.identification AS user2_identification", 
			"user2.login AS user2_login", 
			"user2.password AS user2_password", 
			"user2.emailuser AS user2_emailuser", 
			"user2.avatar AS user2_avatar", 
			"user2.date_deleted AS user2_date_deleted", 
			"user2.is_deleted AS user2_is_deleted", 
			"user2.user_role_id AS user2_user_role_id", 
			"user2.niveau_imputation AS user2_niveau_imputation", 
			"user2.direction AS user2_direction", 
			"user2.division AS user2_division", 
			"user2.online AS user2_online", 
			"evenement.idcourrier");
		if($value){
			$db->where($rec_id, urldecode($value)); //select record based on field name
		}
		else{
			$db->where("evenement.idevenement", $rec_id);; //select record based on primary key
		}
		$db->join("user", "evenement.userid = user.iduser", "LEFT ");
		$db->join("user AS user2", "evenement.destinataire = user2.iduser", "LEFT ");  
		$record = $db->getOne($tablename, $fields );
		if($record){
			$page_title = $this->view->page_title = get_lang('vue_evenement');
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
		return $this->render_view("evenement/view.php", $record);
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
			$fields = $this->fields = array("userid","action","numero_courrier","destinataire","idcourrier");
			$postdata = $this->format_request_data($formdata);
			$this->rules_array = array(
				'userid' => 'required|numeric',
				'action' => 'required',
				'numero_courrier' => 'required',
				'destinataire' => 'required|numeric',
				'idcourrier' => 'required|numeric',
			);
			$this->sanitize_array = array(
				'userid' => 'sanitize_string',
				'action' => 'sanitize_string',
				'numero_courrier' => 'sanitize_string',
				'destinataire' => 'sanitize_string',
				'idcourrier' => 'sanitize_string',
			);
			$this->filter_vals = true; //set whether to remove empty fields
			$modeldata = $this->modeldata = $this->validate_form($postdata);
			if($this->validated()){
				$rec_id = $this->rec_id = $db->insert($tablename, $modeldata);
				if($rec_id){
					$this->set_flash_msg(get_lang('enregistrement_ajout_avec_succ_s'), "success");
					return	$this->redirect("evenement");
				}
				else{
					$this->set_page_error();
				}
			}
		}
		$page_title = $this->view->page_title = get_lang('ajouter_un_nouveau');
		$this->render_view("evenement/add.php");
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
		$fields = $this->fields = array("idevenement","userid","action","numero_courrier","destinataire","idcourrier");
		if($formdata){
			$postdata = $this->format_request_data($formdata);
			$this->rules_array = array(
				'userid' => 'required|numeric',
				'action' => 'required',
				'numero_courrier' => 'required',
				'destinataire' => 'required|numeric',
				'idcourrier' => 'required|numeric',
			);
			$this->sanitize_array = array(
				'userid' => 'sanitize_string',
				'action' => 'sanitize_string',
				'numero_courrier' => 'sanitize_string',
				'destinataire' => 'sanitize_string',
				'idcourrier' => 'sanitize_string',
			);
			$this->filter_vals = true; //set whether to remove empty fields
			$modeldata = $this->modeldata = $this->validate_form($postdata);
			if($this->validated()){
				$db->where("evenement.idevenement", $rec_id);;
				$bool = $db->update($tablename, $modeldata);
				$numRows = $db->getRowCount(); //number of affected rows. 0 = no record field updated
				if($bool && $numRows){
					$this->set_flash_msg(get_lang('enregistrement_mis_jour_avec_succ_s'), "success");
					return $this->redirect("evenement");
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
						return	$this->redirect("evenement");
					}
				}
			}
		}
		$db->where("evenement.idevenement", $rec_id);;
		$data = $db->getOne($tablename, $fields);
		$page_title = $this->view->page_title = get_lang('modifier');
		if(!$data){
			$this->set_page_error();
		}
		return $this->render_view("evenement/edit.php", $data);
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
		$fields = $this->fields = array("idevenement","userid","action","numero_courrier","destinataire","idcourrier");
		$page_error = null;
		if($formdata){
			$postdata = array();
			$fieldname = $formdata['name'];
			$fieldvalue = $formdata['value'];
			$postdata[$fieldname] = $fieldvalue;
			$postdata = $this->format_request_data($postdata);
			$this->rules_array = array(
				'userid' => 'required|numeric',
				'action' => 'required',
				'numero_courrier' => 'required',
				'destinataire' => 'required|numeric',
				'idcourrier' => 'required|numeric',
			);
			$this->sanitize_array = array(
				'userid' => 'sanitize_string',
				'action' => 'sanitize_string',
				'numero_courrier' => 'sanitize_string',
				'destinataire' => 'sanitize_string',
				'idcourrier' => 'sanitize_string',
			);
			$this->filter_rules = true; //filter validation rules by excluding fields not in the formdata
			$modeldata = $this->modeldata = $this->validate_form($postdata);
			if($this->validated()){
				$db->where("evenement.idevenement", $rec_id);;
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
		$db->where("evenement.idevenement", $arr_rec_id, "in");
		$bool = $db->delete($tablename);
		if($bool){
			$this->set_flash_msg(get_lang('enregistrement_supprim_avec_succ_s'), "success");
		}
		elseif($db->getLastError()){
			$page_error = $db->getLastError();
			$this->set_flash_msg($page_error, "danger");
		}
		return	$this->redirect("evenement");
	}
	/**
     * List page records
     * @param $fieldname (filter record by a field) 
     * @param $fieldvalue (filter field value)
     * @return BaseView
     */
	function suivi($fieldname = null , $fieldvalue = null){
		$request = $this->request;
		$db = $this->GetModel();
		$tablename = $this->tablename;
		$fields = array("evenement.idevenement", 
			"evenement.numero_courrier", 
			"evenement.date_evenement", 
			"user.identification AS user_identification", 
			"user.avatar AS user_avatar", 
			"evenement.action", 
			"user2.identification AS user2_identification", 
			"user2.avatar AS user2_avatar", 
			"evenement.idcourrier");
		$pagination = $this->get_pagination(MAX_RECORD_COUNT); // get current pagination e.g array(page_number, page_limit)
		//search table record
		if(!empty($request->search)){
			$text = trim($request->search); 
			$search_condition = "(
				evenement.idevenement LIKE ? OR 
				evenement.numero_courrier LIKE ? OR 
				evenement.date_evenement LIKE ? OR 
				evenement.userid LIKE ? OR 
				user.identification LIKE ? OR 
				user.avatar LIKE ? OR 
				evenement.action LIKE ? OR 
				evenement.destinataire LIKE ? OR 
				user.iduser LIKE ? OR 
				user.login LIKE ? OR 
				user.password LIKE ? OR 
				user.emailuser LIKE ? OR 
				user.date_deleted LIKE ? OR 
				user.is_deleted LIKE ? OR 
				user.user_role_id LIKE ? OR 
				user.niveau_imputation LIKE ? OR 
				user.direction LIKE ? OR 
				user.division LIKE ? OR 
				user.online LIKE ? OR 
				user2.iduser LIKE ? OR 
				user2.identification LIKE ? OR 
				user2.login LIKE ? OR 
				user2.password LIKE ? OR 
				user2.emailuser LIKE ? OR 
				user2.avatar LIKE ? OR 
				user2.date_deleted LIKE ? OR 
				user2.is_deleted LIKE ? OR 
				user2.user_role_id LIKE ? OR 
				user2.niveau_imputation LIKE ? OR 
				user2.direction LIKE ? OR 
				user2.division LIKE ? OR 
				user2.online LIKE ? OR 
				evenement.idcourrier LIKE ?
			)";
			$search_params = array(
				"%$text%","%$text%","%$text%","%$text%","%$text%","%$text%","%$text%","%$text%","%$text%","%$text%","%$text%","%$text%","%$text%","%$text%","%$text%","%$text%","%$text%","%$text%","%$text%","%$text%","%$text%","%$text%","%$text%","%$text%","%$text%","%$text%","%$text%","%$text%","%$text%","%$text%","%$text%","%$text%","%$text%"
			);
			//setting search conditions
			$db->where($search_condition, $search_params);
			 //template to use when ajax search
			$this->view->search_template = "evenement/search.php";
		}
		$db->join("user", "evenement.userid = user.iduser", "LEFT");
		$db->join("user AS user2", "evenement.destinataire = user2.iduser", "LEFT");
		if(!empty($request->orderby)){
			$orderby = $request->orderby;
			$ordertype = (!empty($request->ordertype) ? $request->ordertype : ORDER_TYPE);
			$db->orderBy($orderby, $ordertype);
		}
		else{
			$db->orderBy("idevenement", "DESC");
		}
		// idcourrier vient de l'URL : force en entier pour empecher toute injection SQL.
		$db->where(" idcourrier=" . (int) (isset($_GET['idcourrier']) ? $_GET['idcourrier'] : 0));
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
		$page_title = $this->view->page_title = get_lang('evenement');
		$this->view->report_filename = date('Y-m-d') . '-' . $page_title;
		$this->view->report_title = $page_title;
		$this->view->report_layout = "report_layout.php";
		$this->view->report_paper_size = "A4";
		$this->view->report_orientation = "portrait";
		$this->render_view("evenement/suivi.php", $data); //render the full page
	}
}
