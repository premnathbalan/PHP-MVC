<div class="endSemesterExams view">
<h2><?php echo __('End Semester Exam'); ?></h2>
	<dl>
		<dt><?php echo __('Id'); ?></dt>
		<dd>
			<?php echo h($endSemesterExam['EndSemesterExam']['id']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Course Mapping'); ?></dt>
		<dd>
			<?php echo $this->Html->link($endSemesterExam['CourseMapping']['program_id'], array('controller' => 'course_mappings', 'action' => 'view', $endSemesterExam['CourseMapping']['id'])); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Student'); ?></dt>
		<dd>
			<?php echo $this->Html->link($endSemesterExam['Student']['name'], array('controller' => 'students', 'action' => 'view', $endSemesterExam['Student']['id'])); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Month Year'); ?></dt>
		<dd>
			<?php echo $this->Html->link($endSemesterExam['MonthYear']['month_id'], array('controller' => 'month_years', 'action' => 'view', $endSemesterExam['MonthYear']['id'])); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Actual Marks'); ?></dt>
		<dd>
			<?php echo h($endSemesterExam['EndSemesterExam']['actual_marks']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Max Convert To'); ?></dt>
		<dd>
			<?php echo h($endSemesterExam['EndSemesterExam']['max_convert_to']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Converted Marks'); ?></dt>
		<dd>
			<?php echo h($endSemesterExam['EndSemesterExam']['converted_marks']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Moderation Marks'); ?></dt>
		<dd>
			<?php echo h($endSemesterExam['EndSemesterExam']['moderation_marks']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Moderation Operator'); ?></dt>
		<dd>
			<?php echo h($endSemesterExam['EndSemesterExam']['moderation_operator']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Revaluation'); ?></dt>
		<dd>
			<?php echo h($endSemesterExam['EndSemesterExam']['revaluation']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Revaluation Marks'); ?></dt>
		<dd>
			<?php echo h($endSemesterExam['EndSemesterExam']['revaluation_marks']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Final Marks'); ?></dt>
		<dd>
			<?php echo h($endSemesterExam['EndSemesterExam']['final_marks']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Created By'); ?></dt>
		<dd>
			<?php echo h($endSemesterExam['EndSemesterExam']['created_by']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Modified By'); ?></dt>
		<dd>
			<?php echo h($endSemesterExam['EndSemesterExam']['modified_by']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Created'); ?></dt>
		<dd>
			<?php echo h($endSemesterExam['EndSemesterExam']['created']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Modified'); ?></dt>
		<dd>
			<?php echo h($endSemesterExam['EndSemesterExam']['modified']); ?>
			&nbsp;
		</dd>
	</dl>
</div>
<div class="actions">
	<h3><?php echo __('Actions'); ?></h3>
	<ul>
		<li><?php echo $this->Html->link(__('Edit End Semester Exam'), array('action' => 'edit', $endSemesterExam['EndSemesterExam']['id'])); ?> </li>
		<li><?php echo $this->Form->postLink(__('Delete End Semester Exam'), array('action' => 'delete', $endSemesterExam['EndSemesterExam']['id']), array('confirm' => __('Are you sure you want to delete # %s?', $endSemesterExam['EndSemesterExam']['id']))); ?> </li>
		<li><?php echo $this->Html->link(__('List End Semester Exams'), array('action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New End Semester Exam'), array('action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Course Mappings'), array('controller' => 'course_mappings', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Course Mapping'), array('controller' => 'course_mappings', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Students'), array('controller' => 'students', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Student'), array('controller' => 'students', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Month Years'), array('controller' => 'month_years', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Month Year'), array('controller' => 'month_years', 'action' => 'add')); ?> </li>
	</ul>
</div>
