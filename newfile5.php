<?php
if ($grpValue == 'Y' && empty($this->commonDatesAssigned[$batch_duration])) {
	//pr($this->commonDatesAssigned);
	if ($batch_duration % 5 == 0 || $batch_duration % 4 == 0) $examDate = $timetableDates[1];
	else if ($batch_duration % 3 == 0) $examDate = $timetableDates[4];
	else if ($batch_duration % 2 == 0) $examDate = $timetableDates[10];
	else if ($batch_duration % 1 == 0) $examDate = $timetableDates[15];
}
else if ($grpValue == 'Y' && count($this->commonDatesAssigned[$batch_duration])>0) {
	$tmpDateArray = $this->commonDatesAssigned[$batch_duration];
	sort($tmpDateArray);
	//pr($tmpDateArray);
	$output = end($tmpDateArray); //echo $output;
	$bKey = array_keys($timetableDates, $output);
	//echo "bKey ";
	//pr($bKey);
	if (isset($bKey[0])) {
		$nextKey = $bKey[0] + $daysDiff;
	} else {
		$nextKey = 10;
	}
	//echo "Prev Key ".$prevKey;
	if (isset($timetableDates[$nextKey])) {
		$examDate = $timetableDates[$nextKey];
	}
	else {
		/* foreach ($this->timetableDatesAssigned as $eDate => $boolValue) {	///
		 if ($boolValue == 0) {
		 $this->timetableDatesAssigned[$cmValue['batch_id']][$cmValue['program_id']][$eDate]=1;	///
		 $examDate = $eDate;
		 break;
		 }
		 } */
		$examDate = "NA";
	}
	//echo "</br>".$examDate;
}
$this->commonDatesAssigned[$batch_duration] = array($examDate);






$data = array();
$timetableArray = array();

$timetableArray['month_year_id'] = $exam_month_year_id;
$timetableArray['semester_id'] = $cmValue['semester_id'];
$timetableArray['course_id'] = $courseId;
$timetableArray['course_mapping_id'] = $cm_id;
$timetableArray['exam_type'] = $cmValue['type'];
$exam_session = "";
if ($cmValue['type'] == "R") $exam_session = 'FN';
else $exam_session = 'AN';
	
$timetableArray['exam_session'] = $exam_session;
//$timetableArray['exam_date'] = date("d-m-Y", strtotime($examDate));
$crseDetails = $this->CourseMapping->getBatchAcademicProgramFromCmId($cm_id);
//pr($crseDetails); die;
$timetableArray['course_code'] = $crseDetails[0]['Course']['course_code'];
$timetableArray['course_name'] = $crseDetails[0]['Course']['course_name'];
$bAcademic = "";
if ($crseDetails[0]['Batch']['academic'] == "JUN")
	$bAcademic = " [A]";
		
	$timetableArray['batch'] = $crseDetails[0]['Batch']['batch_from']."-".$crseDetails[0]['Batch']['batch_to'].$bAcademic;
	$timetableArray['program'] = $crseDetails[0]['Program']['Academic']['short_code'];
	$timetableArray['specialization'] = $crseDetails[0]['Program']['short_code'];
	if ($cmValue['type'] == "A") {
		$noOfUsers = $this->fail_count($cm_id, $exam_month_year_id);
	}
	else if ($cmValue['type'] == "R") {
		$arrQuery = "SELECT count( DISTINCT CourseStudentMapping.student_id ) AS NoOfUsers
							FROM course_student_mappings CourseStudentMapping
							JOIN students Student ON Student.discontinued_status =0
							JOIN course_mappings CourseMapping ON CourseMapping.id = CourseStudentMapping.course_mapping_id
							WHERE Student.discontinued_status =0
							AND CourseStudentMapping.indicator = 0
							AND CourseMapping.id =".$cm_id;
		$cnt = $this->StudentMark->query($arrQuery);
		$noOfUsers = $cnt[0][0]['NoOfUsers'];
	}
		
	$timetableArray['count'] = $noOfUsers;
		
	array_push($this->timetableArray, $timetableArray);
		
	$data['Timetable']['month_year_id'] = $exam_month_year_id;
	$data['Timetable']['course_mapping_id'] = $cm_id;
	$data['Timetable']['type'] = $cmValue['type'];
	$data['Timetable']['exam_session'] = 'FN';
	//$data['Timetable']['exam_date'] = $examDate;

	$data_exists=$this->Timetable->find('first', array(
			'conditions' => array(
					'Timetable.course_mapping_id'=>$cm_id,
					'Timetable.month_year_id'=>$exam_month_year_id,
			),
			'fields' => array('Timetable.id'),
			'recursive' => 0
	));
	if(isset($data_exists['Timetable']['id']) && $data_exists['Timetable']['id']>0) {
		$id = $data_exists['Timetable']['id'];
		$data['Timetable']['id'] = $id;
		$data['Timetable']['modified_by'] = $this->Auth->user('id');
		$data['Timetable']['modified'] = date("Y-m-d H:i:s");
	}
	else {
		$this->Timetable->create($data);
		$data['Timetable']['created_by'] = $this->Auth->user('id');
	}
