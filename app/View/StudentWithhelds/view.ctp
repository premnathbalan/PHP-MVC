<div class="studentWithhelds view">
<h2><?php echo __('Student Withheld'); ?></h2>
	<dl>
		<dt><?php echo __('Id'); ?></dt>
		<dd>
			<?php echo h($studentWithheld['StudentWithheld']['id']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Student'); ?></dt>
		<dd>
			<?php echo $this->Html->link($studentWithheld['Student']['name'], array('controller' => 'students', 'action' => 'view', $studentWithheld['Student']['id'])); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Withheld'); ?></dt>
		<dd>
			<?php echo $this->Html->link($studentWithheld['Withheld']['withheld_type'], array('controller' => 'withhelds', 'action' => 'view', $studentWithheld['Withheld']['id'])); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Month Year Id'); ?></dt>
		<dd>
			<?php echo h($studentWithheld['StudentWithheld']['month_year_id']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Indicator'); ?></dt>
		<dd>
			<?php echo h($studentWithheld['StudentWithheld']['indicator']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Created By'); ?></dt>
		<dd>
			<?php echo h($studentWithheld['StudentWithheld']['created_by']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Modified By'); ?></dt>
		<dd>
			<?php echo h($studentWithheld['StudentWithheld']['modified_by']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Created'); ?></dt>
		<dd>
			<?php echo h($studentWithheld['StudentWithheld']['created']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Modified'); ?></dt>
		<dd>
			<?php echo h($studentWithheld['StudentWithheld']['modified']); ?>
			&nbsp;
		</dd>
	</dl>
</div>
<div class="actions">
	<h3><?php echo __('Actions'); ?></h3>
	<ul>
		<li><?php echo $this->Html->link(__('Edit Student Withheld'), array('action' => 'edit', $studentWithheld['StudentWithheld']['id'])); ?> </li>
		<li><?php echo $this->Form->postLink(__('Delete Student Withheld'), array('action' => 'delete', $studentWithheld['StudentWithheld']['id']), array('confirm' => __('Are you sure you want to delete # %s?', $studentWithheld['StudentWithheld']['id']))); ?> </li>
		<li><?php echo $this->Html->link(__('List Student Withhelds'), array('action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Student Withheld'), array('action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Students'), array('controller' => 'students', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Student'), array('controller' => 'students', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Withhelds'), array('controller' => 'withhelds', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Withheld'), array('controller' => 'withhelds', 'action' => 'add')); ?> </li>
	</ul>
</div>
