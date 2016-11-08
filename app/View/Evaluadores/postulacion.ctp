<?php if($this->Session->read('UserLogued.Administrativo.perfil') == 0) :?>
<?php echo $this->element('formulario_postulacion_evaluador_administrador') ;?>
<?php else :?>
<?php echo $this->element('formulario_postulacion_evaluador') ;?>
<?php endif ;?>