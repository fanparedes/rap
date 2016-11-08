<?php
	App::uses('AppController', 'Controller');
class PermisosController extends AppController {

	var $name = 'Permisos';
	var $uses=array('Permiso','Perfil','Funcion','Administrativo','Carrera', 'Escuela', 'EscuelaCarrera', 'AdministrativoCarrera');
	var $layout="administrativos-2016";
    var $helpers = array('Form', 'Html', 'Ingenia');
	function beforeFilter(){
		$this->validarAdmin();
	  
	}
	function index() {
		$perfil=$this->Perfil->find('all');
		$this->set('perfiles', $perfil);
	}
	function index_funcion() {
		$funcion=$this->Funcion->find('all', array('order' => 'funcion.controlador'));
		$this->set('funciones', $funcion);
	}
	
	
	//Muestra el listado de usuarios para administrativos, sin superusuarios.
	function index_usuarios() {
		$conditions = array('Administrativo.perfil >' => '0'); 
		$order = array('Administrativo.nombre' => 'ASC');
		$administrativos=$this->Administrativo->find('all', array ('conditions' => $conditions, 'order' => $order));		
		$perfil=$this->Perfil->find('all');
		foreach ($perfil as $p){
			$perfiles[$p['Perfil']['id']]=$p['Perfil']['perfil'];
		}
		$perfiles[0]='SuperAdministrador';
		$this->set('perfiles', $perfiles);
		$this->set('administrativos', $administrativos);
	}
	
	
	//Muestra el listado de superusuarios.
	function index_superusuarios() {
		$conditions = array('Administrativo.perfil' => '0'); 
		$order = array('Administrativo.nombre' => 'ASC');
		$administrativos=$this->Administrativo->find('all', array ('conditions' => $conditions, 'order' => $order));		
		$perfil=$this->Perfil->find('all');
		foreach ($perfil as $p){
			$perfiles[$p['Perfil']['id']]=$p['Perfil']['perfil'];
		}
		$perfiles[0]='SuperAdministrador';
		$this->set('perfiles', $perfiles);
		$this->set('administrativos', $administrativos);
	}
	
	
	function add_funcion() {
		if (!empty($this->data)) {
			$this->Funcion->create();
			$id=$this->Funcion->query('Select max(id) as id_max from RAP_FUNCIONES');
			$id=$id[0][0]['RAP_FUNCIONES'];
			$this->request->data['Funcion']['id']=$id+1;
			if ($this->Funcion->save($this->request->data)) {			  
				$this->Session->setFlash(__('La funcionalidad ha sido guardada correctamente.'),'mensaje-exito');
				$this->redirect(array('action' => 'index_funcion'));
			} else {
				$this->Session->setFlash(__('La funcionalidad no se pudo guardar. Inténtelo más tarde.'),'mensaje-error');				
			}
		}
	}
	function add_usuario() {		
			$perfil=$this->Perfil->find('all');
			foreach ($perfil as $p){
				$perfiles[$p['Perfil']['id']]=$p['Perfil']['perfil'];
			}
			//$perfiles[0]='SuperAdministrador';
			ksort($perfiles);
			$carrera=$this->Carrera->find('all', array('conditions' => array('Carrera.codigo >' => '10000000')));
			foreach ($carrera as $p){
				$carreras[$p['Carrera']['codigo']]=$p['Carrera']['nombre'];
			}
			$escuelas = $this->Escuela->find('list',  array('fields' => array('Escuela.id','UNISTR(Escuela.nombre)'),
															'order' => 'Escuela.nombre asc' 
															));
			$escuelas = $this->Escuela->find('list',  array('fields' => array('Escuela.id','Escuela.nombre'),
															'order' => 'Escuela.nombre asc', 
										));			
			$escuelas_carreras = $this->Carrera->obtenerCarreras();
			
			$this->set('escuelas_carreras', $escuelas_carreras);
			$this->set('escuelas', $escuelas);
			$this->set('perfiles', $perfiles);
			$this->set('carreras', $carreras);	
			if (!empty($this->data)) { //Guardo los datos
				if (!empty($this->request->data['Administrativo']['carreras'])) { $carreras2 = $this->request->data['Administrativo']['carreras']; }
				//COMPROBAMOS EMAIL
				if ($this->Administrativo->compruebaEmail($this->request->data['Administrativo']['email']) == false){						
						unset($this->request->data['Administrativo']['email']);                
						$this->Administrativo->invalidate('email', 'El formato no es correcto.');
						$this->Session->setFlash(__('El formato del email introducido es incorrecto.'),'mensaje-error');
						return;					
				}				
				$this->Administrativo->create();			
				$array=$this->Administrativo->query('Select CODIGO as id_max from RAP_ADMINISTRATIVOS');
				$max = 0;
				/* SI VIENE LA ESCUELA GUARDAMOS EN EL ADMINISTRATIVO LA PRIMERA CARRERA QUE APAREZCA. ESTO HAY QUE MEJORARLO CON TIEMPO */
				if (!empty($this->data['Administrativo']['escuela_id'])){
					if ($this->data['Administrativo']['escuela_id']!== null){
						$escuela_id = $this->data['Administrativo']['escuela_id'];
						$escuelita = $this->EscuelaCarrera->find('first', array('conditions' => array('Escuela_codigo' => $escuela_id)));
						$this->request->data['Administrativo']['carrera_codigo'] = $escuelita['EscuelaCarrera']['carrera_codigo'];
					}
				}
				foreach($array as $id)
				{
					if ($id[0]['RAP_ADMINISTRATIVOS'] > $max){
						$max=$id[0]['RAP_ADMINISTRATIVOS'];
					}
				}
				if (!$this->Administrativo->validaInsert($this->request->data, null)) {		
					return;
				}			
				$this->request->data['Administrativo']['codigo']=(int)$max+1;
				if (($this->request->data['Administrativo']['perfil'] == 2)) {
					$this->request->data['Administrativo']['orientador'] = 1;
				}
				if (!empty($carreras2)){
					if (($this->Administrativo->save($this->request->data)) && ($this->AdministrativoCarrera->guardarCarreras($carreras2,$this->request->data['Administrativo']['codigo'] ))) {
						$this->Session->setFlash(__('El usuario ha sido guardado correctamente.'),'mensaje-exito');
						$this->redirect(array('action' => 'index_usuarios'));
					} else {							
						$this->Session->setFlash(__('No se pudo guardar. Inténtelo otra vez.'),'mensaje-error');	
						$this->redirect(array('action' => 'index_usuarios'));					
					}
				}
				else{
					if (($this->Administrativo->save($this->request->data))) {
						$this->Session->setFlash(__('El usuario ha sido guardado correctamente.'),'mensaje-exito');
						$this->redirect(array('action' => 'index_usuarios'));
					} else {							
						$this->Session->setFlash(__('No se pudo guardar. Inténtelo otra vez.'),'mensaje-error');	
						$this->redirect(array('action' => 'index_usuarios'));					
					}				
				}
		}	
	}
	
