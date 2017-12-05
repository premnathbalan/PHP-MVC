<div class="studentViewCtp">
	<?php 
	if($this->Html->checkPathAccesstopath('Students/view','',$authUser['id'])){
	echo $this->Html->link(__('View Details'), array('controller'=>'Students','action'=>'view',$studentId));
	}if($this->Html->checkPathAccesstopath('Students/student_marks','',$authUser['id'])){
	echo $this->Html->link(__('View Marks'), array('controller'=>'Students','action'=>'student_marks',$studentId));
	}if($this->Html->checkPathAccesstopath('Students/manage_courses','',$authUser['id'])){
	echo $this->Html->link(__('Manage Courses'), array('controller'=>'Students','action'=>'manage_courses',$studentId));
	}if($this->Html->checkPathAccesstopath('Students/credits','',$authUser['id'])){
	echo $this->Html->link(__('View Credits'), array('controller'=>'Students','action'=>'credits',$studentId));
	}if($this->Html->checkPathAccesstopath('Students/attendance','',$authUser['id'])){ 
	echo $this->Html->link(__('Attendance'), array('controller'=>'Students','action'=>'attendance',$studentId));
	}if($this->Html->checkPathAccesstopath('Students/signature','',$authUser['id'])){
	echo $this->Html->link(__('Add Signature'), array('controller'=>'Students','action'=>'signature',$studentId));
	}if($this->Html->checkPathAccesstopath('Students/remarks','',$authUser['id'])){
	echo $this->Html->link(__('View Remarks'), array('controller'=>'Students','action'=>'remarks',$studentId));
	}if($this->Html->checkPathAccesstopath('Students/withheldAll','',$authUser['id'])){
	echo $this->Html->link(__('Withheld'), array('controller'=>'Students','action'=>'withheldAll',$studentId));
	}if($this->Html->checkPathAccesstopath('Students/ncc','',$authUser['id'])){
	echo $this->Html->link(__('View NCC'), array('controller'=>'Students','action'=>'ncc',$studentId));
	}if($this->Html->checkPathAccesstopath('Students/transferCourses','',$authUser['id'])){
		if($student['Student']['student_type_id'] == '3' || $student['Student']['student_type_id'] == '5'){
			echo $this->Html->link(__('Transfer Courses'), array('controller'=>'Students','action'=>'transferCourses',$studentId));
		}
	}if($this->Html->checkPathAccesstopath('Students/chgStatus','',$authUser['id'])){
	echo $this->Html->link(__('Status'), array('controller'=>'Students','action'=>'chgStatus',$studentId));
	}?>
</div>