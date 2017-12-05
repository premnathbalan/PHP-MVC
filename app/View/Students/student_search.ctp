<div class="searchFrm bgFrame1" style="width:89%;">
	<?php echo $this->Form->create('Student');?>
	<?php echo $this->Form->input('batch_id', array( 'empty' => __(" - Batch - "),'required'=>'required', 'class' => '','style'=>'width:120px;')); ?>
	<?php echo $this->Form->input('academic_id', array('label'=>'Program','type' => 'select', 'empty' => __("----- Select Program-----"),'required'=>'',  'class' => 'js-academic')); ?>
	<div id="programs" class="program"><?php echo $this->Form->input('program_id', array('label'=>'Specialisation','type' => 'select', 'empty' => __("----- Select Specialisation-----"),'required'=>'', 'class' => 'js-programs')); ?></div>
	<?php echo $this->Form->button('<i class="ace-icon fa fa-check bigger-110"></i>'.__('Search'),array('type'=>'submit','name'=>'submit','value'=>'submit','class'=>'btn'));?>
	<?php //echo $this->Form->button('<i class="ace-icon fa fa-undo bigger-110"></i>'.__('Reset'),array('type'=>'reset','name'=>'reset','value'=>'reset','class'=>'btn'));?>
	<?php echo $this->Form->end(); ?>
