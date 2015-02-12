<?php

/**
* Classe Request
*
* Cette classe analyse l'url et en extrait le controleur et l'action à charger
*/
class Request {

	/**
	* @var string $_url Url du navigateur
	*
	* @access private
	*/
	private $_url;

	/**
	* @var string $_controller Controller à charger
	*
	* @access private
	*/
	private $_controller;

	/**
	* @var string $_action Action à executer
	*
	* @access private
	*/
	private $_action;

	/**
	* @var string[] $_params Paramètres de l'action
	* @access private
	*/
	private $_params = array();

	/**
	* Constructeur
	*
	* Permet de découper l'url en controller/action/paramètres
	*
	* @param string[] $url Url du navigateur
	*
	* @return void
	*/
	public function __construct($url) {

		$this -> _url = $url;
		$this -> _controller = (!empty($this -> _url['controller'])) ? $url['controller'] : 'article';
		$this -> _action = (!empty($this -> _url['action'])) ? $url['action'] : 'index';
		$this -> _params = (!empty($this -> _url['params'])) ? explode('/', rtrim($url['params'], '/')) : '';
		
	}

	/**
	* Getter controller
	*
	* Retourne le controller
	*
	* @return void
	*/
	public function controller() {

		return $this -> _controller;

	}

	/**
	* Getter action
	*
	* Retourne l'action'
	*
	* @return void
	*/
	public function action() {

		return $this -> _action;

	}

	/**
	* Getter params
	*
	* Retourne les paramètres
	*
	* @return void
	*/
	public function params() {

		return $this -> _params;

	}

}