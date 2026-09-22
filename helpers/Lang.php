<?php

/**
 * Project Language Class/ Interface
 */

class Lang
{
	public $phrases = array();

	function __construct()
	{
		$l = get_cookie('lang');
		$lang = (!empty($l) ? $l : DEFAULT_LANGUAGE);

		// Le nom de langue vient d'un cookie : sans filtrage, une valeur du
		// type "../../config" permettait de faire analyser un fichier .ini
		// arbitraire du serveur et d'en exposer le contenu.
		$lang = preg_replace('/[^a-z_-]/', '', strtolower((string) $lang));
		$lang_file = ROOT . LANGS_DIR . $lang . ".ini";
		if ($lang !== '' && file_exists($lang_file)) {
			$this->phrases = parse_ini_file($lang_file);
		}
	}
	/**
	 * Get user langauge or return the default language
	 * @return string
	 */
	public static function get_user_language()
	{
		$l = get_cookie('lang');
		$lang = (!empty($l) ? $l : DEFAULT_LANGUAGE);
		return $lang;
	}

	/**
	 * Get a language phrase with a key
	 * @return string
	 */
	public function get_phrase($key)
	{
		$phrase = isset($this->phrases[$key]) ? $this->phrases[$key] : null;
		return $phrase;
	}
}
