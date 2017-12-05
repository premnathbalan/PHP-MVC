<div id="js-load-forms"></div>
<div class="page-header">
<h1>
		Edit Faculty
		<small>
			<i class="ace-icon fa fa-angle-double-right"></i>
		</small>
	</h1>	
</div>
<div class="caes index">
<div class="searchFrm">
<?php echo $this->Form->create('Edit', array('onSubmit' => 'return valEditFaculty();'));?>
<?php echo $this->Form->input('id', array('type' => 'hidden', 'label' => "CourseLecturerId <span class='ash'>*</span>", 'class' => 'js-mod-from', 'value' => $course_faculty_id)); ?>
<?php echo $this->Form->input('program_id', array('type' => 'hidden', 'label' => "ProgramId <span class='ash'>*</span>", 'class' => 'js-mod-from', 'value' => $program_id)); ?>
<?php echo $this->Form->input('cm_id', array('type' => 'hidden', 'label' => "CmId <span class='ash'>*</span>", 'class' => 'js-mod-from', 'value' => $cmId)); ?>

<div class="searchFrm col-sm-12">
	<div class="program col-lg-4" >
		<?php echo $this->Html->getProgram($program_id); ?> 
	</div>
	
	<div class="program col-lg-4" >
		<?php echo $this->Html->getCourseNameFromCmId($cmId); ?> 
	</div>
	
	<div class="col-lg-4">
		<div id="faculty">
		<?php echo $this->Form->input('faculty_id', array('type' => 'select', 'options'=> $facultyArray, 'empty' => __("----- Select Faculty-----"), 'label' => 'Faculty')); ?>
		</div>
	</div>
	<div class="col-lg-4">	
	<?php
		echo $this->Form->button('<i class="ace-icon fa fa-check bigger-110"></i>'.__('Edit'),array('type'=>'submit','name'=>'submit','value'=>'search','class'=>'btn js-edit-faculty'));
	?>
	</div>
</div>
</div>
<?php echo $this->Form->end(); ?>

</div>

<?php echo $this->Html->script('common'); ?>