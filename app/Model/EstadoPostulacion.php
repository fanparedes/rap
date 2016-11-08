<?php

App::uses('AppModel', 'Model');

class EstadoPostulacion extends AppModel {
	
	var $useTable = 'RAP_ESTADOS_POSTULACIONES';
	var $primaryKey = 'CODIGO';
	var $name = 'EstadoPostulacion';
	
	function deleteEstadoPostulacion($codigo=null){
		if(empty($codigo)){
			return false;
		}
		$sql="
			DELETE FROM RAP_ESTADOS_POSTULACIONES EstadoPostulacion WHERE EstadoPostulacion.codigo = '".$codigo."' 
		";
		$this->query($sql);
		return true;
	}
	
}
