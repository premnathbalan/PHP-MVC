<div class="disciplines view userFrm">
	<legend><?php echo __('Discipline View'); ?></legend>
	<dl>
		<!--<dt><?php echo __('Id'); ?></dt>
		<dd>
			<?php echo h($discipline['Discipline']['id']); ?>
		</dd>-->
		<dt><?php echo __('Discipline Name'); ?></dt>
		<dd>
			<?php echo h($discipline['Discipline']['discipline_name']); ?>
		</dd>
		<dt><?php echo __('Discipline Name Tamil'); ?></dt>
		<dd class='baamini'>
			<?php echo h($discipline['Discipline']['discipline_name_tamil']); ?>
		</dd>
		<dt><?php echo __('Created By'); ?></dt>
		<dd>
			<?php echo h($discipline['User']['username']); ?>
		</dd>
		<dt><?php echo __('Created On'); ?></dt>
		<dd>
			<?php echo date( "d-M-Y h:i:s", strtotime(h($discipline['Discipline']['created'])) ); ?>
		</dd>
		
		<?php if(h($discipline['ModifiedUser']['username'])){?>		
		<dt><?php echo __('Modified By'); ?></dt>		
		<dd>
			<?php echo h($discipline['ModifiedUser']['username']); ?>
		</dd>
		<dt><?php echo __('Modified On'); ?></dt>
		<dd>
			<?php echo date( "d-M-Y h:i:s", strtotime(h($discipline['Discipline']['modified'])) ); ?>
		</dd>
		<?php }?>
		
	</dl>
</div>
