<?php 
if(isset($finalArray)) {
	//pr($finalArray);
	//pr($studentArray);
	?>
	<table cellpadding="0" cellspacing="0" border="0" class="display" id="example" style="margin-top:10px;">
			<thead>
				<tr>
					<th>S.No.</th>
					<th>Reg.&nbsp;Number</th>
					<th>Student&nbsp;Name</th>
					<?php
						foreach ($courseMappingArray as $cmId => $courseCode) { 
							?>
							<th><?php echo $courseCode['course_code']."-CAE&nbsp;"."(".$finalArray[$cmId]['courseDetails']['max_cae_mark']." Marks)";?></th>
							<th><?php echo $courseCode['course_code']."-ESE&nbsp;"."(".$finalArray[$cmId]['courseDetails']['max_ese_mark']." Marks)"; ?></th>
							<th><?php echo $courseCode['course_code']."-Total&nbsp;"."(".$finalArray[$cmId]['courseDetails']['course_max_marks']." Marks)"; ?></th>
							<th><?php echo $courseCode['course_code']."-Status"; ?></th>
							<?php
						}
					?>
				</tr>
			</thead>
			<tbody>
				<?php
				$i=1;
				foreach ($studentArray as $student_id => $stuDetails) {
				//$stuArray['Student']['id'].":".
				?>
				<tr class='gradeX'>
							<td><?php echo $i;?></td>
							<td><?php echo $stuDetails['reg_num'];?></td>
							<td><?php echo $stuDetails['name'];?></td>
							<?php
							foreach ($courseMappingArray as $cmId => $courseCode) {
								$tmpArray = $finalArray[$cmId];
								if ($tmpArray['totalStatus'][$student_id] && $tmpArray['minEseStatus'][$student_id]) 
									$result = "Pass";
								else 
									$result = "Fail";
								?>
								<td><?php echo $finalArray[$cmId]['CAE'][$student_id]; ?></td>
								<td><?php echo $finalArray[$cmId]['ESE'][$student_id]; ?></td>
								<td><?php echo $finalArray[$cmId]['total'][$student_id]; ?></td>
								<td><?php echo $result; ?></td>
								<?php
							}
					?>
				</tr>
				<?php
				$i++;
				}
				?>
			</tbody>
			<tfoot>
				<!--<tr>
					<th>S.No.</th>
					<th>Reg.&nbsp;Number</th>
					<th>Student&nbsp;Name</th>
					<?php
						foreach ($courseMappingArray as $cmId => $courseCode) {
							?>
							<th><?php echo $courseCode."</br>CAE"; ?></th>
							<th><?php echo $courseCode."</br>ESE"; ?></th>
							<th><?php echo $courseCode."</br>Total"; ?></th>
							<?php
						}
					?>
					<th class="thAction">&nbsp;&nbsp;Action&nbsp;&nbsp;&nbsp;&nbsp;</th>
				</tr>-->
			</tfoot>
		</table>
	<?php
}

?>
<?php echo $this->Html->script('common'); ?>