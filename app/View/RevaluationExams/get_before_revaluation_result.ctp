<?php
//pr($results);
//die;
//echo $option." ".$failed_option;
//echo $ese_from." ".$ese_to;
?>
<?php echo $this->Form->button('<i class="ace-icon fa fa-check bigger-110"></i>'.__('&nbsp;Moderate&nbsp;&nbsp;'),array('type'=>'submit','name'=>'before_reval_fail','value'=>'br','class'=>'btn before_reval_fail')); ?>
<table cellpadding="0" cellspacing="0" border="0" class="display" id="example" style="margin-top:10px;">
	<thead>
		<tr>
			<th>Action&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>
			<th>Reg&nbsp;No.</th>
			<th>Course code</th>
			<th>CAE Mark</th>
			<th>New ESE Mark</th>			
			<th>Total Mark</th>
			<th>ESE&nbsp;Before Revaluation</th>
			<th>Difference</th>
			<th>Dummy Number</th>
			<th>Min ESE PassMark</th>
			<th>Minimum Total PassMark</th>
		</tr>
	</thead>
	<?php foreach ($results as $key => $result) {
		$cm_id = $result['CourseMapping']['id']; 
		//echo $cm_id."</br>";
		$internalExam=$result['Student']['InternalExam'];
		$endSemesterExam=$result['Student']['EndSemesterExam'];
		$studentMark=$result['Student']['StudentMark'];
		//pr($internalExam);
		foreach ($internalExam as $key => $internalMarkArray) { 
			if ($internalMarkArray['course_mapping_id'] == $cm_id) {
				$internalMark = $internalMarkArray['marks'];
				break;
			}
		}
		foreach ($endSemesterExam as $key => $eseMarkArray) { 
			$tmpOldEseMark = $eseMarkArray['marks'];
			if ($eseMarkArray['course_mapping_id'] == $cm_id && ($eseMarkArray['marks']>=$ese_from && $eseMarkArray['marks']<=$ese_to)) {
						//echo "if ".$eseMarkArray['marks']." ".$ese_from." ".$ese_to;
						$ese_bool = true;
				$oldEseMark = $eseMarkArray['marks'];
				$ese_mod_operator = $eseMarkArray['moderation_operator'];
				$ese_mod_mark = $eseMarkArray['moderation_marks'];
				$ese_id = $eseMarkArray['id'];
				$ese_bool = true;
				break;
			}
			else {
				$ese_bool = false;
			}
		}
		foreach ($studentMark as $key => $studentMarkArray) { 
			if ($studentMarkArray['course_mapping_id'] == $cm_id && ($studentMarkArray['marks']>=$total_from && $studentMarkArray['marks']<=$total_to)) {
				$stuMark = $studentMarkArray['marks'];
				$total_bool = true;
				break;
			}
			else {
				$total_bool = false;
			}
		}
		
		$revaluation_mark = $result['RevaluationExam']['revaluation_marks'];
		$total = $internalMark + $revaluation_mark;
		$max_ese_mark = $result['CourseMapping']['max_ese_mark'];
		$min_ese_pass_percent = $result['CourseMapping']['min_ese_mark'];
		$min_ese_pass_mark = round($max_ese_mark * $min_ese_pass_percent / 100);
		
		$course_max_mark = $result['CourseMapping']['course_max_marks'];
		$total_min_pass_percent = $result['CourseMapping']['total_min_pass'];
		$total_min_pass_mark = round($course_max_mark * $total_min_pass_percent / 100);
		
		//echo $internalMark;
		if ($ese_bool && $total_bool) {
		?>
		<tr class=" gradeX">
			<td>
			<?php 
			$rev_id = $result['RevaluationExam']['id'];
			$student_id = $result['Student']['id'];
			$course_code = $result['CourseMapping']['Course']['course_code'];
			echo $this->Form->button('Edit',array('type'=>'button','name'=>'submit','value'=>'get','class'=>'btn js-reval-mark-display', 'onclick' => "displayRevaluationMark(&#39;$rev_id&#39;, &#39;$student_id&#39;, &#39;$cm_id&#39;, &#39;$exam_month_year_id&#39;, &#39;$course_code&#39;)"));
			?>
			</td>
			<td align='center'>
				<?php echo $result['Student']['registration_number']." ".$rev_id;?>
				<?php 
				echo $this->Form->input('student_id', array('type'=>'hidden','label'=>false, 'value'=>$student_id, 'name'=>'data[RevaluationExam][student_id][]')); 
				echo $this->Form->input('ese_id', array('type'=>'hidden','label'=>false, 'value'=>$ese_id, 'name'=>'data[RevaluationExam][ese_id][]'));
				echo $this->Form->input('ese_mod_mark', array('type'=>'hidden','label'=>false, 'value'=>$ese_mod_mark, 'name'=>'data[RevaluationExam][ese_mod_mark][]'));
				echo $this->Form->input('ese_mod_operator', array('type'=>'hidden','label'=>false, 'value'=>$ese_mod_operator, 'name'=>'data[RevaluationExam][ese_mod_operator][]'));
				?>
			</td>
			<td align='center'>
				<?php echo $course_code;?>
			</td>
			<td align='center'>
				<?php echo $internalMark; ?>
				<?php echo $this->Form->input('internal', array('type'=>'hidden','label'=>false, 'value'=>$internalMark, 'name'=>'data[RevaluationExam][caeMark][]')); ?>
			</td>
			<td align='center'>
				<?php echo $revaluation_mark;?>
				<?php echo $this->Form->input('revaluation_mark', array('type'=>'hidden','label'=>false, 'value'=>$revaluation_mark, 'name'=>'data[RevaluationExam][revaluationMark][]')); ?>
			</td>
			<td align='center'>
				<?php echo $result['RevaluationExam']['total_marks'];?>
				<?php echo $this->Form->input('total', array('type'=>'hidden','label'=>false, 'value'=>$total, 'name'=>'data[RevaluationExam][total][]')); ?>
			</td>
			<td align='center'>
				<?php echo $result['Revaluation']['ese_marks'];?>
				<?php echo $this->Form->input('old_ese_mark', array('type'=>'hidden','label'=>false, 'value'=>$oldEseMark, 'name'=>'data[RevaluationExam][oldEseMark][]')); ?>
			</td>
			<td align='center'>
				<?php 
				$diff = $result['RevaluationExam']['revaluation_marks']-$result['Revaluation']['ese_marks'];
				echo $diff;
				echo $this->Form->input('diff', array('type'=>'hidden','label'=>false, 'value'=>$diff, 'name'=>'data[RevaluationExam][diff][]'));
				?>
			</td>
			<td align='center'>
				<?php echo $result['RevaluationExam']['dummy_number'];?>
			</td>
			<td align='center'>
				<?php echo $min_ese_pass_mark;
				echo $this->Form->input('min_ese_pass_mark', array('type'=>'hidden','label'=>false, 'value'=>$min_ese_pass_mark, 'name'=>'data[RevaluationExam][min_ese_pass_mark][]'));
				?>
			</td>
			<td align='center'>
				<?php echo $total_min_pass_mark;
				echo $this->Form->input('total_min_pass_mark', array('type'=>'hidden','label'=>false, 'value'=>$total_min_pass_mark, 'name'=>'data[RevaluationExam][total_min_pass_mark][]'));
				?>
			</td>
		</tr>
		<?php
		}
		}
		?>
	<!--<tfoot>
		<tr>
			<th><input type="text" name="Dummy No Range" value="Dummy No. Range" class="search_init" /></th>
			<th><input type="text" name="No. Of Students" value="No. Of Students" class="search_init" /></th>
			<th><input type="text" name="Mark Entry1" value="Mark Entry1" class="search_init" /></th>
			<th><input type="text" name="Entry1 Action" value="Entry1 Action" class="search_init" /></th>			
			<th><input type="text" name="Mark Entry2" value="Mark Entry2" class="search_init" /></th>
			<th><input type="text" name="Entry2 Action" value="Entry2 Action" class="search_init" /></th>
			<th><input type="text" name="Final Status" value="Final Status" class="search_init" /></th>
		</tr>
	</tfoot>-->
	<?php echo $this->Html->script('common');?>
</table>

<span class='breadcrumb1'>
<span class='navbar-brand'><small>REVALUATION <i class='ace-icon fa fa-angle-double-right'></i></small></span>
<?php echo $this->Html->link("<span class='navbar-brand'><small>Moderation <i class='ace-icon fa fa-angle-double-right'></i></small></span>", array("controller"=>"RevaluationExams",'action' => 'moderation'),array('data-placement'=>'left','escape' => false)); ?>
</span>