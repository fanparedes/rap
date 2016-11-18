<?php
App::uses('AppController', 'Controller');
App::uses('CakeEmail', 'Network/Email');
App::uses('File','Utility');

class PrepostulacionesController extends AppController{

    var $name = 'Prepostulaciones';
    var $uses = array(
        'Prepostulacion',
        'AutoEvaluacion',
        'Alerta',
        'UnidadCompetencia',
        'CapacitacionPostulacion',
        'CompetenciaPostulacion',
        'Competencia',
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
        'ArchivoPostulante',
        'Plazo',
        'Entrevista',
        'ArchivoPrePostulacion',
        'Escuela',
        'EscuelaCarrera',
        'SedeCarreraCupo',
		'Correo',
    );
   
    var $layout     = "rap-postulante-2016";
    var $helpers    = array('Form', 'Html', 'format');
    var $components = array('Utilidades','Session','RequestHandler');
    
    function editarPrepostulacion($prepostulacion_codigo = null){
        
        $postulante_codigo   = $this->Session->read('UserLogued.Postulante.codigo');
		$postulante = $this->Postulante->find('first', array('conditions' => array('Postulante.codigo' => $postulante_codigo)));

        $prepostulacion      = $this->Prepostulacion->find('all', array('conditions' => array('Prepostulacion.codigo' => $prepostulacion_codigo)));
        $archivos_anexos     = $this->ArchivoPrePostulacion->find('all', array('conditions' => array('ArchivoPrePostulacion.prepostulacion_codigo' => $prepostulacion_codigo)));
        $archivos_postulante = $this->ArchivoPostulante->find('all', array('conditions' => array('or' => array(
            array('ArchivoPostulante.codigo' => 'li-'.$postulante_codigo),
            array('ArchivoPostulante.codigo' => 'ci-'.$postulante_codigo)
        ))));

        $escuelas            = $this->Escuela->find('all', array(
                'fields'     => array('Escuela.id', 'Escuela.nombre'),
                'conditions' => array('Escuela.activo' => 1)
        ));
        $this->set('escuelas',$escuelas);
        
        /*$carreras = $this->Carrera->find('all', array('conditions' => array('or' => array(
            'AND' => array(
                array('Carrera.codigo' => $prepostulacion[0]['Prepostulacion']['carrera_id']),
                array('Carrera.activo' => 1)
            )
        ))));*/
        $carreras         = $this->EscuelaCarrera->find('all', array(
            'joins' => array(
                array(
                    'table' => 'RAP_CARRERAS',
                    'alias' => 'Carrera',
                    'type' => 'INNER',
                    'conditions' => array(
                        'Carrera.codigo = EscuelaCarrera.carrera_codigo'
                    )
                )
            ),
            'conditions' => array(
                'EscuelaCarrera.escuela_codigo' => $prepostulacion[0]['Prepostulacion']['escuela_id']
            ),
            'fields' => array('Carrera.codigo', 'Carrera.nombre'),
            'order' => 'Carrera.nombre ASC'
        ));
        $this->set('carreras',$carreras);
        
        
        /*$sedes = $this->Sede->find('all', array('conditions' => array('or' => array(
            'AND' => array(
                array('Sede.codigo_sede' => $prepostulacion[0]['Prepostulacion']['sede_id']),
                array('Sede.activo' => 1)
            )
        ))));*/
        $sedes         = $this->SedeCarreraCupo->find('all', array(
            'joins' => array(
                array(
                    'table' => 'RAP_SEDES',
                    'alias' => 'Sede',
                    'type' => 'INNER',
                    'conditions' => array(
                        'Sede.codigo_sede = SedeCarreraCupo.codigo_sede'
                    )
                )
            ),
            'conditions' => array(
                'SedeCarreraCupo.codigo_carrera' => $prepostulacion[0]['Prepostulacion']['carrera_id']
            ),
            'fields' => array('Sede.codigo_sede', 'Sede.nombre_sede'),
            'order' => 'Sede.nombre_sede ASC'
        ));
        $this->set('sedes',$sedes);
        
        $ciudades            = $this->Ciudad->find('all', array(
                'fields'     => array('Ciudad.codigo', 'Ciudad.nombre'),
                'conditions' => array('Ciudad.activo' => 1),
                'order'      => array('Ciudad.nombre ASC'),
        ));
        $this->set('ciudades',$ciudades);
        
        
        $this->set('prepostulacion',$prepostulacion);
        $this->set('archivos_postulante',$archivos_postulante);
        $this->set('archivos_anexos',$archivos_anexos);
        $this->set('postulante_codigo',$postulante_codigo);
        
        
        if(!empty($this->data)){
            
            try{
                $array_msjes_error = array();
                
                // 1 = enviar , 2 = guardar
                $accion            = $this->data['Prepostulacion']['guardado'];
                $ultima_accion     = $this->data['Prepostulacion']['ultima_accion'];
                $prepo             = $this->Prepostulacion->find('first', array('conditions' => array('Prepostulacion.codigo' => $prepostulacion_codigo)));
                $idPrepostulacion  = $prepo['Prepostulacion']['id'];
                $guardado  = $this->request->data['Prepostulacion']['guardado'];

                $prepo             = array(
                    'escuela_id'    => "'".$this->request->data['Prepostulacion']['escuela_id']."'",
                    'carrera_id'    => "'".$this->request->data['Prepostulacion']['carrera_id']."'",
                    'sede_id'       => "'".$this->request->data['Prepostulacion']['sede_id']."'",
                    'ciudad_codigo' => "'".$this->request->data['Prepostulacion']['ciudad_codigo']."'",
                    'guardado'      => "'".$accion."'",
                    'ultima_accion' => "'".$ultima_accion."'"
                );
                $this->Prepostulacion->updateAll($prepo,array('id' => $idPrepostulacion));

                $count             = 0;
                $errores           = 0;

                foreach($this->data['Archivos'] as $row){

                    //____[Si es anexo]____
                    if(isset($row['tipo_archivo']) && $row['tipo_archivo'] == 'anexo' && $row['estado'] == 'por_validar'){

                        if($row['anexo_id'] == 'nuevo'){//Creando uno nuevo...

                                $value_anexo  = $row['valor_anexo_value']; //Posicion array request
                                
                                if($this->request->data['Archivos']['anexo'.$value_anexo.'']){
                                    
                                        $anexo['ArchivoPrePostulacion'] = $this->request->data['Archivos']['anexo'.$value_anexo.''];

                                        $valid_formats                  = array('jpg','jpeg','pdf','doc','docx','png');
                                        $extension                      = $this->obtieneExtension($anexo['ArchivoPrePostulacion']['name']);
                                        $name                           = $anexo['ArchivoPrePostulacion']['name'];
                                        $tamano                         = $anexo['ArchivoPrePostulacion']['size'];
                                        $max_file_size                  = 1024*5000; //5000 kb -> 5mb

                                        if(in_array(pathinfo($name,PATHINFO_EXTENSION),$valid_formats)){

                                            if($tamano < $max_file_size){
                                                
                                                $anexo['ArchivoPrePostulacion']['codigo']                = 'anexo-'.$this->generateRandomString();
                                                $anexo['ArchivoPrePostulacion']['postulante_codigo']     = $postulante_codigo;
                                                $anexo['ArchivoPrePostulacion']['prepostulacion_codigo'] = $prepostulacion_codigo;
                                                $anexo['ArchivoPrePostulacion']['tipo']                  = $row['tipo'];
                                                $anexo['ArchivoPrePostulacion']['nombre']                = $row['nombre'];
                                                $anexo['ArchivoPrePostulacion']['nombre_fisico']         = $anexo['ArchivoPrePostulacion']['name'];
                                                $anexo['ArchivoPrePostulacion']['extension']             = $this->obtieneExtension($anexo['ArchivoPrePostulacion']['name']);
                                                $anexo['ArchivoPrePostulacion']['tamano']                = $anexo['ArchivoPrePostulacion']['size'];
                                                $anexo['ArchivoPrePostulacion']['mimetype']              = $anexo['ArchivoPrePostulacion']['type'];

                                                //Si es enviar [a coordinador], setea también el campo new de estado 
                                                $anexo['ArchivoPrePostulacion']['valido_new'] = null;
                                                $anexo['ArchivoPrePostulacion']['valido']     = null;
                                                
                                                if(($this->subirArchivoAnexo($anexo))){
                                                    if($this->ArchivoPrePostulacion->save($anexo)){
                                                        
                                                    }
                                                    else{
                                                        $msjes_error = 'Error al tratar de guardar el(los) archivo(s)';
                                                        $errores++;
                                                        /*debug($this->ArchivoPrePostulacion->validationErrors); //show validationErrors
                                                        debug($this->ArchivoPrePostulacion->getDataSource()->getLog(false, false));*/
                                                    }
                                                }
                                                else{
                                                    $msjes_error = 'Error al tratar de subir el(los) archivo(s)';
                                                    $errores++;
                                                }
                                
                                            }
                                            else{
                                                $msjes_error = 'Tamaño de archivo(s) excede lo permitido';
                                                $errores++;
                                            }
                                        }
                                        else{
                                            $msjes_error = 'Extensión de archivo(s) incorrecto';
                                            $errores++;
                                        }
                                }
                                else{
                                    $msjes_error = 'Datos de archivo(s) vacíos';
                                    $errores++;
                                }

                        }
                        else{ //Modificando un anexo...
                                $value_anexo                            = $row['valor_anexo_value'];

                                $anexo = $this->ArchivoPrePostulacion->find('first',array('conditions'=>array('id'=>$row['anexo_id'])));
                                
                                if($this->request->data['Archivos']['anexo'.$value_anexo.'']){
                                    
                                        $anexo_archivo['ArchivoPrePostulacion'] = $this->request->data['Archivos']['anexo'.$value_anexo.''];

                                        $valid_formats                  = array('jpg','jpeg','pdf','doc','docx', 'png');
                                        $extension                      = $this->obtieneExtension($row['name']);
                                        $name                           = $row['name'];
                                        $tamano                         = $row['size'];
                                        $max_file_size                  = 1024*5000; //5000 kb -> 5mb

                                        if(in_array(pathinfo($name,PATHINFO_EXTENSION),$valid_formats)){

                                            if($tamano < $max_file_size){
                                                
                                                    $anexo_archivo['ArchivoPrePostulacion']['codigo']    = $anexo['ArchivoPrePostulacion']['codigo'];
                                                    $anexo_archivo['ArchivoPrePostulacion']['extension'] = $this->obtieneExtension($row['name']);

                                                    $anexo = array(
                                                        'nombre'        => "'".$row['nombre']."'",
                                                        'nombre_fisico' => "'".$row['name']."'",
                                                        'extension'     => "'".$this->obtieneExtension($row['name'])."'",
                                                        'tamano'        => "'".$row['size']."'",
                                                        'mimetype'      => "'".$row['type']."'",
                                                        'tipo'          => "'".$row['tipo']."'"
                                                    );

                                                    //Si es enviar [a coordinador], setea también el campo new de estado 
                                                    $anexo['valido_new'] = null;
                                                    $anexo['valido']     = null;


                                                    if(($this->subirArchivoAnexo($anexo_archivo))){
                                                        if($this->ArchivoPrePostulacion->updateAll($anexo,array('id' => $row['anexo_id']))){
                                                            
                                                        }
                                                        else{
                                                            $msjes_error = 'Error al tratar de guardar el(los) archivo(s)';
                                                            $errores++;
                                                            /*debug($this->ArchivoPrePostulacion->validationErrors); //show validationErrors
                                                            debug($this->ArchivoPrePostulacion->getDataSource()->getLog(false, false));*/
                                                        }
                                                        //echo "Update Ok <br>";
                                                    }
                                                    else{
                                                        $msjes_error = 'Error al tratar de subir el(los) archivo(s)';
                                                        /*debug($this->ArchivoPrePostulacion->validationErrors); //show validationErrors
                                                        debug($this->ArchivoPrePostulacion->getDataSource()->getLog(false, false));*/
                                                        $errores++;
                                                    }
                                            }
                                            else{
                                                $msjes_error = 'Extensión de archivo(s) incorrecto';
                                                $errores++;
                                            }
                                        }
                                        else{
                                            $msjes_error = 'Extensión de archivo(s) incorrecto';
                                            $errores++;
                                        }
                                }
                                else{
                                    $msjes_error = 'Datos de archivo(s) vacíos';
                                    $errores++;
                                }
                                
                                
                        }

                    }

                    //____[Si es licencia]____
                    if(isset($row['tipo_archivo']) && $row['tipo_archivo'] == 'licencia' && $row['estado'] == 'por_validar'){

                        $archivo = $this->ArchivoPostulante->find('first',array('conditions'=>array('id'=>$row['id'])));
                        
                        if($this->request->data['Archivos']['licencia']){
                                    
                                $licencia_archivo['ArchivoPostulante'] = $this->request->data['Archivos']['licencia'];

                                $valid_formats                  = array('jpg','jpeg','pdf','doc','docx', 'png');
                                $extension                      = $this->obtieneExtension($row['name']);
                                $name                           = $row['name'];
                                $tamano                         = $row['size'];
                                $max_file_size                  = 1024*5000; //5000 kb -> 5mb

                                if(in_array(pathinfo($name,PATHINFO_EXTENSION),$valid_formats)){

                                    if($tamano < $max_file_size){

                                            $licencia_archivo['ArchivoPostulante']['extension'] = $this->obtieneExtension($row['name']);
                                            $licencia_archivo['ArchivoPostulante']['codigo']    = $archivo['ArchivoPostulante']['codigo'];

                                            $archivo = array(
                                                'nombre'        => "'".$row['name']."'",
                                                'extension'     => "'".$this->obtieneExtension($row['name'])."'",
                                                'tamano'        => "'".$row['size']."'",
                                                'mimetype'      => "'".$row['type']."'"
                                            );

                                            //Si es enviar [a coordinador], setea también el campo new de estado 
                                            $archivo['valido_new'] = null;
                                            $archivo['valido']     = null;


                                            if($this->subirArchivo($licencia_archivo)){
                                                if($this->ArchivoPostulante->updateAll($archivo,array('id' => $row['id']))){

                                                }
                                                else{
                                                    $msjes_error = 'Error al tratar de guardar el(los) archivo(s)';
                                                    $errores++;
                                                    /*debug($this->ArchivoPrePostulacion->validationErrors); //show validationErrors
                                                    debug($this->ArchivoPrePostulacion->getDataSource()->getLog(false, false));*/
                                                }
                                                //echo "Update Ok <br>";
                                            }
                                            else{
                                                $msjes_error = 'Error al tratar de subir el(los) archivo(s)';
                                                /*debug($this->ArchivoPrePostulacion->validationErrors); //show validationErrors
                                                debug($this->ArchivoPrePostulacion->getDataSource()->getLog(false, false));*/
                                                $errores++;
                                            }
                        
                                    }
                                    else{
                                        $msjes_error = 'Tamaño de archivo(s) excede lo permitido';
                                        $errores++;
                                    }
                                }
                                else{
                                    $msjes_error = 'Extensión de archivo(s) incorrecto';
                                    $errores++;
                                }
                        }
                        else{
                            $msjes_error = 'Datos de archivo(s) vacíos';
                            $errores++;
                        }

                        
                    }


                    //____[Si es ci]____
                    if(isset($row['tipo_archivo']) && $row['tipo_archivo'] == 'ci' && $row['estado'] == 'por_validar'){

                        
                        $archivo = $this->ArchivoPostulante->find('first',array('conditions'=>array('id'=>$row['id'])));
                        
                        if($this->request->data['Archivos']['ci']){
                            
                            $ci_archivo['ArchivoPostulante'] = $this->request->data['Archivos']['ci'];

                            $valid_formats                   = array('jpg','jpeg','pdf','doc','docx', 'png');
                            $extension                       = $this->obtieneExtension($row['name']);
                            $name                            = $row['name'];
                            $tamano                          = $row['size'];
                            $max_file_size                   = 1024*5000; //5000 kb -> 5mb

                            if(in_array(pathinfo($name,PATHINFO_EXTENSION),$valid_formats)){

                                if($tamano < $max_file_size){
                                        
                                        $ci_archivo['ArchivoPostulante']['extension'] = $this->obtieneExtension($row['name']);
                                        $ci_archivo['ArchivoPostulante']['codigo']    = $archivo['ArchivoPostulante']['codigo'];

                                        $archivo = array(
                                            'nombre'        => "'".$row['name']."'",
                                            'extension'     => "'".$this->obtieneExtension($row['name'])."'",
                                            'tamano'        => "'".$row['size']."'",
                                            'mimetype'      => "'".$row['type']."'"
                                        );

                                        //Si es enviar [a coordinador], setea también el campo new de estado 
                                        $archivo['valido_new'] = null;
                                        $archivo['valido']     = null;

                                        if($this->subirArchivo($ci_archivo)){
                                            if($this->ArchivoPostulante->updateAll($archivo,array('id' => $row['id']))){
                                                
                                            }
                                            else{
                                                $msjes_error = 'Error al tratar de guardar el(los) archivo(s)';
                                                /*debug($this->ArchivoPrePostulacion->validationErrors); //show validationErrors
                                                debug($this->ArchivoPrePostulacion->getDataSource()->getLog(false, false));*/
                                                $errores++;
                                            }
                                            //echo "Update Ok <br>";
                                        }
                                        else{
                                            $msjes_error = 'Error al tratar de subir el(los) archivo(s)';
                                            /*debug($this->ArchivoPrePostulacion->validationErrors); //show validationErrors
                                            debug($this->ArchivoPrePostulacion->getDataSource()->getLog(false, false));*/
                                            $errores++;
                                        }
                                        
                                }
                                else{
                                    $msjes_error = 'Tamaño de archivo(s) excede lo permitido';
                                    $errores++;
                                }
                            }
                            else{
                                $msjes_error = 'Extensión de archivo(s) incorrecto';
                                $errores++;
                            }
                        }
                        else{
                            $msjes_error = 'Datos de archivo(s) vacíos';
                            $errores++;
                        }

                    }

                    $count++;
                }   

                
                if($errores == 0){	
					if ($guardado == 1){ //Se envian emails cuando el postulante confirma los cambios
						$this->Correo->enviarEmail($postulante, 7);
						$this->Correo->enviarEmail($postulante, 6);
					}				
                    $this->Session->setFlash(__('Prepostulacion Modificada.'), 'mensaje-exito');				
                    $this->redirect(array('controller' => 'home', 'action' => 'postulantes'));				
                }
                else{
                    $this->Session->setFlash(__('No se pudo modificar la prepostulacion: '.$msjes_error), 'mensaje-error');
                    $this->redirect(array('controller' => 'home', 'action' => 'postulantes'));			
                }

            }catch(Exception $e){
                $this->Session->setFlash(__('No se pudo modificar la prepostulacion.'), 'mensaje-error');
                $this->redirect(array('controller' => 'home', 'action' => 'postulantes'));
            }
            
        }//No empty data

    }

