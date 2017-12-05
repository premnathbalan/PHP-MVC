<table cellpadding="0" cellspacing="0" border="0" class="display" id="example" style="margin-top:10px;">
	<thead>
	<tr>
		<th>Reg No.</th>
		<th>Name</th>
		<th>Common&nbsp;Code</th>
		<th>Batch</th>
		<th>Program</th>
		<th>Specialisation</th>				
	</tr>
	</thead>
	<tbody>
	<?php foreach ($examAttendances as $examAttendance): 
	if(isset($examAttendance['CourseMapping']['Course']['common_code'])){
	for($i=0;$i<count($examAttendance['ExamAttendance']);$i++){?>
	<tr class=" gradeX">
		<td><?php echo $examAttendance['ExamAttendance'][$i]['Student']['registration_number'];?></td>
		<td><?php echo $examAttendance['ExamAttendance'][$i]['Student']['name'];?></td>
		<td><?php echo $examAttendance['CourseMapping']['Course']['common_code'];?></td>
		<td><?php echo $examAttendance['CourseMapping']['Batch']['batch_from']."-".$examAttendance['CourseMapping']['Batch']['batch_to'];?></td>
		<td><?php echo $examAttendance['CourseMapping']['Program']['Academic']['academic_name'];?></td>
		<td><?php echo $examAttendance['CourseMapping']['Program']['program_name'];?></td>				
	</tr>
<?php }} endforeach;?>
	</tbody>
	<tfoot>
		<tr>
			<th><input type="text" name="Reg No." value="Reg No." class="search_init" /></th>
			<th><input type="text" name="Name" value="Name" class="search_init" /></th>
			<th><input type="text" name="Common&nbsp;Code" value="Common&nbsp;Code" class="search_init" /></th>
			<th><input type="text" name="Batch" value="Batch" class="search_init" /></th>
			<th><input type="text" name="Program" value="Program" class="search_init" /></th>
			<th><input type="text" name="Specialisation" value="Specialisation" class="search_init" /></th>						
		</tr>
	</tfoot>
	</table>
<?php echo $this->Html->script('common');?>
<script>leftMenuSelection('ExamAttendances');</script>
<span class='breadcrumb1'>
<span class='navbar-brand'><small>EXAMINATION <i class='ace-icon fa fa-angle-double-right'></i></small></span>
<?php echo $this->Html->link("<span class='navbar-brand'><small>ESE EXAM ABSENT RECORD <i class='ace-icon fa fa-angle-double-right'></i></small></span>", array("controller"=>"ExamAttendances",'action' => 'absent'),array('data-placement'=>'left','escape' => false)); ?>
</span>