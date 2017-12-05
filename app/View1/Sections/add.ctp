<div class="sections form deptFrm">
	<?php echo $this->Session->flash();?>
	<legend><?php echo __('Section Add'); ?></legend>
	<?php
		echo $this->Form->create('Section', array('class'=>'js-form','success_url'=>$this->Html->url(array('controller'=>'Sections','action'=>'index'))));	
		echo $this->Form->input('name',array("label"=>"Section Name<span class='ash'>*</span>",'type' => 'text'));
	?>
	<?php echo $this->Form->end(__('Submit')); ?>
<div>