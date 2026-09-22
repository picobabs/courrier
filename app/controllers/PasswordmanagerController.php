<?php 
class PasswordmanagerController extends BaseController{
	function __construct(){
		parent::__construct();
		$this->tablename = "user";
	}
	function index(){
		$this->render_view("passwordmanager/index.php", null, "info_layout.php");
	}
	function postresetlink(){
		if(!empty($this->post->email)){
			$email = $this->post->email;
			$tablename = $this->tablename;
			$db = $this->GetModel();
			$db->where ("emailuser", $email); //get user by email
			$user = $db->getOne($tablename, array('iduser', 'login'));
			if(!empty($user)){
				//Generate new password reset
				$password_reset_key = password_hash(random_str(), PASSWORD_DEFAULT);
				$date_to_expire = format_date("+1day");
				$modeldata = array(
					"password_reset_key" => hash_value($password_reset_key),
					"password_expire_date" => $date_to_expire
				);
				$user_id = $user['iduser'];
				$db->where ("iduser", $user_id);
				$db->update($tablename, $modeldata);
				$reset_link = SITE_ADDR."Passwordmanager/updatepassword?key=$password_reset_key";
				$sitename = SITE_NAME;
				$user_name = $user['login'];
				$mailtitle = "$sitename password reset";
				//Password reset html template
				$mailbody = file_get_contents(PAGES_DIR . "passwordmanager/password_reset_email_template.html");
				$mailbody = str_ireplace("{{username}}", $user_name, $mailbody);
				$mailbody = str_ireplace("{{link}}" , $reset_link,$mailbody);
				$mailbody = str_ireplace("{{sitename}}" , $sitename,$mailbody);
				$mailer = new Mailer;
				if($mailer->send_mail($email, $mailtitle, $mailbody) == true){
					$this->render_view("passwordmanager/password_reset_link_sent.php", $mailbody, "info_layout.php");
				}
				else{
					$msg = get_lang('erreur_lors_de_l_envoi_du_courrier_lectronique_veuillez_contacter_l_administrateur_syst_me_pour_plus_d_informations');
					$this->render_view("errors/error_general.php", $msg, "info_layout.php");
				}
			}
			else{
				$this->set_page_error(get_lang('l_adresse_e_mail_n_est_pas_enregistr_e_sur_le_syst_me'));
				$this->render_view("passwordmanager/index.php", null, "info_layout.php");
			}
		}
		else{
			$this->redirect("passwordmanager");
		}
	}
	function updatepassword(){
		$password_key = get_value("key"); //get password resek key from $_GET
		if(!empty($password_key)){
			$db = $this->GetModel();
			$tablename = $this->tablename;
			$hashed_key = hash_value($password_key);
			$db->where ("password_reset_key", $hashed_key);
			$date_to_expire = $db->getValue($tablename, "password_expire_date");
			if(!empty($date_to_expire)){
				$password_has_not_expired =  new DateTime($date_to_expire) > new DateTime();
				if($password_has_not_expired){
					if(!empty($_POST['password'])){
						$password = $_POST["password"]; 
						$cpassword = $_POST["cpassword"];
						if($password == $cpassword){
							$new_password_hash = password_hash($password , PASSWORD_DEFAULT);
							$new_date_to_expire = format_date("3 months");
							$new_password_data = array(
								"password" => $new_password_hash,
								"password_reset_key" => null,
								"password_expire_date" => $new_date_to_expire
							);
							$db->where ("password_reset_key", $hashed_key);
							$db->update($tablename, $new_password_data);
							if($db->getRowCount()){
								$this->render_view("passwordmanager/password_reset_completed.php", null, "info_layout.php");
							}
							else{
								$this->render_view("passwordmanager/password_reset_error.php", null, "info_layout.php");
							}
						}
						else{
							$this->set_page_error(get_lang('votre_confirmation_de_mot_de_passe_n_est_pas_coh_rente'));
							$this->render_view("passwordmanager/password_reset_form.php", null, "info_layout.php");
						}
					}
					else{
						$this->render_view("passwordmanager/password_reset_form.php", null, "info_layout.php");
					}
				}
				else{
					$this->set_page_error("Password reset key has expired. Please start a new password request");
					$this->render_view("passwordmanager/index.php", null, "info_layout.php");
				}
			}
			else{
				$this->render_view("errors/error_general.php", "Invalid Password Reset Key", "info_layout.php");
			}	
		}
		else{
			$this->redirect("passwordmanager");
		}
	}
}