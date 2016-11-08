<?php
	App::uses('AppController', 'Controller');
	
	class OrientadoresController extends AppController {
	
		var $name = 'Orientadores';
		var $uses = array('Administrativo', 'Postulacion','Cargas','EducacionPostulacion','CapacitacionPostulacion',
		'LaboralPostulacion','AutoEvaluacion','CompetenciaPostulacion', 'EstadoPostulacion', 'Postulante', 'Horario','Entrevista', 'Cargas', 'EvidenciasPrevias', 'ArchivoEvidencia');
		var $layout = "administrativos-2016";
		function beforeFilter(){
			$this->validarAdmin();
	  
		}
		
		function index(){
			$userLogued = $this->Session->read('UserLogued');
			//echo var_dump($userLogued);
			$horarios = $this->Administrativo->horariosDelOrientador($userLogued['Administrativo']['codigo']);
			
			#debug($horarios);
			$this->set('horarios',$horarios);
		}
		
		
		//funcion para realizar la entrevista, cambia el estado a REALIZADA
		function estado()
		{
			if(! empty($this->data))
			{
				$orientador = $this->Administrativo->find('first', array('conditions' => array('Administrativo.codigo' => $this->data['Orientadores']['codigo'],
																							   'Administrativo.orientador' => 1)));
				if($orientador)
				{
					$entrevista = $this->Entrevista->find('first', array('conditions' => array('Entrevista.administrativo_codigo' =>$orientador['Administrativo']['codigo'],
																							   'Entrevista.postulacion_codigo' => $this->data['Orientadores']['codigo_postulacion'])));
					$horario = $this->Horario->find('first', array('conditions' => array('Horario.codigo' => $entrevista['Entrevista']['horario_codigo'])));
					$postulacion_codigo = $this->data['Orientadores']['codigo_postulacion'];
					if($entrevista)
					{
						$this->loadModel('Alerta');
						$this->Entrevista->query("UPDATE RAP_ENTREVISTAS SET ESTADO = 'REALIZADO' WHERE CODIGO = '".$entrevista['Entrevista']['codigo']."'");
						$this->Horario->query("UPDATE RAP_HORARIOS SET ESTADO = 'REALIZADO' WHERE CODIGO = '".$horario['Horario']['codigo']."'");
						$this->Alerta->crear_alerta(17,null,$postulacion_codigo);
						$this->Session->setFlash(__('La entrevista fue realizada.'),'mensaje-exito');
						$this->redirect(array('action'=>'index'));	
					}else{
						$this->Session->setFlash(__('Error! Usted no tiene esta entrevista.'),'mensaje-error');
						$this->redirect(array('action'=>'index'));
					}
				}else{
					$this->Session->setFlash(__('Error! Usted no es Orientador ó no tiene perfil asignado.'),'mensaje-error');
					$this->redirect(array('action'=>'index'));
				}
			}else{
				$this->Session->setFlash(__('Error! Usted no es Orientador.'),'mensaje-error');
				$this->redirect(array('action'=>'index'));
			}
		}
		
		
	function listadopostulaciones(){
			$this->response->disableCache();			
			//DEBIDO A UN BUG DE CAKEPHP, NO SE PERMITE ORDENAR "DINAMICAMENTE" LAS CONSULTAS JOIN POR UN CAMPO QUE NO PERTENECE AL MODELO
			//LO QUE SE CREA AQUÍ ES UN CAMPO VIRTUAL QUE HACE LAS VECES DE LA UNIÓN DEL MODELO, Y LUEGO A LA HORA DE PAGINARLO TE PERMITE
			$this->Postulacion->virtualFields = array(
						'nombre_postulante' => 'Postulante.nombre',
						'sede' => 'Sede.nombre_sede',
						'carrera' => 'Carrera.nombre',						
						'jornada' => 'Postulacion.jornada',						
						'estado' => 'maximo',						
						'fecha_creacion' => 'Postulacion.created',						
						'fecha' => 'fechita'						
			);
			
			if(isset($this->params) && ! empty($this->params['pass'][0]))
			{
				$numero_filtro =  $this->params['pass'][0];
				
				if($numero_filtro == 10)
				{
					$numero_filtro = 6;
					$condicion = array('NOT' => array('Postulantes.nombre' => null),
													'Postulantes.activo' => 1,
													'Evidencia.preliminar' => 1);
				}elseif($numero_filtro == 11)
				{
					$numero_filtro = 8;
					$condicion = array('NOT' => array('Postulantes.nombre' => null),
													'Postulantes.activo' => 1,
													'Evidenciasfinales.preliminar' => 0);
				}elseif($numero_filtro == 6)
				{
					$numero_filtro = 6;
					$condicion = array('NOT' => array('Postulantes.nombre' => null),
													'Postulantes.activo' => 1,
													'Evidencia.preliminar' => null);
				}elseif($numero_filtro == 8)
				{
					$numero_filtro = 8;
					$condicion = array('NOT' => array('Postulantes.nombre' => null),
													'Postulacion.tipo' => 'RAP',
													'Evidenciasfinales.preliminar' => null);
				}
				else{					
					$condicion = array('NOT' => array('Postulantes.nombre' => null),
													 'Postulaciones.tipo' => 'RAP');
				}
				
				$this->paginate = array('limit' => 20,
										'conditions' => $condicion,
										'fields' => array( 'Postulaciones.codigo',
															'Postulaciones.tipo',															
														   'Postulantes.nombre',
 												           'Postulantes.codigo',
														   'Postulaciones.tipo',
														   'Carrera.codigo',
														   'Carrera.nombre',
														   'Sede.nombre_sede',
														   'Postulaciones.jornada',
														   'Postulaciones.id_correlativo',
														   'Postulaciones.created',
														   'Postulaciones.activo',
														   'Postulantes.activo',
														   'EstadoPostulacion.postulacion_codigo',
														   //'Postulaciones.carrera_codigo',
/*													   										 				  
 														  
														  
														   
														   'Postulaciones.jornada',	
														   
 														   'Carrera.nombre',
														   'Sede.nombre_sede', */
														   'MAX(Evidencia.preliminar) as evidencia',
														   'MAX(Evidenciasfinales.preliminar) as evidenciafinal',
														   'MAX (EstadoPostulacion.fecha_cambio) as fechita', 
														   'MAX (EstadoPostulacion.estado_codigo) as maximo',
														 ),
										'order BY' => array('EstadoPostulacion.estado_codigo' => 'DESC'),
										'group' => array ( 
																		'Postulantes.nombre',
		 																'Postulantes.codigo','Postulaciones.codigo', 
																		'Carrera.codigo',
																		'Carrera.nombre',
																		'Postulaciones.jornada',
																		'Postulantes.activo',
																		'Postulaciones.tipo',
																		'Postulaciones.activo',
																		'Postulaciones.created',
																		'Postulaciones.id_correlativo',
																	/*	'Postulaciones.carrera_codigo',	
																		'Postulaciones.activo',
																		'Postulaciones.jornada',
																																																																							
																		'Carrera.nombre',*/ 
																		'Sede.nombre_sede',																	
																		'EstadoPostulacion.postulacion_codigo HAVING MAX(EstadoPostulacion.estado_codigo) = '.$numero_filtro), 
										'joins' => array(
 														 array('table' => 'RAP_POSTULACIONES',
															   'type'	=> 'left',
															   'alias' => 'Postulaciones',
															   'conditions' => array('Postulaciones.codigo = EstadoPostulacion.postulacion_codigo')), 
														  array('table' => 'RAP_POSTULANTES',
															   'type'	=> 'left',
															   'alias' => 'Postulantes',
															   'conditions' => array('Postulantes.codigo = EstadoPostulacion.postulante_codigo')),
														  array('table' => 'RAP_EVIDENCIAS_PREVIAS',
															   'type'	=> 'left',
															   'alias' => 'Evidencia',
															   'conditions' => array('Evidencia.postulacion_codigo = Postulaciones.codigo',
																					'Evidencia.preliminar = 1',
																					'Evidencia.validar = 1')),
														   array('table' => 'RAP_EVIDENCIAS_PREVIAS',
															   'type'	=> 'left',
															   'alias' => 'Evidenciasfinales',
															   'conditions' => array('Evidenciasfinales.postulacion_codigo = Postulaciones.codigo',
																					'Evidenciasfinales.preliminar = 0',
																					'Evidenciasfinales.validar = 1')),
														     array('table' => 'RAP_SEDES',
															   'type'	=> 'left',
															   'alias' => 'Sede',
															  'conditions' => array('Sede.codigo_sede = Postulaciones.sede_codigo')),
															  array('table' => 'RAP_CARRERAS',
															   'type'	=> 'left',
															   'alias' => 'Carrera',
															   'conditions' => array('Carrera.codigo = Postulaciones.carrera_codigo')) 											
														 ));
				$this->set('postulaciones',$this->paginate('EstadoPostulacion'));					
												
				
			}else{					
				$condiciones = array(
										'NOT' => array(
											(int) 0 => array('Postulante.nombre' => null)),
										'AND' => array(
											(int) 0 => array(
												'Postulante.activo' => 1
											),
											(int) 1 => array(
												'Postulacion.tipo' => 'RAP'
											)
										));				
				/* $condiciones = array(
									'NOT' => array('Postulante.nombre' => null),
									'AND' => array('Postulante.activo' => 1,'Postulacion.tipo' => 'RAP')
									); */
						$this->paginate = array('limit' => 20,
									'conditions' =>  array('NOT' => array('Postulante.nombre' => null),
																		'Postulacion.tipo' => 'RAP'),												
									'fields' => array(
												'MAX(EstadoPostulacion.fecha_cambio) as fechita',
												'Postulante.nombre',
												'Postulacion.codigo',
												'Evidencia.postulacion_codigo',
												'Postulacion.activo',
												'Postulacion.tipo',
												'Postulacion.created',
												'Postulacion.jornada as jornada',
												'MAX(Evidencia.preliminar) as evidencia',
												'MAX(Evidenciasfinales.preliminar) as evidenciafinal',
												'Postulante.codigo',
												'Postulante.nombre',
												'Postulante.activo',											
												'Carrera.nombre',
												'MAX(EstadoPostulacion.estado_codigo) maximo',												
												'Sede.nombre_sede'
												),	
									'group' => array (
												'Postulacion.codigo',
												'Postulacion.postulante_codigo',
												'Evidencia.postulacion_codigo',
												'Postulacion.codigo',
												'Postulacion.tipo',
												'Postulante.nombre', 
												'Postulacion.activo', 
												'Postulacion.jornada',												
												'Postulacion.created',												
												'Postulante.codigo',
												'Postulante.nombre',
												'Postulante.activo',
												'Carrera.nombre',												
												'Sede.nombre_sede'
												),
									'joins' => array(
 										array(
											'table' => 'RAP_SEDES',
											'type' => 'LEFT',
											'alias' => 'Sede',
											'conditions' => array('Sede.codigo_sede = Postulacion.sede_codigo')), 
										array(															
											'type'=>'left',
											'table'=>'RAP_ESTADOS_POSTULACIONES',
											'alias'=>'EstadoPostulacion',											
											'conditions'=>array('EstadoPostulacion.postulacion_codigo = Postulacion.codigo')),
										array(															
											'type'=>'left',
											'table'=>'RAP_EVIDENCIAS_PREVIAS',
											'alias'=>'Evidencia',											
											'conditions'=>array('Evidencia.postulacion_codigo = Postulacion.codigo',
																'Evidencia.preliminar = 1',
																'Evidencia.validar = 1'																
																)),
										array(															
											'type'=>'left',
											'table'=>'RAP_EVIDENCIAS_PREVIAS',
											'alias'=>'Evidenciasfinales',											
											'conditions'=>array('Evidenciasfinales.postulacion_codigo = Postulacion.codigo',
																'Evidenciasfinales.preliminar = 0',
																'Evidenciasfinales.validar = 1'																
																)),	
									)); 
			$this->set('postulaciones',$this->paginate('Postulacion'));
			}
	
			$this->loadModel('Estado');
			$estados =  $this->Estado->find('all', array ('order' => 'Estado.codigo asc'));
			$this->set('estados',$estados);
			
							$condiciones2 = array('Postulante.activo =' => '0');
				$postulantesNoActivos = $this->Postulante->find('all', array('conditions' => $condiciones2, 'order by' => 'Postulante.nombre'));

				$this->set('postulantesNoActivos',$postulantesNoActivos);

		}
		

		
		
		
		
		//ESTA FUNCIÓN ES IDÉNTICA A LA DE ADMINISTRATIVOS PERO NO SE MUESTRAN LAS EVIDENCIAS FINALES - SE APROVECHA A QUE NO SE MUESTREN CIERTOS BOTONES
		function postulaciones($cod_postulacion = null)	{		
			if($cod_postulacion == null){
				$postulaciones = $this->Postulacion->obtenerPostulaciones();
				foreach($postulaciones as $key => $valor){
					$estadoActual = $this->Postulacion->estadoActual($valor['Postulacion']['codigo']);					
					$postulaciones[$key]['Estado'] = $estadoActual['Estado']['nombre'];
				}

				$this->set('postulaciones', $postulaciones);
			}else{
				$estadoActual = $this->Postulacion->estadoActual($cod_postulacion);
				$postulacion = $this->Postulacion->datosCompletosPostulacion($cod_postulacion);
				if(empty($estadoActual) || empty($postulacion)){
					//$this->Session->setFlash(__('Error, al intentar navegar el sistema.'),'mensaje-exito');
					//$this->redirect(array('action'=>'postulaciones'));
				}
				$postulacion['Estado'] = $estadoActual['Estado'];
				$documentos = $this->Cargas->find('all',array('conditions'=>array('postulacion_codigo'=>$cod_postulacion)));
				$licencia_educacion_media = array();
				$fotocopia_carnet = array();
				$declaracion_renta = array();
				foreach($documentos as $documento){
					if($documento['Cargas']['tipo_archivo_codigo']==1){
						$licencia_educacion_media = $documento;
					}elseif($documento['Cargas']['tipo_archivo_codigo']==2){
						$fotocopia_carnet = $documento;
					}elseif($documento['Cargas']['tipo_archivo_codigo']==3){
						$declaracion_renta = $documento;
					}
				}
				$historial_educacional = $this->EducacionPostulacion->obtenerEducacionPostulacion($cod_postulacion);
				$capacitaciones = $this->CapacitacionPostulacion->find('all',array('conditions'=>array('postulacion_codigo'=>$cod_postulacion)));
				$historial_laboral = $this->LaboralPostulacion->find('all',array('conditions'=>array('postulacion_codigo'=>$cod_postulacion)));
				$autoevaluacion = $this->AutoEvaluacion->obtenerAutoevaluacion($cod_postulacion);
				$competencias = $this->CompetenciaPostulacion->find('all', array(
					'conditions' => array('postulacion_codigo'=>$cod_postulacion),
					'joins' => array(
						array(
							'table' => 'RAP_COMPETENCIA',
							'alias' => 'Compentencia',
							'type' => 'inner',
							'conditions' => array('Compentencia.codigo_competencia = CompetenciaPostulacion.competencia_codigo')
						)
					),
					'fields' => array(
						'Compentencia.nombre_competencia',
						'Compentencia.codigo_competencia',
						'Compentencia.troncal'
					)
				));
				$carrera_codigo = $postulacion['Postulacion']['carrera_codigo'];
				$ponderacion  =array();
				if($estadoActual['Estado']['codigo'] > 4){
					$ponderacion = $this->Postulacion->getPonderacion($carrera_codigo,$cod_postulacion);	
				}
				$observaciones = $this->Postulacion->find('first', array('conditions' => array('codigo' => $postulacion['Postulacion']['codigo'] )));
				$observaciones = $observaciones['Postulacion']['observaciones_cvrap'];				
				//Sacamos todos los estados de la postulación y ordenados por el estado del código así podemos luego mostrarlo en la vista
				$this->loadModel('EstadoPostulacion');
				$estados_postulacion = $this->EstadoPostulacion->find('all', array('conditions'=> array('EstadoPostulacion.postulacion_codigo' => $cod_postulacion), 'order' => 'EstadoPostulacion.estado_codigo'));				
				//echo var_dump($estados_postulacion);
				$this->loadModel('EvidenciasPrevias');
				$this->loadModel('ArchivoEvidencia');
				$this->loadModel('UnidadCompetencia');
				//EVIDENCIAS PREVIAS
				$evidencias = $this->EvidenciasPrevias->find('all', array('conditions'=>array('EvidenciasPrevias.postulacion_codigo' => $cod_postulacion, 'preliminar' => '1', 'validar' => '1')));
				$evidencia_datos = array();
				$fecha_validacion = $this->EvidenciasPrevias->find('first', array('conditions' => array('EvidenciasPrevias.postulacion_codigo' => $cod_postulacion, 'preliminar' => '1', 'EvidenciasPrevias.validar' => '1' )));
				//if (!Empty($fecha_validacion)) { $fecha_validacion = $fecha_validacion['EvidenciasPrevias']['fecha_validacion']; }								
				foreach($evidencias  as $clave => $datos)
				{
					$imagenes[$clave] = $this->ArchivoEvidencia->find('first', array('conditions' => array('ArchivoEvidencia.id_evidencia' => $datos['EvidenciasPrevias']['id'])));
					$evidencia_datos[$clave] = array('nombre_evidencia' => $datos['EvidenciasPrevias']['nombre_evidencia'],
											   'fecha_validacion' => $datos['EvidenciasPrevias']['fecha_validacion'],
											   'relacion' => $datos['EvidenciasPrevias']['relacion_evidencia'],
											   'imagen'	=> $imagenes[$clave]);
					$evidencia_datos[$clave]['uCom'] = $this->UnidadCompetencia->find('first', array('conditions' => array('Unidadcompetencia.codigo_unidad_comp' => $datos['EvidenciasPrevias']['cod_unidad_competencia'])));
				}	
				
							
				
				$this->response->disableCache();		
				
			

				//ENTREVISTA
				$entrevista = $this->Entrevista->find('first', array('conditions' => array('postulacion_codigo' => $cod_postulacion)));
				if (!empty($entrevista)){
					$horario = $this->Horario->find('first', array('conditions' => array('codigo' => $entrevista['Entrevista']['horario_codigo'])));
					$administrativo = $this->Administrativo->find('first', array('conditions' => array('codigo' => $entrevista['Entrevista']['administrativo_codigo'])));	
					$this->set('horario',$horario);
					$this->set('administrativo',$administrativo);
				}
					
				
				$this->set('fecha_validacion', $fecha_validacion);
				$this->set('evidencias',$evidencias);
				$this->set('estados_postulacion', $estados_postulacion);
				$this->set('ponderacion', $ponderacion); 
				$this->set('estadoActual', $estadoActual);
				$this->set('autoevaluacion', $autoevaluacion);
				$this->set('competencias', $competencias);
				$this->set('historial_laboral', $historial_laboral);
				$this->set('capacitaciones',$capacitaciones);
				$this->set('historial_educacional',$historial_educacional);
				$this->set('licencia_educacion_media',$licencia_educacion_media);
				$this->set('declaracion_renta',$declaracion_renta);
				$this->set('fotocopia_carnet',$fotocopia_carnet); 
				$this->set('postulacion', $postulacion);
				$this->set('cod_postulacion', $cod_postulacion);
				$this->set('observaciones', $observaciones);
				$this->set('evidencia_datos',$evidencia_datos);				
				if (isset($entrevista)){$this->set('entrevista',$entrevista);}

				$this->render('postulacion');
			}	
		}
		
		
	}
?>