    function generateRandomString($length = 15){
        $characters       = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString     = '';
        for ($i = 0; $i < $length; $i++){
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }
        
    
    //MÉTODO EN EL QUE SE CREA LA NUEVA PREPOSTULACIÓN
    function nuevaPrepostulacion($postulacion_codigo = null){

        $postulante_codigo   = $this->Session->read('UserLogued.Postulante.codigo');
		$postulante = $this->Postulante->find('first', array('conditions' => array('Postulante.codigo' => $postulante_codigo)));
        /*
        $carreras            = $this->Carrera->find('all', array(
                'fields'     => array('Carrera.codigo', 'Carrera.nombre'),
                'conditions' => array('carrera.activo' => 1)
        ));
        $this->set('carreras',$carreras);
        */
        $escuelas            = $this->Escuela->find('all', array(
                'fields'     => array('Escuela.id', 'Escuela.nombre'),
                'conditions' => array('Escuela.activo' => 1)
        ));
        $this->set('escuelas',$escuelas);
        $ciudades            = $this->Ciudad->find('all', array(
                'fields'     => array('Ciudad.codigo', 'Ciudad.nombre'),
                'conditions' => array('Ciudad.activo' => 1),
                'order'      => array('Ciudad.nombre ASC'),
        ));
        
        $this->set('ciudades',$ciudades);
        /* OBTENEMOS UN NÚMERO ALEATORIO DE PREPOSTULACION */
        
        
        $licencia_archivo = $this->ArchivoPostulante->find('first', array('conditions' => array('ArchivoPostulante.codigo' => 'li-'.$postulante_codigo)));
        $cedula_archivo   = $this->ArchivoPostulante->find('first', array('conditions' => array('ArchivoPostulante.codigo' => 'ci-'.$postulante_codigo)));
        
        $this->set('licencia_archivo',$licencia_archivo);
        $this->set('cedula_archivo',$cedula_archivo);
        
        if(isset($prepostulacion['Prepostulacion']['codigo'])){
            $prepostulacion_codigo = $prepostulacion['Prepostulacion']['codigo'];
            $archivos_postulante   = $this->ArchivoPostulante->find('all', array('conditions' => array('ArchivoPostulante.codigo_prepostulacion' => $prepostulacion_codigo)));
        
            $this->set('archivos_postulante',$archivos_postulante);
        }
        
        if(!empty($this->data)){

            if(count($this->Session->read('UserLogued')) > 0){

                $codigo_prepostulacion  = 'pp-';
                $unico                  = uniqid();
                $codigo_prepostulacion .= $unico;
                $codigo_prepostulacion .= rand(100,999);
                $codigo_prepostulacion .= rand(100,999);
                $this->request->data['Prepostulacion']['codigo'] = $codigo_prepostulacion;
				$guardado = $this->request->data['Prepostulacion']['guardado'];
                $errores           = 0;
                $count             = 0;

                $msjes_error       = '';
                $archivos          = $this->data['Archivos'];
                $resp_array        = $this->subirDocumentosPostulante($this->request->data['Archivos']['ci'], $this->request->data['Archivos']['licencia'], $this->request->data['Prepostulacion']['postulante_codigo'] , $codigo_prepostulacion);

                if($resp_array[0]){
				
						unset($archivos['licencia']);
						unset($archivos['ci']);
						
						
						foreach($archivos as $row){

								//Si es anexo
								if(isset($row['tipo_archivo']) && $row['tipo_archivo'] == 'anexo'){

									if($count == 0){

										if($this->request->data['Archivos']['anexo']){

												$anexo['ArchivoPrePostulacion'] = $this->request->data['Archivos']['anexo'];

												$valid_formats                  = array('jpg','jpeg','pdf','doc','docx', 'png');
												$extension                      = $this->obtieneExtension($anexo['ArchivoPrePostulacion']['name']);
												$name                           = $anexo['ArchivoPrePostulacion']['name'];
												$tamano                         = $anexo['ArchivoPrePostulacion']['size'];
												$max_file_size                  = 1024*5000; //5000 kb -> 5mb

												if(in_array(pathinfo($name,PATHINFO_EXTENSION),$valid_formats)){

													if($tamano < $max_file_size){

														$anexo['ArchivoPrePostulacion']['codigo']                = 'anexo-'.$this->generateRandomString();
														$anexo['ArchivoPrePostulacion']['postulante_codigo']     = $postulante_codigo;
														$anexo['ArchivoPrePostulacion']['prepostulacion_codigo'] = $codigo_prepostulacion;
														$anexo['ArchivoPrePostulacion']['tipo']                  = $row['tipo'];
														$anexo['ArchivoPrePostulacion']['nombre']                = $row['nombre'];
														$anexo['ArchivoPrePostulacion']['nombre_fisico']         = $anexo['ArchivoPrePostulacion']['name'];
														$anexo['ArchivoPrePostulacion']['extension']             = $this->obtieneExtension($anexo['ArchivoPrePostulacion']['name']);
														$anexo['ArchivoPrePostulacion']['tamano']                = $anexo['ArchivoPrePostulacion']['size'];
														$anexo['ArchivoPrePostulacion']['mimetype']              = $anexo['ArchivoPrePostulacion']['type'];


														if(($this->subirArchivoAnexo($anexo))){
															if($this->ArchivoPrePostulacion->save($anexo)){

															}
															else{
																$msjes_error = 'Error al tratar de guardar el(los) archivo(s)';
																$errores++;
																//debug($this->ArchivoPrePostulacion->validationErrors); //show validationErrors
																//debug($this->ArchivoPrePostulacion->getDataSource()->getLog(false, false));
															}
														}
														else{
															$msjes_error = 'Error al tratar de subir el(los) archivo(s)';
															/* 
															debug($this->ArchivoPrePostulacion->validationErrors); //show validationErrors
															debug($this->ArchivoPrePostulacion->getDataSource()->getLog(false, false));
															*/ 
															$errores++;
														}

													}
													else{
														$msjes_error = 'Tamaño de archivo(s) excede lo permitido';
														$errores++;
													}
												}
												else{
													$msjes_error = 'Extensión de archivo(s) incorrecto';
													$errores++;
												}

										}
										else{
											$msjes_error = 'Datos de archivo(s) vacíos';
											$errores++;
										}

									}
									else{

										if($this->request->data['Archivos']['anexo'.($count+1).'']){

												$anexo['ArchivoPrePostulacion'] = $this->request->data['Archivos']['anexo'.($count+1).''];

												$valid_formats                  = array('jpg','jpeg','pdf','doc','docx','png');
												$extension                      = $this->obtieneExtension($anexo['ArchivoPrePostulacion']['name']);
												$name                           = $anexo['ArchivoPrePostulacion']['name'];
												$tamano                         = $anexo['ArchivoPrePostulacion']['size'];
												$max_file_size                  = 1024*5000; //5000 kb -> 5mb

												if(in_array(pathinfo($name,PATHINFO_EXTENSION),$valid_formats)){

													if($tamano < $max_file_size){
														$anexo['ArchivoPrePostulacion']['codigo']                = 'anexo-'.$this->generateRandomString();
														$anexo['ArchivoPrePostulacion']['postulante_codigo']     = $postulante_codigo;
														$anexo['ArchivoPrePostulacion']['prepostulacion_codigo'] = $codigo_prepostulacion;
														$anexo['ArchivoPrePostulacion']['tipo']                  = $row['tipo'];
														$anexo['ArchivoPrePostulacion']['nombre']                = $row['nombre'];
														$anexo['ArchivoPrePostulacion']['nombre_fisico']         = $anexo['ArchivoPrePostulacion']['name'];
														$anexo['ArchivoPrePostulacion']['extension']             = $this->obtieneExtension($anexo['ArchivoPrePostulacion']['name']);
														$anexo['ArchivoPrePostulacion']['tamano']                = $anexo['ArchivoPrePostulacion']['size'];
														$anexo['ArchivoPrePostulacion']['mimetype']              = $anexo['ArchivoPrePostulacion']['type'];


														if(($this->subirArchivoAnexo($anexo))){
															if($this->ArchivoPrePostulacion->save($anexo)){

															}
															else{
																$msjes_error = 'Error al tratar de guardar el(los) archivo(s)';
																$errores++;
																//debug($this->ArchivoPrePostulacion->validationErrors); //show validationErrors
																//debug($this->ArchivoPrePostulacion->getDataSource()->getLog(false, false));
															}
														}
														else{
															$msjes_error = 'Error al tratar de subir el(los) archivo(s)';
															$errores++;
															/*
															debug($this->ArchivoPrePostulacion->validationErrors); //show validationErrors
															debug($this->ArchivoPrePostulacion->getDataSource()->getLog(false, false));
															*/
														}

													}
													else{
														$msjes_error = 'Tamaño de archivo(s) excede lo permitido';
														$errores++;
													}
												}
												else{
													$msjes_error = 'Extensión de archivo(s) incorrecto';
													$errores++;
												}

										}
										else{
											$msjes_error = 'Datos de archivo(s) vacíos';
											$errores++;
										}

									}
								}

								$count++;
						}

						if($errores == 0){	
						
							$this->Prepostulacion->save($this->request->data);
							
							if ($guardado == 1){ //Se envian emails cuando el postulante confirma los cambios
								$this->Correo->enviarEmail($postulante, 7);
								$this->Correo->enviarEmail($postulante, 6);
							}
							$this->Session->setFlash(__('Prepostulacion Guardada.'), 'mensaje-exito');				
							$this->redirect(array('controller' => 'home', 'action' => 'postulantes'));				
						}
						else{
							$this->Session->setFlash(__('No se pudo guardar la prepostulación: '.$msjes_error), 'mensaje-error');
							$this->redirect(array('controller' => 'home', 'action' => 'postulantes'));			
						}
				}
				else{
						$errores++;
						unset($archivos['licencia']);
						unset($archivos['ci']);

						$msjes_error = $resp_array[1];
						
						$this->Session->setFlash(__('No se pudo guardar la prepostulación: '.$msjes_error), 'mensaje-error');
						$this->redirect(array('controller' => 'home', 'action' => 'postulantes'));	
				}


            }
            else{
                $this->Session->setFlash(__('No se pudo guardar la prepostulación: Debe iniciar sesión'), 'mensaje-error');
                $this->redirect(array('controller' => 'home', 'action' => 'postulantes'));			
            }
            //exit;
        }
		 
    }

	/* 
	ESTE METODO SUBIRÁ LOS DOCUMENTOS DEL POSTULANTE, CARNET DE IDENTIDAD
	Y LICENCIA MEDIA A UNA TABLA LLAMADA RAP_ARCHIVOS_POSTULANTE 
	COMO PARÁMETRO DE ENTRADA SE LE ENVIAN $ci $licencia
	*/
	function subirDocumentosPostulante($ci,$li,$codigo_postulante,$codigo_prepostulacion){
                
                $array_resp    = array();
                $array_resp[0] = true;
                $errores       = 0;
            
                /*__________________ Si Existe _________________ */
                if($ci){
                    $cedula['ArchivoPostulante']                           = $ci;
                }
                else{
					$array_resp[0] = false;
                    $array_resp[1] = 'Datos de archivo(s) vacíos';
                    $errores++;
					return $array_resp;
                }
                /* __ */
                

                if($cedula['ArchivoPostulante']['estado'] == 'por_validar'){
                    
                    $valid_formats = array('jpg','jpeg','pdf','doc','docx','png');
                    $extension     = $this->obtieneExtension($cedula['ArchivoPostulante']['name']);
                    $name          = $cedula['ArchivoPostulante']['name'];
                    $tamano        = $cedula['ArchivoPostulante']['size'];
                    $max_file_size = 1024*5000; //5000 kb -> 5mb
                    
					$variable = false;
					
                    //if(in_array(pathinfo($name,PATHINFO_EXTENSION),$valid_formats)){
					if($variable){
                        
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
										$array_resp[0] = false;
                                        $array_resp[1] = 'Error al tratar de guardar el(los) archivo(s)';
                                        $errores++;
										return $array_resp;
                                        //debug($this->ArchivoPrePostulacion->validationErrors); //show validationErrors
                                        //debug($this->ArchivoPrePostulacion->getDataSource()->getLog(false, false));
                                    }
                                    //echo var_dump('Archivo subido');	
                                }
                                else{
									$array_resp[0] = false;
                                    $array_resp[1] = 'Error al tratar de subir el(los) archivo(s)';
                                    $errores++;
									return $array_resp;
                                }
                            }
                
                        }
                        else{
							$array_resp[0] = false;
                            $array_resp[1] = 'Tamaño de archivo(s) excede lo permitido';
                            $errores++;
							return $array_resp;
                        }
                    }
                    else{
						$array_resp[0] = false;
                        $array_resp[1] = 'Extensión de archivo(s) incorrecto';
                        $errores++;
						return $array_resp;
                    }
                }
                

                /*__________________ Si Existe _________________ */
                if($li){
                    $licencia['ArchivoPostulante']                         = $li;
                }
                else{
					$array_resp[0] = false;
                    $array_resp[1] = 'Datos de archivo(s) vacíos';
                    $errores++;
					return $array_resp;
                }
                /* __ */

                
                if($licencia['ArchivoPostulante']['estado'] == 'por_validar'){
                    
                    $valid_formats = array('jpg','jpeg','pdf','doc','docx','png');
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
										$array_resp[0] = false;
                                        $array_resp[1] = 'Error al tratar de guardar el(los) archivo(s)';
                                        $errores++;
										return $array_resp;
										
                                        //debug($this->ArchivoPrePostulacion->validationErrors); //show validationErrors
                                        //debug($this->ArchivoPrePostulacion->getDataSource()->getLog(false, false));
                                    }
                                    //echo var_dump('Archivo subido');	
                                }
                                else{
									$array_resp[0] = false;
                                    $array_resp[1] = 'Error al tratar de subir el(los) archivo(s)';
                                    $errores++;
									return $array_resp;
                                }
                            }
                
                        }
                        else{
							$array_resp[0] = false;
                            $array_resp[1] = 'Tamaño de archivo(s) excede lo permitido';
                            $errores++;
							return $array_resp;
                        }
                        
                    }
                    else{
						$array_resp[0] = false;
                        $array_resp[1] = 'Extensión de archivo(s) incorrecto';
                        $errores++;
						return $array_resp;
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
	
	
	private function obtieneExtension($archivo){
		$trozos = explode(".", $archivo); 
		$extension = end($trozos); 
		// mostramos la extensión del archivo
		return $extension;
	}
	
	//Esta función subirá el archivo pasado por parámetro de todos los archivos de las prepostulaciones
	private function subirArchivo($archivo){
            
                if(count($this->Session->read('UserLogued')) > 0){
                    $ruta = $this->webroot.'uploads/prepostulaciones';
                    $ruta = WWW_ROOT.'uploads'.DS.'prepostulaciones'.DS.$archivo['ArchivoPostulante']['codigo'].'.'.$archivo['ArchivoPostulante']['extension'];		

                    if(move_uploaded_file($archivo['ArchivoPostulante']['tmp_name'], $ruta)){			
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
        
        private function subirArchivoAnexo($anexo){
            
            if(count($this->Session->read('UserLogued')) > 0){
                $ruta = $this->webroot.'uploads/prepostulaciones';
                $ruta = WWW_ROOT.'uploads'.DS.'prepostulaciones'.DS.$anexo['ArchivoPrePostulacion']['codigo'].'.'.$anexo['ArchivoPrePostulacion']['extension'];		
                if(move_uploaded_file($anexo['ArchivoPrePostulacion']['tmp_name'], $ruta)) {			
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
	

    function cargaDocumentos($codigo_postulacion = null) {
        if ($codigo_postulacion == 'undefined' || empty($codigo_postulacion)) {
            $this->Session->setFlash(__('Error de navegación.'), 'mensaje-error');
            $this->redirect(array('controller' => 'home', 'action' => 'postulantes'));
        }
        $postulacion = $this->Postulacion->datosPostulacion($codigo_postulacion);
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
       
        $documentos = $this->Cargas->find('all', array('conditions' => array('postulacion_codigo' => $codigo_postulacion)));
        $licencia_educacion_media = array();
        $fotocopia_carnet = array();
        $declaracion_renta = array();
        foreach ($documentos as $documento) {
            if ($documento['Cargas']['tipo_archivo_codigo'] == 1) {
                $licencia_educacion_media = $documento;
            } elseif ($documento['Cargas']['tipo_archivo_codigo'] == 2) {
                $fotocopia_carnet = $documento;
            } elseif ($documento['Cargas']['tipo_archivo_codigo'] == 3) {
                $declaracion_renta = $documento;
            }
        }
        $estado = $this->Postulacion->estadoActual($codigo_postulacion);		
        $this->set('codigo_postulacion', $codigo_postulacion);
        $this->set('postulacion', $postulacion);
        $this->set('resumen', $resumen);
        $this->set('licencia_educacion_media', $licencia_educacion_media);
        $this->set('declaracion_renta', $declaracion_renta);
        $this->set('fotocopia_carnet', $fotocopia_carnet);
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
    
    ///para imagenes
    function upload($ruta, $file, $postulante_codigo, $tipo_archivo,$evidencia = null){
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
    
    function get_carreras(){
        
        //asd
        $this->autoRender = false; // We don't render a view in this example
        $this->request->onlyAllow('ajax'); // No direct access via browser URL
        
        $escuela_id       = $this->params['data']['escuela_id'];
        
        $carreras         = $this->EscuelaCarrera->find('all', array(
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
                'EscuelaCarrera.escuela_codigo' => $escuela_id
            ),
            'fields' => array('carreraJoin.codigo', 'carreraJoin.nombre'),
            'order' => 'carreraJoin.nombre ASC'
        ));
        
        $html  = "";
        $html .= "<option selected></option>";
        
        foreach($carreras as $carrera){
            $html .= "<option value='".$carrera['carreraJoin']['codigo']."'>".$carrera['carreraJoin']['nombre']."</option>";
        }
        echo $html;
        exit;
    }
    
    function get_sedes(){
        
        //asd2
        $this->autoRender = false; // We don't render a view in this example
        $this->request->onlyAllow('ajax'); // No direct access via browser URL
        
        $carrera_id    = $this->params['data']['carrera_id'];
        
        $sedes         = $this->SedeCarreraCupo->find('all', array(
            'joins' => array(
                array(
                    'table' => 'RAP_SEDES',
                    'alias' => 'sedesJoin',
                    'type' => 'INNER',
                    'conditions' => array(
                        'sedesJoin.codigo_sede = SedeCarreraCupo.codigo_sede'
                    )
                )
            ),
            'conditions' => array(
                'SedeCarreraCupo.codigo_carrera' => $carrera_id
            ),
            'fields' => array('sedesJoin.codigo_sede', 'sedesJoin.nombre_sede'),
            'order' => 'sedesJoin.nombre_sede ASC'
        ));
        /*
        debug($this->SedeCarreraCupo->validationErrors); //show validationErrors
        debug($this->SedeCarreraCupo->getDataSource()->getLog(false, false));
        exit;
        */
        $html  = "";
        $html .= "<option selected='selected'></option>";
		
        foreach($sedes as $sede){
            $html .= "<option value='".$sede['sedesJoin']['codigo_sede']."'>".$sede['sedesJoin']['nombre_sede']."</option>";
        }
        echo $html;
        exit;
    }

}

/*
// Register handler
set_error_handler("error_handler");
set_exception_handler("error_handler");
register_shutdown_function("error_handler");

function error_handler(){
    
    //header("Location: http://www.google.com");
    
    if($exception instanceof MyException) {
        //echo "you didn't catch a myexception instance\n";
        echo "<h1> Se ha producido un error: Volver a la pagina anterior</h1>";
        exit;

    } else if($exception instanceof Exception) {
        //echo "you didn't catch a exception instance\n";
        echo "<h1> Se ha producido un error: Volver a la pagina anterior</h1>";
        exit;

    } else {
        //echo "uncaught exception of type: ".gettype($exception)."\n";
        echo "<h1> Se ha producido un error: Volver a la pagina anterior</h1>";
        exit;
    }

    //echo "Uncaught exception: " , $exception->getMessage(), "\n";
    echo "<h1> Se ha producido un error: Volver a la pagina anterior</h1>";
    exit;
}
*/
?>