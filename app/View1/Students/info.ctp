<div class="students view userFrm">
<dl class="bgFrame1">	
	<dt style='height:200px;width:220px;padding:5px 5px;'>
		<?php if($student['Student']['picture']){ $imgPicture = str_replace("  "," ",str_replace("   "," ",$student['Student']['picture']));}else{$imgPicture = 'profile.jpg';} ?>
		<?php //echo $this->Html->image(h("students/".$imgPicture), ['alt' => h($imgPicture),'style'=>'width:150px;border-radius:10px;']);?>
	</dt>
	<dt><?php echo __('Name'); ?></dt>
	<dd><?php echo h($student['Student']['name']); ?></td>
	
		<dt><?php echo __('Reg.No.'); ?></dt>
		<dd><?php echo h($student['Student']['registration_number']); ?></dd>
		
		<dt><?php echo __('Roll Number'); ?></dt>
		<dd><?php echo h($student['Student']['roll_number']); ?></td>
		
		<dt><?php echo __('Gender'); ?></dt>
		<dd><?php echo h($student['Student']['gender']); ?></dd>
		
		<?php if(isset($student['Section']['name']) && !empty($student['Section']['name'])) { ?>	
			<dt><?php echo __('section'); ?></dt>
			<dd><?php echo h($student['Section']['name']); ?></dd>
		<?php } ?>
	
		<dt><?php echo __('Batch'); ?></dt>
		<dd><?php echo $student['Batch']['batch_from']; ?></dd>
			
		<dt><?php echo __('Specialisation'); ?></dt>
		<dd><?php echo $student['Program']['program_name']; ?></dd>
		
		<dt><?php echo __('Program'); ?></dt>
		<dd><?php echo $student['Academic']['academic_name']; ?></dd>
						
		<dt><?php echo __('Phone Number'); ?></dt>
		<dd><?php echo h($student['Student']['phone_number']); ?></dd>
		
		<dt><?php echo __('Email Id'); ?></dt>
		<dd><?php echo h($student['Student']['email']); ?></dd>
					
</dl>
</div>

<?php if($this->request->params['action'] != 'student_course_edit'){?>
<script>leftMenuSelection('Students/student_search');</script>
<?php }?>