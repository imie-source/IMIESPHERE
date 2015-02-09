<?php
/**
* Classe Controller
*
* Cette classe comporte les fonctions communes à tous les controllers enfants
*
* @abstract
*/
abstract class Controller {

	/**
	* @var $_layout Layout de la page
	* @access private
	*/
	private $_layout = 'default';

	/**
	* @var $_viewDir Dossier des vues
	* @access private
	*/
	private $_viewDir = 'article';

	/**
	* @var $_model Model du blog
	* @access private
	*/
	private $_model;

	/**
	* @var $_params Paramètres de l'url
	* @access private
	*/
	private $_params = array();

	/**
	* @var $_vars Variables à afficher dans la vue
	* @access private
	*/
	private $_vars = array();

	/**
	* Constructeur
	*
	* Permet de stocker les paramètres de l'url et d'initialiser le model
	*
	* @param PDO Instance de la classe PDO
	* @param string[] Paramètres de l'url
	*
	* @return void
	*/
	public function __construct($pdo, $params) {

		$this -> _params = $params;
		$this -> _model = new Model($pdo);

		// Si l'url comporte au moins 2 paramètres
		if (isset($this -> _params[1])) {

			// Levée d'une exception 404 - introuvable
			new ErrorController('not-found');
			die();

		}

	}

	/**
	* Fonction render
	*
	* Permet d'afficher une vue
	*
	* @param string Nom de la vue à afficher
	*
	* @return void
	*/
	public function render($view) {

		// Récupération des variables de la vue
		$vars = $this -> _vars;

		// Mise en marche de la mémoire tampon
		ob_start();

		// Inclusion de la vue demandée
		require('view/'.$this -> _viewDir.'/'.$view.'.html');

		// Stockage de la vue dans une variable $page
		$page = ob_get_contents();

		// Fin de la mémoire tampon
		ob_end_clean();

		// Inclusion du layout et affichage des données de la mémoire tampon
		require('view/layout/'.$this -> _layout.'.html');
		

	}

	/**
	* Fonction loadCSS
	*
	* Permet de charger le ou les fichiers CSS selon les besoins de chaque vue
	*
	* @param string Nom des fichiers CSS à charger séparés par ":"
	*
	* @return string Inclusion(s) HTML des CSS
	*/
	public function loadCSS($css) {

		$css = explode(':', $css);
		$styles = '';

		for ($i = 0; $i < sizeof($css); $i++) {

			$styles .= '<link rel="stylesheet" type="text/css" href="'.WEB_ROOT.'view/css/'.$css[$i].'.css" />';

		}

		return $styles;

	}

	/**
	* Fonction loadJS
	*
	* Permet de charger le ou les fichiers JS selon les besoins de chaque vue
	*
	* @param string Nom des fichiers JS à charger
	*
	* @return string Inclusion(s) HTML des JS
	*/
	public function loadJS($js) {

		$js = explode(':', $js);
		$scripts = '';

		for ($i = 0; $i < sizeof($js); $i++) {

			$scripts .= '<script src="'.WEB_ROOT.'view/js/'.$js[$i].'.js"></script>';

		}

		return $scripts;

	}

	/**
	* Fonction sendToRender
	*
	* Permet de preparer les données de la vue
	*
	* @param string[] Données à envoyer à la vue
	* @param string Nom de la vue
	*
	* @return void
	*/
	public function sendToRender($vars, $view) {

		$this -> _vars = $vars;
		$this -> render($view);

	}

