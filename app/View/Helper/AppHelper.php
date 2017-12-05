<?php
/**
 * Application level View Helper
 *
 * This file is application-wide helper file. You can put all
 * application-wide helper-related methods here.
 *
 * @link          http://cakephp.org CakePHP(tm) Project
 * @package       app.View.Helper
 * @since         CakePHP(tm) v 0.2.9
 */

App::uses('Helper', 'View');
/**
 * Application helper
 *
 * Add your application-wide methods in the class below, your helpers
 * will inherit them.
 *
 * @package       app.View.Helper
 */
class AppHelper extends Helper {
	
	public function getAcademicFromBatchId($batch_id) {
		App::import("Model", "Student");
		$model = new Student();
		$conditions= array();
		$conditions['Student.batch_id']=$batch_id;
		$academics = $model->find("all", array(
				'conditions'=>$conditions,
				'fields' => array('Student.dateTime', 'DISTINCT Student.academic_id'),
				'order' => 'Student.academic_id DESC',
		));		
		return $academics;
	}
	
	public function getBatch($id){
		App::import("Model", "Batch");
		$model = new Batch();
		$conditions= array();
		$conditions['Batch.id']=$id;
		$batch_period = $model->find("list", array(
				'conditions'=>$conditions,
				'fields' => 'Batch.batch_period'
		));
		//pr($batch_period);
		return $batch_period[$id];
	}
	
	public function getAcademic($id){
		App::import("Model", "Academic");
		$model = new Academic();
		$conditions= array();
		$conditions['Academic.id']=$id;
		$academic_name = $model->find("list", array(
				'conditions'=>$conditions,
				'fields' => 'Academic.academic_name'
		));
		//pr($academic_name);
		return $academic_name[$id];
	}
	
