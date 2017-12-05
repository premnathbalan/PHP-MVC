<div class="studentMarks index">
	<h2><?php echo __('Student Marks'); ?></h2>
	<table cellpadding="0" cellspacing="0">
	<thead>
	<tr>
			<th><?php echo $this->Paginator->sort('id'); ?></th>
			<th><?php echo $this->Paginator->sort('month_year_id'); ?></th>
			<th><?php echo $this->Paginator->sort('student_id'); ?></th>
			<th><?php echo $this->Paginator->sort('course_mapping_id'); ?></th>
			<th><?php echo $this->Paginator->sort('marks'); ?></th>
			<th><?php echo $this->Paginator->sort('status'); ?></th>
			<th><?php echo $this->Paginator->sort('created_by'); ?></th>
			<th><?php echo $this->Paginator->sort('modified_by'); ?></th>
			<th><?php echo $this->Paginator->sort('created'); ?></th>
			<th><?php echo $this->Paginator->sort('modified'); ?></th>
			<th class="actions"><?php echo __('Actions'); ?></th>
	</tr>
	</thead>
	<tbody>
	<?php foreach ($studentMarks as $studentMark): ?>
	<tr>
		<td><?php echo h($studentMark['StudentMark']['id']); ?>&nbsp;</td>
		<td>
			<?php echo $this->Html->link($studentMark['MonthYear']['month_id'], array('controller' => 'month_years', 'action' => 'view', $studentMark['MonthYear']['id'])); ?>
		</td>
		<td>
			<?php echo $this->Html->link($studentMark['Student']['name'], array('controller' => 'students', 'action' => 'view', $studentMark['Student']['id'])); ?>
		</td>
		<td>
			<?php echo $this->Html->link($studentMark['CourseMapping']['program_id'], array('controller' => 'course_mappings', 'action' => 'view', $studentMark['CourseMapping']['id'])); ?>
		</td>
		<td><?php echo h($studentMark['StudentMark']['marks']); ?>&nbsp;</td>
		<td><?php echo h($studentMark['StudentMark']['status']); ?>&nbsp;</td>
		<td><?php echo h($studentMark['StudentMark']['created_by']); ?>&nbsp;</td>
		<td><?php echo h($studentMark['StudentMark']['modified_by']); ?>&nbsp;</td>
		<td><?php echo h($studentMark['StudentMark']['created']); ?>&nbsp;</td>
		<td><?php echo h($studentMark['StudentMark']['modified']); ?>&nbsp;</td>
		<td class="actions">
			<?php echo $this->Html->link(__('View'), array('action' => 'view', $studentMark['StudentMark']['id'])); ?>
			<?php echo $this->Html->link(__('Edit'), array('action' => 'edit', $studentMark['StudentMark']['id'])); ?>
			<?php echo $this->Form->postLink(__('Delete'), array('action' => 'delete', $studentMark['StudentMark']['id']), array('confirm' => __('Are you sure you want to delete # %s?', $studentMark['StudentMark']['id']))); ?>
		</td>
	</tr>
<?php endforeach; ?>
	</tbody>
	</table>
	<p>
	<?php
	echo $this->Paginator->counter(array(
		'format' => __('Page {:page} of {:pages}, showing {:current} records out of {:count} total, starting on record {:start}, ending on {:end}')
	));
	?>	</p>
	<div class="paging">
	<?php
		echo $this->Paginator->prev('< ' . __('previous'), array(), null, array('class' => 'prev disabled'));
		echo $this->Paginator->numbers(array('separator' => ''));
		echo $this->Paginator->next(__('next') . ' >', array(), null, array('class' => 'next disabled'));
	?>
	</div>
</div>
<div class="actions">
	<h3><?php echo __('Actions'); ?></h3>
	<ul>
		<li><?php echo $this->Html->link(__('New Student Mark'), array('action' => 'add')); ?></li>
		<li><?php echo $this->Html->link(__('List Month Years'), array('controller' => 'month_years', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Month Year'), array('controller' => 'month_years', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Students'), array('controller' => 'students', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Student'), array('controller' => 'students', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Course Mappings'), array('controller' => 'course_mappings', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Course Mapping'), array('controller' => 'course_mappings', 'action' => 'add')); ?> </li>
	</ul>
</div>
