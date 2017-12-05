<?php if (isset($results) && count($results) > 0) { //pr($results); 
echo $this->Form->create('StudentAuditCourse');
$sac = $results['StudentAuditCourse'];
/*	$finalArray = array();
		for($p = 0; $p < count($results['StudentAuditCourse']); $p++) {
			$month_year_id = $results['StudentAuditCourse'][$p]['month_year_id'];
			if (isset($finalArray[$month_year_id])) {
				$finalArray[$month_year_id][] = $results['StudentAuditCourse'][$p];
			} else {
				$finalArray[$month_year_id] = array($results['StudentAuditCourse'][$p]);
			}
		}
		pr($finalArray);*/
		
?>
	<table id="attendanceHeadTbl" border="1">
		<tr>
			<td><strong>Registration Number</strong></td>
			<td><?php echo $regNo;?></td>
			<td><strong>Name</strong></td>
			<td><?php echo $results['Student']['name'];?></td>			
		</tr>
		<tr>
			<td><strong>Batch</strong></td>
			<td><?php echo $this->Html->getBatch($results['Batch']['id']);?></td>		
			<td><strong>Academic</strong></td>
			<td><?php echo $this->Html->getAcademic($results['Academic']['id']);?></td>	
		</tr>		
		<tr>
			<td><strong>Program</strong></td>
			<td><?php echo $this->Html->getProgram($results['Program']['id']);?></td>
			<td><strong>MonthYear</strong></td>
			<td style='text-align:left;'><?php echo $this->Form->input('month_year_id', array('label' => false, 'empty' => __("----- Select MonthYear-----"), 'options'=>$month_year, 'name'=>'data[StudentAuditCourse][month_year_id]', 'onchange'=>'audit_course('.$studentId.');')); ?></td> 
		</tr>	
	</table>
	<div id='result'></div>
<?php
echo $this->Form->end();
}
else {
	echo "Invalid registration number. Please try again.";
}
?>

<?php echo $this->Html->script('common'); ?>

<script>leftMenuSelection('StudentAuditCourses/index');</script>
<span class='breadcrumb1'>
<span class='navbar-brand'><small>Students <i class='ace-icon fa fa-angle-double-right'></i></small></span>
<?php echo $this->Html->link("<span class='navbar-brand'><small> Audit Courses <i class='ace-icon fa fa-angle-double-right'></i></small></span>", array("controller"=>"StudentAuditCourses",'action' => 'view',$regNo),array('data-placement'=>'left','escape' => false)); ?>
</span>