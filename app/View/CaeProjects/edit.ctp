<div class="caeProjects form">
<?php echo $this->Form->create('CaeProject'); ?>
	<fieldset>
		<legend><?php echo __('Edit Cae Project'); ?></legend>
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

		<li><?php echo $this->Form->postLink(__('Delete'), array('action' => 'delete', $this->Form->value('CaeProject.id')), array('confirm' => __('Are you sure you want to delete # %s?', $this->Form->value('CaeProject.id')))); ?></li>
		<li><?php echo $this->Html->link(__('List Cae Projects'), array('action' => 'index')); ?></li>
		<li><?php echo $this->Html->link(__('List Course Mappings'), array('controller' => 'course_mappings', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Course Mapping'), array('controller' => 'course_mappings', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Semesters'), array('controller' => 'semesters', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Semester'), array('controller' => 'semesters', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Lecturers'), array('controller' => 'lecturers', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Lecturer'), array('controller' => 'lecturers', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Users'), array('controller' => 'users', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New User'), array('controller' => 'users', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Modified Users'), array('controller' => 'modified_users', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Modified User'), array('controller' => 'modified_users', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Project Reviews'), array('controller' => 'project_reviews', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Project Review'), array('controller' => 'project_reviews', 'action' => 'add')); ?> </li>
	</ul>
</div>
