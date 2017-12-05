<div class="students form">
	<div style="float:right;">
		<div class="col-sm-12">
		<?php echo $this->Html->link('<i class="ace-icon fa fa-download"></i>'. 'Template', array("controller"=>"Students",'action'=>'student_upload_template'),array('class' =>"btn",'escape' => false)); ?>
		</div>
	</div>
	
	<?php echo $this->Form->create('Student', array('type' => 'file'));?>
	
	<div class="bgFrame1">
	    <div class=" col-sm-6">
			<?php echo $this->Form->input('batch_id', array('type' => 'select', 'options' => $batches, 'empty' => __("-- Batch --"), 'label' => "Batch <span class='ash'>*</span>"));?>
		</div>       
        <div class=" col-sm-6">
			<?php echo $this->Form->input('academic_id', array('type' => 'select', 'options' => $academics, 'empty' => __("-- Program --"), 'class' => 'student-academic','style'=>'width:180px;', 'label' => "Program<span class='ash'>*</span>"));?>
		</div>       
        <div class=" col-sm-6">
			<div id="programs"  class="program">
			<?php echo $this->Form->input('program_id', array('type' => 'select', 'empty' => __("-- Specialisation --"), 'label' => "Specialisation <span class='ash'>*</span>",'style'=>'width:180px;', 'class' => 'js-programs')); ?>
			</div>
    	</div>

	   	<div class=" col-sm-6">
	   		<?php echo $this->Form->input('csv', array('type' => 'file','required'=>'required', 'label' => "Upload File <span class='ash'>*</span>")); ?>
	   	</div>
	   	<div class=" col-sm-6">
	   		<?php echo $this->Form->end(__('Submit')); ?>
	   	</div>
   </div>
   
   <div>
		<div>Upload Notes</div>
		<div>1. dd - Date of Bith date</div>
		<div>2. mm - Date of Bith month</div>
		<div>3. yyyy - Data of Birth year</div>
		<div>4. Admission date should be dd/mm/yyyy formate. example 22/07/2016</div>
		<div>5. Gender - Male type M & Female type F</div>
		
   </div>
<script>
$('.input label').addClass('col-sm-5 control-label no-padding-right');
$('.radio label').removeClass('col-sm-5 control-label no-padding-right');
leftMenuSelection('Students/studentUpload');
</script>

<span class='breadcrumb1'>
<span class='navbar-brand'><small>STUDENTS <i class='ace-icon fa fa-angle-double-right'></i></small></span>
<?php echo $this->Html->link("<span class='navbar-brand'><small> LIST <i class='ace-icon fa fa-angle-double-right'></i></small></span>", array("controller"=>"Students",'action' => 'student_search'),array('data-placement'=>'left','escape' => false)); ?>
<?php echo $this->Html->link("<span class='navbar-brand'><small> STUDENT UPLOAD <i class='ace-icon fa fa-angle-double-right'></i></small></span>", array("controller"=>"Students",'action' => 'studentUpload'),array('data-placement'=>'left','escape' => false)); ?>
</span>
