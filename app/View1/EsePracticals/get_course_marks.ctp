<style>
input[type=number]::-webkit-inner-spin-button, 
input[type=number]::-webkit-outer-spin-button { 
  -webkit-appearance: none; 
  margin: 0; 
}
</style>
<?php
//pr($results);
foreach ($results as $result) {

	$student_mark_id = $result['StudentMark'][0]['id'];
	$student_marks = $result['StudentMark'][0]['marks'];
	
	$internal_practical = $result['InternalPractical'];
	foreach ($internal_practical as $key => $ipresult) {
		if (isset($ipresult['CaePractical']['course_mapping_id']) && $ipresult['CaePractical']['course_mapping_id'] == $result['StudentMark'][0]['CourseMapping']['id']) {
			$internal_practical_id = $ipresult['id'];
			$internal_practical_marks = $ipresult['marks'];
		}
	}
	
	$external_practical = $result['Practical'];
	foreach ($external_practical as $key => $epresult) {
		if (isset($epresult['EsePractical']['course_mapping_id']) && $epresult['EsePractical']['course_mapping_id'] == $result['StudentMark'][0]['CourseMapping']['id']) {
			$external_practical_id = $epresult['id'];
			$external_practical_marks = $epresult['marks'];
			$moderation_operator = $epresult['moderation_operator'];
			$moderation_marks = $epresult['moderation_marks'];
		}
	}
	
	$max_cae_mark = $result['StudentMark'][0]['CourseMapping']['Course']['max_cae_mark'];
	$max_ese_mark = $result['StudentMark'][0]['CourseMapping']['Course']['max_ese_mark'];
	
	$min_ese_pass_percent = $result['StudentMark'][0]['CourseMapping']['Course']['min_ese_mark'];
	$min_total_pass_percent = $result['StudentMark'][0]['CourseMapping']['Course']['total_min_pass'];
	$course_max_marks = $result['StudentMark'][0]['CourseMapping']['Course']['course_max_marks'];
	
}
?>

<table width="100%">
	<tr>
		<td width='20%'>CAE</td>
		<td>	
			<?php 
			echo $this->Form->input('max_cae_mark', array('label' => false, 'type' => 'hidden', 'maxlength'=>30, 'value'=>$max_cae_mark));
			echo $this->Form->input('internal_practical_id', array('label' => false, 'type' => 'hidden', 'maxlength'=>30, 'value'=>$internal_practical_id));
			echo $this->Form->input('internal_practical_marks', array('label' => false, 'type' => 'hidden', 'maxlength'=>30, 'value'=>$internal_practical_marks));
			echo $this->Form->input('internal_practical_new_mark', array('label' => false, 'type' => 'text', 'style'=>'width:45px;', 'maxlength'=>30, 
			'value'=>$internal_practical_marks, 'onblur'=>"computeTotal(document.getElementById('external_practical_new_mark').value)", 
			'max'=>$max_cae_mark))." Out of ".$max_cae_mark;
			?>
		</td>
	</tr>
	<tr>
		<td>ESE</td>
		<td>
			<?php 
			echo $this->Form->input('external_practical_id', array('label' => false, 'type' => 'hidden', 'maxlength'=>30, 'value'=>$external_practical_id));
			echo $this->Form->input('external_practical_marks', array('label' => false, 'type' => 'hidden', 'maxlength'=>30, 'value'=>$external_practical_marks));
			echo $this->Form->input('moderation_operator', array('label' => false, 'type' => 'hidden', 'maxlength'=>30, 'value'=>$moderation_operator));
			echo $this->Form->input('moderation_marks', array('label' => false, 'type' => 'hidden', 'maxlength'=>30, 'value'=>$moderation_marks));
			echo $this->Form->input('min_ese_pass_percent', array('label' => false, 'type' => 'hidden', 'maxlength'=>30, 'value'=>$min_ese_pass_percent));
			echo $this->Form->input('min_total_pass_percent', array('label' => false, 'type' => 'hidden', 'maxlength'=>30, 'value'=>$min_total_pass_percent));
			echo $this->Form->input('max_ese_mark', array('label' => false, 'type' => 'hidden', 'maxlength'=>30, 'value'=>$max_ese_mark));
			echo $this->Form->input('course_max_marks', array('label' => false, 'type' => 'hidden', 'maxlength'=>30, 'value'=>$course_max_marks));
			echo $this->Form->input('external_practical_new_mark', array('label' => false, 'type' => 'text', 'style'=>'width:45px;', 'maxlength'=>30, 'value'=>$external_practical_marks, 'onblur'=>"computeTotal(this.value)", 'max'=>$max_ese_mark))." Out of ".$max_ese_mark;
			?>
		</td>
	</tr>
	<tr>
		<td>Total</td>
		<td>	
			<?php 
			echo $this->Form->input('student_mark_id', array('label' => false, 'type' => 'hidden', 'maxlength'=>30, 'value'=>$student_mark_id));
			echo $this->Form->input('student_marks', array('label' => false, 'type' => 'hidden', 'maxlength'=>30, 'value'=>$student_marks));
			echo $this->Form->input('student_new_mark', array('label' => false, 'type' => 'text', 'style'=>'width:45px;', 'maxlength'=>30, 'value'=>$student_marks))." Out of ".$course_max_marks;
			?>
		</td>
	</tr>
</table>

<?php echo $this->Html->script('common'); ?>

<script>leftMenuSelection('EsePracticals/tmpModeration');</script>
<span class='breadcrumb1'>
<span class='navbar-brand'><small>MARKS <i class='ace-icon fa fa-angle-double-right'></i></small></span>
<span class='navbar-brand'><small>Practical <i class='ace-icon fa fa-angle-double-right'></i></small></span>
<?php echo $this->Html->link("<span class='navbar-brand'><small> Moderation <i class='ace-icon fa fa-angle-double-right'></i></small></span>", array("controller"=>"EsePracticals",'action' => 'tmpModeration'),array('data-placement'=>'left','escape' => false)); ?>
</span>
