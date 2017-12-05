<div id="js-load-forms"></div>

<?php 
if($this->Html->checkPathAccesstopath('CollegeReferences/add','',$authUser['id'])){
	echo $this->Html->link( '<i class="ace-icon fa fa-plus-square"></i>'. 'College Add', array("controller"=>"CollegeReferences",'action'=>'add'),array('class' =>"js-popup addBtn btn",'escape' => false, 'title'=>'Add College','style'=>'margin-bottom:5px;')); 
}
?>

<div class="collegeReferences index">
	<table cellpadding="0" cellspacing="0" border="0" class="display" id="example">
	<thead>
	<tr>
		<th class="thAction">&nbsp;&nbsp;Action&nbsp;&nbsp;</th>
		<th>College Name</th>
		<th>Address1</th>
		<th>Address2</th>
		<th>Address3</th>
		<th>City</th>
		<th>Pincode</th>
		<th>Phone No.</th>
		<th class="thAction">&nbsp;&nbsp;Delete&nbsp;&nbsp;</th>
	</tr>
	</thead>
	<tbody>
	<?php foreach ($collegeReferences as $collegeReference): ?>
	<tr class="gradeX">
		<td class="actions">
			<?php
			if($this->Html->checkPathAccesstopath('CollegeReferences/view','',$authUser['id'])){ 
				echo $this->Html->link("<i class='fa fa-eye fa-lg'></i>", array("controller"=>"CollegeReferences",'action' => 'view', $collegeReference['CollegeReference']['id']),array('class' =>"js-popup",'escape' => false, 'title'=>'View'));
			}if($this->Html->checkPathAccesstopath('CollegeReferences/edit','',$authUser['id'])){ 
				echo $this->Html->link("<i class='fa fa-pencil fa-lg'></i>", array("controller"=>"CollegeReferences",'action' => 'edit', $collegeReference['CollegeReference']['id']),array('class' =>"js-popup", 'title'=>'Edit','escape' => false));
			}
			?>						
		</td>
		<td><?php echo h($collegeReference['CollegeReference']['college_name']); ?></td>
		<td><?php echo h($collegeReference['CollegeReference']['address1']); ?></td>
		<td><?php echo h($collegeReference['CollegeReference']['address2']); ?></td>
		<td><?php echo h($collegeReference['CollegeReference']['address3']); ?></td>
		<td><?php echo h($collegeReference['CollegeReference']['city']); ?></td>
		<td><?php if($collegeReference['CollegeReference']['pincode'] !=0){echo h($collegeReference['CollegeReference']['pincode']);} ?></td>
		<td><?php echo h($collegeReference['CollegeReference']['phone_number']); ?></td>		
		<td class="actions">
			<?php
			if($this->Html->checkPathAccesstopath('CollegeReferences/delete','',$authUser['id'])){ 
				echo $this->Form->postLink("<span class='fa fa-times fa-lg red'></span>", array("controller"=>"CollegeReferences",'action' => 'delete', $collegeReference['CollegeReference']['id']), array('confirm' => __('Are you sure you want to delete?'),'escape' => false, 'title'=>'Delete')); 
			}
			?>						
		</td>
	</tr>
<?php endforeach; ?>
	</tbody>
	<tfoot>
	<tr>
		<th><input type="text" name="&nbsp;&nbsp;Action&nbsp;&nbsp;" value="&nbsp;&nbsp;Action&nbsp;&nbsp;" class="search_init" /></th>
		<th><input type="text" name="College Name" value="College Name" class="search_init" /></th>
		<th><input type="text" name="Address1" value="Address1" class="search_init" /></th>
		<th><input type="text" name="Address2" value="Address2" class="search_init" /></th>
		<th><input type="text" name="Address3" value="Address3" class="search_init" /></th>
		<th><input type="text" name="City" value="City" class="search_init" /></th>
		<th><input type="text" name="Pincode" value="Pincode" class="search_init" /></th>
		<th><input type="text" name="Phone No." value="Phone No." class="search_init" /></th>
		<th></th>
	</tr>
</tfoot>
	</table>
</div>

<span class='breadcrumb1'>
<span class='navbar-brand'><small>MASTERS <i class='ace-icon fa fa-angle-double-right'></i></small></span>
<?php echo $this->Html->link("<span class='navbar-brand'><small>COLLEGE <i class='ace-icon fa fa-angle-double-right'></i></small></span>", array("controller"=>"CollegeReferences",'action' => 'index'),array('data-placement'=>'left','escape' => false)); ?>
</span>	