<div>
	<div class="bgFrame1">
		<div class="col-lg-12">
			<div class="col-lg-4"><b>Course Code : </b><?php echo $results[0]['DummyRangeAllocation'][0]['Timetable']['CourseMapping']['Course']['course_code'];?></div>
			<div class="col-lg-4"><b>Course Name : </b><?php echo $results[0]['DummyRangeAllocation'][0]['Timetable']['CourseMapping']['Course']['course_code'];?></div>
			<div class="col-lg-4"><b>Program : </b><?php echo $results[0]['DummyRangeAllocation'][0]['Timetable']['CourseMapping']['Program']['program_name'];?></div>
		</div>
	</div>		
	<table cellpadding="0" cellspacing="0" border="0" class="display" id="example" style="margin-top:10px;">		
		<thead>
		<tr>
			<th>Packet No.</th>
			<th>Dummy No.</th>
			<th class="actions">Register No.</th>			
		</tr>
		</thead>
		<tbody>
			<?php if(isset($results[0]['DummyNumber']['start_range'])){
		$p =1;
		for($i=($results[0]['DummyNumber']['start_range']);$i<=($results[0]['DummyNumber']['end_range']);$i++){
		$assignRegNo = "";$autoGenId = ""; $actualDummyNo ="";
		
		if(isset($DNassignedValue[$i]['id'])){
			$assignRegNo =$DNassignedValue[$i]['Student']['registration_number'];
			$autoGenId = $DNassignedValue[$i]['id'];
		}
		?>
		<tr class=" gradeX">
			<td><?php if($p <=20){ echo "1";}else{ echo ceil(($p/20));}?></td>
			<td><?php echo $i;?></td> 
			<td>
			<?php 
				echo $this->Form->input('autoGenId'.$p, array('type'=>'hidden','label'=>false,'default'=>$autoGenId));
				echo $this->Form->input('DNId'.$p, array('type'=>'hidden','label'=>false,'default'=>$results[0]['DummyNumber']['id']));
				echo $this->Form->input('DN'.$p, array('type'=>'hidden','label'=>false,'default'=>$i));
				echo $this->Form->input('registration_number'.$p, array('type'=>'text','label'=>false,'maxlength'=>10,'class'=>'dummy','required'=>'required','default'=>$assignRegNo,'onblur'=>"DNtoRG('".$p."',this.value)"));?>
			<span id="msg<?php echo $p;?>"></span>
			</td>			
		</tr>
	<?php $p = $p+1;}} ?>
		</tbody>	
		<tfoot>
			<tr>
				<th><input type="text" name="Packet No." value="Packet No." class="search_init" /></th>
				<th><input type="text" name="Dummy No." value="Dummy No." class="search_init" /></th>
				<th></th>				
			</tr>
		</tfoot>	
		<input type="hidden" id="maxRow" name="maxRow" value="<?php echo $p-1;?>">
	</table>
	<?php echo $this->Form->button('<i class="ace-icon fa fa-check bigger-110"></i>'.__('Reset'),array('type'=>'button','name'=>'Reset','value'=>'Reset','class'=>'btn',"onclick"=>"return resetDN('".$results[0]['DummyNumber']['id']."');"));?>
	<?php echo $this->Form->button('<i class="ace-icon fa fa-check bigger-110"></i>'.__('Confirm'),array('type'=>'button','name'=>'Confirm','value'=>'Confirm','class'=>'btn',"onclick"=>"return confirmDNtoRg();"));?>
	<?php echo $this->Html->script('common');?>

	<?php echo $this->Html->script('common-front');?>
	
<script>leftMenuSelection('DummyNumberAllocations');</script>
<span class='breadcrumb1'>
<span class='navbar-brand'><small>EXAMINATION <i class='ace-icon fa fa-angle-double-right'></i></small></span>
<?php echo $this->Html->link("<span class='navbar-brand'><small>Dummy Number Allocations <i class='ace-icon fa fa-angle-double-right'></i></small></span>", array("controller"=>"DummyNumberAllocations",'action' => 'index'),array('data-placement'=>'left','escape' => false)); ?>
<?php echo $this->Html->link("<span class='navbar-brand'><small>ADD <i class='ace-icon fa fa-angle-double-right'></i></small></span>", array("controller"=>"DummyNumberAllocations",'action' => 'add',$DNId,''),array('data-placement'=>'left','escape' => false)); ?>
</span>
</div>