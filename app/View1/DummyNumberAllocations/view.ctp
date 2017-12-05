<div class="dummyNumberAllocations view">
<h2><?php echo __('Dummy Number Allocation'); ?></h2>
	<dl>
		<dt><?php echo __('Id'); ?></dt>
		<dd>
			<?php echo h($dummyNumberAllocation['DummyNumberAllocation']['id']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Month Year Id'); ?></dt>
		<dd>
			<?php echo h($dummyNumberAllocation['DummyNumberAllocation']['month_year_id']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Dummy Number'); ?></dt>
		<dd>
			<?php echo h($dummyNumberAllocation['DummyNumberAllocation']['dummy_number']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Student Id'); ?></dt>
		<dd>
			<?php echo h($dummyNumberAllocation['DummyNumberAllocation']['student_id']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Indicator'); ?></dt>
		<dd>
			<?php echo h($dummyNumberAllocation['DummyNumberAllocation']['indicator']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Created By'); ?></dt>
		<dd>
			<?php echo h($dummyNumberAllocation['DummyNumberAllocation']['created_by']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Modified By'); ?></dt>
		<dd>
			<?php echo h($dummyNumberAllocation['DummyNumberAllocation']['modified_by']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Created'); ?></dt>
		<dd>
			<?php echo h($dummyNumberAllocation['DummyNumberAllocation']['created']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Modified'); ?></dt>
		<dd>
			<?php echo h($dummyNumberAllocation['DummyNumberAllocation']['modified']); ?>
			&nbsp;
		</dd>
	</dl>
</div>
<div class="actions">
	<h3><?php echo __('Actions'); ?></h3>
	<ul>
		<li><?php echo $this->Html->link(__('Edit Dummy Number Allocation'), array('action' => 'edit', $dummyNumberAllocation['DummyNumberAllocation']['id'])); ?> </li>
		<li><?php echo $this->Form->postLink(__('Delete Dummy Number Allocation'), array('action' => 'delete', $dummyNumberAllocation['DummyNumberAllocation']['id']), array('confirm' => __('Are you sure you want to delete # %s?', $dummyNumberAllocation['DummyNumberAllocation']['id']))); ?> </li>
		<li><?php echo $this->Html->link(__('List Dummy Number Allocations'), array('action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Dummy Number Allocation'), array('action' => 'add')); ?> </li>
	</ul>
</div>
