<div style="clear:both;"></div>
<table cellpadding="0" cellspacing="0" border="0" class="display" id="example">
	<thead>
		<tr>
			<th>Batch</th>
			<th>Program</th>
			<th>Specialisation</th>
			<th>Student&nbsp;Name</th>
			<th>Registration&nbsp;Number</th>
			<th>Month&nbsp;Year</th>
			<th>Course&nbsp;Name</th>
			<th>Course&nbsp;Type</th>
			<th>Course&nbsp;Code</th>
			<th>Marks</th>
		</tr>
	</thead>
	<tbody>
		<?php $i = 0;foreach ($results as $result):?>
		<tr class=" gradeX">
			<td><?php echo h($result['Student']['Batch']['batch_period']); ?></td>
			<td><?php echo h($result['Student']['Program']['Academic']['academic_name']); ?></td>
			<td><?php echo h($result['Student']['Program']['program_name']); ?></td>
			<td><?php echo h($result['Student']['name']); ?></td>
			<td><?php echo h($result['Student']['registration_number']); ?></td>
			<td><?php echo $month_year; ?></td>
			<td><?php echo h($result['CourseMapping']['Course']['course_name']); ?></td>
			<td><?php echo h($result['CourseMapping']['Course']['CourseType']['course_type']); ?></td>
			<td><?php echo h($result['CourseMapping']['Course']['course_code']); ?></td>
			<td><?php echo h($result['StudentMark']['marks']); ?></td>
		</tr>
		  <?php endforeach; ?>		
	</tbody>
	<tfoot>
		<tr>
			<th><input type="text" name="Batch" value="Batch" class="search_init" /></th>
			<th><input type="text" name="Program" value="Program" class="search_init" /></th>
			<th><input type="text" name="Speciaisation" value="Speciaisation" class="search_init" /></th>
			<th><input type="text" name="Student&nbsp;Name" value="Student&nbsp;Name" class="search_init" /></th>
			<th><input type="text" name="Registration&nbsp;Number" value="Registration&nbsp;Number" class="search_init" /></th>
			<th><input type="text" name="Month&nbsp;Year" value="Month&nbsp;Year" class="search_init" /></th>
			<th><input type="text" name="Course&nbsp;Name" value="Course&nbsp;Name" class="search_init" /></th>
			<th><input type="text" name="Course&nbsp;Type" value="Course&nbsp;Type" class="search_init" /></th>
			<th><input type="text" name="Course&nbsp;Code" value="Course&nbsp;Code" class="search_init" /></th>
			<th><input type="text" name="Marks" value="Marks" class="search_init" /></th>
		</tr>
	</tfoot>
</table>
<?php echo $this->Html->script('common'); ?>

<span class='breadcrumb1'>
<span class='navbar-brand'><small>MASTERS <i class='ace-icon fa fa-angle-double-right'></i></small></span>
<?php echo $this->Html->link("<span class='navbar-brand'><small>STAFF <i class='ace-icon fa fa-angle-double-right'></i></small></span>", array("controller"=>"Users",'action' => 'index'),array('data-placement'=>'left','escape' => false)); ?>
</span>