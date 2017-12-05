<div class="typeOfCertifications form deptFrm">

<?php echo $this->Form->create('TypeOfCertification', array('class'=>'js-form','success_url'=>$this->Html->url(array('controller'=>'TypeOfCertifications','action'=>'index')))); ?>
		<legend><?php echo __('Edit Type Of Certification'); ?></legend>
		<div><?php echo $this->Session->flash();?></div>
	<?php
		echo $this->Form->input('id');
		echo $this->Form->input('certification');
		echo $this->Form->input('short_code');
	?>
<?php echo $this->Form->end(__('Submit')); ?>
</div>