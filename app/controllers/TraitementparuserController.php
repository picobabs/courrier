<?php 
/**
 * Traitementparuser Page Controller
 * @category  Controller
 */
class TraitementparuserController extends SecureController{
	function __construct(){
		parent::__construct();
		$this->tablename = "traitementparuser";
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
		$fields = array("traitementparuser.user", 
			"user.avatar AS user_avatar", 
			"traitementparuser.Nouvelle_imputation", 
			"traitementparuser.Traitement_en_cours", 
			"traitementparuser.Imputation_retournee", 
			"nouvelle_imputation+traitement_en_cours+imputation_retournee AS Total");
		$pagination = $this->get_pagination(MAX_RECORD_COUNT); // get current pagination e.g array(page_number, page_limit)
		//search table record
		if(!empty($request->search)){
			$text = trim($request->search); 
			$search_condition = "(
				traitementparuser.user LIKE ? OR 
				user.avatar LIKE ? OR 
				traitementparuser.Nouvelle_imputation LIKE ? OR 
				traitementparuser.Traitement_en_cours LIKE ? OR 
				traitementparuser.Imputation_retournee LIKE ? OR 
				traitementparuser.Total LIKE ? OR 
				traitementparuser.iduser LIKE ? OR 
				user.iduser LIKE ? OR 
				user.identification LIKE ? OR 
				user.login LIKE ? OR 
				user.password LIKE ? OR 
				user.emailuser LIKE ? OR 
				user.date_deleted LIKE ? OR 
				user.is_deleted LIKE ? OR 
				user.user_role_id LIKE ?
			)";
			$search_params = array(
				"%$text%","%$text%","%$text%","%$text%","%$text%","%$text%","%$text%","%$text%","%$text%","%$text%","%$text%","%$text%","%$text%","%$text%","%$text%"
			);
			//setting search conditions
			$db->where($search_condition, $search_params);
			 //template to use when ajax search
			$this->view->search_template = "traitementparuser/search.php";
		}
		$db->join("user", "traitementparuser.iduser = user.iduser", "LEFT");
		if(!empty($request->orderby)){
			$orderby = $request->orderby;
			$ordertype = (!empty($request->ordertype) ? $request->ordertype : ORDER_TYPE);
			$db->orderBy($orderby, $ordertype);
		}
		else{
			$db->orderBy("traitementparuser.user", ORDER_TYPE);
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
		$page_title = $this->view->page_title = get_lang('traitementparuser');
		$this->render_view("traitementparuser/list.php", $data); //render the full page
	}
// No View Function Generated Because No Field is Defined as the Primary Key on the Database Table
}
