<div class="typeOfCertifications view userFrm">
<legend><?php echo __('Type Of Certification'); ?></legend>
	<dl>
		<dt><?php echo __('Id'); ?></dt>
		<dd>
			<?php echo h($typeOfCertification['TypeOfCertification']['id']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Certification'); ?></dt>
		<dd>
			<?php echo h($typeOfCertification['TypeOfCertification']['certification']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Short Code'); ?></dt>
		<dd>
			<?php echo h($typeOfCertification['TypeOfCertification']['short_code']); ?>
			&nbsp;
		</dd>
	<dt><?php echo __('Created By'); ?></dt>
	<dd><?php echo h($typeOfCertification['User']['username']); ?></dd>
	
	<dt><?php echo __('Created On'); ?></dt>
	<dd><?php echo date( "d-M-Y h:i:s", strtotime(h($typeOfCertification['TypeOfCertification']['created'])) ); ?></dd>
	
	<?php if(h($typeOfCertification['ModifiedUser']['username'])){?>		
	<dt><?php echo __('Modified By'); ?></dt>		
	<dd><?php echo h($typeOfCertification['TypeOfCertification']['username']); ?></dd>
	<dt><?php echo __('Modified On'); ?></dt>
	<dd><?php echo date( "d-M-Y h:i:s", strtotime(h($typeOfCertification['TypeOfCertification']['modified'])) ); ?></dd>
	<?php }?>
	</dl>
</div>