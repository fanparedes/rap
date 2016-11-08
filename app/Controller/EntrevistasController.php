<?php
	App::uses('AppController', 'Controller');
	App::uses('CakeEmail', 'Network/Email');
	
	
class EntrevistasController extends AppController {
	var $layout = "postulaciones";	
	var $uses = array(
		'Postulacion',
		'Carrera',
		'Horario',
		'Postulante',
		'Sede',
		'Entrevista',
		'Administrativo',
		'EstadoPostulacion',
		'EvidenciasPrevias'
	);

	function agendarEntrevista(){
		$fechasDisponible = array('0' => array(
			'fechas' => array(
				'id' => '10',
				'hora' => '2013-12-21T13:15:30Z',
				'hora_fin' => '2013-12-21T13:15:30Z',
			)),
			'1' => array(
			'fechas' => array(
				'id' => '11',
				'hora' => '2013-12-22T13:15:30Z',
				'hora_fin' => '2013-12-21T13:15:30Z',
			)),
			'2' => array(
			'fechas' => array(
				'id' => '12',
				'hora' => '2013-12-23T13:15:30Z',
				'hora_fin' => '2013-12-21T13:15:30Z',
			)),
			'3' => array(
			'fechas' => array(
				'id' => '13',
				'hora' => '2013-12-24T13:15:30Z',
				'hora_fin' => '2013-12-21T13:15:30Z',
			)),
			'4' => array(
			'fechas' => array(
				'id' => '13',
				'hora' => '2013-12-24T16:15:30Z',
				'hora_fin' => '2013-12-21T13:15:30Z',
			)),
		);
		
		$this->set('fechasDisponible', $fechasDisponible);
		
	}	
	
	function guardarEntrevista($id_Hora = null){
		if($id_Hora != null){
			echo "guardar hora";
		}else{
			echo 'error de navegacion';
		}
		exit;
	}
	
	function agendaPostulante($codigo_postulacion=null){		
		$this->layout="rap-postulante";
		if($codigo_postulacion == 'undefined' || empty($codigo_postulacion)){
			$this->Session->setFlash(__('Postulación no registrada.'),'mensaje-error');
			$this->redirect(array('controller'=>'home','action'=>'postulantes'));
		}
		
		$user_logued = $this->Session->read('UserLogued');
		//$postulacion = $this->Postulacion->find('first',array('conditions'=>array('postulante_codigo'=>$user_logued['Postulante']['codigo']))); ERROR ORIGINAL 
		$postulacion = $this->Postulacion->find('first',array('conditions'=>array('Postulacion.codigo'=>$codigo_postulacion)));
		
		$carrera = $this->Carrera->findByCodigo($postulacion['Postulacion']['carrera_codigo']);
		$horarios_disponibles = $this->Horario->horariosDisponibles($carrera['Carrera']['codigo']);
		//$postulacion = $this->Postulacion->find('first',array('conditions'=>array('codigo'=>$codigo_postulacion)));
		
		$estado_actual = $this->Postulacion->estadoActual($codigo_postulacion);
		$sede = $this->Sede->find('first',array('conditions'=>array('Sede.codigo_sede'=>$postulacion['Postulacion']['sede_codigo'])));
		$carrera = $this->Carrera->find('first',array('conditions'=>array('Carrera.codigo'=>$postulacion['Postulacion']['carrera_codigo'])));
		$entrevista = $this->Entrevista->find('first', array('conditions' => array('Entrevista.postulacion_codigo' => $postulacion['Postulacion']['codigo'])));
		if(isset($entrevista) && $entrevista)
		{
			$resumen = array(
			'carrera'=>$carrera['Carrera']['nombre'],
			'sede'=>$sede['Sede']['nombre_sede'],
			'jornada'=>$postulacion['Postulacion']['jornada'],
			'estado'=>$estado_actual['Estado'],
			'entrevista' => $entrevista['Entrevista']['estado']
			);
		}else{
			$resumen = array(
			'carrera'=>$carrera['Carrera']['nombre'],
			'sede'=>$sede['Sede']['nombre_sede'],
			'jornada'=>$postulacion['Postulacion']['jornada'],
			'estado'=>$estado_actual['Estado']
			);
		}
		
		$ya_agendado = false;
		$datos_entrevista = array();
		if($estado_actual['Estado']['codigo']>=8){
			$ya_agendado = true;
			$datos_entrevista = $this->Entrevista->find('first',array(
			'joins'=>array(
				array(
					'type'=>'INNER',
					'table'=>'RAP_HORARIOS',
					'alias'=>'Horario',
					'conditions'=>array(
						'Entrevista.horario_codigo = Horario.codigo'
					)
				),
				array(
					'type'=>'INNER',
					'table'=>'RAP_ADMINISTRATIVOS',
					'alias'=>'Administrativo',
					'conditions'=>array(
						'Entrevista.administrativo_codigo = Administrativo.codigo'
					)
				),
				
			),
			'conditions'=>array(
				'Entrevista.postulacion_codigo'=>$codigo_postulacion,
				
			),
			'fields'=>array(
				'Administrativo.nombre',
				'Entrevista.codigo',
				'Entrevista.estado',
				'Horario.hora_inicio',
				'Horario.codigo',
				'Horario.hora_fin'
			)
		));
			
		}
		if (empty($datos_entrevista)) {$ya_agendado = false;}
		#debug($horarios_disponibles);
		//optener hora actual
		//$hora_Actual =  getdate();
		//$hora_Actual = $hora_Actual['hours'];
		
		//$this->set('horarios', $horarios);		
		$validados = $this->EvidenciasPrevias->find('first', array('conditions' => array('EvidenciasPrevias.postulacion_codigo' => $codigo_postulacion,
                                                                                             'EvidenciasPrevias.validar' => 1,
                                                                                             'EvidenciasPrevias.preliminar' => 0)));			
		$validado_final = '';
		if (!empty($validados)) { $validado_final = true;}			
		$this->set('validado_final',$validado_final);
		$this->set('resumen',$resumen);
		$this->set('ya_agendado',$ya_agendado);
		$this->set('codigo_postulacion',$codigo_postulacion);
		$this->set('horarios_disponibles',$horarios_disponibles);
		$this->set('datos_entrevista',$datos_entrevista);
	}
	
