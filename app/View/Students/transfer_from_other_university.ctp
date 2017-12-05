<?php echo $this->Html->script('chosen.jquery');  ?>
<script type="text/javascript" language="javascript">
	jQuery(document).ready(function(){ jQuery(".chosen").data("placeholder","Select Frameworks...").chosen();});
</script>

<div id="accordion" >
<?php 
$noOfSemesters = $semester_joined-1;
if (isset($results[0]['TransferStudent'])) {
	$transfer_courses = $results[0]['TransferStudent'];
}
//pr($transfer_courses);
for ($i=1; $i<= $noOfSemesters; $i++) {	
		echo "<h3>Semester $i </h3>";
		echo "<div>";
		echo "<div>";
		echo $this->Form->create('Transfer');
		?>
		<DIV id="header">
		<DIV class="float-left">&nbsp;</DIV>
		<DIV class="float-left" style="width:100px;font-weight:bold;">Course Code</DIV>
		<DIV class="float-left" style="width:150px;font-weight:bold;">Course Name</DIV>
		<DIV class="float-left" style="width:75px;font-weight:bold;">Course</br> Max Marks</DIV>
		<DIV class="float-left" style="width:50px;font-weight:bold;">Mark</br> Secured</DIV>
		<DIV class="float-left" style="width:100px;font-weight:bold;">Status</DIV>
		<DIV class="float-left" style="width:50px;font-weight:bold;">Credit</DIV>
		<DIV class="float-left" style="width:50px;font-weight:bold;">Grade</DIV>
		<DIV class="float-left" style="width:50px;font-weight:bold;">Grade</br> Point</DIV>
		<DIV class="float-left" style="width:50px;font-weight:bold;">MonthYear</DIV>
		</DIV>
		<?php echo "<DIV id=product$i>"; ?>
		<?php if (isset($transfer_courses) && count($transfer_courses)>0) {
			foreach ($transfer_courses as $tkey => $tvalue) {
				//echo $tvalue['semester_id'];
				if ($tvalue['semester_id'] == $i) {
					?>
					<DIV class="product-item float-clear" style="clear:both;">
						<DIV class="float-left"><input type="checkbox" value="<?php echo $tvalue['id']; ?>" name="data[Transfer][<?php echo $i; ?>][item_index][]" /></DIV>
						<DIV class="float-left"><input style="width:100px;" type="hidden" name="data[Transfer][<?php echo $i; ?>][edit][]" value="<?php echo $tvalue['id'];?>" /></DIV>
						<DIV class="float-left"><input style="width:100px;" type="text" name="data[Transfer][<?php echo $i; ?>][course_code][]" value="<?php echo $tvalue['course_code'];?>" required /></DIV>
						<DIV class="float-left"><input style="width:150px;" type="text" name="data[Transfer][<?php echo $i; ?>][course_name][]" value="<?php echo $tvalue['course_name'];?>" /></DIV>
						<DIV class="float-left"><input style="width:75px;" type="text" name="data[Transfer][<?php echo $i; ?>][course_max_marks][]" value="<?php echo $tvalue['course_max_marks'];?>" /></DIV>
						<DIV class="float-left"><input style="width:50px;" type="text" name="data[Transfer][<?php echo $i; ?>][mark_secured][]" value="<?php echo $tvalue['marks'];?>" /></DIV>
						<DIV class="float-left"><input style="width:100px;" type="text" name="data[Transfer][<?php echo $i; ?>][status][]" value="<?php echo $tvalue['status'];?>" /></DIV>
						<DIV class="float-left"><input style="width:50px;" type="text" name="data[Transfer][<?php echo $i; ?>][credit][]" value="<?php echo $tvalue['credit'];?>" /></DIV>
						<DIV class="float-left"><input style="width:50px;" type="text" name="data[Transfer][<?php echo $i; ?>][grade][]" value="<?php echo $tvalue['grade'];?>" /></DIV>
						<DIV class="float-left"><input style="width:50px;" type="text" name="data[Transfer][<?php echo $i; ?>][grade_point][]" value="<?php echo $tvalue['grade_point'];?>" /></DIV>
						<DIV class="float-left">
							<?php $my_id = $tvalue['month_year_id']; ?>
							<?php echo $this->Form->input('month_year_id', array('type'=>'select', 'label'=>false, 'empty' => __("-Month Year-"), 'name'=>'data[Transfer]['.$i.'][monthyear][]', 'options'=>$monthyears, 'default' => $my_id)); ?>
						</DIV>
					</div>
					<?php
				}
			}
		}
		?>
		<?php echo "<span id=pdt$i>"; ?>
		<DIV class="product-item float-clear" style="clear:both;">
			<DIV class="float-left"><input type="checkbox" name="data[Transfer][<?php echo $i; ?>][item_index][]" /></DIV>
			<DIV class="float-left"><input style="width:100px;" type="hidden" name="data[Transfer][<?php echo $i; ?>][edit][]" value="<?php echo "0"; ?>" /></DIV>
			<DIV class="float-left"><input style="width:100px;" type="text" name="data[Transfer][<?php echo $i; ?>][course_code][]" required /></DIV>
			<DIV class="float-left"><input style="width:150px;" type="text" name="data[Transfer][<?php echo $i; ?>][course_name][]" required /></DIV>
			<DIV class="float-left"><input style="width:75px;" type="text" name="data[Transfer][<?php echo $i; ?>][course_max_marks][]" required /></DIV>
			<DIV class="float-left"><input style="width:50px;" type="text" name="data[Transfer][<?php echo $i; ?>][mark_secured][]" /></DIV>
			<DIV class="float-left"><input style="width:100px;" type="text" name="data[Transfer][<?php echo $i; ?>][status][]" /></DIV>
			<DIV class="float-left"><input style="width:50px;" type="text" name="data[Transfer][<?php echo $i; ?>][credit][]" /></DIV>
			<DIV class="float-left"><input style="width:50px;" type="text" name="data[Transfer][<?php echo $i; ?>][grade][]" /></DIV>
			<DIV class="float-left"><input style="width:50px;" type="text" name="data[Transfer][<?php echo $i; ?>][grade_point][]" /></DIV>

			<DIV class="float-left">
			<?php echo $this->Form->input('month_year_id', array('type'=>'select', 'label'=>false, 'empty' => __("-Month Year-"), 'name'=>'data[Transfer]['.$i.'][monthyear][]', 'options'=>$monthyears, 'required'=>'required')); ?>
			</DIV>
		</DIV>
		</DIV>
		</span>
		<DIV class="btn-action float-clear">
		<input type="button" name="add_item" value="&nbsp;&nbsp;Add&nbsp;&nbsp;" onClick="addMore(<?php echo $i; ?>);" />
		<input type="button" name="del_item" value="Delete" onClick="deleteRow(<?php echo $i; ?>); deleteTransferRecord(<?php if (isset($tvalue['id'])) echo $tvalue['id']; ?>);" />
		<span class="success"><?php if(isset($message)) { echo $message; }?></span>
		</DIV>
		<?php
		echo $this->Form->submit('SUBMIT');
		echo "</div>";
		echo "<div class='col-sm-6' style='text-align:center;'>";
		echo "<input type='hidden' name='student_id' value='$student_id'>";
		echo "</div>";
		echo $this->Form->end();
		echo "</div>";
	
}
?>
</div>
<script>$(function(){$("#accordion").accordion();});</script>
<?php echo $this->Html->script('common'); ?>
<?php echo $this->Html->css('dynamic_textbox'); ?>
<SCRIPT>
function addMore(semester) {
	$("#product"+semester).append($('#pdt'+semester).html());
}
function deleteRow() {
	$('DIV.product-item').each(function(index, item){
		jQuery(':checkbox', this).each(function () {
            if ($(this).is(':checked')) {
				$(item).remove();
				transfer_id = $(this).val();
				urlOption = path_relative+'Students/deactivateTransferStudent/'+transfer_id+'/';
				$.ajax({
					url: urlOption,
					type: 'POST',
				    dataType: 'HTML',
					success: function(data, txtStatus) {
						alert('Success');
					},
				});
            }
        });
	});
}
</SCRIPT>

<span class='breadcrumb1'>
<span class='navbar-brand'><small>Transfer STUDENT <i class='ace-icon fa fa-angle-double-right'></i></small></span>
<?php echo $this->Html->link("<span class='navbar-brand'><small> MAPPING Courses <i class='ace-icon fa fa-angle-double-right'></i></small></span>", array("controller"=>"Students",'action' => 'transferCourses',$reg_number),array('data-placement'=>'left','escape' => false)); ?>
</span>
