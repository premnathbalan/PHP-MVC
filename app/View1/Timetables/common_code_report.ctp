<div style="margin-top:10px;">
<table cellpadding="0" cellspacing="0" border="0" class="display" id="example">
	<thead>
		<tr>
			<th>CourseCode</th>
			<th>CourseName</th>
			<th>BatchDuration</th>
			<th>TotalStrength</th>			
			<th>TotalAppeared</th>
			<th>TotalPass</th>
			<th>StartRange</th>
			<th>EndRange</th>
		</tr>
	</thead>
	<tbody>
		<?php foreach ($finalArray as $course_code => $array) { ?>
			<?php foreach ($array as $batch_duration => $value) { ?>
				<tr class=" gradeX">
					<td><?php echo $course_code; ?></td>
					<td><?php echo $value['course_name'];?></td>
					<td><?php echo $batch_duration;?></td>
					<td><?php echo $value['totalStrength'];?></td>
					<td><?php echo $value['totalAppeared'];?></td>			
					<td><?php echo $value['totalPass'];?></td>
					<td><?php echo $value['start_range'];?></td>
					<td><?php echo $value['end_range']; ?></td>
				</tr>
		<?php }} ?>		
	</tbody>
	<tfoot>
		<tr>
			<th><input type="text" name="CourseCode" value="CourseCode" class="search_init" /></th>
			<th><input type="text" name="CourseName" value="CourseName" class="search_init" /></th>
			<th><input type="text" name="BatchDuration" value="BatchDuration" class="search_init" /></th>			
			<th><input type="text" name="TotalStrength" value="TotalStrength" class="search_init" /></th>
			<th><input type="text" name="TotalAppeared" value="TotalAppeared" class="search_init" /></th>
			<th><input type="text" name="TotalPass" value="TotalPass" class="search_init" /></th>
			<th><input type="text" name="StartRange" value="StartRange" class="search_init" /></th>
			<th><input type="text" name="EndRange" value="EndRange" class="search_init" /></th>
		</tr>
	</tfoot>
	
</table>
<script>leftMenuSelection('Timetables/common_code_report');</script>
<?php echo $this->Html->script('common');?>
<?php echo $this->Html->script('common-front');?>
<span class='breadcrumb1'>
<span class='navbar-brand'><small>REPORTS <i class='ace-icon fa fa-angle-double-right'></i></small></span>
<?php echo $this->Html->link("<span class='navbar-brand'><small>COMMON CODE REPORT <i class='ace-icon fa fa-angle-double-right'></i></small></span>", array("controller"=>"Students",'action' => 'publishWebsiteMark'),array('data-placement'=>'left','escape' => false)); ?>
</span>