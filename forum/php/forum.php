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
}
else{
	include(__DIR__ . '/../html/index.html');
}