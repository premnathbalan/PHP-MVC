<div id="js-load-forms"></div>
<div class="page-header">
	<?php echo $this->Html->link('<i class="ace-icon fa fa-plus-square"></i>'. 'Add Project', array("controller"=>"ContinuousAssessmentExams",'action'=>'caeAssignment'/*, $courseTypes*/, $action),array('class' =>" addBtn btn js-cae-add",'escape' => false, 'title'=>'Add')); ?>
</div>

<div class="caes index">

	<?php //pr($courseTypes); ?>
<div class="searchFrm">
<?php echo $this->Form->create('Student');?>
<div class="searchFrm col-sm-12">

	<?php echo $this->element('search_fields'); ?>
	<div class="col-lg-4">	 
	<?php
		echo $this->Form->button('<i class="ace-icon fa fa-check bigger-110"></i>'.__('Submit'),array('type'=>'button','id'=>'js-cae-display', 'name'=>'submit','value'=>'submit','class'=>'btn js-cae-display', 'onclick' => 'getCaeDisplay(this.id);'));
		echo "&nbsp;".$this->Form->button('<i class="ace-icon fa fa-undo bigger-110"></i>'.__('Reset'),array('type'=>'reset','name'=>'submit','value'=>'submit','class'=>'btn'));
	?>
	</div>
</div>
</div>
<div id="courses" class="caeDisplay"></div>
<?php echo $this->Form->end(); ?>

</div>

<?php echo $this->Html->script('common'); ?>

<span class='breadcrumb1'>
<span class='navbar-brand'><small>MARKS <i class='ace-icon fa fa-angle-double-right'></i></small></span>
<?php echo $this->Html->link("<span class='navbar-brand'><small> Project <i class='ace-icon fa fa-angle-double-right'></i></small></span>", array("controller"=>"ContinuousAssessmentExams",'action' => 'project'),array('data-placement'=>'left','escape' => false)); ?>
</span>