<?php

App::uses('AppController', 'Controller');
App::uses('CakeEmail', 'Network/Email');

class PostulacionesController extends AppController {

    var $name = 'Postulaciones';
    var $uses = array(
        'AutoEvaluacion',
		'Alerta',
        'UnidadCompetencia',
        'CapacitacionPostulacion',
        'CompetenciaPostulacion',
        'Competencia',
        'Correo',
        'Postulante',
        'Postulacion',
        'Estado',
        'EstadoPostulacion',
        'MedioInformacion',
        'Ciudad',
        'Carrera',
        'Cargas',
        'TipoCargo',
        'EducacionPostulacion',
        'LaboralPostulacion',
        'TipoEducacion',
        'Sede',
        'EvidenciasPrevias',
        'ArchivoEvidencia',
        'Plazo',
        'Entrevista',
        'ArchivoPostulante',
        'Prepostulacion',
        'ArchivoPrepostulacion',
        'Cargas',
    );
   
    var $layout = "rap-postulante-2016";
    var $helpers = array('Form', 'Html', 'format');
    var $components = array('Utilidades', 'Session','RequestHandler');
    

	
   
   function nuevaCapacitacion($codigo_postulacion = null) {
        $this->layout = 'ajax';
        if (!empty($this->data)) {
			$institucion = $this->data['Capacitacion']['institucion'];		            
			$institucion = mb_strtoupper($institucion);
			$nombre_curso = $this->data['Capacitacion']['nombre_curso'];			            
			$nombre_curso = mb_strtoupper($nombre_curso);
			$observaciones = $this->data['Capacitacion']['observaciones'];			            
			$observaciones = mb_strtoupper($observaciones);				
            $codigo = 'pc';
			$codigo .= uniqid();
			$data_capacitacion = array(
                'codigo' => $codigo,
                'postulacion_codigo' => $codigo_postulacion,
                'institucion' => $institucion,
                'nombre_curso' => $nombre_curso,
                'observaciones' => $observaciones
            );
            $this->CapacitacionPostulacion->create();
            $this->CapacitacionPostulacion->save($data_capacitacion);
            $this->Session->setFlash(__('Capacitación agregada con éxito.'), 'mensaje-exito');
            $this->redirect(array('action' => 'cvRap', $codigo_postulacion));
        }
        $this->Postulacion->recursive = -1;
        $postulacion = $this->Postulacion->find('first', array('conditions' => array('Postulacion.codigo' => $codigo_postulacion)));
        #$tipos_educacion  = $this->TipoEducacion->find('all');
        #pr($tipos_educacion);
        $this->set('postulacion', $postulacion);
        #$this->set('tipos_educacion',$tipos_educacion);
        $this->set('codigo_postulacion', $codigo_postulacion);
    }

    function editarCapacitacion($codigo = null) {
        $this->layout = 'ajax';
        if (!empty($this->data)) {
            $institucion = $this->data['Capacitacion']['institucion'];		            
			$institucion = mb_strtoupper($institucion);
			$nombre_curso = $this->data['Capacitacion']['nombre_curso'];			            
			$nombre_curso = mb_strtoupper($nombre_curso);
			$observaciones = $this->data['Capacitacion']['observaciones'];			            
			$observaciones = mb_strtoupper($observaciones);	
            $data_capacitacion = array(
                'institucion' => "'" . $institucion . "'",
                'nombre_curso' => "'" . $nombre_curso . "'",
                'observaciones' => "'" . $observaciones . "'"
            );
            $this->CapacitacionPostulacion->updateAll($data_capacitacion, array('codigo' => $codigo));
            $capacitacion = $this->CapacitacionPostulacion->find('first', array('conditions' => array('codigo' => $codigo)));
            $this->Session->setFlash(__('Capacitación  actualizada con éxito.'), 'mensaje-exito');
            $this->redirect(array('controller'=> 'Postulaciones', 'action' => 'cvRap', $capacitacion['CapacitacionPostulacion']['postulacion_codigo']));
        }
        $this->Postulacion->recursive = -1;

        $capacitacion = $this->CapacitacionPostulacion->find('first', array('conditions' => array('codigo' => $codigo)));
        #$tipos_educacion  = $this->TipoEducacion->find('all');
        #pr($tipos_educacion);
        $this->set('capacitacion', $capacitacion);
        #$this->set('tipos_educacion',$tipos_educacion);
    }

    function eliminarCapacitacion($codigo = null) {
        $capacitacion = $this->CapacitacionPostulacion->find('first', array('conditions' => array('codigo' => $codigo)));
        if (!empty($capacitacion)) {
            $this->CapacitacionPostulacion->eliminar($codigo);
            $this->Session->setFlash(__('Registro eliminado.'), 'mensaje-exito');
            $this->redirect(array('controller' => 'postulaciones','action' => 'cvRap', $capacitacion['CapacitacionPostulacion']['postulacion_codigo']));
        }
    }

    function autoEvaluacion($codigo_postulacion = null) {
        if ($codigo_postulacion == 'undefined' || empty($codigo_postulacion)) {
            $this->Session->setFlash(__('Ha ocurrido un error de navegación.'), 'mensaje-error');
            $this->redirect(array('controller' => 'home', 'action' => 'postulantes'));
        }
        $postulacion = $this->Postulacion->find('first', array('conditions' => array('Postulacion.codigo' => $codigo_postulacion)));
	
        if ( empty($postulacion) ) {
            $this->Session->setFlash(__('Ha ocurrido un error de navegación.'), 'mensaje-error');
            $this->redirect(array('controller' => 'home', 'action' => 'postulantes'));
        }
        #labusqueda de unidades de competencia se hace antes para tener la cantidad de unidades que debe autoevaluar
        $opciones = array(
			'order' => array('Competencia.troncal ASC', 'Competencia.codigo_competencia DESC'),
			'fields' => array(
                'UnidadCompetencia.codigo_unidad_comp',
                'UnidadCompetencia.nombre_unidad_comp',
                'Competencia.nombre_competencia',
                'Competencia.codigo_competencia',
                'Competencia.troncal'
            ),
            'joins' => array(
                array(
                    'type' => 'inner',
                    'alias' => 'UnidadCompetencia',
                    'table' => 'RAP_COMPETENCIA_UNIDAD_COMP',
                    'conditions' => array(
                        'UnidadCompetencia.codigo_competencia = CompetenciaPostulacion.competencia_codigo'
                    )
                ),
                array(
                    'type' => 'inner',
                    'alias' => 'Competencia',
					
                    'table' => 'RAP_COMPETENCIA',
                    'conditions' => array(
                        'UnidadCompetencia.codigo_competencia = Competencia.codigo_competencia'
                    )
                )
            ),
            'conditions' => array(
                'CompetenciaPostulacion.postulacion_codigo' => $codigo_postulacion
            )
        );
        $unidades_competencias_postulacion = $this->CompetenciaPostulacion->find('all', $opciones);
        if (!empty($this->data)) {
            //debug($this->data);
            if (!empty($this->data['Postulacion']['Unidades'])) {
                $unidades = $this->data['Postulacion']['Unidades'];
                if (count($unidades) === count($unidades_competencias_postulacion)) {
                    #crear la matriz de la autoevaluacion
                    #debug($this->data);
                    #exit();
                    foreach ($unidades as $codigo_unidad => $indicador) {
						$codigo_autoevaluacion = 'st';
						$codigo_autoevaluacion .= uniqid();
                        $data_autoevaluacion = array(
                            'codigo' => $codigo_autoevaluacion,
                            'postulacion_codigo' => $this->data['Postulacion']['codigo'],
                            'unidad_competencia_codigo' => $codigo_unidad,
                            'indicador' => $indicador
                        );
                        #pr($data_autoevaluacion);
                        $this->AutoEvaluacion->create();
                        $this->AutoEvaluacion->save($data_autoevaluacion);
                    }
                    $postulacion = $this->Postulacion->find('first', array('conditions' => array('Postulacion.codigo' => $this->data['Postulacion']['codigo'])));
                    $codigo_estado = 'px';
					$codigo_estado .= uniqid();
					$data_cambio_status = array(
                        'codigo' => $codigo_estado,
                        'postulacion_codigo' => $this->data['Postulacion']['codigo'],
                        'estado_codigo' => '5',
                        'fecha_cambio' => date('Y-M-d H:i:s'),
                        'postulante_codigo' => $postulacion['Postulacion']['postulante_codigo']
                    );
                    #cambiar estado
                    $this->EstadoPostulacion->create();
                    $this->EstadoPostulacion->save($data_cambio_status);
                    $postulante = $this->Postulante->find('first', array('conditions' => array('codigo' => $postulacion['Postulacion']['postulante_codigo'])));
                    $this->Alerta->crear_alerta(5,null,$codigo_postulacion);
					$this->actualizaFecha($codigo_postulacion);
					$Email = new CakeEmail('smtp');
                    $Email->emailFormat('html');
                    $Email->to($postulante['Postulante']['email']);
                    $Email->subject('[Portal-RAP] Curriculum RAP - AutoEvaluacion Completado');
                    $Email->from('rap@duoc.cl');
                    $Email->template('autoevaluacion', 'postulante');
                    $Email->viewVars(array('postulante' => $postulante));
                    $Email->delivery = 'smtp';
                    if ($Email->send()) {
                       
						$this->Session->setFlash(__('Ha completado con éxito el Curriculum RAP y la Autoevaluación, su postulación ha iniciado un nuevo proceso de revisión.'), 'mensaje-exito');
                        $this->redirect(array('controller' => 'home', 'action' => 'postulantes'));
                    } else {
                        $this->Session->setFlash(__('No se pudo enviar el e-mail.'), 'mensaje-error');
                        $this->redirect(array('controller' => 'home', 'action' => 'postulantes'));
                    }
                    #mensaje de exito
                } else {
                    $this->Session->setFlash(__('Debe responder a todas las unidades que aparecen en la Autoevaluación.'), 'mensaje-error');
                    #$this->redirect(array('controller'=>'postulaciones','action'=>'autoEvaluacion',$codigo_postulacion));
                }
            } else {
                $this->Session->setFlash(__('Hubo un error, debe responder a todas las unidades'), 'mensaje-error');
                $this->redirect(array('controller' => 'home', 'action' => 'postulantes'));
            }
        }
        $auto_evaluacion = $this->AutoEvaluacion->find('all', array('conditions' => array('postulacion_codigo' => $codigo_postulacion)));
        $new_array = array();
        foreach ($auto_evaluacion as $k => $seleccion) {
            $new_array[$seleccion['AutoEvaluacion']['unidad_competencia_codigo']] = $seleccion['AutoEvaluacion']['indicador'];
        };
        $formulario_completado = false;
        if (count($new_array) === count($unidades_competencias_postulacion)) {
            $formulario_completado = true;
        }
        $estado = $this->Postulacion->estadoActual($codigo_postulacion);
        $sede = $this->Sede->find('first', array('conditions' => array('Sede.codigo_sede' => $postulacion['Postulacion']['sede_codigo'])));
        $carrera = $this->Carrera->find('first', array('conditions' => array('Carrera.codigo' => $postulacion['Postulacion']['carrera_codigo'])));
         $entrevista = $this->Entrevista->find('first', array('conditions' => array('Entrevista.postulacion_codigo' => $codigo_postulacion)));
         if(isset($entrevista) && $entrevista)
         {
             $resumen = array(
            'carrera' => $carrera['Carrera']['nombre'],
            'sede' => $sede['Sede']['nombre_sede'],
            'jornada' => $postulacion['Postulacion']['jornada'],
            'estado' => $estado['Estado'],
             'entrevista' => $entrevista['Entrevista']['estado']
            );
         }else{
             $resumen = array(
            'carrera' => $carrera['Carrera']['nombre'],
            'sede' => $sede['Sede']['nombre_sede'],
            'jornada' => $postulacion['Postulacion']['jornada'],
            'estado' => $estado['Estado']
            
             );
         }
		 $validado_final = '';
		$validados = $this->EvidenciasPrevias->find('first', array('conditions' => array('EvidenciasPrevias.postulacion_codigo' => $codigo_postulacion,
                                                                                             'EvidenciasPrevias.validar' => 1,
                                                                                             'EvidenciasPrevias.preliminar' => 0)));			
		if (!empty($validados)) { $validado_final = true;}			
		$this->set('validado_final',$validado_final);
       
        $this->set('unidades_competencias_postulacion', $unidades_competencias_postulacion);
        $this->set('codigo_postulacion', $codigo_postulacion);
        $this->set('auto_evaluacion', $new_array);
        $this->set('formulario_completado', $formulario_completado);
        $this->set('postulacion', $postulacion);
        $this->set('estado', $estado);
        $this->set('resumen', $resumen);
    }
  
    function nueva() {
        $this->layout = 'web-home-2016';		
        $this->set('generos', array(array('Masculino', 'M'), array('Femenino', 'F')));
        $dias = array();
        for ($x = 1; $x < 32; $x++) {
            if (strlen($x) === 1) {
                $dias["0$x"] = $x;
            } else {
                $dias[$x] = $x;
            }
        }
		
        $meses = array(
            '01' => 'Enero',
            '02' => 'Febrero',
            '03' => 'Marzo',
            '04' => 'Abril',
            '05' => 'Mayo',
            '06' => 'Junio',
            '07' => 'Julio',
            '08' => 'Agosto',
            '09' => 'Septiembre',
            '10' => 'Octubre',
            '11' => 'Noviembre',
            '12' => 'Diciembre',
        );
        $hora = ((int) date('H') >= 12) ? 'PM' : 'AM ';
        $anos = array();
        $year = date('Y');
        $anho_actual = $year + 1;
        $year = (int) ($year - 60);
        for ($i = $year; $i < $anho_actual; $i++) {
            $anos[$i] = $i;
        }
        $this->set('anos', $anos);
        $this->set('dias', $dias);
        $this->set('meses', $meses);
		
        if (!empty($this->data)) {			
			//COMPROBAMOS QUE SI ES EXTRANJERO NO HAGA FALTA LA VALIDACIÓN DEL RUT REQUISITO 2016. 
			if (($this->request->data['Postulante']['extranjero'] == '0') && (($this->request->data['Postulante']['rut'] == null) )) {
                unset($this->request->data['Postulante']['rut']);
                unset($this->request->data['Postulante']['extranjero']);
                $this->Postulante->invalidate('rut', 'Necesita indicar su RUT');                
                return;
            }	
		   
			if ($this->request->data['Postulante']['contrasenha'] !== $this->request->data['Postulante']['contrasenha2']) {
                unset($this->request->data['Postulante']['contrasenha']);
                unset($this->request->data['Postulante']['contrasenha2']);
                $this->Postulante->invalidate('contrasenha', 'Las contraseñas no coinciden');
                $this->Postulante->invalidate('contrasenha2', 'Las contraseñas no coinciden');
                return;
            }
			if ($this->request->data['Postulante']['email'] !== $this->request->data['Postulante']['email2']) {
                unset($this->request->data['Postulante']['email']);
                unset($this->request->data['Postulante']['email2']);
                $this->Postulante->invalidate('email', 'Las correos electrónicos no coinciden');
                $this->Postulante->invalidate('email2', 'Los correos electrónicos no coinciden');
                return;
            }
            if (strlen($this->request->data['Postulante']['contrasenha']) === 0 ) {
                unset($this->request->data['Postulante']['contrasenha']);
                unset($this->request->data['Postulante']['contrasenha2']);
                $this->Postulante->invalidate('contrasenha', 'Las contraseñas no pueden ser vacías');
                $this->Postulante->invalidate('contrasenha2', 'Las contraseñas no pueden ser vacías');
                return;
            }
			$this->request->data['Postulante']['rut'] = str_replace('-', '', str_replace('.', '', $this->request->data['Postulante']['rut']));           
            $codigo = 'pt';
			$codigo .= uniqid();			
			$this->request->data['Postulante']['codigo'] = $codigo;	
            $this->request->data['Postulante']['activo'] = 0;
			// debug($this->Postulante->validaInsert($this->request->data));
			// debug($this->Postulante->validationErrors);die();
           
            if (!$this->Postulante->validaInsert($this->request->data)) {		
                return;
            }
            $postulante = $this->request->data;
			
			$nombreConvertido = mb_strtoupper($this->data['Postulante']['nombre']);
			$nombreConvertido = $nombreConvertido;
			
			$postulante['Postulante']['apellidop'] = mb_strtoupper($this->data['Postulante']['apellidop']);					
			$postulante['Postulante']['apellidom'] = mb_strtoupper($this->data['Postulante']['apellidom']);					
           
			$postulante['Postulante']['nombre'] = $nombreConvertido;
            $postulante['Postulante']['fecha_nacimiento'] = $postulante['Postulante']['dia'] . '-' . $postulante['Postulante']['mes'] . '-' . $postulante['Postulante']['anho'];
            $fecha_nacimiento = $postulante['Postulante']['anho'] . '-' . $postulante['Postulante']['mes'] . '-' . $postulante['Postulante']['dia'];
            $postulante['Postulante']['password'] = md5($postulante['Postulante']['contrasenha']);
            unset($postulante['Postulante']['contrasenha']);
            $postulante['Postulante']['codigo'] = $codigo;
            $postulante['Postulante']['activo'] = 0;
            $postulante['Postulante']['fecha_nacimiento'] = $fecha_nacimiento;
			$plazo = $this->Plazo->find('first', array('conditions' => array('etapa_id' => 1)));
			$plazo = $plazo['Plazo']['plazo'];			
            $this->Postulante->create();
            if ($this->Postulante->save($postulante)) {	
                if ($this->Correo->enviarEmail($postulante,0,$plazo) === true) {					
                    $this->Session->setFlash(__('Tu cuenta ha sido creada con éxito, te hemos enviado un e-mail para que puedas activarla.'), 'mensaje-exito');
                    $this->redirect('/');
                }
				else {
				    $this->Session->setFlash(__('Tu cuenta ha sido activada pero no se pudo enviar el email de activación. Contacte con la mesa de ayuda.'), 'mensaje-error');
                    $this->redirect('/');	
				}
            } else {
                $this->Session->setFlash(__('Error de navegación o conexión con el servidor, intente nuevamente.'), 'mensaje-error');
                $this->redirect(array('action' => 'nueva'));
            }
        }
    }

