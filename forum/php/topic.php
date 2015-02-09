<?php
	
// a tester
// rendre visuel html
// grafikart

	require_once("lib.inc.php");


	define("CRE_TOPIC_OK", 1);
	

function createTopic($libelle_topic, $id_utilisateur, $id_categorie) {
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
			$codeErr = $e->getCode();
			
			$pdoStmt = NULL;
			$pdo = NULL; 
			//return CRE_TOPIC_OK;
				
		}
	
}





		//if (isset($_POST["pseudo"])) {

		$res = createTopic($_POST["libelle_topic"], $_SESSION["id_utilisateur"], $_GET["id_categorie"]);
		//$res = createMessageTopic($_POST["contenu_message"], $_SESSION["id_utilisateur"], $_GET["id_topic"]);
		
						
	
		/*switch($res) {
			
			case CRE_TOPIC_OK : 
				echo "topic enregistrer...";
		}*/
	
		include(__DIR__ . '/../html/accueil.html');

