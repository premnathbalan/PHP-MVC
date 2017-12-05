<?php echo $this->element('student_view',array("studentId"=>$studentId)); ?>	

<div class="students view userFrm">
<dl class="bgFrame1">
		<dt style='height:330px;width:200px;padding:50px 20px;'>
			<?php if($student['Student']['picture']){ $imgPicture = str_replace("  "," ",str_replace("   "," ",$student['Student']['picture']));}else{$imgPicture = 'profile.jpg';} 
				  if($student['Student']['signature']){ $imgSignature = str_replace("   "," ",$student['Student']['signature']);}else{$imgSignature = 'signature.png';} ?>
				  			
		<?php echo $this->Html->image(h("students/".$imgPicture), ['alt' => h($imgPicture),'style'=>'width:150px;height:175px;border-radius:10px;']); // ?>
		<?php echo $this->Html->image(h("signatures/".$imgSignature), ['alt' => h($imgSignature),'style'=>'width:150px;height:30px;border-radius:10px;']); //?>
		</dt>
		<dt><?php echo __('Student Type'); ?></dt>
		<?php //pr($student);
		$status = ""; $univ = "";
		if ($student['Student']['discontinued_status']==1) {
			$status = "<span style='color:#f00;'><strong>Discontinued</strong></span>";
		}
		if ($student['Student']['student_type_id']==3 && ($student['Student']['university_references_id']==1 || $student['Student']['university_references_id']==2)) {
			$univ = "<span style='color:#f00;'><strong>".$student['UniversityReference']['university_name']."</strong></span>";
		}
		if ($student['Student']['student_type_id']==3 && $student['Student']['university_references_id']==1) {
			$old_batch_id = $student['ParentGroup']['batch_id'];
			$old_academic_id = $student['ParentGroup']['academic_id'];
			$old_program_id = $student['ParentGroup']['program_id'];
			$old_batch = $this->Html->getBatch($old_batch_id);
			$old_academic = $this->Html->getAcademic($old_academic_id);
			$old_program = $this->Html->getProgram($old_program_id);
			echo $old_batch_id." ".$old_academic_id." ".$old_program_id;
		}
		
		if ($student['Student']['student_type_id']>1) {
			$joining_month_year_id = $student['Student']['month_year_id'];
			$month_year_of_joining = $month_years[$joining_month_year_id];
		}
		?>
		<dd><?php echo h($student['StudentType']['type'])."&nbsp;&nbsp;&nbsp;".$status; ?></dd>

		<dt><?php echo __('Name'); ?></dt>
		<dd><?php echo h($student['Student']['name']); ?></dd>
		
		<dt><?php echo __('Transfer'); ?></dt>
		<dd><?php echo $univ; ?></dd>
	
		<?php if(isset($student['Student']['parent_id']) && $student['Student']['parent_id'] > 0) { ?>
		
		<dt><?php echo __('Old Batch'); ?></dt>
		<dd><?php echo $old_batch; ?></dd>

		<?php } ?>
		
		<?php if(isset($student['Student']['parent_id']) && $student['Student']['parent_id'] > 0) { ?>
		
		<dt><?php echo __('Old Academic'); ?></dt>
		<dd><?php echo $old_academic; ?></dd>

		<?php } ?>
		
		<?php if(isset($student['Student']['parent_id']) && $student['Student']['parent_id'] > 0) { ?>
		
		<dt><?php echo __('Old Program'); ?></dt>
		<dd><?php echo $old_program; ?></dd>

		<?php } ?>
		
		<?php if(isset($student['Student']['month_year_id']) && $student['Student']['month_year_id'] > 0) { ?>
	
		<dt><?php echo __('Month & Year of joining'); ?></dt>
		<dd><?php echo $month_year_of_joining; ?></dd>

		<?php } ?>
		
	<?php if(isset($student['Student']['user_initial']) && !empty($student['Student']['user_initial'])) { ?>
	
		<dt><?php echo __('User Initial'); ?></dt>
		<dd><?php echo h($student['Student']['user_initial']); ?></dd>

	<?php } ?>
		
		<dt><?php echo __('User Tamil Name'); ?></dt>
		<dd class='baamini'><?php echo h($student['Student']['tamil_name']); ?></dd>
		
		<dt><?php echo __('Tamil Initial'); ?></dt>
		<dd><?php echo h($student['Student']['tamil_initial']); ?></dd>
		
		<dt><?php echo __('Father Name'); ?></dt>
		<dd><?php echo h($student['Student']['father_name']); ?></dd>
			
		<dt><?php echo __('Mother Name'); ?></dt>
		<dd><?php echo h($student['Student']['mother_name']); ?></dd>
		
		<dt><?php echo __('Registration Number'); ?></dt>
		<dd><?php echo h($student['Student']['registration_number']); ?></dd>
		
		<dt><?php echo __('Roll Number'); ?></dt>
		<dd><?php echo h($student['Student']['roll_number']); ?></td>
		
		<?php if(isset($student['Section']['name']) && !empty($student['Section']['name'])) { ?>	
		<dt><?php echo __('Section'); ?></dt>
		<dd><?php echo $student['Section']['name']; ?></dd>
		<?php } ?>
		
		<dt><?php echo __('Batch'); ?></dt>
		<dd><?php echo $student['Batch']['batch_period']; ?></dd>
		
		<dt><?php echo __('Program'); ?></dt>
		<dd><?php echo $student['Academic']['academic_name']; ?></dd>
		
		<dt><?php echo __('Specialisation'); ?></dt>
		<dd><?php echo $student['Program']['program_name']; ?></dd>
		
		<dt><?php echo __('Dual Degree'); ?></dt>
		<dd></dd>
	
		<dt><?php echo __('Dual Branch'); ?></dt>
		<dd></dd>
	
		<dt><?php echo __('Dual Program'); ?></dt>
		<dd></dd>
		
