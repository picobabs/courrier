<?php 
/**
 * Index Page Controller
 * @category  Controller
 */
class IndexController extends BaseController{
	function __construct(){
		parent::__construct(); 
		$this->tablename = "user";
	}
	/**
     * Index Action 
     * @return null
     */
	function index(){
		if(user_login_status() == true){
			$this->redirect(HOME_PAGE);
		}
		else{
			$this->render_view("index/index.php");
		}
	}
	private function login_user($username , $password_text, $rememberme = false){
		$db = $this->GetModel();
		$username = safe_sanitize_string($username);
		$db->where("login", $username)->orWhere("emailuser", $username);
		$tablename = $this->tablename;
		$user = $db->getOne($tablename);
		if(!empty($user)){
			//Verify User Password Text With DB Password Hash Value.
			//Uses PHP password_verify() function with default options
			$password_hash = $user['password'];
			$this->modeldata['password'] = $password_hash; //update the modeldata with the password hash
			if(password_verify($password_text,$password_hash)){
        		unset($user['password']); //Remove user password. No need to store it in the session
				// Change l'identifiant de session au moment de la connexion : sans cela,
				// un identifiant impose a la victime avant sa connexion reste valable
				// apres celle-ci (fixation de session).
				session_regenerate_id(true);
				set_session("user_data", $user); // Set active user data in a sessions
				//if Remeber Me, Set Cookie
				if($rememberme == true){
					$sessionkey = bin2hex(random_bytes(32)); // cle aleatoire sure (64 caracteres)
					//Update user session info in database with the session key
					$db->where("iduser", $user['iduser']);
					$res = $db->update($tablename, array("login_session_key" => hash_value($sessionkey)));
					if(!empty($res)){
						set_cookie("login_session_key", $sessionkey); // save user login_session_key in a Cookie
					}
				}
				else{
					clear_cookie("login_session_key");// Clear any previous set cookie
				}
		# Statement to execute after user login
		$today  = datetime_now();
$action = "Connexion à la plateforme SENCOURRIER";  
$table_data = array(
    "userid" => get_active_user('iduser'),
    "action" => $action
);
$db->insert("evenement", $table_data);
$useronline = 1;
$table_data = array(
    "online" => $useronline
);
$db->where("iduser", get_active_user('iduser'));
$bool = $db->update("user", $table_data);
		# End of after login statement
				$redirect_url = get_session("login_redirect_url");// Redirect to user active page
				if(!empty($redirect_url)){
					clear_session("login_redirect_url");
					return $this->redirect($redirect_url);
				}
				else{
					return $this->redirect(HOME_PAGE);
				}
			}
			else{
				//password is not correct
				return $this->login_fail(get_lang('nom_d_utilisateur_ou_mot_de_passe_incorrect_'));
			}
		}
		else{
			//user is not registered
			return $this->login_fail(get_lang('nom_d_utilisateur_ou_mot_de_passe_incorrect_'));
		}
	}
	/**
     * Display login page with custom message when login fails
     * @return BaseView
     */
	private function login_fail($page_error = null){
		$this->set_page_error($page_error);
		$this->render_view("index/login.php");
	}
	/**
     * Login Action
     * If Not $_POST Request, Display Login Form View
     * @return View
     */
	function login($formdata = null){
		if($formdata){
			$modeldata = $this->modeldata = $formdata;
			$username = trim($modeldata['username']);
			$password = $modeldata['password'];
			$rememberme = (!empty($modeldata['rememberme']) ? $modeldata['rememberme'] : false);
			$this->login_user($username, $password, $rememberme);
		}
		else{
			$this->set_page_error(get_lang('requ_te_invalide'));
			$this->render_view("index/login.php");
		}
	}
	/**
     * Logout Action
     * Destroy All Sessions And Cookies
     * @return View
     */
	function logout($arg=null){
		# Statement to execute before user login
		$db         = $this->GetModel();
$useronline = 0;
$table_data = array(
    "online" => $useronline
);
$db->where("iduser", get_active_user('iduser'));
$bool = $db->update("user", $table_data);
$today  = datetime_now();
$action = "Déconnexion à la plateforme SENCOURRIER";  
$table_data = array(
    "userid" => get_active_user('iduser'),
    "action" => $action
);
$db->insert("evenement", $table_data);
		# End of before login statement
		Csrf::cross_check();
		session_destroy();
		clear_cookie("login_session_key");
		$this->redirect("");
	}
	/**
     * Change User Language
     * @return null
     */
	function change_language($lang){
		set_cookie('lang', $lang);
		$this->redirect(DEFAULT_PAGE);
	}
}
