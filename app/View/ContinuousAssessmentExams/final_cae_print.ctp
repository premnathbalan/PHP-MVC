<?php echo $this->element('print_head_a4');?>
	<table class="attendanceHeadTblP" border="1" cellpadding="0" cellspacing="0" style="margin-left:50px;">
		<tr><td colspan="4" align="center"><b>FINAL CAE - OUT OF 50 MARKS</b></td></tr>
		<tr>
			<td style="height:30px;"><b>Batch</b></td>
			<td><?php echo $this->Html->getBatch($batch_id);?></td>			
			<td><b>Program</b></td>
			<td><?php echo $this->Html->getAcademicFromProgId($program_id);?></td>
		</tr>		
		<tr>
			<td style="height:30px;"><b>Month&Year of Exam</b></td>
			<td><?php echo $this->Html->getMonthYearFromId($month_year_id);?></td>			
			<td><b>Specialisation</b></td>
			<td><?php echo $this->Html->getProgram($program_id);?></td>
		</tr>
	</table>
	
<table class="attendanceHeadTblP" cellpadding="0" cellspacing="0" border="1" style="margin-top:10px;margin-left:50px;">
<thead>
	<?php if (isset($marks)){ ?>
	<tr>
		<th style="height:30px;">Sl.&nbsp;No.</th>
		<th>Reg.No.</th>
		<th>Student&nbsp;Name</th>
		<?php if($cmIdArray){ $totSubject = count($cmIdArray); for($i=0;$i<$totSubject;$i++){?>
		<th><?php echo $this->Html->getCourseCode($cmIdArray[$i]);?></th>
		<?php }}?>		
	</tr>
	<?php }?>
</thead>
<tbody>
	<?php if(isset($marks)) { 					
	$j = 1;
	for($p=0;$p<count($marks);$p++) { $eachSubject = 0;
	?>
	<tr class=" gradeX">					
		<td style="height:30px;"><?php echo $j; ?></td>
		<td><?php echo $marks[$p]['Student']['registration_number']; ?></td>
		<td><?php echo $marks[$p]['Student']['name']; ?></td>
<?php if($cmIdArray){for($i=0;$i<$totSubject;$i++){ ?>
      <td align='center'>
        <?php
        if(isset($marks[$p+$i]['Student']['registration_number'])){ 
        if($marks[$p]['Student']['registration_number'] == $marks[$p+$i]['Student']['registration_number']){
        echo $marks[$p+$i]['InternalExam']['marks'];$eachSubject = $eachSubject +1;}}?>
      </td>
      <?php }} $p = (($p+$eachSubject)-1);?>
	</tr>
	  <?php $j++;  if($j% 17 == 0){?>
	  </tbody>
</table>
<table><tr><td style='height:50px;'>&nbsp;</td></table>
<?php echo $this->element('print_head_a4');?>
<table class="attendanceHeadTblP" border="1" cellpadding="0" cellspacing="0" style="margin-left:50px;">
	<tr><td colspan="4" align="center"><b>FINAL CAE - OUT OF 50 MARKS</b></td></tr>
	<tr>
		<td style="height:30px;"><b>Batch</b></td>
		<td><?php echo $this->Html->getBatch($batch_id);?></td>			
		<td><b>Program</b></td>
		<td><?php echo $this->Html->getAcademicFromProgId($program_id);?></td>
	</tr>		
	<tr>
		<td style="height:30px;"><b>Month&Year of Exam</b></td>
		<td><?php echo $this->Html->getMonthYearFromId($month_year_id);?></td>			
		<td><b>Specialisation</b></td>
		<td><?php echo $this->Html->getProgram($program_id);?></td>
	</tr>
</table>
<table class="attendanceHeadTblP" cellpadding="0" cellspacing="0" border="1" style="margin-top:10px;margin-left:50px;">
<thead>	
	<tr>
		<th style="height:30px;">Sl.&nbsp;No.</th>
		<th>Reg.No.</th>
		<th>Student&nbsp;Name</th>
		<?php for($i=0;$i<$totSubject;$i++){?>
		<th><?php echo $this->Html->getCourseCode($cmIdArray[$i]);?></th>
		<?php }?>		
	</tr>

</thead>
<tbody><?php }}
	  }
	  ?>
</tbody>
</table>
<?php 	
	echo $this->Html->script('common');
	echo $this->Html->script('common-front');	
	echo $this->Html->css('certificate');
?>