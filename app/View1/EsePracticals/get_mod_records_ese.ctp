<?php
//pr($result);
//pr($studentArray);
//pr($manipuatedResult);
//pr($courseDetails);
if ($mod_option == "ese") {
?>
<div class="col-sm-12 bgFrame1" style="margin-top:5px;">
			<div class="col-lg-4"></div>
			<div class="col-lg-1">
			<?php
				$optionsArray=array("1"=>"1","2"=>"2","3"=>"3","4"=>"4","5"=>"5","6"=>"6","7"=>"7","8"=>"8","9"=>"9","10"=>"10");
				//echo $this->Form->input('modOperator', array('type'=> 'select', 'options'=>array("plus"=>"+"), 'label'=>false));
?>
			</div>
			<div class="col-lg-2">		
				<?php //echo $this->Form->input('modMarks', array('type'=> 'select', 'empty'=>'----Select----', 'label'=>false, 'options' => $optionsArray, 'required'=>'required')); ?>
			</div>
			<div class="col-lg-2">
				<?php echo $this->Form->button('<i class="ace-icon fa fa-check bigger-110"></i>'.__('&nbsp;Moderate&nbsp;'),array('type'=>'submit','name'=>'submit','value'=>'submit','class'=>'btn')); ?>
			</div>
		</div>
	<table cellpadding="0" cellspacing="0" border="0" class="display" id="example" style="margin-top:10px;">
			<thead>
				<tr>
					<th>S.No.</th>
					<th>Reg.&nbsp;Number</th>
					<th>Student&nbsp;Name</th>
					<th>Course&nbsp;Code</th>
					<th>ESE</th>
				</tr>
			</thead>
			<tbody>
				<?php
				$i=1;
				foreach ($manipuatedResult as $course_code => $modResultArray) {
					foreach ($modResultArray as $key => $rArray) {
						$student_id = $rArray['student_id'];
						?>
						<tr class='gradeX'>
							<td><?php echo $i++." ".$this->Form->input('id', array('type'=> 'hidden', 'label'=>false, 'default'=>$rArray['id'], 'name' => 'data[PracticalMod]['.$mod_option.'][]'));?></td>
							<td><?php echo $studentArray[$student_id]['reg_num'];?></td>
							<td><?php echo $studentArray[$student_id]['name'];?></td>
							<td><?php echo $course_code." ".
							$this->Form->input('marks', array('type'=> 'hidden', 'label'=>false, 'default'=>$courseDetails[$course_code]['min_pass_mark'], 'name' => 'data[PracticalMod][min_pass_marks][]'))
							." ".
							$this->Form->input('marks', array('type'=> 'hidden', 'label'=>false, 'default'=>$courseDetails[$course_code]['min_ese_mark'], 'name' => 'data[PracticalMod][min_ese_marks][]'));?></td>
							<td><?php echo $rArray['marks']." ".
							$this->Form->input('marks', array('type'=> 'hidden', 'label'=>false, 'default'=>$rArray['marks'], 'name' => 'data[PracticalMod][marks][]'))
							." ".
							$this->Form->input('mMarks', array('type'=> 'hidden', 'label'=>false, 'default'=>$rArray['mMarks'], 'name' => 'data[PracticalMod][mMarks][]'))
							;?></td>
						</tr>
						<?php
					}
				}
				?>
			</tbody>
			<tfoot>
				<tr>
					<th></th>
					<th><input type="text" name="Reg.&nbsp;Number" value="Reg.&nbsp;Number" class="search_init" /></th>
					<th><input type="text" name="Name" value="Name" class="search_init" /></th>
					<th><input type="text" name="CourseCode" value="CourseCode" class="search_init" /></th>
					<th><input type="text" name="Marks" value="Marks" class="search_init" /></th>
				</tr>
			</tfoot>
		</table>
<?php
}
?>
<?php echo $this->Html->script('common'); ?>