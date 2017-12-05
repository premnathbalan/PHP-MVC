<div id="js-load-forms"></div>

<?php 
if($this->Html->checkPathAccesstopath('Users/add','',$authUser['id'])){
echo $this->Html->link( '<i class="ace-icon fa fa-plus-square"></i>'. 'Staff Add', array("controller"=>"Users",'action'=>'add'),array('class' =>"addBtn btn",'escape' => false, 'title'=>'Add Staff','style'=>'margin-bottom:5px;')); 
}
?>

<table cellpadding="0" cellspacing="0" border="0" class="display" id="example">
	<thead>
		<tr>
			<th class="thAction">&nbsp;&nbsp;Action&nbsp;&nbsp;</th>
			<th>Username</th>
			<th>Department</th>
			<th>Designation</th>
			<th>Name</th>
			<th>Email</th>
			<th>Mobile</th>
			<th>User&nbsp;Role</th>
			<th class="thAction">&nbsp;&nbsp;Delete&nbsp;&nbsp;</th>
		</tr>
	</thead>
	<tbody>
		<?php $i = 0;foreach ($users as $user):?>
		<tr class=" gradeX">
			<td>
			<?php 
			if($this->Html->checkPathAccesstopath('Users/view','',$authUser['id'])){
				echo $this->Html->link("<i class='fa fa-eye fa-lg'></i>", array("controller"=>"Users",'action' => 'view', $user['User']['id']),array('escape' => false, 'title'=>'View')); 
			}?> &nbsp;
			<?php if($this->Html->checkPathAccesstopath('Users/edit','',$authUser['id'])){
				echo $this->Html->link("<i class='fa fa-pencil fa-lg'></i>", array("controller"=>"Users",'action' => 'edit', $user['User']['id']),array('title'=>'Edit','escape' => false));
			}?>	
			</td>
			<td><?php echo h($user['User']['username']); ?></td>
			<td><?php echo h($user['Department']['department_name']); ?></td>
			<td><?php echo h($user['Designation']['designation_name']); ?></td>
			<td><?php echo h($user['User']['name']); ?></td>
			<td><?php echo h($user['User']['email']); ?></td>
			<td><?php echo h($user['User']['mobile']); ?></td>
			<td><?php echo h($user['UserRole']['user_role']); ?></td>
			<td>
			
			<?php if($this->Html->checkPathAccesstopath('Users/delete','',$authUser['id'])){ 
				echo $this->Form->postLink("<span class='fa fa-times fa-lg red'></span>", array("controller"=>"Users",'action' => 'delete', $user['User']['id']), array('confirm' => __('Are you sure you want to delete # %s?', $user['User']['id']),'escape' => false, 'title'=>'Delete')); 
			}
			?>			
			</td>
		</tr>
		  <?php endforeach; ?>		
	</tbody>
	<tfoot>
		<tr>
			<th><input type="text" name="&nbsp;&nbsp;Action&nbsp;&nbsp;" value="&nbsp;&nbsp;Action&nbsp;&nbsp;" class="search_init" /></th>
			<th><input type="text" name="Username" value="Username" class="search_init" /></th>
			<th><input type="text" name="Department" value="Department" class="search_init" /></th>
			<th><input type="text" name="Designation" value="Designation" class="search_init" /></th>
			<th><input type="text" name="Name" value="Name" class="search_init" /></th>
			<th><input type="text" name="Email" value="Email" class="search_init" /></th>
			<th><input type="text" name="Mobile" value="Mobile" class="search_init" /></th>
			<th><input type="text" name="User Role" value="User Role" class="search_init" /></th>
			<th></th>
		</tr>
	</tfoot>
</table>

<span class='breadcrumb1'>
<span class='navbar-brand'><small>MASTERS <i class='ace-icon fa fa-angle-double-right'></i></small></span>
<?php echo $this->Html->link("<span class='navbar-brand'><small>STAFF <i class='ace-icon fa fa-angle-double-right'></i></small></span>", array("controller"=>"Users",'action' => 'index'),array('data-placement'=>'left','escape' => false)); ?>
</span>