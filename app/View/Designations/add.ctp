<div class="designations form deptFrm">
	<legend><?php echo __('Designation Add'); ?></legend>
	<?php echo $this->Session->flash();?>
	<?php
		echo $this->Form->create('Designation', array('class'=>'js-form','success_url'=>$this->Html->url(array('controller'=>'Designations','action'=>'index'))));
		echo $this->Form->input('department_id',array("label"=>"Department <span class='ash'>*</span>","type" => "select", 'empty' => "-- Select Department --", 'options' => $departments));
		echo $this->Form->input('designation_name',array("label"=>"Designation Name <span class='ash'>*</span>"));
		
		echo $this->Form->end(__('Submit')); 
	?>
</div>
