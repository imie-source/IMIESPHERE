<?php

/**
* Classe Model
*
* Cette classe effectue les interactions avec la base de données
*/
class Model {

	/**
	* @var $_db Instance de PDO
	* @access private
	*/
	private $_db;

	/**
	* Constructeur
	*
	* Permet de stocker l'objet PDO
	*
	* @param PDO Instance de la classe PDO
	*
	* @return void
	*/
	public function __construct(PDO $pdo) {

		$this -> _db = $pdo;

	}

	/**
	* Fonction countArticles
	*
	* Renvoi le nombre d'articles en base de données
	*
	* @return int Nombre d'articles
	*/
	public function countArticles() {

		try {

			$req = $this -> _db -> query('SELECT COUNT(id) AS nb FROM article');

		// Si la requete echoue
		} catch (PDOException $e) {

			// Levée d'une exception serveur MySQL
			new ErrorController('server');
			die();

		}

		// Si la base de données renvoie un résulat, on le retourne
		if ($data = $req -> fetch(PDO::FETCH_ASSOC))
			return $data['nb'];

	}

	/**
	* Fonction getArticlesList
	*
	* Renvoi les données des articles entre des bornes d'affichage précises
	*
	* @param int $start Début des articles à récupérer
	* @param int $toDisplay Fin des articles à récupérer
	*
	* @return mixed[] Données des articles
	*/
	public function getArticlesList($start, $toDisplay) {

		$req = $this -> _db -> prepare('SELECT id_article AS id, title_article AS title, LEFT(content, 400) AS content, DATE_FORMAT(publication_article, "%d/%m/%Y à %Hh%i") AS publish, id_utilisateur AS username FROM article a INNER JOIN utilisateur u ON a.id_utilisateur = u.id_utilisateur ORDER BY a.id_article DESC LIMIT :start, :toDisplay');
		$req -> bindValue(':start', $start - 1, PDO::PARAM_INT);
		$req -> bindValue(':toDisplay', $toDisplay, PDO::PARAM_INT);

		try {

			$req -> execute();

		// Si la requete echoue
		} catch(PDOException $e) {

			// Levée d'une exception serveur MySQL
			new ErrorController('server');
			die();

		}

		// Si la base de données renvoie un résulat, on le retourne
		if ($data = $req -> fetchAll(PDO::FETCH_ASSOC)) {

			return $data;

		}

	}

	/**
	* Fonction getArticles
	*
	* Renvoi l'id et le titre de tous les articles en base de données
	*
	* @return mixed[] Données des articles
	*/
	public function getArticles() {

		try {

			$req = $this -> _db -> query('SELECT id_article AS id, title_article AS title FROM article');

		// Si la requete echoue
		} catch(PDOException $e) {

			// Levée d'une exception serveur MySQL
			new ErrorController('server');
			die();

		}

		// Si la base de données renvoie un résulat
		if ($data = $req -> fetchAll(PDO::FETCH_ASSOC)) {

			// Pour i variant de 0 au nombre de données renvoyées
			for ($i = 0; $i < sizeof($data); $i++) {

				// On parse l'id récupéré en entier
				$data[$i]['id'] = intval($data[$i]['id']);

			}

			// On retourne les données
			return $data;

		}

	}

	/**
	* Fonction getArticle
	*
	* Renvoi les données d'un article en fonction de son identifiant en base de données
	*
	* @param int $id Identifiant de l'article
	*
	* @return mixed[] Données de l'article
	*/
	public function getArticle($id) {

		if (is_int($id)) {

			$req = $this -> _db -> prepare('SELECT title_article AS title, content_article AS content, DATE_FORMAT(publication_article, "%d/%m/%Y à %Hh%i") AS publish, DATE_FORMAT(edition_article, "%d/%m/%Y à %Hh%i") AS edited, pseudo AS username FROM article a INNER JOIN utilisateur u ON a.id_utilisateur = u.id_utilisateur WHERE a.id_article = :idArticle');
			$req -> bindValue(':idArticle', $id);

			try {

				$req -> execute();

			// Si la requete echoue
			} catch(PDOException $e) {

				// Levée d'une exception serveur MySQL
				new ErrorController('server');
				die();

			}

			// Si la base de données renvoie un résulat
			if ($data = $req -> fetch(PDO::FETCH_ASSOC)) {

				// Si aucune édition n'a été faite sur l'article, destruction de la donnée d'édition
				if ($data['edited'] == '00/00/0000 à 00h00')
					unset($data['edited']);

				// On retourne les données
				return $data;

			}

		}

	}

