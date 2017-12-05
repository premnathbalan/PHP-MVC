<?php if($degreeResult){ if($mode != 'PRINT'){?>	
	<div class='col-lg-12' style='float:left;width:100%;'>
	<?php echo $this->Html->link('<i class="ace-icon fa fa-file-excel-o"></i>'.'Excel',array("controller"=>"Students",'action'=>'dcReportSearch',$batch_id,$academic_id,$program_id,'EXCEL'),array('type'=>'submit','name'=>'Excel','value'=>'Excel','class'=>'btn','escape' => false,'style'=>'float:right;'));?>
	<div style='clear:both;'></div>
	</div>
<?php }?>
	
<table cellpadding='0' cellspacing='0' border='1' class='display' id='example' style='margin-top:10px;'>
	<thead>
		<tr>
			<th style='padding:0px;'>Reg.&nbsp;No.</th>
			<th style='padding:0px;'>Student&nbsp;Name&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>
			<th style='padding:0px;'>Program<br/>CR</th>
			<th style='padding:0px;'>Degree<br/>Status</th>
			<th style='padding:0px;'>Class<br/>classification</th>
			<!--<th style='padding:0px;'>Credits&nbsp;Regd<br/>so&nbsp;far</th>-->		
			<th style='padding:0px;'>CGPA</th>
			<th style='padding:0px;'>CR <br/>Regd</th>
			<th style='padding:0px;'>CR <br/>Ear</th>
			<th style='padding:0px;'>GP <br/>Ear</th>
			<th style='padding:0px;'>No of Arrears</th>
			<th style='padding:0px;'>ABS</th>
			<th style='padding:0px;'>With<br/>drawal</th>
			<th style='padding:0px;'>With<br/>held</th>
			<th style='padding:0px;'>Audit<br/>Course</th>
		</tr>
	</thead>
	<tbody>
	<?php
		foreach ($degreeResult as $student_id => $result) {
			echo "<tr>";
			echo "<td align='center'>".$result['reg_num']."</td>";
			echo "<td align='center'>".$result['name']."</td>";
			echo "<td align='center'>".$result['program_credit']."</td>";
			echo "<td align='center'>".$result['status']."</td>";
			if ($result['no_of_arrears'] == 0 && $result['current_credit_regd']>=$result['program_credit']) echo "<td align='center'>".$result['class_classification']."</td>";
			else echo "<td align='center'></td>";
			//echo "<td align='center'>".$result['current_credit_regd']."</td>";
			echo "<td align='center'>".$result['cgpa']."</td>";
			echo "<td align='center'>".$result['current_credit_regd']."</td>";
			echo "<td align='center'>".$result['credits_earned']."</td>";
			echo "<td align='center'>".$result['grades_earned']."</td>";
			$arrear_status = "";
			if ($result['no_of_arrears'] != 0) $arrear_status = $result['no_of_arrears'];
			echo "<td align='center'>".$arrear_status."</td>";
			$abs = "";
			$withdrawal = "";
			$withheld = "";
			$audit_course = "";
			if ($result['abs'] == 1) $abs = "Y"; 
			if ($result['withdrawal'] == 1) $withdrawal = "Y";
			if ($result['withheld'] == 1) $withheld = "Y";
			if ($result['audit_course'] == 1) $audit_course = "Y";
			echo "<td align='center'>".$abs."</td>";
			echo "<td align='center'>".$withdrawal."</td>";
			echo "<td align='center'>".$withheld."</td>";
			echo "<td align='center'>".$audit_course."</td>";
			echo "</tr>";
		}
	?>
	</tbody>
	<tfoot>
		<tr>
			<th><input type="text" name="Reg.&nbsp;No." value="Reg.&nbsp;No." class="search_init" /></th>
			<th><input type="text" name="Student&nbsp;Name&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;" value="Student&nbsp;Name&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;" class="search_init" /></th>
			<th><input type="text" name="Program <br/>CR" value="Program CR" class="search_init" /></th>
			<th><input type="text" name="Degree <br/>Status" value="Degree Status" class="search_init" /></th>	
			<th><input type="text" name="Class <br/>classification" value="Class classification" class="search_init" /></th>	
			<!--<th><input type="text" name="Credits&nbsp;Regd<br/>so&nbsp;far" value="Credits Regd so far" class="search_init" /></th>-->
			<th><input type="text" name="CGPA" value="CGPA" class="search_init" /></th>
			<th><input type="text" name="CR <br/>Rec" value="CR Rec" class="search_init" /></th>
			<th><input type="text" name="CR <br/>Ear" value="CR Ear" class="search_init" /></th>
			<th><input type="text" name="GP <br/>Ear" value="CR Rec" class="search_init" /></th>
			<th><input type="text" name="No of Arrears" value="No of Arrears" class="search_init" /></th>
			<th><input type="text" name="ABS" value="ABS" class="search_init" /></th>
			<th><input type="text" name="With<br/>drawal" value="Withdrawal" class="search_init" /></th>
			<th><input type="text" name="With<br/>held" value="Withheld" class="search_init" /></th>
			<th><input type="text" name="Audit<br/>Course" value="AuditCourse" class="search_init" /></th>
		</tr>
	</tfoot>
	<?php echo $this->Html->script('common');?>
</table>

<span class='breadcrumb1'>
<span class='navbar-brand'><small>REPORTS <i class='ace-icon fa fa-angle-double-right'></i></small></span>
<?php echo $this->Html->link("<span class='navbar-brand'><small>Degree Certificate Report <i class='ace-icon fa fa-angle-double-right'></i></small></span>", array("controller"=>"Students",'action' => 'dcReport'),array('data-placement'=>'left','escape' => false)); ?>
</span>
<?php } else{?>RECORD NOT FOUND<?php }?>