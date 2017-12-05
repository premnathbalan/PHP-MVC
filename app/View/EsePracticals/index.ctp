<div class="esePracticals index">
	<h2><?php echo __('Ese Practicals'); ?></h2>
	<table cellpadding="0" cellspacing="0">
	<thead>
	<tr>
			<th><?php echo $this->Paginator->sort('id'); ?></th>
			<th><?php echo $this->Paginator->sort('course_mapping_id'); ?></th>
			<th><?php echo $this->Paginator->sort('assessment_type'); ?></th>
			<th><?php echo $this->Paginator->sort('semester_id'); ?></th>
			<th><?php echo $this->Paginator->sort('marks'); ?></th>
			<th><?php echo $this->Paginator->sort('marks_status'); ?></th>
			<th><?php echo $this->Paginator->sort('add_status'); ?></th>
			<th><?php echo $this->Paginator->sort('approval_status'); ?></th>
			<th><?php echo $this->Paginator->sort('lecturer_id'); ?></th>
			<th><?php echo $this->Paginator->sort('indicator'); ?></th>
			<th><?php echo $this->Paginator->sort('created_by'); ?></th>
			<th><?php echo $this->Paginator->sort('modified_by'); ?></th>
			<th><?php echo $this->Paginator->sort('created'); ?></th>
			<th><?php echo $this->Paginator->sort('modified'); ?></th>
			<th class="actions"><?php echo __('Actions'); ?></th>
	</tr>
	</thead>
	<tbody>
	<?php foreach ($esePracticals as $esePractical): ?>
	<tr>
		<td><?php echo h($esePractical['EsePractical']['id']); ?>&nbsp;</td>
		<td>
			<?php echo $this->Html->link($esePractical['CourseMapping']['program_id'], array('controller' => 'course_mappings', 'action' => 'view', $esePractical['CourseMapping']['id'])); ?>
		</td>
		<td><?php echo h($esePractical['EsePractical']['assessment_type']); ?>&nbsp;</td>
		<td>
			<?php echo $this->Html->link($esePractical['Semester']['id'], array('controller' => 'semesters', 'action' => 'view', $esePractical['Semester']['id'])); ?>
		</td>
		<td><?php echo h($esePractical['EsePractical']['marks']); ?>&nbsp;</td>
		<td><?php echo h($esePractical['EsePractical']['marks_status']); ?>&nbsp;</td>
		<td><?php echo h($esePractical['EsePractical']['add_status']); ?>&nbsp;</td>
		<td><?php echo h($esePractical['EsePractical']['approval_status']); ?>&nbsp;</td>
		<td>
			<?php echo $this->Html->link($esePractical['Lecturer']['id'], array('controller' => 'lecturers', 'action' => 'view', $esePractical['Lecturer']['id'])); ?>
		</td>
		<td><?php echo h($esePractical['EsePractical']['indicator']); ?>&nbsp;</td>
		<td><?php echo h($esePractical['EsePractical']['created_by']); ?>&nbsp;</td>
		<td><?php echo h($esePractical['EsePractical']['modified_by']); ?>&nbsp;</td>
		<td><?php echo h($esePractical['EsePractical']['created']); ?>&nbsp;</td>
		<td><?php echo h($esePractical['EsePractical']['modified']); ?>&nbsp;</td>
		<td class="actions">
			<?php echo $this->Html->link(__('View'), array('action' => 'view', $esePractical['EsePractical']['id'])); ?>
			<?php echo $this->Html->link(__('Edit'), array('action' => 'edit', $esePractical['EsePractical']['id'])); ?>
			<?php echo $this->Form->postLink(__('Delete'), array('action' => 'delete', $esePractical['EsePractical']['id']), array('confirm' => __('Are you sure you want to delete # %s?', $esePractical['EsePractical']['id']))); ?>
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
		<li><?php echo $this->Html->link(__('New Ese Practical'), array('action' => 'add')); ?></li>
		<li><?php echo $this->Html->link(__('List Course Mappings'), array('controller' => 'course_mappings', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Course Mapping'), array('controller' => 'course_mappings', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Semesters'), array('controller' => 'semesters', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Semester'), array('controller' => 'semesters', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Lecturers'), array('controller' => 'lecturers', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Lecturer'), array('controller' => 'lecturers', 'action' => 'add')); ?> </li>
	</ul>
</div>
