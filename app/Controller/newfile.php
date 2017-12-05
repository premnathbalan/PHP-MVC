<div>
	No. of Semesters :<?php echo $programSemesters;?>
</div>
<?php echo $this->Html->script('chosen.jquery');  ?>
<script type="text/javascript" language="javascript">
	jQuery(document).ready(function(){ jQuery(".chosen").data("placeholder","Select Frameworks...").chosen();});
</script>

<div id="accordion">
<?php
for($i = 1; $i <= $programSemesters; $i ++) {
	$defaultMY = "";
	if (isset ( $arr [$i] )) {
		foreach ( $arr [$i] as $key => $val ) {
			$defaultMY = $val;
		}
	}
	$courses = "";
	$btnDisabled = "";
	if (isset ( $arr [$i] )) {
		$courses = count ( $arr [$i] );
	} else {
		$courses = 0;
	}
	echo "<h3>Semester $i </h3>"; 
	echo "<div>";
	echo $this->Form->create ( 'Semester' . $i, array (
			'url' => array (
					'controller' => 'CourseMappings',
					'action' => 'index' 
			) 
	) );
	echo "<div id='SemesterDiv$i' style='height:300px;'>";
	echo "<input type='hidden' name='Semester" . $i . "CM' id='Semester" . $i . "CM' value='$courses' size = '2' />";
	
	echo "<div class='col-sm-12' style='margin-bottom:5px;'><div class='col-sm-7'></div>";
	echo "<div class='col-sm-5 bgFrame1'><div class='col-sm-8'>";
	$selDDMonthYear = "";
	if (isset ( $defaultMY ['MONTH_YEAR'] )) {
		$selDDMonthYear = $defaultMY ['MONTH_YEAR'];
	}
	echo $this->Form->input ( 'Month&nbsp;Year', array (
			'name' => 'MonthYear',
			'id' => 'MonthYear' . $i,
			'label' => "Month&nbsp;Year<span class='ash'>*</span>",
			'type' => 'select',
			'options' => $monthYears,
			'default' => $selDDMonthYear 
	) );
	
	echo "</div>";
	if ($this->Html->checkPathAccesstopath ( 'CourseMappings/add', '', $authUser ['id'] )) {
		echo "<div class='col-sm-4' style='cursor:pointer;' name='Semester" . $i . "btn'  id='Semester" . $i . "btn'><i class='fa fa-plus'></i>Add Course</div>";
	}
	echo "</div></div>";
	
	if (isset ( $arr [$i] )) {
		
		foreach ( $arr [$i] as $key => $val ) {
			
			// for($j=1;$j<=$courses;$j++) {
			if (isset ( $arr [$i] [$key] ['course_mode_id'] )) {
				// $k = $j-1;
				$disabled = "";
				// disabled
				// if(!empty($arr[$i][$key]['CAES_ID']) && !empty($arr[$i][$key]['CSM_ID'])){ $disabled = "disabled";$btnDisabled = "disabled"; }
				$varP = "S" . $i . "CourseDiv" . ($key);
				echo "<div class='col-sm-6'  id='$varP' >";
				
				echo "<span class='col-sm-1'>";
				
				$spanVar4 = "CM" . $i . "AutoId" . $key;
				$spanVarVal = $arr [$i] [$key] ['course_mapping_id'];
				echo "<input type='hidden' name='$spanVar4' id='$spanVar4'  value='$spanVarVal' size='0'>";
				
				$QueryMode = "A";
				if ($arr [$i] [$key] ['course_mapping_id']) {
					$QueryMode = "E";
				}
				$spanVar2 = "Semester" . $i . "Mode" . $key;
				echo "<input type='hidden' name='$spanVar2' id='$spanVar2'  value='$QueryMode' size='0'>";
				
				$spanVar3 = "Course" . $i . "Number" . $key;
				$val3 = $arr [$i] [$key] ['course_number'];
				echo "<input type='hidden' name='$spanVar3' id='$spanVar3'  value='$val3' size='0'>";
				
				$imgDisabled = "";
				if ($this->Html->checkPathAccesstopath ( 'CourseMappings/delete', '', $authUser ['id'] )) {
					$imgDisabled = " onclick='delCM1(&#39;" . $arr [$i] [$key] ['course_mapping_id'] . "&#39;,&#39;" . $varP . "&#39;)' ";
				}
				
				if ($arr [$i] [$key] ['CAES_ID']) {
					$imgDisabled = " class= 'imgDisabled' ";
				}
				echo "<image src='img/delete.png' $imgDisabled >";
				
				echo "<i></i></span>"; // Label display Ex. 1
				
				echo $this->Form->input ( 'CT', array (
						'label' => false,
						'class' => 'col-sm-4',
						'empty' => __ ( "----- Course Type-----" ),
						'options' => $course_mode,
						'default' => $arr [$i] [$key] ['course_mode_id'],
						'onchange' => 'toggleCT(' . $i . ',this.value,' . $key . ');',
						'name' => "Semester" . $i . "CT" . $key,
						'id' => "Semester" . $i . "CT" . $key,
						$disabled 
				) );
				
				$spanVar = "spanSemester" . $i . "CourseId" . $key;
				echo "<span id='$spanVar'>";
				echo $this->Form->input ( 'course_id', array (
						'label' => false,
						'class' => 'col-sm-6 chosen',
						'empty' => __ ( "----- Select Course-----" ),
						'options' => $allCourses,
						'default' => $arr [$i] [$key] ['course_id'],
						'multiple' => 'multiple',
						'name' => "Semester" . $i . "CourseId" . $key,
						'id' => "Semester" . $i . "CourseId" . $key,
						$disabled 
				) );
				echo "</span></div>";
			}
		}
	}
	echo "</div>";
	echo "<div class='col-sm-6' style='text-align:center;'>";
	echo "<input type='hidden' name='batch_id' value='$batchId' size='2'>";
	echo "<input type='hidden' name='program_id' value='$programId' size='2'>";
	echo "<input type='hidden' name='semester' value='$i' size='2'>";
	if (empty ( $key )) {
		$key = 0;
	}
	echo "<input type='hidden' name='SemId$i' id='SemId$i' value='$key' size='2'>";
	if ($this->Html->checkPathAccesstopath ( 'CourseMappings/add', '', $authUser ['id'] )) {
		echo $this->Form->button ( '<i class="ace-icon fa fa-check bigger-110"></i>' . __ ( 'Submit' ), array (
				'style' => 'float:right;',
				'type' => 'button',
				'name' => 'Save' . $i,
				'id' => 'Save' . $i,
				'value' => 'Save',
				'onclick' => "saveCourseMapping($batchId,$programId,$i);",
				'class' => 'btn',
				$btnDisabled 
		) );
	}
	echo "</div>";
	echo $this->Form->end ();
	echo "</div>";
}
?>
</div>
<script type="text/javascript">$(function(){$("#accordion").accordion();});</script>
<?php echo $this->Html->script('common'); ?>
<script type="text/javascript">
<?php for($i=1; $i<=$programSemesters; $i++) { ?>
	$("#Semester<?php echo $i; ?>btn").click(function() { 
		if($('#MonthYear<?php echo $i; ?>').val() == ''){
			alert("Select Month Year");
			$('#MonthYear<?php echo $i; ?>').focus();
			return false;
		}
		var oldValue = $("#SemId<?php echo $i; ?>").val();
		var newValue = parseInt($("#SemId<?php echo $i; ?>").val())+1;//$("#Semester<?php echo $i; ?>CM").val();		
		var cnt = newValue - oldValue;
		//alert(oldValue+' '+newValue+' '+cnt);
		if(cnt > 0){			
			var course_mode = <?php echo json_encode($course_mode); ?>;
			var courses = <?php echo json_encode($allCourses); ?>;
			newtag = "";
			for(var i =oldValue; i < newValue; i++) {
			$("#SemId<?php echo $i; ?>").val(newValue);
				newtag+= "<div class='col-sm-6' id='S<?php echo $i; ?>CourseDiv"+(parseInt(i)+1)+"'><span class='col-sm-1'>";
				newtag+= "<input type='hidden' name='Course<?php echo $i; ?>Number"+(parseInt(i)+1)+"' id='Course<?php echo $i; ?>Number"+(parseInt(i)+1)+"' value='"+(parseInt(i)+1)+"' size='0'>";
				newtag+= "<input type='hidden' name='Semester<?php echo $i; ?>Mode"+(parseInt(i)+1)+"' id='Semester<?php echo $i; ?>Mode"+(parseInt(i)+1)+"' value='A' size='0'>";
				var delparam = "&#39;S"+<?php echo $i; ?>+"CourseDiv"+(parseInt(i)+1)+"&#39;";
				newtag+= "<image src='"+path_relative+"img/delete.png' onclick='delCM2("+delparam+")'><i></i></span>";				 
		        newtag+= "<select name='Semester<?php echo $i; ?>CT"+(parseInt(i)+1)+"' class='Semester<?php echo $i; ?>CT col-sm-4' id='Semester"+<?php echo $i; ?>+"CT"+(parseInt(i)+1)+"' onchange='toggleCT("+<?php echo $i; ?>+",this.value,"+(parseInt(i)+1)+");'><option value=''>- Course Type -</option>";
		        $.each(course_mode, function (index, item){
		        	newtag+="<option value="+index+">"+item+"</option>";
		        });		       
		        newtag+="</select>";
				
				newtag+="<span id='spanSemester<?php echo $i; ?>CourseId"+(parseInt(i)+1)+"'>";
				newtag+="<select multiple='multiple' name='Semester<?php echo $i; ?>courseId"+(parseInt(i)+1)+"[]' class='Semester<?php echo $i; ?>CourseId col-sm-6 chosen' id='Semester<?php echo $i; ?>CourseId"+(parseInt(i)+1)+"'><option>-- Select Course--</option>";
		      	$.each(courses, function (index, item){
		        	newtag+="<option value="+index+">"+item+"</option>";
		        });
			newtag+="</select>";
			newtag+="</span>";
			newtag+="</div>";
			}			
			$("#SemesterDiv<?php echo $i; ?>").append(newtag);		
			$(".chosen").data("placeholder","Select Frameworks...").chosen();
		}else{alert("Please Enter : "+(parseInt(oldValue)+1));}		
	});	
<?php } ?>

	function toggleCT(str = null, crsevalue = null, counter = null) { //alert(str+"*"+crsevalue+"*"+counter);				
		var multipleVar = "";
		var myStringVar = $('#Semester'+str+'CT'+counter+' option:selected').text(); //alert(myStringVar);
		var myMatch = myStringVar.search('Electives');//alert(myMatch);

		var courses = <?php echo json_encode($allCourses); ?>;
		var newtag = "";
		newtag+="<select multiple='multiple' name='Semester"+str+"CourseId"+counter+"[]' class='col-sm-6 chosen' id='Semester"+str+"CourseId"+counter+"'><option>Select Course</option>";
	      	$.each(courses, function (index, item){
	        	newtag+="<option value="+index+">"+item+"</option>";
	        });
		newtag +="</select>";//alert(newtag);
		$('#spanSemester'+str+'CourseId'+counter).html(newtag);
		$(".chosen").data("placeholder","Select Frameworks...").chosen();
	}
	function delCM1(CMAutoId,SemesterCourseDivId){
		if(confirm("Are you sure want to Delete?")){
			$.ajax({
				url: path_relative+"CourseMappings/edit/"+CMAutoId,
				type: 'POST',
				data: '',
			    dataType: 'HTML',
				success: function(data, txtStatus) { 
					$('#'+SemesterCourseDivId).remove();
					alert("Deleted Successfully");
				}				
			});		
		}
	}
	function delCM2(SemesterCourseDivId){
		if(confirm("Are you sure want to Delete?")){
			$('#'+SemesterCourseDivId).remove();
		}
	}
</script>

<script type="text/javascript">leftMenuSelection('CourseMappings');</script>
<span class='breadcrumb1'> <span class='navbar-brand'><small>MASTERS <i
			class='ace-icon fa fa-angle-double-right'></i></small></span>
<?php echo $this->Html->link("<span class='navbar-brand'><small>COURSE MAPPINGS <i class='ace-icon fa fa-angle-double-right'></i></small></span>", array("controller"=>"CourseMappings",'action' => 'index'),array('data-placement'=>'left','escape' => false)); ?>
</span>
