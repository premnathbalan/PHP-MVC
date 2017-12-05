<div>
<div class="searchFrm col-sm-12 bgFrame1">
	
	<div class="col-sm-9">	
		<div class="col-lg-6">
			<?php echo $this->Form->input('batch_id', array( 'label' => "Batch <span class='ash'>*</span>", 'empty' => __("----- Batch-----"), 'class' => 'js-batch js-monthYear')); ?>
		</div>	
		<div class="col-lg-6">
			<?php echo $this->Form->input('academic_id', array('label' => "Program<span class='ash'>*</span>", 'type' => 'select', 'empty' => __("----- Program -----"), 'onchange' => 'academicProgram(this.value);')); ?>
		</div>
	
		<div class="col-lg-6">
			<div id="programs">
				<?php echo $this->Form->input('program_id', array('label' => "Specialisation<span class='ash'>*</span>", 'id'=>'StudentProgramId','type' => 'select', 'empty' => __("----- Specialisation -----"))); ?>
			</div>
		</div>
		<div class="col-lg-6">
			<?php echo $this->Form->input('Month&nbsp;Year', array('label' => "Month&nbsp;Year<span class='ash'>*</span>", 'id'=>'monthYears','type' => 'select', 'empty' => __("--- Month Year ---"),'options'=>$monthyears)); ?>
		</div>
	</div>
	
		<div class="col-lg-3">		
		<?php echo $this->Form->button('<i class="ace-icon fa fa-check bigger-110"></i>'.__('Submit'),array('type'=>'submit','name'=>'submit','value'=>'submit','class'=>'btn',"onclick"=>"attendanceSearch('".$type."','','','1,3');"));?>
		</div>
		<div class="col-lg-3">
		<?php echo $this->Html->link(' <i class="ace-icon fa fa-undo bigger-110"></i> '.'&nbsp;Reset&nbsp;',array("controller"=>"Attendances",'action'=>'index',$type),array('type'=>'submit','name'=>'reset','value'=>'reset','class'=>'btn','escape' => false));?>
		</div>		
	
</div>

<!-- LIST -->
<div id="listAttendance"></div>

<?php echo $this->Html->script('common-front');?>

</div>
<?php if($type == 'G'){ $varAtten = "GROSS ";$varParam = "G";} else{ $varAtten ="COURSE ";$varParam = "C";}?>  
<span class='breadcrumb1'>
<span class='navbar-brand'><small>MARKS <i class='ace-icon fa fa-angle-double-right'></i></small></span>
<span class='navbar-brand'><small>C.A.E <i class='ace-icon fa fa-angle-double-right'></i></small></span>
<?php echo $this->Html->link("<span class='navbar-brand'><small>$varAtten ATTENDANCE <i class='ace-icon fa fa-angle-double-right'></i></small></span>", array("controller"=>"Attendances",'action' => 'index',$varParam),array('data-placement'=>'left','escape' => false)); ?>
</span>
