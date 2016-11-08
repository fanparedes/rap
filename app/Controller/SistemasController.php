<?php
	App::uses('AppController', 'Controller');
	
	
class SistemasController extends AppController {	
	var $uses = array('Ciudad','Sede','Carrera','TipoCargo','MedioInformacion','Modalidad','SedeCarreraCupo', 'Plazo', 'Csv','Periodo','Correo','Postulante','Postulacion' ,'Escuela', 'EscuelaCarrera');
	var $layout = 'administrativos-2016';
	//var $components = array('carga');
	//var $mapeo = array('CARRERA');
	
	function index(){
		$this->paginate = array('limit'=>50, 'order' => array('nombre' => 'asc'));
		$this->set('ciudades', $this->paginate());	
	}
	
	///funcion que lista las sedes
	
	function index_sede()
	{
		$this->paginate = array('limit'=>20, 'order' => array('nombre_sede' => 'asc'));
		$this->set('sede',$this->paginate('Sede'));
	}
	
	//agregar sede
	function add_sede()
	{
		if(! empty($this->data))
		{
			//$codigos_actuales = $this->Sede->find('all');
			//foreach($codigos_actuales as $codigos)
			//{
			//	if($codigos['Sede']['codigo_sede'] == $this->data['Sistema']['codigo_sede'])
			//	{
			//		$this->Session->setFlash(__('El código del sede ya existe en BD.'),'mensaje-error');
			//		$this->redirect(array('action'=>'add_sede'));
			//	}
			//}
			$nombre = mb_strtoupper($this->data['Sistema']['nombre_sede']);		
			$nombre = $nombre;
			$datos = array('nombre_sede'		=> $nombre,
							'codigo_sede'		=> uniqid());
			$this->Sede->create();
			if($this->Sede->save($datos))
			{
				$this->Session->setFlash(__('Sede creada correctamente'),'mensaje-exito');
				$this->redirect(array('action' => 'index_sede'));
			}
		}
		//$this->Sistema->validaInsert();
	}
	
	//edita las sedes
	function edit_sede($codigo = null)
	{
		if (! $codigo && empty($this->data))
		{
			$this->Session->setFlash('Permiso inválido','alerta',array('class'=>'alert-error'));
			$this->redirect(array('action' => 'index_sede'));
		}
		
		if(! empty($this->data))
		{
			$nombre = mb_strtoupper($this->data['Sistema']['nombre_sede']);		
			$nombre = $nombre;
			if($this->Sede->query("UPDATE RAP_SEDES SET NOMBRE_SEDE = '".$nombre."' WHERE CODIGO_SEDE = '".$this->data['Sistema']['codigo_sede']."'"))
			{
				$this->Session->setFlash(__('Sede actualizada correctamente'),'mensaje-exito');
				$this->redirect(array('action' => 'index_sede'));
			}else{
				$this->Session->setFlash(__('Sede actualizada correctamente'),'mensaje-exito');
				$this->redirect(array('action' => 'index_sede'));
			}
		}
		if(empty($this->data))
		{
			$this->data = $this->Sede->find('first', array('conditions' => array('Sede.codigo_sede' => $codigo)));
		}
		
	}
	
	//elimina sede
	function delete_sede($codigo = null)
	{
		if( ! $codigo )
		{
			$this->Session->setFlash(__('El código no puede ser eliminado de la BD.'),'mensaje-error');
			$this->redirect(array('action'=>'index_sede'));
		}
		$this->Sede->query("DELETE FROM RAP_SEDES WHERE CODIGO_SEDE ='".$codigo."'");
		$this->Session->setFlash(__('Sede eliminada correctamente'),'mensaje-exito');
		$this->redirect(array('action' => 'index_sede'));
	}
	
	
	
	//Función que permite añadir ciudades
	function add_ciudad()
	{	
		
		if(! empty($this->data))
		{
			//$codigos_actuales = $this->Ciudad->find('all');
			//foreach($codigos_actuales as $codigos)
			//{
			//
			//	if($codigos['Ciudad']['codigo'] == $this->data['Sistema']['codigo'])
			//	{
			//		$this->Session->setFlash(__('El código de ciudad ya existe en BD.'),'mensaje-error');
			//		$this->redirect(array('action'=>'add_ciudad'));
			//	}
			//}
			$nombre = mb_strtoupper($this->data['Sistema']['nombre']);
			$nombre = $nombre;				
			$datos = array('nombre'		=> $nombre,
							 'codigo'	=> uniqid());
			$this->Ciudad->create();
			if($this->Ciudad->save($datos))
			{
				$this->Session->setFlash(__('Ciudad creada correctamente'),'mensaje-exito');
				$this->redirect(array('action' => 'index'));
			}
			else {
				$this->Session->setFlash(__('Ciudad creada correctamente'),'mensaje-exito');
				$this->redirect(array('action' => 'index'));			
			}
		}
	}
	
