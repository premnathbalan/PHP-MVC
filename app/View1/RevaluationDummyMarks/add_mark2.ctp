<div><?php $level = 2;	if($results){
//pr($results);
//die;
?>
	<div class="bgFrame1">
		<div class="col-lg-12">
			<div class="col-lg-2"><b>Course Code : </b><?php echo "</br>".$courseDetails['Timetable']['CourseMapping']['Course']['course_code'];?></div>
			<div class="col-lg-3"><b>Course Name : </b><?php echo "</br>".$courseDetails['Timetable']['CourseMapping']['Course']['course_name'];?></div>
			<div class="col-lg-2"><b>Max QP Marks : </b><?php $max_ese_qp_mark = $courseDetails['Timetable']['CourseMapping']['Course']['max_ese_qp_mark'];?>
			<input type="text" size="2" maxlength="3" id="max_ese_qp_mark" value="<?php echo $max_ese_qp_mark;?>" readonly="1" />
			</div>
			<div class="col-lg-3"><b>Dummy Number Range : </b><?php echo "</br>".$courseDetails['DummyNumber']['start_range']." to ".$courseDetails['DummyNumber']['end_range']?></div>
			<div class="col-lg-2"><b>Month Year : </b><?php echo "</br>".$month_year;?></div>
		</div>
	</div>		
<table cellpadding="0" cellspacing="0" border="0" class="display" id="example" style="margin-top:5px;">
	<thead>
		<?php
		$p =1; $packetMark = "";
		for($i=1;$i<=count($results);$i++){
			$autoGenId = "";$assignedMark = ""; 
			if(isset($DNMassignedValue[$i]['id'])){
				$assignedMark 	= $DNMassignedValue[$i]['mark_entry'.$level];
				$autoGenId		= $DNMassignedValue[$i]['id'];
			}
		?>
		<tr>
			<th>Packet No.</th>
			<th>Cumulative Mark</th>
			<th>Dummy No.</th>
			<th>Marks (Out of <?php echo $max_ese_qp_mark;?>)</th>
		</tr>
	</thead>
	<tbody>
		<?php
		$i=1; $p=1;
		foreach ($results as $key => $eseArray) {
		$assignedMark = "";
		$revaluationId = $eseArray['Revaluation']['id'];
		if (isset($eseArray['Revaluation']['RevaluationDummyMark'][0])) {
			if ($eseArray['Revaluation']['RevaluationDummyMark'][0]['mark_entry'.$level]) {
				$assignedMark = $eseArray['Revaluation']['RevaluationDummyMark'][0]['mark_entry'.$level];
			}
			$autoGenId		= $eseArray['Revaluation']['RevaluationDummyMark'][0]['id'];
		}
		?>
		<tr class=" gradeX">
			<td>
				<?php
				if(($p%20) == 1 && $p >20){				
					$packetMark = $assignedMark;
				}else{$packetMark = $packetMark + $assignedMark;}
				
				if($p <=20){ 
					echo "1";
				}else{
					echo ceil(($p/20));
				}
				?>
			</td>
			<td id="packetMark<?php echo $p;?>"><?php echo $packetMark;?></td>
			<td><?php echo $eseArray['EndSemesterExam']['dummy_number'];?></td>
			<td><?php  				
				echo $this->Form->input('revaluationId'.$p, array('type'=>'hidden','id'=>'revId'.$i,'label'=>false,'default'=>$revaluationId));
				echo $this->Form->input('autoGenId'.$p, array('type'=>'hidden','label'=>false,'default'=>$autoGenId));
				echo $this->Form->input('DNId'.$p, array('type'=>'hidden','label'=>false,'default'=>$dummy_number_id));
				echo $this->Form->input('DN'.$p, array('type'=>'hidden','label'=>false,'default'=>$eseArray['EndSemesterExam']['dummy_number']));			
				echo $this->Form->input('mark', array('type'=>'text','class'=>'dummy','id'=>'DNM'.$i,'label'=>false,'required'=>'required','maxlength'=>3,'default'=>$assignedMark, 'name'=>'data[RevaluationDummyMarks][mark1][]','onblur'=>"store($p,this.value,$level, $revaluationId)"));
			?>
			<span id="msg<?php echo $p;?>"></span>
			</td>			
		</tr>
		<?php
		$i++;
		$p = $p+1;
		}
		?>
		<?php } ?>
		 
	</tbody>
	<tfoot>
		<tr>
			<th><input type="text" name="Packet No." value="Packet No." class="search_init" /></th>
			<th><input type="text" name="Cumulative Mark" value="Cumulative Mark" class="search_init" /></th>
			<th><input type="text" name="Dummy No." value="Dummy No. Range" class="search_init" /></th>
			<th></th>
		</tr>
	</tfoot>	
	<input type="hidden" id="maxRow" name="maxRow" value='<?php echo count($results); ?>' />
</table>

	<?php echo $this->Form->button('<i class="ace-icon fa fa-check bigger-110"></i>'.__('Confirm'),array('type'=>'button','name'=>'Confirm','value'=>'Confirm','class'=>'btn',"onclick"=>"return confirmRDN();"));?>
	<?php echo $this->Html->script('common');?>
	
<script>leftMenuSelection('RevaluationDummyMarks/marks');</script>
<?php }else{ echo "Record Not Found...";} ?>
<span class='breadcrumb1'>
<span class='navbar-brand'><small>REVALUATION <i class='ace-icon fa fa-angle-double-right'></i></small></span>
<span><?php echo $this->Html->link("<span class='navbar-brand'><small>Dummy Marks <i class='ace-icon fa fa-angle-double-right'></i></small></span>", array("controller"=>"RevaluationDummyMarks",'action' => 'marks'),array('data-placement'=>'left','escape' => false)); ?></span>
<span><?php echo $this->Html->link("<span class='navbar-brand'><small>Add ( ENTRY $level ) <i class='ace-icon fa fa-angle-double-right'></i></small></span>", array("controller"=>"RevaluationDummyMarks",'action' => 'addMark'.$level,$dummy_number_id,$level),array('data-placement'=>'left','escape' => false)); ?></span>

</div>