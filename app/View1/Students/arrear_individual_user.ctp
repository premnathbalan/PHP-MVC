<div class="col-sm-12">
<?php //echo $this->Form->button('<i class="ace-icon fa fa-check bigger-110"></i>'.__('Submit'),array('type'=>'submit','name'=>'submit','value'=>'submit', 'class'=>'btn','style'=>'float:right;')); ?>
</div>
<div id="resultdata"></div>
<?php
echo $this->Form->input('student_id', array('type'=> 'hidden', 'label'=>false, 'default'=>$student_id, 'name' => 'student_id'));
echo "<table border='1' class=' tblOddEven' style='width:100%;text-align:center;'>";
echo "<th style='height:30px;'>Sl.No.</th>";
echo "<th>CourseCode</th>";
echo "<th>CourseType</th>";
echo "<th>Course Max Marks</th>";
echo "<th>CAE Split Up</th>";
echo "<th>MIN CAE MARK</th>";
echo "<th>CAE</th>";
echo "<th>MIN ESE MARK</th>";
echo "<th>ESE</th>";
echo "<th>MIN TOTAL PASS MARK</th>";
echo "<th>Total</th>";
echo "<th>Status</th>";
echo "</tr>";

$i=1;
if (isset($results)) {
	if (count($results[0]['CourseStudentMapping']) == 0) {
		echo "<tr><td colspan='12'>No Arrear Data!!!</td></tr>";
	}
	else {
		$csmResults = $results[0]['CourseStudentMapping'];
		//pr($csmResults);
		$j=1;
		foreach ($csmResults as $key => $csmArray) { //pr($csmArray);
			if(isset($csmArray['CourseMapping']) && count($csmArray['CourseMapping'])>0) {
				$csmDetails = $csmArray['CourseMapping'];
				$course_type_id = $csmArray['CourseMapping']['Course']['course_type_id'];
				$cm_id = $csmArray['CourseMapping']['id'];
				$course_code = $csmArray['CourseMapping']['Course']['course_code'];
				//echo $i." ".$course_type_id." ";
				$course_max_marks = $csmArray['CourseMapping']['Course']['course_max_marks'];
				$min_cae_mark_pass_percent = $csmArray['CourseMapping']['Course']['min_cae_mark'];
				$max_cae_mark = $csmArray['CourseMapping']['Course']['max_cae_mark'];
				$min_ese_mark_pass_percent = $csmArray['CourseMapping']['Course']['min_ese_mark'];
				$max_ese_mark = $csmArray['CourseMapping']['Course']['max_ese_mark'];
				$total_min_pass_pass_percent = $csmArray['CourseMapping']['Course']['total_min_pass'];
				
				$min_cae_pass_mark = round($max_cae_mark * $min_cae_mark_pass_percent / 100);
				$min_ese_pass_mark = round($max_ese_mark * $min_ese_mark_pass_percent / 100);
				$min_total_pass_mark = round($course_max_marks * $total_min_pass_pass_percent / 100);
				
				$marks = "";
				$status = "";
					
				SWITCH ($course_type_id) {
					CASE 1:
						$tmp = $csmDetails['Cae']; //pr($tmp);
						$caetmpid = $tmp[0]['id'];
						$no_of_caes = count($csmDetails['Cae']);
							echo "<tr id=cm_id_$cm_id class='cm_id'>";
								echo "<td>".$j."</td>";
								echo "<td>".$course_code."</td>";
								echo "<td>Theory</td>";
								echo "<td>".$course_max_marks."</td>";
								echo "<td>";
								$k=0;
								echo $this->Form->input('no_of_caes_'.$cm_id, array('type'=> 'hidden', 'style'=>'position:relative;margin-top:-14px;width:50px;border-color:#000;color:#000;padding-left:10px;', 'label'=>false, 'default'=>$no_of_caes, 'name' => 'data[no_of_caes]['.$cm_id.']'));
								foreach ($tmp as $key => $caeArray) { 
									//echo $caeArray['id']." *** ";
								 $series = $k+1;
									$cont_ass_id = 0; $cont_ass_marks = '';
									if (isset($caeArray['ContinuousAssessmentExam'][0]['id'])) {
										$cont_ass_id = $caeArray['ContinuousAssessmentExam'][0]['id'];
									}
									if (isset($caeArray['ContinuousAssessmentExam'][0]['marks'])) {
										$cont_ass_marks = $caeArray['ContinuousAssessmentExam'][0]['marks'];
									}
									echo "CAE $series : ".$this->Form->input('cont_ass_id'.$k, array('type'=> 'hidden', 'style'=>'position:relative;margin-top:-14px;width:50px;border-color:#000;color:#000;padding-left:10px;', 'label'=>false, 'default'=>$cont_ass_id, 'name' => 'data[cae_details][cont_ass_id]['.$cm_id.']['.$k.']', 'class'=>'cae_'.$cm_id));
									echo $this->Form->input('cont_ass_marks_'.$cm_id.'_'.$k, array('type'=> 'hidden', 'style'=>'position:relative;margin-top:-14px;width:50px;border-color:#000;color:#000;padding-left:10px;', 'label'=>false, 'default'=>$cont_ass_marks, 'name' => 'data[cae_details][cont_ass_marks]['.$cm_id.']['.$k.']', 'class'=>'cae_'.$cm_id));
									echo $this->Form->input('cont_ass_marks_new_'.$cm_id.'_'.$k, array('type'=> 'text', 'style'=>'position:relative;margin-top:-14px;width:50px;border-color:#000;color:#000;padding-left:10px;', 'label'=>false, 'default'=>$cont_ass_marks, 'name' => 'data[cae_details][cont_ass_marks_new]['.$cm_id.']['.$k.']', 'onkeyup'=>"computeArrearIndividual('$cm_id', 'cae', this.value, $max_cae_mark, $k, 'A')", 'class'=>'caenew_'.$cm_id, 'asessment_type'=>'cae'));
									$k++;
								}
								echo "</td>";
								echo "<td>".$min_cae_pass_mark."</td>";
								echo "<td>";
								$internal_exam_id =0; $internal_exam_marks=''; $internal_exam_mod_op=''; $internal_exam_mod_marks=0;
								if (isset($csmArray['CourseMapping']['InternalExam'][0]['id'])) {
									$internal_exam_id = $csmArray['CourseMapping']['InternalExam'][0]['id'];
								}
								if (isset($csmArray['CourseMapping']['InternalExam'][0]['marks'])) {
									$internal_exam_marks = $csmArray['CourseMapping']['InternalExam'][0]['marks'];
								}
								if (isset($csmArray['CourseMapping']['InternalExam'][0]['moderation_operator'])) {
									$internal_exam_mod_op = $csmArray['CourseMapping']['InternalExam'][0]['moderation_operator'];
								}
								if (isset($csmArray['CourseMapping']['InternalExam'][0]['moderation_marks'])) {
									$internal_exam_mod_marks = $csmArray['CourseMapping']['InternalExam'][0]['moderation_marks'];
								}
									
									echo $this->Form->input('course_type_id', array('type'=> 'hidden', 'style'=>'width:50px;', 'label'=>false, 'default'=>$course_type_id, 'name' => 'data[course_type_id]'));
									echo $this->Form->input('internal_exam_id', array('type'=> 'hidden', 'style'=>'width:50px;border-color:#000;color:#000;padding-left:10px;', 'label'=>false, 'default'=>$internal_exam_id, 'name' => 'data[internal_exam_id]['.$cm_id.']'));
									echo $this->Form->input('internal_exam_marks_'.$cm_id, array('type'=> 'text', 'style'=>'width:50px;border-color:#EBEBEB;color:#000;padding-left:10px;', 'label'=>false, 'default'=>$internal_exam_marks, 'name' => 'data[internal_exam_marks]['.$cm_id.']', 'readonly'));
									echo $this->Form->input('internal_exam_mod_marks', array('type'=> 'hidden', 'style'=>'width:50px;border-color:#000;color:#000;padding-left:10px;', 'label'=>false, 'default'=>$internal_exam_mod_op, 'name' => 'data[internal_exam_mod_op]['.$cm_id.']'));
									echo $this->Form->input('internal_exam_mod_marks', array('type'=> 'hidden', 'style'=>'width:50px;border-color:#000;color:#000;padding-left:10px;', 'label'=>false, 'default'=>$internal_exam_mod_marks, 'name' => 'data[internal_exam_mod_marks]['.$cm_id.']'));
								echo "</td>";
								
								echo "<td>".$min_ese_pass_mark."</td>";
								echo "<td>";
								if (isset ($csmArray['CourseMapping']['StudentMark'][0]['revaluation_status']) && 
												$csmArray['CourseMapping']['StudentMark'][0]['revaluation_status']==1) {
												$model = "RevaluationExam";
												$mod_operator = "reval_moderation_operator";
												$mod_marks = "reval_moderation_marks";
								} else {
												$model = "EndSemesterExam";
												$mod_operator = "moderation_operator";
												$mod_marks = "moderation_marks";
								}
								
								if (isset($csmArray['CourseMapping'][$model]) && count($csmArray['CourseMapping'][$model])>0) {
									$ese_id = $csmArray['CourseMapping'][$model][0]['id'];
									$ese_marks = $csmArray['CourseMapping'][$model][0]['marks'];
									$ese_mod_op = $csmArray['CourseMapping'][$model][0][$mod_operator];
									$ese_mod_marks = $csmArray['CourseMapping'][$model][0][$mod_marks];
								}
								else {
									$ese_id = 0;
									$ese_marks = "A";
									$ese_mod_op = "";
									$ese_mod_marks = 0;
								}	
									echo $this->Form->input('ese_id', array('type'=> 'hidden', 'style'=>'width:50px;border-color:#000;color:#000;padding-left:10px;', 'label'=>false, 'default'=>$ese_id, 'name' => 'data[ese_id]['.$cm_id.']'));
									echo $this->Form->input('model', array('type'=> 'hidden', 'style'=>'width:50px;border-color:#000;color:#000;padding-left:10px;', 'label'=>false, 'default'=>$model, 'name' => 'data[model]['.$cm_id.']'));
									echo $this->Form->input('ese_marks_'.$cm_id, array('type'=> 'hidden', 'style'=>'width:50px;border-color:#000;color:#000;padding-left:10px;', 'label'=>false, 'default'=>$ese_marks, 'name' => 'data[ese_marks]['.$cm_id.']'));
									echo $this->Form->input('ese_new_marks_'.$cm_id, array('type'=> 'text', 'style'=>'width:50px;border-color:#000;color:#000;padding-left:10px;', 'label'=>false, 'default'=>$ese_marks, 'name' => 'data[ese_new_marks]['.$cm_id.']', 'onkeyup'=>"computeArrearIndividual('$cm_id', 'ese', this.value, $max_ese_mark, 0, 'A')", 'class'=>'esenew_'.$cm_id, 'asessment_type'=>'ese'));
									echo $this->Form->input('ese_mod_op', array('type'=> 'hidden', 'style'=>'width:50px;border-color:#000;color:#000;padding-left:10px;', 'label'=>false, 'default'=>$ese_mod_op, 'name' => 'data[ese_mod_operator]['.$cm_id.']'));
									echo $this->Form->input('ese_mod_marks', array('type'=> 'hidden', 'style'=>'width:50px;border-color:#000;color:#000;padding-left:10px;', 'label'=>false, 'default'=>$ese_mod_marks, 'name' => 'data[ese_mod_marks]['.$cm_id.']'));
								
								echo "</td>";
							
								echo "<td>".$min_total_pass_mark."</td>";
								echo "<td>";
								$student_mark_id=0; $total='';
								if (isset($csmArray['CourseMapping']['StudentMark'][0]['id'])) {
									$student_mark_id = $csmArray['CourseMapping']['StudentMark'][0]['id'];
								}
								if (isset($csmArray['CourseMapping']['StudentMark'][0]['marks'])) {
									$total = $csmArray['CourseMapping']['StudentMark'][0]['marks'];
								} 
								
								if (isset($csmArray['CourseMapping']['StudentMark'][0]['revaluation_status']) && 
									$csmArray['CourseMapping']['StudentMark'][0]['revaluation_status']==1) {
									$marks = $csmArray['CourseMapping']['StudentMark'][0]['final_marks'];
									$status = $csmArray['CourseMapping']['StudentMark'][0]['final_status'];
								}
								else if (isset($csmArray['CourseMapping']['StudentMark'][0]['status'])) {
									$marks = $csmArray['CourseMapping']['StudentMark'][0]['marks'];
									$status = $csmArray['CourseMapping']['StudentMark'][0]['status'];
								}
								else {
									$status="Fail";
									if ($internal_exam_marks != 'A' && $ese_marks != "A") {
										$marks = $internal_exam_marks + $ese_marks;
									}
									else if ($internal_exam_marks != 'A' && $ese_marks == "A") {
										$marks = $internal_exam_marks;
									}
									else if ($internal_exam_marks == 'A' && $ese_marks != "A") {
										$marks = $ese_marks;
									}
								}
									
									//echo $this->Form->input('student_mark_id', array('type'=> 'hidden', 'style'=>'width:50px;', 'label'=>false, 'default'=>$student_mark_id, 'name' => 'data[student_mark_id]['.$cm_id.'][]'));
									echo $this->Form->input('student_mark_id_'.$cm_id, array('type'=> 'hidden', 'style'=>'width:50px;', 'label'=>false, 'default'=>$student_mark_id, 'name' => 'data[student_mark_id]['.$cm_id.']'));
									echo $this->Form->input('student_marks_'.$cm_id, array('type'=> 'text', 'style'=>'width:50px;color:#000;padding-left:10px;border:none;', 'label'=>false, 'default'=>$marks, 'name' => 'data[student_mark]['.$cm_id.']', 'readonly'));
								echo "</td>";
								echo "<td>";
									
									
									echo "<div id='result_$cm_id'>".$status."</div>";
								echo "</td>";
							echo "</tr>";
							
					break;
					CASE 2:
						echo "<tr id=cm_id_$cm_id class='cm_id'>";
							echo "<td>".$j."</td>";
							echo "<td>".$course_code."</td>";
							echo "<td>Practical</td>";
							echo "<td>".$course_max_marks."</td>";
							echo "<td></td>";
							
							echo "<td>".$min_cae_pass_mark."</td>";
							echo "<td>";
								echo $this->Form->input('course_type_id', array('type'=> 'hidden', 'style'=>'width:50px;', 'label'=>false, 'default'=>$course_type_id, 'name' => 'data[course_type_id]'));
								$cae_id = $csmArray['CourseMapping']['CaePractical'][0]['InternalPractical'][0]['id'];
								//echo "cae_pra_id : ".$csmArray['CourseMapping']['CaePractical'][0]['id']." *** ".$cae_id;
								$cae = $csmArray['CourseMapping']['CaePractical'][0]['InternalPractical'][0]['marks'];
								echo $this->Form->input('course_type_id_'.$cm_id, array('type'=> 'hidden', 'style'=>'width:50px;', 'label'=>false, 'default'=>$course_type_id, 'name' => 'data[Practical][course_type_id]'));
								echo $this->Form->input('cae_id', array('type'=> 'hidden', 'style'=>'width:50px;border-color:#000;color:#000;padding-left:10px;', 'label'=>false, 'default'=>$cae_id, 'name' => 'data[cae_id]['.$cm_id.']'));
								echo $this->Form->input('cae_old_'.$cm_id, array('type'=> 'hidden', 'style'=>'width:50px;border-color:#000;color:#000;padding-left:10px;', 'label'=>false, 'default'=>$cae, 'name' => 'data[cae_old]['.$cm_id.']'));
								echo $this->Form->input('cae_'.$cm_id, array('type'=> 'text', 'style'=>'width:50px;border-color:#000;color:#000;padding-left:10px;', 'label'=>false, 'default'=>$cae, 'name' => 'data[cae]['.$cm_id.']', 'onkeyup'=>"computeArrearIndividual('$cm_id', 'cae', this.value, $max_cae_mark, 0, 'A')"));
								
							echo "</td>";
						
							echo "<td>".$min_ese_pass_mark."</td>";
							echo "<td>";
							if (isset($csmArray['CourseMapping']['EsePractical'][0]['Practical'][0]['id'])) {
								$ese_id = $csmArray['CourseMapping']['EsePractical'][0]['Practical'][0]['id'];
								//echo "ese_pra_id : ".$csmArray['CourseMapping']['EsePractical'][0]['id']." *** ".$ese_id;
								$ese = $csmArray['CourseMapping']['EsePractical'][0]['Practical'][0]['marks'];
								$ese_mod_op = $csmArray['CourseMapping']['EsePractical'][0]['Practical'][0]['moderation_operator'];
								$ese_mod_marks = $csmArray['CourseMapping']['EsePractical'][0]['Practical'][0]['moderation_marks'];
							}
							else {
								$ese_id = 0;
								$ese = "A";
								$ese_mod_op = "";
								$ese_mod_marks = 0;
							}
								echo $this->Form->input('ese_id', array('type'=> 'hidden', 'style'=>'width:50px;border-color:#000;color:#000;padding-left:10px;', 'label'=>false, 'default'=>$ese_id, 'name' => 'data[ese_id]['.$cm_id.']'));
								echo $this->Form->input('ese_marks_'.$cm_id, array('type'=> 'hidden', 'style'=>'width:50px;border-color:#000;color:#000;padding-left:10px;', 'label'=>false, 'default'=>$ese, 'name' => 'data[ese_marks]['.$cm_id.']'));
								echo $this->Form->input('ese_new_marks_'.$cm_id, array('type'=> 'text', 'style'=>'width:50px;border-color:#000;color:#000;padding-left:10px;', 'label'=>false, 'default'=>$ese, 'name' => 'data[ese_new_marks]['.$cm_id.']', 'onkeyup'=>"computeArrearIndividual('$cm_id', 'ese', this.value, $max_ese_mark, 0, 'A')"));
								echo $this->Form->input('ese_mod_marks_'.$cm_id, array('type'=> 'hidden', 'style'=>'width:50px;border-color:#000;color:#000;padding-left:10px;', 'label'=>false, 'default'=>$ese_mod_marks, 'name' => 'data[ese_mod_marks]['.$cm_id.']'));
							echo "</td>";
						
							echo "<td>".$min_total_pass_mark."</td>";
							echo "<td>";
							if (isset($csmArray['CourseMapping']['StudentMark'][0]['id']) && ($csmArray['CourseMapping']['StudentMark'][0]['month_year_id']==$month_year_id)) {
								$student_mark_id = $csmArray['CourseMapping']['StudentMark'][0]['id'];
								//echo $student_mark_id;
								$total = $csmArray['CourseMapping']['StudentMark'][0]['marks'];							}
							else {
								if (isset($ese)) $total = $cae+$ese;
								else $total = $cae;
								$student_mark_id = "";
							}
								echo $this->Form->input('student_mark_id_'.$cm_id, array('type'=> 'hidden', 'style'=>'width:50px;color:#000;padding-left:10px;', 'label'=>false, 'default'=>$student_mark_id, 'name' => 'data[student_mark_id]['.$cm_id.']'));
								echo $this->Form->input('student_mark_old_'.$cm_id, array('type'=> 'hidden', 'style'=>'width:50px;color:#000;padding-left:10px;', 'label'=>false, 'default'=>$total, 'name' => 'data[student_mark_old]['.$cm_id.']', 'readonly'));
								echo $this->Form->input('student_marks_'.$cm_id, array('type'=> 'text', 'style'=>'width:50px;color:#000;border:none;padding-left:10px;', 'label'=>false, 'default'=>$total, 'name' => 'data[student_mark]['.$cm_id.']', 'readonly'));
							
							echo "</td>";
							echo "<td>";
							if (isset($csmArray['CourseMapping']['StudentMark'][0]['id'])) {
								if ($csmArray['CourseMapping']['StudentMark'][0]['revaluation_status']==1) {
									$marks = $csmArray['CourseMapping']['StudentMark'][0]['final_marks'];
									$status = $csmArray['CourseMapping']['StudentMark'][0]['final_status'];
								}
								else {
									$marks = $csmArray['CourseMapping']['StudentMark'][0]['marks'];
									$status = $csmArray['CourseMapping']['StudentMark'][0]['status'];
								}
									echo "<div id='result_$cm_id'>".$status."</div>";
							}
							else {
								echo "Fail";
							}
							echo "</td>";
							echo "</tr>";
						break;
					CASE 3:
						$tmp = $csmDetails['Cae']; //pr($tmp);
						$caetmpid = $tmp[0]['id'];
						$no_of_caes = count($csmDetails['Cae']);
							echo "<tr id=cm_id_$cm_id class='cm_id'>";
								echo "<td>".$j."</td>";
								echo "<td>".$course_code."</td>";
								echo "<td>Theory and Practical</td>";
								echo "<td>".$course_max_marks."</td>";
								echo "<td>";
								$k=0;
								echo $this->Form->input('no_of_caes_'.$cm_id, array('type'=> 'hidden', 'style'=>'position:relative;margin-top:-14px;width:50px;border-color:#000;color:#000;padding-left:10px;', 'label'=>false, 'default'=>$no_of_caes, 'name' => 'data[no_of_caes]['.$cm_id.']'));
								foreach ($tmp as $key => $caeArray) { 
									//echo $caeArray['id']." *** ";
								 $series = $k+1;
									$cont_ass_id = 0; $cont_ass_marks = '';
									if (isset($caeArray['ContinuousAssessmentExam'][0]['id'])) {
										$cont_ass_id = $caeArray['ContinuousAssessmentExam'][0]['id'];
									}
									if (isset($caeArray['ContinuousAssessmentExam'][0]['marks'])) {
										$cont_ass_marks = $caeArray['ContinuousAssessmentExam'][0]['marks'];
									}
									echo "CAE $series : ".$this->Form->input('cont_ass_id'.$k, array('type'=> 'hidden', 'style'=>'position:relative;margin-top:-14px;width:50px;border-color:#000;color:#000;padding-left:10px;', 'label'=>false, 'default'=>$cont_ass_id, 'name' => 'data[cae_details][cont_ass_id]['.$cm_id.']['.$k.']', 'class'=>'cae_'.$cm_id));
									echo $this->Form->input('cont_ass_marks_'.$cm_id.'_'.$k, array('type'=> 'hidden', 'style'=>'position:relative;margin-top:-14px;width:50px;border-color:#000;color:#000;padding-left:10px;', 'label'=>false, 'default'=>$cont_ass_marks, 'name' => 'data[cae_details][cont_ass_marks]['.$cm_id.']['.$k.']', 'class'=>'cae_'.$cm_id));
									echo $this->Form->input('cont_ass_marks_new_'.$cm_id.'_'.$k, array('type'=> 'text', 'style'=>'position:relative;margin-top:-14px;width:50px;border-color:#000;color:#000;padding-left:10px;', 'label'=>false, 'default'=>$cont_ass_marks, 'name' => 'data[cae_details][cont_ass_marks_new]['.$cm_id.']['.$k.']', 'onkeyup'=>"computeArrearIndividual('$cm_id', 'cae', this.value, $max_cae_mark, $k, 'A')", 'class'=>'caenew_'.$cm_id, 'asessment_type'=>'cae'));
									$k++;
								}
								echo "</td>";
								echo "<td>".$min_cae_pass_mark."</td>";
								echo "<td>";
								$internal_exam_id =0; $internal_exam_marks=''; $internal_exam_mod_op=''; $internal_exam_mod_marks=0;
								if (isset($csmArray['CourseMapping']['InternalExam'][0]['id'])) {
									$internal_exam_id = $csmArray['CourseMapping']['InternalExam'][0]['id'];
								}
								if (isset($csmArray['CourseMapping']['InternalExam'][0]['marks'])) {
									$internal_exam_marks = $csmArray['CourseMapping']['InternalExam'][0]['marks'];
								}
								if (isset($csmArray['CourseMapping']['InternalExam'][0]['moderation_operator'])) {
									$internal_exam_mod_op = $csmArray['CourseMapping']['InternalExam'][0]['moderation_operator'];
								}
								if (isset($csmArray['CourseMapping']['InternalExam'][0]['moderation_marks'])) {
									$internal_exam_mod_marks = $csmArray['CourseMapping']['InternalExam'][0]['moderation_marks'];
								}
									
									echo $this->Form->input('course_type_id', array('type'=> 'hidden', 'style'=>'width:50px;', 'label'=>false, 'default'=>$course_type_id, 'name' => 'data[course_type_id]'));
									echo $this->Form->input('internal_exam_id', array('type'=> 'hidden', 'style'=>'width:50px;border-color:#000;color:#000;padding-left:10px;', 'label'=>false, 'default'=>$internal_exam_id, 'name' => 'data[internal_exam_id]['.$cm_id.']'));
									echo $this->Form->input('internal_exam_marks_'.$cm_id, array('type'=> 'text', 'style'=>'width:50px;border-color:#EBEBEB;color:#000;padding-left:10px;', 'label'=>false, 'default'=>$internal_exam_marks, 'name' => 'data[internal_exam_marks]['.$cm_id.']', 'readonly'));
									echo $this->Form->input('internal_exam_mod_marks', array('type'=> 'hidden', 'style'=>'width:50px;border-color:#000;color:#000;padding-left:10px;', 'label'=>false, 'default'=>$internal_exam_mod_op, 'name' => 'data[internal_exam_mod_op]['.$cm_id.']'));
									echo $this->Form->input('internal_exam_mod_marks', array('type'=> 'hidden', 'style'=>'width:50px;border-color:#000;color:#000;padding-left:10px;', 'label'=>false, 'default'=>$internal_exam_mod_marks, 'name' => 'data[internal_exam_mod_marks]['.$cm_id.']'));
								echo "</td>";
								
								echo "<td>".$min_ese_pass_mark."</td>";
								echo "<td>";
								
								if (isset($csmArray['CourseMapping']['EsePractical'][0]['Practical'][0]['id'])) {
									$ese_id = $csmArray['CourseMapping']['EsePractical'][0]['Practical'][0]['id'];
									//echo "ese_pra_id : ".$csmArray['CourseMapping']['EsePractical'][0]['id']." *** ".$ese_id;
									$ese = $csmArray['CourseMapping']['EsePractical'][0]['Practical'][0]['marks'];
									$ese_mod_op = $csmArray['CourseMapping']['EsePractical'][0]['Practical'][0]['moderation_operator'];
									$ese_mod_marks = $csmArray['CourseMapping']['EsePractical'][0]['Practical'][0]['moderation_marks'];
								}
								else {
									$ese_id = 0;
									$ese = "A";
									$ese_mod_op = "";
									$ese_mod_marks = 0;
								}
							
								echo $this->Form->input('ese_id', array('type'=> 'hidden', 'style'=>'width:50px;border-color:#000;color:#000;padding-left:10px;', 'label'=>false, 'default'=>$ese_id, 'name' => 'data[ese_id]['.$cm_id.']'));
								echo $this->Form->input('ese_marks_'.$cm_id, array('type'=> 'hidden', 'style'=>'width:50px;border-color:#000;color:#000;padding-left:10px;', 'label'=>false, 'default'=>$ese, 'name' => 'data[ese_marks]['.$cm_id.']'));
								echo $this->Form->input('ese_new_marks_'.$cm_id, array('type'=> 'text', 'style'=>'width:50px;border-color:#000;color:#000;padding-left:10px;', 'label'=>false, 'default'=>$ese, 'name' => 'data[ese_new_marks]['.$cm_id.']', 'onkeyup'=>"computeArrearIndividual('$cm_id', 'ese', this.value, $max_ese_mark, 0, 'A')"));
								echo $this->Form->input('ese_mod_marks_'.$cm_id, array('type'=> 'hidden', 'style'=>'width:50px;border-color:#000;color:#000;padding-left:10px;', 'label'=>false, 'default'=>$ese_mod_marks, 'name' => 'data[ese_mod_marks]['.$cm_id.']'));
							echo "</td>";
						
							echo "<td>".$min_total_pass_mark."</td>";
							echo "<td>";
								$student_mark_id = $csmArray['CourseMapping']['StudentMark'][0]['id'];
								//echo $student_mark_id;
								$total = $csmArray['CourseMapping']['StudentMark'][0]['marks'];
								echo $this->Form->input('student_mark_id_'.$cm_id, array('type'=> 'hidden', 'style'=>'width:50px;color:#000;padding-left:10px;', 'label'=>false, 'default'=>$student_mark_id, 'name' => 'data[student_mark_id]['.$cm_id.']'));
								echo $this->Form->input('student_mark_old_'.$cm_id, array('type'=> 'hidden', 'style'=>'width:50px;color:#000;padding-left:10px;', 'label'=>false, 'default'=>$total, 'name' => 'data[student_mark_old]['.$cm_id.']', 'readonly'));
								echo $this->Form->input('student_marks_'.$cm_id, array('type'=> 'text', 'style'=>'width:50px;color:#000;border:none;padding-left:10px;', 'label'=>false, 'default'=>$total, 'name' => 'data[student_mark]['.$cm_id.']', 'readonly'));
							echo "</td>";
							echo "<td>";
								if ($csmArray['CourseMapping']['StudentMark'][0]['revaluation_status']==1) {
									$marks = $csmArray['CourseMapping']['StudentMark'][0]['final_marks'];
									$status = $csmArray['CourseMapping']['StudentMark'][0]['final_status'];
								}
								else {
									$marks = $csmArray['CourseMapping']['StudentMark'][0]['marks'];
									$status = $csmArray['CourseMapping']['StudentMark'][0]['status'];
								}
								echo "<div id='result_$cm_id'>".$status."</div>";
							echo "</td>";
							echo "</tr>";
						break;
					CASE 4:
						//echo $course_type_id;
						$tmp = $csmDetails['CaeProject']; 
						//pr($tmp);
						$caetmpid = $tmp[0]['id'];
						
						$no_of_caes = count($csmDetails['CaeProject']);
							echo "<tr id=cm_id_$cm_id class='cm_id'>";
								echo "<td>".$j."</td>";
								echo "<td>".$course_code."</td>";
								echo "<td>Project</td>";
								echo "<td>".$course_max_marks."</td>";
								echo "<td>";
								$k=0;
								echo $this->Form->input('no_of_caes_'.$cm_id, array('type'=> 'hidden', 'style'=>'position:relative;margin-top:-14px;width:50px;border-color:#000;color:#000;padding-left:10px;', 'label'=>false, 'default'=>$no_of_caes, 'name' => 'data[no_of_caes]['.$cm_id.']'));
								foreach ($tmp as $key => $caeArray) { 
									//echo $caeArray['id']." *** ";
									$caeMark = $caeArray['marks'];
								 $series = $k+1;
									$cont_ass_id = 0; $cont_ass_marks = '';
									if (isset($caeArray['ProjectReview'][0]['id'])) {
										$cont_ass_id = $caeArray['ProjectReview'][0]['id'];
									}
									if (isset($caeArray['ProjectReview'][0]['marks'])) {
										$cont_ass_marks = $caeArray['ProjectReview'][0]['marks'];
									}
									echo "CAE $series : ".$this->Form->input('cont_ass_id'.$k, array('type'=> 'hidden', 'style'=>'position:relative;margin-top:-14px;width:50px;border-color:#000;color:#000;padding-left:10px;', 'label'=>false, 'default'=>$cont_ass_id, 'name' => 'data[cae_details][cont_ass_id]['.$cm_id.']['.$k.']', 'class'=>'cae_'.$cm_id));
									echo $this->Form->input('cont_ass_marks_'.$cm_id.'_'.$k, array('type'=> 'hidden', 'style'=>'position:relative;margin-top:-14px;width:50px;border-color:#000;color:#000;padding-left:10px;', 'label'=>false, 'default'=>$cont_ass_marks, 'name' => 'data[cae_details][cont_ass_marks]['.$cm_id.']['.$k.']', 'class'=>'cae_'.$cm_id));
									echo $this->Form->input('cont_ass_marks_new_'.$cm_id.'_'.$k, array('type'=> 'text', 'style'=>'position:relative;margin-top:-14px;width:50px;border-color:#000;color:#000;padding-left:10px;', 'label'=>false, 'default'=>$cont_ass_marks, 'name' => 'data[cae_details][cont_ass_marks_new]['.$cm_id.']['.$k.']', 'onkeyup'=>"computeArrearIndividual('$cm_id', 'cae', this.value, $caeMark, $k, 'A')", 'class'=>'caenew_'.$cm_id, 'asessment_type'=>'cae'));
									$k++;
								}
								echo "</td>";
								echo "<td>".$min_cae_pass_mark."</td>";
								echo "<td>";
								$internal_exam_id =0; $internal_exam_marks=''; $internal_exam_mod_op=''; $internal_exam_mod_marks=0;
								if (isset($csmArray['CourseMapping']['InternalProject'][0]['id'])) {
									$internal_exam_id = $csmArray['CourseMapping']['InternalProject'][0]['id'];
								}
								if (isset($csmArray['CourseMapping']['InternalProject'][0]['marks'])) {
									$internal_exam_marks = $csmArray['CourseMapping']['InternalProject'][0]['marks'];
								}
								if (isset($csmArray['CourseMapping']['InternalProject'][0]['moderation_operator'])) {
									$internal_exam_mod_op = $csmArray['CourseMapping']['InternalProject'][0]['moderation_operator'];
								}
								if (isset($csmArray['CourseMapping']['InternalProject'][0]['moderation_marks'])) {
									$internal_exam_mod_marks = $csmArray['CourseMapping']['InternalProject'][0]['moderation_marks'];
								}
									
									echo $this->Form->input('course_type_id', array('type'=> 'hidden', 'style'=>'width:50px;', 'label'=>false, 'default'=>$course_type_id, 'name' => 'data[course_type_id]'));
									echo $this->Form->input('internal_exam_id', array('type'=> 'hidden', 'style'=>'width:50px;border-color:#000;color:#000;padding-left:10px;', 'label'=>false, 'default'=>$internal_exam_id, 'name' => 'data[internal_exam_id]['.$cm_id.']'));
									echo $this->Form->input('internal_exam_marks_'.$cm_id, array('type'=> 'text', 'style'=>'width:50px;border-color:#EBEBEB;color:#000;padding-left:10px;', 'label'=>false, 'default'=>$internal_exam_marks, 'name' => 'data[internal_exam_marks]['.$cm_id.']', 'readonly'));
									echo $this->Form->input('internal_exam_mod_marks', array('type'=> 'hidden', 'style'=>'width:50px;border-color:#000;color:#000;padding-left:10px;', 'label'=>false, 'default'=>$internal_exam_mod_op, 'name' => 'data[internal_exam_mod_op]['.$cm_id.']'));
									echo $this->Form->input('internal_exam_mod_marks', array('type'=> 'hidden', 'style'=>'width:50px;border-color:#000;color:#000;padding-left:10px;', 'label'=>false, 'default'=>$internal_exam_mod_marks, 'name' => 'data[internal_exam_mod_marks]['.$cm_id.']'));
								echo "</td>";
								
								echo "<td>".$min_ese_pass_mark."</td>";
								echo "<td>";
								
								if (isset($csmArray['CourseMapping']['EseProject'][0]['ProjectViva'][0]['id'])) {
									$ese_id = $csmArray['CourseMapping']['EseProject'][0]['ProjectViva'][0]['id'];
									$ese = $csmArray['CourseMapping']['EseProject'][0]['ProjectViva'][0]['marks'];
									//$ese_mod_op = $csmArray['CourseMapping']['EseProject'][0]['ProjectViva'][0]['moderation_operator'];
									//$ese_mod_marks = $csmArray['CourseMapping']['EseProject'][0]['ProjectViva'][0]['moderation_marks'];
								}
								else {
									$ese_id = 0;
									$ese = "A";
									//$ese_mod_op = "";
									//$ese_mod_marks = 0;
								}
								
								//$ese_id = $csmArray['CourseMapping']['EseProject'][0]['ProjectViva'][0]['id'];
								//echo "ese_pra_id : ".$csmArray['CourseMapping']['EseProject'][0]['id']." *** ".$ese_id;
								//$ese = $csmArray['CourseMapping']['EseProject'][0]['ProjectViva'][0]['marks'];
								echo $this->Form->input('ese_id', array('type'=> 'hidden', 'style'=>'width:50px;border-color:#000;color:#000;padding-left:10px;', 'label'=>false, 'default'=>$ese_id, 'name' => 'data[ese_id]['.$cm_id.']'));
								echo $this->Form->input('ese_marks_'.$cm_id, array('type'=> 'hidden', 'style'=>'width:50px;border-color:#000;color:#000;padding-left:10px;', 'label'=>false, 'default'=>$ese, 'name' => 'data[ese_marks]['.$cm_id.']'));
								echo $this->Form->input('ese_new_marks_'.$cm_id, array('type'=> 'text', 'style'=>'width:50px;border-color:#000;color:#000;padding-left:10px;', 'label'=>false, 'default'=>$ese, 'name' => 'data[ese_new_marks]['.$cm_id.']', 'onkeyup'=>"computeArrearIndividual('$cm_id', 'ese', this.value, $max_cae_mark, 0, 'A')"));
								echo "</td>";
							
								echo "<td>".$min_total_pass_mark."</td>";
								echo "<td>";
								$student_mark_id=0; $total='';
								if (isset($csmArray['CourseMapping']['StudentMark'][0]['id'])) {
									$student_mark_id = $csmArray['CourseMapping']['StudentMark'][0]['id'];
								}
								if (isset($csmArray['CourseMapping']['StudentMark'][0]['marks'])) {
									$total = $csmArray['CourseMapping']['StudentMark'][0]['marks'];
								}
								
								if (isset($csmArray['CourseMapping']['StudentMark'][0]['revaluation_status']) && 
												$csmArray['CourseMapping']['StudentMark'][0]['revaluation_status']==1) {
										$marks = $csmArray['CourseMapping']['StudentMark'][0]['final_marks'];
										$status = $csmArray['CourseMapping']['StudentMark'][0]['final_status'];
									}
									else if (isset($csmArray['CourseMapping']['StudentMark'][0]['status'])) {
										$marks = $csmArray['CourseMapping']['StudentMark'][0]['marks'];
										$status = $csmArray['CourseMapping']['StudentMark'][0]['status'];
									}
									
									//echo $this->Form->input('student_mark_id', array('type'=> 'hidden', 'style'=>'width:50px;', 'label'=>false, 'default'=>$student_mark_id, 'name' => 'data[student_mark_id]['.$cm_id.'][]'));
									echo $this->Form->input('student_mark_id_'.$cm_id, array('type'=> 'hidden', 'style'=>'width:50px;', 'label'=>false, 'default'=>$student_mark_id, 'name' => 'data[student_mark_id]['.$cm_id.']'));
									echo $this->Form->input('student_marks_'.$cm_id, array('type'=> 'text', 'style'=>'width:50px;color:#000;padding-left:10px;border:none;', 'label'=>false, 'default'=>$marks, 'name' => 'data[student_mark]['.$cm_id.']', 'readonly'));
								echo "</td>";
								echo "<td>";
									
									
									echo "<div id='result_$cm_id'>".$status."</div>";
								echo "</td>";
							echo "</tr>";
						break;
				}
				$j++;
				//echo "</br>";
			}
		} 
	}
}
else {
	echo "<tr><td colspan='12'>Invalid registration number. Please, try again.</td></tr>";
}
echo "</table>";
?>

<?php echo $this->Html->script('common'); ?>
<?php echo $this->Html->script('common-front'); ?>

<span class='breadcrumb1'>
<span class='navbar-brand'><small>MARKS <i class='ace-icon fa fa-angle-double-right'></i></small></span>
<?php echo $this->Html->link("<span class='navbar-brand'><small> Moderation <i class='ace-icon fa fa-angle-double-right'></i></small></span>", array("controller"=>"Students",'action' => 'individualUser'),array('data-placement'=>'left','escape' => false)); ?>
</span>