	//Función que permite añadir superusuarios
	function add_superusuario() {
			$perfiles[0]='SuperAdministrador';
			ksort($perfiles);
			$carrera=$this->Carrera->find('all');
			foreach ($carrera as $p){
				$carreras[$p['Carrera']['codigo']]=$p['Carrera']['nombre'];
			}
			$this->set('perfiles', $perfiles);
			$this->set('carreras', $carreras);	
			if (!empty($this->data)) {
			$this->Administrativo->create();			
			$array=$this->Administrativo->query('Select CODIGO as id_max from RAP_ADMINISTRATIVOS');
			$max = 0;
			if (!empty($this->data['Administrativo']['escuela_id'])){
					if ($this->data['Administrativo']['escuela_id']!== null){
						$escuela_id = $this->data['Administrativo']['escuela_id'];
						$escuelita = $this->EscuelaCarrera->find('first', array('conditions' => array('Escuela_codigo' => $escuela_id)));
						$this->request->data['Administrativo']['carrera_codigo'] = $escuelita['EscuelaCarrera']['carrera_codigo'];
					}
			}				
			foreach($array as $id)
			{
				if ($id[0]['RAP_ADMINISTRATIVOS'] > $max){
					$max=$id[0]['RAP_ADMINISTRATIVOS'];
				}
			}
            if (!$this->Administrativo->validaInsert($this->request->data, null)) {		
                return;
            }			
			$this->request->data['Administrativo']['codigo']=$max+1;		
			echo var_dump($this->request->data);
			if ($this->Administrativo->save($this->request->data)) {			  
				$this->Session->setFlash(__('El Superadministrador ha sido guardado correctamente.'),'mensaje-exito');
				$this->redirect(array('action' => 'index_usuarios'));
			} else {							
				$this->Session->setFlash(__('No se pudo guardar. Inténtelo otra vez.'),'mensaje-error');		
			}
		}	
	}
	
	
	
	
	function edit_usuario($id = null) {
		if (!$id && empty($this->data)) {
			$this->Session->setFlash('Usuario inválido','alerta','mensaje-error');
			$this->redirect(array('action' => 'index_funcion'));
		}		
		$perfil=$this->Perfil->find('all');
		foreach ($perfil as $p){
			$perfiles[$p['Perfil']['id']]=$p['Perfil']['perfil'];
		}
		//$perfiles[0]='SuperAdministrador';
		ksort($perfiles);
		$carrera=$this->Carrera->find('all', array('conditions' => array('Carrera.codigo >' => '10000000')));
		foreach ($carrera as $p){
			$nombre_carrera = $p['Carrera']['nombre'];
			$carreras[$p['Carrera']['codigo']]=$nombre_carrera;
			//echo var_dump($nombre_carrera);		
		}
		$escuelas = $this->Escuela->find('list',  array('fields' => array('Escuela.id','Escuela.nombre'),
															'order' => 'Escuela.nombre asc' 
										));
										
		$escuelas_carreras = $this->Carrera->obtenerCarreras();		
		$carreras_usuario = $this->AdministrativoCarrera->find('list', array('conditions' => array('AdministrativoCarrera.administrativo_id' => $id), 'fields' => array('id','AdministrativoCarrera.carrera_id' )));
						
		$this->set('carreras_usuario', $carreras_usuario);
		$this->set('escuelas_carreras', $escuelas_carreras);
		$this->set('escuelas', $escuelas);
		$this->set('perfiles', $perfiles);
		$this->set('carreras', $carreras);
		
		//RECIBIENDO LOS DATOS
		if (!empty($this->data)) {	
				if ($this->Administrativo->compruebaEmail($this->request->data['Administrativo']['email']) == false){						
						unset($this->request->data['Administrativo']['email']);                
						$this->Administrativo->invalidate('email', 'El formato no es correcto.');
						$this->Session->setFlash(__('El formato del email introducido es incorrecto.'),'mensaje-error');
						return;					
				}	
				$carreras = '';
				$carreras = $this->request->data['Administrativo']['carreras'];
				if (!$this->Administrativo->validaInsert($this->request->data, 1)) {		
					return;
				}	
				if (($this->request->data['Administrativo']['perfil'] == 2)) {
					$this->request->data['Administrativo']['orientador'] = 1;
				}
				else { $this->request->data['Administrativo']['orientador'] = 0;
				}
				if (!empty($carreras)){
					if (($this->Administrativo->save($this->request->data)) &&  ($this->AdministrativoCarrera->guardarCarreras($carreras, $id))) {					
						$this->Session->setFlash(__('El usuario ha sido guardado correctamente.'),'mensaje-exito');
						$this->redirect(array('action' => 'index_usuarios'));
					} else {
						$this->Session->setFlash(__('No se pudo guardar. Inténtelo otra vez.'), 'mensaje-error');
						$this->redirect(array('action' => 'index_usuarios'));					
					}				
				}
				else{
					if ($this->Administrativo->save($this->request->data)) {					
						$this->Session->setFlash(__('El usuario ha sido guardado correctamente.'),'mensaje-exito');
						$this->redirect(array('action' => 'index_usuarios'));
					} else {
						$this->Session->setFlash(__('No se pudo guardar. Inténtelo otra vez.'), 'mensaje-error');
						$this->redirect(array('action' => 'index_usuarios'));					
					}	
				}
		}
		if (empty($this->data)) {
			$this->data = $this->Administrativo->read(null, $id);
			//Comprobamos si el usuario a editar es un superusuario
			if ($this->data['Administrativo']['perfil'] == 0)  {
				$this->Session->setFlash(__('No tiene suficientes privilegios para editar este perfil.'), 'mensaje-error');
				$this->redirect(array('action' => 'index_usuarios'));
			};
		}		
	}
	
	

	
	
