<ul id="menu-full">
<li id="prin">
	<a style="margin-left: 0px !important;" href="<?php echo $this->Html->url(array('controller'=>'administrativos', 'action' => 'index'))?>">
		<div class="li-menu li-menu-orange"><i class="icon-home"></i></div><div class="li-menu-texto">Home</div>
	</a>
</li>
<li id="prin">
	<a style="margin-left: 0px !important;" href="<?php echo $this->Html->url(array('controller'=>'administrativos', 'action' => 'index'))?>">
		<div class="li-menu li-menu-orange"><i class="icon-home"></i></div><div class="li-menu-texto">Home</div>
	</a>
</li> 
<li id="prin">
	<a style="margin-left: 0px !important;" href="<?php echo $this->Html->url(array('controller'=>'administrativos', 'action' => 'index'))?>">
		<div class="li-menu li-menu-orange"><i class="icon-home"></i></div><div class="li-menu-texto">Home</div>
	</a>
</li> 
 
<li id="dro">
	<a data-toggle="dropdown" href="#">
		<div class="li-menu li-menu-orange">
			<i class="icon-user"></i>
		</div>
		<div class="li-menu-texto"><?php echo substr($user['Administrativo']['nombre'],0,15); ?></div>
		<span class="caret"></span>
	</a>
	<ul class="dropdown-menu">
		<li>
			<a href="<?php echo $this->Html->url(array('controller'=>'login','action'=>'logout_administrativos')); ?>">
				<div class="li-menu-drop li-menu-drop-orange">
					<i class="icon-power-on"></i>
				</div>
				<div class="li-menu-drop-texto">Loguearse</div>
			</a>
		</li>
		<li>
			<a href="<?php echo $this->Html->url(array('controller'=>'login','action'=>'logout_administrativos')); ?>">
				<div class="li-menu-drop li-menu-drop-orange">
					<i class="icon-power-off"></i>
				</div>
				<div class="li-menu-drop-texto">Salir</div>
			</a>
		</li>
	</ul>
</li>
</ul>