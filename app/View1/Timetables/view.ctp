<div class="timetables view">
<h2><?php echo __('Timetable'); ?></h2>
	<dl>
		<dt><?php echo __('Id'); ?></dt>
		<dd>
			<?php echo h($timetable['Timetable']['id']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Month Year Id'); ?></dt>
		<dd>
			<?php echo h($timetable['Timetable']['month_year_id']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Course Mapping Id'); ?></dt>
		<dd>
			<?php echo h($timetable['Timetable']['course_mapping_id']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Exam Date'); ?></dt>
		<dd>
			<?php echo h($timetable['Timetable']['exam_date']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Exam Session'); ?></dt>
		<dd>
			<?php echo h($timetable['Timetable']['exam_session']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Exam Type'); ?></dt>
		<dd>
			<?php echo h($timetable['Timetable']['exam_type']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Created By'); ?></dt>
		<dd>
			<?php echo h($timetable['Timetable']['created_by']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Modified By'); ?></dt>
		<dd>
			<?php echo h($timetable['Timetable']['modified_by']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Created'); ?></dt>
		<dd>
			<?php echo h($timetable['Timetable']['created']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Modified'); ?></dt>
		<dd>
			<?php echo h($timetable['Timetable']['modified']); ?>
			&nbsp;
		</dd>
	</dl>
</div>
<div class="actions">
	<h3><?php echo __('Actions'); ?></h3>
	<ul>
		<li><?php echo $this->Html->link(__('Edit Timetable'), array('action' => 'edit', $timetable['Timetable']['id'])); ?> </li>
		<li><?php echo $this->Form->postLink(__('Delete Timetable'), array('action' => 'delete', $timetable['Timetable']['id']), array('confirm' => __('Are you sure you want to delete # %s?', $timetable['Timetable']['id']))); ?> </li>
		<li><?php echo $this->Html->link(__('List Timetables'), array('action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Timetable'), array('action' => 'add')); ?> </li>
	</ul>
</div>
