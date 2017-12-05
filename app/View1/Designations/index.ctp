<div id="js-load-forms"></div>

<?php 
if($this->Html->checkPathAccesstopath('Designations/add','',$authUser['id'])){
echo $this->Html->link( '<i class="ace-icon fa fa-plus-square"></i>'. 'Designation Add', array("controller"=>"Designations",'action'=>'add'),array('class' =>"js-popup addBtn btn",'escape' => false, 'title'=>'Add Designation','style'=>'margin-bottom:5px;')); 
}
?>

<table cellpadding="0" cellspacing="0" border="0" class="display" id="example">
	<thead>
		<tr>
			<th class="thAction">&nbsp;&nbsp;Action&nbsp;&nbsp;</th>
			<th>Department</th>
			<th>Designation</th>
			<th class="thAction">&nbsp;&nbsp;Delete&nbsp;&nbsp;</th>
		</tr>
	</thead>
	<tbody>
		<?php foreach ($designations as $designation):?>
		<tr class=" gradeX">
			<td>
			<?php 
			if($this->Html->checkPathAccesstopath('Designations/view','',$authUser['id'])){
				echo $this->Html->link("<i class='fa fa-eye fa-lg'></i>", array("controller"=>"Designations",'action' => 'view', $designation['Designation']['id']),array('class'=>'js-popup','escape' => false, 'title'=>'View'));
			}?> &nbsp; &nbsp; <?php if($this->Html->checkPathAccesstopath('Designations/edit','',$authUser['id'])){
				echo $this->Html->link("<i class='fa fa-pencil fa-lg'></i>", array("controller"=>"Designations",'action' => 'edit', $designation['Designation']['id']),array('class'=>'js-popup','title'=>'Edit','escape' => false));
			}?> 			
			</td>
			<td><?php echo h($designation['Department']['department_name']); ?></td>
			<td><?php echo h($designation['Designation']['designation_name']); ?></td>
			<td>
			<?php if($this->Html->checkPathAccesstopath('Designations/delete','',$authUser['id'])){
				echo $this->Form->postLink("<span class='fa fa-times fa-lg red'></span>", array("controller"=>"Designations",'action' => 'delete', $designation['Designation']['id']), array('confirm' => __('Are you sure you want to delete?'),'escape' => false)); 
			}
			?>			
			</td>
		</tr>
		  <?php endforeach; ?>
	</tbody>
	<tfoot>
		<tr>
			<th><input type="text" name="&nbsp;&nbsp;Action&nbsp;&nbsp;" value="&nbsp;&nbsp;Action&nbsp;&nbsp;" class="search_init" /></th>
			<th><input type="text" name="Department" value="Department" class="search_init" /></th>
			<th><input type="text" name="Designation" value="Designation" class="search_init" /></th>
			<th></th>
		</tr>
	</tfoot>
</table>

<span class='breadcrumb1'>
<span class='navbar-brand'><small>MASTERS <i class='ace-icon fa fa-angle-double-right'></i></small></span>
<?php echo $this->Html->link("<span class='navbar-brand'><small>DESIGNATIONS <i class='ace-icon fa fa-angle-double-right'></i></small></span>", array("controller"=>"Designations",'action' => 'index'),array('data-placement'=>'left','escape' => false)); ?>
</span>