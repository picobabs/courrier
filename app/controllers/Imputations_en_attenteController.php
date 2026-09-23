<?php 
/**
 * Imputations_en_attente Page Controller
 * @category  Controller
 */
class Imputations_en_attenteController extends SecureController{
	function __construct(){
		parent::__construct();
		$this->tablename = "imputations_en_attente";
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
		$fields = array("imputations_en_attente.idimputation", 
			"expediteur.expediteur AS expediteur_expediteur", 
			"courrier.intitule AS courrier_intitule", 
			"imputations_en_attente.date_imputation", 
			"imputations_en_attente.date_limite_traite", 
			"imputations_en_attente.instruction", 
			"user.identification AS user_identification", 
			"user.avatar AS user_avatar");
		$pagination = $this->get_pagination(MAX_RECORD_COUNT); // get current pagination e.g array(page_number, page_limit)
		//search table record
		if(!empty($request->search)){
			$text = trim($request->search); 
			$search_condition = "(
				imputations_en_attente.idimputation LIKE ? OR 
				expediteur.expediteur LIKE ? OR 
				courrier.intitule LIKE ? OR 
				imputations_en_attente.date_imputation LIKE ? OR 
				imputations_en_attente.date_limite_traite LIKE ? OR 
				imputations_en_attente.instruction LIKE ? OR 
				imputations_en_attente.etat_traitement LIKE ? OR 
				imputations_en_attente.idcourrier LIKE ? OR 
				imputations_en_attente.iduser LIKE ? OR 
				imputations_en_attente.ftraite LIKE ? OR 
				courrier.idcourrier LIKE ? OR 
				courrier.date_courrier LIKE ? OR 
				courrier.date_reception LIKE ? OR 
				courrier.expediteur LIKE ? OR 
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
				expediteur.cellulaire LIKE ? OR 
				user.iduser LIKE ? OR 
				user.identification LIKE ? OR 
				user.login LIKE ? OR 
				user.password LIKE ? OR 
				user.emailuser LIKE ? OR 
				user.avatar LIKE ? OR 
				user.date_deleted LIKE ? OR 
				user.is_deleted LIKE ? OR 
				user.user_role_id LIKE ?
			)";
			$search_params = array(
				"%$text%","%$text%","%$text%","%$text%","%$text%","%$text%","%$text%","%$text%","%$text%","%$text%","%$text%","%$text%","%$text%","%$text%","%$text%","%$text%","%$text%","%$text%","%$text%","%$text%","%$text%","%$text%","%$text%","%$text%","%$text%","%$text%","%$text%","%$text%","%$text%","%$text%","%$text%","%$text%","%$text%","%$text%","%$text%"
			);
			//setting search conditions
			$db->where($search_condition, $search_params);
			 //template to use when ajax search
			$this->view->search_template = "imputations_en_attente/search.php";
		}
		$db->join("courrier", "imputations_en_attente.idcourrier = courrier.idcourrier", "LEFT");
		$db->join("expediteur", "courrier.expediteur = expediteur.idexpediteur", "INNER");
		$db->join("user", "imputations_en_attente.iduser = user.iduser", "INNER");
		// Chacun ne voit que les imputations qui lui sont adressees. Sans ce
		// filtre, la vue SQL renvoie celles de toute l'agence : l'ecran perd
		// son sens et affiche le meme total a tout le monde.
		if (!utilisateur_voit_toutes_les_imputations()) {
			$db->where("imputations_en_attente.iduser", USER_ID);
		}
		if(!empty($request->orderby)){
			$orderby = $request->orderby;
			$ordertype = (!empty($request->ordertype) ? $request->ordertype : ORDER_TYPE);
			$db->orderBy($orderby, $ordertype);
		}
		else{
			$db->orderBy("imputations_en_attente.idimputation", ORDER_TYPE);
		}
		if($fieldname && est_nom_de_colonne($fieldname)){
			$db->where($fieldname , $fieldvalue); //filter by a single field name
		}
		$tc = $db->withTotalCount();
		$records = $db->get($tablename, $pagination, $fields);
		$records_count = count($records);
		$total_records = intval($tc->totalCount);
		$page_limit = $pagination[1];
		$total_pages = ceil($total_records / $page_limit);
		if(	!empty($records)){
			foreach($records as &$record){
				$record['date_imputation'] = format_date($record['date_imputation'],'d-m-Y');
$record['date_limite_traite'] = format_date($record['date_limite_traite'],'d-m-Y');
			}
		}
		$data = new stdClass;
		$data->records = $records;
		$data->record_count = $records_count;
		$data->total_records = $total_records;
		$data->total_page = $total_pages;
		if($db->getLastError()){
			$this->set_page_error();
		}
		$page_title = $this->view->page_title = get_lang('imputations_en_attente');
		$this->render_view("imputations_en_attente/list.php", $data); //render the full page
	}
// No View Function Generated Because No Field is Defined as the Primary Key on the Database Table
}
