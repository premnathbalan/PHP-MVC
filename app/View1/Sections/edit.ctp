<div class="batches form deptFrm">
	<legend><?php echo __('Edit Section'); ?></legend>
	
	<div><?php echo $this->Session->flash();?></div>
	
<?php echo $this->Form->create('Section', array('class'=>'js-form','success_url'=>$this->Html->url(array('controller'=>'Sections','action'=>'index')))); ?>
		
	<?php
		echo $this->Form->input('id');
		echo $this->Form->input('name',array("label"=>"Section Name<span class='ash'>*</span>",'type' => 'text'));
	?>
<?php echo $this->Form->end(__('Submit')); ?>
</div>