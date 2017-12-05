<div class="searchFrm bgFrame1" style="margin-top:10px;">
	<div class="searchFrm col-sm-12">
		<div class="col-lg-3">
			<?php echo $this->Form->input('diff_from', array('type'=>'text', 'label' => 'DiffFrom', 'required'=>'required', 'style'=>'border-color:#000;width:70px;color:#000;margin-top:8px;')); ?>
		</div>
		<div class="col-lg-3">
			<?php echo $this->Form->input('diff_to', array('type'=>'text', 'label' => 'DiffTo', 'required'=>'required', 'style'=>'border-color:#000;width:70px;color:#000;margin-top:8px;')); ?>
		</div>
		<div class="col-lg-3">
			<?php echo $this->Form->input('adjust_to', array('type'=>'text', 'label' => 'AdjustTo', 'required'=>'required', 'style'=>'border-color:#000;width:70px;color:#000;margin-top:8px;')); ?>
		</div>
		<div class="col-lg-3">
			<?php echo $this->Form->button('<i class="ace-icon fa fa-check bigger-110"></i>'.__('&nbsp;Apply&nbsp;&nbsp;'),array('type'=>'submit','name'=>'reval_apply','value'=>'get','class'=>'btn js-reval-adjust')); ?>
		</div>
	</div>
</div>
<?php echo $this->Html->script('common'); ?>
<script>leftMenuSelection('Dummy Marks Moderation');</script>

<span class='breadcrumb1'>
<span class='navbar-brand'><small>REVALUATION <i class='ace-icon fa fa-angle-double-right'></i></small></span>
<?php echo $this->Html->link("<span class='navbar-brand'><small>Moderation <i class='ace-icon fa fa-angle-double-right'></i></small></span>", array("controller"=>"RevaluationExams",'action' => 'moderation'),array('data-placement'=>'left','escape' => false)); ?>
</span>