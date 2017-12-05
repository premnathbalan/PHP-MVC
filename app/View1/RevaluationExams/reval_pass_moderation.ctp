<div class="searchFrm bgFrame1" style="margin-top:10px;">
	<div class="searchFrm col-sm-12">			
		<div class="col-lg-2">	
			After Revaluation	
			<?php echo $this->Form->input('ese_greater_than', array('type' => 'text', 'label' => "<span class='ash'>*</span> ESE >=", 'style'=>'margin-top:10px;width:50px;border-color:#000;color:#000;')); ?>
		</div>
		<div class="col-lg-2">		
			<?php echo $this->Form->input('ese_lesser_than', array('type' => 'text', 'label' => "<span class='ash'>*</span> ESE <=", 'style'=>'margin-top:10px;width:50px;border-color:#000;color:#000;')); ?>
		</div>
		<div class="col-lg-2">		
			<?php echo $this->Form->input('total_greater_than', array('type'=> 'text', 'label'=>'Total >=', 'style' => 'margin-top:10px;width:50px;border-color:#000;color:#000;', 'required'=>'required')); ?>
		</div>
		<div class="col-lg-3">		
			<?php echo $this->Form->input('total_lesser_than', array('type'=> 'text', 'label'=>'Total <=', 'style' => 'margin-top:10px;width:50px;border-color:#000;color:#000;', 'required'=>'required')); ?>
		</div>	
		<div class="col-lg-2">		
			<?php echo $this->Form->button('<i class="ace-icon fa fa-check bigger-110"></i>'.__('&nbsp;Submit&nbsp;&nbsp;'),array('type'=>'submit','name'=>'reval_pass','value'=>'fail_pass','class'=>'btn')); ?>
		</div>	
	</div>
</div>			
<?php echo $this->Html->script('common'); ?>

	