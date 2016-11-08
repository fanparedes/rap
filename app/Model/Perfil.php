<?php
App::uses('AppModel', 'Model');
class Perfil extends AppModel {
	var $name = 'Perfil';
	var $useTable="RAP_PERFILES";
	//The Associations below have been created with all possible keys, those that are not needed can be removed

	// var $hasMany = array(
		// 'Permiso'=>array('className'=>'Permiso',
						 // 'foreignKey'=>'id_perfil'),
		// 'Administrativo'=>array('className'=>'Administrativo',
						 // 'foreignKey'=>'perfil'),
	// );
	
}
?>