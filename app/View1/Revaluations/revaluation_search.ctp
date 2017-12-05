<?php
//pr($results);
$studentMark = $results[0]['EndSemesterExam'];
//pr($studentMark);
//die;
?>
<?php
echo $this->Form->input('student_id', array('type'=>'hidden', 'label' => false, 'default' => $results[0]['Student']['id'], 'name'=>'data[Revaluation][StudentId]'));
echo "</br>";
?>
<div class="searchFrm bgFrame1">
		<div class="col-sm-12">
			<div class="col-lg-4">		
				<?php echo "<strong>Batch</strong> : ". $results[0]['Batch']['batch_from']."-".$results[0]['Batch']['batch_to']; if($results[0]['Batch']['academic'] == 'JUN'){ echo " [A]";} ?>
			</div>
			<div class="col-lg-4">		
				<?php echo "<strong>Program</strong> : ". $results[0]['Program']['program_name']; ?>
			</div>
			<div class="col-lg-4">			
				<?php echo "<strong>Academic</strong> : ". $results[0]['Academic']['academic_name']; ?>
			</div>
		</div>
		<div class="col-sm-12">
			<div class="col-lg-6">		
				<?php echo "<strong>Name</strong> : ". $results[0]['Student']['name']; ?>
			</div>
			<div class="col-lg-12" align="right">
				<?php echo $this->Form->button('<i class="ace-icon fa fa-check bigger-110"></i>'.__('Submit'),array('type'=>'submit','name'=>'submit','id'=>'submit','value'=>'list','class'=>'btn'));?>
			</div>
		</div>
	</div>	
</div>
<?php
echo "</br>";
?>
<div style="clear:both;"></div>
<table cellpadding="0" cellspacing="0" border="0" class="display" id="example">
	<thead>
		<tr>
			<th>Course&nbsp;Name</th>
			<th>Course&nbsp;Type</th>
			<th>Course&nbsp;Code</th>
			<th>Marks</th>
			<th>Apply&nbsp;Revaluation</th>
		</tr>
	</thead>
	<tbody>
		<?php $i = 0;foreach ($studentMark as $result):
		$status = $results[0]['StudentMark'];
		//pr($status);
		foreach ($status as $key => $resStatus) {
			if ($resStatus['course_mapping_id'] == $result['course_mapping_id']) {
				//echo $result['course_mapping_id']." ".$resStatus['status']."</br>";
				$status = $resStatus['status'];
				break;
			}
		}
		?>
		<tr class=" gradeX">
			<td><?php echo h($result['CourseMapping']['Course']['course_name']); ?></td>
			<td><?php echo h($result['CourseMapping']['Course']['CourseType']['course_type']); ?></td>
			<td><?php echo h($result['CourseMapping']['Course']['course_code']); ?></td>
			<td><?php echo h($result['marks'])
			." ".
			$this->Form->input('cm_id', array('label' => false, 'default' => $result['marks'], 'name'=>'data[Revaluation][CourseMapping]['.$result['course_mapping_id'].']', 'type' => 'hidden')) 
			." ".
			$this->Form->input('status', array('label' => false, 'default' => $status, 'name'=>'data[Revaluation][Status]['.$result['course_mapping_id'].']', 'type' => 'hidden')) 
			." ".
			$this->Form->input('marks', array('type'=>'checkbox', 'label' => false, 'default' => $result['marks'], 'name'=>'data[Revaluation][Revaluation]['.$result['course_mapping_id'].']', 'type' => 'hidden'))
			; ?>
			</td>
			<td>
				<?php
				if($result['revaluation_status'] == 1) $chkStatus="checked";
				else $chkStatus="";
				?>
				<?php echo $this->Form->input('checkbox'.$i, array('type'=>'checkbox','label'=>false, 'style'=>'margin-top:-15px;', 'name'=>'data[Revaluation][Revaluation]['.$result['course_mapping_id'].']', $chkStatus)); ?>
			</td>
		</tr>
		  <?php endforeach; ?>
	</tbody>
	<tfoot>
		<tr>
			<th><input type="text" name="Course&nbsp;Name" value="Course&nbsp;Name" class="search_init" /></th>
			<th><input type="text" name="Course&nbsp;Type" value="Course&nbsp;Type" class="search_init" /></th>
			<th><input type="text" name="Course&nbsp;Code" value="Course&nbsp;Code" class="search_init" /></th>
			<th><input type="text" name="Marks" value="Marks" class="search_init" /></th>
			<th></th>
		</tr>
	</tfoot>
</table>
<?php echo $this->Html->script('common'); ?>

<span class='breadcrumb1'>
<span class='navbar-brand'><small>REVALUATION <i class='ace-icon fa fa-angle-double-right'></i></small></span>
<?php echo $this->Html->link("<span class='navbar-brand'><small>Single Entry <i class='ace-icon fa fa-angle-double-right'></i></small></span>", array("controller"=>"Revaluations",'action' => 'revaluation'),array('data-placement'=>'left','escape' => false)); ?>
</span>