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
	
<?php
//pr($results);
echo $this->Form->create('Cae');
echo $this->Form->input('id', array('label' => false, 'type' => 'hidden', 'default' => $eseId));
echo $this->Form->input('course_mapping_id', array('label' => false, 'type' => 'hidden', 'default' => $cm_id));
echo $this->Form->input('max_marks', array('label' => false, 'type' => 'hidden', 'default' => $maxMarks));
echo $this->Form->input('post_model', array('label' => false, 'type' => 'hidden', 'default' => $currentModel));
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
						if (isset($result['Student']['ProjectViva']) && count($result['Student']['ProjectViva'])>0) {
							$marks = $result['Student']['ProjectViva'][0]['marks'];
						}
						else { 
							$marks='';
						}
						$student_id = $result['Student']['id'];
						echo $marks;
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
		if($approval_status) {
			echo $this->Form->button('<i class="ace-icon fa fa-check bigger-110"></i>'.__('Approved'),array('type'=>'button','name'=>'submit','value'=>'submit','class'=>'btn js-cae-approve', 'disabled'));
		}
		else {
			echo $this->Form->button('<i class="ace-icon fa fa-check bigger-110"></i>'.__('Approve'),array('type'=>'button','name'=>'submit','value'=>'submit','class'=>'btn js-cae-approve'));			
		}
		echo $this->Form->end();
		?>
</div>
<script>leftMenuSelection('EseProjects');</script>		
<span class='breadcrumb1'>
<span class='navbar-brand'><small>MARKS <i class='ace-icon fa fa-angle-double-right'></i></small></span>
<span class='navbar-brand'><small>E.S.E <i class='ace-icon fa fa-angle-double-right'></i></small></span>
<?php echo $this->Html->link("<span class='navbar-brand'><small> Edit Marks <i class='ace-icon fa fa-angle-double-right'></i></small></span>", array("controller"=>"EseProjects",'action' => 'editMarks',$batchId,$academicId,$programId,$eseId,$caeNumber),array('data-placement'=>'left','escape' => false)); ?>
</span>	