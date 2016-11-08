<div class="ciudades view">
<h2><?php  __('Ciudad');?></h2>
	<dl><?php $i = 0; $class = ' class="altrow"';?>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Id Ciudad'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $ciudad['Ciudad']['id_ciudad']; ?>
			&nbsp;
		</dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Regiones'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $this->Html->link($ciudad['regiones']['nom_region'], array('controller' => 'regiones', 'action' => 'view', $ciudad['regiones']['id_region'])); ?>
			&nbsp;
		</dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Nom Ciudad'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $ciudad['Ciudad']['nom_ciudad']; ?>
			&nbsp;
		</dd>
	</dl>
</div>
<div class="actions">
	<h3><?php __('Actions'); ?></h3>
	<ul>
		<li><?php echo $this->Html->link(sprintf(__('Edit %s', true), __('Ciudad', true)), array('action' => 'edit', $ciudad['Ciudad']['y'])); ?> </li>
		<li><?php echo $this->Html->link(sprintf(__('Delete %s', true), __('Ciudad', true)), array('action' => 'delete', $ciudad['Ciudad']['y']), null, sprintf(__('Are you sure you want to delete # %s?', true), $ciudad['Ciudad']['y'])); ?> </li>
		<li><?php echo $this->Html->link(sprintf(__('List %s', true), __('Ciudades', true)), array('action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(sprintf(__('New %s', true), __('Ciudad', true)), array('action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(sprintf(__('List %s', true), __('Regiones', true)), array('controller' => 'regiones', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(sprintf(__('New %s', true), __('Regiones', true)), array('controller' => 'regiones', 'action' => 'add')); ?> </li>
	</ul>
</div>
