<?php
App::uses('AppModel', 'Model');

/* COMENTARIOS GENERALES 
ES IMPORTANTE TENER EN CUENTA LOS ESPACIOS EN BLANCO A LA HORA DE GENERAR LA MATRIZ CORRECTA
IMPORTANTE HACER TRIM A LOS CAMPOS CUANDO SE GENERA LA MATRIZ CORRECTA QUE LUEGO SE INSERTARÁ
*/

class Csv extends AppModel {
	public $useTable = false;
	var $cabecera_correcta = array( 
									1 => 'CARRERA',
									//2 => 'ID COMPETENCIA',
									3 => 'COMPETENCIA',
									4 => 'TIPO DE COMPETENCIA',
									//4 => 'ID UNIDAD DE COMPETENCIA',
									5 => 'UNIDAD DE COMPETENCIA',
									6 => 'ASIGNATURA',
									7 => 'NOMBRE ASIGNATURA'
	);
	var $errores = array();
	

//Función que sube el archivo a una carpeta para que se pueda leer.
	function upload($file) {				
					$name = $file["name"];
					$mapeo = array('CARRERA');
					$nombre = 'csv.csv';
					$ruta = APP.'webroot/uploads/csv/'.$nombre;
					$num = strrpos($name, ".");
					$tipo = substr($name, $num+1);	
					//Si el ARCHIVO se sube, se van llamando funciones
					if ($file['error'] == 0) {
						if(move_uploaded_file($file['tmp_name'], $ruta)){
							//Empezamos a ejecutar ordenes
							$this->procesarArchivo($ruta,$mapeo);
						}
						else{
							$this->errores[] = 'Error al subir el archivo al servidor.';							
						}
					}
					else{
						$this->errores[] = 'Error genérico de archivo.';						
					}					
					return $this->errores;
	}

		
	function procesarArchivo($archivo = null)	{
		if ( ! $archivo || ! is_file($archivo))
			return false;
		$registros	= $stats = array();
		$fila		= 0;
		if ( ( $handle = fopen($archivo, 'r') ) !== FALSE )
		{
			$aux = 0;
			while ( ( $datos = fgetcsv($handle, 0, ';') ) !== FALSE )
			{				
				$aux++;				
				$filas[$aux] = $datos;
			}
			//echo var_dump($filas);
			if ($this->comprobar_cabecera($filas[2]) == true){		
				$cabecera = $filas[2];
				$this->ordenar_columnas($filas[2]);
				
				$this->duplicadoscabecera($filas[2]);			
				$csv_final = $this->csv_sin_cabeceras($filas);
				
				$orden_carreras = $this->devuelve_orden($filas[2], 'CARRERA');
				$orden_competencias = $this->devuelve_orden($filas[2], 'COMPETENCIA');
				$orden_unidad_competencia = $this->devuelve_orden($filas[2], 'UNIDAD DE COMPETENCIA');
				$orden_unidad_competencia = $this->devuelve_orden($filas[2], 'UNIDAD DE COMPETENCIA');
				$orden_asignatura = $this->devuelve_orden($filas[2], 'ASIGNATURA');
				$orden_nombre_asignatura = $this->devuelve_orden($filas[2], 'NOMBRE ASIGNATURA');	
				$orden_tipo_competencia = $this->devuelve_orden($filas[2], 'TIPO DE COMPETENCIA');	
				
				//OBTENEMOS LAS COLUMNAS DEL CSV PARA LUEGO ORDENARLAS
				$columna_carreras1 = $this->columnas_csv($this->csv_sin_cabeceras($filas),$orden_carreras);
				$columna_competencias = $this->columnas_csv($this->csv_sin_cabeceras($filas),$orden_competencias);
				$columna_unidad_competencias = $this->columnas_csv($this->csv_sin_cabeceras($filas),$orden_unidad_competencia);
				$columna_codigos_asignaturas = $this->columnas_csv($this->csv_sin_cabeceras($filas),$orden_asignatura);				
				$columna_asignaturas = $this->columnas_csv($this->csv_sin_cabeceras($filas),$orden_nombre_asignatura);
				$columna_tipo_competencia = $this->columnas_csv($this->csv_sin_cabeceras($filas),$orden_tipo_competencia);
			

				$this->unidad_competencia_vacia($columna_unidad_competencias);
				$columna_carreras = $this->rellenacolumna($columna_carreras1);
				$columna_competencias = $this->rellenacolumna($columna_competencias);								
				$columna_tipo_competencia = $this->rellenacolumna($columna_tipo_competencia);								
				$carreras = $this->carreras($columna_carreras);
		
				//$this->compruebafilascsv($columna_carreras1,$columna_competencias,$columna_asignaturas,$columna_codigos_asignaturas);
				$this->existen_carreras($carreras);
				$this->comprobarasignaturas($columna_codigos_asignaturas, $columna_asignaturas);
				$this->maxcompetencia();
				
				
				$listo_para_insertar = $this->matriz($columna_carreras,$columna_competencias, $columna_unidad_competencias, $columna_codigos_asignaturas, $columna_asignaturas, $columna_tipo_competencia);
				//echo var_dump($listo_para_insertar );
				//die;
				//SI EN ESTE ESTADO DEL PROCESO NO HAY ERRORES, ESCRIBIMOS A LA BASE DE DATOS
				
				
				//prx($listo_para_insertar);
				
				if (empty($this->errores)) {
					//EJECUTAMOS EL PROCESO DE ESCRITURA
					//echo var_dump($listo_para_insertar);
					$this->insertar($listo_para_insertar);					
				}				
				return true;
			}
			else {
				$this->errores[]= 'Error en la cabecera del archivo';
			}					
			fclose($handle);			
		}
	}	
		
