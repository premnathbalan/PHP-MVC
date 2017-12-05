<div class="endSemesterExams form">
<?php echo $this->Form->create('EndSemesterExam'); ?>
	<fieldset>
		<legend><?php echo __('Add End Semester Exam'); ?></legend>
	<?php
		echo $this->Form->input('course_mapping_id');
		echo $this->Form->input('student_id');
		echo $this->Form->input('month_year_id');
		echo $this->Form->input('actual_marks');
		echo $this->Form->input('max_convert_to');
		echo $this->Form->input('converted_marks');
		echo $this->Form->input('moderation_marks');
		echo $this->Form->input('moderation_operator');
		echo $this->Form->input('revaluation');
		echo $this->Form->input('revaluation_marks');
		echo $this->Form->input('final_marks');
		echo $this->Form->input('created_by');
		echo $this->Form->input('modified_by');
	?>
	</fieldset>
<?php echo $this->Form->end(__('Submit')); ?>
</div>
<div class="actions">
	<h3><?php echo __('Actions'); ?></h3>
	<ul>

		<li><?php echo $this->Html->link(__('List End Semester Exams'), array('action' => 'index')); ?></li>
		<li><?php echo $this->Html->link(__('List Course Mappings'), array('controller' => 'course_mappings', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Course Mapping'), array('controller' => 'course_mappings', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Students'), array('controller' => 'students', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Student'), array('controller' => 'students', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Month Years'), array('controller' => 'month_years', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Month Year'), array('controller' => 'month_years', 'action' => 'add')); ?> </li>
	</ul>
</div>
