<?php
	
	require_once("lib.inc.php");

function getThemes(){
	$dbConf = chargeConfiguration();
	$pdo = cnxBDD($dbConf);

	$req = "SELECT libelle_theme, id_theme FROM theme_forum";

	$pdoStmt = $pdo->prepare($req);
		// J'exécute ma requête
		try {
			$pdoStmt->execute();
		} catch(PDOException $e) {
			// En cas d'erreur, je l'affiche et je stope le script
			die($e->getCode() . " / " . $e->getMessage());
		}
		// On récupère les enregistrements sous forme d'un tableau
		$res = $pdoStmt->fetchAll(PDO::FETCH_ASSOC);
		$pdoStmt = NULL; // On "désalloue" l'objet représentant la requête
		$pdo = NULL; // On "désalloue" l'objet de la connexion -> fin de la cnx
		return $res;
}

function getCat($id_theme){
	$dbConf = chargeConfiguration();
	$pdo = cnxBDD($dbConf);

	$req = "SELECT libelle_categorie, id_categorie, id_theme ";
	$req .= "FROM categorie_forum WHERE id_theme = :id_theme";

	$pdoStmt = $pdo->prepare($req);
	$pdoStmt->bindParam(':id_theme', $id_theme);
		// J'exécute ma requête
		try {
			$pdoStmt->execute();
		} catch(PDOException $e) {
			// En cas d'erreur, je l'affiche et je stope le script
			die($e->getCode() . " / " . $e->getMessage());
		}
		// On récupère les enregistrements sous forme d'un tableau
		$res = $pdoStmt->fetchAll(PDO::FETCH_ASSOC);
		$pdoStmt = NULL; // On "désalloue" l'objet représentant la requête
		$pdo = NULL; // On "désalloue" l'objet de la connexion -> fin de la cnx
		return $res;
}


/* Recuperer les données dans la bdd pour les topics */
function getTopic($id_categorie){
	$self = "?action=ftopic";
	$dbConf = chargeConfiguration();
	$pdo = cnxBDD($dbConf);

	// Requete pour recuperer les données de la base
	$req =	"SELECT libelle_topic as lt, tf.id_topic as id_topic, pseudo as p, id_categorie as idc, crea_topic as ct, tf.id_utilisateur as uid, ";
	$req .= "DATE_FORMAT(date_msg_forum, 'le %d %M %Y') as dmf, (SELECT COUNT(id_msg_forum) FROM message_forum WHERE message_forum.id_topic=tf.id_topic ) AS nbmsg ";
	$req .=	"FROM topic_forum tf ";
	$req .=	"LEFT JOIN message_forum mf ON tf.id_topic = mf.id_topic ";
	$req .=	"INNER JOIN utilisateur u ON tf.id_utilisateur = u.id_utilisateur ";
	$req .=	"WHERE tf.id_categorie = :id_categorie ";
	$req .= "GROUP BY id_topic ";
	$req .=	"ORDER BY date_msg_forum DESC";

	$pdoStmt = $pdo->prepare($req);
	$pdoStmt->bindParam(':id_categorie', $id_categorie);
		// J'exécute ma requête
		try {
			$pdoStmt->execute();
		} catch(PDOException $e) {
			// En cas d'erreur, je l'affiche et je stope le script
			die($e->getCode() . " / " . $e->getMessage());
		}
		// On récupère les enregistrements sous forme d'un tableau
		$res = $pdoStmt->fetchAll(PDO::FETCH_ASSOC);
		$pdoStmt = NULL; // On "désalloue" l'objet représentant la requête
		$pdo = NULL; // On "désalloue" l'objet de la connexion -> fin de la cnx
		return $res;
}


/* Recuperer les données dans la bdd pour les messages */
function getMsg($id_topic){
	$dbConf = chargeConfiguration();
	$pdo = cnxBDD($dbConf);

	// Requete pour recuperer les données dans la bdd
	$req = "SELECT content_msg_forum, id_msg_forum, id_topic, pseudo, id_utilisateur, date_msg_forum ";
	$req .= "FROM message_forum NATURAL JOIN utilisateur WHERE id_topic = :id_topic ORDER BY id_msg_forum;";

	$pdoStmt = $pdo->prepare($req);
	$pdoStmt->bindParam(':id_topic', $id_topic);
		// J'exécute ma requête
		try {
			$pdoStmt->execute();
		} catch(PDOException $e) {
			// En cas d'erreur, je l'affiche et je stope le script
			die($e->getCode() . " / " . $e->getMessage());
		}
		// On récupère les enregistrements sous forme d'un tableau
		$res = $pdoStmt->fetchAll(PDO::FETCH_ASSOC);
		$pdoStmt = NULL; // On "désalloue" l'objet représentant la requête
		$pdo = NULL; // On "désalloue" l'objet de la connexion -> fin de la cnx
		return $res;
}
/* fonction (old) Creation de topic
 * Rentre les données dans la bdd pour la création de topic 
 */	