</dl>
<dl class="bgFrame2">			
		<dt><?php echo __('Date Of Birth'); ?></dt>
		<dd><?php echo date( "d-M-Y", strtotime(h($student['Student']['birth_date'])) ); ?></dd>
			
		<dt><?php echo __('Gender'); ?></dt>
		<dd><?php echo h($student['Student']['gender']); ?></dd>
			
		<dt><?php echo __('Nationality'); ?></dt>
		<dd><?php echo h($student['Student']['nationality']); ?></dd>
			
		<dt><?php echo __('Religion'); ?></dt>
		<dd><?php echo h($student['Student']['religion']); ?></dd>
			
		<dt><?php echo __('Community'); ?></dt>
		<dd><?php echo h($student['Student']['community']); ?></dd>
		
		<dt><?php echo __('Address'); ?></dt>
		<dd><?php echo h($student['Student']['address']); ?></dd>
			
		<dt><?php echo __('City'); ?></dt>
		<dd><?php echo h($student['Student']['city']); ?></dd>
		
		<dt><?php echo __('State'); ?></dt>
		<dd><?php echo h($student['Student']['stat']); ?></dd>
			
		<dt><?php echo __('Country'); ?></dt>
		<dd><?php echo h($student['Student']['country']); ?></dd>
		
		<dt><?php echo __('Pincode'); ?></dt>
		<dd><?php echo h($student['Student']['pincode']); ?></td>
			
		<dt><?php echo __('Phone Number'); ?></dt>
		<dd><?php echo h($student['Student']['phone_number']); ?></dd>
		
		<dt><?php echo __('Email Id'); ?></dt>
		<dd><?php echo h($student['Student']['email']); ?></dd>
			
		<dt><?php echo __('Mobile Number'); ?></dt>
		<dd><?php echo h($student['Student']['mobile_number']); ?></dd>
		
		<!--<dt><?php echo __('Discontinued Status'); ?></dt>
		<dd><?php echo h($student['Student']['discontinued_status']); ?></dd>-->
</dl>
<dl class="bgFrame2">		
		<dt><?php echo __('Reason'); ?></dt>
		<dd><?php echo h($student['Student']['reason']); ?>	</dd>
		
		<dt><?php echo __('Batch transfer prior to 2015'); ?></dt>
		<dd><?php echo h($student['Student']['prior_batch']); ?>	</dd>
		
		<dt><?php echo __('Addlfield3'); ?></dt>
		<dd><?php echo h($student['Student']['addlfield3']); ?></dd>
		
		<dt><?php echo __('Addlfield4'); ?></dt>
		<dd><?php echo h($student['Student']['addlfield4']); ?></dd>
	
		<dt><?php echo __('Addlfield5'); ?></dt>
		<dd><?php echo h($student['Student']['aadhar']); ?></dd>
		
		<!--<dt><?php echo __('Indicator'); ?></dt>
		<dd><?php echo h($student['Student']['indicator']); ?></dd>-->
</dl>
<dl class="bgFrame2">	
		<dt><?php echo __('Admission Date'); ?></dt>
		<dd><?php if(h($student['Student']['admission_date']) !='0000-00-00'){echo date( "d-M-Y", strtotime(h($student['Student']['admission_date'])) );} ?></dd>
		<dt><?php echo __('Created By'); ?></dt>
		<dd><?php echo h($student['User']['username']); ?></dd>
		<dt><?php echo __('Created On'); ?></dt>
		<dd><?php echo date( "d-M-Y h:i:s", strtotime(h($student['Student']['created'])) ); ?></dd>
		
		<?php if(h($student['ModifiedUser']['username'])){?>		
		<dt><?php echo __('Modified By'); ?></dt>		
		<dd><?php echo h($student['ModifiedUser']['username']); ?></dd>
		<dt><?php echo __('Modified On'); ?></dt>
		<dd><?php echo date( "d-M-Y h:i:s", strtotime(h($student['Student']['modified'])) ); ?></dd>
		<?php }?>
</dl>
</div>

<script>
leftMenuSelection('Students/add');
</script>

<span class='breadcrumb1'>
<span class='navbar-brand'><small>STUDENTS <i class='ace-icon fa fa-angle-double-right'></i></small></span>
<?php echo $this->Html->link("<span class='navbar-brand'><small> LIST <i class='ace-icon fa fa-angle-double-right'></i></small></span>", array("controller"=>"students",'action' => 'student_search'),array('data-placement'=>'left','escape' => false)); ?>
<?php echo $this->Html->link("<span class='navbar-brand'><small> VIEW DETAILS( $studentId ) <i class='ace-icon fa fa-angle-double-right'></i></small></span>", array("controller"=>"Students",'action' => 'view',$studentId),array('data-placement'=>'left','escape' => false)); ?>
</span>

<script>leftMenuSelection('Students/student_search');</script>