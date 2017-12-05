<?php
//pr($internalDetails);
echo $this->Form->input('studentId', array('type'=>'hidden', 'label' => false, 'default' => $studentId, 'name'=>'data[CAE][studentId]'));
echo "<table style='width=300px;'>";
echo "<tr><td>Course Code</td><td>Marks</td></tr>";
foreach ($internalDetails as $key => $internalDetail) {
//pr($internalDetail);
$cmId = $internalDetail['course_mapping_id'];
	echo "<tr>";
		echo "<td>".$cmId." ".$this->Form->input('cmId', array('type'=>'hidden', 'label' => false, 'default' => $cmId, 'name'=>'data[CAE][cmId][]'))."</td>";
		echo "<td>".
		$this->Form->input('marks', array('type'=>'text', 'label' => false, 'value' => $internalDetail['marks'], 'name'=>'data[CAE][marks][]'))
		."</td>";
	echo "<tr>";
}
echo "</table>";
?>