	//COMPRUEBA SI LA CABECERA ES CORRECTA O NO LO ES
	private function comprobar_cabecera($cabecera) {
		if (empty($cabecera)){
			$this->errores[] = 'La cabecera del archivo está vacía';
			return false;
		}
		$numero_elementos = count($cabecera);
		if ($cabecera[$numero_elementos-1] == '') { array_pop ($cabecera); }
		//echo var_dump($cabecera);
		$diferencias = array_diff($cabecera, $this->cabecera_correcta);
		//echo var_dump($diferencias);
		if (!empty($diferencias)){
			if ($this->comprobar_diferencias($diferencias)){
				return true;
				}
			else {
				$this->errores[] = 'Existe alguna diferencia en la cabecera';
				return false;
			}		
		}
		else {	

			return true;
		}
	}
	
	
	//ESTA FUNCIÓN DEVUELVE EL CSV A PARTIR DE LA CABECERA
	private function csv_sin_cabeceras($csv){
		unset($csv[1]);
		unset($csv[2]);
		return $csv;
	}
	
	private function columnas_csv($csv, $numero_columna) {
		foreach ($csv as $fila) {
			$columna[] = $fila[$numero_columna];		
		}		
		return $columna;
	}
	
		
	//COMPRUEBA QUE EL NÚMERO DE ASIGNATURAS SEA IGUAL AL NÚMERO DE CODIGO
	private function comprobarasignaturas($columna_codigo_asignaturas, $columna_asignaturas){
		$ccodigos =0;
		$casignaturas =0;
		foreach ($columna_codigo_asignaturas as $codigo){
			if ($codigo !== '') {
				$ccodigos++;			
			}		
		}
		foreach ($columna_asignaturas as $asignatura){
			if ($asignatura !== '') {
				$casignaturas++;			
			}		
		}
		if ($ccodigos !== $casignaturas) {
			$this->errores[] = 'Problema con las asignaturas: hay '.$casignaturas.' asignaturas y '.$ccodigos.' codigos. Para cada asignatura debe existir un único código y viceversa en el archivo CSV.'  ;
			return false; 
		}
		else {
			return true; 		
		}	
	}
	
		
	
	
	//COMPRUEBO LAS DIFERENCIAS DEL ARRAY ES VÁLIDO SOLAMENTE QUE FALTEN LA ID DE COMPETENCIA Y LA DE UNIDAD DE COMPETENCIA
	private function comprobar_diferencias($diferencias){		
		$aciertos = 0;
		foreach ($diferencias as $diferencia){			
			if ($diferencia == 'ID COMPETENCIA'){
				$aciertos++;
			}
			if ($diferencia == 'ID UNIDAD DE COMPETENCIA'){
				$aciertos++;
			}	
		}
		if (($aciertos == 2) || ((($aciertos == 1) && (count($diferencias) == 1)))) {
			return true;
		}
		else {
			return false;
		}		
	}
	
	
	
	//RELLENA LA COLUMNA CON LOS DATOS ANTERIORES SI SON NULOS
	private function rellenacolumna($columna){
		if ((($columna[0]) == '') || (($columna[0]) == ' ')) {
			$this->errores[] = 'Revisar las competencias, la primera fila esta vacía.';
			return false;
		}
		$primerdato = $columna[0];
		foreach ($columna as $indice => $dato){					
			if (($dato == '') || ($dato == null)){
				$array_final[] = trim($primerdato);
			}
			else {
				$primerdato = $dato;
				$array_final[] = trim($dato);			
			}			
		}		
		return ($array_final);
	}
	
	
	
