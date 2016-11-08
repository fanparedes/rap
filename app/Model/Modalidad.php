<?php
App::uses('AppModel', 'Model');
class Modalidad extends AppModel {
	var $useTable="RAP_MODALIDADES";
	var $name	= 'Modalidad';
	var $primaryKey = 'ID';
	//The Associations below have been created with all possible keys, those that are not needed can be removed

	// var $belongsTo = array(
		// 'Perfil','Funcion'
	// );
	
}
?>