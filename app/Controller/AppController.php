<?php
/**
 * Application level Controller
 *
 * This file is application-wide controller file. You can put all
 * application-wide controller-related methods here.
 *
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @package       app.Controller
 * @since         CakePHP(tm) v 0.2.9
 * @license       http://www.opensource.org/licenses/mit-license.php MIT License
 */
App::uses('Controller','Controller');
App::uses('Folder','Utility');
App::uses('File','Utility');
/**
 * Application Controller
 *
 * Add your application-wide methods in the class below, your controllers
 * will inherit them.
 *
 * @package		app.Controller
 * @link		http://book.cakephp.org/2.0/en/controllers.html#the-app-controller
 */
class AppController extends Controller{
    
	public $helpers    = array('Session','Html','Form','Text');
	public $components = array('Session','Email','DebugKit.Toolbar','Cookie');
	
	/* 
	ESTA FUNCIÓN VALIDARÁ QUE EL USUARIO QUE SE HA LOGUEADO ES EL MISMO QUE 
	ESTÁ COMPROBANDO LOS DATOS 
	*/
    
	public function validarusuario(){

            $usuario_logueado                 = $this->Session->read('UserLogued');
            
            if(!empty($usuario_logueado) && key($this->Session->read('UserLogued')) == 'Postulante'){
                
                if($this->layout != 'ajax'){
                    
                        $postulante_codigo            = $usuario_logueado['Postulante']['codigo'];

                        $controlador_actual           = $this->params['controller'];
                        $controladores_permitidos     = array('cargas',
                                                        'correos',
                                                        'home',
                                                        'login',
														'entrevistas',
                                                        'masivos',
                                                        'postulaciones',
                                                        'prepostulaciones',
                                                        'web');


                        if(in_array($controlador_actual,$controladores_permitidos)){
                            //$this->Session->setFlash(__('Usuario tiene permiso para estar aca.'), 'mensaje-exito');
                            //return true;

                            if(isset($this->params['pass'][0])){

                                $this->loadModel('Prepostulacion');
                                $this->loadModel('Postulacion');

                                $array_codigos     = array();
                                $parametro_actual  = $this->params['pass'][0];

                                $prepostulaciones  = $this->Prepostulacion->find('all', array('conditions' => array('Prepostulacion.postulante_codigo' => $postulante_codigo)));
                                $postulaciones     = $this->Postulacion->find('all', array('conditions' => array('Postulacion.postulante_codigo' => $postulante_codigo)));

                                foreach($prepostulaciones as $prepos){
                                    array_push($array_codigos,$prepos['Prepostulacion']['codigo']);
                                }
                                foreach($postulaciones as $post){
                                    array_push($array_codigos,$post['Postulacion']['codigo']);
                                }

                                //echo "Actual: ".$parametro_actual;
                                //echo var_dump($array_codigos);

                                if(in_array($parametro_actual,$array_codigos)){
                                    //Nice
                                }
                                else{ //Fuera
                                    $this->redirect(array('controller' => 'login', 'action' => 'logout'));
                                    //echo 2;
                                    //exit;
                                }

                            }


                        }else{
                            //$this->Session->setFlash(__('Usuario tiene permiso para estar aca.'), 'mensaje-exito');
                            $this->redirect(array('controller' => 'login', 'action' => 'logout'));
                        }
                }

            }
            if(!empty($usuario_logueado) && key($this->Session->read('UserLogued')) == 'Administrativo'){
/*                 
                if($this->layout != 'ajax'){
                    
                        $postulante_codigo            = $usuario_logueado['Administrativo']['codigo'];

                        $controlador_actual           = $this->params['controller'];
                        $controladores_permitidos     = array('cargas',
                                                        'correos',
                                                        'home',
                                                        'login',
							'entrevistas',
                                                        'masivos',
                                                        'postulaciones',
                                                        'web',
                                                        'administrativos',
                                                        'alertas',
                                                        'coordinadores',
                                                        'evaluadores',
                                                        'orientadores',
                                                        'pages',
                                                        'permisos',
                                                        'reporteria',
                                                        'sistemas');


                        if(in_array($controlador_actual,$controladores_permitidos)){
                            
                        }
                        else{
                            $this->redirect(array('controller' => 'login', 'action' => 'home'));
                        }
                }
              */   
            }
            
	}
	
	
	public function validarAdmin(){
 		$perfil =$this->Session->read('UserLogued.Administrativo');
		
		
		if (!empty($perfil)){
			if ($perfil['perfil']==0){return;}
			$this->loadModel('Permiso');
			$this->loadModel('Funcion');
			$funcion=$this->request->params['action'];
			
			$controller=strtolower($this->request->params['controller']);
			$funcion_a=$this->Funcion->find('first',array('conditions'=>array('Funcion.controlador'=>$controller,'Funcion.funcion'=>$funcion)));
			
			//No viene del menú de arriba
			if (empty($funcion_a)){
				return;
			}			
			$permiso=$this->Permiso->find('first',array('conditions'=>array('Permiso.id_perfil'=>$perfil['perfil'],'Permiso.autorizado'=>1,'Permiso.id_funcion'=>$funcion_a['Funcion']['id'])));			
			if (!$permiso){
			  $this->Session->setFlash('No tiene privilegios suficientes para acceder a esta sección.','mensaje-error');			
			  $this->redirect(array('controller'=>'administrativos','action'=>'home'));
			}
		}
		else{
			$this->redirect(array('controller'=>'login','action'=>'logout'));
		}
	  
	}
	function beforeFilter(){
            
		//header('Content-Type: text/html; charset=utf8');
		//header('Content-Type: text/html; charset=iso-8859-1');
		Controller::disableCache();
		///MANEJO DE COOKIES
		$datos          = array();
		$datos['url']   = "http://".$_SERVER['HTTP_HOST'].":".$_SERVER['SERVER_PORT'].$_SERVER['REQUEST_URI'];
		
		//$this->Cookie->write('Duoc', $datos,false,2592000);
		
		//
		$public_actions = array(
			'login/administrativo',
			'login/postulante',
			'login/identificar',
			'login/recuperarClave',
			'login/logout',
			'postulaciones/cargaDocumentos',
			'postulaciones/evidenciasprevias',
			'postulaciones/autoEvaluacion',
			'postulaciones/evidenciasfinales',
			'postulaciones/completarPostulacion',
			'postulaciones/CvRap',
			'web/preguntasFrecuentes',
			'web/queEsRap',
			'cargas/descargarGuia',
			'home/postulantes',
			'entrevistas/agendaPostulante'
		);		
		
		//$this->validarusuario();
				
		$admin_actions=array('administracion/administrativos',
							 'permisos/index',
							 'permisos/edit',
							 'permisos/index_funcion',
							 'permisos/edit_funcion',
							 'permisos/add_funcion',
							 'permisos/index_superusuarios',
							 'permisos/edit_superusuario',
							 'permisos/add_superusuario',
							 'permisos/index_usuarios',
							 'permisos/edit_usuario',
							 'permisos/add_usuario',
							 'orientadores/index',
							 'orientadores/listadopostulaciones',
							 'administrativos/buscador',
							 'orientadores/listadopostulaciones',
							 'orientadores/postulaciones',
							 'Evaluadores/listadopostulaciones',
							 'evaluadores/postulaciones',
							 'evaluadores/listadopostulaciones',
							 'administrativos/agenda',
							 'administrativos/horario',
							 'administrativos/orientador',
							 'administrativos/listadoalertas',
							 'administrativos/listadopostulaciones',
							 'administrativos/postulantes',
							 'Administrativos/updateData',
							 'administrativos/home',
							 'administrativos/postulaciones',
							 'login/home',
							 'reporteria/entrevistadores',
							 'reporteria/postulaciones',
							 'administracion/sistemas'
							/* 'sistemas/Index',
							 'sistemas/add_ciudad',
							 'sistemas/edit_ciudad',
							 'sistemas/index_cargo',
							 'sistemas/add_cargo',
							 'sistemas/edit_cargo',
							 'sistemas/Index_carrera',
							 'sistemas/add_carrera',
							 'sistemas/edit_carrera',
							 'sistemas/Index_medios',
							 'sistemas/add_medios',
							 'sistemas/edit_medios',
							 'sistemas/index_modalidad',
							 'sistemas/add_modalidad',
							 'sistemas/edit_modalidad',
							 'sistemas/index_plazos',
							 'sistemas/edit_plazo',
							 'sistemas/Index_sede',
							 'sistemas/add_sede',
							 'sistemas/edit_sede'*/);
		
		///aca comprovar sesion
		
		if($valido = $this->Session->read('validado')){
			$this->set('validado',$valido);
		}
		if($validado_final = $this->Session->read('validado_final')){
			$this->set('validado_final',$validado_final);
		}
	
		$accion = $this->request->params['controller'].'/'.$this->request->params['action'];
	
		$usuario = $this->Session->read('UserLogued.Postulante.prefix');
		$adminsitrador = $this->Session->read('UserLogued.Administrativo.prefix');
		
		
		if ( in_array($accion, $public_actions))
		{
			if (isset($usuario) && !empty($usuario) && $usuario == 'alumno') {
				#CARGAR PERMISOS
				return true;
			}else{				
				//$this->Session->destroy();
				//$this->Session->setFlash(__('Ud. no ha accedido a su cuenta asociada.'),'mensaje-error');
				//$this->redirect(array('controller'=>'login','action'=>'postulante'));
			}
		}
		
		if ( in_array($accion, $admin_actions)) 
		{
			if (isset($adminsitrador) && !empty($adminsitrador) && $adminsitrador == 'administrador'){
				#CARGAR PERMISOS
				return true;
			}else{
				//$this->Session->destroy();
				$this->Session->setFlash(__('Ud. no ha accedido a su cuenta asociada.'),'mensaje-error');
				//$this->redirect(array('controller'=>'login','action'=>'administrativo'));
			}
		}
	}
	


	
	function afterFilter()
	{
		//header('Content-Type: text/html; charset=utf-8');
		//header('Content-Type: text/html; charset=iso-8859-1');
		
                $this->validarusuario();
                
		Controller::disableCache();
		$datos = array();
		$datos['url']		= json_encode("http://".$_SERVER['HTTP_HOST'].":".$_SERVER['SERVER_PORT'].$_SERVER['REQUEST_URI']);
		$this->Cookie->write('Duoc',$datos,false,2592000);
		
		
		$public_actions = array(
			'login/administrativo',
			'login/postulante',
			'login/identificar',
			'login/recuperarClave',
			'login/logout',
			'postulaciones/cargaDocumentos',
			'postulaciones/evidenciasprevias',
			'postulaciones/autoEvaluacion',
			'postulaciones/evidenciasfinales',
			'postulaciones/completarPostulacion',
			'postulaciones/CvRap',
			'web/preguntasFrecuentes',
			'web/queEsRap',
			'cargas/descargarGuia',
			'home/postulantes',
			//'entrevistas/agendaPostulante'
		);
		
		$admin_actions=array('administracion/administrativos',
							 'permisos/index',
							 'permisos/edit',
							 'permisos/index_funcion',
							 'permisos/edit_funcion',
							 'permisos/add_funcion',
							 'permisos/index_superusuarios',
							 'permisos/edit_superusuario',
							 'permisos/add_superusuario',
							 'permisos/index_usuarios',
							 'permisos/edit_usuario',
							 'permisos/add_usuario',
							 'orientadores/index',
							 'orientadores/listadopostulaciones',
							 'administrativos/buscador',
							 'orientadores/listadopostulaciones',
							 'orientadores/postulaciones',
							 'Evaluadores/listadopostulaciones',
							 'evaluadores/postulaciones',
							 'evaluadores/listadopostulaciones',
							 'administrativos/agenda',
							 'administrativos/horario',
							 'administrativos/orientador',
							 'administrativos/listadoalertas',
							 'administrativos/listadopostulaciones',
							 'administrativos/postulantes',
							 'Administrativos/updateData',
							 'administrativos/home',
							 'administrativos/postulaciones',
							 'login/home',
							 'reporteria/entrevistadores',
							 'reporteria/postulaciones',
							 'administracion/sistemas'
							/* 'sistemas/Index',
							 'sistemas/add_ciudad',
							 'sistemas/edit_ciudad',
							 'sistemas/index_cargo',
							 'sistemas/add_cargo',
							 'sistemas/edit_cargo',
							 'sistemas/Index_carrera',
							 'sistemas/add_carrera',
							 'sistemas/edit_carrera',
							 'sistemas/Index_medios',
							 'sistemas/add_medios',
							 'sistemas/edit_medios',
							 'sistemas/index_modalidad',
							 'sistemas/add_modalidad',
							 'sistemas/edit_modalidad',
							 'sistemas/index_plazos',
							 'sistemas/edit_plazo',
							 'sistemas/Index_sede',
							 'sistemas/add_sede',
							 'sistemas/edit_sede'*/);
		
	
		if($valido = $this->Session->read('validado')){
			$this->set('validado',$valido);
		}
		if($validado_final = $this->Session->read('validado_final')){
			$this->set('validado_final',$validado_final);
		}
		$accion = $this->request->params['controller'].'/'.$this->request->params['action'];
		$adminsitrador = $this->Session->read('UserLogued.Administrativo.prefix');
		$usuario = $this->Session->read('UserLogued.Postulante.prefix');
		//echo var_dump($accion);
		
			
/* 		if ( in_array($accion, $public_actions))
		{
			if (isset($usuario) && !empty($usuario) && $usuario == 'alumno') {
				#CARGAR PERMISOS
				return true;
			}else{
				$this->Session->destroy();
				$this->Session->setFlash(__('Ud. no ha accedido bien, a su cuenta asociada.'),'mensaje-error');
				$this->redirect(array('controller'=>'login','action'=>'postulante'));
			}
		} */
		
		if ( in_array($accion, $admin_actions)) 
		{
			if (isset($adminsitrador) && !empty($adminsitrador) && $adminsitrador == 'administrador'){
				#CARGAR PERMISOS
				return true;
			}else{
				//$this->Session->destroy();
				$this->Session->setFlash(__('Ud. no ha accedido a su cuenta asociada.'),'mensaje-error');
				//$this->redirect(array('controller'=>'login','action'=>'administrativo'));
			}
		}
	}
	
}
