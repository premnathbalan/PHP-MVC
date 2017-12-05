<?php echo $this->Form->create('Moderation');?>
<div class="searchFrm bgFrame1" style="width:100%;">
	<div class="col-sm-9">	
		<div class="col-lg-4">
			<?php echo $this->Form->input('month_year_id', array('label'=>'MonthYear','type' => 'select', 'empty' => __("----- Select MonthYear-----"),'required'=>'')); ?>
		</div>
		
		<div class="col-lg-4">
			<?php echo $this->Form->input('registration_number', array('label' => "Register Number<span class='ash'>*</span>", array("controller"=>"Students",'action'=>'lateJoiner'), 'type' => 'text','style'=>'margin-top:10px;','maxlength'=>10));?>
			<?php //echo $this->Form->input('type_id', array('label' => false, 'type' => 'hidden','style'=>'margin-top:10px;width:25px;','maxlength'=>10, 'value'=>$cTypeValue));?>
		</div>
	
		<div class="col-lg-4">		
			<?php echo $this->Form->button('<i class="ace-icon fa fa-check bigger-110"></i>'.__('&nbsp;Get&nbsp;&nbsp;'),array('type'=>'button','name'=>'submit','value'=>'get','class'=>'btn js-tmp-ese-get')); ?>
		</div>
	</div>	
	<div class="col-sm-9">
		<div id="courses" class="program col-lg-6">
			
		</div>
	</div>	
	<div class="col-sm-9">
		<div id="marks" class="program col-lg-12">
			
		</div>
	</div>	
	<div class="col-sm-9">
			<?php echo $this->Form->button('<i class="ace-icon fa fa-check bigger-110"></i>'.__('Submit'),array('type'=>'submit','name'=>'submit','value'=>'submit', 'class'=>'btn','style'=>'float:right;')); ?>
	</div>		
</div>
<?php echo $this->Form->end(); ?>

<?php echo $this->Html->script('common'); ?>

<script>leftMenuSelection('EsePracticals/tmpModeration');</script>
<span class='breadcrumb1'>
<span class='navbar-brand'><small>MARKS <i class='ace-icon fa fa-angle-double-right'></i></small></span>
<span class='navbar-brand'><small>Practical <i class='ace-icon fa fa-angle-double-right'></i></small></span>
<?php echo $this->Html->link("<span class='navbar-brand'><small> Moderation <i class='ace-icon fa fa-angle-double-right'></i></small></span>", array("controller"=>"EsePracticals",'action' => 'tmpModeration'),array('data-placement'=>'left','escape' => false)); ?>
</span>
