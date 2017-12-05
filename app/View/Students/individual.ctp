<?php echo $this->Form->create('Student', array('inputDefaults' => array('div' => false)));?>
<!--<div class="searchFrm bgFrame1" style="width:100%;">-->
	<div class="col-sm-12">
		<div class="col-lg-2">MonthYear</div>
		<div class="col-lg-10">
			<?php echo $this->Form->input('month_year_id', array('label' => false, 'style'=>'width:100px;', 'empty' => __("----- Select Semester-----"), 'options'=>$monthYears, 'name'=>'data[monthyear]')); ?>
		</div>
	</div>
	<div class="col-sm-12">	
		<div class="col-lg-2">RegistrationNumber</div>		
		<div class="col-lg-4">
			<?php echo $this->Form->input('registration_number', array('label'=>false, 'type' => 'text','style'=>'margin-top:10px;','maxlength'=>10, 'value'=>'')); ?>
		</div>
		<div class="col-lg-6">		
			<?php echo $this->Form->button('<i class="ace-icon fa fa-check bigger-110"></i>'.__('&nbsp;Get&nbsp;&nbsp;'),array('type'=>'button','name'=>'submit','value'=>'Regular','class'=>'btn js-ind')); ?>
		</div>
	</div>	
	<div class="col-sm-12">
			<div class="col-lg-2">Name</div>
			<div class="col-lg-10" id="stuName">blah</div>
	</div>
	<div class="col-sm-12">
			<div class="col-lg-2">Semester</div>
			<div class="col-lg-10" id="stuSemester">blah</div>
	</div>
	<div class="col-sm-12">
			<div class="col-lg-2">Course</div>
			<div class="col-lg-10" id="courses">blah</div>
	</div>
	<div class="col-sm-12" id="course_details">
			<div class="col-lg-2">Course</div>
	</div>
<!--</div>-->
<div id="marks" class="program col-lg-12"></div>
<?php echo $this->Form->end(); ?>

<?php echo $this->Html->script('common'); ?>
<?php echo $this->Html->script('common-front'); ?>

<script>leftMenuSelection('Marks/individualUser');</script>
<span class='breadcrumb1'>
<span class='navbar-brand'><small>MARKS <i class='ace-icon fa fa-angle-double-right'></i></small></span>
<?php echo $this->Html->link("<span class='navbar-brand'><small> Moderation <i class='ace-icon fa fa-angle-double-right'></i></small></span>", array("controller"=>"Students",'action' => 'individualUser'),array('data-placement'=>'left','escape' => false)); ?>
</span>