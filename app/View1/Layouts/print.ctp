<!DOCTYPE html>
<html lang="en">
	<head><meta charset="UTF-8" />
<?php echo $this->Html->meta('title',$this->fetch('title'));?>
	<body class="no-skin" onload="window.print();">
		
		<div class="main-container" id="main-container">
			
			<div class="main-content ">
			<div class="main-content-inner ">
			<div class="page-content">

				<?php echo $this->fetch('content'); ?>

			</div>
			</div>
			</div>	
			
		</div><!-- /.main-content -->		
	<?php echo $this->element('sql_dump'); ?>
<!-- inline scripts related to this page -->

	</body>
</html>