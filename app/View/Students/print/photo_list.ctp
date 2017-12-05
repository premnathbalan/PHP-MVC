	<?php echo $this->element('print_head_a4');?>
	<table class="attendanceHeadTblP" style="width:1220px;" border="1" cellpadding="0" cellspacing="0">
		<tr><td colspan="4" align="center">STUDENT LIST</td></tr>
		<tr>
			<td><b>Batch</b></td>
			<td><?php echo $txtBatch;?></td>			
			<td><b>Program</b></td>
			<td><?php echo $txtAcademic;?></td>
		</tr>		
		<tr>
			<td><b>Specialisation</b></td>
			<td><?php echo $txtProgram;?></td>			
			<td><b>Date<b></td>
			<td></td>
		</tr>
	</table>

<?php if (isset($stuList)) {?>
<table cellpadding="0" style="width:1220px;" cellspacing="0" border="1" class="attendanceHeadTblP">
	<thead>
		<tr>
			<th style="height:30px;">S.No.</th>
			<th>Reg.No.</th>
			<th>Roll No.</th>
			<th>Student Name</th>			
			<th>Tamil Name</th>
			<th>DOB</th>
			<th>Father Name</th>
			<th>Gender</th>
			<th style="text-align:center;">Photo</th>
			<th style="text-align:center;">Signature</th>
			<th>Remarks</th>
		</tr>
	</thead>
	<tbody>
		<?php 	$sno = 1;	
		foreach($stuList as $result):?>
		<tr>
			<td style="height:30px;"><?php echo $sno; ?></td>
			<td align="center"><?php echo $result['Student']['registration_number']; ?></td>
			<td align="center"><?php echo $result['Student']['roll_number']; ?></td>
			<td align="left"><?php echo $result['Student']['user_initial']." ".$result['Student']['name']; ?></td>
			<td align="left"><?php echo $result['Student']['tamil_name']; ?></td>
			<td align="center"><?php echo date( "d-M-Y", strtotime(h($result['Student']['birth_date'])) ); ?></td>
			<td align="left"><?php echo $result['Student']['father_name']; ?></td>
			<td align="center"><?php echo $result['Student']['gender']; ?></td>
			<td align="center"><?php if($result['Student']['picture']){echo $this->Html->image(h("students/".$result['Student']['picture']), ['alt' => h('picture'),'style'=>'width:100px;height:50px;']);}?></td>
			<td align="center"><?php if($result['Student']['signature']){echo $this->Html->image(h("signatures/".$result['Student']['signature']), ['alt' => h('signature'),'style'=>'width:100px;height:50px;']);}?></td>
			<td align="center"></td>			
		</tr>
		<?php $sno++; if($sno% 25 == 0){?>
		</tbody>	
		</table>
		<?php echo $this->element('print_head_a4');?>
	<table class="attendanceHeadTblP" style="width:1220px;" border="1" cellpadding="0" cellspacing="0">
		<tr><td colspan="4" align="center">STUDENT LIST</td></tr>
		<tr>
			<td><b>Batch</b></td>
			<td><?php echo $txtBatch;?></td>			
			<td><b>Program</b></td>
			<td><?php echo $txtAcademic;?></td>
		</tr>		
		<tr>
			<td><b>Specialisation</b></td>
			<td><?php echo $txtProgram;?></td>			
			<td><b>Date<b></td>
			<td></td>
		</tr>
	</table>
		<table cellpadding="0" style="width:1220px;" cellspacing="0" border="1" class="attendanceHeadTblP">
		<thead>
			<tr>
				<th style="height:30px;">S.No.</th>
				<th>Reg.No.</th>
				<th>Roll No.</th>
				<th>Student Name</th>			
				<th>Tamil Name</th>
				<th>DOB</th>
				<th>Father Name</th>
				<th>Gender</th>
				<th style="text-align:center;">Photo</th>
				<th style="text-align:center;">Signature</th>
				<th>Remarks</th>
			</tr>
		</thead>
		<tbody>
			<?php } endforeach; ?>			
		</tbody>
</table>
<?php }?>	

<?php 	
	echo $this->Html->script('common');
	echo $this->Html->script('common-front');	
	echo $this->Html->css('certificate');
?>