    function cuentaCreada() {
        
    }

    function activarCuenta($token = null) {		
        if (empty($token)) {
            exit('No es un token valido.');
        }		
        $postulante = $this->Postulante->find('first', array('conditions' => array('Postulante.codigo' => $token)));		
		$email = $postulante['Postulante']['email'];
        if (!empty($postulante)) {
            $fecha_activado = date('Y-m-d h:i:s');
            $hora = ((int) date('H') >= 12) ? 'PM' : 'AM';
            $data_postulante = array('activo' => 1,
									'fecha_activado' => "TO_DATE('$fecha_activado', 'YYYY-MM-DD HH:MI:SS $hora')");						
			if (!$this->usuarioactivo($token) == 1){			
				if (is_numeric($token)){								
					//Aquí existe un grave problema de origen con la clave de la bd. Al ser varchar, si la clave que le llega es un número, ORACLE devuelve un error.
					//Obliga a realizar la consulta manualmente. Se ha intentado resolver con TO_CHAR($TOKEN), también con (string)$token pero la base de datos entra en un bucle. 
					//$consulta = "UPDATE RAP_POSTULANTES SET activo = 1, fecha_activado = TO_DATE('$fecha_activado', 'YYYY-MM-DD HH:MI:SS $hora') WHERE email = '$email'";	
					//POR UN BUG GENERAL, SI EL CODIGO QUE LLEGA ES NUMÉRICO, ACTUALIZO EL CAMPO POR EMAIL
					if($this->Postulante->updateAll($data_postulante, array('email' => $email))){
						$this->Session->setFlash(__('Su cuenta ha sido activada exitosamente. Ahora puede acceder al sistema introduciéndo sus credenciales.'), 'mensaje-exito');
						$this->redirect(array('controller' => 'login', 'action' => 'postulante'));
					}
					else {
						$this->Session->setFlash(__('Su cuenta no ha podido ser activada. Consulte con la mesa de ayuda.'), 'mensaje-error');
					}
				}
				else {
					if($this->Postulante->updateAll($data_postulante, array('codigo' => $token))){
						$this->Session->setFlash(__('Su cuenta ha sido activada exitosamente. Ahora puede acceder al sistema introduciéndo sus credenciales.'), 'mensaje-exito');
						$this->redirect(array('controller' => 'login', 'action' => 'postulante'));
					}
					else {
						$this->Session->setFlash(__('Su cuenta no ha podido ser activada. Consulte con la mesa de ayuda.'), 'mensaje-error');
					}		
				}
			}
			else { //USUARIO ACTIVADO
				$this->Session->setFlash(__('Su cuenta ya había sido activada previamente. Acceda a su postulación introduciéndo sus credenciales.'), 'mensaje-error');
				$this->redirect(array('controller' => 'login', 'action' => 'postulante'));
			}
        } else {
			$this->Session->setFlash(__('Error de navegación.'), 'mensaje-error');
            $this->redirect(array('controller' => 'login', 'action' => 'postulante'));
        }
    }
	
	
	//FUNCION QUE TE DEVUELVE SI EL USUARIO YA ESTÁ ACTIVO O NO.
	private function usuarioactivo($codigo) {
	    $postulante = $this->Postulante->find('first', array('conditions' => array('Postulante.codigo' => $codigo)));
		$activo = $postulante['Postulante']['activo'];		
		if ($activo == 1) {			
			return TRUE;			
		}
		else {			
			return FALSE;
		}	
	}
	
	

    function updateData(){ //asd
		
        if(!empty($this->data)){			
			if(count($this->Session->read('UserLogued.Postulante')) > 0){ 
			
					$errores               = 0;
					$user                  = $this->Session->read('UserLogued');
					$postulante_codigo     = $user['Postulante']['codigo'];
					$archivos              = $this->data['Archivos'];
					$codigo_prepostulacion = 'pp-';
					
					$msjes_error           = '';
					$resp_array            = $this->subirDocumentosPostulante($this->request->data['Archivos']['ci'], $this->request->data['Archivos']['licencia'], $postulante_codigo , $codigo_prepostulacion);
					
					if($resp_array[0]){

					}
					else{
						$msjes_error       = $resp_array[1];
						$errores++;
					}
					
					#CODIGO ACTUALIZAR DATOS DEL POSTULANTE;
					$data_postulante                      = $this->data;
					$rut                                  = str_replace('.', '', $this->data['Postulante']['rut']);
					$rut                                  = str_replace('-', '', $rut);
					$data_postulante['Postulante']['rut'] = $rut;
					$codigo                               = $data_postulante['Postulante']['codigo'];
					$nombre                               = $data_postulante['Postulante']['nombre'];
					$apellidop                            = mb_strtoupper($data_postulante['Postulante']['apellidop']);
					$apellidom                            = mb_strtoupper($data_postulante['Postulante']['apellidom']);
					$rut                                  = $data_postulante['Postulante']['rut'];
					$email                                = $data_postulante['Postulante']['email'];
					$genero                               = $data_postulante['Postulante']['genero'];
					$telefono                             = $data_postulante['Postulante']['telefonomovil'];
					$fecha_nacimiento                     = $data_postulante['Postulante']['anho'] . '-' . $data_postulante['Postulante']['mes'] . '-' . $data_postulante['Postulante']['dia'];
					$hora                                 = ((int) date('H') >= 12) ? 'PM' : 'AM ';

					if (!isset($data_postulante['Postulante']['contrasenha'])) {
						$data_postulante = array(
							'nombre' => "'$nombre'",
							'apellidop' => "'$apellidop'",
							'apellidom' => "'$apellidom'",
							'rut' => "'$rut'",
							'email' => "'$email'",
							'genero' => "'$genero'",
							'telefonomovil' => "'$telefono'",
							'fecha_nacimiento' => "TO_DATE('$fecha_nacimiento', 'YYYY-MM-DD HH:MI:SS $hora')"
						);
					} else {
						$pass = md5($data_postulante['Postulante']['contrasenha']);
						$data_postulante = array(
							'nombre' => "'$nombre'",
							'rut' => "'$rut'",
							'password' => "'$pass'",
							'email' => "'$email'",
							'genero' => "'$genero'",
							'telefonomovil' => "'$telefono'",
							'fecha_nacimiento' => "TO_DATE('$fecha_nacimiento', 'YYYY-MM-DD HH:MI:SS $hora')"
						);
					}
				   // prx($data_postulante);

					if($errores == 0){
						$this->Postulante->updateAll($data_postulante, array('codigo' => $codigo));
						$this->Session->setFlash(__('Datos Actualizados.'), 'mensaje-exito');
						$this->redirect(array('controller' => 'home', 'action' => 'postulantes'));
					}
					else{
						$this->Postulante->updateAll($data_postulante, array('codigo' => $codigo));
						$this->Session->setFlash(__('No se pudo actualizar, por favor vuelva a intentarlo: '.$msjes_error), 'mensaje-error');
						$this->redirect(array('controller' => 'home', 'action' => 'postulantes'));
					}
			
			}
			else{
				$this->Session->setFlash(__('No se pudo actualizar, por favor inicie sesión como postulante'), 'mensaje-error');
				$this->redirect(array('controller' => 'login', 'action' => 'logout'));
			}
        }
        
        
        
        $user                = $this->Session->read('UserLogued');
		$user2               = $this->Session->read('UserLogued.Postulante');
		
		if(isset($user2)){
			$postulante_codigo   = $user['Postulante']['codigo'];
			$licencia_archivo    = $this->ArchivoPostulante->find('first', array('conditions' => array('ArchivoPostulante.codigo' => 'li-'.$postulante_codigo)));
			$cedula_archivo      = $this->ArchivoPostulante->find('first', array('conditions' => array('ArchivoPostulante.codigo' => 'ci-'.$postulante_codigo)));
			
			$user                = $this->Postulante->find('first', array('conditions' => array('codigo' => $user['Postulante']['codigo'])));
			$this->set('user', $user);
			$this->set('licencia_archivo',$licencia_archivo);
			$this->set('cedula_archivo',$cedula_archivo);
		}
		else{
			$this->Session->setFlash(__('No se pudo actualizar, por favor inicie sesión como postulante'), 'mensaje-error');
			$this->redirect(array('controller' => 'login', 'action' => 'logout'));
			exit;
		}
    }

    function subirDocumentosPostulante($ci,$li,$codigo_postulante,$codigo_prepostulacion){
                
            $array_resp    = array();
            $array_resp[0] = true;
            $errores       = 0;

            /*__________________ Si Existe _________________ */
            if($ci){
                $cedula['ArchivoPostulante']                           = $ci;
            }
            else{
                $array_resp[1] = 'Datos de archivo(s) vacíos';
                $errores++;
            }
            /* __ */


            if($cedula['ArchivoPostulante']['estado'] == 'por_validar'){

                $valid_formats = array('jpg','jpeg','pdf','doc','docx');
                $extension     = $this->obtieneExtension($cedula['ArchivoPostulante']['name']);
                $name          = $cedula['ArchivoPostulante']['name'];
                $tamano        = $cedula['ArchivoPostulante']['size'];
                $max_file_size = 1024*5000; //5000 kb -> 5mb

                if(in_array(pathinfo($name,PATHINFO_EXTENSION),$valid_formats)){

                    if($tamano < $max_file_size){

                        $codigo_cedula                                          = 'ci-'.$codigo_postulante;
                        $cedula['ArchivoPostulante']['nombre_fisico']           = $codigo_cedula;	
                        $cedula['ArchivoPostulante']['codigo']                  = $codigo_cedula;	
                        $cedula['ArchivoPostulante']['tipo']                    = 'CEDULA';	
                        $cedula['ArchivoPostulante']['codigo_postulante']       = $codigo_postulante;	
                        $cedula['ArchivoPostulante']['tamano']                  = $cedula['ArchivoPostulante']['size'];
                        $cedula['ArchivoPostulante']['nombre']                  = $cedula['ArchivoPostulante']['name'];
                        $cedula['ArchivoPostulante']['mimetype']                = $cedula['ArchivoPostulante']['type'];
                        $cedula['ArchivoPostulante']['extension']               = $this->obtieneExtension($cedula['ArchivoPostulante']['name']);
                        $cedula['ArchivoPostulante']['codigo_prepostulacion']   = $codigo_prepostulacion;


                        if($cedula['ArchivoPostulante']['estado'] == 'por_validar'){

                            //Borrando archivo si es cero [invalido]
                            $cedula_archivo     = $this->ArchivoPostulante->find('first', array('conditions' => array('ArchivoPostulante.codigo' => $codigo_cedula)));
                            if(count($cedula_archivo)>0 && $cedula_archivo['ArchivoPostulante']['codigo'] != ''){
                                $nombre_archivo = '';
                                $nombre_archivo = $cedula_archivo['ArchivoPostulante']['nombre_fisico'].'.'.$cedula_archivo['ArchivoPostulante']['extension'];
                                $this->ArchivoPostulante->deleteAll(array('ArchivoPostulante.codigo' => $codigo_cedula), false);
                                $file           = new File(WWW_ROOT.'uploads'.DS.'prepostulaciones'.DS.$nombre_archivo, false, 0777);
                                $file->delete();
                            }

                            if(($this->subirArchivo($cedula))){
                                if($this->ArchivoPostulante->save($cedula)){

                                }
                                else{
                                    $array_resp[1] = 'Error al tratar de guardar el(los) archivo(s)';
                                    $errores++;
                                    //debug($this->ArchivoPrePostulacion->validationErrors); //show validationErrors
                                    //debug($this->ArchivoPrePostulacion->getDataSource()->getLog(false, false));
                                }
                                //echo var_dump('Archivo subido');	
                            }
                            else{
                                $array_resp[1] = 'Error al tratar de subir el(los) archivo(s)';
                                $errores++;
                            }
                        }

                    }
                    else{
                        $array_resp[1] = 'Tamaño de archivo(s) excede lo permitido';
                        $errores++;
                    }
                }
                else{
                    $array_resp[1] = 'Extensión de archivo(s) incorrecto';
                    $errores++;
                }
            }


            /*__________________ Si Existe _________________ */
            if($li){
                $licencia['ArchivoPostulante']                         = $li;
            }
            else{
                $array_resp[1] = 'Datos de archivo(s) vacíos';
                $errores++;
            }
            /* __ */


            if($licencia['ArchivoPostulante']['estado'] == 'por_validar'){

                $valid_formats = array('jpg','jpeg','pdf','doc','docx');
                $extension     = $this->obtieneExtension($licencia['ArchivoPostulante']['name']);
                $name          = $licencia['ArchivoPostulante']['name'];
                $tamano        = $licencia['ArchivoPostulante']['size'];
                $max_file_size = 1024*5000; //5000 kb -> 5mb

                if(in_array(pathinfo($name,PATHINFO_EXTENSION),$valid_formats)){

                    if($tamano < $max_file_size){

                        $codigo_licencia                                        = 'li-'.$codigo_postulante;
                        $licencia['ArchivoPostulante']['extension']             = $this->obtieneExtension($licencia['ArchivoPostulante']['name']);
                        $licencia['ArchivoPostulante']['codigo_postulante']     = $codigo_postulante;	
                        $licencia['ArchivoPostulante']['nombre_fisico']         = $codigo_licencia;	
                        $licencia['ArchivoPostulante']['nombre']                = $licencia['ArchivoPostulante']['name'];	
                        $licencia['ArchivoPostulante']['codigo']                = $codigo_licencia;	
                        $licencia['ArchivoPostulante']['tipo']                  = 'LICENCIA';
                        $licencia['ArchivoPostulante']['tamano']                = $licencia['ArchivoPostulante']['size'];
                        $licencia['ArchivoPostulante']['mimetype']              = $licencia['ArchivoPostulante']['type'];
                        $licencia['ArchivoPostulante']['codigo_prepostulacion'] = $codigo_prepostulacion;


                        if($licencia['ArchivoPostulante']['estado'] == 'por_validar'){

                            //Borrando archivo si es cero [invalido]
                            $licencia_archivo   = $this->ArchivoPostulante->find('first', array('conditions' => array('ArchivoPostulante.codigo' => $codigo_licencia)));
                            if(count($licencia_archivo)>0 && $licencia_archivo['ArchivoPostulante']['codigo'] != ''){
                                $nombre_archivo = '';
                                $nombre_archivo = $licencia_archivo['ArchivoPostulante']['nombre_fisico'].'.'.$licencia_archivo['ArchivoPostulante']['extension'];
                                $this->ArchivoPostulante->deleteAll(array('ArchivoPostulante.codigo' => $codigo_licencia), false);
                                $file           = new File(WWW_ROOT.'uploads'.DS.'prepostulaciones'.DS.$nombre_archivo, false, 0777);
                                $file->delete();
                            }

                            if(($this->subirArchivo($licencia))){
                                if($this->ArchivoPostulante->save($licencia)){

                                }
                                else{
                                    $array_resp[1] = 'Error al tratar de guardar el(los) archivo(s)';
                                    $errores++;
                                    //debug($this->ArchivoPrePostulacion->validationErrors); //show validationErrors
                                    //debug($this->ArchivoPrePostulacion->getDataSource()->getLog(false, false));
                                }
                                //echo var_dump('Archivo subido');	
                            }
                            else{
                                $array_resp[1] = 'Error al tratar de subir el(los) archivo(s)';
                                $errores++;
                            }
                        }

                    }
                    else{
                        $array_resp[1] = 'Tamaño de archivo(s) excede lo permitido';
                        $errores++;
                    }

                }
                else{
                    $array_resp[1] = 'Extensión de archivo(s) incorrecto';
                    $errores++;
                }

            }


            //Respuesta
            if($errores == 0){
                $array_resp[0] = true;
                return $array_resp;
            }
            else{
                //echo var_dump('Errores');
                $array_resp[0] = false;
                return $array_resp;
            }		
    }
    
    
    //Esta función subirá el archivo pasado por parámetro de todos los archivos de las prepostulaciones
    private function subirArchivo($archivo){
        
            if(count($this->Session->read('UserLogued')) > 0){
                $ruta = $this->webroot.'uploads/prepostulaciones';
                $ruta = WWW_ROOT.'uploads'.DS.'prepostulaciones'.DS.$archivo['ArchivoPostulante']['codigo'].'.'.$archivo['ArchivoPostulante']['extension'];		
                if(move_uploaded_file($archivo['ArchivoPostulante']['tmp_name'], $ruta)) {			
                        return true;			
                }
                else{			
                        return false;
                }

            }
            else{

                return false;
            }
    }


