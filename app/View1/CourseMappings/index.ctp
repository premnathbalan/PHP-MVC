<div>

<div class="searchFrm bgFrame1">	
	<div class="col-lg-4">
		<?php echo $this->Form->input('batch_id', array('label' => "Batch<span class='ash'>*</span>", 'empty' => __("----- Batch-----"), 'class' => 'js-batch js-monthYear')); ?>
	</div>	
	<div class="col-lg-4">
		<?php echo $this->Form->input('academic_id', array('label' => "Program<span class='ash'>*</span>", 'type' => 'select', 'empty' => __("----- Select Program-----"), 'onchange' => 'academicProgram(this.value);')); ?>
	</div>
	<div class="col-lg-4" id="programs">
		<?php echo $this->Form->input('program_id', array('label' => "Specialisation&nbsp;&nbsp;<span class='ash'>*</span>", 'id'=>'StudentProgramId','type' => 'select', 'empty' => __("-Select Specialisation-"))); ?>
	</div>
	<div class="col-lg-4"></div>
	<div class="col-lg-4" style="text-align:center;">		
		<?php echo $this->Form->button('<i class="ace-icon fa fa-check bigger-110"></i>'.__('Submit'),array('type'=>'submit','name'=>'submit','value'=>'submit','class'=>'btn',"onclick"=>"courseMappingSearch();"));?>
		<?php echo $this->Html->link('<i class="ace-icon fa fa-undo bigger-110"></i>'.'Reset',array("controller"=>"CourseMappings",'action'=>'index'),array('type'=>'submit','name'=>'reset','value'=>'reset','class'=>'btn','escape' => false));?>		
	</div>		
</div>

<div id ="listCourseMapping"></div>

<?php echo $this->Html->script('common-front');?>
	
</div>
<span class='breadcrumb1'>
<span class='navbar-brand'><small>MASTERS <i class='ace-icon fa fa-angle-double-right'></i></small></span>
<?php echo $this->Html->link("<span class='navbar-brand'><small>COURSE MAPPINGS <i class='ace-icon fa fa-angle-double-right'></i></small></span>", array("controller"=>"CourseMappings",'action' => 'index'),array('data-placement'=>'left','escape' => false)); ?>
</span>