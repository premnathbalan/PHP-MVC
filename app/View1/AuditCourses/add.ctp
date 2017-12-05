<div class="auditCourses form deptFrm">
<?php echo $this->Form->create('AuditCourse'); ?>
	<fieldset>
		<legend><?php echo __('Add Audit Course'); ?></legend>
	<?php
		echo $this->Form->input("course_name", array("label"=>"Course Name <span class='ash'>*</span>", 'placeholder' => 'Course Name'));
		echo $this->Form->input("course_code", array("label"=>"Course Code <span class='ash'>*</span>", 'placeholder' => 'Course Code'));
		echo $this->Form->input("common_code", array("label"=>"Common Code <span class='ash'>*</span>", 'placeholder' => 'Common Code'));
		echo $this->Form->input('course_max_marks', array("label"=>"Course Max Marks", 'placeholder' => 'Course Max Marks'));
		echo $this->Form->input('total_min_pass_mark', array("label"=>"Total Min Pass Mark", 'placeholder' => 'Total Min Pass Mark'));
		
	?>
	</fieldset>
<?php echo $this->Form->end(__('Submit')); ?>
</div>