	/**
	* Fonction getAdminArticles
	*
	* Renvoi l'id, le titre, le pseudo, la date de publication et d'édition de tous les articles en base de données
	*
	* @return mixed[] Données des articles
	*/
	public function getAdminArticles() {

		try {

			$req = $this -> _db -> query('SELECT a.id_article AS id, title_article AS title, DATE_FORMAT(publication_article, "%d/%m/%Y à %Hh%i") AS publish, DATE_FORMAT(edition_article, "%d/%m/%Y à %Hh%i") AS edited, pseudo AS username FROM article a INNER JOIN user u ON a.id_utilisateur = u.id_utilisateur GROUP BY a.id_article DESC');

		// Si la requete echoue
		} catch (PDOException $e) {

			// Levée d'une exception serveur MySQL
			new ErrorController('server');
			die();

		}

		// Si la base de données renvoie un résulat
		if ($data = $req -> fetchAll(PDO::FETCH_ASSOC)) {

			// Pour i variant de 0 au nombre de données renvoyées
			for ($i = 0; $i < sizeof($data); $i++) {

				// Si aucune édition n'a été faite sur l'article
				if ($data[$i]['edited'] == '00/00/0000 à 00h00') {

					// Remplacement de la valeur
					$data[$i]['edited'] = 'Aucune';

				}

			}

			// On retourne les données
			return $data;

		}

	}

	/**
	* Fonction getArticleToEdit
	*
	* Renvoi le titre et le contenu d'un article en fonction de son Id
	*
	* @param int $id Identifiant de l'article
	*
	* @return mixed[] Données des articles
	*/
	public function getArticleToEdit($id) {

		if (is_int($id)) {

			$req = $this -> _db -> prepare('SELECT title_article AS title, content_article AS title FROM article WHERE id_article = :id');
			$req -> bindValue(':id', $id, PDO::PARAM_INT);

			try {

				$req -> execute();

			// Si la requete echoue
			} catch (PDOException $e) {

				// Levée d'une exception serveur MySQL
				new ErrorController('server');
				die();

			}

			// Si la base de données renvoie un résulat
			if ($data = $req -> fetch(PDO::FETCH_ASSOC)) {

				// On retourne les données
				return $data;

			}

		}

	}

	/**
	* Fonction addArticle
	*
	* Insertion d'un article en base de données
	*
	* @param string $title Titre de l'article
	* @param string $content Contenu de l'article
	*
	* @return boolean
	*/
	public function addArticle($title, $content) {

		$req = $this -> _db -> prepare('INSERT INTO article (title_article, content_article, publication_article, edition_article, id_utilisateur) VALUES (:title, :content, NOW(), "0000-00-00 00:00:00", :id_user)');
		$req -> bindValue(':title', trim($title));
		$req -> bindValue(':content', trim($content), PDO::PARAM_STR);
		$req -> bindValue(':id_user', 2, PDO::PARAM_INT);

		try {

			$req -> execute();

		// Si la requete echoue
		} catch (PDOException $e) {

			// Levée d'une exception serveur MySQL
			new ErrorController('server');
			die();

		}

		return true;

	}

	/**
	* Fonction editArticle
	*
	* Mise à jour d'un article en base de données
	*
	* @param string $id Identifiant de l'article
	* @param string $title Titre de l'article
	* @param string $content Contenu de l'article
	*
	* @return boolean
	*/
	public function editArticle($id, $title, $content) {

		if (is_int($id)) {

			$req = $this -> _db -> prepare('UPDATE article SET title_article = :title, content_article = :content, edition_article = NOW() WHERE id_article = :id');
			$req -> bindValue(':title', trim($title));
			$req -> bindValue(':content', trim($content), PDO::PARAM_STR);
			$req -> bindValue(':id', $id, PDO::PARAM_INT);

			try {

				$req -> execute();

			// Si la requete echoue
			} catch(PDOException $e) {

				// Levée d'une exception serveur MySQL
				new ErrorController('server');
				die();

			}

			return true;

		}

	}

	/**
	* Fonction removeArticle
	*
	* Suppression d'un article en base de données
	*
	* @param string $id Identifiant de l'article
	*
	* @return boolean
	*/
	public function removeArticle($id) {

		if (is_int($id)) {

			$req = $this -> _db -> prepare('DELETE FROM article WHERE id_article = :id');
			$req -> bindValue(':id', $id, PDO::PARAM_INT);

			try {

				$req -> execute();

			// Si la requete echoue
			} catch(PDOException $e) {

				// Levée d'une exception serveur MySQL
				new ErrorController('server');
				die();

			}

			return true;

		}

	}
	
}