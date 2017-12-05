<div class="projectVivas view">
<h2><?php echo __('Project Viva'); ?></h2>
	<dl>
		<dt><?php echo __('Id'); ?></dt>
		<dd>
			<?php echo h($projectViva['ProjectViva']['id']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Ese Project'); ?></dt>
		<dd>
			<?php echo $this->Html->link($projectViva['EseProject']['course_mapping_id'], array('controller' => 'ese_projects', 'action' => 'view', $projectViva['EseProject']['id'])); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Student'); ?></dt>
		<dd>
			<?php echo $this->Html->link($projectViva['Student']['name'], array('controller' => 'students', 'action' => 'view', $projectViva['Student']['id'])); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Month Year'); ?></dt>
		<dd>
			<?php echo $this->Html->link($projectViva['MonthYear']['month_id'], array('controller' => 'month_years', 'action' => 'view', $projectViva['MonthYear']['id'])); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Marks'); ?></dt>
		<dd>
			<?php echo h($projectViva['ProjectViva']['marks']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Created By'); ?></dt>
		<dd>
			<?php echo h($projectViva['ProjectViva']['created_by']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Modified By'); ?></dt>
		<dd>
			<?php echo h($projectViva['ProjectViva']['modified_by']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Created'); ?></dt>
		<dd>
			<?php echo h($projectViva['ProjectViva']['created']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Modified'); ?></dt>
		<dd>
			<?php echo h($projectViva['ProjectViva']['modified']); ?>
			&nbsp;
		</dd>
	</dl>
</div>
<div class="actions">
	<h3><?php echo __('Actions'); ?></h3>
	<ul>
		<li><?php echo $this->Html->link(__('Edit Project Viva'), array('action' => 'edit', $projectViva['ProjectViva']['id'])); ?> </li>
		<li><?php echo $this->Form->postLink(__('Delete Project Viva'), array('action' => 'delete', $projectViva['ProjectViva']['id']), array('confirm' => __('Are you sure you want to delete # %s?', $projectViva['ProjectViva']['id']))); ?> </li>
		<li><?php echo $this->Html->link(__('List Project Vivas'), array('action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Project Viva'), array('action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Ese Projects'), array('controller' => 'ese_projects', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Ese Project'), array('controller' => 'ese_projects', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Students'), array('controller' => 'students', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Student'), array('controller' => 'students', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Month Years'), array('controller' => 'month_years', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Month Year'), array('controller' => 'month_years', 'action' => 'add')); ?> </li>
	</ul>
</div>
