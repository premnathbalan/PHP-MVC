<div class="courses form deptFrm">
<?php echo $this->Form->create('Course', array('class'=>'js-form','success_url'=>$this->Html->url(array('controller'=>'Courses','action'=>'index')))); ?>
	<legend><?php echo __('Edit Course'); ?></legend>
	<?php
		echo $this->Form->input('id');
		echo $this->Form->input("course_name", array("label"=>"Course Name <span class='ash'>*</span>", 'placeholder' => 'Course Name'));
		echo $this->Form->input("course_code", array("label"=>"Course Code <span class='ash'>*</span>", 'placeholder' => 'Course Code'));
		echo $this->Form->input("common_code", array("label"=>"Common Code <span class='ash'>*</span>", 'placeholder' => 'Common Code'));
		echo $this->Form->input("board", array("label"=>"Board <span class='ash'>*</span>", 'placeholder' => 'Board'));
		echo $this->Form->input('course_type_id', array("label"=>"Course Category<span class='ash'>*</span>",'type' => 'select', 'options' => $courseTypes, 'empty' => '- Select Course Category -'));
		echo $this->Form->input("credit_point", array("label"=>"Credit Point <span class='ash'>*</span>", 'placeholder' => 'Credit Point'));
		echo $this->Form->input('course_max_marks', array("label"=>"Course Max Marks", 'placeholder' => 'Course Max Marks'));
		echo $this->Form->input('min_cae_mark', array("label"=>"Min CAE Mark (%)", 'placeholder' => 'Min CAE Mark'));
		echo $this->Form->input('min_ese_mark', array("label"=>"Min ESE Mark (%)", 'placeholder' => 'Min ESE Mark'));
		echo $this->Form->input('max_cae_mark', array("label"=>"Max CAE Mark", 'placeholder' => 'Max CAE Mark'));
		echo $this->Form->input('max_ese_mark', array("label"=>"Max ESE Mark", 'placeholder' => 'Max ESE Mark'));
		echo $this->Form->input('max_ese_qp_mark', array("label"=>"ESE QP Mark", 'placeholder' => 'ESE Question Paper Mark'));
		echo $this->Form->input('total_min_pass', array("label"=>"Total Min Pass Mark (%)", 'placeholder' => 'Total Min Pass Mark'));
		echo $this->Form->end(__('Submit')); ?>
</div>