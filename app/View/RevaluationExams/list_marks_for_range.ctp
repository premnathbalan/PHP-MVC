		<table cellpadding="0" cellspacing="0" border="0" class="display" id="example" style="margin-top:10px;">
			<thead>
				<tr>
					<th></th>
					<th>Dummy&nbsp;Number</th>
					<th>Marks</th>
				</tr>
			</thead>
			<tbody>
			<?php
			$i=1;
			foreach ($result as $key => $dummy_mark) {
				echo "<tr class='gradeX'>";
					echo "<td>".$i."</td>";
					echo "<td>".
						$dummy_mark['RevaluationExam']['dummy_number'].
						" ".
						$this->Form->input('dummy_number_id', array('type'=> 'hidden', 'default' => $dummy_mark['RevaluationExam']['reval_dummy_mod_marks'], 'label'=>false, 'name' =>  'data[RevaluationExam][dummy_mod_marks]['.$dummy_mark['RevaluationExam']['id'].']')).
					"</td>";
					echo "<td>".
						$this->Form->input('marks', array('type'=> 'hidden', 'default' => $dummy_mark['RevaluationExam']['final_marks'], 'label'=>false, 'name' =>  'data[RevaluationExam][marks]['.$dummy_mark['RevaluationExam']['id'].']')).
						$dummy_mark['RevaluationExam']['final_marks'].
					"</td>";
				echo "</tr>";
				$i++;
			} 
			?>
			</tbody>
		</table>
		<?php 
		echo $this->Html->script('common'); 
		?>
<script>leftMenuSelection('Dummy Marks Moderation');</script>

<span class='breadcrumb1'>
<span class='navbar-brand'><small>EXAMINATION <i class='ace-icon fa fa-angle-double-right'></i></small></span>
<?php echo $this->Html->link("<span class='navbar-brand'><small>Dummy Marks Moderation <i class='ace-icon fa fa-angle-double-right'></i></small></span>", array("controller"=>"DummyMarks",'action' => 'moderation'),array('data-placement'=>'left','escape' => false)); ?>
</span>