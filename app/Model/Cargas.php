<?php

App::uses('AppModel', 'Model');

class Cargas extends AppModel{
	
	var $useTable   = 'RAP_ARCHIVOS_POSTULACIONES';
	var $primaryKey = 'CODIGO';
	var $name       = 'Cargas';
	
	
	function upload($ruta, $file, $postulante_codigo, $tipo_archivo){	
            
            if(is_dir($ruta)){
			if($file['error'] === UPLOAD_ERR_OK){
				$name         = $file["name"];
				$num          = strrpos($name, ".");
				$tipo         = substr($name, $num+1);
				$cod_archivo  = 'fi';
				$cod_archivo .= uniqid();
				$datos        = array();
				$fecha        = date('Y-M-d H:i:s ');
				
				$size = round((int)$file['size']/1024);
				if((int)$size > 30720){
					return 'El archivo: '.$name.' es demasiado grande.';
				}else{
					$id = String::uuid();
				   
					if(move_uploaded_file($file['tmp_name'], $ruta.$cod_archivo.'.'.$tipo)) {
						$data = array(
							'codigo' => $cod_archivo,
							'postulacion_codigo' => $postulante_codigo,
							'tipo_archivo_codigo' => $tipo_archivo,
							'path_archivo' => $ruta,
							'nombre_fisico_archivo' => $name,
							'extension_archivo' => $tipo,
							'size_archivo' => $size,
							'fecha_upload' => $fecha, 
							'nombre' => $name,
						);
						
						if(!$this->save($data)){
							return 'no guardo';
						}else{
							return 0;
						}
						
				    }else{
				    	return 'Error en subir el archivo: '.$name;
				    }					
				}
			}else{
				switch ($file['error']) {
					case '1':
						return 'Error, el archivo: '.$name.'es muy grande.';
						break;
					case '2':
						return 'Error, el archivo: '.$name.'es muy grande para el html.';
						break;
					case '3':
						return 'Error, el archivo: '.$name.' subió totalmente parciado.';
						break;
					case '4':
						return 'No se encontró el archivo: '.$name.'.';
						break;
					case '6':
						return 'Se perdió la carpeta de origen al intentar subir:'.$name.'.';
						break;
					case '7':
						return 'Falló la escritura del archivo: '.$name.'.';
						break;
					case '8':
						return 'Se detuvo el proceso, al intentar cargar el archivo:'.$name.'';
						break;
					
					default:
						return 'Error Grave al intentar subir: '.$name.'';
						break;
				}
			}
            }else{
                    return 'No es una ruta valida, falló la carga al intentar subir: '.$name.'';
            }
    }
}
