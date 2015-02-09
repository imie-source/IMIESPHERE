<?php

/**
* Classe ArticleController
*
* Cette classe regroupe toutes les actions publiques
*/
class ArticleController extends Controller {

	/**
	* @var string $_articlesPerPage Nombre d'articles par page
	*
	* @access private
	*/
	private $_articlesPerPage = ARTICLES_PER_PAGE;

	/**
	* Fonction par défault si aucune spécifiée dans l'url
	*
	* Execute la fonction page
	*
	* @return void
	*/
	public function index() {

		$this -> page();

	}

	/**
	* Fonction page
	*
	* Affiche la liste des articles en fonction du nombre par page et calcule les pages
	*
	* @return void
	*/
	public function page() {

		// Récupération du nombre d'articles
		$nbArticles = $this -> model() -> countArticles();

		// Calcul du nombre total de pages
		$totalPages = (!(round($nbArticles / $this -> _articlesPerPage) < 1)) ? round($nbArticles / $this -> _articlesPerPage) : 1;

		//$totalPages = (!round(239 / $this -> _articlesPerPage) < 1) ? round(239 / $this -> _articlesPerPage) : 1;

		// Récupération de la page demandée dans l'url
		$currentPage = $this -> params(0);

		// Si la page demandée est < 1, la page sera 1
		if ($currentPage < 1)
			$currentPage = 1;

		// Si la page demandée est > nombre total de pages, la page sera la dernière
		if ($currentPage > $totalPages)
			$currentPage = $totalPages;

		// Calcul du premier article à afficher en fonction de la page et du nombre d'articles par page
		$start = ($currentPage - 1) * $this -> _articlesPerPage + 1;

		// Récupération des articles de la page courante
		$listArticles = $this -> model() -> getArticlesList($start, $this -> _articlesPerPage);

		$url = array();
		$articles = '';

		// Pour i variant de 0 au nombre d'article - 1
		for ($i = 0; $i < sizeof($listArticles); $i++) {

			// On défini l'url pour acceder à chaque article
			$url[$i] = WEB_ROOT.'article/lire/'.strtolower($this -> clean($listArticles[$i]['title'])).'-'.$listArticles[$i]['id'];

			// html des articles
			$articles .= '<a href="'.$url[$i].'"><div class="article">'
						.'<h3>'.$listArticles[$i]['title'].'</h3>'
						.'<p>'.strip_tags($listArticles[$i]['content']).'...</p>'
						.'<span>Par <em>'.ucfirst($listArticles[$i]['username']).'</em></span>'
						.'<span>Publié le <em>'.$listArticles[$i]['publish'].'</em></span></div></a>';

		}

		$navPages = '';

		// Si on est pas sur la page 1, on ajoute d'un lien "Page précédente"
		if ($currentPage != 1) {

			$navPages .= '<a href="'.WEB_ROOT.'article/page/'.($currentPage - 1).'" title="Page précédente"><</a>';

		}

		// Si la page courante est <= 5
		if ($currentPage <= 5) {

			$startPage = 1;
			$endPage = 10;

		}

		// Si la page courante est > 5 et < au nombre de pages - 5
		if ($currentPage > 5 && $currentPage < ($totalPages - 5)) {

			$startPage = $currentPage - 5;
			$endPage = $currentPage + 5;

		}

		// Si la page courante est >= aux nombres total de pages - 5
		if ($currentPage >= ($totalPages - 5)) {

			$startPage = $totalPages - 10;
			$endPage = $totalPages;

		}

		// Génération des numéros de page
		for ($i = $startPage; $i <= $endPage; $i++) {

			if ($i >= 1) {

				$navPages .= '<a ';

				if ($i == $currentPage) {

					$navPages .= 'class="active" ';

				}

				$navPages .= 'href="'.WEB_ROOT.'article/page/'.$i.'">'.$i.'</a>';

			}

		}

		// Si la page courante n'est pas la dernière page, on ajoute un lien "Page suivante"
		if ($currentPage != $totalPages) {

			$navPages .= '<a href="'.WEB_ROOT.'article/page/'.($currentPage + 1).'" title="Page suivante">></a>';

		}

		// Préparation des données à envoyer à la vue
		$vars = array(
			'title' => 'Le blog - Imiesphère',
			'nbArticles' => $nbArticles,
			'totalPages' => $totalPages,
			'currentPage' => $currentPage,
			'listArticles' => $articles,
			'navPages' => $navPages,
			'styles' => $this -> loadCSS('styles')
		);

		// Envoi des données ($vars) à la vue "articles"
		$this -> sendToRender($vars, 'articles');

	}

	/**
	* Fonction page
	*
	* Affiche la liste des articles en fonction du nombre par page et calcule les pages
	*
	* @return void
	*/
	public function lire() {

		if (preg_match('/^([A-Za-z0-9_-]+)[^-]*-(\d+)$/', $this -> params(0), $match)) {

			$articleId = intval($match[2]);
			$dbArticles = $this -> model() -> getArticles();
			$pageFound = false;

			for ($i = 0; $i < sizeof($dbArticles); $i++) {

				if (in_array($articleId, $dbArticles[$i]) && $match[1] == strtolower($this -> clean($dbArticles[$i]['title']))) {

					$pageFound = true;
					$article = $this -> model() -> getArticle($articleId);

					$vars = array(
						'title' => $article['title'],
						'content' => $article['content'],
						'dateTime' => 'Le '.$article['publish'],
						'user' => $article['username'],
						'styles' => $this -> loadCSS('styles'),
						'scripts' => $this -> loadJS('comments')
					);

					if (isset($article['edited'])) {

						$vars['editDateTime'] = $article['edited'];

					}

					$this -> sendToRender($vars, 'article');

				}

			}

			if ($pageFound === false) {

				new ErrorController('not-found');
				die();

			}

		} else {

			new ErrorController('not-found');
			die();

		}

	}

}