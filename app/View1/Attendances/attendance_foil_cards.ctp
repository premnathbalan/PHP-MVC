<div>
	<div class="searchFrm col-sm-12 bgFrame1">	
		<div class="col-sm-12">
			<div class="col-lg-4"></div>
			<div class="col-lg-3" style="text-align:left;">	
			<input type="radio" name="aFoilCards" checked="checked" value="C" >Course &nbsp;&nbsp;&nbsp;
			<input type="radio" name="aFoilCards" value="G" >Gross
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
			<?php echo $this->Form->input('CAE',array('type' => 'select','options'=>array('1'=>'1','2'=>'2','3'=>'3','4'=>'4','5'=>'5'), 'style'=>'width:50px;margin-top:5px;')); ?>
		</div>
		<div class="col-lg-3">
			<?php echo $this->Form->button('<i class="ace-icon fa fa-check bigger-110"></i>'.__('Submit'),array('type'=>'button','name'=>'submit','value'=>'submit','class'=>'btn',"onclick"=>"attendanceSearch('','P','P-F','1,3');"));?>
			<?php echo $this->Form->button('<i class="ace-icon fa fa-check bigger-110"></i>'.__('Gross/Foil Card'),array('type'=>'button','name'=>'submit','value'=>'submit','class'=>'btn',"onclick"=>"attendanceSearch('','F','P-F');"));?>
		</div>		
	
</div>

<!-- LIST -->
<div id="listAttendance"></div>
	<?php echo $this->Html->script('common-front');?>
</div>

<span class='breadcrumb1'>
<span class='navbar-brand'><small>FOIL CARDS <i class='ace-icon fa fa-angle-double-right'></i></small></span>
<?php echo $this->Html->link("<span class='navbar-brand'><small>ATTENDANCE <i class='ace-icon fa fa-angle-double-right'></i></small></span>", array("controller"=>"Attendances",'action' => 'attendance_foil_cards'),array('data-placement'=>'left','escape' => false)); ?>
</span>
