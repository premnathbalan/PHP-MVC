<?php 
echo $this->Html->css("degree");
$a=1;
if($results){ foreach($results as $result){ //pr($result);
//pr($courseMappingMandatory);
		if (isset($result['StudentAuthorizedBreak'][0]['student_id'])) $abs=1;
		else $abs=0;
	
	//echo "ABS : ".$abs;
	
		if (isset($result['StudentWithdrawal'][0]['student_id'])) $withdrawal=1;
		else $withdrawal=0;
	
	//echo "Withdrawal : ".$withdrawal;
	
		//$student_type_id = $result['Student']['student_type_id'];

		$tamil_month = array('January'=>'[dtup','February'=>'gpg;utup','March'=>'khu;r;','April'=>'Vg;uy;','May'=>'Nk',
		'June'=>'[_d;','July'=>'[_iy','August'=>'Mf];l;','September'=>'nrg;lk;gu;','October'=>'mf;Nlhgu;',
		'November'=>'etk;gu;','December'=>'brk;gu;');
		//Code by Hema
		$student_id = $result['Student']['id'];
		for ($i=1; $i<=15; $i++) ${'courseMark'.$i}="";
		$html1 = "";$html2 = "";$html4 = "";
		
		$examMonthYear = array();
		
		$finalArray = array();
		
		if (isset($result['ParentGroup']['StudentMark']) && count($result['ParentGroup']['StudentMark'])>0) {
			for($p = 0; $p < count($result['ParentGroup']['StudentMark']); $p++) {
				$month_year_id = $result['ParentGroup']['StudentMark'][$p]['month_year_id'];
				if (isset($finalArray[$month_year_id])) {
					$finalArray[$month_year_id][] = $result['ParentGroup']['StudentMark'][$p];
				} else {
					$finalArray[$month_year_id] = array($result['ParentGroup']['StudentMark'][$p]);
				}
			}
		}
		for($p = 0; $p < count($result['StudentMark']); $p++) {
			$month_year_id = $result['StudentMark'][$p]['month_year_id'];
			if (isset($finalArray[$month_year_id])) {
				$finalArray[$month_year_id][] = $result['StudentMark'][$p];
			} else {
				$finalArray[$month_year_id] = array($result['StudentMark'][$p]);
			}
		}
		//pr($finalArray);
		$totalCreditsGained = 0; $numerator=0;
		foreach($finalArray as $month_year_id => $value) { 
			//for($p=0;$p<count($value);$p++){
			foreach ($value as $key => $array) {
				unset($details);
				$courseMId = $array['course_mapping_id'];
				if($array['revaluation_status'] == 0 && $array['status'] == 'Pass' && $array['status'] != 'Fail') {
					$stuFinalMark[$courseMId] = $array['marks'];
					$stuFinalStatus[$courseMId] = $array['status'];
					$details = getArrayValues($array);
				} else if($array['revaluation_status'] == 1 && $array['final_status'] == 'Pass' && $array['final_status'] != 'Fail'){
					$stuFinalMark[$courseMId] = $array['final_marks'];
					$stuFinalStatus[$courseMId] = $array['final_status'];
					$details = getArrayValues($array);
				}
				if (isset($details) && !empty($details)) {
					$examMonthYear[$courseMId]=$details['examMonthYear']; 
					$publishing_date[$courseMId]=$details['publishing_date'];
					$courseSemester[$courseMId]=$details['courseSemester'];
					$creditsGained[$courseMId]=$details['creditsGained'];
					$gradePointArray[$courseMId]=$details['gradePointArray'];
					$courseCreditArray[$courseMId]=$details['courseCreditArray'];
					$semesterIdArray[$courseMId]=$details['semesterIdArray'];
					$courseCodeArray[$courseMId]=$details['courseCodeArray'];
					$courseNameArray[$courseMId]=$details['courseNameArray'];
					$totalCreditsGained = $totalCreditsGained + $courseCreditArray[$courseMId];
					$semester=$details['semId'];
					$numerator = $numerator + ($details['grade_point'] * $details['creditsGained']);
					//echo "</br>num :".$numerator;
					${'courseMark'.$semester} .= $details['semester'];
					${'courseMark'.$semester} .= $details['course_code'];
					${'courseMark'.$semester} .= $details['course_name'];
					${'courseMark'.$semester} .= $details['grade'];
					${'courseMark'.$semester} .= $details['monthYearOfPassing'];
				}
			}
		}

		$csmArray = $result['CourseStudentMapping'];
		$csm_cm_id = array();
		foreach ($csmArray as $key => $csmDetails) {
			$csm_cm_id[$csmDetails['course_mapping_id']] = $csmDetails['student_id'];
		}
		//pr($csm_cm_id);

		//for ($i=1; $i<=15; $i++) pr(${'courseMark'.$i});
		
		/*pr($stuFinalMark);
		pr($stuFinalStatus);
		pr($examMonthYear);
		pr($publishing_date);*/
		//pr($courseSemester);
		//pr($semesterIdArray);
		/*pr($creditsGained);
		pr($courseMappingCreditPoint);
		pr($csm_cm_id);*/
		
		$diff_array = array_diff_key($csm_cm_id, $stuFinalStatus);
		//pr($diff_array);
		
		$mandatory_array = array_diff_key($csm_cm_id, $courseMappingMandatory);
		//pr($mandatory_array);
		
		$first_attempt = array_diff_assoc($courseSemester,array_intersect_assoc($courseSemester, $semesterIdArray));
		//pr($first_attempt);
		
		//pr($programArray);
		
		$month_year_passing_diff = ($courseSemester === $semesterIdArray);
		//pr($month_year_passing_diff);
		//echo "Total credit gained : ".$totalCreditsGained;
		
		if (empty($diff_array) && $totalCreditsGained >= $programArray[$programId] && empty($mandatory_array)) {
				//Get Last Month Year of Exam 
			$lastMonthYearOfExamEnglish = "";$lastMonthYearOfExamTamil = "";
			if($semesterIdArray){
				rsort($semesterIdArray);
				$lastMonthYearOfExam = $semesterIdArray[0];
				$getExamMonths = $this->Html->getMonthYears($lastMonthYearOfExam);
				$lastMonthYearOfExamEnglish = $getExamMonths[0];
				$lastMonthYearOfExamTamil = $getExamMonths[1];			
			}
			
			asort($semesterIdArray);
			//pr($semesterIdArray);
			$semIdArray = array_unique($semesterIdArray);
			//pr($semIdArray);
		
			$seqCnt = "";
			foreach($semIdArray as $key=>$val){
				$htmlData = "";$seqCnt++;
				$htmlData .= ${'courseMark'.$val};
				//echo $htmlData."</br></br>";
				if($seqCnt == count($semIdArray)){
					${'BaseInfo_'.$result['Student']['registration_number']} = "";
					${'BaseInfo_'.$result['Student']['registration_number']} .=' $'.strtoupper($result['Student']['name']).' $'.$result['Student']['registration_number'].' $'.date( "d-M-Y", strtotime(h($result['Student']['birth_date']))).' $'.$result['Student']['gender'].' $'.$result['Program']['Academic']['academic_name'].' $'.$result['Program']['program_name'].' $'.$result['Batch']['batch_from'].'-'.$result['Batch']['batch_to'];
					
					$htmlData = $htmlData.${'BaseInfo_'.$result['Student']['registration_number']};
					//echo strtoupper($result['Student']['registration_number'].'-'.$val)."</br>";
					//echo $htmlData;
				}
				$this->Html->generateBarCode(strtoupper($result['Student']['registration_number'].'-'.$val),$htmlData);
			}
		
			// DC STARTED 
			//echo "numerator : ".$numerator;
			//echo "total_credits :".$totalCreditsGained;
			
			$CGPA = sprintf('%0.2f',round(($numerator/$totalCreditsGained),2));
			//echo "CGPA : ".$CGPA;
			
			//echo $student_id;
			$degResult = $this->Html->saveDegreeCertificate($student_id, $convocation_date, $logged_in_user);
			//pr($degResult);
			$con_date=$degResult['convocation_date'];
			$certCount=$degResult['count'];
			//pr($con_date);
			
		?>

		<?php echo $this->Html->css('certificate');?>
		<table class="page">
  		<tr>
  			<td>
  				<table border="0">
  					<tr align="right">
  						<td>
	  						<table class="tab_certificate" cellpadding="0" cellspacing="0">
		  						<tr>
			  						<td>Register No </td>
			  						<td rowspan="2"> &nbsp;&nbsp;:&nbsp;&nbsp; </td>
			  						<td rowspan="2"><span class="fnt fntsize"><?php echo $result['Student']['registration_number']; ?></span></td>
		  						</tr>
		  						<tr>
	  								<td><span class="baamini">gjpntz;</span></td>
	  							</tr>
	  						</table>
  						</td>
  					</tr>
  					<tr><td height="20"></td></tr>
  					<tr>
  						<td class="dc1">Sathyabama University</td>
  					</tr>
  					<tr>
  						<td class="dc2">(Established under section 3 of UGC Act, 1956)</td>
  					</tr>
  					
  					<tr>
  						<td class="dc4">rj;aghkh gy;fiyf;fofk; </td>
  					</tr>
  					<tr>
  						<td class="dc5">(gy;fiyf;fof khdpaf; FO gpupT 3> rl;lk; 1956d; fPo; epWtg;gl;lJ)</td>
  					</tr>
  					
  					<tr>
  						<td class="dc7">
  						
  							<table cellpadding="0" border="0" cellspacing="0" align="center" style="width:100%;align:center;text-align:center;">
		  						<tr>
			  						<td style="width:33%;text-align:right;color:#FF0000;">
			  						<?php
			  							//if ($certCount > 1) 
			  							//echo "DUPLICATE";
			  						?>
			  						</td>
			  						<td class="dc7">
			  							<?php 
					  						//$profileImage = $this->Html->image("students/".str_replace("  "," ",str_replace("   "," ",$result['Student']['picture'])), ["alt" => h("profile.jpg"),"style"=>"width:126px;height:128px;border-radius:5px;"]);
					  						$profileImage = $this->Html->image("students/".str_replace("  "," ",str_replace("   "," ",$result['Student']['picture'])));
					    					echo $profileImage;
					    				?>
									</td>
			  						<td style="width:33%;text-align:left;color:#FF0000;">
			  						<?php
			  							//if ($certCount > 1) 
			  							//echo "DUPLICATE";
			  						?>
			  						</td>
		  						</tr>
	  						</table>
	  						
    					</td>
  					</tr>
  					<tr><td height="5"></td></tr>
  					<tr>
  						<td class="dc8"><?php echo strtoupper($result['Program']['Faculty']['faculty_name']);?></td>
  					</tr>
  					<tr>
  						<td class="faculty-name-tamil"><?php echo $result['Program']['Faculty']['faculty_name_tamil'];?></td>
  					</tr>	
  					<tr>
  						<td class="dc9" style='padding-top:15px;'>The Board of Management of Sathyabama University, hereby makes known that 
							<strong class='fnttext'><?php echo strtoupper($result['Student']['name']);?></strong> 
							has been admitted to the Degree of 
							<strong class='fnttext'><?php echo strtoupper($result['Academic']['academic_name']);?></strong> 
							having been certified by duly appointed examiners to be qualified to receive the same in 
							<strong class='fnttext'><?php echo strtoupper($result['Program']['program_name']);?></strong> 
							and was placed in the <strong class='fnttext'>
								<?php $degree_classification = $this->Html->generateModeClass($CGPA, $abs, $withdrawal, $first_attempt); 
								echo strtoupper($degree_classification['E']);?></strong> 
							at the examination held in <strong class='fnttext'><?php echo $lastMonthYearOfExamEnglish;?>.</strong>
						</td>
  					</tr>
  					<tr><td height="15"></td></tr>
  					<tr>
  						<td class="dc10"><b class="dc10 dc12">rj;aghkh gy;fiyf;fof Nkyhz;ik mit></b> 
  						<b class='baamini dct'><?php echo $result['Student']['tamil_name'];?></b> 
  						vd;gtUf;F 
  						<b class='baamini fntsize'><?php echo $lastMonthYearOfExamTamil;?></b> 
  						Mk; Mz;L ele;j Nju;tpy; 
  						<b class='baamini dct'><?php echo $result['Program']['program_name_tamil'];?></b> 
  						gpuptpy; 
  						<b class='baamini fntsize'><?php echo $degree_classification['T'];?></b> 
  						Nju;r;rp ngw;Wj; jFjpaile;jpUg;gjhf> jf;f Nju;thsu;fs; rhd;wspj;jij Vw;W 
  						<b class='baamini dct'><?php echo $result['Academic']['academic_name_tamil'];?></b> 
  						vd;Dk; gl;lj;jpid ,yr;rpidAld; toq;Ffpd;wJ.</td>			
  					</tr>
  					<tr>
  						<td class="dc2 dc11" style="padding-top:10px;">Given under the seal of the University</td>
  					</tr>
  					<tr>  						
	  					<td>
	  						<table class="dc13" border="0" width="100%">
	  								
	  								
	  														  					
						  					<tr>
						  						<td width="190">&nbsp;</td>
						  						<!--<td align="center"> <?php //echo $this->Html->image("certificate_signature/".$getSignature[0]['Signature']['signature'], ["alt" => h($getSignature[0]['Signature']['signature']),"style"=>"width:120px;"]);?></td>
						  						<td align="center"> <?php //echo $this->Html->image("certificate_signature/".$getSignature[1]['Signature']['signature'], ["alt" => h($getSignature[1]['Signature']['signature']),"style"=>"width:120px;"]);?></td>
						  						<td align="center"> <?php //echo $this->Html->image("certificate_signature/".$getSignature[2]['Signature']['signature'], ["alt" => h($getSignature[2]['Signature']['signature']),"style"=>"width:120px;"]);?></td>
						  						<td align="center"> <?php //echo $this->Html->image("certificate_signature/".$getSignature[3]['Signature']['signature'], ["alt" => h($getSignature[3]['Signature']['signature']),"style"=>"width:120px;"]);?></td>-->
						  						<td align="center"> <?php echo $this->Html->image("certificate_signature/".$getSignature[0]['Signature']['signature']);?></td>
						  						<td align="center"> <?php echo $this->Html->image("certificate_signature/".$getSignature[1]['Signature']['signature']);?></td>
						  						<td align="center"> <?php echo $this->Html->image("certificate_signature/".$getSignature[2]['Signature']['signature']);?></td>
						  						<td align="center"> <?php echo $this->Html->image("certificate_signature/".$getSignature[3]['Signature']['signature']);?></td>						  						
						  					</tr>
						  					<tr>
						  						<td>&nbsp;</td>
						  						<td class="dc17" align="center"><?php echo $getSignature[0]['Signature']['role'];?></td>
						  						<td class="dc17" align="center"><?php echo $getSignature[1]['Signature']['role'];?></td>
						  						<td class="dc17" align="center"><?php echo $getSignature[2]['Signature']['role'];?></td>
						  						<td class="dc17" align="center"><?php echo $getSignature[3]['Signature']['role'];?></td>
						  					</tr>
						  					<tr class='baamini'>
						  						<td>&nbsp;</td>
						  						<td class="dc18" align="center"><?php echo $getSignature[0]['Signature']['role_tamil'];?></td>
						  						<td class="dc18" align="center"><?php echo $getSignature[1]['Signature']['role_tamil'];?></td>
						  						<td class="dc18" align="center"><?php echo $getSignature[2]['Signature']['role_tamil'];?></td>
						  						<td class="dc18" align="center"><?php echo $getSignature[3]['Signature']['role_tamil'];?></td>
						  					</tr>
						  					<tr>
						  						<td colspan="5" height="80px;"></td>
						  					</tr>	
						  					<tr>
							  					<td style=" vertical-align: bottom;" rowspan="3" colspan="2">
							  						<table border="0">
							  						<tr><td height="80"><?php //echo $this->Html->image("certificate_signature/dc_seal.png", ["alt" => h("dc_seal.png"),"style"=>"height:120px;width:120px;"]);?></td></tr>
							  						<tr><td valign="bottom" height="45" class="dc17 dcfont">Dated&nbsp;: <?php $month =date('F', strtotime($con_date));  echo date('F j, Y', strtotime($con_date)); ?></td></tr>
								  						<tr>
								  							<td height="23"><span class='dc22'>ehs;&nbsp;: </span><span class='baamini dc22'><?php $month=date('F', strtotime($con_date)); $day=date('j', strtotime($con_date)); $year=date('Y', strtotime($con_date)); echo $tamil_month[$month]." </span><span class='dcfont'>".$day.", ".$year."</span>"; ?></td>
								  						</tr>
							  						</table>
				  								</td>
						  					
						  						<td colspan="3"></td>
						  					</tr>	
						  					<tr>
						  						<td></td>
						  						<td colspan="2" height="50px;" align="right" class="dc19">Jeppiaar Nagar, Chennai-600119,</br>Tamil Nadu, INDIA.</td>
						  					</tr>
						  					<tr>
						  						<td></td>
						  						<td colspan="2" height="67px;" align="right" class="dc20">N[g;gpahu; efu;>  nrd;id-600119></br> jkpo;ehL> ,e;jpah.</td>
						  					</tr>					  					
					  					
	  								
		  					</table>	
		  				</td>		  					
	  				</tr>			
  				</table>
  			</td>
  		</tr>
  	</table>			
  	<table class="page">
  		<tr><td height="40"></td></tr>
  		<tr>
  			<td class="dc15" align="left">Student Name : <span style="font-size:15px;"><?php echo strtoupper($result['Student']['name']);?></span></td>
  		</tr>
  		<tr><td height="5"></td></tr>
  		<tr>
  			<td class="dc15" align="left">Register Number : <span style="font-size:15px;"><?php echo strtoupper($result['Student']['registration_number']);?></span></td>
  		</tr>
  		<tr><td height="5"></td></tr>
  		<tr>
  			<td class="dc16">The details in the Barcode (Reg.No, Name, DOB, Subject Code, Subject Name, Marks
scored in each subject, etc.,) can be viewed in a tabulated format by downloading the "Barcode Reader Software" from www.sathyabamuniversity.ac.in/download.php 2D Barcode scanner is to be used to read the Barcode for verification of marks secured by the candidate.</td>
  		</tr>
  		<tr><td height="10"></td></tr>
  		<tr>
  			<td class="dc15" align="left" style="margin-top:10px;">
  		<?php rsort($semIdArray);foreach($semIdArray as $key=>$val){?>
  			<div style='float:left;top:0;padding:0px 10px;'>
  			<?php //echo $this->Html->image("barcode/".$result['Student']['registration_number']."-".$val.".png", ["alt" => h("profile.jpg"),"style"=>"width:330px;height:30px;"]);
  			echo $this->Html->image("barcode/".$result['Student']['registration_number']."-".$val.".png");
  			?>
  			</div>
  		<?php	}		?>
  		</td></tr>
  	</table>	
  	<?php
		} 
	}
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