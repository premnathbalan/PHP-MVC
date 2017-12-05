<div class="students view">
<?php echo $this->element('student_view',array("studentId"=>$studentId)); ?>	
<?php echo $this->render('../Students/info',false); ?>
</div>

<?php
//pr($results);
$batchId = $results[0]['Student']['batch_id'];
$programId = $results[0]['Student']['program_id'];
$smArray = $results[0]['StudentMark'];
//pr($smArray); 
$finalArray = array();
foreach ($smArray as $key => $value) {
	$finalArray[$value['month_year_id']][] = $value;
}
//pr($finalArray);
?>
<table border="1" style="margin:5px;" class="display tblOddEven">
	<tr><th>S.No.</th><th>Course Type</th><th>Course Name</th><th>MonthYear</th><th>Credits</th><th>Cumulative</br>Credit</th><th>Marks</th><th>Status</th></tr>
	<?php $prevSemester = ""; $sno =1; $semArray = array();
	//foreach($results[0]['StudentMark'] as $result):
	$cumulativeCredit = 0;
	foreach($finalArray as $month_year_id => $results) { ?>
	<tr><td colspan="6"><b>Semester <?php echo $this->Html->retrieveSemesterFromMonthYear($month_year_id, $batchId, $programId);?></b></td></tr>
	<?php foreach($results as $key => $result) { //pr($result); 
	?>
	<tr>
		<td align='center'><i><?php echo $sno;?></i></td>
		<td><?php echo $result['CourseMapping']['Course']['CourseType']['course_type'];?></td>
		<td><?php echo $result['CourseMapping']['Course']['course_name'].' '.$result['CourseMapping']['Course']['course_code'];?></td>
		<td align='center'><?php echo $result['MonthYear']['Month']['month_name'].'-'.$result['MonthYear']['year']; ?>
		<td><?php 
			$credit_point = $result['CourseMapping']['Course']['credit_point'];
			if($result['revaluation_status'] == 0){
				$resStatus = $result['status'];
			}else{
				$resStatus = $result['final_status'];
			}
			if ($resStatus == 'Pass') {
				echo $result['CourseMapping']['Course']['credit_point'];
			} 
		?>
		</td>
		<td><?php 
			if($result['revaluation_status'] == 0){
				$resStatus = $result['status'];
			}else{
				$resStatus = $result['final_status'];
			}
			if ($resStatus == 'Pass') {
				$cumulativeCredit+=$credit_point;
				echo $cumulativeCredit;
			} 
			?>
		</td>
		<td align='center'><?php 
		if($result['revaluation_status'] == 0){
				echo $result['marks'];
			}else{
				echo $result['final_marks'];
			}
		?></td>
		<td align='center'><?php 
		if($result['revaluation_status'] == 0){
				echo $result['status'];
			}else{
				echo $result['final_status'];
			}
		?></td>
	</tr>
	<?php $sno++;
	}}
	?>
</table>

<script>
leftMenuSelection('Students/regNoSearch');
</script>  

<span class='breadcrumb1'>
<span class='navbar-brand'><small>STUDENTS <i class='ace-icon fa fa-angle-double-right'></i></small></span>
<?php echo $this->Html->link("<span class='navbar-brand'><small> LIST <i class='ace-icon fa fa-angle-double-right'></i></small></span>", array("controller"=>"students",'action' => 'student_search'),array('data-placement'=>'left','escape' => false)); ?>
<?php echo $this->Html->link("<span class='navbar-brand'><small> CREDITS ( $studentId ) <i class='ace-icon fa fa-angle-double-right'></i></small></span>", array("controller"=>"Students",'action' => 'credits',$studentId),array('data-placement'=>'left','escape' => false)); ?>
</span>