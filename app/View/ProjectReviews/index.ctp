<div class="projectReviews index">
	<h2><?php echo __('Project Reviews'); ?></h2>
	<table cellpadding="0" cellspacing="0">
	<thead>
	<tr>
			<th><?php echo $this->Paginator->sort('id'); ?></th>
			<th><?php echo $this->Paginator->sort('cae_project_id'); ?></th>
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
	<?php foreach ($projectReviews as $projectReview): ?>
	<tr>
		<td><?php echo h($projectReview['ProjectReview']['id']); ?>&nbsp;</td>
		<td>
			<?php echo $this->Html->link($projectReview['CaeProject']['id'], array('controller' => 'cae_projects', 'action' => 'view', $projectReview['CaeProject']['id'])); ?>
		</td>
		<td>
			<?php echo $this->Html->link($projectReview['Student']['name'], array('controller' => 'students', 'action' => 'view', $projectReview['Student']['id'])); ?>
		</td>
		<td>
			<?php echo $this->Html->link($projectReview['MonthYear']['month_id'], array('controller' => 'month_years', 'action' => 'view', $projectReview['MonthYear']['id'])); ?>
		</td>
		<td><?php echo h($projectReview['ProjectReview']['marks']); ?>&nbsp;</td>
		<td><?php echo h($projectReview['ProjectReview']['created_by']); ?>&nbsp;</td>
		<td><?php echo h($projectReview['ProjectReview']['modified_by']); ?>&nbsp;</td>
		<td><?php echo h($projectReview['ProjectReview']['created']); ?>&nbsp;</td>
		<td><?php echo h($projectReview['ProjectReview']['modified']); ?>&nbsp;</td>
		<td class="actions">
			<?php echo $this->Html->link(__('View'), array('action' => 'view', $projectReview['ProjectReview']['id'])); ?>
			<?php echo $this->Html->link(__('Edit'), array('action' => 'edit', $projectReview['ProjectReview']['id'])); ?>
			<?php echo $this->Form->postLink(__('Delete'), array('action' => 'delete', $projectReview['ProjectReview']['id']), array('confirm' => __('Are you sure you want to delete # %s?', $projectReview['ProjectReview']['id']))); ?>
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
		<li><?php echo $this->Html->link(__('New Project Review'), array('action' => 'add')); ?></li>
		<li><?php echo $this->Html->link(__('List Cae Projects'), array('controller' => 'cae_projects', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Cae Project'), array('controller' => 'cae_projects', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Students'), array('controller' => 'students', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Student'), array('controller' => 'students', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Month Years'), array('controller' => 'month_years', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Month Year'), array('controller' => 'month_years', 'action' => 'add')); ?> </li>
	</ul>
</div>
