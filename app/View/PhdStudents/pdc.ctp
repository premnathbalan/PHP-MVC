<?php 
echo $this->Html->css("degree");
$a=1;
if($results){

		$student_id = $result['Student']['id'];
		for ($i=1; $i<=15; $i++) ${'courseMark'.$i}="";
		$html1 = "";$html2 = "";$html4 = "";
		?>

		<?php echo $this->Html->css('provisional_degree_certificate');?>
		<?php echo $this->Html->css('certificate');?>
		<table class="page" border="0" width="80%" height="1123px">

	  		<tr>
	  			<td colspan="3" style="width:100%;text-align:center;" height="120">
		  			<!--<div class="pdc1"><?php echo strtoupper("Sathyabama University"); ?></div>
  					<div class="pdc2">(Established under section 3 of UGC Act, 1956)</div>
  					<div class="pdc2">Jeppiar Nagar, Rajiv Gandhi Salai,</div>
  					<div class="pdc2">Chennai - 600 119, Tamilnadu, INDIA.</div>-->
				</td>
			</tr>
	  		<tr>
				<td colspan="3" class="pdc4">PROVISIONAL CERTIFICATE</td>
			</tr>
			<tr>
				<td colspan="3" align="center" style="height:170px;valign:bottom;"><?php 
		  						$profileImage = $this->Html->image("students/".str_replace("  "," ",str_replace("   "," ",$result['Student']['picture'])), ["alt" => h("profile.jpeg"),"style"=>"width:100px;border-radius:5px;margin:0px 0px 0px 0px;"]);
		  						//$profileImage = $this->Html->image("students/".$result['Student']['picture']);
		    					echo $profileImage;
		  						?>
		  		</td>
		  	</tr>
	  		<tr>
	  			<td colspan="3" style="text-align:left;width:100%;padding-left:40px;">
	  				<table border="0" cellspacing="0" cellpadding="0">
		  				<tr>
				  			<td class="pdc3 lineheight" style="text-align:left;width:100%;font-size:18px;">
				  				<i>This is to certify that the under mentioned candidate has qualified for the award of Degree as detailed below:</i>
				  			</td>
				  		</tr>
				  	</table>
				  </td>
			</tr>
			<tr>
				<td colspan="3" width="100%"  style="text-align:left;"  class="pdc3">
				  	<table border="0" width="100%">
	  					<tr><td style="width:40%;font-size:18px;padding-left:40px;"><i>Name</i></td><td style="font-size:16px;"><strong><?php echo strtoupper($result['Student']['name']);?></strong> </td></tr>
	  					<tr height="15"><td colspan="2"></td></tr>
	  					<tr><td style="font-size:18px;padding-left:40px;"><i>Register Number</i></td><td style="font-size:16px;"><strong><?php echo strtoupper($result['Student']['registration_number']);?><strong></td></tr>
	  					<tr height="15"><td colspan="2"></td></tr>
	  					<tr><td style="font-size:18px;padding-left:40px;"><i>Program</i></td><td style="font-size:16px;"><?php echo strtoupper($result['Academic']['academic_name']);?></td></tr>
	  					<tr height="15"><td colspan="2"></td></tr>
	  					<tr><td style="font-size:18px;padding-left:40px;"><i>Branch/Specialisation</i></td><td style="font-size:16px;"><?php echo $result['Program']['program_name'];?></td></tr>
	  					<tr height="15"><td colspan="2"></td></tr>
	  					<tr><td style="font-size:18px;padding-left:40px;"><i>Batch</i></td><td style="font-size:16px;"><?php
	  					$academic="";
	  					if ($result['Batch']['academic']=="JUN") $academic="[A]";
	  					echo $result['Batch']['batch_from']."-".$result['Batch']['batch_to']." ".$academic;
	  					?></td></tr>
	  					<tr height="15"><td colspan="2"></td></tr>
	  					<tr><td style="font-size:18px;padding-left:40px;"><i>MonthYear of completing the degree</i></td><td style="font-size:16px;"><?php echo $lastMonthYearOfExamEnglish; ?></td></tr>
	  					<tr height="15"><td colspan="2"></td></tr>
	  					<tr><td style="font-size:18px;padding-left:40px;"><i>Class Secured</i></td><td style="font-size:16px;">
	  					<?php $degree_classification = $this->Html->generateModeClass($CGPA, $abs, $withdrawal, $first_attempt);
	  					echo "<strong style='font-size:16px;'>".$degree_classification['E']."</strong>";
	  					?>
	  					</td></tr>
	  					<tr height="30">
				  			<td colspan="2"></td>
				  		</tr>
	  					<tr>
				  			<td colspan="2" style="font-size:18px;padding-left:40px;">
				  				<i>The Degree Certificate will be issued in Convocation.</i>
				  			</td>
				  		</tr>
				  		<tr height="50">
				  			<td colspan="2"></td>
				  		</tr>
				  		<tr>
				  			<td style="font-size:13px;padding-left:40px;height:150px;vertical-align:top;" colspan="2">
								Chennai - 600 119.</br>
								Date: <?php echo date("l, F j, Y"); ?>
							</td>
				  		</tr>
				  		<tr>
				  			<td colspan="2" width="100%">
				  				<table border="0" cellspacing="0" cellpadding="0" width="100%">
					  				<tr>
						  				<td colspan="3" valign="bottom" style="text-align:center;font-size:16px;">
						  				Controller of Examinations
						  				</td>
						  			</tr>
					  			</table>
				  			</td>
				  		</tr>
	  				</table>
	  			</td>
	  		</tr>
  	</table>			
  		
  	<?php
}
?>


<?php
function getArrayValues($array) {
	$tmpArray = array();
	//pr($array);
	$tmpArray['examMonthYear'] = $array['MonthYear']['Month']['month_name']."-".$array['MonthYear']['year'];
	$tmpArray['publishing_date'] = $array['MonthYear']['publishing_date'];
	$tmpArray['courseSemester'] = $array['CourseMapping']['semester_id'];
	$tmpArray['creditsGained'] = $array['CourseMapping']['Course']['credit_point'];
	
	$tmpArray['gradePointArray'] = $array['grade_point'];
	$tmpArray['courseCreditArray'] = $array['CourseMapping']['Course']['credit_point'];	
	$tmpArray['semesterIdArray'] = $array['month_year_id'];
	$tmpArray['courseCodeArray'] = $array['CourseMapping']['Course']['course_code'];
	$tmpArray['courseNameArray'] = $array['CourseMapping']['Course']['course_name'];
	
	$tmpArray['creditsGained'] = $array['CourseMapping']['Course']['credit_point'];
	
	$tmpArray['semId'] = $array['CourseMapping']['semester_id'];
	$tmpArray['semester'] = "$".$array['CourseMapping']['semester_id']." ";
	$tmpArray['course_code'] = "$".$array['CourseMapping']['Course']['course_code']." ";
	$tmpArray['course_name'] = "$".$array['CourseMapping']['Course']['course_name']." ";
	$tmpArray['grade'] = "$".$array['grade']." ";
	$tmpArray['grade_point'] = $array['grade_point'];
	$tmpArray['monthYearOfPassing'] = "$".$array['MonthYear']['Month']['month_name']."-".$array['MonthYear']['year']." ";
	$tmpArray['monthYearOfPassingId'] = $array['month_year_id'];
	return $tmpArray;
}
?>