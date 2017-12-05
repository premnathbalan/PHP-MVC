<?php
//pr($results); die;
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
					<!--<th>S.No.</th>-->
					<th>Batch</th>
					<th>Program</th>
					<th>Specialisation</th>
					<th>Reg.&nbsp;Number</th>
					<th>Student&nbsp;Name</th>
					<th>Course&nbsp;Code</th>
					<th>Course&nbsp;Name</th>
					<th>ESE</th>
				</tr>
			</thead>
			<tbody>
				<?php
				$i=1;
				foreach ($results as $key => $result) { //pr($result);
				//$eseResults = $result['EndSemesterExam'];
					//foreach ($eseResults as $key => $eseArray) {
						$student_id = $result['EndSemesterExam']['student_id'];
						?>
						<tr class='gradeX'>
							<!--<td></td>-->
							<td><?php echo $this->Form->input('id', array('type'=> 'hidden', 'label'=>false, 'default'=>$result['EndSemesterExam']['id'], 'name' => 'data[EseMod][ese][]')); ?>
								<?php echo $result['Student']['Batch']['batch_period']; ?>
							</td>
							<td><?php echo $result['Student']['Program']['Academic']['academic_name'];?></td>
							<td><?php echo $result['Student']['Program']['program_name'];?></td>
							<td><?php echo $result['Student']['registration_number'];?></td>
							<td><?php echo $result['Student']['name'];?></td>
							<td><?php echo $courseDetails[$result['EndSemesterExam']['course_mapping_id']]['course_code'];?></td>
							<td><?php echo $courseDetails[$result['EndSemesterExam']['course_mapping_id']]['course_name'];?></td>
							<td><?php 
									$min_ese_pass_mark = round($courseDetails[$result['EndSemesterExam']['course_mapping_id']]['max_ese_mark'] * $courseDetails[$result['EndSemesterExam']['course_mapping_id']]['min_ese_mark'] / 100);
									echo $result['EndSemesterExam']['marks']." ".
									$this->Form->input('min_ese_pass_mark', array('type'=> 'hidden', 'label'=>false, 'default'=>$min_ese_pass_mark, 'name' => 'data[EseMod][min_ese_pass_mark]['.$result['EndSemesterExam']['id'].']'))." ".
									$this->Form->input('actual_mark', array('type'=> 'hidden', 'label'=>false, 'default'=>$result['EndSemesterExam']['marks'], 'name' => 'data[EseMod][actual_mark]['.$result['EndSemesterExam']['id'].']'))
									;
									?>
							</td>
						</tr>
						<?php $i++;
					//}
				}
				?>
			</tbody>
			<!--<tfoot>
				<tr>
					<th></th>
					<th><input type="text" name="Reg.&nbsp;Number" value="Reg.&nbsp;Number" class="search_init" /></th>
					<th><input type="text" name="Name" value="Name" class="search_init" /></th>
					<th><input type="text" name="CourseCode" value="CourseCode" class="search_init" /></th>
					<th><input type="text" name="Marks" value="Marks" class="search_init" /></th>
				</tr>
			</tfoot>-->
		</table>
<?php echo $this->Html->script('common'); ?>

<span class='breadcrumb1'>
<span class='navbar-brand'><small>EXAMINATION <i class='ace-icon fa fa-angle-double-right'></i></small></span>
<?php echo $this->Html->link("<span class='navbar-brand'><small>ESE Moderation <i class='ace-icon fa fa-angle-double-right'></i></small></span>", array("controller"=>"EndSemesterExams",'action' => 'moderation'),array('data-placement'=>'left','escape' => false)); ?>
</span>