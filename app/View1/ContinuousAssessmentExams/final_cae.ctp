<?php if(isset($PRINT) == ''){?>
<div class="searchFrm">
<?php echo $this->Form->create(array("controller"=>"ContinuousAssessmentExams",'action'=>'final_cae'));?>
<div class="searchFrm col-sm-12 bgFrame1">
	<div class="col-lg-4">
		<?php echo $this->Form->input('monthYear', array('label' => 'MonthYear', 'empty' => __("----- Select Month Year-----"), 'class' => 'js-mod-monthyear','required'=>'required')); ?>
	</div>
	<div class="col-lg-4">
		<?php echo $this->Form->input('batch_id', array('label' => 'Batch', 'empty' => __("----- Select Batch-----"), 'class' => 'js-mod-batch')); ?>
	</div>
	<div class="col-lg-4">
		<div id='program' class='program'>
			<?php echo $this->Form->input('program_id', array('label'=>'Specialisation','type' => 'select', 'empty' => __("----- Select Specialisation-----"), 'class' => 'js-mod-program')); ?>
		</div>
	</div>	
	<div class="col-lg-8"></div>
	<div class="col-lg-4">
		<?php echo $this->Form->button('<i class="ace-icon fa fa-download fa-lg"></i>'.__('DOWNLOAD'),array('type'=>'submit','name'=>'DOWNLOAD','value'=>'DOWNLOAD','class'=>'btn'));?>
		<?php echo $this->Form->button('<i class="ace-icon fa fa-print fa-lg"></i>'.__('PRINT'),array('type'=>'submit','name'=>'PRINT','value'=>'PRINT','class'=>'btn'));?>
	</div>
</div>
<?php echo $this->Form->end();?>
</div>

<span class='breadcrumb1'>
<span class='navbar-brand'><small>MARKS <i class='ace-icon fa fa-angle-double-right'></i></small></span>
<span class='navbar-brand'><small>C.A.E <i class='ace-icon fa fa-angle-double-right'></i></small></span>
<?php echo $this->Html->link("<span class='navbar-brand'><small> Final CAE Marks <i class='ace-icon fa fa-angle-double-right'></i></small></span>", array("controller"=>"ContinuousAssessmentExams",'action' => 'final_cae'),array('data-placement'=>'left','escape' => false)); ?>
</span>
<?php }?>