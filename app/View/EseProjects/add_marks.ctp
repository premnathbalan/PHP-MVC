	<table class="display tblOddEven" border="1" cellpadding="0" cellspacing="0">
		<tr>
			<td><b>Batch</b></td>
			<td><?php echo $this->Html->getBatch($batchId);?></td>			
			<td><b>Academic</b></td>
			<td><?php echo $this->Html->getAcademic($academicId);?></td>	
		</tr>		
		<tr>
			<td><b>Program</b></td>
			<td><?php echo $this->Html->getProgram($programId);?></td>			
			<td><b>Course</b></td>
			<td><?php echo $this->Html->getCourseNameFromCmId($cm_id);?></td>
		</tr>
		<tr>
			<td><b>Course Code</b></td>
			<td><?php echo $this->Html->getCourseCode($cm_id);?></td>
			<td><b>Month Year</b></td>
			<td><?php echo $month_year;?></td>	
		</tr>
		<tr>
			<td><b>Marks</b></td>
			<td><?php echo $maxMarks;?></td>
			<td></td>
			<td></td>
		</tr>		
	</table>
<?php
//pr($results);
echo $this->Form->create('EseProject');
echo $this->Form->input('id', array('label' => false, 'type' => 'hidden', 'default' => $eseId));
echo $this->Form->input('course_mapping_id', array('label' => false, 'type' => 'hidden', 'default' => $cm_id));
echo $this->Form->input('max_marks', array('label' => false, 'type' => 'hidden', 'default' => $maxMarks));

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
					<td><?php echo $this->Form->input('marks', array('label' => false, 'name'=>'data[ESE][marks][]', 
							'type' => 'text', 'class' => 'getStudents markEntry',
							'style'=>'position:relative;margin-top:-14px;width:50px;border-color:#000;color:#000;padding-left:5px;',
							'id'=>'mark'.$i, 'class'=>'dummy', 'onblur'=>"PrjESEntry($i,this.value,$student_id,$month_year_id);"));
							
							echo $this->Form->input('student_id', array('type'=>'hidden', 'label' => false, 'default' => $student_id, 'name'=>'data[CAE][student_id][]'));
						?>
						<span id='spanProjectESEEntry<?php echo $i;?>'></span>
					</td>
				</tr>
				  <?php $i++; }} 
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
		echo $this->Form->end();
		?>
<?php echo $this->Html->script('common'); ?>

<script>leftMenuSelection('CaeProjects');</script>

<span class='breadcrumb1'>
<span class='navbar-brand'><small>MARKS <i class='ace-icon fa fa-angle-double-right'></i></small></span>
<span class='navbar-brand'><small>E.S.E <i class='ace-icon fa fa-angle-double-right'></i></small></span>
<?php echo $this->Html->link("<span class='navbar-brand'><small> Add Marks <i class='ace-icon fa fa-angle-double-right'></i></small></span>", array("controller"=>"CaeProjects",'action' => 'addMarks',$batchId,$academicId,$programId,$eseId,$caeNumber),array('data-placement'=>'left','escape' => false)); ?>
</span>	