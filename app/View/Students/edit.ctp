<div class="students form">
<?php echo $this->Form->create('Student', array('type' => 'file')); echo $this->data['Student']['picture']; ?>	
		 <div class="col-sm-12"  style="text-align:center;">	                                
			<div calss="bgFrame1 col-sm-2">
				<?php if(isset($this->data['Student']['picture'])){echo $this->Html->image(h("students/".$this->data['Student']['picture']), ['alt' => 'Student Photo','style'=>'width:150px;height:150px;']);}?>
				<input type="hidden" name="old_picture" value="<?php if(isset($this->data['Student']['picture'])){echo $this->data['Student']['picture'];}?>">
				<?php if(isset($this->data['Student']['signature'])){echo $this->Html->image(h("signature/".$this->data['Student']['signature']), ['alt' => 'Student signature','style'=>'width:150px;height:60px;']);}?>
			</div>
		</div>
	<div class="bgFrame1">
	    <div class="col-sm-4">	                                
	    	<?php  echo $this->Form->input("student_type_id", array('type' => 'select', 'options' => $studenttypes, 'default' => 1, 'label' => "Admission Type <span class='ash'>*</span>", 'class' => 'js-studenttype'));?>
	    </div>
	    <div class="col-sm-4">
	    <?php  echo $this->Form->input('university_references_id', array('type' => 'select', 'options' => $University, 'empty' => '-University-', 'label' => "University <span class='ash'>*</span>", 'disabled'=> 'disabled', 'class' => 'js-student-university'));?>
	    </div>
	    <div class="col-sm-4">	    
	    <?php echo $this->Form->input('month_year_id', array('type' => 'select', 'options' => $monthyears, 'empty' => '-Month Year-', 'label' => "Month&nbsp;Year<span class='ash'>*</span>", 'disabled'=> 'disabled', 'class' => 'js-student-semester')); ?>
       </div> 
   </div><br/>
   <div class="bgFrame1">      
       <div class="col-sm-4">
        <label class="col-sm-5 control-label no-padding-right">Gender <span class='ash'>*</span></label>
		<?php echo $this->Form->radio('gender', array('M' => 'Male&nbsp;&nbsp;&nbsp;', 'F' => 'Female'),array('legend' => false));?>
		</div>
        <div class="col-sm-4">
		<?php echo $this->Form->input('name', array('label' => "First Name <span class='ash'>*</span>"));?>
		</div>       
              
        <div class="col-sm-4">
		<?php echo $this->Form->input('user_initial');?>
		</div>       
       
        <div class="col-sm-4 baminiImg">
		<?php echo $this->Form->input("tamil_name", array('label' => "&nbsp;&nbsp;&nbsp;Name in Tamil <span class='ash'>*</span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;",'style'=>'font-family:Bamini;height:24px;','div'=>false));?>
		<?php echo $this->Html->image("bamini.png", array('type'=>'image'));?>
		</div>		
		       
        <div class="col-sm-4 baminiImg">
		<?php echo $this->Form->input('tamil_initial', array('label' => "&nbsp;&nbsp;&nbsp;Initial in tamil <span class='ash'>*</span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;",'style'=>'font-family:Bamini;height:24px;','div'=>false));?>
		<?php echo $this->Html->image("bamini.png");?>
		</div>      
        <div class="col-sm-4">
		<?php echo $this->Form->input('father_name', array('label' => "Father Name <span class='ash'>*</span>"));?>
		</div>       
        <div class="col-sm-4">
		<?php echo $this->Form->input('mother_name', array('label' => "Mother Name <span class='ash'>*</span>"));?>
		</div>       
        <div class="col-sm-4">
		<?php echo $this->Form->input('registration_number', array('label' => "Register Number <span class='ash'>*</span>", 'type' => 'text'));?>
		</div>       
        <div class="col-sm-4">
		<?php echo $this->Form->input('roll_number', array('label' => "Roll Number <span class='ash'>*</span>"));?>
		</div>
		<div class="col-sm-4">
		<?php echo $this->Form->input('section_id', array('type' => 'select', 'options' => $sections, 'empty' => __("-- Batch --"), 'label' => "Section <span class='ash'>*</span>"));?>
		</div>
		 <div class="col-sm-4">
		<?php echo $this->Form->input("birth_date", array('label' => "Birth Date <span class='ash'>*</span>", 'type' => 'text', 'class' => 'fl tal vat w300p', 'error' => false , 'id' => 'select_date'));?>
		</div>       
        <div class="col-sm-4">
		<?php echo $this->Form->input('batch_id', array('type' => 'select', 'options' => $batches, 'empty' => __("-- Batch --"), 'label' => "Batch <span class='ash'>*</span>"));?>
		</div>       
        <div class="col-sm-4">
		<?php echo $this->Form->input('academic_id', array('type' => 'select', 'options' => $academics, 'empty' => __("-- Program --"), 'class' => 'student-academic', 'label' => "Program<span class='ash'>*</span>"));?>
		</div>       
        <div class="col-sm-4">
		<div id="programs"  class="program">
		<?php echo $this->Form->input('program_id', array('type' => 'select', 'empty' => __("-- Specialisation --"), 'label' => "Specialisation <span class='ash'>*</span>", 'class' => 'js-programs')); ?>
		</div>
		</div>       
         
        <!--<div class="col-sm-4">
        <label class="col-sm-5 control-label no-padding-right">Dual Degree</label>
		<?php echo $this->Form->radio('dual_degree', array('1' => 'Yes&nbsp;&nbsp;', '0' => 'No'),array('legend' => false));?>
		</div>       
        <div class="col-sm-4">
		<?php echo $this->Form->input('dual_branch', array('type' => 'select', 'options' => $batches, 'empty' => '- Select -'));?>
		</div>       
        <div class="col-sm-4">
		<?php echo $this->Form->input('dual_program', array('type' => 'select', 'options' => $batches, 'empty' => '- Select -'));?>		
		</div>-->
		
	</div><br/>
	<div class="bgFrame1">        
		 <div class="col-sm-4">
			<?php echo $this->Form->input("admission_date", array('label' => "Admission Date <span class='ash'>*</span>", 'type' => 'text', 'class' => 'fl tal vat w300p', 'error' => false , 'id' => 'select_date1'));?>
	    </div> 
        <div class="col-sm-4">
		<?php echo $this->Form->input('nationality', array('label' => "Nationality <span class='ash'>*</span>"));?>
		</div>       
        <div class="col-sm-4">
        <?php echo $this->Form->input('religion');?>
        </div>       
        <div class="col-sm-4">
		<?php echo $this->Form->input('community', array('label' => "Community <span class='ash'>*</span>"));?>
		</div>       
        <div class="col-sm-4">
		<?php echo $this->Form->input('address', array('label' => "Address <span class='ash'>*</span>"));?>
		</div>       
        <div class="col-sm-4">
		<?php echo $this->Form->input('city', array('label' => "City <span class='ash'>*</span>"));?>
		</div>       
        <div class="col-sm-4">
		<?php echo $this->Form->input('stat', array('label' => "State <span class='ash'>*</span>"));?>
		</div>       
        <div class="col-sm-4">
        <?php echo $this->Form->input('country', array('label' => "Country <span class='ash'>*</span>"));?>
        </div>       
        <div class="col-sm-4">
		<?php echo $this->Form->input('pincode', array('label' => "Pincode <span class='ash'>*</span>", 'type'=>'text')); ?>
		</div>       
        <div class="col-sm-4">
		<?php echo $this->Form->input('phone_number'); ?>
		</div>       
        <div class="col-sm-4">
		<?php echo $this->Form->input('email'); ?>
		</div>       
        <div class="col-sm-4">
		<?php echo $this->Form->input('mobile_number'); ?>
		</div>       
        <div class="col-sm-4">
		<?php echo $this->Form->input('addlfield1'); ?>
		</div>       
        <div class="col-sm-4">
		<?php echo $this->Form->input('addlfield2'); ?>
		</div>       
        <div class="col-sm-4">
		<?php echo $this->Form->input('addlfield3'); ?>
		</div>       
        <div class="col-sm-4">
		<?php echo $this->Form->input('addlfield4'); ?>
		</div>       
        <div class="col-sm-4">
		<?php echo $this->Form->input('aadhar'); ?>
		</div>       
		<div class="col-sm-5">
		<?php echo $this->Form->input('picture', array('type' => 'file', 'label' => 'Photo')); ?>		
		</div>
		<div class="col-sm-5">	
		<?php echo $this->Form->input('id');	echo $this->Form->end(__('Submit')); ?>
		</div>					
	</div>

