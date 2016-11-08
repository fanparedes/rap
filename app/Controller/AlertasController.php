<?php
	App::uses('AppController', 'Controller');
	App::uses('CakeEmail', 'Network/Email');

	
	
class AlertasController extends AppController {	
	var $uses = array('Plazo','Correo');
	var $layout = 'administrativos';
	
	function index(){	
	
	
	}	
	
	
	
}
?>
	