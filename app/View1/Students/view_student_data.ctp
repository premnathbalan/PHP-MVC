<?php
//pr($results);
//die;
if ($results[0]['Batch']['academic'] == "JUN") $academic="A";
$batch = $results[0]['Batch']['batch_from']."-".$results[0]['Batch']['batch_to']." ".$academic;
?>
<div>	
<div class="searchFrm bgFrame1">	
	<div class="col-lg-12">
		<div class="col-lg-4">Month Year :
			<?php echo $month_year; ?>
		</div>
		<div class="col-lg-4">Batch :
			<?php echo $batch; ?>
		</div>	
		<div class="col-lg-4">Program :
			<?php echo $results[0]['Program']['Academic']['academic_name']; ?>
		</div>
	</div>
	<div class="col-lg-12">
		<div class="col-lg-4">Specialisation :
			<?php echo $results[0]['Program']['program_name']; ?>
		</div>
		<div class="col-lg-4">Register Number :
			<?php echo $results[0]['Student']['registration_number']; ?>		
		</div>
		<div class="col-lg-4">Name :
			<?php echo $results[0]['Student']['name']; ?>		
		</div>				
	</div>
</div>

<?php
echo $this->Form->create('Ese');
echo $this->Form->input('student_id', array('type'=> 'hidden', 'style'=>'width:50px;', 'label'=>false, 'default'=>$student_id, 'name' => 'data[student_id]'));
echo $this->Form->input('reg_num', array('type'=> 'hidden', 'style'=>'width:50px;', 'label'=>false, 'default'=>$results[0]['Student']['registration_number'], 'name' => 'data[reg_num]'));
echo $this->Form->input('exam_month_year_id', array('type'=> 'hidden', 'style'=>'width:50px;', 'label'=>false, 'default'=>$exam_month_year_id, 'name' => 'data[month_year_id]'));
if(isset($results) && count($results)>0) { ?>
<?php echo $this->Form->button('<i class="ace-icon fa fa-check bigger-110"></i>'.__('&nbsp;Submit&nbsp;'),array('type'=>'submit','name'=>'submit','value'=>'submit','class'=>'btn','style'=>'margin:5px 5px 10px 5px;float:right;')); ?>
<div style='clear:both;'></div>
<?php
}

//pr($results);
$studentMarks = $results[0]['StudentMark'];
$internalExam = $results[0]['InternalExam'];
$internalPractical = $results[0]['InternalPractical'];

$endSemesterExam = $results[0]['EndSemesterExam'];
$practical = $results[0]['Practical'];
//pr($endSemesterExam);