	//edita las ciudades
	function edit_ciudad($codigo = null)
	{
		if (! $codigo && empty($this->data))
		{
			$this->Session->setFlash('Permiso inválido','alerta',array('class'=>'alert-error'));
			$this->redirect(array('action' => 'index'));
		}			
		
		if(! empty($this->data))
		{		
			$nombre = mb_strtoupper($this->data['Sistema']['nombre']);		
			$nombre = $nombre;
			if($this->Sede->query("UPDATE RAP_CIUDADES SET NOMBRE = '".strtoupper($nombre)."' WHERE CODIGO = '".$this->data['Sistema']['codigo']."'"))
			{
				$this->Session->setFlash(__('Ciudad actualizada correctamente'),'mensaje-exito');
				$this->redirect(array('action' => 'index'));
			}else{
				$this->Session->setFlash(__('Sede actualizada correctamente'),'mensaje-exito');
				$this->redirect(array('action' => 'index'));
			}
		}
		if(empty($this->data))
		{
			$this->data = $this->Ciudad->find('first', array('conditions' => array('Ciudad.codigo' => $codigo)));
		}
		
	}
	 //borra las ciudades
	function delete_ciudad($codigo = null)
	{
		$this->redirect(array('action' => 'index'));
		if( ! $codigo )
		{
			$this->Session->setFlash(__('El código no puede ser eliminado de la BD.'),'mensaje-error');
			$this->redirect(array('action'=>'index'));
		}
		$this->Sede->query("DELETE FROM RAP_CIUDADES WHERE CODIGO ='".$codigo."'");
		$this->Session->setFlash(__('Ciudad eliminada correctamente'),'mensaje-exito');
		
	}
	
	
	//DESACTIVAR CIUDADES
	function desactivar_ciudad()
	{		
		$codigo = ($this->data['Sistema']['activo']);
		if($codigo == null)
		{
			$this->Session->setFlash(__('El código no puede ser eliminado de la BD.'),'mensaje-error');
			$this->redirect(array('action'=>'index'));
		}
		$ciudad = $this->Ciudad->find('first', array('conditions' => array('Ciudad.codigo' => $codigo)));
		if (!empty($ciudad)){
			$activado = $ciudad['Ciudad']['activo'];			
			if ($activado == 1){
				$this->Ciudad->query("UPDATE RAP_CIUDADES SET ACTIVO = '0' WHERE CODIGO = '".$codigo."'");					
					//Revisamos que se haya realizado el cambio
					$ciudad = $this->Ciudad->find('first', array('conditions' => array('Ciudad.codigo' => $codigo)));
					$activo = $ciudad['Ciudad']['activo'];
					if ($activo == 0) {
						$this->Session->setFlash(__('Ciudad desactivada correctamente'),'mensaje-exito');
						$this->redirect(array('action' => 'index'));
					}
					else{
						$this->Session->setFlash(__('Error al desactivar la ciudad'),'mensaje-error');
						$this->redirect(array('action' => 'index'));
					}
				}								
			}
			if ($activado == 0){			
				$this->Ciudad->query("UPDATE RAP_CIUDADES SET ACTIVO = '1' WHERE CODIGO = '".$codigo."'");
				$ciudad = $this->Ciudad->find('first', array('conditions' => array('Ciudad.codigo' => $codigo)));
				$activo = $ciudad['Ciudad']['activo'];
				if ($activo == 1) {
						$this->Session->setFlash(__('Ciudad activada correctamente'),'mensaje-exito');
						$this->redirect(array('action' => 'index'));
					}
					else{
						$this->Session->setFlash(__('Error al desactivar la ciudad'),'mensaje-error');
						$this->redirect(array('action' => 'index'));
					}
			}
		}
		
		
	//DESACTIVAR CARGOS
	function desactivar_cargo($codigo = null)
	{		
		
		$codigo = ($this->data['Sistema']['activo']);		
		if($codigo == null)
		{
			$this->Session->setFlash(__('El código no puede ser eliminado de la BD.'),'mensaje-error');
			$this->redirect(array('action'=>'index'));
		}
		$cargo = $this->TipoCargo->find('first', array('conditions' => array('TipoCargo.codigo' => $codigo)));
		
		if (!empty($cargo)){
			$activado = $cargo['TipoCargo']['activo'];			
			if ($activado == 1){
				$this->TipoCargo->query("UPDATE RAP_TIPOS_CARGOS SET ACTIVO = '0' WHERE CODIGO = '".$codigo."'");					
					//Revisamos que se haya realizado el cambio
					$cargo = $this->TipoCargo->find('first', array('conditions' => array('TipoCargo.codigo' => $codigo)));
					$activo = $cargo['TipoCargo']['activo'];
					if ($activo == 0) {
						$this->Session->setFlash(__('Cargo desactivado correctamente'),'mensaje-exito');
						$this->redirect(array('action' => 'index_cargo'));
					}
					else{
						$this->Session->setFlash(__('Error al desactivar el cargo'),'mensaje-error');
						$this->redirect(array('action' => 'index_cargo'));
					}
				}								
			}
			if ($activado == 0){			
				$this->TipoCargo->query("UPDATE RAP_TIPOS_CARGOS SET ACTIVO = '1' WHERE CODIGO = '".$codigo."'");
				//Revisamos que se haya realizado el cambio
				$cargo = $this->TipoCargo->find('first', array('conditions' => array('TipoCargo.codigo' => $codigo)));
				$activo = $cargo['TipoCargo']['activo'];
				if ($activo == 1) {
						$this->Session->setFlash(__('Cargo desactivado correctamente'),'mensaje-exito');
						$this->redirect(array('action' => 'index_cargo'));
					}
					else{
						$this->Session->setFlash(__('Error al desactivar el cargo'),'mensaje-error');
						$this->redirect(array('action' => 'index_cargo'));
					}
			}
		}
				
		
	
		//DESACTIVAR CARRERAS
		function desactivar_carrera($codigo = null){	
			$codigo = ($this->data['Sistema']['activo']);	
			if($codigo == null)
			{
				$this->Session->setFlash(__('El código no puede ser eliminado de la BD.'),'mensaje-error');
				$this->redirect(array('action'=>'index'));
			}
			$carrera = $this->Carrera->find('first', array('conditions' => array('Carrera.codigo' => $codigo)));
			if (!empty($carrera)){
				$activado = $carrera['Carrera']['activo'];			
				if ($activado == 1){
					$this->Carrera->query("UPDATE RAP_CARRERAS SET ACTIVO = '0' WHERE CODIGO = '".$codigo."'");					
						//Revisamos que se haya realizado el cambio
						$carrera = $this->Carrera->find('first', array('conditions' => array('Carrera.codigo' => $codigo)));
						$activo = $carrera['Carrera']['activo'];
						if ($activo == 0) {
							$this->Session->setFlash(__('Carrera desactivada correctamente'),'mensaje-exito');
							$this->redirect(array('action' => 'index_carrera'));
						}
						else{
							$this->Session->setFlash(__('Error al desactivar la carrera'),'mensaje-error');
							$this->redirect(array('action' => 'index_carrera'));
						}
					}								
				}
				if ($activado == 0){			
					$this->Carrera->query("UPDATE RAP_CARRERAS SET ACTIVO = '1' WHERE CODIGO = '".$codigo."'");
					$carrera = $this->Carrera->find('first', array('conditions' => array('Carrera.codigo' => $codigo)));
					$activo = $carrera['Carrera']['activo'];
					if ($activo == 1) {
							$this->Session->setFlash(__('Carrera desactivada correctamente'),'mensaje-exito');
							$this->redirect(array('action' => 'index_carrera'));
						}
						else{
							$this->Session->setFlash(__('Error al desactivar la carrera'),'mensaje-error');
							$this->redirect(array('action' => 'index_carrera'));
						}
				}
		}
	
	
		
