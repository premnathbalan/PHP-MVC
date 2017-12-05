	<table id="attendanceHeadTbl">
		<tr>
			<td>Batch</td>
			<td><?php echo $this->Html->getBatch($batchId);?></td>			
			<td>Academic</td>
			<td><?php echo $this->Html->getAcademic($academicId);?></td>	
			<td></td>		
		</tr>		
		<tr>
			<td>Program</td>
			<td><?php echo $this->Html->getProgram($programId);?></td>			
			<td>Course</td>
			<td><?php echo $this->Html->getCourseNameFromCmId($cm_id);?></td>
			<td></td>			
		</tr>
		<tr>
			<td>Course Code</td>
			<td><?php echo $this->Html->getCourseCode($cm_id);?></td>
			<td>Month Year</td>
			<td><?php echo $month_year;?></td>	
			<td></td>
		</tr>
		<tr>
			<td>Marks</td>
			<td><?php echo $maxMarks;?></td>
			<td></td>
			<td></td>
			<td></td>
		</tr>		
	</table>
<?php
//pr($results);
echo $this->Form->create('CAE');
echo $this->Form->input('cae_id', array('label' => false, 'type' => 'hidden', 'default' => $caeId));
echo $this->Form->input('course_mapping_id', array('label' => false, 'type' => 'hidden', 'default' => $cm_id));
if(count($results)==0) {
	echo "<span style='color=#000;font-weight:bold;'>Students yet to enroll for this course!!!</span>";
}
?>
<table cellpadding="0" cellspacing="0" border="0" class="display" id="example" style="margin-top:10px;">
			<thead>
				<tr>
					<!--<th>Sl.&nbsp;No. </th>-->
					<th>Register&nbsp;</th>
					<th>Student&nbsp;</th>
					<th>Marks</th>
				</tr>
			</thead>
			<tbody>
				<?php if (isset($results)) { 
					//$m=1;
					$i = 1;
				foreach($results as $key => $result) { 
				if (isset($result['Student']['id'])) {
				$student_id = $result['Student']['id'];
				?>
				<tr class=" gradeX">
					
					<!--<td><?php //echo $j+1; //if ($m>50) $m=1; 
					?></td>-->
					<td><?php echo $result['Student']['registration_number']; ?></td>
					<td><?php echo $result['Student']['name']; ?></td>
					<td><?php echo $this->Form->input('marks', array('label' => false, 'name'=>'data[CAE][marks][]', 
									'type' => 'text','id'=>'DNM'.$i, 'maxlength'=>'3', 'class' => 'markEntry dummy getStudents', 'default'=>''));
								echo $this->Form->input('student_id', array('type'=>'hidden', 'label' => false, 'default' => $student_id, 'name'=>'data[CAE][student_id][]'));
						?></td>
				</tr>
				  <?php $i++;} }
				  }
				  ?>
			</tbody>
			<!--<tfoot>
				<tr>
					<th><input type="text" name="" value="Regn No." class="search_init" /></th>
					<th><input type="text" name="Registration Number" value="Registration Number" class="search_init" /></th>
					<th><input type="text" name="Academic Id" value="Academic Id" class="search_init" /></th>
					<th><input type="text" name="Program Id" value="Program Id" class="search_init" /></th>
				</tr>
			</tfoot>-->
		</table>
		<?php
		echo $this->Form->button('<i class="ace-icon fa fa-check bigger-110"></i>'.__('Submit'),array('type'=>'submit','name'=>'submit','value'=>'submit','class'=>'btn', 'onclick'=>'return validateMarks();'));
		echo $this->Form->end();
		?>
<?php //echo $this->Html->script('common'); ?>
		
<span class='breadcrumb1'>
<span class='navbar-brand'><small>MARKS <i class='ace-icon fa fa-angle-double-right'></i></small></span>
<span class='navbar-brand'><small>C.A.E <i class='ace-icon fa fa-angle-double-right'></i></small></span>
<?php echo $this->Html->link("<span class='navbar-brand'><small> Add Marks <i class='ace-icon fa fa-angle-double-right'></i></small></span>", array("controller"=>"ContinuousAssessmentExams",'action' => 'calculateCAEMarks'),array('data-placement'=>'left','escape' => false)); ?>
</span>	