    //ESTA FUNCIÓN SUBIRÁ EL ARCHIVO DE LA FIRMA DE SEDE DE TRAYECTORAI FORMATIVA
    private function subirArchivoFirma($archivo, $postulacion){			
			$nombre_archivo = 'si'.$postulacion;
			$extension = $this->obtieneExtension($archivo['name']);
            if(count($this->Session->read('UserLogued')) > 0){
                //$ruta = $this->webroot.'uploads/postulaciones';
                $ruta = WWW_ROOT.'uploads'.DS.'prepostulaciones'.DS.$nombre_archivo.'.'.$extension;
				$datos_archivo['Cargas']['codigo'] = 'si'.$postulacion;
				$datos_archivo['Cargas']['postulacion_codigo'] = $postulacion;
				$datos_archivo['Cargas']['tipo_archivo_codigo'] = 5;
				$datos_archivo['Cargas']['path_archivo'] = WWW_ROOT.'uploads'.DS.'prepostulaciones'.DS;
				$datos_archivo['Cargas']['nombre_fisico_archivo'] = $archivo['name'];
				$datos_archivo['Cargas']['extension_archivo'] = $extension;
				$datos_archivo['Cargas']['size_archivo'] = $archivo['size'];
				$datos_archivo['Cargas']['fecha_upload'] = 'SYSDATE';				
                if(move_uploaded_file($archivo['tmp_name'], $ruta) && ($this->Cargas->save($datos_archivo))) {			
                        return true;			
                }
                else{			
                        return false;
                }
            }
            else{
                return false;
            }
    }
	
	
	
	
	
        
    private function obtieneExtension($archivo){
        $trozos    = explode(".", $archivo); 
        $extension = end($trozos); 
        // mostramos la extensión del archivo
        return $extension;
    }
        
        
    function completarPostulacion($postulacion_codigo = null){
        
        //echo AppController::validarusuario();
        //exit;
        
        $user        = $this->Session->read('UserLogued');
        $medios      = $this->MedioInformacion->find('all',array('conditions' => array('MedioInformacion.activo' => 1),'order'=>'MedioInformacion.nombre ASC'));		
        $ciudades    = $this->Ciudad->find('all', array('conditions' => array('Ciudad.activo' => 1),'order'=>'Ciudad.nombre ASC'));		
        $sedes       = $this->Sede->find('all',array('conditions' => array('Sede.activo' => 1),'order'=>'Sede.nombre_sede ASC'));		
        $carreras    = $this->Carrera->find('all', array('conditions' => array('Carrera.activo' => 1),'order'=>'Carrera.nombre ASC'));		
        $tipos_cargo = $this->TipoCargo->find('all',array('conditions' => array('TipoCargo.activo' => 1),'order'=>'TipoCargo.nombre ASC'));		
       
        $this->set('tipos_cargo', $tipos_cargo);
        $this->set('medios', $medios);
        $this->set('sedes', $sedes); 
        $this->set('ciudades', $ciudades);
        $this->set('carreras', $carreras);
        
        $postulacion = array();     

        if(!empty($postulacion_codigo)){
                        
			$postulacion       = $this->Postulacion->find('first', array('conditions' => array('Postulacion.codigo' => $postulacion_codigo)));
			$postulante_codigo = $postulacion['Postulacion']['postulante_codigo'];
                        
			if(!$postulacion){
				$this->Session->setFlash(__('Postulación inválida.'), 'mensaje-error');
                                $this->redirect(array('controller' => 'home', 'action' => 'postulantes'));
			}
                        
			$carrera_elegida = $this->Carrera->find('first', array('conditions' => array('codigo' => $postulacion['Postulacion']['carrera_codigo'])));
			$sede_elegida    = $this->Sede->find('first', array('conditions' => array('codigo_sede' => $postulacion['Postulacion']['sede_codigo'])));
                        if((isset($sede_elegida)) && (!empty($sede_elegida))){  
							
							$sede = $this->Sede->find('first', array('conditions' => array('Sede.codigo_sede' => $postulacion['Postulacion']['sede_codigo'])));
							$sede = $sede['Sede']['nombre_sede'];
                        } 
                        else{
							$sede = 'Por definir';
                        }                       

                    if((isset($postulacion['Postulacion']['jornada'])) && ($postulacion['Postulacion']['jornada'] !== null)){ 
                           $jornada = $postulacion['Postulacion']['jornada'];
							
                        } 
                        else {
                            $jornada     = 'Por definir';
                        }			
			
			//PENDIENTE
			$estadoActual = $this->Postulacion->estadoActual($postulacion['Postulacion']['codigo']);			
			$resumen      = array('carrera' => $carrera_elegida['Carrera']['nombre'],'sede' => $sede, 'jornada' => $jornada, 'estado' => array('codigo' => $estadoActual['Estado']['codigo'], 'nombre' => $estadoActual['Estado']['nombre']));			
			
			$validado_final = '';
			$validados = $this->EvidenciasPrevias->find('first', array('conditions' => array('EvidenciasPrevias.postulacion_codigo' => $postulacion['Postulacion']['codigo'],
                                                                                             'EvidenciasPrevias.validar' => 1,
                                                                                             'EvidenciasPrevias.preliminar' => 0)));			
			if (!empty($validados)) { $validado_final = true;}			
				$this->set('validado_final',$validado_final);
			
            $this->set('postulacion', $postulacion);
			$this->set('resumen', $resumen);
                        
                        if (!empty($postulacion)){
                            $disabled = true;
                            //$estado_actual = $this->Postulacion->estadoActual($postulacion_codigo);               
                        }
                        
			if(!empty($this->data)){
				//echo var_dump($this->data);
			    $postulante_codigo =  $this->Session->read('UserLogued.Postulante.codigo');
				//GENERA UNA CONSULTA PARA VERIFICAR SI EXISTE YA ESTA POSTULACION, DE NO EXISTIR LA CREARA, DE LO CONTRARIO EL SISTEMA LO ENVIA AL HOME CON UN MENSAJE.
				$data_postulacion = $this->data;
				$codigo_postulacion = $postulacion_codigo;
				if ($data_postulacion['Postulacion']['actividad_laboral'] != 1 && $data_postulacion['Postulacion']['licencia_educacion_media'] != 1) {
						$this->Session->setFlash(__('Debe poseer al menos un año de experiencia y su licencia de educación media.'), 'mensaje-error');
						$this->redirect(array('action' => 'completarPostulacion'));
					}
					if ($data_postulacion['Postulacion']['actividad_laboral'] != 1) {
						$this->Session->setFlash(__('Debe poseer al menos un año de experiencia.'), 'mensaje-error');
						$this->redirect(array('action' => 'completarPostulacion'));
					}
					if ($data_postulacion['Postulacion']['licencia_educacion_media'] != 1) {
						$this->Session->setFlash(__('Debe tener su licencia de educación media.'), 'mensaje-error');
						$this->redirect(array('action' => 'completarPostulacion'));
					}
					
					// CORREGIMOS LOS PROBLEMAS CON LOS HEXADECIMALES
					$data_postulacion['Postulacion']['tipo_cargo_codigo'] = "'".$data_postulacion['Postulacion']['tipo_cargo_codigo']."'";
					$data_postulacion['Postulacion']['sede_codigo'] = "'".$data_postulacion['Postulacion']['sede_codigo']."'";
					$data_postulacion['Postulacion']['medio_informacion_codigo'] = "'".$data_postulacion['Postulacion']['medio_informacion_codigo']."'";
					$data_postulacion['Postulacion']['cargo'] = "'".$data_postulacion['Postulacion']['cargo']."'";
					$data_postulacion['Postulacion']['empresa'] = "'".$data_postulacion['Postulacion']['empresa']."'";
					$data_postulacion['Postulacion']['jornada'] = "'".$data_postulacion['Postulacion']['jornada']."'";
					//$data_postulacion['Postulacion']['carrera_codigo'] = "'".$data_postulacion['Postulacion']['carrera_codigo']."'";
					$codigo_actualizar = $data_postulacion['Postulacion']['codigo'];
					unset($data_postulacion['Postulacion']['codigo']);
					
					if ($this->Postulacion->updateAll($data_postulacion['Postulacion'], array('Postulacion.codigo' => $codigo_actualizar ) )) {
 						$codigo_estado = 'px';
						$codigo_estado .= uniqid();
						$data_estado_postulacion = array(
							'codigo' => $codigo_estado,
							'postulacion_codigo' => $codigo_actualizar,
							'estado_codigo' => 1,
							'fecha_cambio' => date('Y-M-d H:i:s'),							
							'postulante_codigo' => $postulante_codigo 
						);
					
						$this->EstadoPostulacion->create();
						if ($this->EstadoPostulacion->save($data_estado_postulacion)) {
							$this->actualizaFecha($codigo_postulacion);
							//ALERTAS
							//$this->Alerta->crear_alerta(1,$data_postulacion['Postulacion']['postulante_codigo'],$data_postulacion['Postulacion']['codigo']);
							//MAIL DE AVISO PARA QUE TERMINE DE SUBIR LA DOCUMENTACIÓN
								//$this->LoadModel('Plazo');
								//Obtengo el plazo de tiempo destinado a esta etapa.
								//$plazo = $this->Plazo->find('first',array('conditions' => array('Plazo.etapa_id' => 2)));
								//echo var_dump($plazo);	
								//$plazo = $plazo['Plazo']['plazo']-1;
								//$plazo = '+'.$plazo.'day';
								//$hoy = date('Y-m-j');
								//echo $hoy;
								//$nueva_fecha = strtotime($plazo,strtotime($hoy));
								//$nueva_fecha = date('Y-m-j', $nueva_fecha);
								//echo var_dump($nueva_fecha);
								//$this->LoadModel('Correo');
/* 								$data = array(
									array('codigo_postulacion' => $data_postulacion['Postulacion']['codigo'],
										'etapa' => 2,
										'fecha_envio' => $nueva_fecha,
										'estado' => 'PENDIENTE',
										'intentos' => 0)
								);
								$this->Correo->saveAll($data); */
								//SE AGREGA UN CORREO AL PLAZO DE POSTULACIÓN
/* 								$this->LoadModel('Periodo');
								$periodo = $this->Periodo->find('first');
								if ((isset($periodo)) AND (!empty($periodo))){
									$fecha_fin = $periodo['Periodo']['fecha_fin'];
									$data2 = array('codigo_postulacion' => $data_postulacion['Postulacion']['codigo'],
										'etapa' => 10,
										'fecha_envio' => $fecha_fin,
										'estado' => 'PENDIENTE',
										'intentos' => 0);
									
									$this->Correo->saveAll($data2); 
								};*/
							//FIN DE GUARDAR DATOS DE CORREO DE AVISO						
							$this->Session->setFlash(__('Primer paso almacenado.'), 'mensaje-exito');
							$this->redirect(array('action' => 'cargaDocumentos', $postulacion_codigo));
						}						
					}		
			}			
        }else{
            echo var_dump('Error');
            die;
        }
    }
	
	//ESTA FUNCIÓN FUE MODIFICADA DE LA ORIGINAL DE RAP PARA ACEPTAR LOS ARCHIVOS QUE YA ESTÁN EN LA POSTULACIÓN
    function cargaDocumentos($codigo_postulacion = null) {
		if ($codigo_postulacion == 'undefined' || empty($codigo_postulacion)) {
            $this->Session->setFlash(__('Error de navegación.'), 'mensaje-error');
            $this->redirect(array('controller' => 'home', 'action' => 'postulantes'));
        }
        $postulacion = $this->Postulacion->datosPostulacion2($codigo_postulacion);
        if (empty($postulacion)) {
            $this->Session->setFlash(__('Código de postulación inválido.'), 'mensaje-error');
            $this->redirect(array('controller' => 'home', 'action' => 'postulantes'));
        }
       
        $estado_actual = $this->Postulacion->estadoActual($codigo_postulacion);
        $entrevista = $this->Entrevista->find('first', array('conditions' => array('Entrevista.postulacion_codigo' => $codigo_postulacion)));
       if(isset($entrevista) && $entrevista)
       {
             $resumen = array(
            'carrera' => $postulacion['Carrera']['nombre'],
            'sede' => $postulacion['Sede']['nombre_sede'],
            'jornada' => $postulacion['Postulacion']['jornada'],
            'estado' => $estado_actual['Estado'],
            'entrevista' => $entrevista['Entrevista']['estado']
            );
       }else{
            $resumen = array(
            'carrera' => $postulacion['Carrera']['nombre'],
            'sede' => $postulacion['Sede']['nombre_sede'],
            'jornada' => $postulacion['Postulacion']['jornada'],
            'estado' => $estado_actual['Estado']
           );      
       }
		
		
		/* TENEMOS QUE OBTENER LA DOCUMENTACIÓN PREVIA A LOS CAMBIOS DE 2016 */
		$fechaPostulacion = $postulacion['Postulacion']['created'];
		$date = strtotime($fechaPostulacion);		
		/* comparamos las fechas previas al paso a producción de la nueva fase RAP donde la documentación ya es distinta */
		if ($date < (strtotime('2015-11-19 23:59:59'))){
		        $archivos = $this->Cargas->find('all', array('conditions' => array('postulacion_codigo' => $codigo_postulacion)));
				$licencia = array();
				$cedula = array();
				$anexos = array();
				foreach ($archivos as $documento) {
					if ($documento['Cargas']['tipo_archivo_codigo'] == 1) {
						$licencia = $documento;
					} elseif ($documento['Cargas']['tipo_archivo_codigo'] == 2) {
						$cedula = $documento;
					} elseif ($documento['Cargas']['tipo_archivo_codigo'] == 3) {
						$anexos['0']['ArchivoPrepostulacion'] = $documento['Cargas'];
						$anexos['0']['ArchivoPrepostulacion']['tipo'] = 'Contrato de Trabajo';
						$anexos['0']['ArchivoPrepostulacion']['nombre'] = $documento['Cargas']['nombre_fisico_archivo'];
					}
				}
				$documentos['licencia'] = $licencia['Cargas'];
				$documentos['licencia']['nombre'] = $documentos['licencia']['nombre_fisico_archivo'];			
				$documentos['cedula'] = $cedula['Cargas'];
				$documentos['cedula']['nombre'] = $documentos['cedula']['nombre_fisico_archivo'];
				$this->set('documentos', $documentos);
		}			
		else {			
			$codigo_postulante = $postulacion['Postulacion']['postulante_codigo'];
			$licencia = $this->ArchivoPostulante->find('first', array('conditions' => array('codigo' => 'li-'.$codigo_postulante)));
			$cedula = $this->ArchivoPostulante->find('first', array('conditions' => array('codigo' => 'ci-'.$codigo_postulante)));
			/*OBTENEMOS EL CÓDIGO DE LA POSTULACIÓN */
			$prepostulacion = $this->Prepostulacion->find('first', array('conditions' => array('Prepostulacion.codigo_postulacion' => $codigo_postulacion)));
			$codigo_prepostulacion = $prepostulacion['Prepostulacion']['codigo'];		
			$anexos = $this->ArchivoPrepostulacion->find('all', array('conditions' => array('ArchivoPrepostulacion.prepostulacion_codigo' => $codigo_prepostulacion)));
			if (!empty($licencia) && (!empty($cedula))){ 
				$documentos['licencia'] = $licencia['ArchivoPostulante'];
				$documentos['cedula'] = $cedula['ArchivoPostulante'];
				$this->set('documentos', $documentos);
			}			
		}		
		$validado_final = '';	
		$validados = $this->EvidenciasPrevias->find('first', array('conditions' => array('EvidenciasPrevias.postulacion_codigo' => $codigo_postulacion,
                                                                                             'EvidenciasPrevias.validar' => 1,
                                                                                             'EvidenciasPrevias.preliminar' => 0)));			
		$validado_final = '';
		if (!empty($validados)) { $validado_final = true;}			
			$this->set('validado_final',$validado_final);
				
        $estado = $this->Postulacion->estadoActual($codigo_postulacion);	
        if (isset($anexos) && (!empty($anexos))) { $this->set('anexos', $anexos); }
		$this->set('codigo_postulacion', $codigo_postulacion);
        $this->set('postulacion', $postulacion);
        $this->set('resumen', $resumen);
    }

	
	
