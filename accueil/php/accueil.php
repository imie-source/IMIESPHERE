<?php

/**
* Fonction loadConf
*
* Charge la configuration du fichier "conf.php"
*
* @return string[] Tableaux contenant la configuration
*/
function loadConf() {

	$content = file('conf.php');
	$conf = array();

	for ($i = 0; $i < sizeof($content); $i++) {

		$line = explode('=', $content[$i]);

		if (sizeof($line) != 2)
			continue;

		$key = trim($line[0]);
		$value = (trim($line[1]) == '~') ? '' : trim($line[1]);

		$conf[$key] = $value;

	}

	return $conf;

}

/**
* Fonction dbConnect
*
* Permet d'obtenir un objet PDO
*
* @param $conf Tableau contenant la configuration
* @return $pdo Instance la classe PDO ou false si fail
*/
function dbConnect($conf) {

	try {

		$pdo = new PDO($conf['dbType'].':host='.$conf['dbHost'].';port='.$conf['dbPort'].';dbname='.$conf['dbName'],
			$conf['dbUser'],
			$conf['dbPass'], array(
				PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8',
				PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
			)
		);

		return $pdo;

	} catch(PDOException $e) {

		return false;

	}

}

/* -----------------------------------------------------------------------------------------------------------------*/

// Je charge la DB
$conf = loadConf();
$pdo = dbConnect($conf);

// Si connection fail alors die !
if (!$pdo instanceof PDO){
	die();
}

/* ---------------------------------------------------------------------------------------------------------------*/


	define("AUTH_OK", 2);
	define("AUTH_MDP_KO", 1);
	define("AUTH_PSEUDO_KO", 0);
	

	function authentification($pseudo, $motDePasse) {

		$password = md5($motDePasse);
		// Exécution de la requête
		// Je recherche l'enregistrement qui correspond à mon pseudo
		// Je définie le "modèle" de ma requête
		$req = "SELECT pseudo, password, id_profil, id_utilisateur FROM utilisateur " .    
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
		// S'il y a un< enregistrement
		if ($row) {
			// 2 possibilités !
			// Soit les mots de passe correspondent (leur empreinte)
			if ($row["password"] == $password) {
				// On place les trois informations dans la session
				$_SESSION["user_pseudo"] = trim($pseudo);
				$_SESSION["user_profil"] = $row["id_profil"];
				$_SESSION["user_id"] = $row["id_utilisateur"];
				return AUTH_OK;
			}	
			else // les mots de passe ne correspondent pas (leur empreinte)
				return AUTH_MDP_KO;
		} else {
			// Le pseudo n'est pas présent en base
			return AUTH_PSEUDO_KO;
		}
	} // Fin de la fonction authentification
	

	function afficheResultatAuth($msg, $res) {
		global $self;
		// Si cela s'est bien passé
		if ($res) {
			$classRes = "resOK";
			$retour = $self . "?action=fauthentifie";
		} else { // Sinon
			$classRes = "resKO";
			$retour = $self . "?action=fauthentification";
		}
		include(__DIR__ . "/../html/authentification_res.html");
		die();
	}
	
	// Si il y a eu soumission de formulaire
	if (isset($_POST["pseudo"])) {
		// Alors on procède à l'authentification
		// On appelle la fonction et on stocke le résultat dans $res
		$res = authentification($_POST["pseudo"], $_POST["mdp"]);
		$pseudo = ucfirst($_POST["pseudo"]);
		// Selon le "code" résultat de l'authentification
		switch($res) {
			case AUTH_PSEUDO_KO : 
				afficheResultatAuth("Vous n'&ecirc;tes pas connu dans la base...",
								false);
			case AUTH_MDP_KO :
				afficheResultatAuth("Mauvais couple identifiant / mot de passe...",
								false);
			case AUTH_OK :
				/*afficheResultatAuth("Bonjour " . $_POST["pseudo"] . "!",
								true); */
				include_once(__DIR__ . "/tchat.php");
				die();
		}
	} else {
		// Si pas de soumission de formulaire, on affiche le formulaire
		include(__DIR__ . '/../html/authentification.html');
	}	


	if (!empty($_POST)) {
		if (isset($_POST('Connection'))) {
			authentification($_POST["pseudo"], $_POST["mdp"]));
		} else {
			inscription($_POST["email"], $_POST["pseudo"], $_POST["mdp"], $_POST["mdp1"]);
		}
	}


