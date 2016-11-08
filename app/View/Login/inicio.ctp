<?php echo $this->Html->link(
			    'Cerrar sesión',
			    array(
			        'controller' => 'login',
			        'action' => 'logout',
			        'full_base' => true
			    )
			);?>