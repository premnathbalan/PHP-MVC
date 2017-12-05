<div id="js-load-forms"></div>

<?php 
if($this->Html->checkPathAccesstopath('UniversityReferences/add','',$authUser['id'])){ 
	echo $this->Html->link( '<i class="ace-icon fa fa-plus-square"></i>'. 'University Add', array("controller"=>"UniversityReferences",'action'=>'add'),array('class' =>"js-popup addBtn btn",'escape' => false, 'title'=>'Add University','style'=>'margin-bottom:5px;')); 
}
?>				

<div class="universityReferences index">
	<table cellpadding="0" cellspacing="0" border="0" class="display" id="example">
	<thead>
	<tr>
			<th class="thAction">&nbsp;&nbsp;Action&nbsp;&nbsp;</th>
			<th>University Name</th>
			<th class="thAction">&nbsp;&nbsp;Delete&nbsp;&nbsp;</th>
	</tr>
	</thead>
	<tbody>
	<?php foreach ($universityReferences as $universityReference): ?>
	<tr class=" gradeX">
		<td class="actions">
			<?php 
			if($this->Html->checkPathAccesstopath('UniversityReferences/view','',$authUser['id'])){ 
			echo $this->Html->link("<i class='fa fa-eye fa-lg'></i>", array("controller"=>"UniversityReferences",'action' => 'view', $universityReference['UniversityReference']['id']),array('class' =>"js-popup",'escape' => false, 'title'=>'View'));
			}if($this->Html->checkPathAccesstopath('UniversityReferences/edit','',$authUser['id'])){ 
			echo $this->Html->link("<i class='fa fa-pencil fa-lg'></i>", array("controller"=>"UniversityReferences",'action' => 'edit', $universityReference['UniversityReference']['id']),array('class' =>"js-popup", 'title'=>'Edit','escape' => false));
			}
			?>
		</td>
		<td><?php echo h($universityReference['UniversityReference']['university_name']); ?></td>
		<td class="actions">
			<?php 
			if($this->Html->checkPathAccesstopath('UniversityReferences/delete','',$authUser['id'])){ 
			echo $this->Form->postLink("<span class='fa fa-times fa-lg red'></span>", array("controller"=>"UniversityReferences",'action' => 'delete', $universityReference['UniversityReference']['id']), array('confirm' => __('Are you sure you want to delete?'),'escape' => false, 'title'=>'Delete')); 
			}
			?>
		</td>
	</tr>
<?php endforeach; ?>
</tbody>
<tfoot>
	<tr>
		<th><input type="text" name="&nbsp;&nbsp;Action&nbsp;&nbsp;" value="&nbsp;&nbsp;Action&nbsp;&nbsp;" class="search_init" /></th>
		<th><input type="text" name="University Name" value="University Name" class="search_init" /></th>
		<th></th>
	</tr>
</tfoot>	
</table>

<span class='breadcrumb1'>
<span class='navbar-brand'><small>MASTERS <i class='ace-icon fa fa-angle-double-right'></i></small></span>
<?php echo $this->Html->link("<span class='navbar-brand'><small>UNIVERSITY <i class='ace-icon fa fa-angle-double-right'></i></small></span>", array("controller"=>"UniversityReferences",'action' => 'index'),array('data-placement'=>'left','escape' => false)); ?>
</span>