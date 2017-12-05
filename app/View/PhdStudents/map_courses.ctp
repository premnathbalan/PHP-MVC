<?php echo $this->Html->script('chosen.jquery');  ?>
<script type="text/javascript" language="javascript">
	jQuery(document).ready(function(){ jQuery(".chosen").data("placeholder","Select Frameworks...").chosen();});
</script>
<div id="accordion" >
	<?php echo "<h3>Course Details </h3>"; ?>
	<div>
		<?php
		echo $this->Form->create('PhdCourse');
		echo $this->Form->input('phd_student_id', array('label'=>false, 'type'=>'hidden', 'value'=>$studentId, 'style'=>'width:75px;', 'name'=>"phd_student_id",'id'=>"phd_student_id"));
		$i = 1;
		if(isset($mappedCourses)){ 
		echo "<div class='col-sm-12' style='cursor:pointer;float:right;width:15%;' name='Coursebtn'  id='Coursebtn'><i class='fa fa-plus'></i>Add Course</div>";
		
			foreach ($mappedCourses as $key => $value) {  //pr($value); echo $value['PhdCourse']['id'];
				echo "<table style='width:60%;border:1px;' border='0'><tr>";
				echo "<td>".
									$this->Form->input('phd_course_id', array('label' => false,'class' => 'col-sm-6 chosen', 
												'empty' => __("----- Select Course-----"), 'options' => $allCourses, 
												'default'=>$value['PhdCourse']['id'], 'name'=>"Course[]",'id'=>"Course[]",
												'style'=>'width: 374px;'))."</td>";
												$id = $value['PhdCourseStudentMapping']['id'];
												$courseId = $value['PhdCourse']['id'];
				echo "<td 'style=width:250px;'>".
					$this->Form->input('month_year_id', array('label' => false,
												'empty' => __("----- Select MonthYear-----"), 'options' => $monthyears, 
												'name'=>"MonthYear[]",'id'=>"MonthYear[]",
												'style'=>'width: 200px;'))." ".
					$this->Form->input('course_id', array('label'=>false, 'type'=>'hidden', 'value'=>$courseId, 'style'=>'width:75px;', 'name'=>"course_id[]",'id'=>"course_id[]"))." ".
					"</td>";
				//echo "<td 'style=width:100px;'>".$this->Form->input('status', array('label'=>false, 'type'=>'text', 'value'=>$value['PhdCourseStudentMapping']['status'], 'style'=>'width:75px;', 'name'=>"status[]",'id'=>"marks".$courseId, 'readonly'=>'readonly'))."</td>";
				echo "<td 'style=width:100px;'>".$this->Html->image("delete.png", array('onclick'=>'delCSM1('.$value['PhdCourseStudentMapping']['id'].');'))."</td>";
				echo "</tr></table>";
			}
		}
		echo "<div id='CourseDiv' style='height:200px;'>";
		echo "</div>";
		echo "<input type='hidden' name='CourseTotal' id='CourseTotal' value='$key' size='2'>";
		
		echo $this->Form->button('<i class="ace-icon fa fa-check bigger-110"></i>'.__('Submit'),array('style'=>'float:right;','type'=>'submit','name'=>'Save','value'=>'Course','class'=>'btn'));
		echo $this->Form->end();
		?>
	</div>
	<?php echo "<h3>Viva Details </h3>"; ?>
	<div>
		<?php
		echo $this->Form->create('PhdStudent');
		echo $this->Form->input('phd_student_id', array('label'=>false, 'type'=>'hidden', 'value'=>$studentId, 'style'=>'width:75px;', 'name'=>"data[PhdStudent][id]",'id'=>"phd_student_id"));
			$options = array("Y"=>"Completed", "N"=>"Not Completed");
		?>
		<table border="1" style="width:100%;">
			<tr>
				<td class='phd_map_courses'>1</td>
				<td class='phd_map_courses'>Comprehensive Viva</td>
				<td class='phd_map_courses'><?php echo $this->Form->input('comprehensive_viva', array('options'=>$options, 'empty'=>'-Select-', 'default'=>$results[0]['PhdStudent']['comprehensive_viva'], 'label'=>false)); ?></td>
				<td class='phd_map_courses'><?php echo $this->Form->input("comprehensive_viva_date", array('label' => false, 'type' => 'text', 'value'=>$results[0]['PhdStudent']['comprehensive_viva_date'], 'class' => 'fl tal vat w300p', 'error' => false , 'id' => 'select_date'));?></td>
				<td class='phd_map_courses'><?php echo $this->Form->input('comprehensive_month_year_id', array('label' => false, 'options' => $monthyears, 'default'=>$results[0]['PhdStudent']['comprehensive_month_year_id'], 'empty' => '-MonthYear-'));?></td>
			</tr>
			<tr>
				<td class='phd_map_courses'>2</td>
				<td class='phd_map_courses'>Synopsis</td>
				<td class='phd_map_courses'><?php echo $this->Form->input('synopsis', array('options'=>$options, 'empty'=>'-Select-', 'default'=>$results[0]['PhdStudent']['synopsis'], 'label'=>false)); ?></td>
				<td class='phd_map_courses'><?php echo $this->Form->input("synopsis_date", array('label' => false, 'type' => 'text', 'value'=>$results[0]['PhdStudent']['synopsis_date'], 'class' => 'fl tal vat w300p', 'error' => false , 'id' => 'select_date1'));?></td>
				<td class='phd_map_courses'><?php echo $this->Form->input('synopsis_month_year_id', array('label' => false, 'options' => $monthyears, 'default'=>$results[0]['PhdStudent']['synopsis_month_year_id'], 'empty' => '-MonthYear-'));?></td>
			</tr>
			<tr>
				<td class='phd_map_courses'>3</td>
				<td class='phd_map_courses'>Viva</td>
				<td colspan='3' class='phd_map_courses'><?php echo $this->Form->input('viva', array('options'=>$options, 'empty'=>'-Select-', 'default'=>$results[0]['PhdStudent']['viva'], 'label'=>false)); ?></td>
			</tr>
		</table>
		<?php echo $this->Form->button('<i class="ace-icon fa fa-check bigger-110"></i>'.__('Submit'),array('style'=>'float:right;','type'=>'submit','name'=>'Save','value'=>'Viva','class'=>'btn'));
		echo $this->Form->end(); ?>
	</div>
