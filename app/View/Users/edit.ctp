<div class="users form">
	
<?php
	echo $this->Session->flash();	
	echo $this->Form->create('User', array('class'=>'js-form','success_url'=>$this->Html->url(array('controller'=>'users','action'=>'index'))));
	
	echo "<div class='userFrm'>";
	echo $this->Form->input('id');
	echo $this->Form->input('username',array("label"=>"User Name <span class='ash'>*</span>",'type' => 'text', 'placeholder' => 'Username'));	
	echo $this->Form->input('password',array('default'=>''));
	echo $this->Form->input('department_id',array( 'empty' => __("-- Select Department --")));
	echo $this->Form->input('designation_id',array( 'empty' => __("-- Select Designation --")));
	echo $this->Form->input('name');
	echo $this->Form->input('email');
	echo $this->Form->input('mobile');
	echo $this->Form->input('user_role_id',array( 'empty' => __("-- Select User Role --")));
	echo "</div>";	
?>
<table border='1' style='margin:5px;' class='display tblOddEven privileges'>
	<?php $i = 1; $j = 1;
	  foreach($pathGroups as $key => $pages){
	    echo "<tr id='userPrivil$j'><th colspan='12'><b><img src='/sets2015/img/plus.gif'> ".$key."</b></td></th>";  
	    foreach($pages as $key1 =>$value1){      
	      echo "<tr class='userPrivil$j' style='display:none;'><td><b><input type='checkbox' id='chkBxUserRole$i' onclick='userPrivileges(&#39;chkBxUserRole&#39;,$i)'>".$key1."</b></td>";  
	      	foreach($value1 as $key2 =>$value2){   
		        echo "<td align='left'>";
		        echo $this->Form->input('Path.'.$key2,array('value'=>$key2,'type'=>'checkbox','label'=>$value2,'class'=>'chkBxUserRole'.$i));  
		        echo "</td>";
	        }$i++;
	      echo "</tr>";
	    } $j++;  
	  }	 
	?>
</table>

<?php
	echo $this->Form->end(__('Submit'));
	?>
</div>

<script>leftMenuSelection('Users');</script>
<span class='breadcrumb1'>
<span class='navbar-brand'><small>MASTER <i class='ace-icon fa fa-angle-double-right'></i></small></span>
<?php echo $this->Html->link("<span class='navbar-brand'><small>STAFF <i class='ace-icon fa fa-angle-double-right'></i></small></span>", array("controller"=>"Users",'action' => 'index'),array('data-placement'=>'left','escape' => false)); ?>
<?php echo $this->Html->link("<span class='navbar-brand'><small>EDIT <i class='ace-icon fa fa-angle-double-right'></i></small></span>", array("controller"=>"Users",'action' => 'edit',$editUserId),array('data-placement'=>'left','escape' => false)); ?>
</span>