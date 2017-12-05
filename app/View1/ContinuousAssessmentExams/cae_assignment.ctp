<div class="caes index">
	<?php echo $this->Form->create('Cae');?>
	<div class="searchFrm bgFrame1">
	<div class="col-sm-12">
		<div class="col-lg-4">
		<?php //echo $this->Form->input('course_type_id', array('type'=>'hidden', 'default'=>$course_type_id, 'label' => false, 'class' => 'js-txt')); 
		echo $this->Form->input('action', array('type'=>'hidden', 'default'=>$action, 'label' => false, 'class' => 'js-txt'));
		?>
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
		<div class="col-lg-4">
			<div id="semesters" class="semester"><?php echo $this->Form->input('semester_id', array('type' => 'select', 'empty' => __("----- Select Semester-----"), 'label' => 'Semester', 'class' => 'js-semester')); ?></div>
		</div>
	
		<div id="courses" class="course">		
			<div class="col-lg-4">
			<?php echo $this->Form->input('course_mapping_id', array('type' => 'select', 'empty' => __("----- Select Course-----"), 'label' => 'Course', 'class' => 'js-course')); ?>
			</div>
		</div>
		<div class="col-lg-4 monthyear" id="monthyears">			
			<?php echo $this->Form->input('month_year_id', array('options' => $monthyears, 'type' => 'select', 'empty' => __("----- Select Month Year-----"), 'label' => 'MonthYear', 'class' => 'js-month-year', 'default' => $monthyears));?>			
		</div>
	</div>
	<div class="col-sm-12">	
		<div class="col-lg-8"></div>
		<div class="col-lg-4">
		<?php echo $this->Form->button('<i class="ace-icon fa fa-check bigger-110"></i>'.__('Add'),array('type'=>'submit','name'=>'submit','id'=>'submit','value'=>'add','class'=>'btn caeAddBtn', 'onclick' => 'return checkMarks();'/*, 'onclick' => 'addInternal();'*/));?>
		<?php echo $this->Form->button('<i class="ace-icon fa fa-undo bigger-110"></i>'.__('Reset'),array('type'=>'reset','name'=>'reset','value'=>'reset','class'=>'btn'));?>
		</div>
	</div>
	</div>	
	
	<div id="displayOptions"></div>
	<div id="noOfCAEs"></div>
<script>leftMenuSelection('ContinuousAssessmentExams/theory');</script>
<?php echo $this->Form->end(); ?>
</div>

<span class='breadcrumb1'>
<span class='navbar-brand'><small>MARKS <i class='ace-icon fa fa-angle-double-right'></i></small></span>
<span class='navbar-brand'><small>C.A.E <i class='ace-icon fa fa-angle-double-right'></i></small></span>
<?php echo $this->Html->link("<span class='navbar-brand'><small> Theory <i class='ace-icon fa fa-angle-double-right'></i></small></span>", array("controller"=>"ContinuousAssessmentExams",'action' => 'index'),array('data-placement'=>'left','escape' => false)); ?>
<?php echo $this->Html->link("<span class='navbar-brand'><small> ASSESSMENT <i class='ace-icon fa fa-angle-double-right'></i></small></span>", array("controller"=>"ContinuousAssessmentExams",'action' => 'caeAssignment'),array('data-placement'=>'left','escape' => false)); ?>
</span>