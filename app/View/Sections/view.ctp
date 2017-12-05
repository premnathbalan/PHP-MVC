<div class="sections view userFrm">
<h2><?php echo __('Section'); ?></h2>
	<dl>		
	<dt><?php echo __('Name'); ?></dt>
	<dd><?php echo h($section['Section']['name']); ?></dd>
	<dt><?php echo __('Created By'); ?></dt>
	<dd><?php echo h($section['User']['username']); ?></dd>
	
	<dt><?php echo __('Created On'); ?></dt>
	<dd><?php echo date( "d-M-Y h:i:s", strtotime(h($section['Section']['created'])) ); ?></dd>
	
	<?php if(h($section['ModifiedUser']['username'])){?>		
	<dt><?php echo __('Modified By'); ?></dt>		
	<dd><?php echo h($section['ModifiedUser']['username']); ?></dd>
	<dt><?php echo __('Modified On'); ?></dt>
	<dd><?php echo date( "d-M-Y h:i:s", strtotime(h($section['Section']['modified'])) ); ?></dd>
	<?php }?>
	</dl>
</div>
