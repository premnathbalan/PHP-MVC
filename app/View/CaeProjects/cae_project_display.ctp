<?php
//pr($courseMappingArray);
?>

	<table cellpadding="0" cellspacing="0" border="0" class="display" id="example" style="margin-top:10px;">
			<thead>
				<tr>
					<th>&nbsp;&nbsp;Batch&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>
					<th class="thAction">&nbsp;&nbsp;Action&nbsp;&nbsp;&nbsp;&nbsp;</th>
					<th>&nbsp;&nbsp;Academic&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>
					<th>&nbsp;&nbsp;Program&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>
					<th>&nbsp;&nbsp;Course&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>
					<th>Course&nbsp;Type</th>
					<th>Assessment&nbsp;Type</th>
					<th>Course&nbsp;Code</th>
					<th>Common&nbsp;Code</th>
					<th>Semester</th>
					<th>CAE&nbsp;No.</th>
					<th>Marks</th>
					<th>Marks&nbsp;Status</th>
					<th>Approval&nbsp;Status</th>
					<th>Faculty</th>
				</tr>
			</thead>
			<tbody>
			<?php
			//pr($programArray);
			foreach ($courseMappingArray as $courseMapping) {
				$caeProjectArray = $courseMapping['CaeProject'];
				$i=1;
				foreach ($caeProjectArray as $key => $caeProjectDetails) {
					$caeId = $caeProjectDetails['id'];
					$marks_status = $caeProjectDetails['marks_status'];
				if (trim($caeProjectDetails['marks_status']) == "Entered" && ($caeProjectDetails['approval_status'] == 1)) { 
					$bgStyle = 'ovelShapBg1';
				}
				if (trim($caeProjectDetails['marks_status']) == "Entered" && ($caeProjectDetails['approval_status'] == 0)) { 
					$bgStyle = 'ovelShapBg2';
				}
				if ($caeProjectDetails['add_status'] == 1) {
						$markStatusMethod="editStudents";
				}
				else if ($caeProjectDetails['add_status'] == 0) {
					$markStatusMethod="getStudents";
				}
				if ($caeProjectDetails['approval_status']) {
						$approveStatusMethod="Approved";
				}
				else {
					$approveStatusMethod="Not Approved";
				}
				
				?>
				<tr class='gradeX'>
					<td><?php 
					//pr($courseMapping);
					echo $this->Html->link($courseMapping['Batch']['batch_from']."-".$courseMapping['Batch']['batch_to'], array("controller"=>"CaePracticals",'action' => 'viewCae', $batch_id, $academic_id, $program_id, $caeId, $i),array('target'=>'_blank','escape' => false, 'title'=>'View'))
					//echo $courseMapping['Batch']['batch_from']."-".$courseMapping['Batch']['batch_to']; 
					?></td>
					<td>
							<?php 
							if ($marks_status == "Not Entered") {
								if($this->Html->checkPathAccesstopath('CaeProjects/addMarks','',$authUser['id'])){
									echo $this->Html->link("<i class='fa fa-plus fa-lg'></i>", array("controller"=>"CaeProjects",
									'action' => 'addMarks', $batch_id, $academic_id, $program_id, $caeId, $i), 
									array('title'=>'Add','escape' => false))."&nbsp;&nbsp;";
								}
							}
							else if ($marks_status == "Entered") {
								if($this->Html->checkPathAccesstopath('CaeProjects/editMarks','',$authUser['id'])){
									echo $this->Html->link("<i class='fa fa-pencil fa-lg'></i>", array("controller"=>"CaeProjects",
									'action' => 'editMarks', $batch_id, $academic_id, $program_id, $caeId, $i), 
									array('title'=>'Edit','escape' => false))."&nbsp;&nbsp;";
								}
							}
							if($this->Html->checkPathAccesstopath('CaeProjects/view','',$authUser['id'])){
								echo $this->Html->link("<i class='fa fa-eye fa-lg'></i>", array("controller"=>"CaeProjects",
								'action' => 'view', $batch_id, $academic_id, $program_id, $caeId, $i), 
								array('title'=>'View','escape' => false))."&nbsp;&nbsp;";
							}
							?>
					</td>
					<td>Academic</td>
					<td><?php echo $courseMapping['Program']['program_name']; ?></td>
					<td><?php echo $courseMapping['Course']['course_name']; ?></td>
					<td>Project</td>
					<td><?php echo $caeProjectDetails['assessment_type']; ?></td>
					<td><?php echo $courseMapping['Course']['course_code']; ?></td>
					<td><?php echo $courseMapping['Course']['common_code']; ?></td>
					<td><?php echo $courseMapping['CourseMapping']['semester_id']; ?></td>
					<td><?php echo $i; ?></td>
					<td><?php echo $caeProjectDetails['marks']; ?></td>
					<td><?php echo $caeProjectDetails['marks_status']; ?></td>
					<td><?php echo $approveStatusMethod; ?></td>
					<td>Lecturer</td>
				</tr>
				<?php
				$i++;
				}
			}
			?>	
			</tbody>
			<tfoot>
				<tr>
					<th><input type="text" name="Batch" value="Batch" class="search_init" /></th>
					<th></th>
					<th><input type="text" name="Academic" value="Academic" class="search_init" /></th>
					<th><input type="text" name="Program" value="Program" class="search_init" /></th>
					<th><input type="text" name="Course" value="Course" class="search_init" /></th>
					<th><input type="text" name="Course&nbsp;Type" value="Course&nbsp;Type" class="search_init" /></th>
					<th><input type="text" name="Assessment&nbsp;Type" value="Assessment&nbsp;Type" class="search_init" /></th>
					<th><input type="text" name="Course&nbsp;Code" value="Course&nbsp;Code" class="search_init" /></th>
					<th><input type="text" name="Common&nbsp;Code" value="Common&nbsp;Code" class="search_init" /></th>
					<th><input type="text" name="Semester" value="Semester" class="search_init" /></th>
					<th><input type="text" name="CAE&nbsp;No." value="CAE&nbsp;No." class="search_init" /></th>
					<th><input type="text" name="Marks" value="Marks" class="search_init" /></th>
					<th><input type="text" name="Marks&nbsp;Status" value="Marks&nbsp;Status" class="search_init" /></th>
					<th><input type="text" name="Approval&nbsp;Status" value="Approval&nbsp;Status" class="search_init" /></th>
					<th><input type="text" name="Faculty" value="Faculty" class="search_init" /></th>
				</tr>
			</tfoot>
		</table>
		<?php echo $this->Html->script('common'); ?>
		
<script>leftMenuSelection('CaeProjects/index');</script>

<span class='breadcrumb1'>
<span class='navbar-brand'><small>MARKS <i class='ace-icon fa fa-angle-double-right'></i></small></span>
<span class='navbar-brand'><small>Project <i class='ace-icon fa fa-angle-double-right'></i></small></span>
<?php echo $this->Html->link("<span class='navbar-brand'><small> C.A.E <i class='ace-icon fa fa-angle-double-right'></i></small></span>", array("controller"=>"CaeProjects",'action' => 'index'),array('data-placement'=>'left','escape' => false)); ?>
</span>