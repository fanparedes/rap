<?php

App::uses('Component', 'Controller');

class IngeniaComponent extends Component {

    function initialize($controller, $settings = array()) {
        
    }

    function validaRut($rut) {
        $dv = substr($rut, -1);
        $rut = substr($rut, 0, -1);
        return $dv == $this->dv($rut);
    }

    private function dv($r) {
        //calcula el digito verificador del rut
        $s = 1;
        for ($m = 0; $r != 0; $r/=10) {
            $s = ($s + $r % 10 * (9 - $m++ % 6)) % 11;
        }
        return chr($s ? $s + 47 : 75);
    }
	
	
	function formatearFecha($fecha){		
		$fecha = date("d-m-Y", strtotime($fecha));
		return $fecha;	
	}

}
