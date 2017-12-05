	<?php if($results){ if($printMode != 'PRINT'){?>	
	<div class='col-lg-12' style='float:left;width:100%;'>
	<?php echo $this->Html->link('<i class="ace-icon fa fa-file-excel-o"></i>'.'Excel',array("controller"=>"Students",'action'=>'cgpaList',$batchId,$Academic,$programId,$monthYearId,'-','-','Dept Excel'),array('type'=>'submit','name'=>'Excel','value'=>'Excel','class'=>'btn','escape' => false,'style'=>'float:right;'));?>
	<div style='clear:both;'></div>
	</div>
	<?php }?>
	<?php
	//echo $totalCourses;
	?>
	<table cellpadding='0' cellspacing='0' border='1' class='display' id='example' style='margin-top:10px;'>
		<thead>
			<tr>
				<th style='padding:0px;'>Reg.&nbsp;No.</th>
				<th style='padding:0px;'>Student&nbsp;Name&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>	
				<th style='padding:0px;'>1&nbsp;&nbsp;</th>
				<th style='padding:0px;'>2&nbsp;&nbsp;</th>
				<th style='padding:0px;'>3&nbsp;&nbsp;</th>
				<th style='padding:0px;'>4&nbsp;&nbsp;</th>
				<th style='padding:0px;'>5&nbsp;&nbsp;</th>
				<th style='padding:0px;'>6&nbsp;&nbsp;</th>
				<th style='padding:0px;'>7&nbsp;&nbsp;</th>
				<th style='padding:0px;'>8&nbsp;&nbsp;</th>
				<th style='padding:0px;'>9&nbsp;&nbsp;</th>
				<th style='padding:0px;'>10&nbsp;&nbsp;</th>
				<th style='padding:0px;'>Pgm<br/>CR</th>
				<th style='padding:0px;'>CR <br/>Regd</th>
				<th style='padding:0px;'>CR <br/>Ear</th>
				<th style='padding:0px;'>GP <br/>Ear</th>
				<th style='padding:0px;'>No&nbsp;of</br>Arrears</th>
				<th style='padding:0px;'>First</br>Attempt</th>
				<th style='padding:0px;'>CGPA</th>
				<th style='padding:0px;'>Status</th>
				
			</tr>
		</thead>
		<tbody>
		<?php
		foreach ($finalArray as $student_id => $result) { //pr($result);
			echo "<tr>";
			echo "<td align='center'>".$result['reg_num']."</td>";
			echo "<td align='center'>".$result['name']."</td>";
			echo "<td align='center'>".$result['semGpa']['semester1Gpa']."</td>";
			echo "<td align='center'>".$result['semGpa']['semester2Gpa']."</td>";
			echo "<td align='center'>".$result['semGpa']['semester3Gpa']."</td>";
			echo "<td align='center'>".$result['semGpa']['semester4Gpa']."</td>";
			echo "<td align='center'>".$result['semGpa']['semester5Gpa']."</td>";
			echo "<td align='center'>".$result['semGpa']['semester6Gpa']."</td>";
			echo "<td align='center'>".$result['semGpa']['semester7Gpa']."</td>";
			echo "<td align='center'>".$result['semGpa']['semester8Gpa']."</td>";
			echo "<td align='center'>".$result['semGpa']['semester9Gpa']."</td>";
			echo "<td align='center'>".$result['semGpa']['semester10Gpa']."</td>";
			echo "<td align='center'>".$result['program_credit']."</td>";
			echo "<td align='center'>".$result['credits_reg']."</td>";
			echo "<td align='center'>".$result['credits_earned']."</td>";
			echo "<td align='center'>".$result['grade_point_earned']."</td>";
			echo "<td align='center'>".$result['arrears']."</td>";
			echo "<td align='center'>".$result['first_attempt']."</td>";
			echo "<td align='center'>".$result['cgpa']."</td>";
			echo "<td align='center'>".$result['status']."</td>";
			echo "</tr>";
		}
		?>		
	</tbody>
	<tfoot>
		<tr>
			<th><input type="text" name="Reg.&nbsp;No." value="Reg.&nbsp;No." class="search_init" /></th>
			<th><input type="text" name="Student&nbsp;Name&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;" value="Student&nbsp;Name&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;" class="search_init" /></th>	
			<th><input type="text" name="1" value="1" class="search_init" /></th>
			<th><input type="text" name="2" value="2" class="search_init" /></th>
			<th><input type="text" name="3" value="3" class="search_init" /></th>
			<th><input type="text" name="4" value="4" class="search_init" /></th>
			<th><input type="text" name="5" value="5" class="search_init" /></th>
			<th><input type="text" name="6" value="6" class="search_init" /></th>
			<th><input type="text" name="7" value="7" class="search_init" /></th>
			<th><input type="text" name="8" value="8" class="search_init" /></th>
			<th><input type="text" name="9" value="9" class="search_init" /></th>
			<th><input type="text" name="10" value="10" class="search_init" /></th>
			<th><input type="text" name="Pgm<br/>CR" value="Pgm CR" class="search_init" /></th>
			<th><input type="text" name="CR <br/>Regd" value="CR Regd" class="search_init" /></th>
			<th><input type="text" name="CR <br/>Ear" value="CR Ear" class="search_init" /></th>
			<th><input type="text" name="GP <br/>Ear" value="GP Ear" class="search_init" /></th>
			<th><input type="text" name="No&nbsp;of</br>Arrears" value="No of Arrears" class="search_init" /></th>
			<th><input type="text" name="First</br>Attempt" value="First Attempt" class="search_init" /></th>
			<th><input type="text" name="CGPA" value="CGPA" class="search_init" /></th>
			<th><input type="text" name="Status" value="Status" class="search_init" /></th>
			
		</tr>
	</tfoot>
	<?php echo $this->Html->script('common');?>
</table>

<span class='breadcrumb1'>
<span class='navbar-brand'><small>REPORTS <i class='ace-icon fa fa-angle-double-right'></i></small></span>
<?php echo $this->Html->link("<span class='navbar-brand'><small>CGPA Report <i class='ace-icon fa fa-angle-double-right'></i></small></span>", array("controller"=>"Students",'action' => 'cgpa'),array('data-placement'=>'left','escape' => false)); ?>
</span>
<?php } else{?>RECORD NOT FOUND<?php }?>