<div class="caes index">
<div class="searchFrm">
<?php echo $this->Form->create('Student');?>
<div class="searchFrm col-sm-12">

	<?php echo $this->element('search_fields'); ?>
	<div class="col-lg-3">	
	<?php echo $this->Form->button('<i class="ace-icon fa fa-check bigger-110"></i>'.__('Submit'),array('type'=>'button','id'=>'js-cae-import-info', 'name'=>'submit','value'=>'submit','class'=>'btn js-cae-import-info', 'onclick' => 'getCaeDisplay(this.id);'));?>
	</div>
	<div class="col-lg-3">
	<?php
		echo $this->Form->button('<i class="ace-icon fa fa-undo bigger-110"></i>'.'&nbsp;&nbsp;Reset&nbsp;',array('type'=>'reset','name'=>'submit','value'=>'submit','class'=>'btn'));
	?>
	</div>
</div>
</div>
<div id="caeImportInfo" class="caeImportInfo"></div>
<?php echo $this->Form->end(); ?>

</div>

<?php echo $this->Html->script('common_new'); ?>

<span class='breadcrumb1'>
<span class='navbar-brand'><small>MARKS <i class='ace-icon fa fa-angle-double-right'></i></small></span>
<span class='navbar-brand'><small>C.A.E <i class='ace-icon fa fa-angle-double-right'></i></small></span>
<?php echo $this->Html->link("<span class='navbar-brand'><small> Import <i class='ace-icon fa fa-angle-double-right'></i></small></span>", array("controller"=>"ContinuousAssessmentExams",'action' => 'practical'),array('data-placement'=>'left','escape' => false)); ?>
</span>