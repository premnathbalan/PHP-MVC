	
	<?php echo $this->Form->button('<i class="ace-icon fa fa-check bigger-110"></i>'.__('Submit'),array('type'=>'submit','name'=>'submit','value'=>'submit','class'=>'btn','style'=>'float:right;margin-right:10px;'));?>
	
	<table cellpadding="0" cellspacing="0" border="0" class="display" id="example" style="margin-top:10px;">		
		<thead>
			<tr>
				<th>Id</th>
				<th>Reg&nbsp;No.</th>
				<th>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Name</th>
				<?php foreach($Withhelds as $Withheld){?>
					<th><?php echo ucwords($Withheld['Withheld']['Withheld_type']);?></th>
				<?php }?>
			</tr>
		</thead>
		<tbody>
		<div id="studentSearch">
			<?php if(isset($stuList)) { $i = 1;foreach ($stuList as $stuList): 
			$arraySWH = array();
			if($stuList['StudentWithheld']){
				for($p=0;$p<count($stuList['StudentWithheld']);$p++){
					$arraySWH[$stuList['StudentWithheld'][$p]['withheld_id']] = $stuList['StudentWithheld'][$p]['withheld_id'];
				}
			}
			?>
			<tr class=" gradeX">
				<td>
					<?php echo h($stuList['Student']['id']); ?>
					<input type='hidden' name="student<?php echo $i;?>" value="<?php echo $stuList['Student']['id'];?>" />
				</td>
				<td><?php echo h($stuList['Student']['registration_number']); ?></td>
				<td><?php echo h($stuList['Student']['name']); ?></td>
				<?php $j=1;foreach($Withhelds as $key =>$value){
					$checked = ""; 
					if(isset($arraySWH[$value['Withheld']['id']])){ $checked = 'checked';}
				?>     
					<td>
						<?php  echo $this->Form->input($stuList['Student']['id'].'withheld'.$j,array('value'=>$value['Withheld']['id'],'type'=>'checkbox','label'=>$value['Withheld']['Withheld_type'],$checked)); ?>
					</td>
				<?php $j++; }?>
			</tr>
			  <?php $i++;endforeach; } ?>
		</div>	
		</tbody>
		<tfoot>
			<tr>
				<th><input type="text" name="Id" value="Id" class="search_init" /></th>
				<th><input type="text" name="Registration Number" value="Reg&nbsp;No." class="search_init" /></th>
				<th><input type="text" name="Registration Number" value="&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Name" class="search_init" /></th>
				<?php $j=1;foreach($Withhelds  as $key =>$value){?>
				<th><input type="hidden" name="WHOrginal<?php echo $j;?>" value="<?php echo $value['Withheld']['id'];?>" /></th>
				<?php  $j++;} ?>
			</tr>
		</tfoot>
			<input type="hidden" name="examMonth" value="<?php echo $examMonth;?>" />
			<input type="hidden" name="maxCol" value="<?php echo $j-1;?>" />
			<input type="hidden" name="maxRow" value="<?php echo $i-1;?>">		
		<?php echo $this->Html->script('common');?>
	</table>

<span class='breadcrumb1'>
	<span class='navbar-brand'><small>STUDENTS <i class='ace-icon fa fa-angle-double-right'></i></small></span>
	<?php echo $this->Html->link("<span class='navbar-brand'><small> WITH HELD <i class='ace-icon fa fa-angle-double-right'></i></small></span>", array("controller"=>"Students",'action' => 'studentWithHeld'),array('data-placement'=>'left','escape' => false)); ?>
</span>