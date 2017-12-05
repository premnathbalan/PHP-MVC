<div class='col-lg-12' style='float:left;width:100%;'>
<?php echo $this->Html->link('<i class="ace-icon fa fa-print fa-lg"></i>'.'PRINT',array("controller"=>"EndSemesterExams",'action'=>'getReport',$month_year_id, $batch_id, 'PRINT'),array('type'=>'submit','name'=>'PRINT','value'=>'PRINT','class'=>'btn','escape' => false,'style'=>'float:right;'))."&nbsp;&nbsp;&nbsp;";?>
<?php echo $this->Html->link('<i class="ace-icon fa fa-file-excel-o"></i>'.'Excel',array("controller"=>"EndSemesterExams",'action'=>'beforeRevaluationSearch',$month_year_id, $batch_id, 'EXCEL'),array('type'=>'submit','name'=>'Excel','value'=>'Excel','class'=>'btn','escape' => false,'style'=>'float:right;'))."&nbsp;&nbsp;&nbsp;";?>
<div style='clear:both;'></div>
</div>
		
<table border="1" style="margin:5px;width:100%">
	<tr>
		<th>Batch</th>
		<th>Program</th>
	</tr>
	<tr>
		<td><?php echo $this->Html->getBatch($batch_id); ?></td>
		<td><?php echo $this->Html->getProgram($program_id); ?></td>
	</tr>
</table>

<table border="1" style="margin:5px;width:100%">
	<tr>
		<th style='padding-left:5px;'>Sl. No.</th>
		<th style='padding-left:5px;'>Register Number</th>
		<th style='padding-left:5px;'>Name</th>
		<th style='padding-left:5px;'>Arrear Courses</th>
	</tr>
	<?php 
	$num = 0;
	foreach($finalArray as $key =>$value) { ?> 
	<tr>
		<td style='padding-left:5px;'><?php echo $num+1; ?></td>
		<td style='padding-left:5px;'><?php echo $value['s']['registration_number']; ?></td>
		<td style='padding-left:5px;'><?php echo $value['s']['name']; ?></td>
		<td><?php 
			$courseCode = $value[0]['course_code'];
			$courseName = $value[0]['course_name'];
			
			$courseCodeArray = explode(',', $courseCode);
			$courseNameArray = explode(',', $courseName);
			for ($i=0; $i<count($courseCodeArray); $i++) {
			$j = $i+1;
			//if ($option == 1)
			//else $j = "";
				echo "<span style='padding-left:5px;'>".$j.". ".$courseCodeArray[$i]." - ".$courseNameArray[$i]."</span></br>";
			}
			?>
		</td>
	</tr>
	<?php $num++; } ?>
</table>

<?php 
echo $this->Html->script('common');
?>

<script>leftMenuSelection('Batchwise');</script>

<span class='breadcrumb1'>
<span class='navbar-brand'><small>REPORTS <i class='ace-icon fa fa-angle-double-right'></i></small></span>
<?php echo $this->Html->link("<span class='navbar-brand'><small>BatchWise <i class='ace-icon fa fa-angle-double-right'></i></small></span>", array("controller"=>"DummyMarks",'action' => 'moderation'),array('data-placement'=>'left','escape' => false)); ?>
</span>
</div>