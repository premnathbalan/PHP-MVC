<h2><?php echo __('Month Year'); ?></h2>
<div class="monthYears view userFrm">
<dl>	
	<dt><?php echo __('Month'); ?></dt>
	<dd><?php echo $this->Html->link($monthYear['Month']['month_name'], array('controller' => 'months', 'action' => 'view', $monthYear['Month']['id'])); ?></dd>
	
	<dt><?php echo __('Year'); ?></dt>
	<dd><?php echo h($monthYear['MonthYear']['year']); ?></dd>
	
	<dt><?php echo __('Created By'); ?></dt>
	<dd><?php echo h($monthYear['User']['username']); ?></dd>
	
	<dt><?php echo __('Created On'); ?></dt>
	<dd><?php echo date( "d-M-Y h:i:s", strtotime(h($monthYear['MonthYear']['created'])) ); ?></dd>
	
	<?php if(h($monthYear['ModifiedUser']['username'])){?>		
	<dt><?php echo __('Modified By'); ?></dt>		
	<dd>
		<?php echo h($monthYear['ModifiedUser']['username']); ?>
	</dd>
	<dt><?php echo __('Modified On'); ?></dt>
	<dd>
		<?php echo date( "d-M-Y h:i:s", strtotime(h($monthYear['MonthYear']['modified'])) ); ?>
	</dd>
	<?php }?>
</dl>
</div>