	//COMPRUEBA SI HAY ELEMENTOS REPETIDOS EN LA CABECERA
	private function duplicadoscabecera($cabeceracsv){
		foreach ($this->cabecera_correcta as $columna){
				$contador = 0;
				foreach ($cabeceracsv as $columna2) {
					if ($columna == $columna2) {
						$contador++;
					}					
				}
				if ($contador > 1) {
					$this->errores[]= 'En la cabecera existen elementos duplicados';
					return false;
				}
		}
	}
	
	//MÉTODO QUE COMPRUEBA SI HAY ALGUNA COLUMNA QUE TIENE MÁS DATOS QUE EL RESTO
	//EN ESTE CASO EL PROCESO FALLARÍA
	/*private function compruebafilascsv($columna_carreras,$columna_competencias,$columna_asignaturas,$columna_codigos_asignaturas) {
				$ncarreras = count($columna_carreras);
				$ncolumna_competencias = count($columna_competencias);
				$nasignaturas = count($columna_asignaturas);
				$ncodigos_asignaturas = count($columna_codigos_asignaturas);
				$cuentas[] = $ncarreras;
				$cuentas[] = $ncolumna_competencias;
				$cuentas[] = $nasignaturas;
				$cuentas[] = $ncodigos_asignaturas;
				echo var_dump($cuentas);
				foreach ($cuentas as $cuenta) {					
					if ($cuenta !== $ncarreras) {
						$this->errores[] = 'Hay alguna fila del CSV con elementos huérfanos.';
						return false;
					}
					else {
						return true;
					}
				}
	} */
	
	
	//DEVUELVE LAS CARRERAS DEL CSV PASÁNDOLE LA COLUMNA (ARRAY) DE CARRERA
	private function carreras($columna_carreras) {		
		foreach ($columna_carreras as $carrera){
			if ($carrera <> null){
				$carreras[] = $carrera;			
			}		
		}	
		return $carreras;
	}
	
	
	
	//COMPROBAMOS SI LAS CARRERAS EXISTEN EN EL SISTEMA, LE PASAMOS POR TANTO
	//UN ARRAY CON LAS CARRERAS
	private function existen_carreras($carreras) {
		$Carrera = ClassRegistry::init('Carrera');
		$carreras_existentes = $Carrera->find('all', array('fields' => array('Carrera.nombre')));
		
		$existen = false;
		$errores = 0;		
		foreach ($carreras as $carrera){				
				$carrera = $carrera;				
				$existen = false;
				foreach ($carreras_existentes as $carrera_existente){
					//$carrera_existente['Carrera']['nombre'] = utf8_decode($carrera_existente['Carrera']['nombre']);
					$carrera_existente['Carrera']['nombre'] = $carrera_existente['Carrera']['nombre'];
					
					if ($carrera_existente['Carrera']['nombre'] == $carrera) {
					  $existen = true;
					}					
				}
				
			//Revisamos si en algún punto 	
			if ($existen == false){
				$this->errores[] = 'La Carrera '.$carrera.' NO existe en el sistema';
				$errores = $errores++;
			}		
		}//FIN FOREACH GENERAL	
		if ($errores == 0) {
			return true;
		}
		else {
			return false;
		}	
	}
	

	
	
	//MÉTODO QUE COMPRUEBA SI EL TEXTO PASADO POR PARÁMETRO EXISTE EN LA BASE DE DATOS
	//COMPETENCIAS

