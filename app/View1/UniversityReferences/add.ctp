<div class="universityReferences form deptFrm">
	<?php echo $this->Session->flash();?>
	<legend><?php echo __('University Reference Add'); ?></legend>
	<?php
		echo $this->Form->create('UniversityReference', array('class'=>'js-form','success_url'=>$this->Html->url(array('controller'=>'UniversityReferences','action'=>'index'))));
		echo $this->Form->input('university_name',array("label"=>"University Name <span class='ash'>*</span>"));
	?>
<?php echo $this->Form->end(__('Submit')); ?>
</div>