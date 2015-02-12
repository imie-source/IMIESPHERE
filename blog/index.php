<?php

//define('DOMAIN', 'http://127.0.0.1');
//define('DOC_ROOT', str_replace('index.php', '', $_SERVER['SCRIPT_FILENAME']));

// Constante WEB_ROOT contenant le chemin vers l'accueil du blog
define('WEB_ROOT', str_replace('index.php', '', $_SERVER['SCRIPT_NAME']));

/**
* Fonction loadController
*
* Permet de charger un controller
*
* @param $class Nom du controller
*
* @return void
*/
function loadController($class) {

	require('controller/'.strtolower(str_replace('Controller', '', $class)).'.php');

}

// Appel automatique à la fonction loadController à chaque instanciation de classe
spl_autoload_register('loadController');

// Inclusions des classes et fonctions principales
require('core/init.php');
require('core/request.php');
require('core/controller.php');
require('core/model.php');

// Chargement de la configuration
$conf = loadConf();

// Constante ARTICLES_PER_PAGE contenant le nombre d'article à afficher par page
define('ARTICLES_PER_PAGE', intval($conf['nbArticlePerPage']));

// Création d'un objet PDO
$pdo = dbConnect($conf);

// Si $pdo n'est pas une instance de PDO
if (!$pdo instanceof PDO) {

	// Levée d'une exception serveur
	new ErrorController('server');
	die();

}

// Analyse de l'url
$request = new Request($_GET);
$controller = $request -> controller();
$action = $request -> action();
$params = $request -> params();

// Si le controller de l'url existe
if (file_exists('controller/'.$controller.'.php')) {

	// Instanciation du controller
	$controller = ucfirst($controller).'Controller';
	$controller = new $controller($pdo, $params);

	// Si l'action de l'url existe
	if (method_exists($controller, $action)) {

		// Appel de l'action
		$controller -> $action();

	// Sinon
	} else {

		// Levée d'une exception 404 - introuvable
		new ErrorController('not-found');

	}

// Sinon
} else {

	// Levée d'une exception 404 - introuvable
	new ErrorController('not-found');

}