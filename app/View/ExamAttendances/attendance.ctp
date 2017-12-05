<div class="exam index">
<div class="searchFrm bgFrame1">
	<?php echo $this->Form->create('ExamAttendance');?>
		<div class="searchFrm col-sm-12" >
		<div class="col-sm-9">
			<div class="col-sm-9">	
			
				<div id="monthyears" class="monthyear col-lg-6">
					<?php 
					echo $this->Form->input('ctype', array('type' => 'hidden', 'class' => 'js-ctype', 'default' => $cType));
					echo $this->Form->input('month_year_id', array('type' => 'select', 'empty' => __("----- Select Month Year-----"), 'label' => 'MonthYear', 'class' => 'js-month-year')); ?>
				</div>
				<div class="col-lg-4">
					<label class="col-sm-5 control-label">Type <span class='ash'>*</span></label>
					<?php echo $this->Form->radio('exam_type', array('R' => 'Regular', 'A' => 'Arrear'),array('legend' => false, 'class'=>'js-exam-type'));?>
				</div>
				<div class="col-lg-4">
					<?php echo $this->Form->input('batch_id', array('label' => 'Batch', 'empty' => __("----- Select Batch-----"), 'class' => 'js-batch')); ?>
				</div>
				
				<div class="col-lg-4">
					<?php echo $this->Form->input('academic_id', array('type' => 'select', 'empty' => __("----- Select Academic-----"), 'label' => 'Academic', 'class' => 'js-academic')); ?>
				</div>
			
				<div id="programs" class="program col-lg-4" >
					<?php echo $this->Form->input('program_id', array('type' => 'select', 'empty' => __("----- Select Program-----"), 'label' => 'Program', 'class' => 'js-program')); ?>
				</div>

				<div id="examdates" class="examdates col-lg-4" >
					<?php echo $this->Form->input('timetable_id', array('type' => 'select', 'empty' => __("----- Select Exam Date-----"), 'label' => 'Exam Date', 'class' => 'js-exam-date', 'onchange' => 'getExamCourses();')); ?>
				</div>
				
				<div id="courses" class="courses col-lg-4" >
					<?php echo $this->Form->input('cm_id', array('type' => 'select', 'empty' => __("----- Select course-----"), 'label' => 'Course', 'class' => 'js-exam-course')); ?>
				</div>
				
		<div class="col-lg-3">	
			<?php echo $this->Form->button('<i class="ace-icon fa fa-check bigger-110"></i>'.__('Display'),array('type'=>'button','id'=>'js-exam-att', 'name'=>'submit','value'=>'Display','class'=>'btn js-exam-att'));?>
		</div>
		
		<div class="col-lg-3">	
			<?php echo $this->Form->button('<i class="ace-icon fa fa-undo bigger-110"></i> '.'&nbsp;Reset&nbsp;',array('type'=>'reset','name'=>'submit','value'=>'submit','class'=>'btn'));?>
		</div>
		
		<div class="col-lg-3">	
			<?php echo $this->Form->button('<i class="ace-icon fa fa-check bigger-110"></i>'.__('Submit'),array('type'=>'submit','id'=>'js-exam-submit', 'name'=>'submit','value'=>'submit','class'=>'btn js-exam-att-submit'));?>
		</div>
					
			</div>	
		</div>

</div>

<div id="students_list"></div>
<?php echo $this->Form->end(); ?>
	</div>	
</div>
<?php echo $this->Html->script('common'); ?>

<span class='breadcrumb1'>
<span class='navbar-brand'><small>EXAMINATION <i class='ace-icon fa fa-angle-double-right'></i></small></span>
<?php echo $this->Html->link("<span class='navbar-brand'><small>Exam Attendance <i class='ace-icon fa fa-angle-double-right'></i></small></span>", array("controller"=>"ExamAttendances",'action' => 'index'),array('data-placement'=>'left','escape' => false)); ?>
<?php echo $this->Html->link("<span class='navbar-brand'><small>ADD <i class='ace-icon fa fa-angle-double-right'></i></small></span>", array("controller"=>"ExamAttendances",'action' => 'add'),array('data-placement'=>'left','escape' => false)); ?>
</span>