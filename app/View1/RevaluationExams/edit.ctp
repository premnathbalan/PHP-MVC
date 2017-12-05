<?php
//pr($results);
$internal = $results[0]['Student']['InternalExam'][0]['marks'];
$old = $results[0]['Student']['EndSemesterExam'][0]['marks'];
$new = $results[0]['RevaluationExam']['revaluation_marks'];
$diff = $new-$old;
$total = $internal+$new;
$student_id = $results[0]['Student']['id'];
$cm_id = $results[0]['RevaluationExam']['course_mapping_id'];
$examMonthYear= $results[0]['RevaluationExam']['month_year_id'];
$course_max_mark = $results[0]['Student']['CourseMapping'][0]['Course']['course_max_marks'];
$max_ese_mark = $results[0]['Student']['CourseMapping'][0]['Course']['max_ese_mark'];
$min_ese_pass_percent = $results[0]['Student']['CourseMapping'][0]['Course']['min_ese_mark'];
$total_min_pass_percent = $results[0]['Student']['CourseMapping'][0]['Course']['total_min_pass'];
$min_ese_mark = round($max_ese_mark * $min_ese_pass_percent / 100);
$total_min_pass_mark = round($course_max_mark * $total_min_pass_percent / 100);
if ($total >= $total_min_pass_mark && $new >= $min_ese_mark) $resultStatus = "Pass";
else $resultStatus = "Fail";

echo $this->Form->input('id', array('type'=>'hidden','label'=>false, 'default'=>$id, 'name'=>'data[RevaluationExam][id]'));
echo $this->Form->input('old_revaluation_mark', array('type'=>'hidden','label'=>false, 'default'=>$new, 'name'=>'data[RevaluationExam][old_revaluation_mark]'));
echo $this->Form->input('total_min_pass_mark', array('type'=>'hidden','label'=>false, 'default'=>$total_min_pass_mark, 'name'=>'data[RevaluationExam][total_min_pass_mark]'));
echo $this->Form->input('min_ese_mark', array('type'=>'hidden','label'=>false, 'default'=>$min_ese_mark, 'name'=>'data[RevaluationExam][min_ese_mark]'));
echo $this->Form->input('internal', array('type'=>'hidden','label'=>false, 'default'=>$internal, 'name'=>'data[RevaluationExam][internal]'));
?>
<table style="width:40%;align:center;border-color:#000;font-weight:14px;margin-top:10px;" border="1" class="display tblOddEven">
	<tr>
		<td style="align:right;">Register Number</td><td align="center"><?php echo $results[0]['Student']['registration_number']; ?></td>
	</tr>
	<tr>
		<td style="align:right;">Course Code</td><td align="center"><?php echo $course_code; ?></td>
	</tr>
	<tr>
		<td style="align:right;">CAE</td><td align="center"><?php echo $results[0]['Student']['InternalExam'][0]['marks'] ;?></td>
	</tr>
	<tr>
		<td style="align:right;">ESE Before Revaluation</td><td align="center"><?php echo $results[0]['Student']['EndSemesterExam'][0]['marks'];?></td>
	</tr>
	<tr>
		<td style="align:right;">ESE After Revaluation</td><td align="center"><?php echo "<b>".$results[0]['RevaluationExam']['revaluation_marks']."</b>";?></td>
	</tr>
	<tr>
		<td style="align:right;">Mark Difference</td><td align="center"><?php echo $diff;?></td>
	</tr>
	<tr>
		<td style="align:right;">Total Mark</td><td align="center"><?php echo $total;?></td>
	</tr>
	<tr>
		<td style="align:right;">ESE Pass Mark</td><td align="center"><?php echo $min_ese_mark;?></td>
	</tr>
	<tr>
		<td style="align:right;">Total Minimum Pass Mark</td><td align="center"><?php echo $total_min_pass_mark;?></td>
	</tr>
	<tr>
		<td style="align:right;">Result</td><td align="center"><?php echo "<b>".strtoupper($resultStatus)."</b>";?></td>
	</tr>
	<tr>
		<td>New ESE Mark</td><td align="center"><?php echo $this->Form->input('new_external_mark', array('type'=>'text','label'=>false,'maxlength'=>'3','style'=>'border-color:#000;width:50px;color:#000;', 'name'=>'data[RevaluationExam][new_revaluation_mark]'));?></td>
	</tr>
	<tr>
		<td colspan="2"  align="right">		
		<?php echo $this->Form->button('Update', array('type'=>'button', 'class'=>'btn', 'name'=>'Update', 'label'=>false,'style'=>'margin-right:24px;', 'onclick' => "updateRevaluationMark(&#39;$id&#39;, &#39;$student_id&#39;, &#39;$cm_id&#39;,&#39;$examMonthYear&#39;)"));?></td>
	</tr>	
</table>
<?php echo $this->Html->script('common'); ?>

<script>leftMenuSelection('RevaluationExams/Moderation');</script>

<span class='breadcrumb1'>
<span class='navbar-brand'><small>REVALUATION <i class='ace-icon fa fa-angle-double-right'></i></small></span>
<?php echo $this->Html->link("<span class='navbar-brand'><small>Moderation <i class='ace-icon fa fa-angle-double-right'></i></small></span>", array("controller"=>"RevaluationExams",'action' => 'moderation'),array('data-placement'=>'left','escape' => false)); ?>
</span>