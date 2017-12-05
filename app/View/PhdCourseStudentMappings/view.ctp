<div class="phdCourseStudentMappings view">
<h2><?php echo __('Phd Course Student Mapping'); ?></h2>
	<dl>
		<dt><?php echo __('Id'); ?></dt>
		<dd>
			<?php echo h($phdCourseStudentMapping['PhdCourseStudentMapping']['id']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Phd Student'); ?></dt>
		<dd>
			<?php echo $this->Html->link($phdCourseStudentMapping['PhdStudent']['name'], array('controller' => 'phd_students', 'action' => 'view', $phdCourseStudentMapping['PhdStudent']['id'])); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Phd Course'); ?></dt>
		<dd>
			<?php echo $this->Html->link($phdCourseStudentMapping['PhdCourse']['id'], array('controller' => 'phd_courses', 'action' => 'view', $phdCourseStudentMapping['PhdCourse']['id'])); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Marks'); ?></dt>
		<dd>
			<?php echo h($phdCourseStudentMapping['PhdCourseStudentMapping']['marks']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Indicator'); ?></dt>
		<dd>
			<?php echo h($phdCourseStudentMapping['PhdCourseStudentMapping']['indicator']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('User'); ?></dt>
		<dd>
			<?php echo $this->Html->link($phdCourseStudentMapping['User']['username'], array('controller' => 'users', 'action' => 'view', $phdCourseStudentMapping['User']['id'])); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Modified User'); ?></dt>
		<dd>
			<?php echo $this->Html->link($phdCourseStudentMapping['ModifiedUser']['username'], array('controller' => 'modified_users', 'action' => 'view', $phdCourseStudentMapping['ModifiedUser']['id'])); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Created'); ?></dt>
		<dd>
			<?php echo h($phdCourseStudentMapping['PhdCourseStudentMapping']['created']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Modified'); ?></dt>
		<dd>
			<?php echo h($phdCourseStudentMapping['PhdCourseStudentMapping']['modified']); ?>
			&nbsp;
		</dd>
	</dl>
</div>
<div class="actions">
	<h3><?php echo __('Actions'); ?></h3>
	<ul>
		<li><?php echo $this->Html->link(__('Edit Phd Course Student Mapping'), array('action' => 'edit', $phdCourseStudentMapping['PhdCourseStudentMapping']['id'])); ?> </li>
		<li><?php echo $this->Form->postLink(__('Delete Phd Course Student Mapping'), array('action' => 'delete', $phdCourseStudentMapping['PhdCourseStudentMapping']['id']), array('confirm' => __('Are you sure you want to delete # %s?', $phdCourseStudentMapping['PhdCourseStudentMapping']['id']))); ?> </li>
		<li><?php echo $this->Html->link(__('List Phd Course Student Mappings'), array('action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Phd Course Student Mapping'), array('action' => 'add')); ?> </li>
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
