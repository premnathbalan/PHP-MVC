<div class="searchFrm col-sm-12 bgFrame1">
	<?php echo $this->Form->create('Mark', array('type' => 'file'));
	$course_type = $this->params['pass'][0];
	?>
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
		<?php echo $this->Form->button(__('Excel'),array('type'=>'submit','name'=>'submit','value'=>'excel','class'=>'btn', "style"=>"margin-top:20px;width:50px;"));?>
		<?php echo $this->Form->button(__('Print'),array('type'=>'submit','name'=>'submit','value'=>'print','class'=>'btn', "style"=>"margin-top:20px;width:50px;"));?>
		<?php echo $this->Form->end();?>
	</div>		
</div>


<!-- LIST -->
<div id="listAttendance"></div>
	<?php echo $this->Html->script('common-front');?>
</div>

<span class='breadcrumb1'>
<span class='navbar-brand'><small>MARKS<i class='ace-icon fa fa-angle-double-right'></i></small></span>
<span class='navbar-brand'><small><?php echo $course_type; ?><i class='ace-icon fa fa-angle-double-right'></i></small></span>
<?php echo $this->Html->link("<span class='navbar-brand'><small>CAE Mark Print <i class='ace-icon fa fa-angle-double-right'></i></small></span>", array("controller"=>"Attendances",'action' => 'caeMarkPrint'),array('data-placement'=>'left','escape' => false)); ?>
</span>
