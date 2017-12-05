<?php 
//pr($courseMappingResult);
//pr($newSemesterIdArray);
//pr($courseIdArray);
if($this->Html->checkPathAccesstopath('CourseStudentMappings/listStudents','',$authUser['id'])){
	echo $this->Form->button('<i class="ace-icon fa fa-check bigger-110"></i>'.__('Submit'),array('type'=>'submit','name'=>'submit','id'=>'submit','value'=>'list','class'=>'btn js-csm-submit'));
}	
?>
		
		<table cellpadding="0" cellspacing="0" border="1" class="display" id="example" style="margin-top:10px;">
			<thead>
				<tr>					
					<th style='padding:0px;'>Reg&nbsp;No.&nbsp;&nbsp;&nbsp;</th>
					<th style='padding:0px;'>Student&nbsp;Name&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>
					<?php
					foreach ($courseMappingResult as $key => $cmArray) { 
					//echo $cmArray['CourseMapping']['course_id']." *** ";
						echo "<th class='thAction' style='text-align:center;padding:0px;'>".trim($cmArray['Course']['course_code'])."&nbsp;".
						$this->Form->input('course_mapping_id', array('type'=>'checkbox', 'label'=>'', 'id'=>trim($cmArray['Course']['course_code']),'onclick'=>'chkBoxMapStudents(this.id);'))
						."&nbsp;".$this->Form->input('course_details', array('type'=> 'hidden', 'default' => $cmArray['CourseMapping']['id'], 'label'=>false, 'name' =>  'data[CSM][CourseCode]['.trim($cmArray['Course']['course_code']).']'))
						."&nbsp;".$this->Form->input('course_number', array('type'=> 'hidden', 'default' => $cmArray['CourseMapping']['course_number'], 'label'=>false, 'name' => 'data[CSM][CourseNumber]['.trim($cmArray['Course']['course_code']).']'))
						."&nbsp;".$this->Form->input('course_id', array('type'=> 'hidden', 'default'=>$cmArray['CourseMapping']['course_id'], 'label'=>false, 'name' => 'data[CSM][CourseId]['.trim($cmArray['Course']['course_code']).']'))
						."</th>";
						//echo "<th class='thAction' style='text-align:center;'>".trim($cmArray['Course']['course_code'])."</th>";
					}
					?>
				</tr>
			</thead>
			<tbody>
			<?php
			$i=1;
			foreach ($studentResult as $student_id => $stuArray) {
			//pr($stuArray);
				echo "<tr class='gradeX'>";
					
					echo "<td>".$stuArray['reg_num']." ".$this->Form->input('student_id', array('type'=> 'hidden', 'default' => $student_id, 'label'=>false, 'name' =>  'data[CSM][student_id]['.$student_id.']')).
					"</td>";
					echo "<td>".$stuArray['name']."</td>";
					foreach ($courseMappingResult as $key => $cmArray) { 
					//pr($cmArray);
					//pr($newSemesterIdArray[$cmArray['Course']['course_code']]);
					
						if (isset ($newSemesterIdArray[trim($cmArray['Course']['course_code'])][$student_id])) 
							$new_semester_id=$newSemesterIdArray[trim($cmArray['Course']['course_code'])][$student_id]." Sem";
						else $new_semester_id="";
						
						if (isset($tmpArray[trim($cmArray['Course']['course_code'])][$student_id]) && $tmpArray[trim($cmArray['Course']['course_code'])][$student_id] == 1) { 
							echo "<td>".$this->Form->input('checkbox'.$i, array('type'=>'checkbox','label'=>false, 'style'=>'text-align:center;margin-top:-15px;margin-left:5px;','checked'=>'checked', 'name'=>'data[CSM][Course]['.trim($cmArray['Course']['course_code']).']['.$student_id.']', 'class'=>trim($cmArray['Course']['course_code'])))." ".$new_semester_id."</td>";
						}
						else {
							if ($stuArray['type']==1 || ($stuArray['type']>1 && $stuArray['month_year_id']<=$month_year_id)) {
								echo "<td>".$this->Form->input('checkbox'.$i, array('type'=>'checkbox','label'=>false, 'style'=>'text-align:center;margin-top:-15px;margin-left:5px;', 'name'=>'data[CSM][Course]['.trim($cmArray['Course']['course_code']).']['.$student_id.']', 'class'=>trim($cmArray['Course']['course_code'])))." ".$new_semester_id."</td>";
							}
							else {
								echo "<td>Close</td>";
								
							} 
						}
					}
				echo "</tr>";
				$i++;
			} 
			?>
			</tbody>
		</table>
<?php echo $this->Html->script('common'); ?>

<span class='breadcrumb1'>
<span class='navbar-brand'><small>MASTERS <i class='ace-icon fa fa-angle-double-right'></i></small></span>
<?php echo $this->Html->link("<span class='navbar-brand'><small> Course Student Mapping <i class='ace-icon fa fa-angle-double-right'></i></small></span>", array("controller"=>"CourseStudentMappings",'action' => 'mapStudents'),array('data-placement'=>'left','escape' => false)); ?>
</span>