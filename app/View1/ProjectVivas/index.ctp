<div class="projectVivas index">
	<h2><?php echo __('Project Vivas'); ?></h2>
	<table cellpadding="0" cellspacing="0">
	<thead>
	<tr>
			<th><?php echo $this->Paginator->sort('id'); ?></th>
			<th><?php echo $this->Paginator->sort('ese_project_id'); ?></th>
			<th><?php echo $this->Paginator->sort('student_id'); ?></th>
			<th><?php echo $this->Paginator->sort('month_year_id'); ?></th>
			<th><?php echo $this->Paginator->sort('marks'); ?></th>
			<th><?php echo $this->Paginator->sort('created_by'); ?></th>
			<th><?php echo $this->Paginator->sort('modified_by'); ?></th>
			<th><?php echo $this->Paginator->sort('created'); ?></th>
			<th><?php echo $this->Paginator->sort('modified'); ?></th>
			<th class="actions"><?php echo __('Actions'); ?></th>
	</tr>
	</thead>
	<tbody>
	<?php foreach ($projectVivas as $projectViva): ?>
	<tr>
		<td><?php echo h($projectViva['ProjectViva']['id']); ?>&nbsp;</td>
		<td>
			<?php echo $this->Html->link($projectViva['EseProject']['course_mapping_id'], array('controller' => 'ese_projects', 'action' => 'view', $projectViva['EseProject']['id'])); ?>
		</td>
		<td>
			<?php echo $this->Html->link($projectViva['Student']['name'], array('controller' => 'students', 'action' => 'view', $projectViva['Student']['id'])); ?>
		</td>
		<td>
			<?php echo $this->Html->link($projectViva['MonthYear']['month_id'], array('controller' => 'month_years', 'action' => 'view', $projectViva['MonthYear']['id'])); ?>
		</td>
		<td><?php echo h($projectViva['ProjectViva']['marks']); ?>&nbsp;</td>
		<td><?php echo h($projectViva['ProjectViva']['created_by']); ?>&nbsp;</td>
		<td><?php echo h($projectViva['ProjectViva']['modified_by']); ?>&nbsp;</td>
		<td><?php echo h($projectViva['ProjectViva']['created']); ?>&nbsp;</td>
		<td><?php echo h($projectViva['ProjectViva']['modified']); ?>&nbsp;</td>
		<td class="actions">
			<?php echo $this->Html->link(__('View'), array('action' => 'view', $projectViva['ProjectViva']['id'])); ?>
			<?php echo $this->Html->link(__('Edit'), array('action' => 'edit', $projectViva['ProjectViva']['id'])); ?>
			<?php echo $this->Form->postLink(__('Delete'), array('action' => 'delete', $projectViva['ProjectViva']['id']), array('confirm' => __('Are you sure you want to delete # %s?', $projectViva['ProjectViva']['id']))); ?>
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
		<li><?php echo $this->Html->link(__('New Project Viva'), array('action' => 'add')); ?></li>
		<li><?php echo $this->Html->link(__('List Ese Projects'), array('controller' => 'ese_projects', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Ese Project'), array('controller' => 'ese_projects', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Students'), array('controller' => 'students', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Student'), array('controller' => 'students', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Month Years'), array('controller' => 'month_years', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Month Year'), array('controller' => 'month_years', 'action' => 'add')); ?> </li>
	</ul>
</div>
