	<div class="col-lg-3">		
		<?php echo $this->Form->input('total_from', array('type' => 'text', 'label' => "<span class='ash'>*</span> CAE >=", 'class' => 'js-total-from', 'style'=>'margin-top:10px;width:50px;border:1px solid grey;')); ?>
	</div>
	<div class="col-lg-3">		
		<?php echo $this->Form->input('total_to', array('type' => 'text', 'label' => "<span class='ash'>*</span> CAE <=", 'class' => 'js-total-to', 'style'=>'margin-top:10px;width:50px;border:1px solid grey;')); ?>
	</div>
	<div class="col-lg-3">		
		<?php echo $this->Form->input('ese_greater_than', array('type'=> 'text', 'label'=>'ESE >=', 'style' => 'width:50px;border:1px solid grey;', 'required'=>'required')); ?>
	</div>
	<div class="col-lg-3">		
		<?php echo $this->Form->input('ese_lesser_than', array('type'=> 'text', 'label'=>'ESE <=', 'style' => 'width:50px;border:1px solid grey;', 'required'=>'required')); ?>
	</div>		
<?php echo $this->Html->script('common'); ?>			