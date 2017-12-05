<div id="js-load-forms"></div>

<div class="caes index">
<div class="searchFrm">
<?php echo $this->Form->create('Faculty');?>
<div class="searchFrm col-sm-12">
	<div class="col-lg-4">
		<div id="academics">
		<?php echo $this->Form->input('academic_id', array('type' => 'select', 'empty' => __("----- Select Academic-----"), 'label' => 'Academic', 'class' => 'js-academic')); ?>
		</div>
	</div>
	<div id="programs" class="program col-lg-4" >
		<?php echo $this->Form->input('program_id', array('type' => 'select', 'empty' => __("----- Select Program-----"), 'label' => 'Program', 'class' => 'js-program')); ?>
	</div>
	
	<div class="col-lg-4">
		<div id="faculty">
		<?php echo $this->Form->input('faculty_id', array('type' => 'select', 'options'=> $faculty, 'empty' => __("----- Select Faculty-----"), 'label' => 'Faculty', 'class' => 'js-faculty')); ?>
		</div>
	</div>
	<div class="col-lg-4">	
	<?php
		echo $this->Form->button('<i class="ace-icon fa fa-check bigger-110"></i>'.__('Search'),array('type'=>'button','name'=>'submit','value'=>'search','class'=>'btn js-faculty-search'));
		echo "&nbsp;".$this->Html->link('Add', array("controller"=>"ContinuousAssessmentExams",'action'=>'addFaculty'), array('type'=>'button','name'=>'submit','value'=>'add','class'=>'js-popup btn js-faculty-add'));
	?>
	</div>
</div>
</div>
<div id="facultyDisplay" class="facultyDisplay"></div>
<?php echo $this->Form->end(); ?>

</div>

<span class='breadcrumb1'>
<span class='navbar-brand'><small>C.A.E <i class='ace-icon fa fa-angle-double-right'></i></small></span>
<?php echo $this->Html->link("<span class='navbar-brand'><small>Assign Faculty <i class='ace-icon fa fa-angle-double-right'></i></small></span>", array("controller"=>"ContinuousAssessmentExams",'action' => 'facultyAssignment'),array('data-placement'=>'left','escape' => false)); ?>
</span>

<?php echo $this->Html->script('common'); ?>
