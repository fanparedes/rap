<?php

App::uses('AppModel', 'Model');

class Postulante extends AppModel {

    var $useTable = 'RAP_POSTULANTES';
    var $primaryKey = 'CODIGO';

    function connect($nombre_usuario = null, $password = null) {
        $usuario = $this->find('first', array(
            'conditions' => array('email' => $nombre_usuario, 'password' => md5($password))
        ));
        if (!empty($usuario)) {
            return $usuario;
        } else {
            return array();
        }
    }
    
        //'rule' => '/^[1-9a-záéíóúÁÉÍÓÚñÑ\s]{1,}$/i',
    function validaInsert($data) {
        $this->set($data);

        $this->validate = array(
            //'nombre' => array('required' => true, 'rule' => '/^[1-9a-záéíóúÁÉÍÓÚñÑ\s]{1,}$/i',  'message' => 'Nombre incorrecto'),
            'rut' => array(
                //array('required' => true, 'rule' => '/^0*(\d{1,3}(\.?\d{3})*)\-?([\dkK])$/i', 'message' => 'Rut incorrecto'),
                array('required' => false, 'rule' => 'isUnique', 'message' => 'Ya existe un usuario con ese RUT')
            ),
            // 'fecha_nacimiento' => array('required' => true, 'rule' => '/^(0[1-9]|[1-2][0-9]|3[0-1])-(0[1-9]|1[0-2])-[0-9]{4}$/',
                // 'message' => 'fecha de nacimiento incorrecta'),
            // 'password' => array('required' => true, 'rule' => '/^[0-9a-f]{32}$/i', 'message' => 'Contraseña incorrecta'),
            'email' => array(
                array('required' => true, 'rule' => 'email', 'message' => 'Correo incorrecto'),
                array('required' => true, 'rule' => 'isUnique', 'message' => 'Ya existe un usuario con ese correo')
            ),
            'telefonomovil' => array('required' => true, 'rule' => 'numeric','allowEmpty' => true, 'message' => 'Teléfono incorrecto'),
            'genero' => array('required' => true, 'rule' => '/^[M|F]$/i', 'message' => 'Género incorrecto'),
        );
		
		/*
		if($data['Postulante']['extranjero'] == '1'){
			//Es extranjero, no se valida
		}
		else{ //Es chilensi
			$this->validate['rut'] = array(
                array('required' => false, 'rule' => 'isUnique', 'message' => 'Ya existe un usuario con ese RUT')
            );
		}
		*/
		
        return $this->validates();
    }

    function consultaMail($correo = null) {
        $usuario = $this->find('first', array(
            'conditions' => array('email' => $correo)
        ));
        if (!empty($usuario)) {
            return $usuario;
        } else {
            return array();
        }
    }

    function recuperarClave($pass = null, $id_postulante = null) {
        $pass = md5($pass);
        $sql = "UPDATE RAP_POSTULANTES set password = '" . $pass . "' WHERE codigo = '" . $id_postulante . "';";
        $this->query($sql);
        return true;
    }

}