	private function existecompetencia($textocompetencia){
		$Competencia = ClassRegistry::init('Competencia');
		//$competencias = $Competencia->find('count', array('conditions' => array('Competencia.nombre_competencia' => $textocompetencia)));
		
		//$competencias = $Competencia->find('count', array('conditions' => array('TRIM(Competencia.nombre_competencia)' => $textocompetencia)));
		$competencias = $Competencia->find('count', array('conditions' => array('TRIM(nombre_competencia) ' => trim($textocompetencia))));
		
		//$competencias = $Competencia->query("select * from rap_competencia where TRIM(nombre_competencia) like '".trim($textocompetencia)."%'");	
		
		//prx($competencias);

		if($competencias>0){		
			return true;
		}
		else {
			return false;
		}
		
		/*		
		$existe = false;		
		foreach ($competencias as  $key => $competencia)
		{
			//$competencias = $competencia['Competencia']['nombre_competencia'];
			//$competencia2 = utf8_encode($competencia2);
			
			//prx($competencia);
			//prx($competencia['Competencia']['nombre_competencia'].'='.$textocompetencia);
			
			if ($textocompetencia == $competencia['Competencia']['nombre_competencia'])
			{
				
				$existe = true;
				if ($existe){ /*echo ('Existe');}
			}	
		}
		
		return $existe;
		*/
	}
	
	
	//MÉTODO QUE DEVUELVE EL ID DE LA COMPETENCIA PASADA POR TEXTO
	private function idcompetencia($textocompetencia){
		$Competencia = ClassRegistry::init('Competencia');
		//$textocompetencia = $textocompetencia;
		$textocompetencia = trim($textocompetencia);	
		//$competencia2 = $Competencia->find('first', array('conditions' => array('TRIM(Competencia.nombre_competencia)' => $textocompetencia)));
		$competencia2 = $Competencia->find('first', array('conditions' => array('TRIM(Competencia.nombre_competencia) like' => $textocompetencia.'%')));
		
		
		
		/*
		if (empty($competencia2)) {
			pr($competencia2);
			pr($textocompetencia);
		}*/
		
		$codigo_competencia = ($competencia2['Competencia']['codigo_competencia']);	
		return ($codigo_competencia);
	}
	
	
	
	//MÉTODO QUE DEVUELVE EL SIGUIENTE DEL MÁXIMO DE UNA COMPETENCIA
	private function maxcompetencia($maximo = null){	
			$Competencia = ClassRegistry::init('Competencia');
			if (($maximo == null)){
				
				//$posicionCorteCompetencia = 
				
				
				$maximo = $Competencia->find('all', array('fields' => 'MAX(TO_NUMBER(SUBSTR(Competencia.codigo_competencia,5,8))) as MAXIMO'));
				$maximo = $maximo[0][0]['MAXIMO'];
				$maximo = $maximo;
				if ($maximo<10){ $maximo = '0'.$maximo;}
				$competencia = 'COMP'.$maximo;
			}
			else {
				$competencia = substr($maximo, 4, 5);
				$competencia = $competencia+1;
				$competencia = 'COMP'.$competencia;				
			}
			
			
			return ($competencia);
	}
	
	
	//MÉTODO QUE DEVUELVE EL ID DE LA COMPETENCIA PASADA POR TEXTO
	private function idunidadcompetencia($competencia, $texto_competencia){
		$Unidadcompetencia = ClassRegistry::init('Unidadcompetencia');
		$texto_competencia = trim($texto_competencia);
		$unidades = $Unidadcompetencia->find('first', array('conditions' => array('UnidadCompetencia.codigo_competencia' => $competencia, 'TRIM(UnidadCompetencia.nombre_unidad_comp)' => $texto_competencia)));
		return ($unidades['Unidadcompetencia']['codigo_unidad_comp']);
	}
	
	
	//MÉTODO QUE DEVUELVE SI EXISTE LA RELACIÓN ENTRE LA CARRERA Y LA COMPETENCIA
	private function existe_relacion_competencia_carrera($codigo_carrera, $codigo_competencia) {
		$Competenciacarrera = ClassRegistry::init('Competenciacarrera');
		$relacion = $Competenciacarrera->find('all', array('conditions' => array('Competenciacarrera.codigo_carrera' => $codigo_carrera, 'Competenciacarrera.codigo_competencia' => $codigo_competencia)));
		if (!empty($relacion)) {
			
			return true;
		}
		else {
			
			return false;
		}	
	}
	
	
	
	//MÉTODO QUE INDICA SI UNA UNIDAD DE COMPETENCIA EXISTE PARA UNA COMPETENCIA
	//PASADA POR PARÁMETRO
	
	private function existeunidadcompetencia($competencia, $texto_competencia){
		$Unidadcompetencia = ClassRegistry::init('Unidadcompetencia');
		$texto_competencia = trim($texto_competencia);
		
		
		$unidades = $Unidadcompetencia->find('all', array('conditions' => array('UnidadCompetencia.codigo_competencia' => $competencia, 'TRIM(UnidadCompetencia.nombre_unidad_comp)' => $texto_competencia)));
		if (!empty($unidades)) {
			//pr('TRUE:'.$competencia.'='.$texto_competencia);
			return TRUE;
		}
		else {
			//pr('FALSE'.$competencia.'='.$texto_competencia);
			return false;
		}	
	}
	
	
	//MÉTODO QUE INDICA SI UNA COMPETENCIA EXISTE
	//PASADA POR PARÁMETRO SU CÓDIGO DE COMPETENCIA
	
