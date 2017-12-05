<div class="users form userFrm">
	
	<?php	
	echo $this->Session->flash();
	echo $this->Form->create('User', array('class'=>'js-form','success_url'=>$this->Html->url(array('controller'=>'users','action'=>'index'))));
	
	echo $this->Form->input('username',array("label"=>"User Name <span class='ash'>*</span>",'type' => 'text', 'placeholder' => 'Username'));	
	echo $this->Form->input('password',array("label"=>"Password <span class='ash'>*</span>"));
	echo $this->Form->input('department_id',array('empty' => __("-- Select Department --")));
	echo $this->Form->input('designation_id',array( 'empty' => __("-- Select Designation --")));
	echo $this->Form->input('name');
	echo $this->Form->input('email');
	echo $this->Form->input('mobile');
	echo $this->Form->input('user_role_id',array( 'empty' => __("-- Select User Role --")));
	echo $this->Form->end('Submit');
	?>	
</div>

<script>leftMenuSelection('Users');</script>

<span class='breadcrumb1'>
<span class='navbar-brand'><small>MASTER <i class='ace-icon fa fa-angle-double-right'></i></small></span>
<?php echo $this->Html->link("<span class='navbar-brand'><small>STAFF <i class='ace-icon fa fa-angle-double-right'></i></small></span>", array("controller"=>"Users",'action' => 'index'),array('data-placement'=>'left','escape' => false)); ?>
<?php echo $this->Html->link("<span class='navbar-brand'><small>ADD <i class='ace-icon fa fa-angle-double-right'></i></small></span>", array("controller"=>"Users",'action' => 'add'),array('data-placement'=>'left','escape' => false)); ?>
</span>