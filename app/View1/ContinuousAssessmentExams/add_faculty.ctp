<?php echo $this->Html->script('common'); ?>
<?php echo $this->Html->script('jquery-2.1.4.min'); ?>
<?php echo $this->Html->script('bootstrap'); ?>
<?php echo $this->Html->css('bootstrap'); ?>
<div class="searchFrm">
<?php echo $this->Form->create('Add');?>
<div class="searchFrm col-sm-12">
	<div class="col-lg-4">
		<?php echo $this->Form->input('lecturer_id', array('type' => 'select', 'options'=> $faculty, 'empty' => __("----- Select Faculty-----"), 'label' => 'Faculty', 'class' => 'js-faculty')); ?>
	</div>
	<div class="col-lg-4">
		<?php echo $this->Form->input('academic_id', array('type' => 'select', 'empty' => __("----- Select Academic-----"), 'label' => 'Academic', 'class' => 'js-academic-popup')); ?>
	</div>
	<div class="col-lg-4">
		<div id="program_faculty" class="program col-lg-4">
			<?php echo $this->Form->input('program_id', array('type' => 'select', 'empty' => __("----- Select Program-----"), 'label' => 'Program', 'class' => 'js-program')); ?>
		</div>
	</div>
	<div id="courses" class="col-lg-4">
		<?php echo $this->Form->input('course_mapping_id', array('type' => 'select', 'label' => 'Courses', 'class' => 'js-courses')); ?>
	</div>
	<div class="col-lg-4">
		<input type="button" id="btnCourses" value="Get Courses" />
	</div>
	<div class="col-lg-4">
		<?php echo $this->Form->button('<i class="ace-icon fa fa-check bigger-110"></i>'.__('Submit'),array('type'=>'submit','name'=>'submit','value'=>'submit','class'=>'btn'));?>
	</div>
</div>
<?php echo $this->Form->end(); ?>