    function cvRap($codigo_postulacion = null) {
		if ($codigo_postulacion == 'undefined' || empty($codigo_postulacion)) {
            $this->Session->setFlash(__('No se ha indicado el código de postulación.'), 'mensaje-error');
            $this->redirect(array('controller' => 'home', 'action' => 'postulantes'));
        }
        if (!empty($this->data)) { //Si se ha enviado el formulario
			foreach ($this->data['Competencia'] as $competencia) {
                $codigo_competencia = 'pm'; 
				$codigo_competencia .= uniqid();
				$new_competencia_postulacion = array(
                    'codigo' => $codigo_competencia,
                    'postulacion_codigo' => $codigo_postulacion,
                    'competencia_codigo' => $competencia['codigo_competencia']
                );
                $this->CompetenciaPostulacion->create();
                $this->CompetenciaPostulacion->save($new_competencia_postulacion);
            }
            $user = $this->Session->read('UserLogued');
            $codigo_estado = 'px';
			$codigo_estado .= uniqid();
			$cambio_estado = array(
                'codigo' => $codigo_estado,
                'postulacion_codigo' => $codigo_postulacion,
                'estado_codigo' => 4,
                'fecha_cambio' => date('Y-M-d H:i:s'),
                'postulante_codigo' => $user['Postulante']['codigo']
            );
            $this->EstadoPostulacion->create();
            $this->EstadoPostulacion->save($cambio_estado);
            #guardar observaciones de cvrap
            if (isset($this->data['Postulacion']['observaciones_cvrap'])) {
                $update_postulacion = array(
                    'observaciones_cvrap' => "'" . $this->data['Postulacion']['observaciones_cvrap'] . "'"
                );
                $this->Postulacion->updateAll($update_postulacion, array('codigo' => $codigo_postulacion));
            }
			$this->actualizaFecha($codigo_postulacion);			
			$this->Alerta->crear_alerta(4,null,$codigo_postulacion);
			//MAIL DE AVISO PARA QUE TERMINE DE SUBIR LA DOCUMENTACIÓN
						$this->LoadModel('Plazo');
						//Obtengo el plazo de tiempo destinado a esta etapa.
						$plazo = $this->Plazo->find('first',array('conditions' => array('Plazo.etapa_id' => 5)));
						//echo var_dump($plazo);	
						$plazo = $plazo['Plazo']['plazo']-1;
						$plazo = '+'.$plazo.'day';
						$hoy = date('Y-m-j');
						//echo $hoy;
						$nueva_fecha = strtotime($plazo,strtotime($hoy));
						$nueva_fecha = date('Y-m-j', $nueva_fecha);
						//echo var_dump($nueva_fecha);
						$this->LoadModel('Correo');
						$data = array(
							array('codigo_postulacion' => $codigo_postulacion,
								'etapa' => 4,
								'fecha_envio' => $nueva_fecha,
								'estado' => 'PENDIENTE',
								'intentos' => 0)
						);
						$this->Correo->saveAll($data);
			//FIN DE GUARDAR DATOS DE CORREO DE AVISO
            $this->Session->setFlash(__('Competencias añadidas.'), 'mensaje-exito');
            $this->redirect(array('action' => 'autoEvaluacion', $codigo_postulacion));
        } 
		// Si NO se ha enviado el formulario		
        $this->Postulacion->recursive = -1;
        $this->Carrera->recursive = -1;
        $tipos_educacion = $this->TipoEducacion->find('all');
        $new_tipos_educacion = array();
        foreach ($tipos_educacion as $tipo) {
            $new_tipos_educacion[$tipo['TipoEducacion']['codigo']] = $tipo['TipoEducacion']['nombre'];
        }
        $postulacion = $this->Postulacion->datosPostulacion($codigo_postulacion);
        if (empty($postulacion)) {
            $this->Session->setFlash(__('No existe la postulación.'), 'mensaje-error');
            $this->redirect(array('controller' => 'home', 'action' => 'postulantes'));
        }
        $estado_actual = $this->Postulacion->estadoActual($codigo_postulacion);
        $entrevista = $this->Entrevista->find('first', array('conditions' => array('Entrevista.postulacion_codigo' => $codigo_postulacion)));
        if(isset($entrevista) && $entrevista)
        {
              $resumen = array(
             'carrera' => $postulacion['Carrera']['nombre'],
             'sede' => $postulacion['Sede']['nombre_sede'],
             'jornada' => $postulacion['Postulacion']['jornada'],
             'estado' => $estado_actual['Estado'],
             'entrevista' => $entrevista['Entrevista']['estado']
             );
        }else{
             $resumen = array(
             'carrera' => $postulacion['Carrera']['nombre'],
             'sede' => $postulacion['Sede']['nombre_sede'],
             'jornada' => $postulacion['Postulacion']['jornada'],
             'estado' => $estado_actual['Estado']
            );       
        }
        $competencias_seleccionadas = $this->CompetenciaPostulacion->find('all', array('conditions' => array('postulacion_codigo' => $codigo_postulacion)));
        $new_array = array();
        foreach ($competencias_seleccionadas as $k => $competencia_seleccionada) {
            $new_array[$competencia_seleccionada['CompetenciaPostulacion']['competencia_codigo']] = $k;
        }
        #debug($new_array);
        $competencias = $this->Carrera->competenciasByCarrera($postulacion['Postulacion']['carrera_codigo']);
		
		//$competencias_genericas = $this->Competencia->find('all', array('conditions'=> array('competencia.troncal' => '1')));		
		$competencias_genericas = $this->Carrera->competenciasByCarrera2($postulacion['Postulacion']['carrera_codigo']);
		
        $historial_educacional = $this->EducacionPostulacion->find('all', array('conditions' => array('EducacionPostulacion.postulacion_codigo' => $postulacion['Postulacion']['codigo'])));
        $historial_laboral = $this->LaboralPostulacion->find('all', array('conditions' => array('LaboralPostulacion.postulacion_codigo' => $postulacion['Postulacion']['codigo'])));
        $capacitaciones = $this->CapacitacionPostulacion->find('all', array('conditions' => array('CapacitacionPostulacion.postulacion_codigo' => $postulacion['Postulacion']['codigo'])));
        $carrera = $this->Carrera->find('first', array('conditions' => array('Carrera.codigo' => $postulacion['Postulacion']['carrera_codigo'])));
        $disabled = false;
        if ($estado_actual['Estado']['codigo'] >= 4) {
            $disabled = true;
        }
		$validado_final = '';
		$validados = $this->EvidenciasPrevias->find('first', array('conditions' => array('EvidenciasPrevias.postulacion_codigo' => $codigo_postulacion,
                                                                                             'EvidenciasPrevias.validar' => 1,
                                                                                             'EvidenciasPrevias.preliminar' => 0)));			
		if (!empty($validados)) { $validado_final = true;}			
				$this->set('validado_final',$validado_final);
		
        $this->set('codigo_postulacion', $codigo_postulacion);
        $this->set('competencias_seleccionadas', $new_array);
        $this->set('competencias', $competencias);
        $this->set('competencias_troncales', $competencias_genericas);
        $this->set('postulacion', $postulacion);
        $this->set('resumen', $resumen);
        $this->set('disabled', $disabled);
        $this->set('historial_educacional', $historial_educacional);
        $this->set('historial_laboral', $historial_laboral);
        $this->set('capacitaciones', $capacitaciones);
        $this->set('tipos_educacion', $new_tipos_educacion);
        $this->set('carrera', $carrera);
    }

    function nuevoHistorialEducacional($codigo_postulacion = null) {
        $this->layout = 'ajax';
        if (!empty($this->data)) {
			$institucion = $this->data['HistorialEducacional']['institucion'];
			$institucion = mb_strtoupper($institucion);
			$observaciones = $this->data['HistorialEducacional']['observacion'];			            
			$observaciones = mb_strtoupper($observaciones);
			$codigo = 'pc';
			$codigo .= uniqid();
            $data_historial = array(
                'codigo' => $codigo,
                'postulacion_codigo' => $codigo_postulacion,
                'institucion' => $institucion,
                'tipo_educacion_codigo' => $this->data['HistorialEducacional']['ensenanza'],
                'observaciones' => $observaciones
            );
            $this->EducacionPostulacion->create();
            $this->EducacionPostulacion->save($data_historial);
            $this->Session->setFlash(__('Historial añadido.'), 'mensaje-exito');
            $this->redirect(array('controller' => 'postulaciones', 'action' => 'cvRap', $codigo_postulacion));
        }
        $this->Postulacion->recursive = -1;
        $postulacion = $this->Postulacion->find('first', array('conditions' => array('Postulacion.codigo' => $codigo_postulacion)));
        $tipos_educacion = $this->TipoEducacion->find('all');
        #pr($tipos_educacion);
        $this->set('postulacion', $postulacion);
        $this->set('tipos_educacion', $tipos_educacion);
        $this->set('codigo_postulacion', $codigo_postulacion);
    }

    function editarHistorialEducacional($codigo = null) {
        $this->layout = 'ajax';
        if (!empty($this->data)) {
            $institucion = $this->data['HistorialEducacional']['institucion'];
			$institucion = mb_strtoupper($institucion);
			$observaciones = $this->data['HistorialEducacional']['observaciones'];			            
			$observaciones = mb_strtoupper($observaciones);	
            $data_historial = array(
                'institucion' => "'" . $institucion . "'",
                'tipo_educacion_codigo' => $this->data['HistorialEducacional']['ensenanza'],
                'observaciones' => "'" . $observaciones . "'"
            );
            $this->EducacionPostulacion->updateAll($data_historial, array('codigo' => $codigo));
            $historial = $this->EducacionPostulacion->find('first', array('conditions' => array('codigo' => $codigo)));
            $this->Session->setFlash(__('Historial actualizado.'), 'mensaje-exito');
            $this->redirect(array('controller' => 'postulaciones', 'action' => 'cvRap', $historial['EducacionPostulacion']['postulacion_codigo']));
        }
        $this->Postulacion->recursive = -1;
        $historial = $this->EducacionPostulacion->find('first', array('conditions' => array('codigo' => $codigo)));
        $tipos_educacion = $this->TipoEducacion->find('all');
        #pr($tipos_educacion);
        $this->set('historial', $historial);
        $this->set('tipos_educacion', $tipos_educacion);
    }

    function eliminarHistorialEducacional($codigo = null) {
        $historial = $this->EducacionPostulacion->find('first', array('conditions' => array('codigo' => $codigo)));
        if (!empty($historial)) {
            $this->EducacionPostulacion->eliminar($codigo);
            $this->Session->setFlash(__('Registro eliminado.'), 'mensaje-exito');
            $this->redirect(array('controller' => 'postulaciones','action' => 'cvRap', $historial['EducacionPostulacion']['postulacion_codigo']));
        }
    }

    function eliminarHistorialLaboral($codigo = null) {
        $historial = $this->LaboralPostulacion->find('first', array('conditions' => array('codigo' => $codigo)));
        if (!empty($historial)) {
            $this->LaboralPostulacion->eliminar($codigo);
            $this->Session->setFlash(__('Registro eliminado.'), 'mensaje-exito');
            $this->redirect(array('controller' => 'postulaciones','action' => 'cvRap', $historial['LaboralPostulacion']['postulacion_codigo']));
        }
    }

    function nuevoHistorialLaboral($codigo_postulacion = null) {
        $this->layout = 'ajax';
        if (!empty($this->data)) {
			$lugar_trabajo = $this->data['LaboralPostulacion']['lugar_trabajo'];
			$lugar_trabajo = mb_strtoupper($lugar_trabajo);
			$actividades = $this->data['LaboralPostulacion']['actividades'];
			$actividades = mb_strtoupper($actividades);
			$cargo = $this->data['LaboralPostulacion']['cargo'];
			$cargo = mb_strtoupper($cargo);	
			$codigo = 'hl';
			$codigo .= uniqid();
            $data_historial = array(
                'codigo' => $codigo,
                'postulacion_codigo' => $codigo_postulacion,
                'lugar_trabajo' => $lugar_trabajo,
                'cargo' => $cargo,
                'actividades' => $actividades,
                'periodo' => $this->data['LaboralPostulacion']['periodo']
            );
            $this->LaboralPostulacion->create();
            $this->LaboralPostulacion->save($data_historial);
            $this->Session->setFlash(__('Historial añadido.'), 'mensaje-exito');
            $this->redirect(array('controller' => 'postulaciones', 'action' => 'cvRap', $codigo_postulacion));
        }
        $this->Postulacion->recursive = -1;
        $postulacion = $this->Postulacion->find('first', array('conditions' => array('Postulacion.codigo' => $codigo_postulacion)));
        $tipos_educacion = $this->TipoEducacion->find('all');
        #pr($tipos_educacion);
        $this->set('postulacion', $postulacion);
        $this->set('codigo_postulacion', $codigo_postulacion);
    }

    function editarHistorialLaboral($codigo = null) {
        $this->layout = 'ajax';
        if (!empty($this->data)) {
			$lugar_trabajo = $this->data['LaboralPostulacion']['lugar_trabajo'];
			$lugar_trabajo = mb_strtoupper($lugar_trabajo);
			$actividades = $this->data['LaboralPostulacion']['actividades'];
			$actividades = mb_strtoupper($actividades);
			$cargo = $this->data['LaboralPostulacion']['cargo'];
			$cargo = mb_strtoupper($cargo);	
            $data_historial = array(
                'lugar_trabajo' => "'" . $lugar_trabajo . "'",
                'cargo' => "'" . $cargo . "'",
                'actividades' => "'" . $actividades . "'",
                'periodo' => $this->data['LaboralPostulacion']['periodo']
            );
            $this->LaboralPostulacion->updateAll($data_historial, array('codigo' => $codigo));
            $historial = $this->LaboralPostulacion->find('first', array('conditions' => array('codigo' => $codigo)));
            $this->Session->setFlash(__('Historial actualizado.'), 'mensaje-exito');
            $this->redirect(array('controller' => 'postulaciones', 'action' => 'cvRap', $historial['LaboralPostulacion']['postulacion_codigo']));
        }
        $this->Postulacion->recursive = -1;
        $historial = $this->LaboralPostulacion->find('first', array('conditions' => array('codigo' => $codigo)));
        $this->set('historial', $historial);
    }

    function sedesPorCarrera($carrera = null) {
        $this->layout = 'ajax';
        Configure::write('debug',2);
        $sedes = array();
        if (!empty($carrera)) {
            $sedes = $this->Sede->sedesPorCarrera($carrera);
        }
       
        $this->set('sedes', $sedes);
    }

    function jornadasPorSede($carrera = null, $sede = null) {
        $this->layout = 'ajax';
        Configure::write('debug',2);
        $this->loadModel('SedeCarreraCupo');
		$this->loadModel('Modalidad');
        $cupos = array();
        if (!empty($carrera) && !empty($sede)) {
            $cupos = $this->SedeCarreraCupo->find('first', array('conditions' => array('SedeCarreraCupo.codigo_carrera' => $carrera,
                                                                                       'SedeCarreraCupo.codigo_sede' => $sede),
                                                                 'fields' => array('SedeCarreraCupo.cupos_diurno','SedeCarreraCupo.cupos_vespertino','SedeCarreraCupo.cupos_full','SedeCarreraCupo.modalidad')));
			
			$modalidades = array();
			foreach($cupos as $key => $cupo)
			{
				
				$modalidades = $this->Modalidad->find('first', array('conditions' => array('Modalidad.id' => $cupo['modalidad'],
																								 'Modalidad.activo' => 1)));
				
			}
           
        }
		
		$this->set('modalidad', $modalidades);
        $this->set('cupos', $cupos);
    }
	
	/*Controlador que mostrará la vista en al que un postulante
	puede subir las evidencias previas de su postulación */
	
