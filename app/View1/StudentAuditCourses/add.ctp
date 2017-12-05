<div class="studentAuditCourses form">
<?php echo $this->Form->create('StudentAuditCourse'); ?>
	<fieldset>
		<legend><?php echo __('Add Student Audit Course'); ?></legend>
	<?php
		echo $this->Form->input('student_id');
		echo $this->Form->input('audit_course_id');
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

		<li><?php echo $this->Html->link(__('List Student Audit Courses'), array('action' => 'index')); ?></li>
		<li><?php echo $this->Html->link(__('List Students'), array('controller' => 'students', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Student'), array('controller' => 'students', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Audit Courses'), array('controller' => 'audit_courses', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Audit Course'), array('controller' => 'audit_courses', 'action' => 'add')); ?> </li>
	</ul>
</div>
