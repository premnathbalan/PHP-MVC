<div class="continuousAssessmentExams form">
<?php echo $this->Form->create('ContinuousAssessmentExam'); ?>
	<fieldset>
		<legend><?php echo __('Add Continuous Assessment Exam'); ?></legend>
	<?php
		echo $this->Form->input('course_id');
		echo $this->Form->input('student_id');
		echo $this->Form->input('month');
		echo $this->Form->input('year');
		echo $this->Form->input('assessment_number');
		echo $this->Form->input('absent');
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

		<li><?php echo $this->Html->link(__('List Continuous Assessment Exams'), array('action' => 'index')); ?></li>
		<li><?php echo $this->Html->link(__('List Courses'), array('controller' => 'courses', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Course'), array('controller' => 'courses', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Students'), array('controller' => 'students', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Student'), array('controller' => 'students', 'action' => 'add')); ?> </li>
	</ul>
</div>