	private function existecompetenciaxcodigo($codigo_competencia){
		$Competencia = ClassRegistry::init('Competencia');
		$competencia1 = $Competencia->find('first', array('conditions' => array('Competencia.codigo_competencia' => $codigo_competencia)));
		if (!empty($competencia1)) {
			return TRUE;
		}
		else {
			return false;
		}	
	}
	
	
	
		//MÉTODO QUE INDICA SI UNA COMPETENCIA EXISTE
	//PASADA POR PARÁMETRO SU CÓDIGO DE COMPETENCIA
	
	private function existeunidadcompetenciaxcodigo($codigo_unidad_competencia){
		$Unidadcompetencia = ClassRegistry::init('Unidadcompetencia');		
		$unidad = $Unidadcompetencia->find('count', array('conditions' => array('Unidadcompetencia.codigo_unidad_comp' => $codigo_unidad_competencia)));		
		
		if($unidad > 0)
		{
			return true;
		}else{
			return false;
		}
		
	}
	
	
	//ESTA FUNCIÓN COMPRUEBA SI EXISTE ALGUNA UNIDAD DE COMPETENCIA VACÍA
	private function unidad_competencia_vacia($unidadesdecompetencia){
		$errores = 0;
		foreach ($unidadesdecompetencia as $indice => $unidaddecompetencia){
			if ((($unidaddecompetencia) == '' || ($unidaddecompetencia) == null) || ($unidaddecompetencia) == ' ') {
				$fila = $indice + 1;
				$errores++;
				$this->errores[] = 'La unidad de competencia de la fila '. $fila .' está vacía.';	
			} 		
		}
		if ($errores > 0){ return false;}	
		else{return true;}
	}
	
	
	
	//MÉTODO QUE DEVUELVE EL SIGUIENTE AL MÁXIMO DE UNA UNIDAD DE COMPETENCIA,
	//PASÁNDOLE COMO PARÁMETRO LA COMPETENCIA
	
	private function maximounidadcompetencia($codigo_competencia, $maximo_memoria = null) {
		$Unidadcompetencia = ClassRegistry::init('Unidadcompetencia');		
		if ($maximo_memoria == null){
						
			
			//Esto es para que corte dependiendo de que la competencia sea más o menos 100
			$posicionCorte = strlen($codigo_competencia) + 7;
			
			$maximo = $Unidadcompetencia->find('all', array('fields' => 'MAX(TO_NUMBER(SUBSTR(Unidadcompetencia.codigo_unidad_comp,'.$posicionCorte.',8))) as MAXIMO',
															'conditions' => array('Unidadcompetencia.codigo_competencia' => $codigo_competencia )));
			
			$maximo = $maximo[0][0]['MAXIMO'];		
			$maximo = $maximo + 1;
			
			//echo var_dump($maximo);
			if ($maximo<10){ $maximo = '0'.$maximo;} //Para mantener la coherencia de las tablas, si el máximo es un número menor de 10, se le agrega un 0 antes de ponerle el máximo.
				$codigo_nuevo = $codigo_competencia.'_UCOMP'.$maximo;
				//pr('1)'.$codigo_nuevo);
			}
		else {
			//pr('asd');
				$posicionCorteMaxMemoria = strpos($maximo_memoria,'UCOMP')+5;
			
				$unidadcompetencia = substr($maximo_memoria, $posicionCorteMaxMemoria);
				
				//pr($unidadcompetencia.','.$maximo_memoria.','.$posicionCorteMaxMemoria);
				
				$unidadcompetencia = $unidadcompetencia+1;
				if ($unidadcompetencia < 10) {$unidadcompetencia = '0'.$unidadcompetencia;}
				$codigo_nuevo = $codigo_competencia.'_UCOMP'.$unidadcompetencia.'';
				//pr('2)'.$codigo_nuevo);
		}
		
		return ($codigo_nuevo);		
	}
	
	
	
