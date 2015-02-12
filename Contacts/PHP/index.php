<?php 
	
	if (!isset($cle)) {
		
		if (isset($_GET["cle"])) {
			
			$cle = $_GET["cle"];
		}

		elseif (isset($_POST["cle"])) {
			
			$cle = $_POST["cle"];
		}

		else {

			$cle = "";
		}
	}

	if (!isset($self)) {
		
		$self = $_SERVER["PHP_SELF"];
	}

	switch ($cle) {

		case 'contact':
			$self .= "?cle=acontact";
			include (__DIR__ . "/contact.php");
			break;
		
		case 'acontact':
			include (__DIR__ . "/contact.php");
			break;
		
		default:
			session_start();
			include (__DIR__ . "/contact.php");
			break;
	}
?>