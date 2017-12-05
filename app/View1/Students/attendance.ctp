<div class="students view">
<?php echo $this->element('student_view',array("studentId"=>$studentId)); ?>	
<?php echo $this->render('../Students/info',false); ?>

<?php //pr($attendance); 

if($attendance[0]['GrossAttendance']){?>
<legend>Gross Attendance</legend>
<table cellpadding="0" cellspacing="0" border="1" class="display tblOddEven">
	<thead>
		<tr>
			<th>Month-Year</th>
			<th style="text-align:center;">Percentage</th>
		</tr>
	</thead>
	<tbody>
		<?php $result = $attendance[0]['GrossAttendance'];		
		//pr($result);	
		foreach($result as $key => $res) { ?>
		<tr>
			<td><?php echo $this->Html->getMonthYearFromMonthYearId($res['month_year_id']); ?></td>
			<td align="center"><?php echo $res['percentage']; ?></td>			
		</tr>
		<?php } ?>			
	</tbody>
</table><br/>
<?php }?>

<?php if($attendance[0]['Attendance']){?>
<legend>Course wise Attendance</legend>
<table cellpadding="0" cellspacing="0" border="1" class="display tblOddEven">
	<thead>
		<tr>
			<th>Month-Year</th>
			<th>Course</th>
			<th style="text-align:center;">Percentage</th>
		</tr>
	</thead>
	<tbody>
		<?php $result = $attendance[0]['Attendance']; 			
		foreach($result as $result):?>
		<tr>
			<td><?php echo $this->Html->getMonthYearFromMonthYearId(h($result['month_year_id'])); ?></td>
			<td><?php if(isset($result['course_mapping_id'])){echo $this->Html->getCourseNameFromCMId(h($result['course_mapping_id']));} ?></td>
			<td align="center"><?php echo h($result['percentage']); ?></td>			
		</tr>
		<?php endforeach; ?>	
	</tbody>
</table>
<?php }?>
</div>

<script>
leftMenuSelection('Students/regNoSearch');
</script>  

<span class='breadcrumb1'>
<span class='navbar-brand'><small>STUDENTS <i class='ace-icon fa fa-angle-double-right'></i></small></span>
<?php echo $this->Html->link("<span class='navbar-brand'><small> LIST <i class='ace-icon fa fa-angle-double-right'></i></small></span>", array("controller"=>"students",'action' => 'student_search'),array('data-placement'=>'left','escape' => false)); ?>
<?php echo $this->Html->link("<span class='navbar-brand'><small> ATTENDANCE ( $studentId ) <i class='ace-icon fa fa-angle-double-right'></i></small></span>", array("controller"=>"Students",'action' => 'attendance',$studentId),array('data-placement'=>'left','escape' => false)); ?>
</span>