<?php
	App::uses('AppController', 'Controller');
	
	class CoordinadoresController extends AppController {
	
		var $name = 'Coordinadores';
		var $uses = array('ArchivoPostulante', 
						  'Prepostulacion',
						  'Administrativo',
						  'Postulacion',
						  'Cargas',
						  'EducacionPostulacion',
						  'CapacitacionPostulacion',
						  'LaboralPostulacion',
						  'AutoEvaluacion',
						  'CompetenciaPostulacion', 
						  'EstadoPostulacion', 
						  'Postulante', 
						  'Horario',
						  'Entrevista', 
						  'Cargas', 
						  'EvidenciasPrevias', 
						  'ArchivoEvidencia', 
						  'ArchivoPrepostulacion',
						  'ArchivoPostulacion',
						  'Correo',
						  'Carrera',
						  'EscuelaCarrera',
						  'EstadoPostulacion');
                
		var $layout = "administrativos-2016";
                
		function beforeFilter(){
			$this->validarAdmin();	  
		}
		

	public function round_up($value, $places)
		{
		    $mult = pow(10, abs($places));
		     return $places < 0 ?
		    ceil($value / $mult) * $mult :
		        ceil($value * $mult) / $mult;
		}		
		
		/* ESTA FUNCIÓN MOSTRARÁ EL LISTADO DE PREPOSTULACIONES PARA QUE EL Coordinadores
		GENERAL PUEDA VALIDAR O INVALIDAR LAS PREPOSTULACIONES */
		
		public function listadoPrepostulaciones($buscar_estado = null, $pagina = null){








			if (($buscar_estado <> Null) and ($buscar_estado <> 'todos')) {







			$this->Prepostulacion->virtualFields = array(						
						'nombre'        	 => 'Postulante.nombre',
						'apellidop'          => 'Postulante.apellidop',
						'apellidom'          => 'Postulante.apellidom',
						'rut'          		 => 'Postulante.rut',
						'email'          	 => 'Postulante.email',
						'carrera' 	    	 => 'Carrera.nombre',
						'tipo' 	    	     => 'Prepostulacion.destino',
						'telefonomovil' 	 => 'Postulante.telefonomovil',
						'estado' 			 => 'Prepostulacion.revision',
						'correlativo'        => 'Prepostulacion.id_correlativo',
						'fecha_modificacion' => 'Prepostulacion.modified',
						'destino2' 	    	 => 'Prepostulacion.destino',
						'fecha_creacion' 	 => 'Prepostulacion.created'
			);
			
			$this->paginate = array(
				'limit'       => 9999,
				'order'       => 'Prepostulacion.modified desc',
				'conditions'  => array(
					'Prepostulacion.guardado' => '1',
				),
				'fields' => array('Prepostulacion.codigo', 'Prepostulacion.id_correlativo', 'Prepostulacion.guardado', 'Prepostulacion.postulante_codigo','Postulante.nombre', 'Postulante.apellidop', 'Postulante.apellidom', 'Postulante.rut', 'Postulante.email', 'Carrera.nombre','Postulante.telefonomovil', 'Prepostulacion.modified', 'Prepostulacion.revision', 'Prepostulacion.destino','Prepostulacion.codigo_postulacion','Prepostulacion.created'),
				'group'  => array('Prepostulacion.codigo', 'Prepostulacion.id_correlativo', 'Prepostulacion.guardado', 'Prepostulacion.postulante_codigo','Postulante.nombre', 'Postulante.apellidop', 'Postulante.apellidom', 'Postulante.rut', 'Postulante.email', 'Carrera.nombre','Postulante.telefonomovil', 'Prepostulacion.modified', 'Prepostulacion.revision', 'Prepostulacion.destino','Prepostulacion.codigo_postulacion','Prepostulacion.created')
			);
			
			$prepostulaciones   = $this->paginate('Prepostulacion');
			$codigo_postulacion = '';
			
			foreach($prepostulaciones as $k => $prepostulacion){ //asd
			
				$codigo_postulacion = $prepostulacion['Prepostulacion']['codigo_postulacion'];
				$postulaciones      = $this->Postulacion->find('all', array('conditions' => array('Postulacion.codigo' => $prepostulacion['Prepostulacion']['codigo_postulacion'])));
				
				foreach($postulaciones as $postulacion){
					$prepostulaciones[$k]['Postulacion'] = $postulacion['Postulacion'];
					
					if(count($postulaciones)>0 && $postulacion['Postulacion']['tipo'] == 'RAP'){
						//$estado_postulacion 			    	   = $this->EstadoPostulacion->find('all', array('conditions' => array('EstadoPostulacion.postulacion_codigo' => $codigo_postulacion),'group' => 'EstadoPostulacion.codigo'),array('fields' => array('MAX(estado_codigo) AS asdasd', '*')));
						$estado_postulaciones 				       = $this->EstadoPostulacion->find('all', array('conditions' => array('EstadoPostulacion.postulacion_codigo' => $codigo_postulacion)) );
						$array_estados                             = array();
						
						if(count($estado_postulaciones)>0){
							foreach($estado_postulaciones as $estado_postulacion){
								array_push($array_estados,$estado_postulacion['EstadoPostulacion']['estado_codigo']);
							}
							

						}

						$prepostulaciones[$k]['EstadoPostulacion'] = $array_estados;
						
					}
					
				}
					
			}

			$tmp_pre = array();

			foreach($prepostulaciones as $k => $prepostulacion):

									if($prepostulacion['Prepostulacion']['destino'] !== null){//Si la postulación ya fue derivada por el coordinador	
										
										if($prepostulacion['Prepostulacion']['destino']=='RAP'){
											if (isset($prepostulacion['EstadoPostulacion'])){
												$maximo = max($prepostulacion['EstadoPostulacion']);
												$estado = '';
												$paso   = '';
												//echo var_dump($prepostulacion['EstadoPostulacion']);
												
												$resp   = false;
												$resp   = in_array(7,$prepostulacion['EstadoPostulacion']); //Verifico si está rechazada
												
												if($resp){
													if($buscar_estado=='post_rech'){
														$estado = '<span class="rojo">POSTULACIÓN RECHAZADA</span>';
														$paso   = '';
														$tmp_pre[] = $prepostulacion;
													}
														
												}
												else{
														switch($maximo){
															case 1:
																if ($buscar_estado == 'por_rap') {
																$tmp_pre[] = $prepostulacion;
																}
															break;
															case 2:
																if ($buscar_estado == 'pro_rap') {
																$tmp_pre[] = $prepostulacion;
																}
															break;
															case 3:
																if ($buscar_estado == 'pro_rap') {
																$tmp_pre[] = $prepostulacion;
																}
															break;
															case 4:
																if ($buscar_estado == 'pro_rap') {
																$tmp_pre[] = $prepostulacion;
																}
															break;
															case 5:
																if ($buscar_estado == 'pro_rap') {
																$tmp_pre[] = $prepostulacion;
																}
															break;
															case 6:
																if ($buscar_estado == 'pro_rap') {
																$tmp_pre[] = $prepostulacion;
																}
															break;
															case 7:
																if ($buscar_estado == 'no_hab') {
																$tmp_pre[] = $prepostulacion;
																}
															break;
															case 8:
																if ($buscar_estado == 'pro_rap') {
																$tmp_pre[] = $prepostulacion;
																}
															break;
															case 9:
																if ($buscar_estado == 'hab') {
																$tmp_pre[] = $prepostulacion;
																}
															break;
														}
												}
											}
											else{ //NO ESTÁ EL ESTADO DE RAP POR TANTO SOLO PUEDE ESTAR EN OBSERVACIONES
											}
										}
										if($prepostulacion['Prepostulacion']['destino']== 'AH'){
											if (isset($prepostulacion['Postulacion']['habilitado'])){																									
												if($prepostulacion['Postulacion']['habilitado'] == '1'){
														if (($prepostulacion['Postulacion']['firma'] == '1') && ($prepostulacion['Postulacion']['csa'] == '1') ){
																if ($buscar_estado == 'in_csa') {
																$tmp_pre[] = $prepostulacion;
																}
														}
														elseif (($prepostulacion['Postulacion']['firma'] == '1') && ($prepostulacion['Postulacion']['csa'] !== '1') ){
																if ($buscar_estado == 'hab_firma') {
																$tmp_pre[] = $prepostulacion;
																}
													
														}
														else{
																if ($buscar_estado == 'hab') {
																$tmp_pre[] = $prepostulacion;
																}
														}					
													}
												
													elseif ($prepostulacion['Postulacion']['habilitado'] == '0') {
																if ($buscar_estado == 'no_hab') {
																$tmp_pre[] = $prepostulacion;
																}
													}
													else {
																if ($buscar_estado == 'en_rev') {
																$tmp_pre[] = $prepostulacion;
																}
													}
											}
											else{
																if ($buscar_estado == 'en_rev') {
																$tmp_pre[] = $prepostulacion;
																}
											}
										}
										if($prepostulacion['Prepostulacion']['destino']== 'AV'){
											$estado = '';
											if (isset($prepostulacion['Postulacion']['habilitado'])){	
											if($prepostulacion['Postulacion']['habilitado'] == '1'){
													if (($prepostulacion['Postulacion']['firma'] == '1') && ($prepostulacion['Postulacion']['csa'] == '1') ){
																if ($buscar_estado == 'in_csa') {
																$tmp_pre[] = $prepostulacion;
																}
													}
													elseif (($prepostulacion['Postulacion']['firma'] == '1') && ($prepostulacion['Postulacion']['csa'] !== '1') ){
																if ($buscar_estado == 'hab_firma') {
																$tmp_pre[] = $prepostulacion;
																}
												
													}
													else{
																if ($buscar_estado == 'hab') {
																$tmp_pre[] = $prepostulacion;
																}
									
													}
												}
												elseif ($prepostulacion['Postulacion']['habilitado'] == '0') {
																if ($buscar_estado == 'no_hab') {
																$tmp_pre[] = $prepostulacion;
																}
												}
												else {
																if ($buscar_estado == 'en_rev') {
																$tmp_pre[] = $prepostulacion;
																}
												}
											}
											else{
																if ($buscar_estado == 'en_rev') {
																$tmp_pre[] = $prepostulacion;
																}
											}
										}
								
									}else{
										if($prepostulacion['Prepostulacion']['revision']==0 || $prepostulacion['Prepostulacion']['revision']== null){
																	if ($buscar_estado == 'pen_rev') {
																	$tmp_pre[] = $prepostulacion;
																	}
										}
										else{
																	if ($buscar_estado == 'doc_obs') {
																	$tmp_pre[] = $prepostulacion;
																	}
										}
									}
					
					endforeach ;
					$this->set('busqueda', 'busqueda');
					$this->set('buscar_estado', $buscar_estado);
					$prepostulaciones = $tmp_pre;

					$cant = count($prepostulaciones);
					$cant_pag_tmp = $cant / 20;

					$cant_pag = $this->round_up($cant_pag_tmp, 0);

					$prepostulaciones = array_slice($prepostulaciones, 0, 20);


					if($cant_pag == 0) {
						$cant_pag = 1;
					}

					$this->set('cant_pag', $cant_pag);

			} else {

			//$prepostulaciones = $this->Prepostulacion->find('all');
			$this->Prepostulacion->virtualFields = array(						
						'nombre'        	 => 'Postulante.nombre',
						'apellidop'          => 'Postulante.apellidop',
						'apellidom'          => 'Postulante.apellidom',
						'rut'          		 => 'Postulante.rut',
						'email'          	 => 'Postulante.email',
						'carrera' 	    	 => 'Carrera.nombre',
						'tipo' 	    	     => 'Prepostulacion.destino',
						'telefonomovil' 	 => 'Postulante.telefonomovil',
						'estado' 			 => 'Prepostulacion.revision',
						'correlativo'        => 'Prepostulacion.id_correlativo',
						'fecha_modificacion' => 'Prepostulacion.modified',
						'destino2' 	    	 => 'Prepostulacion.destino',
						'fecha_creacion' 	 => 'Prepostulacion.created'
			);
			
			$this->paginate = array(
				'limit'       => 20,
				'order'       => 'Prepostulacion.modified desc',
				'conditions'  => array(
					'Prepostulacion.guardado' => '1',
				),
				'fields' => array('Prepostulacion.codigo', 'Prepostulacion.id_correlativo', 'Prepostulacion.guardado', 'Prepostulacion.postulante_codigo','Postulante.nombre', 'Postulante.apellidop', 'Postulante.apellidom', 'Postulante.rut', 'Postulante.email', 'Carrera.nombre','Postulante.telefonomovil', 'Prepostulacion.modified', 'Prepostulacion.revision', 'Prepostulacion.destino','Prepostulacion.codigo_postulacion','Prepostulacion.created'),
				'group'  => array('Prepostulacion.codigo', 'Prepostulacion.id_correlativo', 'Prepostulacion.guardado', 'Prepostulacion.postulante_codigo','Postulante.nombre', 'Postulante.apellidop', 'Postulante.apellidom', 'Postulante.rut', 'Postulante.email', 'Carrera.nombre','Postulante.telefonomovil', 'Prepostulacion.modified', 'Prepostulacion.revision', 'Prepostulacion.destino','Prepostulacion.codigo_postulacion','Prepostulacion.created')		
			);
			
			$prepostulaciones   = $this->paginate('Prepostulacion');
			$codigo_postulacion = '';
			
			foreach($prepostulaciones as $k => $prepostulacion){ //asd
			
				$codigo_postulacion = $prepostulacion['Prepostulacion']['codigo_postulacion'];
				$postulaciones      = $this->Postulacion->find('all', array('conditions' => array('Postulacion.codigo' => $prepostulacion['Prepostulacion']['codigo_postulacion'])));
				
				foreach($postulaciones as $postulacion){
					$prepostulaciones[$k]['Postulacion'] = $postulacion['Postulacion'];
					
					if(count($postulaciones)>0 && $postulacion['Postulacion']['tipo'] == 'RAP'){
						//$estado_postulacion 			    	   = $this->EstadoPostulacion->find('all', array('conditions' => array('EstadoPostulacion.postulacion_codigo' => $codigo_postulacion),'group' => 'EstadoPostulacion.codigo'),array('fields' => array('MAX(estado_codigo) AS asdasd', '*')));
						$estado_postulaciones 				       = $this->EstadoPostulacion->find('all', array('conditions' => array('EstadoPostulacion.postulacion_codigo' => $codigo_postulacion)) );
						$array_estados                             = array();
						
						if(count($estado_postulaciones)>0){
							foreach($estado_postulaciones as $estado_postulacion){
								array_push($array_estados,$estado_postulacion['EstadoPostulacion']['estado_codigo']);
							}
							
							$prepostulaciones[$k]['EstadoPostulacion'] = $array_estados;
						}
						
					}
					break;
				}
					
			}
			$this->set('buscar_estado', '0');

			}

			
			$this->set('prepostulaciones',$prepostulaciones);
	
			$cantidades = $this->Prepostulacion->find('all', array(
					'fields' 	 => array('Prepostulacion.postulante_codigo', 'count(*) as "Prepostulacion.total"'),
					'group' 	 => array('Prepostulacion.postulante_codigo'),						
					//'conditions' => array('Prepostulacion.destino' => null),
					)		
			);
			$this->set('cantidades', $cantidades);
		}

		public function ajax_exportar_excel_postulaciones($buscar_estado = null, $pagina = null){


			 $this->layout='ajax';
			 $this->data = $this->Session->read('Datos');


			
			//$prepostulaciones = $this->Prepostulacion->find('all');
			$this->Prepostulacion->virtualFields = array(						
						'nombre'        	 => 'Postulante.nombre',
						'apellidop'          => 'Postulante.apellidop',
						'apellidom'          => 'Postulante.apellidom',
						'rut'          		 => 'Postulante.rut',
						'email'          	 => 'Postulante.email',
						'carrera' 	    	 => 'Carrera.nombre',
						'tipo' 	    	     => 'Prepostulacion.destino',
						'telefonomovil' 	 => 'Postulante.telefonomovil',
						'estado' 			 => 'Prepostulacion.revision',
						'correlativo'        => 'Prepostulacion.id_correlativo',
						'fecha_modificacion' => 'Prepostulacion.modified',
						'destino2' 	    	 => 'Prepostulacion.destino',
						'fecha_creacion' 	 => 'Prepostulacion.created'
			);
			
			$this->paginate = array(
				'limit' 	=> 9999,
				'order'       => 'Prepostulacion.modified desc',
				'conditions'  => array(
					'OR' => array(
						//array('Prepostulacion.revision <>' => 2),
						//array('Prepostulacion.revision'    => null)     
					),
					'Prepostulacion.guardado' => '1',
				),
				'fields' => array('Prepostulacion.codigo', 'Prepostulacion.id_correlativo', 'Prepostulacion.guardado', 'Prepostulacion.postulante_codigo','Postulante.nombre', 'Postulante.apellidop', 'Postulante.apellidom', 'Postulante.rut', 'Postulante.email', 'Carrera.nombre','Postulante.telefonomovil', 'Prepostulacion.modified', 'Prepostulacion.revision', 'Prepostulacion.destino','Prepostulacion.codigo_postulacion','Prepostulacion.created'),
				'group'  => array('Prepostulacion.codigo', 'Prepostulacion.id_correlativo', 'Prepostulacion.guardado', 'Prepostulacion.postulante_codigo','Postulante.nombre', 'Postulante.apellidop', 'Postulante.apellidom', 'Postulante.rut', 'Postulante.email', 'Carrera.nombre','Postulante.telefonomovil', 'Prepostulacion.modified', 'Prepostulacion.revision', 'Prepostulacion.destino','Prepostulacion.codigo_postulacion','Prepostulacion.created'),
				/* 'joins'  => array(
								array('table' => 'RAP_POSTULACIONES',
									'type'       => 'LEFT',
									'alias'      => 'Postulacion',
									'conditions' => array('Prepostulacion.codigo_postulacion = Postulacion.codigo')
								)			  
				), */
			);
			
			$prepostulaciones   = $this->paginate('Prepostulacion');
			
			$codigo_postulacion = '';
			
			foreach($prepostulaciones as $k => $prepostulacion){ //asd
			
				$codigo_postulacion = $prepostulacion['Prepostulacion']['codigo_postulacion'];
				$postulaciones      = $this->Postulacion->find('all', array('conditions' => array('Postulacion.codigo' => $prepostulacion['Prepostulacion']['codigo_postulacion'])));
				
				foreach($postulaciones as $postulacion){
					$prepostulaciones[$k]['Postulacion'] = $postulacion['Postulacion'];
					
					if(count($postulaciones)>0 && $postulacion['Postulacion']['tipo'] == 'RAP'){
						//$estado_postulacion 			    	   = $this->EstadoPostulacion->find('all', array('conditions' => array('EstadoPostulacion.postulacion_codigo' => $codigo_postulacion),'group' => 'EstadoPostulacion.codigo'),array('fields' => array('MAX(estado_codigo) AS asdasd', '*')));
						$estado_postulaciones 				       = $this->EstadoPostulacion->find('all', array('conditions' => array('EstadoPostulacion.postulacion_codigo' => $codigo_postulacion)) );
						$array_estados                             = array();
						
						if(count($estado_postulaciones)>0){
							foreach($estado_postulaciones as $estado_postulacion){
								array_push($array_estados,$estado_postulacion['EstadoPostulacion']['estado_codigo']);
							}
							
							$prepostulaciones[$k]['EstadoPostulacion'] = $array_estados;
						}
						
					}
					break;
				}
					
			}
			$this->set('buscar_estado', '0');

			

			
			$this->set('prepostulaciones',$prepostulaciones);
	
			$cantidades = $this->Prepostulacion->find('all', array(
					'fields' 	 => array('Prepostulacion.postulante_codigo', 'count(*) as "Prepostulacion.total"'),
					'group' 	 => array('Prepostulacion.postulante_codigo'),						
					//'conditions' => array('Prepostulacion.destino' => null),
					)		
			);
			$this->set('cantidades', $cantidades);
		}
		 
		

		public function paginacion() {



			$this->layout="ajax";

			$buscar_estado = $this->data['busqueda'];
			$pagina = $this->data['pagina'];

			$this->Prepostulacion->virtualFields = array(						
						'nombre'        	 => 'Postulante.nombre',
						'apellidop'          => 'Postulante.apellidop',
						'apellidom'          => 'Postulante.apellidom',
						'carrera' 	    	 => 'Carrera.nombre',
						'tipo' 	    	     => 'Prepostulacion.destino',
						'telefonomovil' 	 => 'Postulante.telefonomovil',
						'estado' 			 => 'Prepostulacion.revision',
						'correlativo'        => 'Prepostulacion.id_correlativo',
						'fecha_modificacion' => 'Prepostulacion.modified',
						'destino2' 	    	 => 'Prepostulacion.destino',
						'fecha_creacion' 	 => 'Prepostulacion.created'
			);
			
			$this->paginate = array(
				'limit'       => 9999,
				'order'       => 'Prepostulacion.modified desc',
				'conditions'  => array(
					'Prepostulacion.guardado' => '1',
				),
				'fields' => array('Prepostulacion.codigo', 'Prepostulacion.id_correlativo', 'Prepostulacion.guardado', 'Prepostulacion.postulante_codigo','Postulante.nombre', 'Postulante.apellidop', 'Postulante.apellidom', 'Carrera.nombre','Postulante.telefonomovil', 'Prepostulacion.modified', 'Prepostulacion.revision', 'Prepostulacion.destino','Prepostulacion.codigo_postulacion','Prepostulacion.created'),
				'group'  => array('Prepostulacion.codigo', 'Prepostulacion.id_correlativo', 'Prepostulacion.guardado', 'Prepostulacion.postulante_codigo','Postulante.nombre', 'Postulante.apellidop', 'Postulante.apellidom', 'Carrera.nombre','Postulante.telefonomovil', 'Prepostulacion.modified', 'Prepostulacion.revision', 'Prepostulacion.destino','Prepostulacion.codigo_postulacion','Prepostulacion.created'),
			);
			
			$prepostulaciones   = $this->paginate('Prepostulacion');
			$codigo_postulacion = '';
			
			foreach($prepostulaciones as $k => $prepostulacion){ //asd
			
				$codigo_postulacion = $prepostulacion['Prepostulacion']['codigo_postulacion'];
				$postulaciones      = $this->Postulacion->find('all', array('conditions' => array('Postulacion.codigo' => $prepostulacion['Prepostulacion']['codigo_postulacion'])));
				
				foreach($postulaciones as $postulacion){
					$prepostulaciones[$k]['Postulacion'] = $postulacion['Postulacion'];
					
					if(count($postulaciones)>0 && $postulacion['Postulacion']['tipo'] == 'RAP'){
						//$estado_postulacion 			    	   = $this->EstadoPostulacion->find('all', array('conditions' => array('EstadoPostulacion.postulacion_codigo' => $codigo_postulacion),'group' => 'EstadoPostulacion.codigo'),array('fields' => array('MAX(estado_codigo) AS asdasd', '*')));
						$estado_postulaciones 				       = $this->EstadoPostulacion->find('all', array('conditions' => array('EstadoPostulacion.postulacion_codigo' => $codigo_postulacion)) );
						$array_estados                             = array();
						
						if(count($estado_postulaciones)>0){
							foreach($estado_postulaciones as $estado_postulacion){
								array_push($array_estados,$estado_postulacion['EstadoPostulacion']['estado_codigo']);
							}
							
							$prepostulaciones[$k]['EstadoPostulacion'] = $array_estados;
						}
						
					}
					break;
				}
					
			}








				$tmp_pre = array();






				foreach($prepostulaciones as $k => $prepostulacion):

									if($prepostulacion['Prepostulacion']['destino'] !== null){//Si la postulación ya fue derivada por el coordinador	
										
											if($prepostulacion['Prepostulacion']['destino']=='RAP'):
												if (isset($prepostulacion['EstadoPostulacion'])){
													$maximo = max($prepostulacion['EstadoPostulacion']);
													$estado = '';
													$paso   = '';
													
													$resp   = false;
													$resp   = in_array(7,$prepostulacion['EstadoPostulacion']); //Verifico si está rechazada
													
													if($resp){
															$estado = '<span class="rojo">Postulación rechazada</span>';
															$paso   = '';
													}
													else{
															switch($maximo){
																case 1:
																	if ($buscar_estado == 'pro_rap') {
																	$tmp_pre[] = $prepostulacion;
																	}
																break;
																case 2:
																	if ($buscar_estado == 'pro_rap') {
																	$tmp_pre[] = $prepostulacion;
																	}
																break;
																case 3:
																	if ($buscar_estado == 'pro_rap') {
																	$tmp_pre[] = $prepostulacion;
																	}
																break;
																case 4:
																	if ($buscar_estado == 'pro_rap') {
																	$tmp_pre[] = $prepostulacion;
																	}
																break;
																case 5:
																	if ($buscar_estado == 'pro_rap') {
																	$tmp_pre[] = $prepostulacion;
																	}
																break;
																case 6:
																	if ($buscar_estado == 'pro_rap') {
																	$tmp_pre[] = $prepostulacion;
																	}
																break;
																case 7:
																	if ($buscar_estado == 'no_hab') {
																	$tmp_pre[] = $prepostulacion;
																	}
																break;
																case 8:
																	if ($buscar_estado == 'pro_rap') {
																	$tmp_pre[] = $prepostulacion;
																	}
																break;
																case 9:
																	if ($buscar_estado == 'hab') {
																	$tmp_pre[] = $prepostulacion;
																	}
																break;
															}
													}
												}
												else{ //NO ESTÁ EL ESTADO DE RAP POR TANTO SOLO PUEDE ESTAR EN OBSERVACIONES
												}
											endif;
											if($prepostulacion['Prepostulacion']['destino']=='AH'):
												if (isset($prepostulacion['Postulacion']['habilitado'])){																									
													if($prepostulacion['Postulacion']['habilitado'] == '1'){
															if (($prepostulacion['Postulacion']['firma'] == '1') && ($prepostulacion['Postulacion']['csa'] == '1') ){
																	if ($buscar_estado == 'in_csa') {
																	$tmp_pre[] = $prepostulacion;
																	}
															}
															elseif (($prepostulacion['Postulacion']['firma'] == '1') && ($prepostulacion['Postulacion']['csa'] !== '1') ){
																	if ($buscar_estado == 'hab_firma') {
																	$tmp_pre[] = $prepostulacion;
																	}
														
															}
															else{
																	if ($buscar_estado == 'hab') {
																	$tmp_pre[] = $prepostulacion;
																	}
															}					
														}
													
														elseif ($prepostulacion['Postulacion']['habilitado'] == '0') {
																	if ($buscar_estado == 'no_hab') {
																	$tmp_pre[] = $prepostulacion;
																	}
														}
														else {
																	if ($buscar_estado == 'en_rev') {
																	$tmp_pre[] = $prepostulacion;
																	}
														}
												}
												else{
																	if ($buscar_estado == 'en_rev') {
																	$tmp_pre[] = $prepostulacion;
																	}
												}
											endif;
											if($prepostulacion['Prepostulacion']['destino']=='AV'):
												$estado = '';
												if (isset($prepostulacion['Postulacion']['habilitado'])){	
												if($prepostulacion['Postulacion']['habilitado'] == '1'){
														if (($prepostulacion['Postulacion']['firma'] == '1') && ($prepostulacion['Postulacion']['csa'] == '1') ){
																	if ($buscar_estado == 'in_csa') {
																	$tmp_pre[] = $prepostulacion;
																	}
														}
														elseif (($prepostulacion['Postulacion']['firma'] == '1') && ($prepostulacion['Postulacion']['csa'] !== '1') ){
																	if ($buscar_estado == 'hab_firma') {
																	$tmp_pre[] = $prepostulacion;
																	}
													
														}
														else{
																	if ($buscar_estado == 'hab') {
																	$tmp_pre[] = $prepostulacion;
																	}
										
														}
													}
													elseif ($prepostulacion['Postulacion']['habilitado'] == '0') {
																	if ($buscar_estado == 'no_hab') {
																	$tmp_pre[] = $prepostulacion;
																	}
													}
													else {
																	if ($buscar_estado == 'en_rev') {
																	$tmp_pre[] = $prepostulacion;
																	}
													}
												}
												else{
																	if ($buscar_estado == 'en_rev') {
																	$tmp_pre[] = $prepostulacion;
																	}
												}	
											endif;
																		
									}
									else{
										if($prepostulacion['Prepostulacion']['revision']==0){
																	if ($buscar_estado == 'pen_rev') {
																	$tmp_pre[] = $prepostulacion;
																	}
										}
										else{
																	if ($buscar_estado == 'doc_obs') {
																	$tmp_pre[] = $prepostulacion;
																	}
										}
									}
					
					endforeach ;
					$this->set('busqueda', 'busqueda');
					$this->set('buscar_estado', $buscar_estado);
					$prepostulaciones = $tmp_pre;

					$cant = count($prepostulaciones);
					$cant_pag_tmp = $cant / 20;

					$cant_pag = $this->round_up($cant_pag_tmp, 0);

					$numero = (($pagina-1) * 20) - 1;
					if($numero == -1) {
						$numero = 1;
					}

					$prepostulaciones = array_slice($prepostulaciones, $numero, 20);


					if($cant_pag == 0) {
						$cant_pag = 1;
					}

					$this->set('cant_pag', $cant_pag);			
					$this->set('prepostulaciones',$prepostulaciones);
					$cantidades = $this->Prepostulacion->find('all', array(
							'fields' 	 => array('Prepostulacion.postulante_codigo', 'count(*) as "Prepostulacion.total"'),
							'group' 	 => array('Prepostulacion.postulante_codigo'),						
							//'conditions' => array('Prepostulacion.destino' => null),
							)		
					);
					$this->set('cantidades', $cantidades);					
					$this->set('pagina', $pagina);					
					$this->set('busqueda', $buscar_estado);					
		}
		
        public function verPrepostulacion($codigo_postulacion = null){
            
                if($codigo_postulacion == null){
                    $this->Session->setFlash('El código de la postulación es nulo o erroneo', "mensaje-error");
                    $this->redirect(array('controller' => 'coordinadores','action' => 'listadoPrepostulacionesClose'));	
                }			
                if(empty($this->data)){
                    
                    if($this->request->is('get')){
                        
                        //obtenemos los datos de la prepostulacion
                        $prepostulacion        = $this->Prepostulacion->find('first', array('conditions' => array('Prepostulacion.codigo' => $codigo_postulacion)));

                        $k =0;
						if(is_array($prepostulacion) && count($prepostulacion)>0){
							foreach ($prepostulacion as  $value) {
								$estado_postulaciones = $this->EstadoPostulacion->find('all', array('conditions' => array('EstadoPostulacion.postulacion_codigo' => $prepostulacion['Prepostulacion']['codigo_postulacion'])) );

								//echo var_dump($estado_postulaciones);
								$array_estados  = array();
								if(count($estado_postulaciones)>0){
									foreach($estado_postulaciones as $estado_postulacion){
										array_push($array_estados,$estado_postulacion['EstadoPostulacion']['estado_codigo']);
									}
									
									$prepostulacion[$k]['EstadoPostulacion'] = $array_estados;
								}

								//$postulaciones[$k]['EstadoPostulacion'] = $estado_postulaciones[0]['estado_codigo'];
								$k++;
							}
						}

						$codigoPostulacion = $prepostulacion['Prepostulacion']['codigo_postulacion'];
                        if ((isset($prepostulacion['Prepostulacion']['postulante_codigo']))){
							$codigo_postulante     = ($prepostulacion['Prepostulacion']['postulante_codigo']);
							$maximo = $this->EstadoPostulacion->find('all', array('conditions' => array('EstadoPostulacion.postulacion_codigo' => $codigoPostulacion), 'fields' => array('MAX(estado_codigo) AS maximo')));
							if (!empty($maximo)){ $this->set('maximo', ($maximo[0][0]));}
						}else{
							$this->redirect(array('controller' => 'coordinadores','action' => 'listadoPrepostulacionesClose'));	
						}
                        $prepostulacion_codigo = ($prepostulacion['Prepostulacion']['codigo']);		
                        
                        $ci                 = $this->ArchivoPostulante->find('first', array(
                            'conditions'    => array(
                                'codigo'    => 'ci-'.$codigo_postulante,
                                'tipo'      => 'CEDULA'
                            ))
                        );	
                        $this->set('cedulaIdentidad', $ci);
                        
                        $licencia            = $this->ArchivoPostulante->find('first', array(
                                'conditions' => array(
                                    'codigo' => 'li-'.$codigo_postulante,
                                    'tipo'   => 'LICENCIA'
                                )
                            )
                        );	
                        $this->set('licencia', $licencia);
                        
                        $anexos                             = $this->ArchivoPrepostulacion->find('all', array(
                                'conditions'                => array(
                                    'prepostulacion_codigo' => $prepostulacion_codigo
                                )
                            )
                        );
                        //var_dump($prepostulacion);
						$codigo_postulacion = $prepostulacion['Prepostulacion']['codigo_postulacion'];
						$archivo_resp = $this->Cargas->find('all',array(
											'conditions'         => array(
												'postulacion_codigo' => $codigo_postulacion,
												'tipo_archivo_codigo' => '4'
										))
							);
						$acta_firmada          = $this->Cargas->find('first', array('conditions' => array('Cargas.postulacion_codigo' => $codigo_postulacion, 'tipo_archivo_codigo' => 5)));
						
						$this->set('acta_firmada', $acta_firmada);
                        $this->set('archivo_resp', $archivo_resp);                        
                        $this->set('anexos', $anexos);                        
                        $this->set('prepostulacion', $prepostulacion);
						
						/* INTENTAMOS ENCONTRAR LA RESPUESTA AL POSTULANTE PARA ESO ACCEDEMOS A LOS DATOS DE LA POSTULACIÓN... SI EXISTE SETEAMOS LAS VARIABLES */						
						
						$postulacion = $this->Postulacion->find('first', array('conditions' => array('Postulacion.codigo' => $codigo_postulacion)));						
						if (!empty($postulacion)){
							$this->set('postulacion', $postulacion);
						}
                    }
                    else{
                        $this->Session->setFlash('Problema a la hora de acceder a la postulación. Método inválido', "mensaje-error");
                        $this->redirect(array('controller' => 'coordinadores','action' => 'listadoPrepostulaciones'));
                    }
                }
                else{
                    //echo var_dump($this->params['pass'][0]);
                    $codigoPrepostulacion     = $this->params['pass'][0];
                    //echo var_dump($codigoPrepostulacion);
                    $codigoPostulante         = $this->Prepostulacion->find('first', array('conditions' => array('Prepostulacion.codigo' => $codigoPrepostulacion)));
                    //echo var_dump($codigoPostulante);
                    $this->Prepostulacion->id = $codigoPrepostulacion;	
                    //SI GUARDAMOS
                    $this->modificarArchivos($codigoPostulante, $this->request->data, $this->params['pass'][0]);
                }	

        }
		
		
		//Modifica el estado del CSA de una postulación pasada por post
		public function csa(){			
			$csa = $this->request->data['Coordinadore']['multiple'];
			$codigo_postulacion = $this->request->data['Coordinadore']['postulacion'];
			$codigo_prepostulacion = $this->request->data['Coordinadore']['prepostulacion'];
			$date = date('Y-m-d H:i:s', time());
			$data = array('Postulacion.codigo' => $codigo_postulacion);
			$data2 = array('Prepostulacion.codigo_postulacion' => $codigo_postulacion);
			if ($this->Postulacion->updateAll(array('Postulacion.csa' => $csa, 'Postulacion.modified' => "SYSDATE"), $data)){
						$this->Prepostulacion->updateAll(array('Prepostulacion.modified' => "SYSDATE"), $data2);
						$this->Session->setFlash('CSA modificado correctamente', "mensaje-exito");
						$this->redirect(array('controller' => 'coordinadores','action' => 'verPrepostulacion', $codigo_prepostulacion));			
			}
			else{
						$this->Session->setFlash('Hubo algún problema al cambiar el estado de CSA', "mensaje-error");
						$this->redirect(array('controller' => 'coordinadores','action' => 'verPrepostulacion', $codigo_prepostulacion));			
			}
		}
		
		public function listadoPrepostulacionesClose(){

		}
		//GUARDA EL ESTADO DE LOS ARCHIVOS, VALIDADOS O INVALIDADOS
        public function modificarArchivos($codigo_postulante, $validacion, $id_prepostulacion){            
			$accion                      = $validacion['validacion']['accion'];
            $postulante['Postulante']    = $codigo_postulante['Postulante'];
			$licencia                    = $this->ArchivoPostulante->find('first', array('conditions' => array('ArchivoPostulante.codigo' => 'li-'.$codigo_postulante['Postulante']['codigo'])));
			$id_licencia                 = $licencia['ArchivoPostulante']['id'];
                        $this->ArchivoPostulante->id = $id_licencia;
                        if($accion == 'guardar'){
                            $this->ArchivoPostulante->saveField('valido_new',$validacion['validacion']['licencia']);
                        }
                        else{
                            $this->ArchivoPostulante->saveField('valido',$validacion['validacion']['licencia']);
                            $this->ArchivoPostulante->saveField('valido_new',$validacion['validacion']['licencia']);
                        }

                        
                        $cedula                      = $this->ArchivoPostulante->find('first', array('conditions' => array('ArchivoPostulante.codigo' => 'ci-'.$codigo_postulante['Postulante']['codigo'])));
			$id_cedula                   = $cedula['ArchivoPostulante']['id'];
			$this->ArchivoPostulante->id = $id_cedula;
                        if($accion == 'guardar'){
                            $this->ArchivoPostulante->saveField('valido_new', $validacion['validacion']['cedula']);
                        }
                        else{
                            $this->ArchivoPostulante->saveField('valido', $validacion['validacion']['cedula']);
                            $this->ArchivoPostulante->saveField('valido_new', $validacion['validacion']['cedula']);
                        }
			
                                
                        /* Traigo los archivos anexos de la tabla, para rescatar su codigo, a través del codigo
                        *  Puedo recoger el valor de validación que viene por post de acuerdo a cada archivo
                        */
                        //$arrayPrepostulacion         = $this->Prepostulacion->find('first', array('conditions' => array('Prepostulacion.codigo' => $id_prepostulacion)));
                        //$codigoPrepostulacion        = $arrayPrepostulacion['Prepostulacion']['codigo'];
                        
                        $archivosPrePostulaciones    = $this->ArchivoPrepostulacion->find('all', array('conditions' => array('ArchivoPrepostulacion.prepostulacion_codigo' => $id_prepostulacion)));
                        
                        foreach($archivosPrePostulaciones as $row){
                            
                            $id_archivo_pre                  = $row['ArchivoPrepostulacion']['id'];
                            $this->ArchivoPrepostulacion->id = $id_archivo_pre;
                            $valor                           = $this->request->data['validacion'][$row['ArchivoPrepostulacion']['codigo']];
                            
                            if($accion == 'guardar'){
                                $this->ArchivoPrepostulacion->saveField('valido_new',$valor);
                            }
                            else{
                                $this->ArchivoPrepostulacion->saveField('valido',$valor);
                                $this->ArchivoPrepostulacion->saveField('valido_new',$valor);
                            }
                            
                        }
                        
                        if($accion == 'guardar'){			
				$this->Session->setFlash('Postulación guardada correctamente.', "mensaje-exito");
				$this->redirect(array('controller' => 'Coordinadores','action' => 'verPrepostulacion', $id_prepostulacion ));		
			}
			if($accion == 'invalidar'){
				$codigoPrepostulacion     = $this->Prepostulacion->find('first', array('conditions' => array('Prepostulacion.codigo' => $id_prepostulacion)));
				$codigo_correlativo = $codigoPrepostulacion['Prepostulacion']['id_correlativo'];
                $idPrepostulacion         = $codigoPrepostulacion['Prepostulacion']['id'];
				$this->Prepostulacion->id = $idPrepostulacion;
				$motivos                  = $validacion['validacion']['motivos'];
				$motivos                  = htmlentities($motivos);
                $date = date('Y-M-d H:i:s', time());        
				if($this->Prepostulacion->saveField('motivos', $validacion['validacion']['motivos']) && ($this->Prepostulacion->saveField('modified', $date)) && ($this->Prepostulacion->saveField('revision', 2)) && $this->Prepostulacion->saveField('revision', 1) && $this->Prepostulacion->saveField('ultima_accion', 'revisado')){
					$this->Correo->enviarEmail($postulante, 8,null, null, null, $codigo_correlativo);
					$this->Session->setFlash('Postulación Invalidada correctamente.', "mensaje-exito");
					//echo "<script languaje='javascript' type='text/javascript'>window.close();</script>";
					$this->redirect(array('controller' => 'Coordinadores','action' => 'listadoPrepostulacionesClose'));
				}
				else {
					$this->Session->setFlash('La postulación no se pudo invalidar', "mensaje-error");
					$this->redirect(array('controller' => 'Coordinadores','action' => 'listadoPrepostulacionesClose'));					
				}
			}
			if($accion == 'validar'){				
				$codigoPrepostulacion     = $this->Prepostulacion->find('first', array('conditions' => array('Prepostulacion.codigo' => $id_prepostulacion)));
				$idPrepostulacion         = $codigoPrepostulacion['Prepostulacion']['id'];
				$idCorrelativo            = $codigoPrepostulacion['Prepostulacion']['id_correlativo'];
				$this->Prepostulacion->id = $idPrepostulacion;
				$destino                  = $validacion['validacion']['destino'];				
				
				// ESTADO = 2 REVISADO Y ACEPTADO
				$funcion                  = $this->crearPostulacion($codigoPrepostulacion, $idPrepostulacion, $destino );				
				$codigo_nuevo_postulacion = $funcion[1];   
				$date = date('Y-M-d H:i:s', time());				
                if($this->Prepostulacion->saveField('destino', $destino) && ($this->Prepostulacion->saveField('revision', 2)) && $this->Prepostulacion->saveField('modified', 'SYSDATE') && $this->Prepostulacion->saveField('codigo_postulacion', $codigo_nuevo_postulacion) && $this->Prepostulacion->saveField('ultima_accion', 'revisado')){                                    
									
									if ($destino == 'RAP'){
										$this->Correo->enviarEmail($postulante, 12, null, null, null, $idCorrelativo);
										$this->Correo->enviarEmail($postulante, 9, null, null, null, $idCorrelativo);
									}
									elseif (($destino == 'AH')){
										//obtenemos la escuela a la que pertenece         $carreras         = $this->EscuelaCarrera->find('all', array(
										$escuela = $this->Prepostulacion->find('first', array('conditions' => array('id' => $idPrepostulacion)));
										$escuela_id = $escuela['Prepostulacion']['escuela_id'];										
										$carrera_id = $escuela['Prepostulacion']['carrera_id'];										
										$carreras         = $this->Carrera->find('all', array(
											/* 'joins' => array(
												array(
													'table' => 'RAP_CARRERAS',
													'alias' => 'carreraJoin',
													'type' => 'INNER',
													'conditions' => array(
														'carreraJoin.codigo = EscuelaCarrera.carrera_codigo'
													)
												)
											), */
											'conditions' => array(
												'Carrera.codigo' => $carrera_id
											)
										));
										if (!empty($carreras)){											
											$this->Correo->enviarEmail($postulante, 16,'','',$carreras, $idCorrelativo);											
										}
										else{
											//$this->Correo->enviarEmail($postulante, 9,'','');
										}
									}
									else{ //ADMISIÓN VERTICAL										
										$this->Correo->enviarEmail($postulante, 13, null, null, null, $idCorrelativo);
									}
									$this->Session->setFlash('Postulación VALIDADA correctamente.', "mensaje-exito");
                                    $this->redirect(array('controller' => 'Coordinadores','action' => 'listadoPrepostulacionesClose'));
				}
				else{
                                    $this->Session->setFlash('La Postulación NO se pudo validar', "mensaje-error");
                                    $this->redirect(array('controller' => 'Coordinadores','action' => 'listadoPrepostulacionesClose'));					
				}
			}	
		}


/* 			private function crearPrepostulacion($prepostulacion,$id_prepostulacion, $destino, $cantidad){                    
					
					
			$codigoPrepostulacion  = $prepostulacion['Prepostulacion']['codigo'].$cantidad;
			$codigo_original = $prepostulacion['Prepostulacion']['codigo'];

			$codigoPostulacion  = 'ps';	
			$codigoPostulacion .= uniqid();
			$codigoPostulacion .= rand(10,999);
			                        
			$prepostulacion['Prepostulacion']['id'] = '';
			$prepostulacion['Prepostulacion']['id_correlativo'] = $prepostulacion['Prepostulacion']['id_correlativo'].$destino;
			$prepostulacion['Prepostulacion']['codigo'] = $codigoPrepostulacion;
			$prepostulacion['Prepostulacion']['codigo_postulacion'] = $codigoPostulacion;
			$prepostulacion['Prepostulacion']['destino'] = $destino;
			$prepostulacion['Prepostulacion']['revision'] = '1';			
			$date = date('Y-M-d H:i:s', time());
			$prepostulacion['Prepostulacion']['modified'] =  $date;
			
			// ARCHIVOS PREPOSTULACION 
			$archivos = $this->ArchivoPrepostulacion->find('all', array('conditions' => array('prepostulacion_codigo' => $codigo_original)));			
			foreach ($archivos as $archivo){
				$this->copiarAnexos($codigoPrepostulacion, $archivo);	
			}				
			//FIN ARCHIVOS PREPOSTULACION 
			
			$this->Prepostulacion->create();			
			if($this->Prepostulacion->save($prepostulacion)){							
                            return array(true, $codigoPostulacion);
			}
			else{
                            return false;
			}
		}  */
		
		
		public function crearPostulacion($prepostulacion,$id_prepostulacion, $destino){
                        
			$prepostulacion     = $prepostulacion;
			$codigoPostulacion  = 'ps';	
			$codigoPostulacion .= uniqid();
			$codigoPostulacion .= rand(10,999);
			$postulacion        = array();
                        
			$postulacion['Postulacion']['codigo']            = $codigoPostulacion;
			$postulacion['Postulacion']['postulante_codigo'] = $prepostulacion['Postulante']['codigo'];
			$postulacion['Postulacion']['carrera_codigo']    = $prepostulacion['Carrera']['codigo'];
            $postulacion['Postulacion']['escuela_codigo']    = $prepostulacion['Prepostulacion']['escuela_id'];
            $postulacion['Postulacion']['sede_codigo']       = $prepostulacion['Prepostulacion']['sede_id'];
			//echo var_dump($postulacion['Postulacion']['carrera_codigo']);
			$postulacion['Postulacion']['activo']            = 1;
			$postulacion['Postulacion']['ciudad_codigo']     = $prepostulacion['Ciudad']['codigo'];
			$postulacion['Postulacion']['tipo']              = $destino;
			$postulacion['Postulacion']['id_correlativo']    = $prepostulacion['Prepostulacion']['id_correlativo'];
			$date = date('Y-M-d H:i:s', time());
			$postulacion['Postulacion']['modified']          = $date;
			$postulacion['Postulacion']['created']           = $prepostulacion['Prepostulacion']['created'];
                        
			if(($destino) == 'RAP'){
                            $postulacion['Postulacion']['actividad_laboral']    = 1;
			}
			else {
                            $postulacion['Postulacion']['actividad_laboral']    = 0;
			}
                        
			$postulacion['Postulacion']['licencia_educacion_media'] = 1;
                        
			if($this->Postulacion->save($postulacion)){
                            return array(true, $codigoPostulacion);
			}
			else{
                            return false;
			}
		}
		
		
		/* ESTA FUNCIÓN GUARDA ARCHIVOS NUEVOS PROCEDENTES DE LA PREPOSTULACIÓN CUANDO SE DERIVA A MÁS DE UNA VÍA */
/* 		private function copiarAnexos($codigo_nuevo, $archivo){
			$archivo['ArchivoPrepostulacion']['prepostulacion_codigo'] = $codigo_nuevo;
			$this->ArchivoPrepostulacion->create();
			$this->ArchivoPrepostulacion->save($archivo);
		} */
	}
?>