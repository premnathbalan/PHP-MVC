<div class="students form">
	
	<?php echo $this->Form->create('ProfessionalTraining', array('type' => 'file')); ?>
	
	<div class="bgFrame1">
	     <div class=" col-sm-12">
	    <div class=" col-sm-4">
			<?php echo $this->Form->input('batch_id', array('label' => "Batch <span class='ash'>*</span>",'type' => 'select', 'options' => $batches, 'empty' => __("-- Batch --"), 'class'=>'js-batch', 'style'=>'width:150px;' ));?>
		</div>       
        <div class=" col-sm-4">
			<?php echo $this->Form->input('academic_id', array('type' => 'select', 'options' => $academics, 'empty' => __("-- Program --"), 'class' => 'student-academic','style'=>'width:180px;', 'label' => "Program<span class='ash'>*</span>"));?>
		</div>       
        <div class=" col-sm-4">
			<div id="programs"  class="program">
			<?php echo $this->Form->input('program_id', array('type' => 'select', 'empty' => __("-- Specialisation --"), 'label' => "Specialisation <span class='ash'>*</span>",'style'=>'width:180px;', 'class' => 'js-programs')); ?>
			</div>
    	</div>
		</div>
		 <div class=" col-sm-12">
	   	<div class=" col-sm-4">
	   		<div id="monthyear"  class="monthyear">
	   		<?php echo $this->Form->input('month_year_id', array('type' => 'select', 'options'=>$monthYears, 'empty' => __("-- MonthYear --"), 'required'=>'required', 'label' => "Month Year <span class='ash'>*</span>")); ?>
	   		</div>
	   	</div>
	   	<div class=" col-sm-4">
	   		<?php echo $this->Form->end(__('Submit')); ?>
	   	</div>
	   	</div>
   </div>
   
<!--<script>
$('.input label').addClass('col-sm-5 control-label no-padding-right');
$('.radio label').removeClass('col-sm-5 control-label no-padding-right');
leftMenuSelection('Marks/Practical');
</script>-->
<script>leftMenuSelection('ProfessionalTrainings/download');</script>
<span class='breadcrumb1'>
<span class='navbar-brand'><small>MARKS <i class='ace-icon fa fa-angle-double-right'></i></small></span>
<span class='navbar-brand'><small>Professional Training <i class='ace-icon fa fa-angle-double-right'></i></small></span>
<?php echo $this->Html->link("<span class='navbar-brand'><small> C.A.E <i class='ace-icon fa fa-angle-double-right'></i></small></span>", array("controller"=>"CaePracticals",'action' => 'practical'),array('data-placement'=>'left','escape' => false)); ?>
<?php echo $this->Html->link("<span class='navbar-brand'><small> Download Template <i class='ace-icon fa fa-angle-double-right'></i></small></span>", array("controller"=>"CaePracticals",'action' => 'practicalDownloadTemplate'),array('data-placement'=>'left','escape' => false)); ?>
</span>
