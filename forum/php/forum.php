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
	$req = "SELECT libelle_topic, id_topic, pseudo, id_categorie, crea_topic, id_utilisateur, ";
	$req .= "(SELECT COUNT(id_msg_forum)  FROM message_forum WHERE message_forum.id_topic= topic_forum.id_topic) AS nbmsg ";
	$req .= "FROM topic_forum NATURAL JOIN utilisateur WHERE id_categorie = :id_categorie ORDER BY id_topic DESC ";

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
	$req .= "FROM message_forum NATURAL JOIN utilisateur WHERE id_topic = :id_topic";

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
	$res = "";
	foreach($liste as $topic) {
		$res .= $topic["libelle_topic"] . ";" . $topic["id_topic"] . ";" . ucfirst($topic["pseudo"]) . ";" . $topic["crea_topic"] . ";" . $topic["nbmsg"] . "\n";
	}
	die($res);
}else if($action == "listeMsg"){
	$liste = getMsg($_GET["id_topic"]);
	$res = "";
	foreach($liste as $msg) {
		$res .= $msg["content_msg_forum"] . ";" . $msg["id_msg_forum"] . ";" . ucfirst($msg["pseudo"]) . ";" . $msg["date_msg_forum"] . ";" . $msg["id_topic"] . "\n";
	}	
	die($res);
}else if(isset($_POST["libelle_topic"])){			
			$result = createTopic($_POST["libelle_topic"], $_SESSION["id_utilisateur"], $_GET["id_categorie"]);
			die($result);
}else{
	include(__DIR__ . '/../html/accueil.html');
}