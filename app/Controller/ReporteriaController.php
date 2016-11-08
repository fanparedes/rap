<?php
	App::uses('AppController', 'Controller');
	
	class ReporteriaController extends AppController {
	
		var $name = 'Reporteria';
		var $uses = array('Sede','Carrera','Administrativo', 'Postulacion', 'Prepostulacion', 'Cargas','EducacionPostulacion','CapacitacionPostulacion',
		'LaboralPostulacion','AutoEvaluacion','CompetenciaPostulacion', 'EstadoPostulacion', 'Postulante', 'Horario','Entrevista', 'Cargas', 'EvidenciasPrevias', 'ArchivoEvidencia','Entrevista', 'Escuela', 'EscuelaCarrera', 'Liceo');
		var $layout = "administrativos-2016";
		
		function beforeFilter(){
			$this->validarAdmin();
	  
		}
		
		function entrevistadores()
		{
			$carreras = $this->Carrera->find('all');
			$orientadores = $this->Administrativo->find('all', array('conditions' => array('Administrativo.orientador' => 1,
																						   'Administrativo.perfil' => 2)));
			
			$this->set(compact('orientadores','carreras'));
		}
		
		private function convertir($datos)
		{
			$cont = 0;
			for($x =0 ; $x < count($datos); $x++)
			{
				 pr($datos[$x]['codigo'].",");
				$cont++;
			}
			 
		}
		
		
		/*
		 *funcion para generar los reportes en ajax de entrevistadores
		 **/
		function ajax_entrevistadores()
		{
			 $this->layout='ajax';
			 Configure::write('debug',2);
			//esta sesion de datos se crea para que cuando la vista de excel sea llamada, la rescate y genere los datos del $this->data
			$this->Session->write('Datos',$this->data);
			//las siguientes dos bucles , son para limpiar ya sean los orientadores o las carreras cuando vengan en cero, vale decir que no se checkearon
			$nuevo_orientador = array();
			$nueva_carrera = array();
			foreach($this->data['Orientador'] as $clave => $orienta)
			{
				if(empty($this->data['Orientador'][$clave]['orientador']))
				{
					unset($clave);
				}else{
					$nuevo_orientador[] = array('codigo' => $this->data['Orientador'][$clave]['orientador']);
				}
			}
			
			foreach($this->data['Carrera'] as $clave2 => $carrera)
			{
				
				if(empty($this->data['Carrera'][$clave2]['orientador']))
				{
					unset($clave2);
				}else{
					$nueva_carrera[] = array('codigo' => $this->data['Carrera'][$clave2]['orientador']);
				}
			}
			
			$desde = $this->data['Reporteria']['fecha_desde'];
			$hasta = $this->data['Reporteria']['fecha_hasta'];
			//$fech_inicio	= '20/03/2014';
			//$fech_final		= '05/04/2014';
			$formateo_fech_inicio = explode('/',$this->data['Reporteria']['fecha_desde']);
			$formateo_fech_final = explode('/',$this->data['Reporteria']['fecha_hasta']);
			
			//defino fecha 1 
			$ano1 = $formateo_fech_inicio[2]; 
			$mes1 = $formateo_fech_inicio[1]; 
			$dia1 = $formateo_fech_inicio[0]; 
			
			//defino fecha 2 
			$ano2 = $formateo_fech_final[2]; 
			$mes2 = $formateo_fech_final[1]; 
			$dia2 = $formateo_fech_final[0]; 
		
			//calculo timestam de las dos fechas 
			$timestamp1 = mktime(0,0,0,$mes1,$dia1,$ano1); 
			$timestamp2 = mktime(4,12,0,$mes2,$dia2,$ano2); 
			
			//resto a una fecha la otra 
			$segundos_diferencia = $timestamp1 - $timestamp2; 
			//echo $segundos_diferencia; 
			
			//convierto segundos en días 
			$dias_diferencia = $segundos_diferencia / (60 * 60 * 24); 
			
			//obtengo el valor absoulto de los días (quito el posible signo negativo) 
			$dias_diferencia = abs($dias_diferencia); 
			
			//quito los decimales a los días de diferencia 
			$dias_diferencia = floor($dias_diferencia) +1 ;
			
			//FIN DE CUENTA LOS DIAS
			if(! empty($nueva_carrera))
			{
				foreach ($nueva_carrera as $key => $value1)
				{
				  $newarray1[$key] = $value1['codigo'];
				}
			   
				$carrera_valor = implode(',', $newarray1);
			}else{
				$carrera_valor = 0;
			}
			
			
			if(! empty($nuevo_orientador))
			{
				foreach ($nuevo_orientador as $key => $value2)
				{
				  $newarray2[$key] = $value2['codigo'];
				}
		   
				$orientador_valor = implode(',', $newarray2);
			}else{
				$orientador_valor = 0;
			}
		
			
			//TRAE LAS ENTTREVISTAS HUERFANAS :(
		
			$huerfanas = $this->Entrevista->query("select count(*) from rap_entrevistas where administrativo_codigo not in (select codigo from  rap_administrativos where orientador=1)");
			//Obtiene los orientadores
			$orientadores = $this->Entrevista->query("select A.CODIGO, A.NOMBRE, COUNT(*) AS TOTAL 
											from rap_entrevistas E, rap_administrativos A
											where E.CREATED BETWEEN TO_DATE('".$desde."', 'DD/MM/YY') AND TO_DATE('".$hasta."', 'DD/MM/YY')
											and A.ORIENTADOR = 1
											and A.CODIGO in (".$orientador_valor.")
											and A.carrera_codigo in (".$carrera_valor.")
											AND E.administrativo_codigo = A.codigo
											group by A.CODIGO, A.NOMBRE");
			
			//limpia los orientadores
			$orientador_datos = array();
			foreach($orientadores as $key => $datos)
			{
				
				$orientador_datos[]['Orientador'] = array('codigo'		=> $datos['A']['codigo'],
														  'nombre'		=> $datos['A']['nombre'],
														  'cantidad'	=> $datos[0]['E']);
				
			}
			///Consulta para obtener agrupado por carrera
			$carrera = $this->Entrevista->query("select C.codigo, C.Nombre, COUNT(*) AS TOTAL 
												from rap_entrevistas E, rap_administrativos A, RAP_CARRERAS C
												where E.CREATED BETWEEN TO_DATE('".$desde."', 'DD/MM/YY') AND TO_DATE('".$hasta."', 'DD/MM/YY')
												and A.ORIENTADOR = 1
												and A.CODIGO in (".$orientador_valor.")
												and A.carrera_codigo in (".$carrera_valor.")
												AND E.administrativo_codigo = A.codigo
												and C.CODIGO = A.carrera_codigo
												group by C.codigo, C.NOMBRE");
				
			//limpia las carreras
			$carrera_datos = array();
			foreach($carrera as $llave => $datos_carrera)
			{
				
				$carrera_datos[]['Carrera'] = array('codigo'		=> $datos_carrera['C']['codigo'],
													'nombre'	=> $datos_carrera['C']['nombre'],
													'cantidad'	=> $datos_carrera[0]['E']);
				
			}
			//total de entrevistas
			$entrevistas_totales = $this->Entrevista->query("select COUNT(*) AS TOTAL 
														from rap_entrevistas E, rap_administrativos A
														where E.CREATED BETWEEN TO_DATE('".$desde."', 'DD/MM/YY') AND TO_DATE('".$hasta."', 'DD/MM/YY')
														and A.ORIENTADOR = 1
														and A.CODIGO in (".$orientador_valor.")
														and A.carrera_codigo in (".$carrera_valor.")
														AND E.administrativo_codigo = A.codigo");
					
			//variables para graficos
			$barras = $this->data['Reporteria']['barras'];
			$tortas = $this->data['Reporteria']['tortas'];
			//para entrevistas totales
			$this->set('totales',$entrevistas_totales[0][0]['E']);
			//para grafico de carreras
			$this->set('carrera_grafico',$carrera_datos);
			//grafico de Orientadores
			$this->set('orientador_grafico',$orientador_datos);
			//para tabla de carreras
			$tabla_carrera = array_chunk($carrera_datos,2);
			$this->set('carrera_tabla',$tabla_carrera);
			//para tabla de Orientadores
			$tabla_orientadores = array_chunk($orientador_datos,2);
			$this->set('orientador_tabla',$tabla_orientadores);
			//dias diferecia
			$this->set('dias_diferencia',$dias_diferencia);
		
			$this->set('tortas',$tortas);
			$this->set('barras',$barras);
			$this->set(compact('desde','hasta'));
		}
		
		//Esta función te devuelve el estado de una postulación pasandole el código de postulación por parámetro
		private function estadoPostulacion($codigo_postulacion){
			if ($codigo_postulacion <> null){
				$estado = $this->EstadoPostulacion->find('first',
					array(
						'conditions' => array( 'postulacion_codigo' => $codigo_postulacion),
						'order' => 'created desc',						
					)	
				);	
				$postulacionEstado = '';
				if (isset($estado['EstadoPostulacion'])){
					switch ($estado['EstadoPostulacion']['estado_codigo']) {
						case 1:
							$postulacionEstado = 'FORMULARIO DE POSTULACIÓN COMPLETADO';
							break;
						case 2:
							$postulacionEstado = 'DOCUMENTACIÓN RECIBIDA EN REVISIÓN';
							break;
						case 3:
							$postulacionEstado = 'DOCUMENTACIÓN APROBADA';
							break;
						case 4:
							$postulacionEstado = 'CURRÍCULUM RAP COMPLETADO';
							break;
						case 5:
							$postulacionEstado = 'CV RAP Y AUTOEVALUACIÓN EN REVISIÓN';
							break;	
						case 6:
							$postulacionEstado = 'EVIDENCIAS PREVIAS';						
							break;
						case 7:
							$postulacionEstado = 'POSTULACIÓN RECHAZADA';
							break;
						case 8:
						    //Comprobamos evidencias preliminares
							$evidencias_finales = $this->EvidenciasPrevias->find('first', array('conditions' => array('preliminar' => '0', 'validar' => '1')));
							$evidencias_previas = $this->EvidenciasPrevias->find('first', array('conditions' => array('preliminar' => '1', 'validar' => '1')));
							if (!empty($evidencias_finales)){
								$postulacionEstado = 'EVIDENCIAS FINALES';
							}	
							if (!empty($evidencias_previas)){
								$postulacionEstado = 'ENTREVISTA';
							}
							else {							
								$postulacionEstado = 'EVIDENCIAS PREVIAS';
							}
							break;	
						case 9:
							$postulacionEstado = 'POSTULACIÓN FINALIZADA';
							break;
					}
					return ($postulacionEstado);	
				}
				else {
					return('Sin datos');
				}
						
			}
			else {
				return ('Sin estado');
			}
		}
		
		
		
		
		function ajax_exporta_excel()
		{
			$this->layout = 'ajax';
			//Configure::write('debug',2);
			$this->data = $this->Session->read('Datos');
			//echo var_dump($this->data);
			$nuevo_orientador = array();
			$nueva_carrera = array();
			foreach($this->data['Orientador'] as $clave => $orienta)
			{
				if(empty($this->data['Orientador'][$clave]['orientador']))
				{
					unset($clave);
				}else{
					$nuevo_orientador[] = array('codigo' => $this->data['Orientador'][$clave]['orientador']);
				}
			}
			
			foreach($this->data['Carrera'] as $clave2 => $carrera)
			{
				
				if(empty($this->data['Carrera'][$clave2]['orientador']))
				{
					unset($clave2);
				}else{
					$nueva_carrera[] = array('codigo' => $this->data['Carrera'][$clave2]['orientador']);
				}
			}
			
			$desde = $this->data['Reporteria']['fecha_desde'];
			$desde2 = date('Y-d-m', strtotime($desde));
			$hasta = $this->data['Reporteria']['fecha_hasta'];
			$hasta = str_replace( '/', '-',$hasta);		
			$hasta2 = date('Y-m-j',strtotime($hasta));	
			
			//MODIFICACIÓN PARA OBTENER SOLAMENTE LOS NOMBRES DE LAS ESTADÍSTICAS. 
			$condiciones = array( 
						'fields' => array(
							'Entrevista.codigo',
							'Entrevista.created',
							'Entrevista.estado',
							'Entrevista.modified',
							'Entrevista.postulacion_codigo',
							'Postulaciones.codigo',
							'Postulaciones.ciudad_codigo',
							'Postulaciones.sede_codigo',
							'Postulaciones.jornada',						
							'Postulantes.codigo',						
							'Postulantes.nombre',
							'Postulantes.email',							
							'Administrativos.nombre',						
							'Administrativos.codigo as orientador',						
							'Administrativos.carrera_codigo',						
							'Carreras.nombre',
							'Horarios.hora_inicio',
							'Horarios.fecha'
						),
						'joins' => array(
							array(
								'type' => 'left',
								'alias' => 'Postulaciones',
								'table' => 'RAP_POSTULACIONES',
								'conditions' => array(
									'Postulaciones.codigo = Entrevista.postulacion_codigo'
								)
							), 
							array(
								'type' => 'left',
								'alias' => 'Postulantes',
								'table' => 'RAP_POSTULANTES',
								'conditions' => array(
									'Postulantes.codigo = Entrevista.postulante_codigo'
								)
							),
							array(
								'type' => 'left',
								'alias' => 'Administrativos',
								'table' => 'RAP_ADMINISTRATIVOS',
								'conditions' => array(
									'Administrativos.codigo = Entrevista.administrativo_codigo'
								)
							),
							array(
								'type' => 'left',
								'alias' => 'Carreras',
								'table' => 'RAP_CARRERAS',
								'conditions' => array(
									'Carreras.codigo = Administrativos.carrera_codigo'
								)
							),
							array(
								'type' => 'left',
								'alias' => 'Horarios',
								'table' => 'RAP_HORARIOS',
								'conditions' => array(
									'Horarios.codigo = Entrevista.horario_codigo'
								)
							)
						),
						'order' => array(
							'Carreras.nombre DESC'						
						),
						'conditions'=> array(
								'Entrevista.created >= ' => $desde2,
								'Entrevista.created <= ' => $hasta2,								
						   ));
			$entrevistas = $this->Entrevista->find('all', $condiciones);
			//echo var_dump($entrevistas);

			// DEBEMOS ELIMINAR DE ESTE ARRAY LOS ORIENTADORES Y LAS CARRERAS CUYOS CÓDIGOS NO ESTÉN ELEGIDOS POR EL USUARIO EN LOS CHECKBOXES
			$carreras_elegidas = $this->data['Carrera'];
			
			//Aquí se muestran todos los códigos de las carreras elegidas por el usuario
			$checks = array();
			foreach ($carreras_elegidas as $carrera) {	
					$codigo_carrera = (implode($carrera));
					array_push($checks,$codigo_carrera); 
			}			
			//echo var_dump($checks);	

			//Aquí se muestran todos los códigos de los orientadores elegidas por el usuario
			$checks2 = array();
			$orientadores_elegidos = $this->data['Orientador'];
			foreach ($orientadores_elegidos as $orientador) {	
					$codigo_orientador = (implode($orientador));
					array_push($checks2,$codigo_orientador); 
			}			
			//echo var_dump($checks2);
			
			//Con esto obtenemos los códigos de las carreras de las entrevistas que se han realizado.
			$carreras_entrevistas = array();
			foreach ($entrevistas as $k=> $entrevista){
					$codigo_carrera = (($entrevista['Administrativos']['carrera_codigo']));
					if (!in_array($codigo_carrera,$carreras_entrevistas)){
						array_push($carreras_entrevistas,$codigo_carrera); 
					}	
					//Buscamos el código de la postulacion para ponerle el estado del postulante
					$estado = $this->EstadoPostulacion($entrevista['Postulaciones']['codigo']);
					//echo var_dump($estado);
					$entrevistas[$k]['Postulaciones']['Estado'] = $estado;//El estado lo agregamos al array de Entrevistas para mostrarlo en el excel
			}
			//echo var_dump($entrevistas);
			//die;
			//echo var_dump($carreras_entrevistas);			
			$resultado = array_intersect($checks, $carreras_entrevistas);	

			//Con esto obtenemos los códigos de las carreras de las entrevistas que se han realizado.
			$orientadores_entrevistas = array();
			foreach ($entrevistas as $entrevista){
					$codigo_orientador = (($entrevista['Administrativos']['orientador']));
					if (!in_array($codigo_orientador,$carreras_entrevistas)){
						array_push($orientadores_entrevistas,$codigo_orientador); 
					}	
			}
			//echo var_dump($carreras_entrevistas);			
			$resultado2 = array_intersect($checks2, $orientadores_entrevistas);			

			//Limpiamos el array de las entrevistas que no han sido elegidas sus carreras y los orientadores
			
			foreach ($entrevistas as $k => $entrevista) {
				if (!in_array($entrevista['Administrativos']['carrera_codigo'], $resultado)){
					//echo var_dump('No está');
					unset($entrevistas[$k]);				
				}
				if (!in_array($entrevista['Administrativos']['orientador'], $resultado2)){
					//echo var_dump('No está este orientador');
					unset($entrevistas[$k]);				
				}
			}			
			//echo var_dump($entrevistas);
			
			$this->set('entrevistas2',$entrevistas);
			
			//$this->render('sql');	
			
			//$fech_inicio	= '20/03/2014';
			//$fech_final		= '05/04/2014';
			$formateo_fech_inicio = explode('/',$this->data['Reporteria']['fecha_desde']);
			$formateo_fech_final = explode('/',$this->data['Reporteria']['fecha_hasta']);
			
			//defino fecha 1 
			$ano1 = $formateo_fech_inicio[2]; 
			$mes1 = $formateo_fech_inicio[1]; 
			$dia1 = $formateo_fech_inicio[0]; 
			
			//defino fecha 2 
			$ano2 = $formateo_fech_final[2]; 
			$mes2 = $formateo_fech_final[1]; 
			$dia2 = $formateo_fech_final[0]; 
		
			//calculo timestam de las dos fechas 
			$timestamp1 = mktime(0,0,0,$mes1,$dia1,$ano1); 
			$timestamp2 = mktime(4,12,0,$mes2,$dia2,$ano2); 
			
			//resto a una fecha la otra 
			$segundos_diferencia = $timestamp1 - $timestamp2; 
			//echo $segundos_diferencia; 
			
			//convierto segundos en días 
			$dias_diferencia = $segundos_diferencia / (60 * 60 * 24); 
			
			//obtengo el valor absoulto de los días (quito el posible signo negativo) 
			$dias_diferencia = abs($dias_diferencia); 
			
			//quito los decimales a los días de diferencia 
			$dias_diferencia = floor($dias_diferencia) +1 ;
			
			//FIN DE CUENTA LOS DIAS
			if(! empty($nueva_carrera))
			{
				foreach ($nueva_carrera as $key => $value1)
				{
				  $newarray1[$key] = $value1['codigo'];
				}
			   
				$carrera_valor = implode(',', $newarray1);
			}else{
				$carrera_valor = 0;
			}
			
			
			if(! empty($nuevo_orientador))
			{
				foreach ($nuevo_orientador as $key => $value2)
				{
				  $newarray2[$key] = $value2['codigo'];
				}
		   
				$orientador_valor = implode(',', $newarray2);
			}else{
				$orientador_valor = 0;
			}
		
			
			//TRAE LAS ENTTREVISTAS HUERFANAS :(
		
			$huerfanas = $this->Entrevista->query("select count(*) from rap_entrevistas where administrativo_codigo not in (select codigo from  rap_administrativos where orientador=1)");
			//Obtiene los orientadores
			$orientadores = $this->Entrevista->query("select A.CODIGO, A.NOMBRE, COUNT(*) AS TOTAL 
											from rap_entrevistas E, rap_administrativos A
											where E.CREATED BETWEEN TO_DATE('".$desde."', 'DD/MM/YY') AND TO_DATE('".$hasta."', 'DD/MM/YY')
											and A.ORIENTADOR = 1
											and A.CODIGO in (".$orientador_valor.")
											and A.carrera_codigo in (".$carrera_valor.")
											AND E.administrativo_codigo = A.codigo
											group by A.CODIGO, A.NOMBRE");
			
			//limpia los orientadores
			$orientador_datos = array();
			foreach($orientadores as $key => $datos)
			{
				
				$orientador_datos[]['Orientador'] = array('codigo'		=> $datos['A']['codigo'],
														  'nombre'		=> $datos['A']['nombre'],
														  'cantidad'	=> $datos[0]['E']);
				
			}
			///Consulta para obtener agrupado por carrera
			$carrera = $this->Entrevista->query("select C.codigo, C.Nombre, COUNT(*) AS TOTAL 
												from rap_entrevistas E, rap_administrativos A, RAP_CARRERAS C
												where E.CREATED BETWEEN TO_DATE('".$desde."', 'DD/MM/YY') AND TO_DATE('".$hasta."', 'DD/MM/YY')
												and A.ORIENTADOR = 1
												and A.CODIGO in (".$orientador_valor.")
												and A.carrera_codigo in (".$carrera_valor.")
												AND E.administrativo_codigo = A.codigo
												and C.CODIGO = A.carrera_codigo
												group by C.codigo, C.NOMBRE");
				
			//limpia las carreras
			$carrera_datos = array();
			foreach($carrera as $llave => $datos_carrera)
			{
				
				$carrera_datos[]['Carrera'] = array('codigo'		=> $datos_carrera['C']['codigo'],
													'nombre'	=> $datos_carrera['C']['nombre'],
													'cantidad'	=> $datos_carrera[0]['E']);
				
			}
			//total de entrevistas
			$entrevistas_totales = $this->Entrevista->query("select COUNT(*) AS TOTAL 
														from rap_entrevistas E, rap_administrativos A
														where E.CREATED BETWEEN TO_DATE('".$desde."', 'DD/MM/YY') AND TO_DATE('".$hasta."', 'DD/MM/YY')
														and A.ORIENTADOR = 1
														and A.CODIGO in (".$orientador_valor.")
														and A.carrera_codigo in (".$carrera_valor.")
														AND E.administrativo_codigo = A.codigo");
					
			//variables para graficos
			$barras = $this->data['Reporteria']['barras'];
			$tortas = $this->data['Reporteria']['tortas'];
			//para entrevistas totales
			$this->set('totales',$entrevistas_totales[0][0]['E']);
			//para tabla de carreras
			$this->set('carrera_excel',$carrera_datos);
			//para tabla de Orientadores
			$this->set('orientador_excel',$orientador_datos);
			//dias diferecia
			$this->set('dias_diferencia',$dias_diferencia);
			$this->set('tortas',$tortas);
			$this->set('barras',$barras);
			$this->set(compact('desde','hasta'));
			
		}
		
		//reporteria para postulaciones
		function postulaciones()
		{
			$carreras	= $this->Carrera->find('all');
			$sedes		= $this->Sede->find('all');
			$this->set(compact('sedes','carreras'));
		
		}
		
		
		/*
		 *funcion para generar los reportes en ajax de entrevistadores
		 **/
		
		function ajax_postulaciones()
		{
			 $this->layout='ajax';
			//Configure::write('debug',2);
			//esta sesion de datos se crea para que cuando la vista de excel sea llamada, la rescate y genere los datos del $this->data
			$this->Session->write('Datos',$this->data);
			ini_set("memory_limit","256M");
			$nueva_sede = array();
			$nueva_carrera = array();
			foreach($this->data['Sede'] as $clave => $sede)
			{
				if(empty($this->data['Sede'][$clave]['codigo']))
				{
					unset($clave);
				}else{
					$nueva_sede[]['Sede'] = array('codigo' => $this->data['Sede'][$clave]['codigo']);
				}
			}
			
			foreach($this->data['Carrera'] as $clave2 => $carrera)
			{
				if(empty($this->data['Carrera'][$clave2]['codigo']))
				{
					unset($clave2);
				}else{
					$nueva_carrera[]['Carrera'] = array('codigo' => $this->data['Carrera'][$clave2]['codigo']);
				}
			}
			
			$desde = $this->data['Postulaciones']['fecha_desde'];
			$hasta = $this->data['Postulaciones']['fecha_hasta'];
			

			
			if($this->data['Postulaciones']['totales'] == 1)
			{
				$totales_default	= $this->totales($desde,$hasta);
				
				$activo_carrera = array();
				foreach($nueva_carrera as $key2 => $dato2)
				{
					foreach($totales_default as $key1 => $dato1)
					{
						if($totales_default[$key1]['Postulacion']['carrera_codigo'] == $nueva_carrera[$key2]['Carrera']['codigo'] )
						{
							$activo_carrera[]['Postulacion'] = array('carrera' => $totales_default[$key1]['Postulacion']['carrera_codigo'],
																	'sede'	=> $totales_default[$key1]['Postulacion']['sede_codigo']) ;
						}
					}
					
				}
			
				$final_total = array();
				foreach($nueva_sede as $key3 => $dato3)
				{
				
					foreach($activo_carrera as $key4 => $dato3)
					{
						if($activo_carrera[$key4]['Postulacion']['sede'] == $nueva_sede[$key3]['Sede']['codigo'] )
						{
							$final_total[]['Postulacion'] = array('carrera' => $activo_carrera[$key4]['Postulacion']['carrera'],
																	'sede'	=> $activo_carrera[$key4]['Postulacion']['sede']) ;
						}
					}
				}
				
				$totales = count($final_total);
				//pr($totales);
					
			}else{
					$totales	= 0;
			}
			if($this->data['Postulaciones']['rechazados'] == 7)
			{
					$rechazo_numero = 7;
					$rechazados_default			= $this->rechaso($desde,$hasta,$rechazo_numero);
					
				$total_rechazo = array();
				foreach($nueva_carrera as $key2 => $dato2)
				{
					foreach($rechazados_default as $key1 => $dato1)
					{
						if($rechazados_default[$key1]['Carreras']['codigo'] == $nueva_carrera[$key2]['Carrera']['codigo'] )
						{
							$total_rechazo[]['Rechazo'] = array('carrera'	=> $rechazados_default[$key1]['Carreras']['codigo'],
																'sede'		=> $rechazados_default[$key1]['Sedes']['codigo_sede'],
																'max'		=>  $rechazados_default[$key1][0]['maximo']) ;
						}
					}
					
				}
				
				$rechazo_total = array();
				foreach($nueva_sede as $key3 => $dato3)
				{
				
					foreach($total_rechazo as $key4 => $dato3)
					{
						if($total_rechazo[$key4]['Rechazo']['sede'] == $nueva_sede[$key3]['Sede']['codigo'] )
						{
							$rechazo_total[]['Rechazo'] = array('carrera'	=> $total_rechazo[$key4]['Rechazo']['carrera'],
																	'sede'		=> $total_rechazo[$key4]['Rechazo']['sede'],
																	'max'		=>  $total_rechazo[$key4]['Rechazo']['max']) ;
						}
					}
				}
				
				$rechazados = count($rechazo_total);
					
			}else{
					$rechazados		= 0;
			}
			if($this->data['Postulaciones']['finalizados'] == 9)
			{
					$final_numero	= 9;
					$finales_default			= $this->finales($desde,$hasta,$final_numero);
					$total_final = array();
					foreach($nueva_carrera as $key2 => $dato2)
					{
						foreach($finales_default as $key1 => $dato1)
						{
							if($finales_default[$key1]['Carreras']['codigo'] == $nueva_carrera[$key2]['Carrera']['codigo'] )
							{
								$total_final[]['Final'] = array('carrera'	=> $finales_default[$key1]['Carreras']['codigo'],
																'sede'	=> $finales_default[$key1]['Sedes']['codigo_sede'],
																'max'	=>  $finales_default[$key1][0]['maximo']);
							}
						}
					}
					
					$final_total = array();
					foreach($nueva_sede as $key3 => $dato3)
					{
					
						foreach($total_final as $key4 => $dato3)
						{
							if($total_final[$key4]['Final']['sede'] == $nueva_sede[$key3]['Sede']['codigo'] )
							{
								$final_total[]['Final'] = array('carrera'	=> $total_final[$key4]['Final']['carrera'],
																'sede'		=> $total_final[$key4]['Final']['sede'],
																'max'		=>  $total_final[$key4]['Final']['max']);
							}
						}
					}
					
					$finales = count($final_total);
					
			}else{
					$finales		= 0;
			}
			if(! empty($this->data['Postulaciones']['deserciones']))
			{
				$desertor_default		= $this->deserto($desde,$hasta);
					
				$desertor_total= array();
				foreach($nueva_carrera as $key2 => $dato2)
				{
					foreach($desertor_default as $key1 => $dato1)
					{
						if($desertor_default[$key1]['Postulacion']['carrera_codigo'] == $nueva_carrera[$key2]['Carrera']['codigo'] )
						{
							$desertor_total[]['Desertor'] = array('carrera' => $desertor_default[$key1]['Postulacion']['carrera_codigo'],
																	'sede'	=> $desertor_default[$key1]['Postulacion']['sede_codigo']) ;
						}
					}
					
				}
			
				$total_desertor = array();
				foreach($nueva_sede as $key3 => $dato3)
				{
				
					foreach($desertor_total as $key4 => $dato6)
					{
						if($desertor_total[$key4]['Desertor']['sede'] == $nueva_sede[$key3]['Sede']['codigo'] )
						{
							$total_desertor[]['Desertor'] = array('carrera' => $desertor_total[$key4]['Desertor']['carrera'],
																	'sede'	=> $desertor_total[$key4]['Desertor']['sede']) ;
						}
					}
				}
					$desertor = count($total_desertor);
					
					
			}else{
					$desertor		= 0;
			}
			
			
			$carrera_por_sede	= $this->carrera($desde,$hasta);
			$sedes_por_carrera	= $this->sedes_carrera($desde,$hasta); 
			
			$total_sedes		= $this->sedes($desde,$hasta);
			
			//array para totales, rechazados y finales
			$datos_finales = array('0'		=> array('nombre' =>'Activos','totales' => $totales),
								   '1'		=> array('nombre' =>'Finales','totales' => $finales),
								   '2'		=> array('nombre' =>'Rechazo','totales' => $rechazados),
								   '3'		=> array('nombre' => 'Desertores','totales' => $desertor));
			
			$this->set('totales',$datos_finales);
			//desertores
			$this->set('desertor',$desertor);
		
			//calzamos	las postulaciones por sedes
			$sede_formulario = array();
			foreach($nueva_sede as $key2 => $dato2)
			{
				foreach($total_sedes as $key1 => $dato1)
				{
					if($total_sedes[$key1]['Sede']['codigo'] == $nueva_sede[$key2]['Sede']['codigo'] )
					{
						$sede_formulario[]['Sede'] = array('sede'	=> $total_sedes[$key1]['Sede']['nombre'],
															'codigo'	=> $total_sedes[$key1]['Sede']['codigo'],
															'cantidad'	=> $total_sedes[$key1]['Sede']['cantidad']);
					}
				}
			}
			
			//calzamos las postulaciones por carrera
			$carrera_formulario = array();
			foreach($nueva_carrera as $key2 => $dato2)
			{
				foreach($carrera_por_sede as $key1 => $dato1)
				{
					if($carrera_por_sede[$key1]['Carrera']['codigo'] == $nueva_carrera[$key2]['Carrera']['codigo'] )
					{
						$carrera_formulario[]['Carrera'] = array('carrera'	=> $carrera_por_sede[$key1]['Carrera']['nombre'],
																'codigo'	=> $carrera_por_sede[$key1]['Carrera']['codigo'],
																'cantidad'	=> $carrera_por_sede[$key1]['Carrera']['cantidad']);
					}
				}
			}
			
			
			//calzamos las estadisticas por carrera
			$sede_formulario_postula = array();
			foreach($nueva_sede as $key2 => $dato2)
			{
				foreach($carrera_por_sede as $key1 => $dato1)
				{
					foreach($carrera_por_sede[$key1]['Sede'] as $key3 => $dato3)
					{
						
						if($carrera_por_sede[$key1]['Sede'][$key3]['codigo'] == $nueva_sede[$key2]['Sede']['codigo'] )
						{
							$sede_formulario_postula[$key1]['Carrera'] = array('carrera'	=> $dato1['Carrera']['nombre'],
																			'codigo'	=> $dato1['Carrera']['codigo'],
																			'cantidad'	=> $dato1['Carrera']['cantidad']);
							
							$sede_formulario_postula[$key1]['Sede'][] = array('sede'	=> $dato3['nombre'],
																			'codigo'	=> $dato3['codigo'],
																			'cantidad'	=> $dato3['cantidad']);
							
						}
					}
				}
			}
			
			//calzamos las estadisticas por sede
			$carrera_formulario_postula = array();
			foreach($nueva_carrera as $key2 => $dato2)
			{
				foreach($sedes_por_carrera as $key1 => $dato1)
				{
					foreach($sedes_por_carrera[$key1]['Carrera'] as $key3 => $dato3)
					{
						
						if($sedes_por_carrera[$key1]['Carrera'][$key3]['codigo'] == $nueva_carrera[$key2]['Carrera']['codigo'] )
						{
							$carrera_formulario_postula[$key1]['Sede'] = array('sede'	=> $dato1['Sede']['nombre'],
																			'codigo'	=> $dato1['Sede']['codigo'],
																			'cantidad'	=> $dato1['Sede']['cantidad']);
							$carrera_formulario_postula[$key1]['Carrera'][] = array('carrera'	=> $dato3['nombre'],
																					'codigo'	=> $dato3['codigo'],
																					'cantidad'	=> $dato3['cantidad']);
						}
					}
				}
			}
			
			//calzamos las sedes para vista finalmente
			$sede_vista = array();
			foreach($nueva_sede as $key2 => $dato2)
			{
				foreach($carrera_formulario_postula as $llave  => $parametro)
				{
					if($carrera_formulario_postula[$llave]['Sede']['codigo'] == $nueva_sede[$key2]['Sede']['codigo'] )
					{
						
						foreach($carrera_formulario_postula[$llave]['Carrera'] as $index => $parametro2)
						{
							$sede_vista[$llave]['Sede'] = array('sede'	=> $parametro['Sede']['sede'],
																'codigo'	=> $parametro['Sede']['codigo'],
																'cantidad'	=> $parametro['Sede']['cantidad']);
							
							$sede_vista[$llave]['Carrera'][] = array('carrera'	=> $parametro2['carrera'],
																	'codigo'	=> $parametro2['codigo'],
																	'cantidad'	=> $parametro2['cantidad']);
						}
						
					}
				}
			}
			
			//calzamos las Carreras para vista finalmente
			$carrera_vista = array();
			foreach($nueva_carrera as $primer => $primer_dato)
			{
				foreach($sede_formulario_postula as $llave  => $parametro)
				{
				
					if($sede_formulario_postula[$llave]['Carrera']['codigo'] == $nueva_carrera[$primer]['Carrera']['codigo'] )
					{
						
						foreach($sede_formulario_postula[$llave]['Sede'] as $index => $parametro4)
						{
							$carrera_vista[$llave]['Carrera'] = array('carrera'	=> $sede_formulario_postula[$llave]['Carrera']['carrera'],
																	'codigo'	=> $sede_formulario_postula[$llave]['Carrera']['codigo'],
																	'cantidad'	=> $sede_formulario_postula[$llave]['Carrera']['cantidad']);
							$carrera_vista[$llave]['Sede'][] = array('sede'	=> $parametro4['sede'],
																'codigo'	=> $parametro4['codigo'],
																'cantidad'	=> $parametro4['cantidad']);
							
							
						}
						
					}
				}
			}
			
			
			
			$barras = $this->data['Postulaciones']['barras'];
			$tortas = $this->data['Postulaciones']['tortas'];
			$t_sede		= array_chunk($sede_formulario,2);
			$t_carrera	= array_chunk($carrera_formulario,2);
			
			//para postulaciones por sedes
			$this->set('total_sedes',$t_sede);
			//para postulaciones por carrera
			//pr($t_carrera);
			$this->set('total_carreras',$t_carrera);
			//fechas
			$this->set(compact('desde','hasta'));
			//para estadisticas por carrera
			$this->set('estadistica_por_carrera',$carrera_vista);
			//para estadisticas por sede
			
			$this->set('estadisticas_por_sede',$sede_vista);
			//para graficos de postulaciones por carrera
			$this->set('grafico_postulacion_carrera',$carrera_formulario);
			//para graficos de postulaciones por sede
			$this->set('grafico_postulaciones_sede',$sede_formulario);
			////grafico de sedes por carrera
			//$this->set('sede_por_carrera',$sede_grafico);
			
			//graficos de postulaciones
			$this->set('tortas',$tortas);
			$this->set('barras',$barras);
			
		}
		
		/*
		 Exportador de excel para postulaciones
		*/
		function ajax_exportar_excel_postulaciones()
		{
			 $this->layout='ajax';
			//Configure::write('debug',2);
			//esta sesion de datos se crea para que cuando la vista de excel sea llamada, la rescate y genere los datos del $this->data
			$this->data = $this->Session->read('Datos');
			//echo var_dump($this->data);
			ini_set("memory_limit","256M");
			$nueva_sede = array();
			$nueva_carrera = array();
			foreach($this->data['Sede'] as $clave => $sede)
			{
				if(empty($this->data['Sede'][$clave]['codigo']))
				{
					unset($clave);
				}else{
					$nueva_sede[]['Sede'] = array('codigo' => $this->data['Sede'][$clave]['codigo']);
				}
			}
			
			foreach($this->data['Carrera'] as $clave2 => $carrera)
			{
				if(empty($this->data['Carrera'][$clave2]['codigo']))
				{
					unset($clave2);
				}else{
					$nueva_carrera[]['Carrera'] = array('codigo' => $this->data['Carrera'][$clave2]['codigo']);
				}
			}
			
						
			$desde = $this->data['Postulaciones']['fecha_desde'];
			$desde2 = date('Y-d-m', strtotime($desde));
			$hasta = $this->data['Postulaciones']['fecha_hasta'];
			$hasta = str_replace( '/', '-',$hasta);		
			$hasta2 = date('Y-m-j',strtotime($hasta));				
			
			
			
			//MODIFICACIÓN PARA OBTENER LOS NOMBRES DE LAS ESTADÍSTICAS DE POSTULACIONES RAP REQ. 2015. 
			$condiciones = array( 
						'fields' => array(
							'Postulacion.codigo',
							'Postulacion.postulante_codigo',
							'Postulacion.carrera_codigo',
							'Postulacion.ciudad_codigo',
							'Postulacion.sede_codigo',
							'Postulantes.nombre',
							'Postulantes.email',
							'Sedes.nombre_sede',
							'Carreras.nombre',
							'Ciudades.nombre',
						),
 						'joins' => array(
							array(
								'type' => 'left',
								'alias' => 'Postulantes',
								'table' => 'RAP_POSTULANTES',
								'conditions' => array(
									'Postulantes.codigo = Postulacion.postulante_codigo'
								)
							),
							array(								
								'type' => 'left',
								'alias' => 'Carreras',
								'table' => 'RAP_CARRERAS',
								'conditions' => array(
									'Carreras.codigo = Postulacion.carrera_codigo'
								)
							),
							array(
								'type' => 'left',
								'alias' => 'Sedes',
								'table' => 'RAP_SEDES',
								'conditions' => array(
									'Sedes.codigo_sede = Postulacion.sede_codigo'
								)
							),
							array(
								'type' => 'left',
								'alias' => 'Ciudades',
								'table' => 'RAP_CIUDADES',
								'conditions' => array(
									'Ciudades.codigo = Postulacion.ciudad_codigo'
								)
							)
						),
/* 						'order' => array(
							'Carreras.nombre DESC'						
						), */
						'conditions'=> array(
								'Postulacion.created >= ' => $desde2,
								'Postulacion.created <= ' => $hasta2,								
						   ));
			$postulaciones = $this->Postulacion->find('all', $condiciones);
				
			// DEBEMOS ELIMINAR DE ESTE ARRAY LOS ORIENTADORES Y LAS CARRERAS CUYOS CÓDIGOS NO ESTÉN ELEGIDOS POR EL USUARIO EN LOS CHECKBOXES			
			$carreras_elegidas = $this->data['Carrera'];			
			//Aquí se muestran todos los códigos de las carreras elegidas por el usuario
			$checks = array();
			foreach ($carreras_elegidas as $i => $carrera) {	
					if ($carrera['codigo'] <> 0){ 
						$codigo_carrera = (implode($carrera));
						array_push($checks,$codigo_carrera); 
					}
			}			
			
			//Aquí se muestran todos los códigos de los orientadores elegidas por el usuario
			$checks2 = array();
			$sedes_elegidas = $this->data['Sede'];
			foreach ($sedes_elegidas as $sede) {	
					$codigo_sede = (implode($sede));
					array_push($checks2,$codigo_sede); 
			}			

			//Con esto obtenemos los códigos de las carreras de las entrevistas que se han realizado.
			$carreras_postulaciones = array();

			foreach ($postulaciones as $k=> $postulacion){
					$codigo_carrera = (($postulacion['Postulacion']['carrera_codigo']));
					if (!in_array($codigo_carrera,$carreras_postulaciones)){
						array_push($carreras_postulaciones,$codigo_carrera); 
					}	
					//Buscamos el código de la postulacion para ponerle el estado del postulante
					$estado = $this->EstadoPostulacion($postulacion['Postulacion']['codigo']);					
					$postulaciones[$k]['Postulaciones']['Estado'] = $estado; //El estado lo agregamos al array de Entrevistas para mostrarlo en el excel
			}	
			$resultado = array_intersect($checks, $carreras_postulaciones);	

			//Con esto obtenemos los códigos de las sedes de las postulaciones
			$sedes_postulaciones = array();
			foreach ($postulaciones as $postulacion){
					$codigo_sede = (($postulacion['Postulacion']['sede_codigo']));
					if (!in_array($codigo_sede,$sedes_postulaciones)){
						array_push($sedes_postulaciones,$codigo_sede); 
					}	
			}		
			$resultado2 = array_intersect($checks2, $sedes_postulaciones);	
			
			//Limpiamos el array de las POSTULACIONES que no han sido elegidas sus carreras,
			//sus sedes en el formulario de reporteria			
			foreach ($postulaciones as $k => $postulacion) {
				if (!in_array($postulacion['Postulacion']['carrera_codigo'], $resultado)){					
					unset($postulaciones[$k]);				
				}
				if (!in_array($postulacion['Postulacion']['sede_codigo'], $resultado2)){					
					unset($postulaciones[$k]);				
				} 
			}	

			$this->set('postulaciones2',$postulaciones);
			
			if($this->data['Postulaciones']['totales'] == 1)
			{
				$totales_default	= $this->totales($desde,$hasta);
				
				$activo_carrera = array();
				foreach($nueva_carrera as $key2 => $dato2)
				{
					foreach($totales_default as $key1 => $dato1)
					{
						if($totales_default[$key1]['Postulacion']['carrera_codigo'] == $nueva_carrera[$key2]['Carrera']['codigo'] )
						{
							$activo_carrera[]['Postulacion'] = array('carrera' => $totales_default[$key1]['Postulacion']['carrera_codigo'],
																	'sede'	=> $totales_default[$key1]['Postulacion']['sede_codigo']) ;
						}
					}
					
				}
			
				$final_total = array();
				foreach($nueva_sede as $key3 => $dato3)
				{
				
					foreach($activo_carrera as $key4 => $dato3)
					{
						if($activo_carrera[$key4]['Postulacion']['sede'] == $nueva_sede[$key3]['Sede']['codigo'] )
						{
							$final_total[]['Postulacion'] = array('carrera' => $activo_carrera[$key4]['Postulacion']['carrera'],
																	'sede'	=> $activo_carrera[$key4]['Postulacion']['sede']) ;
						}
					}
				}
				
				$totales = count($final_total);
					
			}else{
					$totales	= 0;
			}
			if($this->data['Postulaciones']['rechazados'] == 7)
			{
					$rechazo_numero = 7;
					$rechazados_default			= $this->rechaso($desde,$hasta,$rechazo_numero);
					
				$total_rechazo = array();
				foreach($nueva_carrera as $key2 => $dato2)
				{
					foreach($rechazados_default as $key1 => $dato1)
					{
						if($rechazados_default[$key1]['Carreras']['codigo'] == $nueva_carrera[$key2]['Carrera']['codigo'] )
						{
							$total_rechazo[]['Rechazo'] = array('carrera'	=> $rechazados_default[$key1]['Carreras']['codigo'],
																'sede'		=> $rechazados_default[$key1]['Sedes']['codigo_sede'],
																'max'		=>  $rechazados_default[$key1][0]['maximo']) ;
						}
					}
					
				}
				
				$rechazo_total = array();
				foreach($nueva_sede as $key3 => $dato3)
				{
				
					foreach($total_rechazo as $key4 => $dato3)
					{
						if($total_rechazo[$key4]['Rechazo']['sede'] == $nueva_sede[$key3]['Sede']['codigo'] )
						{
							$rechazo_total[]['Rechazo'] = array('carrera'	=> $total_rechazo[$key4]['Rechazo']['carrera'],
																	'sede'		=> $total_rechazo[$key4]['Rechazo']['sede'],
																	'max'		=>  $total_rechazo[$key4]['Rechazo']['max']) ;
						}
					}
				}
				
				$rechazados = count($rechazo_total);
					
			}else{
					$rechazados		= 0;
			}
			if($this->data['Postulaciones']['finalizados'] == 9)
			{
					$final_numero	= 9;
					$finales_default			= $this->finales($desde,$hasta,$final_numero);
					$total_final = array();
					foreach($nueva_carrera as $key2 => $dato2)
					{
						foreach($finales_default as $key1 => $dato1)
						{
							if($finales_default[$key1]['Carreras']['codigo'] == $nueva_carrera[$key2]['Carrera']['codigo'] )
							{
								$total_final[]['Final'] = array('carrera'	=> $finales_default[$key1]['Carreras']['codigo'],
																'sede'	=> $finales_default[$key1]['Sedes']['codigo_sede'],
																'max'	=>  $finales_default[$key1][0]['maximo']);
							}
						}
					}
					
					$final_total = array();
					foreach($nueva_sede as $key3 => $dato3)
					{
					
						foreach($total_final as $key4 => $dato3)
						{
							if($total_final[$key4]['Final']['sede'] == $nueva_sede[$key3]['Sede']['codigo'] )
							{
								$final_total[]['Final'] = array('carrera'	=> $total_final[$key4]['Final']['carrera'],
																'sede'		=> $total_final[$key4]['Final']['sede'],
																'max'		=>  $total_final[$key4]['Final']['max']);
							}
						}
					}
					
					$finales = count($final_total);
					
			}else{
					$finales		= 0;
			}
			if(! empty($this->data['Postulaciones']['deserciones']))
			{
				$desertor_default		= $this->deserto($desde,$hasta);
					
				$desertor_total= array();
				foreach($nueva_carrera as $key2 => $dato2)
				{
					foreach($desertor_default as $key1 => $dato1)
					{
						if($desertor_default[$key1]['Postulacion']['carrera_codigo'] == $nueva_carrera[$key2]['Carrera']['codigo'] )
						{
							$desertor_total[]['Desertor'] = array('carrera' => $desertor_default[$key1]['Postulacion']['carrera_codigo'],
																	'sede'	=> $desertor_default[$key1]['Postulacion']['sede_codigo']) ;
						}
					}
					
				}
			
				$total_desertor = array();
				foreach($nueva_sede as $key3 => $dato3)
				{
				
					foreach($desertor_total as $key4 => $dato3)
					{
						if($desertor_total[$key4]['Desertor']['sede'] == $nueva_sede[$key3]['Sede']['codigo'] )
						{
							$total_desertor[]['Desertor'] = array('carrera' => $desertor_total[$key4]['Desertor']['carrera'],
																	'sede'	=> $desertor_total[$key4]['Desertor']['sede']) ;
						}
					}
				}
					$desertor = count($total_desertor);
					
					
			}else{
					$desertor		= 0;
			}
			
			
			$carrera_por_sede	= $this->carrera($desde,$hasta);
			$sedes_por_carrera	= $this->sedes_carrera($desde,$hasta); 
			
			$total_sedes		= $this->sedes($desde,$hasta);
			
			//array para totales, rechazados y finales
			$datos_finales = array('0'		=> array('nombre' =>'Activos','totales' => $totales),
								   '1'		=> array('nombre' =>'Finales','totales' => $finales),
								   '2'		=> array('nombre' =>'Rechazo','totales' => $rechazados),
								   '3'		=> array('nombre' => 'Desertores','totales' => $desertor));
			$this->set('totales',$datos_finales);
			//desertores
			$this->set('desertor',$desertor);
		
			//calzamos	las postulaciones por sedes
			$sede_formulario = array();
			foreach($nueva_sede as $key2 => $dato2)
			{
				foreach($total_sedes as $key1 => $dato1)
				{
					if($total_sedes[$key1]['Sede']['codigo'] == $nueva_sede[$key2]['Sede']['codigo'] )
					{
						$sede_formulario[]['Sede'] = array('sede'	=> $total_sedes[$key1]['Sede']['nombre'],
															'codigo'	=> $total_sedes[$key1]['Sede']['codigo'],
															'cantidad'	=> $total_sedes[$key1]['Sede']['cantidad']);
					}
				}
			}
			
			//calzamos las postulaciones por carrera
			$carrera_formulario = array();
			foreach($nueva_carrera as $key2 => $dato2)
			{
				foreach($carrera_por_sede as $key1 => $dato1)
				{
					if($carrera_por_sede[$key1]['Carrera']['codigo'] == $nueva_carrera[$key2]['Carrera']['codigo'] )
					{
						$carrera_formulario[]['Carrera'] = array('carrera'	=> $carrera_por_sede[$key1]['Carrera']['nombre'],
																'codigo'	=> $carrera_por_sede[$key1]['Carrera']['codigo'],
																'cantidad'	=> $carrera_por_sede[$key1]['Carrera']['cantidad']);
					}
				}
			}
			
			
			//calzamos las estadisticas por carrera
			$sede_formulario_postula = array();
			foreach($nueva_sede as $key2 => $dato2)
			{
				foreach($carrera_por_sede as $key1 => $dato1)
				{
					foreach($carrera_por_sede[$key1]['Sede'] as $key3 => $dato3)
					{
						
						if($carrera_por_sede[$key1]['Sede'][$key3]['codigo'] == $nueva_sede[$key2]['Sede']['codigo'] )
						{
							$sede_formulario_postula[$key1]['Carrera'] = array('carrera'	=> $dato1['Carrera']['nombre'],
																			'codigo'	=> $dato1['Carrera']['codigo'],
																			'cantidad'	=> $dato1['Carrera']['cantidad']);
							
							$sede_formulario_postula[$key1]['Sede'][] = array('sede'	=> $dato3['nombre'],
																			'codigo'	=> $dato3['codigo'],
																			'cantidad'	=> $dato3['cantidad']);
							
						}
					}
				}
			}
			
			//calzamos las estadisticas por sede
			$carrera_formulario_postula = array();
			foreach($nueva_carrera as $key2 => $dato2)
			{
				foreach($sedes_por_carrera as $key1 => $dato1)
				{
					foreach($sedes_por_carrera[$key1]['Carrera'] as $key3 => $dato3)
					{
						
						if($sedes_por_carrera[$key1]['Carrera'][$key3]['codigo'] == $nueva_carrera[$key2]['Carrera']['codigo'] )
						{
							$carrera_formulario_postula[$key1]['Sede'] = array('sede'	=> $dato1['Sede']['nombre'],
																			'codigo'	=> $dato1['Sede']['codigo'],
																			'cantidad'	=> $dato1['Sede']['cantidad']);
							$carrera_formulario_postula[$key1]['Carrera'][] = array('carrera'	=> $dato3['nombre'],
																					'codigo'	=> $dato3['codigo'],
																					'cantidad'	=> $dato3['cantidad']);
						}
					}
				}
			}
			
			//calzamos las sedes para vista finalmente
			$sede_vista = array();
			foreach($nueva_sede as $key2 => $dato2)
			{
				foreach($carrera_formulario_postula as $llave  => $parametro)
				{
					if($carrera_formulario_postula[$llave]['Sede']['codigo'] == $nueva_sede[$key2]['Sede']['codigo'] )
					{
						
						foreach($carrera_formulario_postula[$llave]['Carrera'] as $index => $parametro2)
						{
							$sede_vista[$llave]['Sede'] = array('sede'	=> $parametro['Sede']['sede'],
																'codigo'	=> $parametro['Sede']['codigo'],
																'cantidad'	=> $parametro['Sede']['cantidad']);
							
							$sede_vista[$llave]['Carrera'][] = array('carrera'	=> $parametro2['carrera'],
																	'codigo'	=> $parametro2['codigo'],
																	'cantidad'	=> $parametro2['cantidad']);
						}
						
					}
				}
			}
			
			//calzamos las Carreras para vista finalmente
			$carrera_vista = array();
			foreach($nueva_carrera as $primer => $primer_dato)
			{
				foreach($sede_formulario_postula as $llave  => $parametro)
				{
				
					if($sede_formulario_postula[$llave]['Carrera']['codigo'] == $nueva_carrera[$primer]['Carrera']['codigo'] )
					{
						
						foreach($sede_formulario_postula[$llave]['Sede'] as $index => $parametro4)
						{
							$carrera_vista[$llave]['Carrera'] = array('carrera'	=> $sede_formulario_postula[$llave]['Carrera']['carrera'],
																	'codigo'	=> $sede_formulario_postula[$llave]['Carrera']['codigo'],
																	'cantidad'	=> $sede_formulario_postula[$llave]['Carrera']['cantidad']);
							$carrera_vista[$llave]['Sede'][] = array('sede'	=> $parametro4['sede'],
																'codigo'	=> $parametro4['codigo'],
																'cantidad'	=> $parametro4['cantidad']);
							
							
						}
						
					}
				}
			}
			
			$barras = $this->data['Postulaciones']['barras'];
			$tortas = $this->data['Postulaciones']['tortas'];
			$t_sede		= $sede_formulario;
			$t_carrera	= $carrera_formulario;
			
			//para postulaciones por sedes
			$this->set('total_sedes',$t_sede);
			//para postulaciones por carrera
			$this->set('total_carreras',$t_carrera);
			//fechas
			$this->set(compact('desde','hasta'));
			//para estadisticas por carrera
			$this->set('estadistica_por_carrera',$carrera_vista);
			//para estadisticas por sede
			
			$this->set('estadisticas_por_sede',$sede_vista);			
		}
		
		
		
		
		//Esta función muestra los mismos datos que el excel pero 
		//sin tener que importarlos a los mismos y permite jugar con ellos.
		
		function verDetalleOrientadores() {			
				$datos = $this->Session->read('Datos');
				$fecha_inicio = $datos['Reporteria']['fecha_desde'];
				$fecha_final = $datos['Reporteria']['fecha_hasta'];
				$orientadores_elegidos = $datos['Orientador'];				
				$carreras_elegidas = $datos['Carrera'];				
				foreach ($orientadores_elegidos as $k=> $orientador){ //Rellenamos con los nombres los orientadores según el código
					$orienta = $this->Administrativo->find('first', array('conditions' => array('Administrativo.codigo' => $orientador['orientador'])));
					if ($orienta <> null){
						$orientadores_elegidos[$k]['Nombre'] = $orienta['Administrativo']['nombre'];
					}
				
				}
				foreach ($carreras_elegidas as $k=> $carrera){ //Rellenamos con los nombres las carreras según el código
					$carr = $this->Carrera->find('first', array('conditions' => array('Carrera.codigo' => $carrera['orientador'])));
					if ($carr <> null){
						$carreras_elegidas[$k]['carrera'] = $carr['Carrera']['nombre'];
					}				
				}
				
				$this->set('orientadores',$orientadores_elegidos);	
				$this->set('carreras',$carreras_elegidas);	
				$this->set('fecha_final',$fecha_final);	
				$this->set('fecha_inicio',$fecha_inicio);	
				
				

			$desde2 = date('Y-d-m', strtotime($fecha_inicio));			
			$hasta = str_replace( '/', '-',$fecha_final);				
			$hasta2 = date('Y-m-d',strtotime($hasta));	

			
			//MODIFICACIÓN PARA OBTENER SOLAMENTE LOS NOMBRES DE LAS ESTADÍSTICAS. 
			$condiciones = array( 
						'fields' => array(
							'Entrevista.codigo',
							'Entrevista.created',
							'Entrevista.estado',
							'Entrevista.modified',
							'Entrevista.postulacion_codigo',
							'Postulaciones.codigo',
							'Postulaciones.ciudad_codigo',
							'Postulaciones.sede_codigo',
							'Postulaciones.jornada',						
							'Postulantes.codigo',						
							'Postulantes.nombre',						
							'Postulantes.email',						
							'Administrativos.nombre',						
							'Administrativos.codigo as orientador',						
							'Administrativos.carrera_codigo',						
							'Carreras.nombre',
							'Horarios.hora_inicio',
							'Horarios.fecha'
						),
						'joins' => array(
							array(
								'type' => 'left',
								'alias' => 'Postulaciones',
								'table' => 'RAP_POSTULACIONES',
								'conditions' => array(
									'Postulaciones.codigo = Entrevista.postulacion_codigo'
								)
							), 
							array(
								'type' => 'left',
								'alias' => 'Postulantes',
								'table' => 'RAP_POSTULANTES',
								'conditions' => array(
									'Postulantes.codigo = Entrevista.postulante_codigo'
								)
							),
							array(
								'type' => 'left',
								'alias' => 'Administrativos',
								'table' => 'RAP_ADMINISTRATIVOS',
								'conditions' => array(
									'Administrativos.codigo = Entrevista.administrativo_codigo'
								)
							),
							array(
								'type' => 'left',
								'alias' => 'Carreras',
								'table' => 'RAP_CARRERAS',
								'conditions' => array(
									'Carreras.codigo = Administrativos.carrera_codigo'
								)
							),
							array(
								'type' => 'left',
								'alias' => 'Horarios',
								'table' => 'RAP_HORARIOS',
								'conditions' => array(
									'Horarios.codigo = Entrevista.horario_codigo'
								)
							)
						),
						'order' => array('Horarios.fecha' => 'DESC'),
						'conditions'=> array(
								'Entrevista.created >= ' => $desde2,
								'Entrevista.created <= ' => $hasta2,								
						   ));
			$entrevistas = $this->Entrevista->find('all', $condiciones);
			

			// DEBEMOS ELIMINAR DE ESTE ARRAY LOS ORIENTADORES Y LAS CARRERAS CUYOS CÓDIGOS NO ESTÉN ELEGIDOS POR EL USUARIO EN LOS CHECKBOXES
			$carreras_elegidas = $datos['Carrera'];
			
			//Aquí se muestran todos los códigos de las carreras elegidas por el usuario
			$checks = array();
			foreach ($carreras_elegidas as $carrera) {	
					$codigo_carrera = (implode($carrera));
					array_push($checks,$codigo_carrera); 
			}			
			//echo var_dump($checks);	

			//Aquí se muestran todos los códigos de los orientadores elegidas por el usuario
			$checks2 = array();
			$orientadores_elegidos = $datos['Orientador'];
			foreach ($orientadores_elegidos as $orientador) {	
					$codigo_orientador = (implode($orientador));
					array_push($checks2,$codigo_orientador); 
			}			
			//echo var_dump($checks2);
			
			//Con esto obtenemos los códigos de las carreras de las entrevistas que se han realizado.
			$carreras_entrevistas = array();
			foreach ($entrevistas as $k=> $entrevista){
					$codigo_carrera = (($entrevista['Administrativos']['carrera_codigo']));
					if (!in_array($codigo_carrera,$carreras_entrevistas)){
						array_push($carreras_entrevistas,$codigo_carrera); 
					}	
					//Buscamos el código de la postulacion para ponerle el estado del postulante
					$estado = $this->EstadoPostulacion($entrevista['Postulaciones']['codigo']);
					//echo var_dump($estado);
					$entrevistas[$k]['Postulaciones']['Estado'] = $estado;//El estado lo agregamos al array de Entrevistas para mostrarlo en el excel
			}
			//echo var_dump($entrevistas);
			//die;
			//echo var_dump($carreras_entrevistas);			
			$resultado = array_intersect($checks, $carreras_entrevistas);	

			//Con esto obtenemos los códigos de las carreras de las entrevistas que se han realizado.
			$orientadores_entrevistas = array();
			foreach ($entrevistas as $entrevista){
					$codigo_orientador = (($entrevista['Administrativos']['orientador']));
					if (!in_array($codigo_orientador,$carreras_entrevistas)){
						array_push($orientadores_entrevistas,$codigo_orientador); 
					}	
			}
			//echo var_dump($carreras_entrevistas);			
			$resultado2 = array_intersect($checks2, $orientadores_entrevistas);			

			//Limpiamos el array de las entrevistas que no han sido elegidas sus carreras y los orientadores
			
			foreach ($entrevistas as $k => $entrevista) {
				if (!in_array($entrevista['Administrativos']['carrera_codigo'], $resultado)){
					//echo var_dump('No está');
					unset($entrevistas[$k]);				
				}
				if (!in_array($entrevista['Administrativos']['orientador'], $resultado2)){
					//echo var_dump('No está este orientador');
					unset($entrevistas[$k]);				
				}
			}						
			$this->set('entrevistas2',$entrevistas);	
		}		
		
		
		//Esta función muestra los mismos datos que el excel pero 
		//sin tener que importarlos a los mismos y permite jugar con ellos POSTULACIONES.
		
		function verDetallePostulaciones() {			
				$datos = $this->Session->read('Datos');
				$fecha_inicio = $datos['Postulaciones']['fecha_desde'];
				$fecha_final = $datos['Postulaciones']['fecha_hasta'];			
				$carreras_elegidas = $datos['Carrera'];			
				$sedes_elegidas = $datos['Sede'];			

				foreach ($carreras_elegidas as $k=> $carrera){ //Rellenamos con los nombres las carreras según el código
					$carr = $this->Carrera->find('first', array('conditions' => array('Carrera.codigo' => $carrera['codigo'])));
					if ($carr <> null){
						$carreras_elegidas[$k]['carrera'] = $carr['Carrera']['nombre'];
					}				
				}
				foreach ($sedes_elegidas as $k=> $sede){ //Rellenamos con los nombres las carreras según el código
					$sed = $this->Sede->find('first', array('conditions' => array('Sede.codigo_sede' => $sede['codigo'])));
					if ($sed <> null){
						$sedes_elegidas[$k]['sede'] = $sed['Sede']['nombre_sede'];
					}				
				}	
				$this->set('carreras',$carreras_elegidas);	
				$this->set('sedes',$sedes_elegidas);	
				$this->set('fecha_final',$fecha_final);	
				$this->set('fecha_inicio',$fecha_inicio);	

				$desde = $fecha_inicio;
				$desde2 = date('Y-d-m', strtotime($desde));
				$hasta = $fecha_final;
				$hasta = str_replace( '/', '-',$hasta);		
				$hasta2 = date('Y-m-j',strtotime($hasta));	
			
			//MODIFICACIÓN PARA OBTENER LOS NOMBRES DE LAS ESTADÍSTICAS DE POSTULACIONES RAP REQ. 2015. 
			$condiciones = array( 
						'fields' => array(
							'Postulacion.codigo',
							'Postulacion.postulante_codigo',
							'Postulacion.carrera_codigo',
							'Postulacion.ciudad_codigo',
							'Postulacion.sede_codigo',
							'Postulantes.codigo',
							'Postulantes.nombre',
							'Postulantes.email',
							'Sedes.nombre_sede',
							'Carreras.nombre',
							'Ciudades.nombre',
						),
 						'joins' => array(
							array(
								'type' => 'left',
								'alias' => 'Postulantes',
								'table' => 'RAP_POSTULANTES',
								'conditions' => array(
									'Postulantes.codigo = Postulacion.postulante_codigo'
								)
							),
							array(								
								'type' => 'left',
								'alias' => 'Carreras',
								'table' => 'RAP_CARRERAS',
								'conditions' => array(
									'Carreras.codigo = Postulacion.carrera_codigo'
								)
							),
							array(
								'type' => 'left',
								'alias' => 'Sedes',
								'table' => 'RAP_SEDES',
								'conditions' => array(
									'Sedes.codigo_sede = Postulacion.sede_codigo'
								)
							),
							array(
								'type' => 'left',
								'alias' => 'Ciudades',
								'table' => 'RAP_CIUDADES',
								'conditions' => array(
									'Ciudades.codigo = Postulacion.ciudad_codigo'
								)
							)
						),
/* 						'order' => array(
							'Carreras.nombre DESC'						
						), */
						'conditions'=> array(
								'Postulacion.created >= ' => $desde2,
								'Postulacion.created <= ' => $hasta2,								
						   ));
			$postulaciones = $this->Postulacion->find('all', $condiciones);
				
			// DEBEMOS ELIMINAR DE ESTE ARRAY LOS ORIENTADORES Y LAS CARRERAS CUYOS CÓDIGOS NO ESTÉN ELEGIDOS POR EL USUARIO EN LOS CHECKBOXES			
			$carreras_elegidas = $datos['Carrera'];			
			//Aquí se muestran todos los códigos de las carreras elegidas por el usuario
			$checks = array();
			foreach ($carreras_elegidas as $i => $carrera) {	
					if ($carrera['codigo'] <> 0){ 
						$codigo_carrera = (implode($carrera));
						array_push($checks,$codigo_carrera); 
					}
			}			
			
			//Aquí se muestran todos los códigos de los orientadores elegidas por el usuario
			$checks2 = array();
			$sedes_elegidas = $datos['Sede'];
			foreach ($sedes_elegidas as $sede) {	
					$codigo_sede = (implode($sede));
					array_push($checks2,$codigo_sede); 
			}			

			//Con esto obtenemos los códigos de las carreras de las entrevistas que se han realizado.
			$carreras_postulaciones = array();

			foreach ($postulaciones as $k=> $postulacion){
					$codigo_carrera = (($postulacion['Postulacion']['carrera_codigo']));
					if (!in_array($codigo_carrera,$carreras_postulaciones)){
						array_push($carreras_postulaciones,$codigo_carrera); 
					}	
					//Buscamos el código de la postulacion para ponerle el estado del postulante
					$estado = $this->EstadoPostulacion($postulacion['Postulacion']['codigo']);					
					$postulaciones[$k]['Postulaciones']['Estado'] = $estado; //El estado lo agregamos al array de Entrevistas para mostrarlo en el excel
			}	
			$resultado = array_intersect($checks, $carreras_postulaciones);	

			//Con esto obtenemos los códigos de las sedes de las postulaciones
			$sedes_postulaciones = array();
			foreach ($postulaciones as $postulacion){
					$codigo_sede = (($postulacion['Postulacion']['sede_codigo']));
					if (!in_array($codigo_sede,$sedes_postulaciones)){
						array_push($sedes_postulaciones,$codigo_sede); 
					}	
			}		
			$resultado2 = array_intersect($checks2, $sedes_postulaciones);	
			
			//Limpiamos el array de las POSTULACIONES que no han sido elegidas sus carreras,
			//sus sedes en el formulario de reporteria			
			foreach ($postulaciones as $k => $postulacion) {
				if (!in_array($postulacion['Postulacion']['carrera_codigo'], $resultado)){					
					unset($postulaciones[$k]);				
				}
				if (!in_array($postulacion['Postulacion']['sede_codigo'], $resultado2)){					
					unset($postulaciones[$k]);				
				} 
			}	

			$this->set('postulaciones2',$postulaciones);
		}
		
		
		
		protected function totales($desde= null,$hasta = null)
		{
				$condiciones = array('conditions' => array("Postulacion.created BETWEEN TO_DATE('".$desde." 00:00:00','DD-MM-YYYY HH24:MI:SS') AND TO_DATE('".$hasta." 23:59:59','DD-MM-YYYY HH24:MI:SS')"));
			
			
			$postulacion_totales = $this->Postulacion->find('all',$condiciones);
			
			//prx(count($postulacion_totales));
			return $postulacion_totales;
		}
		
		
		protected function deserto($desde= null,$hasta = null)
		{
				$condiciones = array('conditions' => array("Postulacion.created BETWEEN TO_DATE('".$desde." 00:00:00','DD-MM-YYYY HH24:MI:SS') AND TO_DATE('".$hasta." 23:59:59','DD-MM-YYYY HH24:MI:SS')",
														   'Postulacion.motivo_desactivacion !=' =>  null));
			
			
			$postulacion_totales = $this->Postulacion->find('all',$condiciones);
			return $postulacion_totales;
		}
		
		private function finales($desde= null,$hasta = null,$numero = null)
		{
			$condiciones2 = array('conditions' => array("Postulacion.created BETWEEN TO_DATE('".$desde." 00:00:00','DD-MM-YYYY HH24:MI:SS') AND TO_DATE('".$hasta." 23:59:59','DD-MM-YYYY HH24:MI:SS')"),
								 'fields'	=> array('Postulacion.carrera_codigo','Carreras.codigo',
													 'Sedes.codigo_sede','Carreras.nombre','Sedes.nombre_sede',
													 'EstadosPostulaciones.postulacion_codigo','MAX (EstadosPostulaciones.estado_codigo) as maximo'),
								 'group'	=> array('Postulacion.carrera_codigo','Carreras.codigo',
													 'Sedes.codigo_sede','Carreras.nombre',
													 'Sedes.nombre_sede','EstadosPostulaciones.postulacion_codigo',
													 'EstadosPostulaciones.estado_codigo',
													 'EstadosPostulaciones.postulacion_codigo HAVING MAX(EstadosPostulaciones.estado_codigo) ='.$numero),
								'joins' 	=> array(array('table' => 'RAP_CARRERAS',
															'alias' => 'Carreras',
															'type' => 'INNER',
															'conditions' => array('Carreras.codigo=Postulacion.carrera_codigo')),
													 array('table' => 'RAP_SEDES',
															'alias' => 'Sedes',
															'type' => 'INNER',
															'conditions' => array('Sedes.codigo_sede=Postulacion.sede_codigo')),
													 array('table' => 'RAP_ESTADOS_POSTULACIONES',
															'alias' => 'EstadosPostulaciones',
															'type' => 'INNER',
															'conditions' => array('Postulacion.codigo=EstadosPostulaciones.postulacion_codigo'))));
			
			
			$postulacion_finalizada = $this->Postulacion->find('all',$condiciones2);
			return $postulacion_finalizada;
		}
		
		private function rechaso($desde= null,$hasta = null,$numero = null)
		{
			$condiciones2 = array('conditions' => array("Postulacion.created BETWEEN TO_DATE('".$desde." 00:00:00','DD-MM-YYYY HH24:MI:SS') AND TO_DATE('".$hasta." 23:59:59','DD-MM-YYYY HH24:MI:SS')",
														'Postulacion.motivo_rechazo !=' =>  null),
								 'fields'	=> array('Postulacion.carrera_codigo','Carreras.codigo',
													 'Sedes.codigo_sede','Carreras.nombre','Sedes.nombre_sede',
													 'EstadosPostulaciones.postulacion_codigo','MAX (EstadosPostulaciones.estado_codigo) as maximo'),
								 'group'	=> array('Postulacion.carrera_codigo','Carreras.codigo',
													 'Sedes.codigo_sede','Carreras.nombre',
													 'Sedes.nombre_sede','EstadosPostulaciones.postulacion_codigo',
													 'EstadosPostulaciones.estado_codigo',
													 'EstadosPostulaciones.postulacion_codigo HAVING MAX(EstadosPostulaciones.estado_codigo) ='.$numero),
								'joins' 	=> array(array('table' => 'RAP_CARRERAS',
															'alias' => 'Carreras',
															'type' => 'INNER',
															'conditions' => array('Carreras.codigo=Postulacion.carrera_codigo')),
													 array('table' => 'RAP_SEDES',
															'alias' => 'Sedes',
															'type' => 'INNER',
															'conditions' => array('Sedes.codigo_sede=Postulacion.sede_codigo')),
													 array('table' => 'RAP_ESTADOS_POSTULACIONES',
															'alias' => 'EstadosPostulaciones',
															'type' => 'INNER',
															'conditions' => array('Postulacion.codigo=EstadosPostulaciones.postulacion_codigo'))));
			
			
			$postulacion_rechaso = $this->Postulacion->find('all',$condiciones2);
			return $postulacion_rechaso;
		}
		
		private function carrera($desde= null,$hasta = null)
		{
			$condiciones = array('conditions' => array("Postulacion.created BETWEEN TO_DATE('".$desde." 00:00:00','DD-MM-YYYY HH24:MI:SS') AND TO_DATE('".$hasta." 23:59:59','DD-MM-YYYY HH24:MI:SS')"),
								  'fields' => array('Postulacion.carrera_codigo','Postulacion.sede_codigo'));
								
			$totales_carreras_por_sede = $this->Postulacion->find('all',$condiciones);
			
			
			$carreras = array();
			$carrera_tabla = array();
			$sedes_tabla = array();
			foreach($totales_carreras_por_sede as $key => $carreras_postulacion)
			{
				$carrera_tabla[$key] = $this->Carrera->find('first', array('conditions' => array('Carrera.codigo' => $totales_carreras_por_sede[$key]['Postulacion']['carrera_codigo']),
																	 'fields' => array('Carrera.codigo','Carrera.nombre')));
				$sedes_tabla[$key] = $this->Sede->find('first', array('conditions' => array('Sede.codigo_sede' => $totales_carreras_por_sede[$key]['Postulacion']['sede_codigo']),
																  'fields' => array('Sede.nombre_sede','Sede.codigo_sede')));
			
				$carreras[$key] = array('Carrera'	=> array('nombre'	=> $carrera_tabla[$key]['Carrera']['nombre'],
															 'codigo'	=> $carrera_tabla[$key]['Carrera']['codigo']),
										'Sede'		=> array('nombre_sede'	=> $sedes_tabla[$key]['Sede']['nombre_sede'],
															 'codigo_sede'	=> $sedes_tabla[$key]['Sede']['codigo_sede']));
				
			}
			
			//agrupamos las carreras por sedes
			$arrayCarreraSede = array();
			foreach($carreras as $index =>  $elemento2)
			{
				
				if(array_key_exists($elemento2['Carrera']['codigo'], $arrayCarreraSede))
				{
					$arrayCarreraSede[$elemento2['Carrera']['codigo']]['Carrera']['nombre'] = $elemento2['Carrera']['nombre'];
					$arrayCarreraSede[$elemento2['Carrera']['codigo']]['Carrera']['codigo'] = $elemento2['Carrera']['codigo'];
					$arrayCarreraSede[$elemento2['Carrera']['codigo']]['Carrera']['cantidad'] += 1;
					
					
					$arrayCarreraSede[$elemento2['Carrera']['codigo']]['Sede'][$elemento2['Sede']['codigo_sede']]['nombre'] = $elemento2['Sede']['nombre_sede'];
					$arrayCarreraSede[$elemento2['Carrera']['codigo']]['Sede'][$elemento2['Sede']['codigo_sede']]['codigo'] = $elemento2['Sede']['codigo_sede'];
					if(isset($arrayCarreraSede[$elemento2['Carrera']['codigo']]['Sede'][$elemento2['Sede']['codigo_sede']]['cantidad']))
					{
						$arrayCarreraSede[$elemento2['Carrera']['codigo']]['Sede'][$elemento2['Sede']['codigo_sede']]['cantidad'] += 1;
					}else{
						$arrayCarreraSede[$elemento2['Carrera']['codigo']]['Sede'][$elemento2['Sede']['codigo_sede']]['cantidad'] =+ 1;
					}
				
				}else{
					$arrayCarreraSede[$elemento2['Carrera']['codigo']]['Carrera']['nombre'] = $elemento2['Carrera']['nombre'];
					$arrayCarreraSede[$elemento2['Carrera']['codigo']]['Carrera']['codigo'] = $elemento2['Carrera']['codigo'];
					$arrayCarreraSede[$elemento2['Carrera']['codigo']]['Carrera']['cantidad'] = 1;
					
					$arrayCarreraSede[$elemento2['Carrera']['codigo']]['Sede'][$elemento2['Sede']['codigo_sede']]['nombre'] = $elemento2['Sede']['nombre_sede'];
					$arrayCarreraSede[$elemento2['Carrera']['codigo']]['Sede'][$elemento2['Sede']['codigo_sede']]['codigo'] = $elemento2['Sede']['codigo_sede'];
					$arrayCarreraSede[$elemento2['Carrera']['codigo']]['Sede'][$elemento2['Sede']['codigo_sede']]['cantidad'] = 1;
				}
			}
			return $arrayCarreraSede;
		}
		
		private function sedes($desde= null,$hasta = null)
		{
			$condiciones = array('conditions' => array("Postulacion.created BETWEEN TO_DATE('".$desde." 00:00:00','DD-MM-YYYY HH24:MI:SS') AND TO_DATE('".$hasta." 23:59:59','DD-MM-YYYY HH24:MI:SS')"),
								 'fields' => array('Postulacion.sede_codigo'));
								
			$totales_sede = $this->Postulacion->find('all',$condiciones);
			
			
			$carreras = array();
			
			foreach($totales_sede as $key => $carreras_postulacion)
			{
				
				$sedes_tabla[$key] = $this->Sede->find('first', array('conditions' => array('Sede.codigo_sede' => $carreras_postulacion['Postulacion']['sede_codigo']),
																  'fields' => array('Sede.nombre_sede','Sede.codigo_sede')));
				$carreras[$key] = array('Sede'		=> array('nombre_sede'	=> $sedes_tabla[$key]['Sede']['nombre_sede'],
															 'codigo_sede'	=> $sedes_tabla[$key]['Sede']['codigo_sede']));
			}
			//prx($carreras);
			//agrupamos las carreras por sedes
			$arraySede = array();
			foreach($carreras as $index =>  $elemento2)
			{
				
				if(array_key_exists($elemento2['Sede']['codigo_sede'], $arraySede))
				{
					
					$arraySede[$elemento2['Sede']['codigo_sede']]['Sede']['nombre'] = $elemento2['Sede']['nombre_sede'];
					$arraySede[$elemento2['Sede']['codigo_sede']]['Sede']['codigo'] = $elemento2['Sede']['codigo_sede'];
					$arraySede[$elemento2['Sede']['codigo_sede']]['Sede']['cantidad'] += 1; 
					
				
				}else{
					
					$arraySede[$elemento2['Sede']['codigo_sede']]['Sede']['nombre'] = $elemento2['Sede']['nombre_sede'];
					$arraySede[$elemento2['Sede']['codigo_sede']]['Sede']['codigo'] = $elemento2['Sede']['codigo_sede'];
					$arraySede[$elemento2['Sede']['codigo_sede']]['Sede']['cantidad'] = 1; 
					
				}
			}
			return $arraySede;
		}
		
		private function sedes_carrera($desde= null,$hasta = null)
		{
			$condiciones = array('conditions' => array("Postulacion.created BETWEEN TO_DATE('".$desde." 00:00:00','DD-MM-YYYY HH24:MI:SS') AND TO_DATE('".$hasta." 23:59:59','DD-MM-YYYY HH24:MI:SS')"),
								  'fields' => array('Postulacion.carrera_codigo','Postulacion.sede_codigo'));
								
			$totales_carreras_por_sede = $this->Postulacion->find('all',$condiciones);
			
			
			$carreras = array();
			
			foreach($totales_carreras_por_sede as $key => $carreras_postulacion)
			{
				$carrera_tabla[$key] = $this->Carrera->find('first', array('conditions' => array('Carrera.codigo' => $carreras_postulacion['Postulacion']['carrera_codigo']),
																	 'fields' => array('Carrera.codigo','Carrera.nombre')));
				$sedes_tabla[$key] = $this->Sede->find('first', array('conditions' => array('Sede.codigo_sede' => $carreras_postulacion['Postulacion']['sede_codigo']),
																  'fields' => array('Sede.nombre_sede','Sede.codigo_sede')));
				$carreras[$key] = array('Carrera'	=> array('nombre'	=> $carrera_tabla[$key]['Carrera']['nombre'],
															 'codigo'	=> $carrera_tabla[$key]['Carrera']['codigo']),
										'Sede'		=> array('nombre_sede'	=> $sedes_tabla[$key]['Sede']['nombre_sede'],
															 'codigo_sede'	=> $sedes_tabla[$key]['Sede']['codigo_sede']));
			}
			
			//agrupamos las carreras por sedes
			$arraySedeCarrera = array();
			
			foreach($carreras as $index =>  $elemento2)
			{
				
				if(array_key_exists($elemento2['Sede']['codigo_sede'], $arraySedeCarrera))
				{
					
					$arraySedeCarrera[$elemento2['Sede']['codigo_sede']]['Sede']['nombre'] = $elemento2['Sede']['nombre_sede'];
					$arraySedeCarrera[$elemento2['Sede']['codigo_sede']]['Sede']['codigo'] = $elemento2['Sede']['codigo_sede'];
					$arraySedeCarrera[$elemento2['Sede']['codigo_sede']]['Sede']['cantidad'] += 1;
					
					$arraySedeCarrera[$elemento2['Sede']['codigo_sede']]['Carrera'][$elemento2['Carrera']['codigo']]['nombre'] = $elemento2['Carrera']['nombre'];
					$arraySedeCarrera[$elemento2['Sede']['codigo_sede']]['Carrera'][$elemento2['Carrera']['codigo']]['codigo'] = $elemento2['Carrera']['codigo'];
					if(isset($arraySedeCarrera[$elemento2['Sede']['codigo_sede']]['Carrera'][$elemento2['Carrera']['codigo']]['cantidad']))
					{
						$arraySedeCarrera[$elemento2['Sede']['codigo_sede']]['Carrera'][$elemento2['Carrera']['codigo']]['cantidad'] += 1;
					}else{
						$arraySedeCarrera[$elemento2['Sede']['codigo_sede']]['Carrera'][$elemento2['Carrera']['codigo']]['cantidad'] =+ 1;
					}
				
				}else{
					
					$arraySedeCarrera[$elemento2['Sede']['codigo_sede']]['Sede']['nombre'] = $elemento2['Sede']['nombre_sede'];
					$arraySedeCarrera[$elemento2['Sede']['codigo_sede']]['Sede']['codigo'] = $elemento2['Sede']['codigo_sede'];
					$arraySedeCarrera[$elemento2['Sede']['codigo_sede']]['Sede']['cantidad'] = 1;
					
					$arraySedeCarrera[$elemento2['Sede']['codigo_sede']]['Carrera'][$elemento2['Carrera']['codigo']]['nombre'] = $elemento2['Carrera']['nombre'];
					$arraySedeCarrera[$elemento2['Sede']['codigo_sede']]['Carrera'][$elemento2['Carrera']['codigo']]['codigo'] = $elemento2['Carrera']['codigo'];
					$arraySedeCarrera[$elemento2['Sede']['codigo_sede']]['Carrera'][$elemento2['Carrera']['codigo']]['cantidad'] = 1;
				}
			}
			return $arraySedeCarrera;
		}
		
		public function escuelas(){
			$escuelas = $this->Escuela->find('list', array(
					'fields' => array(
							'id', 'nombre'
						),
					'order' => 'Escuela.nombre asc' 
				));
			$this->set('escuelas', $escuelas);
		}
		
		function ajax_escuelas(){		
			 $this->layout='ajax';
			 $this->Session->write('Datos',$this->data);
			 
			 $si_barra = $this->data['Escuelas']['barras'];
			 $si_torta = $this->data['Escuelas']['tortas'];

			 $this->set(compact('si_barra','si_torta'));

			 $escuelas = $this->data['Escuela'];
			 $escuelas_carreras = array();

			 $escuelas_rs = array();
			 

			 foreach($escuelas as $key => $escuela) {
			 	if ($escuela <> 0) {
					 $carreras = $this->EscuelaCarrera->find('list', array(
					 		'conditions' => array(
					 				'EscuelaCarrera.escuela_codigo' => $escuela
					 			),
					 		'fields' => array('carrera_codigo', 'carrera_codigo')
					 	));
					 $array_tmp = array();
					 foreach ($carreras as $car) {
					 	$array_tmp[$car] = $car;
					 }
					 $carreras_nombre = array();
					 foreach ($array_tmp as $carr) {
						 $carrera_nombre = $this->Carrera->find('list', array(
						 		'conditions' => array(
						 				'Carrera.codigo' => $carr
						 			),
						 		'fields' => array('codigo', 'nombre')
						 	));
						 $carreras_nombre[$carr] = $carrera_nombre[$carr];
		 			}

		 			asort($carreras_nombre);
		 			$carreras = array();
		 			foreach($carreras_nombre as $key1 => $carrr) {
		 				$carreras[$key1] = $key1;
		 			}	 			
					 $escuelas_carreras[$key] = $carreras;
					 $escuelas_rs[$escuela] = 1;
				}else{
					$escuelas_rs[$escuela] = 0;
				}
			}

			if ($this->data['Escuelas']['en_revision'] == 1) {
				$si_en_revision = true;
			} else {
				$si_en_revision = false;
			}

			$this->set('si_en_revision',$si_en_revision);

			if ($this->data['Escuelas']['no_habilitado'] == 1) {
				$si_no_habilitado = true;
			} else {
				$si_no_habilitado = false;
			}

			$this->set('si_no_habilitado',$si_no_habilitado);

				$si_habilitado = true;



			if ($this->data['Escuelas']['habilitado_sin_firma'] == 1) {
				$si_habilitado_sin_firma = true;
			} else {
				$si_habilitado_sin_firma = false;
			}
			$this->set('si_habilitado_sin_firma',$si_habilitado_sin_firma);



			if ($this->data['Escuelas']['habilitado_firma'] == 1) {
				$si_habilitado_firma = true;
			} else {
				$si_habilitado_firma= false;
			}
			$this->set('si_habilitado_firma',$si_habilitado_firma);


			if ($this->data['Escuelas']['habilitado_csa'] == 1) {
				$si_habilitado_csa = true;
			} else {
				$si_habilitado_csa = false;
			}
			$this->set('si_habilitado_csa',$si_habilitado_csa);



	        $desde = $this->data['Escuelas']['fecha_desde'];
			$fecha_inicio = date('Y-d-m', strtotime($desde));
			$hasta = $this->data['Escuelas']['fecha_hasta'];
			$hasta = str_replace( '/', '-',$hasta);	
			$hasta1 = $hasta.' 23:59:59';	
			$fecha_termino = date('Y-m-j H:i:s',strtotime($hasta1));	
			$totales = array();
			$tot_tot = 0;



			foreach ($escuelas_carreras as $key_escuela => $carreras) {
				$nom_escuela = $this->Escuela->find('first', array(
						'conditions' => array(
								'Escuela.id' => $key_escuela
							),
						'fields' => array('nombre')
					));
				$total_en_revision = 0;
				$total_no_habilitado = 0;
				$total_habilitado = 0;
				$total_firma = 0;
				$total_csa = 0;
				foreach($carreras as $key_carrera => $carrera) {
					$tmp_en_revision = 0;
						$tmp_en_revision = $this->Postulacion->find('count', array(
								'conditions' => array(
										'Postulacion.created >=' => $fecha_inicio,
	                            		'Postulacion.created <=' => $fecha_termino,
	                            		'Postulacion.carrera_codigo' => $carrera,
	                            		'Postulacion.habilitado' => null,
	                            		'Postulacion.tipo' => 'AH'
									)
							));
						$total_en_revision = $total_en_revision + $tmp_en_revision;

						$nombre_carrera = $this->Carrera->find('first', array(
								'conditions' => array(
										'Carrera.codigo' => $carrera
									),
								'fields' => array('Carrera.nombre')
							));


						$totales[$key_escuela][$carrera]['nombre_carrera'] = $nombre_carrera['Carrera']['nombre'];
						$totales[$key_escuela][$carrera]['en_revision'] = $tmp_en_revision;
					$tmp_no_habilitado = 0;
						$tmp_no_habilitado = $this->Postulacion->find('count', array(
								'conditions' => array(
										'Postulacion.created >=' => $fecha_inicio,
	                            		'Postulacion.created <=' => $fecha_termino,
	                            		'Postulacion.carrera_codigo' => $carrera,
	                            		'Postulacion.habilitado' => 0,
	                            		'Postulacion.tipo' => 'AH'
									)
							));
						$total_no_habilitado = $total_no_habilitado + $tmp_no_habilitado;
						$nombre_carrera = $this->Carrera->find('first', array(
								'conditions' => array(
										'Carrera.codigo' => $carrera
									),
								'fields' => array('Carrera.nombre')
							));


						$totales[$key_escuela][$carrera]['nombre_carrera'] = $nombre_carrera['Carrera']['nombre'];
						$totales[$key_escuela][$carrera]['no_habilitado'] = $tmp_no_habilitado;

					$tmp_habilitado = 0;
						$tmp_habilitado = $this->Postulacion->find('count', array(
								'conditions' => array(
										'Postulacion.created >=' => $fecha_inicio,
	                            		'Postulacion.created <=' => $fecha_termino,
	                            		'Postulacion.carrera_codigo' => $carrera,
	                            		'Postulacion.habilitado' => 1,
	                            		'Postulacion.tipo' => 'AH'
									)
							));
						$total_habilitado = $total_habilitado + $tmp_habilitado;
						$nombre_carrera = $this->Carrera->find('first', array(
								'conditions' => array(
										'Carrera.codigo' => $carrera
									),
								'fields' => array('Carrera.nombre')
							));


						$totales[$key_escuela][$carrera]['nombre_carrera'] = $nombre_carrera['Carrera']['nombre'];
						$totales[$key_escuela][$carrera]['habilitado'] = $tmp_habilitado;


						$tmp_firma = $this->Postulacion->find('count', array(
								'conditions' => array(
										'Postulacion.created >=' => $fecha_inicio,
	                            		'Postulacion.created <=' => $fecha_termino,
	                            		'Postulacion.carrera_codigo' => $carrera,
	                            		'Postulacion.firma' => 1,
	                            		'Postulacion.tipo' => 'AH'
									)
							));
						$total_firma = $total_firma + $tmp_firma;
						$nombre_carrera = $this->Carrera->find('first', array(
								'conditions' => array(
										'Carrera.codigo' => $carrera
									),
								'fields' => array('Carrera.nombre')
							));


						$totales[$key_escuela][$carrera]['nombre_carrera'] = $nombre_carrera['Carrera']['nombre'];
						$totales[$key_escuela][$carrera]['firma'] = $tmp_firma;



						$tmp_csa = $this->Postulacion->find('count', array(
								'conditions' => array(
										'Postulacion.created >=' => $fecha_inicio,
	                            		'Postulacion.created <=' => $fecha_termino,
	                            		'Postulacion.carrera_codigo' => $carrera,
	                            		'Postulacion.csa' => 1,
	                            		'Postulacion.tipo' => 'AH'
									)
							));
						$total_csa = $total_csa + $tmp_csa;
						$nombre_carrera = $this->Carrera->find('first', array(
								'conditions' => array(
										'Carrera.codigo' => $carrera
									),
								'fields' => array('Carrera.nombre')
							));


						$totales[$key_escuela][$carrera]['nombre_carrera'] = $nombre_carrera['Carrera']['nombre'];
						$totales[$key_escuela][$carrera]['csa'] = $tmp_csa;


				}
				$cant_carreras = 0;
					$cant_carreras = count($totales[$key_escuela]);
					$totales[$key_escuela]['nombre_escuela'] = $nom_escuela['Escuela']['nombre'];
					$totales[$key_escuela]['en_revision'] = $total_en_revision;
					$totales[$key_escuela]['no_habilitado'] = $total_no_habilitado;
					$totales[$key_escuela]['habilitado'] = $total_habilitado;
					$totales[$key_escuela]['firma'] = $total_firma - $total_csa;
					$totales[$key_escuela]['csa'] = $total_csa;
					$totales[$key_escuela]['total'] = $total_en_revision + $total_no_habilitado + $total_habilitado;
					$totales[$key_escuela]['total_carreras'] = $cant_carreras;
					$tot_tot = $tot_tot + $total_en_revision + $total_no_habilitado + $total_habilitado;
					//array_multisort($totales, SORT_NUMERIC, SORT_DESC);
			}
			//debug($totales);

			//Debugger::dump($totales);
			$this->set(compact('totales', 'tot_tot'));
			$this->set(compact('desde', 'hasta'));



			// primer grafico
			 $escuelas = $this->data['Escuela'];

			 foreach($escuelas as $key => $escuela) {
			 	$escuelas[$key] = $key;
			 	
			 }
			 $escuelas_carreras = array();

			 foreach($escuelas as $key => $escuela) {
			 	if ($escuela <> 0) {
					$carreras = $this->EscuelaCarrera->find('list', array(
					 		'conditions' => array(
					 				'EscuelaCarrera.escuela_codigo' => $escuela
					 			),
					 		'fields' => array('carrera_codigo', 'carrera_codigo')
					 	));
					$escuelas_carreras[$key] = $carreras;
					
				}
			}

				$si_en_revision = true;

			if ($this->data['Escuelas']['no_habilitado'] == 1) {
				$si_no_habilitado = true;
			} else {
				$si_no_habilitado = false;
			}

				$si_habilitado = true;


	        $desde = $this->data['Escuelas']['fecha_desde'];
			$fecha_inicio = date('Y-d-m', strtotime($desde));
			$hasta = $this->data['Escuelas']['fecha_hasta'];
			$hasta = str_replace( '/', '-',$hasta);	
			$hasta1 = $hasta.' 23:59:59';	
			$fecha_termino = date('Y-m-j H:i:s',strtotime($hasta1));	
			$totales = array();
			$tot_tot = 0;



			foreach ($escuelas_carreras as $key_escuela => $carreras) {
				$nom_escuela = $this->Escuela->find('first', array(
						'conditions' => array(
								'Escuela.id' => $key_escuela
							),
						'fields' => array('nombre')
					));
				$total_en_revision = 0;
				$total_no_habilitado = 0;
				$total_habilitado = 0;
				$total_firma = 0;
				$total_csa = 0;
				foreach($carreras as $key_carrera => $carrera) {
					$tmp_en_revision = 0;
						$tmp_en_revision = $this->Postulacion->find('count', array(
								'conditions' => array(
										'Postulacion.created >=' => $fecha_inicio,
	                            		'Postulacion.created <=' => $fecha_termino,
	                            		'Postulacion.carrera_codigo' => $carrera,
	                            		'Postulacion.habilitado' => null,
	                            		'Postulacion.tipo' => 'AH'
									)
							));
						$total_en_revision = $total_en_revision + $tmp_en_revision;

						$nombre_carrera = $this->Carrera->find('first', array(
								'conditions' => array(
										'Carrera.codigo' => $carrera
									),
								'fields' => array('Carrera.nombre')
							));


						$totales[$key_escuela][$carrera]['nombre_carrera'] = $nombre_carrera['Carrera']['nombre'];
						$totales[$key_escuela][$carrera]['en_revision'] = $tmp_en_revision;


					$tmp_no_habilitado = 0;
					if ($si_no_habilitado) {
						$tmp_no_habilitado = $this->Postulacion->find('count', array(
								'conditions' => array(
										'Postulacion.created >=' => $fecha_inicio,
	                            		'Postulacion.created <=' => $fecha_termino,
	                            		'Postulacion.carrera_codigo' => $carrera,
	                            		'Postulacion.habilitado' => 0,
	                            		'Postulacion.tipo' => 'AH'
									)
							));
						$total_no_habilitado = $total_no_habilitado + $tmp_no_habilitado;
						$nombre_carrera = $this->Carrera->find('first', array(
								'conditions' => array(
										'Carrera.codigo' => $carrera
									),
								'fields' => array('Carrera.nombre')
							));


						$totales[$key_escuela][$carrera]['nombre_carrera'] = $nombre_carrera['Carrera']['nombre'];
						$totales[$key_escuela][$carrera]['no_habilitado'] = $tmp_no_habilitado;

					}
					$tmp_habilitado = 0;
					if ($si_habilitado) {
						$tmp_habilitado = $this->Postulacion->find('count', array(
								'conditions' => array(
										'Postulacion.created >=' => $fecha_inicio,
	                            		'Postulacion.created <=' => $fecha_termino,
	                            		'Postulacion.carrera_codigo' => $carrera,
	                            		'Postulacion.habilitado' => 1,
	                            		'Postulacion.tipo' => 'AH'
									)
							));
						$total_habilitado = $total_habilitado + $tmp_habilitado;
						$nombre_carrera = $this->Carrera->find('first', array(
								'conditions' => array(
										'Carrera.codigo' => $carrera
									),
								'fields' => array('Carrera.nombre')
							));


						$totales[$key_escuela][$carrera]['nombre_carrera'] = $nombre_carrera['Carrera']['nombre'];
						$totales[$key_escuela][$carrera]['habilitado'] = $tmp_habilitado;


						$tmp_firma = $this->Postulacion->find('count', array(
								'conditions' => array(
										'Postulacion.created >=' => $fecha_inicio,
	                            		'Postulacion.created <=' => $fecha_termino,
	                            		'Postulacion.carrera_codigo' => $carrera,
	                            		'Postulacion.firma' => 1,
	                            		'Postulacion.tipo' => 'AH'
									)
							));
						$total_firma = $total_firma + $tmp_firma;
						$nombre_carrera = $this->Carrera->find('first', array(
								'conditions' => array(
										'Carrera.codigo' => $carrera
									),
								'fields' => array('Carrera.nombre')
							));


						$totales[$key_escuela][$carrera]['nombre_carrera'] = $nombre_carrera['Carrera']['nombre'];
						$totales[$key_escuela][$carrera]['firma'] = $tmp_firma;



						$tmp_csa = $this->Postulacion->find('count', array(
								'conditions' => array(
										'Postulacion.created >=' => $fecha_inicio,
	                            		'Postulacion.created <=' => $fecha_termino,
	                            		'Postulacion.carrera_codigo' => $carrera,
	                            		'Postulacion.csa' => 1,
	                            		'Postulacion.tipo' => 'AH'
									)
							));
						$total_csa = $total_csa + $tmp_csa;
						$nombre_carrera = $this->Carrera->find('first', array(
								'conditions' => array(
										'Carrera.codigo' => $carrera
									),
								'fields' => array('Carrera.nombre')
							));


						$totales[$key_escuela][$carrera]['nombre_carrera'] = $nombre_carrera['Carrera']['nombre'];
						$totales[$key_escuela][$carrera]['csa'] = $tmp_csa;

					}

				}
				$totales_1[$key_escuela]['key_escuela'] = intval($key_escuela);
				$totales_1[$key_escuela]['nombre_escuela'] = $nom_escuela['Escuela']['nombre'];
				$totales_1[$key_escuela]['en_revision'] = $total_en_revision;
				$totales_1[$key_escuela]['no_habilitado'] = $total_no_habilitado;
				$totales_1[$key_escuela]['habilitado'] = $total_habilitado;
				$totales_1[$key_escuela]['firma'] = $total_firma - $total_csa;
				$totales_1[$key_escuela]['csa'] = $total_csa;
				$totales_1[$key_escuela]['total'] = $total_en_revision + $total_no_habilitado + $total_habilitado;
				$tot_tot_1 = $tot_tot + $total_en_revision + $total_no_habilitado + $total_habilitado;

			}			
			//Debugger::dump($totales_1);
			$this->set(compact('totales_1', 'tot_tot_1', 'escuelas_rs'));

		}
		
		
		function ajax_escuelas_order(){		
			 $this->layout='ajax';
			 $this->Session->write('Datos',$this->data);
			 
			 $si_barra = $this->data['Escuelas']['barras'];
			 $si_torta = $this->data['Escuelas']['tortas'];

			 $order_rs = $this->data['order'];
			 $column_rs = $this->data['column'];

			 $this->set(compact('si_barra','si_torta'));

			 $escuelas = $this->data['Escuela'];
			 $escuelas_carreras = array();

			 foreach($escuelas as $key => $escuela) {
			 	if ($escuela <> 0) {
					 $carreras = $this->EscuelaCarrera->find('list', array(
					 		'conditions' => array(
					 				'EscuelaCarrera.escuela_codigo' => $escuela
					 			),
					 		'fields' => array('carrera_codigo', 'carrera_codigo')
					 	));
					 $array_tmp = array();
					 foreach ($carreras as $car) {
					 	$array_tmp[$car] = $car;
					 }
					 $carreras_nombre = array();
					 foreach ($array_tmp as $carr) {
						 $carrera_nombre = $this->Carrera->find('list', array(
						 		'conditions' => array(
						 				'Carrera.codigo' => $carr
						 			),
						 		'fields' => array('codigo', 'nombre')
						 	));
						 $carreras_nombre[$carr] = $carrera_nombre[$carr];
		 			}

		 			asort($carreras_nombre);
		 			$carreras = array();
		 			foreach($carreras_nombre as $key1 => $carrr) {
		 				$carreras[$key1] = $key1;
		 			}	 			
					 $escuelas_carreras[$key] = $carreras;

				}
			}

			if ($this->data['Escuelas']['en_revision'] == 1) {
				$si_en_revision = true;
			} else {
				$si_en_revision = false;
			}

			$this->set('si_en_revision',$si_en_revision);

			if ($this->data['Escuelas']['no_habilitado'] == 1) {
				$si_no_habilitado = true;
			} else {
				$si_no_habilitado = false;
			}

			$this->set('si_no_habilitado',$si_no_habilitado);

				$si_habilitado = true;



			if ($this->data['Escuelas']['habilitado_sin_firma'] == 1) {
				$si_habilitado_sin_firma = true;
			} else {
				$si_habilitado_sin_firma = false;
			}
			$this->set('si_habilitado_sin_firma',$si_habilitado_sin_firma);



			if ($this->data['Escuelas']['habilitado_firma'] == 1) {
				$si_habilitado_firma = true;
			} else {
				$si_habilitado_firma= false;
			}
			$this->set('si_habilitado_firma',$si_habilitado_firma);


			if ($this->data['Escuelas']['habilitado_csa'] == 1) {
				$si_habilitado_csa = true;
			} else {
				$si_habilitado_csa = false;
			}
			$this->set('si_habilitado_csa',$si_habilitado_csa);



	        $desde = $this->data['Escuelas']['fecha_desde'];
			$fecha_inicio = date('Y-d-m', strtotime($desde));
			$hasta = $this->data['Escuelas']['fecha_hasta'];
			$hasta = str_replace( '/', '-',$hasta);	
			$hasta1 = $hasta.' 23:59:59';	
			$fecha_termino = date('Y-m-j H:i:s',strtotime($hasta1));	
			$totales = array();
			$tot_tot = 0;



			foreach ($escuelas_carreras as $key_escuela => $carreras) {
				$nom_escuela = $this->Escuela->find('first', array(
						'conditions' => array(
								'Escuela.id' => $key_escuela
							),
						'fields' => array('nombre')
					));
				$total_en_revision = 0;
				$total_no_habilitado = 0;
				$total_habilitado = 0;
				$total_firma = 0;
				$total_csa = 0;
				foreach($carreras as $key_carrera => $carrera) {
					$tmp_en_revision = 0;
						$tmp_en_revision = $this->Postulacion->find('count', array(
								'conditions' => array(
										'Postulacion.created >=' => $fecha_inicio,
	                            		'Postulacion.created <=' => $fecha_termino,
	                            		'Postulacion.carrera_codigo' => $carrera,
	                            		'Postulacion.habilitado' => null,
	                            		'Postulacion.tipo' => 'AH'
									)
							));
						$total_en_revision = $total_en_revision + $tmp_en_revision;

						$nombre_carrera = $this->Carrera->find('first', array(
								'conditions' => array(
										'Carrera.codigo' => $carrera
									),
								'fields' => array('Carrera.nombre')
							));


						$totales[$key_escuela][$carrera]['nombre_carrera'] = $nombre_carrera['Carrera']['nombre'];
						$totales[$key_escuela][$carrera]['en_revision'] = $tmp_en_revision;
					$tmp_no_habilitado = 0;
						$tmp_no_habilitado = $this->Postulacion->find('count', array(
								'conditions' => array(
										'Postulacion.created >=' => $fecha_inicio,
	                            		'Postulacion.created <=' => $fecha_termino,
	                            		'Postulacion.carrera_codigo' => $carrera,
	                            		'Postulacion.habilitado' => 0,
	                            		'Postulacion.tipo' => 'AH'
									)
							));
						$total_no_habilitado = $total_no_habilitado + $tmp_no_habilitado;
						$nombre_carrera = $this->Carrera->find('first', array(
								'conditions' => array(
										'Carrera.codigo' => $carrera
									),
								'fields' => array('Carrera.nombre')
							));


						$totales[$key_escuela][$carrera]['nombre_carrera'] = $nombre_carrera['Carrera']['nombre'];
						$totales[$key_escuela][$carrera]['no_habilitado'] = $tmp_no_habilitado;

					$tmp_habilitado = 0;
						$tmp_habilitado = $this->Postulacion->find('count', array(
								'conditions' => array(
										'Postulacion.created >=' => $fecha_inicio,
	                            		'Postulacion.created <=' => $fecha_termino,
	                            		'Postulacion.carrera_codigo' => $carrera,
	                            		'Postulacion.habilitado' => 1,
	                            		'Postulacion.tipo' => 'AH'
									)
							));
						$total_habilitado = $total_habilitado + $tmp_habilitado;
						$nombre_carrera = $this->Carrera->find('first', array(
								'conditions' => array(
										'Carrera.codigo' => $carrera
									),
								'fields' => array('Carrera.nombre')
							));


						$totales[$key_escuela][$carrera]['nombre_carrera'] = $nombre_carrera['Carrera']['nombre'];
						$totales[$key_escuela][$carrera]['habilitado'] = $tmp_habilitado;


						$tmp_firma = $this->Postulacion->find('count', array(
								'conditions' => array(
										'Postulacion.created >=' => $fecha_inicio,
	                            		'Postulacion.created <=' => $fecha_termino,
	                            		'Postulacion.carrera_codigo' => $carrera,
	                            		'Postulacion.firma' => 1,
	                            		'Postulacion.tipo' => 'AH'
									)
							));
						$total_firma = $total_firma + $tmp_firma;
						$nombre_carrera = $this->Carrera->find('first', array(
								'conditions' => array(
										'Carrera.codigo' => $carrera
									),
								'fields' => array('Carrera.nombre')
							));


						$totales[$key_escuela][$carrera]['nombre_carrera'] = $nombre_carrera['Carrera']['nombre'];
						$totales[$key_escuela][$carrera]['firma'] = $tmp_firma;



						$tmp_csa = $this->Postulacion->find('count', array(
								'conditions' => array(
										'Postulacion.created >=' => $fecha_inicio,
	                            		'Postulacion.created <=' => $fecha_termino,
	                            		'Postulacion.carrera_codigo' => $carrera,
	                            		'Postulacion.csa' => 1,
	                            		'Postulacion.tipo' => 'AH'
									)
							));
						$total_csa = $total_csa + $tmp_csa;
						$nombre_carrera = $this->Carrera->find('first', array(
								'conditions' => array(
										'Carrera.codigo' => $carrera
									),
								'fields' => array('Carrera.nombre')
							));


						$totales[$key_escuela][$carrera]['nombre_carrera'] = $nombre_carrera['Carrera']['nombre'];
						$totales[$key_escuela][$carrera]['csa'] = $tmp_csa;


				}
				$cant_carreras = 0;
					$cant_carreras = count($totales[$key_escuela]);
					$totales[$key_escuela]['nombre_escuela'] = $nom_escuela['Escuela']['nombre'];
					$totales[$key_escuela]['en_revision'] = $total_en_revision;
					$totales[$key_escuela]['no_habilitado'] = $total_no_habilitado;
					$totales[$key_escuela]['habilitado'] = $total_habilitado;
					$totales[$key_escuela]['firma'] = $total_firma - $total_csa;
					$totales[$key_escuela]['csa'] = $total_csa;
					$totales[$key_escuela]['total'] = $total_en_revision + $total_no_habilitado + $total_habilitado;
					$totales[$key_escuela]['total_carreras'] = $cant_carreras;
					$tot_tot = $tot_tot + $total_en_revision + $total_no_habilitado + $total_habilitado;
			}

			$this->set(compact('totales', 'tot_tot'));
			$this->set(compact('desde', 'hasta'));



			// primer grafico
			 $escuelas = $this->data['Escuela'];
			 foreach($escuelas as $key => $escuela) {
			 	$escuelas[$key] = $key;
			 }
			 $escuelas_carreras = array();

			 foreach($escuelas as $key => $escuela) {
			 	if ($escuela <> 0) {
					 $carreras = $this->EscuelaCarrera->find('list', array(
					 		'conditions' => array(
					 				'EscuelaCarrera.escuela_codigo' => $escuela
					 			),
					 		'fields' => array('carrera_codigo', 'carrera_codigo')
					 	));
					 $escuelas_carreras[$key] = $carreras;
				}
			}

				$si_en_revision = true;

			if ($this->data['Escuelas']['no_habilitado'] == 1) {
				$si_no_habilitado = true;
			} else {
				$si_no_habilitado = false;
			}

				$si_habilitado = true;


	        $desde = $this->data['Escuelas']['fecha_desde'];
			$fecha_inicio = date('Y-d-m', strtotime($desde));
			$hasta = $this->data['Escuelas']['fecha_hasta'];
			$hasta = str_replace( '/', '-',$hasta);	
			$hasta1 = $hasta.' 23:59:59';	
			$fecha_termino = date('Y-m-j H:i:s',strtotime($hasta1));	
			$totales = array();
			$tot_tot = 0;



			foreach ($escuelas_carreras as $key_escuela => $carreras) {
				$nom_escuela = $this->Escuela->find('first', array(
						'conditions' => array(
								'Escuela.id' => $key_escuela
							),
						'fields' => array('nombre')
					));
				$total_en_revision = 0;
				$total_no_habilitado = 0;
				$total_habilitado = 0;
				$total_firma = 0;
				$total_csa = 0;
				foreach($carreras as $key_carrera => $carrera) {
					$tmp_en_revision = 0;
						$tmp_en_revision = $this->Postulacion->find('count', array(
								'conditions' => array(
										'Postulacion.created >=' => $fecha_inicio,
	                            		'Postulacion.created <=' => $fecha_termino,
	                            		'Postulacion.carrera_codigo' => $carrera,
	                            		'Postulacion.habilitado' => null,
	                            		'Postulacion.tipo' => 'AH'
									)
							));
						$total_en_revision = $total_en_revision + $tmp_en_revision;

						$nombre_carrera = $this->Carrera->find('first', array(
								'conditions' => array(
										'Carrera.codigo' => $carrera
									),
								'fields' => array('Carrera.nombre')
							));


						$totales[$key_escuela][$carrera]['nombre_carrera'] = $nombre_carrera['Carrera']['nombre'];
						$totales[$key_escuela][$carrera]['en_revision'] = $tmp_en_revision;


					$tmp_no_habilitado = 0;
					if ($si_no_habilitado) {
						$tmp_no_habilitado = $this->Postulacion->find('count', array(
								'conditions' => array(
										'Postulacion.created >=' => $fecha_inicio,
	                            		'Postulacion.created <=' => $fecha_termino,
	                            		'Postulacion.carrera_codigo' => $carrera,
	                            		'Postulacion.habilitado' => 0,
	                            		'Postulacion.tipo' => 'AH'
									)
							));
						$total_no_habilitado = $total_no_habilitado + $tmp_no_habilitado;
						$nombre_carrera = $this->Carrera->find('first', array(
								'conditions' => array(
										'Carrera.codigo' => $carrera
									),
								'fields' => array('Carrera.nombre')
							));


						$totales[$key_escuela][$carrera]['nombre_carrera'] = $nombre_carrera['Carrera']['nombre'];
						$totales[$key_escuela][$carrera]['no_habilitado'] = $tmp_no_habilitado;

					}
					$tmp_habilitado = 0;
					if ($si_habilitado) {
						$tmp_habilitado = $this->Postulacion->find('count', array(
								'conditions' => array(
										'Postulacion.created >=' => $fecha_inicio,
	                            		'Postulacion.created <=' => $fecha_termino,
	                            		'Postulacion.carrera_codigo' => $carrera,
	                            		'Postulacion.habilitado' => 1,
	                            		'Postulacion.tipo' => 'AH'
									)
							));
						$total_habilitado = $total_habilitado + $tmp_habilitado;
						$nombre_carrera = $this->Carrera->find('first', array(
								'conditions' => array(
										'Carrera.codigo' => $carrera
									),
								'fields' => array('Carrera.nombre')
							));


						$totales[$key_escuela][$carrera]['nombre_carrera'] = $nombre_carrera['Carrera']['nombre'];
						$totales[$key_escuela][$carrera]['habilitado'] = $tmp_habilitado;


						$tmp_firma = $this->Postulacion->find('count', array(
								'conditions' => array(
										'Postulacion.created >=' => $fecha_inicio,
	                            		'Postulacion.created <=' => $fecha_termino,
	                            		'Postulacion.carrera_codigo' => $carrera,
	                            		'Postulacion.firma' => 1,
	                            		'Postulacion.tipo' => 'AH'
									)
							));
						$total_firma = $total_firma + $tmp_firma;
						$nombre_carrera = $this->Carrera->find('first', array(
								'conditions' => array(
										'Carrera.codigo' => $carrera
									),
								'fields' => array('Carrera.nombre')
							));


						$totales[$key_escuela][$carrera]['nombre_carrera'] = $nombre_carrera['Carrera']['nombre'];
						$totales[$key_escuela][$carrera]['firma'] = $tmp_firma;



						$tmp_csa = $this->Postulacion->find('count', array(
								'conditions' => array(
										'Postulacion.created >=' => $fecha_inicio,
	                            		'Postulacion.created <=' => $fecha_termino,
	                            		'Postulacion.carrera_codigo' => $carrera,
	                            		'Postulacion.csa' => 1,
	                            		'Postulacion.tipo' => 'AH'
									)
							));
						$total_csa = $total_csa + $tmp_csa;
						$nombre_carrera = $this->Carrera->find('first', array(
								'conditions' => array(
										'Carrera.codigo' => $carrera
									),
								'fields' => array('Carrera.nombre')
							));


						$totales[$key_escuela][$carrera]['nombre_carrera'] = $nombre_carrera['Carrera']['nombre'];
						$totales[$key_escuela][$carrera]['csa'] = $tmp_csa;

					}

				}
				$totales_1[$key_escuela]['nombre_escuela'] = $nom_escuela['Escuela']['nombre'];
				$totales_1[$key_escuela]['en_revision'] = $total_en_revision;
				$totales_1[$key_escuela]['no_habilitado'] = $total_no_habilitado;
				$totales_1[$key_escuela]['habilitado'] = $total_habilitado;
				$totales_1[$key_escuela]['firma'] = $total_firma - $total_csa;
				$totales_1[$key_escuela]['csa'] = $total_csa;
				$totales_1[$key_escuela]['total'] = $total_en_revision + $total_no_habilitado + $total_habilitado;
				$tot_tot_1 = $tot_tot + $total_en_revision + $total_no_habilitado + $total_habilitado;

			}			

			$this->set(compact('totales_1', 'tot_tot_1'));

		}

		function ajax_exportar_excel_escuelas() {
			$this->layout = "ajax";

			 $this->data = $this->Session->read('Datos');

			 $escuelas = $this->data['Escuela'];
			 $escuelas_carreras = array();

			 foreach($escuelas as $key => $escuela) {
			 	if ($escuela <> 0) {
					 $carreras = $this->EscuelaCarrera->find('list', array(
					 		'conditions' => array(
					 				'EscuelaCarrera.escuela_codigo' => $escuela
					 			),
					 		'fields' => array('carrera_codigo', 'carrera_codigo')
					 	));
					 $array_tmp = array();
					 foreach ($carreras as $car) {
					 	$array_tmp[$car] = $car;
					 }
					 $carreras_nombre = array();
					 foreach ($array_tmp as $carr) {
						 $carrera_nombre = $this->Carrera->find('list', array(
						 		'conditions' => array(
						 				'Carrera.codigo' => $carr
						 			),
						 		'fields' => array('codigo', 'nombre')
						 	));
						 $carreras_nombre[$carr] = $carrera_nombre[$carr];
		 			}

		 			asort($carreras_nombre);
		 			$carreras = array();
		 			foreach($carreras_nombre as $key1 => $carrr) {
		 				$carreras[$key1] = $key1;
		 			}	 			
					 $escuelas_carreras[$key] = $carreras;

				}
			}

			if ($this->data['Escuelas']['en_revision'] == 1) {
				$si_en_revision = true;
			} else {
				$si_en_revision = false;
			}

			$this->set('si_en_revision',$si_en_revision);

			if ($this->data['Escuelas']['no_habilitado'] == 1) {
				$si_no_habilitado = true;
			} else {
				$si_no_habilitado = false;
			}

			$this->set('si_no_habilitado',$si_no_habilitado);

				$si_habilitado = true;



			if ($this->data['Escuelas']['habilitado_sin_firma'] == 1) {
				$si_habilitado_sin_firma = true;
			} else {
				$si_habilitado_sin_firma = false;
			}
			$this->set('si_habilitado_sin_firma',$si_habilitado_sin_firma);



			if ($this->data['Escuelas']['habilitado_firma'] == 1) {
				$si_habilitado_firma = true;
			} else {
				$si_habilitado_firma= false;
			}
			$this->set('si_habilitado_firma',$si_habilitado_firma);


			if ($this->data['Escuelas']['habilitado_csa'] == 1) {
				$si_habilitado_csa = true;
			} else {
				$si_habilitado_csa = false;
			}
			$this->set('si_habilitado_csa',$si_habilitado_csa);



	        $desde = $this->data['Escuelas']['fecha_desde'];
			$fecha_inicio = date('Y-d-m', strtotime($desde));
			$hasta = $this->data['Escuelas']['fecha_hasta'];
			$hasta = str_replace( '/', '-',$hasta);	
			$hasta1 = $hasta.' 23:59:59';	
			$fecha_termino = date('Y-m-j H:i:s',strtotime($hasta1));	
			$totales = array();
			$tot_tot = 0;



			foreach ($escuelas_carreras as $key_escuela => $carreras) {
				$nom_escuela = $this->Escuela->find('first', array(
						'conditions' => array(
								'Escuela.id' => $key_escuela
							),
						'fields' => array('nombre')
					));
				$total_en_revision = 0;
				$total_no_habilitado = 0;
				$total_habilitado = 0;
				$total_firma = 0;
				$total_csa = 0;
				foreach($carreras as $key_carrera => $carrera) {
					$tmp_en_revision = 0;
						$tmp_en_revision = $this->Postulacion->find('count', array(
								'conditions' => array(
										'Postulacion.created >=' => $fecha_inicio,
	                            		'Postulacion.created <=' => $fecha_termino,
	                            		'Postulacion.carrera_codigo' => $carrera,
	                            		'Postulacion.habilitado' => null,
	                            		'Postulacion.tipo' => 'AH'
									)
							));
						$total_en_revision = $total_en_revision + $tmp_en_revision;

						$nombre_carrera = $this->Carrera->find('first', array(
								'conditions' => array(
										'Carrera.codigo' => $carrera
									),
								'fields' => array('Carrera.nombre')
							));


						$totales[$key_escuela][$carrera]['nombre_carrera'] = $nombre_carrera['Carrera']['nombre'];
						$totales[$key_escuela][$carrera]['en_revision'] = $tmp_en_revision;
					$tmp_no_habilitado = 0;
						$tmp_no_habilitado = $this->Postulacion->find('count', array(
								'conditions' => array(
										'Postulacion.created >=' => $fecha_inicio,
	                            		'Postulacion.created <=' => $fecha_termino,
	                            		'Postulacion.carrera_codigo' => $carrera,
	                            		'Postulacion.habilitado' => 0,
	                            		'Postulacion.tipo' => 'AH'
									)
							));
						$total_no_habilitado = $total_no_habilitado + $tmp_no_habilitado;
						$nombre_carrera = $this->Carrera->find('first', array(
								'conditions' => array(
										'Carrera.codigo' => $carrera
									),
								'fields' => array('Carrera.nombre')
							));


						$totales[$key_escuela][$carrera]['nombre_carrera'] = $nombre_carrera['Carrera']['nombre'];
						$totales[$key_escuela][$carrera]['no_habilitado'] = $tmp_no_habilitado;

					$tmp_habilitado = 0;
						$tmp_habilitado = $this->Postulacion->find('count', array(
								'conditions' => array(
										'Postulacion.created >=' => $fecha_inicio,
	                            		'Postulacion.created <=' => $fecha_termino,
	                            		'Postulacion.carrera_codigo' => $carrera,
	                            		'Postulacion.habilitado' => 1,
	                            		'Postulacion.tipo' => 'AH'
									)
							));
						$total_habilitado = $total_habilitado + $tmp_habilitado;
						$nombre_carrera = $this->Carrera->find('first', array(
								'conditions' => array(
										'Carrera.codigo' => $carrera
									),
								'fields' => array('Carrera.nombre')
							));


						$totales[$key_escuela][$carrera]['nombre_carrera'] = $nombre_carrera['Carrera']['nombre'];
						$totales[$key_escuela][$carrera]['habilitado'] = $tmp_habilitado;


						$tmp_firma = $this->Postulacion->find('count', array(
								'conditions' => array(
										'Postulacion.created >=' => $fecha_inicio,
	                            		'Postulacion.created <=' => $fecha_termino,
	                            		'Postulacion.carrera_codigo' => $carrera,
	                            		'Postulacion.firma' => 1,
	                            		'Postulacion.tipo' => 'AH'
									)
							));
						$total_firma = $total_firma + $tmp_firma;
						$nombre_carrera = $this->Carrera->find('first', array(
								'conditions' => array(
										'Carrera.codigo' => $carrera
									),
								'fields' => array('Carrera.nombre')
							));


						$totales[$key_escuela][$carrera]['nombre_carrera'] = $nombre_carrera['Carrera']['nombre'];
						$totales[$key_escuela][$carrera]['firma'] = $tmp_firma;



						$tmp_csa = $this->Postulacion->find('count', array(
								'conditions' => array(
										'Postulacion.created >=' => $fecha_inicio,
	                            		'Postulacion.created <=' => $fecha_termino,
	                            		'Postulacion.carrera_codigo' => $carrera,
	                            		'Postulacion.csa' => 1,
	                            		'Postulacion.tipo' => 'AH'
									)
							));
						$total_csa = $total_csa + $tmp_csa;
						$nombre_carrera = $this->Carrera->find('first', array(
								'conditions' => array(
										'Carrera.codigo' => $carrera
									),
								'fields' => array('Carrera.nombre')
							));


						$totales[$key_escuela][$carrera]['nombre_carrera'] = $nombre_carrera['Carrera']['nombre'];
						$totales[$key_escuela][$carrera]['csa'] = $tmp_csa;


				}
				$cant_carreras = 0;
					$cant_carreras = count($totales[$key_escuela]);
					$totales[$key_escuela]['nombre_escuela'] = $nom_escuela['Escuela']['nombre'];
					$totales[$key_escuela]['en_revision'] = $total_en_revision;
					$totales[$key_escuela]['no_habilitado'] = $total_no_habilitado;
					$totales[$key_escuela]['habilitado'] = $total_habilitado;
					$totales[$key_escuela]['firma'] = $total_firma - $total_csa;
					$totales[$key_escuela]['csa'] = $total_csa;
					$totales[$key_escuela]['total'] = $total_en_revision + $total_no_habilitado + $total_habilitado;
					$totales[$key_escuela]['total_carreras'] = $cant_carreras;
					$tot_tot = $tot_tot + $total_en_revision + $total_no_habilitado + $total_habilitado;
			}

			$this->set(compact('totales', 'tot_tot'));
			$this->set(compact('desde', 'hasta'));

			// primer grafico
			 $escuelas = $this->data['Escuela'];
			 foreach($escuelas as $key => $escuela) {
			 	$escuelas[$key] = $key;
			 }
			 $escuelas_carreras = array();

			 foreach($escuelas as $key => $escuela) {
			 	if ($escuela <> 0) {
					 $carreras = $this->EscuelaCarrera->find('list', array(
					 		'conditions' => array(
					 				'EscuelaCarrera.escuela_codigo' => $escuela
					 			),
					 		'fields' => array('carrera_codigo', 'carrera_codigo')
					 	));
					 $escuelas_carreras[$key] = $carreras;
				}
			}

				$si_en_revision = true;

			if ($this->data['Escuelas']['no_habilitado'] == 1) {
				$si_no_habilitado = true;
			} else {
				$si_no_habilitado = false;
			}

				$si_habilitado = true;


	        $desde = $this->data['Escuelas']['fecha_desde'];
			$fecha_inicio = date('Y-d-m', strtotime($desde));
			$hasta = $this->data['Escuelas']['fecha_hasta'];
			$hasta = str_replace( '/', '-',$hasta);	
			$hasta1 = $hasta.' 23:59:59';	
			$fecha_termino = date('Y-m-j H:i:s',strtotime($hasta1));	
			$totales = array();
			$tot_tot = 0;



			foreach ($escuelas_carreras as $key_escuela => $carreras) {
				$nom_escuela = $this->Escuela->find('first', array(
						'conditions' => array(
								'Escuela.id' => $key_escuela
							),
						'fields' => array('nombre')
					));
				$total_en_revision = 0;
				$total_no_habilitado = 0;
				$total_habilitado = 0;
				$total_firma = 0;
				$total_csa = 0;
				foreach($carreras as $key_carrera => $carrera) {
					$tmp_en_revision = 0;
						$tmp_en_revision = $this->Postulacion->find('count', array(
								'conditions' => array(
										'Postulacion.created >=' => $fecha_inicio,
	                            		'Postulacion.created <=' => $fecha_termino,
	                            		'Postulacion.carrera_codigo' => $carrera,
	                            		'Postulacion.habilitado' => null,
	                            		'Postulacion.tipo' => 'AH'
									)
							));
						$total_en_revision = $total_en_revision + $tmp_en_revision;

						$nombre_carrera = $this->Carrera->find('first', array(
								'conditions' => array(
										'Carrera.codigo' => $carrera
									),
								'fields' => array('Carrera.nombre')
							));


						$totales[$key_escuela][$carrera]['nombre_carrera'] = $nombre_carrera['Carrera']['nombre'];
						$totales[$key_escuela][$carrera]['en_revision'] = $tmp_en_revision;


					$tmp_no_habilitado = 0;
					if ($si_no_habilitado) {
						$tmp_no_habilitado = $this->Postulacion->find('count', array(
								'conditions' => array(
										'Postulacion.created >=' => $fecha_inicio,
	                            		'Postulacion.created <=' => $fecha_termino,
	                            		'Postulacion.carrera_codigo' => $carrera,
	                            		'Postulacion.habilitado' => 0,
	                            		'Postulacion.tipo' => 'AH'
									)
							));
						$total_no_habilitado = $total_no_habilitado + $tmp_no_habilitado;
						$nombre_carrera = $this->Carrera->find('first', array(
								'conditions' => array(
										'Carrera.codigo' => $carrera
									),
								'fields' => array('Carrera.nombre')
							));


						$totales[$key_escuela][$carrera]['nombre_carrera'] = $nombre_carrera['Carrera']['nombre'];
						$totales[$key_escuela][$carrera]['no_habilitado'] = $tmp_no_habilitado;

					}
					$tmp_habilitado = 0;
					if ($si_habilitado) {
						$tmp_habilitado = $this->Postulacion->find('count', array(
								'conditions' => array(
										'Postulacion.created >=' => $fecha_inicio,
	                            		'Postulacion.created <=' => $fecha_termino,
	                            		'Postulacion.carrera_codigo' => $carrera,
	                            		'Postulacion.habilitado' => 1,
	                            		'Postulacion.tipo' => 'AH'
									)
							));
						$total_habilitado = $total_habilitado + $tmp_habilitado;
						$nombre_carrera = $this->Carrera->find('first', array(
								'conditions' => array(
										'Carrera.codigo' => $carrera
									),
								'fields' => array('Carrera.nombre')
							));


						$totales[$key_escuela][$carrera]['nombre_carrera'] = $nombre_carrera['Carrera']['nombre'];
						$totales[$key_escuela][$carrera]['habilitado'] = $tmp_habilitado;


						$tmp_firma = $this->Postulacion->find('count', array(
								'conditions' => array(
										'Postulacion.created >=' => $fecha_inicio,
	                            		'Postulacion.created <=' => $fecha_termino,
	                            		'Postulacion.carrera_codigo' => $carrera,
	                            		'Postulacion.firma' => 1,
	                            		'Postulacion.tipo' => 'AH'
									)
							));
						$total_firma = $total_firma + $tmp_firma;
						$nombre_carrera = $this->Carrera->find('first', array(
								'conditions' => array(
										'Carrera.codigo' => $carrera
									),
								'fields' => array('Carrera.nombre')
							));


						$totales[$key_escuela][$carrera]['nombre_carrera'] = $nombre_carrera['Carrera']['nombre'];
						$totales[$key_escuela][$carrera]['firma'] = $tmp_firma;



						$tmp_csa = $this->Postulacion->find('count', array(
								'conditions' => array(
										'Postulacion.created >=' => $fecha_inicio,
	                            		'Postulacion.created <=' => $fecha_termino,
	                            		'Postulacion.carrera_codigo' => $carrera,
	                            		'Postulacion.csa' => 1,
	                            		'Postulacion.tipo' => 'AH'
									)
							));
						$total_csa = $total_csa + $tmp_csa;
						$nombre_carrera = $this->Carrera->find('first', array(
								'conditions' => array(
										'Carrera.codigo' => $carrera
									),
								'fields' => array('Carrera.nombre')
							));


						$totales[$key_escuela][$carrera]['nombre_carrera'] = $nombre_carrera['Carrera']['nombre'];
						$totales[$key_escuela][$carrera]['csa'] = $tmp_csa;

					}

				}
				$totales_1[$key_escuela]['nombre_escuela'] = $nom_escuela['Escuela']['nombre'];
				$totales_1[$key_escuela]['en_revision'] = $total_en_revision;
				$totales_1[$key_escuela]['no_habilitado'] = $total_no_habilitado;
				$totales_1[$key_escuela]['habilitado'] = $total_habilitado;
				$totales_1[$key_escuela]['firma'] = $total_firma - $total_csa;
				$totales_1[$key_escuela]['csa'] = $total_csa;
				$totales_1[$key_escuela]['total'] = $total_en_revision + $total_no_habilitado + $total_habilitado;
				$tot_tot_1 = $tot_tot + $total_en_revision + $total_no_habilitado + $total_habilitado;

			}			

			$this->set(compact('totales_1', 'tot_tot_1'));			
		}

		public function generales(){
			$escuelas = $this->Escuela->find('list', array(
					'fields' => array(
							'id', 'nombre'
						),
					'order' => 'Escuela.nombre asc' 
				));
			$this->set('escuelas', $escuelas);
		}
		


		function ajax_generales(){		




			 $this->layout='ajax';
			 $this->Session->write('Datos',$this->data);

			 $escuelas = $this->data['Escuela'];
			 $escuelas_carreras = array();
			 $lista_escuelas = array();

			 foreach($escuelas as $key => $escuela) {
			 	if ($escuela <> 0) {
					 $carreras = $this->EscuelaCarrera->find('list', array(
					 		'conditions' => array(
					 				'EscuelaCarrera.escuela_codigo' => $escuela
					 			),
					 		'fields' => array('carrera_codigo', 'carrera_codigo')
					 	));
					 $escuelas_carreras[$key] = $carreras;
					 $lista_escuelas[$escuela] =  array();
				}
			}

			if ($this->data['Escuelas']['en_revision'] == 1) {
				$si_en_revision = true;
			} else {
				$si_en_revision = false;
			}
			$this->set('si_en_revision',$si_en_revision);

			if ($this->data['Escuelas']['no_habilitado'] == 1) {
				$si_no_habilitado = true;
			} else {
				$si_no_habilitado = false;
			}
			$this->set('si_no_habilitado',$si_no_habilitado);

			if ($this->data['Escuelas']['Habilitado'] == 1) {
				$si_habilitado = true;
			} else {
				$si_habilitado = false;
			}
			$this->set('si_habilitado',$si_habilitado);

			if ($this->data['Escuelas']['habilitado_firma'] == 1) {
				$si_habilitado_firma = true;
			} else {
				$si_habilitado_firma = false;
			}
			$this->set('si_habilitado_firma',$si_habilitado_firma);

			if ($this->data['Escuelas']['habilitado_csa'] == 1) {
				$si_habilitado_csa = true;
			} else {
				$si_habilitado_csa = false;
			}
			$this->set('si_habilitado_csa',$si_habilitado_csa);


			if ($this->data['Escuelas']['doc_con_obs'] == 1) {
				$si_doc_con_obs = true;
			} else {
				$si_doc_con_obs = false;
			}
			$this->set('si_doc_con_obs',$si_doc_con_obs);

			if ($this->data['Escuelas']['rev_pendiente'] == 1) {
				$si_rev_pendiente = true;
			} else {
				$si_rev_pendiente = false;
			}
			$this->set('si_rev_pendiente',$si_rev_pendiente);

			if ($this->data['Escuelas']['sin_act_cuenta'] == 1) {
				$si_sin_act_cuenta = true;
			} else {
				$si_sin_act_cuenta = false;
			}
			$this->set('si_sin_act_cuenta',$si_sin_act_cuenta);

			if ($this->data['Escuelas']['sin_form_guardado'] == 1) {
				$si_sin_form_guardado = true;
			} else {
				$si_sin_form_guardado = false;
			}
			$this->set('si_sin_form_guardado',$si_sin_form_guardado);			


			if ($this->data['Escuelas']['tipo_articulacion'] == 1) {
				$si_tipo_articulacion = true;
			} else {
				$si_tipo_articulacion = false;
			}
			$this->set('si_tipo_articulacion',$si_tipo_articulacion);		

			if ($this->data['Escuelas']['tipo_escuelas'] == 1) {
				$si_tipo_escuelas = true;
			} else {
				$si_tipo_escuelas = false;
			}
			$this->set('si_tipo_escuelas',$si_tipo_escuelas);		


			if ($this->data['Escuelas']['tipo_rap'] == 1) {
				$si_tipo_rap = true;
			} else {
				$si_tipo_rap = false;
			}
			$this->set('si_tipo_rap',$si_tipo_rap);		

			if ($this->data['Escuelas']['tipo_otros'] == 1) {
				$si_tipo_otros = true;
			} else {
				$si_tipo_otros = false;
			}
			$this->set('si_tipo_otros',$si_tipo_otros);		






	        $desde = $this->data['Escuelas']['fecha_desde'];
			$fecha_inicio = date('Y-d-m', strtotime($desde));
			$hasta = $this->data['Escuelas']['fecha_hasta'];
			$hasta = str_replace( '/', '-',$hasta);	
			$hasta1 = $hasta.' 23:59:59';	
			$fecha_termino = date('Y-m-j H:i:s',strtotime($hasta1));	
			$totales = array();
			$tot_ah = 0;

			foreach ($escuelas_carreras as $key_escuela => $carreras) {
				$nom_escuela = $this->Escuela->find('first', array(
						'conditions' => array(
								'Escuela.id' => $key_escuela
							),
						'fields' => array('nombre')
					));
				$total_en_revision = 0;
				$total_no_habilitado = 0;
				$total_habilitado = 0;
				$total_firma = 0;
				$total_csa = 0;
				foreach($carreras as $key_carrera => $carrera) {
					$tmp_en_revision = 0;
					if ($si_en_revision) {
						$tmp_en_revision = $this->Postulacion->find('count', array(
								'conditions' => array(
										'Postulacion.created >=' => $fecha_inicio,
	                            		'Postulacion.created <=' => $fecha_termino,
	                            		'Postulacion.carrera_codigo' => $carrera,
	                            		'Postulacion.habilitado' => null,
	                            		'Postulacion.tipo' => 'AH'
									)
							));
						$total_en_revision = $total_en_revision + $tmp_en_revision;
					}
					$tmp_no_habilitado = 0;
					if ($si_no_habilitado) {
						$tmp_no_habilitado = $this->Postulacion->find('count', array(
								'conditions' => array(
										'Postulacion.created >=' => $fecha_inicio,
	                            		'Postulacion.created <=' => $fecha_termino,
	                            		'Postulacion.carrera_codigo' => $carrera,
	                            		'Postulacion.habilitado' => 0,
	                            		'Postulacion.tipo' => 'AH'
									)
							));
						$total_no_habilitado = $total_no_habilitado + $tmp_no_habilitado;
					}
					$tmp_habilitado = 0;
					if ($si_habilitado) {
						$tmp_habilitado = $this->Postulacion->find('count', array(
								'conditions' => array(
										'Postulacion.created >=' => $fecha_inicio,
	                            		'Postulacion.created <=' => $fecha_termino,
	                            		'Postulacion.carrera_codigo' => $carrera,
	                            		'Postulacion.habilitado' => 1,
	                            		'Postulacion.tipo' => 'AH'
									)
							));
						$total_habilitado = $total_habilitado + $tmp_habilitado;

						$tmp_firma = $this->Postulacion->find('count', array(
								'conditions' => array(
										'Postulacion.created >=' => $fecha_inicio,
	                            		'Postulacion.created <=' => $fecha_termino,
	                            		'Postulacion.carrera_codigo' => $carrera,
	                            		'Postulacion.firma' => 1,
	                            		'Postulacion.tipo' => 'AH'
									)
							));
						$total_firma = $total_firma + $tmp_firma;

						$tmp_csa = $this->Postulacion->find('count', array(
								'conditions' => array(
										'Postulacion.created >=' => $fecha_inicio,
	                            		'Postulacion.created <=' => $fecha_termino,
	                            		'Postulacion.carrera_codigo' => $carrera,
	                            		'Postulacion.csa' => 1,
	                            		'Postulacion.tipo' => 'AH'
									)
							));
						$total_csa = $total_csa + $tmp_csa;
					}

				}
				$totales['AH'][$key_escuela]['nombre_escuela'] = $nom_escuela['Escuela']['nombre'];
				$totales['AH'][$key_escuela]['en_revision'] = $total_en_revision;
				$totales['AH'][$key_escuela]['no_habilitado'] = $total_no_habilitado;
				$totales['AH'][$key_escuela]['habilitado'] = $total_habilitado;
				$totales['AH'][$key_escuela]['firma'] = $total_firma - $total_csa;
				$totales['AH'][$key_escuela]['csa'] = $total_csa;
				$totales['AH'][$key_escuela]['total'] = $total_en_revision + $total_no_habilitado + $total_habilitado;
				$tot_ah = $tot_ah + $total_en_revision + $total_no_habilitado + $total_habilitado;
			}



			$tot_av = 0;
			foreach ($escuelas_carreras as $key_escuela => $carreras) {
				$nom_escuela = $this->Escuela->find('first', array(
						'conditions' => array(
								'Escuela.id' => $key_escuela
							),
						'fields' => array('nombre')
					));
				$total_en_revision = 0;
				$total_no_habilitado = 0;
				$total_habilitado = 0;
				$total_firma = 0;
				$total_csa = 0;
				foreach($carreras as $key_carrera => $carrera) {
					$tmp_en_revision = 0;
					if ($si_en_revision) {
						$tmp_en_revision = $this->Postulacion->find('count', array(
								'conditions' => array(
										'Postulacion.created >=' => $fecha_inicio,
	                            		'Postulacion.created <=' => $fecha_termino,
	                            		'Postulacion.carrera_codigo' => $carrera,
	                            		'Postulacion.habilitado' => null,
	                            		'Postulacion.tipo' => 'AV'
									)
							));
						$total_en_revision = $total_en_revision + $tmp_en_revision;
					}
					$tmp_no_habilitado = 0;
					if ($si_no_habilitado) {
						$tmp_no_habilitado = $this->Postulacion->find('count', array(
								'conditions' => array(
										'Postulacion.created >=' => $fecha_inicio,
	                            		'Postulacion.created <=' => $fecha_termino,
	                            		'Postulacion.carrera_codigo' => $carrera,
	                            		'Postulacion.habilitado' => 0,
	                            		'Postulacion.tipo' => 'AV'
									)
							));
						$total_no_habilitado = $total_no_habilitado + $tmp_no_habilitado;
					}
					$tmp_habilitado = 0;
					if ($si_habilitado) {
						$tmp_habilitado = $this->Postulacion->find('count', array(
								'conditions' => array(
										'Postulacion.created >=' => $fecha_inicio,
	                            		'Postulacion.created <=' => $fecha_termino,
	                            		'Postulacion.carrera_codigo' => $carrera,
	                            		'Postulacion.habilitado' => 1,
	                            		'Postulacion.tipo' => 'AV'
									)
							));
						$total_habilitado = $total_habilitado + $tmp_habilitado;

						$tmp_firma = $this->Postulacion->find('count', array(
								'conditions' => array(
										'Postulacion.created >=' => $fecha_inicio,
	                            		'Postulacion.created <=' => $fecha_termino,
	                            		'Postulacion.carrera_codigo' => $carrera,
	                            		'Postulacion.firma' => 1,
	                            		'Postulacion.tipo' => 'AV'
									)
							));
						$total_firma = $total_firma + $tmp_firma;

						$tmp_csa = $this->Postulacion->find('count', array(
								'conditions' => array(
										'Postulacion.created >=' => $fecha_inicio,
	                            		'Postulacion.created <=' => $fecha_termino,
	                            		'Postulacion.carrera_codigo' => $carrera,
	                            		'Postulacion.csa' => 1,
	                            		'Postulacion.tipo' => 'AV'
									)
							));
						$total_csa = $total_csa + $tmp_csa;
					}

				}
				$totales['AV'][$key_escuela]['nombre_escuela'] = $nom_escuela['Escuela']['nombre'];
				$totales['AV'][$key_escuela]['en_revision'] = $total_en_revision;
				$totales['AV'][$key_escuela]['no_habilitado'] = $total_no_habilitado;
				$totales['AV'][$key_escuela]['habilitado'] = $total_habilitado;
				$totales['AV'][$key_escuela]['firma'] = $total_firma - $total_csa;
				$totales['AV'][$key_escuela]['csa'] = $total_csa;
				$totales['AV'][$key_escuela]['total'] = $total_en_revision + $total_no_habilitado + $total_habilitado;
				$tot_av = $tot_av + $total_en_revision + $total_no_habilitado + $total_habilitado;
			}


			$tot_rap = 0;
			foreach ($escuelas_carreras as $key_escuela => $carreras) {
				$nom_escuela = $this->Escuela->find('first', array(
						'conditions' => array(
								'Escuela.id' => $key_escuela
							),
						'fields' => array('nombre')
					));
				$total_en_revision = 0;
				$total_no_habilitado = 0;
				$total_habilitado = 0;
				$total_firma = 0;
				$total_csa = 0;
				foreach($carreras as $key_carrera => $carrera) {
					$tmp_en_revision = 0;
					if ($si_en_revision) {
						$tmp_en_revision = $this->Postulacion->find('count', array(
								'conditions' => array(
										'Postulacion.created >=' => $fecha_inicio,
	                            		'Postulacion.created <=' => $fecha_termino,
	                            		'Postulacion.carrera_codigo' => $carrera,
	                            		'Postulacion.habilitado' => null,
	                            		'Postulacion.tipo' => 'RAP'
									)
							));
						$total_en_revision = $total_en_revision + $tmp_en_revision;
					}
					$tmp_no_habilitado = 0;
					if ($si_no_habilitado) {
						$tmp_no_habilitado = $this->Postulacion->find('count', array(
								'conditions' => array(
										'Postulacion.created >=' => $fecha_inicio,
	                            		'Postulacion.created <=' => $fecha_termino,
	                            		'Postulacion.carrera_codigo' => $carrera,
	                            		'Postulacion.habilitado' => 0,
	                            		'Postulacion.tipo' => 'RAP'
									)
							));
						$total_no_habilitado = $total_no_habilitado + $tmp_no_habilitado;
					}
					$tmp_habilitado = 0;
					if ($si_habilitado) {
						$tmp_habilitado = $this->Postulacion->find('count', array(
								'conditions' => array(
										'Postulacion.created >=' => $fecha_inicio,
	                            		'Postulacion.created <=' => $fecha_termino,
	                            		'Postulacion.carrera_codigo' => $carrera,
	                            		'Postulacion.habilitado' => 1,
	                            		'Postulacion.tipo' => 'RAP'
									)
							));
						$total_habilitado = $total_habilitado + $tmp_habilitado;

						$tmp_firma = $this->Postulacion->find('count', array(
								'conditions' => array(
										'Postulacion.created >=' => $fecha_inicio,
	                            		'Postulacion.created <=' => $fecha_termino,
	                            		'Postulacion.carrera_codigo' => $carrera,
	                            		'Postulacion.firma' => 1,
	                            		'Postulacion.tipo' => 'RAP'
									)
							));
						$total_firma = $total_firma + $tmp_firma;

						$tmp_csa = $this->Postulacion->find('count', array(
								'conditions' => array(
										'Postulacion.created >=' => $fecha_inicio,
	                            		'Postulacion.created <=' => $fecha_termino,
	                            		'Postulacion.carrera_codigo' => $carrera,
	                            		'Postulacion.csa' => 1,
	                            		'Postulacion.tipo' => 'RAP'
									)
							));
						$total_csa = $total_csa + $tmp_csa;
					}

				}
				$totales['RAP'][$key_escuela]['nombre_escuela'] = $nom_escuela['Escuela']['nombre'];
				$totales['RAP'][$key_escuela]['en_revision'] = $total_en_revision;
				$totales['RAP'][$key_escuela]['no_habilitado'] = $total_no_habilitado;
				$totales['RAP'][$key_escuela]['habilitado'] = $total_habilitado;
				$totales['RAP'][$key_escuela]['firma'] = $total_firma - $total_csa;
				$totales['RAP'][$key_escuela]['csa'] = $total_csa;
				$totales['RAP'][$key_escuela]['total'] = $total_en_revision + $total_no_habilitado + $total_habilitado;
				$tot_rap= $tot_rap + $total_en_revision + $total_no_habilitado + $total_habilitado;
			}

			$nombres_escuelas = array();
			foreach($totales['AH'] as $escuela) {
				$nombres_escuelas[] = "'".$escuela['nombre_escuela']."'";
			}

			foreach($escuelas as $key => $esc) {
				$escuelas[$key] = 0;
			}
			$nombres_escuelas = implode(',', $nombres_escuelas);

			$this->set(compact('totales', 'tot_ah', 'tot_av', 'tot_rap', 'escuelas', 'nombres_escuelas'));
			$this->set(compact('desde', 'hasta'));


			// OTROS postulantes sin activacion cuenta
			$postulantes_sin_activacion_cuenta =  $this->Postulante->find('count', array(
					'conditions' => array(
							'Postulante.activo' => 0
						)
				));
			$this->set('postulantes_sin_activacion_cuenta', $postulantes_sin_activacion_cuenta);

			// OTROS postulantes sin formulario guardado

			$postulantes = $this->Postulante->find('list', array(
					'fields' => array('codigo', 'codigo'),
					'conditions' => array(
							'Postulante.activo' => 1
						)
				));

			$postulantes_sin_formulario_guardado = 0;
			foreach($postulantes as $postulante) {
				$si_postulacion = $this->Postulacion->find('first', array(
						'conditions' => array(
										'Postulacion.postulante_codigo' => $postulante
									)
							)
					);
				if (!$si_postulacion) {
					$postulantes_sin_formulario_guardado++;
				}
			}

			$this->set('postulantes_sin_formulario_guardado', $postulantes_sin_formulario_guardado);

			// Prepostulaciones en "Documento con observaciones"

			$tot_prepostulaciones_documento_con_observaciones = $lista_escuelas;


			foreach($lista_escuelas as $key => $esc) {
				$prepostulaciones_documento_con_observaciones_escuela = $this->Prepostulacion->find('count',array(
						'conditions' => array(
								'Prepostulacion.destino' => null,
								'Prepostulacion.revision' => 1,
								'Prepostulacion.escuela_id' => $key
							)
					));
				$tot_prepostulaciones_documento_con_observaciones[$key] =  $prepostulaciones_documento_con_observaciones_escuela;

			}

			$this->set('tot_prepostulaciones_documento_con_observaciones', $tot_prepostulaciones_documento_con_observaciones);


			// Prepostulaciones en "En Revision"

			$tot_prepostulaciones_documento_con_observaciones = $lista_escuelas;


			foreach($lista_escuelas as $key => $esc) {
				$prepostulaciones_en_revision = $this->Prepostulacion->find('count',array(
						'conditions' => array(
								'Prepostulacion.destino' => null,
								'Prepostulacion.revision' => null,
								'Prepostulacion.escuela_id' => $key
							)
					));
				$tot_prepostulaciones_en_revision[$key] =  $prepostulaciones_en_revision;

			}

			$this->set('tot_prepostulaciones_en_revision', $tot_prepostulaciones_en_revision);


		}


		function ajax_exportar_excel_generales(){		




			 $this->layout='ajax';
			 $this->data = $this->Session->read('Datos');


			 $escuelas = $this->data['Escuela'];
			 $escuelas_carreras = array();
			 $lista_escuelas = array();

			 foreach($escuelas as $key => $escuela) {
			 	if ($escuela <> 0) {
					 $carreras = $this->EscuelaCarrera->find('list', array(
					 		'conditions' => array(
					 				'EscuelaCarrera.escuela_codigo' => $escuela
					 			),
					 		'fields' => array('carrera_codigo', 'carrera_codigo')
					 	));
					 $escuelas_carreras[$key] = $carreras;
					 $lista_escuelas[$escuela] =  array();
				}
			}

			if ($this->data['Escuelas']['en_revision'] == 1) {
				$si_en_revision = true;
			} else {
				$si_en_revision = false;
			}
			$this->set('si_en_revision',$si_en_revision);

			if ($this->data['Escuelas']['no_habilitado'] == 1) {
				$si_no_habilitado = true;
			} else {
				$si_no_habilitado = false;
			}
			$this->set('si_no_habilitado',$si_no_habilitado);

			if ($this->data['Escuelas']['Habilitado'] == 1) {
				$si_habilitado = true;
			} else {
				$si_habilitado = false;
			}
			$this->set('si_habilitado',$si_habilitado);

			if ($this->data['Escuelas']['habilitado_firma'] == 1) {
				$si_habilitado_firma = true;
			} else {
				$si_habilitado_firma = false;
			}
			$this->set('si_habilitado_firma',$si_habilitado_firma);

			if ($this->data['Escuelas']['habilitado_csa'] == 1) {
				$si_habilitado_csa = true;
			} else {
				$si_habilitado_csa = false;
			}
			$this->set('si_habilitado_csa',$si_habilitado_csa);


			if ($this->data['Escuelas']['doc_con_obs'] == 1) {
				$si_doc_con_obs = true;
			} else {
				$si_doc_con_obs = false;
			}
			$this->set('si_doc_con_obs',$si_doc_con_obs);

			if ($this->data['Escuelas']['rev_pendiente'] == 1) {
				$si_rev_pendiente = true;
			} else {
				$si_rev_pendiente = false;
			}
			$this->set('si_rev_pendiente',$si_rev_pendiente);

			if ($this->data['Escuelas']['sin_act_cuenta'] == 1) {
				$si_sin_act_cuenta = true;
			} else {
				$si_sin_act_cuenta = false;
			}
			$this->set('si_sin_act_cuenta',$si_sin_act_cuenta);

			if ($this->data['Escuelas']['sin_form_guardado'] == 1) {
				$si_sin_form_guardado = true;
			} else {
				$si_sin_form_guardado = false;
			}
			$this->set('si_sin_form_guardado',$si_sin_form_guardado);			


			if ($this->data['Escuelas']['tipo_articulacion'] == 1) {
				$si_tipo_articulacion = true;
			} else {
				$si_tipo_articulacion = false;
			}
			$this->set('si_tipo_articulacion',$si_tipo_articulacion);		

			if ($this->data['Escuelas']['tipo_escuelas'] == 1) {
				$si_tipo_escuelas = true;
			} else {
				$si_tipo_escuelas = false;
			}
			$this->set('si_tipo_escuelas',$si_tipo_escuelas);		


			if ($this->data['Escuelas']['tipo_rap'] == 1) {
				$si_tipo_rap = true;
			} else {
				$si_tipo_rap = false;
			}
			$this->set('si_tipo_rap',$si_tipo_rap);		

			if ($this->data['Escuelas']['tipo_otros'] == 1) {
				$si_tipo_otros = true;
			} else {
				$si_tipo_otros = false;
			}
			$this->set('si_tipo_otros',$si_tipo_otros);		






	        $desde = $this->data['Escuelas']['fecha_desde'];
			$fecha_inicio = date('Y-d-m', strtotime($desde));
			$hasta = $this->data['Escuelas']['fecha_hasta'];
			$hasta = str_replace( '/', '-',$hasta);	
			$hasta1 = $hasta.' 23:59:59';	
			$fecha_termino = date('Y-m-j H:i:s',strtotime($hasta1));	
			$totales = array();
			$tot_ah = 0;

			foreach ($escuelas_carreras as $key_escuela => $carreras) {
				$nom_escuela = $this->Escuela->find('first', array(
						'conditions' => array(
								'Escuela.id' => $key_escuela
							),
						'fields' => array('nombre')
					));
				$total_en_revision = 0;
				$total_no_habilitado = 0;
				$total_habilitado = 0;
				$total_firma = 0;
				$total_csa = 0;
				foreach($carreras as $key_carrera => $carrera) {
					$tmp_en_revision = 0;
					if ($si_en_revision) {
						$tmp_en_revision = $this->Postulacion->find('count', array(
								'conditions' => array(
										'Postulacion.created >=' => $fecha_inicio,
	                            		'Postulacion.created <=' => $fecha_termino,
	                            		'Postulacion.carrera_codigo' => $carrera,
	                            		'Postulacion.habilitado' => null,
	                            		'Postulacion.tipo' => 'AH'
									)
							));
						$total_en_revision = $total_en_revision + $tmp_en_revision;
					}
					$tmp_no_habilitado = 0;
					if ($si_no_habilitado) {
						$tmp_no_habilitado = $this->Postulacion->find('count', array(
								'conditions' => array(
										'Postulacion.created >=' => $fecha_inicio,
	                            		'Postulacion.created <=' => $fecha_termino,
	                            		'Postulacion.carrera_codigo' => $carrera,
	                            		'Postulacion.habilitado' => 0,
	                            		'Postulacion.tipo' => 'AH'
									)
							));
						$total_no_habilitado = $total_no_habilitado + $tmp_no_habilitado;
					}
					$tmp_habilitado = 0;
					if ($si_habilitado) {
						$tmp_habilitado = $this->Postulacion->find('count', array(
								'conditions' => array(
										'Postulacion.created >=' => $fecha_inicio,
	                            		'Postulacion.created <=' => $fecha_termino,
	                            		'Postulacion.carrera_codigo' => $carrera,
	                            		'Postulacion.habilitado' => 1,
	                            		'Postulacion.tipo' => 'AH'
									)
							));
						$total_habilitado = $total_habilitado + $tmp_habilitado;

						$tmp_firma = $this->Postulacion->find('count', array(
								'conditions' => array(
										'Postulacion.created >=' => $fecha_inicio,
	                            		'Postulacion.created <=' => $fecha_termino,
	                            		'Postulacion.carrera_codigo' => $carrera,
	                            		'Postulacion.firma' => 1,
	                            		'Postulacion.tipo' => 'AH'
									)
							));
						$total_firma = $total_firma + $tmp_firma;

						$tmp_csa = $this->Postulacion->find('count', array(
								'conditions' => array(
										'Postulacion.created >=' => $fecha_inicio,
	                            		'Postulacion.created <=' => $fecha_termino,
	                            		'Postulacion.carrera_codigo' => $carrera,
	                            		'Postulacion.csa' => 1,
	                            		'Postulacion.tipo' => 'AH'
									)
							));
						$total_csa = $total_csa + $tmp_csa;
					}

				}
				$totales['AH'][$key_escuela]['nombre_escuela'] = $nom_escuela['Escuela']['nombre'];
				$totales['AH'][$key_escuela]['en_revision'] = $total_en_revision;
				$totales['AH'][$key_escuela]['no_habilitado'] = $total_no_habilitado;
				$totales['AH'][$key_escuela]['habilitado'] = $total_habilitado;
				$totales['AH'][$key_escuela]['firma'] = $total_firma - $total_csa;
				$totales['AH'][$key_escuela]['csa'] = $total_csa;
				$totales['AH'][$key_escuela]['total'] = $total_en_revision + $total_no_habilitado + $total_habilitado;
				$tot_ah = $tot_ah + $total_en_revision + $total_no_habilitado + $total_habilitado;
			}



			$tot_av = 0;
			foreach ($escuelas_carreras as $key_escuela => $carreras) {
				$nom_escuela = $this->Escuela->find('first', array(
						'conditions' => array(
								'Escuela.id' => $key_escuela
							),
						'fields' => array('nombre')
					));
				$total_en_revision = 0;
				$total_no_habilitado = 0;
				$total_habilitado = 0;
				$total_firma = 0;
				$total_csa = 0;
				foreach($carreras as $key_carrera => $carrera) {
					$tmp_en_revision = 0;
					if ($si_en_revision) {
						$tmp_en_revision = $this->Postulacion->find('count', array(
								'conditions' => array(
										'Postulacion.created >=' => $fecha_inicio,
	                            		'Postulacion.created <=' => $fecha_termino,
	                            		'Postulacion.carrera_codigo' => $carrera,
	                            		'Postulacion.habilitado' => null,
	                            		'Postulacion.tipo' => 'AV'
									)
							));
						$total_en_revision = $total_en_revision + $tmp_en_revision;
					}
					$tmp_no_habilitado = 0;
					if ($si_no_habilitado) {
						$tmp_no_habilitado = $this->Postulacion->find('count', array(
								'conditions' => array(
										'Postulacion.created >=' => $fecha_inicio,
	                            		'Postulacion.created <=' => $fecha_termino,
	                            		'Postulacion.carrera_codigo' => $carrera,
	                            		'Postulacion.habilitado' => 0,
	                            		'Postulacion.tipo' => 'AV'
									)
							));
						$total_no_habilitado = $total_no_habilitado + $tmp_no_habilitado;
					}
					$tmp_habilitado = 0;
					if ($si_habilitado) {
						$tmp_habilitado = $this->Postulacion->find('count', array(
								'conditions' => array(
										'Postulacion.created >=' => $fecha_inicio,
	                            		'Postulacion.created <=' => $fecha_termino,
	                            		'Postulacion.carrera_codigo' => $carrera,
	                            		'Postulacion.habilitado' => 1,
	                            		'Postulacion.tipo' => 'AV'
									)
							));
						$total_habilitado = $total_habilitado + $tmp_habilitado;

						$tmp_firma = $this->Postulacion->find('count', array(
								'conditions' => array(
										'Postulacion.created >=' => $fecha_inicio,
	                            		'Postulacion.created <=' => $fecha_termino,
	                            		'Postulacion.carrera_codigo' => $carrera,
	                            		'Postulacion.firma' => 1,
	                            		'Postulacion.tipo' => 'AV'
									)
							));
						$total_firma = $total_firma + $tmp_firma;

						$tmp_csa = $this->Postulacion->find('count', array(
								'conditions' => array(
										'Postulacion.created >=' => $fecha_inicio,
	                            		'Postulacion.created <=' => $fecha_termino,
	                            		'Postulacion.carrera_codigo' => $carrera,
	                            		'Postulacion.csa' => 1,
	                            		'Postulacion.tipo' => 'AV'
									)
							));
						$total_csa = $total_csa + $tmp_csa;
					}

				}
				$totales['AV'][$key_escuela]['nombre_escuela'] = $nom_escuela['Escuela']['nombre'];
				$totales['AV'][$key_escuela]['en_revision'] = $total_en_revision;
				$totales['AV'][$key_escuela]['no_habilitado'] = $total_no_habilitado;
				$totales['AV'][$key_escuela]['habilitado'] = $total_habilitado;
				$totales['AV'][$key_escuela]['firma'] = $total_firma - $total_csa;
				$totales['AV'][$key_escuela]['csa'] = $total_csa;
				$totales['AV'][$key_escuela]['total'] = $total_en_revision + $total_no_habilitado + $total_habilitado;
				$tot_av = $tot_av + $total_en_revision + $total_no_habilitado + $total_habilitado;
			}


			$tot_rap = 0;
			foreach ($escuelas_carreras as $key_escuela => $carreras) {
				$nom_escuela = $this->Escuela->find('first', array(
						'conditions' => array(
								'Escuela.id' => $key_escuela
							),
						'fields' => array('nombre')
					));
				$total_en_revision = 0;
				$total_no_habilitado = 0;
				$total_habilitado = 0;
				$total_firma = 0;
				$total_csa = 0;
				foreach($carreras as $key_carrera => $carrera) {
					$tmp_en_revision = 0;
					if ($si_en_revision) {
						$tmp_en_revision = $this->Postulacion->find('count', array(
								'conditions' => array(
										'Postulacion.created >=' => $fecha_inicio,
	                            		'Postulacion.created <=' => $fecha_termino,
	                            		'Postulacion.carrera_codigo' => $carrera,
	                            		'Postulacion.habilitado' => null,
	                            		'Postulacion.tipo' => 'RAP'
									)
							));
						$total_en_revision = $total_en_revision + $tmp_en_revision;
					}
					$tmp_no_habilitado = 0;
					if ($si_no_habilitado) {
						$tmp_no_habilitado = $this->Postulacion->find('count', array(
								'conditions' => array(
										'Postulacion.created >=' => $fecha_inicio,
	                            		'Postulacion.created <=' => $fecha_termino,
	                            		'Postulacion.carrera_codigo' => $carrera,
	                            		'Postulacion.habilitado' => 0,
	                            		'Postulacion.tipo' => 'RAP'
									)
							));
						$total_no_habilitado = $total_no_habilitado + $tmp_no_habilitado;
					}
					$tmp_habilitado = 0;
					if ($si_habilitado) {
						$tmp_habilitado = $this->Postulacion->find('count', array(
								'conditions' => array(
										'Postulacion.created >=' => $fecha_inicio,
	                            		'Postulacion.created <=' => $fecha_termino,
	                            		'Postulacion.carrera_codigo' => $carrera,
	                            		'Postulacion.habilitado' => 1,
	                            		'Postulacion.tipo' => 'RAP'
									)
							));
						$total_habilitado = $total_habilitado + $tmp_habilitado;

						$tmp_firma = $this->Postulacion->find('count', array(
								'conditions' => array(
										'Postulacion.created >=' => $fecha_inicio,
	                            		'Postulacion.created <=' => $fecha_termino,
	                            		'Postulacion.carrera_codigo' => $carrera,
	                            		'Postulacion.firma' => 1,
	                            		'Postulacion.tipo' => 'RAP'
									)
							));
						$total_firma = $total_firma + $tmp_firma;

						$tmp_csa = $this->Postulacion->find('count', array(
								'conditions' => array(
										'Postulacion.created >=' => $fecha_inicio,
	                            		'Postulacion.created <=' => $fecha_termino,
	                            		'Postulacion.carrera_codigo' => $carrera,
	                            		'Postulacion.csa' => 1,
	                            		'Postulacion.tipo' => 'RAP'
									)
							));
						$total_csa = $total_csa + $tmp_csa;
					}

				}
				$totales['RAP'][$key_escuela]['nombre_escuela'] = $nom_escuela['Escuela']['nombre'];
				$totales['RAP'][$key_escuela]['en_revision'] = $total_en_revision;
				$totales['RAP'][$key_escuela]['no_habilitado'] = $total_no_habilitado;
				$totales['RAP'][$key_escuela]['habilitado'] = $total_habilitado;
				$totales['RAP'][$key_escuela]['firma'] = $total_firma - $total_csa;
				$totales['RAP'][$key_escuela]['csa'] = $total_csa;
				$totales['RAP'][$key_escuela]['total'] = $total_en_revision + $total_no_habilitado + $total_habilitado;
				$tot_rap= $tot_rap + $total_en_revision + $total_no_habilitado + $total_habilitado;
			}

			$nombres_escuelas = array();
			foreach($totales['AH'] as $escuela) {
				$nombres_escuelas[] = "'".$escuela['nombre_escuela']."'";
			}

			foreach($escuelas as $key => $esc) {
				$escuelas[$key] = 0;
			}
			$nombres_escuelas = implode(',', $nombres_escuelas);

			$this->set(compact('totales', 'tot_ah', 'tot_av', 'tot_rap', 'escuelas', 'nombres_escuelas'));
			$this->set(compact('desde', 'hasta'));


			// OTROS postulantes sin activacion cuenta
			$postulantes_sin_activacion_cuenta =  $this->Postulante->find('count', array(
					'conditions' => array(
							'Postulante.activo' => 0
						)
				));
			$this->set('postulantes_sin_activacion_cuenta', $postulantes_sin_activacion_cuenta);

			// OTROS postulantes sin formulario guardado

			$postulantes = $this->Postulante->find('list', array(
					'fields' => array('codigo', 'codigo'),
					'conditions' => array(
							'Postulante.activo' => 1
						)
				));

			$postulantes_sin_formulario_guardado = 0;
			foreach($postulantes as $postulante) {
				$si_postulacion = $this->Postulacion->find('first', array(
						'conditions' => array(
										'Postulacion.postulante_codigo' => $postulante
									)
							)
					);
				if (!$si_postulacion) {
					$postulantes_sin_formulario_guardado++;
				}
			}

			$this->set('postulantes_sin_formulario_guardado', $postulantes_sin_formulario_guardado);

			// Prepostulaciones en "Documento con observaciones"

			$tot_prepostulaciones_documento_con_observaciones = $lista_escuelas;


			foreach($lista_escuelas as $key => $esc) {
				$prepostulaciones_documento_con_observaciones_escuela = $this->Prepostulacion->find('count',array(
						'conditions' => array(
								'Prepostulacion.destino' => null,
								'Prepostulacion.revision' => 1,
								'Prepostulacion.escuela_id' => $key
							)
					));
				$tot_prepostulaciones_documento_con_observaciones[$key] =  $prepostulaciones_documento_con_observaciones_escuela;

			}

			$this->set('tot_prepostulaciones_documento_con_observaciones', $tot_prepostulaciones_documento_con_observaciones);


			// Prepostulaciones en "En Revision"

			$tot_prepostulaciones_documento_con_observaciones = $lista_escuelas;


			foreach($lista_escuelas as $key => $esc) {
				$prepostulaciones_en_revision = $this->Prepostulacion->find('count',array(
						'conditions' => array(
								'Prepostulacion.destino' => null,
								'Prepostulacion.revision' => null,
								'Prepostulacion.escuela_id' => $key
							)
					));
				$tot_prepostulaciones_en_revision[$key] =  $prepostulaciones_en_revision;

			}

			$this->set('tot_prepostulaciones_en_revision', $tot_prepostulaciones_en_revision);


		}


		public function rap(){
			$escuelas = $this->Escuela->find('list', array(
					'fields' => array(
							'id', 'nombre'
						),
					'order' => 'Escuela.nombre asc' 
				));
			$this->set('escuelas', $escuelas);
		}


		function ajax_rap(){		
			 $this->layout='ajax';
			 $this->Session->write('Datos',$this->data);
			 
			 $si_barra = $this->data['Rap']['barras'];
			 $si_torta = $this->data['Rap']['tortas'];

			 $this->set(compact('si_barra','si_torta'));

			 $escuelas = $this->data['Escuela'];
			 $escuelas_carreras = array();

			 foreach($escuelas as $key => $escuela) {
			 	if ($escuela <> 0) {
					 $carreras = $this->EscuelaCarrera->find('list', array(
					 		'conditions' => array(
					 				'EscuelaCarrera.escuela_codigo' => $escuela
					 			),
					 		'fields' => array('carrera_codigo', 'carrera_codigo')
					 	));
					 $array_tmp = array();
					 foreach ($carreras as $car) {
					 	$array_tmp[$car] = $car;
					 }
					 $carreras_nombre = array();
					 foreach ($array_tmp as $carr) {
						 $carrera_nombre = $this->Carrera->find('list', array(
						 		'conditions' => array(
						 				'Carrera.codigo' => $carr
						 			),
						 		'fields' => array('codigo', 'nombre')
						 	));
						 $carreras_nombre[$carr] = $carrera_nombre[$carr];
		 			}

		 			asort($carreras_nombre);
		 			$carreras = array();
		 			foreach($carreras_nombre as $key1 => $carrr) {
		 				$carreras[$key1] = $key1;
		 			}	 			
					 $escuelas_carreras[$key] = $carreras;

				}
			}



			if ($this->data['Rap']['form_completado'] == 1) {
				$si_form_completado = true;
			} else {
				$si_form_completado = false;
			}
			$this->set('si_form_completado',$si_form_completado);

			if ($this->data['Rap']['doc_recibida_revision'] == 1) {
				$si_doc_recibida_revision = true;
			} else {
				$si_doc_recibida_revision = false;
			}
			$this->set('si_doc_recibida_revision',$si_doc_recibida_revision);

			if ($this->data['Rap']['cv_completado'] == 1) {
				$si_cv_completado = true;
			} else {
				$si_cv_completado = false;
			}
			$this->set('si_cv_completado',$si_cv_completado);

			if ($this->data['Rap']['doc_aprobada'] == 1) {
				$si_doc_aprobada = true;
			} else {
				$si_doc_aprobada = false;
			}
			$this->set('si_doc_aprobada',$si_doc_aprobada);

			if ($this->data['Rap']['cv_revision'] == 1) {
				$si_cv_revision = true;
			} else {
				$si_cv_revision = false;
			}
			$this->set('si_cv_revision',$si_cv_revision);

			if ($this->data['Rap']['cv_aprobada'] == 1) {
				$si_cv_aprobada = true;
			} else {
				$si_cv_aprobada = false;
			}
			$this->set('si_cv_aprobada',$si_cv_aprobada);

			if ($this->data['Rap']['entrevista'] == 1) {
				$si_entrevista = true;
			} else {
				$si_entrevista = false;
			}
			$this->set('si_entrevista',$si_entrevista);




			if ($this->data['Rap']['no_habilitado'] == 1) {
				$si_no_habilitado = true;
			} else {
				$si_no_habilitado = false;
			}
			$this->set('si_no_habilitado',$si_no_habilitado);

				$si_habilitado = true;



			if ($this->data['Rap']['habilitado_sin_firma'] == 1) {
				$si_habilitado_sin_firma = true;
			} else {
				$si_habilitado_sin_firma = false;
			}
			$this->set('si_habilitado_sin_firma',$si_habilitado_sin_firma);



			if ($this->data['Rap']['habilitado_firma'] == 1) {
				$si_habilitado_firma = true;
			} else {
				$si_habilitado_firma= false;
			}
			$this->set('si_habilitado_firma',$si_habilitado_firma);


			if ($this->data['Rap']['habilitado_csa'] == 1) {
				$si_habilitado_csa = true;
			} else {
				$si_habilitado_csa = false;
			}
			$this->set('si_habilitado_csa',$si_habilitado_csa);



	        $desde = $this->data['Rap']['fecha_desde'];
			$fecha_inicio = date('Y-d-m', strtotime($desde));
			$hasta = $this->data['Rap']['fecha_hasta'];
			$hasta = str_replace( '/', '-',$hasta);	
			$hasta1 = $hasta.' 23:59:59';	
			$fecha_termino = date('Y-m-j H:i:s',strtotime($hasta1));	
			$totales = array();
			$tot_tot = 0;

			foreach ($escuelas_carreras as $key_escuela => $carreras) {
				$nom_escuela = $this->Escuela->find('first', array(
						'conditions' => array(
								'Escuela.id' => $key_escuela
							),
						'fields' => array('nombre', 'id')
					));
				$total_form_completado = 0;
				$total_doc_recibida_revision = 0;
				$total_cv_completado = 0;
				$total_doc_aprobada = 0;
				$total_cv_revision = 0;
				$total_cv_aprobada = 0;
				$total_entrevista = 0;
				$total_no_habilitado = 0;
				$total_habilitado = 0;
				$total_firma = 0;
				$total_csa = 0;
				foreach($carreras as $key_carrera => $carrera) {




						$tmp_en_revision = $this->Postulacion->find('all', array(
								'conditions' => array(
										'Postulacion.created >=' => $fecha_inicio,
	                            		'Postulacion.created <=' => $fecha_termino,
	                            		'Postulacion.carrera_codigo' => $carrera,
	                            		'Postulacion.habilitado' => null,
	                            		'Postulacion.tipo' => 'RAP'
									)
							));



						foreach($tmp_en_revision as $key => $revision) {
							$estado_rap = $this->EstadoPostulacion->find('first', array(
									'fields' => array('MAX (EstadoPostulacion.estado_codigo) AS max_estado'),
									'conditions' => array(
											'EstadoPostulacion.postulacion_codigo' => $revision['Postulacion']['codigo']
										)
								));
							$tmp_en_revision[$key]['Postulacion']['max_estado'] = $estado_rap['0']['max_estado'];
							
						}

						$tmp_form_completado = 0;
						$tmp_doc_recibida_revision = 0;
						$tmp_cv_completado = 0;
						$tmp_doc_aprobada = 0;
						$tmp_cv_revision = 0;
						$tmp_cv_aprobada = 0;
						$tmp_entrevista = 0;

						foreach($tmp_en_revision as $postu) {
							$casos = $postu['Postulacion']['max_estado'];

							switch ($casos) {
								case 1:
									$tmp_form_completado++;
									break;
								case 2:
									$tmp_doc_recibida_revision++;
									break;
								case 3:
									$tmp_cv_completado++;
									break;
								case 4:
									$tmp_doc_aprobada++;
									break;
								case 5:
									$tmp_cv_revision++;
									break;
								case 6:
									$tmp_cv_aprobada++;
									break;
								case 8:
									$tmp_entrevista++;
									break;
								
								default:
									break;
							}

						}



						$total_form_completado = $total_form_completado + $tmp_form_completado;
						$nombre_carrera = $this->Carrera->find('first', array(
								'conditions' => array(
										'Carrera.codigo' => $carrera
									),
								'fields' => array('Carrera.nombre')
							));
						$totales[$key_escuela][$carrera]['nombre_carrera'] = $nombre_carrera['Carrera']['nombre'];
						$totales[$key_escuela][$carrera]['form_completado'] = $tmp_form_completado;
						$total_doc_recibida_revision = $total_doc_recibida_revision + $tmp_doc_recibida_revision;
						$nombre_carrera = $this->Carrera->find('first', array(
								'conditions' => array(
										'Carrera.codigo' => $carrera
									),
								'fields' => array('Carrera.nombre')
							));
						$totales[$key_escuela][$carrera]['nombre_carrera'] = $nombre_carrera['Carrera']['nombre'];
						$totales[$key_escuela][$carrera]['doc_recibida_revision'] = $tmp_doc_recibida_revision;
						$total_cv_completado = $total_cv_completado + $tmp_cv_completado;
						$nombre_carrera = $this->Carrera->find('first', array(
								'conditions' => array(
										'Carrera.codigo' => $carrera
									),
								'fields' => array('Carrera.nombre')
							));
						$totales[$key_escuela][$carrera]['nombre_carrera'] = $nombre_carrera['Carrera']['nombre'];
						$totales[$key_escuela][$carrera]['cv_completado'] = $tmp_cv_completado;
						$total_doc_aprobada = $total_doc_aprobada + $tmp_doc_aprobada;
						$nombre_carrera = $this->Carrera->find('first', array(
								'conditions' => array(
										'Carrera.codigo' => $carrera
									),
								'fields' => array('Carrera.nombre')
							));
						$totales[$key_escuela][$carrera]['nombre_carrera'] = $nombre_carrera['Carrera']['nombre'];
						$totales[$key_escuela][$carrera]['doc_aprobada'] = $tmp_doc_aprobada;
						$total_cv_revision = $total_cv_revision + $tmp_cv_revision;
						$nombre_carrera = $this->Carrera->find('first', array(
								'conditions' => array(
										'Carrera.codigo' => $carrera
									),
								'fields' => array('Carrera.nombre')
							));
						$totales[$key_escuela][$carrera]['nombre_carrera'] = $nombre_carrera['Carrera']['nombre'];
						$totales[$key_escuela][$carrera]['cv_revision'] = $tmp_cv_revision;
						$total_cv_aprobada = $total_cv_aprobada + $tmp_cv_aprobada;
						$nombre_carrera = $this->Carrera->find('first', array(
								'conditions' => array(
										'Carrera.codigo' => $carrera
									),
								'fields' => array('Carrera.nombre')
							));
						$totales[$key_escuela][$carrera]['nombre_carrera'] = $nombre_carrera['Carrera']['nombre'];
						$totales[$key_escuela][$carrera]['cv_aprobada'] = $tmp_cv_aprobada;

						$total_entrevista = $total_entrevista + $tmp_entrevista;
						$nombre_carrera = $this->Carrera->find('first', array(
								'conditions' => array(
										'Carrera.codigo' => $carrera
									),
								'fields' => array('Carrera.nombre')
							));
						$totales[$key_escuela][$carrera]['nombre_carrera'] = $nombre_carrera['Carrera']['nombre'];
						$totales[$key_escuela][$carrera]['entrevista'] = $tmp_entrevista;

						$tmp_no_habilitado = 0;
						$tmp_no_habilitado = $this->Postulacion->find('count', array(
								'conditions' => array(
										'Postulacion.created >=' => $fecha_inicio,
	                            		'Postulacion.created <=' => $fecha_termino,
	                            		'Postulacion.carrera_codigo' => $carrera,
	                            		'Postulacion.habilitado' => 0,
	                            		'Postulacion.tipo' => 'RAP'
									)
							));
						$total_no_habilitado = $total_no_habilitado + $tmp_no_habilitado;
						$nombre_carrera = $this->Carrera->find('first', array(
								'conditions' => array(
										'Carrera.codigo' => $carrera
									),
								'fields' => array('Carrera.nombre')
							));


						$totales[$key_escuela][$carrera]['nombre_carrera'] = $nombre_carrera['Carrera']['nombre'];
						$totales[$key_escuela][$carrera]['no_habilitado'] = $tmp_no_habilitado;

						$tmp_habilitado = 0;
						$tmp_habilitado = $this->Postulacion->find('count', array(
								'conditions' => array(
										'Postulacion.created >=' => $fecha_inicio,
	                            		'Postulacion.created <=' => $fecha_termino,
	                            		'Postulacion.carrera_codigo' => $carrera,
	                            		'Postulacion.habilitado' => 1,
	                            		'Postulacion.tipo' => 'RAP'
									)
							));
						$total_habilitado = $total_habilitado + $tmp_habilitado;
						$nombre_carrera = $this->Carrera->find('first', array(
								'conditions' => array(
										'Carrera.codigo' => $carrera
									),
								'fields' => array('Carrera.nombre')
							));


						$totales[$key_escuela][$carrera]['nombre_carrera'] = $nombre_carrera['Carrera']['nombre'];
						$totales[$key_escuela][$carrera]['habilitado'] = $tmp_habilitado;


						$tmp_firma = $this->Postulacion->find('count', array(
								'conditions' => array(
										'Postulacion.created >=' => $fecha_inicio,
	                            		'Postulacion.created <=' => $fecha_termino,
	                            		'Postulacion.carrera_codigo' => $carrera,
	                            		'Postulacion.firma' => 1,
	                            		'Postulacion.tipo' => 'RAP'
									)
							));
						$total_firma = $total_firma + $tmp_firma;
						$nombre_carrera = $this->Carrera->find('first', array(
								'conditions' => array(
										'Carrera.codigo' => $carrera
									),
								'fields' => array('Carrera.nombre')
							));


						$totales[$key_escuela][$carrera]['nombre_carrera'] = $nombre_carrera['Carrera']['nombre'];
						$totales[$key_escuela][$carrera]['firma'] = $tmp_firma;



						$tmp_csa = $this->Postulacion->find('count', array(
								'conditions' => array(
										'Postulacion.created >=' => $fecha_inicio,
	                            		'Postulacion.created <=' => $fecha_termino,
	                            		'Postulacion.carrera_codigo' => $carrera,
	                            		'Postulacion.csa' => 1,
	                            		'Postulacion.tipo' => 'RAP'
									)
							));
						$total_csa = $total_csa + $tmp_csa;
						$nombre_carrera = $this->Carrera->find('first', array(
								'conditions' => array(
										'Carrera.codigo' => $carrera
									),
								'fields' => array('Carrera.nombre')
							));


						$totales[$key_escuela][$carrera]['nombre_carrera'] = $nombre_carrera['Carrera']['nombre'];
						$totales[$key_escuela][$carrera]['csa'] = $tmp_csa;



				}

					$cant_carreras = 0;
					$cant_carreras = count($totales[$key_escuela]);
					$totales[$key_escuela]['nombre_escuela'] = $nom_escuela['Escuela']['nombre'];
					$totales[$key_escuela]['escuela_codigo'] = $nom_escuela['Escuela']['id'];


				$total_form_completado = 0;
				$total_doc_recibida_revision = 0;
				$total_cv_completado = 0;
				$total_doc_aprobada = 0;
				$total_cv_revision = 0;
				$total_cv_aprobada = 0;
				$total_entrevista = 0;



					$totales[$key_escuela]['form_completado'] = $total_form_completado;
					$totales[$key_escuela]['doc_recibida_revision'] = $total_doc_recibida_revision;
					$totales[$key_escuela]['cv_completado'] = $total_cv_completado;
					$totales[$key_escuela]['doc_aprobada'] = $total_doc_aprobada;
					$totales[$key_escuela]['cv_revision'] = $total_cv_revision;
					$totales[$key_escuela]['cv_aprobada'] = $total_cv_aprobada;
					$totales[$key_escuela]['entrevista'] = $total_entrevista;


					$totales[$key_escuela]['no_habilitado'] = $total_no_habilitado;
					$totales[$key_escuela]['habilitado'] = $total_habilitado;
					$totales[$key_escuela]['firma'] = $total_firma - $total_csa;
					$totales[$key_escuela]['csa'] = $total_csa;


					$totales[$key_escuela]['total'] = $total_form_completado + $total_doc_recibida_revision + $total_cv_completado + $total_doc_aprobada + $total_cv_revision + $total_cv_aprobada + $total_entrevista + $total_no_habilitado + $total_habilitado;


					$totales[$key_escuela]['total_carreras'] = $cant_carreras;
					$tot_tot = $tot_tot + $total_form_completado + $total_doc_recibida_revision + $total_cv_completado + $total_doc_aprobada + $total_cv_revision + $total_cv_aprobada + $total_entrevista + $total_no_habilitado + $total_habilitado;
			}



			$this->set(compact('totales', 'tot_tot'));
			$this->set(compact('desde', 'hasta'));


		}


		function ajax_exportar_excel_rap(){		
			 $this->layout='ajax';
			 $this->data = $this->Session->read('Datos');
			 
			 $si_barra = $this->data['Rap']['barras'];
			 $si_torta = $this->data['Rap']['tortas'];

			 $this->set(compact('si_barra','si_torta'));

			 $escuelas = $this->data['Escuela'];
			 $escuelas_carreras = array();

			 foreach($escuelas as $key => $escuela) {
			 	if ($escuela <> 0) {
					 $carreras = $this->EscuelaCarrera->find('list', array(
					 		'conditions' => array(
					 				'EscuelaCarrera.escuela_codigo' => $escuela
					 			),
					 		'fields' => array('carrera_codigo', 'carrera_codigo')
					 	));
					 $array_tmp = array();
					 foreach ($carreras as $car) {
					 	$array_tmp[$car] = $car;
					 }
					 $carreras_nombre = array();
					 foreach ($array_tmp as $carr) {
						 $carrera_nombre = $this->Carrera->find('list', array(
						 		'conditions' => array(
						 				'Carrera.codigo' => $carr
						 			),
						 		'fields' => array('codigo', 'nombre')
						 	));
						 $carreras_nombre[$carr] = $carrera_nombre[$carr];
		 			}

		 			asort($carreras_nombre);
		 			$carreras = array();
		 			foreach($carreras_nombre as $key1 => $carrr) {
		 				$carreras[$key1] = $key1;
		 			}	 			
					 $escuelas_carreras[$key] = $carreras;

				}
			}



			if ($this->data['Rap']['form_completado'] == 1) {
				$si_form_completado = true;
			} else {
				$si_form_completado = false;
			}
			$this->set('si_form_completado',$si_form_completado);

			if ($this->data['Rap']['doc_recibida_revision'] == 1) {
				$si_doc_recibida_revision = true;
			} else {
				$si_doc_recibida_revision = false;
			}
			$this->set('si_doc_recibida_revision',$si_doc_recibida_revision);

			if ($this->data['Rap']['cv_completado'] == 1) {
				$si_cv_completado = true;
			} else {
				$si_cv_completado = false;
			}
			$this->set('si_cv_completado',$si_cv_completado);

			if ($this->data['Rap']['doc_aprobada'] == 1) {
				$si_doc_aprobada = true;
			} else {
				$si_doc_aprobada = false;
			}
			$this->set('si_doc_aprobada',$si_doc_aprobada);

			if ($this->data['Rap']['cv_revision'] == 1) {
				$si_cv_revision = true;
			} else {
				$si_cv_revision = false;
			}
			$this->set('si_cv_revision',$si_cv_revision);

			if ($this->data['Rap']['cv_aprobada'] == 1) {
				$si_cv_aprobada = true;
			} else {
				$si_cv_aprobada = false;
			}
			$this->set('si_cv_aprobada',$si_cv_aprobada);

			if ($this->data['Rap']['entrevista'] == 1) {
				$si_entrevista = true;
			} else {
				$si_entrevista = false;
			}
			$this->set('si_entrevista',$si_entrevista);




			if ($this->data['Rap']['no_habilitado'] == 1) {
				$si_no_habilitado = true;
			} else {
				$si_no_habilitado = false;
			}
			$this->set('si_no_habilitado',$si_no_habilitado);

				$si_habilitado = true;



			if ($this->data['Rap']['habilitado_sin_firma'] == 1) {
				$si_habilitado_sin_firma = true;
			} else {
				$si_habilitado_sin_firma = false;
			}
			$this->set('si_habilitado_sin_firma',$si_habilitado_sin_firma);



			if ($this->data['Rap']['habilitado_firma'] == 1) {
				$si_habilitado_firma = true;
			} else {
				$si_habilitado_firma= false;
			}
			$this->set('si_habilitado_firma',$si_habilitado_firma);


			if ($this->data['Rap']['habilitado_csa'] == 1) {
				$si_habilitado_csa = true;
			} else {
				$si_habilitado_csa = false;
			}
			$this->set('si_habilitado_csa',$si_habilitado_csa);



	        $desde = $this->data['Rap']['fecha_desde'];
			$fecha_inicio = date('Y-d-m', strtotime($desde));
			$hasta = $this->data['Rap']['fecha_hasta'];
			$hasta = str_replace( '/', '-',$hasta);	
			$hasta1 = $hasta.' 23:59:59';	
			$fecha_termino = date('Y-m-j H:i:s',strtotime($hasta1));	
			$totales = array();
			$tot_tot = 0;

			foreach ($escuelas_carreras as $key_escuela => $carreras) {
				$nom_escuela = $this->Escuela->find('first', array(
						'conditions' => array(
								'Escuela.id' => $key_escuela
							),
						'fields' => array('nombre')
					));
				$total_form_completado = 0;
				$total_doc_recibida_revision = 0;
				$total_cv_completado = 0;
				$total_doc_aprobada = 0;
				$total_cv_revision = 0;
				$total_cv_aprobada = 0;
				$total_entrevista = 0;
				$total_no_habilitado = 0;
				$total_habilitado = 0;
				$total_firma = 0;
				$total_csa = 0;
				foreach($carreras as $key_carrera => $carrera) {




						$tmp_en_revision = $this->Postulacion->find('all', array(
								'conditions' => array(
										'Postulacion.created >=' => $fecha_inicio,
	                            		'Postulacion.created <=' => $fecha_termino,
	                            		'Postulacion.carrera_codigo' => $carrera,
	                            		'Postulacion.habilitado' => null,
	                            		'Postulacion.tipo' => 'RAP'
									)
							));



						foreach($tmp_en_revision as $key => $revision) {
							$estado_rap = $this->EstadoPostulacion->find('first', array(
									'fields' => array('MAX (EstadoPostulacion.estado_codigo) AS max_estado'),
									'conditions' => array(
											'EstadoPostulacion.postulacion_codigo' => $revision['Postulacion']['codigo']
										)
								));
							$tmp_en_revision[$key]['Postulacion']['max_estado'] = $estado_rap['0']['max_estado'];
							
						}

						$tmp_form_completado = 0;
						$tmp_doc_recibida_revision = 0;
						$tmp_cv_completado = 0;
						$tmp_doc_aprobada = 0;
						$tmp_cv_revision = 0;
						$tmp_cv_aprobada = 0;
						$tmp_entrevista = 0;

						foreach($tmp_en_revision as $postu) {
							$casos = $postu['Postulacion']['max_estado'];

							switch ($casos) {
								case 1:
									$tmp_form_completado++;
									break;
								case 2:
									$tmp_doc_recibida_revision++;
									break;
								case 3:
									$tmp_cv_completado++;
									break;
								case 4:
									$tmp_doc_aprobada++;
									break;
								case 5:
									$tmp_cv_revision++;
									break;
								case 6:
									$tmp_cv_aprobada++;
									break;
								case 8:
									$tmp_entrevista++;
									break;
								
								default:
									break;
							}

						}



						$total_form_completado = $total_form_completado + $tmp_form_completado;
						$nombre_carrera = $this->Carrera->find('first', array(
								'conditions' => array(
										'Carrera.codigo' => $carrera
									),
								'fields' => array('Carrera.nombre')
							));
						$totales[$key_escuela][$carrera]['nombre_carrera'] = $nombre_carrera['Carrera']['nombre'];
						$totales[$key_escuela][$carrera]['form_completado'] = $tmp_form_completado;
						$total_doc_recibida_revision = $total_doc_recibida_revision + $tmp_doc_recibida_revision;
						$nombre_carrera = $this->Carrera->find('first', array(
								'conditions' => array(
										'Carrera.codigo' => $carrera
									),
								'fields' => array('Carrera.nombre')
							));
						$totales[$key_escuela][$carrera]['nombre_carrera'] = $nombre_carrera['Carrera']['nombre'];
						$totales[$key_escuela][$carrera]['doc_recibida_revision'] = $tmp_doc_recibida_revision;
						$total_cv_completado = $total_cv_completado + $tmp_cv_completado;
						$nombre_carrera = $this->Carrera->find('first', array(
								'conditions' => array(
										'Carrera.codigo' => $carrera
									),
								'fields' => array('Carrera.nombre')
							));
						$totales[$key_escuela][$carrera]['nombre_carrera'] = $nombre_carrera['Carrera']['nombre'];
						$totales[$key_escuela][$carrera]['cv_completado'] = $tmp_cv_completado;
						$total_doc_aprobada = $total_doc_aprobada + $tmp_doc_aprobada;
						$nombre_carrera = $this->Carrera->find('first', array(
								'conditions' => array(
										'Carrera.codigo' => $carrera
									),
								'fields' => array('Carrera.nombre')
							));
						$totales[$key_escuela][$carrera]['nombre_carrera'] = $nombre_carrera['Carrera']['nombre'];
						$totales[$key_escuela][$carrera]['doc_aprobada'] = $tmp_doc_aprobada;
						$total_cv_revision = $total_cv_revision + $tmp_cv_revision;
						$nombre_carrera = $this->Carrera->find('first', array(
								'conditions' => array(
										'Carrera.codigo' => $carrera
									),
								'fields' => array('Carrera.nombre')
							));
						$totales[$key_escuela][$carrera]['nombre_carrera'] = $nombre_carrera['Carrera']['nombre'];
						$totales[$key_escuela][$carrera]['cv_revision'] = $tmp_cv_revision;
						$total_cv_aprobada = $total_cv_aprobada + $tmp_cv_aprobada;
						$nombre_carrera = $this->Carrera->find('first', array(
								'conditions' => array(
										'Carrera.codigo' => $carrera
									),
								'fields' => array('Carrera.nombre')
							));
						$totales[$key_escuela][$carrera]['nombre_carrera'] = $nombre_carrera['Carrera']['nombre'];
						$totales[$key_escuela][$carrera]['cv_aprobada'] = $tmp_cv_aprobada;

						$total_entrevista = $total_entrevista + $tmp_entrevista;
						$nombre_carrera = $this->Carrera->find('first', array(
								'conditions' => array(
										'Carrera.codigo' => $carrera
									),
								'fields' => array('Carrera.nombre')
							));
						$totales[$key_escuela][$carrera]['nombre_carrera'] = $nombre_carrera['Carrera']['nombre'];
						$totales[$key_escuela][$carrera]['entrevista'] = $tmp_entrevista;

						$tmp_no_habilitado = 0;
						$tmp_no_habilitado = $this->Postulacion->find('count', array(
								'conditions' => array(
										'Postulacion.created >=' => $fecha_inicio,
	                            		'Postulacion.created <=' => $fecha_termino,
	                            		'Postulacion.carrera_codigo' => $carrera,
	                            		'Postulacion.habilitado' => 0,
	                            		'Postulacion.tipo' => 'RAP'
									)
							));
						$total_no_habilitado = $total_no_habilitado + $tmp_no_habilitado;
						$nombre_carrera = $this->Carrera->find('first', array(
								'conditions' => array(
										'Carrera.codigo' => $carrera
									),
								'fields' => array('Carrera.nombre')
							));


						$totales[$key_escuela][$carrera]['nombre_carrera'] = $nombre_carrera['Carrera']['nombre'];
						$totales[$key_escuela][$carrera]['no_habilitado'] = $tmp_no_habilitado;

						$tmp_habilitado = 0;
						$tmp_habilitado = $this->Postulacion->find('count', array(
								'conditions' => array(
										'Postulacion.created >=' => $fecha_inicio,
	                            		'Postulacion.created <=' => $fecha_termino,
	                            		'Postulacion.carrera_codigo' => $carrera,
	                            		'Postulacion.habilitado' => 1,
	                            		'Postulacion.tipo' => 'RAP'
									)
							));
						$total_habilitado = $total_habilitado + $tmp_habilitado;
						$nombre_carrera = $this->Carrera->find('first', array(
								'conditions' => array(
										'Carrera.codigo' => $carrera
									),
								'fields' => array('Carrera.nombre')
							));


						$totales[$key_escuela][$carrera]['nombre_carrera'] = $nombre_carrera['Carrera']['nombre'];
						$totales[$key_escuela][$carrera]['habilitado'] = $tmp_habilitado;


						$tmp_firma = $this->Postulacion->find('count', array(
								'conditions' => array(
										'Postulacion.created >=' => $fecha_inicio,
	                            		'Postulacion.created <=' => $fecha_termino,
	                            		'Postulacion.carrera_codigo' => $carrera,
	                            		'Postulacion.firma' => 1,
	                            		'Postulacion.tipo' => 'RAP'
									)
							));
						$total_firma = $total_firma + $tmp_firma;
						$nombre_carrera = $this->Carrera->find('first', array(
								'conditions' => array(
										'Carrera.codigo' => $carrera
									),
								'fields' => array('Carrera.nombre')
							));


						$totales[$key_escuela][$carrera]['nombre_carrera'] = $nombre_carrera['Carrera']['nombre'];
						$totales[$key_escuela][$carrera]['firma'] = $tmp_firma;



						$tmp_csa = $this->Postulacion->find('count', array(
								'conditions' => array(
										'Postulacion.created >=' => $fecha_inicio,
	                            		'Postulacion.created <=' => $fecha_termino,
	                            		'Postulacion.carrera_codigo' => $carrera,
	                            		'Postulacion.csa' => 1,
	                            		'Postulacion.tipo' => 'RAP'
									)
							));
						$total_csa = $total_csa + $tmp_csa;
						$nombre_carrera = $this->Carrera->find('first', array(
								'conditions' => array(
										'Carrera.codigo' => $carrera
									),
								'fields' => array('Carrera.nombre')
							));


						$totales[$key_escuela][$carrera]['nombre_carrera'] = $nombre_carrera['Carrera']['nombre'];
						$totales[$key_escuela][$carrera]['csa'] = $tmp_csa;



				}

					$cant_carreras = 0;
					$cant_carreras = count($totales[$key_escuela]);
					$totales[$key_escuela]['nombre_escuela'] = $nom_escuela['Escuela']['nombre'];


				$total_form_completado = 0;
				$total_doc_recibida_revision = 0;
				$total_cv_completado = 0;
				$total_doc_aprobada = 0;
				$total_cv_revision = 0;
				$total_cv_aprobada = 0;
				$total_entrevista = 0;



					$totales[$key_escuela]['form_completado'] = $total_form_completado;
					$totales[$key_escuela]['doc_recibida_revision'] = $total_doc_recibida_revision;
					$totales[$key_escuela]['cv_completado'] = $total_cv_completado;
					$totales[$key_escuela]['doc_aprobada'] = $total_doc_aprobada;
					$totales[$key_escuela]['cv_revision'] = $total_cv_revision;
					$totales[$key_escuela]['cv_aprobada'] = $total_cv_aprobada;
					$totales[$key_escuela]['entrevista'] = $total_entrevista;


					$totales[$key_escuela]['no_habilitado'] = $total_no_habilitado;
					$totales[$key_escuela]['habilitado'] = $total_habilitado;
					$totales[$key_escuela]['firma'] = $total_firma - $total_csa;
					$totales[$key_escuela]['csa'] = $total_csa;


					$totales[$key_escuela]['total'] = $total_form_completado + $total_doc_recibida_revision + $total_cv_completado + $total_doc_aprobada + $total_cv_revision + $total_cv_aprobada + $total_entrevista + $total_no_habilitado + $total_habilitado;


					$totales[$key_escuela]['total_carreras'] = $cant_carreras;
					$tot_tot = $tot_tot + $total_form_completado + $total_doc_recibida_revision + $total_cv_completado + $total_doc_aprobada + $total_cv_revision + $total_cv_aprobada + $total_entrevista + $total_no_habilitado + $total_habilitado;
			}



			$this->set(compact('totales', 'tot_tot'));
			$this->set(compact('desde', 'hasta'));


		}

		public function articulacion() {
			$postu = $this->Postulacion->find('all', array(
					'conditions' => array(
							'Postulacion.tipo' => 'AV'),
							'order' => 'Carrera.nombre asc'
						)
				);

			$tmp_array = array();
			foreach($postu as $po) {
				$tmp_array[$po['Carrera']['codigo']] = $po['Carrera']['nombre'];
			}

			$carreras = array_unique($tmp_array);

			$this->set('listado', $carreras);

			/*$liceos = $this->Liceo->find('list', array(
					'fields' => array('id', 'nombre')
				));

			$this->set('liceos', $liceos);*/


		}


		function ajax_articulacion(){		



			 $this->layout='ajax';
			 $this->Session->write('Datos',$this->data);
			 
			 $si_barra = $this->data['Articulacion']['barras'];
			 $si_torta = $this->data['Articulacion']['tortas'];

			 $this->set(compact('si_barra','si_torta'));


			if ($this->data['Articulacion']['en_revision'] == 1) {
				$si_en_revision = true;
			} else {
				$si_en_revision = false;
			}

			$this->set('si_en_revision',$si_en_revision);

			if ($this->data['Articulacion']['no_habilitado'] == 1) {
				$si_no_habilitado = true;
			} else {
				$si_no_habilitado = false;
			}

			$this->set('si_no_habilitado',$si_no_habilitado);

				$si_habilitado = true;



			if ($this->data['Articulacion']['habilitado_sin_firma'] == 1) {
				$si_habilitado_sin_firma = true;
			} else {
				$si_habilitado_sin_firma = false;
			}
			$this->set('si_habilitado_sin_firma',$si_habilitado_sin_firma);



			if ($this->data['Articulacion']['habilitado_firma'] == 1) {
				$si_habilitado_firma = true;
			} else {
				$si_habilitado_firma= false;
			}
			$this->set('si_habilitado_firma',$si_habilitado_firma);


			if ($this->data['Articulacion']['habilitado_csa'] == 1) {
				$si_habilitado_csa = true;
			} else {
				$si_habilitado_csa = false;
			}
			$this->set('si_habilitado_csa',$si_habilitado_csa);



	        $desde = $this->data['Articulacion']['fecha_desde'];
			$fecha_inicio = date('Y-d-m', strtotime($desde));
			$hasta = $this->data['Articulacion']['fecha_hasta'];
			$hasta = str_replace( '/', '-',$hasta);	
			$hasta1 = $hasta.' 23:59:59';	
			$fecha_termino = date('Y-m-j H:i:s',strtotime($hasta1));	
			$totales = array();
			$tot_tot = 0;

			//recorrer liceos
			$liceos = array();
			foreach($this->data['Articulacion']['Liceos'] as $key => $car) {
				$liceos[] = $car;
			}

			//recorrer carreras
			$carreras = array();
			foreach($this->data['Articulacion']['Carreras'] as $key => $car) {
				if($car!='0'){
					$carreras[$key] = $key;
				}
			}

			$total_en_revision = 0;
			$total_no_habilitado = 0;
			$total_habilitado = 0;
			$total_firma = 0;
			$total_csa = 0;
			foreach($carreras as $key_carrera => $carrera) {
				$tmp_en_revision = 0;
				$tmp_en_revision = $this->Postulacion->find('count', array(
						'conditions' => array(
								'Postulacion.created >=' => $fecha_inicio,
                        		'Postulacion.created <=' => $fecha_termino,
                        		'Postulacion.carrera_codigo' => $carrera,
                        		'Postulacion.habilitado' => null,
                        		'Postulacion.tipo' => 'AV'
							)
					));
				$total_en_revision = $total_en_revision + $tmp_en_revision;

				$nombre_carrera = $this->Carrera->find('first', array(
						'conditions' => array(
								'Carrera.codigo' => $carrera
							),
						'fields' => array('Carrera.nombre')
					));


				$totales[$carrera]['nombre_carrera'] = $nombre_carrera['Carrera']['nombre'];
				$totales[$carrera]['en_revision'] = $tmp_en_revision;
				$tmp_no_habilitado = 0;
				$tmp_no_habilitado = $this->Postulacion->find('count', array(
						'conditions' => array(
								'Postulacion.created >=' => $fecha_inicio,
                        		'Postulacion.created <=' => $fecha_termino,
                        		'Postulacion.carrera_codigo' => $carrera,
                        		'Postulacion.habilitado' => 0,
                        		'Postulacion.tipo' => 'AV'
							)
					));
				$total_no_habilitado = $total_no_habilitado + $tmp_no_habilitado;
				$nombre_carrera = $this->Carrera->find('first', array(
						'conditions' => array(
								'Carrera.codigo' => $carrera
							),
						'fields' => array('Carrera.nombre')
					));


				$totales[$carrera]['nombre_carrera'] = $nombre_carrera['Carrera']['nombre'];
				$totales[$carrera]['no_habilitado'] = $tmp_no_habilitado;

				$tmp_habilitado = 0;
				$tmp_habilitado = $this->Postulacion->find('count', array(
						'conditions' => array(
								'Postulacion.created >=' => $fecha_inicio,
                        		'Postulacion.created <=' => $fecha_termino,
                        		'Postulacion.carrera_codigo' => $carrera,
                        		'Postulacion.habilitado' => 1,
                        		'Postulacion.tipo' => 'AV'
							)
					));
				$total_habilitado = $total_habilitado + $tmp_habilitado;
				$nombre_carrera = $this->Carrera->find('first', array(
						'conditions' => array(
								'Carrera.codigo' => $carrera
							),
						'fields' => array('Carrera.nombre')
					));


				$totales[$carrera]['nombre_carrera'] = $nombre_carrera['Carrera']['nombre'];
				$totales[$carrera]['habilitado'] = $tmp_habilitado;


				$tmp_firma = $this->Postulacion->find('count', array(
						'conditions' => array(
								'Postulacion.created >=' => $fecha_inicio,
                        		'Postulacion.created <=' => $fecha_termino,
                        		'Postulacion.carrera_codigo' => $carrera,
                        		'Postulacion.firma' => 1,
                        		'Postulacion.tipo' => 'AV'
							)
					));
				$total_firma = $total_firma + $tmp_firma;
				$nombre_carrera = $this->Carrera->find('first', array(
						'conditions' => array(
								'Carrera.codigo' => $carrera
							),
						'fields' => array('Carrera.nombre')
					));


				$totales[$carrera]['nombre_carrera'] = $nombre_carrera['Carrera']['nombre'];
				$totales[$carrera]['firma'] = $tmp_firma;



				$tmp_csa = $this->Postulacion->find('count', array(
						'conditions' => array(
								'Postulacion.created >=' => $fecha_inicio,
                        		'Postulacion.created <=' => $fecha_termino,
                        		'Postulacion.carrera_codigo' => $carrera,
                        		'Postulacion.csa' => 1,
                        		'Postulacion.tipo' => 'AV'
							)
					));
				$total_csa = $total_csa + $tmp_csa;
				$nombre_carrera = $this->Carrera->find('first', array(
						'conditions' => array(
								'Carrera.codigo' => $carrera
							),
						'fields' => array('Carrera.nombre')
					));


				$totales[$carrera]['nombre_carrera'] = $nombre_carrera['Carrera']['nombre'];
				$totales[$carrera]['csa'] = $tmp_csa;
			}
			//var_dump($totales); die;
			$this->set(compact('totales', 'tot_tot'));
			$this->set(compact('desde', 'hasta'));


		}



		function ajax_exportar_excel_articulacion(){		

			 $this->layout='ajax';
			 $this->data = $this->Session->read('Datos');

			 //print_r($this->data);
			 
			 $si_barra = $this->data['Articulacion']['barras'];
			 $si_torta = $this->data['Articulacion']['tortas'];

			 $this->set(compact('si_barra','si_torta'));


			if ($this->data['Articulacion']['en_revision'] == 1) {
				$si_en_revision = true;
			} else {
				$si_en_revision = false;
			}

			$this->set('si_en_revision',$si_en_revision);

			if ($this->data['Articulacion']['no_habilitado'] == 1) {
				$si_no_habilitado = true;
			} else {
				$si_no_habilitado = false;
			}

			$this->set('si_no_habilitado',$si_no_habilitado);

				$si_habilitado = true;



			if ($this->data['Articulacion']['habilitado_sin_firma'] == 1) {
				$si_habilitado_sin_firma = true;
			} else {
				$si_habilitado_sin_firma = false;
			}
			$this->set('si_habilitado_sin_firma',$si_habilitado_sin_firma);



			if ($this->data['Articulacion']['habilitado_firma'] == 1) {
				$si_habilitado_firma = true;
			} else {
				$si_habilitado_firma= false;
			}
			$this->set('si_habilitado_firma',$si_habilitado_firma);


			if ($this->data['Articulacion']['habilitado_csa'] == 1) {
				$si_habilitado_csa = true;
			} else {
				$si_habilitado_csa = false;
			}
			$this->set('si_habilitado_csa',$si_habilitado_csa);



	        $desde = $this->data['Articulacion']['fecha_desde'];
			$fecha_inicio = date('Y-d-m', strtotime($desde));
			$hasta = $this->data['Articulacion']['fecha_hasta'];
			$hasta = str_replace( '/', '-',$hasta);	
			$hasta1 = $hasta.' 23:59:59';	
			$fecha_termino = date('Y-m-j H:i:s',strtotime($hasta1));	
			$totales = array();
			$tot_tot = 0;

			$carreras = array();
			foreach($this->data['Articulacion']['Carreras'] as $key => $car) {
				if($car!='0'){
					$carreras[$key] = $key;
				}
			}



				$total_en_revision = 0;
				$total_no_habilitado = 0;
				$total_habilitado = 0;
				$total_firma = 0;
				$total_csa = 0;
				foreach($carreras as $key_carrera => $carrera) {
					$tmp_en_revision = 0;
						$tmp_en_revision = $this->Postulacion->find('count', array(
								'conditions' => array(
										'Postulacion.created >=' => $fecha_inicio,
	                            		'Postulacion.created <=' => $fecha_termino,
	                            		'Postulacion.carrera_codigo' => $carrera,
	                            		'Postulacion.habilitado' => null,
	                            		'Postulacion.tipo' => 'AV'
									)
							));
						$total_en_revision = $total_en_revision + $tmp_en_revision;

						$nombre_carrera = $this->Carrera->find('first', array(
								'conditions' => array(
										'Carrera.codigo' => $carrera
									),
								'fields' => array('Carrera.nombre')
							));


						$totales[$carrera]['nombre_carrera'] = $nombre_carrera['Carrera']['nombre'];
						$totales[$carrera]['en_revision'] = $tmp_en_revision;
					$tmp_no_habilitado = 0;
						$tmp_no_habilitado = $this->Postulacion->find('count', array(
								'conditions' => array(
										'Postulacion.created >=' => $fecha_inicio,
	                            		'Postulacion.created <=' => $fecha_termino,
	                            		'Postulacion.carrera_codigo' => $carrera,
	                            		'Postulacion.habilitado' => 0,
	                            		'Postulacion.tipo' => 'AV'
									)
							));
						$total_no_habilitado = $total_no_habilitado + $tmp_no_habilitado;
						$nombre_carrera = $this->Carrera->find('first', array(
								'conditions' => array(
										'Carrera.codigo' => $carrera
									),
								'fields' => array('Carrera.nombre')
							));


						$totales[$carrera]['nombre_carrera'] = $nombre_carrera['Carrera']['nombre'];
						$totales[$carrera]['no_habilitado'] = $tmp_no_habilitado;

					$tmp_habilitado = 0;
						$tmp_habilitado = $this->Postulacion->find('count', array(
								'conditions' => array(
										'Postulacion.created >=' => $fecha_inicio,
	                            		'Postulacion.created <=' => $fecha_termino,
	                            		'Postulacion.carrera_codigo' => $carrera,
	                            		'Postulacion.habilitado' => 1,
	                            		'Postulacion.tipo' => 'AV'
									)
							));
						$total_habilitado = $total_habilitado + $tmp_habilitado;
						$nombre_carrera = $this->Carrera->find('first', array(
								'conditions' => array(
										'Carrera.codigo' => $carrera
									),
								'fields' => array('Carrera.nombre')
							));


						$totales[$carrera]['nombre_carrera'] = $nombre_carrera['Carrera']['nombre'];
						$totales[$carrera]['habilitado'] = $tmp_habilitado;


						$tmp_firma = $this->Postulacion->find('count', array(
								'conditions' => array(
										'Postulacion.created >=' => $fecha_inicio,
	                            		'Postulacion.created <=' => $fecha_termino,
	                            		'Postulacion.carrera_codigo' => $carrera,
	                            		'Postulacion.firma' => 1,
	                            		'Postulacion.tipo' => 'AV'
									)
							));
						$total_firma = $total_firma + $tmp_firma;
						$nombre_carrera = $this->Carrera->find('first', array(
								'conditions' => array(
										'Carrera.codigo' => $carrera
									),
								'fields' => array('Carrera.nombre')
							));


						$totales[$carrera]['nombre_carrera'] = $nombre_carrera['Carrera']['nombre'];
						$totales[$carrera]['firma'] = $tmp_firma;



						$tmp_csa = $this->Postulacion->find('count', array(
								'conditions' => array(
										'Postulacion.created >=' => $fecha_inicio,
	                            		'Postulacion.created <=' => $fecha_termino,
	                            		'Postulacion.carrera_codigo' => $carrera,
	                            		'Postulacion.csa' => 1,
	                            		'Postulacion.tipo' => 'AV'
									)
							));
						$total_csa = $total_csa + $tmp_csa;
						$nombre_carrera = $this->Carrera->find('first', array(
								'conditions' => array(
										'Carrera.codigo' => $carrera
									),
								'fields' => array('Carrera.nombre')
							));


						$totales[$carrera]['nombre_carrera'] = $nombre_carrera['Carrera']['nombre'];
						$totales[$carrera]['csa'] = $tmp_csa;


				}

			$this->set(compact('totales', 'tot_tot'));
			$this->set(compact('desde', 'hasta'));


		}

	}
?>
