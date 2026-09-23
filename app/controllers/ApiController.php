<?php

/**
 * Info Contoller Class
 * @category  Controller
 */

class ApiController extends SecureController
{

	/**
	 * call model action to retrieve data
	 * @return json data
	 */

	function json($action, $arg1 = null, $arg2 = null)
	{
		// Seules les methodes de donnees declarees dans SharedController sont
		// appelables. Auparavant, n'importe quelle methode publique heritee de
		// BaseController (render_view, redirect...) pouvait etre invoquee avec
		// des arguments choisis dans l'URL.
		$autorisees = array_diff(get_class_methods('SharedController'), get_class_methods('BaseController'));
		if (!is_string($action) || !in_array($action, $autorisees, true)) {
			render_error("Action inconnue", 404);
			return;
		}
		$model = new SharedController;
		$args = array($arg1, $arg2);
		$data = call_user_func_array(array($model, $action), $args);
		render_json($data);
	}
	
	
	
	
	 function niveauUser()
    {
	    if(!empty($_POST))
	    { // we check if the post variable is not empty.
			$unid = $_POST['varid']; 
			
			$db = $this->GetModel();
			$db->where("iduser", $unid);
			// Ne jamais renvoyer toutes les colonnes : la table user contient le
			// hash du mot de passe, la cle de session et la cle de reinitialisation.
			$done = $db->getOne("user", array("iduser","identification","niveau_imputation","direction","division"));

			if($done) { // checks if it was successful or not
				$res['success'] = true;
				$res['msg'] = "Action Successfully.";
				$res['data'] = $done;
			} else{
				$res['success'] = false;
				$res['msg'] = "Error getting User.";
			}
			render_json($res); // we return result of the update query in JSON format as expected.
		} else {
            $res['success'] = false;
            $res['msg'] = "No post data detected.";
            
            render_json($res); // here we return access denied if Csrf fails
        }
    }
	

	
	
	
	
	
	
	
	
	
	
}
