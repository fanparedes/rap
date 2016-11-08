<?php
App::uses('AppController', 'Controller');
App::uses('CakeEmail', 'Network/Email');

class CargasController extends AppController {
	
	public $uses = array('Cargas','EstadoPostulacion','Postulante', 'Alerta', 'ArchivoPostulante','ArchivoPrepostulacion','Postulacion', 'Prepostulacion');
	public $layout  = 'postulaciones';
	public $components = array('Utilidades','Session');
	function beforeFilter(){
			//$this->validarAdmin();
	  
		}
	function cargarDocumentos(){
		//pr($this->data);
		$this->response->disableCache();		
		if (!empty($this->data)) {			
			$postulante = $this->Session->read('UserLogued');
			$datos = $this->data;			
			$postulante_codigo = $datos['Postulacion']['postulante_codigo'];
			$postulante_id = $postulante['Postulante']['codigo'];			
			$fecha = date('Y-M-d H:i:s');	
			$codigo = 'px';
			$codigo .= uniqid();
			$codigo .= rand(0,999);
			$data = array(
							'codigo' => $codigo,
							'postulacion_codigo' => $postulante_codigo,
							'estado_codigo' => 2,
							'fecha_cambio' => $fecha,
							'administrativo_codigo' => '',
							'postulante_codigo' =>$postulante_id,
			
			);
			if($this->EstadoPostulacion->save($data)){
							$data = array('Postulacion.codigo' => $postulante_codigo);
							$this->Postulacion->updateAll(array('Postulacion.modified' => 'SYSDATE'), $data);
 							$data2 = array('Prepostulacion.codigo_postulacion' => $postulante_codigo);
							$this->Prepostulacion->updateAll(array('Prepostulacion.modified' => 'SYSDATE'), $data2); 
							$this->Alerta->crear_alerta(2,null,$postulante_codigo);
/* 							$Email = new CakeEmail('smtp');
					        $Email->emailFormat('html');
					        $Email->to($postulante['Postulante']['email']);
					        $Email->subject('[Portal-RAP] Documentos Cargados');
					        $Email->from('rap@duoc.cl');
					        $Email->template('documentosCargados','postulante');
					        $Email->viewVars(array('postulante'=>$postulante));
							$Email->delivery = 'smtp';
							if(!$Email->send()){
								
								$this->Session->setFlash('Su documentación se ha guardado con éxito, pero hubo problemas en el envío del e-mail.', "mensaje-exito");
								$this->redirect(array('controller'=> 'postulaciones', 'action' => 'cargaDocumentos', $postulante_codigo));
							}else{
								$this->Session->setFlash('Su documentación se ha guardado con éxito.', "mensaje-exito");
								$this->redirect(array('controller'=> 'home', 'action' => 'postulantes'));	 */	
							$this->Session->setFlash('Su documentación se ha guardado con éxito. Debe esperar a que un administrativo acepte la documentación.', "mensaje-exito");
							$this->redirect(array('controller'=> 'home', 'action' => 'postulantes'));
			}
			else{
				$this->Session->setFlash('Hubo un problema al cambiar el estado', "mensaje-error");
				$this->redirect(array('controller'=> 'postulaciones', 'action' => 'cargaDocumentos', $postulante_codigo));
			}
			
					
		} else {
			$this->Session->setFlash('Hubo un problema al acceder a la documentación guardada. Consulte a la mesa de ayuda.', "mensaje-error");
			$this->redirect(array('controller'=> 'postulaciones', 'action' => 'cargaDocumentos', $postulante_codigo));		
		}
	}
	
	
	

	function descargarDocumento($codigo = null)
	{	
		if($codigo !== null)
		{
			$this->viewClass = 'Media';
			$docuemento = $this->Cargas->find('first',
				array(
					'conditions' => 
						array(
							'codigo' => $codigo
						),
					)
				);	
		    $params = array(
		        'id'        => $docuemento['Cargas']['codigo'].'.'.$docuemento['Cargas']['extension_archivo'],
		        'name'      => $docuemento['Cargas']['nombre_fisico_archivo'],
		        'extension' => '',
		        'download'  => true,
		        'path'      => $docuemento['Cargas']['path_archivo'],
		    );

	    	$this->set($params);
			
		}else
		{
			throw new NotFoundException();
		}
	}
	
	

	function descargarArchivo($codigo = null)
	{	
		if($codigo != null)
		{
			$this->viewClass = 'Media';
			$docuemento = $this->ArchivoPostulante->find('first',
				array(
					'conditions' => 
						array(
							'codigo' => $codigo
						),
					)
				);	
			#debug($docuemento); exit;		
			
		    $params = array(
		        'id'        => $docuemento['ArchivoPostulante']['codigo'].'.'.$docuemento['ArchivoPostulante']['extension'],
		        'name'      => $docuemento['ArchivoPostulante']['codigo'],
		        'extension' => '',
		        'download'  => true,
		        'path'      => WWW_ROOT.'uploads'.DS.'prepostulaciones'.DS,
		    );

	    	$this->set($params);
			
		}else
		{
			throw new NotFoundException();
		}
	}

