<div class="searchFrm bgFrame1 col-sm-12" style="margin-top:7px;height:60px;">	
	<div class="col-lg-3" style='padding:0;'></div>
	<div class="col-lg-3" style='padding:0;'>
		<?php 
		$signOptions = array('plus'=>'+','minus'=>'-');
		echo $this->Form->input('sign', array('options' => $signOptions, 'label' => '+ OR -', 'empty' => __("Operator"), 'class' => 'js-mod-sign', 'required' => 'required', 'style'=>'width:60px;'));
		?>
	</div>
	<div class="col-lg-3" style='padding:0;'>
		<?php 
		$valueOptions = array('1'=>'1','2'=>'2','3'=>'3','4'=>'4','5'=>'5','6'=>'6','7'=>'7','8'=>'8','9'=>'9','10'=>'10');
		echo $this->Form->input('mark', array('options' => $valueOptions, 'label' => 'Mark', 'empty' => __("Mark"), 'class' => 'js-mod-mark', 'required' => 'required', 'style'=>'width:60px;'));
		?>
	</div>
	<div class="col-lg-3" style='padding:0;'>		
		<?php echo $this->Form->button('<i class="ace-icon fa fa-check bigger-110"></i>'.__('&nbsp;Submit&nbsp;&nbsp;'),array('type'=>'submit','name'=>'submit','value'=>'submit','class'=>'btn')); ?>
	</div>
</div>
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
						$dummy_mark['EndSemesterExam']['dummy_number'].
						" ".
						$this->Form->input('dummy_number_id', array('type'=> 'hidden', 'default' => $dummy_mark['EndSemesterExam']['dummy_mod_marks'], 'label'=>false, 'name' =>  'data[EndSemesterExam][dummy_mod_marks]['.$dummy_mark['EndSemesterExam']['id'].']')).
					"</td>";
					echo "<td>".
						$this->Form->input('marks', array('type'=> 'hidden', 'default' => $dummy_mark['EndSemesterExam']['marks'], 'label'=>false, 'name' =>  'data[EndSemesterExam][marks]['.$dummy_mark['EndSemesterExam']['id'].']')).
						$dummy_mark['EndSemesterExam']['marks'].
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