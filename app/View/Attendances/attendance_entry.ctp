	<?php			
		echo $this->Form->create('P');
	?>
	<table id="attendanceHeadTbl">
		<tr>
			<td>Month&Year of Exam</td>
			<td><?php echo $txtMonthYears;?></td>			
			<td>Program</td>
			<td><?php echo $txtAcademic;?></td>	
			<td></td>		
		</tr>		
		<tr>
			<td>Specialisation</td>
			<td><?php echo $txtProgram;?></td>			
			<td>Subject Code</td>
			<td><?php echo $shortCode;?></td>
			<td></td>			
		</tr>
		<tr>
			<td>Batch</td>
			<td><?php echo $txtBatch;?></td>			
			<td></td>
			<td>
			<?php echo $this->Form->button('<i class="ace-icon fa fa-check bigger-110"></i>'.__('Submit'),array('type'=>'submit','name'=>'submit','value'=>'submit','class'=>'btn'));?>
			</td>			
		</tr>
	</table>
	
<table cellpadding="0" cellspacing="0" border="0" class="display" id="example" style="margin-top:10px;">
	<thead>
	<tr>		
		<th>S.No.</th>
		<th>Register No.</th>
		<th>Student Name</th>
		<th>Percentage</th>
	</tr>
	</thead>
	<tbody>
	<?php $sno =1; foreach ($Students as $student): ?>
	<tr class="gradeX">
		<td><?php echo $sno; ?></td>
		<td>
			<?php echo $student['Student']['registration_number']; ?>
		</td>
		<td>
			<?php echo $student['Student']['name'].".".$student['Student']['user_initial']; ?>
		</td>
		<td>
			<?php if($sno ==1){?>
				<input type="hidden" name="maxRow" value="<?php echo count($Students);?>" class="search_init" />
				<input type="hidden" name="typeMode" value="<?php echo $typeMode;?>" class="search_init" />
			<?php }?> 
			
			<input type="hidden" name="student_id<?php echo $sno;?>" value="<?php echo $student['Student']['id']; ?>">
			<input type="hidden" name="month_year_id<?php echo $sno;?>" value="<?php echo $MonthYears;?>">
			<input type="hidden" name="type<?php echo $sno;?>" value="<?php echo $type;?>">
			<input type="hidden" name="course_mapping_id<?php echo $sno;?>" value="<?php echo $courseMappingId;?>">	
			<?php $attPercentageId = "";$attPercentage = ""; 
				if(isset($Students[$sno-1]['Attendance'])){
					$attVar = $Students[$sno-1]['Attendance'];
					for($i=0;$i<count($attVar);$i++){
						if($attVar[$i]['course_mapping_id'] == $courseMappingId){
							$attPercentage = $attVar[$i]['percentage'];
							$attPercentageId = $attVar[$i]['id'];
						}
					}
				}				
			 ?>		
			<input type="text" class="markEntry" name="percentage<?php echo $sno;?>" value="<?php echo $attPercentage;?>" maxlength="3">
			<?php if($attPercentageId){?>
			<input type="hidden" name="attendance<?php echo $sno;?>" value="<?php echo $attPercentageId;?>">
			<?php }?>
		</td>
	</tr>
<?php $sno++; endforeach; ?>

	</tbody>
	<tfoot>
		<tr>
			<th><input type="text" name="S.No." value="S.No." class="search_init" /></th>
			<th><input type="text" name="Register No." value="Register No." class="search_init" /></th>
			<th><input type="text" name="Student Name" value="Student Name" class="search_init" /></th>
			<th><input type="text" name="Percentage" value="Percentage" class="search_init" /></th>
		</tr>
	</tfoot>
	</table>
	
</div>
<?php echo $this->Form->end();?>
<?php 	
	echo $this->Html->script('common');
	echo $this->Html->script('common-front');
?>
<script>leftMenuSelection('Attendances/index/C');</script>

<?php if($type == 'G'){ $varAtten = "GROSS ";$varParam = "G";} else{ $varAtten ="COURSE ";$varParam = "C";}?>  
<span class='breadcrumb1'>
<span class='navbar-brand'><small>MARKS <i class='ace-icon fa fa-angle-double-right'></i></small></span>
<span class='navbar-brand'><small>C.A.E <i class='ace-icon fa fa-angle-double-right'></i></small></span>
<?php echo $this->Html->link("<span class='navbar-brand'><small>$varAtten ATTENDANCE <i class='ace-icon fa fa-angle-double-right'></i></small></span>", array("controller"=>"Attendances",'action' => 'index',$varParam),array('data-placement'=>'left','escape' => false)); ?>
<?php echo $this->Html->link("<span class='navbar-brand'><small>ATTENDANCE ENTER <i class='ace-icon fa fa-angle-double-right'></i></small></span>", array("controller"=>"Attendances",'action' => 'attendance_entry',$typeMode,$type,$courseMappingId,$course_mode_id,$program_id,$batch_id,$MonthYears,$txtMonthYears),array('data-placement'=>'left','escape' => false)); ?>
</span>