	function descargarTrabajador(){
		$this->viewClass = 'Media';
	    $params = array(
	        'id'        => 'FUNCIONES_TRABAJADOR.DOCX',
	        'name'      => 'FUNCIONES_TRABAJADOR',
	        'extension' => '',
	        'mimeType' => array('ext' => 'mimeType/subtype'),
	        'download'  => true,
	        'path'      => WWW_ROOT.'uploads'.DS.'fichas'.DS,
	    );

    	$this->set($params);
	}
	
//	function descargarGuia(){
//		$this->viewClass = 'Media';
//		
//	    $params = array(
//	        'id'        => 'guiapostulante.pdf',
//	        'name'      => 'guiapostulante',
//	        'extension' => 'pdf',
//	        'download'  => true,
//	        'path'      => 'files/',
//	    );
//		prx($params);
//    	$this->set($params);
//	}
	
	
	function descargarGuia()
	{
		//Configure::write('debug', 2);
		
		$fileq = APP.'img/guiapostulante.pdf';
		$file = APP.'/webroot/img/'.basename($fileq);
		header('Content-Description: File Transfer');
		header('Content-Type: application/octet-stream');
		header('Content-Disposition: attachment; filename="'.basename($file).'"'); //<<< Note the " " surrounding the file name
		header('Content-Transfer-Encoding: binary');
		header('Connection: Keep-Alive');
		header('Expires: 0');
		header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
		header('Pragma: public');
		readfile($file);
	}

	
		public function descargar_anexo($codigo){
            
            if($codigo != null)
            {
                $this->viewClass = 'Media';
                $doc             = $this->ArchivoPrepostulacion->find('first',array('conditions' => array('codigo' => $codigo)));	

                $params = array(
                    'id'        => $doc['ArchivoPrepostulacion']['codigo'].'.'.$doc['ArchivoPrepostulacion']['extension'],
                    'name'      => $doc['ArchivoPrepostulacion']['codigo'],
                    'extension' => '',
                    'download'  => true,
                    'path'      => WWW_ROOT.'uploads'.DS.'prepostulaciones'.DS,
                );

            $this->set($params);

            }else
            {
                    throw new NotFoundException();
            }
        }
        
        public function descargar_archivo_postulaciones($codigo){
            
            if($codigo != null)
            {
                $this->viewClass = 'Media';
                $doc             = $this->Cargas->find('first',array('conditions' => array('codigo' => $codigo)));	

                $params = array(
                    'id'        => $doc['Cargas']['codigo'].'.'.$doc['Cargas']['extension_archivo'],
                    'name'      => $doc['Cargas']['codigo'],
                    'extension' => '',
                    'download'  => true,
                    'path'      => WWW_ROOT.'uploads'.DS.'prepostulaciones'.DS,
                );

            $this->set($params);

            }else
            {
                throw new NotFoundException();
            }
        } 



        public function descargar_archivo_antiguo($codigo){
            
            if($codigo != null)
            {
                $this->viewClass = 'Media';
                $doc             = $this->Cargas->find('first',array('conditions' => array('codigo' => $codigo)));	

                $params = array(
                    'id'        => $doc['Cargas']['codigo'].'.'.$doc['Cargas']['extension_archivo'],
                    'name'      => $doc['Cargas']['codigo'],
                    'extension' => '',
                    'download'  => true,
                    'path'      => WWW_ROOT.'uploads'.DS.'archivos'.DS,
                );

            $this->set($params);			
            }else
            {
                throw new NotFoundException();
            }
        } 
		
		
		//CAMBIA TODOS LOS NOMBRES DE UNA VEZ
		public function cambiarNombres($separacion = null, $token = null){
			if ( ($separacion == null) && ($token == null) ){
				echo 'Error de acceso al script';
			}
			else{
				$usuario = $this->Session->read('UserLogued');
				if ($usuario['Administrativo']['tipo'] <> 0){ 
					 throw new NotFoundException();	
					 $this->redirect(array('controller' => 'login','action' => 'logout'));				 
				}
				$token_real = md5('duoccl');	
				$contador = 0;			
				if ($token_real == md5($token)){
					$postulantes = $this->Postulante->find('all');					
					foreach($postulantes as $k=> $postulante){
						if ($k < 100000){
							$nombre = $postulante['Postulante']['nombre'];						
							$posicion = strpos ($nombre , ' ');							
							$nombre_nuevo = substr($nombre, 0, $posicion);							
							$apellidos = substr($nombre, $posicion+1, 150);										
							$data2 = array('Postulante.codigo' => $postulante['Postulante']['codigo']);
							$cambios = array('Postulante.nombre' => "'".$nombre_nuevo."'", 'Postulante.apellidos' => "'".$apellidos."'");	
							
							if ($postulante['Postulante']['apellidos'] == null){
								if ($this->Postulante->updateAll($cambios, $data2)){
									$contador++;
									echo 'Cambio realizado del postulante: '.$nombre_nuevo.', '.$apellidos.'<br>';							
								} 	
							}
						}					
					}								
				}
				if ($contador == 0){
						echo 'No se han realizado cambios';
					}	
			}
			die;
		}

}