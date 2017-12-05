<?php
//pr($results);
?>
<table cellpadding="0" cellspacing="0" border="0" class="display" id="example" style="margin-top:10px;">
	<thead>
		<tr>
			<th></th>
			<th>Reg&nbsp;Number</th>
			<th>StudentName</th>
			<th>ACTION</th>
		</tr>
	</thead>
	<tbody>
		<?php
		$i=1;
		foreach ($studentResults as $student_id => $studentData) {
		echo "<tr class='gradeX'>";
			echo "<td>".$i."</td>";
			echo "<td>".$studentData['reg_num']."</td>";
			echo "<td>".$studentData['name']."</td>";
			echo "<td>".
				$this->Html->link("<i class='fa fa-eye fa-lg'></i>", array("controller"=>"Students",'action' => 'viewStudentData', $exam_month_year_id, $batch_id, $academic_id, $program_id, $student_id),array('escape' => false, 'title'=>'View'))."&nbsp;&nbsp;"
			."</td>";
		echo "</tr>";
		$i++;
		} 
		?>
	</tbody>
	<!--<tfoot>
		<tr>
			<th></th>
			<th><input type="text" name="RegistrationNumber" value="RegistrationNumber" class="search_init" /></th>
			<th><input type="text" name="StudentName" value="StudentName" class="search_init" /></th>
		</tr>
	</tfoot>-->
</table>

<?php echo $this->Html->script('common');?>
</div>

<span class='breadcrumb1'>
<span class='navbar-brand'><small>RESULTS <i class='ace-icon fa fa-angle-double-right'></i></small></span>
<?php echo $this->Html->link("<span class='navbar-brand'><small>CoE <i class='ace-icon fa fa-angle-double-right'></i></small></span>", array("controller"=>"Students",'action' => 'coe'),array('data-placement'=>'left','escape' => false)); ?>
</span>