		//DESACTIVAR MEDIOS DE COMUNICACIÓN
		function desactivar_medio($codigo = null)
		{		
			$codigo = ($this->data['Sistema']['activo']);	
			if($codigo == null)
			{
				$this->Session->setFlash(__('El código no puede ser eliminado de la BD.'),'mensaje-error');
				$this->redirect(array('action'=>'index'));
			}
			$medio = $this->MedioInformacion->find('first', array('conditions' => array('codigo' => $codigo)));
			if (!empty($medio)){
				$activado = $medio['MedioInformacion']['activo'];			
				if ($activado == 1){
					$this->MedioInformacion->query("UPDATE RAP_MEDIOS_INFORMACION SET ACTIVO = '0' WHERE CODIGO = '".$codigo."'");					
						//Revisamos que se haya realizado el cambio
						$medio = $this->MedioInformacion->find('first', array('conditions' => array('codigo' => $codigo)));
						$activo = $medio['MedioInformacion']['activo'];
						if ($activo == 0) {
							$this->Session->setFlash(__('Medio de información desactivado correctamente'),'mensaje-exito');
							$this->redirect(array('action' => 'index_medios'));
						}
						else{
							$this->Session->setFlash(__('Error al desactivar el medio de información'),'mensaje-error');
							$this->redirect(array('action' => 'index_medios'));
						}
					}								
				}
				if ($activado == 0){			
					$this->MedioInformacion->query("UPDATE RAP_MEDIOS_INFORMACION SET ACTIVO = '1' WHERE CODIGO = '".$codigo."'");
					$medio = $this->MedioInformacion->find('first', array('conditions' => array('codigo' => $codigo)));
					$activo = $medio['MedioInformacion']['activo'];
					if ($activo == 1) {
							$this->Session->setFlash(__('Medio de información desactivado correctamente'),'mensaje-exito');
							$this->redirect(array('action' => 'index_medios'));
						}
						else{
							$this->Session->setFlash(__('Error al desactivar el medio de información'),'mensaje-error');
							$this->redirect(array('action' => 'index_medios'));
						}
				}
		}
				
		
		//DESACTIVAR SEDE
		function desactivar_sede($codigo = null)
		{		
			$codigo = ($this->data['Sistema']['activo']);	
			if($codigo == null)
			{
				$this->Session->setFlash(__('El código no puede ser eliminado de la BD.'),'mensaje-error');
				$this->redirect(array('action'=>'index'));
			}
			$sede = $this->Sede->find('first', array('conditions' => array('codigo_sede' => $codigo)));
			if (!empty($sede)){
				$activado = $sede['Sede']['activo'];			
				if ($activado == 1){
					$this->Sede->query("UPDATE RAP_SEDES SET ACTIVO = '0' WHERE CODIGO_SEDE = '".$codigo."'");					
						//Revisamos que se haya realizado el cambio
						$sede = $this->Sede->find('first', array('conditions' => array('codigo_sede' => $codigo)));
						$activo = $sede['Sede']['activo'];
						if ($activo == 0) {
							$this->Session->setFlash(__('Sede desactivada correctamente'),'mensaje-exito');
							$this->redirect(array('action' => 'index_sede'));
						}
						else{
							$this->Session->setFlash(__('Error al desactivar la sede'),'mensaje-error');
							$this->redirect(array('action' => 'index_sede'));
						}
					}								
				}
				if ($activado == 0){			
					$this->Sede->query("UPDATE RAP_SEDES SET ACTIVO = '1' WHERE CODIGO_SEDE = '".$codigo."'");
					$sede = $this->Sede->find('first', array('conditions' => array('codigo_sede' => $codigo)));
					$activo = $sede['Sede']['activo'];
					if ($activo == 1) {
							$this->Session->setFlash(__('Sede desactivada correctamente'),'mensaje-exito');
							$this->redirect(array('action' => 'index_sede'));
						}
						else{
							$this->Session->setFlash(__('Error al desactivar la sede de información'),'mensaje-error');
							$this->redirect(array('action' => 'index_sede'));
						}
				}
		}	
		
			
		
		//DESACTIVAR MODALIDAD
		function desactivar_modalidad($codigo = null)
		{		
			$codigo = ($this->data['Sistema']['activo']);			
			if($codigo == null)
			{
				$this->Session->setFlash(__('El código no puede ser eliminado de la BD.'),'mensaje-error');
				$this->redirect(array('action'=>'index'));
			}
			$modalidad = $this->Modalidad->find('first', array('conditions' => array('id' => $codigo)));
			if (!empty($modalidad)){
				$activado = $modalidad['Modalidad']['activo'];			
				if ($activado == 1){
					$this->Modalidad->query("UPDATE RAP_MODALIDADES SET ACTIVO = '0' WHERE ID = '".$codigo."'");					
						//Revisamos que se haya realizado el cambio
						$modalidad = $this->Modalidad->find('first', array('conditions' => array('id' => $codigo)));
						$activo = $modalidad['Modalidad']['activo'];
						if ($activo == 0) {
							$this->Session->setFlash(__('Modalidad desactivada correctamente'),'mensaje-exito');
							$this->redirect(array('action' => 'index_modalidad'));
						}
						else{
							$this->Session->setFlash(__('Error al desactivar la modalidad '),'mensaje-error');
							$this->redirect(array('action' => 'index_modalidad'));
						}
					}								
				}
				if ($activado == 0){			
					$this->Modalidad->query("UPDATE RAP_MODALIDADES SET ACTIVO = '1' WHERE ID = '".$codigo."'");
					$modalidad = $this->Modalidad->find('first', array('conditions' => array('id' => $codigo)));
					$activo = $modalidad['Modalidad']['activo'];
					if ($activo == 1) {
							$this->Session->setFlash(__('Modalidad activada correctamente'),'mensaje-exito');
							$this->redirect(array('action' => 'index_modalidad'));
						}
						else{
							$this->Session->setFlash(__('Error al desactivar la modalidad'),'mensaje-error');
							$this->redirect(array('action' => 'index_modalidad'));
						}
				}
		}	
	
	/*Mantenedor de carerras*/
	
