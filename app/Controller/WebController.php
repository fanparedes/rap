<?php
App::uses('Controller', 'Controller');
App::uses('CakeEmail', 'Network/Email');

class WebController extends Controller {
	
	public $layout  = 'web-home-2016';
	
	function preguntasFrecuentes($var = null){	
		$this->set('var',$var);
		return true;		
	}
	
	function queEsRap(){
		$this->layout = "web-home-2016";
		return true;
	}
}
