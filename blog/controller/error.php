<?php

class ErrorController extends Controller {

	public function __construct($type) {

		$vars = array('styles' => $this -> loadCSS('styles'));

		switch ($type) {

			case 'server':
				header($_SERVER["SERVER_PROTOCOL"]." 500 Internal Server Error");
				$vars['img'] = WEB_ROOT."view/img/500.png";
				$vars['title'] = "500 - Pas de réponse du serveur !";
				$vars['content'] = "Nos équipes travaillent activement à la résolution de l'incident.<br />
								Nous nous excusons pour la gêne occasionnée.";
				break;
			
			case 'not-found':
				header($_SERVER["SERVER_PROTOCOL"]." 404 Not Found");
				$vars['img'] = WEB_ROOT."view/img/404.png";
				$vars['title'] = "404 - Page introuvable";
				$vars['content'] = "La page demandée n'existe pas ou plus !<br />
								Vérifiez votre saisie si vous avez essayer d'y acceder manuellement.";
				break;

			case 'forbidden':
				header($_SERVER["SERVER_PROTOCOL"]." 403 Unauthorized");
				$vars['img'] = WEB_ROOT."view/img/403.png";
				$vars['title'] = "403 - Accès refusé !";
				$vars['content'] = "Cet espace est réservé aux administrateurs.<br />Connectez-vous en tant que tel pour y acceder.";
				break;

		}

		$this -> setViewDir('error');
		$this -> sendToRender($vars, 'error');

	}

}