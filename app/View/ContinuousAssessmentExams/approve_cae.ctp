<?php //echo $this->Html->script('common'); ?>
<div class="caes index">
	<h2><?php echo __('Approve Cae'); ?></h2>
<div class="searchFrm">
<?php echo $this->Form->create('Student');?>
	<?php echo $this->Form->input('batch_id', array('label' => false, 'empty' => __("----- Select Batch-----"), 'class' => 'js-batch')); ?>
	<?php echo $this->Form->input('academic_id', array('type' => 'select', 'empty' => __("----- Select Academic-----"), 'label' => false, 'class' => 'js-academic')); ?>
	<div id="programs" class="program"><?php echo $this->Form->input('program_id', array('type' => 'select', 'empty' => __("----- Select Program-----"), 'label' => false, 'class' => 'js-program')); ?></div>
	<div id="monthyears" class="monthyear"><?php echo $this->Form->input('month_year_id', array('type' => 'select', 'empty' => __("----- Select Month Year-----"), 'label' => false, 'class' => 'js-month-year')); ?></div>
	<!--<div id="semesters" class="semester"><?php echo $this->Form->input('semester_id', array('type' => 'select', 'empty' => __("----- Select Semester-----"), 'label' => false, 'class' => 'js-semester-cae')); ?></div>-->
	<?php echo $this->Form->button('<i class="ace-icon fa fa-undo bigger-110"></i>'.__('Reset'),array('type'=>'reset','name'=>'reset','value'=>'reset','class'=>'btn'));?>
	</br>
	<div id="courses" class="course"></div>
	<!--<div id="noOfCAEs"><?php echo $this->Form->input('cae_id', array('type' => 'select', 'empty' => __("----- Select CAE-----"), 'label' => false, 'class' => 'cae')); ?></div>-->
	<?php //echo $this->Form->button('<i class="ace-icon fa fa-check bigger-110"></i>'.__('Submit'),array('type'=>'submit','name'=>'submit','value'=>'submit','class'=>'btn'));?>
	<?php //echo $this->Form->button('<i class="ace-icon fa fa-check bigger-110"></i>'.__('Get Students'),array('type'=>'button','name'=>'submit','value'=>'add','class'=>'btn'));?>
	
<?php echo $this->Form->end(); ?>
</div>	
</div>