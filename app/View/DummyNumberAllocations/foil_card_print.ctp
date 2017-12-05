	<div class="attendanceHeadTblP2">
		
		<div style="width:500px;margin-left:130px;">	
			<?php $totalPresent = $results[0]['DummyNumber']['end_range'] - $results[0]['DummyNumber']['start_range'];?>
			<table height="30px;"><tr><td></td></tr></table>
			<table style="width:100%;margin-top:60px;" border="1" cellpadding="0" cellspacing="0">
				<thead>
					<tr><td colspan="4" align="center" style='font:18px Arial;font-weight:bold;height:60px;'>SATHYABAMA UNIVERSITY</td></tr>
					<tr><td colspan="4" align="center" style='font:14px Arial;font-weight:bold;'><?php //if(isset($examType) == "R"){echo "REGULAR ";}?>THEORY EXAM FOIL CARD </td></tr>
					<tr>
						<td colspan="3" style='font:12px Arial;font-weight:bold;'>Month&Year of Exam : <?php echo $monthYear; ?></td>		
					</tr>
					<tr>
						<td colspan="3" style='font:12px Arial;font-weight:bold;'>Course Name : <?php echo $subject; ?></td>			
					</tr>
					<tr>
						<td colspan="3" style='font:12px Arial;font-weight:bold;'>Course Code : <?php echo $subjectCode;?> <span style='padding-left:80px;'> Max Marks. : <?php echo $courseMaxMark; ?></span><span style='float:right;padding-right:5px;'>(<?php echo "Page 1 of ".$pages = ceil($totalPresent / 20);?>)</span></td>			
					</tr>   
				  <tr>
				   <td rowspan="2" style="width:100px;" align='center'>DUMMY <br/>NUMBER</td>
				    <td colspan="2" align='center'><b>MARKS OBTAINED OUT OF 100</b></td>    
				  </tr>
				  <tr>
				    <td align='center' style="width:20px;">IN <br/>FIGURES</td>
				    <td align='center'>IN WORDS</td>
				  </tr>
				</thead>
				
				<tbody>
				<?php if(isset($results[0]['DummyNumber']['start_range'])){
		$actualDummyNo = $results[0]['DummyNumber']['start_range'];$p =0;
		for($i=($results[0]['DummyNumber']['start_range']);$i<=($results[0]['DummyNumber']['end_range']);$i++){?>
				<tr class="gradeX">
					<td align='center'><?php echo $actualDummyNo;?></td> 
					<td></td>
					<td></td>
				</tr>
				<?php $actualDummyNo = $actualDummyNo +1; $p = $p+1; if(($p % 20 == 0) && ($i < $results[0]['DummyNumber']['end_range'])){?>
				<tr>
					<td align='center'>TOTAL</td>  
					<td></td>
					<td></td>
				</tr>
				<tr><td colspan='3' style="height:60px;vertical-align: text-bottom;">Signature & Name of Examiner in Capitals</td></tr>
				<tr><td colspan='3' style="height:60px;" align="left"><u><span style='padding-left:160px;'>Instruction to Examiners</span></u><br/>1. Totalling of marks is mandatory <br/>2. Mark column should not be left blank. <br/>3. Avoid omission, alteration of Register/Dummy Number/mark.</td></tr>
				</tbody>	
			</table>
		</div>
		<div style='height:30px;'></div>	
		<div style="width:500px;margin-left:130px;">	
			<table height="30px;"><tr><td></td></tr></table>		
			<table style="width:100%;margin-top:60px;" border="1" cellpadding="0" cellspacing="0">
				<thead>
					<tr><td colspan="4" align="center" style='font:18px Arial;font-weight:bold;height:60px;'>SATHYABAMA UNIVERSITY</td></tr>
					<tr><td colspan="4" align="center" style='font:14px Arial;font-weight:bold;'><?php //if(isset($examType) == "R"){echo "REGULAR ";}?>THEORY EXAM FOIL CARD </td></tr>
					<tr>
						<td colspan="3" style='font:12px Arial;font-weight:bold;'>Month&Year of Exam : <?php echo $monthYear; ?></td>		
					</tr>
					<tr>
						<td colspan="3" style='font:12px Arial;font-weight:bold;'>Course Name : <?php echo $subject; ?></td>			
					</tr>
					<tr>
						<td colspan="3" style='font:12px Arial;font-weight:bold;'>Course Code : <?php echo $subjectCode;?><span style='padding-left:80px;'> Max Marks. : <?php echo $courseMaxMark; ?></span><span style='float:right;padding-right:5px;'>(<?php echo "Page ".(($p/20)+1)." of ".$pages = ceil($totalPresent / 20);?>)</span></td>			
					</tr>   
				  <tr>
				   <td rowspan="2" style="width:100px;" align='center'>DUMMY <br/>NUMBER</td>
				    <td colspan="2" align='center'><b>MARKS OBTAINED OUT OF 100</b></td>    
				  </tr>
				  <tr>
				    <td align='center' style="width:20px;">IN <br/>FIGURES</td>
				    <td align='center'>IN WORDS</td>
				  </tr>
				</thead>							
				<tbody>
			<?php }}}?>
				<tr>
					<td align='center'>TOTAL</td> 
					<td></td>
					<td></td>
				</tr>
				<tr><td colspan='3' style="height:60px;"></tr>
				<tr><td colspan='3' style="height:60px;vertical-align: text-bottom;">Signature & Name of Examiner in Capitals</td></tr>
				<tr><td colspan='3' style="height:60px;" align="left"><u><b><span style='padding-left:160px;'>Instruction to Examiners</span></b></u><br/>1. Totalling of marks is mandatory <br/>2. Mark column should not be left blank. <br/>3. Avoid omission, alteration of Register/Dummy Number/mark.</td></tr>
			</tbody>	
			</table>
		</div>
				
		</div>
	</div>
	
<?php 	
	echo $this->Html->script('common');
	echo $this->Html->script('common-front');	
	echo $this->Html->css('certificate');
?>