<?php echo $this->Form->create('Cae');?>
<div class="caes index">
<div class="searchFrm bgFrame1">
		<div class="col-sm-12">
			<!--<div class="col-lg-4">
				<?php echo $this->Form->input('batch_id', array('label' => "<span class='ash'>*</span> Batch", 'empty' => __("----- Select Batch-----"), 'class' => 'js-batch', 'style'=>'width:150px;')); ?>
			</div>-->		
			<div class="col-lg-4">		
				<?php echo $this->Form->input('month_year_id', array('type' => 'select', 'options'=>$monthyears, 'empty' => __("----- Select Month Year-----"), 'label' => "<span class='ash'>*</span> MonthYear", 'class' => 'js-month-year')); ?>
			</div>
			<div class="col-lg-4">		
				<?php 
				$assOptions = array("1"=>"1","2"=>"2", "3"=>"3"); 
				echo $this->Form->input('no', array('type' => 'select', 'options'=>$assOptions, 'empty' => __("----- Select CAE No.-----"), 'label' => "<span class='ash'>*</span> Ass No.")); ?>
			</div>
			<div class="col-lg-4">		
				<?php echo $this->Form->button('<i class="ace-icon fa fa-check bigger-110"></i>'.__('&nbsp;Submit&nbsp;&nbsp;'),array('type'=>'submit','name'=>'button','value'=>'submit','class'=>'btn')); ?>
				<?php echo $this->Html->link(' <i class="ace-icon fa fa-undo bigger-110"></i> '.'&nbsp;Reset&nbsp;',array("controller"=>"ContinuousAssessmentExams",'action'=>'caeReport'),array('type'=>'submit','name'=>'reset','value'=>'reset','class'=>'btn','escape' => false));?>
			</div>
		</div>
		<!--<div class="col-sm-12">
			<div class="col-lg-12" align="right">			
				<?php echo $this->Form->button('<i class="ace-icon fa fa-check bigger-110"></i>'.__('&nbsp;Submit&nbsp;&nbsp;'),array('type'=>'submit','name'=>'button','value'=>'submit','class'=>'btn')); ?>
				<?php echo $this->Html->link(' <i class="ace-icon fa fa-undo bigger-110"></i> '.'&nbsp;Reset&nbsp;',array("controller"=>"ContinuousAssessmentExams",'action'=>'caeReport'),array('type'=>'submit','name'=>'reset','value'=>'reset','class'=>'btn','escape' => false));?>
			</div>
		</div>-->
	</div>	
</div>

<?php echo $this->Form->end(); ?>

<?php echo $this->Html->script('common'); ?>

<span class='breadcrumb1'>
<span class='navbar-brand'><small>REPORTS <i class='ace-icon fa fa-angle-double-right'></i></small></span>
<?php echo $this->Html->link("<span class='navbar-brand'><small>Cae Report <i class='ace-icon fa fa-angle-double-right'></i></small></span>", array("controller"=>"ContinuousAssessmentExams",'action' => 'caeReport'),array('data-placement'=>'left','escape' => false)); ?>
</span>