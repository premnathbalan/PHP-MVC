<div class="signatures view userFrm">
<legend><?php echo __('Signature'); ?></legend>
	
	<dl>		
		<dt><?php echo __('Name'); ?></dt>
		<dd>
			<?php echo h($signature['Signature']['name']); ?>
		</dd>
		
		<dt><?php echo __('Tamil Name'); ?></dt>
		<dd class='baamini'>
			<?php echo h($signature['Signature']['tamil_name']); ?>
		</dd>
		
		<dt><?php echo __('Role'); ?></dt>
		<dd>
			<?php echo h($signature['Signature']['role']); ?>
		</dd>
		
		<dt><?php echo __('Signature'); ?></dt>
		<dd class='baamini'>
			<?php echo h($signature['Signature']['role_tamil']); ?>
		</dd>
		
		<dt><?php echo __('Created By'); ?></dt>
		<dd>
			<?php echo h($signature['User']['username']); ?>
		</dd>
		<dt><?php echo __('Created On'); ?></dt>
		<dd>
			<?php echo date( "d-M-Y h:i:s", strtotime(h($signature['Signature']['created'])) ); ?>
		</dd>
		
		<?php if(h($signature['ModifiedUser']['username'])){?>		
		<dt><?php echo __('Modified By'); ?></dt>		
		<dd>
			<?php echo h($signature['ModifiedUser']['username']); ?>
		</dd>
		<dt><?php echo __('Modified On'); ?></dt>
		<dd>
			<?php echo date( "d-M-Y h:i:s", strtotime(h($signature['Signature']['modified'])) ); ?>
		</dd>
		<?php }?>
		
		<div style="text-align:center;">	
			<?php echo $this->Html->image(h("certificate_signature/".$signature['Signature']['signature']), ['alt' => $signature['Signature']['signature'],'style'=>'width:100px;height:100px;']);?>
		</div>	
	</dl>
</div>
