<div class="caes index">
	<?php echo $this->Form->create('CSM');?>
	<div class="searchFrm bgFrame1">
	<div class="col-sm-12">
		<div class="col-lg-4">
			<?php echo $this->Form->input('batch_id', array('label' => 'Batch', 'empty' => __("----- Select Batch-----"), 'class' => 'js-batch')); ?>
		</div>
		<div class="col-lg-4">
			<?php echo $this->Form->input('academic_id', array('type' => 'select', 'empty' => __("----- Select Academic-----"), 'label' => 'Academic', 'class' => 'js-academic')); ?>
		</div>
		<div class="col-lg-4">
			<div id="programs" class="program">
			<?php echo $this->Form->input('program_id', array('type' => 'select', 'empty' => __("----- Select Program-----"), 'label' => 'Program', 'class' => 'js-program')); ?>
			</div>
		</div>
	</div>
	
	<div class="col-sm-12">	
		<div class="col-lg-8">
			<div id="semester" class="semester">
				<?php echo $this->Form->input('semester_id', array('type' => 'select', 'empty' => __("----- Select Semester-----"), 'label' => 'Semester', 'class' => 'js-csm-semester'));?>
			</div>
		</div>
		<div class="col-lg-4">
			<?php echo $this->Form->button('<i class="ace-icon fa fa-check bigger-110"></i>'.__('Get'),array('type'=>'button','name'=>'button','id'=>'button','value'=>'list','class'=>'btn js-csm-list'));?>
			<?php echo $this->Form->button('<i class="ace-icon fa fa-undo bigger-110"></i>'.__('Reset'),array('type'=>'reset','name'=>'reset','value'=>'reset','class'=>'btn'));?>
		</div>
	</div>
	</div>	
	
	<div id="displayCSMData"></div>
	
<?php echo $this->Form->end(); ?>
</div>
<?php echo $this->Html->script('common'); ?>

<span class='breadcrumb1'>
<span class='navbar-brand'><small>MASTERS <i class='ace-icon fa fa-angle-double-right'></i></small></span>
<?php echo $this->Html->link("<span class='navbar-brand'><small> Course Student Mapping <i class='ace-icon fa fa-angle-double-right'></i></small></span>", array("controller"=>"CourseStudentMappings",'action' => 'mapStudents'),array('data-placement'=>'left','escape' => false)); ?>
</span>