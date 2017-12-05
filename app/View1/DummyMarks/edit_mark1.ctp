<div><?php $level = 1;	if($results){
$publish_status = count($results[0]['DummyRangeAllocation'][0]['Timetable']['CourseMapping']['StudentMark']);
//echo $publish_status;
?>

<div class=" col-sm-12" style="text-align: center;">
<?php
if ($publish_status > 0) echo "<strong style='color:#ff0000;align:center;'>Data already published</strong>";
?>
</div>

	<div class="bgFrame1">
		<div class="col-lg-12">
			<div class="col-lg-3"><b>Course Code : </b><?php echo $results[0]['DummyRangeAllocation'][0]['Timetable']['CourseMapping']['Course']['course_code'];?></div>
			<div class="col-lg-3"><b>Course Name : </b><?php echo $results[0]['DummyRangeAllocation'][0]['Timetable']['CourseMapping']['Course']['course_code'];?></div>
			<div class="col-lg-4"><b>Program : </b><?php echo $results[0]['DummyRangeAllocation'][0]['Timetable']['CourseMapping']['Program']['program_name'];?></div>
			<div class="col-lg-2"><b>Max QP Marks : </b><?php $max_ese_qp_mark = $results[0]['DummyRangeAllocation'][0]['Timetable']['CourseMapping']['Course']['max_ese_qp_mark'];?>
			<input type="text" size="3" maxlength="3" id="max_ese_qp_mark" value="<?php echo $max_ese_qp_mark;?>" readonly="1" />
			</div>
		</div>
	</div>		
<table cellpadding="0" cellspacing="0" border="0" class="display tblOddEven" id="" style="margin-top:5px;">
	<thead>
		<tr>
			<th>Packet No.</th>
			<th>Cumulative Mark</th>
			<th>Dummy No.</th>
			<th>Marks (Out of <?php echo $max_ese_qp_mark;?>)</th>
		</tr>
	</thead>
	<tbody>
		<?php if(isset($results[0]['DummyNumber']['start_range'])){
		
		$p =1; $packetMark = "";
		for($i=($results[0]['DummyNumber']['start_range']);$i<=($results[0]['DummyNumber']['end_range']);$i++){
			$autoGenId = "";$assignedMark = ""; $DNId = $results[0]['DummyNumber']['id'];
			if(isset($DNMassignedValue[$i]['id'])){
				//if($DNMassignedValue[$i]['mark_entry'.$level] !=0){
					$assignedMark 	= $DNMassignedValue[$i]['mark_entry'.$level];
				//}
				$autoGenId		= $DNMassignedValue[$i]['id'];
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
				}?>				
			</td>
			<td id="packetMark<?php echo $p;?>"><?php echo $packetMark;?></td>
			<td><?php echo $i;?></td>
			<td><?php  				
				echo $this->Form->input('autoGenId'.$p, array('type'=>'hidden','label'=>false,'default'=>$autoGenId));
				echo $this->Form->input('DNId'.$p, array('type'=>'hidden','label'=>false,'default'=>$DNId));
				echo $this->Form->input('DN'.$p, array('type'=>'hidden','label'=>false,'default'=>$i));			
			echo $this->Form->input('mark'.$p, array('type'=>'text','class'=>'dummy','id'=>'DNM'.$p,'label'=>false,'required'=>'required','maxlength'=>3,'default'=>$assignedMark,'onblur'=>"DNtoM($p,this.value,$level)"));
			?>
			<span id="msg<?php echo $p;?>"></span>
			</td>			
		</tr>
		 <?php $p = $p+1;}} ?>
		 
	</tbody>
	<tfoot>
		<tr>
			<th><input type="text" name="Packet No." value="Packet No." class="search_init" /></th>
			<th><input type="text" name="Cumulative Mark" value="Cumulative Mark" class="search_init" /></th>
			<th><input type="text" name="Dummy No." value="Dummy No. Range" class="search_init" /></th>
			<th></th>
		</tr>
	</tfoot>	
	<input type="hidden" id="maxRow" name="maxRow" value='<?php echo $p-1; ?>' />
</table>

	<?php echo $this->Form->button('<i class="ace-icon fa fa-check bigger-110"></i>'.__('Confirm'),array('type'=>'button','name'=>'Confirm','value'=>'Confirm','class'=>'btn',"onclick"=>"return confirmDNtoM();"));?>
	<?php echo $this->Html->script('common');?>

	<?php echo $this->Html->script('common-front');?>
	
<script>leftMenuSelection('DummyMarks');</script>
<?php }else{ echo "Record Not Found...";} ?>
<span class='breadcrumb1'>
<span class='navbar-brand'><small>EXAMINATION <i class='ace-icon fa fa-angle-double-right'></i></small></span>
<span><?php echo $this->Html->link("<span class='navbar-brand'><small>Dummy Marks <i class='ace-icon fa fa-angle-double-right'></i></small></span>", array("controller"=>"DummyMarks",'action' => 'index'),array('data-placement'=>'left','escape' => false)); ?></span>
<span><?php echo $this->Html->link("<span class='navbar-brand'><small>Edit ( ENTRY $level )<i class='ace-icon fa fa-angle-double-right'></i></small></span>", array("controller"=>"DummyMarks",'action' => 'editMark'.$level,$DNId),array('data-placement'=>'left','escape' => false)); ?></span>

</div>