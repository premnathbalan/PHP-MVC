 <?php if($results){?>
	<table class="attendanceHeadTblP" cellpadding="0" cellspacing="0" border="1" style="margin-top:10px;">
		<tr>
			<th>S.No.</th>
			<th>SUBJECT CODE</th>
			<th>COURSE NAME</th>
			<th>DUMMY NO.</th>
			<th>BOARD</th>
			<th>SIGNATURE</th>
		</tr>
		<?php $i=1; for($p=0;$p<count($results);$p++){?>
		<tr>
			<td><?php echo $i;?></td>
			<td><?php echo $results[$p]['CourseMapping']['Course']['course_code'];?></td>  
			<td><?php echo $results[$p]['CourseMapping']['Course']['course_name'];?></td>
			<td><?php echo $results[$p]['EndSemesterExam']['dummy_number'];?></td>
			<td><?php echo $results[$p]['CourseMapping']['Course']['board'];?></td>
			<td></td>
		</tr>
		<?php $i++;if((isset($results[$p+1]['Revaluation']['board']) && ($results[$p]['Revaluation']['board'] != $results[$p+1]['Revaluation']['board'])) || $i == 21){?>
		<tr>
			<td colspan="6"></td>
		</tr>
		<?php $i=1;}}?>
   </table>
   <?php }?>
   
 <?php 	
	echo $this->Html->script('common');
	echo $this->Html->script('common-front');	
	echo $this->Html->css('certificate');
?>