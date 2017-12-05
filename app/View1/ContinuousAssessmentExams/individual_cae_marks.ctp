<?php //echo $this->Html->script('common'); ?>
<div class="caes index">
	<h2><?php echo __('Manage Caes'); ?></h2>
<div class="searchFrm">
<?php echo $this->Form->create('Student');?>
	<?php echo $this->Form->input('batch_id', array('label' => false, 'empty' => __("----- Select Batch-----"), 'class' => 'js-batch')); ?>
	<?php echo $this->Form->input('academic_id', array('type' => 'select', 'empty' => __("----- Select Academic-----"), 'label' => false, 'class' => 'js-academic')); ?>
	<div id="programs" class="program"><?php echo $this->Form->input('program_id', array('type' => 'select', 'empty' => __("----- Select Program-----"), 'label' => false, 'class' => 'js-program')); ?></div>
	<div id="semesters" class="semester"><?php echo $this->Form->input('semester_id', array('type' => 'select', 'empty' => __("----- Select Semester-----"), 'label' => false, 'class' => 'js-semester')); ?></div>
	<div id="courses" class="course"><?php echo $this->Form->input('course_mapping_id', array('type' => 'select', 'empty' => __("----- Select Course-----"), 'label' => false, 'class' => 'js-course')); ?></div>
	<div id="noOfCAEs"></div>
	<?php //echo $this->Form->button('<i class="ace-icon fa fa-check bigger-110"></i>'.__('Submit'),array('type'=>'submit','name'=>'submit','value'=>'submit','class'=>'btn'));?>
	<?php echo $this->Form->button('<i class="ace-icon fa fa-check bigger-110"></i>'.__('Add'),array('type'=>'button','name'=>'submit','value'=>'add','class'=>'btn', 'onclick' => 'addInternal();'));?>
	<?php echo $this->Form->button('<i class="ace-icon fa fa-undo bigger-110"></i>'.__('Reset'),array('type'=>'reset','name'=>'reset','value'=>'reset','class'=>'btn'));?>
<?php echo $this->Form->end(); ?>
</div>	
</div>