	function evidenciasprevias($codigo_postulacion = null) {		
        $this->response->disableCache();
		if ($codigo_postulacion == 'undefined' || empty($codigo_postulacion)) {			
			$this->Session->setFlash(__('No se ha indicado el código de postulación.'), 'mensaje-error');
            $this->redirect(array('controller' => 'home', 'action' => 'postulantes'));	
		}
		else{
			$this->response->disableCache();
			$validado_final = '';
			$validado = false;
			$validados = $this->EvidenciasPrevias->find('first', array('conditions' => array('EvidenciasPrevias.postulacion_codigo' => $codigo_postulacion, 'validar' => '1' ) ));			
			if (!empty($validados)) { $validado = true;}			
            // $this->Session->write('validado', $validado);			
			$postulacion = $this->Postulacion->datosPostulacion2($codigo_postulacion);
			if (empty($postulacion)) {
				$this->Session->setFlash(__('Código de postulación inválido.'), 'mensaje-error');
				//$this->redirect(array('controller' => 'home', 'action' => 'postulantes'));
			}
			$codigo_postulacion= $postulacion['Postulacion']['codigo'];
			$this->set('codigo_postulacion',$codigo_postulacion);
			$this->set('postulacion',$postulacion);
			$estado = $this->Postulacion->estadoActual($codigo_postulacion);
			$sede = $this->Sede->find('first', array('conditions' => array('Sede.codigo_sede' => $postulacion['Postulacion']['sede_codigo'])));
			$carrera = $this->Carrera->find('first', array('conditions' => array('Carrera.codigo' => $postulacion['Postulacion']['carrera_codigo'])));
            $entrevista = $this->Entrevista->find('first', array('conditions' => array('Entrevista.postulacion_codigo' => $codigo_postulacion)));
			if(isset($entrevista) && $entrevista)
            {
                $resumen = array(
                'carrera' => $carrera['Carrera']['nombre'],
                'sede' => $sede['Sede']['nombre_sede'],
                'jornada' => $postulacion['Postulacion']['jornada'],
                'estado' => $estado['Estado'],
                'entrevista' => $entrevista['Entrevista']['estado']
                ); 
            }else{
                 $resumen = array(
                'carrera' => $carrera['Carrera']['nombre'],
                'sede' => $sede['Sede']['nombre_sede'],
                'jornada' => $postulacion['Postulacion']['jornada'],
                'estado' => $estado['Estado']
                );
            }
           
			$opciones = array(
					'fields' => array(
						'UnidadCompetencia.codigo_unidad_comp',
						'UnidadCompetencia.nombre_unidad_comp',
						'Competencia.nombre_competencia',
						'Competencia.codigo_competencia',
						'Competencia.troncal'
					),
					'joins' => array(
						array(
							'type' => 'inner',
							'alias' => 'UnidadCompetencia',
							'table' => 'RAP_COMPETENCIA_UNIDAD_COMP',
							'conditions' => array(
								'UnidadCompetencia.codigo_competencia = CompetenciaPostulacion.competencia_codigo'
							)
						),
						array(
							'type' => 'inner',
							'alias' => 'Competencia',
							'table' => 'RAP_COMPETENCIA',
							'conditions' => array(
								'UnidadCompetencia.codigo_competencia = Competencia.codigo_competencia'
							)
						)
					),
					'conditions' => array(
						'CompetenciaPostulacion.postulacion_codigo' => $codigo_postulacion,
						'Competencia.troncal' => 0
					)
				);
			$asignaturas = $this->CompetenciaPostulacion->find('all', $opciones);
			
			foreach ($asignaturas as $k=>$asignatura) {
					$competencias[$k]['Competencia'] = $asignatura['Competencia']['nombre_competencia'];
					$competencias[$k]['Codigo'] = $asignatura['Competencia']['codigo_competencia'];
			}			
		
			$competencias = array_map("unserialize", array_unique(array_map("serialize", $competencias)));
			
            $evidencias = $this->EvidenciasPrevias->find('all', array('conditions' => array('EvidenciasPrevias.postulacion_codigo' => $codigo_postulacion,
                                                                                            'EvidenciasPrevias.preliminar' => 1),
																		'order' => 'modified desc'));
			
            
			/* SETEAMOS UN ARRAY CON LAS EVIDENCIAS QUE EXISTEN PARA PODER PINTAR LOS BOTONES AÑADIR EN LAS NO EXISTENTES */
			$evidenciasExistentes = $this->EvidenciasPrevias->find('list', array('conditions' => array('EvidenciasPrevias.postulacion_codigo' => $codigo_postulacion,
                                                                                            'EvidenciasPrevias.preliminar' => 1),
																		'order' => 'modified desc',
																		'fields' => array('EvidenciasPrevias.id', 'EvidenciasPrevias.cod_unidad_competencia')
																		));
			
			 $this->set('evidenciasExistentes', $evidenciasExistentes);
			
			/*FIN DE ESTO */
			
            $datos_evidencia = array();
            foreach($evidencias  as $key => $datos)
            {
              $datos_evidencia[$key]            =       $this->ArchivoEvidencia->find('first', array('conditions' => array('ArchivoEvidencia.id_evidencia' => $datos['EvidenciasPrevias']['id'])));
              $datos_evidencia[$key]['ArchivoEvidencia']['evidencia_iden']      = $datos['EvidenciasPrevias']['id'];
              $datos_evidencia[$key]['ArchivoEvidencia']['nombre']              = $datos['EvidenciasPrevias']['nombre_evidencia'];
              $datos_evidencia[$key]['ArchivoEvidencia']['descripcion']         = $datos['EvidenciasPrevias']['descripcion'];
              $datos_evidencia[$key]['ArchivoEvidencia']['relacion_evidencia']  = $datos['EvidenciasPrevias']['relacion_evidencia'];
              $datos_evidencia[$key]['ArchivoEvidencia']['postulacion_codigo']  = $datos['EvidenciasPrevias']['postulacion_codigo'];
              $datos_evidencia[$key]['ArchivoEvidencia']['cod_unidad_competencia']  = $datos['EvidenciasPrevias']['cod_unidad_competencia'];
            }
			$asignaturas2 = array();
			foreach ($competencias as $i=>$competencia){
				
				
				$asignaturas2[$i]['Código']=$competencia['Codigo'];
				$asignaturas2[$i]['Nombre']=$competencia['Competencia'];
				$asignaturas2[$i]['Asignaturas'] = $this->UnidadCompetencia->find('all', array('conditions' => array ('codigo_competencia' => $competencia['Codigo'])));
				
				foreach($asignaturas2[$i]['Asignaturas'] as $key => $datos)
				{
					$asignaturas2[$i]['Asignaturas'][$key]['EvidenciaPrevia'] = $this->EvidenciasPrevias->find('first', array('conditions' => array('EvidenciasPrevias.postulacion_codigo' => $codigo_postulacion,
																																					'EvidenciasPrevias.COD_UNIDAD_COMPETENCIA' =>$datos['UnidadCompetencia']['codigo_unidad_comp'],
																																					'EvidenciasPrevias.preliminar' => 1)));
				
				
					if(! empty($asignaturas2[$i]['Asignaturas'][$key]['EvidenciaPrevia']['EvidenciasPrevias']))
					{
						$asignaturas2[$i]['Asignaturas'][$key]['DocEvidencia'] =  $this->ArchivoEvidencia->find('first', array('conditions' => array('ArchivoEvidencia.id_evidencia' => $asignaturas2[$i]['Asignaturas'][$key]['EvidenciaPrevia']['EvidenciasPrevias']['id'])));
						$asignaturas2[$i]['Asignaturas'][$key]['DocEvidencia']['evidencia_iden'] = $asignaturas2[$i]['Asignaturas'][$key]['EvidenciaPrevia']['EvidenciasPrevias']['id'];
					}
					
				}
				
			}
           // prx($asignaturas2);
			
			
            //verifica la validacion de evidencias previas
            $valida_evidencia = $this->EvidenciasPrevias->find('first', array('conditions' => array('EvidenciasPrevias.postulacion_codigo' => $codigo_postulacion,
                                                                                                    'EvidenciasPrevias.validar' => 1)));
            
			$asignaturas2 = array_map("unserialize", array_unique(array_map("serialize", $asignaturas2)));
			
			$validado_final = '';
			$validados = $this->EvidenciasPrevias->find('first', array('conditions' => array('EvidenciasPrevias.postulacion_codigo' => $codigo_postulacion,
                                                                                             'EvidenciasPrevias.validar' => 1,
                                                                                             'EvidenciasPrevias.preliminar' => 0)));			
			if (!empty($validados)) { $validado_final = true;}			
				$this->set('validado_final',$validado_final);
			
            $this->loadModel('Competencia');
            $this->set('codigo_postulacion', $codigo_postulacion);
			$this->set('validado', $validado);
			$this->set('competencias', $asignaturas2);
			$this->set('postulacion', $postulacion);
            $this->set('datos_evidencia',$datos_evidencia);
            $this->set('validada', $valida_evidencia);
            $this->set('resumen',$resumen);
			
		}
	}
    
    ///paso 7 evidencias finales
    function evidenciasfinales($codigo_postulacion = null) {
         $this->response->disableCache();
        if ($codigo_postulacion == 'undefined' || empty($codigo_postulacion)) {
			$this->Session->setFlash(__('No se ha indicado el código de postulación.'), 'mensaje-error');
            $this->redirect(array('controller' => 'home', 'action' => 'postulantes'));	
		}
        
		else{
             $this->response->disableCache();
			$validado_final = false;
			$validado_final = '';
			$validados = $this->EvidenciasPrevias->find('first', array('conditions' => array('EvidenciasPrevias.postulacion_codigo' => $codigo_postulacion,
                                                                                             'EvidenciasPrevias.validar' => 1,
                                                                                             'EvidenciasPrevias.preliminar' => 0)));			
			if (!empty($validados)) { $validado_final = true;}			
            //$this->Session->write('validado_final', $validado_final);
			
			$postulacion = $this->Postulacion->datosPostulacion2($codigo_postulacion);
			//echo var_dump($postulacion);
			if (empty($postulacion)) {
				$this->Session->setFlash(__('Código de postulación inválido.'), 'mensaje-error');
				//$this->redirect(array('controller' => 'home', 'action' => 'postulantes'));
			}
			$codigo_postulacion= $postulacion['Postulacion']['codigo'];
			$this->set('codigo_postulacion',$codigo_postulacion);
			$this->set('postulacion',$postulacion);
			$estado = $this->Postulacion->estadoActual($codigo_postulacion);
			$sede = $this->Sede->find('first', array('conditions' => array('Sede.codigo_sede' => $postulacion['Postulacion']['sede_codigo'])));
			$carrera = $this->Carrera->find('first', array('conditions' => array('Carrera.codigo' => $postulacion['Postulacion']['carrera_codigo'])));
            $entrevista = $this->Entrevista->find('first', array('conditions' => array('Entrevista.postulacion_codigo' => $codigo_postulacion)));
			$resumen = array(
            'carrera' => $carrera['Carrera']['nombre'],
            'sede' => $sede['Sede']['nombre_sede'],
            'jornada' => $postulacion['Postulacion']['jornada'],
            'estado' => $estado['Estado'],
            'entrevista' => $entrevista['Entrevista']['estado']
			);
			$opciones = array(
					'fields' => array(
						'UnidadCompetencia.codigo_unidad_comp',
						'UnidadCompetencia.nombre_unidad_comp',
						'Competencia.nombre_competencia',
						'Competencia.codigo_competencia',
						'Competencia.troncal'
					),
					'joins' => array(
						array(
							'type' => 'inner',
							'alias' => 'UnidadCompetencia',
							'table' => 'RAP_COMPETENCIA_UNIDAD_COMP',
							'conditions' => array(
								'UnidadCompetencia.codigo_competencia = CompetenciaPostulacion.competencia_codigo'
							)
						),
						array(
							'type' => 'inner',
							'alias' => 'Competencia',
							'table' => 'RAP_COMPETENCIA',
							'conditions' => array(
								'UnidadCompetencia.codigo_competencia = Competencia.codigo_competencia'
							)
						)
					),
					'conditions' => array(
						'CompetenciaPostulacion.postulacion_codigo' => $codigo_postulacion,
						'Competencia.troncal' => 0
					)
				);
			$asignaturas = $this->CompetenciaPostulacion->find('all', $opciones);			
			foreach ($asignaturas as $k=>$asignatura) {
					$competencias[$k]['Competencia'] = $asignatura['Competencia']['nombre_competencia'];
					$competencias[$k]['Codigo'] = $asignatura['Competencia']['codigo_competencia'];
			}			
			$competencias = array_map("unserialize", array_unique(array_map("serialize", $competencias)));
            $evidencias = $this->EvidenciasPrevias->find('all', array('conditions' => array('EvidenciasPrevias.postulacion_codigo' => $codigo_postulacion,
                                                                                            'EvidenciasPrevias.preliminar' => 0)));
             $datos_evidencia = array();
            foreach($evidencias  as $key => $datos)
            {
              $datos_evidencia[$key]            =       $this->ArchivoEvidencia->find('first', array('conditions' => array('ArchivoEvidencia.id_evidencia' => $datos['EvidenciasPrevias']['id'])));
              $datos_evidencia[$key]['ArchivoEvidencia']['evidencia_iden']      = $datos['EvidenciasPrevias']['id'];
              $datos_evidencia[$key]['ArchivoEvidencia']['nombre']              = $datos['EvidenciasPrevias']['nombre_evidencia'];
              $datos_evidencia[$key]['ArchivoEvidencia']['descripcion']         = $datos['EvidenciasPrevias']['descripcion'];
              $datos_evidencia[$key]['ArchivoEvidencia']['relacion_evidencia']  = $datos['EvidenciasPrevias']['relacion_evidencia'];
              $datos_evidencia[$key]['ArchivoEvidencia']['postulacion_codigo']  = $datos['EvidenciasPrevias']['postulacion_codigo'];
              $datos_evidencia[$key]['ArchivoEvidencia']['cod_unidad_competencia']  = $datos['EvidenciasPrevias']['cod_unidad_competencia'];
            }
			$asignaturas2 = array();
			foreach ($competencias as $i=>$competencia){
				
				
				$asignaturas2[$i]['Código']=$competencia['Codigo'];
				$asignaturas2[$i]['Nombre']=$competencia['Competencia'];
				$asignaturas2[$i]['Asignaturas'] = $this->UnidadCompetencia->find('all', array('conditions' => array ('codigo_competencia' => $competencia['Codigo'])));
				
				foreach($asignaturas2[$i]['Asignaturas'] as $key => $datos)
				{
					$asignaturas2[$i]['Asignaturas'][$key]['EvidenciaPrevia'] = $this->EvidenciasPrevias->find('first', array('conditions' => array('EvidenciasPrevias.postulacion_codigo' => $codigo_postulacion,
																																					'EvidenciasPrevias.COD_UNIDAD_COMPETENCIA' =>$datos['UnidadCompetencia']['codigo_unidad_comp'],
																																					'EvidenciasPrevias.preliminar' => 0)));
				
					if(! empty($asignaturas2[$i]['Asignaturas'][$key]['EvidenciaPrevia']['EvidenciasPrevias']))
					{
						$asignaturas2[$i]['Asignaturas'][$key]['DocEvidencia'] =  $this->ArchivoEvidencia->find('first', array('conditions' => array('ArchivoEvidencia.id_evidencia' => $asignaturas2[$i]['Asignaturas'][$key]['EvidenciaPrevia']['EvidenciasPrevias']['id'])));
						$asignaturas2[$i]['Asignaturas'][$key]['DocEvidencia']['evidencia_iden'] = $asignaturas2[$i]['Asignaturas'][$key]['EvidenciaPrevia']['EvidenciasPrevias']['id'];
					}
					
				}
				
			}
			/* SETEAMOS UN ARRAY CON LAS EVIDENCIAS QUE EXISTEN PARA PODER PINTAR LOS BOTONES AÑADIR EN LAS NO EXISTENTES */
			$evidenciasExistentes = $this->EvidenciasPrevias->find('list', array('conditions' => array('EvidenciasPrevias.postulacion_codigo' => $codigo_postulacion,
                                                                                            'EvidenciasPrevias.preliminar' => 0),
																		'order' => 'modified desc',
																		'fields' => array('EvidenciasPrevias.id', 'EvidenciasPrevias.cod_unidad_competencia')
																		));
			
			 $this->set('evidenciasExistentes', $evidenciasExistentes);
			
			/*FIN DE ESTO */
            //verifica la validacion de evidencias previas
            $valida_evidencia = $this->EvidenciasPrevias->find('first', array('conditions' => array('EvidenciasPrevias.postulacion_codigo' => $codigo_postulacion,
                                                                                                    'EvidenciasPrevias.validar' => 1)));
			$asignaturas2 = array_map("unserialize", array_unique(array_map("serialize", $asignaturas2)));
            $this->loadModel('Competencia');
            $this->set('codigo_postulacion', $codigo_postulacion);
			$this->set('validado_final', $validado_final);
			$this->set('competencias', $asignaturas2);
			$this->set('postulacion', $postulacion);
            $this->set('datos_evidencia',$datos_evidencia);
            $this->set('validada', $valida_evidencia);
            $this->set('resumen',$resumen);
		}
	}
    
 
    ///Evidencia Previa
    
    function evidencia($codigo_postulacion = null,$cod_unidad_comp = null)
    {
      $this->response->disableCache();
       $this->layout = 'ajax';       
       $extensiones_permitidas = array('jpg', 'gif','png','jpeg','pdf','doc','docx','xls','xlsx','odt','ods', 'rar', 'zip');
        if ( ! empty($this->data))
        {
         
            $postulante = $this->Session->read('UserLogued');
            $datos = $this->data;
            $postulante_codigo = $datos['Evidencia']['postulacion_codigo'];
            $postulante_id = $postulante['Postulante']['codigo'];
            $ruta = APP.'webroot/uploads/archivos/';
            $file1 = $datos['Evidencia']['archivo'];
            $extension1 = end(explode(".", $file1['name']));
              
            if($file1['name'] == '')
            {
                $this->Session->setFlash('Debe ingresar todos los documentos obligatorios.', "mensaje-error");
                $this->redirect(array('controller'=> 'postulaciones', 'action' => 'evidenciasprevias', $codigo_postulacion));
            }
            
            #validacion extension archivo
            if(!in_array(strtolower ($extension1), $extensiones_permitidas))
            {
                $this->Session->setFlash('Uno de los archivos adjuntos no tiene un formato compatible, asegúrese de haber leído las instrucciones y vuelva a intentarlo.', "mensaje-error");
                $this->redirect(array('controller'=> 'postulaciones', 'action' => 'evidenciasprevias', $postulante_codigo));
            }
            
            if( (int)$file1['error'] !== 0 )
            {
                $this->Session->setFlash('Uno de los archivos adjuntos tiene problemas, favor vuelva a intentarlo.', "mensaje-error");
                $this->redirect(array('controller'=> 'postulaciones', 'action' => 'evidenciasprevias', $codigo_postulacion));	
            }
            $fecha = date('Y-M-d H:i:s');
          
              $data = array(
                    'codigo'                => uniqid(),
                    'nombre_evidencia'      =>  mb_strtoupper(htmlspecialchars($this->data['Evidencia']['nombre'])),
                    'relacion_evidencia'    =>  mb_strtoupper(htmlspecialchars($this->data['Evidencia']['relacion'])),
                    'codigo_archivo'        =>  $this->data['Evidencia']['nombre'],
                    'preliminar'            =>  1,
                    'postulacion_codigo'    =>  $this->data['Evidencia']['postulacion_codigo'],
					'cod_unidad_competencia' => $cod_unidad_comp);
             
                $this->EvidenciasPrevias->create();
                 if($this->EvidenciasPrevias->save($data))
                {
                    $upload = $this->upload($ruta, $file1, $codigo_postulacion, 1,$this->EvidenciasPrevias->id);
                    if(isset($upload) && $upload == 0)
                    {
                         $this->Session->setFlash('No ha adjuntado imagenes.', "mensaje-error");
                        // $this->Session->setFlash('Su documentación se ha guardado con éxito.', "mensaje-exito");
                         $this->redirect(array('controller'=> 'postulaciones', 'action' => 'evidenciasprevias', $codigo_postulacion));
                      
                    }else{
                        $this->Session->setFlash('Su documentación se ha guardado con éxito.', "mensaje-exito");
                         $this->redirect(array('controller'=> 'postulaciones', 'action' => 'evidenciasprevias', $codigo_postulacion));
                    }
                   
                    
                }else{
                    $this->Session->setFlash('Hubo problemas al cambiar el estado.', "mensaje-error");
                    $this->redirect(array('controller'=> 'postulaciones', 'action' => 'evidenciasprevias', $codigo_postulacion));
                }
        }
		$this->set('cod_unidad_comp',$cod_unidad_comp);
       $this->set('codigo_postulacion', $codigo_postulacion);
   }
   
