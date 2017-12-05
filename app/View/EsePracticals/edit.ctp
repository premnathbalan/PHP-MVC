<div class="esePracticals form">
<?php echo $this->Form->create('EsePractical'); ?>
	<fieldset>
		<legend><?php echo __('Edit Ese Practical'); ?></legend>
	<?php
		echo $this->Form->input('id');
		echo $this->Form->input('course_mapping_id');
		echo $this->Form->input('assessment_type');
		echo $this->Form->input('semester_id');
		echo $this->Form->input('marks');
		echo $this->Form->input('marks_status');
		echo $this->Form->input('add_status');
		echo $this->Form->input('approval_status');
		echo $this->Form->input('lecturer_id');
		echo $this->Form->input('indicator');
		echo $this->Form->input('created_by');
		echo $this->Form->input('modified_by');
	?>
	</fieldset>
<?php echo $this->Form->end(__('Submit')); ?>
</div>
<div class="actions">
	<h3><?php echo __('Actions'); ?></h3>
	<ul>

		<li><?php echo $this->Form->postLink(__('Delete'), array('action' => 'delete', $this->Form->value('EsePractical.id')), array('confirm' => __('Are you sure you want to delete # %s?', $this->Form->value('EsePractical.id')))); ?></li>
		<li><?php echo $this->Html->link(__('List Ese Practicals'), array('action' => 'index')); ?></li>
		<li><?php echo $this->Html->link(__('List Course Mappings'), array('controller' => 'course_mappings', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Course Mapping'), array('controller' => 'course_mappings', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Semesters'), array('controller' => 'semesters', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Semester'), array('controller' => 'semesters', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Lecturers'), array('controller' => 'lecturers', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Lecturer'), array('controller' => 'lecturers', 'action' => 'add')); ?> </li>
	</ul>
</div>
