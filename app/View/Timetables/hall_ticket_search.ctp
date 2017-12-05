<?php echo $this->Form->create('Student');?>
<div class="caes index">
<div class="searchFrm bgFrame1">
		<div class="col-sm-12">
			<div class="col-lg-3">		
				<?php echo $this->Form->input('month_year_id', array('type' => 'select', 'options'=>$monthyears, 'empty' => __("----- Select Month Year-----"), 'label' => "<span class='ash'>*</span> MonthYear", 'class' => 'js-month-year')); ?>
			</div>
			<div class="col-lg-3">		
				<?php echo $this->Form->input('exam_type' ,array('label' => "Exam Type<span class='ash'>*</span>",'options' => array('R' => 'Regular', 'A' => 'Arrear')));?>
			</div>
			<div class="col-lg-3">
				<?php echo $this->Form->input('registration_number', array('label' => "Register Number<span class='ash'>*</span>", array("controller"=>"Students",'action'=>'lateJoiner'), 'type' => 'text','style'=>'margin-top:10px;','maxlength'=>10));?>
			</div>
			<div class="col-lg-3">		
				<?php echo $this->Form->button('<i class="ace-icon fa fa-check bigger-110"></i>'.__('&nbsp;Get&nbsp;&nbsp;'),array('type'=>'submit','name'=>'submit','value'=>'submit','class'=>'btn js-individual', 'style'=>'margin-top: 40px;')); ?>
			</div>
		</div>
	</div>	
</div>

<?php echo $this->Form->end(); ?>

<?php echo $this->Html->script('common'); ?>

<span class='breadcrumb1'>
<span class='navbar-brand'><small>EXAMINATION <i class='ace-icon fa fa-angle-double-right'></i></small></span>
<?php echo $this->Html->link("<span class='navbar-brand'><small>HALLTICKET <i class='ace-icon fa fa-angle-double-right'></i></small></span>", array("controller"=>"Timetables",'action' => 'hallTicketSearch'),array('data-placement'=>'left','escape' => false)); ?>
</span>