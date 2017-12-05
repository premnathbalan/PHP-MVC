<div class="deptFrm" style="width:80%;">
<?php
echo $this->Form->create('User');
echo $this->Form->input('id');
echo $this->Form->input('current_password',array("label"=>"Current Password <span class='ash'>*</span>",'type' => 'password', 'placeholder' => 'Current Password'));
echo $this->Form->input('new_password',array("label"=>"New Password <span class='ash'>*</span>",'type' => 'password', 'placeholder' => 'New Password'));
echo $this->Form->input('confirm_password',array("label"=>"Confirm Password <span class='ash'>*</span>",'type' => 'password', 'placeholder' => 'Confirm Password'));
echo $this->Form->end(__('Submit'));
?>
</div>

<span class='breadcrumb1'>
<span class='navbar-brand'><small>MASTERS <i class='ace-icon fa fa-angle-double-right'></i></small></span>
<?php echo $this->Html->link("<span class='navbar-brand'><small>CHANGE PASSWORD <i class='ace-icon fa fa-angle-double-right'></i></small></span>", array("controller"=>"Users",'action' => 'change_password'),array('data-placement'=>'left','escape' => false)); ?>
</span>