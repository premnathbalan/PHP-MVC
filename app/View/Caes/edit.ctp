<div class="caes form">
<?php echo $this->Form->create('Cae'); ?>
	<fieldset>
		<legend><?php echo __('Edit Cae'); ?></legend>
	<?php
		echo $this->Form->input('id');
		echo $this->Form->input('number');
		echo $this->Form->input('course_mapping_id');
		echo $this->Form->input('semester_id');
		echo $this->Form->input('month_year_id');
		echo $this->Form->input('created_by');
		echo $this->Form->input('modified_by');
	?>
	</fieldset>
<?php echo $this->Form->end(__('Submit')); ?>
</div>
<div class="actions">
	<h3><?php echo __('Actions'); ?></h3>
	<ul>

		<li><?php echo $this->Form->postLink(__('Delete'), array('action' => 'delete', $this->Form->value('Cae.id')), array('confirm' => __('Are you sure you want to delete # %s?', $this->Form->value('Cae.id')))); ?></li>
		<li><?php echo $this->Html->link(__('List Caes'), array('action' => 'index')); ?></li>
		<li><?php echo $this->Html->link(__('List Course Mappings'), array('controller' => 'course_mappings', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Course Mapping'), array('controller' => 'course_mappings', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Semesters'), array('controller' => 'semesters', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Semester'), array('controller' => 'semesters', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Month Years'), array('controller' => 'month_years', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Month Year'), array('controller' => 'month_years', 'action' => 'add')); ?> </li>
	</ul>
</div>
