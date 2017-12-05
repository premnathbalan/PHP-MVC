<div id="js-load-forms"></div>

<?php 
if($this->Html->checkPathAccesstopath('NonCreditCourses/add','',$authUser['id'])){
echo $this->Html->link( '<i class="ace-icon fa fa-plus-square"></i>'. 'Non Credit Courses Add', array("controller"=>"NonCreditCourses",'action'=>'add'),array('class' =>"js-popup addBtn btn",'escape' => false,'style'=>'margin-bottom:5px;')); 
}
?>

<div class="nonCreditCourses index">
	<table cellpadding="0" cellspacing="0" border="0" class="display" id="example">
	<thead>
	<tr>
		<th class="thAction">&nbsp;&nbsp;Action&nbsp;&nbsp;</th>
		<th>Non Credit Course Name</th>
		<th class="thAction">&nbsp;&nbsp;Delete&nbsp;&nbsp;</th>
	</tr>
	</thead>
	<tbody>
	<?php foreach ($nonCreditCourses as $nonCreditCourse): ?>
	<tr class="gradeX">
		<td class="actions">
			<?php 
			if($this->Html->checkPathAccesstopath('NonCreditCourses/view','',$authUser['id'])){
				echo $this->Html->link("<i class='fa fa-eye fa-lg'></i>", array("controller"=>"NonCreditCourses",'action' => 'view', $nonCreditCourse['NonCreditCourse']['id']),array('class' =>"js-popup",'escape' => false, 'title'=>'View'));
			}if($this->Html->checkPathAccesstopath('NonCreditCourses/edit','',$authUser['id'])){
				echo $this->Html->link("<i class='fa fa-pencil fa-lg'></i>", array("controller"=>"NonCreditCourses",'action' => 'edit', $nonCreditCourse['NonCreditCourse']['id']),array('class' =>"js-popup", 'title'=>'Edit','escape' => false));
			}
			?>			
		</td>
		<td><?php echo h($nonCreditCourse['NonCreditCourse']['non_credit_course_name']); ?></td> 
		<td class="actions">
			<?php 
			if($this->Html->checkPathAccesstopath('NonCreditCourses/delete','',$authUser['id'])){
				echo $this->Form->postLink("<span class='fa fa-times fa-lg red'></span>", array("controller"=>"NonCreditCourses",'action' => 'delete', $nonCreditCourse['NonCreditCourse']['id']), array('confirm' => __('Are you sure you want to delete?'),'escape' => false, 'title'=>'Delete')); 
			}
			?>			
		</td>
	</tr>
<?php endforeach; ?>
	</tbody>
	<tfoot>
	<tr>
		<th><input type="text" name="&nbsp;&nbsp;Action&nbsp;&nbsp;" value="&nbsp;&nbsp;Action&nbsp;&nbsp;" class="search_init" /></th>
		<th><input type="text" name="Non Credit Course Name" value="Non Credit Course Name" class="search_init" /></th>
		<th></th>
	</tr>
	</tfoot>
	
	</table>
</div>	
<span class='breadcrumb1'>
<span class='navbar-brand'><small>MASTERS <i class='ace-icon fa fa-angle-double-right'></i></small></span>
<?php echo $this->Html->link("<span class='navbar-brand'><small>NON CREDIT COURSES <i class='ace-icon fa fa-angle-double-right'></i></small></span>", array("controller"=>"NonCreditCourses",'action' => 'index'),array('data-placement'=>'left','escape' => false)); ?>
</span>