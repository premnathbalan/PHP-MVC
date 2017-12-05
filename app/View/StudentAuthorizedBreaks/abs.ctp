<?php echo $this->Form->create('ABS', array('inputDefaults' => array('div' => false)));?>
<div class="searchFrm bgFrame1" style="width:100%;">
	<div class="col-sm-12">	
		<div class="col-lg-4">
			<?php echo $this->Form->input('registration_number', array('label' => "Register Number<span class='ash'>*</span>", array("controller"=>"Students",'action'=>'lateJoiner'), 'type' => 'text','style'=>'margin-top:10px;','maxlength'=>10));?>
			<?php //echo $this->Form->input('type_id', array('label' => false, 'type' => 'hidden','style'=>'margin-top:10px;width:25px;','maxlength'=>10, 'value'=>$cTypeValue));?>
		</div>
	
		<div class="col-lg-4">		
			<?php echo $this->Form->button('<i class="ace-icon fa fa-check bigger-110"></i>'.__('&nbsp;Get&nbsp;&nbsp;'),array('type'=>'button','name'=>'submit','value'=>'get','class'=>'btn js-abs')); ?>
		</div>
	</div>	
	<!--<div class="col-sm-12">-->
	<!--</div>-->	
</div>
<div id="courses" class="program col-lg-12"></div>
<?php echo $this->Form->end(); ?>

<?php echo $this->Html->script('common'); ?>

<script>leftMenuSelection('AuthorizedBreaks/abs');</script>
<span class='breadcrumb1'>
<span class='navbar-brand'><small>Students <i class='ace-icon fa fa-angle-double-right'></i></small></span>
<?php echo $this->Html->link("<span class='navbar-brand'><small> ABS <i class='ace-icon fa fa-angle-double-right'></i></small></span>", array("controller"=>"AuthorizedBreaks",'action' => 'abs'),array('data-placement'=>'left','escape' => false)); ?>
</span>
