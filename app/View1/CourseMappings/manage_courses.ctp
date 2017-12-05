<?php //echo $this->Form->create('CourseMapping'); ?>
	<fieldset>
		<legend><?php echo __('Add Course Mapping'); ?></legend>
	<?php
		/*echo $this->Form->input('program_id');
		echo $this->Form->input('course_id');
		echo $this->Form->input('course_mode_id');
		echo $this->Form->input('semester_id');*/
	?>
	</fieldset>
<?php //echo $this->Form->end(__('Submit')); ?>
</div>
<div class="searchFrm">
<?php 
//echo $this->Form->input('batch_id', array('options' => $batches, 'label' => false, 'empty' => __("----- Select Batch-----")));
echo $this->Form->input('program_id', array('options' => $batchProgram, 'label' => false, 'empty' => __("----- Select DataSet-----"), 'class' => 'js-data-set'));
?>
</div>
<div id="semesters"></div>
<?php
echo $this->Html->script('common');

?>