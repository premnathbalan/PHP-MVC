<?php
if (isset($studentInternalMarks)) {
//pr($studentInternalMarks); pr($course_details); pr($allStudents);
?>
<?php echo $this->Form->button('<i class="ace-icon fa fa-check bigger-110"></i>'.__('Calculate'), 
array('type'=>'submit','name'=>'submit','value'=>'submit', 'class'=>'btn','style'=>'float:right;'));
echo "</br>";
?>
<table cellpadding="0" cellspacing="0" border="0" class="display" id="example" style="margin-top:10px;">
			<thead>
				<tr>
					<th>Reg&nbsp;No.</th>
					<th>Student&nbsp;Name</th>
					<?php
						if(isset($studentInternalMarks)) { 
						foreach ($studentInternalMarks as $cmId => $cmArray) {
							echo "<th>".$this->Html->getCourseCode($cmId)."</th>";
						}
					}
					?>
				</tr>
			</thead>
			<tbody>
			<?php
				foreach ($allStudents as $allStudent) {
				
				?>
					<tr>
						<td><?php echo $allStudent['Student']['registration_number']; ?></td>
						<td><?php echo $allStudent['Student']['name']; ?></td>
						<?php
							foreach ($studentInternalMarks as $cmId => $cmArray) {
							if (isset($studentInternalMarks[$cmId][$allStudent['Student']['id']])) {
								echo "<td>".$studentInternalMarks[$cmId][$allStudent['Student']['id']]."</td>";
							}
							else {
								echo "<td>NA</td>";
							}
						}
						?>
					</tr>
				<?php
				}
			?>
			</tbody>
			<tfoot>
				<tr>
					<th><input type="text" name="Registration Number" value="Regn No." class="search_init" /></th>
					<th><input type="text" name="Student&nbsp;Name" value="Student&nbsp;Name" class="search_init" /></th>
					<?php
						if(isset($studentInternalMarks)) { 
						foreach ($studentInternalMarks as $cmId => $cmArray) {
							echo "<th><input type=text name=".$this->Html->getCourseCode($cmId)." value=".$this->Html->getCourseCode($cmId)." class=search_init /></th>";
						}
					}
					?>					
				</tfoot>
			</table>
<?php
}
?>
<?php echo $this->Html->script('common'); ?>