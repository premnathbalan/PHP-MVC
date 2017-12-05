<div class="col-sm-12">
	<div class="col-lg-2">CAE</div>
	<div class="col-lg-10">
		<?php
			echo $this->Form->input('cae_id', array('type'=> 'hidden', 'style'=>'width:50px;border-color:#000;color:#000;padding-left:10px;', 'label'=>false, 'default'=>$ese_id, 'name' => 'data[cae_id]['.$cm_id.']', 'value'=>$array['cae_id']));
			echo $this->Form->input('cae_new_marks_'.$cm_id, array('label' => false, 'type' => 'text', 'name' => 'data[cae_new_marks]['.$cm_id.']','style'=>'margin-top:10px;','maxlength'=>10, 'value'=>$array['cae']));
		?>
	</div>
</div>
<div class="col-sm-12">
	<div class="col-lg-2">ESE</div>
	<div class="col-lg-10">
		<?php 
		echo $this->Form->input('ese_id', array('type'=> 'hidden', 'style'=>'width:50px;border-color:#000;color:#000;padding-left:10px;', 'label'=>false, 'default'=>$ese_id, 'name' => 'data[ese_id]['.$cm_id.']', 'value'=>$array['ese_id']));
		echo $this->Form->input('ese_new_marks_'.$cm_id, array('label' => false, 'type' => 'text', 'name' => 'data[ese_new_marks]['.$cm_id.']','style'=>'margin-top:10px;','maxlength'=>10, 'value'=>$array['ese'], 'onkeyup'=>"test('$cm_id', 'ese', this.value, $course_details[$cm_id]['max_ese_mark'],0)")); 
		?>
	</div>
</div>
<div class="col-sm-12">
	<div class="col-lg-2">TOTAL</div>
	<div class="col-lg-10">
		<?php echo $this->Form->input('student_mark_id_'.$cm_id, array('type'=> 'hidden', 'style'=>'width:50px;', 'label'=>false, 'default'=>$student_mark_id, 'name' => 'data[student_mark_id]['.$cm_id.']', 'value'=>$array['total_id'])); ?>
		<?php echo $this->Form->input('student_marks_'.$cm_id, array('label' => false, 'type' => 'text','style'=>'margin-top:10px;','maxlength'=>10, 'value'=>$array['total'], 'name' => 'data[student_mark]['.$cm_id.']')); ?>
	</div>
</div>
<div class="col-sm-12">
	<div class="col-lg-2">Grade</div>
	<div class="col-lg-10">
		<?php echo $this->Form->input('grade', array('label' => false, 'type' => 'text','style'=>'margin-top:10px;','maxlength'=>10, 'value'=>$array['grade'], 'readonly'=>'readonly')); ?>
	</div>
</div>