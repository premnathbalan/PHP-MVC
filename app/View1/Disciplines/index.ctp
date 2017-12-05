<div id="js-load-forms"></div>

<?php 
$chkDeptAdd = $this->Html->checkPathAccesstopath('Disciplines/add','',$authUser['id']);
//pr($chkDeptAdd);
if($chkDeptAdd){
	echo $this->Html->link('<i class="ace-icon fa fa-plus-square"></i>'. 'Discipline Add', array("controller"=>"Disciplines",'action' => 'add'),array('class' =>"js-popup addBtn btn",'escape' => false,'style'=>'margin-bottom:5px;')); 
}
?>

<table cellpadding="0" cellspacing="0" border="0" class="display" id="example">
	<thead>
		<tr>
			<th class="thAction">&nbsp;&nbsp;Action&nbsp;&nbsp;</th>
			<th>Discipline Name</th>
			<th>Discipline Name Tamil</th>
			<th class="thAction">&nbsp;&nbsp;Delete&nbsp;&nbsp;</th>
		</tr>
	</thead>
	<tbody>
		<?php foreach ($disciplines as $discipline):?>
		<tr class=" gradeX">
			<td>
			<?php 
			//if($this->Html->checkPathAccesstopath('Disciplines/view','',$authUser['id'])){
				echo $this->Html->link("<i class='fa fa-eye fa-lg'></i>",array("controller"=>"Disciplines",'action' => 'view',$discipline['Discipline']['id']), array('class' =>"js-popup",'escape' => false));
			//}?>&nbsp; &nbsp; 
			<?php //if($this->Html->checkPathAccesstopath('Disciplines/edit','',$authUser['id'])){
				echo $this->Html->link("<i class='fa fa-pencil fa-lg'></i>",array("controller"=>"Disciplines",'action' => 'edit',$discipline['Discipline']['id']),array('class' =>"js-popup", 'escape' => false));
			//}?>		
			</td>
			<td><?php echo h($discipline['Discipline']['discipline_name']); ?></td>
			<td class='baamini'><?php echo h($discipline['Discipline']['discipline_name_tamil']); ?></td>			
			<td>
			<?php //if($this->Html->checkPathAccesstopath('Disciplines/delete','',$authUser['id'])){ 
				echo $this->Form->postLink("<span class='fa fa-times fa-lg red'></span>", array("controller"=>"Disciplines",'action' => 'delete', $discipline['Discipline']['id']), array('confirm' => __('Are you sure you want to delete # %s?', $discipline['Discipline']['id']),'escape' => false, 'title'=>'Delete')); 
			//}
			?>			
			</td>
		</tr>
		  <?php endforeach; ?>		
	</tbody>
	<tfoot>
		<tr>
			<th><input type="text" name="&nbsp;&nbsp;Action&nbsp;&nbsp;" value="&nbsp;&nbsp;Action&nbsp;&nbsp;" class="search_init" /></th>
			<th><input type="text" name="Discipline Name" value="Discipline Name" class="search_init" /></th>
			<th><input type="text" name="Discipline Name Tamil" value="Discipline Name Tamil" class="search_init" /></th>
			<th></th>
		</tr>
	</tfoot>
</table>

<span class='breadcrumb1'>
<span class='navbar-brand'><small>MASTERS <i class='ace-icon fa fa-angle-double-right'></i></small></span>
<?php echo $this->Html->link("<span class='navbar-brand'><small>Disciplines <i class='ace-icon fa fa-angle-double-right'></i></small></span>", array("controller"=>"Disciplines",'action' => 'index'),array('data-placement'=>'left','escape' => false)); ?>
</span>