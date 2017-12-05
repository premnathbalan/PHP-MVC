<?php
echo $this->element('print_head_a4');
?>
<div style="clear:both;"></div>
<?php
echo "Batch : ".$batch_period;
?>
<div style="clear:both;"></div>
<table border="1" style="margin:5px;" class="display tblOddEven">
	<tr>
		<!--<th>Batch</th>-->
		<th>Program</th>
		<th>Specialisation</th>
		<th>Total Strength</th>
		<th>All Pass</th>
		<th>One Arrear</th>
		<th>Two Arrear</th>
		<th>Three Arrear</th>
		<th>More than Three Arrear</th>
		<th>Pass Percentage</th>
	</tr>
	<?php
	foreach ($reportResult as $key => $result) { 
	?>
	<tr>
		<!--<td><?php echo $result['batch'];?></td>-->
		<td><?php echo $result['academic'];?></td>
		<td><?php echo $result['program'];?></td>
		<td align="center"><?php echo $result['totalStrength'];?></td>
		<td align="center"><?php echo $result['allPass'];?></td>
		<td align="center"><?php echo $result['oneArrear'];?></td>
		<td align="center"><?php echo $result['twoArrear'];?></td>
		<td align="center"><?php echo $result['threeArrear'];?></td>
		<td align="center"><?php echo $result['moreThanThreeArrear'];?></td>
		<td align="center"><?php echo $result['totalPercent'];?></td>
	</tr>
	<?php
	}
	?>
</table>
<?php 
echo $this->Html->script('common');
?>

<script>leftMenuSelection('Batchwise');</script>

<span class='breadcrumb1'>
<span class='navbar-brand'><small>REPORTS <i class='ace-icon fa fa-angle-double-right'></i></small></span>
<?php echo $this->Html->link("<span class='navbar-brand'><small>BatchWise <i class='ace-icon fa fa-angle-double-right'></i></small></span>", array("controller"=>"DummyMarks",'action' => 'moderation'),array('data-placement'=>'left','escape' => false)); ?>
</span>
</div>