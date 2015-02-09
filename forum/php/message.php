<?php

require_once("lib.inc.php");

function createMessageTopic($contenu_message, $id_utilisateur = 1, $id_topic = 1) {
		$dbConf = chargeConfiguration();
		$pdo = cnxBDD($dbConf);

		$req = "INSERT INTO message_forum (date_msg_forum, content_msg_forum, id_utilisateur, id_topic) " .
				"VALUES (NOW(), :contenu, :utilisateur, :topic);";
		$pdoStmt = $pdo->prepare($req);

		
		
		$pdoStmt->bindParam(':contenu', $contenu_message);
		$pdoStmt->bindParam(':utilisateur', $id_utilisateur);
		$pdoStmt->bindParam(':categorie', $id_topic);

		try {
			$pdoStmt->execute();
		} catch(PDOException $e) {
			
			$codeErr = $e->getCode();
			
			$pdoStmt = NULL;
			$pdo = NULL; 
	}
}



		//if (isset($_POST["pseudo"])) {

		
		$res = createMessageTopic($_POST["contenu_message"]/*, $_SESSION["id_utilisateur"], $_GET["id_topic"]*/);
		
		include(__DIR__ . '/../html/accueil.html');