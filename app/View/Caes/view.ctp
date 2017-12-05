<div class="caes view">
<h2><?php echo __('Cae'); ?></h2>
	<dl>
		<dt><?php echo __('Id'); ?></dt>
		<dd>
			<?php echo h($cae['Cae']['id']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Number'); ?></dt>
		<dd>
			<?php echo h($cae['Cae']['number']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Course Mapping'); ?></dt>
		<dd>
			<?php echo $this->Html->link($cae['CourseMapping']['program_id'], array('controller' => 'course_mappings', 'action' => 'view', $cae['CourseMapping']['id'])); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Semester'); ?></dt>
		<dd>
			<?php echo $this->Html->link($cae['Semester']['semester_name'], array('controller' => 'semesters', 'action' => 'view', $cae['Semester']['id'])); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Month Year'); ?></dt>
		<dd>
			<?php echo $this->Html->link($cae['MonthYear']['month_id'], array('controller' => 'month_years', 'action' => 'view', $cae['MonthYear']['id'])); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Created By'); ?></dt>
		<dd>
			<?php echo h($cae['Cae']['created_by']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Modified By'); ?></dt>
		<dd>
			<?php echo h($cae['Cae']['modified_by']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Created'); ?></dt>
		<dd>
			<?php echo h($cae['Cae']['created']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Modified'); ?></dt>
		<dd>
			<?php echo h($cae['Cae']['modified']); ?>
			&nbsp;
		</dd>
	</dl>
</div>
<div class="actions">
	<h3><?php echo __('Actions'); ?></h3>
	<ul>
		<li><?php echo $this->Html->link(__('Edit Cae'), array('action' => 'edit', $cae['Cae']['id'])); ?> </li>
		<li><?php echo $this->Form->postLink(__('Delete Cae'), array('action' => 'delete', $cae['Cae']['id']), array('confirm' => __('Are you sure you want to delete # %s?', $cae['Cae']['id']))); ?> </li>
		<li><?php echo $this->Html->link(__('List Caes'), array('action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Cae'), array('action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Course Mappings'), array('controller' => 'course_mappings', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Course Mapping'), array('controller' => 'course_mappings', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Semesters'), array('controller' => 'semesters', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Semester'), array('controller' => 'semesters', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Month Years'), array('controller' => 'month_years', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Month Year'), array('controller' => 'month_years', 'action' => 'add')); ?> </li>
	</ul>
</div>
