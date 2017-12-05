<div class="phdStudents index">
	<h2><?php echo __('Phd Students'); ?></h2>
	<table cellpadding="0" cellspacing="0">
	<thead>
	<tr>
			<th><?php echo $this->Paginator->sort('id'); ?></th>
			<th><?php echo $this->Paginator->sort('name'); ?></th>
			<th><?php echo $this->Paginator->sort('birth_date'); ?></th>
			<th><?php echo $this->Paginator->sort('gender'); ?></th>
			<th><?php echo $this->Paginator->sort('father_name'); ?></th>
			<th><?php echo $this->Paginator->sort('address'); ?></th>
			<th><?php echo $this->Paginator->sort('mobile_number'); ?></th>
			<th><?php echo $this->Paginator->sort('email'); ?></th>
			<th><?php echo $this->Paginator->sort('faculty_id'); ?></th>
			<th><?php echo $this->Paginator->sort('thesi_id'); ?></th>
			<th><?php echo $this->Paginator->sort('discipline_id'); ?></th>
			<th><?php echo $this->Paginator->sort('supervisor_id'); ?></th>
			<th><?php echo $this->Paginator->sort('year_of_register'); ?></th>
			<th><?php echo $this->Paginator->sort('month_id'); ?></th>
			<th><?php echo $this->Paginator->sort('date_of_register'); ?></th>
			<th><?php echo $this->Paginator->sort('month_year_id'); ?></th>
			<th><?php echo $this->Paginator->sort('picture'); ?></th>
			<th><?php echo $this->Paginator->sort('created_by'); ?></th>
			<th><?php echo $this->Paginator->sort('modified_by'); ?></th>
			<th><?php echo $this->Paginator->sort('created'); ?></th>
			<th><?php echo $this->Paginator->sort('modified'); ?></th>
			<th class="actions"><?php echo __('Actions'); ?></th>
	</tr>
	</thead>
	<tbody>
	<?php foreach ($phdStudents as $phdStudent): ?>
	<tr>
		<td><?php echo h($phdStudent['PhdStudent']['id']); ?>&nbsp;</td>
		<td><?php echo h($phdStudent['PhdStudent']['name']); ?>&nbsp;</td>
		<td><?php echo h($phdStudent['PhdStudent']['birth_date']); ?>&nbsp;</td>
		<td><?php echo h($phdStudent['PhdStudent']['gender']); ?>&nbsp;</td>
		<td><?php echo h($phdStudent['PhdStudent']['father_name']); ?>&nbsp;</td>
		<td><?php echo h($phdStudent['PhdStudent']['address']); ?>&nbsp;</td>
		<td><?php echo h($phdStudent['PhdStudent']['mobile_number']); ?>&nbsp;</td>
		<td><?php echo h($phdStudent['PhdStudent']['email']); ?>&nbsp;</td>
		<td>
			<?php echo $this->Html->link($phdStudent['Faculty']['faculty_name'], array('controller' => 'faculties', 'action' => 'view', $phdStudent['Faculty']['id'])); ?>
		</td>
		<td>
			<?php echo $this->Html->link($phdStudent['Thesi']['id'], array('controller' => 'thesis', 'action' => 'view', $phdStudent['Thesi']['id'])); ?>
		</td>
		<td>
			<?php echo $this->Html->link($phdStudent['Discipline']['id'], array('controller' => 'disciplines', 'action' => 'view', $phdStudent['Discipline']['id'])); ?>
		</td>
		<td>
			<?php echo $this->Html->link($phdStudent['Supervisor']['id'], array('controller' => 'supervisors', 'action' => 'view', $phdStudent['Supervisor']['id'])); ?>
		</td>
		<td><?php echo h($phdStudent['PhdStudent']['year_of_register']); ?>&nbsp;</td>
		<td>
			<?php echo $this->Html->link($phdStudent['Month']['month_name'], array('controller' => 'months', 'action' => 'view', $phdStudent['Month']['id'])); ?>
		</td>
		<td><?php echo h($phdStudent['PhdStudent']['date_of_register']); ?>&nbsp;</td>
		<td>
			<?php echo $this->Html->link($phdStudent['MonthYear']['month_id'], array('controller' => 'month_years', 'action' => 'view', $phdStudent['MonthYear']['id'])); ?>
		</td>
		<td><?php echo h($phdStudent['PhdStudent']['picture']); ?>&nbsp;</td>
		<td><?php echo h($phdStudent['PhdStudent']['created_by']); ?>&nbsp;</td>
		<td><?php echo h($phdStudent['PhdStudent']['modified_by']); ?>&nbsp;</td>
		<td><?php echo h($phdStudent['PhdStudent']['created']); ?>&nbsp;</td>
		<td><?php echo h($phdStudent['PhdStudent']['modified']); ?>&nbsp;</td>
		<td class="actions">
			<?php echo $this->Html->link(__('View'), array('action' => 'view', $phdStudent['PhdStudent']['id'])); ?>
			<?php echo $this->Html->link(__('Edit'), array('action' => 'edit', $phdStudent['PhdStudent']['id'])); ?>
			<?php echo $this->Form->postLink(__('Delete'), array('action' => 'delete', $phdStudent['PhdStudent']['id']), array('confirm' => __('Are you sure you want to delete # %s?', $phdStudent['PhdStudent']['id']))); ?>
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
		<li><?php echo $this->Html->link(__('New Phd Student'), array('action' => 'add')); ?></li>
		<li><?php echo $this->Html->link(__('List Faculties'), array('controller' => 'faculties', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Faculty'), array('controller' => 'faculties', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Thesis'), array('controller' => 'thesis', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Thesi'), array('controller' => 'thesis', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Disciplines'), array('controller' => 'disciplines', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Discipline'), array('controller' => 'disciplines', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Supervisors'), array('controller' => 'supervisors', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Supervisor'), array('controller' => 'supervisors', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Months'), array('controller' => 'months', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Month'), array('controller' => 'months', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Month Years'), array('controller' => 'month_years', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Month Year'), array('controller' => 'month_years', 'action' => 'add')); ?> </li>
	</ul>
</div>