	function agendarHora($codigo_postulacion = null, $codigo_horario=null){		
		$this->layout='ajax';		
		if(!empty($this->data)){
			$horario = $this->Horario->find('first',array('conditions'=>array('codigo'=>$this->data['codigo_horario'])));
			if(!empty($horario)){				
				$postulacion = $this->Postulacion->find('first',array('conditions'=>array('Postulacion.codigo'=>$this->data['codigo_postulacion'])));
				if(!empty($postulacion)){
					$estado_actual = $this->Postulacion->estadoActual($postulacion['Postulacion']['codigo']);
					if((int)$estado_actual['Estado']['codigo']>=6){
						if($estado_actual['Estado']['codigo']==7){
							$this->Session->setFlash(__('Su postulación ha sido anulada, para mas información acérquese a alguna de nuestras oficinas.'),'mensaje-error');
							$this->redirect(array('controller'=>'home','action'=>'postulantes'));
						}
						$codigo_entrevista = 'in';
						$codigo_entrevista .= uniqid();
						$new_entrevista = array(
							'codigo'=>$codigo_entrevista,
							'postulacion_codigo'=>$postulacion['Postulacion']['codigo'],
							'postulante_codigo'=>$postulacion['Postulacion']['postulante_codigo'],
							'horario_codigo'=>$horario['Horario']['codigo'],
							'administrativo_codigo'=>$horario['Horario']['administrativo_codigo']
						);
						$this->Entrevista->create();
						$this->Entrevista->save($new_entrevista);
						$update_horario = array(
							'estado'=>"'AGENDADO'"
						);
						$this->Horario->query("UPDATE RAP_HORARIOS SET ESTADO = 'AGENDADO' WHERE CODIGO = '".$codigo_horario."'");
						$codigo_estado = 'px';
						$codigo_estado .= uniqid();
						$new_cambio_status = array(							
							'codigo' => $codigo_estado,
							'postulacion_codigo' => $postulacion['Postulacion']['codigo'],
							'estado_codigo' => '8',
							'fecha_cambio' => date('Y-M-d H:i:s'),
							'postulante_codigo' => $postulacion['Postulacion']['postulante_codigo']
						);
						$this->EstadoPostulacion->create();
						$this->EstadoPostulacion->save($new_cambio_status);
						$orientador = $this->Administrativo->find('first',array('conditions'=>array('Administrativo.codigo'=>$horario['Horario']['administrativo_codigo'])));
						$postulante = $this->Postulante->find('first',array('conditions'=>array('codigo'=>$postulacion['Postulacion']['postulante_codigo'])));
						//MAIL DE AVISO PARA QUE TERMINE DE SUBIR LA DOCUMENTACIÓN
							$this->LoadModel('Plazo');
							//Obtengo el plazo de tiempo destinado a esta etapa.
							$plazo = $this->Plazo->find('first',array('conditions' => array('Plazo.etapa_id' => 11)));
							//echo var_dump($plazo);	
							$plazo = $plazo['Plazo']['plazo']-1;
							$plazo = '+'.$plazo.'day';
							$hoy = $horario['Horario']['hora_inicio'];
							//echo $hoy;
							$nueva_fecha = strtotime($plazo,strtotime($hoy));
							$nueva_fecha = date('Y-m-j', $nueva_fecha);
							//echo var_dump($nueva_fecha);
							$this->LoadModel('Correo');
							$data = array(
								array('codigo_postulacion' => $codigo_postulacion,
									'etapa' => 8,
									'fecha_envio' => $nueva_fecha,
									'estado' => 'PENDIENTE',
									'intentos' => 0)
							);
							$this->Correo->saveAll($data);
/* 						//FIN DE GUARDAR DATOS DE CORREO DE AVISO
						$Email = new CakeEmail('smtp');
				        $Email->emailFormat('html');
				        $Email->to($postulante['Postulante']['email']);
				        $Email->subject('[Portal-RAP] Entrevista Agendada.');
				        $Email->from('rap@duoc.cl');
				        $Email->template('entrevistaAgendada','postulante');
						$Email->viewVars(array('postulante'=>$postulante,'horario'=>$horario,'orientador'=>$orientador));
						$Email->delivery = 'smtp';
						$Email->send();
						$this->loadModel('Alerta');
						$this->Alerta->crear_alerta(8,null,$codigo_postulacion); */
						$data = array('Postulacion.codigo' => $codigo_postulacion);
						$this->Postulacion->updateAll(array('Postulacion.modified' => 'SYSDATE'), $data); 		
						$this->Session->setFlash(__('Ha agendado su entrevista.'),'mensaje-exito');
						$this->redirect(array('controller'=>'entrevistas','action'=>'agendaPostulante',$codigo_postulacion));
					}else{
						$this->Session->setFlash(__('Ud. no cumple con los requisitos para reservar una entrevista.'),'mensaje-error');
						$this->redirect(array('controller'=>'home','action'=>'postulantes'));
					}
				}
			}else{
				$this->Session->setFlash(__('Solicitud errónea.'),'mensaje-error');
				$this->redirect(array('controller'=>'home','action'=>'postulantes'));	
			}
		}
		$horario_administrativo = $this->Horario->horarioAdministrativo($codigo_horario);
		$error = false;
		if(empty($horario_administrativo)){
			$error = true;
		}
		$this->set('error',$error);
		$this->set('codigo_postulacion',$codigo_postulacion);
		$this->set('horario_administrativo',$horario_administrativo);
	}
	
	
	
