<div class="revaluationDummyMarks form">
<?php echo $this->Form->create('RevaluationDummyMark'); ?>
	<fieldset>
		<legend><?php echo __('Add Revaluation Dummy Mark'); ?></legend>
	<?php
		echo $this->Form->input('dummy_number_id');
		echo $this->Form->input('dummy_number');
		echo $this->Form->input('mark_entry1');
		echo $this->Form->input('mark_entry2');
		echo $this->Form->input('indicator');
		echo $this->Form->input('mark1_created_by');
		echo $this->Form->input('mark2_created_by');
		echo $this->Form->input('modified_by');
		echo $this->Form->input('created2');
	?>
	</fieldset>
<?php echo $this->Form->end(__('Submit')); ?>
</div>
<div class="actions">
	<h3><?php echo __('Actions'); ?></h3>
	<ul>

		<li><?php echo $this->Html->link(__('List Revaluation Dummy Marks'), array('action' => 'index')); ?></li>
		<li><?php echo $this->Html->link(__('List Dummy Numbers'), array('controller' => 'dummy_numbers', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Dummy Number'), array('controller' => 'dummy_numbers', 'action' => 'add')); ?> </li>
	</ul>
</div>
