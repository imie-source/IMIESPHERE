<?php

require_once(__DIR__ . "/lib.inc.php");

define("AUTH_OK", 2);
define("AUTH_MDP_KO", 1);
define("AUTH_PSEUDO_KO", 0);

function authentification($pseudo, $motDePasse) {
		// Chargement de la configuration
		$dbConf = chargeConfiguration();
		// Connexion à la base de données
		$pdo = cnxBDD($dbConf);
		// Calcul de l'empreinte MD5 du mot de passe passé en clair
		$password = md5($motDePasse);
		// Exécution de la requête
		// Je recherche l'enregistrement qui correspond à mon pseudo
		// Je définie le "modèle" de ma requête
		$req = "SELECT pseudo, password, id_profil FROM Utilisateur " .
			   "WHERE pseudo=:pseudo";
		// Je prépare ma requête et j'obtient un objet la représentant
		$pdoStmt = $pdo->prepare($req);
		// J'associe à ma requête le contenu de la variable $pseudo
		$pdoStmt->bindParam(':pseudo', $pseudo);
		// J'exécute ma requête
		$pdoStmt->execute();
		// Récupération de l'enregistrement sous forme de tableau associatif
		$row = $pdoStmt->fetch(PDO::FETCH_ASSOC);
		// Libération de l'enregistrement
		$pdoStmt = null;
		// Fermeture de la connexion au SGBD
		$pdo = null;
		// S'il y a un enregistrement
		if ($row) {
			// 2 possibilités !
			// Soit les mots de passe correspondent (leur empreinte)
			if ($row["password"] == $password) {
				// On place les deux informations dans la session
				$_SESSION["user_pseudo"] = trim($pseudo);
				$_SESSION["user_profil"] = $row["id_profil"];
				return AUTH_OK;
			}	
			else // les mots de passe ne correspondent pas (leur empreinte)
				return AUTH_MDP_KO;
		} else {
			// Le pseudo n'est pas présent en base
			return AUTH_PSEUDO_KO;
		}
	} // Fin de la fonction authentification


if(isset($_POST["pseudo"])){
	
	$res = authentification($_POST["pseudo"], $_POST["mdp"]);
	$pseudo = ($_POST["pseudo"]);

switch($res){
		case AUTH_PSEUDO_KO: 
			$message = "Le pseudo n'est pas correct!";					
			include (__DIR__ . '/../html/index.html');
			die();
		case AUTH_MDP_KO: 
			$message = "Le mot de passe n'est pas correct!";					
			include (__DIR__ . '/../html/index.html');
			die();
		case AUTH_OK: 
	
			include(__DIR__ . '/forum.php');
			break;
	}
}else {
	include(__DIR__ . '/../html/index.html');
}