</div>
	<table cellpadding="0" cellspacing="0" border="0" class="display" id="example" style="margin-top:10px;">
		<thead>
			<tr>
				<th>Reg&nbsp;No.</th>
				<th>&nbsp;&nbsp;Action&nbsp;&nbsp;&nbsp;&nbsp;</th>
				<th>Delete</th>
				<th>Batch</th>
				<th>&nbsp;&nbsp;&nbsp;&nbsp;Program</th>
				<th>Specialisation</th>
				<th>Admission&nbsp;Type</th>
				<th>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;First&nbsp;Name</th>
				<th>Gender</th>
				<th>Tamil&nbsp;Name</th>
				<th>User&nbsp;Initial</th>
				<th>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Father&nbsp;Name</th>
				<th>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Mother&nbsp;Name</th>
				<th>Specialisation</th>
				<th>Birth&nbsp;Date</th>
				<th>Nationality</th>
				<th>Religion</th>
				<th>Community</th>				
				<th>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Address</th>
				<th>City</th>
				<th>State</th>
				<th>Country</th>
				<th>Pincode</th>
			</tr>
		</thead>
		<tbody>
		<div id="studentSearch">
			<?php if (isset($stuList)) { $i = 0;foreach ($stuList as $stuList): ?>
			<tr class=" gradeX">
				<td><?php echo $stuList['Student']['registration_number']; ?></td>
				<td>
				<?php echo "&nbsp;&nbsp;&nbsp;&nbsp;";
				if($this->Html->checkPathAccesstopath('Students/view','',$authUser['id'])){
					echo $this->Html->link("<i class='fa fa-eye fa-lg'></i>", array("controller"=>"Students",'action' => 'view', h($stuList['Student']['registration_number'])),array('target'=>'_blank','escape' => false, 'title'=>'View','target'=>'_blank'));
				}echo "&nbsp;&nbsp;";if($this->Html->checkPathAccesstopath('Students/edit','',$authUser['id'])){
					echo $this->Html->link("<i class='fa fa-pencil fa-lg'></i>", array("controller"=>"Students",'action' => 'edit', h($stuList['Student']['registration_number'])),array('title'=>'Edit','escape' => false,'target'=>'_blank'));
				}
				?>			
				</td>
				<td>
					<?php echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
						if($this->Html->checkPathAccesstopath('Students/delete_user','',$authUser['id'])){
							echo $this->Html->Link("<span class='fa fa-times fa-lg red'></span>", array("controller"=>"Students",'action' => 'delete_user', h($stuList['Student']['id'])), array('confirm' => __('Are you sure you want to delete?', h($stuList['Student']['id'])),'escape' => false, 'title'=>'Delete')); 
						}
					?>
				</td>
				<td><?php 
				$modeAcademic = ""; if($stuList['Batch']['academic'] == 'JUN'){ $modeAcademic = " [A]"; }
				echo h($stuList['Batch']['batch_from']."-".$stuList['Batch']['batch_to'].$modeAcademic); ?></td>
				<td><?php echo h($stuList['Academic']['academic_name']); ?></td>
				<td><?php echo h($stuList['Program']['program_name']); ?></td>
				<td><?php echo h($stuList['StudentType']['type']); ?></td>
				<td><?php echo h($stuList['Student']['name']); ?></td>
				<td><?php echo h($stuList['Student']['gender']); ?></td>
				<td class='baamini'><?php echo h($stuList['Student']['tamil_name']); ?></td>
				<td class='baamini'><?php echo h($stuList['Student']['user_initial']); ?></td>
				
				<td><?php echo h($stuList['Student']['father_name']); ?></td>
				<td><?php echo h($stuList['Student']['mother_name']); ?></td>
				<td><?php echo h($stuList['Student']['specialisation']); ?></td>

				<td><?php echo date("d-M-Y", strtotime(h($stuList['Student']['birth_date']))); ?></td>
				<td><?php echo h($stuList['Student']['nationality']); ?></td>

				<td><?php echo h($stuList['Student']['religion']); ?></td>
				<td><?php echo h($stuList['Student']['community']); ?></td>
				<td><?php echo h($stuList['Student']['address']); ?></td>
				<td><?php echo h($stuList['Student']['city']); ?></td>
				<td><?php echo h($stuList['Student']['stat']); ?></td>
				<td><?php echo h($stuList['Student']['country']); ?></td>
				<td><?php echo h($stuList['Student']['pincode']); ?></td>
			</tr>
			  <?php endforeach; } ?>
		</div>	
		</tbody>
		<tfoot>
			<tr>
				<th><input type="text" name="Registration Number" value="Regn No." class="search_init" /></th>
				<th></th>
				<th></th>
				<th><input type="text" name="Batch Id" value="Batch Id" class="search_init" /></th>
				<th><input type="text" name="Program" value="Program" class="search_init" /></th>
				<th><input type="text" name="Specialisation" value="Program Id" class="search_init" /></th>
				<th><input type="text" name="Admission Type" Id" value="Admission Type" class="search_init" /></th>
				<th><input type="text" name="Firstname" value="Firstname" class="search_init" /></th>
				<th><input type="text" name="Gender" value="Gender" class="search_init" /></th>
				<th><input type="text" name="Tamil&nbsp;Name" value="Tamil&nbsp;Name" class="search_init" /></th>
				<th><input type="text" name="UserInitial" value="UserInitial" class="search_init" /></th>
				<th><input type="text" name="Father Name" value="Father Name" class="search_init" /></th>
				<th><input type="text" name="Mother Name" value="Mother Name" class="search_init" /></th>
				<th><input type="text" name="Specialisation" value="Specialisation" class="search_init" /></th>
				<th></th>
				<th><input type="text" name="Nationality" value="Nationality" class="search_init" /></th>
				<th><input type="text" name="Religion" value="Religion" class="search_init" /></th>
				<th><input type="text" name="Community" value="Community" class="search_init" /></th>
				<th><input type="text" name="Address" value="Address" class="search_init" /></th>
				<th><input type="text" name="City" value="City" class="search_init" /></th>
				<th><input type="text" name="Stat" value="Stat" class="search_init" /></th>
				<th><input type="text" name="Country" value="Country" class="search_init" /></th>
				<th><input type="text" name="Pincode" value="Pincode" class="search_init" /></th>
			</tr>
		</tfoot>
	</table>

<script> 
test();
</script>

<span class='breadcrumb1'>
	<span class='navbar-brand'><small>STUDENTS <i class='ace-icon fa fa-angle-double-right'></i></small></span>
	<?php echo $this->Html->link("<span class='navbar-brand'><small> LIST <i class='ace-icon fa fa-angle-double-right'></i></small></span>", array("controller"=>"Students",'action' => 'studentSearch'),array('data-placement'=>'left','escape' => false)); ?>
</span>