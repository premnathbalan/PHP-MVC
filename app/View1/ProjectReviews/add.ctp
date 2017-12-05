<div class="projectReviews form">
<?php echo $this->Form->create('ProjectReview'); ?>
	<fieldset>
		<legend><?php echo __('Add Project Review'); ?></legend>
	<?php
		echo $this->Form->input('cae_project_id');
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

		<li><?php echo $this->Html->link(__('List Project Reviews'), array('action' => 'index')); ?></li>
		<li><?php echo $this->Html->link(__('List Cae Projects'), array('controller' => 'cae_projects', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Cae Project'), array('controller' => 'cae_projects', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Students'), array('controller' => 'students', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Student'), array('controller' => 'students', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Month Years'), array('controller' => 'month_years', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Month Year'), array('controller' => 'month_years', 'action' => 'add')); ?> </li>
	</ul>
</div>
