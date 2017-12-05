<?php $a=1;
if($results){ foreach($results as $result){ 
?>
<?php echo $this->Html->css('certificate');?>
  <table class="page"  style="border:1px; border-color:#000;">		
  		<tr>
  			<td>
  				<table border="0" cellspacing="0" cellspading="0" style="width:100%;margin-top:80px;"> 
  					<tr>
  						<td colspan="5" class="tc1" align="center">TRANSFER CERTIFICATE</td>
  					</tr>
  					<tr>
					<td colspan="4"></td>
  						<td rowspan="3" height="80" align="center">
  						<?php 
  						$profileImage = $this->Html->image("students/".str_replace("  "," ",str_replace("   "," ",$result['Student']['picture'])), ["alt" => h("profile.jpeg"),"style"=>"width:100px;border-radius:5px;margin:-90px 0px 0px 0px;"]);
  						//$profileImage = $this->Html->image("students/".$result['Student']['picture']);
    					echo $profileImage;
  						?>
  						</td>
  					</tr>
  					<tr>
  						<td colspan="3" style="height:70px;margin-top:34px;" class='tc5'>Folio No : 
						<?php
						$seqFolioNo = $seqFolioNo+1; 
						if(strlen($seqFolioNo) == 1){$seqFolioNo = "0000".$seqFolioNo;}
						else if(strlen($seqFolioNo)==2){$seqFolioNo = "000".$seqFolioNo;}
						else if(strlen($seqFolioNo)==3){$seqFolioNo = "00".$seqFolioNo;}
						else if(strlen($seqFolioNo)==4){$seqFolioNo = "0".$seqFolioNo;}
						else {$seqFolioNo = $seqFolioNo;}    
						echo date('dmy').$type_of_cert.$seqFolioNo;
						?></td>
  						<td align="center">Date : <?php echo date( "d-M-Y", strtotime($con_date) ); ?></td>
  					</tr>
  					<tr>
  						<td class='tc5'>1. </td>
						<td class='tc2'>Name of the Student in full </td>
  						<td class='tc3'>:</td>
  						<td class='tc4' colspan="2"><?php echo $result['Student']['name'];?></td>
  					</tr>
					<tr>
  						<td class='tc5'>2.</td>
						<td class='tc2'>Register Number </td>
  						<td class='tc3'>:</td>
  						<td class='tc4' colspan='2'><?php echo $result['Student']['registration_number'];?></td>
  					</tr>
					<tr>
  						<td class='tc5'>3.</td>
						<td class='tc2'>Name of the Parent/Guardian </td>
  						<td class='tc3'>:</td>
  						<td class='tc4' colspan='2'><?php echo $result['Student']['father_name'];?></td>
  					</tr>
					<tr>
  						<td class='tc5'>4.</td>
						<td class='tc2'>Nationality </td>
  						<td class='tc3'>:</td>
  						<td class='tc4' colspan='2'><?php echo $result['Student']['nationality'];?></td>
  					</tr>
					<tr>
  						<td class='tc5' style='vertical-align:top;padding-top:10px;'>5.</td>
						<td class='tc2'>Date of Birth as entered in the University Records </td>
  						<td class='tc3'>:</td>
  						<td class='tc4' colspan='2'><?php echo date( "d-M-Y", strtotime(h($result['Student']['birth_date'])) ); ?></td>
  					</tr>
					<tr>
  						<td class='tc5' style='vertical-align:top;padding-top:10px;'>6.</td>
						<td class='tc2'>Programme / Branch to which the student was admitted</td>
  						<td class='tc3'>:</td>
  						<td class='tc4' colspan='2'>
  							<?php 
  								echo $result['Academic']['short_code']." </br>[ ".$result['Program']['program_name']." ]";
  								//echo $result['Academic']['short_code']." </br>[ Electronics and Telecommunication Engineering ]";
  							?>
  						</td>
  					</tr>
					<tr>
  						<td class='tc5'>7.</td>
						<td class='tc2'>Date of Admission to the Programme </td>
  						<td class='tc3'>:</td>
  						<td class='tc4' colspan='2'><?php echo date( "d-M-Y", strtotime(h($result['Student']['admission_date'])) ); ?></td>
  					</tr>
					<tr>
  						<td class='tc5' style='vertical-align:top;padding-top:10px;'>8.</td>
						<td class='tc2'>Medium of Instruction during the programme of study </td>
  						<td class='tc3'>:</td>
  						<td class='tc4' colspan='2'>English</td>
  					</tr>
					<tr>
  						<td class='tc5' style='vertical-align:top;padding-top:10px;'>9.</td>
						<td class='tc2'>Semester studied at the time of leaving the programme</td>
  						<td class='tc3'>:</td>
  						<td class='tc4' colspan='2'>FINAL SEMESTER</td>
  					</tr>
					<tr>
  						<td class='tc5' style='vertical-align:top;padding-top:10px;'>10.</td>
						<td class='tc2'>Whether he/she has completed the programme </td>
  						<td class='tc3'>:</td>
  						<td class='tc4' colspan='2'><?php echo $pgm_completion_status; ?></td>
  					</tr>
					<tr>
  						<td class='tc5'>11.</td>
						<td class='tc2'>Month & Year of Programme Completion</td>
  						<td class='tc3'>:</td>
  						<td class='tc4' colspan='2'><?php echo date( "M-Y", strtotime(h($result['Batch']['consolidated_pub_date'])) ); ?></td>
  					</tr>
					<tr>
  						<td class='tc5' style='vertical-align:top;padding-top:10px;'>12.</td>
						<td class='tc2'>Whether he / she has qualified to pursue higher studies</td>
  						<td class='tc3'>:</td>
  						<td class='tc4' colspan="2">Refer to University Exam Grade Sheets</td>
  					</tr>
					<tr>
  						<td class='tc5' style='vertical-align:top;padding-top:10px;'>13.</td>
						<td class='tc2'>If he / she has discontinued the programme, Date of leaving</td>
  						<td class='tc3'>:</td>
  						<td class='tc4' colspan='2'><?php echo $discontinued_date; ?></td>
  					</tr>
					<tr>
  						<td class='tc5' style='vertical-align:top;padding-top:10px;'>14.</td>
						<td class='tc2'>Character & Conduct during the programme of study </td>
  						<td class='tc3'>:</td>
  						<td class='tc4' colspan='2'>Good</td>
  					</tr>  					
  					<tr>
  						<td colspan='5'>
							<table style='width:100%' border='0'>
								<tr>
									<td style='width:25%'></td>
									<td style='width:25%;font-weight:bold;' align='center'>
									<?php
									echo $this->Html->image("certificate_signature/".$getSignature[0]['Signature']['signature'], ["alt" => h($getSignature[0]['Signature']['signature']),"style"=>"margin-top:12px;"]);
									//echo $this->Html->image("certificate_signature/".$getSignature[0]['Signature']['signature']);
									?><br/>
  							<?php echo $getSignature[0]['Signature']['name'];?><br/>
  							<?php echo $getSignature[0]['Signature']['role'];?></td>
									<td style='width:25%;font-weight:bold;' align='center'>
									<?php //pr($getSignature);
									echo $this->Html->image("certificate_signature/".$getSignature[1]['Signature']['signature'], ["alt" => h($getSignature[0]['Signature']['signature']),"style"=>"margin-top:12px;"]);
									//echo $this->Html->image("certificate_signature/".$getSignature[1]['Signature']['signature']);
									?><br/>
  							<?php echo $getSignature[1]['Signature']['name'];?><br/>
  							<?php echo $getSignature[1]['Signature']['role'];?></td>
									<td style='width:25%'></td>
								</tr>
							</table>
						</td>
  					</tr>
  				</table>
  			</td>
  		</tr>
  	</table>
<?php }}?>