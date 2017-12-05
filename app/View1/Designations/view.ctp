<div class="designations view userFrm">
		<legend><?php echo __('Designation View'); ?></legend>
	<dl>
		<dt><?php echo __('Designation Name'); ?></dt>
		<dd><?php echo h($designation['Designation']['designation_name']); ?></dd>
		<dt><?php echo __('Created By'); ?></dt>
		<dd><?php echo h($designation['User']['username']); ?></dd>
		<dt><?php echo __('Created On'); ?></dt>
		<dd><?php echo date( "d-M-Y h:i:s", strtotime(h($designation['Designation']['created'])) ); ?></dd>
		
		<?php if(h($designation['ModifiedUser']['username'])){?>		
		<dt><?php echo __('Modified By'); ?></dt>		
		<dd><?php echo h($designation['ModifiedUser']['username']); ?></dd>
		<dt><?php echo __('Modified On'); ?></dt>
		<dd><?php echo date( "d-M-Y h:i:s", strtotime(h($designation['Designation']['modified'])) ); ?></dd>
		<?php }?>
	</dl>
