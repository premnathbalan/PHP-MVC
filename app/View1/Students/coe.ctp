<div>	
<div class="searchFrm bgFrame1">	
	<div class="col-lg-12">
	<div class="col-lg-4">
		<?php echo $this->Form->input('monthyears', array('type' => 'select', 'empty' => __("----- Exam Month Year-----"), 'label' => "Exam&nbsp;Month&nbsp;Year<span class='ash'>*</span>", 'required'=>'required')); ?>
	</div>
	<div class="col-lg-4">
		<?php echo $this->Form->input('batch_id', array('label' => "Batch", 'empty' => __("----- Batch-----"), 'class' => 'js-batch js-monthYear')); ?>
	</div>	
	<div class="col-lg-4">
		<?php echo $this->Form->input('academic_id', array('type' => 'select', 'empty' => __("----- Select Program-----"), 'label' => "Program", 'class' => 'js-academic')); ?>
	</div>
	</div>
	<div class="col-lg-12">
	<div class="col-lg-6">
		<div id="programs" class="program">
		<?php echo $this->Form->input('program_id', array('type' => 'select', 'empty' => __("----- Select Specialisation-----"), 'label' => "Specialisation", 'class' => 'js-program')); ?>
		</div>
	</div>
	<div class="col-lg-6" style="text-align:center;">		
		<?php echo $this->Form->button('<i class="ace-icon fa fa-check bigger-110"></i>'.__('Search'),array('type'=>'submit','name'=>'submit','value'=>'submit','class'=>'btn',"onclick"=>"coeSearch();"));?>
		<?php echo $this->Html->link('<i class="ace-icon fa fa-undo bigger-110"></i>'.'Reset',array("controller"=>"Students",'action'=>'coe'),array('type'=>'submit','name'=>'reset','value'=>'reset','class'=>'btn','escape' => false));?>		
	</div>		
	</div>
</div>

<div id="coeSearch"></div>

<?php echo $this->Html->script('common');?>
</div>

<span class='breadcrumb1'>
<span class='navbar-brand'><small>RESULTS <i class='ace-icon fa fa-angle-double-right'></i></small></span>
<?php echo $this->Html->link("<span class='navbar-brand'><small>CoE <i class='ace-icon fa fa-angle-double-right'></i></small></span>", array("controller"=>"Students",'action' => 'coe'),array('data-placement'=>'left','escape' => false)); ?>
</span>