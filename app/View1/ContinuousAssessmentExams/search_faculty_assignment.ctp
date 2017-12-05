		<table cellpadding="0" cellspacing="0" border="0" class="display" id="example" style="margin-top:10px;">
			<thead>
				<tr>
					<th>Academic</th>
					<th>Program</th>
					<th>Course</th>
					<th>Faculty</th>
					<th class="thAction">&nbsp;&nbsp;Action&nbsp;&nbsp;&nbsp;&nbsp;</th>
				</tr>
			</thead>
			<tbody>
			<?php
			//pr($programArray);
			foreach ($result as $facultySearch) {
				$i=1;
				echo "<tr class='gradeX'>";
					echo "<td>".$this->Html->getAcademicFromProgramId($facultySearch['CourseMapping']['program_id'])."</td>";
					echo "<td>".$this->Html->getProgram($facultySearch['CourseMapping']['program_id'])."</td>";
					echo "<td>".$this->Html->getCourseNameFromCourseId($facultySearch['CourseMapping']['course_id'])."</td>";
					echo "<td>".$facultySearch['User']['username']."</td>";
					echo "<td>";
						echo $this->Html->link("<i class='fa fa-pencil fa-lg'></i>", array("controller"=>"ContinuousAssessmentExams",'action' => 'editFaculty', $facultySearch['CourseFaculty']['id'], $facultySearch['CourseMapping']['program_id'], $facultySearch['CourseFaculty']['course_mapping_id'], $facultySearch['User']['id']),array('title'=>'Edit','escape' => false))."&nbsp;&nbsp;";
						//echo $this->Html->link("<i class='fa fa-eye fa-lg'></i>", array("controller"=>"ContinuousAssessmentExams",'action' => 'viewFaculty', $facultySearch['User']['id']),array('escape' => false, 'title'=>'View'))."&nbsp;&nbsp;";
					echo "</td>";
				echo "</tr>";
				$i++;
			}
			?>
			</tbody>
			<tfoot>
				<tr>
					<th><input type="text" name="Academic" value="Academic" class="search_init" /></th>
					<th><input type="text" name="Program" value="Program" class="search_init" /></th>
					<th><input type="text" name="Course" value="Course" class="search_init" /></th>
					<th><input type="text" name="Faculty" value="Faculty" class="search_init" /></th>
					<th></th>
				</tr>
			</tfoot>
		</table>
		<?php echo $this->Html->script('common'); ?>