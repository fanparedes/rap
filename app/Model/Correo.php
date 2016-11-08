<?php

App::uses('AppModel', 'Model','Administrativo');
App::uses('BaseLog', 'Log/Engine');
App::uses('CakeEmail', 'Network/Email');


class Correo extends AppModel {
	var $name = 'Correo';
	var $useTable="RAP_EMAILS";	
	
	
	//Esta función obtendrá los mails que posteriormente se enviarán. 
	function obtenermails() {
		
		$hoy = getdate();
		$hoy = ''.$hoy['mday'].'/'.$hoy['mon'].'/'.$hoy['year'].'';		
		$options['conditions'] = array('Correo.estado' => 'PENDIENTE',
										"Correo.fecha_envio BETWEEN TO_DATE('".$hoy." 00:00:00','DD-MM-YYYY HH24:MI:SS') AND TO_DATE('".$hoy." 23:59:59','DD-MM-YYYY HH24:MI:SS')"
		
										);
		$options['fields'] = array('Correo.id', 'Correo.codigo_postulacion', 'Correo.etapa', 'Correo.fecha_envio',  'Correo.estado' ,  'Correo.intentos' ,
									'Postulaciones.codigo', 'Postulaciones.postulante_codigo', 'Postulantes.email', 'Postulantes.nombre');
		$options['joins'] = array(
				array('table' => 'RAP_POSTULACIONES',
					'alias' => 'Postulaciones',
					'type' => 'LEFT',
					'conditions' => array(
						'Postulaciones.codigo = Correo.codigo_postulacion',
					)
				),
				array('table' => 'RAP_POSTULANTES',
					'alias' => 'Postulantes',
					'type' => 'LEFT',
					'conditions' => array(
						'Postulantes.codigo = Postulaciones.postulante_codigo',
					)
				)
		);

		$emails = $this->find('all',$options );

		return $emails;
	}
	
	
	
	
	/* MÉTODO PARA ENVIAR EMAILS DESDE CUALQUIER CONTROLADOR */
	/* EN ESTE CASO VAMOS A PASAR EL CÓDIGO DEL POSTULANTE 
	   SI EL PLAZO ES NULO ES QUE NO SE ENVIA EL PLAZO         */
	public function enviarEmail($postulante, $tipo, $plazo = null, $plazo2 = null, $carreras = null, $postulacion = null){	
			$Email = new CakeEmail('smtp');				
            $Email->emailFormat('html');
            $Email->to($postulante['Postulante']['email']);
			$Email->from('admisionespecial@duoc.cl');
			$Email->delivery = 'smtp';
			$tipo_mail = '';
			$otro = false;
			switch ($tipo) {
				case 0: //Activación de cuenta
					$Email->subject('[Portal – Admisión Especial] Registro de nueva Inscripción ');
					$Email->template('newPostulante', 'postulante');
					$Email->viewVars(array('postulante' => $postulante, 'plazo' => $plazo));
					$tipo_mail = 0;	
					break;
				case 1: //Recuperar contraseña
			        $Email->template('identificarPostulante','postulante');
					$Email->subject('[Portal – Admisión Especial] Recuperar Clave');
					$token = $postulante['Postulante']['codigo'];
					$Email->viewVars(array('postulante'=>$postulante,'token'=>$token));		
					$tipo_mail = 1;
					break;
				case 2: //Acepta la documentación del postulante RAP.
					$Email->template('aceptarDocumentos','postulante');
					$Email->subject('[Portal – Admisión Especial] Documentos Validados');					
					$Email->viewVars(array('postulante'=>$postulante,'plazo1' => $plazo, 'plazo2' => $plazo2, 'postulacion' => $postulacion));	
					$tipo_mail = 2;
					break;
				case 3: //Acepta CV RAP Y AUTOEVALUACION.
				    $Email->subject('[Portal – Admisión Especial]  CvRap y AutoEvaluación aceptada.');
				    $Email->template('cvRapAceptado','postulante');
				    $Email->viewVars(array('postulante'=>$postulante, 'plazo' => $plazo));
					$tipo_mail = 3;
					break;
				case 4: //ACEPTACIÓN DE LA POSTULACIÓN
					$Email->template('aceptado','postulante');
					$Email->subject('[Portal – Admisión Especial]  Ud. ha sido Habilitado');				    
				    $Email->viewVars(array('postulante'=>$postulante));
					$tipo_mail = 4;
					break;
				case 5: //RECHAZO DE LA POSTULACIÓN
					$Email->template('rechazado','postulante');
					$Email->subject('[Portal – Admisión Especial]  Postulación Inhabilitada');				    
				    $Email->viewVars(array('postulante'=>$postulante, 'postulacion' => $postulacion));
					$tipo_mail = 5;
					break;
				case 6: //ENVIO AL POSTULANTE CUANDO REALIZA UNA PREPOSTULACIÓN										
					$Email->template('prepostulacioncreada','postulante');	
					$Email->subject('[Portal – Admisión Especial]  Solicitud de habilitación generada ('.$postulacion.')');			    
				    $Email->viewVars(array('postulante'=>$postulante, 'postulacion' => $postulacion));
					$tipo_mail = 6;
					break;
				case 7: 
					//SE CREA UNA POSTULACIÓN Y SE ENVÍA UN CORREO AL COORDINADOR GENERAL PARA ALERTARLE DE TAL HECHO
					$Administrativo = ClassRegistry::init('Administrativo');	
					$administrativos = $Administrativo->find('all', array('conditions' => array('Administrativo.perfil' => 4)));
					foreach ($administrativos as $administrativo){
							$Email->to($administrativo['Administrativo']['email']);
							$Email->template('prepostulacionNueva','coordinador');
							$Email->subject('[Portal – Admisión Especial]  Se ha creado una nueva postulación ('.$postulacion.')');				    
							$Email->viewVars(array('postulante'=>$postulante, 'postulacion' => $postulacion));
							$Email->send();
					}
					return true;
					break;
				case 8: // Se avisa al postulante que la documentación ha sido invalidada.
					$Email->subject('[Portal – Admisión Especial] Documentos con observaciones ('.$postulacion.')');
					$Email->template('documentosinvalidos', 'postulante');
					$Email->viewVars(array('postulante'=>$postulante, 'postulacion' => $postulacion));
				break;
				case 9: // Se avisa al postulante que la documentación ha sido validada.
					$Email->subject('[Portal – Admisión Especial] Documentación aprobada. Debe completar el formulario de postulación ('.$postulacion.')');
					$Email->template('postulacionaceptada', 'postulante');
					$Email->viewVars(array('postulante'=>$postulante, 'postulacion' => $postulacion));
				break;
				case 10: // Se avisa al coordinador que se ha habilitado un postulante por parte de un administrativo.
					$Administrativo = ClassRegistry::init('Administrativo');	
					$administrativos = $Administrativo->find('all', array('conditions' => array('Administrativo.perfil' => 4)));
					foreach ($administrativos as $administrativo){
							$Email->to($administrativo['Administrativo']['email']);
							$Email->template('postulantehabilitado','coordinador');
							$Email->subject('[Portal – Admisión Especial]  Se ha habilitado a un postulante ('.$postulacion.')');				    
							$Email->viewVars(array('postulante'=>$postulante, 'postulacion' => $postulacion));
							$Email->send();
							return true;
					}
				break;
				case 11: // Se avisa al coordinador general que se ha 
					$Administrativo = ClassRegistry::init('Administrativo');	
					$administrativos = $Administrativo->find('all', array('conditions' => array('Administrativo.perfil' => 4)));
					foreach ($administrativos as $administrativo){
							$Email->to($administrativo['Administrativo']['email']);
							$Email->template('postulanteinhabilitado','coordinador');
							$Email->subject('[Portal – Admisión Especial]  Se ha inhabilitado a un postulante ('.$postulacion.')');				    
							$Email->viewVars(array('postulante'=>$postulante, 'postulacion' => $postulacion));
							$Email->send();
							return true;
					}
				break;
				case 12: // Se avisa al administrativo RAP que una persona ha accedido al sistema RAP
					$Administrativo = ClassRegistry::init('Administrativo');	
					$administrativos = $Administrativo->find('all', array('conditions' => array('Administrativo.perfil' => 1, 'Administrativo.tipo' => 'RAP')));
					foreach ($administrativos as $administrativo){
							if ($administrativo['Administrativo']['email'] !== null){
								$Email->to($administrativo['Administrativo']['email']);
								$Email->template('postulanterap','coordinador');
								$Email->subject('[Portal – Admisión Especial]  Nueva Solicitud de habilitación RAP ('.$postulacion.')');				    
								$Email->viewVars(array('postulante'=>$postulante, 'postulacion' => $postulacion));
								if ($Email->send()){
									return true;
								}
								else {
									return false;							
							}
							}
					}
				break;
				case 13: // Se avisa al administrativo AV que una persona ha accedido al sistema aV
					//$Administrativo = ClassRegistry::init('Administrativo');	
					$Administrativo = ClassRegistry::init('Administrativo');	
					$administrativos = $Administrativo->find('all', array('conditions' => array('Administrativo.perfil' => 1, 'Administrativo.tipo' => 'AV', 'Administrativo.email <>' => null)));
					$errores = 0;
					foreach ($administrativos as $administrativo){		
							if ($administrativo['Administrativo']['email'] !== null){								
								$Email = new CakeEmail('smtp');				
								$Email->emailFormat('html');							
								$Email->from('admisionespecial@duoc.cl');
								$Email->delivery = 'smtp';		
								$Email->to($administrativo['Administrativo']['email']);
								$Email->template('postulanteav','coordinador');
								$Email->subject('[Portal – Admisión Especial]  Nueva Solicitud de habilitación ('.$postulacion.')');				    
								$Email->viewVars(array('postulante'=>$postulante, 'postulacion' => $postulacion));	
								if ($Email->send() == true){
									CakeLog::write('success', 'Email a ' .$administrativo['Administrativo']['email'].' SE ENVÍO CORRECTAMENTE: AVISO ARTICULACIÓN', 'email');
									$errores++;
								}
								else {
									CakeLog::write('alert', 'Email a '.$administrativo['Administrativo']['email'].' NO SE PUDO ENVIAR: AVISO ARTICULACIÓN', 'email');
									$errores++;
								}
							}
					}
					if ($errores == 0){
						return true;
					}
					else {
						return false;
					}
				break;
				case 14: // Se avisa al administrativo AH que una persona ha accedido al sistema ESCUELAS	
					$Escuela = ClassRegistry::init('Escuela');	
					$EscuelaCarrera = ClassRegistry::init('EscuelaCarrera');	
					$Administrativo = ClassRegistry::init('Administrativo');	
					$administrativos = $Administrativo->find('all', array('conditions' => array('Administrativo.perfil' => 1, 'Administrativo.tipo' => 'AH')));
					foreach ($administrativos as $k => $administrativo){									
								$k = '';									
								$k = new CakeEmail('smtp');
								$k->delivery = 'smtp';					
								$k->emailFormat('html');							
								$k->from('admisionespecial@duoc.cl');	
								$k->subject('[Portal - Admision Especial] Nueva Solicitud de habilitación en su ESCUELA ('.$postulacion.')');
								$k->template('postulanteah','coordinador');	
								if ($administrativo['Administrativo']['email'] !== null){
									$carreras2 = $EscuelaCarrera->find('all', array(
												'joins' => array(
													array(
														'table' => 'RAP_CARRERAS',
														'alias' => 'carreraJoin',
														'type' => 'INNER',
														'conditions' => array(
															'carreraJoin.codigo = EscuelaCarrera.carrera_codigo'
														)
													)
												),
												'conditions' => array(
													'EscuelaCarrera.escuela_codigo' => $administrativo['Administrativo']['escuela_id'],
												),
												'fields' => array('carreraJoin.codigo', 'carreraJoin.nombre'),
												'order' => 'carreraJoin.nombre ASC'
									)); 									
									if ($this->compararCarreras($carreras, $carreras2) == true){	
											$administrativo['Administrativo']['email'];								
											$k->to();							
											$k->to($administrativo['Administrativo']['email']);							
											$k->viewVars(array('postulante'=>$postulante));											
  											if ($k->send() == true){
												CakeLog::write('success', 'Email a '.$administrativo['Administrativo']['email'].' SE ENVÍO CORRECTAMENTE: AVISO ESCUELA', 'email');																	
											}
											else {
												CakeLog::write('alert', 'Email a '.$administrativo['Administrativo']['email'].' NO SE PUDO ENVIAR: AVISO ESCUELA', 'email');													
											}  		
									}
 									else {
										CakeLog::write('alert', 'NO EXISTEN ESCUELAS ASOCIADAS A LOS ADMINISTRADORES PARA ENVIAR EMAIL', 'email');																		
									} 
								}									
					} //ENDFOREACH
					break;
					case 15: //ENVIO AL POSTULANTE CUANDO REALIZA UNA POSTULACIÓN										
						$Email->template('prepostulacionactualizada','postulante');	
						$Email->subject('[Portal – Admisión Especial]  Postulación Modificada ('.($postulacion).')');			    
						$Email->viewVars(array('postulante'=>$postulante, 'postulacion' => $postulacion));
					break;
					case 16: //Se envia un email a cada administrativo de la misma carrera					
						$administrativoCarrera = ClassRegistry::init('AdministrativoCarrera');						
						$administrativos = $administrativoCarrera->find('all', array('conditions' => array('carrera_id' => $carreras[0]['Carrera']['codigo'])));						
						$administrativos2 = '(';	
						$numero = count($administrativos);
						$contador = '';
						foreach ($administrativos as $key => $administrativo){	
								$contador++;
								$administrativos2 .= "".$administrativo['AdministrativoCarrera']['administrativo_id'];
									if ($contador < $numero){
										$administrativos2 .= ",";
									}
								}
						$administrativos2 .= ')';						
						$EscuelaCarrera = ClassRegistry::init('EscuelaCarrera');	
						$Administrative = ClassRegistry::init('Administrativo');	
						$administrativos = $Administrative->find('all', array('conditions' => array('Administrativo.codigo IN '.$administrativos2.'', 'Administrativo.perfil' => '1')));			
						if (count($administrativos) > 0){							
							foreach ($administrativos as $j => $administrativo){
									$j = '';									
									$j = new CakeEmail('smtp');
									$j->delivery = 'smtp';					
									$j->emailFormat('html');							
									$j->from('admisionespecial@duoc.cl');	
									$j->subject('[Portal - Admision Especial] Nueva Solicitud de habilitación para una CARRERA ASIGNADA SUYA');
									$j->template('postulanteah','coordinador');	
									$j->to($administrativo['Administrativo']['email']);
									if ($administrativo['Administrativo']['email'] !== null){																			
												$j->to($administrativo['Administrativo']['email']);							
												$j->viewVars(array('postulante'=>$postulante, 'postulacion' => $postulacion));											
												if ($j->send() == true){
													CakeLog::write('success', 'Email a '.$administrativo['Administrativo']['email'].' SE ENVÍO CORRECTAMENTE: AVISO CARRERA', 'email');																	
												}
												else {
													CakeLog::write('alert', 'Email a '.$administrativo['Administrativo']['email'].' NO SE PUDO ENVIAR: AVISO CARRERA', 'email');													
												}  	
									}									
						}
						$otro = true;
						break;
					} //ENDFOREACH		
					break;
			} //FIN SWITCH
			if ($otro <> true){
				if ($Email->send() ){
					CakeLog::write('success', 'Email a '.$postulante['Postulante']['email'].' SE ENVÍO CORRECTAMENTE AVISO N#'.$tipo_mail.'#', 'email');
					return true;
				} 
				else {
					CakeLog::write('alert', 'Email a '.$postulante['Postulante']['email'].' NO SE PUDO ENVIAR EL AVISO N#'.$tipo_mail.'#', 'email');
					return false;
				}
			}
	}
	
	
	private function compararCarreras($carreras, $carreras2){
		foreach ($carreras as $carrera){
			if (in_array ( $carrera , $carreras2 ) == true){
				return true;
				//break;				
			}
		}
		
		return false;
	}
	
}
?>