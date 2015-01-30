<?php 
	
	require ("bdd_inc.php");

	/**
	 * Fonction qui envoie un mail à la personne postant son message (accusé de reception)
	 */
	/*function accuseRecep() {

		$subject = "IMIE vous remercie";

		$msg = "Bonjour, \nVotre message à bien été envoyé à l'association IMIESPHERE\n. Elle vous répondra dans les plus bref délais\n Cordialement l'équipe de l'IMIESPHERE";

		mail($_POST["emailaddress"], $subject, $msg);
	}*/

	/**
	 * Fonction qui check si le mail est valide
	 *
	 * @return Vrai le mail est correct, faux si le mail n'est pas correct
	 */
	function check_mail($email){

		// Utilisation expression régulière pour vérifier le mail
 		return(preg_match("/^[a-z0-9._-]+@[a-z0-9._-]{2,}\.[a-z]{2,4}$/", $email));
	}

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

			// On envoie un accusé de réception
			//accuseRecep();
		}
	}

	envoieMail();
?>