/**
function createTopic($libelle_topic, $id_utilisateur, $id_categorie) {
		var_dump($_POST["libelle_topic"]); 
		$dbConf = chargeConfiguration();
		$pdo = cnxBDD($dbConf);

		$req = "INSERT INTO topic_forum (libelle_topic, crea_topic, id_utilisateur, id_categorie) " .
			   "VALUES (:libelle, NOW(), :utilisateur, :categorie);";
		$pdoStmt = $pdo->prepare($req);

		$pdoStmt->bindParam(':libelle', $libelle_topic);
		$pdoStmt->bindParam(':utilisateur', $id_utilisateur);
		$pdoStmt->bindParam(':categorie', $id_categorie);		

		try {
			$pdoStmt->execute();
		} catch(PDOException $e) {
			die($e->getCode() . " / " . $e->getMessage());
		}	
			$pdoStmt = NULL;
			$pdo = NULL; 				
			
}
**/

/* Rentre les données dans la bdd pour la création de message */
function createMessage($content_msg_forum, $id_utilisateur, $id_topic) {
		$dbConf = chargeConfiguration();
		$pdo = cnxBDD($dbConf);

		$req = "INSERT INTO message_forum (date_msg_forum, content_msg_forum, id_utilisateur, id_topic) " .
				"VALUES (NOW(), :contenu, :utilisateur, :topic);";
		$pdoStmt = $pdo->prepare($req);

		
		
		$pdoStmt->bindParam(':contenu', $content_msg_forum);
		$pdoStmt->bindParam(':utilisateur', $id_utilisateur);
		$pdoStmt->bindParam(':topic', $id_topic);

		try {
			$pdoStmt->execute();
		} catch(PDOException $e) {
			
			die($e->getCode() . " / " . $e->getMessage());
		}	
		
		$pdoStmt = NULL;
		$pdo = NULL; 
		 
}
/* fonction de creation de topic + message (lors de la creation d'un topic) */
function createTopicMessage($libelle_topic, $id_utilisateur, $id_categorie, $content_msg_forum/*, $id_topic*/) {
		//var_dump($_POST["libelle_topic"], $_POST["content_msg_forum"]); 
		$dbConf = chargeConfiguration();
		$pdo = cnxBDD($dbConf);

		$req = "INSERT INTO topic_forum (libelle_topic, crea_topic, id_utilisateur, id_categorie) "; 
		$req .= "VALUES(:libelle, NOW(), :utilisateur, :categorie);";
		$req .= "INSERT INTO message_forum (date_msg_forum, content_msg_forum, id_utilisateur, id_topic) ";
		$req .= "VALUES (NOW(), :contenu, :utilisateur, last_insert_id());";
		

		$pdoStmt = $pdo->prepare($req);

		$pdoStmt->bindParam(':libelle', $libelle_topic);
		$pdoStmt->bindParam(':utilisateur', $id_utilisateur);
		$pdoStmt->bindParam(':categorie', $id_categorie);
		$pdoStmt->bindParam(':contenu', $content_msg_forum);
		//$pdoStmt->bindParam(':topic', $id_topic);
		

		try {
			$pdoStmt->execute();
		} catch(PDOException $e) {
			die($e->getCode() . " / " . $e->getMessage());
		}	
			$pdoStmt = NULL;
			$pdo = NULL; 				
			
}



if ($action == "listeTheme") {
	$liste = getThemes();
	$res = "";
	foreach($liste as $theme) {
		$res .= ucfirst($theme["libelle_theme"]) . ";" . $theme["id_theme"]  . "\n";
	}	
	die($res);
}else if($action == "listeCat"){
	$liste = getCat($_GET["id_theme"]);
	$res = "";
	foreach($liste as $cat) {
		$res .= ucfirst($cat["libelle_categorie"]) . ";" . $cat["id_categorie"]  . "\n";
	}	
	die($res);
}else if($action == "listeTopic"){
	$liste = getTopic($_GET["id_categorie"]);
	$_SESSION["catEnCours"] = $_GET["id_categorie"];
	$res = "";
	foreach($liste as $topic) {
		$res .= $topic["lt"] . ";" . $topic["id_topic"] . ";" . ucfirst($topic["p"]) . ";" . $topic["dmf"] . ";" . $topic["nbmsg"] . "\n";
	}
	die($res);
}else if($action == "listeMsg"){
	$liste = getMsg($_GET["id_topic"]);
	$_SESSION["topicEnCours"] = $_GET["id_topic"];
	$res = "";
	foreach($liste as $msg) {
		$res .= $msg["content_msg_forum"] . ";" . $msg["id_msg_forum"] . ";" . ucfirst($msg["pseudo"]) . ";" . $msg["date_msg_forum"] . ";" . $msg["id_topic"] . "\n";
	}	
	die($res);
	} else if (isset($_POST["libelle_topic"]) && isset($_POST["content_msg_forum"])) {
		//$_SESSION["topicCree"] = $_GET["id_topic"];	
		$resTopMsg = createTopicMessage($_POST["libelle_topic"], $_SESSION["id_utilisateur"], $_SESSION["catEnCours"], $_POST["content_msg_forum"]);
		//die($resTopMsg);
		header("Refresh:0");
/**	
	}else if(isset($_POST["libelle_topic"])){
		$resTop = createTopic($_POST["libelle_topic"], $_SESSION["id_utilisateur"], $_SESSION["catEnCours"]);
		header("Refresh:0");
		//return $result;
**/		
	}else if(isset($_POST["content_msg_forum"])){			
		$resMsg = createMessage($_POST["content_msg_forum"], $_SESSION["id_utilisateur"], $_SESSION["topicEnCours"]);
		header("Refresh:0");
		//die($resMsg);
	}else{
	include(__DIR__ . '/../html/accueil.html');
}