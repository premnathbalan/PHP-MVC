<div>
<div class="searchFrm col-sm-12 bgFrame1">
	<?php echo $this->Form->create('ESE');?>
	<div class="col-sm-9">
		<div class="col-lg-6">
			<?php echo $this->Form->input('month_year_id', array('label' => "Month&nbsp;Year<span class='ash'>*</span>", 'id'=>'monthYears','type' => 'select', 'empty' => __("--- Month Year ---"),'options'=>$monthyears)); ?>
		</div>	
		<div class="col-lg-6">
			<?php echo $this->Form->input('batch_id', array( 'label' => "Batch <span class='ash'>*</span>", 'empty' => __("----- Batch-----"), 'class' => 'js-batch js-monthYear')); ?>
		</div>	
		<div class="col-lg-6">
			<?php echo $this->Form->input('academic_id', array('label' => "Program<span class='ash'>*</span>", 'type' => 'select', 'empty' => __("----- Program -----"), 'class'=>'js-academic')); ?>
		</div>
	
		<div class="col-lg-6">
			<div id="program" class="program">
				<?php echo $this->Form->input('program_id', array('label' => "Specialisation<span class='ash'>*</span>", 'id'=>'StudentProgramId','type' => 'select', 'empty' => __("----- Specialisation -----"))); ?>
			</div>
		</div>
		
	</div>
	
		<div class="col-lg-3">		
			<?php echo $this->Form->button('<i class="ace-icon fa fa-check bigger-110"></i>'.__('Submit'),array('type'=>'button','name'=>'submit','value'=>'submit','class'=>'btn js-ese'));?>
		</div>
		<div class="col-lg-3">
			<?php echo $this->Html->link(' <i class="ace-icon fa fa-undo bigger-110"></i> '.'&nbsp;Reset&nbsp;',array("controller"=>"EndSemesterExams",'action'=>'index'),array('type'=>'submit','name'=>'reset','value'=>'reset','class'=>'btn','escape' => false));?>
		</div>
</div>
<div class="col-sm-9">
		<div id="eseResult">testdata</div>
	</div>
</div>
	
<?php echo $this->Form->end(); ?>
<span class='breadcrumb1'>
<span class='navbar-brand'><small>EXAMINATION <i class='ace-icon fa fa-angle-double-right'></i></small></span>
<?php echo $this->Html->link("<span class='navbar-brand'><small>ESE <i class='ace-icon fa fa-angle-double-right'></i></small></span>", array("controller"=>"EndSemesterExams",'action' => 'index'),array('data-placement'=>'left','escape' => false)); ?>
</span>