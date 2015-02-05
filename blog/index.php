<?php

//define('DOMAIN', 'http://127.0.0.1');
//define('DOC_ROOT', str_replace('index.php', '', $_SERVER['SCRIPT_FILENAME']));
define('WEB_ROOT', str_replace('index.php', '', $_SERVER['SCRIPT_NAME']));

function loadController($class) {

	require('controller/'.strtolower(str_replace('Controller', '', $class)).'.php');

}

spl_autoload_register('loadController');

require('core/init.php');
require('core/request.php');
require('core/controller.php');
require('core/model.php');

$conf = loadConf();

define('ARTICLES_PER_PAGE', intval($conf['nbArticlePerPage']));

$pdo = dbConnect($conf);

if (!$pdo instanceof PDO) {

	new ErrorController('server');
	die();

}

$request = new Request($_GET);
$controller = $request -> controller();
$action = $request -> action();
$params = $request -> params();

if (file_exists('controller/'.$controller.'.php')) {

	$controller = ucfirst($controller).'Controller';
	$controller = new $controller($pdo, $controller, $params);

	if (method_exists($controller, $action)) {

		$controller -> $action();

	} else {

		new ErrorController('not-found');

	}

} else {

	new ErrorController('not-found');

}