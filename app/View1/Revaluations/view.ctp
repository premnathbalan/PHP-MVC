<div class="revaluations view">
<h2><?php echo __('Revaluation'); ?></h2>
	<dl>
		<dt><?php echo __('Id'); ?></dt>
		<dd>
			<?php echo h($revaluation['Revaluation']['id']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Course Mapping'); ?></dt>
		<dd>
			<?php echo $this->Html->link($revaluation['CourseMapping']['program_id'], array('controller' => 'course_mappings', 'action' => 'view', $revaluation['CourseMapping']['id'])); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Student'); ?></dt>
		<dd>
			<?php echo $this->Html->link($revaluation['Student']['name'], array('controller' => 'students', 'action' => 'view', $revaluation['Student']['id'])); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Month Year'); ?></dt>
		<dd>
			<?php echo $this->Html->link($revaluation['MonthYear']['month_id'], array('controller' => 'month_years', 'action' => 'view', $revaluation['MonthYear']['id'])); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Ese Marks'); ?></dt>
		<dd>
			<?php echo h($revaluation['Revaluation']['ese_marks']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Moderation Marks'); ?></dt>
		<dd>
			<?php echo h($revaluation['Revaluation']['moderation_marks']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Moderation Operator'); ?></dt>
		<dd>
			<?php echo h($revaluation['Revaluation']['moderation_operator']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Marks'); ?></dt>
		<dd>
			<?php echo h($revaluation['Revaluation']['marks']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Final Marks'); ?></dt>
		<dd>
			<?php echo h($revaluation['Revaluation']['final_marks']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Created By'); ?></dt>
		<dd>
			<?php echo h($revaluation['Revaluation']['created_by']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Modified By'); ?></dt>
		<dd>
			<?php echo h($revaluation['Revaluation']['modified_by']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Created'); ?></dt>
		<dd>
			<?php echo h($revaluation['Revaluation']['created']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Modified'); ?></dt>
		<dd>
			<?php echo h($revaluation['Revaluation']['modified']); ?>
			&nbsp;
		</dd>
	</dl>
</div>
<div class="actions">
	<h3><?php echo __('Actions'); ?></h3>
	<ul>
		<li><?php echo $this->Html->link(__('Edit Revaluation'), array('action' => 'edit', $revaluation['Revaluation']['id'])); ?> </li>
		<li><?php echo $this->Form->postLink(__('Delete Revaluation'), array('action' => 'delete', $revaluation['Revaluation']['id']), array('confirm' => __('Are you sure you want to delete # %s?', $revaluation['Revaluation']['id']))); ?> </li>
		<li><?php echo $this->Html->link(__('List Revaluations'), array('action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Revaluation'), array('action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Course Mappings'), array('controller' => 'course_mappings', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Course Mapping'), array('controller' => 'course_mappings', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Students'), array('controller' => 'students', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Student'), array('controller' => 'students', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Month Years'), array('controller' => 'month_years', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Month Year'), array('controller' => 'month_years', 'action' => 'add')); ?> </li>
	</ul>
</div>
