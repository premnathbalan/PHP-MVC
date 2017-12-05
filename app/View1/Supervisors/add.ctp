<div class="supervisors form">
<?php echo $this->Form->create('Supervisor'); ?>
	<fieldset>
		<legend><?php echo __('Add Supervisor'); ?></legend>
	<?php
		echo $this->Form->input('supervisor_name');
		echo $this->Form->input('supervisor_name_tamil');
		echo $this->Form->input('created_by');
		echo $this->Form->input('modified_by');
	?>
	</fieldset>
<?php echo $this->Form->end(__('Submit')); ?>
</div>
<div class="actions">
	<h3><?php echo __('Actions'); ?></h3>
	<ul>

		<li><?php echo $this->Html->link(__('List Supervisors'), array('action' => 'index')); ?></li>
		<li><?php echo $this->Html->link(__('List Phd Students'), array('controller' => 'phd_students', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Phd Student'), array('controller' => 'phd_students', 'action' => 'add')); ?> </li>
	</ul>
</div>
