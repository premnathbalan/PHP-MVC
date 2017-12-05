<div class="users view userFrm">

	<dl>
		<dt><?php echo __('User Name'); ?></dt>
		<dd><?php echo h($user['User']['username']); ?></dd>		
		<dt><?php echo __('Department'); ?></dt>
		<dd><?php echo h($user['Department']['department_name']); ?></dd>
		<dt><?php echo __('Designation'); ?></dt>
		<dd><?php echo h($user['Designation']['designation_name']); ?></dd>
		<dt><?php echo __('First Name'); ?></dt>
		<dd><?php echo h($user['User']['name']); ?></dd>
		<dt><?php echo __('Email'); ?></dt>
		<dd><?php echo h($user['User']['email']); ?></dd>
		<dt><?php echo __('Mobile'); ?></dt>
		<dd><?php echo h($user['User']['mobile']); ?></dd>
		<dt><?php echo __('User Role'); ?></dt>
		<dd><?php echo h($user['UserRole']['user_role']); ?></dd>
	
		<dt><?php echo __('Created By'); ?></dt>
		<dd><?php echo h($user['User']['username']); ?></dd>
		<dt><?php echo __('Created On'); ?></dt>
		<dd><?php echo date( "d-M-Y h:i:s", strtotime(h($user['User']['created'])) ); ?></dd>
		
		<?php if(h($user['User']['username'])){?>		
		<dt><?php echo __('Modified By'); ?></dt>		
		<dd><?php echo h($user['User']['username']); ?></dd>
		<dt><?php echo __('Modified On'); ?></dt>
		<dd><?php echo date( "d-M-Y h:i:s", strtotime(h($user['User']['modified'])) ); ?></dd>
		<?php }?>		
	</dl>
</div>

<script>leftMenuSelection('Users');</script>

<span class='breadcrumb1'>
<span class='navbar-brand'><small>MASTER <i class='ace-icon fa fa-angle-double-right'></i></small></span>
<?php echo $this->Html->link("<span class='navbar-brand'><small>STAFF <i class='ace-icon fa fa-angle-double-right'></i></small></span>", array("controller"=>"Users",'action' => 'index'),array('data-placement'=>'left','escape' => false)); ?>
<?php echo $this->Html->link("<span class='navbar-brand'><small>VIEW <i class='ace-icon fa fa-angle-double-right'></i></small></span>", array("controller"=>"Users",'action' => 'view',$viewUserId),array('data-placement'=>'left','escape' => false)); ?>
</span>