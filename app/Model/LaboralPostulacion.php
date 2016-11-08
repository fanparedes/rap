<?php

App::uses('AppModel', 'Model');

class LaboralPostulacion extends AppModel {
	
	var $useTable = 'RAP_LABORALES_POSTULACIONES';
	var $primaryKey = 'CODIGO';
	var $name = 'LaboralPostulacion';
	
	function eliminar($codigo = null){
		$sql = "DELETE FROM RAP_LABORALES_POSTULACIONES WHERE CODIGO = '".$codigo."'";
		$this->query($sql);
		return TRUE;
	}	
}
