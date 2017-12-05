<div id="js-load-forms"></div>

<?php 
if($this->Html->checkPathAccesstopath('UserRoles/add','',$authUser['id'])){
echo $this->Html->link( '<i class="ace-icon fa fa-plus-square"></i>'. 'User Role Add', array("controller"=>"UserRoles",'action'=>'add'),array('class' =>"js-popup addBtn btn",'escape' => false, 'title'=>'Add Designation','style'=>'margin-bottom:5px;')); 
}
?>
<div class="userRoles index">
	<table cellpadding="0" cellspacing="0" border="0" class="display" id="example">
	<thead>
		<tr>
			<th class="thAction">&nbsp;&nbsp;Action&nbsp;&nbsp;</th>
			<th>User Role</th>
			<th class="thAction">&nbsp;&nbsp;Delete&nbsp;&nbsp;</th>
	</tr>
	</thead>
	<tbody>
	<?php foreach ($userRoles as $userRole): ?>
	<tr class=" gradeX">
		<td class="actions">
			<?php 
			if($this->Html->checkPathAccesstopath('UserRoles/view','',$authUser['id'])){
				echo $this->Html->link("<i class='fa fa-eye fa-lg'></i>", array("controller"=>"UserRoles",'action' => 'view', $userRole['UserRole']['id']),array('class'=>'js-popup','escape' => false, 'title'=>'View'));
			}if($this->Html->checkPathAccesstopath('UserRoles/edit','',$authUser['id'])){
				echo $this->Html->link("<i class='fa fa-pencil fa-lg'></i>", array("controller"=>"UserRoles",'action' => 'edit', $userRole['UserRole']['id']),array('title'=>'Edit','escape' => false));
			}
			?>
		</td>
		<td><?php echo h($userRole['UserRole']['user_role']); ?>&nbsp;</td>
		<td class="actions">
			<?php 
			if($this->Html->checkPathAccesstopath('UserRoles/delete','',$authUser['id'])){
				echo $this->Form->postLink("<span class='fa fa-times fa-lg red'></span>", array("controller"=>"UserRoles",'action' => 'delete', $userRole['UserRole']['id']), array('confirm' => __('Are you sure you want to delete?'),'escape' => false)); 
			}
			?>
		</td>
	</tr>
<?php endforeach; ?>
	</tbody>
	<tfoot>
		<tr>
			<th><input type="text" name="&nbsp;&nbsp;Action&nbsp;&nbsp;" value="&nbsp;&nbsp;Action&nbsp;&nbsp;" class="search_init" /></th>
			<th><input type="text" name="User Role" value="User Role" class="search_init" /></th>
			<th></th>
		</tr>
	</tfoot>
	</table>	
</div>

<span class='breadcrumb1'>
<span class='navbar-brand'><small>MASTERS <i class='ace-icon fa fa-angle-double-right'></i></small></span>
<?php echo $this->Html->link("<span class='navbar-brand'><small>USER ROLES <i class='ace-icon fa fa-angle-double-right'></i></small></span>", array("controller"=>"UserRoles",'action' => 'index'),array('data-placement'=>'left','escape' => false)); ?>
</span>