	//listar carreras
	function index_carrera()
	{
		$this->paginate = array('limit'=>30, 'order' => array('nombre' => 'asc'));
		$this->set('carreras', $this->paginate('Carrera'));  
	}
	
	
	//edita las carreras
	function edit_carrera($codigo = null)
	{
		$this->response->disableCache();
		if (! $codigo && empty($this->data))
		{
			$this->Session->setFlash(__('Permiso inválido'),'mensaje-error');
			$this->redirect(array('action' => 'index_carrera'));
		}
		
		if(! empty($this->data))
		{
			$carrera_cupo = array();
			
			$nombre = mb_strtoupper($this->data['Sistema']['nombre']);
			//$nombre = $this->data['Sistema']['nombre']
			//$nombre = $nombre;
			
			$this->Carrera->id =$this->data['Sistema']['codigo'];
			$this->Carrera->saveField('nombre',$nombre); 
			$this->Carrera->saveField('modalidad',$this->data['Sistema']['modalidad']);
			
			$this->SedeCarreraCupo->query("DELETE FROM RAP_SEDE_CARRERA_CUPO WHERE  CODIGO_CARRERA = '". $codigo ."'");
			
			foreach($this->data['Sede'] as $key => $sede_carrera_cupo)
			{
				if(isset($this->data['Sede'][$key]['full']) && ! empty($this->data['Sede'][$key]['full']))
				{
					$carrera_cupo[] = array('codigo_sede'		=> $sede_carrera_cupo['codigo_sede'],
											'codigo_carrera'	=> $codigo,
											'cupos_full'		=> $sede_carrera_cupo['full'],
											'modalidad'			=> $this->data['Sistema']['modalidad']);
				}
				if(isset($this->data['Sede'][$key]['codigo']) && ! empty($this->data['Sede'][$key]['codigo']))
				{
					$carrera_cupo[] = array('codigo_sede'			=> $sede_carrera_cupo['codigo'],
											'codigo_carrera'	=> $codigo,
											'cupos_diurno'		=> $sede_carrera_cupo['diurno'],
											'cupos_vespertino'	=> $sede_carrera_cupo['vespertino'],
											'modalidad'			=> $this->data['Sistema']['modalidad']);
				}
			}
			 $this->SedeCarreraCupo->create();
			if($this->SedeCarreraCupo->saveAll($carrera_cupo))
			{
				$this->Session->setFlash(__('Carrera actualizada correctamente'),'mensaje-exito');
				$this->redirect(array('action' => 'index_carrera'));
			}else{
				$this->Session->setFlash(__('Error al actualizar la carrera'),'mensaje-error');
				$this->redirect(array('action' => 'index_carrera'));
			}
				
			
		}
		if(empty($this->data))
		{
			$this->data = $this->Carrera->find('first', array('conditions' => array('Carrera.codigo' => $codigo)));
		}
		
		//Las siguientes matrices realizan interceptan y ordenan las 2 llamadas a base de datos las cuales son "sedes_creadas" y "sedes", es la unica forma de hacerlo puesto que no estan relacionadas
		
		$this->loadModel('Sede');
		
		$this->loadModel('SedeCarreraCupo');
		$sedes_creadas  = $this->SedeCarreraCupo->find('all', array('conditions' => array('SedeCarreraCupo.codigo_carrera' => $codigo)));
		$sede 			= $this->Sede->find('all', array('conditions' => array('Sede.activo' => 1), 'order' => 'nombre_sede ASC'));
		$arreglo = array();
		for($x=0 ; $x < count($sede) ; $x++)
		{
			for($i=0 ; $i < count($sedes_creadas) ; $i++)
			{
				if($sedes_creadas[$i]['SedeCarreraCupo']['codigo_sede'] == $sede[$x]['Sede']['codigo_sede'])
				{
					$arreglo[$x]['Sede']['id']					= $sedes_creadas[$i]['SedeCarreraCupo']['id'];
					$arreglo[$x]['Sede']['nombre_sede']			= $sede[$x]['Sede']['nombre_sede'];
					$arreglo[$x]['Sede']['codigo_sede']			= $sedes_creadas[$i]['SedeCarreraCupo']['codigo_sede'];
					$arreglo[$x]['Sede']['codigo_carrera']		= $sedes_creadas[$i]['SedeCarreraCupo']['codigo_carrera'];
					$arreglo[$x]['Sede']['cupos_diurno']		= $sedes_creadas[$i]['SedeCarreraCupo']['cupos_diurno'];
					$arreglo[$x]['Sede']['cupos_vespertino']	= $sedes_creadas[$i]['SedeCarreraCupo']['cupos_vespertino'];
					$arreglo[$x]['Sede']['cupos_full']			= $sedes_creadas[$i]['SedeCarreraCupo']['cupos_full'];
					$arreglo[$x]['Sede']['modalidad']			= $sedes_creadas[$i]['SedeCarreraCupo']['modalidad'];
				}else{
					$arreglo[$x]['Sede']['nombre_sede']			= $sede[$x]['Sede']['nombre_sede'];
					$arreglo[$x]['Sede']['codigo_sede']			= $sede[$x]['Sede']['codigo_sede'];
				}
			}
		}
		$modalidad = $this->Modalidad->find('list', array('fields' => array('Modalidad.id','Modalidad.nombre')));
		$this->set(compact('modalidad','arreglo','sede'));
		
	}
	
	
	
	 //borra las ciudades
	function delete_carrera($codigo = null)
	{
		if( ! $codigo )
		{
			$this->Session->setFlash(__('El código no puede ser eliminado de la BD.'),'mensaje-error');
			$this->redirect(array('action'=>'index'));
		}
		$this->Sede->query("DELETE FROM RAP_CARRERAS WHERE CODIGO ='".$codigo."'");
		$this->Session->setFlash(__('Carrera eliminada correctamente'),'mensaje-exito');
		$this->redirect(array('action' => 'index_carrera'));
	}
	
//Función que permite añadir carreras
	function add_carrera()
	{	
		$this->response->disableCache();
		if(! empty($this->data))
		{
		
			$nombre = mb_strtoupper($this->data['Sistema']['nombre']);
			//$nombre = $nombre;
			//CALCULA EL MAYOR VALOR, PARA ESTO, BUSCA TODAS LAS CARRERAS , CONVIERTE EL CODIGO A ENTERO Y TRAE EL MAYOR VALOR Y LE SUMA 1
			
			$todos_codigos = $this->Carrera->find('all', array('fields' => array('Carrera.codigo')));
			$entero = array();
			foreach($todos_codigos as $key => $valor)
			{
				$entero[] = (int)$valor['Carrera']['codigo'];
				
			}
			$mayor_valor = max($entero);
			$datos = array('nombre'		=> $nombre,
							'codigo'	=> $mayor_valor+1,
							'modalidad'	=> $this->data['Sistema']['modalidad']);
			
			$this->Carrera->create();
			if($this->Carrera->save($datos))
			{
				$this->loadModel('SedeCarreraCupo');
				$carrera_cupo = array();
				foreach($this->data['Sede'] as $key => $sede_carrera_cupo)
				{
					if(isset($this->data['Sede'][$key]['full']) && ! empty($this->data['Sede'][$key]['full']))
					{
						$carrera_cupo[] = array('codigo_sede'		=> $sede_carrera_cupo['codigo_sede'],
												'codigo_carrera'	=> $datos['codigo'],
												'cupos_full'		=> $sede_carrera_cupo['full'],
												'modalidad'			=> $this->data['Sistema']['modalidad']);
					}
					if(isset($this->data['Sede'][$key]['codigo']) && ! empty($this->data['Sede'][$key]['codigo']))
					{
						$carrera_cupo[] = array('codigo_sede'			=> $sede_carrera_cupo['codigo'],
												'codigo_carrera'	=> $datos['codigo'],
												'cupos_diurno'		=> $sede_carrera_cupo['diurno'],
												'cupos_vespertino'	=> $sede_carrera_cupo['vespertino'],
												'modalidad'			=> $this->data['Sistema']['modalidad']);
					}
				}
				
				$this->SedeCarreraCupo->create();
				$this->SedeCarreraCupo->saveAll($carrera_cupo);
				
				$this->Session->setFlash(__('Carrera creada correctamente'),'mensaje-exito');
				$this->redirect(array('action' => 'index_carrera'));
			}
			else{
				$this->Session->setFlash(__('La Carrera no pudo ser ingresada'),'mensaje-error');
				$this->redirect(array('action' => 'index_carrera'));
			
			}
		}
		$this->loadModel('Sede');
		$sede 			= $this->Sede->find('all', array('conditions' => array('Sede.activo' => 1), 'order' => 'NOMBRE_SEDE ASC'));
		$arreglo = array();
		for($x=0 ; $x < count($sede) ; $x++)
		{
			$arreglo[$x]['Sede']['nombre_sede']			= $sede[$x]['Sede']['nombre_sede'];
			$arreglo[$x]['Sede']['codigo_sede']			= $sede[$x]['Sede']['codigo_sede'];
		}
		$modalidad = $this->Modalidad->find('list', array('conditions' => array('Modalidad.activo' => 1),
														  'fields' => array('Modalidad.id','Modalidad.nombre')));
		$this->set(compact('modalidad','arreglo','sede'));
		
	}
	