// ---------------------------------------------------------------------------------------------------------------- // 

	// FUNCTION INSCRIPTION


	define('INSCR_OK', 1);
	define('INSCR_PSEUDO_EXIST', 2);
	define('INSCR_EMAIL_EXIST', 3);
	
	/**
	 * Vérifie si le pseudo est correct ou non
	 *
	 * Le pseudo doit contenir que A-Za-z0-9
	 *
	 * @param $pseudo Pseudo à vérifier
	 * @return Vrai ou faux selon le pseudo
	 */
	function checkPseudo($pseudo) {
	
		// Utilisation des expressions régulières
		// return preg_match('/^[A-Za-z0-9]{4,50}$/', $pseudo);
	
		// Je mets chaque caractère dans un tableau
		$tab = str_split($pseudo);
		// Je parcours le tableau caractère par caractère
		for($i = 0; $i < count($tab); $i++) {
			// Je mets le caractère d'indice $i dans une variable $c
			$c = $tab[$i];
			// Si le caractère est compris entre
			// 'A' et 'Z' ou 'a' et 'z' ou '0' et '9'
			if (($c >= 'A' && $c <= 'z') ||
			    ($c >= '0' && $c <= '9'))
				// On continue la vérification...
				continue;
			else // Le caractère n'est pas conforme, on renvoie faux
				return false;
		}
		// l'ensemble des caractères ont été vérifiés, on renvoie donc vrai
		return true;
	} // Fin de la fonction checkPseudo
	
	/**
	 * Vérifie si les mots de passe concordent
	 *
	 * Les mots de passe doivent être identiques et non vides
	 * 
	 * @param $mdp1 Mot de passe original
	 * @param $mdp2 Mot de passe de confirmation
	 * @return Vrai si les mots de passe sont identiques et non vide, faux sinon
	 */
	function checkMdp($mdp1, $mdp2) {
		// les deux mots de passe doivent être identiques et non vide
		return ($mdp1 == $mdp2 && $mdp1 != ""); 
	} // Fin de la fonction checkMdp
	
	/**
	 * Vérifie si le courriel est bien formé ou non
	 *
	 * @param $courriel Courriel à tester
	 * @return vrai ou faux suivant le courriel passé
	 */
	function checkCourriel($courriel) {
		return filter_var($courriel, FILTER_VALIDATE_EMAIL);
	}
	
	/**
	 * Inscrit dans la base le nouvel utilisateur
	 *
	 * @param $pseudo Pseudo à insérer
	 * @param $mdp Mot de passe
	 * @param $email Courriel de l'utilisateur
	 * @param $profil Id du profil demandé
	 * @return Code de retour (bien passé, pseudo existant, courriel existant)
	 */
	function inscription($pseudo, $mdp, $email, $profil) {

		// Exécution de la requête
		// Je définie le "modèle" de ma requête
		$req = "INSERT INTO utilisateur (pseudo, password, email, id_profil) " .
			   "VALUES (:pseudo, :password, :email, :profil);";
		// Je prépare ma requête et j'obtient un objet la représentant
		$pdoStmt = $pdo->prepare($req);
		// J'associe à ma requête le contenu des variables
		$pdoStmt->bindParam(':pseudo', $pseudo);
		$pdoStmt->bindParam(':password', $mdp);
		$pdoStmt->bindParam(':email', $email);
		$pdoStmt->bindParam(':profil', $profil);
		// J'exécute ma requête
		try {
			$pdoStmt->execute();
		} catch(PDOException $e) {
			// En cas d'erreur, je récupère le code
			$codeErr = $e->getCode();
			switch($codeErr) {
				case 23000: // C'est une valeur déjà présente dans la table
					// "pseudo" n'est pas présent dans le message
					if (strpos($e->getMessage(), "pseudo") === false ) {
						// C'est donc le courriel qui est déjà présent
						return INSCR_EMAIL_EXIST;
					} else {
						// C'est bien le pseudo qui existe déjà
						return INSCR_PSEUDO_EXIST;
					}
				default:	
					// juste pour d'éventuelles gestions de nouvelles erreurs
					die($e->getCode() . " / " . $e->getMessage());
			}		
		}
		$pdoStmt = NULL; // On "désalloue" l'objet représentant la requête
		$pdo = NULL; // On "désalloue" l'objet de la connexion -> fin de la cnx
		return INSCR_OK; // Tout s'est bien passé, on renvoie "OK"
	} // Fin de la fonction inscription
	
	
	/**
	 * Affiche la page HTML indiquant le résultat de l'inscription
	 *
	 * @param $msg Message à afficher
	 * @param $res Cela s'est-il bien passé ou non ?
	 */
	function afficheResultat($msg, $res) {
		global $self;
		// Si cela s'est bien passé
		if ($res) {
			$classRes = "resOK";
			$retour = $self . "?action=fauthentification";
		} else { // Sinon
			$classRes = "resKO";
			$retour = $self . "?action=finscription";
		}
		include(__DIR__ . "/../html/inscription_res.html");
		die();
	}
	
	// Si il y a eu soumission de formulaire
	if (isset($_POST["pseudo"])) {
		// Alors on procède à l'inscription
		if (checkPseudo($_POST["pseudo"])) {
			// on continue à checker
			if (checkMdp($_POST["mdp"], $_POST["mdp1"])) {
				if (checkCourriel($_POST["email"])) {
					$res = inscription($_POST["pseudo"], 
									md5($_POST["mdp"]),
									$_POST["email"],
									$_POST["profil"]);
					switch($res) {
						case INSCR_OK:
							afficheResultat("Vous avez &eacute;t&eacute; bien inscrit !",
											true);
						case INSCR_PSEUDO_EXIST:
							afficheResultat("Votre pseudo est d&eacute;j&agrave; pr&eacute;sent...",
											false);
						case INSCR_EMAIL_EXIST:
							afficheResultat("Votre courriel est d&eacute;j&agrave; utilis&eacute;...",
											false);
					}		
				} else {
					afficheResultat("le courriel est mal form&eacute;...",
									false);
				}
			} else {
				afficheResultat("les mots de passe ne correspondent pas...",
								false);
			}	
		} else {
			afficheResultat("le pseudo n'est pas correct...",
							false);
		}
	} else {
		// Si pas de soumission de formulaire, on affiche le formulaire
		$lesProfils = creerListeProfils();
		include(__DIR__ . '/../html/inscription.html');
	}