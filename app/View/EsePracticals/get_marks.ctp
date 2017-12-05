<?php 
if(isset($finalArray)) {
//pr($finalArray);
	?>
	<table cellpadding="0" cellspacing="0" border="0" class="display" id="example" style="margin-top:10px;">
			<thead>
				<tr>
					<th>S.No.</th>
					<th>Reg.&nbsp;Number</th>
					<th>Student&nbsp;Name</th>
					<th>Course&nbsp;Code</th>
					<th>Total&nbsp;Course&nbsp;Marks</th>
					<th>CAE</th>
					<!--<th>CAE&nbsp;Total</th>-->
					<th>ESE</th>
					<!--<th>ESE&nbsp;Total</th>-->
					<th>Total</th>
					<th>Status</th>
				</tr>
			</thead>
			<tbody>
				<?php
				$i=1;
				foreach ($finalArray as $cm_id => $practicalArray) {
				$total = $practicalArray['total'];
					foreach ($total as $student_id => $totalMarks) {
				?>
				<tr class='gradeX'>
					<td><?php echo $i;?></td>
					<td><?php echo $studentArray[$student_id]['reg_num'];?></td>
					<td><?php echo $studentArray[$student_id]['name'];?></td>
					<td><?php echo $this->Html->getCourseCode($cm_id);?></td>
					<td><?php echo $practicalArray['courseDetails']['course_max_marks'];?></td>
					<td><?php echo $practicalArray['CAE'][$student_id]." / ".$practicalArray['courseDetails']['max_cae_mark'];?></td>
					<!--<td><?php //echo $practicalArray['courseDetails']['max_cae_mark'];?></td>-->
					<td><?php echo $practicalArray['ESE'][$student_id]." / ".$practicalArray['courseDetails']['max_ese_mark'];?></td>
					<!--<td><?php //echo $practicalArray['courseDetails']['max_ese_mark'];?></td>-->
					<?php 
					$total = $practicalArray['courseDetails']['max_cae_mark']+$practicalArray['courseDetails']['max_ese_mark'];
					?>
					<td><?php echo $practicalArray['total'][$student_id]." / ".$total;?></td>
					<td><?php
						if ($practicalArray['totalStatus'][$student_id] == 0)
							echo "Fail";
						else echo "Pass";
						?></td>
					<?php
					?>
				</tr>
				<?php
					$i++;
					}
				}
				?>
			</tbody>
			<tfoot>
				<tr>
					<th></th>
					<th><input type="text" name="Reg.&nbsp;Number" value="Reg.&nbsp;Number" class="search_init" /></th>
					<th><input type="text" name="Name" value="Name" class="search_init" /></th>
					<th><input type="text" name="CourseCode" value="CourseCode" class="search_init" /></th>
					<th><input type="text" name="Total&nbsp;Course&nbsp;Marks" value="Total&nbsp;Course&nbsp;Marks" class="search_init" /></th>
					<th><input type="text" name="CAE&nbsp;Marks" value="CAE&nbsp;Marks" class="search_init" /></th>
					<!--<th><input type="text" name="Marks" value="Marks" class="search_init" /></th>-->
					<th><input type="text" name="ESE&nbsp;Marks" value="ESE&nbsp;Marks" class="search_init" /></th>
					<!--<th><input type="text" name="Marks" value="Marks" class="search_init" /></th>-->
					<th><input type="text" name="Status" value="Status" class="search_init" /></th>
				</tr>
			</tfoot>
		</table>
	<?php
}

?>
<?php echo $this->Html->script('common'); ?>

<span class='breadcrumb1'>
<span class='navbar-brand'><small>MARKS <i class='ace-icon fa fa-angle-double-right'></i></small></span>
<span class='navbar-brand'><small>Practical <i class='ace-icon fa fa-angle-double-right'></i></small></span>
<?php echo $this->Html->link("<span class='navbar-brand'><small> Practical Report <i class='ace-icon fa fa-angle-double-right'></i></small></span>", array("controller"=>"EsePracticals",'action' => 'report'),array('data-placement'=>'left','escape' => false)); ?>
</span>