<div class="caePts view">
<h2><?php echo __('Cae Pt'); ?></h2>
	<dl>
		<dt><?php echo __('Id'); ?></dt>
		<dd>
			<?php echo h($caePt['CaePt']['id']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Course Mapping'); ?></dt>
		<dd>
			<?php echo $this->Html->link($caePt['CourseMapping']['id'], array('controller' => 'course_mappings', 'action' => 'view', $caePt['CourseMapping']['id'])); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Assessment Type'); ?></dt>
		<dd>
			<?php echo h($caePt['CaePt']['assessment_type']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Marks'); ?></dt>
		<dd>
			<?php echo h($caePt['CaePt']['marks']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Marks Status'); ?></dt>
		<dd>
			<?php echo h($caePt['CaePt']['marks_status']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Add Status'); ?></dt>
		<dd>
			<?php echo h($caePt['CaePt']['add_status']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Approval Status'); ?></dt>
		<dd>
			<?php echo h($caePt['CaePt']['approval_status']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Lecturer'); ?></dt>
		<dd>
			<?php echo $this->Html->link($caePt['Lecturer']['id'], array('controller' => 'lecturers', 'action' => 'view', $caePt['Lecturer']['id'])); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Indicator'); ?></dt>
		<dd>
			<?php echo h($caePt['CaePt']['indicator']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Created By'); ?></dt>
		<dd>
			<?php echo h($caePt['CaePt']['created_by']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Modified By'); ?></dt>
		<dd>
			<?php echo h($caePt['CaePt']['modified_by']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Created'); ?></dt>
		<dd>
			<?php echo h($caePt['CaePt']['created']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Modified'); ?></dt>
		<dd>
			<?php echo h($caePt['CaePt']['modified']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Month Year'); ?></dt>
		<dd>
			<?php echo $this->Html->link($caePt['MonthYear']['month_id'], array('controller' => 'month_years', 'action' => 'view', $caePt['MonthYear']['id'])); ?>
			&nbsp;
		</dd>
	</dl>
</div>
<div class="actions">
	<h3><?php echo __('Actions'); ?></h3>
	<ul>
		<li><?php echo $this->Html->link(__('Edit Cae Pt'), array('action' => 'edit', $caePt['CaePt']['id'])); ?> </li>
		<li><?php echo $this->Form->postLink(__('Delete Cae Pt'), array('action' => 'delete', $caePt['CaePt']['id']), array('confirm' => __('Are you sure you want to delete # %s?', $caePt['CaePt']['id']))); ?> </li>
		<li><?php echo $this->Html->link(__('List Cae Pts'), array('action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Cae Pt'), array('action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Course Mappings'), array('controller' => 'course_mappings', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Course Mapping'), array('controller' => 'course_mappings', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Lecturers'), array('controller' => 'lecturers', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Lecturer'), array('controller' => 'lecturers', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Month Years'), array('controller' => 'month_years', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Month Year'), array('controller' => 'month_years', 'action' => 'add')); ?> </li>
	</ul>
</div>
