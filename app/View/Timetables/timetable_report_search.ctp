	<?php //pr($programArray); 
	//pr($currentModelId);
	echo $this->Html->link('<i class="ace-icon fa fa-file-excel-o"></i>'.'Excel',array("controller"=>"Timetables",'action'=>'timetableReportSearch',$examMonth,$exam_type,'excel'),array('type'=>'submit','name'=>'Excel','value'=>'Excel','class'=>'btn','escape' => false,'style'=>'float:right;'));
	?>
	<table cellpadding="0" cellspacing="0" border="0" class="display" id="example" style="margin-top:10px;">
			<thead>
				<tr>
					<th>&nbsp;&nbsp;Batch&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>
					<th>&nbsp;&nbsp;Academic&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>
					<th>&nbsp;&nbsp;Program&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>
					<th>&nbsp;&nbsp;Course&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>
					<th>Course&nbsp;Code</th>
					<th>Common&nbsp;Code</th>
					<th>Exam&nbsp;Date</th>
					<th>Exam&nbsp;Type</th>
					<th>Exam&nbsp;Session</th>
					<th>Total&nbsp;Strength</th>
					<th>No.&nbsp;Present</th>
					<th>No.&nbsp;Absent</th>
				</tr>
			</thead>
			<tbody>
			<?php
			//pr($programArray);
			foreach ($final_array as $key => $details) {
				echo "<tr class='gradeX'>";
					echo "<td>".$details['batch']."</td>";
					echo "<td>".$details['academic']."</td>";					
					echo "<td>".$details['program']."</td>";
					echo "<td>".$details['course_name']."</td>";
					echo "<td>".$details['course_code']."</td>";
					echo "<td>".$details['common_code']."</td>";
					echo "<td>".$details['exam_date']."</td>";
					echo "<td>".$details['exam_type']."</td>";
					echo "<td>".$details['exam_session']."</td>";
					echo "<td>".$details['total_strength']."</td>";
					echo "<td>".$details['present']."</td>";
					echo "<td>".$details['absent']."</td>";
				echo "</tr>";
			}
			?>	
			</tbody>
			<tfoot>
				<tr>
					<th><input type="text" name="Batch" value="Batch" class="search_init" /></th>
					<th><input type="text" name="Academic" value="Academic" class="search_init" /></th>
					<th><input type="text" name="Program" value="Program" class="search_init" /></th>
					<th><input type="text" name="Course" value="Course" class="search_init" /></th>
					<th><input type="text" name="Course&nbsp;code" value="Course&nbsp;code" class="search_init" /></th>
					<th><input type="text" name="Common&nbsp;code" value="Common&nbsp;code" class="search_init" /></th>
					<th><input type="text" name="Exam&nbsp;date" value="Exam&nbsp;date" class="search_init" /></th>
					<th><input type="text" name="Exam&nbsp;type" value="Exam&nbsp;type" class="search_init" /></th>
					<th><input type="text" name="Exam&nbsp;session" value="Exam&nbsp;session" class="search_init" /></th>
					<th><input type="text" name="Total&nbsp;Strength" value="Total&nbsp;Strength" class="search_init" /></th>
					<th><input type="text" name="No.&nbsp;Present" value="No.&nbsp;Present" class="search_init" /></th>
					<th><input type="text" name="No.&nbsp;Absent" value="No.&nbsp;Absent" class="search_init" /></th>
					<th></th>
				</tr>
			</tfoot>
		</table>
		<?php echo $this->Html->script('common'); ?>

<?php echo $this->Html->script('common-front');?>
<script>leftMenuSelection('Timetables/timetableReport');</script>
<span class='breadcrumb1'>
<span class='navbar-brand'><small>EXAMINATION <i class='ace-icon fa fa-angle-double-right'></i></small></span>
<?php echo $this->Html->link("<span class='navbar-brand'><small>TIME TABLES <i class='ace-icon fa fa-angle-double-right'></i></small></span>", array("controller"=>"Timetables",'action' => 'index'),array('data-placement'=>'left','escape' => false)); ?>
<?php echo $this->Html->link("<span class='navbar-brand'><small>Report <i class='ace-icon fa fa-angle-double-right'></i></small></span>", array("controller"=>"Timetables",'action' => 'timetableReport'),array('data-placement'=>'left','escape' => false)); ?>
</span>