<div class="courseMappings view">
<h2><?php echo __('Course Mapping'); ?></h2>
	<dl>
		<dt><?php echo __('Id'); ?></dt>
		<dd>
			<?php echo h($courseMapping['CourseMapping']['id']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Program'); ?></dt>
		<dd>
			<?php echo $this->Html->link($courseMapping['Program']['program_name'], array('controller' => 'programs', 'action' => 'view', $courseMapping['Program']['id'])); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Course'); ?></dt>
		<dd>
			<?php echo $this->Html->link($courseMapping['Course']['course_name'], array('controller' => 'courses', 'action' => 'view', $courseMapping['Course']['id'])); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Course Mode'); ?></dt>
		<dd>
			<?php echo $this->Html->link($courseMapping['CourseMode']['course_mode'], array('controller' => 'course_modes', 'action' => 'view', $courseMapping['CourseMode']['id'])); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Semester'); ?></dt>
		<dd>
			<?php echo $this->Html->link($courseMapping['Semester']['semester_name'], array('controller' => 'semesters', 'action' => 'view', $courseMapping['Semester']['id'])); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Created By'); ?></dt>
		<dd>
			<?php echo h($courseMapping['CourseMapping']['created_by']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Modified By'); ?></dt>
		<dd>
			<?php echo h($courseMapping['CourseMapping']['modified_by']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Created'); ?></dt>
		<dd>
			<?php echo h($courseMapping['CourseMapping']['created']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Modified'); ?></dt>
		<dd>
			<?php echo h($courseMapping['CourseMapping']['modified']); ?>
			&nbsp;
		</dd>
	</dl>
</div>
<div class="actions">
	<h3><?php echo __('Actions'); ?></h3>
	<ul>
		<li><?php echo $this->Html->link(__('Edit Course Mapping'), array('action' => 'edit', $courseMapping['CourseMapping']['id'])); ?> </li>
		<li><?php echo $this->Form->postLink(__('Delete Course Mapping'), array('action' => 'delete', $courseMapping['CourseMapping']['id']), array('confirm' => __('Are you sure you want to delete # %s?', $courseMapping['CourseMapping']['id']))); ?> </li>
		<li><?php echo $this->Html->link(__('List Course Mappings'), array('action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Course Mapping'), array('action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Programs'), array('controller' => 'programs', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Program'), array('controller' => 'programs', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Courses'), array('controller' => 'courses', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Course'), array('controller' => 'courses', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Course Modes'), array('controller' => 'course_modes', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Course Mode'), array('controller' => 'course_modes', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Semesters'), array('controller' => 'semesters', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Semester'), array('controller' => 'semesters', 'action' => 'add')); ?> </li>
	</ul>
</div>
