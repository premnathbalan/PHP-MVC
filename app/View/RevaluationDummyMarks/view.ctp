<div class="revaluationDummyMarks view">
<h2><?php echo __('Revaluation Dummy Mark'); ?></h2>
	<dl>
		<dt><?php echo __('Id'); ?></dt>
		<dd>
			<?php echo h($revaluationDummyMark['RevaluationDummyMark']['id']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Dummy Number'); ?></dt>
		<dd>
			<?php echo $this->Html->link($revaluationDummyMark['DummyNumber']['id'], array('controller' => 'dummy_numbers', 'action' => 'view', $revaluationDummyMark['DummyNumber']['id'])); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Dummy Number'); ?></dt>
		<dd>
			<?php echo h($revaluationDummyMark['RevaluationDummyMark']['dummy_number']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Mark Entry1'); ?></dt>
		<dd>
			<?php echo h($revaluationDummyMark['RevaluationDummyMark']['mark_entry1']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Mark Entry2'); ?></dt>
		<dd>
			<?php echo h($revaluationDummyMark['RevaluationDummyMark']['mark_entry2']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Indicator'); ?></dt>
		<dd>
			<?php echo h($revaluationDummyMark['RevaluationDummyMark']['indicator']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Mark1 Created By'); ?></dt>
		<dd>
			<?php echo h($revaluationDummyMark['RevaluationDummyMark']['mark1_created_by']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Mark2 Created By'); ?></dt>
		<dd>
			<?php echo h($revaluationDummyMark['RevaluationDummyMark']['mark2_created_by']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Modified By'); ?></dt>
		<dd>
			<?php echo h($revaluationDummyMark['RevaluationDummyMark']['modified_by']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Created'); ?></dt>
		<dd>
			<?php echo h($revaluationDummyMark['RevaluationDummyMark']['created']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Created2'); ?></dt>
		<dd>
			<?php echo h($revaluationDummyMark['RevaluationDummyMark']['created2']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Modified'); ?></dt>
		<dd>
			<?php echo h($revaluationDummyMark['RevaluationDummyMark']['modified']); ?>
			&nbsp;
		</dd>
	</dl>
</div>
<div class="actions">
	<h3><?php echo __('Actions'); ?></h3>
	<ul>
		<li><?php echo $this->Html->link(__('Edit Revaluation Dummy Mark'), array('action' => 'edit', $revaluationDummyMark['RevaluationDummyMark']['id'])); ?> </li>
		<li><?php echo $this->Form->postLink(__('Delete Revaluation Dummy Mark'), array('action' => 'delete', $revaluationDummyMark['RevaluationDummyMark']['id']), array('confirm' => __('Are you sure you want to delete # %s?', $revaluationDummyMark['RevaluationDummyMark']['id']))); ?> </li>
		<li><?php echo $this->Html->link(__('List Revaluation Dummy Marks'), array('action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Revaluation Dummy Mark'), array('action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Dummy Numbers'), array('controller' => 'dummy_numbers', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Dummy Number'), array('controller' => 'dummy_numbers', 'action' => 'add')); ?> </li>
	</ul>
</div>
