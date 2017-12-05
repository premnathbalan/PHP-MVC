<div class="searchFrm bgFrame1" style="margin-top:10px;">
	<div class="searchFrm col-sm-12">
		<div class="col-lg-2">
			BeforeRevaluation
		</div>
		<div class="col-lg-4">		
			<?php echo $this->Form->input('ese_diff_greater_than_br', array('type' => 'text', 'label' => "<span class='ash'>*</span> ESE >=", 'style'=>'margin-top:10px;width:50px;border-color:#000;color:#000;')); ?>		
			<?php echo $this->Form->input('ese_diff_lesser_than_br', array('type' => 'text', 'label' => "<span class='ash'>*</span> ESE <=", 'style'=>'margin-top:10px;width:50px;border-color:#000;color:#000;')); ?>
		</div>
		<div class="col-lg-4">
			<?php echo $this->Form->input('total_diff_greater_than_br', array('type' => 'text', 'label' => "<span class='ash'>*</span> Total >=", 'style'=>'margin-top:10px;width:50px;border-color:#000;color:#000;')); ?>		
			<?php echo $this->Form->input('total_diff_lesser_than_br', array('type' => 'text', 'label' => "<span class='ash'>*</span> Total <=", 'style'=>'margin-top:10px;width:50px;border-color:#000;color:#000;')); ?>
		</div>
		<div class="col-lg-2">		
			<?php echo $this->Form->button('<i class="ace-icon fa fa-check bigger-110"></i>'.__('&nbsp;Display&nbsp;&nbsp;'),array('type'=>'button','name'=>'reval_fail_br','value'=>'br','class'=>'btn js-before-reval-display',"onclick"=>"displayBeforeRevaluationResult(this.value);")); ?>
		</div>	
	</div>
</div>

<div class="searchFrm bgFrame1" style="margin-top:10px;">
	<div class="searchFrm col-sm-12">
		<div class="col-lg-2">
			AfterRevaluation
		</div>
		<div class="col-lg-4">		
			<?php echo $this->Form->input('ese_diff_greater_than_ar', array('type'=> 'text', 'label'=>'New ESE >=', 'style' => 'margin-top:10px;width:50px;border-color:#000;color:#000;')); ?>		
			<?php echo $this->Form->input('ese_diff_lesser_than_ar', array('type'=> 'text', 'label'=>'New ESE <=', 'style' => 'margin-top:10px;width:50px;border-color:#000;color:#000;')); ?>
		</div>
		<div class="col-lg-4">
			<?php echo $this->Form->input('total_diff_greater_than_ar', array('type'=> 'text', 'label'=>'New Total >=', 'style' => 'margin-top:10px;width:50px;border-color:#000;color:#000;')); ?>		
			<?php echo $this->Form->input('total_diff_lesser_than_ar', array('type'=> 'text', 'label'=>'New Total <=', 'style' => 'margin-top:10px;width:50px;border-color:#000;color:#000;')); ?>
		</div>	
		<div class="col-lg-2">		
			<?php echo $this->Form->button('<i class="ace-icon fa fa-check bigger-110"></i>'.__('&nbsp;Display&nbsp;&nbsp;'),array('type'=>'button','name'=>'reval_fail_ar','value'=>'ar','class'=>'btn',"onclick"=>"displayAfterRevaluationResult(this.value);")); ?>
		</div>
	</div>
</div>

<?php echo $this->Html->script('common'); ?>

	