<?php 
/**
 * Account Page Controller
 * @category  Controller
 */
class AccountController extends SecureController{
	function __construct(){
		parent::__construct(); 
		$this->tablename = "user";
	}
	/**
		* Index Action
		* @return null
		*/
	function index(){
		$db = $this->GetModel();
		$rec_id = $this->rec_id = USER_ID; //get current user id from session
		$db->where ("iduser", $rec_id);
		$tablename = $this->tablename;
		$fields = array("user.iduser", 
			"user.identification", 
			"user.login", 
			"user.emailuser", 
			"roles.role_name AS roles_role_name", 
			"niveau_imputation.niveau_imputation AS niveau_imputation_niveau_imputation", 
			"direction.direction AS direction_direction", 
			"division.division AS division_division");
		$db->join("roles", "user.user_role_id = roles.role_id", "LEFT ");
		$db->join("niveau_imputation", "user.niveau_imputation = niveau_imputation.idniveau", "LEFT ");
		$db->join("direction", "user.direction = direction.iddirection", "LEFT ");
		$db->join("division", "user.division = division.iddivision", "LEFT ");
		$user = $db->getOne($tablename , $fields);
		if(!empty($user)){
			$page_title = $this->view->page_title = get_lang('mon_compte');
			$this->render_view("account/view.php", $user);
		}
		else{
			$this->set_page_error();
			$this->render_view("account/view.php");
		}
	}
	/**
     * Update user account record with formdata
	 * @param $formdata array() from $_POST
     * @return array
     */
	function edit($formdata = null){
		$request = $this->request;
		$db = $this->GetModel();
		$rec_id = $this->rec_id = USER_ID;
		$tablename = $this->tablename;
		 //editable fields
		$fields = $this->fields = array("iduser","identification","login","avatar","niveau_imputation");
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
			);
			// Retire les champs laisses vides avant l'enregistrement.
			// Sans cela, un champ vide comme "niveau_imputation" (colonne INT NOT NULL)
			// est envoye a MySQL sous la forme d'une chaine vide, ce que le mode strict
			// refuse : "Incorrect integer value: '' for column 'niveau_imputation'".
			// Tous les autres controleurs de l'application font deja cet appel ;
			// il manquait uniquement ici.
			$this->filter_vals = true; //set whether to remove empty fields
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
					$db->where ("iduser", $rec_id);
					$user = $db->getOne($tablename , "*");
					set_session("user_data", $user);// update session with new user data
					return $this->redirect("account");
				}
				else{
					if($db->getLastError()){
						$this->set_page_error();
					}
					elseif(!$numRows){
						//not an error, but no record was updated
						$this->set_flash_msg(get_lang('aucun_enregistrement_mis_jour'), "warning");
						return	$this->redirect("account");
					}
				}
			}
		}
		$db->where("user.iduser", $rec_id);;
		$data = $db->getOne($tablename, $fields);
		$page_title = $this->view->page_title = get_lang('mon_compte');
		if(!$data){
			$this->set_page_error();
		}
		return $this->render_view("account/edit.php", $data);
	}
	/**
     * Change account email
     * @return BaseView
     */
	function change_email($formdata = null){
		if($formdata){
			// Le champ du formulaire s'appelle "email" (voir account/change_email.php),
			// pas "emailuser". La lecture d'origine renvoyait donc toujours null :
			// aucun changement n'etait possible, et l'adresse enregistree etait
			// remplacee par une chaine vide.
			$email = trim((string) (isset($formdata['email']) ? $formdata['email'] : ''));

			if($email === '' || !filter_var($email, FILTER_VALIDATE_EMAIL)){
				$this->set_page_error(get_lang('email_non_modifi_'));
				return $this->render_view("account/change_email.php");
			}

			$db = $this->GetModel();
			$rec_id = $this->rec_id = USER_ID; //get current user id from session
			$tablename = $this->tablename;

			// Refuser une adresse deja utilisee par un autre compte : la connexion
			// accepte l'email comme identifiant, un doublon rendrait le login ambigu.
			$db->where("emailuser", $email)->where("iduser", $rec_id, "!=");
			if($db->has($tablename)){
				$this->set_page_error(get_lang('email_non_modifi_'));
				return $this->render_view("account/change_email.php");
			}

			$db->where("iduser", $rec_id);
			$result = $db->update($tablename, array('emailuser' => $email));
			if($result){
				// Mettre la session a jour, sinon l'ancienne adresse reste affichee
				// jusqu'a la prochaine reconnexion.
				update_session("user_data", "emailuser", $email);
				$this->set_flash_msg(get_lang('enregistrement_mis_jour_avec_succ_s'), "success");
				return $this->redirect("account");
			}
			else{
				$this->set_page_error(get_lang('email_non_modifi_'));
			}
		}
		return $this->render_view("account/change_email.php");
	}
}
