	<?php //pr($programArray); 
	//pr($currentModelId);
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
			foreach ($programArray as $details) {
				$i=1;$bgStyle ='';
				if (trim($details['marks_status']) == "Entered" && ($details['approval_status'] == 1)) { 
					$bgStyle = 'ovelShapBg1';
				}
				if (trim($details['marks_status']) == "Entered" && ($details['approval_status'] == 0)) { 
					$bgStyle = 'ovelShapBg2';
				}
				if ($details['add_status'] == 1) {
						$markStatusMethod="editStudents";
				}
				else if ($details['add_status'] == 0) {
					$markStatusMethod="getStudents";
				}
				if ($details['approval_status']) {
						$approveStatusMethod="Approved";
				}
				else {
					$approveStatusMethod="Not Approved";
				}
				echo "<tr class='gradeX'>";
				
					echo "<td>".$this->Html->link($details['batch'], array("controller"=>"ContinuousAssessmentExams",'action' => 'viewCae', $details['batch_id'], $details['academic_id'], $details['program_id'], $details['caeId'], $details['number'], $currentModelId),array('target'=>'_blank','escape' => false, 'title'=>'View'))."</td>";
					echo "<td>";
							if($markStatusMethod === "getStudents") {
								if($this->Html->checkPathAccesstopath('ContinuousAssessmentExams/getStudents','',$authUser['id'])){
									echo $this->Html->link("<i class='fa fa-plus'></i>", array("controller"=>"ContinuousAssessmentExams",'action' => 'getStudents', $details['batch_id'], $details['academic_id'], $details['program_id'], $details['caeId'], $details['marks'], $details['number'], $currentModelId),array('title'=>'Add','escape' => false))."&nbsp;&nbsp;";
								}
							}

							if($markStatusMethod === "getStudents") {
								echo "<i class='fa fa-pencil fa-lg'></i>"."&nbsp;&nbsp;";
								echo "<i class='fa fa-eye fa-lg'></i>"."&nbsp;&nbsp;";
							}
							else {
							if($this->Html->checkPathAccesstopath('ContinuousAssessmentExams/editStudents','',$authUser['id'])){
echo $this->Html->link("<i class='fa fa-pencil fa-lg'></i>", array("controller"=>"ContinuousAssessmentExams",'action' => 'editStudents', $details['batch_id'], $details['academic_id'], $details['program_id'], $details['caeId'], $details['marks'], $details['number'], $currentModelId),array('title'=>'Edit','escape' => false))."&nbsp;&nbsp;";
							}
								if (trim($details['marks_status']) == "Entered") {
								if($this->Html->checkPathAccesstopath('ContinuousAssessmentExams/viewCae','',$authUser['id'])){
echo $this->Html->link("<i class='fa fa-eye fa-lg'></i>", array("controller"=>"ContinuousAssessmentExams",'action' => 'viewCae', $details['batch_id'], $details['academic_id'], $details['program_id'], $details['caeId'], $details['number'], $currentModelId),array('escape' => false, 'title'=>'View'))."&nbsp;&nbsp;";
								}
								}
								else {
									echo "<i class='fa fa-eye fa-lg'></i>"."&nbsp;&nbsp;";
								}						
							}
							//Approve Cae
							/*if($markStatusMethod === "getStudents") {
								echo "<i class='fa fa-check'></i>"."&nbsp;&nbsp;";
							}
							else {
								if ($caeDetail['Cae']['approval_status']) {
									echo "<i class='fa fa-check'></i>"."&nbsp;&nbsp;";
								}
								else {
									echo $this->Html->link("<i class='fa fa-check'></i>", array("controller"=>"ContinuousAssessmentExams",'action' => 'approveInternals', $details['batch_id'], $details['academic_id'], $details['program_id'], $details['caeId']),array('title'=>'Add','escape' => false))."&nbsp;&nbsp;";
								}
							}*/
							//Deletion icon
							/*if($markStatusMethod === "getStudents") {
								echo $this->Html->Link("<span class='fa fa-times fa-lg red'></span>", array("controller"=>"ContinuousAssessmentExams",'action' => 'delete_cae', $details['caeId']), array('confirm' => __('Are you sure you want to delete?'),'escape' => false, 'title'=>'Delete'))."&nbsp;&nbsp;";
							}
							else {
								echo "<span class='fa fa-times fa-lg'></span>"."&nbsp;&nbsp;";
							}	*/
							echo "</td>";	
					echo "<td>".$this->Html->link($this->Html->getAcademic($details['academic_id']), array("controller"=>"ContinuousAssessmentExams",'action' => 'viewCae', $details['batch_id'], $details['academic_id'], $details['program_id'], $details['caeId'], $details['number'], $currentModelId),array('target'=>'_blank','escape' => false, 'title'=>'View'))."</td>";
					echo "<td>".$this->Html->link($details['program'], array("controller"=>"ContinuousAssessmentExams",'action' => 'viewCae', $details['batch_id'], $details['academic_id'], $details['program_id'], $details['caeId'], $details['number'], $currentModelId),array('target'=>'_blank','escape' => false, 'title'=>'View'))."</td>";
					echo "<td>".$this->Html->link($details['course'], array("controller"=>"ContinuousAssessmentExams",'action' => 'viewCae', $details['batch_id'], $details['academic_id'], $details['program_id'], $details['caeId'], $details['number'], $currentModelId),array('target'=>'_blank','escape' => false, 'title'=>'View'))."</td>";
					echo "<td>".
					$this->Html->link($this->Html->getCourseType($details['course_type_id']), array("controller"=>"ContinuousAssessmentExams",'action' => 'viewCae', $details['batch_id'], $details['academic_id'], $details['program_id'], $details['caeId'], $details['number'], $currentModelId),array('target'=>'_blank','escape' => false, 'title'=>'View'))."</td>";
					echo "<td>".$this->Html->link($details['assessment_type'], array("controller"=>"ContinuousAssessmentExams",'action' => 'viewCae', $details['batch_id'], $details['academic_id'], $details['program_id'], $details['caeId'], $details['number'], $currentModelId),array('target'=>'_blank','escape' => false, 'title'=>'View'))."</td>";
					echo "<td>".$this->Html->link($details['course_code'], array("controller"=>"ContinuousAssessmentExams",'action' => 'viewCae', $details['batch_id'], $details['academic_id'], $details['program_id'], $details['caeId'], $details['number'], $currentModelId),array('target'=>'_blank','escape' => false, 'title'=>'View'))."</td>";
					echo "<td>".$this->Html->link($details['common_code'], array("controller"=>"ContinuousAssessmentExams",'action' => 'viewCae', $details['batch_id'], $details['academic_id'], $details['program_id'], $details['caeId'], $details['number'], $currentModelId),array('target'=>'_blank','escape' => false, 'title'=>'View'))."</td>";
					echo "<td>".$this->Html->link($details['semester_id'], array("controller"=>"ContinuousAssessmentExams",'action' => 'viewCae', $details['batch_id'], $details['academic_id'], $details['program_id'], $details['caeId'], $details['number'], $currentModelId),array('target'=>'_blank','escape' => false, 'title'=>'View'))."</td>";
					echo "<td>".$this->Html->link($details['number'], array("controller"=>"ContinuousAssessmentExams",'action' => 'viewCae', $details['batch_id'], $details['academic_id'], $details['program_id'], $details['caeId'], $details['number'], $currentModelId),array('target'=>'_blank','escape' => false, 'title'=>'View'))."</td>";
					echo "<td>".$this->Html->link($details['marks'], array("controller"=>"ContinuousAssessmentExams",'action' => 'viewCae', $details['batch_id'], $details['academic_id'], $details['program_id'], $details['caeId'], $details['number'], $currentModelId),array('target'=>'_blank','escape' => false, 'title'=>'View'))."</td>";
					echo "<td><span class=$bgStyle>".$this->Html->link($details['marks_status'], array("controller"=>"ContinuousAssessmentExams",'action' => 'viewCae', $details['batch_id'], $details['academic_id'], $details['program_id'], $details['caeId'], $details['number'], $currentModelId),array('target'=>'_blank','escape' => false, 'title'=>'View'))."</span></td>";
					echo "<td><span class=$bgStyle>".$this->Html->link($approveStatusMethod, array("controller"=>"ContinuousAssessmentExams",'action' => 'viewCae', $details['batch_id'], $details['academic_id'], $details['program_id'], $details['caeId'], $details['number'], $currentModelId),array('target'=>'_blank','escape' => false, 'title'=>'View'))."</span></td>";
					if (is_null($details['lecturer_id'])) {
						echo "<td></td>";
					}
					else {
						echo "<td>".$this->Html->link($this->Html->getLecturer($details['lecturer_id']), array("controller"=>"ContinuousAssessmentExams",'action' => 'viewCae', $details['batch_id'], $details['academic_id'], $details['program_id'], $details['caeId'], $details['number'], $currentModelId),array('target'=>'_blank','escape' => false, 'title'=>'View'))."</td>";
					}
				echo "</tr>";
				$i++;
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

<span class='breadcrumb1'>
<span class='navbar-brand'><small>MARKS <i class='ace-icon fa fa-angle-double-right'></i></small></span>
<span class='navbar-brand'><small>C.A.E <i class='ace-icon fa fa-angle-double-right'></i></small></span>
<?php echo $this->Html->link("<span class='navbar-brand'><small> Theory <i class='ace-icon fa fa-angle-double-right'></i></small></span>", array("controller"=>"ContinuousAssessmentExams",'action' => 'index'),array('data-placement'=>'left','escape' => false)); ?>
</span>		