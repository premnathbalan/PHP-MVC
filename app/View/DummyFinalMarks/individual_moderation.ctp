<?php echo $this->Form->create('Dummy');?>
<div class="searchFrm bgFrame1">
	<div class="searchFrm col-sm-12" >
		<div class="col-sm-9">
			<div class="col-lg-6">
				<?php echo $this->Form->input('number', array('label' => "<span class='ash'>*</span> Dummy&nbsp;Number")); ?>
			</div>
			<div class="col-lg-6">			
				<?php echo $this->Form->button('<i class="ace-icon fa fa-check bigger-110"></i>'.__('&nbsp;Get&nbsp;&nbsp;'),array('type'=>'button','name'=>'submit','value'=>'get','class'=>'btn js-dummy-individual')); ?>
			</div>
		</div>
	</div>
</div>
<div id="dummyResult"></div>
<?php echo $this->Form->end(); ?>
		
<?php echo $this->Html->script('common'); ?>

<script>leftMenuSelection('Dummy Marks Approval');</script>

<span class='breadcrumb1'>
<span class='navbar-brand'><small>EXAMINATION <i class='ace-icon fa fa-angle-double-right'></i></small></span>
<?php echo $this->Html->link("<span class='navbar-brand'><small>Dummy Marks Approval <i class='ace-icon fa fa-angle-double-right'></i></small></span>", array("controller"=>"DummyMarks",'action' => 'approval'),array('data-placement'=>'left','escape' => false)); ?>
</span>