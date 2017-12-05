<?php
//pr($results);
//echo $option." ".$failed_option;
?>
<div class='col-lg-12' style='float:left;width:100%;'>
	<?php if(empty($print)) echo $this->Html->link('<i class="ace-icon fa fa-print fa-lg"></i>'.'PRINT',array("controller"=>"RevaluationExams",'action'=>'getResult',$exam_month_year_id, $option, $failed_option, 'PRINT'),array('type'=>'submit','name'=>'PRINT','value'=>'PRINT','class'=>'btn','escape' => false,'style'=>'float:right;'));?>	
	<?php echo $this->Html->link('<i class="ace-icon fa fa-file-excel-o"></i>'.'DOWNLOAD',array("controller"=>"RevaluationExams",'action'=>'getResult',$exam_month_year_id, $option, $failed_option, 'DOWNLOAD'),array('type'=>'submit','name'=>'PRINT','value'=>'PRINT','class'=>'btn','escape' => false,'style'=>'float:right;margin-right:15px;')); ?>
	<div style='clear:both;'></div>
</div>
<?php if($print == 'PRINT'){
	echo "<div style='padding-left:200px;'>";
	echo $this->element('print_head_a4'); 
	echo "<div style='clear:both;'></div>";
	echo "</div>";
	$my = $this->Html->getMonthYearFromMonthYearId($exam_month_year_id);
	
	echo "<table class='attendanceHeadTblP' border='1' cellpadding='0' cellspacing='0' style='width:700px;font-size:13px;'>
				<tr><td colspan='4' align='center'>REVALUATION RESULTS</td></tr>
				<tr>
					<td COLSPAN='2'><b>Month&Year of Exam</b></td>
					<td COLSPAN='2'>";
	echo $this->Html->getMonthYearFromMonthYearId($exam_month_year_id);
	echo "</td>			
				</tr>		
			</table>";
} ?>

