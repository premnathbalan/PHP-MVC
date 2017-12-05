<!--  <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/smoothness/jquery-ui.css"> -->
  
<?php echo $this->Html->css('jquery-ui'); ?>
<?php echo $this->Html->script('jquery-1.12.4'); ?>
<?php echo $this->Html->script('jquery-ui'); ?>

<?php //echo $this->Html->script('common-front'); ?> 
<?php echo $this->Html->script('common'); ?>

<?php echo $this->Html->script('table3-jquery.dataTables'); ?>

<script>
$(function() {
    $( "#PhdStudentTitle" ).autocomplete({
        source: 'findTitle'
    });
     $( "#PhdStudentArea" ).autocomplete({
        source: 'findArea'
    });
});
</script>

<div class="phdStudents form">
<?php echo $this->Form->create('PhdStudent', array('type' => 'file'));?>
	<div class="bgFrame1">      
        <div class="col-sm-4">
        <label class="col-sm-5 control-label no-padding-right">Gender <span class='ash'>*</span></label>
		<?php echo $this->Form->radio('gender', array('M' => 'Male&nbsp;&nbsp;&nbsp;', 'F' => 'Female'),array('legend' => false));?>
		</div>
        <div class="col-sm-4">
		<?php echo $this->Form->input('name', array('label' => "Name <span class='ash'>*</span>"));?>
		</div>       
        <div class="col-sm-4">
		<?php echo $this->Form->input('user_initial');?>
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
		<?php echo $this->Form->input("birth_date", array('label' => "Birth Date <span class='ash'>*</span>", 'type' => 'text', 'class' => 'fl tal vat w300p', 'error' => false , 'id' => 'select_date'));?>
		</div>       
	</div><br/>
	
	<div class="bgFrame1">      
	    <div class="col-sm-4">
		<?php echo $this->Form->input('address', array('label' => "Address <span class='ash'>*</span>"));?>
		</div>
        <div class="col-sm-4">
		<?php echo $this->Form->input('email', array('label' => "Email <span class='ash'>*</span>"));?>
		</div>       
        <div class="col-sm-4">
		<?php echo $this->Form->input('mobile_number', array('label' => "Mobile Number <span class='ash'>*</span>"));?>
		</div>       
        <div class="col-sm-4">
		<?php echo $this->Form->input('date_of_register', array('label' => "Date of Register<span class='ash'>*</span>", 'type' => 'text', 'class' => 'fl tal vat w300p', 'error' => false , 'id' => 'select_date1'));?>
		</div>       
        <div class="col-sm-4">
		<?php echo $this->Form->input('month_year_id', array('label' => "Month Year <span class='ash'>*</span>", 'options' => $monthyears, 'empty' => '-MonthYear-'));?>
		</div>
		<div class="col-sm-4">
		<?php echo $this->Form->input('supervisor_id', array('label' => "Supervisor <span class='ash'>*</span>", 'empty' => '-Supervisor-'));?>
		</div>
		<div class="col-sm-4">
		<?php echo $this->Form->input('picture', array('type' => 'file', 'label' => 'Upload Picture')); ?>
		</div>       
	</div><br/>
	
	<div class="bgFrame1">      
        <div class="col-sm-6"> <!-- , 'onkeyup'=>'searchTitle();' -->
				<?php echo $this->Form->input('title', array('label' => "Title <span class='ash'>*</span>", 'style'=>'width:300px;'));?>
		</div>       
        <div class="col-sm-6">
		<?php echo $this->Form->input('area', array('label' => "Area <span class='ash'>*</span>", 'style'=>'width:300px;'));?>
		</div>
        
	</div><br/>	
<?php echo $this->Form->end(__('Submit')); ?>
</div>

<script>
leftMenuSelection('PhdStudents/add');
</script>

<span class='breadcrumb1'>
<span class='navbar-brand'><small>PHD STUDENTS <i class='ace-icon fa fa-angle-double-right'></i></small></span>
<?php echo $this->Html->link("<span class='navbar-brand'><small> ADD STUDENT <i class='ace-icon fa fa-angle-double-right'></i></small></span>", array("controller"=>"PhdStudents",'action' => 'add'),array('data-placement'=>'left','escape' => false)); ?>
</span> 