   ///Mantenedor de Cargo
   
   function index_cargo()
   {
		$this->paginate = array('limit'=>30, 'order' => array('nombre' => 'asc'));
		$this->set('cargos', $this->paginate('TipoCargo')); 
   }
   
   //Función que permite agregar cargos
	function add_cargo()
	{	
		
		if(! empty($this->data))
		{
			//$codigos_actuales = $this->TipoCargo->find('all');
			//foreach($codigos_actuales as $codigos)
			//{
			//	if($codigos['TipoCargo']['codigo'] == $this->data['Sistema']['codigo'])
			//	{
			//		$this->Session->setFlash(__('El código de cargo ya existe en BD.'),'mensaje-error');
			//		$this->redirect(array('action'=>'add_cargo'));
			//	}
			//}
			$nombre = mb_strtoupper($this->data['Sistema']['nombre']);
			$nombre = $nombre;
			$datos = array('nombre'		=> $nombre,
							 'codigo'	=> uniqid());
			$this->TipoCargo->create();
			if($this->TipoCargo->save($datos))
			{
				$this->Session->setFlash(__('Cargo creado correctamente'),'mensaje-exito');
				$this->redirect(array('action' => 'index_cargo'));
			}
			else {
				$this->Session->setFlash(__('Error agregando el cargo.'),'mensaje-error');
				$this->redirect(array('action' => 'index_cargo'));			
			}
		}
	}
	
	
	//edita los cargos
	function edit_cargo($codigo = null)
	{
		if (! $codigo && empty($this->data))
		{
			$this->Session->setFlash('Permiso inválido','alerta',array('class'=>'alert-error'));
			$this->redirect(array('action' => 'index_cargo'));
		}
		
		if(! empty($this->data))
		{	
			$nombre = mb_strtoupper($this->data['Sistema']['nombre']);
			$nombre = $nombre;	
			if(!$this->Sede->query("UPDATE RAP_TIPOS_CARGOS SET NOMBRE = '".$nombre."' WHERE CODIGO = '".$this->data['Sistema']['codigo']."'"))			
			{
				$this->Session->setFlash(__('Cargo actualizado correctamente'),'mensaje-exito');
				$this->redirect(array('action' => 'index_cargo'));
			}else{				
				$this->Session->setFlash(__('No se pudo actualizar el cargo'),'mensaje-error');
				$this->redirect(array('action' => 'index_cargo'));
			}
		}
		if(empty($this->data))
		{
			$this->data = $this->TipoCargo->find('first', array('conditions' => array('TipoCargo.codigo' => $codigo)));
		}
		
	}
	
	//elimina EL CARGO
	function delete_cargo($codigo = null)
	{
		if( ! $codigo )
		{
			$this->Session->setFlash(__('El código no puede ser eliminado de la BD.'),'mensaje-error');
			$this->redirect(array('action'=>'index_cargo'));
		}
		$this->Sede->query("DELETE FROM RAP_TIPOS_CARGOS WHERE CODIGO ='".$codigo."'");
		$this->Session->setFlash(__('Cargo eliminado correctamente'),'mensaje-exito');
		$this->redirect(array('action' => 'index_cargo'));
	}
	
	
	///Mantenedor de Medios
   
   function index_medios()
   {
		$this->paginate = array('limit'=>20, 'order' => array('nombre' => 'asc'));
		$this->set('medios', $this->paginate('MedioInformacion')); 
   }
   
   //Función que permite agregar cargos
	function add_medios()
	{	
		
		if(! empty($this->data))
		{
			//$codigos_actuales = $this->MedioInformacion->find('all');
			//foreach($codigos_actuales as $codigos)
			//{
			//	if($codigos['MedioInformacion']['codigo'] == $this->data['Sistema']['codigo'])
			//	{
			//		$this->Session->setFlash(__('El código de medios ya existe en BD.'),'mensaje-error');
			//		$this->redirect(array('action'=>'add_medios'));
			//	}
			//}
			$nombre = mb_strtoupper($this->data['Sistema']['nombre']);
			$nombre = $nombre;	
			$datos = array('nombre'=> $nombre,
							'codigo'	=>  uniqid());
			$this->MedioInformacion->create();
			if($this->MedioInformacion->save($datos))
			{
				$this->Session->setFlash(__('Medios creados correctamente'),'mensaje-exito');
				$this->redirect(array('action' => 'index_medios'));
			}
		}
	}
	
	
	//edita los cargos
	function edit_medios($codigo = null)
	{
		if (! $codigo && empty($this->data))
		{
			$this->Session->setFlash('Permiso inválido','alerta',array('class'=>'alert-error'));
			$this->redirect(array('action' => 'index_medios'));
		}
		
		if(! empty($this->data))
		{
			$nombre = mb_strtoupper($this->data['Sistema']['nombre']);
			$nombre = $nombre;	
			if($this->Sede->query("UPDATE RAP_MEDIOS_INFORMACION SET NOMBRE = '".$nombre."' WHERE CODIGO = '".$this->data['Sistema']['codigo']."'"))
			{
				$this->Session->setFlash(__('Medios actualizado correctamente'),'mensaje-exito');
				$this->redirect(array('action' => 'index_medios'));
			}else{
				$this->Session->setFlash(__('Medios actualizado correctamente'),'mensaje-exito');
				$this->redirect(array('action' => 'index_medios'));
			}
		}
		if(empty($this->data))
		{
			$this->data = $this->MedioInformacion->find('first', array('conditions' => array('MedioInformacion.codigo' => $codigo)));
		}
		
	}
	
	//elimina EL CARGO
	function delete_medios($codigo = null)
	{
		if( ! $codigo )
		{
			$this->Session->setFlash(__('El código no puede ser eliminado de la BD.'),'mensaje-error');
			$this->redirect(array('action'=>'index_medios'));
		}
		$this->Sede->query("DELETE FROM RAP_MEDIOS_INFORMACION WHERE CODIGO ='".$codigo."'");
		$this->Session->setFlash(__('Medios eliminado correctamente'),'mensaje-exito');
		$this->redirect(array('action' => 'index_medios'));
	}
	
	
	///Modalidades	
	function index_modalidad()
	{
		
		$modalidad= $this->Modalidad->find('all',array('order' => array('nombre' => 'asc')));
		$this->set(compact('modalidad'));
	}
	
