<?php
	App::uses('AppController', 'Controller');
	App::uses('CakeEmail', 'Network/Email');
	App::uses('BaseLog', 'Log/Engine');

	
	
class CorreosController extends AppController {	
	var $uses = array('Plazo','Correo');
	
	
	function index(){

	}
	
	
	//FUNCIÓN QUE ENVIARÁ LOS EMAILS PROGRMADOS
	function enviar_emails(){
		//Con esto, el controlador no llama a la vista, porque no hace falta.
		$this->autoRender = false;

		//CONDICIONES DE ENVIO DE EMAIL DE HORARIO
		$hora_minima = '07:00';
		$hora_maxima = '19:00';
		
		$condiciones = false;
		//ANÁLISIS DE CONDICIONES
		$hora_actual = date("H:i");		
		
		//HORA DE ENVIO
		if (($hora_actual > $hora_minima) && ($hora_actual < $hora_maxima)) {
			$condiciones = true;			
		}
		else { 
			$condiciones = false;
		}
		
		//ÚLTIMO INTENTO DE ENVIO BUSCAMOS EL ÚLTIMO CORREO MODIFICADO 
		$minutos = 10; //MINUTOS DESDE LA ÚLTIMA VEZ QUE SE INTENTÓ ENVIAR EL ÚLTIMO EMAIL
		
		$hora_ultimo_modificado = 0;
		$ultimo_modificado = $this->Correo->find('first', array('order' => array('Correo.modified DESC'), 'conditions' => array('Correo.estado' => 'ENVIADO')));
		if (isset($ultimo_modificado['Correo']['modified'])){
			$hora_ultimo_modificado = ($ultimo_modificado['Correo']['modified']);
			$hora_ultimo_modificado = strtotime($hora_ultimo_modificado);
		}
		$hora_actual = time();
		
		//echo var_dump($hora_actual);
		//echo var_dump(($hora_ultimo_modificado + ($minutos * 60)));
		
		if (($hora_ultimo_modificado + ($minutos * 60)) >= $hora_actual){
			$condiciones = false;			
		}
		else {			
			$condiciones = true;
		}
		//ENVIO DE EMAILS SI LAS CONDICIONES SON CORRECTAS
		if ($condiciones == true) {
			
			$emails = $this->Correo->obtenermails();
			if (empty($emails)) {
				//akeLog::write('alert',	'No existen emails para enviar', 'email');
				//CakeLog::write('info',	'FIN DEL PROCESO', 'email');
			}
			else{
				foreach ($emails as $email){
					//INTENTAMOS ENVIAR EL EMAIL, SI SE ENVIA CORRECTAMENTE SE MODIFICA EL ESTADO DE LA ALERTA
					CakeLog::write('info',	'PROCESO INICIADO', 'email');					
					if ($this->tipo_mail($email['Correo']['id'], $email['Correo']['etapa'], $email['Postulantes']['email'], $email['Postulantes']['nombre'], $email['Correo']['codigo_postulacion'])){
						$this->mail_enviado($email['Correo']['id'], $email['Postulantes']['email']);
					}
					else {
						$this->mail_no_enviado($email['Correo']['id'], $email['Postulantes']['email']);							
					}					
				}
				CakeLog::write('info','FIN DEL PROCESO', 'email');
			}
		}
	}
	
	
	//FUNCIÓN QUE CRIBA EL TIPO DE EMAIL A ENVIAR DEPENDIENDO DE LA ETAPA EN LA QUE ESTÁ
	private function tipo_mail ($id, $etapa, $email, $postulante, $postulacion) {		
		$email = $email;
		if ((!$email) == null){
		switch ($etapa) {
			case 1:
					//Comprobamos que a la hora de enviar el email el estado de la postulación esté en el estado 
					//por el cuál sea menester enviarlo.
					if ($this->estadopostulacion($postulacion) == $etapa) {
						$Email = new CakeEmail('smtp');
						$Email->emailFormat('html');
						$Email->to($email);
						$Email->subject('[Portal – Admisión Especial] Aviso: Plazo Etapa');
						$Email->from('admisionespecial@duoc.cl');
						$Email->template('aviso1', 'postulante');
						$Email->viewVars(array('postulante' => $postulante));
						//echo var_dump($this->viewVars);
						$Email->delivery = 'smtp';
						if ($Email->send()) {							
							return true;
						} else {
							CakeLog::write('alert',	'Email #'.$id.' a '.$email. ' HUBO ALGÚN PROBLEMA (TÉCNICO) AL ENVIAR EL EMAIL', 'email');
							return false;
						}	
					}
					else {
						//La postulación no estaba de acuerdo a la etapa que se iba a enviar el email
						$this->mail_no_enviado_por_plazo($id, $email);							
						return false;
					}
					break;
			case 2:
					break;
			case 3:
					if (($this->estadopostulacion($postulacion) == 3) || ($this->estadopostulacion($postulacion) == 4)) {
						$Email = new CakeEmail('smtp');
						$Email->emailFormat('html');
						$Email->to($email);
						$Email->subject('[Portal – Admisión Especial] Aviso: Plazo Etapa');
						$Email->from('admisionespecial@duoc.cl');
						$Email->template('aviso3', 'postulante');
						$Email->viewVars(array('postulante' => $postulante));
						//echo var_dump($this->viewVars);
						$Email->delivery = 'smtp';
						if ($Email->send()) {
							return true;
						} else {
							CakeLog::write('alert',	'Email #'.$id.' a '.$email. ' HUBO ALGÚN PROBLEMA (TÉCNICO) AL ENVIAR EL EMAIL', 'email');
							return false;
						}	
					}
					else {
						//La postulación no estaba de acuerdo a la etapa que se iba a enviar el email
						$this->mail_no_enviado_por_plazo($id,$email);							
						return false;
					}
					break;
			case 4:
					if (($this->estadopostulacion($postulacion) == 3) || ($this->estadopostulacion($postulacion) == 4)) {
						$Email = new CakeEmail('smtp');
						$Email->emailFormat('html');
						$Email->to($email);
						$Email->subject('[Portal – Admisión Especial] Aviso: Plazo Etapa');
						$Email->from('admisionespecial@duoc.cl');
						$Email->template('aviso3', 'postulante');
						$Email->viewVars(array('postulante' => $postulante));
						//echo var_dump($this->viewVars);
						$Email->delivery = 'smtp';
						if ($Email->send()) {
							return true;
						} else {
							CakeLog::write('alert',	'Email #'.$id.' a '.$email. ' HUBO ALGÚN PROBLEMA (TÉCNICO) AL ENVIAR EL EMAIL', 'email');
							return false;
						}	
					}
					else {
						//La postulación no estaba de acuerdo a la etapa que se iba a enviar el email
						$this->mail_no_enviado_por_plazo($id,$email);		
						return false;
					}
					break;
			case 6:
					if (($this->estadopostulacion($postulacion) == 6) || ($this->estadopostulacion($postulacion) == 10)) {
						$Email = new CakeEmail('smtp');
						$Email->emailFormat('html');
						$Email->to($email);
						$Email->subject('[Portal – Admisión Especial] Aviso: Plazo Etapa');
						$Email->from('admisionespecial@duoc.cl');
						$Email->template('aviso6', 'postulante');
						$Email->viewVars(array('postulante' => $postulante));
						//echo var_dump($this->viewVars);
						$Email->delivery = 'smtp';
 						if ($Email->send()) {
							return true;
						} else {
							CakeLog::write('alert',	'Email #'.$id.' a '.$email. ' HUBO ALGÚN PROBLEMA (TÉCNICO) AL ENVIAR EL EMAIL', 'email');
							return false;
						}	 
					}
					else {
						//La postulación no estaba de acuerdo a la etapa que se iba a enviar el email
						$this->mail_no_enviado_por_plazo($id, $email);		
						return false;
					}
					break;
			case 8:
					if ($this->estadopostulacion($postulacion) == 8) {
						$Email = new CakeEmail('smtp');
						$Email->emailFormat('html');
						$Email->to($email);
						$Email->subject('[Portal – Admisión Especial] Aviso: Plazo Etapa');
						$Email->from('admisionespecial@duoc.cl');
						$Email->template('aviso6', 'postulante');
						$Email->viewVars(array('postulante' => $postulante));
						//echo var_dump($this->viewVars);
						$Email->delivery = 'smtp';
						if ($Email->send()) {
							return true;
						} else {
							CakeLog::write('alert',	'Email #'.$id.' a '.$email. ' HUBO ALGÚN PROBLEMA (TÉCNICO) AL ENVIAR EL EMAIL', 'email');
							return false;
						}	
					}
					else {
						//La postulación no estaba de acuerdo a la etapa que se iba a enviar el email
						$this->mail_no_enviado_por_plazo($id, $email);
						return false;
					}
					break;
			case 10:
					if ($this->estadopostulacion($postulacion) != 9) {
						$Email = new CakeEmail('smtp');
						$Email->emailFormat('html');
						$Email->to($email);
						$Email->subject('[Portal – Admisión Especial] Aviso: Periodo de postulación terminado.');
						$Email->from('admisionespecial@duoc.cl');
						$Email->template('aviso10', 'postulante');
						$Email->viewVars(array('postulante' => $postulante));
						//echo var_dump($this->viewVars);
						$Email->delivery = 'smtp';
						if ($Email->send()) {
							return true;
						} else {
							CakeLog::write('alert',	'Email #'.$id.' a '.$email. ' HUBO ALGÚN PROBLEMA (TÉCNICO) AL ENVIAR EL EMAIL', 'email');
							return false;
						}	
					}
					else {
						//La postulación no estaba de acuerdo a la etapa que se iba a enviar el email
						$this->mail_no_enviado_por_plazo($id, $email);
						return false;
					}
					break;

		}		
		}	else{
			CakeLog::write('alert',	'Email #'.$id.' a '.$email. ' ERROR EMAIL VACIO (POSIBLE ALERTA HUÉRFANA)', 'email');			
		}
	}
	
	
	//FUNCIÓN QUE MODIFICA EL ESTADO DEL CORREO SI HA SIDO POSITIVO EL ENVIO
	private function mail_enviado($id = null, $email) {			
			$intentos = $this->Correo->find('first', array('conditions' => array('id' => $id )));
			$intentos = $intentos['Correo']['intentos'] + 1;
			$this->Correo->id = $id;
			$this->Correo->saveField('intentos', $intentos);
			CakeLog::write('alert',	'Email #'.$id.' a '.$email. ' OK'	, 'email');				
			if ($this->Correo->saveField('estado', 'ENVIADO')) {	
				return true;
			}			
			else {
				return false;
			}	
	}
	
	
	
