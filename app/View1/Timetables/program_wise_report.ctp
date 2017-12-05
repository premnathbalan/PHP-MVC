<div style="margin-top:10px;">
<table cellpadding="0" cellspacing="0" border="0" class="display" id="example">
	<thead>
		<tr>
			<th>CourseCode</th>
			<th>CourseName</th>
			<th>MonthYear</th>
			<th>TotalRegistered</th>			
			<th>TotalAppeared</th>
			<th>TotalPass</th>
			<th>PassPercentage</th>
		</tr>
	</thead>
	<tbody>
		<?php foreach ($finalArray as $cm_id => $array) { ?>
			<tr class=" gradeX">
				<td><?php echo $array['course_code']; ?></td>
				<td><?php echo $array['course_name'];?></td>
				<td><?php echo $array['month_year'];?></td>
				<td><?php echo $array['totalStrength'];?></td>
				<td><?php echo $array['totalAppeared'];?></td>			
				<td><?php echo $array['totalPass'];?></td>
				<td><?php echo $array['passPercent'];?></td>
				</tr>
		<?php } ?>		
	</tbody>
	<tfoot>
		<tr>
			<th><input type="text" name="CourseCode" value="CourseCode" class="search_init" /></th>
			<th><input type="text" name="CourseName" value="CourseName" class="search_init" /></th>
			<th><input type="text" name="MonthYear" value="MonthYear" class="search_init" /></th>
			<th><input type="text" name="TotalRegistered" value="TotalRegistered" class="search_init" /></th>
			<th><input type="text" name="TotalAppeared" value="TotalAppeared" class="search_init" /></th>
			<th><input type="text" name="TotalPass" value="TotalPass" class="search_init" /></th>
			<th><input type="text" name="PassPercentage" value="PassPercentage" class="search_init" /></th>
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