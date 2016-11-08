<?php 
App::uses('AppHelper', 'View/Helper');

class formatHelper extends AppHelper {
	
   	public function rut($rut=null){	 
		$largo = strlen($rut);
	  	if($largo == 8){
	   		$parte1 = substr($rut, 0,1);
		    $parte2 = substr($rut, 1,3); 
		    $parte3 = substr($rut, 4,3);
			$dv = substr($rut,7,9); 
		    return $parte1.'.'.$parte2.'.'.$parte3.'-'.$dv;
			
		}elseif($largo == 9){
	   		$parte1 = substr($rut, 0,2);
		    $parte2 = substr($rut, 2,3); 
		    $parte3 = substr($rut, 5,3);
			$dv = substr($rut,8,9);
			return $parte1.'.'.$parte2.'.'.$parte3.'-'.$dv;
		}else{	
			return $rut;
		}	
    }
}


?>