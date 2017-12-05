<div class="phdCourseStudentMappings form">
<?php echo $this->Form->create('PhdCourseStudentMapping'); ?>
	<fieldset>
		<legend><?php echo __('Edit Phd Course Student Mapping'); ?></legend>
	<?php
		echo $this->Form->input('id');
		echo $this->Form->input('phd_student_id');
		echo $this->Form->input('phd_course_id');
		echo $this->Form->input('marks');
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

		<li><?php echo $this->Form->postLink(__('Delete'), array('action' => 'delete', $this->Form->value('PhdCourseStudentMapping.id')), array('confirm' => __('Are you sure you want to delete # %s?', $this->Form->value('PhdCourseStudentMapping.id')))); ?></li>
		<li><?php echo $this->Html->link(__('List Phd Course Student Mappings'), array('action' => 'index')); ?></li>
		<li><?php echo $this->Html->link(__('List Phd Students'), array('controller' => 'phd_students', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Phd Student'), array('controller' => 'phd_students', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Phd Courses'), array('controller' => 'phd_courses', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Phd Course'), array('controller' => 'phd_courses', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Users'), array('controller' => 'users', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New User'), array('controller' => 'users', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Modified Users'), array('controller' => 'modified_users', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Modified User'), array('controller' => 'modified_users', 'action' => 'add')); ?> </li>
	</ul>
</div>
