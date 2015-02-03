<?php 

	define('ENVOIE_OK', 1);
	
	require ("bdd_inc.php");

	/**
 	 *
	 * Fonction qui envoie un mail à la personne postant son message (accusé de reception)
	 */
	function accuseRecep() {

		if (check_mail($_POST["emailaddress"])) {

			// Déclaration de l'adresse de destination.
			$mail = $_POST["emailaddress"];

			// On filtre les serveurs qui rencontrent des bogues.
			if (!preg_match("/^[a-z0-9._-]+@(hotmail|live|msn)\.[a-z]{2,4}$/", $mail)) {

				$passage_ligne = "\r\n";
			}

			else {

				$passage_ligne = "\n";
			}

			//Déclaration du message au format HTML.
			$message_html = "Bonjour, \nVotre message à bien été envoyé à l'association IMIESPHERE\n. Elle vous répondra dans les plus bref délais\n Cordialement l'équipe de l'IMIESPHERE";
			 
			//Création de la boundary
			$boundary = "-----=".md5(rand());
			 
			//Définition du sujet.
			$sujet = "Accusé de reception";

			$nom = $_POST["nom"];

			$mailExp = $_POST["emailaddress"];
			 
			//Création du header de l'e-mail.
			$header = "From: \"$nom\"$mailExp".$passage_ligne;
			$header.= "Reply-to: \"$nom\"$mailExp".$passage_ligne;
			$header.= "MIME-Version: 1.0".$passage_ligne;
			$header.= "Content-Type: multipart/alternative;".$passage_ligne." boundary=\"$boundary\"".$passage_ligne;
			 
			//Création du message.
			$message = $passage_ligne."--".$boundary.$passage_ligne;

			//Ajout du message au format HTML
			$message.= "Content-Type: text/html; charset=\"ISO-8859-1\"".$passage_ligne;
			$message.= "Content-Transfer-Encoding: 8bit".$passage_ligne;
			$message.= $passage_ligne.$message_html.$passage_ligne;

			$message.= $passage_ligne."--".$boundary."--".$passage_ligne;
			$message.= $passage_ligne."--".$boundary."--".$passage_ligne;
			 
			//Envoi de l'e-mail.
			mail($mail,$sujet,$message,$header);

		}
	}

	/**
	 * Fonction qui check si le mail est valide
	 *
	 * @return Vrai le mail est correct, faux si le mail n'est pas correct
	 */
	function check_mail($email){

		// Utilisation expression régulière pour vérifier le mail
 		return(preg_match("/^[a-z0-9._-]+@[a-z0-9._-]{2,}\.[a-z]{2,4}$/", $email));
	}

	/**
	 * Fonction qui envoie le message à l'association
	 *
	 */
	function envoieMail() {
		
		// Si le mail est correct
		if (check_mail($_POST["emailaddress"])) {

			// Déclaration de l'adresse de destination.
			$mail = 'thibaud.carie@gmail.com';

			// On filtre les serveurs qui rencontrent des bogues.
			if (!preg_match("/^[a-z0-9._-]+@(hotmail|live|msn)\.[a-z]{2,4}$/", $mail)) {

				$passage_ligne = "\r\n";
			}

			else {

				$passage_ligne = "\n";
			}

			//Déclaration du message au format HTML.
			$message_html = $_POST["msg"];
			 
			//Création de la boundary
			$boundary = "-----=".md5(rand());
			 
			//Définition du sujet.
			$sujet = $_POST["objet"];

			$nom = $_POST["nom"];

			$tel = $_POST["tel"];

			$mailExp = $_POST["emailaddress"];
			 
			//Création du header de l'e-mail.
			$header = "From: \"$nom\"$mailExp".$passage_ligne;
			$header.= "Reply-to: \"$nom\"$mailExp".$passage_ligne;
			$header.= "MIME-Version: 1.0".$passage_ligne;
			$header.= "Content-Type: multipart/alternative;".$passage_ligne." boundary=\"$boundary\"".$passage_ligne;
			 
			//Création du message.
			$message = $passage_ligne."--".$boundary.$passage_ligne;

			//Ajout du message au format HTML
			$message.= "Content-Type: text/html; charset=\"ISO-8859-1\"".$passage_ligne;
			$message.= "Content-Transfer-Encoding: 8bit".$passage_ligne;
			$message.= $passage_ligne.$tel.$passage_ligne.$message_html.$passage_ligne;

			$message.= $passage_ligne."--".$boundary."--".$passage_ligne;
			$message.= $passage_ligne."--".$boundary."--".$passage_ligne;
			 
			//Envoi de l'e-mail.
			mail($mail,$sujet,$message,$header);

			// On envoie un accusé de réception
			accuseRecep();
		}
	}

	/**
	 * Enregistre le message dans la BDD
	 *
	 * @param $nom Nom à insérer
	 * @param $tel Téléphone à insérer
	 * @param $email mail à insérer
	 * @param $objet objet du mail à insérer
	 * @param $msg Message à insérer
	 */
	function envoieMessage($nom, $tel, $email, $objet, $msg) {

		// Chargement de la configuration
		$dbConf = chargeConfiguration();
		// Connexion à la base de données
		$dbLink = cnxBDD($dbConf);

		$sql = "INSERT INTO Contact (nom, mail, tel, objet, msg) VALUES (:nom, :mail, :tel, :objet, :msg);";

		$req = $dbLink->prepare($sql);
		// J'associe à ma requête le contenu de la variable $nom
		$req -> bindParam(":nom", $nom);
		// J'associe à ma requête le contenu de la variable $mail
		$req -> bindParam(":mail", $email);
		// J'associe à ma requête le contenu de la variable $tel
		$req -> bindParam(":tel", $tel);
		// J'associe à ma requête le contenu de la variable $objet
		$req -> bindParam(":objet", $objet);
		// J'associe à ma requête le contenu de la variable $msg
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
	}
	
	envoieMessage($_POST["nom"], $_POST["tel"], $_POST["emailaddress"], $_POST["objet"], $_POST["msg"]);

	envoieMail();
?>