<div class="typeOfCertifications form deptFrm"> 
	<?php echo $this->Session->flash();?>
	<legend><?php echo __('Add Type Of Certification'); ?></legend>
	<?php
	    echo $this->Form->create('TypeOfCertification', array('class'=>'js-form','success_url'=>$this->Html->url(array('controller'=>'TypeOfCertifications','action'=>'index'))));
		echo $this->Form->input('certification');
		echo $this->Form->input('short_code');
	?>
<?php echo $this->Form->end(__('Submit')); ?>
</div>