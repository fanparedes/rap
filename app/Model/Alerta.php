<?php
App::uses('AppModel', 'Model');

class Alerta extends AppModel {
	var $name = 'Alerta';
	var $useTable="RAP_ALERTAS";

	//Este método devolverá un si o un no en el caso de que existan alertas
	//para el administrativos
	function hay_alertas(){
		$alertas = $this->find('all');
		if (!empty($alertas)) {
			return true;	
		}		
		else {
			return false;
		}	
	}
	
	//ESTE MÉTODO AGREGA UNA ALERTA, SE USA TAMBIÉN LA TABLA PLAZOS YA QUE ES NECESARIA
	//PARA LA GENERACIÓN DE DATOS DEL MODELO - $ID SERÍA LA ETAPA QUE VA LA ALERTA
	//PARA LAS ETAPAS 0 Y 1 AUN NO HAY CÓDIGO DE POSTULACION CON LO QUE SE UTILIZA
	//EL CÓDIGO DE POSTULANTE PARA COMPLETAR LA ALERTA
	function crear_alerta($etapa, $codigo_postulante = null, $codigo_postulacion = null){	
		//Instanciamos los otros modelos.
		$Plazo = ClassRegistry::init('Plazo');	
		$Postulante = ClassRegistry::init('Postulante');	
		$Entrevista = ClassRegistry::init('Entrevista');	
		$Horario = ClassRegistry::init('Horario');	
		
		//Agregamos el plazo				
		$hoy = date('Y-m-j');
		
		//COMPROBAMOS QUE TIPO DE ALERTA ES
		switch ($etapa) {
			case 0:
				$mensaje = "NUEVO POSTULANTE REGISTRADO";
				$plazo = $Plazo->find('first', array('conditions' => array ('etapa_id' => 1)));
				$plazo = $plazo['Plazo']['plazo'];
				$postulante = $Postulante->find('first', array('conditions' => array ('codigo' => $codigo_postulante)));				
				$codigo = $postulante['Postulante']['codigo'];
				$nombre = $postulante['Postulante']['nombre'];
				$postulante1 = $codigo.'-'.$nombre;				
				//Se avisa también de que se ha registrado un nuevo postulante
					$datos1 =  array('codigo_postulacion' => $postulante1,
								'etapa' => '0',
								'mensaje'=> 'NUEVO POSTULANTE REGISTRADO',
								'fecha_activacion'=> $hoy,
								'vinculado_a_postulacion' => '1');
					$this->create();
					$this->save($datos1);										
				$mensaje = 'ALERTA DE PLAZO: FORMULARIO DE POSTULACION';
				$codigo_postulacion = $postulante1;
				$plazo = $plazo-1;
				$nuevafecha = strtotime ( '+'.$plazo.' day' , strtotime ( $hoy ) ) ;
				$nuevafecha = date ( 'Y-m-j' , $nuevafecha );	
				break;
			case 1:
				$mensaje = "ALERTA DE PLAZO: SUBIDA DOCUMENTACION";
				$plazo = $Plazo->find('first', array('conditions' => array ('etapa_id' => 2)));
				$plazo = $plazo['Plazo']['plazo'];
				$plazo = $plazo-1;
				
				$nuevafecha = strtotime ( '+'.$plazo.' day' , strtotime ( $hoy ) ) ;
				$nuevafecha = date ( 'Y-m-j' , $nuevafecha );	
				break;
			case 2:
				
				$mensaje = "SE REQUIERE REVISAR LA DOCUMENTACION BASICA";
				$nuevafecha = $hoy;
				break;
			case 3:
				$mensaje = "ALERTA DE PLAZO: CVRAP";
				$plazo = $Plazo->find('first', array('conditions' => array ('etapa_id' => 4)));
				$plazo = $plazo['Plazo']['plazo'];
				$plazo = $plazo-1;
				$nuevafecha = strtotime ( '+'.$plazo.' day' , strtotime ( $hoy ) ) ;
				$nuevafecha = date ( 'Y-m-j' , $nuevafecha );	
				break;
			case 4:
				$mensaje = "ALERTA DE PLAZO: AUTOEVALUACION";
				$plazo = $Plazo->find('first', array('conditions' => array ('etapa_id' => 5)));
				$plazo = $plazo['Plazo']['plazo'];
				$plazo = $plazo-1;
				$nuevafecha = strtotime ( '+'.$plazo.' day' , strtotime ( $hoy ) ) ;
				$nuevafecha = date ( 'Y-m-j' , $nuevafecha );	
				break;
			case 5:
				$mensaje = "SE REQUIERE REVISAR CVRAP Y AUTOEVALUACION";
				$nuevafecha = $hoy;
				break;
			case 6:
				$mensaje = "ALERTA DE PLAZO: EVIDENCIAS PREVIAS";
				$plazo = $Plazo->find('first', array('conditions' => array ('etapa_id' => 10)));
				$plazo = $plazo['Plazo']['plazo'];
				$plazo = $plazo-1;
				$nuevafecha = strtotime ( '+'.$plazo.' day' , strtotime ( $hoy ) ) ;
				$nuevafecha = date ( 'Y-m-j' , $nuevafecha );	
				break;
			case 6:
				$mensaje = "ALERTA DE PLAZO: EVIDENCIAS PREVIAS";
				$plazo = $Plazo->find('first', array('conditions' => array ('etapa_id' => 10)));
				$plazo = $plazo['Plazo']['plazo'];
				$plazo = $plazo-1;
				$nuevafecha = strtotime ( '+'.$plazo.' day' , strtotime ( $hoy ) ) ;
				$nuevafecha = date ( 'Y-m-j' , $nuevafecha );	
				break;
			case 10:
				$mensaje = "EVIDENCIAS PREVIAS VALIDADAS";
				$plazo = $Plazo->find('first', array('conditions' => array ('etapa_id' => 8)));
				$plazo = $plazo['Plazo']['plazo'];
				$plazo = $plazo-1;
				$nuevafecha = strtotime ( '+'.$plazo.' day' , strtotime ( $hoy ) ) ;
				$nuevafecha = date ( 'Y-m-j' , $nuevafecha );	
				$datos2 =  array('codigo_postulacion' => $codigo_postulacion,
						'etapa' => 10,
						'mensaje'=> 'ALERTA DE PLAZO: AGENDAR ENTREVISTA',
						'fecha_activacion'=> $nuevafecha,
						'vinculado_a_postulacion' => '1');
				$this->create();
				$this->save($datos2);
				$nuevafecha = $hoy;	
				break;
			case 8:							
				$entrevista = $Entrevista->find('first', array('conditions' => array ('postulacion_codigo' => $codigo_postulacion)));
				$codigo_entrevista = $entrevista['Entrevista']['horario_codigo'];				
				$fecha_entrevista = $Horario->find('first', array('conditions' => array ('codigo' => $codigo_entrevista)));
				$fecha_entrevista = $fecha_entrevista['Horario']['fecha'];
				$plazo = $Plazo->find('first', array('conditions' => array ('etapa_id' => 11)));
				$plazo = $plazo['Plazo']['plazo'];
				$plazo = $plazo-1;
				$nuevafecha = strtotime ( '+'.$plazo.' day' , strtotime ( $fecha_entrevista ) ) ;
				$nuevafecha = date ( 'Y-m-j' , $nuevafecha );
				$datos2 =  array('codigo_postulacion' => $codigo_postulacion,
						'etapa' => 8,
						'mensaje'=> 'ALERTA DE PLAZO: EVIDENCIAS FINALES',
						'fecha_activacion'=> $nuevafecha,
						'vinculado_a_postulacion' => '1');
				$this->create();
				$this->save($datos2);
				$mensaje = "ENTREVISTA AGENDADA";	
				$nuevafecha = $hoy;				
				break;
			case 11:
				$mensaje = "EVIDENCIAS FINALES VALIDADAS";
				$nuevafecha = $hoy;
				break;
			case 16://SI UN POSTULANTE ANULA LA ENTREVISTA
				$plazo = $Plazo->find('first', array('conditions' => array ('etapa_id' => 8)));
				$plazo = $plazo['Plazo']['plazo'];
				$plazo = $plazo-1;
				$nuevafecha = strtotime ( '+'.$plazo.' day' , strtotime ( $hoy ) ) ;
				$nuevafecha = date ( 'Y-m-j' , $nuevafecha );	
				$datos2 =  array('codigo_postulacion' => $codigo_postulacion,
								'etapa' => 10,
								'mensaje' => 'ALERTA DE PLAZO: AGENDAR ENTREVISTA',
								'fecha_activacion' => $nuevafecha,
								'vinculado_a_postulacion' => '1');
				$this->create();
				$this->save($datos2);
				$mensaje = "ENTREVISTA ANULADA";
				$nuevafecha = $hoy;	
				break;
			case 17://ENTREVISTA REALIZADA
				$plazo = $Plazo->find('first', array('conditions' => array ('etapa_id' => 8)));
				$plazo = $plazo['Plazo']['plazo'];
				$plazo = $plazo-1;
				$nuevafecha = strtotime ( '+'.$plazo.' day' , strtotime ( $hoy ) ) ;
				$nuevafecha = date ( 'Y-m-j' , $nuevafecha );	
				$datos2 =  array('codigo_postulacion' => $codigo_postulacion,
								'etapa' => 10,
								'mensaje' => 'ALERTA: ENTREVISTA REALIZADA',
								'fecha_activacion' => $nuevafecha,
								'vinculado_a_postulacion' => '1');
				$this->create();
				$this->save($datos2);
				$mensaje = "ENTREVISTA REALIZADA";
				$nuevafecha = $hoy;	
				break;
			case 100: //SI EL POSTULANTE BORRA UNA ALERTA
				$datos2 =  array('codigo_postulacion' => $codigo_postulacion,
								'etapa' => 100,
								'mensaje' => 'POSTULANTE BORRÓ POSTULACION:'.$codigo_postulacion,
								'fecha_activacion' => 'SYSDATE',
								'vinculado_a_postulacion' => '1');
				$mensaje = 'POSTULANTE BORRÓ POSTULACION:'.$codigo_postulacion;
				$this->create();
				$this->save($datos2);
				break;
		}
		//SE ESTÁ DESACTIVANDO LA ETAPA		
		if ($etapa == 15){					
			$datos3 =  array('codigo_postulacion' => $codigo_postulacion,
						'etapa' => $etapa,
						'mensaje' => 'ETAPA DESACTIVADA POR ADMINISTRATIVO',
						'fecha_activacion' => $hoy,
						'vinculado_a_postulacion' => '1');
			$this->create();
			$this->save($datos3);
		}
		elseif ($etapa == 16) {
				$this->borrar_alertas($etapa,$codigo_postulacion,$codigo_postulacion);
				$plazo = $Plazo->find('first', array('conditions' => array ('etapa_id' => 8)));
				$plazo = $plazo['Plazo']['plazo'];
				$plazo = $plazo-1;
				$nuevafecha = strtotime ( '+'.$plazo.' day' , strtotime ( $hoy ) ) ;
				$nuevafecha = date ( 'Y-m-j' , $nuevafecha );	
				$datos2 =  array('codigo_postulacion' => $codigo_postulacion,
						'etapa' => 10,
						'mensaje'=> 'ALERTA DE PLAZO: AGENDAR ENTREVISTA',
						'fecha_activacion'=> $nuevafecha,
						'vinculado_a_postulacion' => '1');
				$this->create();
				$this->save($datos2);
				$mensaje = "ENTREVISTA ANULADA";
				$nuevafecha = $hoy;	
				$datos =  array('codigo_postulacion' => $codigo_postulacion,
								'etapa' => $etapa,
								'mensaje'=> $mensaje,
								'fecha_activacion'=> $nuevafecha,
								'vinculado_a_postulacion' => '1');
				$this->create();
				$this->save($datos);
		}		
		else {
			$this->borrar_alertas($etapa,$codigo_postulacion,$codigo_postulacion);
			$datos =  array('codigo_postulacion' => $codigo_postulacion,
							'etapa' => $etapa,
							'mensaje'=> $mensaje,
							'fecha_activacion'=> $nuevafecha,
							'vinculado_a_postulacion' => '1');
			$this->create();
			$this->save($datos);		
		}	
	}

