<div style="margin-top:5px;">
<table cellpadding="0" cellspacing="0" border="0" class="display" id="example">
	<thead>
		<tr>
			<th>Exam&nbsp;Date</th>
			<th>Batch</th>
			<th>Exam&nbsp;Month</th>
			<th>Program</th>			
			<th>Specialisation</th>
			<th>Course</th>	
			<th>Common&nbsp;Code</th>			
			<th>Dummy No.</th>
		</tr>
	</thead>
	<tbody>
		<?php foreach ($dummyNos as $timetable): 
			if($timetable['CourseMapping']['Course']['course_type_id']==1) {
			?>
			<tr class=" gradeX">
				<td><?php echo date( "d-M-Y", strtotime(h($timetable['Timetable']['exam_date']))); ?></td>
				<td><?php
					$modeAcademic = ""; if($timetable['CourseMapping']['Batch']['academic'] == 'JUN'){ $modeAcademic = " [A]"; }
					echo h($timetable['CourseMapping']['Batch']['batch_from']."-".$timetable['CourseMapping']['Batch']['batch_to'].$modeAcademic);?></td>
				<td><?php echo $timetable['MonthYear']['Month']['month_name']." ".$timetable['MonthYear']['year'];?></td>
				<td><?php echo $timetable['CourseMapping']['Program']['Academic']['academic_name'];?></td>			
				<td><?php echo $timetable['CourseMapping']['Program']['program_name'];?></td>
				<td><?php echo $timetable['CourseMapping']['Course']['course_code']." - ".$timetable['CourseMapping']['Course']['course_name'];?></td>
				<td><?php echo $timetable['CourseMapping']['Course']['common_code'];?></td>
				<td><?php if($timetable['DummyRangeAllocation']){ echo $timetable['DummyRangeAllocation'][0]['DummyNumber']['start_range'];}?></td>			
			</tr>
			<?php 
		  }
		  endforeach; ?>		
	</tbody>
	<tfoot>
		<tr>
			<th><input type="text" name="Exam&nbsp;Date" value="Exam&nbsp;Date" class="search_init" /></th>
			<th><input type="text" name="Batch" value="Batch" class="search_init" /></th>
			<th><input type="text" name="Exam&nbsp;Month Year" value="Exam&nbsp;Month Year" class="search_init" /></th>
			<th><input type="text" name="Program" value="Program" class="search_init" /></th>			
			<th><input type="text" name="Specialisation" value="Specialisation" class="search_init" /></th>
			<th><input type="text" name="Course" value="Course" class="search_init" /></th>
			<th><input type="text" name="Common&nbsp;Code" value="Common&nbsp;Code" class="search_init" /></th>
			<th><input type="text" name="Dummy No." value="Dummy No." class="search_init" /></th>
		</tr>
	</tfoot>
	<?php echo $this->Html->script('common');?>
</table>

<span class='breadcrumb1'>
<span class='navbar-brand'><small>EXAMINATION <i class='ace-icon fa fa-angle-double-right'></i></small></span>
<?php echo $this->Html->link("<span class='navbar-brand'><small>Dummy Numbers <i class='ace-icon fa fa-angle-double-right'></i></small></span>", array("controller"=>"DummyNumbers",'action' => 'index'),array('data-placement'=>'left','escape' => false)); ?>
</span>
</div>