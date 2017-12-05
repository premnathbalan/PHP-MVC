<div class="collegeReferences view userFrm">
<legend><?php echo __('College Reference'); ?></legend>
	<dl>		
		<dt><?php echo __('College Name'); ?></dt>
		<dd><?php echo h($collegeReference['CollegeReference']['college_name']); ?></dd>
		<dt><?php echo __('Address1'); ?></dt>
		<dd><?php echo h($collegeReference['CollegeReference']['address1']); ?></dd>
		<dt><?php echo __('Address2'); ?></dt>
		<dd><?php echo h($collegeReference['CollegeReference']['address2']); ?></dd>
		<dt><?php echo __('Address3'); ?></dt>
		<dd><?php echo h($collegeReference['CollegeReference']['address3']); ?></dd>
		<dt><?php echo __('City'); ?></dt>
		<dd><?php echo h($collegeReference['CollegeReference']['city']); ?></dd>
		<dt><?php echo __('Pin Code'); ?></dt>
		<dd><?php if($collegeReference['CollegeReference']['pincode'] !=0){echo h($collegeReference['CollegeReference']['pincode']);} ?></dd>
		<dt><?php echo __('Phone No.'); ?></dt>
		<dd><?php echo h($collegeReference['CollegeReference']['phone_number']); ?></dd>
		
		<dt><?php echo __('Created By'); ?></dt>
		<dd><?php echo h($collegeReference['User']['username']); ?></dd>
		<dt><?php echo __('Created On'); ?></dt>
		<dd><?php echo date( "d-M-Y h:i:s", strtotime(h($collegeReference['CollegeReference']['created'])) ); ?></dd>
		
		<?php if(h($collegeReference['ModifiedUser']['username'])){?>		
		<dt><?php echo __('Modified By'); ?></dt>		
		<dd><?php echo h($collegeReference['ModifiedUser']['username']); ?></dd>
		<dt><?php echo __('Modified On'); ?></dt>
		<dd><?php echo date( "d-M-Y h:i:s", strtotime(h($collegeReference['CollegeReference']['modified'])) ); ?></dd>
		<?php }?>
	</dl>
</div>

