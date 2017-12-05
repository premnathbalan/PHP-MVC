<div class="revaluationDummyMarks index">
	<h2><?php echo __('Revaluation Dummy Marks'); ?></h2>
	<table cellpadding="0" cellspacing="0">
	<thead>
	<tr>
			<th><?php echo $this->Paginator->sort('id'); ?></th>
			<th><?php echo $this->Paginator->sort('dummy_number_id'); ?></th>
			<th><?php echo $this->Paginator->sort('dummy_number'); ?></th>
			<th><?php echo $this->Paginator->sort('mark_entry1'); ?></th>
			<th><?php echo $this->Paginator->sort('mark_entry2'); ?></th>
			<th><?php echo $this->Paginator->sort('indicator'); ?></th>
			<th><?php echo $this->Paginator->sort('mark1_created_by'); ?></th>
			<th><?php echo $this->Paginator->sort('mark2_created_by'); ?></th>
			<th><?php echo $this->Paginator->sort('modified_by'); ?></th>
			<th><?php echo $this->Paginator->sort('created'); ?></th>
			<th><?php echo $this->Paginator->sort('created2'); ?></th>
			<th><?php echo $this->Paginator->sort('modified'); ?></th>
			<th class="actions"><?php echo __('Actions'); ?></th>
	</tr>
	</thead>
	<tbody>
	<?php foreach ($revaluationDummyMarks as $revaluationDummyMark): ?>
	<tr>
		<td><?php echo h($revaluationDummyMark['RevaluationDummyMark']['id']); ?>&nbsp;</td>
		<td>
			<?php echo $this->Html->link($revaluationDummyMark['DummyNumber']['id'], array('controller' => 'dummy_numbers', 'action' => 'view', $revaluationDummyMark['DummyNumber']['id'])); ?>
		</td>
		<td><?php echo h($revaluationDummyMark['RevaluationDummyMark']['dummy_number']); ?>&nbsp;</td>
		<td><?php echo h($revaluationDummyMark['RevaluationDummyMark']['mark_entry1']); ?>&nbsp;</td>
		<td><?php echo h($revaluationDummyMark['RevaluationDummyMark']['mark_entry2']); ?>&nbsp;</td>
		<td><?php echo h($revaluationDummyMark['RevaluationDummyMark']['indicator']); ?>&nbsp;</td>
		<td><?php echo h($revaluationDummyMark['RevaluationDummyMark']['mark1_created_by']); ?>&nbsp;</td>
		<td><?php echo h($revaluationDummyMark['RevaluationDummyMark']['mark2_created_by']); ?>&nbsp;</td>
		<td><?php echo h($revaluationDummyMark['RevaluationDummyMark']['modified_by']); ?>&nbsp;</td>
		<td><?php echo h($revaluationDummyMark['RevaluationDummyMark']['created']); ?>&nbsp;</td>
		<td><?php echo h($revaluationDummyMark['RevaluationDummyMark']['created2']); ?>&nbsp;</td>
		<td><?php echo h($revaluationDummyMark['RevaluationDummyMark']['modified']); ?>&nbsp;</td>
		<td class="actions">
			<?php echo $this->Html->link(__('View'), array('action' => 'view', $revaluationDummyMark['RevaluationDummyMark']['id'])); ?>
			<?php echo $this->Html->link(__('Edit'), array('action' => 'edit', $revaluationDummyMark['RevaluationDummyMark']['id'])); ?>
			<?php echo $this->Form->postLink(__('Delete'), array('action' => 'delete', $revaluationDummyMark['RevaluationDummyMark']['id']), array('confirm' => __('Are you sure you want to delete # %s?', $revaluationDummyMark['RevaluationDummyMark']['id']))); ?>
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
		<li><?php echo $this->Html->link(__('New Revaluation Dummy Mark'), array('action' => 'add')); ?></li>
		<li><?php echo $this->Html->link(__('List Dummy Numbers'), array('controller' => 'dummy_numbers', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Dummy Number'), array('controller' => 'dummy_numbers', 'action' => 'add')); ?> </li>
	</ul>
</div>
