<div class="courses form deptFrm">
<?php echo $this->Form->create('PhdCourse', array('class'=>'js-form','success_url'=>$this->Html->url(array('controller'=>'PhdCourses','action'=>'index')))); ?>
	<legend><?php echo __('Add Course'); ?></legend>
	<?php
		echo $this->Form->input("course_name", array("label"=>"Course Name <span class='ash'>*</span>", 'placeholder' => 'Course Name'));
		echo $this->Form->input("course_code", array("label"=>"Course Code <span class='ash'>*</span>", 'placeholder' => 'Course Code'));
		echo $this->Form->input('course_max_marks', array("label"=>"Course Max Marks <span class='ash'>*</span>", 'placeholder' => 'Course Max Marks'));
		echo $this->Form->input('total_min_pass_percent', array("label"=>"Total Min Pass Percentage (%) <span class='ash'>*</span>", 'placeholder' => 'Total Min Pass Mark'));
	?>	
<?php echo $this->Form->end(__('Submit')); ?>
</div>