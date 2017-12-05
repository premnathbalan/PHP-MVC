<div class="projectReviews view">
<h2><?php echo __('Project Review'); ?></h2>
	<dl>
		<dt><?php echo __('Id'); ?></dt>
		<dd>
			<?php echo h($projectReview['ProjectReview']['id']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Cae Project'); ?></dt>
		<dd>
			<?php echo $this->Html->link($projectReview['CaeProject']['id'], array('controller' => 'cae_projects', 'action' => 'view', $projectReview['CaeProject']['id'])); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Student'); ?></dt>
		<dd>
			<?php echo $this->Html->link($projectReview['Student']['name'], array('controller' => 'students', 'action' => 'view', $projectReview['Student']['id'])); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Month Year'); ?></dt>
		<dd>
			<?php echo $this->Html->link($projectReview['MonthYear']['month_id'], array('controller' => 'month_years', 'action' => 'view', $projectReview['MonthYear']['id'])); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Marks'); ?></dt>
		<dd>
			<?php echo h($projectReview['ProjectReview']['marks']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Created By'); ?></dt>
		<dd>
			<?php echo h($projectReview['ProjectReview']['created_by']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Modified By'); ?></dt>
		<dd>
			<?php echo h($projectReview['ProjectReview']['modified_by']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Created'); ?></dt>
		<dd>
			<?php echo h($projectReview['ProjectReview']['created']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Modified'); ?></dt>
		<dd>
			<?php echo h($projectReview['ProjectReview']['modified']); ?>
			&nbsp;
		</dd>
	</dl>
</div>
<div class="actions">
	<h3><?php echo __('Actions'); ?></h3>
	<ul>
		<li><?php echo $this->Html->link(__('Edit Project Review'), array('action' => 'edit', $projectReview['ProjectReview']['id'])); ?> </li>
		<li><?php echo $this->Form->postLink(__('Delete Project Review'), array('action' => 'delete', $projectReview['ProjectReview']['id']), array('confirm' => __('Are you sure you want to delete # %s?', $projectReview['ProjectReview']['id']))); ?> </li>
		<li><?php echo $this->Html->link(__('List Project Reviews'), array('action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Project Review'), array('action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Cae Projects'), array('controller' => 'cae_projects', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Cae Project'), array('controller' => 'cae_projects', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Students'), array('controller' => 'students', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Student'), array('controller' => 'students', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Month Years'), array('controller' => 'month_years', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Month Year'), array('controller' => 'month_years', 'action' => 'add')); ?> </li>
	</ul>
</div>
