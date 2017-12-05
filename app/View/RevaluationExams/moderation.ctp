<?php echo $this->Form->create('RevaluationExam');?>
<span id="errormsg" style="align:center;color:14px;"></span>
<div class="searchFrm bgFrame1">
	<div class="searchFrm col-sm-12">
		<div class="col-lg-3">
			<?php echo $this->Form->input('month_year_id', array('label' => 'MonthYear', 'options'=>$monthYears, 'empty' => __("- Month Year -"), 'class' => 'js-month-year', 'required'=>'required', 'style'=>'border-color:#000;width:100px;color:#000;')); ?>
		</div>
		<div class="col-lg-4">
			<?php echo $this->Form->input('option', array('type' => 'select', 'options'=>array("Pass"=>"Applied for Improvement", "Fail"=>"Applied for Pass"), 'empty'=>'- Applied for -', 'label' => 'Applied for', 'class' => 'js-reval-moderation', 'style'=>'margin-top:10px;border-color:#000;width:150px;color:#000;')); ?>
		</div>
		<div class="col-lg-5">
			<span id="failedOption"></span>
			<?php echo $this->Form->button('<i class="ace-icon fa fa-check bigger-110"></i>'.__('&nbsp;Display&nbsp;&nbsp;'),array('type'=>'button','name'=>'submit','value'=>'get','class'=>'btn js-reval-display')); ?>
		</div>
	</div>
</div>
<div id="diffOption">
</div>
<div id="revalResult">
</div>
<?php echo $this->Form->end(); ?>

<?php echo $this->Html->script('common'); ?>

<script>leftMenuSelection('Dummy Marks Moderation');</script>

<span class='breadcrumb1'>
<span class='navbar-brand'><small>REVALUATION <i class='ace-icon fa fa-angle-double-right'></i></small></span>
<?php echo $this->Html->link("<span class='navbar-brand'><small>Moderation <i class='ace-icon fa fa-angle-double-right'></i></small></span>", array("controller"=>"RevaluationExams",'action' => 'moderation'),array('data-placement'=>'left','escape' => false)); ?>
</span>