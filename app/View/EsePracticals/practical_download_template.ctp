<div class="students form">
	
	<?php echo $this->Form->create('CaePractical', array('type' => 'file')); ?>
	
	<div class="bgFrame1">
	<div class=" col-sm-12">
	    <div class=" col-sm-4">
			<?php echo $this->Form->input('batch_id', array('type' => 'select', 'options' => $batches, 'empty' => __("-- Batch --"), 'class'=>'js-batch', 'label' => "Batch <span class='ash'>*</span>"));?>
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
   
<script>leftMenuSelection('EsePracticals/practical');</script>

<?php //echo $this->Html->script('common'); ?>
<span class='breadcrumb1'>
<span class='navbar-brand'><small>MARKS <i class='ace-icon fa fa-angle-double-right'></i></small></span>
<span class='navbar-brand'><small>Practical <i class='ace-icon fa fa-angle-double-right'></i></small></span>
<?php echo $this->Html->link("<span class='navbar-brand'><small> E.S.E <i class='ace-icon fa fa-angle-double-right'></i></small></span>", array("controller"=>"EsePracticals",'action' => 'practical'),array('data-placement'=>'left','escape' => false)); ?>
<?php echo $this->Html->link("<span class='navbar-brand'><small> Download Template <i class='ace-icon fa fa-angle-double-right'></i></small></span>", array("controller"=>"EsePracticals",'action' => 'practicalDownloadTemplate'),array('data-placement'=>'left','escape' => false)); ?>
</span>
