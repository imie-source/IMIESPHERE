<?php

/**
* Classe ErrorController
*
* Cette classe lève les exceptions
*/
class ErrorController extends Controller {

	/**
	* Constructeur
	*
	* Permet de lever l'exception correspondante au type renseigné
	*
	* @param string Type de l'exception
	*
	* @return void
	*/
	public function __construct($type) {

		// Préparation des données à envoyer à la vue
		$vars = array('styles' => $this -> loadCSS('styles'));

		switch ($type) {

			// Si erreur de connexion ou d'execution MySQL
			case 'server':
				header($_SERVER["SERVER_PROTOCOL"]." 500 Internal Server Error");
				$vars['img'] = WEB_ROOT."view/img/500.png";
				$vars['title'] = "500 - Pas de réponse du serveur !";
				$vars['content'] = "Nos équipes travaillent activement à la résolution de l'incident.<br />
								Nous nous excusons pour la gêne occasionnée.";
				break;
			
			// Si erreur 404 - Page introuvable
			case 'not-found':
				header($_SERVER["SERVER_PROTOCOL"]." 404 Not Found");
				$vars['img'] = WEB_ROOT."view/img/404.png";
				$vars['title'] = "404 - Page introuvable";
				$vars['content'] = "La page demandée n'existe pas ou plus !<br />
								Vérifiez votre saisie si vous avez essayer d'y acceder manuellement.";
				break;

			// Si erreur 403 - Accès non autorisé
			case 'forbidden':
				header($_SERVER["SERVER_PROTOCOL"]." 403 Unauthorized");
				$vars['img'] = WEB_ROOT."view/img/403.png";
				$vars['title'] = "403 - Accès refusé !";
				$vars['content'] = "Cet espace est réservé aux administrateurs.<br />Connectez-vous en tant que tel pour y acceder.";
				break;

		}

		// Mise à jour du dossier des vues
		$this -> setViewDir('error');

		// Envoi des données à la vue
		$this -> sendToRender($vars, 'error');

	}

}