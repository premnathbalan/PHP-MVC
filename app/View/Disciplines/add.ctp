<div class="disciplines form">
<?php echo $this->Form->create('Discipline'); ?>
	<fieldset>
		<legend><?php echo __('Add Discipline'); ?></legend>
	<?php
		echo $this->Form->input('discipline_name');
		echo $this->Form->input('discipline_name_tamil');
		echo $this->Form->input('created_by');
		echo $this->Form->input('modified_by');
	?>
	</fieldset>
<?php echo $this->Form->end(__('Submit')); ?>
</div>
<div class="actions">
	<h3><?php echo __('Actions'); ?></h3>
	<ul>

		<li><?php echo $this->Html->link(__('List Disciplines'), array('action' => 'index')); ?></li>
		<li><?php echo $this->Html->link(__('List Phd Students'), array('controller' => 'phd_students', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Phd Student'), array('controller' => 'phd_students', 'action' => 'add')); ?> </li>
	</ul>
</div>
