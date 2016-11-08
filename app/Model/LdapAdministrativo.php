<?php
App::uses('AppModel', 'Model');
class LdapAdministrativo {

	var $name = 'LdapAdministrativo';
	var $direccion = LDAP_ADMINISTRATIVO;
	var $puerto = 389;
	var $ds = null;

	function connect($user=null,$pass=null){
		$ldap_response = array(
			'username' => null, #null o rut sin digito verificador
			'status' => '', #success o error
			'mensaje' => '' #ok o mensaje de error
		);
		
		try{
			
			$this->ds = ldap_connect($this->direccion,$this->puerto);
				
			ldap_set_option($this->ds, LDAP_OPT_PROTOCOL_VERSION, 3);
			ldap_set_option($this->ds, LDAP_OPT_REFERRALS, 0);
			
			
			if($r=@ldap_bind($this->ds,$user."@duoc.cl",$pass)){
				
		       	$sradm=ldap_search($this->ds,"DC=duoc,DC=cl", "(sAMAccountName=".$user.")");
		       	$info = ldap_get_entries($this->ds, $sradm);
		       	if(!$r || empty($info)){
					$ldap_response = array(
						'username' => $user,
						'status' => 'error', #success o error
						'mensaje' => 'Usuario o contraseña incorrectos.' #ok o mensaje de error
					);
		       	}else{
		       		$ldap_response = array(
						'username' => $user,
						'status' => 'success', #success o error
					);
		       	}
			}else{
				#error final
				$ldap_response = array(
					'username' => $user,
					'status' => 'error bind', #success o error
					'mensaje' => 'Usuario o contraseña incorrectos' #ok o mensaje de error
				);
			}
		}catch(Exception $e){
			$ldap_response = array(
				'username' => $user,
				'status' => 'error', #success o error
				'mensaje' => 'Hubo un error en la conexión, favor intente más tarde' #ok o mensaje de error
			);
		}
	return $ldap_response;
	}
}
?>