	public function getCourseTypeFromCmId($cm_id) {
		App::import("Model", "CourseMapping");
		$model = new CourseMapping();
		$conditions= array();
		$conditions['CourseMapping.id']=$cm_id;
		$courseMappingArray = $model->query("select ct.course_mode from course_mappings cm JOIN courses c ON cm.course_id =
				c.id JOIN course_modes ct on c.course_mode_id=ct.id where cm.id=".$cm_id);
		//pr($courseMappingArray);
		$course_type = $courseMappingArray[0]['ct']['course_mode'];
		return $course_type;
	}
	
	public function getProgram($id){
		App::import("Model", "Program");
		$model = new Program();
		$conditions= array();
		$conditions['Program.id']=$id;
		$program_name = $model->find("list", array(
				'conditions'=>$conditions,
				'fields' => 'Program.program_name'
		));
		return $program_name[$id];
	}
	public function getAcademicFromProgId($id){
		App::import("Model", "Program");
		$model = new Program();
		$conditions= array();
		$conditions['Program.id']=$id;
		$program_name = $model->find("list", array(
				'conditions'=>$conditions,
				'fields' => 'Program.academic_id'
		));
		if(isset($program_name[$id])){
			return $this->getAcademic($program_name[$id]);
		}
	}
		
	public function getCourseType($id){
		App::import("Model", "CourseType");
		$model = new CourseType();
		$conditions= array();
		$conditions['CourseType.id']=$id;
		$course_type = $model->find("list", array(
				'conditions'=>$conditions,
				'fields' => 'CourseType.course_type'
		));
		return $course_type[$id];
	}
	
	public function getLecturer($id){
		App::import("Model", "Lecturer");
		$model = new Lecturer();
		$conditions= array();
		$conditions['Lecturer.id']=$id;
		$lecturer_name = $model->find("list", array(
				'conditions'=>$conditions,
				'fields' => 'Lecturer.lecturer_name'
		));
		return $lecturer_name[$id];
	}
	
	public function getCourseCode($id){
		App::import("Model", "CourseType");
		$model = new CourseMapping();
		$conditions= array();
		$conditions['CourseMapping.id']=$id;
		$courseMapping = $model->find("all", array(
				'conditions'=>$conditions,
				'recursive' => 0
		));
		//pr($courseMapping);
		foreach ($courseMapping as $course) {
			return $course['Course']['course_code'];
		}
	}
	public function getCourseName($id){
		App::import("Model", "Course");
		$model = new Course();
		$conditions= array();
		$conditions['Course.id']=$id;
		$course = $model->find("first", array(
				'conditions'=>$conditions,
				'recursive' => 0
		));
		return $course['Course']['course_name'];
	}
	public function getCourseNameFromMapping($student_id,$course_mapping_id,$semester_id,$courseIds,$mode){
		App::import("Model", "CourseStudentMapping");
		
		$model = new CourseStudentMapping();
		$conditions= array();
		$conditions['CourseStudentMapping.student_id']=$student_id;
		array('CourseStudentMapping.course_mapping_id' => $courseIds);
		$conditions['CourseStudentMapping.semester_id']=$semester_id;
		$CSM = $model->find("first", array(
				'conditions'=>$conditions,
				'order' => array('CourseStudentMapping.id desc'),
				'recursive' => 0
		)); 
		if($mode == 2){ return $CSM['CourseStudentMapping']['course_mapping_id'];}//For Edit Mode
		if(isset($CSM['CourseStudentMapping']['course_mapping_id'])){		
			App::import("Model", "CourseMapping");		
			$model = new CourseMapping();
			$conditions= array();
			$conditions['CourseMapping.semester_id']=$semester_id;
			$conditions['CourseMapping.id']=$CSM['CourseStudentMapping']['course_mapping_id'];
			$conditions['CourseMapping.indicator']=0;
			$CM = $model->find("first", array(
					'conditions'=>$conditions,
					'order' => array('CourseMapping.id desc'),
					'recursive' => 0
			));
			return $CM['Course']['course_name'];
		}else{return "";}
	}
	public function getLecturerNameFromMapping($student_id,$course_mapping_id,$semester_id,$courseIds,$mode){
		App::import("Model", "CourseStudentMapping");
		
		$model = new CourseStudentMapping();
		$conditions= array();
		$conditions['CourseStudentMapping.student_id']=$student_id;
		array('CourseStudentMapping.course_mapping_id' => $courseIds);
		$conditions['CourseStudentMapping.semester_id']=$semester_id;
		$CSM = $model->find("first", array(
				'conditions'=>$conditions,
				'order' => array('CourseStudentMapping.id desc'),
				'recursive' => 0
		));
		
		if($mode == 2){ return $CSM['CourseStudentMapping']['user_id'];}//For Edit Mode
		
		if(isset($CSM['CourseStudentMapping']['user_id'])){		
		App::import("Model", "User");
		$model = new User();
		$conditions= array();
		$conditions['User.id']=$CSM['CourseStudentMapping']['user_id'];
		$lect = $model->find("first", array(
				'conditions'=>$conditions,
				'order' => array('User.id desc'),				
				'recursive' => 0
		));
		return $lect['User']['name'];
		}else{return "";}
	}
	
	public function getBatchAcademicProgramFromCmId($id) {
		//echo $id;
		App::import("Model", "CourseMapping");
		$model = new CourseMapping();
		$conditions= array();
		$conditions['CourseMapping.id']=$id;
		$courseMapping = $model->find("all", array(
				'conditions'=>$conditions,
				'recursive' => 0
		));
		//pr($courseMapping);
		foreach($courseMapping as $courseMapping) {}
		$details = array();
		$details['batch'] = $courseMapping['Batch']['batch_period'];
		$details['academic'] = $this->getAcademic($courseMapping['Program']['academic_id']);
		$details['program'] = $courseMapping['Program']['program_name'];
		$details['course'] = $courseMapping['Course']['course_name'];
		$details['course_code'] = $courseMapping['Course']['course_code'];
		$details['course_type'] = $courseMapping['Course']['course_type_id'];
		/* foreach ($courseMapping as $course) {
			return $course['Course']['course_code'];
		} */
		//pr($details);
		return $details;
	}
	
	public function getMonthYearFromId($id) {
		App::import("Model", "MonthYear");
		$model = new MonthYear();
		$conditions= array();
		$conditions['MonthYear.id']=$id;
		$month_year = $model->find("all", array(
				'conditions'=>$conditions,
				'fields' => array('MonthYear.month_id','MonthYear.year'),
				'contain'=>array(
					'Month'=>array(
							'fields'=>array('Month.month_name')
					)
				)
		));
		//pr($month_year);
		foreach ($month_year as $month_year) {
			
		}
		$monthYear = $month_year['Month']['month_name']." - ".$month_year['MonthYear']['year'];
		//pr($month_year);
		return $monthYear;
	}
	
	public function getStudentInfo($id) {
		App::import("Model", "Student");
		$model = new Student();
		$conditions= array();
		$conditions['Student.id']=$id;
		$student = $model->find("first", array(
				'conditions'=>$conditions,
				'fields'=>array('Student.registration_number', 'Student.name'),
				'recursive' => 0
		));
		return $student;
	}
	
	public function getCourseNameFromCMId($id) {
		App::import("Model", "CourseMapping");
		App::import("Model", "Course");
		
		$model = new CourseMapping();
		$conditions= array();
		$conditions['CourseMapping.id']=$id;
		$CId = $model->find("first", array(
				'conditions'=>$conditions,
				'fields'=>array('CourseMapping.course_id'),
				'recursive' => 0
		));	
		
		$model2 = new Course();
		$conditions2= array();
		$conditions2['Course.id']=$CId['CourseMapping']['course_id'];
		$CName = $model2->find("first", array(
				'conditions'=>$conditions2,
				'fields'=>array('Course.course_name'),
				'recursive' => 0
		));
		return $CName['Course']['course_name'];
	}
	
	public function getUserNameFromUserId($user_id) {
		App::import("Model", "User");
		$model = new User();
		$conditions= array();
		$conditions['User.id']=$user_id;
		$user = $model->find("all", array(
				'conditions'=>$conditions,
				'fields' => array('User.username'),
		));
		$username = $user[0]['User']['username'];
		return $username;
	}
	
	public function checkPathAccesstopath($path,$params,$user_id){
		App::import("Model", "Path");
		App::import("Model", "UsersPath");
		$cu_path = $path;
		$cu_params = Null;
		$return = false;
		if(!empty($params)){
			$cu_params = $params;
		}
		//$this->loadModel('Path');
		$PathModel = new Path();
		$path = $PathModel->find('first',array('conditions'=>array('Path.path'=>$cu_path,'Path.params'=>$cu_params),'recursive'=>-1));
		//pr($path);
		if(empty($path)){
			$return = false;
		}else{
			$UsersPath = new UsersPath();
			$check_access = $UsersPath->find('first',array('conditions'=>array('UsersPath.path_id'=>$path['Path']['id'],'UsersPath.user_id'=>$user_id)));
			//pr($check_access);
			if(empty($check_access)){
				$return = false;
			}else{
				$return = true;
			}
		}
		//pr($return);
		return $return;
	}
	
	public function getMonthYearFromMonthYearId($id) {
		App::import("Model", "MonthYear");
		$model = new MonthYear();
		$conditions= array();
		$conditions['MonthYear.id']=$id;
		$month_year = $model->find("all", array(
				'conditions'=>$conditions,
				'recursive' => 0
		));
		$month_year = $month_year[0]['Month']['month_name']." - ".$month_year[0]['MonthYear']['year'];
		return $month_year;
	
	}
	
	public function getGP($courseMappingId = null, $mark = null,$mode = null){
		App::import("Model", "CourseMapping");
		$CM = new CourseMapping();
		$conditions= array();
		$conditions['CourseMapping.id']=$courseMappingId;
		$CMCrPoints = $CM->find("all", array(
				'conditions'=>$conditions,
				'fields' => array('CourseMapping.credit_point','CourseMapping.course_max_marks'),
		));
		$credit_point = $CMCrPoints[0]['CourseMapping']['credit_point'];		
		if($mode == 1){
			return $credit_point;
		}
		$studentMarkGP = 0;
		if(($courseMappingId) && ($mark)){
			if($CMCrPoints[0]['CourseMapping']['course_max_marks'] != 100){
				$mark = ($mark/$CMCrPoints[0]['CourseMapping']['course_max_marks'])*100;
			}
			
			if($mark >= 90 && $mark <= 100 ){
				$studentMarkGP = "10";
			}
			else if($mark >= 80 && $mark <= 89 ){
				$studentMarkGP = "9";
			}
			else if($mark >= 70 && $mark <= 79 ){
				$studentMarkGP = "8";
			}
			else if($mark >= 60 && $mark <= 69 ){
				$studentMarkGP = "7";
			}
			else if($mark >= 50 && $mark <= 59 ){
				$studentMarkGP = "6";
			}
			else if($mark >= 0 && $mark <= 49 ){
				$studentMarkGP = "0";
			}else{
				$studentMarkGP = "0";
			}
		}
		if(($studentMarkGP) && ($studentMarkGP != 'A')) {
			return $totalGP = $credit_point * $studentMarkGP;
		}else{return "0";}
	}
	
	public function getGrad($mark = null){
		$studentMarkGrad = "";
		if($mark){
			if($mark == 'A'){
				$studentMarkGrad = "AAA";
			}else if($mark == 'Withdrawal'){
				$studentMarkGrad = "W";
			}else if($mark == 'Authorised Break of Study'){
				$studentMarkGrad = "ABS";
			}else if($mark >= 90 && $mark <= 100 ){
				$studentMarkGrad = "A++";
			}else if($mark >= 80 && $mark <= 89 ){
				$studentMarkGrad = "A+";
			}else if($mark >= 70 && $mark <= 79 ){
				$studentMarkGrad = "B++";
			}else if($mark >= 60 && $mark <= 69 ){
				$studentMarkGrad = "B+";
			}else if($mark >= 50 && $mark <= 59 ){
				$studentMarkGrad = "C";
			}else if($mark >= 0 && $mark <= 49 ){
				$studentMarkGrad = "RA";
			}
			return $studentMarkGrad;
		}
	
	}
	
	public function getAllRoleAccess($userId){
		App::import("Model", "UsersPath");
		$model = new UsersPath();
		$conditions= array();
		$conditions['UsersPath.user_id'] = $userId;
		$check_access_all = $model->find("list", array(
				'conditions'=>$conditions,
				'fields' => 'UsersPath.path_id'
		));	
		return $check_access_all;		  
	}
	
	public function getCourseNameCrseCodeCmnCodeFromCMId($cm_id) {
		App::import("Model", "CourseMapping");
		$model = new CourseMapping();
		$conditions= array();
		$conditions['CourseMapping.id']=$cm_id;
		$courseMapping = $model->find("all", array(
				'conditions'=>$conditions,
				'recursive' => 0
		));
		//pr($courseMapping);
		$result = array();
		foreach ($courseMapping as $course) {
			$result['course_code'] = $course['Course']['course_code'];
			$result['common_code'] = $course['Course']['common_code'];
			$result['course_name'] = $course['Course']['course_name'];
		}
		return $result;
	}
	
	public function attendanceCount($cm_id, $exam_month_year_id) {
		App::import("Model", "PracticalAttendance");
		$model = new PracticalAttendance();
		$conditions= array();
		$conditions['PracticalAttendance.course_mapping_id']=$cm_id;
		$conditions['PracticalAttendance.month_year_id']=$exam_month_year_id;
		$results = $model->find("count", array(
				'conditions'=>$conditions,
				'recursive' => 0
		));
		return $results;
	}
	public function practicalCount($ese_practical_id, $exam_month_year_id) {
		App::import("Model", "Practical");
		$model = new Practical();
		$conditions= array();
		$conditions['Practical.ese_practical_id']=$ese_practical_id;
		$conditions['Practical.month_year_id']=$exam_month_year_id;
		$results = $model->find("count", array(
				'conditions'=>$conditions,
				'recursive' => 0
		));
		return $results;
	}

	public function generateBarCode($studentName = null,$htmlData = null){
		App::import('Vendor', 'tcpdf_barcodes_2d', array('file' => 'tcpdf_barcodes_2d.php'));
		// sample data to encode
		/*$data_to_encode = $studentName.','.$studentName.','.$studentName;
			
		$barcode=new BarcodeHelper();
			
		// Generate Barcode data
		$barcode->barcode();
		$barcode->setType('C128');
		$barcode->setCode($data_to_encode);
		$barcode->setSize(80,200);
			
		// Generate filename
		$random = rand(0,1000000);
		$file = 'img/barcode/code_'.$random.'.png';
			
		// Generates image file on server
		$barcode->writeBarcodeFile($file);*/
		
		$type = "PDF417";
		$barcodeobj = new TCPDF2DBarcode($htmlData, $type);
		//print_r($barcodeobj);
		$barcodeobj->getBarcodePNG($studentName);
	}
	
	public function generateQrCode($htmlData, $regNum) {
    	include_once "../Vendor/qrlib.php";
    	$PNG_TEMP_DIR = 'img/qr_code/';
    	$filename = $PNG_TEMP_DIR.$regNum.'.png';
    	QRcode::png($htmlData, $filename);
    	
		//$tempDir = EXAMPLE_TMP_SERVERPATH;
		
		//$codeContents = 'your message here...';
		
		//$fileName = 'qrcode_name.png';
		
		//$pngAbsoluteFilePath = $tempDir.$fileName;
		//$urlRelativeFilePath = EXAMPLE_TMP_URLRELPATH.$fileName;
		
		//QRcode::png($codeContents, $pngAbsoluteFilePath);
	}
	
	public function generateModeClass($CGPA, $abs, $withdrawal, $first_attempt){
		/* if(abs($CGPA) >= 9.00 && !$withdrawal && empty($first_attempt)){
			return "First Class - Exemplary";
		}else if((abs($CGPA) >= 7.50) && (abs($CGPA) < 9.00) && !$withdrawal && empty($first_attempt)){
			return "First Class with Distinction";
		}else if((abs($CGPA) >= 6.00) && (abs($CGPA) < 7.50)){
			return "First Class";
		}else if((abs($CGPA) >= 5.00) && (abs($CGPA) < 6.00)){
			return "Second Class";
		} */	
		$opt1E="First Class - Exemplary";
		$opt1T = "cau;rpwg;Gld; Kjy; tFg;gpy;";
		
		$opt2E = "First Class with Distinction";
		$opt2T = "jdpr;rpwg;Gld; Kjy; tFg;gpy;";
		
		$opt3E = "First Class";
		$opt3T = "Kjy; tFg;gpy;";
		
		$opt4E = "Second Class";
		$opt4T = ",uz;lhk; tFg;gpy;";
		
		$finalArray=array();
		if(abs($CGPA) >= 9.00){
			if (!$withdrawal && empty($first_attempt)) {
				$finalArray['E']=$opt1E;
				$finalArray['T']=$opt1T;
			}
			else {
				$finalArray['E']=$opt4E;
				$finalArray['T']=$opt4T;
			}
			return $finalArray;
		}else if((abs($CGPA) >= 7.50) && (abs($CGPA) < 9.00)){
			if (!$withdrawal && empty($first_attempt)) {
				$finalArray['E']=$opt2E;
				$finalArray['T']=$opt2T;
			}
			else {
				//echo $withdrawal;
				$finalArray['E']=$opt3E;
				$finalArray['T']=$opt3T;
			}
			return $finalArray;
		}else if((abs($CGPA) >= 6.00) && (abs($CGPA) < 7.50)){
			if (!$abs && !$withdrawal) {
				$finalArray['E']=$opt3E;
				$finalArray['T']=$opt3T;
			}
			else {
				$finalArray['E']=$opt4E;
				$finalArray['T']=$opt4T;
			}
			return $finalArray;
		}else if((abs($CGPA) >= 5.00) && (abs($CGPA) < 6.00)){
			$finalArray['E']=$opt4E;
			$finalArray['T']=$opt4T;
			return $finalArray;
		}
	}
	
	/* public function generateModeClassTamil($CGPA){
		if(abs($CGPA) >= 9.00){
			return "cau;rpwg;Gld; Kjy; tFg;gpy;";
		}else if((abs($CGPA) >= 7.50) && (abs($CGPA) < 9.00)){
			return "jdpr;rpwg;Gld; Kjy; tFg;gpy;";
		}else if((abs($CGPA) >= 6.00) && (abs($CGPA) < 7.50)){
			return "Kjy; tFg;gpy;";
		}else if((abs($CGPA) >= 5.00) && (abs($CGPA) < 6.00)){
			return ",uz;lhk; tFg;gpy;";
		}
	} */
	
	public function getMonthYears($id) {
		App::import("Model", "MonthYear");
		$model = new MonthYear();
		$conditions= array();
		$conditions['MonthYear.id']=$id;
		$month_year = $model->find("all", array(
				'conditions'=>$conditions,
				'fields' => array('MonthYear.month_id','MonthYear.year'),
				'recursive' => 0
		));
		foreach ($month_year as $month_year) {
				
		}
		
		$res = "";
		$monthId = $month_year['MonthYear']['month_id'];
		$monthYear = $month_year['MonthYear']['year'];
		if(isset($monthId)){
			App::import("Model", "Month");
			$model = new Month();
			$conditions= array();
			$conditions['Month.id']=$monthId;
			$res = $model->find("first", array(
					'conditions'=>$conditions,
					'fields' => array('Month.month_name','Month.tamil_month_name'),
					'order' => array('Month.id desc'),
					'recursive' => 0
			));
		}
		
		$resMonth = array();
		if($res){			
			$resMonth['month'][0] = $res['Month']['month_name'];
			$resMonth['month'][1] = $res['Month']['tamil_month_name'];		
			$resMonth['year'] = $monthYear;
		}
		return $resMonth;
	}
	
	public function saveDegreeCertificate($student_id, $convocation_date, $logged_in_user) {
		$finalArray = array();
		App::import("Model", "DegreeCertificate");
		$model = new DegreeCertificate();
		$conditions= array();
		$conditions['DegreeCertificate.student_id']=$student_id;
		$conditions['DegreeCertificate.convocation_date']=$convocation_date;
		/* $degDetails = $model->find("first", array(
				'conditions'=>$conditions,
				'fields' => array('DegreeCertificate.id'),
				'recursive' => 0
		));
		
		pr($degDetails); */
		$convocation_db_date = date("Y-m-d", strtotime($convocation_date));
		
		$sql = "SELECT * FROM degree_certificates WHERE student_id =$student_id AND convocation_date = '".$convocation_db_date."'";
		$degDetails = $model->query($sql);
		
		$saveData=array();
		$saveData['student_id']=$student_id;
		$saveData['convocation_date']=$convocation_db_date;
		if (isset($degDetails) && !empty($degDetails)) {
			$saveData['id'] = $degDetails[0]['degree_certificates']['id'];
			$saveData['modified_by']=$logged_in_user;
			$saveData['modified'] = date("Y-m-d H:i:s");
		} else {
			$saveData['created_by']=$logged_in_user;
		}
		//pr($saveData);
		$model->save($saveData);
		
		$degCertificateCount = $model->find("count", array(
				'conditions'=>array('DegreeCertificate.student_id'=>$student_id),
				'recursive' => 0
		));
		
		//pr($degCertificateCount);
		$finalArray['convocation_date'] = $convocation_date;
		$finalArray['count'] = $degCertificateCount;
		return $finalArray;
	}
	
	public function retrieveSemesterFromMonthYear($month_year_id, $batch_id, $program_id) {
		App::import("Model", "CourseMapping");
		$model = new CourseMapping();
		$cm = $model->find('first', array(
				'conditions' => array('CourseMapping.month_year_id'=>$month_year_id,
						'CourseMapping.batch_id'=>$batch_id, 'CourseMapping.program_id'=>$program_id
				),
				'fields' => array('CourseMapping.id', 'CourseMapping.month_year_id', 'CourseMapping.semester_id'),
				'contain'=>array(
						'Course' => array(
								'fields' => array('Course.course_code', 'Course.course_name'),
						),
						'Program' => array('fields' => array('Program.id', 'Program.semester_id')),
				)
		));
		//pr($cm);
		return $cm['CourseMapping']['semester_id'];
	}
	
	public function getMarks($cmId, $courseTypeId, $revaluationStatus, $studentId, $examMonth) {
		SWITCH ($courseTypeId) {
			CASE 1:
				if ($revaluationStatus) {
					App::import("Model", "RevaluationExam");
					$model = new RevaluationExam();
					$ese_results = $model->find('all', array(
							'conditions' => array( 
									'RevaluationExam.course_mapping_id'=>$cmId,
									'RevaluationExam.student_id'=>$studentId,
							),
							'fields'=>array('RevaluationExam.id', 'RevaluationExam.course_mapping_id', 
									'RevaluationExam.student_id', 'RevaluationExam.revaluation_marks', 
									'RevaluationExam.month_year_id'
							),
							'contain'=>false,
					));
					$ese_marks = $ese_results[0]['RevaluationExam']['revaluation_marks'];
				} else {
					App::import("Model", "EndSemesterExam");
					$model = new EndSemesterExam();
					$ese_results = $model->find('all', array(
							'conditions' => array(
									'EndSemesterExam.student_id'=>$studentId, 
									'EndSemesterExam.course_mapping_id'=>$cmId,
							),
							'fields'=>array('EndSemesterExam.id', 'EndSemesterExam.course_mapping_id', 'EndSemesterExam.student_id',
									'EndSemesterExam.marks', 'EndSemesterExam.month_year_id'
							),
							'contain'=>false,
					));
					if (count($ese_results)>0) {
						$ese_marks = $ese_results[0]['EndSemesterExam']['marks'];
					}
				}
				//pr($ese_results);
				if ($revaluationStatus == 0 && empty($ese_results)) $ese_marks = "AAA";
				break;
			CASE 2:
			CASE 3:
				//$subModel = "EsePractical";
				//$model = "Practical";
				App::import("Model", "EsePractical");
				$model = new EsePractical();
				//echo $model." ".$cmId." ".$courseTypeId." ".$revaluationStatus." ".$studentId; 
				$ese_results = $model->find('all', array(
						'conditions' => array('EsePractical.course_mapping_id'=>$cmId,),
						'fields'=>array('EsePractical.id'),
						'contain'=>array(
								'Practical'=>array(
										'conditions'=>array('Practical.student_id'=>$studentId),
										'fields'=>array('Practical.id', 'Practical.marks')
								)
						)
				));
				//pr($ese_results);
				$ese_marks = trim($ese_results[0]['Practical'][0]['marks']);
				//echo $ese_marks;
				break;
			CASE 4:
				//$model = "EseProject";
				App::import("Model", "EseProject");
				$model = new EseProject();
				//echo $model." ".$cmId." ".$courseTypeId." ".$revaluationStatus." ".$studentId;
				$ese_results = $model->find('all', array(
						'conditions' => array('EseProject.course_mapping_id'=>$cmId,),
						'fields'=>array('EseProject.id'),
						'contain'=>array(
								'ProjectViva'=>array(
										'conditions'=>array('ProjectViva.student_id'=>$studentId),
										'fields'=>array('ProjectViva.id', 'ProjectViva.marks')
								)
						)
				));
				//pr($ese_results);
				$ese_marks = trim($ese_results[0]['ProjectViva'][0]['marks']);
				//echo $ese_marks;
				break;
			CASE 5:
				App::import("Model", "CaePt");
				$model = new CaePt();
				$caeIdArray = $model->find('all', array(
						'conditions' => array('CaePt.course_mapping_id'=>$cmId,),
						'fields'=>array('CaePt.id'),
						'contain'=>false
				));
				//pr($caeIdArray);
				$caeId = $caeIdArray[0]['CaePt']['id'];
				
				App::import("Model", "ProfessionalTraining");
				$model = new ProfessionalTraining();
				//echo $model." ".$cmId." ".$courseTypeId." ".$revaluationStatus." ".$studentId;
				$ese_results = $model->find('all', array(
						'conditions' => array('ProfessionalTraining.cae_pt_id'=>$caeId,
								'ProfessionalTraining.student_id'=>$studentId,
								'ProfessionalTraining.month_year_id'=>$examMonth,
						),
						'fields'=>array('ProfessionalTraining.id', 'ProfessionalTraining.marks'),
						'contain'=>false
				));
				//pr($ese_results);
				if(count($ese_results)>0) $ese_marks = trim($ese_results[0]['ProfessionalTraining']['marks']);
				else $ese_marks = "AAA";
				//echo $ese_marks;
				break;
		}
		if ($ese_marks=='A' || $ese_marks=='AAA' || $ese_marks=='a' || $ese_marks=='aaa') $ese_marks='AAA';
		return $ese_marks;
	}
	
	public function getTheoryCaeAndEse($courseTypeId, $cm_id, $student_id, $actualMYId, $revaluation_status, $examMonth) {
		App::import("Model", "Student");
		$model = new Student();
		$results=array();
		//if($cm_id == 116) echo $revaluation_status;
		
		$eseCond = array();
		if($examMonth > 0) $eseCond['EndSemesterExam.month_year_id'] = $examMonth;
		if($student_id > 0) $eseCond['EndSemesterExam.student_id'] = $student_id;
		if($cm_id > 0) $eseCond['EndSemesterExam.course_mapping_id'] = $cm_id;
		
		$res = $model->find('all', array(
				'fields'=>array('Student.id'),
				'conditions'=>array('Student.id'=>$student_id),
				'contain'=>array(
						'InternalExam' => array(
								'fields' => array('InternalExam.month_year_id', 'InternalExam.marks', 'InternalExam.student_id', 'InternalExam.course_mapping_id'),
								'conditions' =>array('InternalExam.course_mapping_id' => $cm_id, 'InternalExam.student_id' => $student_id),
								'order'=>array('InternalExam.month_year_id DESC'),
								'limit'=>1
						),
						'EndSemesterExam' => array(
								'fields' => array('EndSemesterExam.month_year_id', 'EndSemesterExam.marks', 'EndSemesterExam.student_id', 'EndSemesterExam.course_mapping_id', 'EndSemesterExam.dummy_number','EndSemesterExam.revaluation_status'),
								'conditions' => array($eseCond),
								'order'=>array('EndSemesterExam.month_year_id DESC'),
								'limit'=>1
						),
						'RevaluationExam' => array(
								'fields' => array('RevaluationExam.id','RevaluationExam.course_mapping_id',
										'RevaluationExam.revaluation_marks'),
								'conditions' => array('RevaluationExam.course_mapping_id'=>$cm_id),
								'order'=>array('RevaluationExam.month_year_id DESC'),
						),
						'StudentMark' => array(
								'fields' => array('StudentMark.month_year_id', 'StudentMark.marks', 'StudentMark.final_marks', 'StudentMark.student_id', 
								'StudentMark.course_mapping_id', 'StudentMark.revaluation_status','StudentMark.status','StudentMark.final_status'),
								'conditions' =>array('StudentMark.course_mapping_id' => $cm_id, 'StudentMark.student_id' => $student_id),
								'order'=>array('StudentMark.month_year_id DESC'),
								'limit'=>1
						),
				),
		));
		//pr($res);
		$results['cae'] = $res[0]['InternalExam'][0]['marks'];
		if ($revaluation_status) {
			
			$ese_marks = $res[0]['EndSemesterExam'][0]['marks'];
			$reval_marks = $res[0]['RevaluationExam'][0]['revaluation_marks'];
			//if ($reval_marks > $ese_marks) $results['ese'] = $reval_marks;
			//else $results['ese'] = $ese_marks;
			
			$results['rev_ese'] = $res[0]['RevaluationExam'][0]['revaluation_marks'];
			$results['ese'] = $res[0]['EndSemesterExam'][0]['marks'];
			$results['status'] =$res[0]['StudentMark'][0]['final_status'];
			$results['total'] = $res[0]['StudentMark'][0]['final_marks'];
			
			//if ($cm_id == 116) echo "*".$ese_marks." ".$reval_marks;
		}
		else if(isset($res[0]['EndSemesterExam'][0]['marks'])){
			$results['ese'] = $res[0]['EndSemesterExam'][0]['marks'];
			$results['status'] = $res[0]['StudentMark'][0]['status'];
			$results['total'] = $res[0]['StudentMark'][0]['marks'];
		}
		else {
			$results['ese'] = "A";
		}
		//$results['total'] = $results['cae']+$results['ese'];
		return $results;
	}
	
	public function getPracticalCaeAndEse($courseTypeId, $cm_id, $student_id, $actualMYId, $revaluation_status, $examMonth) {
		App::import("Model", "CourseMapping");
		$model = new CourseMapping();
		$markArray = array();
		
		$eseCond = array();
		if($examMonth > 0) $eseCond['Practical.month_year_id'] = $examMonth;
		if($student_id > 0) $eseCond['Practical.student_id'] = $student_id;
		
		$results = $model->find('all', array(
				'fields'=>array('CourseMapping.id'),
				'conditions'=>array('CourseMapping.id'=>$cm_id),
				'contain'=>array(
						'CaePractical' => array(
								'fields' => array('CaePractical.course_mapping_id'),
								'conditions' => array('CaePractical.course_mapping_id'=>$cm_id),
								'InternalPractical' => array(
										'conditions'=>array('InternalPractical.student_id'=>$student_id),
										'fields'=>array('InternalPractical.id', 'InternalPractical.cae_practical_id',
												'InternalPractical.marks', 'InternalPractical.student_id', 'InternalPractical.month_year_id'),
										'order'=>array('InternalPractical.id DESC'),
										'limit'=>1,
	
								),
						),
						'EsePractical' => array(
								'fields' => array('EsePractical.course_mapping_id'),
								'conditions' => array('EsePractical.course_mapping_id'=>$cm_id),
								'Practical' => array(
										'fields' => array('Practical.month_year_id', 'Practical.marks', 'Practical.student_id'),
										'conditions' => array(
												$eseCond
										),
										'order'=>array('Practical.id DESC'),
										'limit'=>1,
								),
						),
						'PracticalAttendance' => array(
								'fields' => array('PracticalAttendance.id','PracticalAttendance.month_year_id', 'PracticalAttendance.attendance_status','PracticalAttendance.course_mapping_id'),
								'conditions' => array('PracticalAttendance.month_year_id' => $actualMYId, 'PracticalAttendance.student_id'=>$student_id, 'PracticalAttendance.course_mapping_id'=>$cm_id)
						),
						'StudentMark' => array(
								'fields' => array('StudentMark.month_year_id', 'StudentMark.marks', 'StudentMark.student_id',
										'StudentMark.course_mapping_id', 'StudentMark.revaluation_status','StudentMark.status','StudentMark.final_status'),
								'conditions' =>array('StudentMark.course_mapping_id' => $cm_id, 'StudentMark.student_id' => $student_id),
								'order'=>array('StudentMark.month_year_id DESC'),
								'limit'=>1
						),
				),
		));
		
		$markArray['cae']=$results[0]['CaePractical'][0]['InternalPractical'][0]['marks'];
		if (isset($results[0]['EsePractical'][0]['Practical']) && count($results[0]['EsePractical'][0]['Practical'])>0) {
			$markArray['ese']=$results[0]['EsePractical'][0]['Practical'][0]['marks'];
			$markArray['status'] = $results[0]['StudentMark'][0]['status'];
			$markArray['total'] = $results[0]['StudentMark'][0]['marks'];
		} else {
			$markArray['ese']='A';
		}
		//$markArray['total'] = $markArray['cae']+$markArray['ese'];
		//pr($results);
		//pr($markArray);
		return $markArray;
	}
	
	public function getTheoryPracticalCaeAndEse($courseTypeId, $cm_id, $student_id, $actualMYId, $revaluation_status, $examMonth) {
		App::import("Model", "CourseMapping");
		$model = new CourseMapping();
		$markArray = array();
	
		$eseCond = array();
		if($examMonth > 0) $eseCond['Practical.month_year_id'] = $examMonth;
		if($student_id > 0) $eseCond['Practical.student_id'] = $student_id;
	
		$results = $model->find('all', array(
				'fields'=>array('CourseMapping.id'),
				'conditions'=>array('CourseMapping.id'=>$cm_id),
				'contain'=>array(
						'InternalExam' => array(
								'fields' => array('InternalExam.month_year_id', 'InternalExam.marks', 'InternalExam.student_id', 'InternalExam.course_mapping_id'),
								'conditions' =>array('InternalExam.course_mapping_id' => $cm_id, 'InternalExam.student_id' => $student_id),
								'order'=>array('InternalExam.month_year_id DESC'),
								'limit'=>1
						),
						'EsePractical' => array(
								'fields' => array('EsePractical.course_mapping_id'),
								'conditions' => array('EsePractical.course_mapping_id'=>$cm_id),
								'Practical' => array(
										'fields' => array('Practical.month_year_id', 'Practical.marks', 'Practical.student_id'),
										'conditions' => array(
												$eseCond
										),
										'order'=>array('Practical.id DESC'),
										'limit'=>1,
								),
						),
						'PracticalAttendance' => array(
								'fields' => array('PracticalAttendance.id','PracticalAttendance.month_year_id', 'PracticalAttendance.attendance_status','PracticalAttendance.course_mapping_id'),
								'conditions' => array('PracticalAttendance.month_year_id' => $actualMYId, 'PracticalAttendance.student_id'=>$student_id, 'PracticalAttendance.course_mapping_id'=>$cm_id)
						),
						'StudentMark' => array(
								'fields' => array('StudentMark.month_year_id', 'StudentMark.marks', 'StudentMark.student_id',
										'StudentMark.course_mapping_id', 'StudentMark.revaluation_status','StudentMark.status','StudentMark.final_status'),
								'conditions' =>array('StudentMark.course_mapping_id' => $cm_id, 'StudentMark.student_id' => $student_id),
								'order'=>array('StudentMark.month_year_id DESC'),
								'limit'=>1
						),
				),
		));
	
		$markArray['cae'] = $results[0]['InternalExam'][0]['marks'];
		
		if (isset($results[0]['EsePractical'][0]['Practical']) && count($results[0]['EsePractical'][0]['Practical'])>0) {
			$markArray['ese']=$results[0]['EsePractical'][0]['Practical'][0]['marks'];
			$markArray['total'] = $results[0]['StudentMark'][0]['marks'];
		} else {
			$markArray['ese']='A';
		}
		//$markArray['total'] = $markArray['cae']+$markArray['ese'];
		//pr($results);
		//pr($markArray);
		return $markArray;
	}
	
	public function getProjectCaeAndEse($courseTypeId, $cm_id, $student_id, $actualMYId, $revaluation_status, $examMonth) {
		App::import("Model", "CourseMapping");
		$model = new CourseMapping();
		$markArray = array();
		
		$eseCond = array();
		if($examMonth > 0) $eseCond['ProjectViva.month_year_id'] = $examMonth;
		if($student_id > 0) $eseCond['ProjectViva.student_id'] = $student_id;
		
		$results = $model->find('all', array(
				'fields'=>array('CourseMapping.id'),
				'conditions'=>array('CourseMapping.id'=>$cm_id),
				'contain'=>array(
						'InternalProject' => array(
								'conditions'=>array('InternalProject.student_id'=>$student_id),
								'fields'=>array('InternalProject.id', 'InternalProject.marks',
										'InternalProject.student_id', 'InternalProject.month_year_id'),
								'order'=>array('InternalProject.id DESC'),
								'limit'=>1,
	
						),
						'EseProject' => array(
								'fields' => array('EseProject.course_mapping_id'),
								'conditions' => array('EseProject.course_mapping_id'=>$cm_id),
								'ProjectViva' => array(
										'fields' => array('ProjectViva.month_year_id', 'ProjectViva.marks', 'ProjectViva.student_id'),
										'conditions' => array(
												$eseCond
										),
	
								),
						),
						'StudentMark' => array(
								'fields' => array('StudentMark.month_year_id', 'StudentMark.marks', 'StudentMark.student_id',
										'StudentMark.course_mapping_id', 'StudentMark.revaluation_status','StudentMark.status','StudentMark.final_status'),
								'conditions' =>array('StudentMark.course_mapping_id' => $cm_id, 'StudentMark.student_id' => $student_id),
								'order'=>array('StudentMark.month_year_id DESC'),
								'limit'=>1
						),
				),
		));
		//pr($results);
		$markArray['cae']=$results[0]['InternalProject'][0]['marks'];
		if (isset($results[0]['EseProject'][0]['ProjectViva']) && count($results[0]['EseProject'][0]['ProjectViva'])>0) {
			$markArray['ese']=$results[0]['EseProject'][0]['ProjectViva'][0]['marks'];
			$markArray['total'] = $results[0]['StudentMark'][0]['marks'];
		} else {
			$markArray['ese']='A';
		}
		$markArray['total'] = $markArray['cae']+$markArray['ese'];
		return $markArray;
	}
	
	public function getProfTrainingCae($courseTypeId, $cm_id, $student_id, $actualMYId, $revaluation_status) {
		App::import("Model", "CaePt");
		$model = new CaePt();
		$caeIdArray = $model->find('all', array(
				'conditions' => array('CaePt.course_mapping_id'=>$cm_id,),
				'fields'=>array('CaePt.id'),
				'contain'=>false
		));
		//pr($caeIdArray);
		$caeId = $caeIdArray[0]['CaePt']['id'];
		
		App::import("Model", "ProfessionalTraining");
		$model = new ProfessionalTraining();
		//echo $model." ".$cmId." ".$courseTypeId." ".$revaluationStatus." ".$student_id;
		
		// Actual MonthYearID
		$ese_results = $model->find('all', array(
				'conditions' => array('ProfessionalTraining.cae_pt_id'=>$caeId,
						'ProfessionalTraining.student_id'=>$student_id,
						),
				'fields'=>array('ProfessionalTraining.id', 'ProfessionalTraining.marks'),
				'order'=>array('ProfessionalTraining.month_year_id DESC'),
				'limit'=>1, 
				'contain'=>false
		));
		//pr($ese_results);
		if(count($ese_results)>0) $cae_marks = trim($ese_results[0]['ProfessionalTraining']['marks']);
		else $cae_marks = "AAA";
				
		$markArray['cae']=$cae_marks;
		$markArray['ese']="";
		$markArray['total'] = $markArray['cae']+$markArray['ese'];
		return $markArray;
	}
	
	public function getCaePtIdFromCmId($cmId) {
		App::import("Model", "CaePt");
		$model = new CaePt();
		$caeIdArray = $model->find('all', array(
				'conditions' => array('CaePt.course_mapping_id'=>$cmId,),
				'fields'=>array('CaePt.id'),
				'contain'=>false
		));
		//pr($caeIdArray);
		$caeId = $caeIdArray[0]['CaePt']['id'];
		return $caeId; 
	}
}