	//MÉTODO QUE DEVUELVE SI LA ASIGNATURA PASADA POR EL CÓDIGO EXISTE O NO	
	private function existeasignatura($codigo_asignatura){
		$Asignatura = ClassRegistry::init('Asignatura');
		$codigo_asignatura = trim($codigo_asignatura);
		$asignaturas = $Asignatura->find('first', array('conditions'=> array('TRIM(codigo_asignatura)' => $codigo_asignatura)));
		if (!empty($asignaturas)){
			return true;
		}
		else {
			return false;
		}	
	}
	
	
	
	
	//MÉTODO QUE DEVUELVE SI LA ASIGNATURA PASADA POR EL CÓDIGO ESTÁ RELACIONADA CON LA UNIDAD DE COMPETENCIA
	private function existeasignaturaunidad($codigo_asignatura, $codigo_unidad_competencia){
		$Unidadcompetenciaasignatura = ClassRegistry::init('Unidadasignatura');		
		$Unidadasignatura = $Unidadcompetenciaasignatura->find('first', array('conditions'=> array('codigo_asignatura' => $codigo_asignatura, 'codigo_unidad_comp' => $codigo_unidad_competencia)));
		if (!empty($Unidadasignatura)){
			return true;
		}
		else {
			return false;
		}	
	}
	
	
	
	
	//DEVUELVE UN ARRAY CON EL ORDEN DE LAS COLUMNAS SEGÚN LA VARIABLE PRINCIPAL
	//CON ESTO DEBERÍAMOS CONSEGUIR QUE SI NOS DAN EL CSV DESORDENADO, NO IMPORTE YA
	//QUE LO IMPORTANTE SON LAS CABECERAS, 
	private function ordenar_columnas($cabecera_csv){
		$aux = 0;
		foreach ($this->cabecera_correcta as $indidice => $dato) {			
			$aux++;
			foreach ($cabecera_csv as $indidice2 => $dato2) {
				if (($dato) == ($dato2)){
					  $mapeo[$aux][1] = $indidice2+1;				
					  $mapeo[$aux][2] = $dato;				
					  
				}		
			}
		}
		//echo var_dump($mapeo);
		return $mapeo;	
	}
	
	
	//PASADO UN DATO Y EL MAPEO DE LA FUNCIÓN ANTERIOR, TE DEVUELVE EL NÚMERO DE COLUMNA
	// EN EL QUE ESTÁ UBICADO
	private function devuelve_orden($mapeo, $columna) {
		foreach ($mapeo as $indice=>$dato) {
			if ($columna == $dato) {
				return $indice;			
			} 		
		}
	}
	
	
	//FUNCIÓN QUE DEVUELVE EL ID DE LA CARRERA PASADO UN TEXTO POR PARÁMETRO
	private function idcarrera($textocarrera){
		$Carrera = ClassRegistry::init('Carrera');
		
		$carrera_elegida = $Carrera->find('first', array('conditions'=> array('nombre' => $textocarrera)));
		if (empty($carrera_elegida)){
			//$this->errores[] = 'Ha habido algún problema obteniendo el ID de la carrera '.utf8_encode($textocarrera).'';		
			return false;
		}
		else{			
			$codigo_carrera = $carrera_elegida['Carrera']['codigo'];
		
			if(isset($codigo_carrera))
			{
				return ($codigo_carrera);		
			}else{
				return false;
			}
		
			
		} 
	}
	

	
	//COMIENZO A ESCRIBIR LA MATRIZ DE DATOS. 
	private function matriz($carreras, $competencias, $unidadesdecomeptencia, $columna_codigo_asignaturas, $columna_asignaturas, $columna_tipo_competencia){
		//echo var_dump($carreras);
		//echo var_dump($competencias);
		//echo var_dump($unidadesdecomeptencia);
		
		
		foreach ($carreras as $carrera){
			$carreras2[] = $this->idcarrera($carrera);		
		}		
		$maxcompetencia = $this->maxcompetencia();
			
			
			//pr($competencias);
			
		foreach ($competencias as $indice => $competencia)
		{
			
			
			$encontrado = false;
			for($indC = 0; $indC < $indice && !$encontrado ; $indC++)
			{
				
				if($competencias[$indC] == $competencia)
				{
					$encontrado = true;
					$columna_com[$indice] = $columna_com[$indC];
				}
			}
			if(!$encontrado)
			{
				
				//pr('busco competencia ');
				//pr($competencia);
				
				if ($this->existecompetencia($competencia)) {
						
						//prx('existe competencia');
									
						$columna_com[$indice] = $this->idcompetencia($competencia);
				}
				else {
					
					//prx('no existe competencia');
								
					$maxcompetencia = $this->maxcompetencia($maxcompetencia);
					$columna_com[$indice] = $maxcompetencia;
					
					
				}
			}
		}

		//prx($columna_com);

		
		//RELLENAMOS LAS UNIDADES DE COMPETENCIA
		foreach ($unidadesdecomeptencia as $indice2 => $unidades){			
			$competencia = $columna_com[$indice2];
			$maxunidadcompetencia[$competencia][] = $this->maximounidadcompetencia($competencia);
			//echo var_dump($maxunidadcompetencia);
			
			$codigoCompetenciaDeLaActual = $competencia;
			$textoUnidadCompetenciaTesteando = $unidades;
								
			$existeUnidadCompetencia = $this->existeunidadcompetencia($competencia, $unidades);
			
			
			$encontrado = false;
			$codigoUnidadCompetenciaEncontrado = null;
			if (!$existeUnidadCompetencia && $indice2>0)
			{					
				
				for($indInv=0; $indInv < $indice2 && !$encontrado; $indInv++)
				{
					$codigoCompetenciaDeEste = $columna_com[$indInv];
					$TextoUnidadCompetenciaDeEste = $unidadesdecomeptencia[$indInv];
					
								
					if($codigoCompetenciaDeEste == $codigoCompetenciaDeLaActual &&
					   $TextoUnidadCompetenciaDeEste == $textoUnidadCompetenciaTesteando)
					{
						$encontrado = true;
						$codigoUnidadCompetenciaEncontrado = $columna_unidad_com[$indInv];
					}
				}			
			}	
			
			if ($existeUnidadCompetencia){				
				$columna_unidad_com[$indice2] = $this->idunidadcompetencia($competencia, $unidades);
				
			}
			else {
				
				if(!$encontrado)
				{
					$columna_unidad_com[$indice2] = $maxunidadcompetencia[$competencia][0];
					$maxunidadcompetencia[$competencia][0] = $this->maximounidadcompetencia($competencia,$maxunidadcompetencia[$competencia][0]);
				}
				else
				{
					
					
					$columna_unidad_com[$indice2] = $codigoUnidadCompetenciaEncontrado;
				}
			}			
		}
		
		
		//RELLENAMOS LAS CLAVES Y LE PONEMOS A FORMATO BOOLEANO
		foreach ($columna_tipo_competencia as $key2 => $tipo){
			if ($tipo == 'CLAVE'){
				$columna_tipo_competencia_nueva[$key2] = '0';			
			}
			else{
				$columna_tipo_competencia_nueva[$key2] = '1';			
			}
		
		}
		
		
		
		//pr($unidadesdecomeptencia);
		//prx($columna_unidad_com);
		
		//RELLENAMOS LAS ASIGNATURAS Y LAS FORMATEAMOS A UTF8		
		$columna_asignaturas = $this->rellenacolumna($columna_asignaturas);		
		$columna_codigo_asignaturas = $this->rellenacolumna($columna_codigo_asignaturas);
		
		//echo var_dump($columna_codigo_asignaturas);
		foreach ($columna_asignaturas as $asignaturas) {
			$columna_final_asignaturas[] = $asignaturas;
		}		
		//echo var_dump($columna_final_asignaturas);
		$resultado = array(	'carreras' => $carreras2,
							'codigo_competencia' => $columna_com,
							'competencias' => $competencias,
							'unidadesdecompetencia' => $unidadesdecomeptencia,
							'codigounidadesdecompetencia' => $columna_unidad_com,
							'codigo_asignaturas' => $columna_codigo_asignaturas,
							'asignaturas' => $columna_final_asignaturas,
							'tipo_de_competencia' => $columna_tipo_competencia_nueva
		);
		//prx($unidadesdecomeptencia);
		//CMG
		//
		//prx($resultado);
		
		return $resultado;
	}
	
