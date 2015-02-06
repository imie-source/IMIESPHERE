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


function getTopic($id_categorie){
	$dbConf = chargeConfiguration();
	$pdo = cnxBDD($dbConf);

	$req = "SELECT libelle_topic, id_topic, id_categorie, crea_topic, id_utilisateur ";
	$req .= "FROM topic_forum WHERE id_categorie = :id_categorie";

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

function getMsg($id_topic){
	$dbConf = chargeConfiguration();
	$pdo = cnxBDD($dbConf);

	$req = "SELECT content_msg_forum, id_msg_forum, id_topic, id_utilisateur, date_msg_forum ";
	$req .= "FROM message_forum WHERE id_topic = :id_topic";

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


if ($action == "listeTheme") {
	$liste = getThemes();
	$res = "";
	foreach($liste as $theme) {
		$res .= $theme["libelle_theme"] . ";" . $theme["id_theme"]  . "\n";
	}	
	die($res);
}else if($action == "listeCat"){
	$liste = getCat($_GET["id_theme"]);
	$res = "";
	foreach($liste as $cat) {
		$res .= $cat["libelle_categorie"] . ";" . $cat["id_categorie"]  . "\n";
	}	
	die($res);
}else if($action == "listeTopic"){
	$liste = getTopic($_GET["id_categorie"]);
	$res = "";
	foreach($liste as $topic) {
		$res .= $topic["libelle_topic"] . ";" . $topic["id_topic"] ."\n"; // ";" . $topic["id_utilisateur"] . $topic["crea_topic"] ."\n";
	}	
	die($res);
}else if($action == "listeMsg"){
	$liste = getMsg($_GET["id_topic"]);
	$res = "";
	foreach($liste as $msg) {
		$res .= $msg["content_msg_forum"] . ";" . $msg["id_msg_forum"] . "\n";// ";" . $msg["id_utilisateur"] . $msg["date_msg_forum"] ."\n";
	}	
	die($res);
}else{
	include(__DIR__ . '/../html/accueil.html');
}