<div class="students form">
	
	<?php echo $this->Form->create('CaeProject', array('type' => 'file'));
	
	$assOptions = array("1"=>"1","2"=>"2", "3"=>"3"); 
	?>
	
	<div class="bgFrame1">
	    <div class=" col-sm-6">
			<?php echo $this->Form->input('batch_id', array('type' => 'select', 'options' => $batches, 'empty' => __("-- Batch --"), 'class'=>'js-batch', 'label' => "Batch <span class='ash'>*</span>"));?>
		</div>       
        <div class=" col-sm-6">
			<?php echo $this->Form->input('academic_id', array('type' => 'select', 'options' => $academics, 'empty' => __("-- Academic --"), 'class' => 'student-academic','style'=>'width:180px;', 'label' => "Academic<span class='ash'>*</span>"));?>
		</div>       
        <div class=" col-sm-6">
			<div id="programs"  class="program">
			<?php echo $this->Form->input('program_id', array('type' => 'select', 'empty' => __("-- Program --"), 'label' => "Program <span class='ash'>*</span>",'style'=>'width:180px;', 'class' => 'js-programs')); ?>
			</div>
    	</div>

	   	<div class=" col-sm-6">
	   		<div id="monthyear"  class="monthyear">
	   		<?php echo $this->Form->input('month_year_id', array('type' => 'select', 'options'=>$monthyears, 'empty' => __("-- MonthYear --"), 'required'=>'required', 'label' => "Month Year <span class='ash'>*</span>")); ?>
	   		</div>
	   	</div>
	   	<div class=" col-sm-6">
			<?php echo $this->Form->input('assessment_number', array('type' => 'select', 'options' => $assOptions, 'empty' => __("-- Assessment --"), 'class' => 'js-assessment','style'=>'width:180px;', 'label' => "Assessment<span class='ash'>*</span>"));?>
		</div> 
		<div class=" col-sm-6">
			<?php //echo $this->Form->input('marks', array('type' => 'text', 'class' => 'js-cae-mark', 'label' => "Marks<span class='ash'>*</span>"));?>
		</div> 
	   	<div class=" col-sm-6">
	   		<?php echo $this->Form->end(__('Submit')); ?>
	   	</div>
   </div>
   
<?php echo $this->Html->script('common'); ?>

<script>
$('.input label').addClass('col-sm-5 control-label no-padding-right');
$('.radio label').removeClass('col-sm-5 control-label no-padding-right');
leftMenuSelection('CaeProjects');
</script>

<span class='breadcrumb1'>
<span class='navbar-brand'><small>MARKS <i class='ace-icon fa fa-angle-double-right'></i></small></span>
<span class='navbar-brand'><small>Project <i class='ace-icon fa fa-angle-double-right'></i></small></span>
<?php echo $this->Html->link("<span class='navbar-brand'><small> C.A.E <i class='ace-icon fa fa-angle-double-right'></i></small></span>", array("controller"=>"CaeProjects",'action' => 'index'),array('data-placement'=>'left','escape' => false)); ?>
<?php echo $this->Html->link("<span class='navbar-brand'><small> Download <i class='ace-icon fa fa-angle-double-right'></i></small></span>", array("controller"=>"CaeProjects",'action' => 'download'),array('data-placement'=>'left','escape' => false)); ?>
</span>
