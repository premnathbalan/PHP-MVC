<div class="login-layout" style="margin:-30px;">
<div class="main-container">
<div class="main-content">
    <div class="row">
      <div class="col-sm-10 col-sm-offset-1">
        <div class="login-container">
          <div class="space-30"></div>        
         <div class="space-30"></div>
           <div class="space-30"></div>
          <div class="space-20"></div>
          <div class="position-relative">
            <div id="login-box" class="login-box visible widget-box no-border">
              <div class="widget-body">    <div class="space-6"></div>
                <div class="center"><?php echo $this->Html->image("logo.jpg");?></div>  <div class="widget-main">
				
				<?php echo $this->Session->flash();?>
				<?php echo $this->Form->create('User'); ?>
                  <h4 class="header blue lighter bigger"> <i class="ace-icon fa fa-coffee blue"></i> Please Login </h4>
                  <div class="space-6"></div>                
                    <fieldset>					
                      <label class="block clearfix"> <span class="block input-icon input-icon-right">	
						<?php echo $this->Form->input('username', array('type' => 'text', 'class' => 'form-control','placeholder' => 'Username','label'=>false ));?>
                        <i class="ace-icon fa fa-user"></i> </span> </label>
                      <label class="block clearfix"> <span class="block input-icon input-icon-right">
                        <?php echo $this->Form->input('password', array('type' => 'password', 'class' => 'form-control','placeholder' => 'Password','label'=>false ));?>
                        <i class="ace-icon fa fa-lock"></i> </span> </label>
                      <div class="space"></div>
                      <div class="clearfix">
						<span class="block input-icon input-icon-right">
						 
						 <i class="ace-icon  fa fa-key" style="right: 100px;color: #fff;font-size: 15px"></i>
						 <?php echo $this->Form->end(array('label' => __('Login', true), 'class' => 'width-35 pull-right btn-sm btn-primary')); ?> 
						  </span>
                      </div>
                      <div class="space-4"></div>
                    </fieldset>
                
                </div>
                <!-- /.widget-main -->
                
              
              </div>
              <!-- /.widget-body --> 
            </div>
            
            
         
            <!-- /.signup-box --> 
          </div>
          <!-- /.position-relative --> 
          
        </div>
      </div>
      <!-- /.col --> 
</div>
</div>
</div>