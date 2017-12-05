<?php echo $this->Html->script('towords'); ?>
<?php 
echo $this->Html->css("degree");
echo $this->Html->css('certificate');
$a=1;
if($rankArray){  //pr($rankArray); 
		?>
		<?php echo $this->Html->css('provisional_degree_certificate');?>
		<?php echo $this->Html->css('certificate');?>
		<table class="page" border="0" width="80%">

	  		<tr>
	  			<td colspan="3" style="width:100%;text-align:center;" height="80">
		  			<!--<div class="pdc1"><?php echo strtoupper("Sathyabama University"); ?></div>
  					<div class="pdc2">(Established under section 3 of UGC Act, 1956)</div>
  					<div class="pdc2">Jeppiar Nagar, Rajiv Gandhi Salai,</div>
  					<div class="pdc2">Chennai - 600 119, Tamilnadu, INDIA.</div>-->
				</td>
			</tr>
	  		<tr>
				<td colspan="3" class="pdc4">RANK CERTIFICATE</td>
			</tr>
			<tr>
				<td colspan="3" align="center" style="height:170px;valign:bottom;"><?php 
		  						$profileImage = $this->Html->image("students/".str_replace("  "," ",str_replace("   "," ",$rankArray['picture'])), ["alt" => h("profile.jpeg"),"style"=>"width:100px;border-radius:5px;margin:0px 0px 0px 0px;"]);
		  						//$profileImage = $this->Html->image("students/".$rankArray['picture']);
		    					echo $profileImage;
		  						?>
		  		</td>
		  	</tr>
			<tr>
				<td colspan="3" width="100%"  style="text-align:left;" class="pdc3">
				  	<table border="0" width="100%">
				  		<tr><td>
				  		<p style="font-style:italic;font-size:18px;padding-left:40px;padding-right:40px;text-align:justify;line-height:50px;">
				  		This is to certify that 
				  				<strong><?php echo strtoupper($rankArray['name']);?></strong>,
				  		(Register number : <?php echo strtoupper($rankArray['registration_number']);?>)  
				  		has obtained 
				  		
	  							<script type='text/javascript' language='javascript'>
	  								var rank = nth(<?php echo $seqRank; ?>);
	  							</script>
						<?php
							echo "<strong><script>document.writeln(rank.toUpperCase());</script>";
						?>
				  		</strong> Rank in the <?php echo strtoupper($rankArray['program']);?> (<?php echo $rankArray['specialisation'];?>) 
				  		Degree of <?php echo $rankArray['batch']; ?> Batch.</p>
				  		</td></tr>

				  		<tr height="80">
				  			<td colspan="2"></td>
				  		</tr>
				  		<tr>
  						<td colspan='2' style='vertical-align:top;height:160px'>
							<table style='width:100%' border='0'>
								<tr>
									<td style='width:15%'></td>
									<td style='width:35%;font-weight:bold;font-size:12px;text-align:bottom;vertical-align:bottom;' align='center'>
									<?php
									echo $this->Html->image("certificate_signature/".$getSignature[0]['Signature']['signature'], ["alt" => h($getSignature[0]['Signature']['signature']),"style"=>"margin-top:12px;"]);
									//echo $this->Html->image("certificate_signature/".$getSignature[0]['Signature']['signature']);
									?><br/>
  							<?php echo $getSignature[0]['Signature']['name'];?><br/>
  							<?php echo $getSignature[0]['Signature']['role'];?></td>
									<td style='width:35%;font-weight:bold;font-size:12px;text-align:bottom;vertical-align:bottom;' align='center'>
									<?php //pr($getSignature);
									echo $this->Html->image("certificate_signature/".$getSignature[1]['Signature']['signature'], ["alt" => h($getSignature[0]['Signature']['signature']),"style"=>"margin-top:12px;"]);
									//echo $this->Html->image("certificate_signature/".$getSignature[1]['Signature']['signature']);
									?><br/>
  							<?php echo $getSignature[1]['Signature']['name'];?><br/>
  							<?php echo $getSignature[1]['Signature']['role'];?></td>
									<td style='width:15%'></td>
								</tr>
							</table>
						</td>
  					</tr>
  					<tr>
	  					<td style=" vertical-align: top;" colspan="2" height="200">
	  						<table border="0" width="100%">
	  						
	  							<tr>
	  							<td valign="bottom" height="45" class="dc17 dcfont">Dated&nbsp;: <?php echo date("l, F j, Y"); ?></td>
	  							<td>
		  							<table border="0" width="100%">
		  								<tr>
			  								<td align="right" class="dc19">Jeppiaar Nagar, Chennai-600119,</br>Tamil Nadu, INDIA.</td>
			  							</tr>
			  							<tr>
			  								<td> &nbsp;</td>
			  							</td>
			  							<tr>
			  								<td align="right" class="dc20">N[g;gpahu; efu;>  nrd;id-600119></br> jkpo;ehL> ,e;jpah.</td>
			  							</tr>
			  						</table>
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
	return $tmpArray;
}
?>