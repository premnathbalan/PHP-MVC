<div class="courseTypes form deptFrm">

	<legend><?php echo __('Edit Course Type'); ?></legend>
	<div><?php echo $this->Session->flash();?></div>
	<?php echo $this->Form->create('CourseType', array('class'=>'js-form','success_url'=>$this->Html->url(array('controller'=>'CourseTypes','action'=>'index')))); ?>
	<?php
		echo $this->Form->input('id');
		echo $this->Form->input('course_type', array("label"=>"Courses Type <span class='ash'>*</span>"));
	?>	
<?php echo $this->Form->end(__('Submit')); ?>
</div>