	//agregar sede
	function add_modalidad()
	{
		$this->response->disableCache();
		if(! empty($this->data))
		{
			$datos =  array('nombre' => mb_strtoupper($this->data['Sistema']['nombre']));
			$this->Modalidad->create();
			if($this->Modalidad->save($datos))
			{
				$this->Session->setFlash(__('Modalidad creada correctamente'),'mensaje-exito');
				$this->redirect(array('action' => 'index_modalidad'));
			}		
		}
		//$this->Sistema->validaInsert();
	}
	
	//edita las modalidades
	function edit_modalidad($id = null)
	{
		if (! $id && empty($this->data))
		{
			$this->Session->setFlash('Permiso inválido','alerta',array('class'=>'alert-error'));
			$this->redirect(array('action' => 'index_modalidad'));
		}
		
		if(! empty($this->data))
		{
			
			$nombre = mb_strtoupper($this->data['Sistema']['nombre']);		
			$nombre = $nombre;
			
			 $this->Modalidad->id = $this->data['Sistema']['id'];
			if( $this->Modalidad->saveField('nombre',$nombre))
			{
				$this->Session->setFlash(__('Modalidad actualizada correctamente'),'mensaje-exito');
				$this->redirect(array('action' => 'index_modalidad'));
			}else{
				$this->Session->setFlash(__('No es posible actualizar la modalidad'),'mensaje-error');
				$this->redirect(array('action' => 'index_modalidad'));
			}
		}
		if(empty($this->data))
		{
			$this->data = $this->Modalidad->find('first', array('conditions' => array('Modalidad.id' => $id)));
		}
		
	}
	
	///borra las modalides
	function delete_modalidad($id = null)
	{
		if( ! $id )
		{
			$this->Session->setFlash(__('El código no puede ser eliminado de la BD.'),'mensaje-error');
			$this->redirect(array('action'=>'index_modalidad'));
		}
		$this->Sede->query("DELETE FROM RAP_MODALIDADES WHERE ID ='".$id."'");
		$this->Session->setFlash(__('Modalidad eliminada correctamente'),'mensaje-exito');
		$this->redirect(array('action' => 'index_modalidad'));
	}
	
	
	///PLAZOS ESTABLECIDOSO POR CADA ETAPA DEL PROCESO
	//LISTADO	
	function index_plazos()
	{
		$plazos= $this->Plazo->find('all', array('order' => 'Plazo.etapa_id ASC'));
		$this->set(compact('plazos'));
	}
	
	
	
	//agregar un plazo para la etapa del proceso
	function add_plazo()
	{
		if(!empty($this->data))
		{
			echo var_dump($this->data);
			$etapa = mb_strtoupper($this->data['Plazo']['etapa']);		
			$etapa = $etapa;
			$datos = array('etapa'=> $etapa,
						   'etapa_id'	=>  $this->data['Plazo']['etapa_id'],
						   'plazo'	=>  $this->data['Plazo']['plazo']						   
						   );
			$this->Plazo->create();
			if($this->Plazo->saveAll($datos))
			{
				$this->Session->setFlash(__('Plazo creado correctamente'),'mensaje-exito');
				$this->redirect(array('action' => 'index_plazos'));
			}
			else {
				$this->Session->setFlash(__('Hubo un error al guardar el plazo'),'mensaje-error');
				$this->redirect(array('action' => 'index_sede'));			
			}
		}
	}
	
	
	//agregar un plazo para la etapa del proceso
	function edit_plazo($id = null)
	{	
		if (!$id && empty($this->data))
		{
			$this->Session->setFlash('Permiso inválido','alerta',array('class'=>'alert-error'));
			$this->redirect(array('action' => 'index_sedes'));
		}	
		if(!empty($this->data))
		{
			echo var_dump($this->data);
			$etapa = mb_strtoupper($this->data['Plazo']['etapa']);		
			$etapa = $etapa;
			$datos = array( 'id' => $id,
							'etapa'=> $etapa,
						   'etapa_id'	=>  $this->data['Plazo']['etapa_id'],
						   'plazo'	=>  $this->data['Plazo']['plazo']						   
						   );
			if( $this->Plazo->save($datos))
			{
				$this->Session->setFlash(__('Plazo creado correctamente'),'mensaje-exito');
				$this->redirect(array('action' => 'index_plazos'));
			}
			else {
				$this->Session->setFlash(__('Hubo un error al guardar el plazo'),'mensaje-error');
				$this->redirect(array('action' => 'index_plazos'));			
			}
		}
		if(empty($this->data)){
			$this->data = $this->Plazo->find('first', array('conditions' => array('Plazo.id' => $id)));
		}
	}
	
	
	///borra el plazo
	function delete_plazo($id = null)
	{
		if( ! $id )
		{
			$this->Session->setFlash(__('El código no puede ser eliminado de la BD.'),'mensaje-error');
			$this->redirect(array('action'=>'index_plazos'));
		}
		if ($this->Plazo->delete($id)) {
			$this->Session->setFlash(__('Plazo eliminado correctamente'),'mensaje-exito');
			$this->redirect(array('action' => 'index_plazos'));
		}
		else {
			$this->Session->setFlash(__('Hubo un error al borrar el plazo'),'mensaje-error');
			$this->redirect(array('action' => 'index_plazos'));
		}
	}
	
	//ESTE MÉTODO PERMITE CARGAR UN ARCHIVO CSV PARA CARGAR LAS COMPETENCIAS
	function cargar_csv()	{
		$this->LoadModel('UnidadCompetencia');
		$datos = $this->data;
		if (!empty($this->data)){
			$file1 = $datos['Archivo']['name'];	
			$extension1 = end(explode(".", $datos['Archivo']['name']));
			if($extension1 !== 'csv' ){
				$this->Session->setFlash('El archivo ingresado no es un CSV.', "mensaje-error");
				$this->redirect(array('controller'=> 'sistemas', 'action' => 'cargar_csv'));
			}
			if($file1 == '' ){
				$this->Session->setFlash('El archivo está vacío', "mensaje-error");
				$this->redirect(array('controller'=> 'sistemas', 'action' => 'cargar_csv'));
			}
			$errores = $this->Csv->upload($datos['Archivo']);
			
			$errores_agrupado = array_unique($errores);
			$error_reverso = array_reverse($errores_agrupado);
			$this->set('errores',$error_reverso);			
		}		
	}

