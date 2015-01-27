<?php 

	define('ENVOIE_OK', 1);
	
	require ("bdd_inc.php");

	/**
	 * Inscrit le nouvel utilisateur dans la base
	 *
	 * @param $pseudo Pseudo à insérer
	 * @param $motDePasse Mot de passe
	 * @param $Email Courriel de l'utilisateur
	 * @param $profil Id du profil demandé
	 * @return Code de retour (bien passé, pseudo exisant et mail existant)
	 */
	function envoieMessage($nom, $email, $sex, $msg) {

		// Chargement de la configuration
		$dbConf = chargeConfiguration();
		// Connexion à la base de données
		$dbLink = cnxBDD($dbConf);

		$sql = "INSERT INTO Contact (nom, sex, mail, msg) VALUES (:nom, :sex, :mail, :msg);";

		$req = $dbLink->prepare($sql);
		// J'associe à ma requête le contenu de la variable $pseudo
		$req -> bindParam(":nom", $nom);
		// J'associe à ma requête le contenu de la variable $password
		$req -> bindParam(":sex", $sex);
		// J'associe à ma requête le contenu de la variable $Email
		$req -> bindParam(":mail", $mail);
		// J'associe à ma requête le contenu de la variable $profil
		$req -> bindParam(":msg", $msg);
		
		try {

			// J'exécute ma requête
			$req -> execute();
		}

		catch (PDOException $e) {
			
			// En cas d'erreur, je récupère le code
			die($e -> getCode() . " / " . $e -> getMessage());
		}

		// On libère le résultat de la requête
		$req = NULL;
		// On se déconnecte de la base
		$dbLink = NULL;
		return ENVOIE_OK;
	}
	
	envoieMessage($_POST["nom"], $_POST["emailaddress"], $_POST["sex"], $_POST["msg"]);

?>