<div class="userRoles form">
<?php echo $this->Form->create('UserRole'); ?>
	<?php
		echo $this->Form->input('id');
		echo $this->Form->input('user_role');
	?>
	<table border='1' style='margin:5px;' class='display tblOddEven privileges'>
	<?php $i = 1;$j = 1;
	  foreach($pathGroups as $key => $pages){
	    echo "<tr id='userPrivil$j'><th colspan='12'><b><img src='/sets2015/img/plus.gif'> ".$key."</b></td></th>";  
	    foreach($pages as $key1 =>$value1){      
	      	echo "<tr class='userPrivil$j' style='display:none;'><td><b><input type='checkbox' id='chkBxUserRole$i' onclick='userPrivileges(&#39;chkBxUserRole&#39;,$i)'>".$key1."</b></td>";  
	      	foreach($value1 as $key2 =>$value2){         
		        echo "<td>";
		        echo $this->Form->input('Path.'.$key2,array('value'=>$key2,'type'=>'checkbox','label'=>$value2,'class'=>'chkBxUserRole'.$i));  
		        echo "</td>";            
	        }$i++;
	      	echo "</tr>";
	    }$j++;    
	  }	
	?>
</table>
<?php echo $this->Form->end(__('Submit')); ?>
</div>

<span class='breadcrumb1'>
<span class='navbar-brand'><small>Masters <i class='ace-icon fa fa-angle-double-right'></i></small></span>
<?php echo $this->Html->link("<span class='navbar-brand'><small> User Roles <i class='ace-icon fa fa-angle-double-right'></i></small></span>", array("controller"=>"UserRoles",'action' => 'index'),array('data-placement'=>'left','escape' => false)); ?>
<?php echo $this->Html->link("<span class='navbar-brand'><small> EDIT <i class='ace-icon fa fa-angle-double-right'></i></small></span>", array("controller"=>"UserRoles",'action' => 'edit',$editId),array('data-placement'=>'left','escape' => false)); ?>
</span>