<table cellpadding="0" cellspacing="0" border="0" class="display" id="example" style="margin-top:10px;">
	<thead>
		<tr>
		<?php if(empty($print)) { ?>
			<th>Action&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>
			<?php } ?>
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
	<?php foreach ($results as $rev_id => $result) { 
			$student_id = $result['student_id'];
			$course_code = $result['course_code'];
			$cm_id = $result['cm_id'];
	?>
		<tr class=" gradeX">
			<?php if(empty($print)) { ?>
			<td>
			<?php 
			//echo $this->Form->button('Edit',array('type'=>'button','name'=>'submit','value'=>'get','class'=>'btn js-reval-mark-display', 'onclick' => "displayRevaluationMark(&#39;$rev_id&#39;, &#39;$student_id&#39;, &#39;$cm_id&#39;, &#39;$exam_month_year_id&#39;, &#39;$course_code&#39;)"));
			echo $this->Html->link("<i class='fa fa-pencil fa-lg'></i>", array("controller"=>"RevaluationExams",'action' => 'edit', $rev_id, $student_id, $cm_id, $exam_month_year_id, $course_code),array('title'=>'Edit','escape' => false,'target'=>'_blank'));
			?>
			</td>
			<?php } ?>
			<td align='center'>
				<?php echo $result['regNum']; 
				echo $this->Form->input('student_id', array('type'=>'hidden','label'=>false, 'value'=>$student_id, 'name'=>'data[RevaluationExam][student_id][]')); 
				echo $this->Form->input('rev_id', array('type'=>'hidden','label'=>false, 'value'=>$rev_id, 'name'=>'data[RevaluationExam][rev_id][]'));
				echo $this->Form->input('reval_mod_mark', array('type'=>'hidden','label'=>false, 'value'=>$result['reval_mod_mark'], 'name'=>'data[RevaluationExam][reval_mod_mark][]'));
				echo $this->Form->input('reval_mod_operator', array('type'=>'hidden','label'=>false, 'value'=>$result['reval_mod_operator'], 'name'=>'data[RevaluationExam][reval_mod_operator][]'));
				?>
			</td>
			<td align='center'>
				<?php echo $course_code;?>
			</td>
			<td align='center'>
				<?php echo $result['internalMark']; ?>
				<?php echo $this->Form->input('internal', array('type'=>'hidden','label'=>false, 'value'=>$result['internalMark'], 'name'=>'data[RevaluationExam][caeMark][]')); ?>
			</td>
			<td align='center'>
				<?php echo $result['revaluation_mark'];?>
				<?php echo $this->Form->input('revaluation_mark', array('type'=>'hidden','label'=>false, 'value'=>$result['revaluation_mark'], 'name'=>'data[RevaluationExam][revaluationMark][]')); ?>
			</td>
			<td align='center'>
				<?php echo $result['total_marks'];?>
				<?php echo $this->Form->input('total', array('type'=>'hidden','label'=>false, 'value'=>$result['total'], 'name'=>'data[RevaluationExam][total][]')); ?>
			</td>
			<td align='center'>
				<?php echo $result['oldEseMark'];?>
				<?php echo $this->Form->input('old_ese_mark', array('type'=>'hidden','label'=>false, 'value'=>$result['oldEseMark'], 'name'=>'data[RevaluationExam][oldEseMark][]')); ?>
			</td>
			<td align='center'>
				<?php 
				$diff = $result['revaluation_mark']-$result['oldEseMark'];
				echo $diff;
				echo $this->Form->input('diff', array('type'=>'hidden','label'=>false, 'value'=>$diff, 'name'=>'data[RevaluationExam][diff][]'));
				?>
			</td>
			<td align='center'>
				<?php echo $result['dummy_number'];?>
			</td>
			<td align='center'>
				<?php echo $result['min_ese_pass_mark'];
				echo $this->Form->input('min_ese_pass_mark', array('type'=>'hidden','label'=>false, 'value'=>$result['min_ese_pass_mark'], 'name'=>'data[RevaluationExam][min_ese_pass_mark][]'));
				?>
			</td>
			<td align='center'>
				<?php echo $result['total_min_pass_mark'];
				echo $this->Form->input('total_min_pass_mark', array('type'=>'hidden','label'=>false, 'value'=>$result['total_min_pass_mark'], 'name'=>'data[RevaluationExam][total_min_pass_mark][]'));
				?>
			</td>
		</tr>
		<?php
		}
		?>
	<tfoot>
		<tr>
			<?php if(empty($print)) { ?>
			<th><input type="text" name="Action&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;" value="Action&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;" class="search_init" /></th>
			<?php } ?>
			<th><input type="text" name="Reg&nbsp;No." value="Reg&nbsp;No." class="search_init" /></th>
			<th><input type="text" name="Course code" value="Course code" class="search_init" /></th>
			<th><input type="text" name="CAE Mark" value="CAE Mark" class="search_init" /></th>			
			<th><input type="text" name="New ESE Mark" value="New ESE Mark" class="search_init" /></th>
			<th><input type="text" name="Total Mark<" value="Total Mark<" class="search_init" /></th>
			<th><input type="text" name="ESE&nbsp;Before Revaluation" value="ESE&nbsp;Before Revaluation" class="search_init" /></th>
			<th><input type="text" name="Difference" value="Difference" class="search_init" /></th>
			<th><input type="text" name="Dummy Number" value="Dummy Number" class="search_init" /></th>
			<th><input type="text" name="Min ESE PassMark" value="Min ESE PassMark" class="search_init" /></th>
			<th><input type="text" name="Minimum Total PassMark" value="Minimum Total PassMark" class="search_init" /></th>			
		</tr>
	</tfoot>
	<?php echo $this->Html->script('common');?>
</table>

<span class='breadcrumb1'>
<span class='navbar-brand'><small>REVALUATION <i class='ace-icon fa fa-angle-double-right'></i></small></span>
<?php echo $this->Html->link("<span class='navbar-brand'><small>Moderation <i class='ace-icon fa fa-angle-double-right'></i></small></span>", array("controller"=>"Revaluations",'action' => 'moderation'),array('data-placement'=>'left','escape' => false)); ?>
</span>