	/**
	* Fonction clean
	*
	* Permet de remplacer les caractères spéciaux et accentués pour les url
	*
	* @param string chaine de caractères
	*
	* @return string Chaine non accentuée et sans espaces
	*/
	public function clean($str) {

		$a = array('À', 'Á', 'Â', 'Ã', 'Ä', 'Å', 'Æ', 'Ç', 'È', 'É', 'Ê', 'Ë', 'Ì', 'Í', 'Î', 'Ï', 'Ð', 'Ñ', 'Ò', 'Ó', 'Ô', 'Õ', 'Ö', 'Ø', 'Ù', 'Ú', 'Û', 'Ü', 'Ý', 'ß', 'à', 'á', 'â', 'ã', 'ä', 'å', 'æ', 'ç', 'è', 'é', 'ê', 'ë', 'ì', 'í', 'î', 'ï', 'ñ', 'ò', 'ó', 'ô', 'õ', 'ö', 'ø', 'ù', 'ú', 'û', 'ü', 'ý', 'ÿ', 'Ā', 'ā', 'Ă', 'ă', 'Ą', 'ą', 'Ć', 'ć', 'Ĉ', 'ĉ', 'Ċ', 'ċ', 'Č', 'č', 'Ď', 'ď', 'Đ', 'đ', 'Ē', 'ē', 'Ĕ', 'ĕ', 'Ė', 'ė', 'Ę', 'ę', 'Ě', 'ě', 'Ĝ', 'ĝ', 'Ğ', 'ğ', 'Ġ', 'ġ', 'Ģ', 'ģ', 'Ĥ', 'ĥ', 'Ħ', 'ħ', 'Ĩ', 'ĩ', 'Ī', 'ī', 'Ĭ', 'ĭ', 'Į', 'į', 'İ', 'ı', 'Ĳ', 'ĳ', 'Ĵ', 'ĵ', 'Ķ', 'ķ', 'Ĺ', 'ĺ', 'Ļ', 'ļ', 'Ľ', 'ľ', 'Ŀ', 'ŀ', 'Ł', 'ł', 'Ń', 'ń', 'Ņ', 'ņ', 'Ň', 'ň', 'ŉ', 'Ō', 'ō', 'Ŏ', 'ŏ', 'Ő', 'ő', 'Œ', 'œ', 'Ŕ', 'ŕ', 'Ŗ', 'ŗ', 'Ř', 'ř', 'Ś', 'ś', 'Ŝ', 'ŝ', 'Ş', 'ş', 'Š', 'š', 'Ţ', 'ţ', 'Ť', 'ť', 'Ŧ', 'ŧ', 'Ũ', 'ũ', 'Ū', 'ū', 'Ŭ', 'ŭ', 'Ů', 'ů', 'Ű', 'ű', 'Ų', 'ų', 'Ŵ', 'ŵ', 'Ŷ', 'ŷ', 'Ÿ', 'Ź', 'ź', 'Ż', 'ż', 'Ž', 'ž', 'ſ', 'ƒ', 'Ơ', 'ơ', 'Ư', 'ư', 'Ǎ', 'ǎ', 'Ǐ', 'ǐ', 'Ǒ', 'ǒ', 'Ǔ', 'ǔ', 'Ǖ', 'ǖ', 'Ǘ', 'ǘ', 'Ǚ', 'ǚ', 'Ǜ', 'ǜ', 'Ǻ', 'ǻ', 'Ǽ', 'ǽ', 'Ǿ', 'ǿ', 'Ά', 'ά', 'Έ', 'έ', 'Ό', 'ό', 'Ώ', 'ώ', 'Ί', 'ί', 'ϊ', 'ΐ', 'Ύ', 'ύ', 'ϋ', 'ΰ', 'Ή', 'ή', '\'', ' ', '!', '?', '.');
		$b = array('A', 'A', 'A', 'A', 'A', 'A', 'AE', 'C', 'E', 'E', 'E', 'E', 'I', 'I', 'I', 'I', 'D', 'N', 'O', 'O', 'O', 'O', 'O', 'O', 'U', 'U', 'U', 'U', 'Y', 's', 'a', 'a', 'a', 'a', 'a', 'a', 'ae', 'c', 'e', 'e', 'e', 'e', 'i', 'i', 'i', 'i', 'n', 'o', 'o', 'o', 'o', 'o', 'o', 'u', 'u', 'u', 'u', 'y', 'y', 'A', 'a', 'A', 'a', 'A', 'a', 'C', 'c', 'C', 'c', 'C', 'c', 'C', 'c', 'D', 'd', 'D', 'd', 'E', 'e', 'E', 'e', 'E', 'e', 'E', 'e', 'E', 'e', 'G', 'g', 'G', 'g', 'G', 'g', 'G', 'g', 'H', 'h', 'H', 'h', 'I', 'i', 'I', 'i', 'I', 'i', 'I', 'i', 'I', 'i', 'IJ', 'ij', 'J', 'j', 'K', 'k', 'L', 'l', 'L', 'l', 'L', 'l', 'L', 'l', 'l', 'l', 'N', 'n', 'N', 'n', 'N', 'n', 'n', 'O', 'o', 'O', 'o', 'O', 'o', 'OE', 'oe', 'R', 'r', 'R', 'r', 'R', 'r', 'S', 's', 'S', 's', 'S', 's', 'S', 's', 'T', 't', 'T', 't', 'T', 't', 'U', 'u', 'U', 'u', 'U', 'u', 'U', 'u', 'U', 'u', 'U', 'u', 'W', 'w', 'Y', 'y', 'Y', 'Z', 'z', 'Z', 'z', 'Z', 'z', 's', 'f', 'O', 'o', 'U', 'u', 'A', 'a', 'I', 'i', 'O', 'o', 'U', 'u', 'U', 'u', 'U', 'u', 'U', 'u', 'U', 'u', 'A', 'a', 'AE', 'ae', 'O', 'o', 'Α', 'α', 'Ε', 'ε', 'Ο', 'ο', 'Ω', 'ω', 'Ι', 'ι', 'ι', 'ι', 'Υ', 'υ', 'υ', 'υ', 'Η', 'η', '-', '-', '-', '-', '-');
		
		return rtrim(preg_replace('/-{2,}/', '-', str_replace($a, $b, $str)), '-');

	}

	/**
	* Setter setViewDir
	*
	* Permet de mettre à jour le dossier des vues à charger
	*
	* @param string Nom du dossier
	*
	* @return void
	*/
	public function setViewDir($dir) {

		$this -> _viewDir = $dir;

	}

	/**
	* Getter model
	*
	* Permet à un controller d'acceder à la base de données
	*
	* @return Model Instance de la classe Model
	*/
	public function model() {

		return $this -> _model;

	}

	/**
	* Fonction params
	*
	* Permet de récupérer un paramètre dans l'url
	*
	* @param int Identifiant du paramètre url
	*
	* @return string Paramètre
	*/
	public function params($id) {

		for ($i = 0; $i < sizeof($this -> _params); $i++) {

			if (is_array($this -> _params) && isset(array_keys($this -> _params)[$i]) && $id == array_keys($this -> _params)[$i]) {

				return $this -> _params[$i];

			}

		}

	}
	
}