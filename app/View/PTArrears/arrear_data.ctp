<table cellpadding="0" cellspacing="0" border="0" class="display" id="example" style="margin-top:10px;">
	<thead>
		<tr>
			<th>Batch</th>
			<th>Academic</th>
			<th>Program</th>
			<th>Common&nbsp;Code</th>
			<th>Course&nbsp;Code</th>
			<th>Course&nbsp;Name</th>
			<th>Arrear/NonArrear</th>
			<th class="actions"><?php echo __('Actions'); ?></th>
		</tr>
	</thead>
	<tbody>
		<?php if (isset($results)) { //pr($results);
			$j = 1;
		foreach ($results as $model => $value) { 
			foreach ($value as $cm_id => $cm_id) { //echo $cm_id;
			$cae_pt_id = $this->Html->getCaePtIdFromCmId($cm_id); 
				$details = $this->Html->getBatchAcademicProgramFromCmId($cm_id); //pr($details);
			//$details = $this->Html->getCourseNameCrseCodeCmnCodeFromCMId($cm_id);
		?>
					<tr class=" gradeX">
						<td><?php echo $details['batch']; ?></td>
						<td><?php echo $details['academic']; ?></td>
						<td><?php echo $details['program']; ?></td>
						<td><?php echo $details['course_code']; ?></td>
						<td><?php echo $details['course_code']; ?></td>
						<td><?php echo $details['course']; ?></td>
						<td><?php if ($model == 'StudentMark') {
								$arrear_status = "Arrear";
							}
							else {
								$arrear_status = "Non arrear";
							}
							echo $arrear_status; ?></td>
						<td>
						<?php
						//if($this->Html->checkPathAccesstopath('PTArrears/editMarks','',$authUser['id'])){ 
							echo $this->Html->link("<i class='fa fa-pencil fa-lg'></i>",array("controller"=>"PTArrears",'action' => 'editMarks', $cm_id, $exam_month_year_id, $model, $cae_pt_id), array('escape' => false,'style'=>'padding-left:10px;'));
						//} 
						?>
						</td>
					</tr>
		  <?php $j++; 
		  }
		  }
		  }?>
	</tbody>
	<tfoot>
		<tr>
			<th><input type="text" name="Batch" value="Batch" class="search_init" /></th>
			<th><input type="text" name="Academic" value="Academic" class="search_init" /></th>
			<th><input type="text" name="Program" value="Program" class="search_init" /></th>
			<th><input type="text" name="Common Code" value="Common Code" class="search_init" /></th>
			<th><input type="text" name="Course Code" value="Course Code" class="search_init" /></th>
			<th><input type="text" name="Course Name" value="Course Name" class="search_init" /></th>
			<th><input type="text" name="Arrear/NonArrear" value="Arrear/NonArrear" class="search_init" /></th>
			<th></th>
		</tr>
	</tfoot>
</table>

<?php echo $this->Html->script('common'); ?>

<span class='breadcrumb1'>
<span class='navbar-brand'><small>Professional Training <i class='ace-icon fa fa-angle-double-right'></i></small></span>
<?php echo $this->Html->link("<span class='navbar-brand'><small> Arrear Mark Entry <i class='ace-icon fa fa-angle-double-right'></i></small></span>", array("controller"=>"PTArrears",'action' => 'index'),array('data-placement'=>'left','escape' => false)); ?>
</span>