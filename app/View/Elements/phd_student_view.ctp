<div class="studentViewCtp">
	<?php 
	if($this->Html->checkPathAccesstopath('PhdStudents/view','',$authUser['id'])){
	echo $this->Html->link(__('View Details'), array('controller'=>'PhdStudents','action'=>'view',$regNo));
	}
	if($this->Html->checkPathAccesstopath('PhdStudents/mapCourses','',$authUser['id'])){
	echo $this->Html->link(__('Map Courses'), array('controller'=>'PhdStudents','action'=>'mapCourses',$regNo));
	}
	if($this->Html->checkPathAccesstopath('PhdStudents/marks','',$authUser['id'])){
	echo $this->Html->link(__('Add/Edit Marks'), array('controller'=>'PhdStudents','action'=>'marks',$regNo));
	}
	if($this->Html->checkPathAccesstopath('PhdStudents/certificate','',$authUser['id'])){
	echo $this->Html->link(__('Course Completion Certificate'), array('controller'=>'PhdStudents','action'=>'certificate',$regNo));
	}
	?>
</div>