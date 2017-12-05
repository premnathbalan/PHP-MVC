<div>
	<div class="searchFrm col-sm-12 bgFrame1">	
		<div class="col-sm-12">
			<div class="col-lg-4"></div>
			<div class="col-lg-3" style="text-align:left;">	
			
		</div>				
	</div>
		
	<div class="col-sm-9">	
		<div class="col-lg-6">
			<?php echo $this->Form->input('batch_id', array('label' => "Batch <span class='ash'>*</span>", 'empty' => __("----- Batch-----"), 'class' => 'js-batch js-monthYear')); ?>
		</div>	
		<div class="col-lg-6">
			<?php echo $this->Form->input('academic_id', array('label' => "Academic<span class='ash'>*</span>",'type' => 'select', 'empty' => __("----- Academic -----"), 'onchange' => 'academicProgram(this.value);')); ?>
		</div>
	
		<div class="col-lg-6">
			<div id="programs">
				<?php echo $this->Form->input('program_id', array('label' => "Program<span class='ash'>*</span>",'id'=>'StudentProgramId','type' => 'select', 'empty' => __("----- Program -----"))); ?>
			</div>
		</div>
		<div class="col-lg-6">
			<?php echo $this->Form->input('Month&nbsp;Year', array('label' => "Month&nbsp;Year<span class='ash'>*</span>",'id'=>'monthYears', 'name'=>'monthYears','type' => 'select', 'empty' => __("--- Month Year ---"),'options'=>$monthyears)); ?>
		</div>
	</div>
	
		<div class="col-lg-3">		
			<?php echo $this->Form->input('CAE',array('type' => 'select','options'=>array('CAE'=>'CAE','ESE'=>'ESE'), 'style'=>'width:50px;margin-top:5px;')); ?>
		</div>
		<div class="col-lg-3">
			<?php echo $this->Form->button('<i class="ace-icon fa fa-check bigger-110"></i>'.__('Submit'),array('type'=>'button','name'=>'submit','value'=>'submit','class'=>'btn',"onclick"=>"attendanceSearch('','P','P-F','2,3,6');"));?>
		</div>		
	
</div>

<!-- LIST -->
<div id="listAttendance"></div>
	<?php echo $this->Html->script('common-front');?>
</div>

<span class='breadcrumb1'>
<span class='navbar-brand'><small>PRACTICAL <i class='ace-icon fa fa-angle-double-right'></i></small></span>
<span class='navbar-brand'><small>FOIL CARDS <i class='ace-icon fa fa-angle-double-right'></i></small></span>
<?php echo $this->Html->link("<span class='navbar-brand'><small>ATTENDANCE <i class='ace-icon fa fa-angle-double-right'></i></small></span>", array("controller"=>"Attendances",'action' => 'practical_attendance_foil_cards'),array('data-placement'=>'left','escape' => false)); ?>
</span>
