<div class="faculties form  deptFrm">
	<legend><?php echo __('Faculty Add'); ?></legend>
	
	<?php
	echo $this->Session->flash(); 
	echo $this->Form->create('Faculty', array('class'=>'js-form','success_url'=>$this->Html->url(array('controller'=>'Faculties','action'=>'index'))));
	
	echo $this->Form->input('faculty_name',array("label"=>"Faculty Name <span class='ash'>*</span>"));
    ?>
	<div class="col-sm-12 baminiImg">	
		<?php echo $this->Form->input("faculty_name_tamil", array('label' => "&nbsp;&nbsp;&nbsp;Faculty Name Tamil <span class='ash'>*</span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;",'style'=>'font-family:Bamini;height:24px;','div'=>false));
		echo $this->Html->image("bamini.png", array('type'=>'image'));
		?>
	</div>	
	<?php	
	echo $this->Form->end(__('Submit')); 
	?>
</div>