<div class="revaluationExams view">
<h2><?php echo __('Revaluation Exam'); ?></h2>
	<dl>
		<dt><?php echo __('Id'); ?></dt>
		<dd>
			<?php echo h($revaluationExam['RevaluationExam']['id']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Course Mapping'); ?></dt>
		<dd>
			<?php echo $this->Html->link($revaluationExam['CourseMapping']['program_id'], array('controller' => 'course_mappings', 'action' => 'view', $revaluationExam['CourseMapping']['id'])); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Student'); ?></dt>
		<dd>
			<?php echo $this->Html->link($revaluationExam['Student']['name'], array('controller' => 'students', 'action' => 'view', $revaluationExam['Student']['id'])); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Month Year'); ?></dt>
		<dd>
			<?php echo $this->Html->link($revaluationExam['MonthYear']['month_id'], array('controller' => 'month_years', 'action' => 'view', $revaluationExam['MonthYear']['id'])); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Dummy Number'); ?></dt>
		<dd>
			<?php echo $this->Html->link($revaluationExam['DummyNumber']['id'], array('controller' => 'dummy_numbers', 'action' => 'view', $revaluationExam['DummyNumber']['id'])); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Dummy Number'); ?></dt>
		<dd>
			<?php echo h($revaluationExam['RevaluationExam']['dummy_number']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Marks'); ?></dt>
		<dd>
			<?php echo h($revaluationExam['RevaluationExam']['marks']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Reval Dummy Mod Operator'); ?></dt>
		<dd>
			<?php echo h($revaluationExam['RevaluationExam']['reval_dummy_mod_operator']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Reval Dummy Mod Marks'); ?></dt>
		<dd>
			<?php echo h($revaluationExam['RevaluationExam']['reval_dummy_mod_marks']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Reval Moderation Marks'); ?></dt>
		<dd>
			<?php echo h($revaluationExam['RevaluationExam']['reval_moderation_marks']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Reval Moderation Operator'); ?></dt>
		<dd>
			<?php echo h($revaluationExam['RevaluationExam']['reval_moderation_operator']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Final Marks'); ?></dt>
		<dd>
			<?php echo h($revaluationExam['RevaluationExam']['final_marks']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Created By'); ?></dt>
		<dd>
			<?php echo h($revaluationExam['RevaluationExam']['created_by']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Modified By'); ?></dt>
		<dd>
			<?php echo h($revaluationExam['RevaluationExam']['modified_by']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Created'); ?></dt>
		<dd>
			<?php echo h($revaluationExam['RevaluationExam']['created']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Modified'); ?></dt>
		<dd>
			<?php echo h($revaluationExam['RevaluationExam']['modified']); ?>
			&nbsp;
		</dd>
	</dl>
</div>
<div class="actions">
	<h3><?php echo __('Actions'); ?></h3>
	<ul>
		<li><?php echo $this->Html->link(__('Edit Revaluation Exam'), array('action' => 'edit', $revaluationExam['RevaluationExam']['id'])); ?> </li>
		<li><?php echo $this->Form->postLink(__('Delete Revaluation Exam'), array('action' => 'delete', $revaluationExam['RevaluationExam']['id']), array('confirm' => __('Are you sure you want to delete # %s?', $revaluationExam['RevaluationExam']['id']))); ?> </li>
		<li><?php echo $this->Html->link(__('List Revaluation Exams'), array('action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Revaluation Exam'), array('action' => 'add')); ?> </li>
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
