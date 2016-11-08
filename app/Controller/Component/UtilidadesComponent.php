<?php
App::uses('Component', 'Controller');
App::uses('Security', 'Utility');
App::uses('Hash', 'Utility');

class UtilidadesComponent extends Component {
 
    var $llave = " ";
 
	function encriptar($valor = null) {
        $output = base64_encode(mcrypt_encrypt(MCRYPT_RIJNDAEL_256, md5($this->llave), $valor, MCRYPT_MODE_CBC, md5(md5($this->llave))));
        $output = str_replace('/','KkK', $output);
		$output = str_replace('%','p', $output);
		$output = str_replace('?','p', $output);
		$output = str_replace('¿','p', $output);
        return $output;
    }
 
    function desencriptar($valor = null) {
    	$valor = str_replace('KkK', '/', $valor);
		$output = str_replace('p','%', $valor);
		$output = str_replace('p','?', $output);
		$output = str_replace('p','¿', $output);
        $output = rtrim(mcrypt_decrypt(MCRYPT_RIJNDAEL_256, md5($this->llave), base64_decode($valor), MCRYPT_MODE_CBC, md5(md5($this->llave))), "\0");
        return $output;
    }
 
}