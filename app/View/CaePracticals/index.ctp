<div class="caePracticals index">
	<h2><?php echo __('Cae Practicals'); ?></h2>
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
	<?php foreach ($caePracticals as $caePractical): ?>
	<tr>
		<td><?php echo h($caePractical['CaePractical']['id']); ?>&nbsp;</td>
		<td>
			<?php echo $this->Html->link($caePractical['CourseMapping']['program_id'], array('controller' => 'course_mappings', 'action' => 'view', $caePractical['CourseMapping']['id'])); ?>
		</td>
		<td><?php echo h($caePractical['CaePractical']['assessment_type']); ?>&nbsp;</td>
		<td>
			<?php echo $this->Html->link($caePractical['Semester']['id'], array('controller' => 'semesters', 'action' => 'view', $caePractical['Semester']['id'])); ?>
		</td>
		<td><?php echo h($caePractical['CaePractical']['marks']); ?>&nbsp;</td>
		<td><?php echo h($caePractical['CaePractical']['marks_status']); ?>&nbsp;</td>
		<td><?php echo h($caePractical['CaePractical']['add_status']); ?>&nbsp;</td>
		<td><?php echo h($caePractical['CaePractical']['approval_status']); ?>&nbsp;</td>
		<td>
			<?php echo $this->Html->link($caePractical['Lecturer']['id'], array('controller' => 'lecturers', 'action' => 'view', $caePractical['Lecturer']['id'])); ?>
		</td>
		<td><?php echo h($caePractical['CaePractical']['indicator']); ?>&nbsp;</td>
		<td>
			<?php echo $this->Html->link($caePractical['User']['username'], array('controller' => 'users', 'action' => 'view', $caePractical['User']['id'])); ?>
		</td>
		<td>
			<?php echo $this->Html->link($caePractical['ModifiedUser']['username'], array('controller' => 'modified_users', 'action' => 'view', $caePractical['ModifiedUser']['id'])); ?>
		</td>
		<td><?php echo h($caePractical['CaePractical']['created']); ?>&nbsp;</td>
		<td><?php echo h($caePractical['CaePractical']['modified']); ?>&nbsp;</td>
		<td class="actions">
			<?php echo $this->Html->link(__('View'), array('action' => 'view', $caePractical['CaePractical']['id'])); ?>
			<?php echo $this->Html->link(__('Edit'), array('action' => 'edit', $caePractical['CaePractical']['id'])); ?>
			<?php echo $this->Form->postLink(__('Delete'), array('action' => 'delete', $caePractical['CaePractical']['id']), array('confirm' => __('Are you sure you want to delete # %s?', $caePractical['CaePractical']['id']))); ?>
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
		<li><?php echo $this->Html->link(__('New Cae Practical'), array('action' => 'add')); ?></li>
		<li><?php echo $this->Html->link(__('List Course Mappings'), array('controller' => 'course_mappings', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Course Mapping'), array('controller' => 'course_mappings', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Semesters'), array('controller' => 'semesters', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Semester'), array('controller' => 'semesters', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Lecturers'), array('controller' => 'lecturers', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Lecturer'), array('controller' => 'lecturers', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Users'), array('controller' => 'users', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New User'), array('controller' => 'users', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Modified Users'), array('controller' => 'modified_users', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Modified User'), array('controller' => 'modified_users', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Practicals'), array('controller' => 'practicals', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Practical'), array('controller' => 'practicals', 'action' => 'add')); ?> </li>
	</ul>
</div>
