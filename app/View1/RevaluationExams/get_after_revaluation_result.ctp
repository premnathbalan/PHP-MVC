<?php
//pr($results);
//die;
//echo $option." ".$failed_option;
//echo $ese_from." ".$ese_to;
?>

<div class="searchFrm bgFrame1" style="margin-top:10px;">
	<div class="searchFrm col-sm-12">
		<div class="col-lg-12">
			<?php echo $this->Form->button('<i class="ace-icon fa fa-check bigger-110"></i>'.__('&nbsp;Display&nbsp;&nbsp;'),array('type'=>'button','name'=>'export_reval_fail_ar','value'=>'ar','class'=>'btn',"onclick"=>"exportAfterRevaluation(this.value);")); ?>
			<?php echo $this->Html->link('<i class="ace-icon fa fa-print fa-lg"></i>'.'PRINT',array("controller"=>"RevaluationExams",'action'=>'printFailedAfterRevaluation','fail','PRINT',$exam_month_year_id),array('type'=>'submit','name'=>'PRINT','value'=>'PRINT','class'=>'btn','escape' => false,'style'=>'float:right;'));?>
		</div>
	</div>
</div>

<?php echo $this->Form->button('<i class="ace-icon fa fa-check bigger-110"></i>'.__('&nbsp;Moderate&nbsp;&nbsp;'),array('type'=>'submit','name'=>'after_reval_fail','value'=>'ar','class'=>'btn after_reval_fail')); ?>
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
	<?php foreach ($results as $rev_id => $result) {
		$cm_id = $result['cm_id']; 
		$revaluation_mark = $result['revaluation_mark'];
		$internalMark = $result['internalMark'];
		$oldEseMark = $result['oldEseMark'];
		
		//echo $internalMark." ".$oldEseMark." ".$revaluation_mark;
		$new_total = $result['total_marks'];

		$reval_mod_mark = $result['reval_mod_mark'];
		$reval_mod_operator = $result['reval_mod_operator'];
		$total = $internalMark + $revaluation_mark;
		$max_ese_mark = $result['max_ese_mark'];
		//$min_ese_pass_percent = $result['min_ese_mark'];
		$min_ese_pass_mark = $result['min_ese_pass_mark'];
		$course_max_mark = $result['course_max_mark'];
		$total_min_pass_mark = $result['total_min_pass_mark'];
		
		//echo $internalMark;
		if ($result['range']) {
		?>
		<tr class=" gradeX">
			<td>
			<?php
			$cm_id = $result['cm_id'];
			$student_id = $result['student_id'];
			$course_code = $result['course_code'];
			//echo $this->Form->button('Edit',array('type'=>'button','name'=>'submit','value'=>'get','class'=>'btn js-reval-mark-display', 'target'=>'_blank', 'onclick' => "displayRevaluationMark(&#39;$rev_id&#39;, &#39;$student_id&#39;, &#39;$cm_id&#39;, &#39;$exam_month_year_id&#39;, &#39;$course_code&#39;)"));
			echo $this->Html->link("<i class='fa fa-pencil fa-lg'></i>", array("controller"=>"RevaluationExams",'action' => 'edit', $rev_id, $student_id, $cm_id, $exam_month_year_id, $course_code),array('title'=>'Edit','escape' => false,'target'=>'_blank'));
			?>
			</td>
			<td align='center'>
				<?php echo $result['regNum'];?>
				<?php 
				echo $this->Form->input('student_id', array('type'=>'hidden','label'=>false, 'value'=>$student_id, 'name'=>'data[RevaluationExam][student_id][]')); 
				echo $this->Form->input('rev_id', array('type'=>'hidden','label'=>false, 'value'=>$rev_id, 'name'=>'data[RevaluationExam][rev_id][]'));
				echo $this->Form->input('ese_mod_mark', array('type'=>'hidden','label'=>false, 'value'=>$reval_mod_mark, 'name'=>'data[RevaluationExam][reval_mod_mark][]'));
				echo $this->Form->input('ese_mod_operator', array('type'=>'hidden','label'=>false, 'value'=>$reval_mod_operator, 'name'=>'data[RevaluationExam][reval_mod_operator][]'));
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
				<?php echo $result['total_marks'];?>
				<?php echo $this->Form->input('total', array('type'=>'hidden','label'=>false, 'value'=>$total, 'name'=>'data[RevaluationExam][total][]')); ?>
			</td>
			<td align='center'>
				<?php echo $result['oldEseMark'];?>
				<?php echo $this->Form->input('old_ese_mark', array('type'=>'hidden','label'=>false, 'value'=>$result['oldEseMark'], 'name'=>'data[RevaluationExam][oldEseMark][]')); ?>
			</td>
			<td align='center'>
				<?php 
				//$diff = $result['RevaluationExam']['revaluation_marks']-$result['oldEseMarks'];
				$diff = $result['revaluation_mark']-$result['oldEseMark'];
				echo $diff;
				echo $this->Form->input('diff', array('type'=>'hidden','label'=>false, 'value'=>$diff, 'name'=>'data[RevaluationExam][diff][]'));
				?>
			</td>
			<td align='center'>
				<?php echo $result['dummy_number'];?>
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