</div>
<script>$(function(){$("#accordion").accordion();});</script>
<?php echo $this->Html->script('common'); ?>
<?php echo $this->Html->script('common-front'); ?>
<?php echo $this->Html->css('common'); ?>

<script>
	$("#Coursebtn").click(function() { 
		var oldValue = $("#CourseTotal").val();
		var newValue = parseInt($("#CourseTotal").val())+1;
		$("#CourseTotal").val(newValue);
	
		var courses = <?php echo json_encode($allCourses); ?>;
		//alert(courses);
		newtag = "";
		$varP = 'courseAdd'+newValue;
		newtag+="<div id="+$varP+"><select name='Course[]' id='Course[]' class='col-sm-4 chosen'><option>-- Select Course--</option>";
	    $.each(courses, function (index, item){
	       	newtag+="<option value="+index+">"+item+"</option>";
	    });
		newtag+="</select>";
		newtag+="<input type='text' name='marks[]' id='marks[]' 'style'='width:75px;' />";
		//var delparam = "marks"+newValue;
		newtag+= "<image src='"+path_relative+"img/delete.png' onclick='delCSM2("+newValue+")'><i></i></span>";				 
		newtag+="</div>";
		//alert(newtag);
		$("#CourseDiv").append(newtag);		
		$(".chosen").data("placeholder","Select Frameworks...").chosen();
	});	
	
	function delCSM1(phdCSMId) {
		if(confirm("Are you sure want to Delete?")){
			$.ajax({
				url: path_relative+"PhdCourseStudentMappings/edit/"+phdCSMId,
				type: 'POST',
				data: '',
			    dataType: 'HTML',
				success: function(data, txtStatus) { 
					//$('#'+SemesterCourseDivId).remove();
					alert("Deleted Successfully");
				}				
			});		
		}
	}
	
	function delCSM2(newValue) {
		if(confirm("Are you sure want to Delete?")){
			$('#courseAdd'+newValue).remove();
		}
	}
</script>

<script>
leftMenuSelection('PhdStudents/searchIndex');
</script>

<span class='breadcrumb1'>
<span class='navbar-brand'><small>PHD STUDENTS <i class='ace-icon fa fa-angle-double-right'></i></small></span>
<?php echo $this->Html->link("<span class='navbar-brand'><small> MAP COURSES <i class='ace-icon fa fa-angle-double-right'></i></small></span>", array("controller"=>"PhdStudents",'action' => 'mapCourses',$regNo),array('data-placement'=>'left','escape' => false)); ?>
</span> 