<div class="caePracticals view">
<h2><?php echo __('Cae Practical'); ?></h2>
	<dl>
		<dt><?php echo __('Id'); ?></dt>
		<dd>
			<?php echo h($caePractical['CaePractical']['id']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Course Mapping'); ?></dt>
		<dd>
			<?php echo $this->Html->link($caePractical['CourseMapping']['program_id'], array('controller' => 'course_mappings', 'action' => 'view', $caePractical['CourseMapping']['id'])); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Assessment Type'); ?></dt>
		<dd>
			<?php echo h($caePractical['CaePractical']['assessment_type']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Semester'); ?></dt>
		<dd>
			<?php echo $this->Html->link($caePractical['Semester']['id'], array('controller' => 'semesters', 'action' => 'view', $caePractical['Semester']['id'])); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Marks'); ?></dt>
		<dd>
			<?php echo h($caePractical['CaePractical']['marks']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Marks Status'); ?></dt>
		<dd>
			<?php echo h($caePractical['CaePractical']['marks_status']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Add Status'); ?></dt>
		<dd>
			<?php echo h($caePractical['CaePractical']['add_status']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Approval Status'); ?></dt>
		<dd>
			<?php echo h($caePractical['CaePractical']['approval_status']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Lecturer'); ?></dt>
		<dd>
			<?php echo $this->Html->link($caePractical['Lecturer']['id'], array('controller' => 'lecturers', 'action' => 'view', $caePractical['Lecturer']['id'])); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Indicator'); ?></dt>
		<dd>
			<?php echo h($caePractical['CaePractical']['indicator']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('User'); ?></dt>
		<dd>
			<?php echo $this->Html->link($caePractical['User']['username'], array('controller' => 'users', 'action' => 'view', $caePractical['User']['id'])); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Modified User'); ?></dt>
		<dd>
			<?php echo $this->Html->link($caePractical['ModifiedUser']['username'], array('controller' => 'modified_users', 'action' => 'view', $caePractical['ModifiedUser']['id'])); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Created'); ?></dt>
		<dd>
			<?php echo h($caePractical['CaePractical']['created']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Modified'); ?></dt>
		<dd>
			<?php echo h($caePractical['CaePractical']['modified']); ?>
			&nbsp;
		</dd>
	</dl>
</div>
<div class="actions">
	<h3><?php echo __('Actions'); ?></h3>
	<ul>
		<li><?php echo $this->Html->link(__('Edit Cae Practical'), array('action' => 'edit', $caePractical['CaePractical']['id'])); ?> </li>
		<li><?php echo $this->Form->postLink(__('Delete Cae Practical'), array('action' => 'delete', $caePractical['CaePractical']['id']), array('confirm' => __('Are you sure you want to delete # %s?', $caePractical['CaePractical']['id']))); ?> </li>
		<li><?php echo $this->Html->link(__('List Cae Practicals'), array('action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Cae Practical'), array('action' => 'add')); ?> </li>
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
<div class="related">
	<h3><?php echo __('Related Practicals'); ?></h3>
	<?php if (!empty($caePractical['Practical'])): ?>
	<table cellpadding = "0" cellspacing = "0">
	<tr>
		<th><?php echo __('Id'); ?></th>
		<th><?php echo __('Student Id'); ?></th>
		<th><?php echo __('Cae Practical Id'); ?></th>
		<th><?php echo __('Marks'); ?></th>
		<th><?php echo __('Created By'); ?></th>
		<th><?php echo __('Modified By'); ?></th>
		<th><?php echo __('Created'); ?></th>
		<th><?php echo __('Modified'); ?></th>
		<th class="actions"><?php echo __('Actions'); ?></th>
	</tr>
	<?php foreach ($caePractical['Practical'] as $practical): ?>
		<tr>
			<td><?php echo $practical['id']; ?></td>
			<td><?php echo $practical['student_id']; ?></td>
			<td><?php echo $practical['cae_practical_id']; ?></td>
			<td><?php echo $practical['marks']; ?></td>
			<td><?php echo $practical['created_by']; ?></td>
			<td><?php echo $practical['modified_by']; ?></td>
			<td><?php echo $practical['created']; ?></td>
			<td><?php echo $practical['modified']; ?></td>
			<td class="actions">
				<?php echo $this->Html->link(__('View'), array('controller' => 'practicals', 'action' => 'view', $practical['id'])); ?>
				<?php echo $this->Html->link(__('Edit'), array('controller' => 'practicals', 'action' => 'edit', $practical['id'])); ?>
				<?php echo $this->Form->postLink(__('Delete'), array('controller' => 'practicals', 'action' => 'delete', $practical['id']), array('confirm' => __('Are you sure you want to delete # %s?', $practical['id']))); ?>
			</td>
		</tr>
	<?php endforeach; ?>
	</table>
<?php endif; ?>

	<div class="actions">
		<ul>
			<li><?php echo $this->Html->link(__('New Practical'), array('controller' => 'practicals', 'action' => 'add')); ?> </li>
		</ul>
	</div>
</div>