	/* ESTA FUNCIÓN MOSTRARÁ EL PERIODO DE POSTULACION. */
	function periodopostulacion() {
		$periodo = $this->Periodo->find('first');	
		$this->set(compact('periodo'));			
			if (!empty($this->data)){	
				$fecha_inicio = $this->data['Periodo']['fecha_inicio'];
				$dia1 = substr($fecha_inicio, 0, 2);
				$mes1   = substr($fecha_inicio, 3, 2);
				$ano1 = substr($fecha_inicio, -4);
				$fecha1 = $dia1 . '-' . $mes1 . '-' . $ano1;
				$fecha_fin = $this->data['Periodo']['fecha_fin'];
				$dia2 = substr($fecha_fin, 0, 2);
				$mes2   = substr($fecha_fin, 3, 2);
				$ano2 = substr($fecha_fin, -4);
				$fecha2 = $dia2 . '-' . $mes2 . '-' . $ano2;				
				$periodo = array(
					'nombre' => $this->data['Periodo']['nombre'],
					'fecha_inicio' => $fecha1,
					'fecha_fin' => $fecha2				
				);			
				if ($this->Periodo->save($periodo)){	
						$this->correosperiodo();
						$this->Session->setFlash(__('Exito al guardar el periodo de postulación.'),'mensaje-exito');	
						$this->redirect(array('action'=>'periodopostulacion'));			
				}
				else {
						$this->Session->setFlash(__('Exito al guardar el periodo de postulación.'),'mensaje-exito');	
						$this->redirect(array('action'=>'periodopostulacion'));				
				}
				
				
				$this->redirect(array('action'=>'periodopostulacion'));					
			}
				
	}
	
	function eliminarperiodo (){
		/* EN PRIMER LUGAR SE ELIMINAN LOS PERIODOS */
		$this->loadmodel('Correo');		
		$condition = array('Correo.etapa' => 10);	
		$this->Correo->deleteAll($condition,false);
		if ($this->Periodo->query('TRUNCATE TABLE RAP_PERIODOS_POSTULACION')) {					
				$this->redirect(array('action'=>'periodopostulacion'));					
				$this->Session->setFlash(__('Exito al borrar el periodo de postulación.'),'mensaje-exito');				
		}
		else {
			$this->redirect(array('action'=>'periodopostulacion'));			
			$this->Session->setFlash(__('Exito al borrar el periodo de postulación.'),'mensaje-exito');			
		}
		
			
	}
	
	/*ESTE MÉTODO BUSCARÁ LOS POSTULANTES ACTIVADOS ENTRE EL PROCESO DE POSTULACION Y LES MANDARÁ UN EMAIL EL DÍA QUE CULMINE */
	private function correosperiodo(){
		/* BUSCAMOS EN PRIMER LUGAR LOS POSTULANTES ACTIVOS EN EL PERIODO */
		$periodo = $this->Periodo->find('first');		
		$fecha_fin = $periodo['Periodo']['fecha_fin'];
		$fecha_inicio = $periodo['Periodo']['fecha_inicio'];
		/* POSTULANTES QUE ACTIVARON SU CUENTA DENTRO DEL PERIODO DE POSTULACION */
		$postulantes = $this->Postulante->find('all', array('conditions' => array('Postulante.fecha_activado <=' => $fecha_fin,
																				  'Postulante.fecha_activado >=' => $fecha_inicio
																				  )));
		$this->loadmodel('Correo');		
		foreach ($postulantes as $postulante) {			
			$postulacion = $this->Postulacion->find('first', array('conditions' => array('Postulacion.postulante_codigo' => $postulante['Postulante']['codigo'])));
			if ((isset($postulacion)) AND ($postulacion != null)){
				$codigo_postulacion = $postulacion['Postulacion']['codigo'];
				$email = array('codigo_postulacion' => $codigo_postulacion,
								'etapa' => '10',
								'fecha_envio' => $fecha_fin,
								'estado' => 'PENDIENTE',
								'intentos' => 0
				);
				$this->Correo->saveAll($email);
			}			
		}
		return true;
	}	


	/* MÉTODO PARA AGREGAR ESCUELAS AL SISTEMA */
	/* EL MODELO ORIGINAL DE LA APLICACIÓN COMPLICA LA EJECUCIÓN DE UN MODELADO MEJOR QUE EL ACTUAL */
		//agregar sede
	function crearEscuela()	{
		/* Montamos el array para las carreras */
		$carreras = $this->Carrera->find('all');
		$carreraSede = $this->SedeCarreraCupo->find('all');
		//echo var_dump($carreraSede);
		foreach ($carreras as $k =>$carrera){
			$modalidad_id = $carrera['Carrera']['modalidad'];
			$modalidad = $this->Modalidad->find('first', array('conditions' => array('Modalidad.id' => $modalidad_id)));			
			unset($carreras[$k]['modalidad']);	
			$carreras[$k]['Modalidad'] = $modalidad['Modalidad'];
			$carrera_id = $carrera['Carrera']['codigo'];
			$sedesCarreras = $this->SedeCarreraCupo->find('all', array('conditions' => array('codigo_carrera' => $carrera_id)));
			foreach ($sedesCarreras as $l=> $sedeCarrera){
				$codigo_sede = $sedeCarrera['SedeCarreraCupo']['codigo_sede'];
				$sede = $this->Sede->find('first', array('conditions' => array('Sede.codigo_sede' => $codigo_sede)));
				$carreras[$k]['Sedes'][$l] = $sede['Sede'];			
			}
		}		
		$this->set('carreras',$carreras);
		if(! empty($this->request->data)){			
			$escuela = $this->request->data;
			$nombre = mb_strtoupper($escuela['Escuela']['nombre']);	
			$escuela['Escuela']['nombre'] = $nombre;
			$escuela['Escuela']['activo'] = 1;		
			$this->Escuela->create();
			if($this->Escuela->save($escuela)){
				if ($this->guardaEscuelasCarreras($this->Escuela->getInsertID(), $escuela['EscuelaCarrera']));
					$this->Session->setFlash(__('Escuela creada correctamente'),'mensaje-exito');
					$this->redirect(array('action' => 'listadoEscuelas'));
			}
			else{
				$this->Session->setFlash(__('Error al crear la Escuela'),'mensaje-error');
			}
		}		
	}