   ///nueva evidencia final
    function nueva_evidencia_final($codigo_postulacion = null,$cod_unidad_comp = null){     
      $this->layout = 'ajax';
      $this->response->disableCache();
       $extensiones_permitidas = array('jpg', 'gif','png','jpeg','pdf','doc','docx','xls','xlsx','odt','ods', 'rar', 'zip');
        if ( ! empty($this->data))
		{           
            $postulante = $this->Session->read('UserLogued');
            $datos = $this->data;
            $postulante_codigo = $datos['Evidencia']['postulacion_codigo'];
            $postulante_id = $postulante['Postulante']['codigo'];
            $ruta = APP.'webroot/uploads/archivos/';
            $file1 = $datos['Evidencia']['archivo'];
            $extension1 = end(explode(".", $file1['name']));            
              
            if($file1['name'] == '')
            {
                $this->Session->setFlash('Debe ingresar todos los documentos obligatorios.', "mensaje-error");
                $this->redirect(array('controller'=> 'postulaciones', 'action' => 'evidenciasfinales', $codigo_postulacion));
            }
            
            #validacion extension archivo
            if(!in_array(strtolower($extension1), $extensiones_permitidas))
            {
                $this->Session->setFlash('Uno de los archivos adjuntos no tiene un formato compatible, asegúrese de haber leído las instrucciones y vuelva a intentarlo.', "mensaje-error");
                $this->redirect(array('controller'=> 'postulaciones', 'action' => 'evidenciasfinales', $postulante_codigo));
				breaK;
            }
            
            if( (int)$file1['error'] !== 0 )
            {
                $this->Session->setFlash('Uno de los archivos adjuntos tiene problemas, favor vuelva a intentarlo.', "mensaje-error");
                $this->redirect(array('controller'=> 'postulaciones', 'action' => 'evidenciasfinales', $codigo_postulacion));	
            }
            $fecha = date('Y-M-d H:i:s');
          
              $data = array(
                    'codigo'                =>  uniqid(),
                    'nombre_evidencia'      =>  mb_strtoupper(htmlspecialchars($this->data['Evidencia']['nombre'])),
                    'relacion_evidencia'    =>  mb_strtoupper(htmlspecialchars($this->data['Evidencia']['relacion'])),
                    'codigo_archivo'        =>  $this->data['Evidencia']['nombre'],
                    'preliminar'            =>  0,
                    'validar'               =>  0,
                    'postulacion_codigo'    =>  $this->data['Evidencia']['postulacion_codigo'],
					'cod_unidad_competencia' => $cod_unidad_comp);
			
                
                $this->EvidenciasPrevias->create();
                 if($this->EvidenciasPrevias->save($data))
                {
                    $upload = $this->upload($ruta, $file1, $codigo_postulacion, 1,$this->EvidenciasPrevias->id);
                    if(isset($upload) && $upload == 0)
                    {
                         $this->Session->setFlash('No ha adjuntado imagenes.', "mensaje-error");
                        // $this->Session->setFlash('Su documentación se ha guardado con éxito.', "mensaje-exito");
                         $this->redirect(array('controller'=> 'postulaciones', 'action' => 'evidenciasfinales', $codigo_postulacion));
                      
                    }else{
                        $this->Session->setFlash('Su documentación se ha guardado con éxito.', "mensaje-exito");
                         $this->redirect(array('controller'=> 'postulaciones', 'action' => 'evidenciasfinales', $codigo_postulacion));
                    }
                   
                    
                }else{
                    $this->Session->setFlash('Hubo problemas al cambiar el estado.', "mensaje-error");
                    $this->redirect(array('controller'=> 'postulaciones', 'action' => 'evidenciasfinales', $codigo_postulacion));
                }
        }
		$this->set('cod_unidad_comp',$cod_unidad_comp);
       $this->set('codigo_postulacion', $codigo_postulacion);
   }
   
   /*
    *VALIDA TODAS LAS EVIDENCIAS PREVIAS DEJANDO EL CAMPO PRELIMINAR EN 0
   */
   
   function validar_evidencias($codigo_postulacion = null)
   {   


    $this->response->disableCache();
        if(isset($codigo_postulacion) && ! empty($codigo_postulacion))
        {
            if(true)
            {
                $buscar_todos_preliminar =$this->EvidenciasPrevias->find('first', array('conditions' => array('EvidenciasPrevias.postulacion_codigo' => $codigo_postulacion,
                                                                                                                  'EvidenciasPrevias.preliminar' => 0),
                                                                                      'fields' => array('EvidenciasPrevias.preliminar')));
                if(! empty($buscar_todos_preliminar))   //SI HAY REGISTROS FINALES
                {
                    $todas_evidencias_a_valida = $this->EvidenciasPrevias->find('all', array('conditions' => array('EvidenciasPrevias.postulacion_codigo' => $codigo_postulacion,
                                                                                                                    'EvidenciasPrevias.preliminar' => 1)));
                    
                    $fecha_activado = date('Y-M-d H:i:s');                    
                    foreach($todas_evidencias_a_valida as $clave => $evi)
                    {
                         $todas_evidencias_a_valida[$clave]['EvidenciasPrevias']['fecha_validacion'] = $fecha_activado;
                         $todas_evidencias_a_valida[$clave]['EvidenciasPrevias']['validar'] =  1;
                         $this->EvidenciasPrevias->id =  $todas_evidencias_a_valida[$clave]['EvidenciasPrevias']['id'] ;
                         $this->EvidenciasPrevias->saveField('fecha_validacion', $todas_evidencias_a_valida[$clave]['EvidenciasPrevias']['fecha_validacion']);
                         $this->EvidenciasPrevias->saveField('validar', $todas_evidencias_a_valida[$clave]['EvidenciasPrevias']['validar']);
                    }
                     $this->Session->setFlash('Evidencias validadas correctamente.', "mensaje-exito");
                    $this->redirect(array('controller'=> 'entrevistas', 'action' => 'agendaPostulante', $codigo_postulacion));
                }else{	//SI NO HAY REGISTROS FINALES - SE DUPLICAN LOS ARCHIVOS
				
						$todas_evidencias_a_valida = $this->EvidenciasPrevias->find('all', array('conditions' => array('EvidenciasPrevias.postulacion_codigo' => $codigo_postulacion,
                                                                                                                    'EvidenciasPrevias.preliminar' => 1)));
						                        
                        $fecha_activado = date('Y-M-d H:i:s');
                        foreach($todas_evidencias_a_valida as $clave => $evi) //SE VALIDAN TODAS LAS EVIDENCIAS PREVIAS PRELIMINARES
                        {
                             $todas_evidencias_a_valida[$clave]['EvidenciasPrevias']['fecha_validacion'] = $fecha_activado;
                             $todas_evidencias_a_valida[$clave]['EvidenciasPrevias']['validar'] =  1;
                             $this->EvidenciasPrevias->id =  $todas_evidencias_a_valida[$clave]['EvidenciasPrevias']['id'] ;
                             $this->EvidenciasPrevias->saveField('fecha_validacion', $todas_evidencias_a_valida[$clave]['EvidenciasPrevias']['fecha_validacion']);
                             $this->EvidenciasPrevias->saveField('validar', $todas_evidencias_a_valida[$clave]['EvidenciasPrevias']['validar']);
                        }
						
                        $todas_evidencias_duplicadas = $this->EvidenciasPrevias->find('all', array('conditions' => array('EvidenciasPrevias.postulacion_codigo' => $codigo_postulacion,
                                                                                                                  'EvidenciasPrevias.validar' => 1)));
						foreach($todas_evidencias_duplicadas as $key => $datos)
						   {
							   $todas_evidencias_duplicadas[$key]['EvidenciasPrevias']['id_antigua'] = $todas_evidencias_duplicadas[$key]['EvidenciasPrevias']['id'];
								unset($todas_evidencias_duplicadas[$key]['EvidenciasPrevias']['id']);
								unset($todas_evidencias_duplicadas[$key]['EvidenciasPrevias']['created']);
								unset($todas_evidencias_duplicadas[$key]['EvidenciasPrevias']['modified']);
								$todas_evidencias_duplicadas[$key]['EvidenciasPrevias']['validar'] = 0;
								$todas_evidencias_duplicadas[$key]['EvidenciasPrevias']['preliminar'] = 0;
								$todas_evidencias_duplicadas[$key]['EvidenciasPrevias']['cod_unidad_competencia'] = $todas_evidencias_duplicadas[$key]['EvidenciasPrevias']['cod_unidad_competencia'];
								
						   }
						//prx($todas_evidencias_duplicadas);
						$this->EvidenciasPrevias->create();
						if($this->EvidenciasPrevias->saveAll($todas_evidencias_duplicadas))
						{
                            $todas_evidencias_duplicadas_preliminares = $this->EvidenciasPrevias->find('all', array('conditions' => array('EvidenciasPrevias.postulacion_codigo' => $codigo_postulacion,
                                                                                                                                        'EvidenciasPrevias.validar' => 0)));
                            $imagenes = array();
                            foreach($todas_evidencias_duplicadas_preliminares as $key => $datos)
                            {
								$imagenes[$key] = $this->ArchivoEvidencia->find('first', array('conditions' => array('ArchivoEvidencia.id_evidencia' => $datos['EvidenciasPrevias']['id_antigua'])));
								unset($imagenes[$key]['ArchivoEvidencia']['id']);
								$imagenes[$key]['ArchivoEvidencia']['codigo'] =   $imagenes[$key]['ArchivoEvidencia']['codigo'].'_f';
								$imagenes[$key]['ArchivoEvidencia']['id_evidencia'] =  $datos['EvidenciasPrevias']['id'];
								$dir    = new Folder('uploads/archivos',true,0777);
								$file   = new File('uploads/archivos/'.$imagenes[$key]['ArchivoEvidencia']['codigo'].'.'.$imagenes[$key]['ArchivoEvidencia']['extencion_archivo'].'',true,0777);
								$file->copy($dir->path . DS . $file->name().'_f.'.$file->info['extension']);
                            }
                            $this->ArchivoEvidencia->create();
							
                            if($this->ArchivoEvidencia->saveAll($imagenes))
                            {
								$this->actualizaFecha($codigo_postulacion);
								$this->Alerta->crear_alerta(10,null,$codigo_postulacion);
				   				$this->CambiarFecha($codigo_postulacion, 6);
								$this->Session->setFlash('Evidencias validadas correctamente.', "mensaje-exito");								
							   $this->redirect(array('controller'=> 'entrevistas', 'action' => 'agendaPostulante', $codigo_postulacion));
                            }else{
                                $this->Session->setFlash('No se pueden duplicar las imagenes.', "mensaje-error");
                               $this->redirect(array('controller'=> 'postulaciones', 'action' => 'evidenciasprevias', $codigo_postulacion));
                            }
                       }else{
                               $this->Session->setFlash('No se pueden guardar las evidencias duplicadas.', "mensaje-error");
                               $this->redirect(array('controller'=> 'postulaciones', 'action' => 'evidenciasprevias', $codigo_postulacion));
                       } 
                }
            }else{
                 $this->Session->setFlash('No se pueden guardar las evidencias validadas.', "mensaje-error");
                 $this->redirect(array('controller'=> 'postulaciones', 'action' => 'evidenciasprevias', $codigo_postulacion));
            }
            
        }else{
             $this->Session->setFlash('Usted no tiene permisos para esta acción.', "mensaje-error");
            $this->redirect(array('controller'=> 'postulaciones', 'action' => 'evidenciasprevias', $codigo_postulacion));
        } 
   }
   
  
  // ESTA FUNCIÓN CAMBIA LA FECHA DEL ESTADO DE POSTULACIÓN PARA QUE PUEDA APARECER EN LOS LISTADOS, SE INVOCARÁ CUANDO SE CAMBIE LA FECHA
   private function cambiarFecha($codigo_postulacion = null, $etapa = null){
   	$condiciones2 = array('Postulacion_codigo' => $codigo_postulacion, 'Estado_codigo' => $etapa);
    $codigo_editar = $this->EstadoPostulacion->find('first', array('conditions' => $condiciones2));		
	$codigo_editar = ($codigo_editar['EstadoPostulacion']['codigo']);
	$hoy = date("Y-m-d H:i:s");
    $this->EstadoPostulacion->query("UPDATE RAP_ESTADOS_POSTULACIONES SET fecha_cambio = TO_DATE('".$hoy." ', 'YYYY-MM-DD HH24:MI:SS') WHERE codigo = '".$codigo_editar."'");
   }
   
   
   
    function validar_evidencias_finales($codigo_postulacion = null)
   {
     $this->response->disableCache();
        if(isset($codigo_postulacion) && ! empty($codigo_postulacion))
        {
            $todas_evidencias_a_valida = $this->EvidenciasPrevias->find('all', array('conditions' => array('EvidenciasPrevias.postulacion_codigo' => $codigo_postulacion,
                                                                                                           'EvidenciasPrevias.preliminar' => 0 )));
          
  
           $fecha_validacion = date('Y-M-d H:i:s');
            //$hora = ((int) date('H') >= 12) ? 'PM' : 'AM';
           foreach($todas_evidencias_a_valida as $clave => $evi)
           {
                $todas_evidencias_a_valida[$clave]['EvidenciasPrevias']['fecha_validacion'] = $fecha_validacion;
                $todas_evidencias_a_valida[$clave]['EvidenciasPrevias']['validar'] =  1;
                $this->EvidenciasPrevias->id =  $todas_evidencias_a_valida[$clave]['EvidenciasPrevias']['id'] ;
                $this->EvidenciasPrevias->saveField('fecha_validacion', $todas_evidencias_a_valida[$clave]['EvidenciasPrevias']['fecha_validacion']);
                $this->EvidenciasPrevias->saveField('validar', $todas_evidencias_a_valida[$clave]['EvidenciasPrevias']['validar']);
           }
            //prx($todas_evidencias_a_valida);
			$this->actualizaFecha($codigo_postulacion);
			$this->Alerta->crear_alerta(11,null,$codigo_postulacion);
			$this->CambiarFecha($codigo_postulacion, 8);
            $this->Session->setFlash('Evidencias finales validadas correctamente.', "mensaje-exito");
            $this->redirect(array('controller'=> 'postulaciones', 'action' => 'respuesta', $codigo_postulacion));
            
        }else{
             $this->Session->setFlash('Usted no tiene permisos para esta acción.', "mensaje-error");
            $this->redirect(array('controller'=> 'postulaciones', 'action' => 'evidenciasfinales', $codigo_postulacion));
        }
   }
   
   
   //levanta un popup para editar la evidencia
     function popup_edit_evidencia()
       {
        $this->response->disableCache();
		$this->layout = 'ajax';
		//Configure::write('debug',2);
		//pr($this->params);
        $evidencia          =   $this->EvidenciasPrevias->find('first', array('conditions' => array('EvidenciasPrevias.id' => $this->params['pass'][0])));
        $imagen_evidencia   =   $this->ArchivoEvidencia->find('first', array('conditions' => array('ArchivoEvidencia.id_evidencia' => $evidencia['EvidenciasPrevias']['id'])));
        $postulante_codigo  = $this->params['pass'][1];
        
        if(isset($imagen_evidencia) && ! empty($imagen_evidencia))
        {
            $datos = array('id'                     => $evidencia['EvidenciasPrevias']['id'],
                            'nombre'                 => htmlspecialchars($evidencia['EvidenciasPrevias']['nombre_evidencia']),
                            'relacion'               => htmlspecialchars($evidencia['EvidenciasPrevias']['relacion_evidencia']),
                            'postulacion_codigo'     => $this->params['pass'][1],
                            'id_evidencia'           => $evidencia['EvidenciasPrevias']['id'],
                            'id_evidencia_archivo'   => $imagen_evidencia['ArchivoEvidencia']['id'],
                            'codigo_imagen'          => $imagen_evidencia['ArchivoEvidencia']['codigo'],
                            'imagen'                 => $imagen_evidencia['ArchivoEvidencia']['nombre_fisico']); 
        }else{
             $datos = array('id'                => $evidencia['EvidenciasPrevias']['id'],
                            'nombre'                 => htmlspecialchars($evidencia['EvidenciasPrevias']['nombre_evidencia']),
                            'relacion'               => htmlspecialchars($evidencia['EvidenciasPrevias']['relacion_evidencia']),
                            'descripcion'            => $evidencia['EvidenciasPrevias']['descripcion'],
                            'postulacion_codigo'     => $this->params['pass'][1],
                            'id_evidencia'           => $evidencia['EvidenciasPrevias']['id']);
        }
       
			
		
         //llamada de evidencias previas y a las imagenes para editar
            $this->set(compact('datos'));        
           $this->set('codigo_postulacion', $postulante_codigo);
       }
       
