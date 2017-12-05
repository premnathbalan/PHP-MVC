<div class="studentAuthorizedBreaks form">
<?php echo $this->Form->create('StudentAuthorizedBreak'); ?>
	<fieldset>
		<legend><?php echo __('Edit Student Authorized Break'); ?></legend>
	<?php
		echo $this->Form->input('id');
		echo $this->Form->input('student_id');
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

		<li><?php echo $this->Form->postLink(__('Delete'), array('action' => 'delete', $this->Form->value('StudentAuthorizedBreak.id')), array('confirm' => __('Are you sure you want to delete # %s?', $this->Form->value('StudentAuthorizedBreak.id')))); ?></li>
		<li><?php echo $this->Html->link(__('List Student Authorized Breaks'), array('action' => 'index')); ?></li>
		<li><?php echo $this->Html->link(__('List Students'), array('controller' => 'students', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Student'), array('controller' => 'students', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Semesters'), array('controller' => 'semesters', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Semester'), array('controller' => 'semesters', 'action' => 'add')); ?> </li>
	</ul>
</div>
