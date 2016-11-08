<?php

App::uses('AppModel', 'Model');

class Estado extends AppModel {
	
	var $useTable = 'RAP_ESTADOS';
	var $primaryKey = 'CODIGO';
	var $name = 'Estado';
	var $displayField = 'NOMBRE';
	
}
