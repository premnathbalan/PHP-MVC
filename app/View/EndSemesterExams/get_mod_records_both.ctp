<?php
//pr($courseDetails);
//pr($studentArray);
//die;
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
					<th>CAE</th>
					<th>ESE</th>
					<th>Total</th>
				</tr>
			</thead>
			<tbody>
				<?php
				$i=1;
				foreach ($finalArray as $cm_id => $eseArray) {
					foreach ($eseArray as $student_id => $arrayDetails) { //pr($arrayDetails);
						?>
						<tr class='gradeX'>
							<!--<td></td>-->
							<td><?php echo $arrayDetails['batch_period'];
								echo $this->Form->input('id', array('type'=> 'hidden', 'label'=>false, 'default'=>$arrayDetails['ese_id'], 'name' => 'data[EseMod][ese][]'));
								?>
							</td>
							<td><?php echo $arrayDetails['academic']; ?></td>
							<td><?php echo $arrayDetails['program'];?></td>
							<td><?php echo $arrayDetails['reg_num']." ".
						$this->Form->input('ese_marks', array('type'=> 'hidden', 'label'=>false, 'default'=>$arrayDetails['ese_marks'], 'name' => 'data[EseMod][ese_marks][]'))
						." ".
						$this->Form->input('cae_marks', array('type'=> 'hidden', 'label'=>false, 'default'=>$arrayDetails['cae_marks'], 'name' => 'data[EseMod][cae_marks][]'))
						." ".
						$this->Form->input('mMarks', array('type'=> 'hidden', 'label'=>false, 'default'=>$arrayDetails['mMarks'], 'name' => 'data[EseMod][mMarks][]'))
						;?></td>
							<td><?php echo $arrayDetails['name'];?></td>
							<td><?php echo $courseDetails[$cm_id]['course_code']." ".
							$this->Form->input('min_pass_marks', array('type'=> 'hidden', 'label'=>false, 'default'=>$courseDetails[$cm_id]['min_pass_mark'], 'name' => 'data[EseMod][min_pass_marks][]'))
							." ".
							$this->Form->input('min_ese_marks', array('type'=> 'hidden', 'label'=>false, 'default'=>$courseDetails[$cm_id]['min_ese_mark'], 'name' => 'data[EseMod][min_ese_marks][]'))
							;?></td>
							<td><?php echo $courseDetails[$cm_id]['course_name'];?></td>
							<td><?php echo $arrayDetails['cae_marks'];?></td>
							<td><?php echo $arrayDetails['ese_marks'];?></td>
							<td><?php echo $arrayDetails['total'];?></td>
						</tr>
						<?php $i++;
					}
				}
				?>
			</tbody>
			<!--<tfoot>
				<tr>
					<th></th>
					<th><input type="text" name="Batch" value="Batch" class="search_init" /></th>
					<th><input type="text" name="Program" value="Program" class="search_init" /></th>
					<th><input type="text" name="Specialisation" value="Specialisation" class="search_init" /></th>
					<th><input type="text" name="Reg.&nbsp;Number" value="Reg.&nbsp;Number" class="search_init" /></th>
					<th><input type="text" name="Name" value="Name" class="search_init" /></th>
					<th><input type="text" name="CourseCode" value="CourseCode" class="search_init" /></th>
					<th><input type="text" name="CourseName" value="CourseName" class="search_init" /></th>
					<th><input type="text" name="CAE" value="CAE" class="search_init" /></th>
					<th><input type="text" name="ESE" value="ESE" class="search_init" /></th>
					<th><input type="text" name="Total" value="Total" class="search_init" /></th>
				</tr>
			</tfoot>-->
		</table>
<?php echo $this->Html->script('common'); ?>

<span class='breadcrumb1'>
<span class='navbar-brand'><small>EXAMINATION <i class='ace-icon fa fa-angle-double-right'></i></small></span>
<?php echo $this->Html->link("<span class='navbar-brand'><small>ESE Moderation <i class='ace-icon fa fa-angle-double-right'></i></small></span>", array("controller"=>"EndSemesterExams",'action' => 'moderation'),array('data-placement'=>'left','escape' => false)); ?>
</span>