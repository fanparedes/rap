<?php
	App::uses('AppController', 'Controller');
	App::uses('CakeEmail', 'Network/Email');
	App::uses('BaseLog', 'Log/Engine');
	
	class AdministrativosController extends AppController {
	
		var $name = 'Administrativos';
		var $uses = array('Administrativo', 'ArchivoPostulante', 'Postulacion','Cargas','EducacionPostulacion','CapacitacionPostulacion',
			'LaboralPostulacion','Alerta', 'AutoEvaluacion','CompetenciaPostulacion', 'EstadoPostulacion','Carrera', 'Competencia',
			'Postulante', 'Horario','Entrevista', 'Cargas', 'EvidenciasPrevias', 'ArchivoEvidencia', 'Periodo', 'Prepostulacion', 'ArchivoPrepostulacion', 'Correo','EscuelaCarrera', 'Escuela', 'AdministrativoCarrera');
		var $layout = 'administrativos-2016';
		var $helpers = array('Form','Html','format');
		var $components = array('Utilidades','Mpdf','RequestHandler');
		public $paginate = array();
		
		function beforeFilter(){
			$this->validarAdmin();			
		}
		
		function stripAccents($string){
			return strtr($string,'àáâãäçèéêëìíîïñòóôõöùúûüýÿÀÁÂÃÄÇÈÉÊËÌÍÎÏÑÒÓÔÕÖÙÚÛÜÝ',
		'aaaaaceeeeiiiinooooouuuuyyAAAAACEEEEIIIINOOOOOUUUUY');
		}

				
		public function ajax_autocompletar(){	
			$this->loadModel('Postulante');
			$this->loadModel('Prepostulacion');
			$this->autoRender = false;
			$this->RequestHandler->respondAs('json');
			
			$term 	   = $this->params['pass'][1];
			$tipo 	   = $this->params['pass'][0];						
			$nombreMod = strtoupper($term);
			$nombreMod = $this->stripAccents($nombreMod);
			if ($tipo == 'PREPOSTULACIONES'){ //BUSCAMOS POR PREPOSTULACIONES	
				$result = array();
				if ((substr($term,0,3)) !== 'AEE'){
						/*$users = $this->Postulante->find('all', array('conditions' =>  
							array("OR" => array(
									'Postulante.rut LIKE' => '%'. $term .'%',
									'Postulante.nombre LIKE' => '%'.$nombreMod.'%',
									'Postulante.apellidop LIKE' => '%'.$nombreMod.'%',
									'Postulante.email LIKE' => '%'. $term .'%'
									)
								)
							)
						);*/
						$users = $this->Postulante->find('all', array('conditions' =>  
								array(
									'UPPER(Postulante.nombre || Postulante.apellidop || Postulante.apellidom || Postulante.email || Postulante.rut) LIKE' => '%'. str_replace(" ", "", $nombreMod).'%'
									)
							)
						);
						foreach($users as $key => $user){										
							$user['Postulante']['apellidop'] = utf8_encode(utf8_decode($user['Postulante']['apellidop']));
							$user['Postulante']['nombre']    = utf8_encode(utf8_decode($user['Postulante']['nombre']));
							$nombre = $user['Postulante']['nombre']!='' && $user['Postulante']['nombre']!='null' ? $user['Postulante']['nombre'] : '';
							$apellidop = $user['Postulante']['apellidop']!='' && $user['Postulante']['apellidop']!='null' ? $user['Postulante']['apellidop'] : '';
							$apellidom = $user['Postulante']['apellidom']!='' && $user['Postulante']['apellidom']!='null' ? $user['Postulante']['apellidom'] : '';
							$email = $user['Postulante']['email']!='' && $user['Postulante']['email']!='null' ? $user['Postulante']['email'] : '';
							$rut = $user['Postulante']['rut']!='' && $user['Postulante']['rut']!='null' ? $user['Postulante']['rut'] : '';
							$rs_cadena = $nombre.' '.$apellidop.' '.$apellidom.', '.$email.', '. number_format( substr ( $rut, 0 , -1 ) , 0, "", ".") . '-' . substr ($rut, strlen($rut) -1 , 1 );
							//Debugger::dump($rs_cadena);
							if($rs_cadena!='null'){
								array_push($result, $rs_cadena);
							}
						} 
				} 
				else {
					$users = $this->Prepostulacion->find('all', array('conditions' =>  array('Prepostulacion.id_correlativo LIKE' => '%'.$term.'%')));
					$postulantes = $this->Postulante->find('list', array('fields' => array('Postulante.codigo', 'Postulante.apellidos')));
					foreach ($postulantes as $k => $postulante){						
							$postulantes[$k] = utf8_decode($postulante);										
					}					
					foreach($users as $key => $user){
						$codigo_postulante = $user['Postulante']['codigo'];
						$user['Postulante']['apellidop'] = utf8_decode($user['Postulante']['apellidop']);
						array_push($result, ($user['Prepostulacion']['id_correlativo']).', '.$user['Postulante']['nombre'].' '.$user['Postulante']['apellidop'].', '.$user['Postulante']['email']);
					} 								
				}	
			}
			elseif ($tipo == 'TODOS'){
				$result = array();
				/* SI EL TERMINO A BUSCAR EMPIEZA POR AEE ENTONCES BUSCA EN LAS POSTULACIONES SI NO BUSCA EN LOS POSTULANTES */
				if ((substr($term,0,3)) !== 'AEE'){
					if($tipo == 'TODOS'){
						$condicion =  array('Postulacion.tipo <>' => 'RAP');
					}		
					$users = $this->Postulacion->find('all', array('conditions' =>  array("OR" => array('Postulante.rut LIKE' => '%'. $term .'%',
																									   'Postulante.nombre LIKE' => '%'.$nombreMod.'%',
																									   'Postulante.apellidop LIKE' => '%'.$nombreMod.'%',
																									   'Postulante.apellidom LIKE' => '%'.$nombreMod.'%',
																									   'Postulante.email LIKE' => '%'. $term .'%'),
																						  "AND" => $condicion,	
																						)));
					foreach($users as $key => $user){	
						$user['Postulante']['apellidos'] = utf8_decode($user['Postulante']['apellidos']);					
						array_push($result, ($user['Postulante']['nombre'].' '.$user['Postulante']['apellidop'].' '.$user['Postulante']['apellidom']).', '.$user['Postulante']['email'].', '. number_format( substr ( $user['Postulante']['rut'], 0 , -1 ) , 0, "", ".") . '-' . substr ( $user['Postulante']['rut'], strlen($user['Postulante']['rut']) -1 , 1 ));
					} 
				} 
				else {
					if($tipo == 'TODOS'){
						$condicion =  array('Postulacion.id_correlativo LIKE' => '%'. $term .'%','Postulacion.tipo <>' => 'RAP');
					}
					$users = $this->Postulacion->find('all', array('conditions' =>  array("AND" => $condicion)));
					foreach($users as $key => $user){
						$user['Postulante']['apellidos'] = utf8_decode($user['Postulante']['apellidos']);		
						array_push($result, ($user['Postulacion']['id_correlativo']).', '.$user['Postulante']['nombre'].' '.$user['Postulante']['apellidos'].', '.$user['Postulante']['email']);
					} 				
				}
			}
			elseif ($tipo == 'AH'){
				$result = array();
				$usuario = $this->Session->read('UserLogued');
				$tipo2 = $usuario['Administrativo']['tipo'];
				if ($tipo2 <> 'AH'){
					/* SI EL TERMINO A BUSCAR EMPIEZA POR AEE ENTONCES BUSCA EN LAS POSTULACIONES SI NO BUSCA EN LOS POSTULANTES */
					if ((substr($term,0,3)) !== 'AEE'){
						if($tipo == 'AH'){
							$condicion =  array('Postulacion.tipo' => 'AH');
						}		
						$users = $this->Postulacion->find('all', array('conditions' =>  array("OR" => array('Postulante.rut LIKE' => '%'. $term .'%',
																										   'Postulante.nombre LIKE' => '%'.$nombreMod.'%',
																										   'Postulante.apellidop LIKE' => '%'.$nombreMod.'%',
																										   'Postulante.apellidom LIKE' => '%'.$nombreMod.'%',
																										   'Postulante.email LIKE' => '%'. $term .'%'),
																							  "AND" => $condicion,	
																							)));
						foreach($users as $key => $user){
							$user['Postulante']['apellidos'] = utf8_decode($user['Postulante']['apellidos']);		
							array_push($result, ($user['Postulante']['nombre'].' '.$user['Postulante']['apellidop'].' '.$user['Postulante']['apellidom']).', '.$user['Postulante']['email'].', '. number_format( substr ( $user['Postulante']['rut'], 0 , -1 ) , 0, "", ".") . '-' . substr ( $user['Postulante']['rut'], strlen($user['Postulante']['rut']) -1 , 1 ));
						} 
					} 
					else {			
						$condicion =  array('Postulacion.id_correlativo LIKE' => '%'. $term .'%','Postulacion.tipo' => 'AH');
						$users = $this->Postulacion->find('all', array('conditions' =>  array("AND" => $condicion)));
						foreach($users as $key => $user){
							$user['Postulante']['apellidom'] = utf8_decode($user['Postulante']['apellidom']);
							$user['Postulante']['apellidop'] = utf8_decode($user['Postulante']['apellidom']);
							array_push($result, ($user['Postulacion']['id_correlativo']).', '.$user['Postulante']['nombre'].' '.$user['Postulante']['apellidop'].' '.$user['Postulante']['apellidom'].', '.$user['Postulante']['email']);
						} 				
					}
				}
				else{ //SI EL USUARIO ES UN ADMINISTRADOR DE ESCUELAS
						$carreras_administrativo = $this->AdministrativoCarrera->find('all', array('conditions' => array('AdministrativoCarrera.administrativo_id' => $usuario['Administrativo']['codigo'])));																				
						$carreras2 = '(';	//Aquí se buscarán las carreras por codigo que son del administrativo
						$numero = count($carreras_administrativo);
						$contador2 = '';
							foreach ($carreras_administrativo as $key => $carrera){	
									$contador2++;
									$carreras2 .= "'".$carrera['AdministrativoCarrera']['carrera_id']."'";
									if ($contador2 < $numero){
											$carreras2 .= ",";
											}
									}
									$carreras2 .= ')';			
					if ((substr($term,0,3)) !== 'AEE'){
							if($tipo == 'AH'){
								$condicion =  array('Postulacion.tipo' => 'AH', 'Postulacion.carrera_codigo IN '.$carreras2.'');
							}		
							$users = $this->Postulacion->find('all', array('conditions' =>  array("OR" => array('Postulante.rut LIKE' => '%'. $term .'%',
																											   'Postulante.nombre LIKE' => '%'.$nombreMod.'%',
																											   'Postulante.apellidom LIKE' => '%'.$nombreMod.'%',
																											   'Postulante.apellidop LIKE' => '%'.$nombreMod.'%',
																											   'Postulante.email LIKE' => '%'. $term .'%'),
																								  "AND" => $condicion,	
																								)));
							foreach($users as $key => $user){
								$user['Postulante']['apellidos'] = utf8_decode($user['Postulante']['apellidos']);
								array_push($result, ($user['Postulante']['nombre'].' '.$user['Postulante']['apellidos']).', '.$user['Postulante']['email'].', '. number_format( substr ( $user['Postulante']['rut'], 0 , -1 ) , 0, "", ".") . '-' . substr ( $user['Postulante']['rut'], strlen($user['Postulante']['rut']) -1 , 1 ));
							} 
						} 
						else {			
							$condicion =  array('Postulacion.id_correlativo LIKE' => '%'. $term .'%', 'Postulacion.tipo' => 'AH','Postulacion.carrera_codigo IN '.$carreras2.'' );
							$users = $this->Postulacion->find('all', array('conditions' =>  array("AND" => $condicion)));
							foreach($users as $key => $user){
								$user['Postulante']['apellidos'] = utf8_decode($user['Postulante']['apellidos']);
								array_push($result, ($user['Postulacion']['id_correlativo']).', '.$user['Postulante']['nombre'].' '.$user['Postulante']['apellidos'].', '.$user['Postulante']['email']);
							} 				
						}
				}
			}
			elseif ($tipo == 'AV'){
				$result = array();
				/* SI EL TERMINO A BUSCAR EMPIEZA POR AEE ENTONCES BUSCA EN LAS POSTULACIONES SI NO BUSCA EN LOS POSTULANTES */
				if ((substr($term,0,3)) !== 'AEE'){
					if($tipo == 'AV'){
						$condicion =  array('Postulacion.tipo' => 'AV');
					}		
					$users = $this->Postulacion->find('all', array('conditions' =>  array("OR" => array('Postulante.rut LIKE' => '%'. $term .'%',
																									   'nombre LIKE' => '%'.$nombreMod.'%',
																									   'Postulante.apellidop LIKE' => '%'.$nombreMod.'%',
																									   'Postulante.email LIKE' => '%'. $term .'%'),
																						  "AND" => $condicion,	
																						)));
					foreach($users as $key => $user){
						$user['Postulante']['apellidos'] = utf8_decode($user['Postulante']['apellidos']);
						array_push($result, ($user['Postulante']['nombre']).', '.$user['Postulante']['email'].', '. number_format( substr ( $user['Postulante']['rut'], 0 , -1 ) , 0, "", ".") . '-' . substr ( $user['Postulante']['rut'], strlen($user['Postulante']['rut']) -1 , 1 ));
					} 
				} 
				else {			
					$condicion =  array('Postulacion.id_correlativo LIKE' => '%'. $term .'%','Postulacion.tipo' => 'AV');
					$users = $this->Postulacion->find('all', array('conditions' =>  array("AND" => $condicion)));
					foreach($users as $key => $user){
						$user['Postulante']['apellidos'] = utf8_decode($user['Postulante']['apellidos']);
						array_push($result, ($user['Postulacion']['id_correlativo']).', '.$user['Postulante']['nombre'].' '.$user['Postulante']['apellidos'].', '.$user['Postulante']['email']);
					} 				
				}
			}
			else{ 					
				$result = array();
				// SI EL TERMINO A BUSCAR EMPIEZA POR AEE ENTONCES BUSCA EN LAS POSTULACIONES SI NO BUSCA EN LOS POSTULANTES 
				if ((substr($term,0,3)) !== 'AEE'){
					$users = $this->Postulante->find('all', array('conditions' =>  array("OR" => array('Postulante.rut LIKE' => '%'. $term .'%',
																									'nombre LIKE' => '%'.$nombreMod.'%',
																									'Postulante.apellidop LIKE' => '%'.$nombreMod.'%',
																									'Postulante.email LIKE' => '%'. $term .'%'))));
					foreach($users as $key => $user){
						$user['Postulante']['apellidos'] = utf8_decode($user['Postulante']['apellidos']);
						array_push($result, ($user['Postulante']['nombre'].' '.$user['Postulante']['apellidos']).', '.$user['Postulante']['email'].', '. number_format( substr ( $user['Postulante']['rut'], 0 , -1 ) , 0, "", ".") . '-' . substr ( $user['Postulante']['rut'], strlen($user['Postulante']['rut']) -1 , 1 ));
					} 
				} 
				else {
					$users = $this->Postulacion->find('all', array('conditions' =>  array("OR" => array('Postulacion.id_correlativo LIKE' => '%'. $term .'%'),
																						"AND" => array('Postulacion.tipo =' => $tipo)
																						)));
					foreach($users as $key => $user){
						$user['Postulante']['apellidos'] = utf8_decode($user['Postulante']['apellidos']);
						array_push($result, ($user['Postulacion']['id_correlativo']).', '.$user['Postulante']['nombre'].' '.$user['Postulante']['apellidos'].', '.$user['Postulante']['email']);
					} 				
				}
			} 
			$users = $result;	
			echo json_encode($users);			
		}
    
	
	
		//FUNCIÓN MEJORADA DE BUSCADOR, SE COMENTA LA ANTERIOR PERO SE USARÁ LA ACTUAL
		function buscador(){
			$this->layout = 'administrativos-2016';			
			$busqueda = $this->request->data['Administrativos']['buscar'];	
			$tipo = $this->request->data['Administrativos']['tipo'];
			$terminos = explode(", ", $busqueda);	
			$busqueda = mb_strtoupper($busqueda);
			$terminos[0] = trim($terminos[0]);
			//COMENZAMOS LA CASUISTICA DE BUSCADORES
			if (substr(mb_strtoupper($terminos[0]),0,4) == 'AEE1'){ //Están buscando por código único	
				switch ($tipo) {
					case 'RAP':
						//Buscamos el código correlativo para sacar el id único y redireccionar a la pantalla que sea. 
						$postulacion = $this->Postulacion->find('first', array('conditions' => array('Postulacion.id_correlativo' => $terminos[0])));						
						if (!empty($postulacion)){
							$this->redirect(array('action'=>'postulaciones', $postulacion['Postulacion']['codigo']));
							}
						else {
							$this->Session->setFlash(__('El ID introducido no ha arrojado resultados.'),'mensaje-error');
							$this->redirect(array('action'=>'listadopostulaciones'));
						}
						break;
					case 'PREPOSTULACIONES':
						//Buscamos el código correlativo para sacar el id único y redireccionar a la pantalla que sea. 
						$prepostulacion = $this->Prepostulacion->find('first', array('conditions' => array('Prepostulacion.id_correlativo' => $terminos[0])));							
						if (!empty($prepostulacion)){
							$this->redirect(array('controller' => 'Coordinadores', 'action'=>'verPrepostulacion', $prepostulacion['Prepostulacion']['codigo']));
							}
						else {
							$this->Session->setFlash(__('El ID introducido no ha arrojado resultados.'),'mensaje-error');
							$this->redirect(array('action'=>'listadopostulaciones'));
						}
						break;
					case 'TODOS':						
						//Buscamos el código correlativo para sacar el id único y redireccionar a la pantalla que sea. 
						$postulaciones = $this->Postulacion->find('all', array('conditions' => array('Postulacion.id_correlativo' => $terminos[0])));						
						if (!empty($postulaciones)){
								$cantidad = count($postulaciones);
								$vista_buscador = $tipo;
								$this->set('postulaciones', $postulaciones);							
								$this->set('vista_buscador', $vista_buscador);							
								$this->set('catidad_result', $cantidad);	
							}
						else {
							$this->Session->setFlash(__('El ID introducido no ha arrojado resultados.'),'mensaje-error');
							$this->redirect(array('action'=>'listadoPostulacionesNuevas'));
						}
						break;
					case 'AH'://TENEMOS QUE SEPARAR CUANDO EL ADMINISTRATIVO ES HORIZONTAL DE UNA ESCUELA O ES UN SUPERUSUARIO						
						$usuario = $this->Session->read('UserLogued');												
						if ($usuario['Administrativo']['tipo'] !== 'AH'){
							//Buscamos el código correlativo para sacar el id único y redireccionar a la pantalla que sea. 
							$postulaciones = $this->Postulacion->find('all', array('conditions' => array('Postulacion.id_correlativo' => $terminos[0])));													
							if (!empty($postulaciones)){
									$cantidad = count($postulaciones);
									$vista_buscador = $tipo;
									$this->set('postulaciones', $postulaciones);							
									$this->set('vista_buscador', $vista_buscador);							
									$this->set('catidad_result', $cantidad);	
								}
							else {
								$this->Session->setFlash(__('La búsqueda no ha arrojado resultados.'),'mensaje-error');
								$this->redirect(array('action'=>'listadoPostulacionesNuevas'));
							}
						}
						else{								
							$escuela_administrativo = $usuario['Administrativo']['escuela_id'];							
							/* 
							CODIGO POR ESCUELA
							$carreras = $this->EscuelaCarrera->find('all', array('conditions' => array('escuela_codigo' => $escuela_administrativo)));							
							$carreras_administrativo = array(); //Aquí se guardarán todos los códigos de las carreras que se pueden visualizar. 
							$carreras2 = '(';	
							$numero = count($carreras);
								foreach ($carreras as $key => $carrera){						
									$carreras2 .= "".$carrera['EscuelaCarrera']['carrera_codigo'];
									if ($key+1 < $numero){
										$carreras2 .= ",";
									}
								}
								$carreras2 .= ')';
							$postulaciones = $this->Postulacion->find('all', array('conditions' => array('AND' => array('Postulacion.id_correlativo' => $terminos[0], 'Postulacion.carrera_codigo IN '.$carreras2.''))));						
							 */
							$carreras_administrativo = $this->AdministrativoCarrera->find('all', array('conditions' => array('AdministrativoCarrera.administrativo_id' => $usuario['Administrativo']['codigo'])));																				
							$carreras2 = '(';	//Aquí se buscarán las carreras por codigo que son del administrativo
							$numero = count($carreras_administrativo);
							$contador2 = '';
							foreach ($carreras_administrativo as $key => $carrera){	
									$contador2++;
									$carreras2 .= "'".$carrera['AdministrativoCarrera']['carrera_id']."'";
									if ($contador2 < $numero){
											$carreras2 .= ",";
											}
									}
									$carreras2 .= ')';	
							$postulaciones = $this->Postulacion->find('all', array('conditions' => array('AND' => array('Postulacion.id_correlativo' => $terminos[0], 'Postulacion.carrera_codigo IN '.$carreras2.''))));													
							if (!empty($postulaciones)){
									$cantidad = count($postulaciones);
									$vista_buscador = $tipo;
									$this->set('postulaciones', $postulaciones);							
									$this->set('vista_buscador', $vista_buscador);							
									$this->set('catidad_result', $cantidad);	
								}
							else {
								$this->Session->setFlash(__('El ID no ha arrojado resultados o no tiene permiso para acceder a esta postulación.'),'mensaje-error');
								$this->redirect(array('action'=>'listadoPostulacionesNuevas'));
							}	
						}
						break;
					case 'AV':						
						//Buscamos el código correlativo para sacar el id único y redireccionar a la pantalla que sea. 
						$postulaciones = $this->Postulacion->find('all', array('conditions' => array('Postulacion.id_correlativo' => $terminos[0], 'tipo' => 'AV')));						
						if (!empty($postulaciones)){
								$cantidad = count($postulaciones);
								$vista_buscador = $tipo;
								$this->set('postulaciones', $postulaciones);							
								$this->set('vista_buscador', $vista_buscador);							
								$this->set('catidad_result', $cantidad);	
							}
						else {
							$this->Session->setFlash(__('La búsqueda no ha arrojado resultados.'),'mensaje-error');
							$this->redirect(array('action'=>'listadoPostulacionesNuevas'));
						}
						break;					
				}
			}
			elseif (!empty($terminos[1])) { //Se está buscando por el usuario no por el código de la postulación
				switch ($tipo){
					case 'RAP':						
						$postulante = $this->Postulante->find('first', array('conditions' => array('Postulante.email' => $terminos[1])));	
						if (!empty($postulante)){
							$codigo_postulante = $postulante['Postulante']['codigo'];
							$postulaciones = $this->Postulacion->find('all', array('conditions' => array('Postulacion.postulante_codigo' => $codigo_postulante), 'order' => 'Postulacion.created desc'));							
							$cantidad = count($postulaciones);
							$vista_buscador = 'RAP';
							$this->set('postulaciones', $postulaciones);							
							$this->set('vista_buscador', $vista_buscador);							
							$this->set('catidad_result', $cantidad);							
							}
						else {
							$this->Session->setFlash(__('La búsqueda no ha arrojado resultados.'),'mensaje-error');
							$this->redirect(array('action'=>'listadopostulaciones'));
						}
						break;
					case 'PREPOSTULACIONES':											
						$postulante = $this->Postulante->find('first', array('conditions' => array('Postulante.email' => $terminos[1])));	
							if (!empty($postulante)){
								$codigo_postulante = $postulante['Postulante']['codigo'];
								$postulaciones = $this->Prepostulacion->find('all', array('conditions' => array('Postulacion.postulante_codigo' => $codigo_postulante), 'order' => 'Postulacion.created desc'));							
								$cantidad = count($postulaciones);
								$vista_buscador = 'PREPOSTULACIONES';
								$this->set('postulaciones', $postulaciones);							
								$this->set('vista_buscador', $vista_buscador);							
								$this->set('catidad_result', $cantidad);							
								}
							else {
								$this->Session->setFlash(__('La búsqueda no ha arrojado resultados.'),'mensaje-error');
								$this->redirect(array('action'=>'listadopostulaciones'));
							}
							break;
					case 'TODOS': //Se están buscando postulaciones AH y AV por parte del superusuario
						$postulante = $this->Postulante->find('first', array('conditions' => array('Postulante.email' => $terminos[1])));
							if (!empty($postulante)){
								$codigo_postulante = $postulante['Postulante']['codigo'];
								$postulaciones = $this->Postulacion->find('all', array('conditions' => array('OR' => array('Postulacion.tipo <>' => 'RAP'), 'AND' => array('Postulacion.postulante_codigo' => $codigo_postulante))
																									,'order' => 'Postulacion.created desc'));							
								$cantidad = count($postulaciones);
								$vista_buscador = 'TODOS2';
								$this->set('postulaciones', $postulaciones);							
								$this->set('vista_buscador', $vista_buscador);							
								$this->set('catidad_result', $cantidad);							
								}
							else {
								$this->Session->setFlash(__('La búsqueda no ha arrojado resultados.'),'mensaje-error');
								$this->redirect(array('action'=>'listadoPostulacionesNuevas'));
							}
							break;
					case 'AH': //Se están buscando postulaciones AH y AV por parte del superusuario
						$usuario = $this->Session->read('UserLogued');
						if ($usuario['Administrativo']['tipo'] !== 'AH'){
							$postulante = $this->Postulante->find('first', array('conditions' => array('Postulante.email' => $terminos[1])));
							if (!empty($postulante)){
								$codigo_postulante = $postulante['Postulante']['codigo'];
								$postulaciones = $this->Postulacion->find('all', array('conditions' => array('Postulacion.tipo' => 'AH', 'Postulacion.postulante_codigo' => $codigo_postulante)
																									,'order' => 'Postulacion.created DESC'));							
								$cantidad = count($postulaciones);
								$vista_buscador = 'TODOS2';
								$this->set('postulaciones', $postulaciones);							
								$this->set('vista_buscador', $vista_buscador);							
								$this->set('catidad_result', $cantidad);							
								}
							else {
								$this->Session->setFlash(__('La búsqueda no ha arrojado resultados.'),'mensaje-error');
								$this->redirect(array('action'=>'listadoPostulacionesNuevas'));
							}							
						}	
						else{							
							$postulante = $this->Postulante->find('first', array('conditions' => array('Postulante.email' => $terminos[1])));
							if (!empty($postulante)){
								$codigo_postulante = $postulante['Postulante']['codigo'];
								
								$escuela_administrativo = $usuario['Administrativo']['escuela_id'];							
								$carreras = $this->EscuelaCarrera->find('all', array('conditions' => array('escuela_codigo' => $escuela_administrativo)));							
								$carreras_administrativo = array(); //Aquí se guardarán todos los códigos de las carreras que se pueden visualizar. 
								$carreras2 = '(';	
								$numero = count($carreras);
								foreach ($carreras as $key => $carrera){						
									$carreras2 .= "".$carrera['EscuelaCarrera']['carrera_codigo'];
									if ($key+1 < $numero){
										$carreras2 .= ",";
									}
								}
								$carreras2 .= ')';	
								$postulaciones = $this->Postulacion->find('all', array('conditions' => array('Postulacion.tipo' => 'AH', 'Postulacion.postulante_codigo' => $codigo_postulante, 'Postulacion.carrera_codigo IN '.$carreras2.'')
																									,'order' => 'Postulacion.created DESC'));							
								$cantidad = count($postulaciones);
								$vista_buscador = 'TODOS2';
								$this->set('postulaciones', $postulaciones);							
								$this->set('vista_buscador', $vista_buscador);							
								$this->set('catidad_result', $cantidad);							
							}
							else {
								$this->Session->setFlash(__('La búsqueda no ha arrojado resultados.'),'mensaje-error');
								$this->redirect(array('action'=>'listadoPostulacionesNuevas'));
							}	
							
						}
						break;
					case 'AV': //Se están buscando postulaciones AH y AV por parte del superusuario
						$postulante = $this->Postulante->find('first', array('conditions' => array('Postulante.email' => $terminos[1])));
							if (!empty($postulante)){
								$codigo_postulante = $postulante['Postulante']['codigo'];
								$postulaciones = $this->Postulacion->find('all', array('conditions' => array('Postulacion.tipo' => 'AV', 'Postulacion.postulante_codigo' => $codigo_postulante)
																									,'order' => 'Postulacion.created DESC'));							
								$cantidad = count($postulaciones);
								$vista_buscador = 'TODOS2';
								$this->set('postulaciones', $postulaciones);							
								$this->set('vista_buscador', $vista_buscador);							
								$this->set('catidad_result', $cantidad);							
								}
							else {
								$this->Session->setFlash(__('La búsqueda no ha arrojado resultados.'),'mensaje-error');
								$this->redirect(array('action'=>'listadoPostulacionesNuevas'));
							}
							break;
				}
			}
			else{
				switch ($tipo) {
					case 'PREPOSTULACIONES':						
						$postulantes = $this->Postulante->find('all', array('conditions' => array('OR' => array("UPPER(Postulante.nombre) LIKE UPPER('%".$busqueda ."%')", "UPPER(Postulante.apellidop) LIKE UPPER('%".$busqueda ."%')", "UPPER(Postulante.apellidom) LIKE UPPER('%".$busqueda ."%')", "Postulante.rut LIKE UPPER('%".$busqueda ."%')", "Postulante.email LIKE ('%".$busqueda ."%')"))));							
						if (!empty($postulantes)){
									$postulantes2 = '(';	
									$numero = count($postulantes);
									$contador = '';
									foreach ($postulantes as $key => $postulante){	
										$contador++;
										$postulantes2 .= "'".$postulante['Postulante']['codigo']."'";
										if ($contador < $numero){
											$postulantes2 .= ",";
										}
									}
								$postulantes2 .= ')';
								$codigo_postulante = $postulante['Postulante']['codigo'];
								$postulaciones = $this->Prepostulacion->find('all', array('conditions' => array( 'Prepostulacion.postulante_codigo IN '.$postulantes2.''), 'order' => 'Postulacion.created desc'));							
								$cantidad = count($postulaciones);
								$vista_buscador = 'PREPOSTULACIONES';
								$this->set('postulaciones', $postulaciones);							
								$this->set('vista_buscador', $vista_buscador);							
								$this->set('catidad_result', $cantidad);							
								}
							else {
								$this->Session->setFlash(__('La búsqueda no ha arrojado resultados.'),'mensaje-error');
								//$this->redirect(array('controller' => 'Coordinadores', 'action'=>'listadoPrepostulaciones'));
							}
							break;
					case 'AH': //BUSCADOR DE HORIZONTAL						
						$postulantes = $this->Postulante->find('all', array('conditions' => array('OR' => array("UPPER(Postulante.nombre) LIKE UPPER('%".$busqueda ."%')", "UPPER(Postulante.apellidop) LIKE UPPER('%".$busqueda ."%')", "UPPER(Postulante.apellidom) LIKE UPPER('%".$busqueda ."%')", "Postulante.rut LIKE UPPER('%".$busqueda ."%')", "Postulante.email LIKE ('%".$busqueda ."%')"))));	
						if (!empty($postulantes)){
									$postulantes2 = '(';	
									$numero = count($postulantes);
									$contador = '';
									foreach ($postulantes as $key => $postulante){	
										$contador++;
										$postulantes2 .= "'".$postulante['Postulante']['codigo']."'";
										if ($contador < $numero){
											$postulantes2 .= ",";
										}
									}
								$postulantes2 .= ')';
								$codigo_postulante = $postulante['Postulante']['codigo'];
								//SI ES UN SUPERUSUARIO HAY QUE BUSCAR EN TODAS LAS POSTULACIONES								
									$usuario_logueado = $this->Session->read('UserLogued');
									if ($usuario_logueado['Administrativo']['perfil'] == '0'){
										$postulaciones = $this->Postulacion->find('all', array('conditions' => array('Postulacion.destino' => 'AH', 'Postulacion.postulante_codigo IN '.$postulantes2.''), 'order' => 'Postulacion.created desc'));							
									}
									else{ //ES UN ADMINISTRATIVO DE CARRERA
										$carreras_administrativo = $this->AdministrativoCarrera->find('all', array('conditions' => array('AdministrativoCarrera.administrativo_id' => $usuario_logueado['Administrativo']['codigo'])));																				
										$carreras2 = '(';	//Aquí se buscarán las carreras por codigo que son del administrativo
										$numero = count($carreras_administrativo);
										$contador2 = '';
										foreach ($carreras_administrativo as $key => $carrera){	
											$contador2++;
											$carreras2 .= "'".$carrera['AdministrativoCarrera']['carrera_id']."'";
											if ($contador2 < $numero){
													$carreras2 .= ",";
												}
											}
										$carreras2 .= ')';											
										$postulaciones = $this->Postulacion->find('all', array('conditions' => array('Postulacion.tipo' => 'AH', 'Postulacion.postulante_codigo IN '.$postulantes2.'', 'Postulacion.carrera_codigo IN '.$carreras2.''), 'order' => 'Postulacion.created desc'));							
									}		
								$cantidad = count($postulaciones);
									
								$vista_buscador = 'TODOS';
								$this->set('postulaciones', $postulaciones);							
								$this->set('vista_buscador', $vista_buscador);							
								$this->set('catidad_result', $cantidad);							
								}
							else {
								$this->Session->setFlash(__('La búsqueda no ha arrojado resultados.'),'mensaje-error');
								$this->redirect(array('controller' => 'Coordinadores', 'action'=>'listadoPrepostulaciones'));
							}
							break;	
						case 'TODOS': //BUSCADOR DE TODOS EN CASO DE SER SUPER USUARIO	
						$postulantes = $this->Postulante->find('all', array('conditions' => array('OR' => array("UPPER(Postulante.nombre) LIKE UPPER('%".$busqueda ."%')", "UPPER(Postulante.apellidom) LIKE UPPER('%".$busqueda ."%')", "UPPER(Postulante.apellidop) LIKE UPPER('%".$busqueda ."%')",  "Postulante.rut LIKE UPPER('%".$busqueda ."%')", "Postulante.email LIKE ('%".$busqueda ."%')"))));	
						if (!empty($postulantes)){
									$postulantes2 = '(';	
									$numero = count($postulantes);
									$contador = '';
									foreach ($postulantes as $key => $postulante){	
										$contador++;
										$postulantes2 .= "'".$postulante['Postulante']['codigo']."'";
										if ($contador < $numero){
											$postulantes2 .= ",";
										}
									}
								$postulantes2 .= ')';
								$codigo_postulante = $postulante['Postulante']['codigo'];
								//SI ES UN SUPERUSUARIO HAY QUE BUSCAR EN TODAS LAS POSTULACIONES								
									$usuario_logueado = $this->Session->read('UserLogued');
									if ($usuario_logueado['Administrativo']['perfil'] == '0'){
										$postulaciones = $this->Postulacion->find('all', array('conditions' => array('Postulacion.tipo <>' => 'RAP', 'Postulacion.postulante_codigo IN '.$postulantes2.''), 'order' => 'Postulacion.created desc'));							
									}
								$cantidad = count($postulaciones);
									
								$vista_buscador = 'TODOS';
								$this->set('postulaciones', $postulaciones);							
								$this->set('vista_buscador', $vista_buscador);							
								$this->set('catidad_result', $cantidad);							
								}
							else {
								$this->Session->setFlash(__('La búsqueda no ha arrojado resultados.'),'mensaje-error');
								$this->redirect(array('controller' => 'Coordinadores', 'action'=>'listadoPrepostulaciones'));
							}
							break;
					case 'AV': //BUSCADOR VERTICAL					
						$postulantes = $this->Postulante->find('all', array('conditions' => array('OR' => array("UPPER(Postulante.nombre) LIKE UPPER('%".$busqueda ."%')", "UPPER(Postulante.apellidos) LIKE UPPER('%".$busqueda ."%')", "Postulante.rut LIKE UPPER('%".$busqueda ."%')", "Postulante.email LIKE ('%".$busqueda ."%')"))));	
						if (!empty($postulantes)){
									$postulantes2 = '(';	
									$numero = count($postulantes);
									$contador = '';
									foreach ($postulantes as $key => $postulante){	
										$contador++;
										$postulantes2 .= "'".$postulante['Postulante']['codigo']."'";
										if ($contador < $numero){
											$postulantes2 .= ",";
										}
									}
								$postulantes2 .= ')';
								$codigo_postulante = $postulante['Postulante']['codigo'];
								//SI ES UN SUPERUSUARIO HAY QUE BUSCAR EN TODAS LAS POSTULACIONES								
									$usuario_logueado = $this->Session->read('UserLogued');
									if ($usuario_logueado['Administrativo']['perfil'] == '0'){
										$postulaciones = $this->Postulacion->find('all', array('conditions' => array('Postulacion.destino' => 'AV', 'Postulacion.postulante_codigo IN '.$postulantes2.''), 'order' => 'Postulacion.created desc'));							
									}
									else{ //ES UN ADMINISTRATIVO VERTICAL												
										$postulaciones = $this->Postulacion->find('all', array('conditions' => array('Postulacion.tipo' => 'AV', 'Postulacion.postulante_codigo IN '.$postulantes2.''), 'order' => 'Postulacion.created desc'));							
									}		
								$cantidad = count($postulaciones);
									
								$vista_buscador = 'TODOS2';
								$this->set('postulaciones', $postulaciones);							
								$this->set('vista_buscador', $vista_buscador);							
								$this->set('catidad_result', $cantidad);							
								}
							else {
								$this->Session->setFlash(__('La búsqueda no ha arrojado resultados.'),'mensaje-error');
								$this->redirect(array('controller' => 'Coordinadores', 'action'=>'listadoPrepostulaciones'));
							}
							break;
					case 'RAP': //BUSCADOR VERTICAL	
						$postulantes = $this->Postulante->find('all', array('conditions' => array('OR' => array("UPPER(Postulante.nombre) LIKE UPPER('%".$busqueda ."%')", "UPPER(Postulante.apellidop) LIKE UPPER('%".$busqueda ."%')", "UPPER(Postulante.apellidom) LIKE UPPER('%".$busqueda ."%')", "Postulante.rut LIKE UPPER('%".$busqueda ."%')", "Postulante.email LIKE ('%".$busqueda ."%')"))));	
						if (!empty($postulantes)){
									$postulantes2 = '(';	
									$numero = count($postulantes);
									$contador = '';
									foreach ($postulantes as $key => $postulante){	
										$contador++;
										$postulantes2 .= "'".$postulante['Postulante']['codigo']."'";
										if ($contador < $numero){
											$postulantes2 .= ",";
										}
									}
								$postulantes2 .= ')';
								$codigo_postulante = $postulante['Postulante']['codigo'];											
								$postulaciones = $this->Postulacion->find('all', array('conditions' => array('Postulacion.tipo' => 'RAP', 'Postulacion.postulante_codigo IN '.$postulantes2.''), 'order' => 'Postulacion.created desc'));																		
								foreach ($postulaciones as $k=> $postulacion){
									 $postulaciones[$k]['Postulacion']['estado'] = $this->Postulacion->estadoRAP($postulacion['Postulacion']['codigo']);									 
								}
								$cantidad = count($postulaciones);
									
								$vista_buscador = 'RAP';						
								$this->set('postulaciones', $postulaciones);							
								$this->set('vista_buscador', $vista_buscador);							
								$this->set('catidad_result', $cantidad);							
								}
							else {
								$this->Session->setFlash(__('La búsqueda no ha arrojado resultados.'),'mensaje-error');
								$this->redirect(array('controller' => 'Coordinadores', 'action'=>'listadoPrepostulaciones'));
							}
							break;
					CASE 'POSTULANTES':		
						//$busqueda = $this->stripAccents($busqueda);											
						$postulantes = $this->Postulante->find('all', array('conditions' => array('OR' => array("
							TRANSLATE(CONVERT(nombre,  'AL32UTF8','WE8ISO8859P1'),'ÁÉÍÓÚ','AEIOU') LIKE TRANSLATE('%".$busqueda."%','ÁÉÍÓÚ','AEIOU')", 
							"UPPER(Postulante.apellidop) LIKE UPPER('%".$busqueda ."%')",
							"UPPER(Postulante.apellidom) LIKE UPPER('%".$busqueda ."%')", 
							"Postulante.rut LIKE UPPER('%".$busqueda ."%')", 
							"Postulante.email LIKE ('%".$busqueda ."%')"))));
						//echo var_dump($postulantes);
						//$log = $this->Postulante->getDataSource()->getLog(false, false);       
						//debug($log);
						
						if (!empty($postulantes)){									
								$postulaciones = $postulantes;
								$cantidad = count($postulaciones);									
								$vista_buscador = 'POSTULANTES';						
								$this->set('postulaciones', $postulaciones);							
								$this->set('vista_buscador', $vista_buscador);							
								$this->set('catidad_result', $cantidad);							
								}
							else {
								$this->Session->setFlash(__('La búsqueda no ha arrojado resultados.'),'mensaje-error');
								//$this->redirect(array('controller' => 'administrativos', 'action'=>'postulantes'));
							}
							break;
				}		
			}			
		}
		
		function home(){
			$perfil=$this->Session->read('UserLogued.Administrativo');
			$this->set('perfil', $perfil);	
			$this->layout='administrativos-2016';
			$this->loadModel('Perfil');			
			$rol = $this->Perfil->find('all', array ('conditions' => array('Perfil.id' => $perfil['perfil'])));	
			$escuela = $this->Escuela->find('first', array('conditions' => array('id' => $perfil['escuela_id'])));
			 
			if (isset($escuela) && (!empty($escuela))){ 
				$this->set('escuela', $escuela);
/* 				$carreras = $this->EscuelaCarrera->find('all',
					array(
						'conditions' => array('escuela_codigo' => $perfil['escuela_id']),
						'fields' => array('Carrera.codigo', 'Carrera.nombre', 'EscuelaCarrera.carrera_codigo', 'EscuelaCarrera.escuela_codigo'), 
						'order' => array('Carrera.nombre ASC'),
						'joins' => array(
							array(
								'table' => 'RAP_CARRERAS',
								'alias' => 'Carrera',
								'type' => 'INNER',
								'conditions' => array(
									'Carrera.codigo = EscuelaCarrera.carrera_codigo',
								)
							)
						))
						); */
				$carreras_usuario = $this->AdministrativoCarrera->find('list', array('conditions' => array('AdministrativoCarrera.administrativo_id' => $perfil['codigo']), 'fields' => array('carrera_id','administrativo_id')));
				foreach($carreras_usuario as $k => $carrera){
					$carreras[$k] = $this->Carrera->nombreCarrera($k);
				
				}
				if (!empty($carreras)){
					$this->set('carreras', $carreras);
				}
			}
			$this->set('rol', $rol);
		}
		

		function index(){ 
			$postulaciones = $this->Postulacion->obtenerCincoPostulaciones(); 
			$this->set('postulaciones', $postulaciones);
		}
		
		
		
		/* FUNCIÓN PRINCIPAL DE POSTULACIONES */
		function postulaciones($cod_postulacion = null){
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
				
				/*DOCUMENTOS NUEVOS DE PREPOSTULACION */
				$postulacion['Estado'] = $estadoActual['Estado'];
				
				/* TENEMOS QUE OBTENER LA DOCUMENTACIÓN PREVIA A LOS CAMBIOS DE 2016 */
				$fechaPostulacion = $postulacion['Postulacion']['created'];
				$date = strtotime($fechaPostulacion);		
				/* comparamos las fechas previas al paso a producción de la nueva fase RAP donde la documentación ya es distinta */
				if ($date < (strtotime('2015-12-04 23:59:59'))){						
						$archivos = $this->Cargas->find('all', array('conditions' => array('postulacion_codigo' => $cod_postulacion)));
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
								$anexos['0']['ArchivoPrepostulacion']['nombre_fisico'] = $documento['Cargas']['nombre_fisico_archivo'];
							}
						}
						$documentos['licencia'] = $licencia['Cargas'];
						$documentos['licencia']['nombre_fisico'] = $documentos['licencia']['nombre_fisico_archivo'];			
						$documentos['cedula'] = $cedula['Cargas'];
						$documentos['cedula']['nombre_fisico'] = $documentos['cedula']['nombre_fisico_archivo'];
						$this->set('documentos', $documentos);
				}			
				else {			
					$codigo_postulante = $postulacion['Postulacion']['postulante_codigo'];
					$licencia = $this->ArchivoPostulante->find('first', array('conditions' => array('codigo' => 'li-'.$codigo_postulante)));
					$cedula = $this->ArchivoPostulante->find('first', array('conditions' => array('codigo' => 'ci-'.$codigo_postulante)));
					/*OBTENEMOS EL CÓDIGO DE LA POSTULACIÓN */
					$prepostulacion = $this->Prepostulacion->find('first', array('conditions' => array('Prepostulacion.codigo_postulacion' => $cod_postulacion)));
					$codigo_prepostulacion = $prepostulacion['Prepostulacion']['codigo'];		
					$anexos = $this->ArchivoPrepostulacion->find('all', array('conditions' => array('ArchivoPrepostulacion.prepostulacion_codigo' => $codigo_prepostulacion)));
					if (!empty($licencia) && (!empty($cedula))){ 
						$documentos['licencia'] = $licencia['ArchivoPostulante'];
						$documentos['cedula'] = $cedula['ArchivoPostulante'];
						$this->set('documentos', $documentos);
					}			
				}	
				$this->set('anexos', $anexos);
				$historial_educacional = $this->EducacionPostulacion->obtenerEducacionPostulacion($cod_postulacion);
				$capacitaciones = $this->CapacitacionPostulacion->find('all',array('conditions'=>array('postulacion_codigo'=>$cod_postulacion)));
				$historial_laboral = $this->LaboralPostulacion->find('all',array('conditions'=>array('postulacion_codigo'=>$cod_postulacion)));
				$autoevaluacion = $this->AutoEvaluacion->obtenerAutoevaluacion($cod_postulacion);
				$competencias = $this->CompetenciaPostulacion->find('all', array(
					'order' => array('Compentencia.troncal ASC', 'Compentencia.codigo_competencia DESC'),
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
				#pr($postulacion);
				$carrera_codigo = $postulacion['Postulacion']['carrera_codigo'];
				$ponderacion  =array();
				if($estadoActual['Estado']['codigo'] > 4){
					$ponderacion = $this->Postulacion->getPonderacion($carrera_codigo,$cod_postulacion);	
				}
				$observaciones = $this->Postulacion->find('first', array('conditions' => array('codigo' => $postulacion['Postulacion']['codigo'] )));
				//$observaciones = $observaciones['Postulacion']['observaciones_cvrap'];				
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
											   'descripcion' => $datos['EvidenciasPrevias']['descripcion'],
											   'fecha_validacion' => $datos['EvidenciasPrevias']['fecha_validacion'],
											   'relacion' => $datos['EvidenciasPrevias']['relacion_evidencia'],
											   'imagen'	=> $imagenes[$clave],
											   'competencia'=> $datos['EvidenciasPrevias']['cod_unidad_competencia']);
					$evidencia_datos[$clave]['uCom'] = $this->UnidadCompetencia->find('first', array('conditions' => array('Unidadcompetencia.codigo_unidad_comp' => $datos['EvidenciasPrevias']['cod_unidad_competencia'])));
				}	
				$this->response->disableCache();
				
				//EVIDENCIAS FINALES
				$evidencias2 = $this->EvidenciasPrevias->find('all', array('conditions'=>array('EvidenciasPrevias.postulacion_codigo' => $cod_postulacion, 'preliminar' => 0, 'validar' => 1)));
				$evidencia_datos2 = array();
				$fecha_validacion2 = $this->EvidenciasPrevias->find('first', array('conditions' => array('EvidenciasPrevias.postulacion_codigo' => $cod_postulacion, 'preliminar' => 0, 'EvidenciasPrevias.validar' => '1' )));
				if (!Empty($fecha_validacion2)) { $fecha_validacion2 = $fecha_validacion2['EvidenciasPrevias']['fecha_validacion']; }
								
				foreach($evidencias2  as $clave => $datos2)
				{
					
					$imagenes2[$clave] = $this->ArchivoEvidencia->find('first', array('conditions' => array('ArchivoEvidencia.id_evidencia' => $datos2['EvidenciasPrevias']['id'])));
					$evidencia_datos2[$clave] = array('nombre_evidencia' => $datos2['EvidenciasPrevias']['nombre_evidencia'],
											   'descripcion' => $datos2['EvidenciasPrevias']['descripcion'],
											   'fecha_validacion' => $datos2['EvidenciasPrevias']['fecha_validacion'],
											   'relacion' => $datos2['EvidenciasPrevias']['relacion_evidencia'],
											   'imagen'	=> $imagenes2[$clave],
											   'competencia'=> $datos['EvidenciasPrevias']['cod_unidad_competencia']);
					$evidencia_datos2[$clave]['uCom'] = $this->UnidadCompetencia->find('first', array('conditions' => array('Unidadcompetencia.codigo_unidad_comp' => $datos2['EvidenciasPrevias']['cod_unidad_competencia'])));
				}

				//ENTREVISTA
				$entrevista = $this->Entrevista->find('first', array('conditions' => array('postulacion_codigo' => $cod_postulacion)));
				if (!empty($entrevista)){
					$horario = $this->Horario->find('first', array('conditions' => array('codigo' => $entrevista['Entrevista']['horario_codigo'])));
					$administrativo = $this->Administrativo->find('first', array('conditions' => array('codigo' => $entrevista['Entrevista']['administrativo_codigo'])));	
					$this->set('horario',$horario);
					$this->set('administrativo',$administrativo);
				}
				
				
				//FIRMA DE ACTA DE TRAYECTORIA FORMATIVA
				$archivo_firma = $this->Cargas->find('first', array('conditions' => array('Cargas.postulacion_codigo' => $cod_postulacion, 'Cargas.tipo_archivo_codigo' => '5')));
				$this->set('archivo_firma',$archivo_firma);
					
				
				$this->set('fecha_validacion', $fecha_validacion);
				$this->set('fecha_validacion', $fecha_validacion2);
				$this->set('evidencias',$evidencias);
				$this->set('evidencias2',$evidencias2);
				//$this->set('archivos_evidencias',$archivos_evidencias);
				$this->set('estados_postulacion', $estados_postulacion);
				$this->set('ponderacion', $ponderacion); 
				$this->set('estadoActual', $estadoActual);
				$this->set('autoevaluacion', $autoevaluacion);
				$this->set('competencias', $competencias);
				$this->set('historial_laboral', $historial_laboral);
				$this->set('capacitaciones',$capacitaciones);
				$this->set('historial_educacional',$historial_educacional);
