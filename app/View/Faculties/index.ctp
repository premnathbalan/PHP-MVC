<div id="js-load-forms"></div>

<?php 
if($this->Html->checkPathAccesstopath('Faculties/add','',$authUser['id'])){
echo $this->Html->link( '<i class="ace-icon fa fa-plus-square"></i>'. 'Faculty Add', array("controller"=>"Faculties",'action'=>'add'),array('class' =>"js-popup addBtn btn",'escape' => false,'style'=>'margin-bottom:5px;')); 
}
?>

<div class="faculties index">
	<table cellpadding="0" cellspacing="0" border="0" class="display" id="example">
	<thead>
	<tr>
		<th class="thAction">&nbsp;&nbsp;Action&nbsp;&nbsp;</th>
		<th>Faculty Name</th>
		<th>Faculty Tamil Name</th>
		<th class="thAction">&nbsp;&nbsp;Delete&nbsp;&nbsp;</th>
	</tr>
	</thead>
	<tbody>
	<?php foreach ($faculties as $faculty): ?>
	<tr class="gradeX">
		<td class="actions">
			<?php
			if($this->Html->checkPathAccesstopath('Faculties/view','',$authUser['id'])){ 
				echo $this->Html->link("<i class='fa fa-eye fa-lg'></i>", array("controller"=>"Faculties",'action' => 'view', $faculty['Faculty']['id']),array('class' =>"js-popup",'escape' => false));
			}if($this->Html->checkPathAccesstopath('Faculties/edit','',$authUser['id'])){
				echo $this->Html->link("<i class='fa fa-pencil fa-lg'></i>", array("controller"=>"Faculties",'action' => 'edit', $faculty['Faculty']['id']),array('class' =>"js-popup", 'title'=>'Edit','escape' => false));
			}
			?>			
		</td>
		<td><?php echo h($faculty['Faculty']['faculty_name']); ?></td>
		<td class='baamini'><?php echo h($faculty['Faculty']['faculty_name_tamil']); ?></td>
		<td class="actions">
			<?php
			if($this->Html->checkPathAccesstopath('Faculties/delete','',$authUser['id'])){
				echo $this->Form->postLink("<span class='fa fa-times fa-lg red'></span>", array("controller"=>"Faculties",'action' => 'delete', $faculty['Faculty']['id']), array('confirm' => __('Are you sure you want to delete?'),'escape' => false));
			} 
			?>			
		</td>
	</tr>
	<?php endforeach; ?>
	</tbody>
	<tfoot>
		<tr>
			<th><input type="text" name="&nbsp;&nbsp;Action&nbsp;&nbsp;" value="&nbsp;&nbsp;Action&nbsp;&nbsp;" class="search_init" /></th>
			<th><input type="text" name="Faculty Name" value="Faculty Name" class="search_init" /></th>
			<th><input type="text" name="Faculty Tamil Name" value="Faculty Tamil Name" class="search_init" /></th>
			<th></th>
		</tr>
	</tfoot>	
	</table>
</div>

<span class='breadcrumb1'>
<span class='navbar-brand'><small>MASTERS <i class='ace-icon fa fa-angle-double-right'></i></small></span>
<?php echo $this->Html->link("<span class='navbar-brand'><small>FACULTY <i class='ace-icon fa fa-angle-double-right'></i></small></span>", array("controller"=>"Faculties",'action' => 'index'),array('data-placement'=>'left','escape' => false)); ?>
</span>