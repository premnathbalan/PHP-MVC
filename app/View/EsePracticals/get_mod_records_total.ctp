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
					<th>Course</th>
					<th>CAE</th>
					<th>ESE</th>
					<th>Total</th>
				</tr>
			</thead>
			<tbody> 
				<?php
				$i=1;
				foreach ($total as $course_code => $totalArray) {
					foreach ($totalArray as $student_id => $tArray) {
					?>
					<tr class='gradeX'>
						<td><?php echo $i." ".$this->Form->input('id', array('type'=> 'hidden', 'label'=>false, 'default'=>$tArray['practical_id'], 'name' => 'data[PracticalMod][total][]'));?></td>
						<td><?php echo $studentArray[$student_id]['reg_num']." ".
						$this->Form->input('marks', array('type'=> 'hidden', 'label'=>false, 'default'=>$tArray['ese_marks'], 'name' => 'data[PracticalMod][ese_marks][]'))
						." ".
						$this->Form->input('marks', array('type'=> 'hidden', 'label'=>false, 'default'=>$tArray['cae_marks'], 'name' => 'data[PracticalMod][cae_marks][]'))
						." ".
						$this->Form->input('mMarks', array('type'=> 'hidden', 'label'=>false, 'default'=>$tArray['mMarks'], 'name' => 'data[PracticalMod][mMarks][]'))
						;?></td>
						<td><?php echo $studentArray[$student_id]['name']." ".$this->Form->input('marks', array('type'=> 'hidden', 'label'=>false, 'default'=>$student_id, 'name' => 'data[PracticalMod][student_id][]'));?></td>
						<td><?php echo $course_code." ".
							$this->Form->input('marks', array('type'=> 'hidden', 'label'=>false, 'default'=>$courseDetails[$course_code]['min_pass_mark'], 'name' => 'data[PracticalMod][min_pass_marks][]'))
							." ".
							$this->Form->input('marks', array('type'=> 'hidden', 'label'=>false, 'default'=>$courseDetails[$course_code]['min_ese_mark'], 'name' => 'data[PracticalMod][min_ese_marks][]'))
							
							;?></td>
						<td><?php echo $tArray['cae_marks'];?></td>
						<td><?php echo $tArray['ese_marks'];?></td>
						<td><?php echo $tArray['total'];?></td>
					</tr>
					<?php
					$i++;
					}
				}
				?>
			</tbody>
			<tfoot>
				<tr>
					<th></th>
					<th><input type="text" name="Reg.&nbsp;Number" value="Reg.&nbsp;Number" class="search_init" /></th>
					<th><input type="text" name="Name" value="Name" class="search_init" /></th>
					<th><input type="text" name="Course" value="Course" class="search_init" /></th>
					<th><input type="text" name="CAE" value="CAE" class="search_init" /></th>
					<th><input type="text" name="Marks" value="Marks" class="search_init" /></th>
					<th><input type="text" name="Total" value="Total" class="search_init" /></th>
				</tr>
			</tfoot>
		</table>
<?php echo $this->Html->script('common'); ?>
<span class='breadcrumb1'>
<span class='navbar-brand'><small>MARKS <i class='ace-icon fa fa-angle-double-right'></i></small></span>
<span class='navbar-brand'><small>Practical <i class='ace-icon fa fa-angle-double-right'></i></small></span>
<?php echo $this->Html->link("<span class='navbar-brand'><small> Moderation <i class='ace-icon fa fa-angle-double-right'></i></small></span>", array("controller"=>"EsePracticals",'action' => 'moderate'),array('data-placement'=>'left','escape' => false)); ?>
</span>