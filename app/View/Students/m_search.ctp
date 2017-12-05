<div class="searchFrm bgFrame1" style="width:89%;">
	<?php echo $this->Form->create('Student');?>
	 <div class="col-sm-4">
	 	<?php echo $this->Form->input('registration_number', array('label' => "Register Number<span class='ash'>*</span>", array("controller"=>"Students",'action'=>'mSearch'), 'type' => 'text','style'=>'margin-top:10px;','maxlength'=>10));?>
	</div> 
	<?php echo $this->Form->button('<i class="ace-icon fa fa-check bigger-110"></i>'.__('Search'),array('type'=>'submit','name'=>'submit','value'=>'submit','class'=>'btn'));?>
	<?php echo $this->Form->end(); ?>
</div>

<span class='breadcrumb1'>
	<span class='navbar-brand'><small>REPORTS <i class='ace-icon fa fa-angle-double-right'></i></small></span>
	<?php echo $this->Html->link("<span class='navbar-brand'><small> Student Mark <i class='ace-icon fa fa-angle-double-right'></i></small></span>", array("controller"=>"Students",'action' => 'mSearch'),array('data-placement'=>'left','escape' => false)); ?>
</span>