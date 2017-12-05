<!-- #section:basics/navbar.layout -->

<div id="navbar" class="navbar navbar-default"> 
    <div class="navbar-container" id="navbar-container"> 
    <!-- #section:basics/sidebar.mobile.toggle -->
    <button type="button" class="navbar-toggle menu-toggler pull-left" id="menu-toggler" data-target="#sidebar"> <span class="sr-only">Toggle sidebar</span> <span class="icon-bar"></span> <span class="icon-bar"></span> <span class="icon-bar"></span> </button>
    
    <!-- /section:basics/sidebar.mobile.toggle -->
    <div class="navbar-header pull-left"> 
      <!-- #section:basics/navbar.layout.brand --> 
     
     <?php echo $this->Html->link("<span class='navbar-brand'><small><i class='fa fa-university'></i> SETS 2015 <i class='ace-icon fa fa-angle-double-right'></i></small></span>", array("controller"=>"Users",'action' => 'dashboard'),array('data-placement'=>'left','escape' => false)); ?>
     
     <span id="breadcrumb1"></span>
		
      <!-- /section:basics/navbar.layout.brand --> 
      
      <!-- #section:basics/navbar.toggle --> 
      
      <!-- /section:basics/navbar.toggle --> 
    </div>
    
    <!-- #section:basics/navbar.dropdown -->
    <div class="navbar-buttons navbar-header pull-right" role="navigation">
      <ul class="nav ace-nav">

         <li class="grey">		 
			<?php echo $this->Html->link(__('<i class="ace-icon fa fa-power-off"></i>'),array('controller'=>'Users','action'=>'logout'), array('confirm' => __('Are you sure to logout?'),'escape' => false)); ?>			
         </li>     
        
        <!-- #section:basics/navbar.user_menu -->
        <li class="light-blue"> <a data-toggle="dropdown" href="#" class="dropdown-toggle"> 
		<?php echo $this->Html->image("user.jpg");?>
		<span class="user-info"> <small>Welcome,</small> <?=$this->Session->read('Auth.User.username')?> </span> <i class="ace-icon fa fa-caret-down"></i> </a>
          <ul class="user-menu dropdown-menu-right dropdown-menu dropdown-yellow dropdown-caret dropdown-close">
            <li> <a href="#"> <i class="ace-icon fa fa-eye"></i> Change Password </a> </li>
            
          </ul>
        </li>
        
        <!-- /section:basics/navbar.user_menu -->
      </ul>
    </div>
    
    <!-- /section:basics/navbar.dropdown --> 
  </div>
  <!-- /.navbar-container --> 
</div>
