<?php

class Request {

	private $_url;
	private $_controller;
	private $_action;
	private $_params = array();

	public function __construct($url) {

		$this -> _url = $url;
		$this -> _controller = (!empty($this -> _url['controller'])) ? $url['controller'] : 'article';
		$this -> _action = (!empty($this -> _url['action'])) ? $url['action'] : 'index';
		$this -> _params = (!empty($this -> _url['params'])) ? explode('/', rtrim($url['params'], '/')) : '';
		
	}

	public function controller() {

		return $this -> _controller;

	}

	public function action() {

		return $this -> _action;

	}

	public function params() {

		return $this -> _params;

	}

}