<div class="studentMarks view">
<h2><?php echo __('Student Mark'); ?></h2>
	<dl>
		<dt><?php echo __('Id'); ?></dt>
		<dd>
			<?php echo h($studentMark['StudentMark']['id']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Month Year'); ?></dt>
		<dd>
			<?php echo $this->Html->link($studentMark['MonthYear']['month_id'], array('controller' => 'month_years', 'action' => 'view', $studentMark['MonthYear']['id'])); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Student'); ?></dt>
		<dd>
			<?php echo $this->Html->link($studentMark['Student']['name'], array('controller' => 'students', 'action' => 'view', $studentMark['Student']['id'])); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Course Mapping'); ?></dt>
		<dd>
			<?php echo $this->Html->link($studentMark['CourseMapping']['program_id'], array('controller' => 'course_mappings', 'action' => 'view', $studentMark['CourseMapping']['id'])); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Marks'); ?></dt>
		<dd>
			<?php echo h($studentMark['StudentMark']['marks']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Status'); ?></dt>
		<dd>
			<?php echo h($studentMark['StudentMark']['status']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Created By'); ?></dt>
		<dd>
			<?php echo h($studentMark['StudentMark']['created_by']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Modified By'); ?></dt>
		<dd>
			<?php echo h($studentMark['StudentMark']['modified_by']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Created'); ?></dt>
		<dd>
			<?php echo h($studentMark['StudentMark']['created']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Modified'); ?></dt>
		<dd>
			<?php echo h($studentMark['StudentMark']['modified']); ?>
			&nbsp;
		</dd>
	</dl>
</div>
<div class="actions">
	<h3><?php echo __('Actions'); ?></h3>
	<ul>
		<li><?php echo $this->Html->link(__('Edit Student Mark'), array('action' => 'edit', $studentMark['StudentMark']['id'])); ?> </li>
		<li><?php echo $this->Form->postLink(__('Delete Student Mark'), array('action' => 'delete', $studentMark['StudentMark']['id']), array('confirm' => __('Are you sure you want to delete # %s?', $studentMark['StudentMark']['id']))); ?> </li>
		<li><?php echo $this->Html->link(__('List Student Marks'), array('action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Student Mark'), array('action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Month Years'), array('controller' => 'month_years', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Month Year'), array('controller' => 'month_years', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Students'), array('controller' => 'students', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Student'), array('controller' => 'students', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Course Mappings'), array('controller' => 'course_mappings', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Course Mapping'), array('controller' => 'course_mappings', 'action' => 'add')); ?> </li>
	</ul>
</div>
