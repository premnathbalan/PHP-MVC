<!DOCTYPE html>
<html lang="en">
	<head><meta charset="UTF-8" />
<?php echo $this->Html->meta('title',$this->fetch('title'));?>
	
<?php
	
	echo $this->Html->meta('icon');
	//echo $this->Html->css('cake.generic');//not require
	echo $this->Html->css('common');
		
	//echo $this->Html->css('ui.jqgrid');//Grid Footer
	echo $this->Html->css('jquery-ui');//Popup window
	echo $this->element('scripts');
?>
<!-- bootstrap & fontawesome -->
<?php  echo $this->Html->css('bootstrap');
echo $this->Html->css('font-awesome');
 //text fonts
echo $this->Html->css('ace-fonts');
 //ace styles
echo $this->Html->css('ace');
 //[if lte IE 9]
echo $this->Html->css('ace-part2');
//[endif]

//[if lte IE 9]>
echo $this->Html->css('ace-ie');?>
<!-- inline styles related to this page -->	
	
	<?php	
		echo $this->Html->css('chosen');
		echo $this->Html->css('table3-demo_page');
		echo $this->Html->css('table3-demo_table');
		echo $this->Html->script('table3-jquery');	
		echo $this->Html->script('jquery-ui');
		echo $this->Html->script('table3-jquery.dataTables');	
		echo $this->Html->script('common');
	?>
	</head>

	<body class="no-skin <?php if($this->request->params['action'] =='login'){ ?> login-layout <?php }?>">
		<?php if($this->request->params['action'] !='login'){ ?> 
		<!-- #section:basics/navbar.layout -->
		<?php echo $this->element('header');?>
		<!-- /section:basics/navbar.layout -->
		<?php } ?>
		
		<div class="main-container" id="main-container">
			<?php echo $this->Flash->render(); ?>		
	
			<?php if($this->request->params['action'] !='login'){ ?> 
			<?php echo $this->element('pages_left'); ?>	
			<?php }?>
				
			<div class="main-content ">
			<div class="main-content-inner ">
			<div class="page-content">

				<?php echo $this->fetch('content'); ?>

			</div>
			</div>
			</div>	
									
			<?php if($this->request->params['action'] !='login'){ ?> 
			<?php //echo $this->element('footer');?>	
			<?php }else{?><div class="loginPageBg" style="height:100px;"></div><?php }?>		
	
		</div><!-- /.main-content -->		
	<?php echo $this->element('sql_dump'); ?>
<!-- inline scripts related to this page -->

	</body>
</html>