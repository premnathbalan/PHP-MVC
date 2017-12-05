<div>
<?php echo $this->Form->create('Label'); ?>
<div class="searchFrm bgFrame1">	
	<div class="col-lg-12">
		
		<div class="col-lg-4">
			<?php echo $this->Form->input('batch_id', array('label' => "Batch<span class='ash'>*</span>", 'empty' => __("----- Batch-----"), 'class' => 'js-batch js-monthYear')); ?>
		</div>	
		<div class="col-lg-4">
			<?php echo $this->Form->input('academic_id', array('type' => 'select', 'empty' => __("----- Select Program-----"), 'label' => "Program<span class='ash'>*</span>", 'class' => 'js-academic')); ?>
		</div>
	
		
		<div class="col-lg-4">
			<div id="programs" class="program">
			<?php echo $this->Form->input('program_id', array('type' => 'select', 'empty' => __("----- Select Specialisation-----"), 'label' => "Specialisation<span class='ash'>*</span>", 'class' => 'js-program')); ?>
			</div>
		</div>
	</div>
		
	<div class="col-lg-12">
	<div class="col-lg-4"></div>
	
	<div class="col-lg-4" style="text-align:center;">
		<?php echo $this->Form->button('<i class="ace-icon fa fa-print"></i>'.__('PRINT'),array('type'=>'submit','name'=>'print','value'=>'print','class'=>'btn'));?>
		<?php echo $this->Html->link('<i class="ace-icon fa fa-undo bigger-110"></i>'.'Reset',array("controller"=>"Students",'action'=>'cgpa'),array('type'=>'submit','name'=>'reset','value'=>'reset','class'=>'btn','escape' => false));?>		
	</div>		
	</div>
</div>

	<div id="cgpaList"></div>
<?php echo $this->Form->end(); ?>		
<?php echo $this->Html->script('common-front');?>
</div>

<span class='breadcrumb1'>
<span class='navbar-brand'><small>Examination <i class='ace-icon fa fa-angle-double-right'></i></small></span>
<?php echo $this->Html->link("<span class='navbar-brand'><small>Label <i class='ace-icon fa fa-angle-double-right'></i></small></span>", array("controller"=>"Students",'action' => 'label'),array('data-placement'=>'left','escape' => false)); ?>
</span>