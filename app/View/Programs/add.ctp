<div class="programs form deptFrm">
	<?php echo $this->Session->flash();?>
	<legend><?php echo __('Specialisation Add'); ?></legend>
	
	<?php
		echo $this->Form->create('Program', array('class'=>'js-form','success_url'=>$this->Html->url(array('controller'=>'programs','action'=>'index')))); 
		echo $this->Form->input('academic_id',array("label"=>"Program <span class='ash'>*</span>","type" => "select", "empty" => " - Select Program -"));
		echo $this->Form->input('program_name',array("label"=>"Specialisation Name <span class='ash'>*</span>"));
		echo $this->Form->input('faculty_id',array("label"=>"Faculty <span class='ash'>*</span>","type" => "select", 'empty' => "-- Select Faculty --", 'options' => $faculty));
		echo $this->Form->input('short_code',array("label"=>"Short Code <span class='ash'>*</span>"));		
		echo $this->Form->input('semester_id',array("label"=>"Semester <span class='ash'>*</span>","type" => "select", "empty" => " - Select Semester -", 'options' => $semesters));
	?>
	<div class="col-sm-12 baminiImg">	
		<?php echo $this->Form->input("program_name_tamil", array('label' => "&nbsp;&nbsp;&nbsp;Specialisation Name Tamil <span class='ash'>*</span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;",'style'=>'font-family:Bamini;height:24px;','div'=>false));
		echo $this->Html->image("bamini.png", array('type'=>'image'));
		?>
	</div>	
	<?php	
		echo $this->Form->input('credits',array("label"=>"Credit <span class='ash'>*</span>"));
		echo $this->Form->input('alternate_name',array("label"=>"Alternative Name <span class='ash'>*</span>"));
	?>
	<?php echo $this->Form->end(__('Submit')); ?>
</div>