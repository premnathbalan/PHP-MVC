	<!-- POUP UP WINDOW -->
	<div id="js-load-forms"></div>
	<?php if($foilCards == 'P-F'){ ?>
	<div class="col-sm-12"><div class="col-sm-2"></div>
	<div class="searchFrm col-sm-8 bgFrame1" style="margin-top:10px;text-align:center;">
		
		<select name="courseFoilCards" id="courseFoilCards" style="width:350px;">
			<option value="">-Select Course-</option>
			<?php  $sno =1; foreach ($courseMappings as $courseMapping):
			//if ($courseMapping['Course']['course_type_id'] == $course_type_id) {
			if (in_array($courseMapping['Course']['course_type_id'], $course_type_id)) {
			?>
			<option value="<?php echo $courseMapping['CourseMapping']['id'].'/'.$courseMapping['CourseMapping']['course_mode_id'].'/'.$courseMapping['Program']['id'];?>"><?php echo $courseMapping['Course']['course_code']." - ".$courseMapping['Course']['course_name'];?></option>
			<?php $sno++; 
			}
			endforeach; ?>
		<select>
		<?php echo $this->Form->button('<i class="ace-icon fa fa-print fa-lg"></i>'.__('ATTENDANCE SHEET'),array('type'=>'button','name'=>'ATTENDANCE SHEET','value'=>'ATTENDANCE SHEET','class'=>'btn','onclick'=>"courseFoilCardPrint('P');"));?>
		<?php echo $this->Form->button('<i class="ace-icon fa fa-print fa-lg"></i>'.__('FOIL CARD'),array('type'=>'button','name'=>'FOIL CARD','value'=>'FOIL CARD','class'=>'btn','onclick'=>"courseFoilCardPrint('F');"));?>
	</div>
	</div>
	
	<span class='breadcrumb1'>
	<span class='navbar-brand'><small>MARKS <i class='ace-icon fa fa-angle-double-right'></i></small></span>
	<?php echo $this->Html->link("<span class='navbar-brand'><small>ATTENDANCE SHEET & FOIL CARD <i class='ace-icon fa fa-angle-double-right'></i></small></span>", array("controller"=>"Attendances",'action' => 'attendance_foil_cards'),array('data-placement'=>'left','escape' => false)); ?>
	</span>

	<?php }else{?>
	<table cellpadding="0" cellspacing="0" border="0" class="display" id="example" style="margin-top:10px;">
	<thead>
	<tr>
		<th>S.No.</th>
		<th>Course Code</th>
		<th>Course</th>
		<th>Program</th>
		<th>Batch</th>			
		<th>Month&nbsp;Year</th>
		<th>Status</th>
		<th class="actions"><?php echo __('Attendance'); ?></th>
	</tr>
	</thead>

	<tbody>
	<?php
	 $sno =1; foreach ($courseMappings as $courseMapping): 
	
	if (in_array($courseMapping['Course']['course_type_id'], $course_type_id)) {
	?>
	<tr class="gradeX">
		<td><?php echo $sno; ?></td>
		<td>
			<?php echo $courseMapping['Course']['course_code']; ?>
		</td>
		<td>
			<?php echo $courseMapping['Course']['course_name']; ?>
		</td>
		<td>
			<?php echo $courseMapping['Program']['program_name']; ?>
		</td>
		<td>
			<?php echo $courseMapping['Batch']['batch_from']."-".$courseMapping['Batch']['batch_to']; ?>
		</td>		
		<td>
			<?php echo $txtMonthYears; ?>
		</td>
		<td align='center'>
			<?php if(isset($courseMapping['Attendance'])){echo "<span class='ovelShapBg1'>COMPLETED</span>";$typeMode ='Edit';}else{ echo "<span class='ovelShapBg2'>OPEN</span>";$typeMode ='Add';} ?>
		</td>		
		<td class="actions"> 
			<?php echo $this->Html->link("<i class='fa fa-building-o'> Entry</i>",array("controller"=>"Attendances",'action' => 'attendance_entry',$typeMode,$type,$courseMapping['CourseMapping']['id'],$courseMapping['CourseMapping']['course_mode_id'],$courseMapping['Program']['id'],$courseMapping['Batch']['id'],$MonthYears,$txtMonthYears),array('escape' => false,'class'=>'btn'));	?> 			
		</td>
	</tr>
<?php $sno++; } endforeach; ?>
	</tbody>
	<tfoot>
		<tr>
			<th><input type="text" name="S.No." value="S.No." class="search_init" /></th>
			<th><input type="text" name="Course Code" value="Course Code" class="search_init" /></th>
			<th><input type="text" name="Course" value="Course" class="search_init" /></th>
			<th><input type="text" name="Program" value="Program" class="search_init" /></th>
			<th><input type="text" name="Batch" value="Batch" class="search_init" /></th>
			<th><input type="text" name="Month Year" value="Month Year" class="search_init" /></th>
			<th><input type="text" name="" value="" class="search_init" /></th>
			<th></th>
		</tr>
	</tfoot>
	</table>
	
<?php if($type == 'G'){ $varAtten = "GROSS ";$varParam = "G";} else{ $varAtten ="COURSE ";$varParam = "C";}?>  
<span class='breadcrumb1'>
<span class='navbar-brand'><small>MARKS <i class='ace-icon fa fa-angle-double-right'></i></small></span>
<span class='navbar-brand'><small>C.A.E <i class='ace-icon fa fa-angle-double-right'></i></small></span>
<?php echo $this->Html->link("<span class='navbar-brand'><small>$varAtten ATTENDANCE <i class='ace-icon fa fa-angle-double-right'></i></small></span>", array("controller"=>"Attendances",'action' => 'index',$varParam),array('data-placement'=>'left','escape' => false)); ?>
</span>
		
<?php }?>

<?php 
echo $this->Html->script('common');
echo $this->Html->script('common-front');
?>