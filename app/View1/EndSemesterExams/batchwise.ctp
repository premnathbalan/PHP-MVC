<?php echo $this->Form->create('Batchwise');?>
<div class="caes index">
<div class="searchFrm bgFrame1">
		<div class="col-sm-12">
			<div class="col-lg-4">		
				<?php echo $this->Form->input('month_year_id', array('type' => 'select', 'options'=>$monthyears, 'empty' => __("----- Select Month Year-----"), 'label' => "<span class='ash'>*</span> MonthYear", 'class' => 'js-month-year')); ?>
			</div>
			<div class="col-lg-4">
				<?php echo $this->Form->input('batch_id', array('label' => "<span class='ash'>*</span> Batch", 'empty' => __("----- Select Batch-----"), 'class' => 'js-batch', 'style'=>'width:150px;')); ?>
			</div>
			<div class="col-lg-4">			
				<?php echo $this->Form->button('<i class="ace-icon fa fa-check bigger-110"></i>'.__('&nbsp;Get&nbsp;&nbsp;'),array('type'=>'button','name'=>'button','value'=>'get','class'=>'btn js-report-batchwise')); ?>
				<?php echo $this->Html->link(' <i class="ace-icon fa fa-undo bigger-110"></i> '.'&nbsp;Reset&nbsp;',array("controller"=>"EsePracticals",'action'=>'calculate'),array('type'=>'submit','name'=>'reset','value'=>'reset','class'=>'btn','escape' => false));?>
			</div>
		</div>
	</div>	
</div>
<div id="reportDisplay"></div>

<?php echo $this->Form->end(); ?>

<?php echo $this->Html->script('common'); ?>

<span class='breadcrumb1'>
<span class='navbar-brand'><small>RESULTS <i class='ace-icon fa fa-angle-double-right'></i></small></span>
<?php echo $this->Html->link("<span class='navbar-brand'><small>BatchWise <i class='ace-icon fa fa-angle-double-right'></i></small></span>", array("controller"=>"EndSemesterExams",'action' => 'batchwise'),array('data-placement'=>'left','escape' => false)); ?>
</span>