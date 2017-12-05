<div class="collegeReferences form userFrm">
	<?php echo $this->Session->flash();?>
	<legend><?php echo __('College Add'); ?></legend>
		
<?php	
	echo $this->Form->create('CollegeReference', array('class'=>'js-form','success_url'=>$this->Html->url(array('controller'=>'CollegeReferences','action'=>'index')))); 
	echo $this->Form->input('college_name',array("label"=>"College Name <span class='ash'>*</span>"));
	echo $this->Form->input('address1');
	echo $this->Form->input('address2');	
	echo $this->Form->input('address3');
	echo $this->Form->input('city');
	echo $this->Form->input('pincode');
	echo $this->Form->input('phone_number');
	?>
	
<?php echo $this->Form->end(__('Submit')); ?>
</div>