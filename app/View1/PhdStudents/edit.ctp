<div class="phdStudents form">
<?php echo $this->Form->create('PhdStudent'); ?>
	<fieldset>
		<legend><?php echo __('Edit Phd Student'); ?></legend>
	<?php
		echo $this->Form->input('id');
		echo $this->Form->input('name');
		echo $this->Form->input('birth_date');
		echo $this->Form->input('gender');
		echo $this->Form->input('father_name');
		echo $this->Form->input('address');
		echo $this->Form->input('mobile_number');
		echo $this->Form->input('email');
		echo $this->Form->input('faculty_id');
		echo $this->Form->input('thesi_id');
		echo $this->Form->input('discipline_id');
		echo $this->Form->input('supervisor_id');
		echo $this->Form->input('year_of_register');
		echo $this->Form->input('month_id');
		echo $this->Form->input('date_of_register');
		echo $this->Form->input('month_year_id');
		echo $this->Form->input('picture');
		echo $this->Form->input('created_by');
		echo $this->Form->input('modified_by');
	?>
	</fieldset>
<?php echo $this->Form->end(__('Submit')); ?>
</div>
<div class="actions">
	<h3><?php echo __('Actions'); ?></h3>
	<ul>

		<li><?php echo $this->Form->postLink(__('Delete'), array('action' => 'delete', $this->Form->value('PhdStudent.id')), array('confirm' => __('Are you sure you want to delete # %s?', $this->Form->value('PhdStudent.id')))); ?></li>
		<li><?php echo $this->Html->link(__('List Phd Students'), array('action' => 'index')); ?></li>
		<li><?php echo $this->Html->link(__('List Faculties'), array('controller' => 'faculties', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Faculty'), array('controller' => 'faculties', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Thesis'), array('controller' => 'thesis', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Thesi'), array('controller' => 'thesis', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Disciplines'), array('controller' => 'disciplines', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Discipline'), array('controller' => 'disciplines', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Supervisors'), array('controller' => 'supervisors', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Supervisor'), array('controller' => 'supervisors', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Months'), array('controller' => 'months', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Month'), array('controller' => 'months', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Month Years'), array('controller' => 'month_years', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Month Year'), array('controller' => 'month_years', 'action' => 'add')); ?> </li>
	</ul>
</div>
