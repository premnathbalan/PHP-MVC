<?php echo $this->Form->create('Report');?>
<div class="caes index">
	<div class="searchFrm bgFrame1">
		<div class="col-sm-12">
			<div class="col-lg-6">		
				<?php echo $this->Form->input('month_year_id', array('type' => 'select', 'options'=>$monthyears, 'empty' => __("----- Select ExamMonthYear-----"), 'label' => "<span class='ash'>*</span> ExamMonthYear")); ?>
			</div>
			<div class="col-lg-6" style='float:right;'>			
				<?php echo $this->Form->button('<i class="ace-icon fa fa-check bigger-110"></i>'.__('&nbsp;Get&nbsp;&nbsp;'),array('type'=>'button','name'=>'button','value'=>'get','class'=>'btn js-report1', "onclick"=>"report1();")); ?>
				<?php echo $this->Html->link(' <i class="ace-icon fa fa-undo bigger-110"></i> '.'&nbsp;Reset&nbsp;',array("controller"=>"TheoryArrears",'action'=>'theory'),array('type'=>'submit','name'=>'reset','value'=>'reset','class'=>'btn','escape' => false));?>
			</div>
		</div>
	</div>	
</div>

<div id="result"></div>

<script>leftMenuSelection('Timetables/common_code_report');</script>
<?php echo $this->Html->script('common-front');?>
<span class='breadcrumb1'>
<span class='navbar-brand'><small>REPORTS <i class='ace-icon fa fa-angle-double-right'></i></small></span>
<?php echo $this->Html->link("<span class='navbar-brand'><small>COMMON CODE REPORT <i class='ace-icon fa fa-angle-double-right'></i></small></span>", array("controller"=>"Students",'action' => 'publishWebsiteMark'),array('data-placement'=>'left','escape' => false)); ?>
</span>