	//ESTA FUNCIÓN PROCESA LOS DATOS Y LOS VA INSERTANDO
	private function insertar($matriz_correcta) {
		//prx($matriz_correcta);
		$dataSource = $this->getDataSource();
		$Carrera = ClassRegistry::init('Carrera');		
		$Competencia = ClassRegistry::init('Competencia');		
		$Asignatura = ClassRegistry::init('Asignatura');		
		$Competenciacarrera = ClassRegistry::init('Competenciacarrera');
		$Unidadcompetencia = ClassRegistry::init('Unidadcompetencia');		
		$Unidadasignatura = ClassRegistry::init('Unidadasignatura');
		$filas = count($matriz_correcta['carreras']);
		$dataSource->begin();	
		try{			
			for ($i = 0; $i <= $filas-1; $i++) {
					//CARRERAS Y COMPETENCIAS CARRERAS
					
					
					
					if (($this->existecompetenciaxcodigo($matriz_correcta['codigo_competencia'][$i]))){		 //EXISTE LA COMPETENCIA
					
						if ($this->existe_relacion_competencia_carrera($matriz_correcta['carreras'][$i], $matriz_correcta['codigo_competencia'][$i]))
						{
							//pr($matriz_correcta['competencias'][$i]);
						}
						else{
							$competencia_carrera_guardar = array('codigo_carrera' => $matriz_correcta['carreras'][$i],
																'codigo_competencia' =>	$matriz_correcta['codigo_competencia'][$i]);						
							if (!$Competenciacarrera->save($competencia_carrera_guardar)){
								throw new Exception();
								}
						}
						
						
					}					
					else { //NO EXISTE LA COMPETENCIA BUSCADA POR CÓDIGO POR TANTO INSERTAMOS EL CÓDIGO DE LA COMPETENCIA, EL TEXTO Y LA RELACIÓN CON LA CARRERA
						//PREGUNTA SI EXISTE LA COMPETENCIA, DE NO EXISTIR SOLO CREARA 1 ENLASADO CON EL CODIGO DE LA COMPETENCIA
						
						$competencia_guardar = array('codigo_competencia' => $matriz_correcta['codigo_competencia'][$i],
													'nombre_competencia' =>	$matriz_correcta['competencias'][$i],
													'troncal' =>	$matriz_correcta['tipo_de_competencia'][$i]);
						
						if (!$Competencia->save($competencia_guardar)){throw new Exception();}					
						$competencia_carrera_guardar = array('codigo_carrera' => $matriz_correcta['carreras'][$i],
															'codigo_competencia' =>	$matriz_correcta['codigo_competencia'][$i],					
															'troncal' =>	$matriz_correcta['tipo_de_competencia'][$i]);					
						if (!$Competenciacarrera->save($competencia_carrera_guardar)){
								throw new Exception();
								}
					}
					
					//UNIDADES DE COMPETENCIA
					if (($this->existeunidadcompetenciaxcodigo($matriz_correcta['codigounidadesdecompetencia'][$i]))){		 //EXISTE LA UNIDAD DE COMPETENCIA			
						//NO SE HACE NADA PORQUE EXISTE
					}
					else {
						$unidad_competencia_guardar = array('codigo_competencia' => $matriz_correcta['codigo_competencia'][$i],
															'codigo_unidad_comp' =>	$matriz_correcta['codigounidadesdecompetencia'][$i],
															'nombre_unidad_comp' =>	$matriz_correcta['unidadesdecompetencia'][$i]
													);
						//Corregir errores esn este nivel.
						if (!$Unidadcompetencia->save($unidad_competencia_guardar))	{ throw new Exception();}
					}
					
					//ASIGNATURAS
					if (($this->existeasignatura($matriz_correcta['codigo_asignaturas'][$i])) == true){		 
						//TENEMOS QUE SALVAR LA RELACIÓN ENTRE LA ASIGNATURA Y LA UNIDAD DE COMPETENCIA
						if ($this->existeasignaturaunidad($matriz_correcta['codigo_asignaturas'][$i], $matriz_correcta['codigounidadesdecompetencia'][$i]) == false) {
							$asignatura_unidad_guardar = array('codigo_unidad_comp' => $matriz_correcta['codigounidadesdecompetencia'][$i],
														'codigo_asignatura' =>	$matriz_correcta['codigo_asignaturas'][$i],												
														);
							
							if (!$Unidadasignatura->save($asignatura_unidad_guardar)){ throw new Exception();}
						}
					}
					else {
						//SALVAMOS EN PRIMER LUGAR LA ASIGNATURA
						$asignatura_guardar = array('codigo_asignatura' => $matriz_correcta['codigo_asignaturas'][$i],
													'nombre_asignatura' =>	$matriz_correcta['asignaturas'][$i],												
													);
						$Asignatura->save($asignatura_guardar);
						
						//ADEMAS TENEMOS QUE SALVAR LA RELACIÓN ENTRE LA ASIGNATURA Y LA UNIDAD DE COMPETENCIA
						if ($this->existeasignaturaunidad($matriz_correcta['codigo_asignaturas'][$i], $matriz_correcta['codigounidadesdecompetencia'][$i]) == false) {
							$asignatura_unidad_guardar = array('codigo_unidad_comp' => $matriz_correcta['codigounidadesdecompetencia'][$i],
																'codigo_asignatura' =>	$matriz_correcta['codigo_asignaturas'][$i],												
														);
							if (!$Unidadasignatura->save($asignatura_unidad_guardar)){ throw new Exception();}
						}					
					}
					
					}  //FIN DEL FOR
					
				$dataSource->commit();
					
					
		} //FIN DEL TRY
		catch (Exception $e) {			
		$this->errores[] =  'Error en la transacción';
			$dataSource->rollback();
		}
	} //FIN DE LA FUNCIÓN
	
}
?>
