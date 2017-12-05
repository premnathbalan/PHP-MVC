<div class="nonCreditCourses form deptFrm">
	<?php echo $this->Session->flash();?>
	<legend><?php echo __('Non Credit Course Add'); ?></legend>
	<?php
		echo $this->Form->create('NonCreditCourse', array('class'=>'js-form','success_url'=>$this->Html->url(array('controller'=>'NonCreditCourses','action'=>'index'))));
				
		echo $this->Form->input('non_credit_course_name',array("label"=>"Non Credit Course Name <span class='ash'>*</span>"));	
		echo $this->Form->end(__('Submit')); ?>
</div>