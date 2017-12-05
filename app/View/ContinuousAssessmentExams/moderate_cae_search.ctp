<?php
echo "</br>";
if (count($marks) > 0) {
echo $this->Form->create('ContinuousAssessmentExam', array('controller' => 'ContinuousAssessmentExam', 'action'=>'moderateCae'));
?>
<div class="searchFrm col-sm-12 bgFrame1">
	<div class="col-lg-4">	
		<?php 
		$signOptions = array('plus'=>'+');
		echo $this->Form->input('sign', array('options' => $signOptions, 'label' => '+', 'class' => 'js-mod-sign'));
		?>
	</div>
	<div class="col-lg-4">	
		<?php 
		$valueOptions = array('1'=>'1','2'=>'2','3'=>'3','4'=>'4','5'=>'5','6'=>'6','7'=>'7','8'=>'8','9'=>'9','10'=>'10');
		echo $this->Form->input('mark', array('options' => $valueOptions, 'label' => 'Mark', 'empty' => __("----- Select Mark-----"), 'class' => 'js-mod-mark'));
		?>
	</div>
	<div class="col-lg-4">	
		<?php
		echo $this->Form->button('<i class="ace-icon fa fa-check bigger-110"></i>'.__('Apply'),array('type'=>'submit','name'=>'submit','value'=>'submit','class'=>'btn','onclick'=>'moderateCae();'));
		?>
	</div>
</div>
		
<table cellpadding="0" cellspacing="0" border="0" class="display" id="example" style="margin-top:10px;">
			<thead>
				<tr>
					<th>Sl.&nbsp;No.</th>
					<th>Register&nbsp;Number</th>
					<th>Student&nbsp;Name</th>
					<th>Course&nbsp;Code</th>
					<th>Marks</th>
				</tr>
			</thead>
			<tbody>
				<?php if (isset($marks)) { 
					
					$j = 1;
				//for($j=0; $j<count($stuList); $j++) {
				foreach ($marks as $mark) { 
				$id = $mark['InternalExam']['id'];
				$cm_id = $mark['InternalExam']['course_mapping_id'];
				$score = $mark['InternalExam']['marks'];
				$student_id = $mark['InternalExam']['student_id'];
				?>
				<tr class=" gradeX">
					
					<td><?php echo $j; ?></td>
					<td><?php echo $mark['Student']['registration_number']; ?></td>
					<td><?php echo $mark['Student']['name']; ?></td>
					<td><?php echo $this->Html->getCourseCode($cm_id); ?></td>
					<td><?php echo $score;
								echo $this->Form->input('marks', array('label' => false, 'default' => $score, 'name'=>'data[InternalExam][marks][]', 'type' => 'hidden'));
								echo $this->Form->input('course_mapping_id', array('type'=>'hidden', 'label' => false, 'default' => $cm_id, 'name'=>'data[InternalExam][course_mapping_id][]'));
								echo $this->Form->input('student_id', array('type'=>'hidden', 'label' => false, 'default' => $student_id, 'name'=>'data[InternalExam][student_id][]'));
								echo $this->Form->input('id', array('type'=>'hidden', 'label' => false, 'default' => $id, 'name'=>'data[InternalExam][id][]'));
						$j++; ?></td>
				</tr>
				  <?php } 
				  }?>
			</tbody>
			<tfoot>
				<tr>
					<th><input type="text" name="Regn No." value="S. No." class="search_init" /></th>
					<th><input type="text" name="Registration Number" value="Registration Number" class="search_init" /></th>
					<th><input type="text" name="Academic Id" value="Student Name" class="search_init" /></th>
					<th><input type="text" name="Program Id" value="Course Code" class="search_init" /></th>
					<th><input type="text" name="Program Id" value="Marks" class="search_init" /></th>
				</tr>
			</tfoot>
			<input type='hidden' name='modOperator' id='modOperator'>
			<input type='hidden' name='modValue' id='modValue'>
		</table>

		<?php
		echo $this->Form->end();
		?>
<?php
}
else {
	echo "No data for the search";
}
?>
<?php echo $this->Html->script('common'); ?>

<span class='breadcrumb1'>
<span class='navbar-brand'><small>MARKS <i class='ace-icon fa fa-angle-double-right'></i></small></span>
<span class='navbar-brand'><small>C.A.E <i class='ace-icon fa fa-angle-double-right'></i></small></span>
<?php echo $this->Html->link("<span class='navbar-brand'><small> Moderate <i class='ace-icon fa fa-angle-double-right'></i></small></span>", array("controller"=>"ContinuousAssessmentExams",'action' => 'moderateCae'),array('data-placement'=>'left','escape' => false)); ?>
</span>