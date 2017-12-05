<?php 
echo $this->Html->css("degree");
$a=1;
if($result){

		echo $this->Html->css('course_completion_certificate');
		echo $this->Html->css('certificate');
		$html="";
		?>
		<?php $html.= '
		
		<table class="page" border="0" width="80%" height="1123px">
	  		<tr>
	  			<td colspan="3" style="width:100%;text-align:center;" height="170"></td>
			</tr>
	  		<tr>
				<td colspan="3" class="ccc4" style="height:100px;valign:top;">CERTIFICATE</td>
			</tr>
			<tr>
				<td colspan="3" align="center" style="height:170px;valign:top;">';
				$profileImage = $this->Html->image("phd_students/".str_replace("  "," ",str_replace("   "," ",$result[$regNo]['picture'])), ["alt" => h("profile.jpeg"),"style"=>"width:100px;border-radius:5px;margin:0px 0px 0px 0px;"]);
		  		//$profileImage = $this->Html->image("phd_students/".$result[$regNo]['picture']);
		    	$html.=$profileImage;
				$html.='</td>
			</tr>
			<tr>
	  			<td colspan="3" style="text-align:left;width:100%;padding-left:40px;">
	  				<table border="0" cellspacing="0" cellpadding="0">
		  				<tr>
				  			<td class="pdc3 lineheight" style="text-align:left;width:100%;font-size:18px;">
				  				<i>This is to certify that ';
				  				$html.= $result[$regNo]['name']; 
				  				$html.= ' a Phd Scholar with Reg. No. '; 
				  				$html.= $regNo;
				  				$html.=' has completed the following Course Work.</i>
				  			</td>
				  		</tr>
				  	</table>
				  </td>
			</tr>
			<tr>
				<td colspan="3" style="text-align:left;vertical-align:top;" class="pdc3">
				  	<table border="0" width="85%" align="center" cellpadding="0" cellspacing="0" class="cmainsubtable" >
						<tr>
							<th class="head1">Sl. No.</th>
							<th class="head1">COURSE CODE</th>
							<th class="head1">COURSE NAME</th>
							<th class="head1" style="padding-left:10px;padding-right:10px;">% OF MARKS</th>
							<th class="head1" style="padding-left:10px;padding-right:10px;">REMARK</th>
							<th class="head1">MONTH  & YEAR OF PASSING</th>
						</tr>';
						$courses = $result[$regNo]['courses'];
						$i=1;
						foreach ($courses as $key => $value) {
							$html.= '<tr>';
							$html.= '<td class="tbody" style="width:70px;">'.$i.'</td>';
							$html.= '<td class="tbody" style="width:100px;">'.$value['course_code'].'</td>';
							$html.= '<td class="tbody" style="width:200px;">'.$value['course_name'].'</td>';
							$html.= '<td class="tbody" style="width:50px;">'.$value['marks'].'</td>';
							$html.= '<td class="tbody">'.$value['status'].'</td>';
							$html.= '<td class="tbody" style="width:150px;">'.$this->Html->getMonthYearFromMonthYearId($value['month_year_id']).'</td>';
							$html.= '</tr>';
							$i++;
						}
	  				$html.= '</table>
	  			</td>
			</tr>	 
			<tr>
	  			<td colspan="3" width="100%">
	  				<table border="0" width="100%">
					  <tr>  	
					    <td align="right" v-align="bottom" style="padding-right:50px;height:100px;"><strong>';
						$html.=$this->Html->image("certificate_signature/".$getSignature[0]['Signature']['signature'], ["alt" => h("coe.jpg"),"style"=>"height:40px;width:100px;"]);	
						$html.="<br/>".$getSignature[0]['Signature']['name'];
						$html.="<br/>".$getSignature[0]['Signature']['role'];
						$html.='</strong></td>
					  </tr>
					</table>
	  			</td>
	  		</tr> 		
		  </table>';
		echo $html;

}
?>