     //levanta un popup para editar la evidencia
     function popup_edit_evidencia_final()
       {
        $this->response->disableCache();
        $this->layout = 'ajax';
      
        //Configure::write('debug',2);
        $evidencia          =   $this->EvidenciasPrevias->find('first', array('conditions' => array('EvidenciasPrevias.id' => $this->params['pass'][0])));
        $imagen_evidencia   =   $this->ArchivoEvidencia->find('first', array('conditions' => array('ArchivoEvidencia.id_evidencia' => $evidencia['EvidenciasPrevias']['id'])));
        $postulante_codigo  = $this->params['pass'][1];
     
        if(isset($imagen_evidencia) && ! empty($imagen_evidencia))
        {
            $datos = array('id'                     => $evidencia['EvidenciasPrevias']['id'],
                            'nombre'                 => htmlspecialchars($evidencia['EvidenciasPrevias']['nombre_evidencia']),
                            'relacion'               => htmlspecialchars($evidencia['EvidenciasPrevias']['relacion_evidencia']),
                            'postulacion_codigo'     => $this->params['pass'][1],
                            'id_evidencia'           => $evidencia['EvidenciasPrevias']['id'],
                            'id_evidencia_archivo'   => $imagen_evidencia['ArchivoEvidencia']['id'],
                            'codigo_imagen'          => $imagen_evidencia['ArchivoEvidencia']['codigo'],
                            'imagen'                 => $imagen_evidencia['ArchivoEvidencia']['nombre_fisico']); 
        }else{
             $datos = array('id'                    => $evidencia['EvidenciasPrevias']['id'],
                            'nombre'                 => htmlspecialchars($evidencia['EvidenciasPrevias']['nombre_evidencia']),
                            'relacion'               => htmlspecialchars($evidencia['EvidenciasPrevias']['relacion_evidencia']),
                            'postulacion_codigo'     => $this->params['pass'][1],
                            'id_evidencia'           => $evidencia['EvidenciasPrevias']['id']);
        }
       
   
		
         //llamada de evidencias previas y a las imagenes para editar
        $this->set(compact('datos'));        
         $this->set('codigo_postulacion', $postulante_codigo);
       }
       
       
       ///final de evidencias previas
       function evidencia_previa_validada($codigo_postulacion = null,$id = null)
       {
         $this->response->disableCache();
           $this->layout = 'ajax';
          // Configure::write('debug',2);
          
           $evidencia          =   $this->EvidenciasPrevias->find('first', array('conditions' => array('EvidenciasPrevias.id' => $id,
                                                                                                       'EvidenciasPrevias.validar' => 1)));
		 
           $imagen_evidencia   =   $this->ArchivoEvidencia->find('first', array('conditions' => array('ArchivoEvidencia.id_evidencia' => $evidencia['EvidenciasPrevias']['id'])));
           $postulante_codigo  = $codigo_postulacion;
           
           if(isset($imagen_evidencia) && ! empty($imagen_evidencia))
           {
               $datos = array('id'                     => $evidencia['EvidenciasPrevias']['id'],
                               'nombre'                 => $evidencia['EvidenciasPrevias']['nombre_evidencia'],
                               'relacion'               => $evidencia['EvidenciasPrevias']['relacion_evidencia'],
                               'postulacion_codigo'     => $codigo_postulacion,
                               'id_evidencia'           => $evidencia['EvidenciasPrevias']['id'],
                               'id_evidencia_archivo'   => $imagen_evidencia['ArchivoEvidencia']['id']); 
           }else{
                $datos = array('id'                => $evidencia['EvidenciasPrevias']['id'],
                               'nombre'                 => $evidencia['EvidenciasPrevias']['nombre_evidencia'],
                               'relacion'               => $evidencia['EvidenciasPrevias']['relacion_evidencia'],
                               'postulacion_codigo'     => $codigo_postulacion,
                               'id_evidencia'           => $evidencia['EvidenciasPrevias']['id']);
           }
		   
		
            //llamada de evidencias previas y a las imagenes para editar
               $this->set(compact('datos'));        
              $this->set('codigo_postulacion', $postulante_codigo);
       }
       
       
       //evidencias finales validadas
    function evidencia_finalizada_previa($codigo_postulacion = null,$id = null)
       {
         $this->response->disableCache();
           $this->layout = 'ajax';
          // Configure::write('debug',2);
          
           $evidencia           = $this->EvidenciasPrevias->find('first', array('conditions' => array('EvidenciasPrevias.id' => $codigo_postulacion,
                                                                                                    'EvidenciasPrevias.validar' => 1)));
          
		 
           $imagen_evidencia   =   $this->ArchivoEvidencia->find('first', array('conditions' => array('ArchivoEvidencia.id_evidencia' => $evidencia['EvidenciasPrevias']['id'])));
           $postulante_codigo  = $codigo_postulacion;
           
           if(isset($imagen_evidencia) && ! empty($imagen_evidencia))
           {
               $datos = array('id'                     => $evidencia['EvidenciasPrevias']['id'],
                               'nombre'                 => $evidencia['EvidenciasPrevias']['nombre_evidencia'],
                               'relacion'               => $evidencia['EvidenciasPrevias']['relacion_evidencia'],
                               'postulacion_codigo'     => $codigo_postulacion,
                               'id_evidencia'           => $evidencia['EvidenciasPrevias']['id'],
                               'id_evidencia_archivo'   => $imagen_evidencia['ArchivoEvidencia']['id']); 
           }else{
                $datos = array('id'                => $evidencia['EvidenciasPrevias']['id'],
                               'nombre'                 => $evidencia['EvidenciasPrevias']['nombre_evidencia'],
                               'relacion'               => $evidencia['EvidenciasPrevias']['relacion_evidencia'],
                               'postulacion_codigo'     => $codigo_postulacion,
                               'id_evidencia'           => $evidencia['EvidenciasPrevias']['id']);
           }
		   
		
            //llamada de evidencias previas y a las imagenes para editar
               $this->set(compact('datos'));        
              $this->set('codigo_postulacion', $postulante_codigo);
       }
       
      /*
       * AJAX PARA ELIMINAR LOS ARCHIVOS DE LAS EVIDENCIAS PREVIAS
      */ 
    function ajax_elimina_imagen()
       {
         $this->layout = 'ajax';
         //Configure::write('debug',2);
         //prx($this->params);
          $id = $this->params['pass'][0];
          if($id)
          {
             $this->borrarArchivoEvidencia($id,$codigo = 0);
             die('OK');
          }else{
            die('error');
          }
       
       }
       
