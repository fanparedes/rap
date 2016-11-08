<?php
App::uses('Controller', 'Controller');
App::uses('CakeEmail', 'Network/Email');

class LoginController extends Controller {
	
	public $uses = array('Postulante','LdapAdministrativo', 'Administrativo', 'Correo');
	public $layout  = 'web-home-2016';
	public $components = array('Utilidades','Session');
		
	
	function postulante(){
		//$this->requestAction('/correos/enviar_emails');
		$user = $this->Session->read('UserLogued');
		
		if(!empty($user)){
			if(isset($user['Administrativo'])){
				$this->redirect(array('action'=>'logout'));
			}
		}
		if(!empty($this->data)){
			$nombre_usuario = trim($this->data['Postulante']['nombre_usuario']);
			$password = trim($this->data['Postulante']['pass']);
			if (!empty($nombre_usuario) && !empty($password)) {
				$conexion = $this->Postulante->connect($nombre_usuario,$password);				
				if(!empty($conexion)){					
					if($conexion['Postulante']['activo']==0){						
						$this->Session->setFlash(__('No has activado tu cuenta. Verifica en tu correo el e-mail de registro.'),'mensaje-error');						
						$this->redirect(array('action'=>'postulante'));
					}
					$conexion['Postulante']['prefix'] = 'alumno';
					$this->Session->delete('UserLogued');
					$this->Session->destroy();
					$this->Session->write('UserLogued',$conexion);
					$this->redirect(array('controller'=>'home','action'=> 'postulantes'));
				}else{
					$this->Session->setFlash(__('Error de usuario y/o contraseña.'),'mensaje-error');
					$this->redirect(array('action'=>'postulante'));
				}
				
			}else{
				$this->Session->setFlash(__('Ingrese ambos campos.'),'mensaje-error');
				$this->redirect(array('action'=>'postulante'));
			}
			
		}
	}
	
	function identificar(){
		//$this->requestAction('/correos/enviar_emails');
		if(!empty($this->data)){
			$correo = trim($this->data['Postulante']['correo']);
			if(!empty($correo)){
				$postulante = $this->Postulante->consultaMail($correo);
				if(!empty($postulante)){
			        if ($this->Correo->enviarEmail($postulante,1) === true) {
			        	$this->Session->setFlash(__('Te hemos enviado un e-mail con las instrucciones para que puedas restablecer la contraseña.'),'mensaje-exito');	
			        }else{
			        	$this->Session->setFlash(__('No se ha enviado su petición. Contacte con la mesa de ayuda.'),'mensaje-error');
			        }
				}else{
					$this->Session->setFlash(__('Usted no tiene una cuenta asociada.'),'mensaje-error');
					$this->redirect(array('action'=>'identificar'));
				}
			}else{
				$this->Session->setFlash(__('Ingrese un correo.'),'mensaje-error');
				$this->redirect(array('action'=>'identificar'));
			}
		}
	}
	
	
	function recuperarClave($valor=null)
	{	
		if(!empty($valor)){			
			if(!empty($this->data)){
				$pass= trim($this->data['Postulante']['pass']);
				$pass_confirm = trim($this->data['Postulante']['pass_confirm']);
				if( !empty($pass) && !empty($pass_confirm)){
					if($pass == $pass_confirm){	
						$postulante = $this->Postulante->find('first',array('conditions' => array('Postulante.codigo'=>$valor,'activo'=>1)));
						if(!empty($postulante)){
							$this->Postulante->updateAll(array('password'=>"'".md5($pass)."'"),array('codigo'=>$valor));
							$this->Session->write('UserLogued',$postulante);
							$this->Session->setFlash(__('Ud ha cambiado su contraseña con éxito.'),'mensaje-exito');
							$this->redirect(array('controller'=>'home','action'=>'postulantes'));
						}		
					}else{
						$this->Session->setFlash(__('Ambas contraseñas deben ser iguales.'),'mensaje-error');
					}
				}else{
					$this->Session->setFlash(__('Debe llenar ambos campos.'),'mensaje-error');
				}
			}
		}else{
			$this->Session->setFlash(__('Error de navegación o conexión con el servidor.'),'mensaje-error');
		}
		$this->set('valor',$valor);
	}
	
	
	function logout(){		
		$this->Session->delete('UserLogued');
		$this->Session->destroy();
		//$this->requestAction('/correos/enviar_emails');
		$this->Session->setFlash(__('Sesión Finalizada.'), 'mensaje-exito');
		$this->redirect(array('action'=>'postulante'));
	}
	
	
	function home($cabecera=null){		
		$perfil=$this->Session->read('UserLogued.Administrativo');
		$this->layout='administrativos';
		if (!empty($perfil)){
			$this->loadModel('Permiso');
			$this->loadModel('Funcion');
			if ($perfil['perfil']==0){$conditions=array('Funcion.menu'=>1,
														'AND' => array(array('Funcion.controlador !=' => 'orientadores'),
																		array('Funcion.controlador !=' => 'Evaluadores')));}
			else{
				$permiso=$this->Permiso->find('all',array('conditions'=>array('Permiso.id_perfil'=>$perfil['perfil'],'Permiso.autorizado'=>1)));
				//echo var_dump($permiso);
				$ids=array();
				foreach ($permiso as $p){
					$ids[]=$p['Permiso']['id_funcion'];
				}
				//Estas son las condiciones para que una persona que no es superadministrador se le muestren en el menú
				//$conditions=array('Funcion.id'=>$ids,'Funcion.menu'=>1);
				//$conditions=array('id'=>$ids);
				//echo var_dump($ids);
				foreach ($ids as $id2) {
					$conditions[] = array('Funcion.id' =>  $id2);
					}				
				//echo var_dump($conditions);
				/* $conditions = array('OR' => array(
										array('Funcion.id' => '5'),
										array('Funcion.id' => '6')										
									));
				*/
				$conditions = array('OR' => $conditions, 'AND' => array('Funcion.menu'=>1));
				//echo var_dump($conditions);
				
			}

			$funciones=$this->Funcion->find('all',array('conditions'=>$conditions, 'order'=>array('Funcion.controlador','Funcion.funcion')));					
		
			//echo var_dump($funciones);
			if (empty($funciones) or ($funciones == null)){$funciones=array();}
			$this->set('funciones',$funciones);			
			$this->set('cabecera',$cabecera);
			
		}else{
				$this->redirect(array('action'=>'logout'));}
	
	}
	
	
	
