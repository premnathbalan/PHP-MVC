<div class="continuousAssessmentExams view">
<h2><?php echo __('Continuous Assessment Exam'); ?></h2>
	<dl>
		<dt><?php echo __('Id'); ?></dt>
		<dd>
			<?php echo h($continuousAssessmentExam['ContinuousAssessmentExam']['id']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Course'); ?></dt>
		<dd>
			<?php echo $this->Html->link($continuousAssessmentExam['Course']['course_name'], array('controller' => 'courses', 'action' => 'view', $continuousAssessmentExam['Course']['id'])); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Student'); ?></dt>
		<dd>
			<?php echo $this->Html->link($continuousAssessmentExam['Student']['name'], array('controller' => 'students', 'action' => 'view', $continuousAssessmentExam['Student']['id'])); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Month'); ?></dt>
		<dd>
			<?php echo h($continuousAssessmentExam['ContinuousAssessmentExam']['month']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Year'); ?></dt>
		<dd>
			<?php echo h($continuousAssessmentExam['ContinuousAssessmentExam']['year']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Assessment Number'); ?></dt>
		<dd>
			<?php echo h($continuousAssessmentExam['ContinuousAssessmentExam']['assessment_number']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Absent'); ?></dt>
		<dd>
			<?php echo h($continuousAssessmentExam['ContinuousAssessmentExam']['absent']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Marks'); ?></dt>
		<dd>
			<?php echo h($continuousAssessmentExam['ContinuousAssessmentExam']['marks']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Created By'); ?></dt>
		<dd>
			<?php echo h($continuousAssessmentExam['ContinuousAssessmentExam']['created_by']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Modified By'); ?></dt>
		<dd>
			<?php echo h($continuousAssessmentExam['ContinuousAssessmentExam']['modified_by']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Created'); ?></dt>
		<dd>
			<?php echo h($continuousAssessmentExam['ContinuousAssessmentExam']['created']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Modified'); ?></dt>
		<dd>
			<?php echo h($continuousAssessmentExam['ContinuousAssessmentExam']['modified']); ?>
			&nbsp;
		</dd>
	</dl>
</div>
<div class="actions">
	<h3><?php echo __('Actions'); ?></h3>
	<ul>
		<li><?php echo $this->Html->link(__('Edit Continuous Assessment Exam'), array('action' => 'edit', $continuousAssessmentExam['ContinuousAssessmentExam']['id'])); ?> </li>
		<li><?php echo $this->Form->postLink(__('Delete Continuous Assessment Exam'), array('action' => 'delete', $continuousAssessmentExam['ContinuousAssessmentExam']['id']), array('confirm' => __('Are you sure you want to delete # %s?', $continuousAssessmentExam['ContinuousAssessmentExam']['id']))); ?> </li>
		<li><?php echo $this->Html->link(__('List Continuous Assessment Exams'), array('action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Continuous Assessment Exam'), array('action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Courses'), array('controller' => 'courses', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Course'), array('controller' => 'courses', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Students'), array('controller' => 'students', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Student'), array('controller' => 'students', 'action' => 'add')); ?> </li>
	</ul>
</div>
