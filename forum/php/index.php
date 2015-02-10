<?php
include_once(__DIR__ . "/lib.inc.php");

$message ="";
$username = "";

if (!isset($action)) {
	if (isset($_GET["action"])) 
		$action = $_GET["action"];
	else
		$action = "";
}		

if(isset($_SESSION["user_pseudo"]))
	$username = $_SESSION["user_pseudo"];
	
if (!isset($self)) 
	$self = $_SERVER["PHP_SELF"];	

switch($action) {
		case 'faccueil' :
			$self .= "?action=faccueil";
			include(__DIR__ . "/accueil.php");
			break;
		case 'ftopic':
			$self .= "?action=ftopic";
			include(__DIR__ . "/forum.php");
			break;
		case 'listeTheme':
		case 'listeCat':
		case 'listeTopic':
		case 'listeMsg' :
		case 'ftheme' :
		default : 
			include(__DIR__ . "/forum.php");
			break;

}