<?php
//echo $batchId." ".$academicId." ".$programId." ".$monthYearId;
//pr($month_year);
/*echo "Batch : ".$this->Html->getBatch($batchId);
echo "Academic : ".$this->Html->getAcademic($academicId);
echo "Program : ".$this->Html->getProgram($programId);
echo "MonthYear : ".$this->Html->getMonthYearFromId($monthYearId);*/

echo $this->Form->create('AddCae');
//echo $this->Form->input('course_mapping_id', array('type' => 'select', 'options' => $courses, 'empty' => __("----- Select Course-----"), 'onchange' => 'getMarks(this.value);', 'class' => 'js-add-cae-course', 'label' => "Course <span class='ash'>*</span>"));
?>
<div id='marks'></div>

<?php //echo $this->Html->script('common'); ?>
<div class="caes index">
	<h2><?php echo __('CAE Assignment'); ?></h2>
	
	<?php echo $this->Form->create('Cae');?>
	<div class="searchFrm col-sm-12">
		<div class="col-lg-4">
			<?php echo $this->Form->input('batch_id', array('label' => false, 'empty' => __("----- Select Batch-----"), 'class' => 'js-cae-batch')); ?>
		</div>
		<div class="col-lg-4">
			<div id="academics_cae">
				<?php echo $this->Form->input('academic_id', array('type' => 'select', 'empty' => __("----- Select Academic-----"), 'label' => 'Academic', 'class' => 'js-academic')); ?>
			</div>
		</div>
		<div class="col-lg-4">
			<div id="programs_cae" class="program">
			<?php echo $this->Form->input('program_id', array('type' => 'select', 'empty' => __("----- Select Program-----"), 'label' => false, 'class' => 'js-program')); ?>
			</div>
		</div>
		<div class="col-lg-4">
			<div id="semesters_cae" class="semester"><?php echo $this->Form->input('semester_id', array('type' => 'select', 'empty' => __("----- Select Semester-----"), 'label' => false, 'class' => 'js-semester')); ?></div>
		</div>
		
		<div id="courses" class="course">			
			<div class="col-lg-4">
			<?php echo $this->Form->input('course_mapping_id', array('type' => 'select', 'empty' => __("----- Select Course-----"), 'label' => false, 'class' => 'js-course')); ?>
			</div>
			<div class="col-lg-4">
			<?php echo $this->Form->input('month_year_id', array('type' => 'select', 'empty' => __("----- Select Month Year-----"), 'label' => false, 'class' => 'js-month-year')); ?>
			</div>
		</div>
		<?php //echo $this->Form->button('<i class="ace-icon fa fa-check bigger-110"></i>'.__('Submit'),array('type'=>'submit','name'=>'submit','value'=>'submit','class'=>'btn js-cae-list'));?>
		<?php echo $this->Form->button('<i class="ace-icon fa fa-check bigger-110"></i>'.__('Add'),array('type'=>'button','name'=>'submit','value'=>'add','class'=>'btn', 'onclick' => 'addInternal();'));?>
		<?php echo $this->Form->button('<i class="ace-icon fa fa-undo bigger-110"></i>'.__('Reset'),array('type'=>'reset','name'=>'reset','value'=>'reset','class'=>'btn'));?>
		</div>
	<?php echo $this->Form->end(); ?>

	<div id="noOfCAEs"></div>
	
</div>
<?php echo $this->Html->script('common'); ?>