	/* EDITAR LA ESCUELA PASADA POR PARÁMETRO */
	function editarEscuela($codigo_escuela)	{
		/* Montamos el array para las carreras */
		$carreras = $this->Carrera->find('all');
		$carreraSede = $this->SedeCarreraCupo->find('all');
		//echo var_dump($carreraSede);
		foreach ($carreras as $k =>$carrera){
			$modalidad_id = $carrera['Carrera']['modalidad'];
			$modalidad = $this->Modalidad->find('first', array('conditions' => array('Modalidad.id' => $modalidad_id)));			
			unset($carreras[$k]['modalidad']);	
			$carreras[$k]['Modalidad'] = $modalidad['Modalidad'];
			$carrera_id = $carrera['Carrera']['codigo'];
			$sedesCarreras = $this->SedeCarreraCupo->find('all', array('conditions' => array('codigo_carrera' => $carrera_id)));
			foreach ($sedesCarreras as $l=> $sedeCarrera){
				$codigo_sede = $sedeCarrera['SedeCarreraCupo']['codigo_sede'];
				$sede = $this->Sede->find('first', array('conditions' => array('Sede.codigo_sede' => $codigo_sede)));
				$carreras[$k]['Sedes'][$l] = $sede['Sede'];			
			}
			$carrera_en_escuela   	       = $this->EscuelaCarrera->find('first',array('conditions' => array('EscuelaCarrera.carrera_codigo' => $carrera['Carrera']['codigo'])));
			
			if(count($carrera_en_escuela)>0){//Utilizado
				$carreras[$k]['Carrera']['utilizado'] = 2;
			}
			else{//Libre
				$carreras[$k]['Carrera']['utilizado'] = 1;
			}
		}
		$escuela = $this->Escuela->find('first', array('conditions' => array('Escuela.id' => $codigo_escuela)));
		$carrerasEscuelas = $this->EscuelaCarrera->find('all',  array('conditions' => array('EscuelaCarrera.escuela_codigo' => $codigo_escuela)));
		$carreras_elegidas = array();
		foreach ($carrerasEscuelas as $carreraEscuela){
			array_push($carreras_elegidas,$carreraEscuela['EscuelaCarrera']['carrera_codigo']);
		}
		$this->set('carrerasEscuela',$carreras_elegidas);
		$this->set('escuela',$escuela);
		$this->set('carreras',$carreras);
		if(!empty($this->request->data)){			
			$escuela = $this->request->data;
			$nombre = mb_strtoupper($escuela['Escuela']['nombre']);	
			$escuela['Escuela']['nombre'] = $nombre;
			$escuela['Escuela']['activo'] = 1;
			$this->Escuela->id = $codigo_escuela;
			if($this->Escuela->save($escuela)){
				if ($this->guardaEscuelasCarreras($codigo_escuela, $escuela['EscuelaCarrera']));
					$this->Session->setFlash(__('Escuela MODIFICADA correctamente'),'mensaje-exito');
					$this->redirect(array('action' => 'listadoEscuelas'));
			}
			else{
				$this->Session->setFlash(__('Error al EDITAR la Escuela'),'mensaje-error');
			}
		}		
	}
	
	
	
	
	
	/* MÉTODO QUE GUARDARÁ LOS DATOS DE ESCUELA_CARRERA */
	private function guardaEscuelasCarreras($Escuela = null, $EscuelasCarreras){
		if ($Escuela == null){
			return false;
		}
		foreach ($EscuelasCarreras as $k => $EscuelaCarrera){			
			$datos = array();
			if ($EscuelaCarrera == 1){ //Guardar				
				/* COMPROBAMOS SI ESTÁ EN LA TABLA */
				$existe = $this->EscuelaCarrera->find('first', array('conditions' => array('escuela_codigo' => $Escuela, 'carrera_codigo' => $k)));
					if (empty($existe)){
						$datos['carrera_codigo'] = $k;
						$datos['escuela_codigo'] = ($Escuela);				
						$this->EscuelaCarrera->create();
						$this->EscuelaCarrera->save($datos);
					}
			}
			if ($EscuelaCarrera == 0){ //No Existe				
				/* COMPROBAMOS SI ESTÁ EN LA TABLA */
				$existe = $this->EscuelaCarrera->find('first', array('conditions' => array('escuela_codigo' => $Escuela, 'carrera_codigo' => $k)));				
					if (!empty($existe)){
						$id = $existe['EscuelaCarrera']['id'];
						$this->EscuelaCarrera->deleteAll(array('EscuelaCarrera.id' => $id), false);						
					}
			}
		}
		return true;
	}
	
	/* MÉTODO QUE PERMITIRÁ PAGINAR LAS ESCUELAS EXISTENTES EN DUOC */
	function listadoEscuelas(){
		$escuelas = $this->Escuela->find('all', array('order' => 'Escuela.nombre asc'));
		foreach ($escuelas as $k => $escuela){
			$escuelascarreras = $this->EscuelaCarrera->find('all', array('conditions' => array('EscuelaCarrera.escuela_codigo' => $escuela['Escuela']['id'])));	
			foreach ($escuelascarreras as $l => $escuelacarrera){
				$nombre_carrera = $this->nombreCarrera($escuelacarrera['EscuelaCarrera']['carrera_codigo']);				
				$escuelas[$k]['Carrera'][$escuelacarrera['EscuelaCarrera']['carrera_codigo']] = $nombre_carrera;			
			}
		}		
		$this->set('escuelas',$escuelas);
	}
	
	/* MÉTODO QUE OBTENDRÁ LA CARRERA PASÁNDOLE UN PARÁMETRO CODIGO */
	private function nombreCarrera($codigo_carrera){			
			$carrera = $this->Carrera->find('first', array('conditions' => array('Carrera.codigo' => $codigo_carrera)));
			return $carrera['Carrera']['nombre'];
	}
	
	
	/* MÉTODO QUE CAMBIARÁ EL ESTADO DE UNA ESCUELA A ACTIVADO Y DESACTIVADO RESPECTIVAMENTE PASADO POR POST */	
	public function activarEscuela(){
			$codigo = ($this->request->data['Sistema']['activo']);
			if($codigo == null)
			{
				$this->Session->setFlash(__('El código de la escuela no Existe.'),'mensaje-error');
				$this->redirect(array('action'=>'listadoEscuelas'));
			}
			$escuela = $this->Escuela->find('first', array('conditions' => array('Escuela.id' => $codigo)));
			if (!empty($escuela)){
				$activado = $escuela['Escuela']['activo'];			
				if ($activado == 1){
					$this->Escuela->query("UPDATE RAP_ESCUELAS SET ACTIVO = '0' WHERE ID = '".$codigo."'");					
						//Revisamos que se haya realizado el cambio
						$escuela = $this->Escuela->find('first', array('conditions' => array('id' => $codigo)));
						$activo = $escuela['Escuela']['activo'];
						if ($activo == 0) {
							$this->Session->setFlash(__('Escuela desactivada correctamente.'),'mensaje-exito');
							$this->redirect(array('action' => 'listadoEscuelas'));
						}
						else{
							$this->Session->setFlash(__('Error al desactivar la Escuela.'),'mensaje-error');
							$this->redirect(array('action' => 'listadoEscuelas'));
						}
					}								
				}
				if ($activado == 0){			
					$this->Escuela->query("UPDATE RAP_ESCUELAS SET ACTIVO = '1' WHERE ID = '".$codigo."'");
					$escuela = $this->Escuela->find('first', array('conditions' => array('Escuela.id' => $codigo)));
					$activo = $escuela['Escuela']['activo'];
					if ($activo == 1) {
							$this->Session->setFlash(__('Escuela activada correctamente'),'mensaje-exito');
							$this->redirect(array('action' => 'listadoEscuelas'));
						}
						else{
							$this->Session->setFlash(__('Error al desactivar la escuela'),'mensaje-error');
							$this->redirect(array('action' => 'listadoEscuelas'));
						}
				}
	}
	
}
?>
	