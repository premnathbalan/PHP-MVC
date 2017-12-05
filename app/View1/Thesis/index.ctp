<div id="js-load-forms"></div>

<?php 
$chkDeptAdd = $this->Html->checkPathAccesstopath('Thesis/add','',$authUser['id']);
//pr($chkDeptAdd);
if($chkDeptAdd){
	echo $this->Html->link('<i class="ace-icon fa fa-plus-square"></i>'. 'Thesis Add', array("controller"=>"Thesis",'action' => 'add'),array('class' =>"js-popup addBtn btn",'escape' => false,'style'=>'margin-bottom:5px;')); 
}
?>

<table cellpadding="0" cellspacing="0" border="0" class="display" id="example">
	<thead>
		<tr>
			<th class="thAction">&nbsp;&nbsp;Action&nbsp;&nbsp;</th>
			<th>Thesis Name</th>
			<th>Thesis Name Tamil</th>
			<th class="thAction">&nbsp;&nbsp;Delete&nbsp;&nbsp;</th>
		</tr>
	</thead>
	<tbody>
		<?php foreach ($thesis as $thesi):?>
		<tr class=" gradeX">
			<td>
			<?php 
			//if($this->Html->checkPathAccesstopath('Thesis/view','',$authUser['id'])){
				echo $this->Html->link("<i class='fa fa-eye fa-lg'></i>",array("controller"=>"Thesis",'action' => 'view',$thesi['Thesi']['id']), array('class' =>"js-popup",'escape' => false));
			//}
			?>&nbsp; &nbsp; <?php 
			//if($this->Html->checkPathAccesstopath('Thesis/edit','',$authUser['id'])){
				echo $this->Html->link("<i class='fa fa-pencil fa-lg'></i>",array("controller"=>"Thesis",'action' => 'edit',$thesi['Thesi']['id']),array('class' =>"js-popup", 'escape' => false));
			//}
			?>		
			</td>
			<td><?php echo h($thesi['Thesi']['thesis_name']); ?></td>
			<td class='baamini'><?php echo h($thesi['Thesi']['thesis_name_tamil']); ?></td>			
			<td>
			<?php //if($this->Html->checkPathAccesstopath('Thesis/delete','',$authUser['id'])){ 
				echo $this->Form->postLink("<span class='fa fa-times fa-lg red'></span>", array("controller"=>"Thesis",'action' => 'delete', $thesi['Thesi']['id']), array('confirm' => __('Are you sure you want to delete # %s?', $thesi['Thesi']['id']),'escape' => false, 'title'=>'Delete')); 
			//}
			?>			
			</td>
		</tr>
		  <?php endforeach; ?>		
	</tbody>
	<tfoot>
		<tr>
			<th><input type="text" name="&nbsp;&nbsp;Action&nbsp;&nbsp;" value="&nbsp;&nbsp;Action&nbsp;&nbsp;" class="search_init" /></th>
			<th><input type="text" name="Thesis Name" value="Thesis Name" class="search_init" /></th>
			<th><input type="text" name="Thesis Name Tamil" value="Thesis Name Tamil" class="search_init" /></th>
			<th></th>
		</tr>
	</tfoot>
</table>

<span class='breadcrumb1'>
<span class='navbar-brand'><small>MASTERS <i class='ace-icon fa fa-angle-double-right'></i></small></span>
<?php echo $this->Html->link("<span class='navbar-brand'><small>THESIS <i class='ace-icon fa fa-angle-double-right'></i></small></span>", array("controller"=>"Thesis",'action' => 'index'),array('data-placement'=>'left','escape' => false)); ?>
</span>