	function anularHorario($codigo_postulacion=null, $codigo_horario=null){
		
		if(empty($codigo_postulacion)){
			$this->Session->setFlash(__('Error al intentar eliminar la hora ya solicitada.'),'mensaje-error');
			$this->redirect(array('controller'=>'home','action'=>'postulantes'));
		}
		else {
			$entrevista = $this->Entrevista->find('first', array('conditions' => array('Entrevista.postulacion_codigo' => $codigo_postulacion, 'Entrevista.horario_codigo' => $codigo_horario, 'Entrevista.estado' => 'ACTIVO')));						
			//Ponemos en anulada la entrevista.
			$codigo = $entrevista['Entrevista']['codigo'];
			$postulacion_codigo = $entrevista['Entrevista']['postulacion_codigo'];
			$postulante_codigo = $entrevista['Entrevista']['postulante_codigo'];
			//Obtenemos el nombre del postulante para avisar en el email al administrativo
			$nombre_postulante = $this->Postulante->find('first',array('conditions' => array('codigo' => $entrevista['Entrevista']['postulante_codigo'])));
			$nombre_postulante = $nombre_postulante['Postulante']['nombre'];
			//Obtenemos la fecha de la entrevista
			$fecha_entrevista = $this->Horario->find('first',array('conditions' => array('codigo' => $codigo_horario)));			
			$fecha_entrevista = $fecha_entrevista['Horario']['hora_inicio'];
			$horario_codigo = $entrevista['Entrevista']['horario_codigo'];
			$administrativo_codigo = $entrevista['Entrevista']['administrativo_codigo'];
			if(!$this->Entrevista->query("DELETE FROM  RAP_ENTREVISTAS  WHERE CODIGO = '".$codigo."' AND HORARIO_CODIGO = '".$codigo_horario."'")) {
			//if(!$this->Entrevista->query("UPDATE RAP_ENTREVISTAS SET ESTADO = 'ANULADO' WHERE CODIGO = '".$codigo."' AND HORARIO_CODIGO = '".$codigo_horario."'")) {
				$estado = $this->EstadoPostulacion->find('first', array('conditions' => array('EstadoPostulacion.postulacion_codigo' => $codigo_postulacion, 'EstadoPostulacion.estado_codigo'=> 8)));
				$condition = array('EstadoPostulacion.codigo' => $estado['EstadoPostulacion']['codigo']);
				$this->loadModel('Alerta');
				$this->Alerta->crear_alerta(16,null,$postulacion_codigo);
				if ($this->EstadoPostulacion->deleteAll($condition,false)){
					$data = array('Postulacion.codigo' => $postulacion_codigo);
					$this->Postulacion->updateAll(array('Postulacion.modified' => 'SYSDATE'), $data); 	
					$this->Horario->query("UPDATE RAP_HORARIOS SET ESTADO = 'DISPONIBLE' WHERE CODIGO = '".$codigo_horario."'");
					//ENVIAMOS UN EMAIL AL ADMINISTRATIVO ALERTANDO
					$Email = new CakeEmail('smtp');
                    $Email->emailFormat('html');					
                    $Email->to('rap@duoc.cl'); //DESTINATARIO DEL EMAIL - CAMBIAR SI FUESE PRECISO
                    $Email->subject('[Portal-RAP] ALERTA: Entrevista anulada.');
                    $Email->from('rap@duoc.cl');
                    $Email->template('anulacion', 'postulante');
                    $Email->viewVars(array('postulante' => $nombre_postulante, 'fecha' => $fecha_entrevista));
                    $Email->delivery = 'smtp';
                    $Email->send();                    
					//FIN EMAIL
					$this->Session->setFlash(__('La entrevista ha sido cancelada, para completar su proceso ud debe tomar una nueva hora.'),'mensaje-exito');
					$this->redirect(array('controller'=>'home','action'=>'postulantes'));
				}
				else {	
					$this->Session->setFlash(__('No pudo anularse la entrevista, por favor contacte con la mesa de ayuda.'),'mensaje-error');
					//$this->redirect(array('controller'=>'home','action'=>'postulantes'));	
					}					
			}
			else {
				$this->Session->setFlash(__('No pudo anularse la entrevista, por favor contacte con la mesa de ayuda.'),'mensaje-error');
				//$this->redirect(array('controller'=>'home','action'=>'postulantes'));			
			}
		}		
	}	
	
	
	
	
	
	
	
	
	
	
	
