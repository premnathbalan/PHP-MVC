<div class="studentAuthorizedBreaks index">
	<h2><?php echo __('Student Authorized Breaks'); ?></h2>
	<table cellpadding="0" cellspacing="0">
	<thead>
	<tr>
			<th><?php echo $this->Paginator->sort('id'); ?></th>
			<th><?php echo $this->Paginator->sort('student_id'); ?></th>
			<th><?php echo $this->Paginator->sort('semester_id'); ?></th>
			<th><?php echo $this->Paginator->sort('created_by'); ?></th>
			<th><?php echo $this->Paginator->sort('modified_by'); ?></th>
			<th><?php echo $this->Paginator->sort('created'); ?></th>
			<th><?php echo $this->Paginator->sort('modified'); ?></th>
			<th class="actions"><?php echo __('Actions'); ?></th>
	</tr>
	</thead>
	<tbody>
	<?php foreach ($studentAuthorizedBreaks as $studentAuthorizedBreak): ?>
	<tr>
		<td><?php echo h($studentAuthorizedBreak['StudentAuthorizedBreak']['id']); ?>&nbsp;</td>
		<td>
			<?php echo $this->Html->link($studentAuthorizedBreak['Student']['name'], array('controller' => 'students', 'action' => 'view', $studentAuthorizedBreak['Student']['id'])); ?>
		</td>
		<td>
			<?php echo $this->Html->link($studentAuthorizedBreak['Semester']['id'], array('controller' => 'semesters', 'action' => 'view', $studentAuthorizedBreak['Semester']['id'])); ?>
		</td>
		<td><?php echo h($studentAuthorizedBreak['StudentAuthorizedBreak']['created_by']); ?>&nbsp;</td>
		<td><?php echo h($studentAuthorizedBreak['StudentAuthorizedBreak']['modified_by']); ?>&nbsp;</td>
		<td><?php echo h($studentAuthorizedBreak['StudentAuthorizedBreak']['created']); ?>&nbsp;</td>
		<td><?php echo h($studentAuthorizedBreak['StudentAuthorizedBreak']['modified']); ?>&nbsp;</td>
		<td class="actions">
			<?php echo $this->Html->link(__('View'), array('action' => 'view', $studentAuthorizedBreak['StudentAuthorizedBreak']['id'])); ?>
			<?php echo $this->Html->link(__('Edit'), array('action' => 'edit', $studentAuthorizedBreak['StudentAuthorizedBreak']['id'])); ?>
			<?php echo $this->Form->postLink(__('Delete'), array('action' => 'delete', $studentAuthorizedBreak['StudentAuthorizedBreak']['id']), array('confirm' => __('Are you sure you want to delete # %s?', $studentAuthorizedBreak['StudentAuthorizedBreak']['id']))); ?>
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
		<li><?php echo $this->Html->link(__('New Student Authorized Break'), array('action' => 'add')); ?></li>
		<li><?php echo $this->Html->link(__('List Students'), array('controller' => 'students', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Student'), array('controller' => 'students', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Semesters'), array('controller' => 'semesters', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Semester'), array('controller' => 'semesters', 'action' => 'add')); ?> </li>
	</ul>
</div>
