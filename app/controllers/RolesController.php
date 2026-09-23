<?php 
/**
 * Roles Page Controller
 * @category  Controller
 */
class RolesController extends SecureController{
	function __construct(){
		parent::__construct();
		$this->tablename = "roles";
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
		$fields = array("role_id", 
			"role_name");
		$pagination = $this->get_pagination(MAX_RECORD_COUNT); // get current pagination e.g array(page_number, page_limit)
		//search table record
		if(!empty($request->search)){
			$text = trim($request->search); 
			$search_condition = "(
				roles.role_id LIKE ? OR 
				roles.role_name LIKE ?
			)";
			$search_params = array(
				"%$text%","%$text%"
			);
			//setting search conditions
			$db->where($search_condition, $search_params);
			 //template to use when ajax search
			$this->view->search_template = "roles/search.php";
		}
		if(!empty($request->orderby)){
			$orderby = $request->orderby;
			$ordertype = (!empty($request->ordertype) ? $request->ordertype : ORDER_TYPE);
			$db->orderBy($orderby, $ordertype);
		}
		else{
			$db->orderBy("roles.role_id", ORDER_TYPE);
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
		$data = new stdClass;
		$data->records = $records;
		$data->record_count = $records_count;
		$data->total_records = $total_records;
		$data->total_page = $total_pages;
		if($db->getLastError()){
			$this->set_page_error();
		}
		$page_title = $this->view->page_title = get_lang('roles');
		$this->render_view("roles/list.php", $data); //render the full page
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
		$fields = array("role_id", 
			"role_name");
		if($value && est_nom_de_colonne($rec_id)){
			$db->where($rec_id, urldecode($value)); //select record based on field name
		}
		else{
			$db->where("roles.role_id", $rec_id);; //select record based on primary key
		}
		$record = $db->getOne($tablename, $fields );
		if($record){
			$page_title = $this->view->page_title = get_lang('vue_roles');
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
		return $this->render_view("roles/view.php", $record);
	}
	/**
     * Insert new record to the database table
	 * @param $formdata array() from $_POST
     * @return BaseView
     */
	/**
     * Creer un nouveau profil en recopiant integralement les droits d'un profil
     * existant : permissions d'ecran, acces en affichage, acces en selection et
     * types de courrier visibles.
     * Sans cela, un profil cree depuis l'ecran habituel naît sans aucun droit et
     * doit etre reparametre ligne par ligne dans quatre ecrans differents.
     * @param $formdata array() from $_POST
     * @return BaseView
     */
	function dupliquer($formdata = null){
		$db = $this->GetModel();
		$tablename = $this->tablename;
		if($formdata){
			$postdata = $this->format_request_data($formdata);
			$this->fields = array("role_name", "role_modele");
			$this->rules_array = array(
				'role_name' => 'required',
				'role_modele' => 'required|numeric',
			);
			$this->sanitize_array = array(
				'role_name' => 'sanitize_string',
			);
			$this->filter_vals = true;
			$modeldata = $this->modeldata = $this->validate_form($postdata);

			$nom = isset($modeldata['role_name']) ? trim($modeldata['role_name']) : '';
			$modele = isset($modeldata['role_modele']) ? (int) $modeldata['role_modele'] : 0;

			// Le profil modele doit exister
			$db->where("role_id", $modele);
			if(!$db->has($tablename)){
				$this->view->page_error[] = get_lang('requ_te_invalide');
			}
			// Refuser un libelle deja utilise
			$db->where("role_name", $nom);
			if($db->has($tablename)){
				$this->view->page_error[] = $nom.get_lang('_existe_d_j_');
			}

			if($this->validated()){
				$nouveau = $db->insert($tablename, array('role_name' => $nom));
				if($nouveau){
					// Recopie des droits. Les cles primaires de ces quatre tables sont
					// auto-incrementees : on ne les reprend pas.
					$db->rawQuery("INSERT INTO role_permissions (role_id, page_name, action_name) SELECT ?, page_name, action_name FROM role_permissions WHERE role_id = ?", array($nouveau, $modele));
					$db->rawQuery("INSERT INTO droit_lister (roles, etat_courrier) SELECT ?, etat_courrier FROM droit_lister WHERE roles = ?", array($nouveau, $modele));
					$db->rawQuery("INSERT INTO droit_select (roles, etat_courrier) SELECT ?, etat_courrier FROM droit_select WHERE roles = ?", array($nouveau, $modele));
					$db->rawQuery("INSERT INTO roles_courrier (roles, etat) SELECT ?, etat FROM roles_courrier WHERE roles = ?", array($nouveau, $modele));

					$this->set_flash_msg(get_lang('enregistrement_ajout_avec_succ_s'), "success");
					return $this->redirect("roles");
				}
				else{
					$this->set_page_error();
				}
			}
		}
		$this->view->page_title = "Dupliquer un profil";
		$this->render_view("roles/dupliquer.php");
	}
	function add($formdata = null){
		if($formdata){
			$db = $this->GetModel();
			$tablename = $this->tablename;
			$request = $this->request;
			//fillable fields
			$fields = $this->fields = array("role_name");
			$postdata = $this->format_request_data($formdata);
			$this->rules_array = array(
				'role_name' => 'required',
			);
			$this->sanitize_array = array(
				'role_name' => 'sanitize_string',
			);
			$this->filter_vals = true; //set whether to remove empty fields
			$modeldata = $this->modeldata = $this->validate_form($postdata);
			if($this->validated()){
				$rec_id = $this->rec_id = $db->insert($tablename, $modeldata);
				if($rec_id){
					// Un profil cree sans aucun droit est inutilisable : menu vide et 403
					// sur chaque ecran, sans moyen de se debloquer. On lui attribue donc
					// le socle par defaut, que l'administrateur peut ensuite completer ou
					// restreindre depuis Parametrage.
					$nb_socle = 0;
					if (defined('ECRANS_PAR_DEFAUT') && ECRANS_PAR_DEFAUT !== '') {
						foreach (explode(',', ECRANS_PAR_DEFAUT) as $ecran) {
							$ecran = trim($ecran);
							if ($ecran === '' || strpos($ecran, '/') === false) { continue; }
							list($page_name, $action_name) = explode('/', $ecran, 2);
							$db->insert('role_permissions', array(
								'role_id'     => $rec_id,
								'page_name'   => $page_name,
								'action_name' => $action_name,
							));
							$nb_socle++;
						}
					}
					$this->set_flash_msg(get_lang('enregistrement_ajout_avec_succ_s')
						. ($nb_socle ? " Le profil recoit $nb_socle ecrans par defaut, a ajuster dans Parametrage." : ""), "success");
					return	$this->redirect("roles");
				}
				else{
					$this->set_page_error();
				}
			}
		}
		$page_title = $this->view->page_title = get_lang('ajouter_un_nouveau');
		$this->render_view("roles/add.php");
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
		$fields = $this->fields = array("role_id","role_name");
		if($formdata){
			$postdata = $this->format_request_data($formdata);
			$this->rules_array = array(
				'role_name' => 'required',
			);
			$this->sanitize_array = array(
				'role_name' => 'sanitize_string',
			);
			$this->filter_vals = true; //set whether to remove empty fields
			$modeldata = $this->modeldata = $this->validate_form($postdata);
			if($this->validated()){
				$db->where("roles.role_id", $rec_id);;
				$bool = $db->update($tablename, $modeldata);
				$numRows = $db->getRowCount(); //number of affected rows. 0 = no record field updated
				if($bool && $numRows){
					$this->set_flash_msg(get_lang('enregistrement_mis_jour_avec_succ_s'), "success");
					return $this->redirect("roles");
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
						return	$this->redirect("roles");
					}
				}
			}
		}
		$db->where("roles.role_id", $rec_id);;
		$data = $db->getOne($tablename, $fields);
		$page_title = $this->view->page_title = get_lang('modifier');
		if(!$data){
			$this->set_page_error();
		}
		return $this->render_view("roles/edit.php", $data);
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
		$fields = $this->fields = array("role_id","role_name");
		$page_error = null;
		if($formdata){
			$postdata = array();
			$fieldname = $formdata['name'];
			$fieldvalue = $formdata['value'];
			$postdata[$fieldname] = $fieldvalue;
			$postdata = $this->format_request_data($postdata);
			$this->rules_array = array(
				'role_name' => 'required',
			);
			$this->sanitize_array = array(
				'role_name' => 'sanitize_string',
			);
			$this->filter_rules = true; //filter validation rules by excluding fields not in the formdata
			$modeldata = $this->modeldata = $this->validate_form($postdata);
			if($this->validated()){
				$db->where("roles.role_id", $rec_id);;
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
		$db->where("roles.role_id", $arr_rec_id, "in");
		$bool = $db->delete($tablename);
		if($bool){
			$this->set_flash_msg(get_lang('enregistrement_supprim_avec_succ_s'), "success");
		}
		elseif($db->getLastError()){
			$page_error = $db->getLastError();
			$this->set_flash_msg($page_error, "danger");
		}
		return	$this->redirect("roles");
	}
}
