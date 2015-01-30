<?php
include_once(__DIR__ . "/lib.inc.php");

$message ="";

if (!isset($action)) {
	if (isset($_GET["action"])) 
		$action = $_GET["action"];
	else
		$action = "";
}		
	
if (!isset($self)) 
	$self = $_SERVER["PHP_SELF"];	

switch($action) {
		case 'faccueil' :
			$self .= "?action=aacueil";
			include(__DIR__ . "/accueil.php");
			break;
		case 'listeTheme':
		case 'listeCat':
		case 'ftheme' :
			include(__DIR__ . "/forum.php");
			break;

}