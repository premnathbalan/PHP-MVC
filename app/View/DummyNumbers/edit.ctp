<div class="dummyNumbers form">
<?php echo $this->Form->create('DummyNumber'); ?>
	<fieldset>
		<legend><?php echo __('Edit Dummy Number'); ?></legend>
	<?php
		echo $this->Form->input('id');
		echo $this->Form->input('timetable_id');
		echo $this->Form->input('start_range');
		echo $this->Form->input('created_by');
		echo $this->Form->input('modified_by');
	?>
	</fieldset>
<?php echo $this->Form->end(__('Submit')); ?>
</div>
<div class="actions">
	<h3><?php echo __('Actions'); ?></h3>
	<ul>

		<li><?php echo $this->Form->postLink(__('Delete'), array('action' => 'delete', $this->Form->value('DummyNumber.id')), array('confirm' => __('Are you sure you want to delete # %s?', $this->Form->value('DummyNumber.id')))); ?></li>
		<li><?php echo $this->Html->link(__('List Dummy Numbers'), array('action' => 'index')); ?></li>
	</ul>
</div>
