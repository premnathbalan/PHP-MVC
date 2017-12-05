		<table id="attendanceHeadTbl" class="bgFrame1">
		<tr>
			<td>Batch</td>
			<td><?php echo $results[0]['Batch']['batch_from']."-".$results[0]['Batch']['batch_to']." ".$results[0]['Batch']['academic'];?></td>			
			<td>Academic</td>
			<td><?php echo $results[0]['Program']['Academic']['academic_name'];?></td>	
			<td></td>		
		</tr>		
		<tr>
			<td>Program</td>
			<td><?php echo $results[0]['Program']['program_name'];?></td>			
			<td>Course</td>
			<td><?php echo $results[0]['Course']['course_name'];?></td>
			<td></td>
		</tr>
		<tr>
			<td>Course Code</td>
			<td><?php echo $results[0]['Course']['course_code'];?></td>
			<td>Month Year</td>
			<td><?php echo $this->Html->getMonthYearFromId($month_year_id);?></td>			
			<td></td>
		</tr>
	</table>
							
	<table cellpadding="0" cellspacing="0" border="0" class="display" id="example" style="margin-top:10px;">
			<thead>
				<tr>
					<th>S.No.</th>
					<th>Reg.&nbsp;Number</th>
					<th>Student&nbsp;Name</th>
					<th>ESE&nbsp;Mark</th>
				</tr>
			</thead>
			<tbody>
				<?php
				$i=1;
				foreach ($results as $key => $result) {
				$eseResults = $result['EndSemesterExam'];
					foreach ($eseResults as $key => $eseArray) {
						$student_id = $eseArray['student_id'];
						?>
						<tr class='gradeX'>
							<td><?php echo $i++;?></td>
							<td><?php echo $studentArray[$student_id]['reg_num'];?></td>
							<td><?php echo $studentArray[$student_id]['name'];?></td>
							<td><?php echo $eseArray['marks']; ?></td>
						</tr>
						<?php $i++;
					}
				}
				?>
			</tbody>
			<tfoot>
				<tr>
					<th></th>
					<th><input type="text" name="Reg.&nbsp;Number" value="Reg.&nbsp;Number" class="search_init" /></th>
					<th><input type="text" name="Name" value="Name" class="search_init" /></th>
					<th><input type="text" name="Mark" value="Mark" class="search_init" /></th>
				</tr>
			</tfoot>
		</table>
<?php echo $this->Html->script('common'); ?>

<span class='breadcrumb1'>
<span class='navbar-brand'><small>EXAMINATION <i class='ace-icon fa fa-angle-double-right'></i></small></span>
<?php echo $this->Html->link("<span class='navbar-brand'><small>ESE Moderation <i class='ace-icon fa fa-angle-double-right'></i></small></span>", array("controller"=>"EndSemesterExams",'action' => 'moderation'),array('data-placement'=>'left','escape' => false)); ?>
</span>