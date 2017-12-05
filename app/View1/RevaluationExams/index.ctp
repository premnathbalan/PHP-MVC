<div class="revaluationExams index">
	<h2><?php echo __('Revaluation Exams'); ?></h2>
	<table cellpadding="0" cellspacing="0">
	<thead>
	<tr>
			<th><?php echo $this->Paginator->sort('id'); ?></th>
			<th><?php echo $this->Paginator->sort('course_mapping_id'); ?></th>
			<th><?php echo $this->Paginator->sort('student_id'); ?></th>
			<th><?php echo $this->Paginator->sort('month_year_id'); ?></th>
			<th><?php echo $this->Paginator->sort('dummy_number_id'); ?></th>
			<th><?php echo $this->Paginator->sort('dummy_number'); ?></th>
			<th><?php echo $this->Paginator->sort('marks'); ?></th>
			<th><?php echo $this->Paginator->sort('reval_dummy_mod_operator'); ?></th>
			<th><?php echo $this->Paginator->sort('reval_dummy_mod_marks'); ?></th>
			<th><?php echo $this->Paginator->sort('reval_moderation_marks'); ?></th>
			<th><?php echo $this->Paginator->sort('reval_moderation_operator'); ?></th>
			<th><?php echo $this->Paginator->sort('final_marks'); ?></th>
			<th><?php echo $this->Paginator->sort('created_by'); ?></th>
			<th><?php echo $this->Paginator->sort('modified_by'); ?></th>
			<th><?php echo $this->Paginator->sort('created'); ?></th>
			<th><?php echo $this->Paginator->sort('modified'); ?></th>
			<th class="actions"><?php echo __('Actions'); ?></th>
	</tr>
	</thead>
	<tbody>
	<?php foreach ($revaluationExams as $revaluationExam): ?>
	<tr>
		<td><?php echo h($revaluationExam['RevaluationExam']['id']); ?>&nbsp;</td>
		<td>
			<?php echo $this->Html->link($revaluationExam['CourseMapping']['program_id'], array('controller' => 'course_mappings', 'action' => 'view', $revaluationExam['CourseMapping']['id'])); ?>
		</td>
		<td>
			<?php echo $this->Html->link($revaluationExam['Student']['name'], array('controller' => 'students', 'action' => 'view', $revaluationExam['Student']['id'])); ?>
		</td>
		<td>
			<?php echo $this->Html->link($revaluationExam['MonthYear']['month_id'], array('controller' => 'month_years', 'action' => 'view', $revaluationExam['MonthYear']['id'])); ?>
		</td>
		<td>
			<?php echo $this->Html->link($revaluationExam['DummyNumber']['id'], array('controller' => 'dummy_numbers', 'action' => 'view', $revaluationExam['DummyNumber']['id'])); ?>
		</td>
		<td><?php echo h($revaluationExam['RevaluationExam']['dummy_number']); ?>&nbsp;</td>
		<td><?php echo h($revaluationExam['RevaluationExam']['marks']); ?>&nbsp;</td>
		<td><?php echo h($revaluationExam['RevaluationExam']['reval_dummy_mod_operator']); ?>&nbsp;</td>
		<td><?php echo h($revaluationExam['RevaluationExam']['reval_dummy_mod_marks']); ?>&nbsp;</td>
		<td><?php echo h($revaluationExam['RevaluationExam']['reval_moderation_marks']); ?>&nbsp;</td>
		<td><?php echo h($revaluationExam['RevaluationExam']['reval_moderation_operator']); ?>&nbsp;</td>
		<td><?php echo h($revaluationExam['RevaluationExam']['final_marks']); ?>&nbsp;</td>
		<td><?php echo h($revaluationExam['RevaluationExam']['created_by']); ?>&nbsp;</td>
		<td><?php echo h($revaluationExam['RevaluationExam']['modified_by']); ?>&nbsp;</td>
		<td><?php echo h($revaluationExam['RevaluationExam']['created']); ?>&nbsp;</td>
		<td><?php echo h($revaluationExam['RevaluationExam']['modified']); ?>&nbsp;</td>
		<td class="actions">
			<?php echo $this->Html->link(__('View'), array('action' => 'view', $revaluationExam['RevaluationExam']['id'])); ?>
			<?php echo $this->Html->link(__('Edit'), array('action' => 'edit', $revaluationExam['RevaluationExam']['id'])); ?>
			<?php echo $this->Form->postLink(__('Delete'), array('action' => 'delete', $revaluationExam['RevaluationExam']['id']), array('confirm' => __('Are you sure you want to delete # %s?', $revaluationExam['RevaluationExam']['id']))); ?>
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
		<li><?php echo $this->Html->link(__('New Revaluation Exam'), array('action' => 'add')); ?></li>
		<li><?php echo $this->Html->link(__('List Course Mappings'), array('controller' => 'course_mappings', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Course Mapping'), array('controller' => 'course_mappings', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Students'), array('controller' => 'students', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Student'), array('controller' => 'students', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Month Years'), array('controller' => 'month_years', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Month Year'), array('controller' => 'month_years', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Dummy Numbers'), array('controller' => 'dummy_numbers', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Dummy Number'), array('controller' => 'dummy_numbers', 'action' => 'add')); ?> </li>
	</ul>
</div>