	function administrativo(){
		$this->layout = 'web-home-2016';
		//$this->requestAction('/correos/enviar_emails');
		$user = $this->Session->read('UserLogued');		
		if(!empty($user)){
		if(isset($user['Postulante'])){
				$this->redirect(array('action'=>'logout'));
			}
			if(isset($user['Administrativo'])){
				$this->redirect(array('action'=>'home'));
			}
		}	
		if(!empty($this->data)){
			$nombre_usuario = trim($this->data['Administrativo']['nombre_usuario']);	
			$password = trim($this->data['Administrativo']['pass']);
			if(!empty($nombre_usuario) && !empty($password)) {
				//$ldap_response = $this->LdapAdministrativo->connect($nombre_usuario,$password);
				$ldap_response['status']="success";
				$ldap_response['username']=$nombre_usuario;
				//echo var_dump($ldap_response); die;
				/* AL ENTRAR EN LA APLICACIÓN SE BORRAN LOS USUARIOS NO ACTIVOS EN MÁS DE UN MES */
				$hoy = date('Y-m-d');		
				$nuevafecha = strtotime ( '-1 month' , strtotime ( $hoy ) ) ;
				$nuevafecha = date ( 'Y-m-j' , $nuevafecha );
				$postulantes = $this->Postulante->find('all', array('conditions' => array('Postulante.activo' => '0', 'Postulante.created <' => $nuevafecha)));		
				foreach ($postulantes as $postulante){
					$condicion = array('Postulante.codigo' => $postulante['Postulante']['codigo']);
					$this->Postulante->deleteAll($condicion, false);
				}
				

				if($ldap_response['status'] == 'error'){
					$this->Session->setFlash(__($ldap_response['mensaje']),'mensaje-error');
					$this->redirect(array('action'=>'administrativo'));
				}elseif($ldap_response['status'] == 'success'){
					$administrativo = $this->Administrativo->find('first', array('conditions'=>array('USERNAME'=>$ldap_response['username'])));
					if(empty($administrativo)){
						$this->Session->setFlash(__('Según nuestros registros sus credenciales no se encuentran vigentes.'),'mensaje-error');
						$this->redirect(array('action'=>'administrativo'));
					}else{
						$user['Administrativo'] = $administrativo['Administrativo'];
						$user['Administrativo']['prefix'] = 'administrador';
						$this->Session->delete('UserLogued');
						$this->Session->destroy();
						$this->Session->write('UserLogued',$user);
						$this->redirect( '/administracion/administrativos/home'/*array('controller'=>'login','action'=>'home')*/);
						
					}
				}else{
					$this->Session->setFlash(__('Ha ocurrido un error al intentar acceder a su cuenta, favor contactar a la mesa de ayuda.'),'mensaje-error');
					$this->redirect(array('action'=>'administrativo'));
				}				
			}else{
				$this->Session->setFlash(__('Ingrese ambos campos.'),'mensaje-error');
				$this->redirect(array('action'=>'administrativo'));
			}			
		}		
	}

	function logout_administrativos(){
		$this->Session->delete('UserLogued');
		$this->Session->destroy();
		//$this->requestAction('/correos/enviar_emails');
		$this->Session->setFlash(__('Sesión Finalizada.'),'mensaje-exito');
		$this->redirect(array('controller'=>'login','action'=>'administrativo'));
	}
}
