<?php

App::uses('AppModel', 'Model');

class CapacitacionPostulacion extends AppModel {
	
	var $useTable = 'RAP_CAPACITACION_POSTULACIONES';
	var $primaryKey = 'CODIGO';
	var $name = 'CapacitacionPostulacion';
	
	function eliminar($codigo = null){
		$sql = "DELETE FROM RAP_CAPACITACION_POSTULACIONES WHERE CODIGO = '".$codigo."'";
		$this->query($sql);
		return TRUE;
	}
}