	function edit_superusuario($id = null) {
		if (!$id && empty($this->data)) {
			$this->Session->setFlash('Usuario inválido','alerta','mensaje-error');
			$this->redirect(array('action' => 'index_funcion'));
		}		
		$perfiles[0]='SuperAdministrador';
		ksort($perfiles);
		$carrera=$this->Carrera->find('all');
		foreach ($carrera as $p){
			$nombre_carrera = $p['Carrera']['nombre'];
			$carreras[$p['Carrera']['codigo']]=$nombre_carrera;
			//echo var_dump($nombre_carrera);			
		}
		$this->set('perfiles', $perfiles);
		$this->set('carreras', $carreras);		
		
		if (!empty($this->data)) {			
			if (!$this->Administrativo->validaInsert($this->request->data, 1)) {		
                return;
            }	
			if ($this->Administrativo->save($this->request->data)) {			  
				$this->Session->setFlash(__('El superadministrador ha sido guardado correctamente.'),'mensaje-exito');
				$this->redirect(array('action' => 'index_superusuarios'));
			} else {
				$this->Session->setFlash(__('No se pudo guardar el superadministrador. Inténtelo otra vez.'), 'mensaje-error');				
			}
		}
		if (empty($this->data)) {
			$this->data = $this->Administrativo->read(null, $id);
			//Comprobamos si el usuario a editar es un superusuario
			if ($this->data['Administrativo']['perfil'] <> 0)  {
				$this->Session->setFlash(__('Este usuario no es un superadministrador.'), 'mensaje-error');
				$this->redirect(array('action' => 'index_superusuarios'));
			};
		}		
	}
	
	
	
	
	

