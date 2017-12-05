<?php echo $this->Form->create('Arrear');?>
<div class="caes index">
<div class="searchFrm bgFrame1">
		<div class="col-sm-12">
			<div class="col-lg-6">		
				<?php echo $this->Form->input('month_year_id', array('type' => 'select', 'options'=>$monthyears, 'empty' => __("----- Select Month Year-----"), 'label' => "<span class='ash'>*</span> MonthYear", 'class' => 'js-month-year')); ?>
			</div>
			<div class="col-lg-6">			
				<?php echo $this->Form->button('<i class="ace-icon fa fa-check bigger-110"></i>'.__('&nbsp;Get&nbsp;&nbsp;'),array('type'=>'button','name'=>'button','value'=>'get','class'=>'btn js-arrear')); ?>
				<?php echo $this->Form->button('<i class="ace-icon fa fa-print fa-lg"></i>'.__('PRINT'),array('type'=>'submit','name'=>'option','value'=>'PRINT','class'=>'btn'));?>
				<?php echo $this->Form->button('<i class="ace-icon fa fa-download fa-lg"></i>'.__('DOWNLOAD'),array('type'=>'submit','name'=>'option','value'=>'DOWNLOAD','class'=>'btn'));?>
				<?php echo $this->Html->link(' <i class="ace-icon fa fa-undo bigger-110"></i> '.'&nbsp;Reset&nbsp;',array("controller"=>"EndSemesterExams",'action'=>'arrear'),array('type'=>'submit','name'=>'reset','value'=>'reset','class'=>'btn','escape' => false));?>
			</div>
		</div>
	</div>	
</div>
<div id="reportDisplay"></div>

<?php echo $this->Form->end(); ?>

<?php echo $this->Html->script('common'); ?>

<span class='breadcrumb1'>
<span class='navbar-brand'><small>REPORTS <i class='ace-icon fa fa-angle-double-right'></i></small></span>
<?php echo $this->Html->link("<span class='navbar-brand'><small>Arrear Analysis <i class='ace-icon fa fa-angle-double-right'></i></small></span>", array("controller"=>"EndSemesterExams",'action' => 'arrear'),array('data-placement'=>'left','escape' => false)); ?>
</span>