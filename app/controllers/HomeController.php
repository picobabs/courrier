<?php 

/**
 * Home Page Controller
 * @category  Controller
 */
class HomeController extends SecureController{
	/**
     * Index Action
     * @return View
     */
	function index(){
		// Le tableau de bord d'administration etait reserve au profil numero 1.
		// Tout autre profil administrateur cree ensuite retombait sur l'accueil
		// standard, sans que rien ne l'explique.
		if(utilisateur_est_administrateur()){
			$this->render_view("home/administrator.php" , null , "main_layout.php");
		}
		else{
			$this->render_view("home/index.php" , null , "main_layout.php");
		}
	}
}
