<table cellpadding="0" cellspacing="0" border="0" class="display" id="example" style="margin-top:10px;">
	<thead>
		<tr>
			<th>Batch</th>
			<th>Academic</th>
			<th>Program</th>
			<th>Common&nbsp;Code</th>
			<th>Course&nbsp;Code</th>
			<th>Course&nbsp;Name</th>
			<th>Attendanc&nbsp;Status</th>	
			<th class="actions"><?php echo __('Actions'); ?></th>
		</tr>
	</thead>
	<tbody>
		<?php if (isset($course_mapping_array)) { 
			$j = 1;
		foreach ($course_mapping_array as $cm_id => $cmArray) { //pr($cmArray);
			$ese_practical_id = $cmArray['ese_practical_id'];
			$cae_practical_id = $cmArray['cae_practical_id'];
					$details = $this->Html->getCourseNameCrseCodeCmnCodeFromCMId($cm_id);
					$attendance_count = $this->Html->attendanceCount($cm_id, $exam_month_year_id);
					$basic_details = $this->Html->getBatchAcademicProgramFromCmId($cm_id);
					$practical_count = $this->Html->practicalCount($ese_practical_id, $exam_month_year_id);
		?>
					<tr class=" gradeX">
						<td><?php echo $basic_details['batch']." ".$cm_id; ?></td>
						<td><?php echo $basic_details['academic']; ?></td>
						<td><?php echo $basic_details['program']; ?></td>
						<td><?php echo $details['common_code']; ?></td>
						<td><?php echo $details['course_code']; ?></td>
						<td><?php echo $details['course_name']; ?></td>
						<td>
							<?php if ($attendance_count > 0) {
								$attStatus = "Closed";
							}
							else {
								$attStatus = "Open";
							}
							echo $attStatus;?>
						</td>
						<td>
						<?php if ($practical_count > 0) {
								if($this->Html->checkPathAccesstopath('Arrears/arrear','',$authUser['id'])){
									echo $this->Html->link("<i class='fa fa-pencil fa-lg'></i>",array("controller"=>"Arrears",'action' => 'editPracticalMarks', $cm_id, $exam_month_year_id, $ese_practical_id, $cae_practical_id), array('escape' => false,'style'=>'padding-left:10px;'));
								}
							}
							else {
								if ($attendance_count > 0) {
									if($this->Html->checkPathAccesstopath('Arrears/arrear','',$authUser['id'])){
										echo $this->Html->link("<i class='fa fa-plus fa-lg'></i>",array("controller"=>"Arrears",'action' => 'addPracticalMarks', $cm_id, $exam_month_year_id, $ese_practical_id, $cae_practical_id), array('escape' => false,'style'=>'padding-left:10px;'));
									}
								} else {
									if($this->Html->checkPathAccesstopath('Arrears/arrear','',$authUser['id'])){
										echo $this->Html->link("<i class='fa fa-plus fa-lg'></i>",array("controller"=>"Arrears",'action' => 'addPracticalMarks', $cm_id, $exam_month_year_id, $ese_practical_id, $cae_practical_id), array('escape' => false,'style'=>'padding-left:10px;'));
									}
								}
							}
						?>
						</td>
					</tr>
		  <?php $j++; 
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
			<th><input type="text" name="Attendanc Status" value="Attendanc Status" class="search_init" /></th>
			<th></th>
		</tr>
	</tfoot>
</table>

<?php echo $this->Html->script('common'); ?>

<span class='breadcrumb1'>
<span class='navbar-brand'><small>Practicals <i class='ace-icon fa fa-angle-double-right'></i></small></span>
<?php echo $this->Html->link("<span class='navbar-brand'><small> Arrear Mark Entry <i class='ace-icon fa fa-angle-double-right'></i></small></span>", array("controller"=>"Arrears",'action' => 'practical'),array('data-placement'=>'left','escape' => false)); ?>
</span>