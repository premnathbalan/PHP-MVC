<div class="courseMappings form">
<?php echo $this->Form->create('CourseMapping'); ?>
	<fieldset>
		<legend><?php echo __('Edit Course Mapping'); ?></legend>
	<?php
		echo $this->Form->input('id');
		echo $this->Form->input('program_id');
		echo $this->Form->input('course_id');
		echo $this->Form->input('course_mode_id');
		echo $this->Form->input('semester_id');
		echo $this->Form->input('created_by');
		echo $this->Form->input('modified_by');
	?>
	</fieldset>
<?php echo $this->Form->end(__('Submit')); ?>
</div>
<div class="actions">
	<h3><?php echo __('Actions'); ?></h3>
	<ul>

		<li><?php echo $this->Form->postLink(__('Delete'), array('action' => 'delete', $this->Form->value('CourseMapping.id')), array('confirm' => __('Are you sure you want to delete # %s?', $this->Form->value('CourseMapping.id')))); ?></li>
		<li><?php echo $this->Html->link(__('List Course Mappings'), array('action' => 'index')); ?></li>
		<li><?php echo $this->Html->link(__('List Programs'), array('controller' => 'programs', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Program'), array('controller' => 'programs', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Courses'), array('controller' => 'courses', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Course'), array('controller' => 'courses', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Course Modes'), array('controller' => 'course_modes', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Course Mode'), array('controller' => 'course_modes', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Semesters'), array('controller' => 'semesters', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Semester'), array('controller' => 'semesters', 'action' => 'add')); ?> </li>
	</ul>
</div>