<script>
$('.input label').addClass('col-sm-5 control-label no-padding-right');
$('.radio label').removeClass('col-sm-5 control-label no-padding-right');
if($('#StudentStudentTypeId').val() == 2) {
  $(".js-student-university").attr("disabled", true); 
  $(".js-student-semester").attr("disabled", false);
}else if ($('#StudentStudentTypeId').val() == 3) {
  $(".js-student-university").attr("disabled", false); 
  $(".js-student-semester").attr("disabled", false);
}else if ($('#StudentStudentTypeId').val() == 4) {
  $(".js-student-university").attr("disabled", true); 
  $(".js-student-semester").attr("disabled", false);
}else if ($('#StudentStudentTypeId').val() == 5) {
  $(".js-student-university").attr("disabled", true); 
  $(".js-student-semester").attr("disabled", false);
  $("#oldRegNo").show();
}else{
  $(".js-student-university").attr("disabled", true); 
  $(".js-student-semester").attr("disabled", false);
  $("#oldRegNo").hide();
}
leftMenuSelection('Students/student_search');
</script>

<span class='breadcrumb1'>
<span class='navbar-brand'><small>STUDENTS <i class='ace-icon fa fa-angle-double-right'></i></small></span>
<?php echo $this->Html->link("<span class='navbar-brand'><small> LIST <i class='ace-icon fa fa-angle-double-right'></i></small></span>", array("controller"=>"Students",'action' => 'student_search'),array('data-placement'=>'left','escape' => false)); ?>
<?php echo $this->Html->link("<span class='navbar-brand'><small> EDIT STUDENT ( $studentId )<i class='ace-icon fa fa-angle-double-right'></i></small></span>", array("controller"=>"Students",'action' => 'edit',$studentId),array('data-placement'=>'left','escape' => false)); ?>
</span>