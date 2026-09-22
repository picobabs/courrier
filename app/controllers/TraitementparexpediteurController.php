<?php 
/**
 * Traitementparexpediteur Page Controller
 * @category  Controller
 */
class TraitementparexpediteurController extends SecureController{
	function __construct(){
		parent::__construct();
		$this->tablename = "traitementparexpediteur";
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
		$fields = array("expediteur", 
			"Nouvelle_imputation", 
			"Traitement_en_cours", 
			"Imputation_retournee", 
			"nouvelle_imputation+traitement_en_cours+imputation_retournee AS Total");
		$pagination = $this->get_pagination(MAX_RECORD_COUNT); // get current pagination e.g array(page_number, page_limit)
		//search table record
		if(!empty($request->search)){
			$text = trim($request->search); 
			$search_condition = "(
				traitementparexpediteur.expediteur LIKE ? OR 
				traitementparexpediteur.Nouvelle_imputation LIKE ? OR 
				traitementparexpediteur.Traitement_en_cours LIKE ? OR 
				traitementparexpediteur.Imputation_retournee LIKE ? OR 
				nouvelle_imputation+traitement_en_cours+imputation_retournee LIKE ?
			)";
			$search_params = array(
				"%$text%","%$text%","%$text%","%$text%","%$text%"
			);
			//setting search conditions
			$db->where($search_condition, $search_params);
			 //template to use when ajax search
			$this->view->search_template = "traitementparexpediteur/search.php";
		}
		if(!empty($request->orderby)){
			$orderby = $request->orderby;
			$ordertype = (!empty($request->ordertype) ? $request->ordertype : ORDER_TYPE);
			$db->orderBy($orderby, $ordertype);
		}
		else{
			$db->orderBy("traitementparexpediteur.expediteur", ORDER_TYPE);
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
		$page_title = $this->view->page_title = get_lang('traitemparexpediteur');
		$this->render_view("traitementparexpediteur/list.php", $data); //render the full page
	}
// No View Function Generated Because No Field is Defined as the Primary Key on the Database Table
}
