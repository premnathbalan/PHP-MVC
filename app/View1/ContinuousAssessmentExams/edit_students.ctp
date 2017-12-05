<?php
//pr($results);
?>
<div>
	
	<table id="attendanceHeadTbl" border="1">
		<tr>
			<td><strong>Batch</strong></td>
			<td><?php echo $this->Html->getBatch($batchId);?></td>			
			<td><strong>Academic</strong></td>
			<td><?php echo $this->Html->getAcademic($academicId);?></td>	
		</tr>		
		<tr>
			<td><strong>Program</td>
			<td><?php echo $this->Html->getProgram($programId);?></td>			
			<td><strong>Course</strong></td>
			<td><?php echo $this->Html->getCourseNameFromCmId($cm_id);?></td>
		</tr>
		<tr>
			<td><strong>Course Code</strong></td>
			<td><?php echo $this->Html->getCourseCode($cm_id);?></td>
			<td><strong>Month Year</strong></td>
			<td><?php echo $month_year;?></td>			
		</tr>
		<tr>
			<td><strong>Marks</strong></td>
			<td><?php echo $maxMarks;?></td>			
			<td><strong>Assessment</strong></td>
			<td><?php echo $caeNumber;?></td>
		</tr>	
	</table>

<div class=" col-sm-12" style="text-align: center;">
<?php
if ($publish_status > 0) echo "<strong style='color:#ff0000;align:center;'>Data already published</strong>";
?>
</div>	
<?php
echo $this->Form->create('CAE');
echo $this->Form->input('cae_id', array('label' => false, 'type' => 'hidden', 'default' => $caeId));
//echo $this->Form->input('course_mapping_id', array('label' => false, 'type' => 'hidden', 'default' => $cm_id));
?>
<table cellpadding="0" cellspacing="0" border="0" class="display" id="example" style="margin-top:10px;">
			<thead>
				<tr>
					<!--<th>Sl.&nbsp;No.</th>-->
					<th>Register&nbsp;Number</th>
					<th>Student&nbsp;Name</th>
					<th>Marks</th>
				</tr>
			</thead>
			<tbody>
				<?php $i=1;
					foreach($results as $key => $result) { //pr($result);
					if ($result['Student']['id'] && $result['CourseStudentMapping']['indicator']==0) {
				?>
					<tr class='gradeX'>
						<!--<td><?php echo $i; ?></td>-->
						<td><?php echo $result['Student']['registration_number']; ?></td>
						<td><?php echo $result['Student']['name']; ?></td>
						<td><?php 
						if (isset($result['Student']['ContinuousAssessmentExam']) && count($result['Student']['ContinuousAssessmentExam'])>0) {
							$marks = $result['Student']['ContinuousAssessmentExam'][0]['marks'];
							//$publish_status = $result['Student']['StudentMark'][0]['marks'];
						}
						else { 
							$marks="";
							//$publish_status = 0;
						}
						$student_id = $result['Student']['id'];
						echo $this->Form->input('marks', array('type'=>'text','class'=>'dummy','id'=>'DNM'.$i, 'maxlength'=>'3', 'label' => false, 'default' => $marks, 'name'=>'data[CAE][marks][]'))
						." ".
						$this->Form->input('student_id', array('type'=>'hidden', 'label' => false, 'default' => $student_id, 'name'=>'data[CAE][student_id][]'))
						?>
						</td>
					</tr>
				<?php
					$i++;
					}
					}
				?>
			</tbody>
			<!--<tfoot>
				<tr>
					<th><input type="text" name="" value="Regn No." class="search_init" /></th>
					<th><input type="text" name="Registration Number" value="Registration Number" class="search_init" /></th>
					<th><input type="text" name="StudentName" value="StudentName" class="search_init" /></th>
					<th><input type="text" name="Marks" value="Marks" class="search_init" /></th>
				</tr>
			</tfoot>-->
		</table>
		<?php
		echo $this->Form->button('<i class="ace-icon fa fa-check bigger-110"></i>'.__('Submit'),array('type'=>'submit','name'=>'submit','value'=>'submit','class'=>'btn'));
		echo $this->Form->end();
		?>
</div>
<span class='breadcrumb1'>
<span class='navbar-brand'><small>MARKS <i class='ace-icon fa fa-angle-double-right'></i></small></span>
<span class='navbar-brand'><small>C.A.E <i class='ace-icon fa fa-angle-double-right'></i></small></span>
<?php echo $this->Html->link("<span class='navbar-brand'><small> Edit Marks <i class='ace-icon fa fa-angle-double-right'></i></small></span>", array("controller"=>"ContinuousAssessmentExams",'action' => 'calculateCAEMarks'),array('data-placement'=>'left','escape' => false)); ?>
</span>	