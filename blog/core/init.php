<?php

/**
* Fonction loadConf
*
* Charge la configuration du fichier "conf.php"
*
* @return string[] Tableaux contenant la configuration
*/
function loadConf() {

	$content = file('core/conf.php');
	$conf = array();

	for ($i = 0; $i < sizeof($content); $i++) {

		$line = explode('=', $content[$i]);

		if (sizeof($line) != 2)
			continue;

		$key = trim($line[0]);
		$value = (trim($line[1]) == '~') ? '' : trim($line[1]);

		$conf[$key] = $value;

	}

	return $conf;

}

/**
* Fonction dbConnect
*
* Permet d'obtenir un objet PDO
*
* @param $conf Tableau contenant la configuration
* @return $pdo Instance la classe PDO ou false si fail
*/
function dbConnect($conf) {

	try {

		$pdo = new PDO($conf['dbType'].':host='.$conf['dbHost'].';port='.$conf['dbPort'].';dbname='.$conf['dbName'],
			$conf['dbUser'],
			$conf['dbPass'], array(
				PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8',
				PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
			)
		);

		return $pdo;

	} catch(PDOException $e) {

		return false;

	}

}