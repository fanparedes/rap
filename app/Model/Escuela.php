<?php

App::uses('AppModel', 'Model');

class Escuela extends AppModel {	
	var $useTable = 'RAP_ESCUELAS';
	var $primaryKey = 'ID';
	var $name = 'Escuela';
	var $displayField = 'nombre';	

 public $validate = array(
			'nombre' => array(
				'noVacío' => array(
					'rule' => 'notEmpty',
					'message' => 'El nombre es obligatorio'
				),
				'uniqueEmailRule' => array(
							'rule' => 'isUnique',
							'message' => 'Esta escuela ya está en el sistema',
							'class' => 'invalidar',
				)
			
			)
		);
}
