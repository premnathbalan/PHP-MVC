
<?php
$cmId = $result[0]['CourseMapping']['id'];
$approvalStatus = $result[0]['EsePractical']['approval_status'];
$month_year_id = $result[0]['CourseMapping']['month_year_id'];
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
</div>
<?php
echo $this->Form->create('EsePractical');
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
				<?php 
				//pr($eseMarks);
				if(isset($eseMarks) && count($eseMarks) > 0) {
				if (isset($result)) { 
				//pr($eseMarks);
				$j=1;
				foreach ($studentArray as $key => $stuList) {
				?>
				<tr class=" gradeX">
					<td><?php $student_id = $stuList['Student']['id']; echo $j; ?></td>
					<td><?php echo $stuList['Student']['registration_number']; ?></td>
					<td><?php echo $stuList['Student']['name']; ?></td>
					<td><?php 
								echo $this->Form->input('student_id', array('type'=>'hidden', 'label' => false, 'default' => $student_id, 'name'=>'data[EsePractical][student_id][]'));
								$temp = $eseMarks[$student_id];
								echo $this->Form->input('id', array('type'=>'hidden', 'label' => false, 'default' => $temp['id'], 'name'=>'data[EsePractical][id][]'));
								echo $temp['marks'];
						?></td>
				</tr>
				  <?php
				  $j++;
				   }
				  }
				  }
				  ?>
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
		if($approvalStatus) {
			echo $this->Form->button('<i class="ace-icon fa fa-check bigger-110"></i>'.__('Approved'),array('type'=>'button','name'=>'submit','value'=>'submit','class'=>'btn js-ese-practical-approve', 'disabled'));
		}
		else {
			echo $this->Form->button('<i class="ace-icon fa fa-check bigger-110"></i>'.__('Approve'),array('type'=>'button','name'=>'submit','value'=>'submit','class'=>'btn js-ese-practical-approve'));			
		}
		echo $this->Form->end();
		?>

<span class='breadcrumb1'>
<span class='navbar-brand'><small>MARKS <i class='ace-icon fa fa-angle-double-right'></i></small></span>
<span class='navbar-brand'><small>C.A.E <i class='ace-icon fa fa-angle-double-right'></i></small></span>
<span class='navbar-brand'><small>View <i class='ace-icon fa fa-angle-double-right'></i></small></span>
<?php //echo $this->Html->link("<span class='navbar-brand'><small> View <i class='ace-icon fa fa-angle-double-right'></i></small></span>", array("controller"=>"ContinuousAssessmentExams",'action' => 'caeAssignment'/*, $course_type_id*/, $action),array('data-placement'=>'left','escape' => false)); ?>
</span>