	//FUNCIÓN QUE MODIFICA EL ESTADO DEL CORREO SI HA SIDO NEGATIVO EL ENVÍO
	private function mail_no_enviado($id = null, $email) {			
			$intentos = $this->Correo->find('first', array('conditions' => array('id' => $id )));
			$intentos = $intentos['Correo']['intentos'] + 1;
			$this->Correo->id = $id;
			$this->Correo->saveField('intentos', $intentos);							
	}
	
	
	//FUNCIÓN QUE MODIFICA EL ESTADO DEL CORREO SI A LA HORA DE ENVIAR EL EMAIL, ESTE YA NO TENÍA SENTIDO POR ETAPA
	private function mail_no_enviado_por_plazo($id = null, $email) {			
			$intentos = $this->Correo->find('first', array('conditions' => array('id' => $id )));
			$intentos = $intentos['Correo']['intentos'] + 1;
			$this->Correo->id = $id;
			$this->Correo->saveField('intentos', $intentos);			
			$this->Correo->saveField('estado', 'EXPIRADO');
			CakeLog::write('alert',	'Email #'.$id.' a '.$email. ' EXPIRADO'	, 'email');				
	}
	
	
	//Esta función enviará un código del 1 al 11 con respecto al estado en el que se encuentre la postulación para que sepa si tiene
	// o no que enviar el email.
	private function estadopostulacion($postulacion = null) {		
		$this->loadModel('EstadoPostulacion');
		$this->loadModel('EvidenciasPrevias');
		if (empty($postulacion)) {
			$this->Session->setFlash(__('Hubo un error al intentar conseguir el estado.'),'mensaje-error');				
		}
		
		$opciones['conditions'] = array('EstadoPostulacion.postulacion_codigo' => $postulacion);
		$opciones['fields'] = array('MAX(EstadoPostulacion.estado_codigo) as maximo','EstadoPostulacion.postulante_codigo');
		$opciones['group'] = array ('EstadoPostulacion.postulante_codigo');
		$estado = $this->EstadoPostulacion->find('all',$opciones);
		//echo var_dump($estado);
		
		
		$opciones2['conditions'] = array('EvidenciasPrevias.postulacion_codigo' => $postulacion,
								'EvidenciasPrevias.preliminar' => 1,	
								'EvidenciasPrevias.validar' => 1	
								);
		$evidencias_previas = $this->EvidenciasPrevias->find('all',$opciones2);
		//echo var_dump($evidencias_previas);
		
		
		$opciones3['conditions'] = array('EvidenciasPrevias.postulacion_codigo' => $postulacion,
								'EvidenciasPrevias.preliminar' => 0,	
								'EvidenciasPrevias.validar' => 1	
								);
		$evidencias_finales = $this->EvidenciasPrevias->find('all',$opciones3);
		//echo var_dump($evidencias_finales);
		//
		//echo var_dump($estado[0][0]['maximo']);
		switch ($estado[0][0]['maximo']) {
			case 1:
				return '1';
				break;
			case 2:
				return '2';
				break;
			case 3:
				return '3';
				break;
			case 4:
				return '4';
				break;
			case 5:
				return '5';
				break;
			case 6:
				if (!empty($evidencias_previas)) {
					return '10';					
				}
				else {
					return '6';				
				}
				break;
			case 7:
					return '7';
					break;
			case 8:
				if (!empty($evidencias_finales)) {
					return '11';					
				}
				else {
					return '8';				
				}
				break;
			case 9:
					return '9';
					break;					
		}
	}	
}
?>
	