	//ESTA FUNCIÓN BORRA ALERTAS DEL SISTEMA CUANDO ESTAS NO TENGAN RAZÓN DE SER
	//SE ACTIVAN CUANDO SE CREAN OTRAS
	private function borrar_alertas($etapa,$codigo_postulacion,$codigo_postulante){
		$Postulante = ClassRegistry::init('Postulante');
		
		if ($etapa == 11){
			$array_borrar = $this->find('all',array('conditions' => array('Alerta.codigo_postulacion' => $codigo_postulacion)));
				foreach ($array_borrar as $array){
					$condition = $array['Alerta']['id'];
					$this->delete($condition);
				}		
		}
		
		if ($etapa == 16){
			$array_borrar = $this->find('all',array('conditions' => array('Alerta.codigo_postulacion' => $codigo_postulacion, 'Alerta.etapa <' =>  16)));
				foreach ($array_borrar as $array){
					$condition = $array['Alerta']['id'];
					$this->delete($condition);
				}		
		}
		
		if (($etapa == 1) || ($etapa == 2)) {
			$array_borrar = $this->find('all',array('conditions' => array('Alerta.codigo_postulacion' => $codigo_postulacion)));
				foreach ($array_borrar as $array){
					$condition = $array['Alerta']['id'];
					$this->delete($condition);
				}				
			$array_borrar2 = $Postulante->find('all',array('conditions' => array('Postulante.codigo' => $codigo_postulante)));
			echo var_dump($codigo_postulante);
			echo var_dump($array_borrar2);
			foreach ($array_borrar2 as $array2){
					$condicion = $array2['Postulante']['codigo'].'-'.$array2['Postulante']['nombre'];					
					$condition = array('Alerta.codigo_postulacion' => $condicion);	
					$this->deleteAll($condition,false);			
			}
		}		
		else{
			$array_borrar = $this->find('all',array('conditions' => array('Alerta.codigo_postulacion' => $codigo_postulacion, 'Alerta.etapa !=' =>  $etapa, 'Alerta.etapa !=' => 16)));
				foreach ($array_borrar as $array){
					$condition = $array['Alerta']['id'];					
					$this->delete($condition);
				}	
		
		}
	}
}
?>