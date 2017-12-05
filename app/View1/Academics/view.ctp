<div class="academics view userFrm">
	<legend><?php echo __('Program'); ?></legend>
	<dl>
		<dt><?php echo __('Program Name'); ?></dt>
		<dd><?php echo h($academic['Academic']['academic_name']); ?></dd>
		
		<dt><?php echo __('Program Type'); ?></dt>
		<dd><?php echo h($academic['Academic']['academic_type']); ?></dd>
		
		<dt><?php echo __('Short Code'); ?></dt>
		<dd><?php echo h($academic['Academic']['short_code']); ?></dd>
		
		<dt><?php echo __('Program Name Tamil'); ?></dt>
		<dd class='baamini'><?php echo h($academic['Academic']['academic_name_tamil']); ?></dd>
		
		<dt><?php echo __('Created By'); ?></dt>
		<dd><?php echo h($academic['User']['username']); ?></dd>
		
		<dt><?php echo __('Created On'); ?></dt>
		<dd><?php echo date( "d-M-Y h:i:s", strtotime(h($academic['Academic']['created'])) ); ?></dd>
		
		<?php if(h($academic['ModifiedUser']['username'])){?>		
		<dt><?php echo __('Modified By'); ?></dt>		
		<dd><?php echo h($academic['ModifiedUser']['username']); ?></dd>
		<dt><?php echo __('Modified On'); ?></dt>
		<dd><?php echo date( "d-M-Y h:i:s", strtotime(h($academic['Academic']['modified'])) ); ?></dd>
		<?php }?>
	</dl>	
</div>