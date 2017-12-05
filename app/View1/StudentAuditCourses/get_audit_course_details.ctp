<?php
$crseCount = count($courses);
$new_courses = array_diff_key($courses, $sac);
//pr($new_courses);
?>
<DIV class='btn-action float-clear'>
	<input type='button' name='add_item' value='&nbsp;&nbsp;Add&nbsp;&nbsp;' onClick='addMore(<?php echo $crseCount; ?>);' />
	<input type='button' name='del_item' value='Delete' onClick='deleteRow();' />
</DIV>
<?php
if (isset($results)) {
$res = $this->Html->getStudentInfo($studentId);
$reg_no = $res['Student']['registration_number'];
	//pr($results);
	$sac = $results['StudentAuditCourse']; 
	$i=1;
	foreach ($sac as $key => $array) { //pr($array);
	?>
		<DIV class='product-item float-clear' id='product-item' style='clear:both;'>
			<DIV class='float-left'><input type='checkbox' class='chkbox' value=<?php echo $array['id']; ?> id='chkbox' name='data[StudentAuditCourse][item_index][]' /></DIV>
			<DIV class='float-left'>
				<select style='width:250px;' name='data[StudentAuditCourse][audit_course_id][]'>
					<option value='0'>--Select Audit Course--</option>
					<?php
						$selected = "";
						foreach ($courses as $cid => $value) {
							if ($array['audit_course_id'] == $cid) $selected = "selected";
							else $selected = "";
							echo "<option value='$cid' ".$selected.">".$value."</option>";
						}
					?>
				</select>
			</DIV>
			<DIV class='float-left'><input style='width:150px;' type='text' value='<?php echo $array['marks']; ?>' name='data[StudentAuditCourse][marks][]' required /></DIV>
		</DIV>
	<?php
	}
}
?>

<?php
echo "<div id='courses'>";
echo "<span id='pdt'>";
echo "</span>";
echo "</div>";

echo $this->Form->button('<i class="ace-icon fa fa-check bigger-110"></i>'.__('Submit'),array('style'=>'float:right;','type'=>'submit','name'=>'Save','id'=>'Save','value'=>'Save','class'=>'btn'));
?>
<?php echo $this->Html->script('common'); ?>
<?php echo $this->Html->css('dynamic_textbox'); ?>

<span class='breadcrumb1'>
<span class='navbar-brand'><small>STUDENT <i class='ace-icon fa fa-angle-double-right'></i></small></span>
<?php echo $this->Html->link("<span class='navbar-brand'><small> Audit Courses <i class='ace-icon fa fa-angle-double-right'></i></small></span>", array("controller"=>"StudentAuditCourses",'action' => 'view',$reg_no),array('data-placement'=>'left','escape' => false)); ?>
</span>

<SCRIPT>
$(document).ready(function(){
   /* $(".chkbox").each(function(index, item) { alert(index);
		if (index==0) $(this).css("visibility", "hidden"); 
		else $(this).css("visibility", "visible");
	});*/
});

function addMore(crseCount) {
	item_count=0;
	$('div#product-item').each(function(index, item) {
		item_count++;
	});
	newvalue = "<DIV class='product-item float-clear' id='product-item' style='clear:both;'>";
	newvalue += "<DIV class='float-left'><input type='checkbox' class='chkbox' id='chkbox' name='data[StudentAuditCourse][item_index][]' /></DIV>";
	newvalue += "<DIV class='float-left'>";
	newvalue += "<select style='width:250px;' name='data[StudentAuditCourse][audit_course_id][]'>";
	newvalue += "<option value='0'>--Select Audit Course--</option>";
	newvalue += "<?php foreach ($new_courses as $cid => $value) {	echo "<option value='$cid'>".$value."</option>"; } ?>";
	newvalue += "</select>";
	newvalue += "</DIV>";
	newvalue += "<DIV class='float-left'>";
	newvalue += "<input style='width:150px;' type='text' name='data[StudentAuditCourse][marks][]' required />";
	newvalue += "</DIV>";
	newvalue += "</DIV>";
	if (item_count < crseCount) {
		//$("#courses").append($('#pdt').html(newvalue));
		$("#pdt").append(newvalue);
	}
	else {
		alert('Cannot add more than '+crseCount+' courses');
	}
}
function deleteRow() {
	$('DIV.product-item').each(function(index, item){
		jQuery(':checkbox', this).each(function () {
            if ($(this).is(':checked')) {
				$(item).remove();
				sac_id = $(this).val();
				//alert(sac_id);
				urlOption = path_relative+'StudentAuditCourses/deactivateAuditCourse/'+sac_id+'/';
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