<?php
	App::uses('AppController', 'Controller');
	App::uses('CakeEmail', 'Network/Email');
	
	
class MasivosController extends AppController {	
	var $uses = array('Plazo','Correo');
	var $layout = 'administrativos';
	var $components = array('Carga');
	
	function index(){	
	
	
	}	
	
	
	
}
?>
	