/* 				$this->set('licencia_educacion_media',$licencia_educacion_media);
				$this->set('declaracion_renta',$declaracion_renta); */				 
				$this->set('documentos',$documentos); 
				$this->set('postulacion', $postulacion);
				$this->set('cod_postulacion', $cod_postulacion);
				$this->set('observaciones', $observaciones);
				$this->set('evidencia_datos',$evidencia_datos);
				$this->set('evidencia_datos2',$evidencia_datos2);
				
				if (isset($entrevista)){$this->set('entrevista',$entrevista);}

				$this->render('postulacion');
			}	
		}
		

		function postulantes($noactivado = null){
			//DEBIDO A UN BUG DE CAKEPHP, NO SE PERMITE ORDENAR "DINAMICAMENTE" LAS CONSULTAS JOIN POR UN CAMPO QUE NO PERTENECE AL MODELO
			//LO QUE SE CREA AQUÍ ES UN CAMPO VIRTUAL QUE HACE LAS VECES DE LA UNIÓN DEL MODELO, Y LUEGO A LA HORA DE PAGINARLO TE PERMITE
 			$this->Postulacion->virtualFields = array(
						'nombre' => 'Postulante.nombre',
						'rut' => 'Postulante.rut',
					    'email' => 'Postulante.email',						
						'fecha' => 'Postulante.fecha_nacimiento',					
						'fecha' => 'Postulante.fecha_activado',			
						'apellidop' => 'Postulante.apellidop',			
						'apellidom' => 'Postulante.apellidom',			
																					
			);
			
			if (isset($this->params['pass'][0])){
				$condiciones = array('Postulante.activo' => '0');
			}
			else {
				$condiciones = "";
			}
			
		
			$this->paginate = array(
								'conditions' => $condiciones,
								'limit' => 20,								
								'fields' => array(	'Postulante.codigo',
                                                    'Postulante.rut',
													'Postulante.nombre',
													'Postulante.apellidop',
													'Postulante.apellidom',
                                                    'Postulante.extranjero',
													'Postulante.fecha_activado',
                                                    'Postulante.email',
                                                    'Postulante.fecha_nacimiento',
                                                    //'Postulacion.codigo'
												),  
								'group' => array(
													//'Postulacion.codigo',
													'Postulante.codigo',
                                                    'Postulante.rut',
													'Postulante.nombre',
													'Postulante.apellidop',
													'Postulante.apellidom',
                                                  'Postulante.extranjero',
												  'Postulante.email',
												  'Postulante.fecha_activado',
												  'Postulante.fecha_nacimiento',
                                                  /*  'Postulante.nombre',
                                                   
                                                    
                                                    
                                                     */								
								),
                                 'joins' => array(
												array('table' => 'RAP_POSTULACIONES',
                                                         'type'   => 'LEFT',
                                                         'alias' =>'Postulacion',
                                                         'conditions' => array('Postulacion.postulante_codigo=Postulante.codigo'))
												));
			
			
			
		/*	$postulantes = $this->Postulante->find('all',array('conditions'=>array('activo'=>1)));
			$postulaciones = $this->Postulacion->find('all',array('fields'=>array('codigo','postulante_codigo'),'conditions'=>array('activo'=>1)));*/
			
			$this->set('postulantes',$this->paginate('Postulante'));	
			//$this->set('postulaciones',$postulaciones);				
		}
		
		
	
		function aceptarDocumentacion($cod_postulacion = null){
			if($cod_postulacion == null){
				echo "error navegacion";
			}else{
				$estadoActual = $this->Postulacion->estadoActual($cod_postulacion);
				$postulante = $this->Postulante->find('first', 
					array(
						'conditions' => array('codigo' => $estadoActual['EstadoPostulacion']['postulante_codigo'])
				));
				$estado = $estadoActual['Estado']['codigo'];
				if($estado == 2){
					$codigo = 'px';
					$codigo .= uniqid();
					$data_estado_postulacion = array(
						
						'codigo'=>$codigo,
						'postulacion_codigo'=>$cod_postulacion,
						'estado_codigo'=>3,	
						'fecha_cambio'=>date('Y-M-d H:i:s'),
						'postulante_codigo'=> $estadoActual['EstadoPostulacion']['postulante_codigo']
					);
					$this->EstadoPostulacion->create();
					$this->loadModel('Plazo');
					$plazo1 = $this->Plazo->find('first', array('conditions' => array('etapa_id' => 4)));
					$plazo1 = $plazo1['Plazo']['plazo'];
					$plazo2 = $this->Plazo->find('first', array('conditions' => array('etapa_id' => 5)));
					$plazo2 = $plazo2['Plazo']['plazo'];
					if($this->EstadoPostulacion->save($data_estado_postulacion)){
						$this->actualizaFecha($cod_postulacion);
						$this->Alerta->crear_alerta(3,null,$cod_postulacion);
						$postulacion = $this->Postulacion->find('first', array('conditions' => array('Postulacion.codigo' => $cod_postulacion), 'fields' => array('id_correlativo')));
						$idCorrelativo = $postulacion['Postulacion']['id_correlativo'];
				        if($this->Correo->enviarEmail($postulante, 2, $plazo1, $plazo2, null, $idCorrelativo ) == true){
				        	$this->Session->setFlash(__('El postulante ha pasado satisfactoriamente al siguiente estado.'),'mensaje-exito');
							$this->redirect(array('action'=>'postulaciones',$cod_postulacion));	
				        }else{
				        	$this->Session->setFlash(__('Error al enviar el e-mail al postulante.'),'mensaje-error');
				        }
					}else{
						$this->Session->setFlash(__('Error al cambiar estado.'),'mensaje-error');
						$this->redirect(array('action'=>'postulaciones',$cod_postulacion));
					}
				}elseif($estado < 2){
					$this->Session->setFlash(__('Error! El postulante tiene pendiente un estado anterior.'),'mensaje-error');
					$this->redirect(array('action'=>'postulaciones',$cod_postulacion));
				}else{
					$this->Session->setFlash(__('Error! El postulante ya pasó por este estado.'),'mensaje-error');
					$this->redirect(array('action'=>'postulaciones',$cod_postulacion));
				}
			}	
		}
	
		function aceptarCvrapAE($cod_postulacion = null){
			if($cod_postulacion == null){
				echo "error navegación";
			}else{
				$estadoActual = $this->Postulacion->estadoActual($cod_postulacion);
				$postulante = $this->Postulante->find('first', 
					array(
						'conditions' => array('codigo' => $estadoActual['EstadoPostulacion']['postulante_codigo'])
				));
				$estado = $estadoActual['Estado']['codigo'];
				$codigo = 'px';
				$codigo .= uniqid();
				if($estado == 5){
					$data_estado_postulacion = array(
						'codigo'=>$codigo,
						'postulacion_codigo'=>$cod_postulacion,
						'estado_codigo'=>6,	
						'fecha_cambio'=>date('Y-M-d H:i:s'),
						'postulante_codigo'=> $estadoActual['EstadoPostulacion']['postulante_codigo']
					);
					$this->EstadoPostulacion->create();					
					if($this->EstadoPostulacion->save($data_estado_postulacion)){
						//MAIL DE AVISO PARA QUE TERMINE DE SUBIR LAS EVIDENCIAS PREVIAS
							$this->LoadModel('Plazo');
							//Obtengo el plazo de tiempo destinado a esta etapa.
							$plazo = $this->Plazo->find('first',array('conditions' => array('Plazo.etapa_id' => 10)));
							$plazo1e = $plazo['Plazo']['plazo'];
							$plazo2 = $this->Plazo->find('first',array('conditions' => array('Plazo.etapa_id' => 8)));
							$plazo2e = $plazo2['Plazo']['plazo'];
							//echo var_dump($plazo);	
							$plazo = $plazo['Plazo']['plazo']-1+$plazo2['Plazo']['plazo'];
							$plazo = '+'.$plazo.'day';
							$plazoe = $plazo1e + $plazo2e;
							$hoy = date('Y-m-j');
							//echo $hoy;
							$nueva_fecha = strtotime($plazo,strtotime($hoy));
							$nueva_fecha = date('Y-m-j', $nueva_fecha);							
							$this->LoadModel('Correo');
							$data = array(
								array('codigo_postulacion' => $cod_postulacion,
									'etapa' => 6,
									'fecha_envio' => $nueva_fecha,
									'estado' => 'PENDIENTE',
									'intentos' => 0)
							);
							$this->Correo->saveAll($data);
							$this->Alerta->crear_alerta(6,null,$cod_postulacion);
							$this->actualizaFecha($cod_postulacion);
							$postulacion = $this->Postulacion->find('first', array('conditions' => array('Postulacion.codigo' => $cod_postulacion)));
							$idCorrelativo = $postulacion['Postulacion']['id_correlativo'];
						//FIN DE GUARDAR DATOS DE CORREO DE AVISO
				        if($this->Correo->enviarEmail($postulante, 3, $plazoe, null, null, $idCorrelativo) == true){
				        	$this->Session->setFlash(__('El postulante ha pasado satisfactoriamente al siguiente estado.'),'mensaje-exito');
							$this->redirect(array('action'=>'postulaciones',$cod_postulacion));	
				        }else{
				        	$this->Session->setFlash(__('Error al enviar el e-mail al postulante.'),'mensaje-error');
				        }
					}else{
						$this->Session->setFlash(__('Error al cambiar estado.'),'mensaje-error');
						$this->redirect(array('action'=>'postulaciones',$cod_postulacion));
					}
				}elseif($estado < 5){
					$this->Session->setFlash(__('Error! El postulante tiene pendiente un estado anterior.'),'mensaje-error');
					$this->redirect(array('action'=>'postulaciones',$cod_postulacion));
				}else{
					$this->Session->setFlash(__('Error! El postulante ya paso por este estado.'),'mensaje-error');
					$this->redirect(array('action'=>'postulaciones',$cod_postulacion));
				}
			}	
			exit;
		}
		
		//ESTE MÉTODO DEVUELVE UN LISTADO DE ALERTAS A LA VISTA
		function listadoAlertas($etapa = null) {
			$administrador = $this->Session->read('UserLogued');			
			if (($administrador['Administrativo']['tipo'] !== 'RAP') && ($administrador['Administrativo']['perfil'] > 0)){
					$this->Session->setFlash(__('No tiene permiso para acceder a esta sección'),'mensaje-error');
					$this->redirect(array('controller'=> 'login', 'action'=>'home'));
			}
			$hoy = date('Y-m-j');
			$hoy = $hoy.' 23:59:59';		
			
			if ($etapa == null) {
				$condiciones = array('Alerta.fecha_activacion <' => $hoy);			
			}
			else {
				$condiciones = array('Alerta.fecha_activacion <' => $hoy, 'Alerta.etapa' => $etapa);	
			}
			
			//pr($this->params);
			$this->Alerta->virtualFields = array(
												'nombre' => 'Postulantes.nombre',
												'carrera' => 'Carrera.nombre',
												'sede' => 'Sede.nombre_sede',						
												'nota' => 'Alerta.nota');
			$this->paginate = array(	'conditions' => $condiciones,										
										'fields' => array('Postulaciones.carrera_codigo',
														  'Postulaciones.postulante_codigo',
														  'Carrera.nombre',
														  'Sede.nombre_sede',
														  'Postulantes.nombre',
														  'Alerta.codigo_postulacion',
														  'Alerta.mensaje',
														  'Alerta.id',
														  'Alerta.fecha_activacion',
														  'Alerta.nota'),
										'order' => array('Alerta.fecha_activacion' => 'desc'),
										'group by' => 'Alerta.codigo_postulacion',
										'limit' => 20,
										'joins' => array(
														 array('table' => 'RAP_POSTULACIONES',
															   'type'	=> 'left',
															   'alias' => 'Postulaciones',
															   'conditions' => array('Postulaciones.codigo = Alerta.codigo_postulacion'),
															   ),
														array('table' => 'RAP_POSTULANTES',
															   'type'	=> 'left',
															   'alias' => 'Postulantes',
															   'conditions' => array('Postulantes.codigo = Postulaciones.postulante_codigo'),
															   ),
														array('table' => 'RAP_CARRERAS',
															   'type'	=> 'left',
															   'alias' => 'Carrera',
															   'conditions' => array('Carrera.codigo = Postulaciones.carrera_codigo'),
															   ),
														array('table' => 'RAP_SEDES',
															   'type'	=> 'left',
															   'alias' => 'Sede',
															   'conditions' => array('Sede.codigo_sede = Postulaciones.sede_codigo'),
															   )));														  
			$this->set('alertas',$this->paginate('Alerta'));
		}
		
		
		//MODIFICA LA NOTA DE LA ALERTA		
		function guardarNota($id = null,$texto = null){
			
			$nota = strtoupper($texto);
			
			if (!empty($id)) {				
				$this->Alerta->id = $id;
				
				if ($this->Alerta->saveField('nota', $nota)){
					if ($nota==null){
						$this->Session->setFlash(__('Nota borrada correctamente'),'mensaje-exito');}
					else{
						$this->Session->setFlash(__('Nota guardada correctamente'),'mensaje-exito');
					}					
					$this->redirect(array('action'=>'listadoalertas'));
				}
				else {
					$this->Session->setFlash(__('Nota no se pudo guardar'),'mensaje-error');
					$this->redirect(array('action'=>'listadoalertas'));				
				}			
			}	
		}
		//BORRAR MENSAJE DE ALERTA
		function borrarNota($id = null){
					
			if (!empty($id)) {				
				$this->Alerta->id = $id;
				
				if ($this->Alerta->saveField('nota', null)){
					if ($nota==null){
						$this->Session->setFlash(__('Nota borrada correctamente'),'mensaje-exito');}
					else{
						$this->Session->setFlash(__('Nota sin cambios correctamente'),'mensaje-exito');
					}					
					$this->redirect(array('action'=>'listadoalertas'));
				}
				else {
					$this->Session->setFlash(__('Nota no se pudo guardar'),'mensaje-error');
					$this->redirect(array('action'=>'listadoalertas'));				
				}			
			}	
		}
		
		//APLAZA LA ALERTA CAMBIANDO SU FECHA DE ACTIVACIÓN
		function recordarAlerta($id = null){
			
			$plazo = $this->data['Alerta']['plazo'];
			$fecha = date('Y-m-j');
			$nuevafecha = strtotime ( '+'.$plazo.' day' , strtotime ( $fecha ) ) ;
			$nuevafecha = date ( 'Y-m-j' , $nuevafecha );

			echo $nuevafecha;
			if (!empty($id)) {				
				$this->Alerta->id = $id;
				if ($this->Alerta->saveField('fecha_activacion', $nuevafecha)){					
					$this->Session->setFlash(__('Alerta retrasada correctamente '.$plazo.' días'),'mensaje-exito');										
					$this->redirect(array('action'=>'listadoalertas'));
				}
				else {
					$this->Session->setFlash(__('Error al retrasar la alerta'),'mensaje-error');
					$this->redirect(array('action'=>'listadoalertas'));				
				}			
			}
			else {
				$this->Session->setFlash(__('Error al acceder al ID de la alerta'),'mensaje-error');
				$this->redirect(array('action'=>'listadoalertas'));	
			}
		}
		
		
		//BORRAR LA ALERTA CAMBIANDO SU FECHA DE ACTIVACIÓN
		function borrarAlerta($id = null){			
			
			if (!empty($id)) {	
				$condition = array('Alerta.id' => $id);			
				if ($this->Alerta->deleteAll($condition,false)){					
					$this->Session->setFlash(__('Alerta borrada correctamente.'),'mensaje-exito');										
					$this->redirect(array('action'=>'listadoalertas'));
				}
				else {
					$this->Session->setFlash(__('Error al borrar la alerta.'),'mensaje-error');
					$this->redirect(array('action'=>'listadoalertas'));				
				}			
			}
			else {
				$this->Session->setFlash(__('Error al acceder al ID de la alerta'),'mensaje-error');
				$this->redirect(array('action'=>'listadoalertas'));	
			}

		}
		
		

		function cancelarPostulacion($cod_postulacion = null){
			if($cod_postulacion == null){
				echo "error navegacion";
			}else{
				$estadoActual = $this->Postulacion->estadoActual($cod_postulacion);
				$postulante = $this->Postulante->find('first', 
					array(
						'conditions' => array('codigo' => $estadoActual['EstadoPostulacion']['postulante_codigo'])
				));
				$codigo = 'px';
				$codigo .= uniqid();
				$data_estado_postulacion = array(
						'codigo'=>$codigo,
						'postulacion_codigo'=>$cod_postulacion,
						'estado_codigo'=>7,	
						'fecha_cambio'=>date('Y-M-d H:i:s'),
						'postulante_codigo'=> $estadoActual['EstadoPostulacion']['postulante_codigo']
				);
				$postulacion = $this->Postulacion->find('first',array('conditions'=>array('codigo'=>$cod_postulacion)));
				#motivo de rechazo en tabla Postulacion
				$postulacion = array(
					'motivo_rechazo'=>"'".$this->data['Postulacion']['motivo_rechazo']."'",
				);
				$this->Postulacion->updateAll($postulacion,array('codigo'=>$cod_postulacion));
				$this->EstadoPostulacion->create();
				if($this->EstadoPostulacion->save($data_estado_postulacion)){
			        if($this->Correo->enviarEmail($postulante, 5, 0, 0, null, $cod_postulacion)){
			        	$this->Session->setFlash(__('Se ha rechazado satisfactoriamente esta postulación. Además se ha enviado un correo al Postulante.'),'mensaje-exito');
						$this->redirect(array('action'=>'listadopostulaciones',$cod_postulacion));	
			        }else{
			        	$this->Session->setFlash(__('La postulación se ha rechazado pero hubo un ERROR al enviar el e-mail al postulante.'),'mensaje-error');
			        }
				}else{
					$this->Session->setFlash(__('Error al rechazar la postulación.'),'mensaje-error');
					$this->redirect(array('action'=>'listadopostulaciones',$cod_postulacion));
				}
			}
		}


		// GENERACIÓN DE PDF DEL ARCHIVO */
		function postulacionArchivo($cod_postulacion = null){			
			if($cod_postulacion == null){
				$this->Session->setFlash(__('Error al intentar generar PDF.'),'mensaje-error');
				$this->redirect(array('action'=>'postulaciones'));	
			}else{
				$this->layout = '';
				$estadoActual = $this->Postulacion->estadoActual($cod_postulacion);	
				$postulacion = $this->Postulacion->datosCompletosPostulacion($cod_postulacion);		
				
                $observaciones = $this->Postulacion->find('all',array('conditions'=>array('Postulacion.codigo'=>$cod_postulacion)));
				$observaciones = $observaciones[0]['Postulacion']['observaciones_cvrap'];				
				$prepostulacion = $this->Prepostulacion->find('first', array('conditions' => array('Prepostulacion.codigo_postulacion' => $cod_postulacion)));
				$codigo_prepostulacion = $prepostulacion['Prepostulacion']['codigo'];		
				$anexos = $this->ArchivoPrepostulacion->find('all', array('conditions' => array('ArchivoPrepostulacion.prepostulacion_codigo' => $codigo_prepostulacion)));
/* 				$licencia_educacion_media = array();
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
				}  */				
				$historial_educacional = $this->EducacionPostulacion->obtenerEducacionPostulacion($cod_postulacion);
				$capacitaciones = $this->CapacitacionPostulacion->find('all',array('conditions'=>array('postulacion_codigo'=>$cod_postulacion)));
				$historial_laboral = $this->LaboralPostulacion->find('all',array('conditions'=>array('postulacion_codigo'=>$cod_postulacion)));
				$autoevaluacion = $this->AutoEvaluacion->obtenerAutoevaluacion($cod_postulacion);				
				$carrera_codigo = $postulacion['Postulacion']['carrera_codigo'];
				$ponderacion=array();
				if($estadoActual['Estado']['codigo'] > 4){
					$ponderacion = $this->Postulacion->getPonderacion($carrera_codigo,$cod_postulacion);
				}
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
				//EVIDENCIAS PREVIAS
				$this->loadModel('UnidadCompetencia');
				$this->loadModel('AutoEvaluacion');
				$evidencias = $this->EvidenciasPrevias->find('all', array('conditions'=>array('EvidenciasPrevias.postulacion_codigo' => $cod_postulacion, 'preliminar' => '1', 'validar' => '1')));
				$evidenciasFinales = $this->EvidenciasPrevias->find('all', array('conditions'=>array('EvidenciasPrevias.postulacion_codigo' => $cod_postulacion, 'preliminar' => '0', 'validar' => '1')));
				
				//$evidencia_datos = array();
				$fecha_validacion = $this->EvidenciasPrevias->find('first', array('conditions' => array('EvidenciasPrevias.postulacion_codigo' => $cod_postulacion, 'preliminar' => '1', 'EvidenciasPrevias.validar' => '1' )));
				
				//if (!Empty($fecha_validacion)) { $fecha_validacion = $fecha_validacion['EvidenciasPrevias']['fecha_validacion']; }
				
				$codigos_unidad_comp = $this->AutoEvaluacion->find('all', array('conditions' => array('AutoEvaluacion.postulacion_codigo' =>$cod_postulacion)));
				
				///para pdf
				$datos_unidad_comp = array();
				foreach($codigos_unidad_comp as $key => $datos)
				{
					$datos_unidad_comp[$key] = $this->UnidadCompetencia->find('first', array('conditions' => array('Unidadcompetencia.codigo_unidad_comp' => $datos['AutoEvaluacion']['unidad_competencia_codigo']),
																							 'fields' => array('Unidadcompetencia.codigo_unidad_comp','Unidadcompetencia.nombre_unidad_comp')));
					
				}
				//para evidencias previas
				$nuevas_evidencias_pre = array();
				foreach($datos_unidad_comp as $clave => $datos_u_comp)
				{
					$nuevas_evidencias_pre[$clave][] = $datos_u_comp['Unidadcompetencia']['nombre_unidad_comp'];
					$nuevas_evidencias_pre[$clave][] = $this->EvidenciasPrevias->find('first', array('conditions'=>array('EvidenciasPrevias.postulacion_codigo' => $cod_postulacion, 'preliminar' => '1', 'validar' => '1',
																														'EvidenciasPrevias.cod_unidad_competencia' => $datos_u_comp['Unidadcompetencia']['codigo_unidad_comp']),
																									 'fields' => array('EvidenciasPrevias.nombre_evidencia','EvidenciasPrevias.relacion_evidencia')));				
					
				}
				
				//Armamos un nuevo array para las competencias nuevas_evidencias_final
				
				foreach ($evidencias as $k=>  $evidenciaprevia){
					$competencia = $this->Competencia->find('first', array('conditions' => array('codigo_competencia' => $evidenciaprevia['EvidenciasPrevias']['cod_unidad_competencia'])));					
					$evidenciasNuevas[$k]['codigo'] = $competencia['Competencia']['codigo_competencia'];
					$evidenciasNuevas[$k]['nombre'] = $competencia['Competencia']['nombre_competencia'];					
					$evidenciasNuevas[$k]['EvidenciasPrevias'] = $evidenciaprevia['EvidenciasPrevias'];	
				}	
				
				//para evidencias finales
				$nuevas_evidencias_final = array();
				foreach($datos_unidad_comp as $clave => $datos_u_comp)
				{
					$nuevas_evidencias_final[$clave][] = $datos_u_comp['Unidadcompetencia']['nombre_unidad_comp'];
					$nuevas_evidencias_final[$clave][] = $this->EvidenciasPrevias->find('first', array('conditions'=>array('EvidenciasPrevias.postulacion_codigo' => $cod_postulacion, 'preliminar' => 0,
																														'EvidenciasPrevias.cod_unidad_competencia' => $datos_u_comp['Unidadcompetencia']['codigo_unidad_comp']),
																									 'fields' => array('EvidenciasPrevias.nombre_evidencia','EvidenciasPrevias.relacion_evidencia')));
					
					
				}

				$fecha_validacion2 = $this->EvidenciasPrevias->find('first', array('conditions' => array('EvidenciasPrevias.postulacion_codigo' => $cod_postulacion, 'preliminar' => 0, 'EvidenciasPrevias.validar' => '1' )));

				//ENTREVISTA
				$entrevista = $this->Entrevista->find('first', array('conditions' => array('postulacion_codigo' => $cod_postulacion)));
				if (!empty($entrevista)){
					$horario = $this->Horario->find('first', array('conditions' => array('codigo' => $entrevista['Entrevista']['horario_codigo'])));
					$administrativo = $this->Administrativo->find('first', array('conditions' => array('codigo' => $entrevista['Entrevista']['administrativo_codigo'])));	
					$this->set('horario',$horario);
					$this->set('administrativo',$administrativo);
				}
				
				//NUEVAS EVIDENCIAS FINALES
				//Armamos un nuevo array para las competencias nuevas_evidencias_final
				
				foreach ($evidenciasFinales as $k=>  $evidenciaprevia){
					$competencia = $this->Competencia->find('first', array('conditions' => array('codigo_competencia' => $evidenciaprevia['EvidenciasPrevias']['cod_unidad_competencia'])));					
					$evidenciasFinalesNuevas[$k]['codigo'] = $competencia['Competencia']['codigo_competencia'];
					$evidenciasFinalesNuevas[$k]['nombre'] = $competencia['Competencia']['nombre_competencia'];					
					$evidenciasFinalesNuevas[$k]['EvidenciasPrevias'] = $evidenciaprevia['EvidenciasPrevias'];	
				}
								
				if(!Empty($fecha_validacion)){
					$this->set('evidencias_previas',$nuevas_evidencias_pre);
					$this->set('evidencias_previas2',$evidenciasNuevas);
				}
				//Si no hay fecha de validación, se supone que no se han validado las evidencias. 
				if (!Empty($fecha_validacion2)){
					$this->set('evidencias_finales',$nuevas_evidencias_final);					
					$this->set('evidencias_finales2',$evidenciasFinalesNuevas);
				}
				$file_name = "Postulación-".$postulacion['Postulante']['rut']. "-".date('dmY-Hi')."hrs.pdf";
		       	$this->Mpdf->init(); // setting filename of output pdf file
		        $this->Mpdf->setFilename($file_name); // setting output to I, D, F, S
				$this->Mpdf->ignore_invalid_utf8 = true;
		        $this->Mpdf->setOutput('D'); // you can call any mPDF method via component, for example:*/
				$this->set('estadoActual', $estadoActual);

				$this->set('entrevista',$entrevista);
				$this->set('ponderacion', $ponderacion);
				$this->set('autoevaluacion', $autoevaluacion);
				$this->set('competencias', $competencias);
				$this->set('historial_laboral', $historial_laboral);
				$this->set('capacitaciones',$capacitaciones);
				$this->set('observaciones',$observaciones);
				$this->set('anexos',$anexos);
 				$this->set('historial_educacional',$historial_educacional);
			/*	$this->set('licencia_educacion_media',$licencia_educacion_media);
				$this->set('declaracion_renta',$declaracion_renta); */
				$this->set('fotocopia_carnet',$fotocopia_carnet); 
				$this->set('postulacion', $postulacion);
				$this->set('cod_postulacion', $cod_postulacion);
			}
		}
		
		
		function listadopostulaciones(){
			$this->response->disableCache();
			/* COMPROBAMOS SI EL ADMINISTRATIVO ES RAP */
			$administrador = $this->Session->read('UserLogued');			
			if (($administrador['Administrativo']['tipo'] !== 'RAP') && ($administrador['Administrativo']['perfil'] > 0)){
					$this->Session->setFlash(__('No tiene permiso para acceder a esta sección'),'mensaje-error');
					$this->redirect(array('controller'=> 'login', 'action'=>'home'));
			}
			//COMPROBAMOS SI HAY ALERTAS PARA EL ADMINISTRATIVO
			$alerta = false;
			if ($this->Alerta->hay_alertas()){
				$alerta = true;		
			}
			else {
				$alerta = false;				
			}
			$this->set('alerta',$alerta);	
			//DEBIDO A UN BUG DE CAKEPHP, NO SE PERMITE ORDENAR "DINAMICAMENTE" LAS CONSULTAS JOIN POR UN CAMPO QUE NO PERTENECE AL MODELO
			//LO QUE SE CREA AQUÍ ES UN CAMPO VIRTUAL QUE HACE LAS VECES DE LA UNIÓN DEL MODELO, Y LUEGO A LA HORA DE PAGINARLO TE PERMITE
			$this->Postulacion->virtualFields = array(
					'nombre'            => 'Postulante.nombre',
					'apellidop'         => 'Postulante.apellidop',
					'apellidom'         => 'Postulante.apellidom',
					'sede' 			    => 'Sede.nombre_sede',
					'correlativo'       => 'Postulacion.id_correlativo',
					'carrera'		    => 'Carrera.nombre',						
					'jornada' 			=> 'Postulacion.jornada',						
					'estado' 		    => 'maximo',						
					'fecha'				=> 'fechita', 	
					'fecha_creacion'    => 'fecha_creacion'
			);
			
			if(isset($this->params) && ! empty($this->params['pass'][0])){
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
														   'Postulantes.apellidop',
														   'Postulantes.apellidom',
 												           'Postulantes.codigo',
														   'Postulaciones.tipo',
														   'Postulaciones.id_correlativo',
														   'Carrera.codigo',
														   'Carrera.nombre',
														   'Sede.nombre_sede',
														   'Postulaciones.jornada',
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
														   'MAX (EstadoPostulacion.created) as fecha_creacion'
														 ),
										'order BY' => array('EstadoPostulacion.estado_codigo' => 'DESC'),
										'group' => array ( 
																		'Postulantes.nombre',
																		'Postulantes.apellidop',
																		'Postulantes.apellidom',
		 																'Postulantes.codigo','Postulaciones.codigo', 
																		'Carrera.codigo',
																		'Carrera.nombre',
																		'Postulaciones.jornada',
																		'Postulantes.activo',
																		'Postulaciones.tipo',
																		'Postulaciones.activo',
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
				$this->Postulacion->virtualFields = array(
					'nombre'            => 'Postulante.nombre',
					'apellidop'         => 'Postulante.apellidop',
					'apellidom'         => 'Postulante.apellidom',
					'sede' 			    => 'Sede.nombre_sede',
					'correlativo'       => 'Postulacion.id_correlativo',
					'carrera'		    => 'Carrera.nombre',						
					'jornada' 			=> 'Postulacion.jornada',						
					'estado' 		    => 'maximo',						
					'fecha'				=> 'fechita', 	
					'fecha_creacion'    => 'fecha_creacion'
				);
						
						
						$this->paginate = array('limit' => 20,
									'conditions' =>  array('NOT' => array('Postulante.nombre' => null),
																		'Postulacion.tipo' => 'RAP'),												
									'fields' => array(
												'MAX(EstadoPostulacion.fecha_cambio) as fechita',
												'Postulante.nombre',
												'Postulante.apellidom',
												'Postulante.apellidop',
												'Postulacion.codigo',
												'Evidencia.postulacion_codigo',
												'Postulacion.activo',
												'Postulacion.tipo',
												'Postulacion.id_correlativo',
												'Postulacion.jornada as jornada',
												'MAX(Evidencia.preliminar) as evidencia',
												'MAX(Evidenciasfinales.preliminar) as evidenciafinal',
												'Postulante.codigo',
												'Postulante.nombre',
												'Postulante.activo',											
												'Carrera.nombre',
												'MAX(EstadoPostulacion.estado_codigo) maximo',												
												'Sede.nombre_sede',
												'MAX(EstadoPostulacion.created) as fecha_creacion',
												),	
									'group' => array (
												'Postulacion.codigo',
												'Postulacion.postulante_codigo',
												'Evidencia.postulacion_codigo',
												'Postulacion.codigo',
												'Postulacion.id_correlativo',
												'Postulacion.tipo',
												'Postulante.nombre', 
												'Postulante.apellidop', 
												'Postulante.apellidom', 
												'Postulacion.activo', 
												'Postulacion.jornada',												
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
		

		function agenda(){
			$administrador = $this->Session->read('UserLogued');			
			if (($administrador['Administrativo']['tipo'] !== 'RAP') && ($administrador['Administrativo']['perfil'] > 0)){
					$this->Session->setFlash(__('No tiene permiso para acceder a esta sección'),'mensaje-error');
					$this->redirect(array('controller'=> 'login', 'action'=>'home'));
			}			
			$orientadores = $this->Administrativo->orientadoresHoras();
			$carreras[0] = 'TODAS LAS CARRERAS';
			$carreras = $this->Carrera->find('all',array('order' => array('Carrera.nombre' => 'ASC'),
														  'fields' => array('Carrera.codigo','Carrera.nombre')));
			
			
			
			$option = array();
			foreach($carreras as $key => $datos)
			{
				$option[$datos['Carrera']['codigo']] =  $datos['Carrera']['nombre'];
			}
		
			$this->set('orientadores', $orientadores);
			$this->set('carreras', $option);
		}
		
		//funcion para filtrar por carrera
		function ajax_orientadores()
		{
			$this->layout = 'ajax';
			//Configure::write('debug',2);
			$codigo_carrera = $this->params['pass'][0];
			$carrera_nombre = $this->Carrera->find('first', array('conditions' => array('Carrera.codigo' => $codigo_carrera)));
		
			$orientadores = $this->Administrativo->orientadoresHorasAjax($codigo_carrera);
			
		   $this->set('carrera',$carrera_nombre);
		   $this->set('orientadores', $orientadores);
		}
	   
		function horario(){
			$administrador = $this->Session->read('UserLogued');			
			if (($administrador['Administrativo']['tipo'] !== 'RAP') && ($administrador['Administrativo']['perfil'] > 0)){
					$this->Session->setFlash(__('No tiene permiso para acceder a esta sección'),'mensaje-error');
					$this->redirect(array('controller'=> 'login', 'action'=>'home'));
			}			
			$orientadores = $this->Administrativo->orientadores();
			foreach($orientadores as $k => $orientador){
				$orientadores[$k]['ProximoHorario'] = $this->Horario->proximoHorarioDisponibleAdministrativo($orientador['Administrativo']['codigo']);
			}
			#debug($orientadores);
			$this->set('orientadores', $orientadores);
		}

		function orientador($administrativo_codigo = null, $fecha = null){
			if($administrativo_codigo == null){
				$this->Session->setFlash(__('Orientador no encontrado.'),'mensaje-error');
				$this->redirect(array('action'=>'horario'));
			}else{
				$fecha = $this->Utilidades->desencriptar($fecha);
				$orientador = $this->Administrativo->obtenerOrientador($administrativo_codigo);
				if(empty($orientador)){
					$this->Session->setFlash(__('Orientador no encontrado.'),'mensaje-error');
					$this->redirect(array('action'=>'horario'));
				}
				$horarios_orientador = $this->Administrativo->horariosDelOrientador($orientador['Administrativo']['codigo']);
				$this->set('horarios_orientador', $horarios_orientador);
				$this->set('orientador', $orientador);
				$this->set('fecha1', $fecha);
			}
		}
		
		function nuevaEntrevista(){
			$this->layout = 'ajax';
			if(!$this->request->is('post')){
				exit();
			}else{
				$administrativo_codigo = $this->request->data('codigo');
				$fecha = date('d-m-Y',strtotime($this->request->data('dia')));				
				$horarios = $this->Horario->find('all',array(
					'conditions'=>array(
						'Horario.administrativo_codigo'=>$administrativo_codigo,
						"Horario.hora_inicio BETWEEN TO_DATE('".$fecha." 00:00:00','DD-MM-YYYY HH24:MI:SS') AND TO_DATE('".$fecha." 23:59:59','DD-MM-YYYY HH24:MI:SS')"
					),
					'order'=>'Horario.hora_inicio'
				));
				//optener hora actual
				$hora_Actual =  getdate();
				$hora_Actual = $hora_Actual['hours'];
				$this->set('horarios', $horarios);
				$this->set('fecha', $fecha);
				$this->set('hora_actual',$hora_Actual);
				$this->set('codigo', $administrativo_codigo);
			}
		}
		
		function guardarHora(){
			if(!$this->request->is('post')){
				echo "error";
			}else{
				
				$administrativo_codigo = $this->data['Horario']['administrativo_codigo'];
				$hora_inicio = $this->data['Horario']['hora_inicio'];
				$min_inicio = $this->data['Horario']['min_inicio'];
				$hora_fin = $this->data['Horario']['hora_fin'];
				$min_fin = $this->data['Horario']['min_fin'];
				if($hora_inicio<10){
					$hora_inicio = '0'.$hora_inicio;
				}
				if($hora_fin<10){
					$hora_fin = '0'.$hora_fin;
				}
				$hora_real_inicio = $this->data['Horario']['fecha']." ".$hora_inicio.":".$min_inicio.":00";
				$hora_real_fin = $this->data['Horario']['fecha']." ".$hora_fin.":".$min_fin.":00";
				
				$hora_inicio = $hora_inicio.":".$min_inicio;
				$hora_fin = 	$hora_fin.":".$min_fin;
				
				
				$fecha = date('d-m-Y H:i:s',strtotime($this->data['Horario']['fecha']));
				$codigo_horario = 'hr';
				$codigo_horario .= uniqid();
				$data_horario = array('Horario' => array(
					'codigo' => $codigo_horario,
					'hora_inicio' => $hora_real_inicio,
					'hora_fin' => $hora_real_fin,
					'administrativo_codigo' => $administrativo_codigo,
					'fecha' => $fecha
				));
				//valida que las fechas y horas no esten tomadas				
				$horas_sistema = $this->Horario->find('all', array('conditions' => array('Horario.administrativo_codigo' => $data_horario['Horario']['administrativo_codigo'],
																						 'Horario.fecha >=' => $fecha)));
				
				foreach($horas_sistema as $key => $hora)
				{
				
					 $desde = date('H:i',strtotime($hora['Horario']['hora_inicio']));
					 $hasta = date('H:i',strtotime($hora['Horario']['hora_fin']));
					 
					 if(!(strtotime($hora_inicio)  < strtotime($desde) && strtotime($hora_fin) <= strtotime($desde)) && !(strtotime($hora_fin) > strtotime($hasta) && strtotime($hora_inicio) >= strtotime($hasta)))
					 {
						$this->Session->setFlash(__('Revisar horario, está cita ya esta agendada.'),'mensaje-error');
						$this->redirect(array('action'=>'orientador',$administrativo_codigo));
					 }
					
				}
				//fin de validacion
				$this->Horario->create();
				if($this->Horario->save($data_horario)){
					$this->Session->setFlash(__('La hora se ha guardado Correctamente.'),'mensaje-exito');
					$fecha1 = $this->Utilidades->encriptar($fecha);
					$this->redirect(array('action'=>'orientador',$administrativo_codigo, $fecha1));	
				}else{
					$this->Session->setFlash(__('Error al guardar la hora.'),'mensaje-error');
					$this->redirect(array('action'=>'orientador',$cod_encuestador));	
				}
				
			}
		}
	
		function eliminarHora($codigo = null, $cod_encuestador = null, $fecha = null){
			if($codigo == null && $cod_encuestador == null){
				echo "error";
			}else{
				if(!$this->Horario->borrarHora($codigo)){
					$this->Session->setFlash(__('La hora se ha eliminado correctamente.'),'mensaje-exito');
					$fecha1 = $this->Utilidades->encriptar($fecha);
					$this->redirect(array('action'=>'orientador',$cod_encuestador, $fecha1));
				}else{
					$this->Session->setFlash(__('Error al eliminar la hora.'),'mensaje-exito');
					$this->redirect(array('action'=>'orientador',$cod_encuestador));
				}
				
			}
			exit;
		}
	
		function finalizarPostulacion($postulacion_codigo=null){			
			if(empty($postulacion_codigo)){
				$this->Session->setFlash(__('Código de postulación vacío.'),'mensaje-error');
				$this->redirect(array('action'=>'listadopostulaciones'));
			}
			$postulacion = $this->Postulacion->find('first',array('conditions'=>array('Postulacion.codigo'=>$postulacion_codigo)));
			$id_correlativo = $postulacion['Postulacion']['id_correlativo'];
			if(empty($postulacion)){
				$this->Session->setFlash(__('El código de la postulación no existe en nuestra BD.'),'mensaje-error');
				$this->redirect(array('action'=>'listadopostulaciones'));
			}
			$postulante = $this->Postulante->find('first',array('conditions'=>array('Postulante.codigo' => $postulacion['Postulacion']['postulante_codigo'])));
			$administrativo = $this->Session->read('UserLogued');
			$codigo_estado = 'px';
			$codigo_estado .= uniqid();
			$cambio_estado = array(
				'codigo'=>$codigo_estado,
				'postulacion_codigo'=>$postulacion_codigo,
				'estado_codigo'=>'9',
				'fecha_cambio'=> date('d-m-Y H:i:s'),
				'administrativo_codigo'=>$administrativo['Administrativo']['codigo'],
				'postulante_codigo'=>$postulacion['Postulacion']['postulante_codigo']
			);
			$this->EstadoPostulacion->create();
			$this->EstadoPostulacion->save($cambio_estado);
			$this->Correo->enviarEmail($postulante, 4, null, null, null, $id_correlativo);	
			$this->borrarEmail($postulacion_codigo);			
			$this->Session->setFlash(__('La postulación ha terminado su proceso a través del portal.'),'mensaje-exito');
			$this->redirect(array('action'=>'listadopostulaciones'));	
		}
		

		function desactivarPostulacion($postulacion_codigo=null){
			if(empty($postulacion_codigo)){
				$this->Session->setFlash(__('Código de postulación vacío.'),'mensaje-error');
				$this->redirect(array('action'=>'postulaciones'));
			}
			$postulacion = $this->Postulacion->find('first',array('conditions'=>array('codigo'=>$postulacion_codigo)));
			if(empty($postulacion)){
				$this->Session->setFlash(__('El código de la postulación no existe en nuestra BD.'),'mensaje-error');
				$this->redirect(array('action'=>'postulaciones'));
			}
			$postulante_codigo = $postulacion['Postulacion']['postulante_codigo'];
			$postulacion = array(
				'activo' => 0,
				'motivo_desactivacion'=>"'".$this->data['Postulacion']['motivo_desactivacion']."'",
			);
			$this->Postulacion->updateAll($postulacion,array('codigo'=>$postulacion_codigo));
			$postulante = $this->Postulante->find('first',array('conditions'=>array('codigo'=>$postulante_codigo)));
			$postulante = array(
				'activo' => 0,
			);
			$this->Postulante->updateAll($postulante,array('codigo'=>$postulante_codigo));
			$this->borrarEmail($postulacion_codigo);
			$this->Session->setFlash(__('La postulación ha sido desactivada.'),'mensaje-exito');
			$this->redirect(array('action'=>'postulaciones'));	
		} 

		function updateData($cod_postulante = null){
			if(!empty($this->data)){				
				$extranjero = $this->data['extranjero'];
				#CODIGO ACTUALIZAR DATOS DEL POSTULANTE;
				$data_postulante = $this->data;				
				//prx($data_postulante);
				$rut = str_replace('.', '', $this->data['Postulante']['rut']);
				$rut = str_replace('-', '', $rut);
				$codigo = $data_postulante['Postulante']['codigo'];
				$data_postulante['Postulante']['rut']=$rut;
				$postulante_bd = $this->Postulante->find('first',array('conditions'=>array('Postulante.RUT'=>$rut)));
				if(!empty($postulante_bd)){
					$postulante_cod=$this->Postulante->find('first',array('conditions'=>array('codigo'=>$cod_postulante)));
					if($postulante_cod['Postulante']['codigo'] != $postulante_bd['Postulante']['codigo']){
						$this->Session->setFlash(__('Ya existe el postulante asociado a este RUT.'),'mensaje-error');
						$this->redirect(array('action'=>'updateData',$codigo));
					}	
				}
				$email = trim($this->data['Postulante']['email']);
				$postulante_bd = $this->Postulante->find('first',array('conditions'=>array('Postulante.EMAIL'=>$email)));
				if(!empty($postulante_bd)){
					$postulante_cod=$this->Postulante->find('first',array('conditions'=>array('codigo'=>$cod_postulante)));
					if($postulante_cod['Postulante']['codigo'] != $postulante_bd['Postulante']['codigo']){
						$this->Session->setFlash(__('Ya existe el postulante asociado a este e-mail.'),'mensaje-error');
						$this->redirect(array('action'=>'updateData',$codigo));
					}
				}
				$fono = $data_postulante['Postulante']['telefonomovil'];
				$apellidom = $data_postulante['Postulante']['apellidom'];
				$apellidop = $data_postulante['Postulante']['apellidop'];
				$nombre = mb_strtoupper($data_postulante['Postulante']['nombre']);
				$rut = $data_postulante['Postulante']['rut'];
				$email = $data_postulante['Postulante']['email'];
				$genero = $data_postulante['Postulante']['genero'];
				$fecha_nacimiento = $data_postulante['Postulante']['anho'].'-'.$data_postulante['Postulante']['mes'].'-'.$data_postulante['Postulante']['dia'];
				$hora = ((int)date('H')>=12)? 'PM': 'AM ';
				$data_postulante = array(
					'nombre'=>"'$nombre'",
					'rut'=>"'$rut'",
					'telefonomovil'=>$fono,
					'email'=>"'$email'",
					'genero'=>"'$genero'",
					'apellidom'=>"'$apellidom'",
					'apellidop'=>"'$apellidop'",
					'extranjero'=> $extranjero,
					'fecha_nacimiento' =>"TO_DATE('$fecha_nacimiento', 'YYYY-MM-DD HH:MI:SS $hora')"
				);				
				$this->Postulante->updateAll($data_postulante,array('codigo'=>$codigo));
				$this->Session->setFlash(__('Datos Actualizados.'),'mensaje-exito');
				$this->redirect(array('action'=>'postulantes'));
			}
			if(empty($cod_postulante)){
				$this->Session->setFlash(__('Código de postulante vacío.'),'mensaje-error');
				$this->redirect(array('action'=>'postulantes'));
			}
			$postulante = $this->Postulante->find('first',array('conditions'=>array('codigo'=>$cod_postulante)));
			if(empty($postulante)){
				$this->Session->setFlash(__('El código del postulante no existe en nuestra BD.'),'mensaje-error');
				$this->redirect(array('action'=>'postulantes'));
			}
			$user = $this->Postulante->find('first',array('conditions'=>array('Postulante.codigo'=>$cod_postulante)));
			$this->set('user',$user);
		}
		
		
		/* 
		FUNCIÓN BORRAR POSTULACIÓN: ESTA FUNCIÓN BORRARÁ AL POSTULANTE Y A SU VEZ LLAMARÁ AL MÉTODO DE BORRAR
		LA POSTULACIÓN PERO QUE A SU VEZ VA A LLAMAR A FUNCIONES ANEXAS PARA REAPROVECHAR EL CÓDIGO 
		*/
		function borrarPostulante(){
			$postulante = $this->data;
			$codigo_postulante = implode($postulante);
			$codigo_postulante = array('postulante.codigo' => $codigo_postulante);
			if(empty($postulante)){
					$this->Session->setFlash(__('Código de postulante vacío.'),'mensaje-error');
					$this->redirect(array('action'=>'postulaciones'));
				}
			$postulante = $this->Postulante->find('first',array('conditions'=>array('codigo'=>$postulante['postulante'])));
			
			if(empty($postulante)){
				$this->Session->setFlash(__('El código del postulante ya no existe en la base de datos.'),'mensaje-error');
				$this->redirect(array('action'=>'postulaciones'));
			}
			else {
				$postulaciones = $this->Postulacion->find('all', array('conditions'=>array('postulante_codigo'=>$postulante['Postulante']['codigo'])));	
				$codigo_postulacion = $postulaciones[0]['Postulacion']['codigo'];	
				
				if ($this->Postulante->deleteAll($codigo_postulante,false)) {
						$this->borrarPostulacion($postulaciones[0]['Postulacion']['codigo']);
						$this->Session->setFlash(__('Se ha borrado correctamente al postulante.'),'mensaje-exito');
						$this->redirect(array('action'=>'postulaciones'));						
				}
				else {
					$this->Session->setFlash(__('Hubo un problema al borrar al postulante.'),'mensaje-error');
					$this->redirect(array('action'=>'postulaciones'));
				}				
			} 
		}
		
		
		/* FUNCIÓN QUE BORRARÍA LA POSTULACIÓN PASADO UN CÓDIGO DE POSTULANTE POR PARÁMETRO */
		function borrarPostulacion($codigo_postulacion = null) {			
			if ($codigo_postulacion == null && (isset($this->request->data))){				
				$codigo_postulacion = $this->request->data['postulacion'];
			}
			if (empty($this->data) && (empty($codigo_postulacion))){ 
				$this->Session->setFlash(__('Error crítico al intentar borrar la postulación. No se ha encontrado el código del postulante.'), 'mensaje-error');
				$this->redirect(array('action'=>'postulaciones'));
			}
			
			//Si el código llega vacío supone que los datos vienen vía formulario, sino es que vienen llamados por la función borrarPostulante();
			if (empty($codigo_postulacion)){
				$codigo_postulacion = $this->data;
				$formulario = true;					
			}			
			$this->borrarPostulacionLaboral($codigo_postulacion);
			$this->borrarPostulacionEducacion($codigo_postulacion);
			$this->borrarPostulacionCapacitacion($codigo_postulacion);
			$this->borrarPostulacionArchivos($codigo_postulacion);
			$this->borrarPostulacionCapacitacion($codigo_postulacion);
			$this->borrarEstadosPostulacion($codigo_postulacion);
			$this->borrarPostulacionEvaluacion($codigo_postulacion);
			$this->borrarCompetenciasPostulacion($codigo_postulacion);
			$this->borrarArchivosEvidencias($codigo_postulacion);
			$this->borrarEvidenciasPrevias($codigo_postulacion);
			$this->borrarEmail($codigo_postulacion);
			$condition = array('Postulacion.codigo' => $codigo_postulacion);
				if ($this->Postulacion->deleteAll($condition,false)) {
					$this->Session->setFlash(__('Se borró correctamente la postulación.'),'mensaje-exito');	
					$this->redirect(array('action'=>'postulantes'));
				}
				else {
					$this->Session->setFlash(__('No se pudo borrar la postulación. Contacte con un administrador del sistema'),'mensaje-error');
					$this->redirect(array('action'=>'postulantes'));
				}
		}
		
		
		/* ESTA FUNCIÓN BORRARÁ EL REGISTRO DE POSTULACIÓN LABORAL ASOCIADO A LA POSTULACIÓN */
		private function borrarPostulacionLaboral($codigo_postulacion = null) {
			$condition = array('LaboralPostulacion.postulacion_codigo' => $codigo_postulacion);
			if($this->LaboralPostulacion->deleteAll($condition,false)){return true;}
		 }
		 
		 /* ESTA FUNCIÓN BORRARÁ EL REGISTRO DE EDUCACIÓN DE LA POSTULACIÓN */
		 private function borrarPostulacionEducacion($codigo_postulacion = null) {
			$condition = array('EducacionPostulacion.postulacion_codigo' => $codigo_postulacion);
			if($this->EducacionPostulacion->deleteAll($condition,false)){return true;}			
		 }
		 
		 
		 /* ESTA FUNCIÓN BORRARÁ EL REGISTRO DE CAPACITACIÓN DE LA POSTULACIÓN */
		 private function borrarPostulacionCapacitacion($codigo_postulacion = null) {
			$condition = array('CapacitacionPostulacion.postulacion_codigo' => $codigo_postulacion);
			if($this->CapacitacionPostulacion->deleteAll($condition,false)){return true;}			
		 }
		 
		 /* ESTA FUNCIÓN BORRARÁ EL REGISTRO DE AUTOEVALUACIONES DE LA POSTULACIÓN */
		 private function borrarPostulacionEvaluacion($codigo_postulacion = null) {	
			$condition = array('AutoEvaluacion.postulacion_codigo' => $codigo_postulacion);			
			if($this->AutoEvaluacion->deleteAll($condition,false)) {return true;}	
		 }
		 
		 
		 /* ESTA FUNCIÓN BORRARÁ EL REGISTRO DE AUTOEVALUACIONES DE LA POSTULACIÓN */
		 private function borrarEstadosPostulacion($codigo_postulacion = null) {
			$condition = array('EstadoPostulacion.postulacion_codigo' => $codigo_postulacion);
			if($this->EstadoPostulacion->deleteAll($condition,false)){return true;}	
		 }
		 
		 
		 /* ESTA FUNCIÓN BORRARÁ EL REGISTRO DE AUTOEVALUACIONES DE LA POSTULACIÓN */
		 private function borrarEvidenciasPrevias($codigo_postulacion = null) {
			$condition = array('EvidenciasPrevias.postulacion_codigo' => $codigo_postulacion);
		
			if($this->EvidenciasPrevias->deleteAll($condition,false)){
				$this->borrarArchivosEvidencias($codigo_postulacion);
				return true;
			}	
		 }
		 
		 
		 /* ESTA FUNCIÓN BORRARÁ LAS COMPETENCIAS ELEGIDAS POR EL POSTULANTE  */
		 private function borrarCompetenciasPostulacion($codigo_postulacion = null) {
			$condition = array('CompetenciaPostulacion.postulacion_codigo' => $codigo_postulacion);			
			if($this->CompetenciaPostulacion->deleteAll($condition,false)){				
				return true;
			}	
		 }
		 
		 		
		 private function desvalidarEvidencias($codigo_postulacion = null) {
			$condition = array('EvidenciasPrevias.postulacion_codigo' => $codigo_postulacion);
			$evidencias = $this->EvidenciasPrevias->find('all', array('conditions' => array('EvidenciasPrevias.postulacion_codigo' => $codigo_postulacion, 'EvidenciasPrevias.validar' => 1 )));
			
			//echo var_dump($evidencias);
			foreach ($evidencias as $evidencia){
				$this->EvidenciasPrevias->id = $evidencia['EvidenciasPrevias']['id'];
				$this->EvidenciasPrevias->saveField('validar', '0');
				$this->EvidenciasPrevias->saveField('fecha_validacion', null);
				//echo var_dump($evidencia);
			}
			
			
		 }
		 
		 
		private function invalidarEvidenciasFinales($codigo_postulacion = null) {
			$condition = array('EvidenciasPrevias.postulacion_codigo' => $codigo_postulacion);
			$evidencias = $this->EvidenciasPrevias->find('all', array('conditions' => array('EvidenciasPrevias.postulacion_codigo' => $codigo_postulacion, 'EvidenciasPrevias.validar' => 1, 'EvidenciasPrevias.Preliminar' => 0 )));
			//echo var_dump($evidencias);
			foreach ($evidencias as $evidencia){
				$this->EvidenciasPrevias->id = $evidencia['EvidenciasPrevias']['id'];
				$this->EvidenciasPrevias->saveField('validar', '0');
				$this->EvidenciasPrevias->saveField('fecha_validacion', null);
				
			}
			return true;
		 }
		 
		 
		 private function borrarEmail($codigo_postulacion = null) {
			$this->LoadModel('Correo');
			$condition = array('Correo.codigo_postulacion' => $codigo_postulacion);
			if ($this->Correo->deleteAll($condition,false)){
				//Esto puede ocurrir, cuando se desactiva la etapa, cuando se borra la postulación, o cuando se rechaza o se finaliza
				CakeLog::write('alert',	'Correos borrados de la postulación #'.$codigo_postulacion.'', 'email');
				return true;
			}
			else {
				return false;
			}
		 }
		 
		 
		 
		 /* ESTA FUNCIÓN BORRARÁ EL REGISTRO DE ARCHIVOS DE LA POSTULACIÓN QUE LLAMARÁ A SU VEZ A UNA FUNCIÓN QUE BORRA LOS ARCHIVOS FÍSICOS DE LA MISMA */
		private function borrarArchivosEvidencias($codigo_postulacion = null) {
			$archivos = $this->ArchivoEvidencia->Find('all',array('conditions' => array('postulacion_codigo' => $codigo_postulacion)));
						
			if (empty($archivos)){
				$this->Session->setFlash(__('No existen archivos asociados a esta postulación.'),'mensaje-error');	
			}			
			else {
				foreach ($archivos as $archivo) {
					
					$archivo = $archivo['ArchivoEvidencia'];
					if ($this->ArchivoEvidencia->deleteAll(array('postulacion_codigo' => $codigo_postulacion))){								
						unlink($archivo['path_archivo'].$archivo['codigo'].'.'.$archivo['extencion_archivo']);
						$this->Session->setFlash(__('Se borró el archivo correctamente.'),'mensaje-exito');		
					}					
					else {
						$this->Session->setFlash(__('Error a la hora de borrar el archivo.'),'mensaje-error');
						return 0;
					}			
				}		
				$condition = array('ArchivoEvidencia.postulacion_codigo' => $codigo_postulacion);
				$this->ArchivoEvidencia->deleteAll($condition,false);	
			return 1;
			}
		}		 
		 
		 		 		 
		 
		/* ESTA FUNCIÓN BORRARÁ EL REGISTRO DE ARCHIVOS DE LA POSTULACIÓN QUE LLAMARÁ A SU VEZ A UNA FUNCIÓN QUE BORRA LOS ARCHIVOS FÍSICOS DE LA MISMA */
		private function borrarPostulacionArchivos($codigo_postulacion = null) {
			$archivos = $this->Cargas->Find('all',array('conditions' => array('postulacion_codigo' => $codigo_postulacion)));
						
			if (empty($archivos)){
				$this->Session->setFlash(__('No existen archivos asociados a esta postulación.'),'mensaje-error');	
			}			
			else {
				foreach ($archivos as $archivo) {
					$archivo = $archivo['Cargas'];
					if ($this->Cargas->deleteAll(array('postulacion_codigo' => $codigo_postulacion))){								
						unlink($archivo['path_archivo'].$archivo['codigo'].'.'.$archivo['extension_archivo']);
						$this->Session->setFlash(__('Se borró el archivo correctamente.'),'mensaje-exito');		
					}					
					else {
						$this->Session->setFlash(__('Error a la hora de borrar el archivo.'),'mensaje-error');
						return 0;
					}			
				}		
				$condition = array('Cargas.postulacion_codigo' => $codigo_postulacion);
				$this->Cargas->deleteAll($condition,false);	
			return 1;
			}
		}
		
		
		/* ESTA FUNCIÓN BORRARÁ LA ENTREVISTA AGENDADA POR EL POSTULANTE */
	private function borrarEntrevistaPostulacion($codigo_postulacion = null) {			
			$horarioentrevista = $this->Entrevista->find('first', array('conditions' => array('Entrevista.postulacion_codigo' => $codigo_postulacion)));
			$codigo_entrevista = $horarioentrevista['Entrevista']['horario_codigo'];
			$this->Horario->id = $codigo_entrevista;
			//echo var_dump($codigo_entrevista);
			//echo var_dump($horarioentrevista);
			$condition = array('Entrevistas.postulacion_codigo' => $codigo_postulacion);
			if($this->Entrevista->deleteAll($condition,false) && ($this->Horario->query("UPDATE RAP_HORARIOS SET ESTADO ='DISPONIBLE' WHERE CODIGO='".$codigo_entrevista."'"))){
				return true;
			}	
		}
	
	
	/* Este método buscará la entrevista agendada por el 
	postulante y cambiará su estado a REALIZADO */
	
	function realizarEntrevista(){
		$codigo_postulacion = $this->data['postulante'];		
		if ($codigo_postulacion == null){
				$this->Session->setFlash(__('Se ha producido un error al marcar como realizada la entrevista. '),'mensaje-error');
				$this->redirect(array('action'=>'postulaciones'));	
		}		
		$entrevista = $this->Entrevista->find('first', array('conditions' => array('Entrevista.postulacion_codigo' => $codigo_postulacion)));		
		$horario = $this->Horario->find('first', array('conditions' => array('Horario.codigo' => $entrevista['Entrevista']['horario_codigo'])));
		$this->Entrevista->query("UPDATE RAP_ENTREVISTAS SET ESTADO = 'REALIZADO' WHERE CODIGO = '".$entrevista['Entrevista']['codigo']."'");
		$this->Horario->query("UPDATE RAP_HORARIOS SET ESTADO = 'REALIZADO' WHERE CODIGO = '".$horario['Horario']['codigo']."'");
		$entrevista = $this->Entrevista->find('first', array('conditions' => array('Entrevista.postulacion_codigo' => $codigo_postulacion)));
		if ($entrevista['Entrevista']['estado'] == 'REALIZADO'){
				$this->actualizaFecha($codigo_postulacion);
				$this->Alerta->crear_alerta(17,null,$codigo_postulacion);
				$this->Session->setFlash(__('Se ha marcado la entrevista como REALIZADA. '),'mensaje-exito');				
				$this->redirect(array('action'=>'postulaciones', $codigo_postulacion));		
		}
		else{
				$this->Session->setFlash(__('Hubo un error al cambiar el estado de la entrevista.'),'mensaje-error');
				$this->redirect(array('action'=>'postulaciones', $codigo_postulacion));				
		}		
	}
	
		
	/* Este método desactivará la última etapa del postulante, 
	borrando todos los datos que en ella se contenga. */
	
	function desactivaretapa($codigo_postulacion){
		if (empty($codigo_postulacion)) {
				$this->Session->setFlash(__('No se ha indicado postulación.'),'mensaje-error');
				$this->redirect(array('action' => 'index'));		
		}
		else {
				//Borramos todos los emails de aviso
				$this->borrarEmail($codigo_postulacion);
				$this->LoadModel('EstadoPostulacion');								
				$etapas = $this->EstadoPostulacion->find('first',array(
												'conditions' => array(
																'EstadoPostulacion.postulacion_codigo' => $codigo_postulacion
																),
												'order' => array('EstadoPostulacion.estado_codigo' => 'desc')));
				
				//echo var_dump($etapas);
				$etapa = $etapas['EstadoPostulacion']['estado_codigo'];
			
				$this->Alerta->crear_alerta(15,null,$codigo_postulacion);
				
				switch ($etapa) {
					case 1:						
						$condition = array('Postulacion.codigo' => $codigo_postulacion);
						$condition2 = array('Estadopostulacion.postulacion_codigo' => $codigo_postulacion);
						if (($this->EstadoPostulacion->deleteAll($condition2,false)) && ($this->Postulacion->deleteAll($condition,false))){
							$this->Alerta->crear_alerta(0,null,$codigo_postulacion); 
							$this->Session->setFlash(__('Se ha borrado correctamente la &uacute;ltima etapa.(1)'),'mensaje-exito');
							$this->redirect(array('action' => 'postulaciones'));	
						}
						else {
							$this->Session->setFlash(__('Error borrando la &uacute;ltima etapa.'),'mensaje-error');
							$this->redirect(array('action' => 'postulaciones',$codigo_postulacion));						
						}
						break;					
					case 2: //DOCUMENTACIÓN ENVIADA EN REVISIÓN
						$condition2 = array('Estadopostulacion.postulacion_codigo' => $codigo_postulacion, 'Estadopostulacion.estado_codigo' => '2');
						if (($this->EstadoPostulacion->deleteAll($condition2,false))){
							$this->borrarPostulacionArchivos($codigo_postulacion); 
							$this->Alerta->crear_alerta(1,null,$codigo_postulacion);
							$this->Alerta->crear_alerta(15,null,$codigo_postulacion);							
							$this->Session->setFlash(__('Se ha borrado correctamente la documentación del postulante'),'mensaje-exito');
							$this->redirect(array('action' => 'postulaciones',$codigo_postulacion));	
						}
						else {
							$this->Session->setFlash(__('Error borrando la &uacute;ltima etapa.'),'mensaje-error');
							$this->redirect(array('action' => 'postulaciones',$codigo_postulacion));						
						}
						break;	
					case 3: //EL ADMINISTRADOR YA HA ACEPTADO LA DOCUMENTACIÓN - SE INVALIDA LA DOCUMENTACIÓN
						$condition2 = array('Estadopostulacion.postulacion_codigo' => $codigo_postulacion, 'Estadopostulacion.estado_codigo' => '3');
						if ( $this->EstadoPostulacion->deleteAll($condition2,false) ){
							$this->Alerta->crear_alerta(2,null,$codigo_postulacion); 
							$this->Alerta->crear_alerta(15,null,$codigo_postulacion);
							$this->Session->setFlash(__('Se ha INVALIDADO la documentación del postulante.'),'mensaje-exito');
							$this->redirect(array('action' => 'postulaciones',$codigo_postulacion));	
						}
						else {
							$this->Session->setFlash(__('Error borrando la &uacute;ltima etapa.'),'mensaje-error');
							$this->redirect(array('action' => 'postulaciones',$codigo_postulacion));						
						}
						break;	
					case 4: //EL POSTULANTE HA RELLENADO EL CVRAP PERO NO LA AUTOEVALUACIÓN	
						$condition = array('Estadopostulacion.postulacion_codigo' => $codigo_postulacion, 'Estadopostulacion.estado_codigo' => '4');						
						$exitos = 0;
						if ($this->borrarPostulacionLaboral($codigo_postulacion)) { $exitos = $exitos + 1 ; }
						if ($this->borrarPostulacionCapacitacion($codigo_postulacion)) { $exitos = $exitos +1; }						
						if ($this->borrarPostulacionEducacion($codigo_postulacion)) { $exitos = $exitos + 1; }
						if ($this->borrarCompetenciasPostulacion($codigo_postulacion)) { $exitos = $exitos + 1; }							
						if ($this->EstadoPostulacion->deleteAll($condition,false))  { $exitos = $exitos + 1; }
						$this->Postulacion->id = $codigo_postulacion;
						if ($this->Postulacion->saveField('observaciones_cvrap', null)) { $exitos = $exitos + 1;}
						//echo var_dump($exitos);
						if ($exitos == 6) {
							$this->Alerta->crear_alerta(3,null,$codigo_postulacion);
							$this->Alerta->crear_alerta(15,null,$codigo_postulacion);
							$this->Session->setFlash(__('Se ha borrado correctamente la &uacute;ltima etapa.(4)'),'mensaje-exito');
							$this->redirect(array('action' => 'postulaciones',$codigo_postulacion));	
						}
						if ($exitos == 0) {
							$this->Session->setFlash(__('Error fatal. No se pudo borrar la etapa (4). Contacte con un administrador del sistema.'),'mensaje-error');
							$this->redirect(array('action' => 'postulaciones',$codigo_postulacion));						
						}
						if (($exitos < 6) && ($exitos > 0)){
							$this->Session->setFlash(__('Error. Algunos datos de la etapa (4) no pudieron ser borrados.'),'mensaje-error');
							$this->redirect(array('action' => 'postulaciones',$codigo_postulacion));						
						}
						break;
						
					case 5: //EL POSTULANTE HA ENVIADO LA AUTOEVALUACIÓN PERO AÚN NO HA SIDO REVISADA, POR TANTO SE DESACTIVA ESTE PASO DE LA AUTOEVALUACIÓN						
						$this->loadModel('AutoEvaluacion');
						$condition = array('Estadopostulacion.postulacion_codigo' => $codigo_postulacion, 'Estadopostulacion.estado_codigo' => '5');												
						$errores = 0; 
						//echo var_dump($errores);//Comprobamos todos los exitos que se obtienen borrando cada cláusula.
						if (!$this->EstadoPostulacion->deleteAll($condition,false)) { $errores ++; }						
						if (!$this->borrarPostulacionEvaluacion($codigo_postulacion))  { $errores++; }					
						if ($errores == 0){
							$this->Alerta->crear_alerta(4,null,$codigo_postulacion);
							$this->Alerta->crear_alerta(15,null,$codigo_postulacion);
							$this->Session->setFlash(__('Se ha borrado correctamente la &uacute;ltima etapa.(5)'),'mensaje-exito');
							$this->redirect(array('action' => 'postulaciones',$codigo_postulacion));	
						}
						else {
							if ($errores == 2) {
							$this->Session->setFlash(__('Error fatal. No se pudo borrar ninguna etapa del proceso.'),'mensaje-error');
							$this->redirect(array('action' => 'postulaciones',$codigo_postulacion));						
							}
							else {
								$this->Session->setFlash(__('Error borrando la &uacute;ltima etapa. Alg&uacute;n paso no se ha borrado correctamente. Contacte con el Administrador del sistema'),'mensaje-error');
								$this->redirect(array('action' => 'postulaciones',$codigo_postulacion));
							}
						}
						break;
										
						
					case 6: //AL POSTULANTE SE LE HA ACEPTADO EL CVRAP Y LA AUTOEVALUACIÓN - COMPROBAMOS SI YA HA SUBIDO EVIDENCIAS PREVIAS O NO LO HA HECHO	
						$this->loadModel('EvidenciasPrevias');
						
						$evidenciasprevias = $this->EvidenciasPrevias->find('first', array('conditions' => array('EvidenciasPrevias.postulacion_codigo' => $codigo_postulacion, 'EvidenciasPrevias.validar' => 1 )));
						//echo var_dump($evidenciasprevias);						
						if (!empty($evidenciasprevias)) { //Existen evidencias previas validadas
							echo 'evidenciasprevias';
								$this->desvalidarEvidencias($codigo_postulacion);
								$this->Alerta->crear_alerta(6,null,$codigo_postulacion);
								$this->Alerta->crear_alerta(15,null,$codigo_postulacion);
								$this->Session->setFlash(__('Las evidencias previas han sido invalidadas'),'mensaje-exito');
								$this->redirect(array('action' => 'postulaciones',$codigo_postulacion));
						}						
						else { //NO EXISTEN EVIDENCIAS PREVIAS VALIDADAS EL ESTADO ENTONCES PASA DE ESTAR DEL 6 AL 5. 
								$condition = array('Estadopostulacion.postulacion_codigo' => $codigo_postulacion, 'Estadopostulacion.estado_codigo' => '6');
								if ($this->EstadoPostulacion->deleteAll($condition,false)) { 
									$this->Alerta->crear_alerta(5,null,$codigo_postulacion);
									$this->Alerta->crear_alerta(15,null,$codigo_postulacion);
									$this->Session->setFlash(__('El CV RAP y la Autoevaluación ahora están pendientes de revisión por un administrador (5-6)'),'mensaje-exito');
									$this->redirect(array('action' => 'postulaciones',$codigo_postulacion));
								}
								else {
									$this->Session->setFlash(__('Hubo un problema a la hora de borrar la etapa'),'mensaje-error');								
								}								
							echo 'No hay evidencias o no han sido validadas';						
						}						
						break;
					
					case 8: //EL POSTULANTE HA AGENDADO LA ENTREVISTA Y TIENE EVIDENCIAS FINALES VALIDADAS
								$this->loadModel('EvidenciasPrevias');								
								$evidenciasfinales = $this->EvidenciasPrevias->find('first', array('conditions' => array('EvidenciasPrevias.postulacion_codigo' => $codigo_postulacion, 'EvidenciasPrevias.validar' => 1, 'EvidenciasPrevias.preliminar' => 0 )));
								if (!empty($evidenciasfinales)) {  //Si hay evidencias finales, las invalidamos	
																						
									if ($this->invalidarEvidenciasFinales($codigo_postulacion)){											
											$this->Alerta->crear_alerta(15,null,$codigo_postulacion);												
											$this->Session->setFlash(__('Se han invalidado las Evidencias Finales'),'mensaje-exito');
											$this->redirect(array('controller' => 'administrativos','action' => 'postulaciones',$codigo_postulacion));										
									}
									else {
											$this->Session->setFlash(__('Error Invalidando las evidencias Finales'),'mensaje-error');
											$this->redirect(array('controller' => 'administrativos','action' => 'postulaciones',$codigo_postulacion));
									}
								}								
								else { //Si no hay evidencias finales validadas, anulamos la entrevista
									$condition = array('Estadopostulacion.postulacion_codigo' => $codigo_postulacion, 'Estadopostulacion.estado_codigo' => '8');
									$exitos = 0;
									$this->borrarEntrevistaPostulacion($codigo_postulacion);
									if ($this->EstadoPostulacion->deleteAll($condition,false)) { 
										$this->Alerta->crear_alerta(10,null,$codigo_postulacion);
										$this->Alerta->crear_alerta(15,null,$codigo_postulacion);	
										$this->Session->setFlash(__('Se ha anulado la entrevista del postulante (8)'),'mensaje-exito');
										$this->redirect(array('action' => 'postulaciones',$codigo_postulacion));
									}
									else {
										$this->Session->setFlash(__('Hubo un error al borrar la entrevista'),'mensaje-error');
										$this->redirect(array('action' => 'postulaciones',$codigo_postulacion));							
									}								
								}
					break;
				}
		}	
	}

	
	//FASE NUEVA ADMISIONES HORIZONTALES Y VERTICALES
	
	/* ESTE METODO LISTARÁ LAS POSTULACIONES QUE SEAN DE ADMISIÓN HORIZONAL */
	
	public function listadoPostulacionesNuevas($resueltos = null){
            
                    $usuario_logueado     = $this->Session->read('UserLogued');
                    $admin_carrera_codigo = $usuario_logueado['Administrativo']['carrera_codigo'];
                    $admin_tipo_usuario   = $usuario_logueado['Administrativo']['tipo']; //No se utiliza ya que se esta validando en controlador...
					
					//echo "Carrera código administrativo: ".$admin_carrera_codigo; exit;
					$escuela_admin = $usuario_logueado['Administrativo']['escuela_id'];
                    
                    $this->set('escuela_admin',$escuela_admin); 
					
                    $usuario 	   = $this->Session->read('UserLogued');	
                    
                    if (($usuario['Administrativo']['tipo'] == 'RAP')){
                        $this->Session->setFlash(__('No tiene permiso para acceder a esta sección. Solamente puede revisar las postulaciones RAP.'),'mensaje-error');
                        $this->redirect(array('controller'=> 'login', 'action' => 'home'));		

                    }
                    if (($usuario['Administrativo']['tipo'] == 'AH')){
						$escuela_administrativo = $usuario['Administrativo']['escuela_id'];
						//echo var_dump($escuela_administrativo);
						//$carreras = $this->EscuelaCarrera->find('all', array('conditions' => array('escuela_codigo' => $escuela_administrativo))); ESTO SE COMENTÓ PORQUE CADA ADMVO TENDRA SUS CARRERAS ASOCIADAAS
												
						$carreras = $this->AdministrativoCarrera->find('list', array('conditions' => array('AdministrativoCarrera.administrativo_id' => $usuario['Administrativo']['codigo']), 'fields' => array('AdministrativoCarrera.carrera_id', 'AdministrativoCarrera.administrativo_id',) ));						
						$carreras_administrativo = array(); //Aquí se guardarán todos los códigos de las carreras que se pueden visualizar. 
						$carreras2 = '(';	
						$numero = count($carreras);
						$contador = '';
						foreach ($carreras as $key => $carrera){	
							$contador++;
							$carreras2 .= "".$key;
							if ($contador < $numero){
								$carreras2 .= ",";
							}
						}
						$carreras2 .= ')';						
                        $condicion = array(  );
                        $condiciones_or[] = array('Postulacion.tipo' => 'AH');
                        $condicion = array('OR' => ($condiciones_or),'AND' => array('Postulacion.habilitado' => null, 'Postulacion.carrera_codigo IN '.$carreras2.''));						
                        $condicion2 = array('OR' => ($condiciones_or),'AND' => array('Postulacion.habilitado <>' => null, 'Postulacion.carrera_codigo IN '.$carreras2.''));	
                        $tipo = 'AH';
                        $this->set('tipo',$tipo);	

                    }
                    if (($usuario['Administrativo']['tipo'] == 'AV')){
                        $condicion = array(  );						
                        $condiciones_or[] = array('Postulacion.tipo' => 'AV');
                        $condicion = array('OR' => ($condiciones_or),'AND' => array('Postulacion.habilitado' => null));						
                        $condicion2 = array('OR' => ($condiciones_or),'AND' => array('Postulacion.habilitado <>' => null));	
                        $tipo = 'AV';
                        $this->set('tipo',$tipo);	

                    }
                    if (($usuario['Administrativo']['perfil'] == 0) || ($usuario['Administrativo']['perfil'] == 4)){						
                        $condicion = array(  );
                        $condiciones_or[] = array('Postulacion.tipo' => 'AH');
                        $condiciones_or[] = array('Postulacion.tipo' => 'AV');
                        $condicion = array('OR' => ($condiciones_or),'AND' => array('Postulacion.habilitado' => null));						
                        $condicion2 = array('OR' => ($condiciones_or),'AND' => array('Postulacion.habilitado <>' => null));	
                    }
					
					$this->set('tipo_admin',$usuario['Administrativo']['tipo']);

                    if($resueltos == 'resueltos'){ // En este caso se muestran solamente las postulaciones que ya se han resuelto, es decir que se han habilitado o inhabilitado
                        $this->Postulacion->virtualFields = array(						
							'nombre'		 => 'Postulante.nombre',	
							'apellidop'		 => 'Postulante.apellidop',	
							'apellidom'		 => 'Postulante.apellidom',	
							'carrera' 		 => 'Carrera.nombre',	
							'fecha'   	     => 'Postulacion.modified',
							'correlativo'    => 'Postulacion.id_correlativo',
							'fecha_creacion' => 'Postulacion.created',										
                        ); 						
                        $this->paginate = array('limit' => 20,
                            'joins' => array(
                                array(
                                    'table' => 'RAP_ESCUELAS_CARRERAS',
                                    'alias' => 'EscuelaCarrera',  //Carrera
                                    'type' => 'INNER',
                                    'conditions' => array(
                                        'Postulacion.carrera_codigo = EscuelaCarrera.carrera_codigo'
                                    )
                                )
                            ),
                            'fields' => array(
                                    'Postulacion.postulante_codigo', 
                                    'Postulacion.revision', 
                                    'Postulacion.habilitado', 
                                    'Postulacion.codigo', 
                                    'Postulacion.carrera_codigo', 
                                    'Postulacion.modified', 
									'Postulacion.created', 
									'Postulacion.firma', 
									'Postulacion.csa', 
									'Postulacion.id_correlativo',
                                    'Postulacion.tipo', 
                                    'Postulante.codigo',
                                    'Postulante.nombre',
                                    'Postulante.apellidop',
                                    'Postulante.apellidom',
                                    'Carrera.codigo',
                                    'Carrera.nombre',

                                    'EscuelaCarrera.escuela_codigo'
                            ),	
							'order' => 'Postulacion.modified DESC',							
                            'conditions' => $condicion2,
                        ); 

                        $this->set('postulaciones',$this->paginate('Postulacion'));	
                    }
                    
                    if ($resueltos == null){
					
                        $this->Postulacion->virtualFields = array(						
                                'carrera' 		 => 'Carrera.nombre',	
                                'correlativo'    => 'Postulacion.id_correlativo',	
                                'fecha'   		 => 'Postulacion.modified',
								'fecha_creacion' => 'Postulacion.created',	
								'nombre'		 => 'Postulante.nombre',	
								'apellidop'		 => 'Postulante.apellidop',								
								'apellidom'		 => 'Postulante.apellidom',								
                        );

                        $this->paginate = array('limit' => 20,
                            'joins' => array(
                                array(
                                    'table' => 'RAP_ESCUELAS_CARRERAS',
                                    'alias' => 'EscuelaCarrera',  //Carrera
                                    'type' => 'INNER',
                                    'conditions' => array(
                                        'Postulacion.carrera_codigo = EscuelaCarrera.carrera_codigo'
                                    )
                                )
                            ),
                            'fields' => array(
                                'Postulacion.postulante_codigo', 
                                'Postulacion.revision', 
                                'Postulacion.habilitado', 
                                'Postulacion.codigo', 
                                'Postulacion.carrera_codigo', 
                                'Postulacion.modified', 
								'Postulacion.created',
								'Postulacion.id_correlativo',								
                                'Postulacion.tipo', 
                                'Postulante.codigo',
                                'Postulante.nombre',								
                                'Postulante.apellidop',								
                                'Postulante.apellidom',								
                                'Carrera.codigo',
                                'Carrera.nombre',
                                'EscuelaCarrera.escuela_codigo'
                            ),
							'order' => 'Postulacion.modified DESC',
                            'conditions' => $condicion,															 

                        );

                        $this->set('postulaciones',$this->paginate('Postulacion'));
                    }
	}
	
	
	/* METODO QUE PERMITE ANALIZAR LA POSTULACION EN DETALLE EN ADMISIÓN VERTICAL Y HORIZONTAL */
	public function verAdmision($codigo_postulacion){			
		if ($this->request->is('post')) {
			$postulacion = $this->Postulacion->find('first', array(			
													'fields' => array(
														'Postulacion.postulante_codigo', 
														'Postulacion.motivos', 
														'Postulacion.habilitado', 
														'Postulacion.codigo', 
														'Postulacion.ciudad_codigo', 
														'Postulacion.carrera_codigo', 
														'Postulacion.modified', 
														'Postulacion.revision', 
														'Postulacion.id_correlativo', 
														'Postulante.codigo',
														'Postulante.nombre',
														'Postulante.apellidop',
														'Postulante.apellidom',
														'Postulante.telefonomovil',
														'Postulante.email',
														'Postulante.rut',
														'Carrera.codigo',
														'Carrera.nombre',
														'Ciudad.codigo',
														'Ciudad.nombre'
													),
													'conditions' => 
																		array('Postulacion.codigo' => $codigo_postulacion),
													)); 
												
			
			$licencia = $this->ArchivoPostulante->find('first', array(
						'conditions' => array(
								'codigo' => 'li-'.$postulacion['Postulante']['codigo'],
								'tipo' => 'LICENCIA'
						))
					);
			
			$cedula = $this->ArchivoPostulante->find('first', array(
						'conditions' => array(
								'codigo' => 'ci-'.$postulacion['Postulante']['codigo'],
								'tipo' => 'CEDULA'
						))
					);
			
			$archivo_resp = $this->Cargas->find('all',array(
											'conditions'         => array(
											'postulacion_codigo' => $codigo_postulacion,
											'tipo_archivo_codigo' => '4'
											
										))
							);
			
			$prepostulacion 	   = $this->Prepostulacion->find('first', array('conditions' => array('Prepostulacion.codigo_postulacion' => $codigo_postulacion)));			
			$codigo_prepostulacion = $prepostulacion['Prepostulacion']['codigo'];		
			$anexos 			   = $this->ArchivoPrepostulacion->find('all', array('conditions' => array('ArchivoPrepostulacion.prepostulacion_codigo' => $codigo_prepostulacion)));		
			$acta_firmada          = $this->Cargas->find('first', array('conditions' => array('Cargas.postulacion_codigo' => $codigo_postulacion, 'tipo_archivo_codigo' => 5)));
			
			if (isset($anexos) && (!empty($anexos))) { $this->set('anexos', $anexos); }		
			$this->set('acta_firmada', $acta_firmada);
			$this->set('licencia', $licencia);
			$this->set('postulacion', $postulacion);
			$this->set('cedulaIdentidad', $cedula);	
            $this->set('archivo_resp', $archivo_resp);			
		}
		else {
			$this->Session->setFlash(__('No se puede acceder. Error de entrada a la postulacion.'),'mensaje-error');
			$this->redirect(array('action' => 'listadoPostulacionesNuevas'));				
		}
	}
	
	//Dados los parámetros de postulacion, guarda la respuesta dada al postulante para las AH y las AV
	//Aquí se ha dado un cuestión curiosa que no sabemos decir porque, pero con las comillas existe un problema en el updateAll
	public function responderPostulante(){
		$respuesta = $this->request->data;	
		$respuesta['Administrativo']['motivos'] = "'".$respuesta['Administrativo']['motivos']."'";
		if (($respuesta['Administrativo']['habilitar'] == null) || ($respuesta['Administrativo']['codigo_postulacion'] == null)) {
			$this->Session->setFlash(__('Algún campo del formulario no se ha rellenado completamente'),'mensaje-error');
			$this->redirect(array('action' => 'listadoPostulacionesNuevas'));				
		}
		$codigo_postulacion = $respuesta['Administrativo']['codigo_postulacion'];
		
		/* CARGAMOS LOS ARCHIVOS */
		foreach($respuesta['Administrativo']['archivos'] as $k=> $archivo){
			if (!empty($archivo['fichero'])){ //Se envía archivo		
					$ruta               = WWW_ROOT.'uploads'.DS.'prepostulaciones'.DS;
					$file               = $archivo['fichero'];
					$posicion = strrpos($archivo['fichero']['name'], '.');		
					$extension = substr($archivo['fichero']['name'],$posicion, 4);
					$file['name'] = $archivo['nombre'].$extension;
					$codigo_postulacion = $respuesta['Administrativo']['codigo_postulacion'];
					$tipo_archivo       = '4';
					$this->Cargas->upload($ruta,$file,$codigo_postulacion,$tipo_archivo); 
			}	
		}
		/* FIN DE CARGA DE ARCHIVOS */
		
		$postulacion = $this->Postulacion->find('first', array('conditions' => array("Postulacion.codigo" => $codigo_postulacion)));
		$idCorrelativo = $postulacion['Postulacion']['id_correlativo'];
		$fecha_postulacion = $postulacion['Postulacion']['created'];		
		$prepostulacion = $this->Prepostulacion->find('first', array('conditions' => array( "Prepostulacion.codigo_postulacion" => $codigo_postulacion ))); 
		$fecha_prepostulacion = $prepostulacion['Prepostulacion']['created'];
		if ($this->Postulacion->updateAll(array(
				'Postulacion.motivos' => $respuesta['Administrativo']['motivos'], 
				'Postulacion.revision' => 1, 
				'Postulacion.habilitado' => $respuesta['Administrativo']['habilitar'], 
				'Postulacion.modified' => 'SYSDATE', 
				'Postulacion.created' => "'".$fecha_postulacion."'", 
			)
			,array("Postulacion.codigo" => $respuesta['Administrativo']['codigo_postulacion']))){
					//Actualizamos también la fecha de prepostulacion//
					$this->Prepostulacion->updateAll(array('Prepostulacion.modified' => 'SYSDATE', 'Prepostulacion.created' => "'".$fecha_prepostulacion."'"),
						array("Prepostulacion.codigo_postulacion" => $respuesta['Administrativo']['codigo_postulacion']));
					//FIN ACTUALIZACIÓN PREPOSTULACIÓN
					if ($respuesta['Administrativo']['habilitar'] == 1){ //Si se ha habilitado al postulante
						$postulacion = $this->Postulacion->find('first', array('conditions' => array('Postulacion.codigo' => $codigo_postulacion)));
						//COMPROBAMOS SI ES RAP PARA AGREGAR EL ESTADO A RAP_ESTADOS_POSTULACIONES
						if ($postulacion['Postulacion']['tipo'] == 'RAP'){
							$data_estado_postulacion = array(						
								'codigo'=> 'px'.uniqid().rand(99, 999),
								'postulacion_codigo'=>$codigo_postulacion,
								'estado_codigo'=>9,	
								'fecha_cambio'=> 'SYSDATE',
								'postulante_codigo'=> $postulacion['Postulante']['codigo']
							);
							$this->EstadoPostulacion->create();
							$this->EstadoPostulacion->save($data_estado_postulacion);	
						}
						//FIN DE COMPROBACIÓN
						$codigo_postulante = $postulacion['Postulacion']['postulante_codigo'];			
						$postulante = $this->Postulante->find('first', array('conditions' => array('Postulante.codigo' => $codigo_postulante)));
						if (($this->Correo->enviarEmail($postulante, 4, 0, 0) === true) && (($this->Correo->enviarEmail($postulante, 10, 0, 0,null, $idCorrelativo) === true))){
							$this->Session->setFlash(__('Postulación Habilitada. Además se ha enviado un email al postulante'),'mensaje-exito');
							$this->redirect(array('action' => 'listadoPostulacionesNuevas'));
						}
						else {
							$this->Session->setFlash(__('Postulación Habilitada, pero ha habido un PROBLEMA a la hora de ENVIAR el mensaje.'),'mensaje-exito');
							$this->redirect(array('action' => 'listadoPostulacionesNuevas'));				
						}
					}
					if ($respuesta['Administrativo']['habilitar'] == 0){ //Si se ha inhabilitado al postulante
						//Actualizamos también la fecha de prepostulacion//
						$this->Prepostulacion->updateAll(array('Prepostulacion.created' => 'SYSDATE', 'Prepostulacion.created' => "'".$fecha_prepostulacion."'"),
							array("Prepostulacion.codigo_postulacion" => $respuesta['Administrativo']['codigo_postulacion']));
						//FIN ACTUALIZACIÓN PREPOSTULACIÓN
						//COMPROBAMOS SI ES RAP PARA AGREGAR EL ESTADO A RAP_ESTADOS_POSTULACIONES
						if ($postulacion['Postulacion']['tipo'] == 'RAP'){
							$data_estado_postulacion = array(						
								'codigo'=> 'px'.uniqid().rand(99, 999),
								'postulacion_codigo'=>$codigo_postulacion,
								'estado_codigo'=>7,	
								'fecha_cambio'=> 'SYSDATE',
								'postulante_codigo'=> $postulacion['Postulante']['codigo']
							);
							$this->EstadoPostulacion->create();
							$this->EstadoPostulacion->save($data_estado_postulacion);	
						}
						//FIN DE COMPROBACIÓN
						$postulacion = $this->Postulacion->find('first', array('conditions' => array('Postulacion.codigo' => $codigo_postulacion)));				
						$codigo_postulante = $postulacion['Postulacion']['postulante_codigo'];			
						$postulante = $this->Postulante->find('first', array('conditions' => array('Postulante.codigo' => $codigo_postulante)));
						if (($this->Correo->enviarEmail($postulante, 5, 0, 0, null, $idCorrelativo) === true) && (($this->Correo->enviarEmail($postulante, 11, 0, 0, null, $idCorrelativo) === true))){
							$this->Session->setFlash(__('Postulación Inhabilitada. Además se ha enviado un email al postulante'),'mensaje-exito');
							if ($postulacion['Postulacion']['tipo'] == 'RAP'){
								$this->redirect(array('action' => 'listadoPostulaciones'));
							}					
							$this->redirect(array('action' => 'listadoPostulacionesNuevas'));
						}
						else {
							$this->Session->setFlash(__('Postulación InHabilitada, pero ha habido un PROBLEMA a la hora de ENVIAR el mensaje.'),'mensaje-exito');
							if ($postulacion['Postulacion']['tipo'] == 'RAP'){
								$this->redirect(array('action' => 'listadoPostulaciones'));
							}		
							$this->redirect(array('action' => 'listadoPostulacionesNuevas'));				
						}
					}	
		}
		else{
			$this->Session->setFlash(__('Error guardando la postulacion'),'mensaje-error');
			if ($postulacion['Postulacion']['tipo'] == 'RAP'){
				$this->redirect(array('action' => 'listadoPostulaciones'));
			}		
			$this->redirect(array('action' => 'listadoPostulacionesNuevas'));		
		}
	}
	
	private function actualizaFecha($postulacion){		
		$data = array('Postulacion.codigo' => $postulacion);
		$this->Postulacion->updateAll(array('Postulacion.modified' => 'SYSDATE'), $data);
		$data2 = array('Prepostulacion.codigo_postulacion' => $postulacion);
		$this->Prepostulacion->updateAll(array('Prepostulacion.modified' => 'SYSDATE'), $data2); 		
	}
}
?>