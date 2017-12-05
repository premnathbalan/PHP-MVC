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
					<th>ESE&nbsp;No.</th>
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
				$esePracticalArray = $courseMapping['EsePractical'];
				$i=1;
				foreach ($esePracticalArray as $key => $esePracticalDetails) {
					$caeId = $esePracticalDetails['id'];
					$marks_status = $esePracticalDetails['marks_status'];
					$add_status = $esePracticalDetails['add_status'];
				if (trim($esePracticalDetails['marks_status']) == "Entered" && ($esePracticalDetails['approval_status'] == 1)) { 
					$bgStyle = 'ovelShapBg1';
				}
				if (trim($esePracticalDetails['marks_status']) == "Entered" && ($esePracticalDetails['approval_status'] == 0)) { 
					$bgStyle = 'ovelShapBg2';
				}
				if ($esePracticalDetails['add_status'] == 1) {
						$markStatusMethod="editStudents";
				}
				else if ($esePracticalDetails['add_status'] == 0) {
					$markStatusMethod="getStudents";
				}
				if ($esePracticalDetails['approval_status']) {
						$approveStatusMethod="Approved";
				}
				else {
					$approveStatusMethod="Not Approved";
				}
				
				?>
				<tr class='gradeX'>
					<td><?php 
					//pr($courseMapping);
					echo $this->Html->link($courseMapping['Batch']['batch_from']."-".$courseMapping['Batch']['batch_to'], array("controller"=>"EsePracticals",'action' => 'viewEse', $batch_id, $academic_id, $program_id, $caeId, $i),array('target'=>'_blank','escape' => false, 'title'=>'View'))
					//echo $courseMapping['Batch']['batch_from']."-".$courseMapping['Batch']['batch_to']; 
					?></td>
					<td>
							<?php 
							if ($markStatusMethod == "getStudents") {
								if($this->Html->checkPathAccesstopath('EsePracticals/addEsePracticalMarks','',$authUser['id'])){
									echo $this->Html->link("<i class='fa fa-plus fa-lg'></i>", array("controller"=>"EsePracticals",
									'action' => 'addEsePracticalMarks', $batch_id, $academic_id, $program_id, $caeId, $i, $month_year_id), 
									array('title'=>'Add','escape' => false))."&nbsp;&nbsp;";
								}
							}
							else {
								if($this->Html->checkPathAccesstopath('EsePracticals/editEsePractical','',$authUser['id'])){
									echo $this->Html->link("<i class='fa fa-pencil fa-lg'></i>", array("controller"=>"EsePracticals",
									'action' => 'editEsePractical', $batch_id, $academic_id, $program_id, $caeId, $i, $month_year_id), 
									array('title'=>'Edit','escape' => false))."&nbsp;&nbsp;";
								} 
							}
							if($this->Html->checkPathAccesstopath('EsePracticals/viewEse','',$authUser['id'])){
								echo $this->Html->link("<i class='fa fa-eye fa-lg'></i>", array("controller"=>"EsePracticals",
								'action' => 'viewEse', $batch_id, $academic_id, $program_id, $caeId, $i, $month_year_id), 
								array('title'=>'View','escape' => false))."&nbsp;&nbsp;";
							} 
							?>
					</td>
					<td>Academic</td>
					<td><?php echo $courseMapping['Program']['program_name']; ?></td>
					<td><?php echo $courseMapping['Course']['course_name']; ?></td>
					<td>Practical</td>
					<td><?php echo $esePracticalDetails['assessment_type']; ?></td>
					<td><?php echo $courseMapping['Course']['course_code']; ?></td>
					<td><?php echo $courseMapping['Course']['common_code']; ?></td>
					<td><?php echo $courseMapping['CourseMapping']['semester_id']; ?></td>
					<td><?php echo $i; ?></td>
					<td><?php echo $esePracticalDetails['marks']; ?></td>
					<td><?php echo $esePracticalDetails['marks_status']; ?></td>
					<td><?php echo $esePracticalDetails['approval_status']; ?></td>
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
					<th><input type="text" name="ESE&nbsp;No." value="CAE&nbsp;No." class="search_init" /></th>
					<th><input type="text" name="Marks" value="Marks" class="search_init" /></th>
					<th><input type="text" name="Marks&nbsp;Status" value="Marks&nbsp;Status" class="search_init" /></th>
					<th><input type="text" name="Approval&nbsp;Status" value="Approval&nbsp;Status" class="search_init" /></th>
					<th><input type="text" name="Faculty" value="Faculty" class="search_init" /></th>
				</tr>
			</tfoot>
		</table>
		<?php echo $this->Html->script('common'); ?>
		
<script>leftMenuSelection('EsePracticals/practical');</script>
<span class='breadcrumb1'>
<span class='navbar-brand'><small>MARKS <i class='ace-icon fa fa-angle-double-right'></i></small></span>
<span class='navbar-brand'><small>Practical <i class='ace-icon fa fa-angle-double-right'></i></small></span>
<?php echo $this->Html->link("<span class='navbar-brand'><small> E.S.E <i class='ace-icon fa fa-angle-double-right'></i></small></span>", array("controller"=>"EsePracticals",'action' => 'practical'),array('data-placement'=>'left','escape' => false)); ?>
</span>		