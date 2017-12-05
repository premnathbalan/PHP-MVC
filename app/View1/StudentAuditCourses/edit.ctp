<div id="js-load-forms"></div>
<div class="studentAuditCourses form">
<?php echo $this->Form->create('StudentAuditCourse'); ?>
	<fieldset>
		<legend><?php echo __('Edit Student Audit Course'); ?></legend>
	<?php
		echo $this->Form->input('id');
		echo $this->Form->input('student_id');
		echo $this->Form->input('marks');
		//echo $this->Form->input('audit_course_id');
		//echo $this->Form->input('indicator');
		//echo $this->Form->input('created_by');
		//echo $this->Form->input('modified_by');
	?>
	</fieldset>
<?php echo $this->Form->end(__('Submit')); ?>
</div>