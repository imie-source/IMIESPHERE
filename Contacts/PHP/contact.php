<?php 

	define('ENREG_OK', 1);
	define('ENVOIE_OK', 2);
	
	
	require ("bdd_inc.php");

	/**
	 * Inscrit le nouvel utilisateur dans la base
	 *
	 * @param $pseudo Pseudo à insérer
	 * @param $motDePasse Mot de passe
	 * @param $profil Id du profil demandé
	 * @return Code de retour (bien passé, pseudo exisant et mail existant)
	 */
	function envoieMessage($nom, $email, $objet, $msg) {

		// Chargement de la configuration
		$dbConf = chargeConfiguration();
		// Connexion à la base de données
		$dbLink = cnxBDD($dbConf);

		$sql = "INSERT INTO Contact (nom, mail, objet, msg) VALUES (:nom, :mail, :objet, :msg);";

		$req = $dbLink->prepare($sql);
		// J'associe à ma requête le contenu de la variable $pseudo
		$req -> bindParam(":nom", $nom);
		// J'associe à ma requête le contenu de la variable $Email
		$req -> bindParam(":mail", $email);
		// J'associe à ma requête le contenu de la variable $Email
		$req -> bindParam(":objet", $objet);
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
		return ENREG_OK;
	}

	function accuseRecep() {

		$subject = "IMIE vous remercie";

		$msg = "Bonjour, \nVotre message à bien été envoyé à l'association IMIESPHERE\n. Elle vous répondra dans les plus bref délais\n Cordialement l'équipe de l'IMIESPHERE";

		mail($_POST["emailaddress"], $subject, $msg);
	}

	function check_mail($email){

		// Utilisation expression régulière pour vérifier le mail
 		return(preg_match("/^[a-z0-9._-]+@[a-z0-9._-]{2,}\.[a-z]{2,4}$/", $email));
	}

	function getMail() {

		// Chargement de la configuration
		$dbConf = chargeConfiguration();
		// Connexion à la base de données
		$dbLink = cnxBDD($dbConf);

		$sql = "SELECT mail_imie FROM info;";

		$req = $dbLink->prepare($sql);
		// J'associe à ma requête le contenu de la variable $pseudo

		try {

			// J'exécute ma requête
			$req -> execute();

			if ($data = $req -> fetch(PDO::FETCH_ASSOC)) {

			return $data["mail_imie"];

			}
				
				
		}

		catch (PDOException $e) {
			
			// En cas d'erreur, je récupère le code
			die($e -> getCode() . " / " . $e -> getMessage());
		}

		// On libère le résultat de la requête
		$req = NULL;
		// On se déconnecte de la base
		$dbLink = NULL;
	}

	if (check_mail($_POST["emailaddress"])) {

		envoieMessage($_POST["nom"], $_POST["emailaddress"], $_POST["objet"], $_POST["msg"]);

		accuseRecep();

		mail(getMail(), $_POST["objet"], $_POST["msg"]);
	}
	
?>