<?php

class AdminController extends Controller {

	public function __construct($pdo, $class, $params) {

		if (empty($_SESSION) || $_SESSION['idProfil'] != 1) {

			new ErrorController('forbidden');
			die();

		}

		$this -> setViewDir('admin');
		parent::__construct($pdo, $class, $params);

	}

	public function index() {

		$this -> liste();

	}

	public function liste() {

		$list = $this -> model() -> getAdminArticles();

		$adminArticles = '<table><tbody><tr><th>Id</th><th>Titre</th><th>Auteur</th><th>Publication</th><th>Dernière mise à jour</th><th>Actions</th></tr>';

		for ($i = 0; $i < sizeof($list); $i++) {

			$path = strtolower($this -> clean($list[$i]['title']).'-'.intval($list[$i]['id']));

			$adminArticles .= '<tr><td>'.$list[$i]['id'].'</td>'
							.'<td>'.$list[$i]['title'].'</td>'
							.'<td>'.ucfirst($list[$i]['username']).'</td>'
							.'<td>'.$list[$i]['publish'].'</td>'
							.'<td>'.$list[$i]['edited'].'</td>'
							.'<td><a href="'.WEB_ROOT.'article/lire/'.$path.'">Voir</a>'
							.'<a href="'.WEB_ROOT.'admin/editer/'.intval($list[$i]['id']).'">Editer</a>'
							.'<a class="delete" href="'.WEB_ROOT.'admin/supprimer/'.$list[$i]['id'].'">Supprimer</a></td></tr>';

		}

		$adminArticles .= '</tbody></table>';

		$vars = array(
			'title' => 'Liste des articles - Administration',
			'styles' => $this -> loadCSS('styles'),
			'scripts' => $this -> loadJS('jquery:delete'),
			'articles' => $adminArticles
		);

		$this -> sendToRender($vars, 'list');

	}

	public function ajouter() {

		if (!empty($_POST)) {

			if (!empty($_POST['title']) && !empty($_POST['content'])) {

				$this -> model() -> addArticle($_POST['title'], $_POST['content']);

				header('Location: '.WEB_ROOT.'admin');

			}

		} else {

			$vars = array(
				'title' => 'Ajouter un article',
				'styles' => $this -> loadCSS('styles'),
				'headScripts' => $this -> loadJS('jquery:editor/ckeditor')
			);

			$this -> sendToRender($vars, 'editor');

		}

	}

	public function editer() {

		if (!empty($_POST)) {

			if (!empty($_POST['title']) && !empty($_POST['content'])) {

				$this -> model() -> editArticle(intval($this -> params(0)), $_POST['title'], $_POST['content']);

				header('Location: '.WEB_ROOT.'admin');

			}

		} else {

			$article = $this -> model() -> getArticleToEdit(intval($this -> params(0)));

			$vars = array(
				'title' => 'Modifier un article',
				'styles' => $this -> loadCSS('styles'),
				'headScripts' => $this -> loadJS('jquery:editor/ckeditor'),
				'articleTitle' => $article['title'],
				'articleContent' => $article['content']
			);

			$this -> sendToRender($vars, 'editor');

		}

	}

	public function supprimer() {

		$articleId = intval($this -> params(0));

		$this -> model() -> removeArticle($articleId);

		header('Location: '.WEB_ROOT.'admin');

	}

}