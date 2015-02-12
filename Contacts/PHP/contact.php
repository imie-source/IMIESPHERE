<?php 

	/**
	 * Fonctions qui permettent d'envoyer un mail à l'association
	 * Avec confirmation de l'envoi du mail
	 * Ou renvoi d'une page d'erreur
	 *
	 * @author Thibaud Carié
	 * @since 05/01/2015
	 */

	// On démarre une session pour l'utilisation des variables de sessions
	// session_start();

	// Définition des constantes
	define('ENVOI_OK', 1);
	define('ENVOI_KO', 2);	

	// On inclus les fonctions de connexion et de chargement de la BDD
	require_once ("bdd_inc.php");

	// On inclus les class de PHPMailer qui nous sont utiles
	include_once (__DIR__ . "/../../lib/PHPMailer/class.phpmailer.php");
	require_once (__DIR__ . "/../../lib/PHPMailer/class.smtp.php");

	/**
	 * Fonction qui va chercher les infos de l'association en base de données
	 * @return void
	 */
	function getInfos() {
		
		// Chargement de la configuration
		$dbConf = chargeConfiguration();
		// Connexion à la base de données
		$dbLink = cnxBDD($dbConf);

		// Si $dbLink est une instance de PDO
		if ($dbLink instanceof PDO) {

			// On définit le modèle de la requête
			$sql = "SELECT mail_imie, tel_imie, adresse1_imie, adresse2_imie, adresse3_imie, adresse4_imie FROM info;";
			
			// On prépare la requête
			$req = $dbLink -> prepare($sql);

			// On l'exécute
			$req -> execute();

			// Si on trouve quelque chose en BDD
			if ($data = $req -> fetch(PDO::FETCH_ASSOC)) {
				
				// On définit les variables de session
				$_SESSION['mail'] = $data["mail_imie"];
				$_SESSION['tel'] = $data["tel_imie"];
				$_SESSION['rue'] = $data["adresse1_imie"];
				$_SESSION['campus'] = $data["adresse2_imie"];
				$_SESSION['build'] = $data["adresse3_imie"];
				$_SESSION['post'] = $data ["adresse4_imie"];
			}
		}

		// Sinon
		else {

			// On renvoi le message d'erreur
			echo "Message d'erreur : " . $dbLink;
		}
	}

	/**
	 * Affiche la page HTML indiquant le résultat de l'authentification
	 *
	 * @param $msgfail Message à afficher
	 * @param $res Cela c'est-il bien passé ou non ?
	 */
	function afficheRes($msgfail, $res) {
		
		global $self;

		// Si cela c'est bien passé
		if ($res) {
			$idRes = "thx";
			$retour = $self . "?cle=contact";
		}

		// Sinon
		else {

			$idRes = "err";
			$retour = $self . "?cle=contact";
		}

		include (__DIR__ . "/../HTML/envoi.html");
		die();
	}

	/**
	 * Fonction qui configure PHPMailer et envoie le mail
	 *
	 * @param $message Le message à envoyer
	 * @return Vrai si le mail a été envoyé, Faux si il y a eu une erreur
	 */
	function mailPHPMailer($message) {

		// On définit le bon fuseau horaire
		date_default_timezone_set('GMT+1');
		
		// On essaye de faire la configuration
		try {

			// On crée une nouvelle instance de la classe
			$mail = new PHPMailer();

			// On active SMTP
			$mail->IsSMTP();

			// On active la fonction de débugage de SMTP
			$mail->STMPDebug = 1;

			// On active l'authentification SMTP
			$mail->SMTPAuth = true;

			// Gmail nécéssite un transfert sécurisé, on définit donc un système de cryptage à utiliser
			// 
			$mail->SMTPSecure = 'ssl';

			// On définit le nom d'hôte du serveur mail ici Gmail
			$mail->Host = 'smtp.gmail.com';

			// On définit le numéro de port SMTP
			$mail->Port = 587;

			// On définit le niveau d'encodage à UTF-8 pour gérer les caractères spéciaux
			$mail->CharSet = "UTF-8";

			// On définit le nom d'utilisateur pour l'authentification SMTP
			// Ici il est définit dans un autre fichier par sécurité
			$mail->Username = USERNAME;

			// On définit le mot de passe pour l'authentification SMTP
			// Ici il est définit dans un autre fichier par sécurité
			$mail->Password = PASSWORD;

			// On définit par qui le message a été envoyé
			$mail->SetFrom($_POST["emailaddress"], $_POST["nom"]);

			// On définit à qui le message est envoyé
			$mail->AddAddress(USERNAME, "IMIE Sph&egrave;re");

			// On définit le sujet du message
			$mail->Subject = $_POST["objet"];

			// On définit le format du message à HTML
			$mail->IsHTML(true);

			// On définit un limite de charactères pour le message
			$mail->WordWrap = 250;

			// On définit le message au format HTML
			$mail->MsgHTML($message);

			// On envoi le message et on check s'il y a eu des erreurs
			if (!$mail->Send()) {

				/*// Si l'envoi du mail se passe mal on retourne l'erreur
				echo "Erreur pendant l'envoie du message : " . $mail->ErrorInfo;
				die();*/

				// Si l'envoi du mail se passe mal on retourne la constante d'erreur
				return ENVOI_KO;
			}

			else {

				// Sinon on retourne la constante OK
				return ENVOI_OK;
			}
		}

		// On "attrape" les erreurs de la class PHPMailer
		catch (phpmailerException $pme) {

			// On affiche l'erreur
			echo $pme -> errorMessage();
		}

		// On "attrape" toutes autres erreurs possibles
		catch (Exception $e) {

			// On affiche l'erreur
			echo $e -> getMessage();
		}
	}

	/**
	 * Fonction qui check si le mail est valide
	 *
	 * @param $email La mail à vérifier
	 * @return Vrai si le mail est correct, faux si le mail n'est pas correct
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
	
	// Si il y a eu soummission de formulaire
	if (isset($_POST["send"])) {

		// Si le mail est correct
		if (check_mail($_POST["emailaddress"])) {

			// Si aucun des champs n'est vide
			if (!empty($_POST["msg"]) && !empty($_POST["nom"]) && !empty($_POST["emailaddress"]) && !empty($_POST["objet"]) && !empty($_POST["tel"])) {

				// On stocke les infos relatives au mail en base de données
				$res = envoieMessage($_POST["nom"], $_POST["tel"], $_POST["emailaddress"], $_POST["objet"], $_POST["msg"]);

				// On stocke les infos du formualaire dans des variables
				$nom = $_POST["nom"];
				$mail = $_POST["emailaddress"];
				$tel = $_POST["tel"];
				$objet = $_POST["objet"];
				$msg = $_POST["msg"];

				// On met en forme le message en HTML
				$msgHTML = "<html><head><meta charset = 'utf-8'/></head><body><p>" . $msg . "<br/><br/><br/><br/>" . $nom . "<br/>" . $mail . "<br/>" . $tel . "<br/></p></body></html>";

				// On fait appelle à la fonction d'envoie du mail
				$send = mailPHPMailer($msgHTML);
				
				switch ($send) {

					case ENVOI_OK:
						afficheRes("Votre message à bien été envoyé", true);
						break;

					case ENVOI_KO:
						afficheRes("Une erreur est apparue pendant l'envoi de votre message", false);
						break;
				}
			}

			// Sinon
			else {

				// On renvoi la page d'erreur
				afficheRes("L'un des champs est vide", false);
			}
		}

		// Sinon
		else {

			// On renvoi la page d'erreur
			afficheRes("L'adresse mail n'est pas correcte", false);
		}
	}

	// Sinon on affiche le formulaire
	else {

		getInfos();
		require_once (__DIR__ . "/../HTML/contact.html");
	}
?>