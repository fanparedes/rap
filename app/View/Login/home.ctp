<?php
//echo var_dump($funciones);
//echo var_dump($cabecera);
$total=count($funciones);
$ant='a';
echo '<ul id="menu-full">';
if (!empty($cabecera)){
		echo "<li id='prin'>".
								$this->Html->link('<div class="li-menu li-menu-orange"><i class="icon-home"></i></div><div class="li-menu-texto">Home</div>',array('controller'=>'administrativos', 'action' => 'home'),array('escape'=>false)).
								"</li>";
}
for ($i=0;$i<$total;$i++){
	if (isset($funciones[$i-1])){
		$ant=$funciones[$i-1]['Funcion']['controlador'];
	}
	if ($ant!=$funciones[$i]['Funcion']['controlador']){
		if ((isset($funciones[$i+1]))&&($funciones[$i]['Funcion']['controlador']==$funciones[$i+1]['Funcion']['controlador'])){
			echo "<li id='dro'>
	<a data-toggle='dropdown' href='#'>
		<div class='li-menu li-menu-orange'>
			<i class='";
			//EN ESTE SWITCH PODEMOS CAMBIAR LOS ICONOS PRINCIPALES DEL MENÚ.
			switch ($funciones[$i]['Funcion']['controlador']){
				case 'administrativos':echo 'icon-folder';break;
				case 'permisos';echo 'icon-key';break;
				case 'orientadores':echo 'icon-bookmark';break;
				case 'sistemas':echo 'icon-gears';break;
				//case 'evaluadores':break;
				case 'postulaciones':echo 'icon-file-o';break;
				
				
				default:echo $funciones[$i]['Funcion']['clase'];break;
			}
			
			echo "'></i>
		</div>
		<div class='li-menu-texto'>".ucwords($funciones[$i]['Funcion']['controlador'])."</div>
		<span class='caret'></span>
	</a>
	<ul class='dropdown-menu'>";
			echo "<li>".
			$this->Html->link("
			
				<div class='li-menu-drop li-menu-drop-orange'>
					<i class='".$funciones[$i]['Funcion']['clase']."'></i>		
				" .ucwords($funciones[$i]['Funcion']['friendly'])."</div>
			</a>"
			,array('controller'=>$funciones[$i]['Funcion']['controlador'], 'action' => $funciones[$i]['Funcion']['funcion']),array('escape'=>false)).
			"</li>";
		}else{
			echo "<li id='prin'>".
								$this->Html->link('<div class="li-menu li-menu-orange"><i class="'.$funciones[$i]['Funcion']['clase'].'"></i></div><div class="li-menu-texto">'.ucwords($funciones[$i]['Funcion']['friendly']).'</div>',array('controller'=>$funciones[$i]['Funcion']['controlador'], 'action' => $funciones[$i]['Funcion']['funcion']),array('escape'=>false)).
								"</li>";
		}
	}else{
		if ((isset($funciones[$i+1]))&&($funciones[$i]['Funcion']['controlador']==$funciones[$i+1]['Funcion']['controlador'])){
			echo "<li>".
			$this->Html->link("
			
				<div class='li-menu-drop li-menu-drop-orange'>
					<i class='".$funciones[$i]['Funcion']['clase']."'></i>".ucwords($funciones[$i]['Funcion']['friendly'])."</div>
			</a>"
			,array('controller'=>$funciones[$i]['Funcion']['controlador'], 'action' => $funciones[$i]['Funcion']['funcion']),array('escape'=>false)).
			"</li>";
		}else{
			echo "<li>".
			$this->Html->link("
			
				<div class='li-menu-drop li-menu-drop-orange'>
					<i class='".$funciones[$i]['Funcion']['clase']."'></i>
				" .ucwords($funciones[$i]['Funcion']['friendly'])."</div>
			</a>"
			,array('controller'=>$funciones[$i]['Funcion']['controlador'], 'action' => $funciones[$i]['Funcion']['funcion']),array('escape'=>false)).
			"</li>";
			echo "</ul>
</li>";
		}
	}

}
if (!empty($cabecera)){
$user = $this->Session->read('UserLogued');

echo "<li id='dro'>
	<a data-toggle='dropdown' href='#'>
		<div class='li-menu li-menu-orange'>
			<i class='icon-user'></i>
		</div>
		<div class='li-menu-texto'>".substr($user['Administrativo']['nombre'],0,15)."</div>
		<span class='caret'></span>
	</a>
	<ul class='dropdown-menu'>
		
		<li>
			".$this->Html->link("
				<div class='li-menu-drop li-menu-drop-orange'>
					<i class='icon-power-off'></i>
				</div>
				<div class='li-menu-drop-texto'>Salir</div>
			",array('controller'=>'login','action'=>'logout_administrativos'),array('escape'=>false))."
		</li>
	</ul>
</li>";


}
echo '</ul>';