	function edit_funcion($id = null) {
		if (!$id && empty($this->data)) {
			$this->Session->setFlash('Función Inválida','mensaje-error');
			$this->redirect(array('action' => 'index_funcion'));
		}
		if (!empty($this->data)) {
			if ($this->Funcion->save($this->data['Funcion'])) {
				$this->Session->setFlash('La Función ha sido guardada','mensaje-exito');
				$this->redirect(array('action' => 'index_funcion'));
			} else {
				$this->Session->setFlash('No se puede Guardar','mensaje-error');
			}
		}
		if (empty($this->data)) {
			$this->data = $this->Funcion->read(null, $id);
		}
		$funcioness=$this->Funcion->find('list');
		$this->set(compact('funciones'));
	}

	function view($id = null) {
		if (!$id) {
			$this->Session->setFlash('No tiene permisos para acceder a este área','mensaje-error');
			$this->redirect(array('action' => 'index'));
		}
		$this->set('permiso', $this->Permiso->read(null, $id));
	}
	
	
	function add() {
		if (!empty($this->data)) {
			$this->Perfil->create();
			
			$idpf=$this->Funcion->query('Select max(id) as id_max from RAP_PERFILES');
			$idpf=$idpf[0][0]['RAP_PERFILES']+1;
			$this->request->data['Perfil']['id']=$idpf;
			$save_error=0;
			if (!$this->Perfil->save($this->request->data['Perfil'])) {
				$save_error=1;
			}else{
			}
			
			$idp=$this->Funcion->query('Select max(id) as id_max from RAP_PERMISOS');
			$idp=$idp[0][0]['RAP_PERMISOS']+1;
			foreach($this->data['Permiso'] as $key=>$permiso){
			$this->Permiso->create();
			$this->request->data['Permiso'][$key]['id']=$idp++;
			$this->request->data['Permiso'][$key]['id_perfil']=$idpf;
				if (!$this->Permiso->save($this->request->data['Permiso'][$key])) {
					$save_error=2;
				}
			}			
			if ($save_error==0){
				$this->Session->setFlash('El tipo de permiso se ha guardado correctamente','mensaje-exito');
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('No se pudo guardar, o se realizó un almacenamiento parcial, verifique el listado y edite o vuelva a ingresar.'), 'alerta', array('class'=>'alert-warning'));
				$this->redirect(array('action' => 'index'));
			}
		}
		
