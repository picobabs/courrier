<?php
/**
 * Page Access Control
 * @category  RBAC Helper
 */
defined('ROOT') or exit('No direct script access allowed');
class ACL
{
	
	/**
	 * Array of user roles and page access 
	 * Use "*" to grant all access right to particular user role
	 * @var array
	 */
	public static $role_pages = array();

	/**
	 * Current user role name
	 * @var string
	 */
	public static $user_role = null;

	/**
	 * pages to exclude from access validation check
	 * @var array
	 */
	public static $exclude_page_check = array("", "index", "home", "account", "info", "masterdetail");

	/**
	 * get the list of pages user role has access to
	 */
	public function __construct()
	{	
		if(!empty(USER_ROLE)){
			$db = new PDODb(DB_TYPE, DB_HOST , DB_USERNAME, DB_PASSWORD, DB_NAME, DB_PORT, DB_CHARSET);
			$db->where("role_id", USER_ROLE);
			//concat PageName And PageAction as page path
			$fields = array("CONCAT(page_name, '/', action_name) AS page");
			
			$roles_permission = $db->get("role_permissions", null, $fields);
			self:: $role_pages = array_column($roles_permission, "page"); //list all pages for the user role
			
			//get user role name
			self::$user_role = $db->where("role_id", USER_ROLE)->getValue("roles", "role_name");
		}
	}

	/**
	 * Check page path against user role permissions
	 * if user has access return AUTHORIZED
	 * if user has NO access return FORBIDDEN
	 * if user has NO role return NOROLE_PERMISSION
	 * @return String
	 */
	public static function GetPageAccess($path)
	{
		$path = strtolower(trim($path, '/'));
		$arr_path = explode("/", $path);
		$page = strtolower($arr_path[0]);
		//If User is accessing exclude access check page
		if (in_array($page, self :: $exclude_page_check)) {
			return AUTHORIZED;
		}
		// Acces integral : administrateurs, plus les profils du sommet de
		// l'organigramme (Directeur General, Secretaire General).
		//
		// Sans cette regle, l'acces depend entierement du contenu de la table
		// des permissions. Or la creation d'un profil n'y ecrit aucune ligne :
		// le profil nouvellement cree se retrouve avec un menu vide et un 403
		// sur chaque ecran, sans aucun moyen de se debloquer.
		if (!empty(self::$user_role)) {
			$normaliser = function($v){
				$v = trim((string) $v);
				$v = function_exists('mb_strtolower') ? mb_strtolower($v, 'UTF-8') : strtolower($v);
				return strtr($v, array('à'=>'a','â'=>'a','ä'=>'a','é'=>'e','è'=>'e','ê'=>'e','ë'=>'e','î'=>'i','ï'=>'i','ô'=>'o','ö'=>'o','ù'=>'u','û'=>'u','ü'=>'u','ç'=>'c'));
			};
			$integral = array();
			foreach (array('ROLES_ADMINISTRATEUR', 'ROLES_ACCES_INTEGRAL') as $constante) {
				if (defined($constante) && constant($constante) !== '') {
					$integral = array_merge($integral, array_map($normaliser, explode(',', constant($constante))));
				}
			}
			if (in_array($normaliser(self::$user_role), $integral, true)) {
				return AUTHORIZED;
			}

			// Le bureau du courrier oriente chaque pli : les ecrans du circuit
			// d'imputation lui sont garantis, meme si la table des permissions
			// ne les lui accorde pas. Sans cette regle, le bouton « Imputer »
			// lui restait invisible et rien n'indiquait pourquoi.
			if (defined('ROLES_VUE_COMPLETE_COURRIER') && defined('ECRANS_BUREAU_COURRIER')) {
				$bureau = array_map($normaliser, explode(',', ROLES_VUE_COMPLETE_COURRIER));
				if (in_array($normaliser(self::$user_role), $bureau, true)) {
					$action = (!empty($arr_path[1]) ? $arr_path[1] : "list");
					if ($action == "index") { $action = "list"; }
					$ecrans = array_map('trim', explode(',', ECRANS_BUREAU_COURRIER));
					if (in_array("$page/$action", $ecrans, true)) {
						return AUTHORIZED;
					}
				}
			}
		}
		// Filet de securite : un profil dont la table des permissions est vide
		// n'a jamais ete parametre. Plutot que de le laisser inutilisable, on lui
		// applique le socle par defaut. Des qu'un seul droit lui est attribue,
		// ce socle cesse de s'appliquer et le parametrage explicite fait foi.
		if (empty(self::$role_pages) && defined('ECRANS_PAR_DEFAUT') && !empty(USER_ROLE)) {
			$action = (!empty($arr_path[1]) ? $arr_path[1] : "list");
			if ($action == "index") { $action = "list"; }
			$socle = array_map('trim', explode(',', ECRANS_PAR_DEFAUT));
			if (in_array("$page/$action", $socle, true)) {
				return AUTHORIZED;
			}
		}
		if (!empty(USER_ROLE)) {
			$action = (!empty($arr_path[1]) ? $arr_path[1] : "list");
			if ($action == "index") {
				$action = "list";
			}
			$path = "$page/$action";
			if(in_array($path, self :: $role_pages)){
				return AUTHORIZED;
			}
			return FORBIDDEN;
		} else {
			return NOROLE; //User Does Not Have Any Role.
		}
	}

	/**
	 * Check if user role has access to a page
	 * @return Bool
	 */
	public static function is_allowed($path)
	{
		return (self::GetPageAccess($path) == AUTHORIZED);
	}

}
