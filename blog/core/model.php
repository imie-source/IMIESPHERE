<?php

class Model {

	private $_db;

	public function __construct(PDO $pdo) {

		$this -> _db = $pdo;

	}

	public function countArticles() {

		try {

			$req = $this -> _db -> query('SELECT COUNT(id) AS nb FROM article');

		} catch (PDOException $e) {

			new ErrorController('server');
			die();

		}

		if ($data = $req -> fetch(PDO::FETCH_ASSOC))
			return $data['nb'];

	}

	public function getArticlesList($start, $toDisplay) {

		$req = $this -> _db -> prepare('SELECT id_article AS id, title_article AS title, LEFT(content, 400) AS content, DATE_FORMAT(publication_article, "%d/%m/%Y à %Hh%i") AS publish, id_utilisateur AS username FROM article a INNER JOIN utilisateur u ON a.id_utilisateur = u.id_utilisateur ORDER BY a.id_article DESC LIMIT :start, :toDisplay');
		$req -> bindValue(':start', $start - 1, PDO::PARAM_INT);
		$req -> bindValue(':toDisplay', $toDisplay, PDO::PARAM_INT);

		try {

			$req -> execute();

		} catch(PDOException $e) {

			new ErrorController('server');
			die();

		}

		if ($data = $req -> fetchAll(PDO::FETCH_ASSOC)) {

			return $data;

		}

	}

	public function getArticles() {

		try {

			$req = $this -> _db -> query('SELECT id_article AS id, title_article AS title FROM article');

		} catch(PDOException $e) {

			new ErrorController('server');
			die();

		}

		if ($data = $req -> fetchAll(PDO::FETCH_ASSOC)) {

			for ($i = 0; $i < sizeof($data); $i++) {

				$data[$i]['id'] = intval($data[$i]['id']);

			}

			return $data;

		}

	}

	public function getArticle($id) {

		if (is_int($id)) {

			$req = $this -> _db -> prepare('SELECT title_article AS title, content_article AS content, DATE_FORMAT(publication_article, "%d/%m/%Y à %Hh%i") AS publish, DATE_FORMAT(edition_article, "%d/%m/%Y à %Hh%i") AS edited, pseudo AS username FROM article a INNER JOIN utilisateur u ON a.id_utilisateur = u.id_utilisateur WHERE a.id_article = :idArticle');
			$req -> bindValue(':idArticle', $id);

			try {

				$req -> execute();

			} catch(PDOException $e) {

				new ErrorController('server');
				die();

			}

			if ($data = $req -> fetch(PDO::FETCH_ASSOC)) {

				if ($data['edited'] == '00/00/0000 à 00h00')
					unset($data['edited']);

				return $data;

			}

		}

	}

	public function getAdminArticles() {

		try {

			$req = $this -> _db -> query('SELECT a.id_article AS id, title_article AS title, DATE_FORMAT(publication_article, "%d/%m/%Y à %Hh%i") AS publish, DATE_FORMAT(edition_article, "%d/%m/%Y à %Hh%i") AS edited, pseudo AS username FROM article a INNER JOIN user u ON a.id_utilisateur = u.id_utilisateur GROUP BY a.id_article DESC');

		} catch (PDOException $e) {

			new ErrorController('server');
			die();

		}

		if ($data = $req -> fetchAll(PDO::FETCH_ASSOC)) {

			for ($i = 0; $i < sizeof($data); $i++) {

				if ($data[$i]['edited'] == '00/00/0000 à 00h00') {

					$data[$i]['edited'] = 'Aucune';

				}

			}

			//var_dump($data); die();
			return $data;

		}

	}

	public function getArticleToEdit($id) {

		if (is_int($id)) {

			$req = $this -> _db -> prepare('SELECT title_article AS title, content_article AS title FROM article WHERE id_article = :id');
			$req -> bindValue(':id', $id, PDO::PARAM_INT);

			try {

				$req -> execute();

			} catch (PDOException $e) {

				new ErrorController('server');
				die();

			}

			if ($data = $req -> fetch(PDO::FETCH_ASSOC)) {

				return $data;

			}

		}

	}

	public function addArticle($title, $content) {

		$req = $this -> _db -> prepare('INSERT INTO article (title_article, content_article, publication_article, edition_article, id_utilisateur) VALUES (:title, :content, NOW(), "0000-00-00 00:00:00", :id_user)');
		$req -> bindValue(':title', trim($title));
		$req -> bindValue(':content', trim($content), PDO::PARAM_STR);
		$req -> bindValue(':id_user', 2, PDO::PARAM_INT);

		try {

			$req -> execute();

		} catch (PDOException $e) {

			new ErrorController('server');
			die();

		}

		return true;

	}

	public function editArticle($id, $title, $content) {

		if (is_int($id)) {

			$req = $this -> _db -> prepare('UPDATE article SET title_article = :title, content_article = :content, edition_article = NOW() WHERE id_article = :id');
			$req -> bindValue(':title', trim($title));
			$req -> bindValue(':content', trim($content), PDO::PARAM_STR);
			$req -> bindValue(':id', $id, PDO::PARAM_INT);

			try {

				$req -> execute();

			} catch(PDOException $e) {

				new ErrorController('server');
				die();

			}

			return true;

		}

	}

	public function removeArticle($id) {

		if (is_int($id)) {

			$req = $this -> _db -> prepare('DELETE FROM article WHERE id_article = :id');
			$req -> bindValue(':id', $id, PDO::PARAM_INT);

			try {

				$req -> execute();

			} catch(PDOException $e) {

				new ErrorController('server');
				die();

			}

			return true;

		}

	}
	
}