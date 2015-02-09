<?php

/**
* Classe AdminController
*
* Cette classe regroupe toutes les actions administrateur
*/
class AdminController extends Controller {

	/**
	* Constructeur
	*
	* Permet d'initialiser les pages admin
	*
	* @param PDO Instance de la classe PDO
	* @param string[] Paramètres de l'url
	*
	* @return void
	*/
	public function __construct($pdo, $params) {

		// Si un visiteur ou un utilisateur non admin accède à l'espace admin
		if (empty($_SESSION) || $_SESSION['idProfil'] != 1) {

			// Levée d'une exception de type 403 - Accès refusé
			new ErrorController('forbidden');
			die();

		}

		// Mise à jour du dossier des vues à afficher
		$this -> setViewDir('admin');

		// Appel du constructeur de la classe Controller
		parent::__construct($pdo, $params);

	}

	/**
	* Action index
	*
	* Action par défaut si aucune appelée dans l'url
	*
	* @return void
	*/
	public function index() {

		// Execution de l'action liste
		$this -> liste();

	}

	/**
	* Action liste
	*
	* Cette action permet de lister les articles pour les voir, les modifier ou les supprimer
	*
	* @return void
	*/
	public function liste() {

		// Récupération des articles en base de données
		$list = $this -> model() -> getAdminArticles();

		// Création de l'entete du tableau
		$adminArticles = '<table><tbody><tr><th>Id</th><th>Titre</th><th>Auteur</th><th>Publication</th><th>Dernière mise à jour</th><th>Actions</th></tr>';

		// Pour i variant de 0 au nombre d'articles
		for ($i = 0; $i < sizeof($list); $i++) {

			// Génération de l'url de l'article
			$path = strtolower($this -> clean($list[$i]['title']).'-'.intval($list[$i]['id']));

			// Ajout d'une ligne dans le tableau avec l'id, le titre, l'auteur, les dates de publications et d'éditions ainsi que les lien "voir", "éditer" et "supprimer"
			$adminArticles .= '<tr><td>'.$list[$i]['id'].'</td>'
							.'<td>'.$list[$i]['title'].'</td>'
							.'<td>'.ucfirst($list[$i]['username']).'</td>'
							.'<td>'.$list[$i]['publish'].'</td>'
							.'<td>'.$list[$i]['edited'].'</td>'
							.'<td><a href="'.WEB_ROOT.'article/lire/'.$path.'">Voir</a>'
							.'<a href="'.WEB_ROOT.'admin/editer/'.intval($list[$i]['id']).'">Editer</a>'
							.'<a class="delete" href="'.WEB_ROOT.'admin/supprimer/'.$list[$i]['id'].'">Supprimer</a></td></tr>';

		}

		// Fin du tableau
		$adminArticles .= '</tbody></table>';

		// Préparation des données à envoyer à la vue
		$vars = array(
			'title' => 'Liste des articles - Administration',
			'styles' => $this -> loadCSS('styles'),
			'scripts' => $this -> loadJS('jquery:delete'),
			'articles' => $adminArticles
		);

		// Envoi des données à la vue
		$this -> sendToRender($vars, 'list');

	}

	/**
	* Action ajouter
	*
	* Cette action permet de créer un nouvel article
	*
	* @return void
	*/
	public function ajouter() {

		// Si la publication de l'article est soumise
		if (!empty($_POST)) {

			// Si tous les champs ont été remplis
			if (!empty($_POST['title']) && !empty($_POST['content'])) {

				// Ajout de l'article en base de données
				$this -> model() -> addArticle($_POST['title'], $_POST['content']);

				// Redirection vers la page d'accueil administration
				header('Location: '.WEB_ROOT.'admin');

			}

		// Sinon
		} else {

			// Préparation des données à envoyer à la vue
			$vars = array(
				'title' => 'Ajouter un article',
				'styles' => $this -> loadCSS('styles'),
				'headScripts' => $this -> loadJS('jquery:editor/ckeditor')
			);

			// Envoi des données à la vue
			$this -> sendToRender($vars, 'editor');

		}

	}

	/**
	* Action editer
	*
	* Cette action permet d'éditer un article
	*
	* @return void
	*/
	public function editer() {

		// Si l'édition de l'article à été soumise
		if (!empty($_POST)) {

			// Si tous les champs ont été remplis
			if (!empty($_POST['title']) && !empty($_POST['content'])) {

				// Mise à jour de l'articl en base de données
				$this -> model() -> editArticle(intval($this -> params(0)), $_POST['title'], $_POST['content']);

				// Redirection vers l'accueil administration
				header('Location: '.WEB_ROOT.'admin');

			}

		// Sinon
		} else {

			// Récupération de l'article en fonction de l'url
			$article = $this -> model() -> getArticleToEdit(intval($this -> params(0)));

			// Préparation des données à envoyer à la vue
			$vars = array(
				'title' => 'Modifier un article',
				'styles' => $this -> loadCSS('styles'),
				'headScripts' => $this -> loadJS('jquery:editor/ckeditor'),
				'articleTitle' => $article['title'],
				'articleContent' => $article['content']
			);

			// Envoi des données à la vue
			$this -> sendToRender($vars, 'editor');

		}

	}

	/**
	* Action supprimer
	*
	* Cette action permet de supprimer un article
	*
	* @return void
	*/
	public function supprimer() {

		// Stockage de l'identifiant de l'article à supprimer
		$articleId = intval($this -> params(0));

		// Suppression de l'article en base de données
		$this -> model() -> removeArticle($articleId);

		// Redirection vers l'accueil administration
		header('Location: '.WEB_ROOT.'admin');

	}

}