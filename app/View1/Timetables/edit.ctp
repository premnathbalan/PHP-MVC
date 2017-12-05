<div class="timetables form">
<?php echo $this->Form->create('Timetable'); ?>
	<fieldset>
		<legend><?php echo __('Edit Timetable'); ?></legend>
	<?php
		echo $this->Form->input('id');
		echo $this->Form->input('month_year_id');
		echo $this->Form->input('course_mapping_id');
		echo $this->Form->input('exam_date');
		echo $this->Form->input('exam_session');
		echo $this->Form->input('exam_type');
		echo $this->Form->input('created_by');
		echo $this->Form->input('modified_by');
	?>
	</fieldset>
<?php echo $this->Form->end(__('Submit')); ?>
</div>
<div class="actions">
	<h3><?php echo __('Actions'); ?></h3>
	<ul>

		<li><?php echo $this->Form->postLink(__('Delete'), array('action' => 'delete', $this->Form->value('Timetable.id')), array('confirm' => __('Are you sure you want to delete # %s?', $this->Form->value('Timetable.id')))); ?></li>
		<li><?php echo $this->Html->link(__('List Timetables'), array('action' => 'index')); ?></li>
	</ul>
</div>
