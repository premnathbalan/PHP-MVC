<?php
//pr($dummy_number);
?>

<table cellpadding="0" cellspacing="0" border="0" class="display" id="example" style="margin-top:10px;">
			<thead>
				<tr>
					<th>Attendance</th>
					<th>Marks</th>
				</tr>
			</thead>
			<tbody>
			<?php
			foreach ($dummy_number as $dummy_number_id => $dummy_number) {
			echo "<tr class='gradeX'>";
			echo "<td>".
			$this->Form->input('dummy_number', array('type'=> 'text', 'default' => $dummy_number, 'label'=>false, 'name' =>  'data[DummyMarks][dummy_number][]')).
			" ".
			$this->Form->input('dummy_number_id', array('type'=> 'hidden', 'default' => $dummy_number_id, 'label'=>false, 'name' =>  'data[DummyMarks][dummy_number_id][]')).
			"</td>";
			echo "<td>".
			$this->Form->input('marks', array('type'=> 'text', 'default' => rand(60,80), 'label'=>false, 'name' =>  'data[DummyMarks][marks]['.$dummy_number_id.']'))."</td>";
			echo "</tr>";
			} 
			?>
			</tbody>
			<!--<tfoot>
				<tr>
					<th></th>
					<th><input type="text" name="RegistrationNumber" value="RegistrationNumber" class="search_init" /></th>
					<th><input type="text" name="StudentName" value="StudentName" class="search_init" /></th>
				</tr>
			</tfoot>-->
		</table>
		<?php echo $this->Html->script('common'); ?>