		$funciones =  $this->Funcion->find('all',array('order'=>array('Funcion.controlador','Funcion.funcion')));
		$this->set(compact('funciones'));
	}

	function edit($id = null) {
		if (!$id && empty($this->data)) {
			$this->Session->setFlash('Permiso inválido','alerta',array('class'=>'alert-error'));
			$this->redirect(array('action' => 'index'));
		}
		if (!empty($this->data)) {
			$save_error=0;
			if (!$this->Perfil->save($this->request->data['Perfil'])) {
				$save_error=1;
			}else{
			
			
			$idp=$this->Funcion->query('Select max(id) as id_max from RAP_PERMISOS');
			$idp=$idp[0][0]['RAP_PERMISOS']+1;
			foreach($this->data['Permiso'] as $key=>$permiso){
			if (empty($this->request->data['Permiso'][$key]['id'])){
			$this->Permiso->create();
			$this->request->data['Permiso'][$key]['id']=$idp++;}
			if (!$this->Permiso->save($this->request->data['Permiso'][$key])) {
				$save_error=2;
			}
			}}
			
			if ($save_error==0){
				$this->Session->setFlash('Se ha editado correctamente el perfil','mensaje-exito');
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('No se pudo guardar, o se realizó un almacenamiento parcial, verifique el listado y edite o vuelva a ingresar'), 'alerta', array('class'=>'alert-warning'));	
				$this->redirect(array('action' => 'index'));
			}
		}
		$funciones=$this->Funcion->find('all',array('order'=>array('Funcion.controlador','Funcion.funcion')));
		if (empty($this->data)) {
			$data= $this->Perfil->read(null, $id);
			foreach ($funciones as $key=>$funcion){
				$permiso=$this->Permiso->find('first',array('conditions'=>array('Permiso.id_funcion'=>$funcion['Funcion']['id'],'Permiso.id_perfil'=>$data['Perfil']['id'])));
				if (empty($permiso)){
					$data['Permiso'][$key]=array('id'=>'','id_perfil'=>$id,'id_funcion'=>$funcion['Funcion']['id'],'id_perfil'=>$data['Perfil']['id'],'autorizado'=>0);
				}else{
					$data['Permiso'][$key]=$permiso['Permiso'];
				}
				//sif ($data['Permiso'][$key]['autorizado']==0){$data['Permiso'][$key]['autorizado']=false;}
			}
			$this->data=$data;
		}
		$this->set(compact('funciones'));
	}

	function delete_funcion($id = null) {
		if (!$id) {
			$this->Session->setFlash('Permiso Inválido','mensaje-error');	
			$this->redirect(array('action'=>'index_funcion'));
		}
		if ($this->Funcion->delete($id)) {
			$this->Permiso->deleteAll(array('conditions'=>array('id_funcion'=>$id)));
			$this->Session->setFlash('Función Eliminada','mensaje-exito');
			$this->redirect(array('action'=>'index_funcion'));
		}
		$this->Session->setFlash('La Función no se pudo eliminar','mensaje-error');
		$this->redirect(array('action' => 'index_funcion'));
	}
	
	
	function delete_usuario($id = null) {
		if (!$id) {
			$this->Session->setFlash('Usuario inválido','mensaje-error');
			$this->redirect(array('action'=>'index_usuarios'));
		}
		$usuario = $this->Administrativo->find('first',array('conditions' => array('codigo' => $id)));
		//Comprobamos si el perfil del usuario es un superadministrador
		if($usuario['Administrativo']['perfil'] == 0) {
				$this->Session->setFlash(__('No tiene suficientes privilegios para borrar este perfil.'), 'mensaje-error');
				$this->redirect(array('action' => 'index_usuarios'));
		}
		//Si no es SUPERADMINISTRADOR, se puede proceder a borrar
		if ($this->Administrativo->delete($id)) {
			$this->AdministrativoCarrera->deleteAll(array('AdministrativoCarrera.administrativo_id' => $id), false);
			$this->Session->setFlash('Usuario eliminado correctamente','mensaje-exito');
			$this->redirect(array('action'=>'index_usuarios'));
		}
		else {
			$this->Session->setFlash('El usuario no se pudo eliminar','mensaje-error');
			$this->redirect(array('action' => 'index_usuarios'));
		}
	}
	
	/* FUNCIÓN QUE PERMITIRÁ BORRAR AL SUPERUSUARIO PASADO POR PARÁMETRO */
	
	function delete_superusuario($id = null) {
		if (!$id) {
			$this->Session->setFlash('Usuario inválido','mensaje-error');
			$this->redirect(array('action'=>'index_usuarios'));
		}
		$usuario = $this->Administrativo->find('first',array('conditions' => array('codigo' => $id)));
		//Comprobamos si el perfil del usuario es un superadministrador
		if($usuario['Administrativo']['perfil'] <> 0) {
				$this->Session->setFlash(__('Este usuario no es un superadministrador. Error Fatal.'), 'mensaje-error');
				$this->redirect(array('action' => 'index_superusuarios'));
		}
		//Si es SUPERADMINISTRADOR, se puede proceder a borrar
		if ($this->Administrativo->delete($id)) {
			$this->Session->setFlash('Usuario eliminado correctamente','mensaje-exito');
			$this->redirect(array('action'=>'index_superusuarios'));
		}
		else {
			$this->Session->setFlash('El usuario no se pudo eliminar','mensaje-error');
			$this->redirect(array('action' => 'index_superusuarios'));
		}
	}
	
	
	
	
	
	function delete($id = null) {
		if (!$id) {
			$this->Session->setFlash('Permiso inválido','mensaje-error');
			$this->redirect(array('action'=>'index'));
		}
		if ($this->Perfil->delete($id)) {
			$this->Permiso->deleteAll(array('conditions'=>array('id_perfil'=>$id)));
			$this->Session->setFlash('Se ha borrado el tipo de permiso correctamente','mensaje-exito');
			$this->redirect(array('action'=>'index'));
		}
		$this->Session->setFlash('No se pudo borrar el permiso','mensaje-error');
		$this->redirect(array('action' => 'index'));
	}
}
?>