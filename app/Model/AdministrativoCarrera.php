<?php

App::uses('AppModel', 'Model');
/* ESTE MODELO ES LA RELACIÓN MUCHOS A MUCHOS DE CARRERAS CON ESCUELAS */
class AdministrativoCarrera extends AppModel {
	var $useTable = 'RAP_ADMINISTRATIVOS_CARRERAS';
	var $primaryKey = 'ID';
	var $name = 'AdministrativoCarrera';	

	
	/* MÉTODO QUE GUARDARÁ LAS CARRERAS A UN USUARIO DETERMINADO PASADO POR ID*/
	function guardarCarreras($carreras, $usuario_id){
				$errores = 0;
				/*BORRAMOS TODOS LOS REGISTROS DEL USUARIO*/
				$this->deleteAll(array('AdministrativoCarrera.administrativo_id' => $usuario_id), false);
				foreach ($carreras as $carrera){
					$guardado['AdministrativoCarrera']['carrera_id'] = $carrera;
					$guardado['AdministrativoCarrera']['administrativo_id'] = $usuario_id;
					if (!$this->save($guardado)){
						$errores++;
					}
				}
				if ($errores > 0){
					return false;
				}
				else{
					return true;
				}
	}
}