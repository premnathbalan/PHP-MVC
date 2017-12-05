<?php echo "No. of Semesters : ".$numSemesters; 
//pr($arr);
//pr($courses);
?>
<div id="accordion">
<?php
for ($i=1; $i<= $numSemesters; $i++) {

	echo "<h3>Semester $i</h3><div>";
	?>
	<div style="height:400px;">
		<?php
			$courses = count($arr[$i]);
			//echo $i;
			echo $this->Form->create('Semester'.$i);
			echo $this->Form->input('CM', array('type'=>'text', 'class' => 'CM', 'label' => 'Courses', 'value' => count($arr[$i])));
			echo "<div id='SemesterDiv$i'></div>";
			echo $this->Form->input('program_id', array('type'=>'hidden', 'class' => 'HM', 'value' => $program_id));
			for($j=1;$j<=$courses;$j++) {
			$k = $j-1;
			$varMultiple = "false";
				if ($arr[$i][$j]['course_mode_id'] == 2) {
					$varMultiple = "true";
					$arr[$i][$j]['course_id'] = explode(",", $arr[$i][$j]['course_id']);
					//pr($allCourses);
					$test = implode(",", $arr[$i][$j]['course_id']);
				}
				else {
					$test = $arr[$i][$j]['course_id'];
				}
				echo $this->Form->input('CT', array('label' => false, 'empty' => __("----- Course Type-----"), 'options' => $course_mode, 'default'=>$arr[$i][$j]['course_mode_id']));
				echo $this->Form->input('course_id', array('label' => false, 'empty' => __("----- Select -----"), 'options' => $allCourses, 
										'default'=>$arr[$i][$j]['course_id'], 'multiple' => $varMultiple));
										$k=$j-1;
										$val = $k.":".$arr[$i][$j]['course_mode_id']."-".$test;
				echo "<input type='text' name='Course$k' id='Course$k' value=$val>";
			}
			//echo "<div id='newControls'>TEXT</div>";
			echo "<div id='SemesterDiv$i'></div>";
			echo "<input type='text' name='SemId$i' id='SemId$i' value='$courses'>";
			echo "<input type='hidden' name='Semester' value='$i'>";
			echo $this->Form->button('<i class="ace-icon fa fa-check bigger-110"></i>'.__('Submit'),array('type'=>'submit','name'=>'submit','value'=>'submit','class'=>'btn'));
			echo $this->Form->end();
		?>
	</div></div>
	<?php
}
?>
</div>
<script>
  $(function() {
    $( "#accordion" ).accordion();
  });
</script>

<script>
<?php for($i=1; $i<=$numSemesters; $i++) { ?>
	$("#Semester<?php echo $i; ?>CM").change(function() {
		var courses = <?php echo json_encode($allCourses); ?>;
		alert(courses);
		var oldValue = $("#SemId<?php echo $i; ?>").val();
		var newValue = $(this).val();
		//alert (oldValue+" "+newValue);
		if (newValue < oldValue) {
			$("#Semester<?php echo $i; ?>CM").text() = $("#SemId<?php echo $i; ?>").val();
			alert ("Courses cannot be reduced!!!");
		}
		else if (newValue > oldValue) {
			var cnt = newValue - oldValue;
			//alert (cnt+" Courses can be increased!!!");
			newtag="";
			for(var i =oldValue; i < newValue; i++) {
			//alert(i);
				newtag+= "<select name='Semester<?php echo $i; ?>CourseType[]' class='Semester<?php echo $i; ?>CourseType' id='Semester"+i+"CT' onchange='palani("+i+",this.value);'><option value=''>Course Type</option><option value='1'>Foundation</option><option value='2'>Elective</option></select>";
				newtag+="<select name='Semester<?php echo $i; ?>CourseId[]' class='Semester<?php echo $i; ?>CourseId' id='Semester"+i+"' onclick='courses("+i+", this.value,this.id, <?php echo $i; ?>);'><option>Select</option>";
		      	$.each(courses, function (index, item) {
		        	newtag+="<option value="+index+">"+item+"</option>";
		        });
			newtag+="</select>";
			newtag+="<input type='text' name='Course"+i+"' id='Course"+i+"' value=''></br>";
			}
			alert(newtag);
		}
			//$("#newControls").html(newtag);
			$("#SemesterDiv<?php echo $i; ?>").html(newtag);
	});
<?php } ?>

	function palani(str, crsevalue) {
		//alert($(".Semester"+str+"CourseType").val());
		//alert(crsevalue);
		if (crsevalue == 2) {
		
			$("#Semester"+str).attr("multiple", "multiple");
		//	$("#Semester"+str).attr("class", "selectivity-input");
		}
	}
	
	function courses(str, str1,strId, sem) {
		//alert(str+" "+str1+" "+strId+" "+sem);
		//alert($('#'+strId).val(str1+"_"+str));
		//alert($('#'+strId).val());
		//alert(str+" "+sem);
		//alert("Course Count : "+str);
		//alert($("#SemesterCT"+str).val());
		//if($("#Course"+str).val()=="") {
			//$("#SemId"+sem).val(str+","+$("#SemesterCT"+str).val()+","+$('#'+strId).val());
			//$("#Course"+sem).val(str+","+$("#SemesterCT"+str).val()+","+$('#'+strId).val());
		//}
		//else {
			//$("#SemId"+str).val($("#SemId"+sem).val()+"-"+str+","+$("#SemesterCT"+str).val()+","+$('#'+strId).val());
			$("#Course"+str).val(str+":"+$("#Semester"+str+"CT").val()+"-"+$('#'+strId).val());
		//}
		
	}
	
</script>