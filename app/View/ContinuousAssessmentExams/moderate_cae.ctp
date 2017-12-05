<div class="searchFrm">
<?php

//$options = array('Batch'=>'Batch','MonthYear'=>'MonthYear');
//echo $this->Form->radio('batchMonth', $options, array('legend'=>false, 'onclick' => 'toggleOption(this.value)', 'class' => 'js-mod-option', 'name'=>'js-option'));
?>
<div class="searchFrm col-sm-12 bgFrame1">
	<div class="col-lg-4">
		<?php echo $this->Form->input('monthYear', array('label' => 'MonthYear', 'empty' => __("----- Select Month Year-----"), 'class' => 'js-mod-monthyear')); ?>
	</div>
	<div class="col-lg-4">
		<?php echo $this->Form->input('batch_id', array('label' => 'Batch', 'empty' => __("----- Select Batch-----"), 'class' => 'js-mod-batch')); ?>
	</div>
	<div class="col-lg-4">
		<div id='program' class='program'>
			<?php echo $this->Form->input('program_id', array('label'=>'Specialisation','type' => 'select', 'empty' => __("----- Select Specialisation-----"), 'class' => 'js-mod-program')); ?>
		</div>
	</div>
	<div class="col-lg-4">	
		<?php echo $this->Form->input('from', array('label' => "&#62;&#61;<span class='ash'>*</span>", 'class' => 'js-mod-from','style'=>'valign:top;')); ?>
	</div>
	<div class="col-lg-4">
		<?php echo $this->Form->input('to', array('label' => "&#60;&#61;<span class='ash'>*</span>", 'class' => 'js-mod-to','style'=>'valign:top;')); ?>
	</div>
	<!--<div class="col-lg-4">
		<?php	
		echo $this->Form->input('numVal', array('label' => 'Moderation Value', 'class' => 'js-mod-num'));
		?>
	</div>-->
	<div class="col-lg-4">
		<?php
		echo $this->Form->button('<i class="ace-icon fa fa-check bigger-110"></i>'.__('Search'),array('type'=>'button','name'=>'submit','value'=>'submit','class'=>'btn js-mod-cae'));
		?>
	</div>
</div>
</div>
<div id='js-moderate-cae'>

</div>

<script>
//$(".js-mod-program, .js-mod-batch, .js-mod-monthyear").attr("disabled", true); 
</script>

<span class='breadcrumb1'>
<span class='navbar-brand'><small>MARKS <i class='ace-icon fa fa-angle-double-right'></i></small></span>
<span class='navbar-brand'><small>C.A.E <i class='ace-icon fa fa-angle-double-right'></i></small></span>
<?php echo $this->Html->link("<span class='navbar-brand'><small> Moderate <i class='ace-icon fa fa-angle-double-right'></i></small></span>", array("controller"=>"ContinuousAssessmentExams",'action' => 'moderateCae'),array('data-placement'=>'left','escape' => false)); ?>
</span>