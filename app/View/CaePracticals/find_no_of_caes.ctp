<?php 
//echo $caeCount;
//echo $action;
SWITCH ($action) {
	CASE "project":
		$model = "CaeProject";
		break;
	CASE "practical":
		$model = "CaePractical";
		break;
	CASE "index":
	CASE "theory":
		$model = "Cae";
		break;
}
if ($template == "theoryTemplate") {
	if(count($cae) > 0) {
		$marks = $cae[0]['Cae']['marks'];
		$disabled = "readonly";
	}
	else {
		$disabled = '';
		$marks = '';
	}
	echo $this->Form->input('marks', array('type' => 'text', 'default' => $marks, 'class' => 'js-marks', 'label' => 'Marks', 'name' => 'data[CAE][Theory]', $disabled));
}
$i=1;
if (count($cae) > 0 && !empty($cae)) {
?>
<table cellpadding="0" cellspacing="0" border="0" class="display" id="example" style="margin-top:10px;">
			<thead>
				<tr>
					<th>Type</th>
					<th>Marks</th>
					<th>Marks&nbsp;Status</th>
					<th>Approval&nbsp;Status</th>
					<th>Created</th>
				</tr>
			</thead>
			<tbody> <?php
				foreach ($cae as $cae) {
					echo "<tr>";
					echo "<td>".$cae[$model]['assessment_type']."</td>";
					echo "<td>".$cae[$model]['marks']."</td>";
					echo "<td>".$cae[$model]['marks_status']."</td>";
					if ($cae[$model]['approval_status']) {
						$approvalStatus = "Approved";
					}
					else {
						$approvalStatus = "Not Approved";
					}
					echo "<td>".$approvalStatus."</td>";
					$user_id = $cae[$model]['created_by'];
					echo "<td>".$this->Html->getUserNameFromUserId($user_id)."</td>";
					echo "<tr>";
					$i++; 
				} ?>
			</tbody>		
		</table>			
<?php
}
?>
<?php echo $this->Html->script('common'); ?>
<script>leftMenuSelection('CaePracticals/practical');</script>

<span class='breadcrumb1'>
<span class='navbar-brand'><small>MARKS <i class='ace-icon fa fa-angle-double-right'></i></small></span>
<span class='navbar-brand'><small>Practical <i class='ace-icon fa fa-angle-double-right'></i></small></span>
<span class='navbar-brand'><small>Add <i class='ace-icon fa fa-angle-double-right'></i></small></span>
<?php echo $this->Html->link("<span class='navbar-brand'><small> ASSESSMENT <i class='ace-icon fa fa-angle-double-right'></i></small></span>", array("controller"=>"CaePracticals",'action' => 'addCaePractical'),array('data-placement'=>'left','escape' => false)); ?>
</span>