
<?php
//$cmId = $result[0]['CourseMapping']['id'];
//$approvalStatus = $result[0]['CaePractical']['approval_status'];
//$month_year_id = $result[0]['CourseMapping']['month_year_id'];
//echo $batch_id." ".$program_id." ".$academic_id." ".$cmId." ".$caeId."</br>";
//pr($result);
?>
<div class="searchFrm bgFrame1">
	<div class="col-sm-12" >
			<div class="col-lg-4">
				<?php echo "<b>Batch : </b>".$this->Html->getBatch($batch_id); ?>
			</div>
			<div class="col-lg-4">
				<?php echo "<b>Academic : </b>".$this->Html->getAcademic($academic_id); ?>
			</div>
			<div class="col-lg-4">
				<?php echo "<b>Program : </b>".$this->Html->getProgram($program_id); ?>
			</div>
	</div>
	<div class=" col-sm-12" >
			<div class="col-lg-4">
				<?php echo "<b>Course : </b>".$this->Html->getCourseNameFromCmId($cmId); ?>
			</div>
			<div class="col-lg-4">
				<?php echo "<b>Month Year : </b>".$this->Html->getMonthYearFromMonthYearId($month_year_id); ?>
			</div>
			<div class="col-lg-4">
				<?php echo "<b>Assessment : </b>".$number; ?>
			</div>
	</div>
	<div class=" col-sm-12" >
			<div class="col-lg-4">
				<?php echo "<b>Course Code : </b>".$this->Html->getCourseCode($cmId); ?>
			</div>
			<div class="col-lg-4">
				<?php echo "<b>Marks : </b>".$marks; ?>
			</div>
<!--			<div class="col-lg-4">
				<?php echo "<b>Assessment : </b>".$number; ?>
			</div>-->
	</div>
</div>
<div class=" col-sm-12" style="text-align: center;">
<?php
if ($publish_status > 0) echo "<strong style='color:#ff0000;align:center;'>Data already published</strong>";
?>
</div>
<?php 
echo $this->Form->create('CaePractical');
echo $this->Form->input('cae_id', array('label' => false, 'type' => 'hidden', 'default' => $caeId));
?>

<table cellpadding="0" cellspacing="0" border="0" class="display" id="example" style="margin-top:10px;">
			<thead>
				<tr>
					<th>Sl.&nbsp;No.</th>
					<th>Register&nbsp;</th>
					<th>Student&nbsp;</th>
					<th>Marks</th>
				</tr>
			</thead>
			<tbody>
				<?php if (isset($result)) { 
				//$cm_month_year_id = $result[0]['CourseMapping']['month_year_id'];
				//echo $cm_month_year_id;
				//pr($caeMarks);
				$j=1;
				foreach ($result as $key => $stuList) {
				//if ($cm_month_year_id >= $stuList['Student']['month_year_id']) {
				?>
				<tr class=" gradeX">
					<td><?php $student_id = $stuList['Student']['id']; echo $j; ?></td>
					<td><?php echo $stuList['Student']['registration_number']; ?></td>
					<td><?php echo $stuList['Student']['name']; ?></td>
					<td><?php 
								echo $this->Form->input('student_id', array('type'=>'hidden', 'label' => false, 'default' => $student_id, 'name'=>'data[CaePractical][student_id][]'));
								$temp = $caeMarks[$student_id];
								echo $this->Form->input('id', array('type'=>'hidden', 'label' => false, 'default' => $temp['id'], 'name'=>'data[CaePractical][id]['.$student_id.']'));
								echo $this->Form->input('marks', array('type'=>'text', 'label' => false, 'default' => $temp['marks'], 'name'=>'data[CaePractical][marks]['.$student_id.']'));
								//echo $temp['marks'];
						?></td>
				</tr>
				  <?php
				  $j++;
				  //	}
				   }
				  }?>
			</tbody>
			<!--<tfoot>
				<tr>
					<th><input type="text" name="" value="Regn No." class="search_init" /></th>
					<th><input type="text" name="Registration Number" value="Registration Number" class="search_init" /></th>
					<th><input type="text" name="Academic Id" value="Academic Id" class="search_init" /></th>
					<th><input type="text" name="Program Id" value="Program Id" class="search_init" /></th>
				</tr>
			</tfoot>-->
		</table>
		<?php
		echo $this->Form->button('<i class="ace-icon fa fa-check bigger-110"></i>'.__('Submit'),array('type'=>'submit','name'=>'submit','value'=>'submit','class'=>'btn'));
		echo $this->Form->end();
		?>

<script>leftMenuSelection('CaePracticals/practical');</script>

<span class='breadcrumb1'>
<span class='navbar-brand'><small>MARKS <i class='ace-icon fa fa-angle-double-right'></i></small></span>
<span class='navbar-brand'><small>C.A.E <i class='ace-icon fa fa-angle-double-right'></i></small></span>
<span class='navbar-brand'><small>View <i class='ace-icon fa fa-angle-double-right'></i></small></span>
<?php //echo $this->Html->link("<span class='navbar-brand'><small> View <i class='ace-icon fa fa-angle-double-right'></i></small></span>", array("controller"=>"ContinuousAssessmentExams",'action' => 'caeAssignment'/*, $course_type_id*/, $action),array('data-placement'=>'left','escape' => false)); ?>
</span>