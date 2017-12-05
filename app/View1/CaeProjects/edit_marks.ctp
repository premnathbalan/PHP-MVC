<?php
//pr($results);
?>
<div>
	
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

<div class=" col-sm-12" style="text-align: center;">
<?php
if ($publish_status > 0) echo "<strong style='color:#ff0000;align:center;'>Data already published</strong>";
?>
</div>	
<?php
//pr($results);
echo $this->Form->create('CaeProject');
echo $this->Form->input('id', array('label' => false, 'type' => 'hidden', 'default' => $caeId));
echo $this->Form->input('course_mapping_id', array('label' => false, 'type' => 'hidden', 'default' => $cm_id));
echo $this->Form->input('max_marks', array('label' => false, 'type' => 'hidden', 'default' => $maxMarks));
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
					foreach($results as $key => $result) { 
					if ($result['Student']['id']) {
				?>
					<tr class='gradeX'>
						<!--<td><?php echo $i; ?></td>-->
						<td><?php echo $result['Student']['registration_number']; ?></td>
						<td><?php echo $result['Student']['name']; ?></td>
						<td><?php 
						if (isset($result['Student']['ProjectReview']) && count($result['Student']['ProjectReview'])>0) {
							$marks = $result['Student']['ProjectReview'][0]['marks'];
						}
						else { 
							$marks='';
						}
						$student_id = $result['Student']['id'];
						
						echo $this->Form->input('marks', array('label' => false, 'name'=>'data[CAE][marks][]', 
							'type' => 'text', 'class' => 'getStudents markEntry', 'default' => $marks,
							'style'=>'position:relative;margin-top:-14px;width:50px;border-color:#000;color:#000;padding-left:5px;',
							'id'=>'mark'.$i, 'class'=>'dummy', 'onblur'=>"PrjCAEntry($i,this.value,$student_id,$month_year_id);"));
							
						?>
						<span id='spanProjectCAEEntry<?php echo $i;?>'></span>
						
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
		echo $this->Form->end();
		?>
</div>
		
<span class='breadcrumb1'>
<span class='navbar-brand'><small>MARKS <i class='ace-icon fa fa-angle-double-right'></i></small></span>
<span class='navbar-brand'><small>C.A.E <i class='ace-icon fa fa-angle-double-right'></i></small></span>
<?php echo $this->Html->link("<span class='navbar-brand'><small> Edit Marks <i class='ace-icon fa fa-angle-double-right'></i></small></span>", array("controller"=>"CaeProjects",'action' => 'editMarks',$batchId,$academicId,$programId,$caeId,$caeNumber),array('data-placement'=>'left','escape' => false)); ?>
</span>	