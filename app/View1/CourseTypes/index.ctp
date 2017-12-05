<div id="js-load-forms"></div>

<?php 
if($this->Html->checkPathAccesstopath('CourseTypes/add','',$authUser['id'])){
	echo $this->Html->link( '<i class="ace-icon fa fa-plus-square"></i>'. 'Add Courese Type', array("controller"=>"CourseTypes",'action'=>'add'),array('class' =>"js-popup addBtn btn",'escape' => false, 'title'=>'Add Courses Type','style'=>'margin-bottom:5px;'));
} 
?>

<table cellpadding="0" cellspacing="0" border="0" class="display" id="example">
	<thead>
		<tr>
			<th class="thAction">&nbsp;&nbsp;Action&nbsp;&nbsp;</th>
			<th>Courses&nbsp;Types</th>
			<th class="thAction">&nbsp;&nbsp;Delete&nbsp;&nbsp;</th>
		</tr>
	</thead>
	<tbody>
		<?php foreach ($courseTypes as $courseType):?>
		<tr class=" gradeX">
			<td class="actions">
			<?php 
			if($this->Html->checkPathAccesstopath('CourseTypes/view','',$authUser['id'])){
				echo $this->Html->link("<i class='fa fa-eye fa-lg'></i>",array("controller"=>"CourseTypes",'action' => 'view',$courseType['CourseType']['id']), array('class' =>"js-popup",'escape' => false));
			} 

			if($this->Html->checkPathAccesstopath('CourseTypes/edit','',$authUser['id'])){ 
				echo $this->Html->link("<i class='fa fa-pencil fa-lg'></i>",array("controller"=>"CourseTypes",'action' => 'edit',$courseType['CourseType']['id']),array('class' =>"js-popup", 'escape' => false));
			} 
			?>			
		</td>
			<td><?php echo h($courseType['CourseType']['course_type']); ?>&nbsp;</td>			
			<td class="actions">
			<?php 			
			if($this->Html->checkPathAccesstopath('CourseTypes/delete','',$authUser['id'])){ 
				echo $this->Form->postLink("<span class='fa fa-times fa-lg red'></span>", array("controller"=>"CourseTypes",'action' => 'delete', $courseType['CourseType']['id']), array('confirm' => __('Are you sure you want to delete # %s?', $courseType['CourseType']['id']),'escape' => false, 'title'=>'Delete'));
			} 
			?>			
		</td>
		</tr>
		  <?php endforeach; ?>		
	</tbody>
	<tfoot>
		<tr>
			<th><input type="text" name="&nbsp;&nbsp;Action&nbsp;&nbsp;" value="&nbsp;&nbsp;Action&nbsp;&nbsp;" class="search_init" /></th>
			<th><input type="text" name="Courses&nbsp;Types" value="Courses&nbsp;Types" class="search_init" /></th>
			<th></th>
		</tr>
	</tfoot>
</table>

<span class='breadcrumb1'>
<span class='navbar-brand'><small>MASTERS <i class='ace-icon fa fa-angle-double-right'></i></small></span>
<?php echo $this->Html->link("<span class='navbar-brand'><small>Courses Type <i class='ace-icon fa fa-angle-double-right'></i></small></span>", array("controller"=>"CourseTypes",'action' => 'index'),array('data-placement'=>'left','escape' => false)); ?>
</span>