echo "<table border='1; style='margin:5px;width:100%;margin-top:15px;' class='display tblOddEven'>";
echo "<tr><th>Course Code</th><th>Course Type</th><th>Course Max Marks</th><th>Max ESE Mark</th><th>CAE</th><th>ESE</th><th>Total</th><th>Status</th></tr>";
$i=1;
foreach ($studentMarks as $key => $smArray) {

	echo "<tr>";
		echo "<td>".$smArray['CourseMapping']['Course']['course_code']."</td>";
		echo "<td>".$smArray['CourseMapping']['Course']['CourseType']['course_type']."</td>";
		echo "<td>".$smArray['CourseMapping']['course_max_marks']."</td>";
		echo "<td>".$smArray['CourseMapping']['max_ese_mark']."</td>";
		$cm_id = $smArray['CourseMapping']['id'];
		$course_type_id = $smArray['CourseMapping']['Course']['course_type_id'];
		if ($course_type_id == 1) {
			foreach ($internalExam as $ieKey => $ieValue) {
				if($ieValue['course_mapping_id'] == $cm_id) 
					echo "<td>".$ieValue['marks']."</td>";
			}
			foreach ($endSemesterExam as $eseKey => $eseValue) {
				if($eseValue['course_mapping_id'] == $cm_id)
					echo "<td align='center'>".$this->Form->input('ese_new_value', array('type'=> 'text', 'style'=>'width:50px;margin-top:8px;border-color:#000;color:#000;padding-left:10px;', 'label'=>false, 'default'=>$eseValue['marks'], 'name' => 'data[Mark][EndSemesterExam][New]['.$cm_id.']', 'class'=>'eseValue'.$i,'onblur'=>"eseValidate(this.value, $i)"))
		." ".
		$this->Form->input('ese_old_value', array('type'=> 'hidden', 'style'=>'width:50px;', 'label'=>false, 'default'=>$eseValue['marks'], 'name' => 'data[Mark][EndSemesterExam][Old]['.$cm_id.']', 'class'=>'eseOldValue'.$i))
		." ".
		$this->Form->input('ese_id', array('type'=> 'hidden', 'style'=>'width:50px;', 'label'=>false, 'default'=>$eseValue['id'], 'name' => 'data[Mark][EndSemesterExam][id]['.$cm_id.']'))
		." ".
		$this->Form->input('mod_marks', array('type'=> 'hidden', 'style'=>'width:50px;', 'label'=>false, 'default'=>$eseValue['moderation_marks'], 'name' => 'data[Mark][EndSemesterExam][mod_marks]['.$cm_id.']'))		
		." ".
		$this->Form->input('student_mark_old', array('type'=> 'hidden', 'style'=>'width:50px;', 'label'=>false, 'default'=>$smArray['marks'], 'name' => 'data[Mark][EndSemesterExam][StudentMark]['.$cm_id.']'))
		." ".
		$this->Form->input('student_mark_id', array('type'=> 'hidden', 'style'=>'width:50px;', 'label'=>false, 'default'=>$smArray['id'], 'name' => 'data[Mark][EndSemesterExam][StudentMarkId]['.$cm_id.']'))
		." ".
		$this->Form->input('max_ese_marks', array('type'=> 'hidden', 'style'=>'width:50px;', 'label'=>false, 'default'=>$smArray['CourseMapping']['max_ese_mark'], 'name' => 'data[Mark][EndSemesterExam][MaxEseMark]['.$cm_id.']', 'class'=>'maxEseValue'.$i))
		." ".
		$this->Form->input('course_max_marks', array('type'=> 'hidden', 'style'=>'width:50px;', 'label'=>false, 'default'=>$smArray['CourseMapping']['course_max_marks'], 'name' => 'data[Mark][EndSemesterExam][CourseMaxMark]['.$cm_id.']'))
		." ".
		$this->Form->input('min_ese_marks', array('type'=> 'hidden', 'style'=>'width:50px;', 'label'=>false, 'default'=>$smArray['CourseMapping']['min_ese_mark'], 'name' => 'data[Mark][EndSemesterExam][MinEseMark]['.$cm_id.']'))
		." ".
		$this->Form->input('total_min_pass', array('type'=> 'hidden', 'style'=>'width:50px;', 'label'=>false, 'default'=>$smArray['CourseMapping']['total_min_pass'], 'name' => 'data[Mark][EndSemesterExam][TotalMinPass]['.$cm_id.']'))
		." ".
		$this->Form->input('course_code', array('type'=> 'hidden', 'style'=>'width:50px;', 'label'=>false, 'default'=>$smArray['CourseMapping']['Course']['course_code'], 'name' => 'data[Mark][EndSemesterExam][CourseCode]['.$cm_id.']'))
					."</td>";
			}
		}
		else if ($course_type_id == 2) {
			foreach ($internalPractical as $ipKey => $ipValue) {
				if($ipValue['CaePractical']['course_mapping_id'] == $cm_id) 
					echo "<td>".$ipValue['marks']."</td>";
			}
			foreach ($practical as $pKey => $pValue) {
				if($pValue['EsePractical']['course_mapping_id'] == $cm_id) 
					echo "<td align='center'>".$this->Form->input('practical_new_value', array('type'=> 'text', 'style'=>'width:50px;margin-top:8px;border-color:#000;color:#000;padding-left:10px;', 'label'=>false, 'default'=>$pValue['marks'], 'name' => 'data[Mark][Practical][New]['.$pValue['ese_practical_id'].']', 'class'=>'eseValue'.$i,'onblur'=>"eseValidate(this.value, $i)"))
		." ".
		$this->Form->input('practical_old_value', array('type'=> 'hidden', 'style'=>'width:50px;', 'label'=>false, 'default'=>$pValue['marks'], 'name' => 'data[Mark][Practical][Old]['.$pValue['ese_practical_id'].']', 'class'=>'eseOldValue'.$i))
		." ".
		$this->Form->input('practical_id', array('type'=> 'hidden', 'style'=>'width:50px;', 'label'=>false, 'default'=>$pValue['id'], 'name' => 'data[Mark][Practical][id]['.$pValue['ese_practical_id'].']'))
		." ".
		$this->Form->input('mod_marks', array('type'=> 'hidden', 'style'=>'width:50px;', 'label'=>false, 'default'=>$pValue['ese_mod_marks'], 'name' => 'data[Mark][Practical][mod_marks]['.$pValue['ese_practical_id'].']'))
		." ".
		$this->Form->input('student_mark_old', array('type'=> 'hidden', 'style'=>'width:50px;', 'label'=>false, 'default'=>$smArray['marks'], 'name' => 'data[Mark][Practical][StudentMark]['.$cm_id.']'))
		." ".
		$this->Form->input('student_mark_id', array('type'=> 'hidden', 'style'=>'width:50px;', 'label'=>false, 'default'=>$smArray['id'], 'name' => 'data[Mark][Practical][StudentMarkId]['.$cm_id.']'))
		." ".
		$this->Form->input('max_ese_marks', array('type'=> 'hidden', 'style'=>'width:50px;', 'label'=>false, 'default'=>$smArray['CourseMapping']['max_ese_mark'], 'name' => 'data[Mark][Practical][MaxEseMark]['.$cm_id.']', 'class'=>'maxEseValue'.$i))
		." ".
		$this->Form->input('course_max_marks', array('type'=> 'hidden', 'style'=>'width:50px;', 'label'=>false, 'default'=>$smArray['CourseMapping']['course_max_marks'], 'name' => 'data[Mark][Practical][CourseMaxMark]['.$cm_id.']'))
		." ".
		$this->Form->input('min_ese_marks', array('type'=> 'hidden', 'style'=>'width:50px;', 'label'=>false, 'default'=>$smArray['CourseMapping']['min_ese_mark'], 'name' => 'data[Mark][Practical][MinEseMark]['.$cm_id.']'))
		." ".
		$this->Form->input('total_min_pass', array('type'=> 'hidden', 'style'=>'width:50px;', 'label'=>false, 'default'=>$smArray['CourseMapping']['total_min_pass'], 'name' => 'data[Mark][Practical][TotalMinPass]['.$cm_id.']'))
		." ".
		$this->Form->input('course_code', array('type'=> 'hidden', 'style'=>'width:50px;', 'label'=>false, 'default'=>$smArray['CourseMapping']['Course']['course_code'], 'name' => 'data[Mark][Practical][CourseCode]['.$cm_id.']'))
		." ".
		$this->Form->input('ese_practical_cm_id', array('type'=> 'hidden', 'style'=>'width:50px;', 'label'=>false, 'default'=>$cm_id, 'name' => 'data[Mark][Practical][EsePracticalCmId]['.$pValue['ese_practical_id'].']'))
					."</td>";
			}
		}
		echo "<td style='width:200px;'>".$smArray['marks']."</td>";
		if ($smArray['status']=="Fail") $txt = "RA";
		else $txt = "Pass";
		echo "<td>".$txt."</td>";
	echo "</tr>"; 
	$i++;
}
echo "</table>"; 
?>
<?php echo $this->Form->end();?>
<?php
echo $this->Html->script('common');
?>

<script>leftMenuSelection('Students/coe');</script>

<span class='breadcrumb1'>
<span class='navbar-brand'><small>RESULTS <i class='ace-icon fa fa-angle-double-right'></i></small></span>
<?php echo $this->Html->link("<span class='navbar-brand'><small>CoE <i class='ace-icon fa fa-angle-double-right'></i></small></span>", array("controller"=>"Students",'action' => 'coe'),array('data-placement'=>'left','escape' => false)); ?>
</span>