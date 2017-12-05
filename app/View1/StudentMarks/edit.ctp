<div class="studentMarks form">
<?php echo $this->Form->create('StudentMark'); ?>
	<fieldset>
		<legend><?php echo __('Edit Student Mark'); ?></legend>
	<?php
		echo $this->Form->input('id');
		echo $this->Form->input('month_year_id');
		echo $this->Form->input('student_id');
		echo $this->Form->input('course_mapping_id');
		echo $this->Form->input('marks');
		echo $this->Form->input('status');
		echo $this->Form->input('created_by');
		echo $this->Form->input('modified_by');
	?>
	</fieldset>
<?php echo $this->Form->end(__('Submit')); ?>
</div>
<div class="actions">
	<h3><?php echo __('Actions'); ?></h3>
	<ul>

		<li><?php echo $this->Form->postLink(__('Delete'), array('action' => 'delete', $this->Form->value('StudentMark.id')), array('confirm' => __('Are you sure you want to delete # %s?', $this->Form->value('StudentMark.id')))); ?></li>
		<li><?php echo $this->Html->link(__('List Student Marks'), array('action' => 'index')); ?></li>
		<li><?php echo $this->Html->link(__('List Month Years'), array('controller' => 'month_years', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Month Year'), array('controller' => 'month_years', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Students'), array('controller' => 'students', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Student'), array('controller' => 'students', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Course Mappings'), array('controller' => 'course_mappings', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Course Mapping'), array('controller' => 'course_mappings', 'action' => 'add')); ?> </li>
	</ul>
</div>
