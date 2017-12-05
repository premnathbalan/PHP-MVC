<div class="attendances view">
<h2><?php echo __('Attendance'); ?></h2>
	<dl>
		<dt><?php echo __('Id'); ?></dt>
		<dd>
			<?php echo h($attendance['Attendance']['id']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Student'); ?></dt>
		<dd>
			<?php echo $this->Html->link($attendance['Student']['name'], array('controller' => 'students', 'action' => 'view', $attendance['Student']['id'])); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Batch'); ?></dt>
		<dd>
			<?php echo $this->Html->link($attendance['Batch']['batch_from'], array('controller' => 'batches', 'action' => 'view', $attendance['Batch']['id'])); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Month Year'); ?></dt>
		<dd>
			<?php echo $this->Html->link($attendance['MonthYear']['month_id'], array('controller' => 'month_years', 'action' => 'view', $attendance['MonthYear']['id'])); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Type'); ?></dt>
		<dd>
			<?php echo h($attendance['Attendance']['type']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Program'); ?></dt>
		<dd>
			<?php echo $this->Html->link($attendance['Program']['program_name'], array('controller' => 'programs', 'action' => 'view', $attendance['Program']['id'])); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Course'); ?></dt>
		<dd>
			<?php echo $this->Html->link($attendance['Course']['course_name'], array('controller' => 'courses', 'action' => 'view', $attendance['Course']['id'])); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('No Of Days'); ?></dt>
		<dd>
			<?php echo h($attendance['Attendance']['no_of_days']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Percentage'); ?></dt>
		<dd>
			<?php echo h($attendance['Attendance']['percentage']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Created By'); ?></dt>
		<dd>
			<?php echo h($attendance['Attendance']['created_by']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Modified By'); ?></dt>
		<dd>
			<?php echo h($attendance['Attendance']['modified_by']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Created'); ?></dt>
		<dd>
			<?php echo h($attendance['Attendance']['created']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Modified'); ?></dt>
		<dd>
			<?php echo h($attendance['Attendance']['modified']); ?>
			&nbsp;
		</dd>
	</dl>
</div>
<div class="actions">
	<h3><?php echo __('Actions'); ?></h3>
	<ul>
		<li><?php echo $this->Html->link(__('Edit Attendance'), array('action' => 'edit', $attendance['Attendance']['id'])); ?> </li>
		<li><?php echo $this->Form->postLink(__('Delete Attendance'), array('action' => 'delete', $attendance['Attendance']['id']), array('confirm' => __('Are you sure you want to delete # %s?', $attendance['Attendance']['id']))); ?> </li>
		<li><?php echo $this->Html->link(__('List Attendances'), array('action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Attendance'), array('action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Students'), array('controller' => 'students', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Student'), array('controller' => 'students', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Batches'), array('controller' => 'batches', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Batch'), array('controller' => 'batches', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Month Years'), array('controller' => 'month_years', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Month Year'), array('controller' => 'month_years', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Programs'), array('controller' => 'programs', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Program'), array('controller' => 'programs', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Courses'), array('controller' => 'courses', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Course'), array('controller' => 'courses', 'action' => 'add')); ?> </li>
	</ul>
</div>
