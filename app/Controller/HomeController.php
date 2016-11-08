<?php
	App::uses('AppController', 'Controller');
	
	class HomeController extends AppController {
	
		var $name = 'Home';
		var $uses = array('Postulante','Postulacion','Estado','EvidenciasPrevias','Entrevista', 'Prepostulacion');
		var $layout = "postulaciones";
		
		
		function postulantes(){
		
			$user2               = $this->Session->read('UserLogued.Postulante');
		
			if(isset($user2)){
					$this->layout	   = 'rap-postulante-2016';
					//$this->requestAction('/correos/enviar_emails'); 
					$postulante 	   = $this->Session->read('UserLogued');
					
					$prepostulaciones  = $this->Prepostulacion->find('all',array('conditions'=>array('Prepostulacion.postulante_codigo'=>$postulante['Postulante']['codigo'], 'Prepostulacion.destino' => null), 'order' => 'Prepostulacion.modified DESC'));
					$postulaciones     = $this->Postulacion->find('all',array('conditions'=>array('Postulacion.postulante_codigo'=>$postulante['Postulante']['codigo']), 'order' => 'Postulacion.modified DESC'));
					$prepostulaciones  = array_merge($prepostulaciones,$postulaciones);

					foreach ($prepostulaciones as $io => $value) {
						if($value['Postulacion']['modified'] == '' ){
							$compara = $value['Prepostulacion']['modified'];
						}else {
							$compara = $value['Postulacion']['modified'];
						}
						$auxiliar[$io] = $compara;
					}

					if(!empty($prepostulaciones)){
						array_multisort($auxiliar, SORT_DESC, $prepostulaciones);
					}

					foreach ($prepostulaciones as $k => $prepostulacion) {
						$estado = $this->obtenerEstado($prepostulacion);
						$prepostulaciones[$k]['Estado'] = $estado;
					}
					
					//REMOVER SIGUIENTE LINEA SI FINALMENTE HABRÁ MAS DE UNA POSTULACIÓN POR PERSONA		
					$this->set('prepostulaciones',$prepostulaciones);
					$this->set('postulaciones',$postulaciones);
					$this->set('postulante',$postulante);
			}
			else{
					$this->Session->setFlash(__('No se pudo actualizar, por favor inicie sesión como postulante'), 'mensaje-error');
					$this->redirect(array('controller' => 'login', 'action' => 'logout'));
					exit;
			}
			
		}
		
		function update($cod=null){
			$this->Postulante->query("UPDATE RAP_POSTULANTES a SET a.rut = '1-9', a.email = 'email@email.cl' WHERE a.codigo = '53277dcc27528'; COMMIT;");
			
			$postulaciones = $this->Postulante->find("all");
			debug($postulaciones);
			exit("UPDATE RAP_POSTULANTES a SET a.rut = '1-9', a.email = 'email@email.cl' WHERE a.codigo = '53277dcc27528'; COMMIT;");
		}
		
		// ESTE MÉTODO PRETENDE OBTENER, DE CADA POSTULACIÓN Y POSTULACIÓN EL ESTADO EN EL QUE SE ENCUENTRE
		// PARA ELLO TENDREMOS QUE DIVIDIR SI ES UNA POSTULACIÓN O UNA POSTULACIÓN
		// SI ES UNA POSTULACIÓN HABRÁ QUE VER SI ES RAP, SI ES AH O AV. 
		
		function obtenerEstado($postulacion) {
			//Si la postulación es una prepostulación 
			if (isset($postulacion['Prepostulacion'])){
					$revisado = $postulacion['Prepostulacion']['revision'];					
					if ($revisado == null): $revisado = '-1'; endif;
					$codigo = $postulacion['Postulacion']['codigo'];					
					switch ($revisado){
						case 1:
							$estado_actual = 'DOCUMENTOS CON OBSERVACIONES';							
							return ''.$estado_actual;
						case 0:
							$estado_actual = 'PENDIENTE DE REVISIÓN';
							return ''.$estado_actual;
						case '-1':
							if ($postulacion['Prepostulacion']['guardado'] == 2){
								$estado_actual = 'GUARDADA. SIN ENVIAR A REVISIÓN';
							}
							else {
								$estado_actual = 'PENDIENTE DE REVISIÓN';
							}
							return ''.$estado_actual;						
					}
			
			}
			// la postulación es una postulación, debemos determinar, qué tipo de postulación es
			else {				
				$tipo = $postulacion['Postulacion']['tipo'];
				$codigo = $postulacion['Postulacion']['codigo'];
				switch ($tipo){
					case 'RAP':
						$estado_actual = $this->Postulacion->estadoActual($codigo);
						if ($estado_actual['Estado'] == 0): unset($estado_actual); $estado_actual['Estado']['nombre'] = 'COMPLETE EL FORMULARIO PARA CONTINUAR' ; endif;
						return 'RAP: '.$estado_actual['Estado']['nombre'];
					case 'AH':
						$estado_actual = $this->Postulacion->obtenerEstadoAdmisiones($codigo);
						return $estado_actual;
					case 'AV':
						$estado_actual = $this->Postulacion->obtenerEstadoAdmisiones($codigo);
						return $estado_actual;						
				}
			}
		
		}
		
	}
?>