    ///edita la evidencia previa   
   function edit_evidencia()
    {
      
       $this->layout = 'ajax';
       //Configure::write('debug',2);
       $this->response->disableCache();
       $extensiones_permitidas = array('jpg', 'gif','png','jpeg','pdf','doc','docx','xls','xlsx','odt','ods', 'rar', 'zip');
      
        if ( ! empty($this->data))
        {
          
            $postulante = $this->Session->read('UserLogued');
            $datos = $this->data;
            $postulante_codigo = $datos['Evidencia']['postulacion_codigo'];
            $postulante_id = $datos['Evidencia']['id'];
            $id_evidencia = $datos['Evidencia']['id'];
            $ruta = APP.'webroot/uploads/archivos/';
            $file1 = $datos['Evidencia']['archivo'];
            $extension1 = end(explode(".", $file1['name']));
            if($file1['size'] != 0)
            {
                  if($file1['name'] == '')
                 {
                     $this->Session->setFlash('Debe ingresar todos los documentos obligatorios.', "mensaje-error");
                     return $this->redirect(array('controller'=> 'postulaciones', 'action' => 'evidenciasprevias', $postulante_codigo));
                 }
                
                 #validacion extension archivo
                 if(!in_array(strtolower($extension1), $extensiones_permitidas))
                 {
                     $this->Session->setFlash('Uno de los archivos adjuntos no tiene un formato compatible, asegúrese de haber leído las instrucciones y vuelva a intentarlo.', "mensaje-error");
                     return $this->redirect(array('controller'=> 'postulaciones', 'action' => 'evidenciasprevias', $postulante_codigo));
                 }
                 if( (int)$file1['error'] !== 0 )
                 {
                     $this->Session->setFlash('Uno de los archivos adjuntos tiene problemas, favor vuelva a intentarlo.', "mensaje-error");
                     return $this->redirect(array('controller'=> 'postulaciones', 'action' => 'evidenciasprevias', $postulante_codigo));	
                 }
            }
           
        
            $fecha = date('Y-M-d H:i:s');
                if( ! empty($this->data['Evidencia']['codigo']))
                {
                    $data = array('id'                  =>  $datos['Evidencia']['id'],
                                'nombre_evidencia'      =>  mb_strtoupper($datos['Evidencia']['nombre']),
                                'relacion_evidencia'    =>  mb_strtoupper($datos['Evidencia']['relacion']));
                  
                }else{
                    $data = array('id'                  =>  $datos['Evidencia']['id'],
                                'codigo'                =>  uniqid(),
                                'nombre_evidencia'      =>  mb_strtoupper($datos['Evidencia']['nombre']),
                                'relacion_evidencia'    =>  mb_strtoupper($datos['Evidencia']['relacion']),
                                'codigo_archivo'        =>  $datos['Evidencia']['nombre'],
                                'preliminar'            =>  1,
                                'postulacion_codigo'    =>  $datos['Evidencia']['postulacion_codigo']);
                }
             
              $this->EvidenciasPrevias->id = $data['id'];
              $this->EvidenciasPrevias->saveField('relacion_evidencia',$data['relacion_evidencia']);
                if(isset($data['codigo']) && ! empty($data['codigo']))
                {
                   $this->EvidenciasPrevias->saveField('codigo',$data['codigo']);
                }
                 if( $this->EvidenciasPrevias->saveField('nombre_evidencia',$data['nombre_evidencia']))
                {
                  
                    if(isset($file1['name']) && $file1['name'] != '')
                    {
                        $upload = $this->upload($ruta, $file1, $datos['Evidencia']['postulacion_codigo'], 1,$data['id']);
                        
                         if(isset($upload) && $upload == 0)
                            {
                                 $this->Session->setFlash('No ha adjuntado imagenes.', "mensaje-error");
                                 $this->Session->setFlash('Su documentación se ha guardado con éxito.', "mensaje-exito");
                                 $this->redirect(array('controller'=> 'postulaciones', 'action' => 'evidenciasprevias', $postulante_codigo));
                              
                            }else{
                                $this->Session->setFlash('Su documentación se ha guardado con éxito.', "mensaje-exito");
                                 $this->redirect(array('controller'=> 'postulaciones', 'action' => 'evidenciasprevias', $postulante_codigo));
                            }
                    
                    }else{
                        $this->Session->setFlash('Su documentación se ha guardado con éxito.', "mensaje-exito");
                        $this->redirect(array('controller'=> 'postulaciones', 'action' => 'evidenciasprevias', $postulante_codigo)); 
                    }
                   
                }else{
                    $this->Session->setFlash('Hubo problemas al cambiar el estado.', "mensaje-error");
                    $this->redirect(array('controller'=> 'postulaciones', 'action' => 'evidenciasprevias', $postulante_codigo));
                }
        }
       
        $evidencia         =   $this->EvidenciasPrevias->find('first', array('conditions' => array('EvidenciasPrevias.id' => $this->params['pass'][0])));
        $imagen_evidencia   =   $this->ArchivoEvidencia->find('first', array('conditions' => array('ArchivoEvidencia.id_evidencia' => $evidencia['EvidenciasPrevias']['id'])));
        $datos = array('id'                     => $evidencia['EvidenciasPrevias']['id'],
                       'nombre'                 => $evidencia['EvidenciasPrevias']['nombre_evidencia'],
                       'relacion'               => $evidencia['EvidenciasPrevias']['relacion_evidencia'],
                       'descripcion'            => $evidencia['EvidenciasPrevias']['descripcion'],
                       'postulacion_codigo'     => $postulante_codigo,
                       'id_evidencia'           => $evidencia['EvidenciasPrevias']['id'],
                       'id_evidencia_archivo'   => $imagen_evidencia['ArchivoEvidencia']['id'],
                       'imagen'                 => $imagen_evidencia['ArchivoEvidencia']['nombre_fisico']);
         //llamada de evidencias previas y a las imagenes para editar
       $this->set(compact('datos'));        
       $this->set('codigo_postulacion', $postulante_codigo);
   }
   
   
    ///edita la evidencia finales   
   function edit_evidencia_finales()
    {
      
       $this->layout = 'ajax';
      // Configure::write('debug',2);
        $this->response->disableCache();
       $extensiones_permitidas = array('jpg', 'gif','png','jpeg','pdf','doc','docx','xls','xlsx','odt','ods', 'rar', 'zip');
    //  prx($this->data);
        if ( ! empty($this->data))
        {
           
            $postulante = $this->Session->read('UserLogued');
            $datos = $this->data;
            $postulante_codigo = $datos['Evidencia']['postulacion_codigo'];
            $postulante_id = $datos['Evidencia']['id'];
            $id_evidencia = $datos['Evidencia']['id'];
            $ruta = APP.'webroot/uploads/archivos/';
            $file1 = $datos['Evidencia']['archivo'];
            $extension1 = end(explode(".", $file1['name']));
            if($file1['size'] != 0)
            {
                  if($file1['name'] == '')
                 {
                     $this->Session->setFlash('Debe ingresar todos los documentos obligatorios.', "mensaje-error");
                     $this->redirect(array('controller'=> 'postulaciones', 'action' => 'evidenciasfinales', $postulante_codigo));
                 }
                
                 #validacion extension archivo
                 if(!in_array(strtolower($extension1), $extensiones_permitidas))
                 {
                     $this->Session->setFlash('Uno de los archivos adjuntos no tiene un formato compatible, asegúrese de haber leído las instrucciones y vuelva a intentarlo.', "mensaje-error");
                     $this->redirect(array('controller'=> 'postulaciones', 'action' => 'evidenciasfinales', $postulante_codigo));
                 }
                 if( (int)$file1['error'] !== 0 )
                 {
                     $this->Session->setFlash('Uno de los archivos adjuntos tiene problemas, favor vuelva a intentarlo.', "mensaje-error");
                     $this->redirect(array('controller'=> 'postulaciones', 'action' => 'evidenciasfinales', $postulante_codigo));	
                 }
            }
           
          
            $fecha = date('Y-M-d H:i:s');
                if( ! empty($this->data['Evidencia']['codigo']))
                {
                    $data = array('id'                  =>  $datos['Evidencia']['id'],
                                'nombre_evidencia'      =>  mb_strtoupper($datos['Evidencia']['nombre']),
                                'relacion_evidencia'    =>  mb_strtoupper($datos['Evidencia']['relacion']),
                                'preliminar'            =>  0,
                                'validar'               =>  0);
                  
                }else{
                    $data = array('id'                  =>  $datos['Evidencia']['id'],
                                'codigo'                =>  uniqid(),
                                'nombre_evidencia'      =>  mb_strtoupper($datos['Evidencia']['nombre']),
                                'relacion_evidencia'    =>  mb_strtoupper($datos['Evidencia']['relacion']),
                                'codigo_archivo'        =>  $datos['Evidencia']['nombre'],
                                'preliminar'            =>  0,
                                'validar'               =>  0,
                                'postulacion_codigo'    =>  $datos['Evidencia']['postulacion_codigo']);
                }
		
              $this->EvidenciasPrevias->id = $data['id'];
              $this->EvidenciasPrevias->saveField('relacion_evidencia',$data['relacion_evidencia']);
               $this->EvidenciasPrevias->saveField('preliminar',0);
                $this->EvidenciasPrevias->saveField('validar',0);
                if(isset($data['codigo']) && ! empty($data['codigo']))
                {
                   $this->EvidenciasPrevias->saveField('codigo',$data['codigo']);
                }
                 if( $this->EvidenciasPrevias->saveField('nombre_evidencia',$data['nombre_evidencia']))
                {
                  
                   if(isset($file1['name']) && $file1['name'] != '')
                    {
                        $upload = $this->upload($ruta, $file1, $datos['Evidencia']['postulacion_codigo'], 1,$data['id']);
                        
                         if(isset($upload) && $upload == 0)
                            {
                                 $this->Session->setFlash('No ha adjuntado imagenes.', "mensaje-error");
                                 $this->Session->setFlash('Su documentación se ha guardado con éxito.', "mensaje-exito");
                                 $this->redirect(array('controller'=> 'postulaciones', 'action' => 'evidenciasfinales', $postulante_codigo));
                              
                            }else{
                                $this->Session->setFlash('Su documentación se ha guardado con éxito.', "mensaje-exito");
                                 $this->redirect(array('controller'=> 'postulaciones', 'action' => 'evidenciasfinales', $postulante_codigo));
                            }
                    
                    }else{
                        $this->Session->setFlash('Su documentación se ha guardado con éxito.', "mensaje-exito");
                        $this->redirect(array('controller'=> 'postulaciones', 'action' => 'evidenciasfinales', $postulante_codigo)); 
                    }
                   
                }else{
                    $this->Session->setFlash('Hubo problemas al cambiar el estado.', "mensaje-error");
                    $this->redirect(array('controller'=> 'postulaciones', 'action' => 'evidenciasfinales', $postulante_codigo));
                }
        }
       
        $evidencia         =   $this->EvidenciasPrevias->find('first', array('conditions' => array('EvidenciasPrevias.id' => $this->params['pass'][0])));
        $imagen_evidencia   =   $this->ArchivoEvidencia->find('first', array('conditions' => array('ArchivoEvidencia.id_evidencia' => $evidencia['EvidenciasPrevias']['id'])));
        $datos = array('id'                     => $evidencia['EvidenciasPrevias']['id'],
                       'nombre'                 => $evidencia['EvidenciasPrevias']['nombre_evidencia'],
                       'relacion'               => $evidencia['EvidenciasPrevias']['relacion_evidencia'],
                       'postulacion_codigo'     => $postulante_codigo,
                       'id_evidencia'           => $evidencia['EvidenciasPrevias']['id'],
                       'id_evidencia_archivo'   => $imagen_evidencia['ArchivoEvidencia']['id'],
                       'imagen'                 => $imagen_evidencia['ArchivoEvidencia']['nombre_fisico']);
         //llamada de evidencias previas y a las imagenes para editar
       $this->set(compact('datos'));        
       $this->set('codigo_postulacion', $postulante_codigo);
   }
   
  
   
    
    ///para imagenes
	function upload($ruta, $file, $postulante_codigo, $tipo_archivo,$evidencia = null) {
		//App::uses('ArchivoEvidencia', 'Model');
        $this->response->disableCache();
    	if(is_dir($ruta)){
			if($file['error'] === UPLOAD_ERR_OK) {
				$name = $file["name"];
				$num = strrpos($name, ".");
				$tipo = substr($name, $num+1);
				$cod_archivo = uniqid();
				$datos = array();
				$fecha = date('Y-M-d H:i:s');
				
				$size = round((int)$file['size']/1024);
				if((int)$size > 30720){
					return 'El archivo: '.$name.' es demasiado grande.';
				}else{
					$id = String::uuid();
				   sleep(10);
					if(move_uploaded_file($file['tmp_name'], $ruta.$cod_archivo.'.'.$tipo)) {
                        $extencion = explode(".", $name); 
						$data = array(
							'codigo'                => $cod_archivo,
							'postulacion_codigo'    => $postulante_codigo,
							'tipo_archivo_codigo'   => $tipo_archivo,
							'path_archivo'          => $ruta,
							'nombre_fisico'         => $name,
							'extencion_archivo'     => end($extencion),
                            'mymetype_archivo'      => end($extencion),
							'size_archivo'          => $size,
							'fecha_upload'          => $fecha, 
							'nombre'                => $name,
                            'id_evidencia'          => $evidencia
						);
						
						if(!$this->ArchivoEvidencia->save($data)){
                          
							return 'no guardo';
						}else{
							return true;
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
    
    
    ///descarga documentos de las evidencias previas
    function descargarEvidencia($codigo = null)
	{	
		if($codigo != null)
		{
			$this->viewClass = 'Media';
			$docuemento = $this->ArchivoEvidencia->find('first',
				array(
					'conditions' => 
						array(
							'codigo' => $codigo
						),
					)
				);	
			#debug($docuemento); exit;		
			
		    $params = array(
		        'id'        => $docuemento['ArchivoEvidencia']['codigo'].'.'.$docuemento['ArchivoEvidencia']['mymetype_archivo'],
		        'name'      => $docuemento['ArchivoEvidencia']['nombre_fisico'],
		        'extension' => '',
		        'download'  => true,
		        'path'      => $docuemento['ArchivoEvidencia']['path_archivo'],
		    );

	    	$this->set($params);
			
		}else
		{
			throw new NotFoundException();
		}
	}
    
    //elimina LA EVIDENCIA
	function delete_evidencia($codigo_postulacion = null,$id = null,$id_evidencia = null)
	{
		
        if( ! $id_evidencia )
        {
            $this->Session->setFlash(__('El código no puede ser eliminado de la BD.'),'mensaje-error');
              $this->redirect(array('action' => 'evidenciasprevias', $codigo_postulacion));
        }
       // prx($codigo_postulacion);
       if(isset($id) && $id != 0)
       {
         $this->borrarArchivoEvidencia($id,$codigo_postulacion);
       }
        $this->EvidenciasPrevias->query("DELETE FROM RAP_EVIDENCIAS_PREVIAS WHERE id =".$id_evidencia."");
         $this->Session->setFlash('Evidencia eliminada.', "mensaje-exito");
        $this->redirect(array('action' => 'evidenciasprevias', $codigo_postulacion));
       
	}
    
    //elimina LA EVIDENCIA FINAL
	function delete_evidencia_final($id = null,$id_evidencia = null,$codigo_postulacion = null)
	{
        if( ! $id_evidencia )
        {
            $this->Session->setFlash(__('El código no puede ser eliminado de la BD.'),'mensaje-error');
              $this->redirect(array('action' => 'evidenciasfinales', $codigo_postulacion));
        }
       // prx($codigo_postulacion);
       if(isset($id) && $id != 0)
       {
         $this->borrarArchivoEvidencia($id,$codigo_postulacion);
       }
        $this->EvidenciasPrevias->query("DELETE FROM RAP_EVIDENCIAS_PREVIAS WHERE id =".$id_evidencia."");
         $this->Session->setFlash('Evidencia eliminada.', "mensaje-exito");
        $this->redirect(array('controller' => 'postulaciones','action' => 'evidenciasfinales', $codigo_postulacion));
       
	}
    
    
    /* ESTA FUNCIÓN BORRARÁ EL REGISTRO DE ARCHIVOS DE LA EVIDENCIA PREVIA */
    private function borrarArchivoEvidencia($id = null,$codigo_postulacion = null) {
     
			$archivos = $this->ArchivoEvidencia->Find('all',array('conditions' => array('ArchivoEvidencia.id' => $id)));
			//echo var_dump($archivos);			
			if (empty($archivos)){
				$this->Session->setFlash(__('No existen archivos asociados a esta evidencia.'),'mensaje-error');	
			}			
			else {
				foreach ($archivos as $archivo) {
					//echo var_dump($archivo);
					$archivo = $archivo['ArchivoEvidencia'];
					if ($this->ArchivoEvidencia->deleteAll(array('ArchivoEvidencia.id' => $id))){								
                    unlink($archivo['path_archivo'].$archivo['codigo'].'.'.$archivo['mymetype_archivo']);
                    $this->Session->setFlash(__('Se borró el archivo correctamente.'),'mensaje-exito');		
					}					
					else {
						$this->Session->setFlash(__('Error a la hora de borrar el archivo.'),'mensaje-error');
						return 0;
					}			
				}		
				$condition = array('ArchivoEvidencia.id' => $id);
				$this->ArchivoEvidencia->deleteAll($condition,false);	
             return true;
			}
		}
		
		
		/* VER ADMISIÓN: PERMITE VER LA POSTULACIÓN UNA VEZ PASADA DE ADMISIÓN VERTICAL Y ADMISIÓN HORIZONTAL */		
		public function verAdmision($codigo_postulacion){		
			$postulacion = $this->Postulacion->find('first', array('conditions' => array('Postulacion.codigo' => $codigo_postulacion)));
			$archivo 	 = $this->Cargas->find('all', array('conditions' => array('postulacion_codigo' => $codigo_postulacion, 'tipo_archivo_codigo' => 4)));	
			$archivo_firma  = $this->Cargas->find('first', array('conditions' => array('postulacion_codigo' => $codigo_postulacion, 'tipo_archivo_codigo' => 5)));	
			$this->set('postulacion', $postulacion);
			$this->set('archivo', $archivo);
			$this->set('archivo_firma', $archivo_firma);
		}

		
	public function respuesta($codigo_postulacion){		
		$postulacion = $this->Postulacion->find('first', array('conditions' => array('Postulacion.codigo' => $codigo_postulacion)));		
		$codigo_postulante = $postulacion['Postulante']['codigo'];
		$usuario_logueado = $this->Session->read('UserLogued');
		$codigo_usuario_logueado = $usuario_logueado['Postulante']['codigo'];
		if ($codigo_usuario_logueado !== $codigo_postulante){				
		        $this->Session->setFlash('No puede acceder a esta sección.', "mensaje-error");
                $this->redirect(array('controller'=> 'login', 'action' => 'logout'));		
		}
		
		if ($codigo_postulacion == null){
		        $this->Session->setFlash('Hubo problemas al acceder a esta sección', "mensaje-error");
                $this->redirect(array('controller'=> 'postulaciones', 'action' => 'evidenciasfinales', $codigo_postulacion));
		}
		$estado = $this->Postulacion->estadoActual($codigo_postulacion);	
		$habilitado = $postulacion['Postulacion']['habilitado'];
		$firma = $postulacion['Postulacion']['firma'];
		$csa = $postulacion['Postulacion']['csa'];
		$sede = $this->Sede->find('first', array('conditions' => array('Sede.codigo_sede' => $postulacion['Postulacion']['sede_codigo'])));
		$carrera = $this->Carrera->find('first', array('conditions' => array('Carrera.codigo' => $postulacion['Postulacion']['carrera_codigo'])));
		$entrevista = $this->Entrevista->find('first', array('conditions' => array('Entrevista.postulacion_codigo' => $codigo_postulacion)));
		if 	($postulacion['Postulacion']['habilitado'] == null){ $postulacion['Postulacion']['habilitado'] = ''; }
		$resumen = array(
            'carrera' => $carrera['Carrera']['nombre'],
            'sede' => $sede['Sede']['nombre_sede'],
            'jornada' => $postulacion['Postulacion']['jornada'],
            'estado' => $estado['Estado'],
            'entrevista' => $entrevista['Entrevista']['estado'],
			'habilitado' => $postulacion['Postulacion']['habilitado'],
			'firma' => $postulacion['Postulacion']['firma'],
			'csa' => $postulacion['Postulacion']['csa']
		);
		//Archivo
		$archivo = $this->Cargas->find('all', array('conditions' => array('Cargas.postulacion_codigo' => $codigo_postulacion, 'Cargas.tipo_archivo_codigo' => '4')));
		$archivo_firma = $this->Cargas->find('first', array('conditions' => array('Cargas.postulacion_codigo' => $codigo_postulacion, 'Cargas.tipo_archivo_codigo' => '5')));
		
		$this->set('archivo_resp',$archivo);
		$this->set('archivo_firma',$archivo_firma);
		$this->set('codigo_postulacion', $codigo_postulacion);
		$this->set('postulacion', $postulacion);
		$this->set('resumen', $resumen);		
	}	
	
	//ESTA FUNCIÓN DERIVARÁ A A SECCIÓN QUE CORRESPONDA DE RAP DEPENDIENDO DE LOS PASOS QUE HAYA DADO EL POSTULANTE
	public function enrutarRap($codigo_postulacion){
		$postulacion = $this->Postulacion->find('first', array('conditions' => array('Postulacion.codigo' => $codigo_postulacion)));		
		if (empty($postulacion)){
				$this->Session->setFlash('No puede acceder a esta sección porque no encontramos la postulación', "mensaje-error");
                $this->redirect(array('controller'=> 'login', 'action' => 'logout'));	
		}
		$codigo_postulante = $postulacion['Postulante']['codigo'];
		$usuario_logueado = $this->Session->read('UserLogued');
		$codigo_usuario_logueado = $usuario_logueado['Postulante']['codigo'];
		if ($codigo_usuario_logueado !== $codigo_postulante){				
		        $this->Session->setFlash('No puede acceder a esta sección.', "mensaje-error");
                $this->redirect(array('controller'=> 'login', 'action' => 'logout'));		
		}
		$estado = $this->Postulacion->estadoActual($codigo_postulacion);
	
		switch ($estado['Estado']['codigo']) {
			case 9:
				$this->redirect(array('controller'=> 'postulaciones', 'action' => 'respuesta', $codigo_postulacion));
				break;
			case 8:		
				$evidencias = $this->EvidenciasPrevias->find('first', array('conditions' => array('postulacion_codigo' => $codigo_postulacion, 'preliminar' => 0, 'validar' => 1)));
				if (!empty($evidencias)){
					$this->redirect(array('controller'=> 'postulaciones', 'action' => 'respuesta', $codigo_postulacion));
				}
				else {
					$this->redirect(array('controller'=> 'postulaciones', 'action' => 'evidenciasfinales', $codigo_postulacion));			
				}
				break;
			case 7:
				$this->redirect(array('controller'=> 'postulaciones', 'action' => 'respuesta', $codigo_postulacion));
				break;
			case 6:		
				//EXISTEN TRES CASOS, SI HA VALIDADO LAS EVIDENCIAS O SINO Y SI LA ENTREVISTA SE HA REALIZADO O NO 
				$evidencias = $this->EvidenciasPrevias->find('first', array('conditions' => array('postulacion_codigo' => $codigo_postulacion, 'preliminar' => 1, 'validar' => 1)));
				$entrevista = $this->Entrevista->find('first', array('conditions' => array('postulacion_codigo' => $codigo_postulacion)));
				if (!empty($evidencias) && (empty($entrevista))){
					if ($entrevista['Entrevista']['ESTADO'] == 'REALIZADO'){
						$this->redirect(array('controller'=> 'postulaciones', 'action' => 'evidenciasfinales', $codigo_postulacion));					
					}
					else{						
						$this->redirect(array('controller'=> 'entrevistas', 'action' => 'agendaPostulante', $codigo_postulacion));	
					}
				}					
				else{
					$this->redirect(array('controller'=> 'postulaciones', 'action' => 'evidenciasprevias', $codigo_postulacion));	
				}
				break;	
			case 5:				
				$this->redirect(array('controller'=> 'postulaciones', 'action' => 'autoEvaluacion', $codigo_postulacion));
				break;
			case 4:
				$this->redirect(array('controller'=> 'postulaciones', 'action' => 'autoEvaluacion', $codigo_postulacion));
				break;
			case 3:
				$this->redirect(array('controller'=> 'postulaciones', 'action' => 'CvRap', $codigo_postulacion));
				break;				
			case 2:
				$this->redirect(array('controller'=> 'postulaciones', 'action' => 'cargaDocumentos', $codigo_postulacion));
				break;
			case 1:			
				$this->redirect(array('controller'=> 'postulaciones', 'action' => 'cargaDocumentos', $codigo_postulacion));
				break;
			case 0:						
				$this->redirect(array('controller'=> 'postulaciones', 'action' => 'completarPostulacion', $codigo_postulacion));
				break;
		}
	}
	
	//METODO QUE SUBIRÁ EL ARCHIVO DE ACTA DE TRAYECTORIA FORMATIVA A LA PLATAFORMA UNA VEZ FIRMADO POR EL POSTULANTE
	public function subirFirma(){
		echo var_dump($this->request->data);
		$codigo_postulacion = $this->request->data['Postulacione']['postulacion'];
		$postulante = $this->request->data['Postulacione']['postulante'];
		$archivo = $this->request->data['Postulacione']['archivo'];
		$archivo['extension'] = $this->obtieneExtension($archivo['name']);
		if ($this->validaArchivo($archivo) == false){
				$this->Session->setFlash('Hubo algún problema al subir el archivo', "mensaje-error");
				$this->redirect(array('controller'=> 'postulaciones', 'action' => 'verAdmision', $codigo_postulacion));		
		}
		if ($this->request->is(array('post'))) {
			$usuario_logueado = $this->Session->read('UserLogued');			
			if ($usuario_logueado['Postulante']['codigo'] == $postulante){
						if ($this->subirArchivoFirma($archivo, $codigo_postulacion)){	
							$data = array('Postulacion.codigo' => $codigo_postulacion);
							$this->Postulacion->updateAll(array('Postulacion.firma' => 1), $data); 
							$this->Session->setFlash('Archivo subido correctamente', "mensaje-exito");
							$this->redirect(array('controller'=> 'postulaciones', 'action' => 'verAdmision', $codigo_postulacion));
						}
						else {
							$this->Session->setFlash('Hubo algún problema al subir el archivo', "mensaje-error");
							$this->redirect(array('controller'=> 'postulaciones', 'action' => 'verAdmision', $codigo_postulacion));
						}
			}
			else{
				$this->redirect(array('controller'=> 'login', 'action' => 'logout', $codigo_postulacion));
			}
		}
		else{
			$this->Session->setFlash('Hubo problemas al acceder a esta sección', "mensaje-error");
            $this->redirect(array('controller'=> 'home', 'action' => 'postulantes', $codigo_postulacion));
		}
	}
				
	
	private function actualizaFecha($postulacion){		
		$data = array('Postulacion.codigo' => $postulacion);
		$data2 = array('Prepostulacion.codigo_postulacion' => $postulacion);
		$this->Postulacion->updateAll(array('Postulacion.modified' => 'SYSDATE'), $data); 		
		$this->Prepostulacion->updateAll(array('Prepostulacion.modified' => 'SYSDATE'), $data2); 		
	}
	
	
	private function validaArchivo($archivo){
		$extensiones_permitidas = array('jpg', 'gif','png','jpeg','pdf','doc','docx','odt','ods', 'rar', 'zip');
		$tamano_permitido = '5120000';
		if (!in_array($archivo['extension'], $extensiones_permitidas)){
			return false;
		}
		if ($tamano_permitido < $archivo['size']){
			return false;		
		}
		else {
			return true;
		}
	}
		
}
?>
