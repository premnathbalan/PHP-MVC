<div class="projectVivas form">
<?php echo $this->Form->create('ProjectViva'); ?>
	<fieldset>
		<legend><?php echo __('Edit Project Viva'); ?></legend>
	<?php
		echo $this->Form->input('id');
		echo $this->Form->input('ese_project_id');
		echo $this->Form->input('student_id');
		echo $this->Form->input('month_year_id');
		echo $this->Form->input('marks');
		echo $this->Form->input('created_by');
		echo $this->Form->input('modified_by');
	?>
	</fieldset>
<?php echo $this->Form->end(__('Submit')); ?>
</div>
<div class="actions">
	<h3><?php echo __('Actions'); ?></h3>
	<ul>

		<li><?php echo $this->Form->postLink(__('Delete'), array('action' => 'delete', $this->Form->value('ProjectViva.id')), array('confirm' => __('Are you sure you want to delete # %s?', $this->Form->value('ProjectViva.id')))); ?></li>
		<li><?php echo $this->Html->link(__('List Project Vivas'), array('action' => 'index')); ?></li>
		<li><?php echo $this->Html->link(__('List Ese Projects'), array('controller' => 'ese_projects', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Ese Project'), array('controller' => 'ese_projects', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Students'), array('controller' => 'students', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Student'), array('controller' => 'students', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Month Years'), array('controller' => 'month_years', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Month Year'), array('controller' => 'month_years', 'action' => 'add')); ?> </li>
	</ul>
</div>
