<?php 
/**
 * User Page Controller
 * @category  Controller
 */
class UserController extends SecureController{
	function __construct(){
		parent::__construct();
		$this->tablename = "user";
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
		$fields = array("user.iduser", 
			"user.identification", 
			"roles.role_name AS roles_role_name", 
			"direction.direction AS direction_direction", 
			"division.division AS division_division", 
			"user.login", 
			"user.emailuser", 
			"user.avatar", 
			"niveau_imputation.niveau_imputation AS niveau_imputation_niveau_imputation", 
			"user.online");
		$pagination = $this->get_pagination(MAX_RECORD_COUNT); // get current pagination e.g array(page_number, page_limit)
		//search table record
		if(!empty($request->search)){
			$text = trim($request->search); 
			$search_condition = "(
				user.iduser LIKE ? OR 
				user.identification LIKE ? OR 
				roles.role_name LIKE ? OR 
				direction.direction LIKE ? OR 
				division.division LIKE ? OR 
				user.login LIKE ? OR 
				user.password LIKE ? OR 
				user.emailuser LIKE ? OR 
				user.avatar LIKE ? OR 
				user.date_deleted LIKE ? OR 
				user.is_deleted LIKE ? OR 
				user.user_role_id LIKE ? OR 
				user.niveau_imputation LIKE ? OR 
				user.direction LIKE ? OR 
				user.division LIKE ? OR 
				roles.role_id LIKE ? OR 
				direction.iddirection LIKE ? OR 
				division.iddivision LIKE ? OR 
				division.direction LIKE ? OR 
				niveau_imputation.idniveau LIKE ? OR 
				niveau_imputation.niveau_imputation LIKE ? OR 
				user.online LIKE ?
			)";
			$search_params = array(
				"%$text%","%$text%","%$text%","%$text%","%$text%","%$text%","%$text%","%$text%","%$text%","%$text%","%$text%","%$text%","%$text%","%$text%","%$text%","%$text%","%$text%","%$text%","%$text%","%$text%","%$text%","%$text%"
			);
			//setting search conditions
			$db->where($search_condition, $search_params);
			 //template to use when ajax search
			$this->view->search_template = "user/search.php";
		}
		$db->join("roles", "user.user_role_id = roles.role_id", "LEFT");
		$db->join("direction", "user.direction = direction.iddirection", "LEFT");
		$db->join("division", "user.division = division.iddivision", "LEFT");
		$db->join("niveau_imputation", "user.niveau_imputation = niveau_imputation.idniveau", "LEFT");
		if(!empty($request->orderby)){
			$orderby = $request->orderby;
			$ordertype = (!empty($request->ordertype) ? $request->ordertype : ORDER_TYPE);
			$db->orderBy($orderby, $ordertype);
		}
		else{
			$db->orderBy("user.iduser", ORDER_TYPE);
		}
		if($fieldname && est_nom_de_colonne($fieldname)){
			$db->where($fieldname , $fieldvalue); //filter by a single field name
		}
		if(!empty($request->user_niveau_imputation)){
			$val = $request->user_niveau_imputation;
			$db->where("user.niveau_imputation", $val , "=");
		}
		if(!empty($request->user_direction)){
			$val = $request->user_direction;
			$db->where("user.direction", $val , "=");
		}
		if(!empty($request->user_user_role_id)){
			$val = $request->user_user_role_id;
			$db->where("user.user_role_id", $val , "=");
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
		$page_title = $this->view->page_title = get_lang('user');
		$this->view->report_filename = date('Y-m-d') . '-' . $page_title;
		$this->view->report_title = $page_title;
		$this->view->report_layout = "report_layout.php";
		$this->view->report_paper_size = "A4";
		$this->view->report_orientation = "portrait";
		$this->render_view("user/list.php", $data); //render the full page
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
		$fields = array("user.iduser", 
			"user.identification", 
			"user.login", 
			"user.emailuser", 
			"user.user_role_id", 
			"user.niveau_imputation", 
			"user.direction", 
			"user.division", 
			"roles.role_id AS roles_role_id", 
			"roles.role_name AS roles_role_name", 
			"direction.iddirection AS direction_iddirection", 
			"direction.direction AS direction_direction", 
			"division.iddivision AS division_iddivision", 
			"division.direction AS division_direction", 
			"division.division AS division_division", 
			"niveau_imputation.idniveau AS niveau_imputation_idniveau", 
			"niveau_imputation.niveau_imputation AS niveau_imputation_niveau_imputation", 
			"user.online");
		if($value){
			$db->where($rec_id, urldecode($value)); //select record based on field name
		}
		else{
			$db->where("user.iduser", $rec_id);; //select record based on primary key
		}
		$db->join("roles", "user.user_role_id = roles.role_id", "LEFT ");
		$db->join("direction", "user.direction = direction.iddirection", "LEFT ");
		$db->join("division", "user.division = division.iddivision", "LEFT ");
		$db->join("niveau_imputation", "user.niveau_imputation = niveau_imputation.idniveau", "LEFT ");  
		$record = $db->getOne($tablename, $fields );
		if($record){
			$page_title = $this->view->page_title = get_lang('vue_user');
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
		return $this->render_view("user/view.php", $record);
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
			$fields = $this->fields = array("identification","login","password","emailuser","avatar","user_role_id","niveau_imputation","direction","division");
			$postdata = $this->format_request_data($formdata);
			$cpassword = $postdata['confirm_password'];
			$password = $postdata['password'];
			if($cpassword != $password){
				$this->view->page_error[] = get_lang('votre_confirmation_de_mot_de_passe_n_est_pas_coh_rente');
			}
			$this->rules_array = array(
				'identification' => 'required',
				'login' => 'required',
				'password' => 'required',
				'emailuser' => 'required|valid_email',
				'avatar' => 'required',
				'user_role_id' => 'required',
			);
			$this->sanitize_array = array(
				'identification' => 'sanitize_string',
				'login' => 'sanitize_string',
				'emailuser' => 'sanitize_string',
				'avatar' => 'sanitize_string',
				'user_role_id' => 'sanitize_string',
				'niveau_imputation' => 'sanitize_string',
				'direction' => 'sanitize_string',
				'division' => 'sanitize_string',
			);
			$this->filter_vals = true; //set whether to remove empty fields
			$modeldata = $this->modeldata = $this->validate_form($postdata);
			$password_text = $modeldata['password'];
			//update modeldata with the password hash
			$modeldata['password'] = $this->modeldata['password'] = password_hash($password_text , PASSWORD_DEFAULT);
			//Check if Duplicate Record Already Exit In The Database
			$db->where("login", $modeldata['login']);
			if($db->has($tablename)){
				$this->view->page_error[] = $modeldata['login'].get_lang('_existe_d_j_');
			}
			//Check if Duplicate Record Already Exit In The Database
			$db->where("emailuser", $modeldata['emailuser']);
			if($db->has($tablename)){
				$this->view->page_error[] = $modeldata['emailuser'].get_lang('_existe_d_j_');
			} 
			if($this->validated()){
				// Colonnes obligatoires en base mais facultatives a la saisie : un
				// Directeur General ou un Secretaire General n'est rattache ni a une
				// direction ni a une division. On enregistre 0, qui vaut "non
				// renseigne", au lieu d'imposer un choix qui n'a pas de sens.
				// 'online' est une colonne technique absente du formulaire.
				foreach (array('niveau_imputation' => 0, 'direction' => 0, 'division' => 0, 'online' => 0) as $col => $defaut) {
					if (!isset($modeldata[$col]) || $modeldata[$col] === '') { $modeldata[$col] = $defaut; }
				}
				$rec_id = $this->rec_id = $db->insert($tablename, $modeldata);
				if($rec_id){
					$this->set_flash_msg(get_lang('enregistrement_ajout_avec_succ_s'), "success");
					return	$this->redirect("user");
				}
				else{
					$this->set_page_error();
				}
			}
		}
		$page_title = $this->view->page_title = get_lang('ajouter_un_nouveau');
		$this->render_view("user/add.php");
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
		$fields = $this->fields = array("iduser","identification","login","avatar","niveau_imputation","user_role_id","direction","division");
		if($formdata){
			$postdata = $this->format_request_data($formdata);
			$this->rules_array = array(
				'identification' => 'required',
				'login' => 'required',
				'avatar' => 'required',
			);
			$this->sanitize_array = array(
				'identification' => 'sanitize_string',
				'login' => 'sanitize_string',
				'avatar' => 'sanitize_string',
				'niveau_imputation' => 'sanitize_string',
				'user_role_id' => 'sanitize_string',
				'direction' => 'sanitize_string',
				'division' => 'sanitize_string',
			);
			$this->filter_vals = true; //set whether to remove empty fields
			// Un champ de rattachement laisse vide vaut 0, et non "inchange" :
			// c'est ce qui permet de detacher un utilisateur de sa direction
			// lorsqu'il passe Directeur General ou Secretaire General.
			foreach (array('direction', 'division') as $col) {
				if (isset($postdata[$col]) && $postdata[$col] === '') { $postdata[$col] = 0; }
			}
			$modeldata = $this->modeldata = $this->validate_form($postdata);
			//Check if Duplicate Record Already Exit In The Database
			if(isset($modeldata['login'])){
				$db->where("login", $modeldata['login'])->where("iduser", $rec_id, "!=");
				if($db->has($tablename)){
					$this->view->page_error[] = $modeldata['login'].get_lang('_existe_d_j_');
				}
			} 
			if($this->validated()){
				$db->where("user.iduser", $rec_id);;
				$bool = $db->update($tablename, $modeldata);
				$numRows = $db->getRowCount(); //number of affected rows. 0 = no record field updated
				if($bool && $numRows){
					$this->set_flash_msg(get_lang('enregistrement_mis_jour_avec_succ_s'), "success");
					return $this->redirect("user");
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
						return	$this->redirect("user");
					}
				}
			}
		}
		$db->where("user.iduser", $rec_id);;
		$data = $db->getOne($tablename, $fields);
		$page_title = $this->view->page_title = get_lang('modifier');
		if(!$data){
			$this->set_page_error();
		}
		return $this->render_view("user/edit.php", $data);
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
		$fields = $this->fields = array("iduser","identification","login","avatar","niveau_imputation");
		$page_error = null;
		if($formdata){
			$postdata = array();
			$fieldname = $formdata['name'];
			$fieldvalue = $formdata['value'];
			$postdata[$fieldname] = $fieldvalue;
			$postdata = $this->format_request_data($postdata);
			$this->rules_array = array(
				'identification' => 'required',
				'login' => 'required',
				'avatar' => 'required',
			);
			$this->sanitize_array = array(
				'identification' => 'sanitize_string',
				'login' => 'sanitize_string',
				'avatar' => 'sanitize_string',
				'niveau_imputation' => 'sanitize_string',
			);
			$this->filter_rules = true; //filter validation rules by excluding fields not in the formdata
			$modeldata = $this->modeldata = $this->validate_form($postdata);
			//Check if Duplicate Record Already Exit In The Database
			if(isset($modeldata['login'])){
				$db->where("login", $modeldata['login'])->where("iduser", $rec_id, "!=");
				if($db->has($tablename)){
					$this->view->page_error[] = $modeldata['login'].get_lang('_existe_d_j_');
				}
			} 
			if($this->validated()){
				$db->where("user.iduser", $rec_id);;
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
		$db->where("user.iduser", $arr_rec_id, "in");
		$bool = $db->delete($tablename);
		if($bool){
			$this->set_flash_msg(get_lang('enregistrement_supprim_avec_succ_s'), "success");
		}
		elseif($db->getLastError()){
			$page_error = $db->getLastError();
			$this->set_flash_msg($page_error, "danger");
		}
		return	$this->redirect("user");
	}
}
