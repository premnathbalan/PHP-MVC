<?php echo $this->Form->create('Cae');?>
<div class="caes index">
<div class="searchFrm bgFrame1">
		<div class="col-sm-12">
			<div class="col-lg-3">		
				<?php echo $this->Form->input('month_year_id', array('type' => 'select', 'options'=>$monthyears, 'empty' => __("----- Select Month Year-----"), 'label' => "<span class='ash'>*</span> MonthYear", 'class' => 'js-month-year')); ?>
			</div>
			<div class="col-lg-3">
				<?php echo $this->Form->input('batch_id', array('label' => "<span class='ash'>*</span> Batch", 'empty' => __("----- Select Batch-----"), 'style'=>'width:150px;')); ?>
			</div>
			<div class="col-lg-3">
				<?php echo $this->Form->input('cae_id', array('type' => 'select', 'options'=>$cae, 'label' => "<span class='ash'>*</span> CAE No.", 'empty' => __("----- Select CAE No-----"), 'style'=>'width:150px;')); ?>
			</div>
			<div class="col-lg-3">			
				<?php echo $this->Form->button('<i class="ace-icon fa fa-check bigger-110"></i>'.__('&nbsp;Export&nbsp;&nbsp;'),array('type'=>'submit','name'=>'button','value'=>'get','class'=>'btn')); ?></br>
				<?php echo $this->Html->link(' <i class="ace-icon fa fa-undo bigger-110"></i> '.'&nbsp;Reset&nbsp;',array("controller"=>"EsePracticals",'action'=>'calculate'),array('type'=>'submit','name'=>'reset','value'=>'reset','class'=>'btn','escape' => false));?>
			</div>
		</div>
	</div>	
</div>
<?php echo $this->Form->end(); ?>

<?php echo $this->Html->script('common'); ?>

<span class='breadcrumb1'>
<span class='navbar-brand'><small>REPORTS <i class='ace-icon fa fa-angle-double-right'></i></small></span>
<?php echo $this->Html->link("<span class='navbar-brand'><small>BatchWise CAE Export <i class='ace-icon fa fa-angle-double-right'></i></small></span>", array("controller"=>"ContinuousAssessmentExams",'action' => 'batchwiseCaeExport'),array('data-placement'=>'left','escape' => false)); ?>
</span>