	/*ESTA FUNCIÓN NO FUNCIONA HAY QUE REVISARLA
	
	
	
	
	
	
	
	
	
	function anularHorario($codigo_postulacion=null, $codigo_horario=null){
		if(empty($codigo_horario) || empty($codigo_postulacion)){
			$this->Session->setFlash(__('Error al intentar eliminar la hora ya solicitada.'),'mensaje-error');
			$this->redirect(array('controller'=>'home','action'=>'postulantes'));
		}
		$horario = $this->Horario->find('first',array('conditions'=>array('codigo'=>$codigo_horario)));
		if(!empty($horario)){
			$postulacion = $this->Postulacion->find('first',array('conditions'=>array('codigo'=>$codigo_postulacion)));
			if(!empty($postulacion)){
				#Verificar estado entrevista agendada
				$estado_actual = $this->Postulacion->estadoActual($codigo_postulacion);
				if($estado_actual['Estado']['codigo']==8){
					#Eliminar estado 8 para que vuelva al 6
					if($this->EstadoPostulacion->deleteEstadoPostulacion($estado_actual['EstadoPostulacion']['codigo'])){
						#Entrevista debe quedar en estado ANULADO
						$entrevista = $this->Entrevista->datosEntrevista($codigo_postulacion);
						if(!empty($entrevista)){
							$update_entrevista = array(
								'estado'=>"'ANULADO'"
							);
							$this->Entrevista->updateAll($update_entrevista,array('Entrevista.codigo'=>$entrevista['Entrevista']['codigo']));
							#El horario que estaba tomado debe quedar en estado disponible 
							$update_horario = array(
								'estado'=>"'DISPONIBLE'"
							);
							$this->Horario->updateAll($update_horario,array('Horario.codigo'=>$horario['Horario']['codigo']));
							$this->Session->setFlash(__('La entrevista ha sido cancelada, para completar su proceso ud debe tomar una nueva hora.'),'mensaje-exito');
							$this->redirect(array('controller'=>'entrevistas','action'=>'agendaPostulante',$codigo_postulacion));
						}else{
							$this->Session->setFlash(__('La entrevista no estaba bien agendada.'),'mensaje-error');
							$this->redirect(array('controller'=>'home','action'=>'postulantes'));
						}
					}else{
						$this->Session->setFlash(__('Error al volver estado de la postulación.'),'mensaje-error');
						$this->redirect(array('controller'=>'home','action'=>'postulantes'));
					}
				}else{
					$this->Session->setFlash(__('Error de estado.'),'mensaje-error');
					$this->redirect(array('controller'=>'home','action'=>'postulantes'));
				}
			}else{
				$this->Session->setFlash(__('Error de postulación.'),'mensaje-error');
				$this->redirect(array('controller'=>'home','action'=>'postulantes'));
			}
		}else{
			$this->Session->setFlash(__('Error de horario.'),'mensaje-error');
			$this->redirect(array('controller'=>'home','action'=>'postulantes'));
		}
		
	}*/
}	
		
