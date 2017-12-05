<div class="batches view userFrm">	
	<legend><?php echo __('Batch'); ?></legend>
	<dl>
	<dt><?php echo __('Batch From'); ?></dt>
	<dd><?php echo h($batch['Batch']['batch_from']); ?></dd>
	
	<dt><?php echo __('Batch To'); ?></dt>
	<dd><?php echo h($batch['Batch']['batch_to']); ?></dd>
	
	<dt><?php echo __('Academic'); ?></dt>
	<dd><?php echo h($batch['Batch']['academic']);?></dd>
	
	<dt><?php echo __('Publishing Date'); ?></dt>
	<dd><?php if($batch['Batch']['consolidated_pub_date'] != '0000-00-00'){echo date( "d-M-Y h:i:s", strtotime(h($batch['Batch']['consolidated_pub_date'])) );} ?></dd>
		
	<dt><?php echo __('Created By'); ?></dt>
	<dd><?php echo h($batch['User']['username']); ?></dd>
	
	<dt><?php echo __('Created On'); ?></dt>
	<dd><?php echo date( "d-M-Y h:i:s", strtotime(h($batch['Batch']['created'])) ); ?></dd>
	
	<?php if(h($batch['ModifiedUser']['username'])){?>		
	<dt><?php echo __('Modified By'); ?></dt>		
	<dd><?php echo h($batch['ModifiedUser']['username']); ?></dd>
	<dt><?php echo __('Modified On'); ?></dt>
	<dd><?php echo date( "d-M-Y h:i:s", strtotime(h($batch['Batch']['modified'])) ); ?></dd>
	<?php }?>
	</dl>
</div>