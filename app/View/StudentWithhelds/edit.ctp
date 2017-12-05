<div class="studentWithhelds form">
<?php echo $this->Form->create('StudentWithheld'); ?>
	<fieldset>
		<legend><?php echo __('Edit Student Withheld'); ?></legend>
	<?php
		echo $this->Form->input('id');
		echo $this->Form->input('student_id');
		echo $this->Form->input('withheld_id');
		echo $this->Form->input('month_year_id');
		echo $this->Form->input('indicator');
		echo $this->Form->input('created_by');
		echo $this->Form->input('modified_by');
	?>
	</fieldset>
<?php echo $this->Form->end(__('Submit')); ?>
</div>
<div class="actions">
	<h3><?php echo __('Actions'); ?></h3>
	<ul>

		<li><?php echo $this->Form->postLink(__('Delete'), array('action' => 'delete', $this->Form->value('StudentWithheld.id')), array('confirm' => __('Are you sure you want to delete # %s?', $this->Form->value('StudentWithheld.id')))); ?></li>
		<li><?php echo $this->Html->link(__('List Student Withhelds'), array('action' => 'index')); ?></li>
		<li><?php echo $this->Html->link(__('List Students'), array('controller' => 'students', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Student'), array('controller' => 'students', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Withhelds'), array('controller' => 'withhelds', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Withheld'), array('controller' => 'withhelds', 'action' => 'add')); ?> </li>
	</ul>
</div>
