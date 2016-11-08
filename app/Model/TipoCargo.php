<?php

App::uses('AppModel', 'Model');

class TipoCargo extends AppModel {
	
	var $useTable = 'RAP_TIPOS_CARGOS';
	var $primaryKey = 'CODIGO';
	var $name = 'TipoCargo';
}
