<?php

/**
 *  Csrf - Cross Site Request Forgery
 * @category  Security
 */
class Csrf
{
	public static $token = null;
	function __construct()
	{
		$token = get_session('csrf_token');
		if (empty($token)) {
			$token = hash_value(random_str(12));
			set_session('csrf_token', $token);		//set new token in session if not available
		}
		self::$token = $token;
	}

	/**
	 *  Csrf - Verify if the request is coming from our origin
	 * @category  Security
	 */
	public static function cross_check()
	{
		$current_token = get_session('csrf_token');

		$req_token = "";
		if (!empty($_SERVER['HTTP_X_CSRF_TOKEN'])) {
			$req_token = $_SERVER['HTTP_X_CSRF_TOKEN'];
		} elseif (!empty($_REQUEST['csrf_token'])) {
			$req_token = $_REQUEST['csrf_token'];
		}

		// hash_equals compare en temps constant : une comparaison classique
		// laisse fuir le jeton caractere par caractere par mesure du temps.
		if (empty($current_token) || !hash_equals((string) $current_token, (string) $req_token)) {
			render_error("Cross-Site request Forgery Detected. Please Contact The System Administrator For More Information", 403);
			exit;
		}

		return null;
	}
}
