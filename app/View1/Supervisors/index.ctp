<div id="js-load-forms"></div>

<?php 
$chkDeptAdd = $this->Html->checkPathAccesstopath('Supervisors/add','',$authUser['id']);
//pr($chkDeptAdd);
if($chkDeptAdd){
	echo $this->Html->link('<i class="ace-icon fa fa-plus-square"></i>'. 'Supervisor Add', array("controller"=>"Supervisors",'action' => 'add'),array('class' =>"js-popup addBtn btn",'escape' => false,'style'=>'margin-bottom:5px;')); 
}
?>

<table cellpadding="0" cellspacing="0" border="0" class="display" id="example">
	<thead>
		<tr>
			<th class="thAction">&nbsp;&nbsp;Action&nbsp;&nbsp;</th>
			<th>Supervisor Name</th>
			<th>Supervisor Name Tamil</th>
			<th class="thAction">&nbsp;&nbsp;Delete&nbsp;&nbsp;</th>
		</tr>
	</thead>
	<tbody>
		<?php foreach ($supervisors as $supervisor):?>
		<tr class=" gradeX">
			<td>
			<?php 
			//if($this->Html->checkPathAccesstopath('Supervisors/view','',$authUser['id'])){
				echo $this->Html->link("<i class='fa fa-eye fa-lg'></i>",array("controller"=>"Supervisors",'action' => 'view',$supervisor['Supervisor']['id']), array('class' =>"js-popup",'escape' => false));
			//}?>&nbsp; &nbsp; 
			<?php //if($this->Html->checkPathAccesstopath('Supervisors/edit','',$authUser['id'])){
				echo $this->Html->link("<i class='fa fa-pencil fa-lg'></i>",array("controller"=>"Supervisors",'action' => 'edit',$supervisor['Supervisor']['id']),array('class' =>"js-popup", 'escape' => false));
			//}?>		
			</td>
			<td><?php echo h($supervisor['Supervisor']['supervisor_name']); ?></td>
			<td class='baamini'><?php echo h($supervisor['Supervisor']['supervisor_name_tamil']); ?></td>			
			<td>
			<?php //if($this->Html->checkPathAccesstopath('Supervisors/delete','',$authUser['id'])){ 
				echo $this->Form->postLink("<span class='fa fa-times fa-lg red'></span>", array("controller"=>"Supervisors",'action' => 'delete', $supervisor['Supervisor']['id']), array('confirm' => __('Are you sure you want to delete # %s?', $supervisor['Supervisor']['id']),'escape' => false, 'title'=>'Delete')); 
			//}
			?>			
			</td>
		</tr>
		  <?php endforeach; ?>		
	</tbody>
	<tfoot>
		<tr>
			<th><input type="text" name="&nbsp;&nbsp;Action&nbsp;&nbsp;" value="&nbsp;&nbsp;Action&nbsp;&nbsp;" class="search_init" /></th>
			<th><input type="text" name="Supervisor Name" value="Supervisor Name" class="search_init" /></th>
			<th><input type="text" name="Supervisor Name Tamil" value="Supervisor Name Tamil" class="search_init" /></th>
			<th></th>
		</tr>
	</tfoot>
</table>

<span class='breadcrumb1'>
<span class='navbar-brand'><small>MASTERS <i class='ace-icon fa fa-angle-double-right'></i></small></span>
<?php echo $this->Html->link("<span class='navbar-brand'><small>Supervisors <i class='ace-icon fa fa-angle-double-right'></i></small></span>", array("controller"=>"Supervisors",'action' => 'index'),array('data-placement'=>'left','escape' => false)); ?>
</span>