<?php 

	/**
	 * Librairie des fonctions utilisées pour se connecter à la base de données
	 *
	 * @author Thibaud CARIÉ
	 * @since 20/01/2015
	 */

	include ("config.inc.php");

	/**
	 * Charge la configuration d'accès à la base de données
	 *
	 * @return Tableau contenant les informations
	 */
	function chargeConfiguration() {
		
		// "Chargement" du fichier contenant les couples clés;valeurs
		$ctn = file(__DIR__ . "/../" . CONF_DB_FILE);
		// Définition facultative du tableau retourné
		$res = array();

		// Pour chaque ligne du fichier à partir de la 2ème ligne
		for($i = 1; $i < count($ctn); $i++) {

			// On extrait les informations clé = valeur
			$tabLigne = explode("=", $ctn[$i]);

			// S'il n'y a pas deux éléments séparés
			if (count($tabLigne) != 2) {

				// On ne prend pas en compte la ligne
				continue;
			}

			// Sinon on "nettoie" les informations
			$cle = strtolower(trim($tabLigne[0])); // On passe en minuscule pour normer au maximum
			$valeur = trim($tabLigne[1]);

			// J'ajoute un élément dans le tableau à l'indice "cle"
			$res[$cle] = $valeur;
		}

		return $res;
	}
	
	/**
	 * Se connect à la base de données passée en paramètres
	 *
	 * @param $conf Tableau des information de connexion
	 * @return Objet PDO si connexion correcte
	 */
	function cnxBDD($conf) {

		// Connexion à la base de données
		try {

			$db = new PDO($conf["dbtype"] . ":host=" . $conf["dbhost"] . ";port=" . $conf["dbport"] . ";dbname=" . $conf["dbname"], $conf["dblogin"], $conf["dbpassword"], array(PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8', PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
		}
		
		catch (PDOException $e) {

			$db = $e -> getMessage();
	
		}

		return $db;
	}

?>