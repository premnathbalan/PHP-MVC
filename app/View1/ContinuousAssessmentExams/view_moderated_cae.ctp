<?php
//pr($moderatedArray);
?>
<div class="caes index">
	<h2><?php echo __('Moderated CAE'); ?></h2>
</div>
		<table cellpadding="0" cellspacing="0" border="0" class="display" id="example" style="margin-top:10px;">
			<thead>
				<tr>
					<th>Batch</th>
					<th>Academic</th>
					<th>Program</th>
					<th>Course</th>
					<th>Register&nbsp;Number</th>
					<th>Student&nbsp;Name</th>
					<th>Marks</th>
				</tr>
			</thead>
			<tbody>
			<?php
			
			foreach ($moderatedArray as $moderatedArray) {
					$details = $this->Html->getBatchAcademicProgramFromCmId($moderatedArray['course_mapping_id']);
					//pr($details);
					$batch = $details['batch'];
					$academic = $details['academic'];
					$program = $details['program'];
					$course = $details['course'];
					$student = $this->Html->getStudentInfo($moderatedArray['student_id']);
					//pr($student);
					$reg_number = $student['Student']['registration_number'];
					$stu_name = $student['Student']['name'];
					$marks = $moderatedArray['marks'];
					$i=1;
					
					echo "<tr class='gradeX'>";
							echo "<td>".$batch."</td>";
							echo "<td>".$academic."</td>";
							echo "<td>".$program."</td>";
							echo "<td>".$course."</td>";
							echo "<td>".$reg_number."</td>";
							echo "<td>".$stu_name."</td>";
							echo "<td>".$marks."</td>";
					echo "</tr>";
					$i++;
			}
			?>	
			</tbody>
			<tfoot>
				<tr>
					<th><input type="text" name="Batch" value="Batch" class="search_init" /></th>
					<th><input type="text" name="Academic" value="Academic" class="search_init" /></th>
					<th><input type="text" name="Program" value="Program" class="search_init" /></th>
					<th><input type="text" name="Course" value="Course" class="search_init" /></th>
					<th><input type="text" name="Register&nbsp;Number" value="Register&nbsp;Number" class="search_init" /></th>
					<th><input type="text" name="Student&nbsp;Name" value="Student&nbsp;Name" class="search_init" /></th>
					<th><input type="text" name="Marks" value="Marks" class="search_init" /></th>
				</tr>
			</tfoot>
		</table>
		<?php echo $this->Html->script('common'); ?>
