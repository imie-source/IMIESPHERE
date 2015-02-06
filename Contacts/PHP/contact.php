<?php 

	/**
	 * Fonctions qui permettent d'envoyer un mail à l'association
	 * Avec confirmation de l'envoie du mail
	 *
	 * @author Thibaud Carié
	 * @since 05/01/2015
	 */

	define('ENVOI_KO', 1);

	define('ENVOI_OK', 2);
			

	require_once ("bdd_inc.php");

	require_once (__DIR__ . "/../../lib/PHPMailer/class.phpmailer.php");

	/**
	 * Fonction qui configure PHPMailer et envoie le mail
	 *
	 * @return Vrai si le mail a été envoyé, Faux si il y a eu une erreur
	 */
	function mailPHPMailer() {
		
		$mail = new PHPMailer();

		$mail->IsSMTP();

		$mail->SMTPAuth = true;

		$mail->SMTPSecure = 'ssl';

		$mail->Host = 'smtp.gmail.com';

		$mail->Port = 465;

		$mail->CharSet = "utf-8";

		$mail->Username = USERNAME;

		$mail->Password = PASSWORD;

		$mail->SetFrom(USERNAME, "IMIE Sph&egrave;re");

		$mail->AddAddress($_POST["emailaddress"], $_POST["nom"]);

		$mail->Subject = $_POST["objet"];

		$mail->MsgHTML($_POST["msg"]);

		if (!$mail->Send()) {

			return ENVOI_KO;
			
		}

		else {

			return ENVOI_OK;
		}
	}

	/**
	 * Fonction qui check si le mail est valide
	 *
	 * @param $email La mail à vérifier
	 * @return Vrai le mail est correct, faux si le mail n'est pas correct
	 */
	function check_mail($email){

		// Utilisation expression régulière pour vérifier le mail
 		return(preg_match("/^[a-z0-9._-]+@[a-z0-9._-]{2,}\.[a-z]{2,4}$/", $email));
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

		// Je définis le modèle de ma requête
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
	
	if (isset($_POST["send"])) {

		$res = mailPHPMailer();

		$send = envoieMessage($_POST["nom"], $_POST["tel"], $_POST["emailaddress"], $_POST["objet"], $_POST["msg"]);
		
		switch ($res) {

			case ENVOI_OK:
				include (__DIR__ . "/../HTML/envoie_ok.html");
				break;

			case ENVOI_KO:
				include (__DIR__ . "/../HTML/envoie_ko.html");
				break;
		}
	}

	else {

		require (__DIR__ . "/../HTML/contact.html");
	}
?>