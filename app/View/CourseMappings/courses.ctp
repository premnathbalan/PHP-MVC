<?php
//pr($courses);
?>
	<div class='col-lg-12' style='float:left;width:100%;'>
	<?php //echo $this->Html->link('<i class="ace-icon fa fa-print fa-lg"></i>'.'PRINT',array("controller"=>"Students",'action'=>'beforeRevaluationSearch',$examMonth,$batchId,$Academic,$programId,$withheldType,$withheldVal,'PRINT', $revalMode),array('type'=>'submit','name'=>'PRINT','value'=>'PRINT','class'=>'btn','escape' => false,'style'=>'float:right;'));?>
	<?php echo $this->Html->link('<i class="ace-icon fa fa-file-excel-o"></i>'.'Excel',array("controller"=>"CourseMappings",'action'=>'courses',$batchId,$academicId,$programId,'Excel'),array('type'=>'submit','name'=>'Excel','value'=>'Excel','class'=>'btn','escape' => false,'style'=>'float:right;'));?>
	<div style='clear:both;'></div>
	</div>
	
	<table cellpadding="0" cellspacing="0" border="0" class="display" id="example" style="margin-top:10px;">
			<thead>
				<tr>
					<th>Semester</th>
					<th>&nbsp;&nbsp;Course&nbsp;Code&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>
					<th>Course&nbsp;Name</th>
					<th>Course&nbsp;Type</th>
					<th>Min&nbsp;CAE&nbsp;Pass&nbsp;Percent</th>
					<th>Max&nbsp;CAE&nbsp;Mark</th>
					<th>Min&nbsp;ESE&nbsp;Pass&nbsp;Percent</th>
					<th>Max&nbsp;ESE&nbsp;Mark</th>
					<th>Total&nbsp;Min&nbsp;Pass&nbsp;Percent</th>
					<th>Course&nbsp;Max&nbsp;Marks</th>
					<th>Max&nbsp;QP&nbsp;Mark</th>
				</tr>
			</thead>
			<tbody>
			<?php
			//pr($programArray);
			foreach ($courses as $semester => $value) {
				foreach ($value as $key => $array) {
				?>
				<tr class='gradeX'>
					<td><?php echo $semester;?></td>
					<td><?php echo $array['course_code'];?></td>
					<td><?php echo $array['course_name'];?></td>
					<td><?php echo $this->Html->getCourseType($array['course_type_id']);?></td>
					<td><?php echo $array['min_cae_mark'];?></td>
					<td><?php echo $array['max_cae_mark'];?></td>
					<td><?php echo $array['min_ese_mark'];?></td>
					<td><?php echo $array['max_ese_mark'];?></td>
					<td><?php echo $array['total_min_pass'];?></td>
					<td><?php echo $array['course_max_marks'];?></td>
					<td><?php echo $array['max_ese_qp_mark'];?></td>
				</tr>
				<?php
				}
			}
			?>	
			</tbody>
			<tfoot>
				<tr>
					<th><input type="text" name="Semester" value="Semester" class="search_init" /></th>
					<th><input type="text" name="&nbsp;&nbsp;Course&nbsp;Code&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;" value="&nbsp;&nbsp;Course&nbsp;Code&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;" class="search_init" /></th>
					<th><input type="text" name="Course&nbsp;Name" value="Course&nbsp;Name" class="search_init" /></th>
					<th><input type="text" name="Course&nbsp;Type" value="Course&nbsp;Type" class="search_init" /></th>
					<th><input type="text" name="Min&nbsp;CAE&nbsp;Mark" value="Min&nbsp;CAE&nbsp;Mark" class="search_init" /></th>
					<th><input type="text" name="Max&nbsp;CAE&nbsp;Mark" value="Max&nbsp;CAE&nbsp;Mark" class="search_init" /></th>
					<th><input type="text" name="Min&nbsp;ESE&nbsp;Mark" value="Min&nbsp;ESE&nbsp;Mark" class="search_init" /></th>
					<th><input type="text" name="Max&nbsp;ESE&nbsp;Mark" value="Max&nbsp;ESE&nbsp;Mark" class="search_init" /></th>
					<th><input type="text" name="Total&nbsp;Min&nbsp;Pass&nbsp;Percent" value="Total&nbsp;Min&nbsp;Pass&nbsp;Percent" class="search_init" /></th>
					<th><input type="text" name="Course&nbsp;Max&nbsp;Marks" value="Course&nbsp;Max&nbsp;Marks" class="search_init" /></th>
					<th><input type="text" name="Max&nbsp;QP&nbsp;Mark" value="Max&nbsp;QP&nbsp;Mark" class="search_init" /></th>
				</tr>
			</tfoot>
		</table>
		<?php echo $this->Html->script('common'); ?>
		
<script>leftMenuSelection('CaePracticals/practical');</script>

<span class='breadcrumb1'>
<span class='navbar-brand'><small>MARKS <i class='ace-icon fa fa-angle-double-right'></i></small></span>
<span class='navbar-brand'><small>Practical <i class='ace-icon fa fa-angle-double-right'></i></small></span>
<?php echo $this->Html->link("<span class='navbar-brand'><small> C.A.E <i class='ace-icon fa fa-angle-double-right'></i></small></span>", array("controller"=>"CaePracticals",'action' => 'practical'),array('data-placement'=>'left','escape' => false)); ?>
</span>