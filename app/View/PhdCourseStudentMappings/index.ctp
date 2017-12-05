<div class="phdCourseStudentMappings index">
	<h2><?php echo __('Phd Course Student Mappings'); ?></h2>
	<table cellpadding="0" cellspacing="0">
	<thead>
	<tr>
			<th><?php echo $this->Paginator->sort('id'); ?></th>
			<th><?php echo $this->Paginator->sort('phd_student_id'); ?></th>
			<th><?php echo $this->Paginator->sort('phd_course_id'); ?></th>
			<th><?php echo $this->Paginator->sort('marks'); ?></th>
			<th><?php echo $this->Paginator->sort('indicator'); ?></th>
			<th><?php echo $this->Paginator->sort('created_by'); ?></th>
			<th><?php echo $this->Paginator->sort('modified_by'); ?></th>
			<th><?php echo $this->Paginator->sort('created'); ?></th>
			<th><?php echo $this->Paginator->sort('modified'); ?></th>
			<th class="actions"><?php echo __('Actions'); ?></th>
	</tr>
	</thead>
	<tbody>
	<?php foreach ($phdCourseStudentMappings as $phdCourseStudentMapping): ?>
	<tr>
		<td><?php echo h($phdCourseStudentMapping['PhdCourseStudentMapping']['id']); ?>&nbsp;</td>
		<td>
			<?php echo $this->Html->link($phdCourseStudentMapping['PhdStudent']['name'], array('controller' => 'phd_students', 'action' => 'view', $phdCourseStudentMapping['PhdStudent']['id'])); ?>
		</td>
		<td>
			<?php echo $this->Html->link($phdCourseStudentMapping['PhdCourse']['id'], array('controller' => 'phd_courses', 'action' => 'view', $phdCourseStudentMapping['PhdCourse']['id'])); ?>
		</td>
		<td><?php echo h($phdCourseStudentMapping['PhdCourseStudentMapping']['marks']); ?>&nbsp;</td>
		<td><?php echo h($phdCourseStudentMapping['PhdCourseStudentMapping']['indicator']); ?>&nbsp;</td>
		<td>
			<?php echo $this->Html->link($phdCourseStudentMapping['User']['username'], array('controller' => 'users', 'action' => 'view', $phdCourseStudentMapping['User']['id'])); ?>
		</td>
		<td>
			<?php echo $this->Html->link($phdCourseStudentMapping['ModifiedUser']['username'], array('controller' => 'modified_users', 'action' => 'view', $phdCourseStudentMapping['ModifiedUser']['id'])); ?>
		</td>
		<td><?php echo h($phdCourseStudentMapping['PhdCourseStudentMapping']['created']); ?>&nbsp;</td>
		<td><?php echo h($phdCourseStudentMapping['PhdCourseStudentMapping']['modified']); ?>&nbsp;</td>
		<td class="actions">
			<?php echo $this->Html->link(__('View'), array('action' => 'view', $phdCourseStudentMapping['PhdCourseStudentMapping']['id'])); ?>
			<?php echo $this->Html->link(__('Edit'), array('action' => 'edit', $phdCourseStudentMapping['PhdCourseStudentMapping']['id'])); ?>
			<?php echo $this->Form->postLink(__('Delete'), array('action' => 'delete', $phdCourseStudentMapping['PhdCourseStudentMapping']['id']), array('confirm' => __('Are you sure you want to delete # %s?', $phdCourseStudentMapping['PhdCourseStudentMapping']['id']))); ?>
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
		<li><?php echo $this->Html->link(__('New Phd Course Student Mapping'), array('action' => 'add')); ?></li>
		<li><?php echo $this->Html->link(__('List Phd Students'), array('controller' => 'phd_students', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Phd Student'), array('controller' => 'phd_students', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Phd Courses'), array('controller' => 'phd_courses', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Phd Course'), array('controller' => 'phd_courses', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Users'), array('controller' => 'users', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New User'), array('controller' => 'users', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Modified Users'), array('controller' => 'modified_users', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Modified User'), array('controller' => 'modified_users', 'action' => 'add')); ?> </li>
	</ul>
</div>
