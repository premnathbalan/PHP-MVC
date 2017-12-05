<?php
/**
 * Application level Controller
 *
 * This file is application-wide controller file. You can put all
 * application-wide controller-related methods here.
 *
 * @link          http://cakephp.org CakePHP(tm) Project
 * @package       app.Controller
 * @since         CakePHP(tm) v 0.2.9
 */

//include_once("xlsxwriter.class.php");

App::uses('Controller', 'Controller');
App::import('Vendor', 'PHPExcel', array('file' => 'PHPExcel'.DS.'IOFactory.php'));
App::import('Helper', 'AppHelper');

/**
 * Application Controller
 *
 * Add your application-wide methods in the class below, your controllers
 * will inherit them.
 *
 * @package		app.Controller
 * @link		http://book.cakephp.org/2.0/en/controllers.html#the-app-controller
 */
class AppController extends Controller {
	public $components = array(
		'Cookie',
		'Session',
		'Flash',
        'Auth' => array(			
			'loginRedirect' => array('controller' => 'users', 'action' => 'dashboard'),
			'logoutRedirect' => array('controller' => 'users', 'action' => 'login'),
			'authError' => 'You must be logged in to view this page.',
			'loginError' => 'Invalid Username or Password entered, please try again.'
		)
	);
	public $uses=array("ProfessionalTraining");
	public $theory = array('1', '3');
	public $practical_or_studio = array('2', '6');
	public $project = array('4');
	public $professional_training = array('5');
	
	
	// only allow the login controllers only
	public function beforeFilter() {
		$this->Auth->allow('login');
	
		$auth_user=array();
		
		
		if($this->here != '/users/login'){
			$this->Session->write('Auth.redirect', $this->here);
		}
		if($this->Auth->user()){
			$auth_user = $this->Auth->user();
			
			//authentication check for admin routing
			if($auth_user['id'] == 1 && ($this->params['admin']==1 || $this->params['prefix']=='admin')&&($this->params['action']!='login')){
				$this->Auth->logout();
				return $this->redirect(array('controller'=>'users','action' => 'login','admin'=>false));
			}
			$cu_path = $this->params['controller']."/".$this->params['action'];
			if($auth_user['id'] !=1 && $this->checkPathAccess($auth_user['id']) && $cu_path != "users/admin_dashboard"){
				$this->Flash->error(__("Sorry.. You Don't have access to that link!..."));
				return $this->redirect(array('controller'=>'users','action' => 'access_denied'));
			}
		}
		//layout selection
		if($this->params['admin']==1 || $this->params['prefix']=='admin'){
			$this->layout = 'admin';
		}
	
		$this->set('authUser', $auth_user);
	}
	
	public function isAuthorized($user) {
		// Here is where we should verify the role and give access based on role
		return true;
	}
	
	public function checkPathAccess($user_id){
		$this->loadModel('Path');
		$cu_path = $this->params['controller']."/".$this->params['action'];
		//echo $cu_path;
		$cu_params = Null;
		$return = false;
		//pr($this->request['pass']);
		//if(!empty($this->params->named)){
		if(!empty($this->request['pass'])){
			foreach ($this->request['pass'] as $key=>$val){
				if(!is_null($cu_params)){
					//$cu_params.="/".$key.":".$val;
					$cu_params.="/".$val;
				}else{
					//$cu_params=$key.":".$val;
					$cu_params=$val;
				}
			}
		}
		//echo $cu_params;
		$path = $this->Path->find('first',array('conditions'=>array('Path.path'=>$cu_path,'Path.params'=>$cu_params),'recursive'=>-1));
		/* $dbo = $this->CourseMapping->getDatasource();
		$logs = $dbo->getLog();
		$lastLog = end($logs['log']);
		echo $lastLog['query']; */
		//pr($path);
		if(empty($path)){
			$return = false;
		}else{
			$this->loadModel('UsersPath');
			$check_access = $this->UsersPath->find('first',array('conditions'=>array('UsersPath.path_id'=>$path['Path']['id'],'UsersPath.user_id'=>$user_id)));
			if(empty($check_access)){
				$return = true;
			}else{
				$return = false;
			}
		}
		return $return;
	}
	public $months = array("1" => "JAN", "2"=>"FEB", "3"=>"MAR", "4"=>"APR", "5"=>"MAY", "6"=>"JUN",
			"7"=>"JUL", "8"=>"AUG", "9"=>"SEP", "10"=>"OCT", "11"=>"NOV", "12"=>"DEC"
	);
	
	public function course_types() {
		$this->loadModel('CourseType');
		$courseTypes = $this->CourseType->find('list'/* , array('conditions'=>array('CourseType.id'=>3)) */);
		return $courseTypes;
	}
	
	public function download_template($batch, $academicDetails, $programDetails, $month_year, $studentArray, $cm, $assessment_number, $type, $course_type, $month_year_id, $absUsers) {
		
		$phpExcel = new PHPExcel();
		$phpExcel->setActiveSheetIndex(0);
		$sheet = $phpExcel->getActiveSheet();
		$sheet->setTitle("Test");
		
		$sheet->getProtection()->setSheet(true);
		
		$sheet->getRowDimension(1)->setRowHeight('18');
		$sheet->getRowDimension(2)->setRowHeight('18');
		$sheet->getRowDimension(3)->setRowHeight('18');
		$sheet->getRowDimension(5)->setRowHeight('18');
		$sheet->getRowDimension(6)->setRowHeight('18');
		
		$sheet->setCellValue("A1", "MonthYear");
		$sheet->setCellValue("A2", "Course");
		$sheet->setCellValue("A3", "Branch");
		$sheet->setCellValue("E1", "Batch");
			
		$sheet->setCellValue("B1", ":");
		$sheet->setCellValue("B2", ":");
		$sheet->setCellValue("B3", ":");
		$sheet->setCellValue("F1", ":");
			
		$sheet->setCellValue("C1", $month_year);
		$sheet->setCellValue("C2", $academicDetails['name']);
		$sheet->setCellValue("C3", $programDetails['name']);
		$sheet->setCellValue("G1", $batch);
			
		$sheet->setCellValue("A5", "Max Assessment Marks");
		$sheet->setCellValue("E2", "Assessment");
			
		$sheet->setCellValue("C5", ":");
		$sheet->setCellValue("F2", ":");
			
		//$sheet->setCellValue("G9", $marks);
		$sheet->setCellValue("G2", $assessment_number);
			
		$sheet->setCellValue("A6", "S. No.");
		$sheet->setCellValue("B6", "Reg. Number");
		$sheet->setCellValue("C6", "Student Name");
			
		$i=7; $j=1;
		foreach ($studentArray as $key => $student) {
			//pr($student);
			$flag = 0;
			$csmTmpArray = $student['CourseStudentMapping'];
			//pr($csmTmpArray);
			if (empty($csmTmpArray)) $flag=1;
			foreach ($csmTmpArray as $key => $csmArray) {
				if ($csmArray['type']!="ABS" && $csmArray['CourseMapping']['month_year_id']!=$month_year) {
					$flag = 1;
				}
			}
			
			if ($flag) {
				//echo $flag." ".$student['Student']['id']."</br>";
				$sheet->getRowDimension($i)->setRowHeight('18');
				$sheet->setCellValue('A'.$i, $j);
				$sheet->setCellValue('B'.$i, $student['Student']['registration_number']);
				$sheet->setCellValue('C'.$i, $student['Student']['name']);
				$i++; $j++;
			}
		}
		//pr($absUsers);
		
		if ($absUsers!='-' && isset($absUsers) && count($absUsers)>0) {
			foreach ($absUsers as $student_id => $absArray) {
				$sheet->getRowDimension($i)->setRowHeight('18');
				$sheet->setCellValue('A'.$i, $j."-"."ABS"."-".$absArray['batch_id']."-".$absArray['program_id']);
				$sheet->setCellValue('B'.$i, $absArray['registration_number']);
				$sheet->setCellValue('C'.$i, $absArray['name']);
				$i++; $j++;
			}
		}
		
		$column='D';
		
		//pr($cm);
		$courseMarks = $this->CourseMapping->getCourseMarks($cm);
	//	pr($cm);
		//pr($courseMarks);		
		
		//die;
		foreach ($cm as $cmId => $array) {
			// echo $cmId;
			//pr($array);
			//exit;
			$cell = $sheet->setCellValue($column.'6', $array['course_code']);
			
			if ($type == "CAE") {
				if ($courseMarks[$cmId]['course_type_id']==5) $value = $courseMarks[$cmId]['course_max_marks'];
				else $value = $courseMarks[$cmId]['max_cae_mark'];
			}
			else if($type == "ESE") {
				$value = $courseMarks[$cmId]['max_ese_mark'];
			}
			$sheet->setCellValue($column.'5', $value);
			
			$csm_array = $array['csm'];
		//	pr($csm_array); 
			// pr($studentArray);
			// exit;
			$m=7;
			foreach ($studentArray as $key => $student) {
				if ($csm_array[$student['Student']['id']] == 0) {
					$cell = $sheet->setCellValue($column.$m, "");
					$sheet->getStyle("$column$m:$column$m")->getProtection()->setLocked(PHPExcel_Style_Protection::PROTECTION_UNPROTECTED);
				}
				else {   
					$cell = $sheet->setCellValue($column.$m, "NA");
				}
				$m++;
			}
			$column++;
		}
		
		$download_filename=$academicDetails['short_code']."_".$programDetails['short_code']."_".$course_type."_".$type;
		header("Content-Type: application/vnd.ms-excel");
		header("Content-Disposition: attachment; filename=\"$download_filename.xls\"");
		header("Cache-Control: max-age=0");
		$objWriter = PHPExcel_IOFactory::createWriter($phpExcel, "Excel5");
		$objWriter->save("php://output");
		exit;
		$this->layout = null;
		$this->autoLayout = false;
	}
	
	public function getCourseCodeFromExcel($col, $row, $highestColumnIndex, $filename) {
		$objPHPExcel = PHPExcel_IOFactory::load($filename);
		$worksheet = $objPHPExcel->setActiveSheetIndex(0);
		
		$arrCourseCodeExcelAddress = array();
		$arrCourseCode = array();
		$excelCourseCodeCells = array();
		for ($col = 3; $col < $highestColumnIndex; ++$col) {
			$cell = $worksheet->getCellByColumnAndRow($col, $row);
			$courseCode = $cell->getValue();
			//pr($courseCode);
			if ($courseCode <> "") {
				$cell1 = $worksheet->getCellByColumnAndRow($col, 5);
				$marks = $cell1->getValue();
				$arrCourseCode[$courseCode] = $marks; 
				$excelCourseCodeCells[$courseCode] = array('col'=>$col, 'row'=> $row);
			}
			//$courseMapping[] = $cell->getValue();
		}
		$arrCourseCodeExcelAddress['courseCode'] = $arrCourseCode;
		$arrCourseCodeExcelAddress['excelCellAddress'] = $excelCourseCodeCells;
		return $arrCourseCodeExcelAddress;
	}
	
	public function publishWebsiteStudentData($studentArray) {
		$phpExcel = new PHPExcel();
		$phpExcel->setActiveSheetIndex(0);
		$sheet = $phpExcel->getActiveSheet();
		$sheet->getRowDimension('1')->setRowHeight('18');
		$sheet->setTitle("Results_to_website_student_data");
			
		$sheet->setCellValue("A1", "STUDENT ID");
		$sheet->setCellValue("B1", "REGISTER NUMBER");
		$sheet->setCellValue("C1", "STUDENT NAME");
		$sheet->setCellValue("D1", "DATE OF BIRTH");
		$sheet->setCellValue("E1", "ACADEMIC");
		$sheet->setCellValue("F1", "PROGRAM");
		$sheet->setCellValue("G1", "BATCH");
		
		$i=2;
		foreach ($studentArray as $key => $student) {	
			$sheet->getRowDimension($i)->setRowHeight('18');
			$sheet->setCellValue('A'.$i, $student['Student']['id']);
			$sheet->setCellValue('B'.$i, $student['Student']['registration_number']);
			$sheet->setCellValue('C'.$i, $student['Student']['name']);
			$dob = date( "d-m-Y", strtotime(h($student['Student']['birth_date'])));
			$sheet->setCellValue('D'.$i, $dob);
			$sheet->setCellValue('E'.$i, $student['Academic']['academic_name']);
			$sheet->setCellValue('F'.$i, $student['Program']['program_name']);
			$sheet->setCellValue('G'.$i, ($student['Batch']['batch_from'].'-'.$student['Batch']['batch_to']));
			$i++;
		}
		
		$download_filename="Results_to_website_student_data-".date('d-M-Y h:i:s');
		header("Content-Type: application/vnd.ms-excel");
		header("Content-Disposition: attachment; filename=\"$download_filename.xls\"");
		header("Cache-Control: max-age=0");
		$objWriter = PHPExcel_IOFactory::createWriter($phpExcel, "Excel5");
		$objWriter->save("php://output");
		exit;
	}
	
	/* public function resultsToWebsiteMarkData($studentArray) {
		$row = 1; // 1-based index
		$col = 0;
		$phpExcel = new PHPExcel();
		$phpExcel->setActiveSheetIndex(0);
		$sheet = $phpExcel->getActiveSheet();
		$sheet->getRowDimension('1')->setRowHeight('18');
		$sheet->setTitle("Results_to_website_mark_data");	
	
		$sheet->setCellValueByColumnAndRow($col, $row, "STUDENT ID");$col++;
		$sheet->setCellValueByColumnAndRow($col, $row, "REGISTER NUMBER");$col++;
		$sheet->setCellValueByColumnAndRow($col, $row, "STUDENT NAME");$col++;
		$sheet->setCellValueByColumnAndRow($col, $row, "DATE OF BIRTH");$col++;
		$sheet->setCellValueByColumnAndRow($col, $row, "SECTION");$col++;
		
		$sheet->setCellValueByColumnAndRow($col, $row, "MARK ID");$col++;
		$sheet->setCellValueByColumnAndRow($col, $row, "COURSE CODE");$col++;
		$sheet->setCellValueByColumnAndRow($col, $row, "COURSE NAME");$col++;
		$sheet->setCellValueByColumnAndRow($col, $row, "CAE");$col++;
		$sheet->setCellValueByColumnAndRow($col, $row, "ESE");$col++;
		$sheet->setCellValueByColumnAndRow($col, $row, "TOTAL");$col++;
		$sheet->setCellValueByColumnAndRow($col, $row, "PUBLISHED RESULT");$col++;
		$sheet->setCellValueByColumnAndRow($col, $row, "MAX COURSE MARK");$col++;
		$sheet->setCellValueByColumnAndRow($col, $row, "COURSE TYPE");$col++;	
		$row++;
	
		foreach ($studentArray as $key => $result) {
			$stuInternalArray = array();
			$stuESArray = array();
			$stuDNArray = array();
			
			//For Theory Internal
			for($p=0;$p<count($result['InternalExam']);$p++){
				$stuInternalArray[$result['InternalExam'][$p]['course_mapping_id']] = $result['InternalExam'][$p]['marks'];
			}
			//For Theory External
			for($p=0;$p<count($result['EndSemesterExam']);$p++){
				$stuESArray[$result['EndSemesterExam'][$p]['course_mapping_id']] = $result['EndSemesterExam'][$p]['marks'];				
			}
				
			//For Practical	Internal
			for($q=0;$q<count($result['InternalPractical']);$q++){
				$stuInternalArray[$result['InternalPractical'][$q]['CaePractical']['course_mapping_id']] = $result['InternalPractical'][$q]['marks'];
			}
			//For Practical	External
			for($q=0;$q<count($result['Practical']);$q++){
				$practicalExternalMarks = $result['Practical'][$q]['marks'];
				if($practicalExternalMarks == '0'){$practicalExternalMarks = " 0";}
				$stuESArray[$result['Practical'][$q]['EsePractical']['course_mapping_id']] = $practicalExternalMarks;
				$practicalExternalMarks = "";
			}
			//For Project Internal
			for($q=0;$q<count($result['InternalProject']);$q++){
				$stuInternalArray[$result['InternalProject'][$q]['course_mapping_id']] = $result['InternalProject'][$q]['marks'];
			}
			//For Project External
			for($q=0;$q<count($result['ProjectViva']);$q++){
				$projectExternalMarks = $result['ProjectViva'][$q]['marks'];
				if($projectExternalMarks == '0'){$projectExternalMarks = "&nbsp;0";}
				$stuESArray[$result['ProjectViva'][$q]['EseProject']['course_mapping_id']] = $projectExternalMarks;
				$projectExternalMarks = "";
			}	
			for($p=0;$p<count($result['StudentMark']);$p++){
				$col = 0;
				$sheet->getRowDimension($row)->setRowHeight('18');
				$sheet->setCellValueByColumnAndRow($col, $row, $result['Student']['id']);$col++;
				$sheet->setCellValueByColumnAndRow($col, $row, $result['Student']['registration_number']);$col++;
				$sheet->setCellValueByColumnAndRow($col, $row, $result['Student']['name']);$col++;
				$dob = date( "d-m-Y", strtotime(h($result['Student']['birth_date'])));
				$sheet->setCellValueByColumnAndRow($col, $row, $dob);$col++;
				$sheet->setCellValueByColumnAndRow($col, $row, $result['Section']['name']);$col++;
				
				$IEV = "A"; $ESV = "A"; 
				$courseMId = $result['StudentMark'][$p]['course_mapping_id'];
					
				if(isset($stuInternalArray[$courseMId])){
					$IEV = $stuInternalArray[$courseMId];
				}
				if(isset($stuESArray[$courseMId])){
					$ESV = $stuESArray[$courseMId];
				}
				if(isset($stuDNArray[$courseMId])){
					$DNV = $stuDNArray[$courseMId];
				}
				$sheet->setCellValueByColumnAndRow($col, $row, $result['StudentMark'][$p]['id']);$col++;
				$sheet->setCellValueByColumnAndRow($col, $row, $result['StudentMark'][$p]['CourseMapping']['Course']['course_code']);$col++;
				$sheet->setCellValueByColumnAndRow($col, $row, $result['StudentMark'][$p]['CourseMapping']['Course']['course_name']);$col++;
	
				if($IEV){
					$sheet->setCellValueByColumnAndRow($col, $row, $IEV);$col++;
				}else{
					$sheet->setCellValueByColumnAndRow($col, $row, '0');$col++;
				}
	
				if($ESV){
					$sheet->setCellValueByColumnAndRow($col, $row, $ESV);$col++;
				}else{
					$sheet->setCellValueByColumnAndRow($col, $row, '0');$col++;
				}
	
				if($result['StudentMark'][$p]['marks']){
					$sheet->setCellValueByColumnAndRow($col, $row, $result['StudentMark'][$p]['marks']);$col++;
				}else{
					$sheet->setCellValueByColumnAndRow($col, $row, '');$col++;
				}
	
	
				if($result['StudentMark'][$p]['status'] == 'Fail'){
					$sheet->setCellValueByColumnAndRow($col, $row, 'RA');$col++;
				}else {
					$sheet->setCellValueByColumnAndRow($col, $row, $result['StudentMark'][$p]['status']);$col++;
				}
				$sheet->setCellValueByColumnAndRow($col, $row, $result['StudentMark'][$p]['CourseMapping']['Course']['course_max_marks']);$col++;
				$sheet->setCellValueByColumnAndRow($col, $row, $result['StudentMark'][$p]['CourseMapping']['Course']['CourseType']['course_type']);
				$row++;
			}
			
		}
	
		$download_filename="Results_to_website_mark_data-".date('d-M-Y h:i:s');
		header("Content-Type: application/vnd.ms-excel");
		header("Content-Disposition: attachment; filename=\"$download_filename.xls\"");
		header("Cache-Control: max-age=0");
		$objWriter = PHPExcel_IOFactory::createWriter($phpExcel, "Excel5");
		$objWriter->save("php://output");
		exit;
	} */
	
	public function resultsToWebsiteMarkData($results, $examMonth, $reval_mode) {
		//pr($results);
		$getWithheldStuRes = $this->StudentWithheld->find("list", array(
				'conditions' => array('StudentWithheld.indicator' => 0, 'StudentWithheld.month_year_id' => $examMonth
				),
				'fields' =>array('StudentWithheld.student_id'),
				'contain'=>false
		));
		//pr($getWithheldStuRes);
		
		$csv_header = '';
		$csv_row = '';
		
		$csv_header .= 'STUDENT_ID,REGISTER_NUMBER,STUDENT_NAME,DATE_OF_BIRTH,SECTION,MARK_ID,SEMESTER,EXAMTYPE,COURSE_CODE,COURSE_NAME,CAE,ESE,TOTAL,PUBLISHED_RESULT,MAX_COURSE_MARK,COURSE_TYPE';
		$csv_header .= "\n";
		
		foreach ($results as $registration_number => $resultArray) {
			//$cnt = $cnt + count($resultArray);
			//pr($resultArray);
			foreach ($resultArray as $key => $result) {
				if (in_array($result['student_id'], $getWithheldStuRes)) {
					$result['cae'] = "-";
					$result['ese'] = "-";
					$result['total'] = "-";
					$result['status'] = "W";
				}
				$csv_row.='"' . $result['student_id'] . '",';
				$csv_row.='"' . $registration_number . '",';
				$csv_row.='"' . $result['name'] . '",';
				$dob = date( "d-m-Y", strtotime(h($result['birth_date'])));
				$csv_row.='"' . $dob .'",';
				$csv_row.='"' . $result['section'] . '",';
				$csv_row.='"' . $result['sm_id'] . '",';
				$csv_row.='"' . $result['actual_semester_id'] . '",';
				$csv_row.='"' . $result['type'] . '",';
				$csv_row.='"' . $result['course_code'] . '",';
				$csv_row.='"' . $result['course_name'] . '",';
				$csv_row.='"' . $result['cae'] . '",';
				
				if (empty($result['ese']) && $result['attendance']==0 && ($result['course_type']=='Theory' || $result['course_type']=='Practical' || $result['course_type']=='Studio')) {
					//Changed $ese = "AAA"; to 0
					$ese = "0";
				}
				else {
					$ese = $result['ese'];
				}
				$csv_row.='"' . $ese . '",';
				
				$csv_row.='"' . $result['total'] . '",';
				
				if($result['status'] == 'Fail'){
					$status = 'RA';
				}else {
					$status = $result['status'];
				}
				$csv_row.='"' . $status . '",';
				$csv_row.='"' . $result['course_max_marks'] . '",';
				$csv_row.='"' . $result['course_type'] . '",';
				$csv_row .= "\n";
	
			}
			
		}
		$download_filename="Results_to_website_mark_data-".$reval_mode."_".date('d-M-Y h:i:s');
		header("Content-type: application/csv");
		header("Content-Disposition: attachment; filename=\"$download_filename.csv\"");
		
		echo $csv_header.$csv_row;
		exit;
		//echo count($results);
		/* 
		$row = 1; // 1-based index
		$col = 0;
		$phpExcel = new PHPExcel();
		$phpExcel->setActiveSheetIndex(0);
		$sheet = $phpExcel->getActiveSheet();
		$sheet->getRowDimension('1')->setRowHeight('18');
		$sheet->setTitle("Website_mark_data");
	
		$sheet->setCellValueByColumnAndRow($col, $row, "STUDENT ID");$col++;
		$sheet->setCellValueByColumnAndRow($col, $row, "REGISTER NUMBER");$col++;
		$sheet->setCellValueByColumnAndRow($col, $row, "STUDENT NAME");$col++;
		$sheet->setCellValueByColumnAndRow($col, $row, "DATE OF BIRTH");$col++;
		$sheet->setCellValueByColumnAndRow($col, $row, "SECTION");$col++;
	
		$sheet->setCellValueByColumnAndRow($col, $row, "MARK ID");$col++;
		$sheet->setCellValueByColumnAndRow($col, $row, "COURSE CODE");$col++;
		$sheet->setCellValueByColumnAndRow($col, $row, "COURSE NAME");$col++;
		$sheet->setCellValueByColumnAndRow($col, $row, "CAE");$col++;
		$sheet->setCellValueByColumnAndRow($col, $row, "ESE");$col++;
		$sheet->setCellValueByColumnAndRow($col, $row, "TOTAL");$col++;
		$sheet->setCellValueByColumnAndRow($col, $row, "PUBLISHED RESULT");$col++;
		$sheet->setCellValueByColumnAndRow($col, $row, "MAX COURSE MARK");$col++;
		$sheet->setCellValueByColumnAndRow($col, $row, "COURSE TYPE");$col++;
		$row++; */
	
		/* foreach ($results as $registration_number => $resultArray) {
			foreach ($resultArray as $key => $result) {
				$col = 0;
					
				$sheet->getRowDimension($row)->setRowHeight('18');
				$sheet->setCellValueByColumnAndRow($col, $row, $result['student_id']);$col++;
				$sheet->setCellValueByColumnAndRow($col, $row, $registration_number);$col++;
				$sheet->setCellValueByColumnAndRow($col, $row, $result['name']);$col++;
				$dob = date( "d-m-Y", strtotime(h($result['birth_date'])));
				$sheet->setCellValueByColumnAndRow($col, $row, $dob);$col++;
				$sheet->setCellValueByColumnAndRow($col, $row, $result['section']);$col++;
	
				$sheet->setCellValueByColumnAndRow($col, $row, $result['sm_id']);$col++;
				$sheet->setCellValueByColumnAndRow($col, $row, $result['course_code']);$col++;
				$sheet->setCellValueByColumnAndRow($col, $row, $result['course_name']);$col++;
	
				$sheet->setCellValueByColumnAndRow($col, $row, $result['cae']);$col++;
	
				//$sheet->setCellValueByColumnAndRow($col, $row, $result['ese']);$col++;
				if (empty($result['ese']) && $result['attendance']==0 && $result['course_type']=='Theory') {
					$sheet->setCellValueByColumnAndRow($col, $row, "A");$col++;
				}
				else {
					$sheet->setCellValueByColumnAndRow($col, $row, $result['ese']);$col++;
				}
				$sheet->setCellValueByColumnAndRow($col, $row, $result['total']);$col++;
	
				if($result['status'] == 'Fail'){
					$sheet->setCellValueByColumnAndRow($col, $row, 'RA');$col++;
				}else {
					$sheet->setCellValueByColumnAndRow($col, $row, $result['status']);$col++;
				}
				$sheet->setCellValueByColumnAndRow($col, $row, $result['course_max_marks']);$col++;
				$sheet->setCellValueByColumnAndRow($col, $row, $result['course_type']);
				$row++;
			}
		}
	
		$download_filename="Results_to_website_mark_data-".date('d-M-Y h:i:s');
		header("Content-Type: application/vnd.ms-excel");
		header("Content-Disposition: attachment; filename=\"$download_filename.xlsx\"");
		header("Cache-Control: max-age=0");
		$objWriter = PHPExcel_IOFactory::createWriter($phpExcel, "Excel2007");
		$objWriter->save("php://output");
		exit; */
	}
	public function publishWebsiteMarkData($studentArray) {
		$row = 1; // 1-based index
		$col = 0;
		$phpExcel = new PHPExcel();
		$phpExcel->setActiveSheetIndex(0);
		$sheet = $phpExcel->getActiveSheet();
		$sheet->getRowDimension('1')->setRowHeight('18');
		$sheet->setTitle("Mark_Data_COE");
		
		
		$sheet->setCellValueByColumnAndRow($col, $row, "STUDENT ID");$col++;
		$sheet->setCellValueByColumnAndRow($col, $row, "REGISTER NUMBER");$col++;
		$sheet->setCellValueByColumnAndRow($col, $row, "STUDENT NAME");$col++;
		
		for($p=0;$p<12;$p++){
			$sheet->setCellValueByColumnAndRow($col, $row, "MARK Id");$col++;
			$sheet->setCellValueByColumnAndRow($col, $row, "DUMMY No.");$col++;
			$sheet->setCellValueByColumnAndRow($col, $row, "COURSE CODE");$col++;
			$sheet->setCellValueByColumnAndRow($col, $row, "CAE");$col++;
			$sheet->setCellValueByColumnAndRow($col, $row, "ESE");$col++;
			$sheet->setCellValueByColumnAndRow($col, $row, "TOTAL");$col++;
			$sheet->setCellValueByColumnAndRow($col, $row, "PUBLISHED RESULT");$col++;
			$sheet->setCellValueByColumnAndRow($col, $row, "MAX COURSE MARK");$col++;
			$sheet->setCellValueByColumnAndRow($col, $row, "COURSE TYPE");$col++;			
		}
		
		$row++;		
		
		foreach ($studentArray as $key => $result) {
			$stuInternalArray = array();
			$stuESArray = array();
			$stuDNArray = array();
			
			$col = 0;
			$sheet->getRowDimension($row)->setRowHeight('18');
			
			$sheet->setCellValueByColumnAndRow($col, $row, $result['Student']['id']);$col++;			
			$sheet->setCellValueByColumnAndRow($col, $row, $result['Student']['registration_number']);$col++;
			$sheet->setCellValueByColumnAndRow($col, $row, $result['Student']['name']);$col++;
			
			//For Theory Internal
			for($p=0;$p<count($result['InternalExam']);$p++){
				$stuInternalArray[$result['InternalExam'][$p]['course_mapping_id']] = $result['InternalExam'][$p]['marks'];
			}
			//For Theory External
			for($p=0;$p<count($result['EndSemesterExam']);$p++){
				$stuESArray[$result['EndSemesterExam'][$p]['course_mapping_id']] = $result['EndSemesterExam'][$p]['marks'];
				$stuDNArray[$result['EndSemesterExam'][$p]['course_mapping_id']] = $result['EndSemesterExam'][$p]['dummy_number'];
			}
			
			//For Practical	Internal
			for($q=0;$q<count($result['InternalPractical']);$q++){
				$stuInternalArray[$result['InternalPractical'][$q]['CaePractical']['course_mapping_id']] = $result['InternalPractical'][$q]['marks'];
			}
			//For Practical	External
			for($q=0;$q<count($result['Practical']);$q++){
				$practicalExternalMarks = $result['Practical'][$q]['marks'];
				if($practicalExternalMarks == '0'){$practicalExternalMarks = " 0";}
				$stuESArray[$result['Practical'][$q]['EsePractical']['course_mapping_id']] = $practicalExternalMarks;
				$practicalExternalMarks = "";
			}
			//For Project Internal
			for($q=0;$q<count($result['InternalProject']);$q++){
				$stuInternalArray[$result['InternalProject'][$q]['course_mapping_id']] = $result['InternalProject'][$q]['marks'];
			}
			//For Project External
			for($q=0;$q<count($result['ProjectViva']);$q++){
				$projectExternalMarks = $result['ProjectViva'][$q]['marks'];
				if($projectExternalMarks == '0'){$projectExternalMarks = "&nbsp;0";}
				$stuESArray[$result['ProjectViva'][$q]['EseProject']['course_mapping_id']] = $projectExternalMarks;
				$projectExternalMarks = "";
			}
			for($p=0;$p<count($result['StudentMark']);$p++){
				$IEV = "A"; $ESV = "A"; $DNV ="";
				$courseMId = $result['StudentMark'][$p]['course_mapping_id'];
							
				if(isset($stuInternalArray[$courseMId])){
					$IEV = $stuInternalArray[$courseMId];
				}
				if(isset($stuESArray[$courseMId])){
					$ESV = $stuESArray[$courseMId];					
				}
				if(isset($stuDNArray[$courseMId])){
					$DNV = $stuDNArray[$courseMId];
				}
				$sheet->setCellValueByColumnAndRow($col, $row, $result['StudentMark'][$p]['id']);$col++;
				if($DNV){
					$sheet->setCellValueByColumnAndRow($col, $row, $DNV);$col++;
				}else{
					$sheet->setCellValueByColumnAndRow($col, $row, '');$col++;
				}
				$sheet->setCellValueByColumnAndRow($col, $row, $result['StudentMark'][$p]['CourseMapping']['Course']['course_code']);$col++;
				
				if($IEV){
					$sheet->setCellValueByColumnAndRow($col, $row, $IEV);$col++;
				}else{
					$sheet->setCellValueByColumnAndRow($col, $row, '0');$col++;
				}
		
				if($ESV){
					$sheet->setCellValueByColumnAndRow($col, $row, $ESV);$col++;
				}else{
					$sheet->setCellValueByColumnAndRow($col, $row, '0');$col++;
				}
		
				if($result['StudentMark'][$p]['marks']){
					$sheet->setCellValueByColumnAndRow($col, $row, $result['StudentMark'][$p]['marks']);$col++;
				}else{
					$sheet->setCellValueByColumnAndRow($col, $row, 'A');$col++;
				}
				
				
				if($result['StudentMark'][$p]['status'] == 'Fail'){
					$sheet->setCellValueByColumnAndRow($col, $row, 'RA');$col++;
				}else {
					$sheet->setCellValueByColumnAndRow($col, $row, $result['StudentMark'][$p]['status']);$col++;
				}	
				$sheet->setCellValueByColumnAndRow($col, $row, $result['StudentMark'][$p]['CourseMapping']['Course']['course_max_marks']);$col++;
				$sheet->setCellValueByColumnAndRow($col, $row, $result['StudentMark'][$p]['CourseMapping']['Course']['CourseType']['course_type']);$col++;
			}			
			$row++;			
		}
	
		$download_filename="Mark_Data_COE-".date('d-M-Y h:i:s');
		header("Content-Type: application/vnd.ms-excel");
		header("Content-Disposition: attachment; filename=\"$download_filename.xls\"");
		header("Cache-Control: max-age=0");
		$objWriter = PHPExcel_IOFactory::createWriter($phpExcel, "Excel5");
		$objWriter->save("php://output");
		exit;
	}
	public function publishWithHeldTypeData($WithheldArray) {	
		$phpExcel = new PHPExcel();
		$phpExcel->setActiveSheetIndex(0);
		$sheet = $phpExcel->getActiveSheet();
		$sheet->getRowDimension('1')->setRowHeight('18');
		$sheet->setTitle("Results_to_website_WH_data");
			
		$sheet->setCellValue("A1", "Withheld Id");
		$sheet->setCellValue("B1", "Withheld Name");
	
		$i=2;
		foreach ($WithheldArray as $key => $Withheld) {
			$sheet->getRowDimension($i)->setRowHeight('18');
			$sheet->setCellValue('A'.$i, $Withheld['Withheld']['id']);
			$sheet->setCellValue('B'.$i, $Withheld['Withheld']['withheld_type']);
			$i++;
		}
	
		$download_filename="Results_to_website_WH_data-".date('d-M-Y h:i:s');
		header("Content-Type: application/vnd.ms-excel");
		header("Content-Disposition: attachment; filename=\"$download_filename.xls\"");
		header("Cache-Control: max-age=0");
		$objWriter = PHPExcel_IOFactory::createWriter($phpExcel, "Excel5");
		$objWriter->save("php://output");
		exit;
	}
	
	public function publishWithHeldStudentData($WithheldArray) {
		$phpExcel = new PHPExcel();
		$phpExcel->setActiveSheetIndex(0);
		$sheet = $phpExcel->getActiveSheet();
		$sheet->getRowDimension('1')->setRowHeight('18');
		$sheet->setTitle("Results_to_website_WH_Stu_data");
			
		$sheet->setCellValue("A1", "WITHHELD ID");
		$sheet->setCellValue("B1", "STUDENT ID");
	
		$i=2;
		foreach ($WithheldArray as $key => $Withheld) {
			$sheet->getRowDimension($i)->setRowHeight('18');
			$sheet->setCellValue('A'.$i, $Withheld['StudentWithheld']['withheld_id']);
			$sheet->setCellValue('B'.$i, $Withheld['StudentWithheld']['student_id']);
			$i++;
		}
	
		$download_filename="Results_to_website_WH_Stu_data-".date('d-M-Y h:i:s');
		header("Content-Type: application/vnd.ms-excel");
		header("Content-Disposition: attachment; filename=\"$download_filename.xls\"");
		header("Cache-Control: max-age=0");
		$objWriter = PHPExcel_IOFactory::createWriter($phpExcel, "Excel5");
		$objWriter->save("php://output");
		exit;
	}
	
	//Department Analysis 
	public function PublishResultMarkData($studentArray,$withheldType) {
		$row = 1; // 1-based index
		$col = 0;
		$phpExcel = new PHPExcel();
		$phpExcel->setActiveSheetIndex(0);
		$sheet = $phpExcel->getActiveSheet();
		$sheet->getRowDimension('1')->setRowHeight('18');
		$sheet->setTitle("Publish_Result_Mark_Data");	
	
		$sheet->setCellValueByColumnAndRow($col, $row, "REGISTER NUMBER");$col++;
		$sheet->setCellValueByColumnAndRow($col, $row, "STUDENT NAME");$col++;
		
		for($p=0;$p<12;$p++){
			$sheet->setCellValueByColumnAndRow($col, $row, "COURSE CODE");$col++;
			$sheet->setCellValueByColumnAndRow($col, $row, "CAE");$col++;
			$sheet->setCellValueByColumnAndRow($col, $row, "ESE");$col++;
			$sheet->setCellValueByColumnAndRow($col, $row, "TOTAL");$col++;
			$sheet->setCellValueByColumnAndRow($col, $row, "PUBLISHED RESULT");$col++;
			$sheet->setCellValueByColumnAndRow($col, $row, "MAX COURSE MARK");$col++;
			$sheet->setCellValueByColumnAndRow($col, $row, "COURSE TYPE");$col++;
		}
		$row++;
	
		foreach ($studentArray as $key => $result) {
			$stuInternalArray = array();
			$stuESArray = array();
			$stuDNArray = array();
			
			$col = 0;
			$sheet->getRowDimension($row)->setRowHeight('18');
				
			$sheet->setCellValueByColumnAndRow($col, $row, $result['Student']['registration_number']);$col++;
			$sheet->setCellValueByColumnAndRow($col, $row, $result['Student']['name']);$col++;
				
			//For Theory Internal
			for($p=0;$p<count($result['InternalExam']);$p++){
				$stuInternalArray[$result['InternalExam'][$p]['course_mapping_id']] = $result['InternalExam'][$p]['marks'];
			}
			//For Theory External
			for($p=0;$p<count($result['EndSemesterExam']);$p++){
				$stuESArray[$result['EndSemesterExam'][$p]['course_mapping_id']] = $result['EndSemesterExam'][$p]['marks'];
				$stuDNArray[$result['EndSemesterExam'][$p]['course_mapping_id']] = $result['EndSemesterExam'][$p]['dummy_number'];
			}
				
			//For Practical	Internal
			for($q=0;$q<count($result['InternalPractical']);$q++){
				$stuInternalArray[$result['InternalPractical'][$q]['CaePractical']['course_mapping_id']] = $result['InternalPractical'][$q]['marks'];
			}
			//For Practical	External
			for($q=0;$q<count($result['Practical']);$q++){
				$practicalExternalMarks = $result['Practical'][$q]['marks'];
				if($practicalExternalMarks == '0'){$practicalExternalMarks = " 0";}
				$stuESArray[$result['Practical'][$q]['EsePractical']['course_mapping_id']] = $practicalExternalMarks;
				$practicalExternalMarks = "";
			}
				
			if(($withheldType == 'All') ){
				for($p=0;$p<count($result['StudentMark']);$p++){
					$IEV = ""; $ESV = ""; $DNV ="";
					$courseMId = $result['StudentMark'][$p]['course_mapping_id'];
						
					if(isset($stuInternalArray[$courseMId])){
						$IEV = $stuInternalArray[$courseMId];
					}
					if(isset($stuESArray[$courseMId])){
						$ESV = $stuESArray[$courseMId];
					}
					if(isset($stuDNArray[$courseMId])){
						$DNV = $stuDNArray[$courseMId];
					}
					$sheet->setCellValueByColumnAndRow($col, $row, $result['StudentMark'][$p]['CourseMapping']['Course']['course_code']);$col++;
		
					if($IEV){
						$sheet->setCellValueByColumnAndRow($col, $row, $IEV);$col++;
					}else{
						$sheet->setCellValueByColumnAndRow($col, $row, 'A');$col++;
					}
		
					if($ESV){
						$sheet->setCellValueByColumnAndRow($col, $row, $ESV);$col++;
					}else{
						$sheet->setCellValueByColumnAndRow($col, $row, 'A');$col++;
					}
		
					if($result['StudentMark'][$p]['marks']){
						$sheet->setCellValueByColumnAndRow($col, $row, $result['StudentMark'][$p]['marks']);$col++;
					}else{
						$sheet->setCellValueByColumnAndRow($col, $row, 'A');$col++;
					}
					
					if($result['StudentMark'][$p]['status'] == 'Fail'){
						$sheet->setCellValueByColumnAndRow($col, $row, 'RA');$col++;
					}else {
						$sheet->setCellValueByColumnAndRow($col, $row, $result['StudentMark'][$p]['status']);$col++;
					}
					$sheet->setCellValueByColumnAndRow($col, $row, $result['StudentMark'][$p]['CourseMapping']['Course']['course_max_marks']);$col++;
					$sheet->setCellValueByColumnAndRow($col, $row, $result['StudentMark'][$p]['CourseMapping']['Course']['CourseType']['course_type']);$col++;
				}
			}else if(empty($result['StudentWithheld'])){
				for($p=0;$p<count($result['StudentMark']);$p++){
					$IEV = ""; $ESV = ""; $DNV ="";
					$courseMId = $result['StudentMark'][$p]['course_mapping_id'];
				
					if(isset($stuInternalArray[$courseMId])){
						$IEV = $stuInternalArray[$courseMId];
					}
					if(isset($stuESArray[$courseMId])){
						$ESV = $stuESArray[$courseMId];
					}
					if(isset($stuDNArray[$courseMId])){
						$DNV = $stuDNArray[$courseMId];
					}
					$sheet->setCellValueByColumnAndRow($col, $row, $result['StudentMark'][$p]['CourseMapping']['Course']['course_code']);$col++;
				
					if($IEV){
						$sheet->setCellValueByColumnAndRow($col, $row, $IEV);$col++;
					}else{
						$sheet->setCellValueByColumnAndRow($col, $row, 'A');$col++;
					}
				
					if($ESV){
						$sheet->setCellValueByColumnAndRow($col, $row, $ESV);$col++;
					}else{
						$sheet->setCellValueByColumnAndRow($col, $row, 'A');$col++;
					}
				
					if($result['StudentMark'][$p]['marks']){
						$sheet->setCellValueByColumnAndRow($col, $row, $result['StudentMark'][$p]['marks']);$col++;
					}else{
						$sheet->setCellValueByColumnAndRow($col, $row, 'A');$col++;
					}
						
					if($result['StudentMark'][$p]['status'] == 'Fail'){
						$sheet->setCellValueByColumnAndRow($col, $row, 'RA');$col++;
					}else {
						$sheet->setCellValueByColumnAndRow($col, $row, $result['StudentMark'][$p]['status']);$col++;
					}
					$sheet->setCellValueByColumnAndRow($col, $row, $result['StudentMark'][$p]['CourseMapping']['Course']['course_max_marks']);$col++;
					$sheet->setCellValueByColumnAndRow($col, $row, $result['StudentMark'][$p]['CourseMapping']['Course']['CourseType']['course_type']);$col++;
				}
			}else{
				//With Held process
				$withheldReason = "";
				for($i=0;$i<count($result['StudentWithheld']);$i++){
					$withheldReason .= $result['StudentWithheld'][$i]['Withheld']['withheld_type'].", ";
				}				
				$sheet->setCellValueByColumnAndRow($col, $row, $withheldReason);
			}
			$row++;
		}
	
		$download_filename="Publish_Result_Mark_Data-".date('d-M-Y h:i:s');
		header("Content-Type: application/vnd.ms-excel");
		header("Content-Disposition: attachment; filename=\"$download_filename.xls\"");
		header("Cache-Control: max-age=0");
		$objWriter = PHPExcel_IOFactory::createWriter($phpExcel, "Excel5");
		$objWriter->save("php://output");
		exit;
	}
	
	//Department Analysis
	public function PublishResultMarkDataAfterRevaluation($studentArray,$withheldType) {
		$row = 1; // 1-based index
		$col = 0;
		$phpExcel = new PHPExcel();
		$phpExcel->setActiveSheetIndex(0);
		$sheet = $phpExcel->getActiveSheet();
		$sheet->getRowDimension('1')->setRowHeight('18');
		$sheet->setTitle("Publish_Result_Mark_Data");
	
		$sheet->setCellValueByColumnAndRow($col, $row, "REGISTER NUMBER");$col++;
		$sheet->setCellValueByColumnAndRow($col, $row, "STUDENT NAME");$col++;
	
		for($p=0;$p<19;$p++){
			$sheet->setCellValueByColumnAndRow($col, $row, "COURSE CODE");$col++;
			$sheet->setCellValueByColumnAndRow($col, $row, "CAE");$col++;
			$sheet->setCellValueByColumnAndRow($col, $row, "ESE");$col++;
			$sheet->setCellValueByColumnAndRow($col, $row, "TOTAL");$col++;
			$sheet->setCellValueByColumnAndRow($col, $row, "PUBLISHED RESULT");$col++;
			$sheet->setCellValueByColumnAndRow($col, $row, "MAX COURSE MARK");$col++;
			$sheet->setCellValueByColumnAndRow($col, $row, "COURSE TYPE");$col++;
		}
		$row++;	
		
		foreach ($studentArray as $studentId => $resArray) {  
			$col = 0;
			$sheet->getRowDimension($row)->setRowHeight('18');
			$sheet->setCellValueByColumnAndRow($col, $row, $resArray[0]['registration_number']);$col++;
			$sheet->setCellValueByColumnAndRow($col, $row, $resArray[0]['name']);$col++;
			foreach ($resArray as $key => $result) { 
				$sheet->setCellValueByColumnAndRow($col, $row, $result['course_code']);$col++;
				$sheet->setCellValueByColumnAndRow($col, $row, $result['cae']);$col++;
				if (empty($result['ese']) && $result['attendance']==0 && $result['course_type']=='Theory') {
					$sheet->setCellValueByColumnAndRow($col, $row, "A");$col++;
				}
				else {
					$sheet->setCellValueByColumnAndRow($col, $row, $result['ese']);$col++;
				}
				$sheet->setCellValueByColumnAndRow($col, $row, $result['total']);$col++;
				if($result['status'] == 'Fail'){
					$sheet->setCellValueByColumnAndRow($col, $row, 'RA');$col++;
				}else {
					$sheet->setCellValueByColumnAndRow($col, $row, $result['status']);$col++;
				}
				$sheet->setCellValueByColumnAndRow($col, $row, $result['course_max_marks']);$col++;
				$sheet->setCellValueByColumnAndRow($col, $row, $result['course_type']);$col++;
			}
			$row++;
		}
		
		$download_filename="Publish_Result_Mark_Data_After_RV-".date('d-M-Y h:i:s');
		header("Content-Type: application/vnd.ms-excel");
		header("Content-Disposition: attachment; filename=\"$download_filename.xls\"");
		header("Cache-Control: max-age=0");
		$objWriter = PHPExcel_IOFactory::createWriter($phpExcel, "Excel5");
		$objWriter->save("php://output");
		exit;
	}
	/* public function publishWebsiteMarkDataAfterRevaluation($studentArray) {
		$row = 1; // 1-based index
		$col = 0;
		$phpExcel = new PHPExcel();
		$phpExcel->setActiveSheetIndex(0);
		$sheet = $phpExcel->getActiveSheet();
		$sheet->getRowDimension('1')->setRowHeight('18');
		$sheet->setTitle("Mark_Data_COE_After_RV");
	
	
		$sheet->setCellValueByColumnAndRow($col, $row, "STUDENT ID");$col++;
		$sheet->setCellValueByColumnAndRow($col, $row, "REGISTER NUMBER");$col++;
		$sheet->setCellValueByColumnAndRow($col, $row, "STUDENT NAME");$col++;
	
		for($p=0;$p<12;$p++){
			$sheet->setCellValueByColumnAndRow($col, $row, "MARK Id");$col++;
			$sheet->setCellValueByColumnAndRow($col, $row, "DUMMY No.");$col++;
			$sheet->setCellValueByColumnAndRow($col, $row, "COURSE CODE");$col++;
			$sheet->setCellValueByColumnAndRow($col, $row, "CAE");$col++;
			$sheet->setCellValueByColumnAndRow($col, $row, "ESE");$col++;
			$sheet->setCellValueByColumnAndRow($col, $row, "TOTAL");$col++;
			$sheet->setCellValueByColumnAndRow($col, $row, "PUBLISHED RESULT");$col++;
			$sheet->setCellValueByColumnAndRow($col, $row, "MAX COURSE MARK");$col++;
			$sheet->setCellValueByColumnAndRow($col, $row, "COURSE TYPE");$col++;
		}
	
		$row++;
	
		foreach ($studentArray as $key => $result) {
			$stuInternalArray = array();
			$stuESArray = array();
			$stuDNArray = array();
			$stuFinalMark = array();
			$stuMarkStatus = array();
			
			$col = 0;
			$sheet->getRowDimension($row)->setRowHeight('18');
				
			$sheet->setCellValueByColumnAndRow($col, $row, $result['Student']['id']);$col++;
			$sheet->setCellValueByColumnAndRow($col, $row, $result['Student']['registration_number']);$col++;
			$sheet->setCellValueByColumnAndRow($col, $row, $result['Student']['name']);$col++;
			
			//For Theory Revaluation
			for($p=0;$p<count($result['RevaluationExam']);$p++){
				$stuESArray[$result['RevaluationExam'][$p]['course_mapping_id']] = $result['RevaluationExam'][$p]['revaluation_marks'];
			}
			
			//For Theory Internal
			for($p=0;$p<count($result['InternalExam']);$p++){
				$stuInternalArray[$result['InternalExam'][$p]['course_mapping_id']] = $result['InternalExam'][$p]['marks'];
			}
			//For Theory External
			for($p=0;$p<count($result['EndSemesterExam']);$p++){
				if($result['EndSemesterExam'][$p]['revaluation_status'] == 0){
					$stuESArray[$result['EndSemesterExam'][$p]['course_mapping_id']] = $result['EndSemesterExam'][$p]['marks'];
				}else{
					if($stuESArray[$result['EndSemesterExam'][$p]['course_mapping_id']] < $result['EndSemesterExam'][$p]['marks']){
						$stuESArray[$result['EndSemesterExam'][$p]['course_mapping_id']] = $result['EndSemesterExam'][$p]['marks'];
					}
				}
				$stuDNArray[$result['EndSemesterExam'][$p]['course_mapping_id']] = $result['EndSemesterExam'][$p]['dummy_number'];
			}		
			
			//For Practical	Internal
			for($q=0;$q<count($result['InternalPractical']);$q++){
				$stuInternalArray[$result['InternalPractical'][$q]['CaePractical']['course_mapping_id']] = $result['InternalPractical'][$q]['marks'];
			}
			//For Practical	External
			for($q=0;$q<count($result['Practical']);$q++){
				$practicalExternalMarks = $result['Practical'][$q]['marks'];
				if($practicalExternalMarks == '0'){$practicalExternalMarks = " 0";}
				$stuESArray[$result['Practical'][$q]['EsePractical']['course_mapping_id']] = $practicalExternalMarks;
				$practicalExternalMarks = "";
			}
			//For Project Internal
			for($q=0;$q<count($result['InternalProject']);$q++){
				$stuInternalArray[$result['InternalProject'][$q]['course_mapping_id']] = $result['InternalProject'][$q]['marks'];
			}
			//For Project External
			for($q=0;$q<count($result['ProjectViva']);$q++){
				$projectExternalMarks = $result['ProjectViva'][$q]['marks'];
				if($projectExternalMarks == '0'){$projectExternalMarks = "&nbsp;0";}
				$stuESArray[$result['ProjectViva'][$q]['EseProject']['course_mapping_id']] = $projectExternalMarks;
				$projectExternalMarks = "";
			}	
			for($p=0;$p<count($result['StudentMark']);$p++){
				$IEV = "A"; $ESV = "A"; $DNV ="";
				$courseMId = $result['StudentMark'][$p]['course_mapping_id'];
				if($result['StudentMark'][$p]['revaluation_status'] == 0){
					$stuFinalMark[$courseMId] = $result['StudentMark'][$p]['marks'];
					$stuMarkStatus[$courseMId] = $result['StudentMark'][$p]['status'];
				}else{
					$stuFinalMark[$courseMId] = $result['StudentMark'][$p]['final_marks'];
					$stuMarkStatus[$courseMId] = $result['StudentMark'][$p]['final_status'];
				}
				if(isset($stuInternalArray[$courseMId])){
					$IEV = $stuInternalArray[$courseMId];
				}
				if(isset($stuESArray[$courseMId])){
					$ESV = $stuESArray[$courseMId];
				}
				if(isset($stuDNArray[$courseMId])){
					$DNV = $stuDNArray[$courseMId];
				}
				$sheet->setCellValueByColumnAndRow($col, $row, $result['StudentMark'][$p]['id']);$col++;
				if($DNV){
					$sheet->setCellValueByColumnAndRow($col, $row, $DNV);$col++;
				}else{
					$sheet->setCellValueByColumnAndRow($col, $row, '');$col++;
				}
				$sheet->setCellValueByColumnAndRow($col, $row, $result['StudentMark'][$p]['CourseMapping']['Course']['course_code']);$col++;
	
				if($IEV){
					$sheet->setCellValueByColumnAndRow($col, $row, $IEV);$col++;
				}else{
					$sheet->setCellValueByColumnAndRow($col, $row, '0');$col++;
				}
	
				if($ESV){
					$sheet->setCellValueByColumnAndRow($col, $row, $ESV);$col++;
				}else{
					$sheet->setCellValueByColumnAndRow($col, $row, '0');$col++;
				}
	
				if($stuFinalMark[$courseMId]){
					$sheet->setCellValueByColumnAndRow($col, $row, $stuFinalMark[$courseMId]);$col++;
				}else{
					$sheet->setCellValueByColumnAndRow($col, $row, 'A');$col++;
				}
				
				if($stuMarkStatus[$courseMId] == 'Fail'){
					$sheet->setCellValueByColumnAndRow($col, $row, 'RA');$col++;
				}else {
					$sheet->setCellValueByColumnAndRow($col, $row, $stuMarkStatus[$courseMId]);$col++;
				}
				$sheet->setCellValueByColumnAndRow($col, $row, $result['StudentMark'][$p]['CourseMapping']['Course']['course_max_marks']);$col++;
				$sheet->setCellValueByColumnAndRow($col, $row, $result['StudentMark'][$p]['CourseMapping']['Course']['CourseType']['course_type']);$col++;
			}
			$row++;
		}
	
		$download_filename="Mark_Data_COE_After_RV-".date('d-M-Y h:i:s');
		header("Content-Type: application/vnd.ms-excel");
		header("Content-Disposition: attachment; filename=\"$download_filename.xls\"");
		header("Cache-Control: max-age=0");
		$objWriter = PHPExcel_IOFactory::createWriter($phpExcel, "Excel5");
		$objWriter->save("php://output");
		exit;
	} */
	
	//public function publishWebsiteMarkDataAfterRevaluation($results, $reval_status) {
	public function markDataCoE($results, $reval_status) {
		//pr($results); 
		//echo count($results);
		
		$csv_header = '';
		$csv_row = '';
		
		$csv_header .= 'STUDENT_ID,REGISTER_NUMBER,STUDENT_NAME,MARK_ID,DUMMY_NUMBER,COURSE_CODE,CAE,ESE,TOTAL,PUBLISHED_RESULT,MAX_COURSE_MARK,COURSE_TYPE,MARK_ID,DUMMY_NUMBER,COURSE_CODE,CAE,ESE,TOTAL,PUBLISHED_RESULT,MAX_COURSE_MARK,COURSE_TYPE,MARK_ID,DUMMY_NUMBER,COURSE_CODE,CAE,ESE,TOTAL,PUBLISHED_RESULT,MAX_COURSE_MARK,COURSE_TYPE,MARK_ID,DUMMY_NUMBER,COURSE_CODE,CAE,ESE,TOTAL,PUBLISHED_RESULT,MAX_COURSE_MARK,COURSE_TYPE,MARK_ID,DUMMY_NUMBER,COURSE_CODE,CAE,ESE,TOTAL,PUBLISHED_RESULT,MAX_COURSE_MARK,COURSE_TYPE,MARK_ID,DUMMY_NUMBER,COURSE_CODE,CAE,ESE,TOTAL,PUBLISHED_RESULT,MAX_COURSE_MARK,COURSE_TYPE,MARK_ID,DUMMY_NUMBER,COURSE_CODE,CAE,ESE,TOTAL,PUBLISHED_RESULT,MAX_COURSE_MARK,COURSE_TYPE,MARK_ID,DUMMY_NUMBER,COURSE_CODE,CAE,ESE,TOTAL,PUBLISHED_RESULT,MAX_COURSE_MARK,COURSE_TYPE,MARK_ID,DUMMY_NUMBER,COURSE_CODE,CAE,ESE,TOTAL,PUBLISHED_RESULT,MAX_COURSE_MARK,COURSE_TYPE,MARK_ID,DUMMY_NUMBER,COURSE_CODE,CAE,ESE,TOTAL,PUBLISHED_RESULT,MAX_COURSE_MARK,COURSE_TYPE,MARK_ID,DUMMY_NUMBER,COURSE_CODE,CAE,ESE,TOTAL,PUBLISHED_RESULT,MAX_COURSE_MARK,COURSE_TYPE,MARK_ID,DUMMY_NUMBER,COURSE_CODE,CAE,ESE,TOTAL,PUBLISHED_RESULT,MAX_COURSE_MARK,COURSE_TYPE';
		$csv_header .= "\n";
		
		foreach ($results as $registration_number => $resultArray) {
			
			$csv_row.='"' . $resultArray[0]['student_id'] . '",';
			$csv_row.='"' . $registration_number . '",';
			$csv_row.='"' . $resultArray[0]['name'] . '",';
			
			foreach ($resultArray as $key => $result) {
				$csv_row.='"' . $result['sm_id'] . '",';
				$csv_row.='"' . $result['dummy_number'] . '",';
				$csv_row.='"' . $result['course_code'] . '",';
				$csv_row.='"' . $result['cae'] . '",';
				if (empty($result['ese']) && $result['attendance']==0 && $result['course_type']=='Theory') {
					$ese = "A";
				}
				else {
					$ese = $result['ese'];
				}
				$csv_row.='"' . $ese . '",';
		
				$csv_row.='"' . $result['total'] . '",';
		
				if($result['status'] == 'Fail'){
					$status = 'RA';
				}else {
					$status = $result['status'];
				}
				$csv_row.='"' . $status . '",';
				$csv_row.='"' . $result['course_max_marks'] . '",';
				$csv_row.='"' . $result['course_type'] . '",';
			}
			$csv_row .= "\n";
		}
		
		$download_filename="Mark_Data_CoE-".$reval_status."_".date('d-M-Y h:i:s');
		header("Content-type: application/csv");
		header("Content-Disposition: attachment; filename=\"$download_filename.csv\"");
		echo $csv_header.$csv_row;
		exit;
		
		/* $row = 1; // 1-based index
		$col = 0;
		$phpExcel = new PHPExcel();
		$phpExcel->setActiveSheetIndex(0);
		$sheet = $phpExcel->getActiveSheet();
		$sheet->getRowDimension('1')->setRowHeight('18');
		if ($reval_status=="AR") {
			$title = "Mark_Data_COE_After_RV";
		}
		else if ($reval_status=="BR") {
			$title = "Mark_Data_COE";
		}
	
		$sheet->setTitle("Mark_Data_COE");
		
		$sheet->setCellValueByColumnAndRow($col, $row, "STUDENT ID");$col++;
		$sheet->setCellValueByColumnAndRow($col, $row, "REGISTER NUMBER");$col++;
		$sheet->setCellValueByColumnAndRow($col, $row, "STUDENT NAME");$col++;
	
		for($p=0;$p<12;$p++){
			$sheet->setCellValueByColumnAndRow($col, $row, "MARK Id");$col++;
			$sheet->setCellValueByColumnAndRow($col, $row, "DUMMY No.");$col++;
			$sheet->setCellValueByColumnAndRow($col, $row, "COURSE CODE");$col++;
			$sheet->setCellValueByColumnAndRow($col, $row, "CAE");$col++;
			$sheet->setCellValueByColumnAndRow($col, $row, "ESE");$col++;
			$sheet->setCellValueByColumnAndRow($col, $row, "TOTAL");$col++;
			$sheet->setCellValueByColumnAndRow($col, $row, "PUBLISHED RESULT");$col++;
			$sheet->setCellValueByColumnAndRow($col, $row, "MAX COURSE MARK");$col++;
			$sheet->setCellValueByColumnAndRow($col, $row, "COURSE TYPE");$col++;
		}
	
		$row++;
	
		foreach ($results as $reg_number => $resultArray) {
			$col = 0;
			if (isset($resultArray) && count($resultArray)>0) {
				//pr($resultArray);
				//$reg_number = $resultArray[0]['registration_number'];
				$student_id = $resultArray[0]['student_id'];
				$name = $resultArray[0]['name'];
			
				$sheet->setCellValueByColumnAndRow($col, $row, $student_id);$col++;
				$sheet->setCellValueByColumnAndRow($col, $row, $reg_number);$col++;
				$sheet->setCellValueByColumnAndRow($col, $row, $name);$col++;
				
				foreach ($resultArray as $key => $result) {
					$sheet->getRowDimension($row)->setRowHeight('18');
		
					$IEV = "A"; $ESV = "A"; $DNV ="";
					
					$sheet->setCellValueByColumnAndRow($col, $row, $result['sm_id']);$col++;
					$sheet->setCellValueByColumnAndRow($col, $row, $result['dummy_number']);$col++;
					$sheet->setCellValueByColumnAndRow($col, $row, $result['course_code']);$col++;
					$sheet->setCellValueByColumnAndRow($col, $row, $result['cae']);$col++;
					if (empty($result['ese']) && $result['attendance']==0 && $result['course_type']=='Theory') {
						$sheet->setCellValueByColumnAndRow($col, $row, "A");$col++;
					}
					else {
						$sheet->setCellValueByColumnAndRow($col, $row, $result['ese']);$col++;
					}
					$sheet->setCellValueByColumnAndRow($col, $row, $result['total']);$col++;
					if($result['status'] == 'Fail'){
						$sheet->setCellValueByColumnAndRow($col, $row, 'RA');$col++;
					}else {
						$sheet->setCellValueByColumnAndRow($col, $row, $result['status']);$col++;
					}
					$sheet->setCellValueByColumnAndRow($col, $row, $result['course_max_marks']);$col++;
					$sheet->setCellValueByColumnAndRow($col, $row, $result['course_type']);$col++;
				}
			}
			$row++;
		}
	
		$download_filename=$title."-".date('d-M-Y h:i:s');
		header("Content-Type: application/vnd.ms-excel");
		header("Content-Disposition: attachment; filename=\"$download_filename.xls\"");
		header("Cache-Control: max-age=0");
		$objWriter = PHPExcel_IOFactory::createWriter($phpExcel, "Excel5");
		$objWriter->save("php://output");
		exit; */
	}
	
	/* public function resultsToWebsiteMarkDataAfterRevaluation($studentArray) {
		$row = 1; // 1-based index
		$col = 0;
		$phpExcel = new PHPExcel();
		$phpExcel->setActiveSheetIndex(0);
		$sheet = $phpExcel->getActiveSheet();
		$sheet->getRowDimension('1')->setRowHeight('18');
		$sheet->setTitle("Website_mark_data_After_RA");
	
		$sheet->setCellValueByColumnAndRow($col, $row, "STUDENT ID");$col++;
		$sheet->setCellValueByColumnAndRow($col, $row, "REGISTER NUMBER");$col++;
		$sheet->setCellValueByColumnAndRow($col, $row, "STUDENT NAME");$col++;
		$sheet->setCellValueByColumnAndRow($col, $row, "DATE OF BIRTH");$col++;
		$sheet->setCellValueByColumnAndRow($col, $row, "SECTION");$col++;
		
		$sheet->setCellValueByColumnAndRow($col, $row, "MARK ID");$col++;
		$sheet->setCellValueByColumnAndRow($col, $row, "COURSE CODE");$col++;
		$sheet->setCellValueByColumnAndRow($col, $row, "COURSE NAME");$col++;
		$sheet->setCellValueByColumnAndRow($col, $row, "CAE");$col++;
		$sheet->setCellValueByColumnAndRow($col, $row, "ESE");$col++;
		$sheet->setCellValueByColumnAndRow($col, $row, "TOTAL");$col++;
		$sheet->setCellValueByColumnAndRow($col, $row, "PUBLISHED RESULT");$col++;
		$sheet->setCellValueByColumnAndRow($col, $row, "MAX COURSE MARK");$col++;
		$sheet->setCellValueByColumnAndRow($col, $row, "COURSE TYPE");$col++;
		$row++;
	
		foreach ($studentArray as $key => $result) {
			$stuInternalArray = array();
			$stuESArray = array();
			$stuDNArray = array();
			$stuFinalMark = array();
			$stuMarkStatus = array();
			
			//For Theory Revaluation
			for($p=0;$p<count($result['RevaluationExam']);$p++){				
				$stuESArray[$result['RevaluationExam'][$p]['course_mapping_id']] = $result['RevaluationExam'][$p]['revaluation_marks'];
			}
			//For Theory Internal
			for($p=0;$p<count($result['InternalExam']);$p++){
				$stuInternalArray[$result['InternalExam'][$p]['course_mapping_id']] = $result['InternalExam'][$p]['marks'];
			}
			
			//For Theory External
			for($p=0;$p<count($result['EndSemesterExam']);$p++){			
				if($result['EndSemesterExam'][$p]['revaluation_status'] == 0){
					$stuESArray[$result['EndSemesterExam'][$p]['course_mapping_id']] = $result['EndSemesterExam'][$p]['marks'];
				}else{
					if(($stuESArray[$result['EndSemesterExam'][$p]['course_mapping_id']] < $result['EndSemesterExam'][$p]['marks']) && ($stuESArray[$result['EndSemesterExam'][$p]['course_mapping_id']] != 'A')){
						$stuESArray[$result['EndSemesterExam'][$p]['course_mapping_id']] = $result['EndSemesterExam'][$p]['marks'];
					}
				}		
			}	
						
			//For Practical	Internal
			for($q=0;$q<count($result['InternalPractical']);$q++){
				$stuInternalArray[$result['InternalPractical'][$q]['CaePractical']['course_mapping_id']] = $result['InternalPractical'][$q]['marks'];
			}
			//For Practical	External
			for($q=0;$q<count($result['Practical']);$q++){
				$practicalExternalMarks = $result['Practical'][$q]['marks'];
				if($practicalExternalMarks == '0'){$practicalExternalMarks = " 0";}
				$stuESArray[$result['Practical'][$q]['EsePractical']['course_mapping_id']] = $practicalExternalMarks;
				$practicalExternalMarks = "";
			}
			//For Project Internal
			for($q=0;$q<count($result['InternalProject']);$q++){
				$stuInternalArray[$result['InternalProject'][$q]['course_mapping_id']] = $result['InternalProject'][$q]['marks'];
			}
			//For Project External
			for($q=0;$q<count($result['ProjectViva']);$q++){
				$projectExternalMarks = $result['ProjectViva'][$q]['marks'];
				if($projectExternalMarks == '0'){$projectExternalMarks = "&nbsp;0";}
				$stuESArray[$result['ProjectViva'][$q]['EseProject']['course_mapping_id']] = $projectExternalMarks;
				$projectExternalMarks = "";
			}
			for($p=0;$p<count($result['StudentMark']);$p++){
				$col = 0;
				$sheet->getRowDimension($row)->setRowHeight('18');
				$sheet->setCellValueByColumnAndRow($col, $row, $result['Student']['id']);$col++;
				$sheet->setCellValueByColumnAndRow($col, $row, $result['Student']['registration_number']);$col++;
				$sheet->setCellValueByColumnAndRow($col, $row, $result['Student']['name']);$col++;
				$dob = date( "d-m-Y", strtotime(h($result['Student']['birth_date'])));
				$sheet->setCellValueByColumnAndRow($col, $row, $dob);$col++;
				$sheet->setCellValueByColumnAndRow($col, $row, $result['Section']['name']);$col++;
				
				$IEV = "A"; $ESV = "A";
				$courseMId = $result['StudentMark'][$p]['course_mapping_id'];
				if($result['StudentMark'][$p]['revaluation_status'] == 0){
					$stuFinalMark[$courseMId] = $result['StudentMark'][$p]['marks'];
					$stuMarkStatus[$courseMId] = $result['StudentMark'][$p]['status'];
				}else{
					$stuFinalMark[$courseMId] = $result['StudentMark'][$p]['final_marks'];
					$stuMarkStatus[$courseMId] = $result['StudentMark'][$p]['final_status'];
				}	
				if(isset($stuInternalArray[$courseMId])){
					$IEV = $stuInternalArray[$courseMId];
				}
				if(isset($stuESArray[$courseMId])){
					$ESV = $stuESArray[$courseMId];
				}
				if(isset($stuDNArray[$courseMId])){
					$DNV = $stuDNArray[$courseMId];
				}
				$sheet->setCellValueByColumnAndRow($col, $row, $result['StudentMark'][$p]['id']);$col++;
				$sheet->setCellValueByColumnAndRow($col, $row, $result['StudentMark'][$p]['CourseMapping']['Course']['course_code']);$col++;
				$sheet->setCellValueByColumnAndRow($col, $row, $result['StudentMark'][$p]['CourseMapping']['Course']['course_name']);$col++;
	
				if($IEV){
					$sheet->setCellValueByColumnAndRow($col, $row, $IEV);$col++;
				}else{
					$sheet->setCellValueByColumnAndRow($col, $row, '');$col++;
				}
	
				if($ESV){
					$sheet->setCellValueByColumnAndRow($col, $row, $ESV);$col++;
				}else{
					$sheet->setCellValueByColumnAndRow($col, $row, '0');$col++;
				}
	
				if($stuFinalMark[$courseMId]){
					$sheet->setCellValueByColumnAndRow($col, $row, $stuFinalMark[$courseMId]);$col++;
				}else{
					$sheet->setCellValueByColumnAndRow($col, $row, '0');$col++;
				}
				
				if($stuMarkStatus[$courseMId] == 'Fail'){
					$sheet->setCellValueByColumnAndRow($col, $row, 'RA');$col++;
				}else {
					$sheet->setCellValueByColumnAndRow($col, $row, $stuMarkStatus[$courseMId]);$col++;
				}
				$sheet->setCellValueByColumnAndRow($col, $row, $result['StudentMark'][$p]['CourseMapping']['Course']['course_max_marks']);$col++;
				$sheet->setCellValueByColumnAndRow($col, $row, $result['StudentMark'][$p]['CourseMapping']['Course']['CourseType']['course_type']);
				$row++;
			}
				
		}
	
		$download_filename="Results_to_website_mark_data_After_RV-".date('d-M-Y h:i:s');
		header("Content-Type: application/vnd.ms-excel");
		header("Content-Disposition: attachment; filename=\"$download_filename.xls\"");
		header("Cache-Control: max-age=0");
		$objWriter = PHPExcel_IOFactory::createWriter($phpExcel, "Excel5");
		$objWriter->save("php://output");
		exit;
	} */
	
	public function resultsToWebsiteMarkDataAfterRevaluation($results, $reval_mode) {
		//pr($studentArray);
		$row = 1; // 1-based index
		$col = 0;
		$phpExcel = new PHPExcel();
		$phpExcel->setActiveSheetIndex(0);
		$sheet = $phpExcel->getActiveSheet();
		$sheet->getRowDimension('1')->setRowHeight('18');
		$sheet->setTitle("Website_mark_data_After_RA");
	
		$sheet->setCellValueByColumnAndRow($col, $row, "STUDENT ID");$col++;
		$sheet->setCellValueByColumnAndRow($col, $row, "REGISTER NUMBER");$col++;
		$sheet->setCellValueByColumnAndRow($col, $row, "STUDENT NAME");$col++;
		$sheet->setCellValueByColumnAndRow($col, $row, "DATE OF BIRTH");$col++;
		$sheet->setCellValueByColumnAndRow($col, $row, "SECTION");$col++;
	
		$sheet->setCellValueByColumnAndRow($col, $row, "MARK ID");$col++;
		$sheet->setCellValueByColumnAndRow($col, $row, "COURSE CODE");$col++;
		$sheet->setCellValueByColumnAndRow($col, $row, "COURSE NAME");$col++;
		$sheet->setCellValueByColumnAndRow($col, $row, "CAE");$col++;
		$sheet->setCellValueByColumnAndRow($col, $row, "ESE");$col++;
		$sheet->setCellValueByColumnAndRow($col, $row, "TOTAL");$col++;
		$sheet->setCellValueByColumnAndRow($col, $row, "PUBLISHED RESULT");$col++;
		$sheet->setCellValueByColumnAndRow($col, $row, "MAX COURSE MARK");$col++;
		$sheet->setCellValueByColumnAndRow($col, $row, "COURSE TYPE");$col++;
		$row++;
	
		foreach ($results as $student_id => $resultArray) { 
			foreach ($resultArray as $key => $result) {
				$col = 0;
			
				$sheet->getRowDimension($row)->setRowHeight('18');
				$sheet->setCellValueByColumnAndRow($col, $row, $student_id);$col++;
				$sheet->setCellValueByColumnAndRow($col, $row, $result['registration_number']);$col++;
				$sheet->setCellValueByColumnAndRow($col, $row, $result['name']);$col++;
				$dob = date( "d-m-Y", strtotime(h($result['birth_date'])));
				$sheet->setCellValueByColumnAndRow($col, $row, $dob);$col++;
				$sheet->setCellValueByColumnAndRow($col, $row, $result['section']);$col++;
	
				$sheet->setCellValueByColumnAndRow($col, $row, $result['sm_id']);$col++;
				$sheet->setCellValueByColumnAndRow($col, $row, $result['course_code']);$col++;
				$sheet->setCellValueByColumnAndRow($col, $row, $result['course_name']);$col++;
	
				$sheet->setCellValueByColumnAndRow($col, $row, $result['cae']);$col++;
	
				//$sheet->setCellValueByColumnAndRow($col, $row, $result['ese']);$col++;
				if (empty($result['ese']) && $result['attendance']==0 && $result['course_type']=='Theory') {
					$sheet->setCellValueByColumnAndRow($col, $row, "A");$col++;
				}
				else {
					$sheet->setCellValueByColumnAndRow($col, $row, $result['ese']);$col++;
				}
				$sheet->setCellValueByColumnAndRow($col, $row, $result['total']);$col++;
	
				if($result['status'] == 'Fail'){
					$sheet->setCellValueByColumnAndRow($col, $row, 'RA');$col++;
				}else {
					$sheet->setCellValueByColumnAndRow($col, $row, $result['status']);$col++;
				}
				$sheet->setCellValueByColumnAndRow($col, $row, $result['course_max_marks']);$col++;
				$sheet->setCellValueByColumnAndRow($col, $row, $result['course_type']);
				$row++;
			}
		}
	
		$download_filename="Results_to_website_mark_data_After_RV-".date('d-M-Y h:i:s');
		header("Content-Type: application/vnd.ms-excel");
		header("Content-Disposition: attachment; filename=\"$download_filename.xls\"");
		header("Cache-Control: max-age=0");
		$objWriter = PHPExcel_IOFactory::createWriter($phpExcel, "Excel5");
		$objWriter->save("php://output");
		exit;
	}
	
	public function arrearFeesReportMarkData($studentArray) {
		$row = 1; // 1-based index
		$col = 0;
		$phpExcel = new PHPExcel();
		$phpExcel->setActiveSheetIndex(0);
		$sheet = $phpExcel->getActiveSheet();
		$sheet->getRowDimension('1')->setRowHeight('18');
		$sheet->setTitle("Arrear_Fees_After_RA");
	
		$sheet->setCellValueByColumnAndRow($col, $row, "STUDENT ID");$col++;
		$sheet->setCellValueByColumnAndRow($col, $row, "REGISTER NUMBER");$col++;
		$sheet->setCellValueByColumnAndRow($col, $row, "STUDENT NAME");$col++;
		$sheet->setCellValueByColumnAndRow($col, $row, "DATE OF BIRTH");$col++;
		$sheet->setCellValueByColumnAndRow($col, $row, "SECTION");$col++;
		$sheet->setCellValueByColumnAndRow($col, $row, "SHORT CODE");$col++;
		$sheet->setCellValueByColumnAndRow($col, $row, "BATCH");$col++;
		$sheet->setCellValueByColumnAndRow($col, $row, "PROGRAM");$col++;
		$sheet->setCellValueByColumnAndRow($col, $row, "SPECIALISATION");$col++;
		$sheet->setCellValueByColumnAndRow($col, $row, "SEMESTER");$col++;
		
		$sheet->setCellValueByColumnAndRow($col, $row, "COURSE CODE");$col++;
		$sheet->setCellValueByColumnAndRow($col, $row, "COURSE NAME");$col++;
		$sheet->setCellValueByColumnAndRow($col, $row, "PUBLISHED RESULT");$col++;
		$sheet->setCellValueByColumnAndRow($col, $row, "COURSE TYPE");$col++;
		$row++;
	
		foreach ($studentArray as $result) {				
			$col = 0;
			$sheet->getRowDimension($row)->setRowHeight('18');
			$sheet->setCellValueByColumnAndRow($col, $row, $result['STUDENT_ID']);$col++;
			$sheet->setCellValueByColumnAndRow($col, $row, $result['REGISTER_NUMBER']);$col++;
			$sheet->setCellValueByColumnAndRow($col, $row, $result['STUDENT_NAME']);$col++;
			$dob = date( "d-m-Y", strtotime(h($result['DATE_OF_BIRTH'])));
			$sheet->setCellValueByColumnAndRow($col, $row, $dob);$col++;
			$sheet->setCellValueByColumnAndRow($col, $row, $result['SECTION']);$col++;
			$shortCode = '';
			if($result['SHORT_CODE']){
				$shortCode = $result['SHORT_CODE'];
			}
			$sheet->setCellValueByColumnAndRow($col, $row, $shortCode);$col++;			
			$sheet->setCellValueByColumnAndRow($col, $row, $result['BATCH']);$col++;
			$sheet->setCellValueByColumnAndRow($col, $row, $result['PROGRAM']);$col++;
			$sheet->setCellValueByColumnAndRow($col, $row, $result['SPECIALISATION']);$col++;
			$sheet->setCellValueByColumnAndRow($col, $row, $result['SEMESTER']);$col++;			
			$sheet->setCellValueByColumnAndRow($col, $row, $result['COURSE_CODE']);$col++;
			$sheet->setCellValueByColumnAndRow($col, $row, $result['COURSE_NAME']);$col++;
			$sheet->setCellValueByColumnAndRow($col, $row, $result['PUBLISHED_RESULT']);$col++;
			$sheet->setCellValueByColumnAndRow($col, $row, $result['COURSE_TYPE']);$col++;			
			$row++;				
		}
	
		$download_filename="Arrear_Fees_Report_After_RV-".date('d-M-Y h:i:s');
		header("Content-Type: application/vnd.ms-excel");
		header("Content-Disposition: attachment; filename=\"$download_filename.xls\"");
		header("Cache-Control: max-age=0");
		$objWriter = PHPExcel_IOFactory::createWriter($phpExcel, "Excel5");
		$objWriter->save("php://output");
		exit;
	}	

	public function topRankingList($studentArray){
		
		$row = 1; // 1-based index
		$col = 0;
		$phpExcel = new PHPExcel();
		$phpExcel->setActiveSheetIndex(0);
		$sheet = $phpExcel->getActiveSheet();
		$sheet->getRowDimension('1')->setRowHeight('18');
		$sheet->setTitle("Top_Ranking_After_RV");
	
		$sheet->setCellValueByColumnAndRow($col, $row, "STUDENT ID");$col++;
		$sheet->setCellValueByColumnAndRow($col, $row, "REGISTER NUMBER");$col++;
		$sheet->setCellValueByColumnAndRow($col, $row, "STUDENT NAME");$col++;
	
		$sheet->setCellValueByColumnAndRow($col, $row, "SHORT CODE");$col++;
		$sheet->setCellValueByColumnAndRow($col, $row, "BATCH");$col++;
		$sheet->setCellValueByColumnAndRow($col, $row, "PROGRAM");$col++;
		$sheet->setCellValueByColumnAndRow($col, $row, "SPECIALISATION");$col++;
		$sheet->setCellValueByColumnAndRow($col, $row, "SEMESTER");$col++;
		$sheet->setCellValueByColumnAndRow($col, $row, "MAXIMUM MARK");$col++;
		$sheet->setCellValueByColumnAndRow($col, $row, "TOTAL MARK");$col++;
		$sheet->setCellValueByColumnAndRow($col, $row, "RANKING");$col++;
		$row++;	
		//echo count($studentArray);
		for($i=0;$i<count($studentArray);$i++){
			$stuInternalArray = array();
			$stuESArray = array();
			$stuDNArray = array();
			$stuFinalMark = array();
			$stuMarkStatus = array();
			
			$seqRanking = 0;$lastTotal = 0; $cnt = 0;
			
			foreach ($studentArray[$i] as $key => $result) {
				$bool = false;
				//pr($studentArray[$i]);
				if(isset($result['total'])){
					if(($lastTotal != $result['total']) || ($seqRanking == 0) && $cnt<=9){
						$seqRanking++; 
					}else{$seqRanking=$lastRanking++; $lastRanking++; $bool = true;}
					$col = 0;
					$sheet->getRowDimension($row)->setRowHeight('18');
					$sheet->setCellValueByColumnAndRow($col, $row, $result['id']);$col++;
					$sheet->setCellValueByColumnAndRow($col, $row, $result['registration_number']);$col++;
					$sheet->setCellValueByColumnAndRow($col, $row, $result['name']);$col++;
					$sheet->setCellValueByColumnAndRow($col, $row, $result['short_code']);$col++;
					$sheet->setCellValueByColumnAndRow($col, $row, $result['batch']);$col++;
					$sheet->setCellValueByColumnAndRow($col, $row, $result['program']);$col++;
					$sheet->setCellValueByColumnAndRow($col, $row, $result['specialisation']);$col++;
					$sheet->setCellValueByColumnAndRow($col, $row, $result['semester']);$col++;
					$sheet->setCellValueByColumnAndRow($col, $row, $result['max_marks']);$col++;
					$sheet->setCellValueByColumnAndRow($col, $row, $result['total']);$col++;
					$sheet->setCellValueByColumnAndRow($col, $row, $seqRanking);$col++;
					$lastTotal = $result['total'];
					$lastRanking = $seqRanking;
					if ($bool) $seqRanking++;
					//echo "</br>".$lastTotal." ".$seqRanking." ".$lastRanking." ".$t;
					$row++;
					$cnt++;
				}
			}
		}
		$download_filename="Top_Ranking_After_RV-".date('d-M-Y h:i:s');
		header("Content-Type: application/vnd.ms-excel");
		header("Content-Disposition: attachment; filename=\"$download_filename.xls\"");
		header("Cache-Control: max-age=0");
		$objWriter = PHPExcel_IOFactory::createWriter($phpExcel, "Excel5");
		$objWriter->save("php://output");
		exit;
	}
	
	public function excelCGPA($studentArray, $withheldType, $totalCourses) {
		$row = 1; // 1-based index
		$col = 0;
		$phpExcel = new PHPExcel();
		$phpExcel->setActiveSheetIndex(0);
		$sheet = $phpExcel->getActiveSheet();
		$sheet->getRowDimension('1')->setRowHeight('18');
		$sheet->getColumnDimension('B')->setWidth(20);
		$sheet->getColumnDimension('C')->setWidth(6);
		$sheet->getColumnDimension('D')->setWidth(6);
		$sheet->getColumnDimension('E')->setWidth(6);
		$sheet->getColumnDimension('F')->setWidth(6);
		$sheet->getColumnDimension('G')->setWidth(6);
		$sheet->getColumnDimension('H')->setWidth(6);
		$sheet->getColumnDimension('I')->setWidth(6);
		$sheet->getColumnDimension('J')->setWidth(6);
		$sheet->getColumnDimension('K')->setWidth(6);
		$sheet->getColumnDimension('L')->setWidth(6);
		$sheet->getColumnDimension('M')->setWidth(6);
		$sheet->getColumnDimension('N')->setWidth(6);
		$sheet->getColumnDimension('O')->setWidth(6);
		$sheet->getColumnDimension('P')->setWidth(8);
		$sheet->getColumnDimension('R')->setWidth(6);
		$sheet->getColumnDimension('S')->setWidth(6);
		$sheet->getColumnDimension('T')->setWidth(6);
		$sheet->getColumnDimension('U')->setWidth(6);
		$sheet->getColumnDimension('V')->setWidth(6);
	
		$sheet->setTitle("CGPA");
	
		$sheet->setCellValueByColumnAndRow($col, $row, "REGISTER NUMBER");$col++;
		$sheet->setCellValueByColumnAndRow($col, $row, "STUDENT NAME");$col++;
		$sheet->setCellValueByColumnAndRow($col, $row, "SEM 1");$col++;
		$sheet->setCellValueByColumnAndRow($col, $row, "SEM 2");$col++;
		$sheet->setCellValueByColumnAndRow($col, $row, "SEM 3");$col++;
		$sheet->setCellValueByColumnAndRow($col, $row, "SEM 4");$col++;
		$sheet->setCellValueByColumnAndRow($col, $row, "SEM 5");$col++;
		$sheet->setCellValueByColumnAndRow($col, $row, "SEM 6");$col++;
		$sheet->setCellValueByColumnAndRow($col, $row, "SEM 7");$col++;
		$sheet->setCellValueByColumnAndRow($col, $row, "SEM 8");$col++;
		$sheet->setCellValueByColumnAndRow($col, $row, "SEM 9");$col++;
		$sheet->setCellValueByColumnAndRow($col, $row, "SEM 10");$col++;
	
		$sheet->setCellValueByColumnAndRow($col, $row, "PROGRAM CR");$col++;
		$sheet->setCellValueByColumnAndRow($col, $row, "CR REGD");$col++;
		$sheet->setCellValueByColumnAndRow($col, $row, "CR EARNED");$col++;
		$sheet->setCellValueByColumnAndRow($col, $row, "GP EARNED");$col++;
		$sheet->setCellValueByColumnAndRow($col, $row, "ARREARS COUNT");$col++;
		$sheet->setCellValueByColumnAndRow($col, $row, "FIRST ATTEMPT");$col++;
		$sheet->setCellValueByColumnAndRow($col, $row, "CGPA");$col++;
		$sheet->setCellValueByColumnAndRow($col, $row, "STATUS");$col++;
		
		$row++;
		$row = 2;
		//pr($studentArray);
		foreach ($studentArray as $student_id => $result) { //pr($result); 
			$col = 0;
			$sheet->getRowDimension($row)->setRowHeight('18');
			$sheet->setCellValueByColumnAndRow($col, $row, $result['reg_num']);$col++;
			$sheet->setCellValueByColumnAndRow($col, $row, $result['name']);$col++;
			$sheet->setCellValueExplicitByColumnAndRow($col, $row, $result['semGpa']['semester1Gpa']);$col++;
			$sheet->setCellValueExplicitByColumnAndRow($col, $row, $result['semGpa']['semester2Gpa']);$col++;
			$sheet->setCellValueExplicitByColumnAndRow($col, $row, $result['semGpa']['semester3Gpa']);$col++;
			$sheet->setCellValueExplicitByColumnAndRow($col, $row, $result['semGpa']['semester4Gpa']);$col++;
			$sheet->setCellValueExplicitByColumnAndRow($col, $row, $result['semGpa']['semester5Gpa']);$col++;
			$sheet->setCellValueExplicitByColumnAndRow($col, $row, $result['semGpa']['semester6Gpa']);$col++;
			$sheet->setCellValueExplicitByColumnAndRow($col, $row, $result['semGpa']['semester7Gpa']);$col++;
			$sheet->setCellValueExplicitByColumnAndRow($col, $row, $result['semGpa']['semester8Gpa']);$col++;
			$sheet->setCellValueExplicitByColumnAndRow($col, $row, $result['semGpa']['semester9Gpa']);$col++;
			$sheet->setCellValueExplicitByColumnAndRow($col, $row, $result['semGpa']['semester10Gpa']);$col++;
			
			$sheet->setCellValueByColumnAndRow($col, $row, $result['program_credit']);$col++;
			$sheet->setCellValueByColumnAndRow($col, $row, $result['credits_reg']);$col++;
			$sheet->setCellValueByColumnAndRow($col, $row, $result['credits_earned']);$col++;
			$sheet->setCellValueByColumnAndRow($col, $row, $result['grade_point_earned']);$col++;
			$sheet->setCellValueByColumnAndRow($col, $row, $result['arrears']);$col++;
			$sheet->setCellValueByColumnAndRow($col, $row, $result['first_attempt']);$col++;
			$sheet->setCellValueExplicitByColumnAndRow($col, $row, $result['cgpa']);$col++;
			$sheet->setCellValueByColumnAndRow($col, $row, $result['status']);$col++;
			
			$row++;
		}
	
		$download_filename="CGPA-".date('d-M-Y h:i:s');
		header("Content-Type: application/vnd.ms-excel");
		header("Content-Disposition: attachment; filename=\"$download_filename.xls\"");
		header("Cache-Control: max-age=0");
		$objWriter = PHPExcel_IOFactory::createWriter($phpExcel, "Excel5");
		$objWriter->save("php://output");
		exit;
	}
	
	public function processCgpaReport ($results, $monthYearId, $batchId, $programId) {
		$cgpaReportArray = array();
		
		foreach ($results as $key => $result) {
			$studentId = $result['Student']['id'];
			
			$stuFinalMark = array();
			$stuMarkStatus = array();
			$stuMarkSemester = array();
			$TotCreditPointsArray = array();
			$smMonthYearArray = array();
			$cmMonthYearArray = array();
			//$noOfCoursesArray = array();
			
			$cgpaReportArray[$studentId]['reg_num'] = $result['Student']['registration_number'];
			$cgpaReportArray[$studentId]['name'] = $result['Student']['name'];
			
			$TotCreditPoints ="";$earnCreditPoints = "";$semesterPassCnt = "0";$noOfCourses = 0;$noOfArrears = "";
			$stuSemesterMark1 = "";$stuSemesterMark2 = "";$stuSemesterMark3 = "";$stuSemesterMark4 = "";$stuSemesterMark5 = "";
			$stuSemesterMark6 = "";$stuSemesterMark7 = "";$stuSemesterMark8 = "";$stuSemesterMark9 = "";$stuSemesterMark10 = "";
				
			$stuSemesterMark1_1 = "";$stuSemesterMark2_1 = "";$stuSemesterMark3_1 = "";$stuSemesterMark4_1 = "";$stuSemesterMark5_1 = "";
			$stuSemesterMark6_1 = "";$stuSemesterMark7_1 = "";$stuSemesterMark8_1 = "";$stuSemesterMark9_1 = "";$stuSemesterMark10_1 = "";
				
			$semester1CourseCredit = "0";$semester2CourseCredit = "0";$semester3CourseCredit = "0";$semester4CourseCredit = "0";$semester5CourseCredit = "0";
			$semester6CourseCredit = "0";$semester7CourseCredit = "0";$semester8CourseCredit = "0";$semester9CourseCredit = "0";$semester10CourseCredit = "0";
		
			$sem1Gpa = ""; $sem2Gpa = ""; $sem3Gpa = ""; $sem4Gpa = ""; $sem5Gpa = ""; 
			$sem6Gpa = ""; $sem7Gpa = ""; $sem8Gpa = ""; $sem9Gpa = ""; $sem10Gpa = "";
			
			$is_first_attempt = '';
			//$totalCourses = count($result['CourseStudentMapping']);
			
			$csm_results = $this->CourseStudentMapping->query("select CourseStudentMapping.course_mapping_id FROM
					course_student_mappings CourseStudentMapping
					JOIN course_mappings CourseMapping
					ON CourseStudentMapping.course_mapping_id = CourseMapping.id
					WHERE CourseMapping.month_year_id<=$monthYearId and CourseMapping.program_id=$programId
					and CourseMapping.batch_id=$batchId and CourseMapping.indicator=0
					and CourseStudentMapping.student_id=$studentId and CourseStudentMapping.indicator=0");
			//pr($csm_results);
			$totalCourses = count($csm_results);
			
			$tmpCsmArray = $result['CourseStudentMapping'];
			//if ($studentId == 3077) pr($result['CourseStudentMapping']);
			$csm_cm_id = array();
			foreach ($tmpCsmArray as $key => $csmDetails) {
				$csm_cm_id[$csmDetails['course_mapping_id']] = $csmDetails['new_semester_id'];
			}
			//echo "Total courses :".count($csm_cm_id)." ".$result['Student']['id'];
			//pr($csm_cm_id);
			
			$csmNewMonthYearId = array_filter($csm_cm_id);
			//pr($result['CourseStudentMapping']);
			 
			for($p=0;$p<count($result['StudentMark']);$p++){
		
				$courseMId = $result['StudentMark'][$p]['course_mapping_id'];
				
				if($result['StudentMark'][$p]['revaluation_status'] == 0){
					$stuFinalMark[$courseMId] = $result['StudentMark'][$p]['marks'];
					$stuMarkStatus[$courseMId] = $result['StudentMark'][$p]['status'];
					$stuMarkSemester[$courseMId] = $result['StudentMark'][$p]['CourseMapping']['semester_id'];
		
				}else{
					/* if ($monthYearId == $result['StudentMark'][$p]['month_year_id']) {
						$stuFinalMark[$courseMId] = $result['StudentMark'][$p]['marks'];
						$stuMarkStatus[$courseMId] = $result['StudentMark'][$p]['status'];
						$stuMarkSemester[$courseMId] = $result['StudentMark'][$p]['CourseMapping']['semester_id'];
					}
					else { */
						$stuFinalMark[$courseMId] = $result['StudentMark'][$p]['final_marks'];
						$stuMarkStatus[$courseMId] = $result['StudentMark'][$p]['final_status'];
						$stuMarkSemester[$courseMId] = $result['StudentMark'][$p]['CourseMapping']['semester_id'];
					//}
				}
		
				$CourseCP = $this->getGP($courseMId,$stuFinalMark[$courseMId],'1');
				
				$cmMonthYearArray[$courseMId] = $result['StudentMark'][$p]['CourseMapping']['month_year_id'];
				
				if($stuMarkStatus[$courseMId] == 'Pass'){
					$semesterPassCnt = $semesterPassCnt +1;
					$earnCreditPoints = $earnCreditPoints + $CourseCP;
				}
				if(isset($stuFinalMark[$courseMId])){
					//$RANGE_OF_MARKS_FOR_GRADES = $this->getGP($courseMId,$stuFinalMark[$courseMId],'2');
					$RANGE_OF_MARKS_FOR_GRADES = $result['StudentMark'][$p]['grade_point'];
					//$TotCreditPoints = $TotCreditPoints + $CourseCP;
					$TotCreditPointsArray[$courseMId] = $CourseCP;
					//$noOfCoursesArray[$courseMId] = $courseMId;
					
					if ($stuMarkStatus[$courseMId] == 'Pass') {
						$smMonthYearArray[$courseMId] = $result['StudentMark'][$p]['month_year_id'];
					}
					if($stuMarkSemester[$courseMId] == 1){
						if($stuMarkStatus[$courseMId] == 'Pass'){
							$TotCreditPoints = $TotCreditPoints + $CourseCP;
							$semester1CourseCredit = $semester1CourseCredit +$CourseCP;
							$stuSemesterMark1 = $stuSemesterMark1+$RANGE_OF_MARKS_FOR_GRADES;
						}
						$stuSemesterMark1_1 = $stuSemesterMark1_1+($result['StudentMark'][$p]['CourseMapping']['Course']['credit_point']*$RANGE_OF_MARKS_FOR_GRADES);
					}
					if($stuMarkSemester[$courseMId] == 2){
						if($stuMarkStatus[$courseMId] == 'Pass'){
							$TotCreditPoints = $TotCreditPoints + $CourseCP;
							$semester2CourseCredit = $semester2CourseCredit +$CourseCP;
							$stuSemesterMark2 = $stuSemesterMark2+$RANGE_OF_MARKS_FOR_GRADES;
						}
						$stuSemesterMark2_1 = $stuSemesterMark2_1+($result['StudentMark'][$p]['CourseMapping']['Course']['credit_point']*$RANGE_OF_MARKS_FOR_GRADES);
					}
					if($stuMarkSemester[$courseMId] == 3){
						if($stuMarkStatus[$courseMId] == 'Pass'){
							$TotCreditPoints = $TotCreditPoints + $CourseCP;
							$semester3CourseCredit = $semester3CourseCredit +$CourseCP;
							$stuSemesterMark3 = $stuSemesterMark3+$RANGE_OF_MARKS_FOR_GRADES;
						}
						$stuSemesterMark3_1 = $stuSemesterMark3_1+($result['StudentMark'][$p]['CourseMapping']['Course']['credit_point']*$RANGE_OF_MARKS_FOR_GRADES);
					}
					if($stuMarkSemester[$courseMId] == 4){
						if($stuMarkStatus[$courseMId] == 'Pass'){
							$TotCreditPoints = $TotCreditPoints + $CourseCP;
							$semester4CourseCredit = $semester4CourseCredit +$CourseCP;
							$stuSemesterMark4 = $stuSemesterMark4+$RANGE_OF_MARKS_FOR_GRADES;
						}
						$stuSemesterMark4_1 = $stuSemesterMark4_1+($result['StudentMark'][$p]['CourseMapping']['Course']['credit_point']*$RANGE_OF_MARKS_FOR_GRADES);
					}
					
					if($stuMarkSemester[$courseMId] == 5){
						if($stuMarkStatus[$courseMId] == 'Pass'){
							$TotCreditPoints = $TotCreditPoints + $CourseCP;
							$semester5CourseCredit = $semester5CourseCredit +$CourseCP;
							$stuSemesterMark5 = $stuSemesterMark5+$RANGE_OF_MARKS_FOR_GRADES;
						}
						$stuSemesterMark5_1 = $stuSemesterMark5_1+($result['StudentMark'][$p]['CourseMapping']['Course']['credit_point']*$RANGE_OF_MARKS_FOR_GRADES);
					}
					if($stuMarkSemester[$courseMId] == 6){
						if($stuMarkStatus[$courseMId] == 'Pass'){
							$TotCreditPoints = $TotCreditPoints + $CourseCP;
							$semester6CourseCredit = $semester6CourseCredit +$CourseCP;
							$stuSemesterMark6 = $stuSemesterMark6+$RANGE_OF_MARKS_FOR_GRADES;
						}
						$stuSemesterMark6_1 = $stuSemesterMark6_1+($result['StudentMark'][$p]['CourseMapping']['Course']['credit_point']*$RANGE_OF_MARKS_FOR_GRADES);
					}
					if($stuMarkSemester[$courseMId] == 7){
						if($stuMarkStatus[$courseMId] == 'Pass'){
							$TotCreditPoints = $TotCreditPoints + $CourseCP;
							$semester7CourseCredit = $semester7CourseCredit +$CourseCP;
							$stuSemesterMark7 = $stuSemesterMark7+$RANGE_OF_MARKS_FOR_GRADES;
						}
						$stuSemesterMark7_1 = $stuSemesterMark7_1+($result['StudentMark'][$p]['CourseMapping']['Course']['credit_point']*$RANGE_OF_MARKS_FOR_GRADES);
					}
					if($stuMarkSemester[$courseMId] == 8){
						if($stuMarkStatus[$courseMId] == 'Pass'){
							$TotCreditPoints = $TotCreditPoints + $CourseCP;
							$semester8CourseCredit = $semester8CourseCredit +$CourseCP;
							$stuSemesterMark8 = $stuSemesterMark8+$RANGE_OF_MARKS_FOR_GRADES;
						}
						$stuSemesterMark8_1 = $stuSemesterMark8_1+($result['StudentMark'][$p]['CourseMapping']['Course']['credit_point']*$RANGE_OF_MARKS_FOR_GRADES);
					}
					if($stuMarkSemester[$courseMId] == 9){
						if($stuMarkStatus[$courseMId] == 'Pass'){
							$TotCreditPoints = $TotCreditPoints + $CourseCP;
							$semester9CourseCredit = $semester9CourseCredit +$CourseCP;
							$stuSemesterMark9 = $stuSemesterMark9+$RANGE_OF_MARKS_FOR_GRADES;
						}
						$stuSemesterMark9_1 = $stuSemesterMark9_1+($result['StudentMark'][$p]['CourseMapping']['Course']['credit_point']*$RANGE_OF_MARKS_FOR_GRADES);
					}
					if($stuMarkSemester[$courseMId] == 10){
						if($stuMarkStatus[$courseMId] == 'Pass'){
							$TotCreditPoints = $TotCreditPoints + $CourseCP;
							$semester10CourseCredit = $semester10CourseCredit +$CourseCP;
							$stuSemesterMark10 = $stuSemesterMark10+$RANGE_OF_MARKS_FOR_GRADES;
						}
						$stuSemesterMark10_1 = $stuSemesterMark10_1+($result['StudentMark'][$p]['CourseMapping']['Course']['credit_point']*$RANGE_OF_MARKS_FOR_GRADES);
					}
				}
				$available_semesters = max($stuMarkSemester);
			}
		
			$totalGP = "";
			if($stuSemesterMark1){
				$totalGP = sprintf('%0.2f',round($stuSemesterMark1_1)/$semester1CourseCredit);
				$sem1Gpa = sprintf('%0.2f',round($stuSemesterMark1_1)/$semester1CourseCredit);
			} else if($stuSemesterMark1<>"") {
				$sem1Gpa = 0;
			}
				
			if($stuSemesterMark2){
				$totalGP = sprintf('%0.2f',round($stuSemesterMark1_1 + $stuSemesterMark2_1) / ($semester1CourseCredit + $semester2CourseCredit));
				$sem2Gpa = sprintf('%0.2f',round($stuSemesterMark2_1)/$semester2CourseCredit);
			} else if($available_semesters == 2) {
				$sem2Gpa = 0;
			}
		
			if($stuSemesterMark3){
				$totalGP = sprintf('%0.2f',round($stuSemesterMark1_1 + $stuSemesterMark2_1 + $stuSemesterMark3_1) / ($semester1CourseCredit + $semester2CourseCredit + $semester3CourseCredit));
				$sem3Gpa = sprintf('%0.2f',round($stuSemesterMark3_1)/$semester3CourseCredit);
			} else if($available_semesters == 3) {
				$sem3Gpa = 0;
			}
		
			if($stuSemesterMark4){
				$totalGP = sprintf('%0.2f',round($stuSemesterMark1_1 + $stuSemesterMark2_1 + $stuSemesterMark3_1 + $stuSemesterMark4_1) / ($semester1CourseCredit + $semester2CourseCredit + $semester3CourseCredit + $semester4CourseCredit));
				$sem4Gpa = sprintf('%0.2f',round($stuSemesterMark4_1)/$semester4CourseCredit);
			} else if($available_semesters == 4) {
				$sem4Gpa = 0;
			}
		
			if($stuSemesterMark5){
				$totalGP = sprintf('%0.2f',round($stuSemesterMark1_1 + $stuSemesterMark2_1 + $stuSemesterMark3_1 + $stuSemesterMark4_1 + $stuSemesterMark5_1) / ($semester1CourseCredit + $semester2CourseCredit + $semester3CourseCredit + $semester4CourseCredit + $semester5CourseCredit));
				$sem5Gpa = sprintf('%0.2f',round($stuSemesterMark5_1)/$semester5CourseCredit);
				echo "***5".$totalGP;
			} else if($available_semesters == 5) {
				$sem5Gpa = 0;
			}
		
			if($stuSemesterMark6){
				$totalGP = sprintf('%0.2f',round($stuSemesterMark1_1 + $stuSemesterMark2_1 + $stuSemesterMark3_1 + $stuSemesterMark4_1 + $stuSemesterMark5_1 + $stuSemesterMark6_1) / ($semester1CourseCredit + $semester2CourseCredit + $semester3CourseCredit + $semester4CourseCredit + $semester5CourseCredit + $semester6CourseCredit));
				$sem6Gpa = sprintf('%0.2f',round($stuSemesterMark6_1)/$semester6CourseCredit);
			} else if($available_semesters == 6) {
				$sem6Gpa = 0;
			}
		
			if($stuSemesterMark7){
				$totalGP = sprintf('%0.2f',round($stuSemesterMark1_1 + $stuSemesterMark2_1 + $stuSemesterMark3_1 + $stuSemesterMark4_1 + $stuSemesterMark5_1 + $stuSemesterMark6_1 + $stuSemesterMark7_1) / ($semester1CourseCredit + $semester2CourseCredit + $semester3CourseCredit + $semester4CourseCredit + $semester5CourseCredit + $semester6CourseCredit + $semester7CourseCredit));
				$sem7Gpa = sprintf('%0.2f',round($stuSemesterMark7_1)/$semester7CourseCredit);
			} else if($available_semesters == 7) {
				$sem7Gpa = 0;
			}
		
			if($stuSemesterMark8){
				$totalGP = sprintf('%0.2f',round($stuSemesterMark1_1 + $stuSemesterMark2_1 + $stuSemesterMark3_1 + $stuSemesterMark4_1 + $stuSemesterMark5_1 + $stuSemesterMark6_1 + $stuSemesterMark7_1 + $stuSemesterMark8_1) / ($semester1CourseCredit + $semester2CourseCredit + $semester3CourseCredit + $semester4CourseCredit + $semester5CourseCredit + $semester6CourseCredit + $semester7CourseCredit + $semester8CourseCredit));
				$sem8Gpa = sprintf('%0.2f',round($stuSemesterMark8_1)/$semester8CourseCredit);
			} else if($available_semesters == 8) {
				$sem8Gpa = 0;
			}
		
			if($stuSemesterMark9){
				$totalGP = sprintf('%0.2f',round($stuSemesterMark1_1 + $stuSemesterMark2_1 + $stuSemesterMark3_1 + $stuSemesterMark4_1 + $stuSemesterMark5_1 + $stuSemesterMark6_1 + $stuSemesterMark7_1 + $stuSemesterMark8_1 + $stuSemesterMark9_1) / ($semester1CourseCredit + $semester2CourseCredit + $semester3CourseCredit + $semester4CourseCredit + $semester5CourseCredit + $semester6CourseCredit + $semester7CourseCredit + $semester8CourseCredit + $semester9CourseCredit));
				$sem9Gpa = sprintf('%0.2f',round($stuSemesterMark9_1)/$semester9CourseCredit);
			} else if($available_semesters == 9) {
				$sem9Gpa = 0;
			}
		
			if($stuSemesterMark10){
				$totalGP = sprintf('%0.2f',round($stuSemesterMark1_1 + $stuSemesterMark2_1 + $stuSemesterMark3_1 + $stuSemesterMark4_1 + $stuSemesterMark5_1 + $stuSemesterMark6_1 + $stuSemesterMark7_1 + $stuSemesterMark8_1 + $stuSemesterMark9_1 + $stuSemesterMark10_1) / ($semester1CourseCredit + $semester2CourseCredit + $semester3CourseCredit + $semester4CourseCredit + $semester5CourseCredit + $semester6CourseCredit + $semester7CourseCredit + $semester8CourseCredit + $semester9CourseCredit + $semester10CourseCredit));
				$sem10Gpa = sprintf('%0.2f',round($stuSemesterMark10_1)/$semester10CourseCredit);
			} else if($available_semesters == 10) {
				$sem10Gpa = 0;
			}
			
			$cgpaReportArray[$studentId]['semGpa']['semester1Gpa'] = $sem1Gpa;
			$cgpaReportArray[$studentId]['semGpa']['semester2Gpa'] = $sem2Gpa;
			$cgpaReportArray[$studentId]['semGpa']['semester3Gpa'] = $sem3Gpa;
			$cgpaReportArray[$studentId]['semGpa']['semester4Gpa'] = $sem4Gpa;
			$cgpaReportArray[$studentId]['semGpa']['semester5Gpa'] = $sem5Gpa;
			$cgpaReportArray[$studentId]['semGpa']['semester6Gpa'] = $sem6Gpa;
			$cgpaReportArray[$studentId]['semGpa']['semester7Gpa'] = $sem7Gpa;
			$cgpaReportArray[$studentId]['semGpa']['semester8Gpa'] = $sem8Gpa;
			$cgpaReportArray[$studentId]['semGpa']['semester9Gpa'] = $sem9Gpa;
			$cgpaReportArray[$studentId]['semGpa']['semester10Gpa'] = $sem10Gpa;
			
			$cgpaReportArray[$studentId]['program_credit'] = $result['Program']['credits'];
			$cgpaReportArray[$studentId]['credits_reg'] = array_sum($TotCreditPointsArray);
			$cgpaReportArray[$studentId]['credits_earned'] = $earnCreditPoints;
			$grade_point_earned = $stuSemesterMark1+$stuSemesterMark2+$stuSemesterMark3+$stuSemesterMark4+$stuSemesterMark5+$stuSemesterMark6+$stuSemesterMark7+$stuSemesterMark8+$stuSemesterMark9+$stuSemesterMark10;
			$cgpaReportArray[$studentId]['grade_point_earned'] = $grade_point_earned;
		//	echo $totalCourses." ".$semesterPassCnt;
			$arrears = $totalCourses - $semesterPassCnt;
			if ($arrears > 0) $cgpaReportArray[$studentId]['arrears'] = $arrears;
			else $cgpaReportArray[$studentId]['arrears'] = '';
			
			//pr($stuMarkStatus);
			
			//first attempt code
			$first_attempt = array_diff_assoc($smMonthYearArray,array_intersect_assoc($smMonthYearArray, $cmMonthYearArray));
			//pr($first_attempt);
				
			//$first_attempt = array_diff_assoc($courseSemester,array_intersect_assoc($courseSemester, $semesterIdArray));
			//if ($studentId == 3077) echo count($smMonthYearArray)." ".count($cmMonthYearArray)." ".count($result['CourseStudentMapping']);
			ksort($smMonthYearArray);
			ksort($cmMonthYearArray);
			
			//pr($smMonthYearArray);
			//pr($cmMonthYearArray);
			
			ksort($csmNewMonthYearId);
			ksort($first_attempt);
			
			if ($arrears == 0) { 
				if ($smMonthYearArray == $cmMonthYearArray) { 
					$is_first_attempt = 'Y';
				}
				if ((isset($csmNewMonthYearId) && count($csmNewMonthYearId) > 0) && 
						(isset($first_attempt) && count($first_attempt) > 0) && 
						($csmNewMonthYearId == $first_attempt)) { 
					$is_first_attempt = 'Y';
				} 
			}
			else $is_first_attempt = '';
			
			$cgpaReportArray[$studentId]['first_attempt'] = $is_first_attempt;
			
			$cgpaReportArray[$studentId]['cgpa'] = $totalGP;
			if(($earnCreditPoints >= $result['Program']['credits']) && ($totalCourses-$semesterPassCnt)){
				$status = 'Pass';
			} else {
				$status = '';
			}
			//if ($studentId == 3079) echo $totalCourses." ".$semesterPassCnt."***".$status."</br>";
			$cgpaReportArray[$studentId]['status'] = $status;
		}
		return $cgpaReportArray; 
	}
	
	public function processTotalReport ($results, $csmArray) {
		$totalReportArray = array();
		
		foreach ($results as $key => $result) {
			$studentId = $result['Student']['id'];
			if (isset($result['StudentAuthorizedBreak'][0]['student_id'])) $abs=1;
			else $abs=0;
			$totalReportArray[$studentId]['abs'] = $abs;

			if (isset($result['StudentWithdrawal'][0]['student_id'])) $withdrawal=1;
			else $withdrawal=0;
			$totalReportArray[$studentId]['withdrawal'] = $withdrawal;
			
			$totalReportArray[$studentId]['reg_num'] = $result['Student']['registration_number'];
			$totalReportArray[$studentId]['name'] = $result['Student']['name'];
			
			$stuFinalMark = array();
			$stuMarkStatus = array();
			$stuMarkSemester = array();
			$CourseCP =  "";
				
			//$totalReportArray[$studentId]['reg_num'] = result['Student']['registration_number'];
			//$totalReportArray[$studentId]['name'] = result['Student']['name'];
			
			//$TotCreditPoints ="";
			
			$earnCreditPoints = "";$semesterPassCnt = "0";$noOfCourses = 0;$noOfArrears = "";
			$stuSemesterMark1 = "";$stuSemesterMark2 = "";$stuSemesterMark3 = "";$stuSemesterMark4 = "";$stuSemesterMark5 = "";
			$stuSemesterMark6 = "";$stuSemesterMark7 = "";$stuSemesterMark8 = "";$stuSemesterMark9 = "";$stuSemesterMark10 = "";
				
			$stuSemesterMark6_1 = "";$stuSemesterMark7_1 = "";$stuSemesterMark8_1 = "";$stuSemesterMark9_1 = "";$stuSemesterMark10_1 = "";
			$stuSemesterMark1_1 = "";$stuSemesterMark2_1 = "";$stuSemesterMark3_1 = "";$stuSemesterMark4_1 = "";$stuSemesterMark5_1 = "";
				
			$semester1CourseCredit = "0";$semester2CourseCredit = "0";$semester3CourseCredit = "0";$semester4CourseCredit = "0";$semester5CourseCredit = "0";
			$semester6CourseCredit = "0";$semester7CourseCredit = "0";$semester8CourseCredit = "0";$semester9CourseCredit = "0";$semester10CourseCredit = "0";
		
			$semester1Total = "0";$semester2Total = "0";$semester3Total = "0";$semester4Total = "0";$semester5Total = "0";
			$semester6Total = "0";$semester7Total = "0";$semester8Total = "0";$semester9Total = "0";$semester10Total = "0";
				
			$TotCreditPointsArray = array();
				
			$csmNewMonthYearId = array();
		
			$total = 0;
			$studentTotal = 0;
			$crseCount = 0;
			$passCount = 0;
			$totalCoursePassMark = 0;
			$totalMarkArray = array();
			$monthYearOfPassing = array();
			$actualMonthYear = array();
			$first_attempt = array();
			$classSecured = "";
			$arrears = "";
			$resStatus = "";
			
			$tmpCsmArray = $result['CourseStudentMapping'];
			//pr($result['CourseStudentMapping']);
			$csm_cm_id = array();
			foreach ($tmpCsmArray as $key => $csmDetails) {
				$csm_cm_id[$csmDetails['course_mapping_id']] = $csmDetails['new_semester_id'];
			}
			//echo "Total courses :".count($csm_cm_id)." ".$result['Student']['id'];
			//pr($csm_cm_id);
				
			$csmNewMonthYearId = array_filter($csm_cm_id);
			//pr($csmArray);
			for($p=0;$p<count($result['StudentMark']);$p++) {
		
				$IEV = ""; $ESV = "";
				$courseMId = $result['StudentMark'][$p]['course_mapping_id'];
				if ($csmArray[$result['Student']['id']][$courseMId]==0) {
					if($result['StudentMark'][$p]['revaluation_status'] == 0){
						$stuFinalMark[$courseMId] = $result['StudentMark'][$p]['marks'];
						$stuMarkStatus[$courseMId] = $result['StudentMark'][$p]['status'];
						$stuMarkSemester[$courseMId] = $result['StudentMark'][$p]['CourseMapping']['semester_id'];
					}else{
						$stuFinalMark[$courseMId] = $result['StudentMark'][$p]['final_marks'];
						$stuMarkStatus[$courseMId] = $result['StudentMark'][$p]['final_status'];
						$stuMarkSemester[$courseMId] = $result['StudentMark'][$p]['CourseMapping']['semester_id'];
					}
					//echo "</br>".$courseMId." ".$result['StudentMark'][$p]['month_year_id'];
					$monthYearOfPassing[$courseMId] = $result['StudentMark'][$p]['month_year_id'];
					$actualMonthYear[$courseMId] = $result['StudentMark'][$p]['CourseMapping']['month_year_id'];
		
					//$CourseCP = $this->Html->getGP($courseMId,$stuFinalMark[$courseMId],'1');
					$CourseCP = $result['StudentMark'][$p]['CourseMapping']['Course']['credit_point'];
					//$total += $result['StudentMark'][$p]['CourseMapping']['Course']['course_max_marks'];
					$totalMarkArray[$courseMId] = $result['StudentMark'][$p]['CourseMapping']['Course']['course_max_marks'];
		
					//echo $crseMark;
		
					if($stuMarkStatus[$courseMId] == 'Pass') {
						$passCount = $passCount + 1;
						$totalCoursePassMark += $stuFinalMark[$courseMId];
						$semesterPassCnt = $semesterPassCnt +1;
						$earnCreditPoints = $earnCreditPoints + $CourseCP;
						//$studentTotal = $studentTotal + $stuFinalMark[$courseMId];
					}
		
					//echo $studentTotal."**";
					if(isset($stuFinalMark[$courseMId])){
						//$RANGE_OF_MARKS_FOR_GRADES = $this->Html->getGP($courseMId,$stuFinalMark[$courseMId],'2');
						$RANGE_OF_MARKS_FOR_GRADES = $result['StudentMark'][$p]['grade_point'];
						$TotCreditPointsArray[$courseMId] = $CourseCP;
						$noOfCourses = $noOfCourses +1;
						if($stuMarkSemester[$courseMId] == 1){
							if($stuMarkStatus[$courseMId] == 'Pass'){
								$semester1CourseCredit = $semester1CourseCredit +$CourseCP;
								$stuSemesterMark1 = $stuSemesterMark1+$RANGE_OF_MARKS_FOR_GRADES;
								$semester1Total = $semester1Total + $stuFinalMark[$courseMId];
							}
							$stuSemesterMark1_1 = $stuSemesterMark1_1+($result['StudentMark'][$p]['CourseMapping']['Course']['credit_point']*$RANGE_OF_MARKS_FOR_GRADES);
						}
						if($stuMarkSemester[$courseMId] == 2){
							if($stuMarkStatus[$courseMId] == 'Pass'){
								$semester2CourseCredit = $semester2CourseCredit +$CourseCP;
								$stuSemesterMark2 = $stuSemesterMark2+$RANGE_OF_MARKS_FOR_GRADES;
								$semester2Total = $semester2Total + $stuFinalMark[$courseMId];
							}
							$stuSemesterMark2_1 = $stuSemesterMark2_1+($result['StudentMark'][$p]['CourseMapping']['Course']['credit_point']*$RANGE_OF_MARKS_FOR_GRADES);
						}
						if($stuMarkSemester[$courseMId] == 3){
							if($stuMarkStatus[$courseMId] == 'Pass'){
								$semester3CourseCredit = $semester3CourseCredit +$CourseCP;
								$stuSemesterMark3 = $stuSemesterMark3+$RANGE_OF_MARKS_FOR_GRADES;
								$semester3Total = $semester3Total + $stuFinalMark[$courseMId];
							}
							$stuSemesterMark3_1 = $stuSemesterMark3_1+($result['StudentMark'][$p]['CourseMapping']['Course']['credit_point']*$RANGE_OF_MARKS_FOR_GRADES);
						}
						if($stuMarkSemester[$courseMId] == 4){
							if($stuMarkStatus[$courseMId] == 'Pass'){
								$semester4CourseCredit = $semester4CourseCredit +$CourseCP;
								$stuSemesterMark4 = $stuSemesterMark4+$RANGE_OF_MARKS_FOR_GRADES;
								$semester4Total = $semester4Total + $stuFinalMark[$courseMId];
							}
							$stuSemesterMark4_1 = $stuSemesterMark4_1+($result['StudentMark'][$p]['CourseMapping']['Course']['credit_point']*$RANGE_OF_MARKS_FOR_GRADES);
						}
						if($stuMarkSemester[$courseMId] == 5){
							if($stuMarkStatus[$courseMId] == 'Pass'){
								$semester5CourseCredit = $semester5CourseCredit +$CourseCP;
								$stuSemesterMark5 = $stuSemesterMark5+$RANGE_OF_MARKS_FOR_GRADES;
								$semester5Total = $semester5Total + $stuFinalMark[$courseMId];
							}
							$stuSemesterMark5_1 = $stuSemesterMark5_1+($result['StudentMark'][$p]['CourseMapping']['Course']['credit_point']*$RANGE_OF_MARKS_FOR_GRADES);
						}
						if($stuMarkSemester[$courseMId] == 6){
							if($stuMarkStatus[$courseMId] == 'Pass'){
								$semester6CourseCredit = $semester6CourseCredit +$CourseCP;
								$stuSemesterMark6 = $stuSemesterMark6+$RANGE_OF_MARKS_FOR_GRADES;
								$semester6Total = $semester6Total + $stuFinalMark[$courseMId];
							}
							$stuSemesterMark6_1 = $stuSemesterMark6_1+($result['StudentMark'][$p]['CourseMapping']['Course']['credit_point']*$RANGE_OF_MARKS_FOR_GRADES);
						}
						if($stuMarkSemester[$courseMId] == 7){
							if($stuMarkStatus[$courseMId] == 'Pass'){
								$semester7CourseCredit = $semester7CourseCredit +$CourseCP;
								$stuSemesterMark7 = $stuSemesterMark7+$RANGE_OF_MARKS_FOR_GRADES;
								$semester7Total = $semester7Total + $stuFinalMark[$courseMId];
							}
							$stuSemesterMark7_1 = $stuSemesterMark7_1+($result['StudentMark'][$p]['CourseMapping']['Course']['credit_point']*$RANGE_OF_MARKS_FOR_GRADES);
						}
						if($stuMarkSemester[$courseMId] == 8){
							if($stuMarkStatus[$courseMId] == 'Pass'){
								$semester8CourseCredit = $semester8CourseCredit +$CourseCP;
								$stuSemesterMark8 = $stuSemesterMark8+$RANGE_OF_MARKS_FOR_GRADES;
								$semester8Total = $semester8Total + $stuFinalMark[$courseMId];
							}
							$stuSemesterMark8_1 = $stuSemesterMark8_1+($result['StudentMark'][$p]['CourseMapping']['Course']['credit_point']*$RANGE_OF_MARKS_FOR_GRADES);
						}
						if($stuMarkSemester[$courseMId] == 9){
							if($stuMarkStatus[$courseMId] == 'Pass'){
								$semester9CourseCredit = $semester9CourseCredit +$CourseCP;
								$stuSemesterMark9 = $stuSemesterMark9+$RANGE_OF_MARKS_FOR_GRADES;
								$semester9Total = $semester9Total + $stuFinalMark[$courseMId];
							}
							$stuSemesterMark9_1 = $stuSemesterMark9_1+($result['StudentMark'][$p]['CourseMapping']['Course']['credit_point']*$RANGE_OF_MARKS_FOR_GRADES);
						}
						if($stuMarkSemester[$courseMId] == 10){
							if($stuMarkStatus[$courseMId] == 'Pass'){
								$semester10CourseCredit = $semester10CourseCredit +$CourseCP;
								$stuSemesterMark10 = $stuSemesterMark10+$RANGE_OF_MARKS_FOR_GRADES;
								$semester10Total = $semester10Total + $stuFinalMark[$courseMId];
							}
							$stuSemesterMark10_1 = $stuSemesterMark10_1+($result['StudentMark'][$p]['CourseMapping']['Course']['credit_point']*$RANGE_OF_MARKS_FOR_GRADES);
						}
					}
					$available_semesters = max($stuMarkSemester);
				}
			} // end FOR at line 99
				
			//echo " Pass count : ".$passCount;
				
			//$diff_array = array_diff_key($csm_cm_id, $stuMarkStatus);
			//pr($diff_array);
		
			//if (count($csm_cm_id) == $passCount) { echo "TRUE"; }
			ksort($monthYearOfPassing);
			ksort($actualMonthYear);
			//echo "Month and Year of Passing";
			//pr($monthYearOfPassing);
			//echo "CM MonthYear";
			//pr($actualMonthYear);
			$first_attempt = array_diff_assoc($actualMonthYear,array_intersect_assoc($actualMonthYear, $monthYearOfPassing));
		//	echo "First attempt array";
		////	pr($first_attempt);
		
			//echo "new month year id";
			$csmNewMonthYearId = array_filter($csm_cm_id);
			//pr($csmNewMonthYearId);
			//echo "intersection of csmNewMonthYearId and monthYearOfPassing";
			$withdrawalFirstAttempt = array_intersect_assoc($csmNewMonthYearId, $monthYearOfPassing);
			//pr($withdrawalFirstAttempt);
			if (isset($withdrawalFirstAttempt) && count($withdrawalFirstAttempt) > 0) {
				$withdrawal = 0;
				if ($csmNewMonthYearId == $withdrawalFirstAttempt) {
					$first_attempt = [];
				}
			}
			$total = array_sum($totalMarkArray);
			$totalReportArray[$studentId]['total'] = $total;
				
			$noOfSemesters = 0;
			$totalGP = "";
			
			if($stuSemesterMark1){
				$totalGP = sprintf('%0.2f',round($stuSemesterMark1_1)/$semester1CourseCredit);
			} else if($stuSemesterMark1<>"") {
				$semester1Total = "0";
			}
				
			if($stuSemesterMark2){ //echo $totalGP." --- ".$stuSemesterMark2." --- ".$semester2CourseCredit." ---";
				$totalGP = sprintf('%0.2f',round($stuSemesterMark1_1 + $stuSemesterMark2_1) / ($semester1CourseCredit + $semester2CourseCredit));
			} else if($available_semesters == 2) {
				$semester2Total = "0";
			}
				
			if($stuSemesterMark3){
				$totalGP = sprintf('%0.2f',round($stuSemesterMark1_1 + $stuSemesterMark2_1 + $stuSemesterMark3_1) / ($semester1CourseCredit + $semester2CourseCredit + $semester3CourseCredit));
			} else if($available_semesters == 3) {
				$semester3Total = "0";
			}
				
			if($stuSemesterMark4){
				$totalGP = sprintf('%0.2f',round($stuSemesterMark1_1 + $stuSemesterMark2_1 + $stuSemesterMark3_1 + $stuSemesterMark4_1) / ($semester1CourseCredit + $semester2CourseCredit + $semester3CourseCredit + $semester4CourseCredit));
			} else if($available_semesters == 4) {
				$semester4Total = "0";
			}
			
			if($stuSemesterMark5){
				$totalGP = sprintf('%0.2f',round($stuSemesterMark1_1 + $stuSemesterMark2_1 + $stuSemesterMark3_1 + $stuSemesterMark4_1 + $stuSemesterMark5_1) / ($semester1CourseCredit + $semester2CourseCredit + $semester3CourseCredit + $semester4CourseCredit + $semester5CourseCredit));
			} else if($available_semesters == 5) {
				$semester5Total = "0";
			}
				
			if($stuSemesterMark6){
				$totalGP = sprintf('%0.2f',round($stuSemesterMark1_1 + $stuSemesterMark2_1 + $stuSemesterMark3_1 + $stuSemesterMark4_1 + $stuSemesterMark5_1 + $stuSemesterMark6_1) / ($semester1CourseCredit + $semester2CourseCredit + $semester3CourseCredit + $semester4CourseCredit + $semester5CourseCredit + $semester6CourseCredit));
			} else if($available_semesters == 6) {
				$semester6Total = "0";
			}
				
			if($stuSemesterMark7){
				$totalGP = sprintf('%0.2f',round($stuSemesterMark1_1 + $stuSemesterMark2_1 + $stuSemesterMark3_1 + $stuSemesterMark4_1 + $stuSemesterMark5_1 + $stuSemesterMark6_1 + $stuSemesterMark7_1) / ($semester1CourseCredit + $semester2CourseCredit + $semester3CourseCredit + $semester4CourseCredit + $semester5CourseCredit + $semester6CourseCredit + $semester7CourseCredit));
			} else if($available_semesters == 7) {
				$semester7Total = "0";
			}
				
			if($stuSemesterMark8){
				$totalGP = sprintf('%0.2f',round($stuSemesterMark1_1 + $stuSemesterMark2_1 + $stuSemesterMark3_1 + $stuSemesterMark4_1 + $stuSemesterMark5_1 + $stuSemesterMark6_1 + $stuSemesterMark7_1 + $stuSemesterMark8_1) / ($semester1CourseCredit + $semester2CourseCredit + $semester3CourseCredit + $semester4CourseCredit + $semester5CourseCredit + $semester6CourseCredit + $semester7CourseCredit + $semester8CourseCredit));
			} else if($available_semesters == 8) {
				$semester8Total = "0";
			}
				
			if($stuSemesterMark9){
				$totalGP = sprintf('%0.2f',round($stuSemesterMark1_1 + $stuSemesterMark2_1 + $stuSemesterMark3_1 + $stuSemesterMark4_1 + $stuSemesterMark5_1 + $stuSemesterMark6_1 + $stuSemesterMark7_1 + $stuSemesterMark8_1 + $stuSemesterMark9_1) / ($semester1CourseCredit + $semester2CourseCredit + $semester3CourseCredit + $semester4CourseCredit + $semester5CourseCredit + $semester6CourseCredit + $semester7CourseCredit + $semester8CourseCredit + $semester9CourseCredit));
			} else if($available_semesters == 9) {
				$semester9Total = "0";
			}
				
			if($stuSemesterMark10){
				$totalGP = sprintf('%0.2f',round($stuSemesterMark1_1 + $stuSemesterMark2_1 + $stuSemesterMark3_1 + $stuSemesterMark4_1 + $stuSemesterMark5_1 + $stuSemesterMark6_1 + $stuSemesterMark7_1 + $stuSemesterMark8_1 + $stuSemesterMark9_1 + $stuSemesterMark10_1) / ($semester1CourseCredit + $semester2CourseCredit + $semester3CourseCredit + $semester4CourseCredit + $semester5CourseCredit + $semester6CourseCredit + $semester7CourseCredit + $semester8CourseCredit + $semester9CourseCredit + $semester10CourseCredit));
			} else if($available_semesters == 10) {
				$semester10Total = "0";
			}
			//for ($k=1; $k<=10; $k++) {
				//$totalReportArray[$studentId]['semester'.$k.'Total'] = "$semester".$k."Total";
			//}
			$totalReportArray[$studentId]['semTotal']['semester1Total'] = $semester1Total;
			$totalReportArray[$studentId]['semTotal']['semester2Total'] = $semester2Total;
			$totalReportArray[$studentId]['semTotal']['semester3Total'] = $semester3Total;
			$totalReportArray[$studentId]['semTotal']['semester4Total'] = $semester4Total;
			$totalReportArray[$studentId]['semTotal']['semester5Total'] = $semester5Total;
			$totalReportArray[$studentId]['semTotal']['semester6Total'] = $semester6Total;
			$totalReportArray[$studentId]['semTotal']['semester7Total'] = $semester7Total;
			$totalReportArray[$studentId]['semTotal']['semester8Total'] = $semester8Total;
			$totalReportArray[$studentId]['semTotal']['semester9Total'] = $semester9Total;
			$totalReportArray[$studentId]['semTotal']['semester10Total'] = $semester10Total;
			
			//echo "<td align='center'>".$total."</td>";
			$totalReportArray[$studentId]['total'] = $total;
			//echo "<td align='center'>".$totalCoursePassMark."</td>"; 
			$totalReportArray[$studentId]['totalCoursePassMark'] = $totalCoursePassMark;
			//echo "<td align='center'>".sprintf('%0.2f',round($totalCoursePassMark)/$total*100)."</td>";
			$totalReportArray[$studentId]['percentage'] = sprintf('%0.2f',round($totalCoursePassMark)/$total*100);
			//echo "<td align='center'>".$result['Program']['credits']."</td>";
			$totalReportArray[$studentId]['program_credit'] = $result['Program']['credits'];
			//echo "<td align='center'>".array_sum($TotCreditPointsArray)."</td>";
			$totalReportArray[$studentId]['credits_reg'] = array_sum($TotCreditPointsArray);
			//echo "<td align='center'>".$earnCreditPoints."</td>";
			$totalReportArray[$studentId]['credits_earned'] = $earnCreditPoints;
			//echo "<td align='center'>";
			$totalReportArray[$studentId]['grade_point_earned'] = $stuSemesterMark1+$stuSemesterMark2+$stuSemesterMark3+$stuSemesterMark4+$stuSemesterMark5+$stuSemesterMark6+$stuSemesterMark7+$stuSemesterMark8+$stuSemesterMark9+$stuSemesterMark10;
			//array_sum($totalReportArray[$studentId]['t']);
			$credit_earned = array_sum($totalReportArray[$studentId]['semTotal']);
			//echo "</td>";
			$arrears = count($csm_cm_id) - $semesterPassCnt;
			if ($arrears == 0) $arrears=""; 
			/* echo "<td align='center'>";
			if ($arrears) echo $arrears;
			echo "</td>"; */
			$totalReportArray[$studentId]['arrears'] = $arrears;
			//echo "<td align='center'>"; echo $totalGP;
			//echo "</td>";
			$totalReportArray[$studentId]['cgpa'] = $totalGP;
			//echo "<td align='center'>";
			if($earnCreditPoints >= $result['Program']['credits']){
				$resStatus = "Pass";
			}
			$totalReportArray[$studentId]['result'] = $resStatus;
			//echo "</td>";
			//echo "<td align='center'>";
			$degree_classification = $this->generateModeClass($totalGP, $abs, $withdrawal, $first_attempt);
			
			if ($earnCreditPoints >= $result['Program']['credits']) {
				$classSecured = strtoupper($degree_classification['E']);
			} 
			$totalReportArray[$studentId]['class'] = $classSecured;
			//echo "</td>";
			
		}
		return $totalReportArray;
	}
	
	public function totalReport($studentArray) {
		$row = 1; // 1-based index
		$col = 0;
		$phpExcel = new PHPExcel();
		$phpExcel->setActiveSheetIndex(0);
		$sheet = $phpExcel->getActiveSheet();
		$sheet->getRowDimension('1')->setRowHeight('18');
		$sheet->getColumnDimension('B')->setWidth(20);
		$sheet->getColumnDimension('C')->setWidth(6);
		$sheet->getColumnDimension('D')->setWidth(6);
		$sheet->getColumnDimension('E')->setWidth(6);
		$sheet->getColumnDimension('F')->setWidth(6);
		$sheet->getColumnDimension('G')->setWidth(6);
		$sheet->getColumnDimension('H')->setWidth(6);
		$sheet->getColumnDimension('I')->setWidth(6);
		$sheet->getColumnDimension('J')->setWidth(6);
		$sheet->getColumnDimension('K')->setWidth(6);
		$sheet->getColumnDimension('L')->setWidth(6);
		$sheet->getColumnDimension('M')->setWidth(6);
		$sheet->getColumnDimension('N')->setWidth(6);
		$sheet->getColumnDimension('O')->setWidth(6);
		$sheet->getColumnDimension('P')->setWidth(8);
		$sheet->getColumnDimension('R')->setWidth(6);
		$sheet->getColumnDimension('S')->setWidth(6);
		$sheet->getColumnDimension('T')->setWidth(6);
		$sheet->getColumnDimension('U')->setWidth(6);
		$sheet->getColumnDimension('V')->setWidth(6);
	
		$sheet->setTitle("Total");
	
		$sheet->setCellValueByColumnAndRow($col, $row, "REGISTER NUMBER");$col++;
		$sheet->setCellValueByColumnAndRow($col, $row, "STUDENT NAME");$col++;
		$sheet->setCellValueByColumnAndRow($col, $row, "SEM 1");$col++;
		$sheet->setCellValueByColumnAndRow($col, $row, "SEM 2");$col++;
		$sheet->setCellValueByColumnAndRow($col, $row, "SEM 3");$col++;
		$sheet->setCellValueByColumnAndRow($col, $row, "SEM 4");$col++;
		$sheet->setCellValueByColumnAndRow($col, $row, "SEM 5");$col++;
		$sheet->setCellValueByColumnAndRow($col, $row, "SEM 6");$col++;
		$sheet->setCellValueByColumnAndRow($col, $row, "SEM 7");$col++;
		$sheet->setCellValueByColumnAndRow($col, $row, "SEM 8");$col++;
		$sheet->setCellValueByColumnAndRow($col, $row, "SEM 9");$col++;
		$sheet->setCellValueByColumnAndRow($col, $row, "SEM 10");$col++;
	
		$sheet->setCellValueByColumnAndRow($col, $row, "GRAND TOTAL");$col++;
		$sheet->setCellValueByColumnAndRow($col, $row, "TOTAL SECURED");$col++;
		$sheet->setCellValueByColumnAndRow($col, $row, "TOTAL PERCENTAGE");$col++;
		$sheet->setCellValueByColumnAndRow($col, $row, "PROGRAM CR");$col++;
		$sheet->setCellValueByColumnAndRow($col, $row, "CR REGD");$col++;
		$sheet->setCellValueByColumnAndRow($col, $row, "CR EARNED");$col++;
		$sheet->setCellValueByColumnAndRow($col, $row, "GP EARNED");$col++;
		$sheet->setCellValueByColumnAndRow($col, $row, "ARREARS COUNT");$col++;
		$sheet->setCellValueByColumnAndRow($col, $row, "CGPA");$col++;
		$sheet->setCellValueByColumnAndRow($col, $row, "STATUS");$col++;
		$sheet->setCellValueByColumnAndRow($col, $row, "CLASS SECURED");$col++;
		
		$row++;
		$row = 2;
		//pr($studentArray);
		foreach ($studentArray as $student_id => $result) {
			$col = 0;
			$sheet->getRowDimension($row)->setRowHeight('18');
			$sheet->setCellValueByColumnAndRow($col, $row, $result['reg_num']);$col++;
			$sheet->setCellValueByColumnAndRow($col, $row, $result['name']);$col++;
			$sheet->setCellValueByColumnAndRow($col, $row, $result['semTotal']['semester1Total']);$col++;
			$sheet->setCellValueByColumnAndRow($col, $row, $result['semTotal']['semester2Total']);$col++;
			$sheet->setCellValueByColumnAndRow($col, $row, $result['semTotal']['semester3Total']);$col++;
			$sheet->setCellValueByColumnAndRow($col, $row, $result['semTotal']['semester4Total']);$col++;
			$sheet->setCellValueByColumnAndRow($col, $row, $result['semTotal']['semester5Total']);$col++;
			$sheet->setCellValueByColumnAndRow($col, $row, $result['semTotal']['semester6Total']);$col++;
			$sheet->setCellValueByColumnAndRow($col, $row, $result['semTotal']['semester7Total']);$col++;
			$sheet->setCellValueByColumnAndRow($col, $row, $result['semTotal']['semester8Total']);$col++;
			$sheet->setCellValueByColumnAndRow($col, $row, $result['semTotal']['semester9Total']);$col++;
			$sheet->setCellValueByColumnAndRow($col, $row, $result['semTotal']['semester10Total']);$col++;
			$sheet->setCellValueByColumnAndRow($col, $row, $result['total']);$col++;
			$sheet->setCellValueByColumnAndRow($col, $row, $result['totalCoursePassMark']);$col++;
			$sheet->setCellValueExplicitByColumnAndRow($col, $row, $result['percentage']);$col++;
			$sheet->setCellValueByColumnAndRow($col, $row, $result['program_credit']);$col++;
			$sheet->setCellValueByColumnAndRow($col, $row, $result['credits_reg']);$col++;
			$sheet->setCellValueByColumnAndRow($col, $row, $result['credits_earned']);$col++;
			$sheet->setCellValueByColumnAndRow($col, $row, $result['grade_point_earned']);$col++;
			$sheet->setCellValueByColumnAndRow($col, $row, $result['arrears']);$col++;
			$sheet->setCellValueExplicitByColumnAndRow($col, $row, $result['cgpa']);$col++;
			$sheet->setCellValueByColumnAndRow($col, $row, $result['result']);$col++;
			$sheet->setCellValueByColumnAndRow($col, $row, $result['class']);$col++;
			$row++;
		}
		$download_filename="Total-".date('d-M-Y h:i:s');
		header("Content-Type: application/vnd.ms-excel");
		header("Content-Disposition: attachment; filename=\"$download_filename.xls\"");
		header("Cache-Control: max-age=0");
		$objWriter = PHPExcel_IOFactory::createWriter($phpExcel, "Excel5");
		$objWriter->save("php://output");
		exit;
	}
	
	public function generateModeClass($CGPA, $abs, $withdrawal, $first_attempt){
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
	
	public function processStudentData($results, $csmArray, $examMonth) {
		foreach ( $results as $k => $result ) {
			$studentId = $result['Student']['id'];
			$program_credit = $result['Program']['credits'];
			$studentSignature = $result['Student']['signature'];
			
			if (isset($result['StudentAuthorizedBreak'][0]['student_id'])) $abs=1;
			else $abs=0;
		
			if (isset($result['StudentWithdrawal'][0]['student_id'])) $withdrawal=1;
			else $withdrawal=0;
			$batchId = $result['Batch']['id'];
			$programId = $result['Program']['id'];
			
			$TotCreditPoints ="";$earnCreditPoints = "";$semesterPassCnt = "0";$noOfCourses = 0;$noOfArrears = "";
			
			$finalArray = array();
			
			if (isset($result['ParentGroup']['StudentMark']) && count($result['ParentGroup']['StudentMark'])>0) {
				for($p = 0; $p < count($result['ParentGroup']['StudentMark']); $p++) {
					$course_mapping_id = $result['ParentGroup']['StudentMark'][$p]['course_mapping_id'];
					if (isset($finalArray[$course_mapping_id])) {
						$finalArray[$course_mapping_id] = $result['ParentGroup']['StudentMark'][$p];
					} else {
						$finalArray[$course_mapping_id] = $result['ParentGroup']['StudentMark'][$p];
					}
				}
			}
			
			for($p = 0; $p < count($result['StudentMark']); $p++) {
				$course_mapping_id = $result['StudentMark'][$p]['course_mapping_id'];
				//echo $course_mapping_id;
				if ($csmArray[$studentId][$course_mapping_id]==0) {
					if (isset($finalArray[$course_mapping_id])) {
						$finalArray[$course_mapping_id] = $result['StudentMark'][$p];
					} else {
						$finalArray[$course_mapping_id] = $result['StudentMark'][$p];
					}
				}
			}
			//pr($finalArray);
			//echo count($finalArray);
			
			$cgpa=0;
			$numerator=0;
			$denominator=0;
			
			$cmArray = array();
			$csm_array = $result['CourseStudentMapping'];
			//pr($csm_array); 
			//echo "**".count($csm_array);
			
			foreach ($csm_array as $key => $cmValue) {
				$cmArray[$cmValue['course_mapping_id']] = $cmValue['type'];
			}
			//pr($cmArray);
			
			$courseArray =array();
			$q = 0;
			foreach ($csm_array as $key => $cmValue) { //pr($cmValue); 
				if ($cmValue['indicator']==0 && isset($cmValue['CourseMapping'])){
					$courseArray[$q] = array(
							'cm_id'=>$cmValue['course_mapping_id'],
							'credit_point'=>$cmValue['CourseMapping']['Course']['credit_point'],
							'semester_id'=>$cmValue['CourseMapping']['semester_id'],
					);
					$q++;
				}
			}
			//pr($courseArray);
			//die;
			$creditPointArray = array();
			foreach ($courseArray as $key => $value) {
				$semester_id = $value['semester_id'];
				$creditPointArray[$semester_id][] = $value;
			}
			//pr($creditPointArray);
			
			$creditsRegistered = array();
			foreach ($creditPointArray as $semester_id => $array) {
				
			}
			
			if (!empty($result['StudentAuditCourse']) && count($result['StudentAuditCourse']) >0) {
				$audit_courses = $result['StudentAuditCourse'];
			}
			
			$markSecured = "";
			$examMonthYearText = array();
			$courseActualSemester = array();
			$courseActualMonthYear = array();
			$CourseCP = array();
			$courseTypeId = array();
			$CourseName = array();
			$CourseCode = array();
			$examMonthYearId = array();
			$stuFinalMark = array();
			$stuFinalStatus = array();
			$markSecuredArray = array();
			$CourseMaxMarksArray = array();
			$resultStatusArray = array();
			
			$p=0;
			$q = 0;
			
			$stuFinalArray = array();
			
			foreach ($finalArray as $course_mapping_id => $stuValue) { 
				$courseMId = $stuValue['course_mapping_id'];
				$examMonthYearText[$courseMId] = $stuValue['MonthYear']['Month']['month_name']."-".$stuValue['MonthYear']['year'];
				$courseActualSemester[$courseMId] = $stuValue['CourseMapping']['semester_id'];
				$courseActualMonthYear[$courseMId] = $stuValue['CourseMapping']['month_year_id'];
				$CourseCP[$courseMId] = $stuValue['CourseMapping']['Course']['credit_point'];
				$CourseName[$courseMId] = $stuValue['CourseMapping']['Course']['course_name'];
				$CourseCode[$courseMId] = $stuValue['CourseMapping']['Course']['course_code'];
				$CourseMaxMarksArray[$courseMId] = $stuValue['CourseMapping']['Course']['course_max_marks'];
				$courseTypeId[$courseMId] = $stuValue['CourseMapping']['Course']['course_type_id'];
				$examMonthYearId[$courseMId] = $stuValue['month_year_id'];
				
				////$coursePassSemester[$courseMId] = $this->Html->retrieveSemesterFromMonthYear($stuValue['month_year_id'], $batchId, $programId);
				
				$revaluationStatus = 0;
					
				if($stuValue['revaluation_status'] == 0){
					$stuFinalMark[$courseMId] = $stuValue['marks'];
					$stuFinalStatus[$courseMId] = $stuValue['status'];
				}else{
					$stuFinalMark[$courseMId] = $stuValue['final_marks'];
					$stuFinalStatus[$courseMId] = $stuValue['final_status'];
				}
				
				$resultStatusArray[$courseMId] = $stuFinalStatus[$courseMId];
					
				//if ($stuFinalStatus[$courseMId] == "Pass") {
					//$markSecured += $stuFinalMark[$courseMId];
				//}
				$cae="";
				$ese="";
				$total="";
				
				$course_type= "";
				
				$markArray = array();
				SWITCH ($courseTypeId[$courseMId]) {
					CASE 1:
						$markArray = $this->theoryCaeAndEse($courseTypeId, $courseMId, $studentId, $courseActualMonthYear[$courseMId], $stuValue['revaluation_status'], $examMonth, 1);
						$course_type = "Theory";
						break;
					CASE 2:
					CASE 6:
						if ($courseTypeId[$courseMId] == 2) {
							$course_type = "Practical";
						}else if ($courseTypeId[$courseMId] == 6) {
							$course_type = "Studio";
						}
						$markArray = $this->practicalCaeAndEse($courseTypeId, $courseMId, $studentId, $courseActualMonthYear[$courseMId], $stuValue['revaluation_status'], $examMonth);
						break;
					CASE 3:
						$course_type = "Theory and Practical";
						$markArray = $this->theoryPracticalCaeAndEse($courseTypeId, $courseMId, $studentId, $courseActualMonthYear[$courseMId], $stuValue['revaluation_status'], $examMonth);
						break;
					CASE 4:
						$course_type = "Project";
						$markArray = $this->projectCaeAndEse($courseTypeId, $courseMId, $studentId, $courseActualMonthYear[$courseMId], $stuValue['revaluation_status'], $examMonth);
						break;
					CASE 5:
						$course_type = "Professional Training";
						$markArray = $this->profTrainingCae($courseTypeId, $courseMId, $studentId, $courseActualMonthYear[$courseMId], $stuValue['revaluation_status'], $examMonth);
						break;
				}
		//pr($markArray);
				$cae=$markArray['cae'];
				$ese=$markArray['ese'];
				$total=$markArray['total'];
				
				if ($resultStatusArray[$courseMId]=='Fail' && ($markArray['ese']=='A' || $markArray['ese']=='AAA' || $markArray['ese']=='aaa' || $markArray['ese']=='a')) {
					$total='AAA';
				}
				
				$markSecured += $total;
			
					$markSecuredArray[$courseMId] = $result['StudentMark'][$p]['CourseMapping']['Course']['course_max_marks'];
					$academic = "";
					if (strtoupper($result['Batch']['academic']) == "JUN") $academic = "A";
					$stuFinalArray[$studentId]['batch'] = $result['Batch']['batch_from']."-".$result['Batch']['batch_to']." [".$academic."]";
					$stuFinalArray[$studentId]['program'] = $result['Program']['program_name'];
					$stuFinalArray[$studentId]['reg_number'] = $result['Student']['registration_number'];
					$stuFinalArray[$studentId]['name'] = $result['Student']['name'];
					
					$stuFinalArray[$studentId]['marks'][$courseMId] = array(
							'cm_id' => $courseMId,
							'semester_id' => $courseActualSemester[$courseMId],
							'month_year_id' => $examMonthYearId[$courseMId],
							'actual_month_year_id' => $courseActualMonthYear[$courseMId],
							'actual_month_year' => $this->MonthYear->getMonthYear($courseActualMonthYear[$courseMId]),
							'course_code' => $CourseCode[$courseMId],
							'course_name' => $CourseName[$courseMId],
							'course_type_id' => $courseTypeId[$courseMId],
							'course_type' => $course_type,
							'credit_point' => $CourseCP[$courseMId],
							'marks' => $stuFinalMark[$courseMId],
							'course_max_marks' => $CourseMaxMarksArray[$courseMId],
							'cae' => $markArray['cae'],
							'ese' => $markArray['ese'],
							'total'=> $total,
							'grade' => $stuValue['grade'],
							'month_year' => $this->MonthYear->getMonthYear($stuValue['month_year_id']),
							'grade_point' => $stuValue['grade_point'],
							'status' => $stuFinalStatus[$courseMId],
					);
					
					$stuFinalArray[$studentId]['result'] = $resultStatusArray;
					$q++;
			}
			//echo array_sum($CourseMaxMarksArray);
			$stuFinalArray[$studentId]['mark_secured'] = array_sum(array_column($stuFinalArray[$studentId]['marks'], 'total'));
			$stuFinalArray[$studentId]['total_marks'] = array_sum(array_column($stuFinalArray[$studentId]['marks'], 'course_max_marks'));
			$stuFinalArray[$studentId]['res_count'] = array_count_values($stuFinalArray[$studentId]['result']);
			$tmpFailedCourses = array();
			$tmpFailedCourses = array_keys($stuFinalArray[$studentId]['result'], "Fail");
			//pr($tmpFailedCourses);
			$failedCourses = array();
			foreach ($tmpFailedCourses as $key => $cm_id) {
				$failedCourses[$cm_id]=$cm_id;
			}
			//pr($failedCourses);
			//echo $markSecured;
			
			$courseDetails = $this->CourseMapping->getCourseMarks($failedCourses);
			//pr($courseDetails);
			//pr($stuFinalArray);
			return $stuFinalArray;
			/* $html = "";
			$head = "<table class='cmainhead2' border='0' align='center'  style='font-family:Arial !important;font-size:16px !important;'>
							 <tr>
							 <td rowspan='2'><img src='../webroot/img/user.jpg'></td>
							 <td align='center'>SATHYABAMA UNIVERSITY<br/>
							 <span class='slogan'>ARREAR CONSOLIDATED MARK</span></td>
							 </tr>
							 </table>";
			$head .= "<table class='attendanceHeadTblP' cellpadding='0' cellspacing='0' border='1' style='font-family:Arial !important;font-size:12px !important;text-indent:10px;width:100%;'>
							  <tr>
								<td style='height:30px;width:30%;' align='left'>&nbsp;Name</td>
								<td align='left' style='width:50%;'>&nbsp;".$stuFinalArray[$studentId]['name']."</td>
								<td style='width:20%;' align='left'>&nbsp;Register No.</td>
								<td align='left' style='height:30px;width:20%;'>&nbsp;".$stuFinalArray[$studentId]['reg_number']."</td>
							</tr>
							<tr>
								<td style='height:30px;' align='left'>&nbsp;Programme & Branch</td>
								<td align='left'>&nbsp;".$stuFinalArray[$studentId]['program']."</td>
								<td align='left'>&nbsp;Batch</td>
								<td align='left'>&nbsp;".$stuFinalArray[$studentId]['batch']."</td>
							</tr>
				            </table>";
			$html .= $head;
			$html .= "<br/><table class='attendanceHeadTblP' cellpadding='0' cellspacing='0' border='1' style='font-family:Arial !important;font-size:12px !important;text-indent:3px;'>";
			if (isset($stuFinalArray[$studentId]['no_of_arrears']) && count($stuFinalArray[$studentId]['no_of_arrears'] > 0)) {
			$html .= "<tr>
					 <th style='width:80px;height:26px;' align='center'>Semester</th>
					 <th style='width:110px;' align='center'>Course Code</th>
					 <th style='width:250px;' align='center'>Course Name</th>
					 <th style='width:250px;' align='center'>Course Type</th>
					 <th style='width:250px;' align='center'>Month Year of Exam</th>
					<th style='width:80px;' align='center'>CAE</th>
					<th style='width:80px;' align='center'>ESE</th>
					 <th style='width:80px;' align='center'>TOTAL</th>
					 <th style='width:80px;' align='center'>STATUS</th>
				 	 </tr>";
			//$html .= $courseMark;$stuFinalArray[$studentId]['marks']
				foreach ($failedCourses as $key => $cm_id) {
					$course_type_id = $stuFinalArray[$studentId]['marks'][$cm_id]['course_type_id']; 
					$results = $this->CourseMapping->Course->CourseType->findCourseTypeById($stuFinalArray[$studentId]['marks'][$cm_id]['course_type_id']);
					if (isset($results) && count($results)>0) $course_type = $results['CourseType']['course_type'];
					else $course_type = "";
					$html .= "<tr>
					<td style='width:80px;height:26px;' align='center'>".$stuFinalArray[$studentId]['marks'][$cm_id]['semester_id']."</td>
					<td style='width:110px;' align='center'>".$stuFinalArray[$studentId]['marks'][$cm_id]['course_code']."</td>
					<td style='width:250px;' align='center'>".$stuFinalArray[$studentId]['marks'][$cm_id]['course_name']."</td>
					<td style='width:250px;' align='center'>".$course_type."</td>
					<td style='width:250px;' align='center'>".$stuFinalArray[$studentId]['marks'][$cm_id]['month_year']."</td>
					<td style='width:50px;' align='center'>".$stuFinalArray[$studentId]['marks'][$cm_id]['cae']."</td>
					<td style='width:50px;' align='center'>".$stuFinalArray[$studentId]['marks'][$cm_id]['ese']."</td>
					<td style='width:50px;' align='center'>".$stuFinalArray[$studentId]['marks'][$cm_id]['total']."</td>
					<td style='width:50px;' align='center'>".$stuFinalArray[$studentId]['marks'][$cm_id]['status']."</td>
					</tr>";
				}
			}
			else {
				$html.="<tr><th colspan='8'></th></tr>";
			}
			//echo $html;
			$html .= "</table><div style='page-break-after:always'></div>";
			if ($html) {
				$html = substr($html,0,-43);
				$this->mPDF->init();
				// setting filename of output pdf file
				$this->mPDF->setFilename('ARREAR_CONSOLIDATED_MARK_VIEW_'.date('d_M_Y').'.pdf');
				// setting output to I, D, F, S
				$this->mPDF->setOutput('D');
				//$this->mPDF->AddPage('L','', '', '', '',30,30,30,30,18,12);
				$this->mPDF->WriteHTML($html);
				// you can call any mPDF method via component, for example:
				$this->mPDF->SetWatermarkText("Draft");
			} */
		}
		/* $this->autoLayout = false;
		$this->autoRender = false; */
	}
	
	public function theoryCaeAndEse($courseTypeId, $cm_id, $student_id, $actualMYId, $revaluation_status, $examMonth, $compare) {
		$eseCond = array();
		if($examMonth > 0) $eseCond['EndSemesterExam.month_year_id'] = $examMonth;
		if($student_id > 0) $eseCond['EndSemesterExam.student_id'] = $student_id;
		if($cm_id > 0) $eseCond['EndSemesterExam.course_mapping_id'] = $cm_id;
		
		$res = $this->Student->find('all', array(
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
								//'order'=>array('EndSemesterExam.month_year_id DESC'),
								'limit'=>1
								
						),
						'RevaluationExam' => array(
								'fields' => array('RevaluationExam.id','RevaluationExam.course_mapping_id',
										'RevaluationExam.revaluation_marks'),
								'conditions' => array('RevaluationExam.course_mapping_id'=>$cm_id,
										'RevaluationExam.student_id' => $student_id,
										'RevaluationExam.month_year_id' => $examMonth,
								),
								'limit'=>1
						),
				),
		));
		/* $dbo = $this->Student->getDatasource();
		$logs = $dbo->getLog();
		$lastLog = end($logs['log']);
		echo $lastLog['query']; */
	//	pr($res);
		$results['cae'] = $res[0]['InternalExam'][0]['marks'];
		
		if ($revaluation_status) { 
			$ese_marks = $res[0]['EndSemesterExam'][0]['marks'];
			$reval_marks = $res[0]['RevaluationExam'][0]['revaluation_marks'];
			if ($compare) {
				if ($reval_marks > $ese_marks) $results['ese'] = $reval_marks;
				else $results['ese'] = $ese_marks;
			}
			else {
				if ($revaluation_status) {
					$results['ese'] = $reval_marks;
				}
				else {
					$results['ese'] = $ese_marks;
				}
			}
		}
		else {
			if(isset($res[0]['EndSemesterExam'][0]['marks'])) {
				$results['ese'] = $res[0]['EndSemesterExam'][0]['marks'];
			}
			else {
				$results['ese'] = "A";
			}
		}
		
		$results['total'] = $results['cae']+$results['ese'];
		return $results;
	}
	
	public function practicalCaeAndEse($courseTypeId, $cm_id, $student_id, $actualMYId, $revaluation_status, $examMonth) {
		$markArray = array();
	
		$eseCond = array();
		if($examMonth > 0) $eseCond['Practical.month_year_id'] = $examMonth;
		if($student_id > 0) $eseCond['Practical.student_id'] = $student_id;
	
		$results = $this->CourseMapping->find('all', array(
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
				),
		));
	
		$markArray['cae']=$results[0]['CaePractical'][0]['InternalPractical'][0]['marks'];
		if (isset($results[0]['EsePractical'][0]['Practical']) && count($results[0]['EsePractical'][0]['Practical'])>0) {
			$markArray['ese']=$results[0]['EsePractical'][0]['Practical'][0]['marks'];
		} else {
			$markArray['ese']='A';
		}
		$markArray['total'] = $markArray['cae']+$markArray['ese'];
		//pr($results);
		//pr($markArray);
		return $markArray;
	}
	
	public function theoryPracticalCaeAndEse($courseTypeId, $cm_id, $student_id, $actualMYId, $revaluation_status, $examMonth) {
		$markArray = array();
	
		$eseCond = array();
		if($examMonth > 0) $eseCond['Practical.month_year_id'] = $examMonth;
		if($student_id > 0) $eseCond['Practical.student_id'] = $student_id;
	
		$results = $this->CourseMapping->find('all', array(
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
				),
		));
	
		$markArray['cae'] = $results[0]['InternalExam'][0]['marks'];
	
		if (isset($results[0]['EsePractical'][0]['Practical']) && count($results[0]['EsePractical'][0]['Practical'])>0) {
			$markArray['ese']=$results[0]['EsePractical'][0]['Practical'][0]['marks'];
		} else {
			$markArray['ese']='A';
		}
		$markArray['total'] = $markArray['cae']+$markArray['ese'];
		//pr($results);
		//pr($markArray);
		return $markArray;
	}
	
	public function projectCaeAndEse($courseTypeId, $cm_id, $student_id, $actualMYId, $revaluation_status, $examMonth) {
		$markArray = array();
	
		$eseCond = array();
		if($examMonth > 0) $eseCond['ProjectViva.month_year_id'] = $examMonth;
		if($student_id > 0) $eseCond['ProjectViva.student_id'] = $student_id;
	
		$results = $this->CourseMapping->find('all', array(
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
				),
		));
		//pr($results);
		$markArray['cae']=$results[0]['InternalProject'][0]['marks'];
		if (isset($results[0]['EseProject'][0]['ProjectViva']) && count($results[0]['EseProject'][0]['ProjectViva'])>0) {
			$markArray['ese']=$results[0]['EseProject'][0]['ProjectViva'][0]['marks'];
		} else {
			$markArray['ese']='A';
		}
		$markArray['total'] = $markArray['cae']+$markArray['ese'];
		return $markArray;
	}
	
	public function profTrainingCae($courseTypeId, $cm_id, $student_id, $actualMYId, $revaluation_status) {
		$this->loadModel('CaePt');
		$caeIdArray = $this->CaePt->find('all', array(
				'conditions' => array('CaePt.course_mapping_id'=>$cm_id,),
				'fields'=>array('CaePt.id'),
				'contain'=>false
		));
		//pr($caeIdArray);
		$caeId = $caeIdArray[0]['CaePt']['id'];
	
		$ese_results = $this->ProfessionalTraining->find('all', array(
				'conditions' => array('ProfessionalTraining.cae_pt_id'=>$caeId,
						'ProfessionalTraining.student_id'=>$student_id,
						'ProfessionalTraining.month_year_id'=>$actualMYId,
				),
				'fields'=>array('ProfessionalTraining.id', 'ProfessionalTraining.marks'),
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
	
	public function excelDegreeCertificateReport($results) {
		$phpExcel = new PHPExcel();
		$phpExcel->setActiveSheetIndex(0);
		$sheet = $phpExcel->getActiveSheet();
		$sheet->getRowDimension('1')->setRowHeight('18');
		$sheet->setTitle("Degree_Certificate_Report");
			
		$sheet->setCellValue("A1", "REGISTER NUMBER");
		$sheet->setCellValue("B1", "STUDENT NAME");
		$sheet->setCellValue("C1", "PROGRAM CR");
		$sheet->setCellValue("D1", "DEGREE STATUS");
		$sheet->setCellValue("E1", "CLASS CLASSIFICATION");
		$sheet->setCellValue("F1", "CGPA");
		$sheet->setCellValue("G1", "CREDITS REGISTERED");
		$sheet->setCellValue("H1", "CREDITS EARNED");
		$sheet->setCellValue("I1", "GRADE POINTS EARNED");
		$sheet->setCellValue("J1", "NO. OF ARREARS");
		$sheet->setCellValue("K1", "AUTHORIZED BREAK OF STUDY");
		$sheet->setCellValue("L1", "WITHDRAWAL");
		$sheet->setCellValue("M1", "WITHHELD");
		$sheet->setCellValue("N1", "AUDIT COURSE");
		
		$i=2;
		foreach ($results as $student_id => $student) {
			$sheet->getRowDimension($i)->setRowHeight('18');
			$sheet->setCellValue('A'.$i, $student['reg_num']);
			$sheet->setCellValue('B'.$i, $student['name']);
			$sheet->setCellValue('C'.$i, $student['program_credit']);
			$sheet->setCellValue('D'.$i, $student['status']);
			if ($student['no_of_arrears'] == 0 && $student['current_credit_regd']>=$student['program_credit']) $sheet->setCellValue('E'.$i, $student['class_classification']);
			else $sheet->setCellValue('E'.$i, "");
			
			$sheet->setCellValue('F'.$i, $student['cgpa']);
			$sheet->setCellValue('G'.$i, $student['program_credit']);
			$sheet->setCellValue('H'.$i, $student['credits_earned']);
			$sheet->setCellValue('I'.$i, $student['grades_earned']);
			
			$arrear_status = "";
			if ($student['no_of_arrears'] != 0) $sheet->setCellValue('J'.$i, $student['no_of_arrears']);
			else $sheet->setCellValue('J'.$i, '');
			
			$abs = "";
			$withdrawal = "";
			$withheld = "";
			$audit_course = "";
			if ($student['abs'] == 1) $abs = "Y";
			if ($student['withdrawal'] == 1) $withdrawal = "Y";
			if ($student['withheld'] == 1) $withheld = "Y";
			if ($student['audit_course'] == 1) $audit_course = "Y";
			$sheet->setCellValue('K'.$i, $abs);
			$sheet->setCellValue('L'.$i, $withdrawal);
			$sheet->setCellValue('M'.$i, $withheld);
			$sheet->setCellValue('N'.$i, $audit_course);
			$i++;
		}
		
		$download_filename="Degree_Certificate_Report_".date('d-M-Y h:i:s');
		header("Content-Type: application/vnd.ms-excel");
		header("Content-Disposition: attachment; filename=\"$download_filename.xls\"");
		header("Cache-Control: max-age=0");
		$objWriter = PHPExcel_IOFactory::createWriter($phpExcel, "Excel5");
		$objWriter->save("php://output");
		exit;
	}
	
	public function getGP($courseMappingId = null, $mark = null, $mode = null){
		$this->loadModel('CourseMapping');
	
		$conditions= array();
		$conditions['CourseMapping.id']=$courseMappingId;
		$CMCrPoints = $this->CourseMapping->find("all", array(
				'conditions'=>$conditions,
				'fields' => array('CourseMapping.id'),
				'contain'=>array(
					'Course' => array(
						'fields'=>array('Course.credit_point','Course.course_max_marks')
					)
				)
		));
		//pr($CMCrPoints);
		$credit_point = $CMCrPoints[0]['Course']['credit_point'];
		if($mode == 1){
			return $credit_point;
		}
		else if($mode == "GRADE") {
			$grade = array();

			$computed_mark = round(($mark/$CMCrPoints[0]['Course']['course_max_marks'])*100);
			//echo $courseMappingId." ".$computed_mark;
			
			$studentMarkGP = $this->grade_point($computed_mark);
			$studentGrade = $this->grade($computed_mark);
			
			if(($studentMarkGP) && ($studentMarkGP != 'A')) {
				$totalGP = $credit_point * $studentMarkGP;
			}
			else {
				$totalGP =0;
			}
			$grade['grade_point'] = $studentMarkGP;
			$grade['grade'] = $studentGrade;
			$grade['totalGP'] = $totalGP;
			
			return $grade;
		}
		
	}
	
	public function grade_point($computed_mark) {
		$studentMarkGP = 0;
		if($computed_mark >= 90 && $computed_mark <= 100 ) {
			$studentMarkGP = "10";
		}
		else if($computed_mark >= 80 && $computed_mark <= 89 ) {
			$studentMarkGP = "9";
		}
		else if($computed_mark >= 70 && $computed_mark <= 79 ) {
			$studentMarkGP = "8";
		}
		else if($computed_mark >= 60 && $computed_mark <= 69 ) {
			$studentMarkGP = "7";
		}
		else if($computed_mark >= 50 && $computed_mark <= 59 ) {
			$studentMarkGP = "6";
		}
		else if($computed_mark >= 0 && $computed_mark <= 49 ) {
			$studentMarkGP = "0";
		}
		else {
			$studentMarkGP = "0";
		}
		return $studentMarkGP;
	}
	
	public function grade($computed_mark) {
		$studentMarkGrad = "";
		if($computed_mark >= 90 && $computed_mark <= 100 ) {
			$studentMarkGrad = "A++";
		}
		else if($computed_mark >= 80 && $computed_mark <= 89 ) {
			$studentMarkGrad = "A+";
		}
		else if($computed_mark >= 70 && $computed_mark <= 79 ) {
			$studentMarkGrad = "B++";
		}
		else if($computed_mark >= 60 && $computed_mark <= 69 ) {
			$studentMarkGrad = "B+";
		}
		else if($computed_mark >= 50 && $computed_mark <= 59 ) {
			$studentMarkGrad = "C";
		}
		else if($computed_mark >= 0 && $computed_mark <= 49 ) {
			$studentMarkGrad = "RA";
		}
		return $studentMarkGrad;
	} 
	
	public function excelConsolidatedMarkView($studentArray) {
		$row = 1; // 1-based index
		$col = 0;
		$phpExcel = new PHPExcel();
		$phpExcel->setActiveSheetIndex(0);
		$sheet = $phpExcel->getActiveSheet();
		$sheet->getRowDimension('1')->setRowHeight('18');
		$sheet->setTitle("Consolidated_Mark_View");			
		
		$sheet->setCellValueByColumnAndRow($col, $row, "REGISTER NUMBER");$col++;
		$sheet->setCellValueByColumnAndRow($col, $row, "STUDENT NAME");$col++;		
		$sheet->setCellValueByColumnAndRow($col, $row, "BATCH");$col++;
		$sheet->setCellValueByColumnAndRow($col, $row, "PROGRAM");$col++;
		$sheet->setCellValueByColumnAndRow($col, $row, "SPECIALISATION");$col++;		
		$sheet->setCellValueByColumnAndRow($col, $row, "COURSE CODE");$col++;
		$sheet->setCellValueByColumnAndRow($col, $row, "COURSE NAME");$col++;
		$sheet->setCellValueByColumnAndRow($col, $row, "SEMESTER");$col++;
		$sheet->setCellValueByColumnAndRow($col, $row, "MONTH YEAR OF EXAM");$col++;
		$sheet->setCellValueByColumnAndRow($col, $row, "CAE");$col++;
		$sheet->setCellValueByColumnAndRow($col, $row, "ESE");$col++;
		$sheet->setCellValueByColumnAndRow($col, $row, "TOTAL");$col++;
		$sheet->setCellValueByColumnAndRow($col, $row, "STATUS");$col++;		
		$row++;
	
		foreach ($studentArray as $studentId => $results) {
			$col = 0;
			$sheet->getRowDimension($row)->setRowHeight('18');
	
			foreach($results as $key => $result) { 
				$col = 0;
				
				$sheet->getRowDimension($row)->setRowHeight('18');
				$sheet->setCellValueByColumnAndRow($col, $row, $result['reg_num']);$col++;
				$sheet->setCellValueByColumnAndRow($col, $row, $result['name']);$col++;
				$sheet->setCellValueByColumnAndRow($col, $row, $result['batch']);$col++;
				$sheet->setCellValueByColumnAndRow($col, $row, $result['academic']);$col++;
				$sheet->setCellValueByColumnAndRow($col, $row, $result['program']);$col++;
				$sheet->setCellValueByColumnAndRow($col, $row, $result['course_code']);$col++;
				$sheet->setCellValueByColumnAndRow($col, $row, $result['course_name']);$col++;
				$sheet->setCellValueByColumnAndRow($col, $row, $result['semester_id']);$col++;
				$sheet->setCellValueByColumnAndRow($col, $row, $result['month_year']);$col++;
				$sheet->setCellValueByColumnAndRow($col, $row, $result['cae']);$col++;
				$sheet->setCellValueByColumnAndRow($col, $row, $result['ese']);$col++;
				$sheet->setCellValueByColumnAndRow($col, $row, $result['final_mark']);$col++;
				$sheet->setCellValueByColumnAndRow($col, $row, $result['final_status']);$col++;
				$row++;
			}		
			
		}
	
		$download_filename="Consolidated_Mark_View".date('d-M-Y h:i:s');
		header("Content-Type: application/vnd.ms-excel");
		header("Content-Disposition: attachment; filename=\"$download_filename.xls\"");
		header("Cache-Control: max-age=0");
		$objWriter = PHPExcel_IOFactory::createWriter($phpExcel, "Excel5");
		$objWriter->save("php://output");
		exit;
	}
	
	public function pdfConsolidatedMarkView($processedResult, $printMode) {
		$html = "";
		if ($processedResult) {
			//pr($processedResult); 
			foreach($processedResult as $studentId => $res) {
				$tmpResult = array();
				if ($printMode == 'PDF2') {
					$tmpResult = $res;
					//pr($tmpResult);
					unset($res);
					//pr($res);
					
					//$res=[];
					//echo sizeof($tmpResult);
					for($i = 0; $i < sizeof($tmpResult); $i++) {
						$cm_id = $tmpResult[$i]['cm_id'];
						echo $cm_id."***";
						/* if (!array_key_exists($tmpResult[$i]['cm_id'], $tmpResult)) {
							$res[$tmpResult[$i]['cm_id']] = $tmpResult[$i];
						} */
						$res[$tmpResult[$i]['cm_id']]=$tmpResult[$i];
					}
					//echo count($res);
					//pr($res); 
				}
					
				$courseMark = "";
				foreach	($res as $key => $result) {
					$courseMark .= "<tr class='gradeX'>";
					$courseMark .= "<td align='center'>".$result['semester_id']."</td>";
					$courseMark .= "<td align='center'>".$result['course_code']."</td>";
					$courseMark .= "<td align='left'>".$result['course_name']."</td>";
					$courseMark .= "<td align='center'>".$result['month_year']."</td>";
					$courseMark .= "<td align='center'>".$result['cae']."</td>";
					$courseMark .= "<td align='center'>".$result['ese']."</td>";
					$courseMark .= "<td align='center'>".$result['final_mark']."</td>";
					$courseMark .= "<td align='center'>".$result['final_status']."</td>";
					$courseMark .= "</tr>";
				}
				$head = "<table class='cmainhead2' border='0' align='center'  style='font-family:Arial !important;font-size:16px !important;'>
							 <tr>
							 <td rowspan='2'><img src='../webroot/img/user.jpg'></td>
							 <td align='center'>SATHYABAMA UNIVERSITY<br/>
							 <span class='slogan'>CONSOLIDATED MARK</span></td>
							 </tr>
							 </table>";
				$head .= "<table class='attendanceHeadTblP' cellpadding='0' cellspacing='0' border='1' style='font-family:Arial !important;font-size:12px !important;text-indent:10px;width:100%;'>
							  <tr>
								<td style='height:30px;width:30%;' align='left'>&nbsp;Name</td>
								<td align='left' style='width:50%;'>&nbsp;".$result['name']."</td>
								<td style='width:20%;' align='left'>&nbsp;Register No.</td>
								<td align='left' style='height:30px;width:20%;'>&nbsp;".$result['reg_num']."</td>
							</tr>
							<tr>
								<td style='height:30px;' align='left'>&nbsp;Programme & Branch</td>
								<td align='left'>&nbsp;".$result['academic']." - ".$result['program']."</td>
								<td align='left'>&nbsp;Batch</td>
								<td align='left'>&nbsp;".$result['batch']."</td>
							</tr>
				            </table>";
				$html .= $head;
				$html .= "<br/><table class='attendanceHeadTblP' cellpadding='0' cellspacing='0' border='1' style='font-family:Arial !important;font-size:12px !important;text-indent:3px;'>
							 <tr>
							 <th style='width:80px;height:26px;' align='center'>Semester</th>
							 <th style='width:110px;' align='center'>Course Code</th>
							 <th style='width:250px;' align='center'>Course Name</th>
							 <th style='width:250px;' align='center'>Month Year of Exam</th>
							 <th style='width:50px;' align='center'>CAE</th>
							 <th style='width:50px;' align='center'>ESE</th>
							 <th style='width:80px;' align='center'>TOTAL</th>
							 <th style='width:80px;' align='center'>STATUS</th>
							 </tr>";
				$html .= $courseMark;
				$html .= "</table><div style='page-break-after:always'></div>";
			}
		}
		if ($html) {
			$html = substr($html,0,-43);
			$this->mPDF->init();
			// setting filename of output pdf file
			$this->mPDF->setFilename('CONSOLIDATE_MARK_VIEW_'.date('d_M_Y').'.pdf');
			// setting output to I, D, F, S
			$this->mPDF->setOutput('D');
			//$this->mPDF->AddPage('L','', '', '', '',30,30,30,30,18,12);
			$this->mPDF->WriteHTML($html);
			// you can call any mPDF method via component, for example:
			$this->mPDF->SetWatermarkText("Draft");
		}	
	}
	
	public function downloadCumulativeArrearReport($studentArray) {
		$phpExcel = new PHPExcel();
		$phpExcel->setActiveSheetIndex(0);
		$sheet = $phpExcel->getActiveSheet();
		$sheet->getRowDimension('1')->setRowHeight('18');
		$sheet->setTitle("Cumulative_Arrear_Report");
			
		$sheet->setCellValue("A1", "STUDENT ID");
		$sheet->setCellValue("B1", "REGISTER NUMBER");
		$sheet->setCellValue("C1", "STUDENT NAME");
		$sheet->setCellValue("D1", "");
		$i=2;
		foreach ($studentArray as $key => $student) {
			$sheet->getRowDimension($i)->setRowHeight('18');
			$sheet->setCellValue('A'.$i, $student['Student']['id']);
			$sheet->setCellValue('B'.$i, $student['Student']['registration_number']);
			$sheet->setCellValue('C'.$i, $student['Student']['name']);
			$sheet->setCellValue('D'.$i, '');
			$i++;
		}
	
		$download_filename="Cumulative_Arrear_Report_data-".date('d-M-Y h:i:s');
		header("Content-Type: application/vnd.ms-excel");
		header("Content-Disposition: attachment; filename=\"$download_filename.xls\"");
		header("Cache-Control: max-age=0");
		$objWriter = PHPExcel_IOFactory::createWriter($phpExcel, "Excel5");
		$objWriter->save("php://output");
		exit;
	}
	
	public function finalCAEExcel($studentArray) {
		$phpExcel = new PHPExcel();
		$phpExcel->setActiveSheetIndex(0);
		$sheet = $phpExcel->getActiveSheet();
		$sheet->getRowDimension('1')->setRowHeight('18');
		$sheet->setTitle("FINAL_CAE");
			
		$sheet->setCellValue("A1", "STUDENT ID");
		$sheet->setCellValue("B1", "REGISTER NUMBER");
		$sheet->setCellValue("C1", "STUDENT NAME");
		$sheet->setCellValue("D1", "COURSE CODE");
		$sheet->setCellValue("E1", "COURSE NAME");
		$sheet->setCellValue("F1", "CAE MARK - OUT OF 50");
		$i=2;
		foreach ($studentArray as $key => $student) {
			if($student['Student']['registration_number']){
				$sheet->getRowDimension($i)->setRowHeight('18');
				$sheet->setCellValue('A'.$i, $student['Student']['id']);
				$sheet->setCellValue('B'.$i, $student['Student']['registration_number']);
				$sheet->setCellValue('C'.$i, $student['Student']['name']);
				$sheet->setCellValue('D'.$i, $student['CourseMapping']['Course']['course_code']);
				$sheet->setCellValue('E'.$i, $student['CourseMapping']['Course']['course_name']);
				$sheet->setCellValue('F'.$i, $student['InternalExam']['marks']);
				$i++;
			}
		}
	
		$download_filename="FINAL_CAE-".date('d-M-Y h:i:s');
		header("Content-Type: application/vnd.ms-excel");
		header("Content-Disposition: attachment; filename=\"$download_filename.xls\"");
		header("Cache-Control: max-age=0");
		$objWriter = PHPExcel_IOFactory::createWriter($phpExcel, "Excel5");
		$objWriter->save("php://output");
		exit;
	}
	
	public function getCourseCode($id){
		$this->loadModel('CourseMapping');
		$conditions= array();
		$conditions['CourseMapping.id']=$id;
		
		$courseMapping = $this->CourseMapping->find("all", array(
				'conditions'=>$conditions,
				'recursive' => 0
		));
		//pr($courseMapping);
		foreach ($courseMapping as $course) {
			return $course['Course']['course_code'];
		}
	}
	public function getExamMonthYearName($EMId = null){
		$this->loadModel('MonthYear');
		$conditions['MonthYear.id']=$EMId;
		$month_year = $this->MonthYear->find("all", array(
				'conditions'=>$conditions,
				'fields' => array('MonthYear.month_id','MonthYear.year'),
				'recursive' => 2
		));
		$monthYear = $month_year[0]['Month']['month_name']." - ".$month_year[0]['MonthYear']['year'];
		//pr($month_year);
		return $monthYear;
	}

	public function getMonthYearName($EMId = null){
		$this->loadModel('MonthYear');
		$conditions['MonthYear.id']=$EMId;
		$month_year = $this->MonthYear->find("all", array(
				'conditions'=>$conditions,
				'fields' => array('MonthYear.month_id','MonthYear.year'),
				'contain'=>array(
						'Month'=>array('fields' =>array('Month.month_name'))
				)
		));
		//pr($month_year);
		$monthYear = $month_year[0]['Month']['month_name']." - ".$month_year[0]['MonthYear']['year'];
		//pr($month_year);
		return $monthYear;
	}
	public function array_MultiOrderBy(){
		$args = func_get_args();
		$data = array_shift($args);
		foreach ($args as $n => $field) {
			if (is_string($field)) {
				$tmp = array();
				foreach ($data as $key => $row)
					$tmp[$key] = $row[$field];
					$args[$n] = $tmp;
			}
		}
		$args[] = &$data;
		call_user_func_array('array_multisort', $args);
		return array_pop($args);
	}
	
	public function array_group_by(array $arr, callable $key_selector) {
		$result = array();
		foreach ($arr as $i) {
			$key = call_user_func($key_selector, $i);
			$result[$key][] = $i;
		}
		return $result;
	}
	
	public function calculateAttendance($attendancePercent) {
		if ($attendancePercent >0 and $attendancePercent<=50) {
			$attendanceMark = 1;
		}
		else if ($attendancePercent >50 and $attendancePercent<=59) {
			$attendanceMark = 2;
		}
		else if ($attendancePercent >60 and $attendancePercent<=69) {
			$attendanceMark = 3;
		}
		else if ($attendancePercent >70 and $attendancePercent<=79) {
			$attendanceMark = 4;
		}
		else if ($attendancePercent >80) {
			$attendanceMark = 5;
		}
		else {
			$attendanceMark = 0;
		}
		return $attendanceMark;
	}
	
	public function theoryCourseMappingArray($batch_id, $courseTypeIdArray, $program_id, $month_year_id, $course_mapping_array) {
		
		$conditions = array();
		$conditions['CourseMapping.batch_id'] = $batch_id;
		$conditions['CourseMapping.indicator'] = 0;
		$conditions['Course.course_type_id'] = $courseTypeIdArray;
		$conditions['CourseMapping.id'] = array_keys($course_mapping_array);
		
		if ($program_id > 0) {
			$conditions['CourseMapping.program_id'] = $program_id;
		}
		if ($month_year_id > 0) {
			$conditions['CourseMapping.month_year_id <='] = $month_year_id;
		}
		
		$cm_results = $this->CourseMapping->find('list', array(
				'conditions' => $conditions,
				'fields'=>array('CourseMapping.id'),
				'contain' => array(
						'Course' => array(
								'fields' => array('Course.id',),
						),
				),
				'recursive'=>2
		));
		return $cm_results;
	}
	
	public function endSemesterExam($month_year_id, $cm_results) {
		//pr($cm_results);
		$ese_results = $this->EndSemesterExam->find('all', array(
				'conditions' => array('EndSemesterExam.month_year_id'=>$month_year_id, 'EndSemesterExam.course_mapping_id'=>$cm_results,
				),
				'fields'=>array('EndSemesterExam.id', 'EndSemesterExam.course_mapping_id', 'EndSemesterExam.student_id',
						'EndSemesterExam.marks', 'EndSemesterExam.month_year_id'
				),
				'contain'=>false,
				'recursive'=>0
		));
		return $ese_results;
	}
	
	public function processESEresults($ese_results, $course_details, $month_year_id) {
		//pr($ese_results);
		//echo "hema";
		//pr($course_details);
		//echo $month_year_id; 
		$finalArray = array();
		
		foreach ($course_details as $course_mapping_id => $cmArray) {
			$resultType="";
			
			if ($month_year_id == $cmArray['month_year_id']) $resultType = "R";
			else $resultType = "A";
		//	echo $resultType;
			
			if ($resultType == "R") {
				$tmpArray = $this->InternalExam->find('list', array(
						'conditions'=>array('InternalExam.course_mapping_id'=>$course_mapping_id),
						'fields'=>array('InternalExam.student_id'),
				));
			} else {
				$tmpArray = $this->EndSemesterExam->find('list', array(
						'conditions'=>array('EndSemesterExam.course_mapping_id'=>$course_mapping_id,
								'EndSemesterExam.month_year_id'=>$month_year_id,
						),
						'fields'=>array('EndSemesterExam.student_id'),
						//'order'=>array('InternalExam.id DESC'),
				));
			}
			//pr($tmpArray);
			foreach ($tmpArray as $intOrEseId => $student_id) {
				$ese_results = $this->EndSemesterExam->find('first', array(
						'conditions' => array('EndSemesterExam.month_year_id'=>$month_year_id,
								'EndSemesterExam.course_mapping_id'=>$course_mapping_id,
								'EndSemesterExam.student_id'=>$student_id
						),
						'fields'=>array('EndSemesterExam.marks'),
						'contain'=>false
				));
			
				$caeArray = $this->InternalExam->find('all', array(
						'conditions'=>array('InternalExam.course_mapping_id'=>$course_mapping_id,
								'InternalExam.student_id'=>$student_id
						),
						'fields'=>array('InternalExam.id', 'InternalExam.course_mapping_id', 'InternalExam.marks',
								'InternalExam.student_id', 'InternalExam.month_year_id'),
						'order'=>array('InternalExam.id DESC'),
						'limit'=>1
				));
				//pr($caeArray);
				$cae_marks = $caeArray[0]['InternalExam']['marks'];
				if(isset($ese_results['EndSemesterExam'])) {
					$ese_marks = $ese_results['EndSemesterExam']['marks'];
				}
				else  $ese_marks = 'A';
				$total = $cae_marks+$ese_marks;
				//echo "</br>".$cae_marks." ".$ese_marks." ".$total;
			
				$finalArray[$course_mapping_id]['cae'][$student_id]=$cae_marks;
				$finalArray[$course_mapping_id]['ese'][$student_id]=$ese_marks;
				$finalArray[$course_mapping_id]['total'][$student_id]=$total;
				if ($cae_marks >= $course_details[$course_mapping_id]['min_cae_mark']) {
					if ($ese_marks >= $course_details[$course_mapping_id]['min_ese_mark']) {
						if ($total >= $course_details[$course_mapping_id]['min_pass_mark']) {
							$status = "Pass";
							$computed_mark = round(($total/$course_details[$course_mapping_id]['course_max_marks'])*100);
							$grade_point = $this->grade_point($computed_mark);
							$grade = $this->grade($computed_mark);
						}
						else {
							$status = "Fail";
							$grade_point = 0;
							$grade = "RA";
						}
					}
					else {
						$status = "Fail";
						$grade_point = 0;
						$grade = "RA";
					}
				}
				else {
					$status = "Fail";
					$grade_point = 0;
					$grade = "RA";
				}
				$finalArray[$course_mapping_id]['status'][$student_id]=$status;
				$finalArray[$course_mapping_id]['grade_point'][$student_id]=$grade_point;
				$finalArray[$course_mapping_id]['grade'][$student_id]=$grade;
			}
		}
		//pr($finalArray);
		return $finalArray;
	}
	
	/* public function processESEresults($ese_results, $course_details) {
		$finalArray = array();
		foreach ($course_details as $course_mapping_id => $cmArray) {
			pr($course_mapping_id);
			$caeArray = $this->InternalExam->find('all', array(
					'conditions'=>array('InternalExam.course_mapping_id'=>$course_mapping_id,
							//'InternalExam.student_id'=>$student_id
					),
					'fields'=>array('InternalExam.id', 'InternalExam.course_mapping_id', 'InternalExam.marks',
							'InternalExam.student_id', 'InternalExam.month_year_id'),
					'order'=>array('InternalExam.id DESC'),
					'limit'=>1
			));
			pr($caeArray);
		}
		
	} */
	
	public function practicalCourseMappingArray($batch_id, $courseTypeIdArray, $program_id, $month_year_id, $course_mapping_array) {
		//pr($course_mapping_array);
		$conditions = array();
		$conditions['CourseMapping.batch_id'] = $batch_id;
		$conditions['CourseMapping.indicator'] = 0;
		$conditions['Course.course_type_id'] = $courseTypeIdArray;
		$conditions['CourseMapping.id'] = array_keys($course_mapping_array);
		
		if ($program_id > 0) {
			$conditions['CourseMapping.program_id'] = $program_id;
		}
		if ($month_year_id > 0) {
			$conditions['CourseMapping.month_year_id <='] = $month_year_id;
		}
		
		$cm_results = $this->CourseMapping->find('all', array(
				'conditions' => $conditions,
				'fields'=>array('CourseMapping.id'),
				'contain' => array(
						'Course' => array(
								'fields' => array('Course.id',),
						),
						'EsePractical' => array(
								'conditions'=>array('EsePractical.indicator'=>0),
								'fields'=>array('EsePractical.id')
						),
						'CaePractical' => array(
								'conditions'=>array('CaePractical.indicator'=>0),
								'fields'=>array('CaePractical.id')
						)
				),
				'recursive'=>2
		));
		
		return $cm_results;
	}
	
	public function processPracticalResults($cm_results, $course_details, $month_year_id, $courseTypeIdArray) {
		//pr($courseTypeIdArray);
		$finalArray = array();
		foreach ($cm_results as $key => $details) { 
			//pr($details);
			if (!in_array(3, $courseTypeIdArray)) {
				$cae_practical_id = $details['CaePractical'][0]['id'];
			}
			$ese_practical_id = $details['EsePractical'][0]['id'];
			$course_mapping_id = $details['CourseMapping']['id'];
			//$course_mapping_array[$course_mapping_id] = $course_mapping_id;
			$ese_results = $this->Practical->find('all', array(
					'conditions' => array('Practical.month_year_id'=>$month_year_id,
							'Practical.ese_practical_id'=>$ese_practical_id,
					),
					'fields'=>array('Practical.id', 'Practical.student_id', 'Practical.marks', 'Practical.ese_practical_id'),
			));
			//pr($ese_results);
			foreach ($ese_results as $key => $eseArray) {
				$student_id = $eseArray['Practical']['student_id'];
				$ese_marks = $eseArray['Practical']['marks'];
				if ($ese_marks == 'a' || $ese_marks == 'A' || $ese_marks == "") $ese_marks=0;
				//$finalArray[$cm_id][$stu]
				if (!in_array(3, $courseTypeIdArray)) {
					$caeArray = $this->InternalPractical->find('all', array(
							'conditions'=>array('InternalPractical.cae_practical_id'=>$cae_practical_id,
									'InternalPractical.student_id'=>$student_id
							),
							'fields'=>array('InternalPractical.id', 'InternalPractical.cae_practical_id',
									'InternalPractical.marks', 'InternalPractical.student_id', 'InternalPractical.month_year_id'),
							'order'=>array('InternalPractical.id DESC'),
							'limit'=>1
					));
					$cae_marks = $caeArray[0]['InternalPractical']['marks'];
				}
				else {
					$caeArray = $this->InternalExam->find('all', array(
							'conditions'=>array('InternalExam.course_mapping_id'=>$course_mapping_id,
									'InternalExam.student_id'=>$student_id
							),
							'fields'=>array('InternalExam.id', 'InternalExam.course_mapping_id',
									'InternalExam.marks', 'InternalExam.student_id', 'InternalExam.month_year_id'),
							'order'=>array('InternalExam.id DESC'),
							'limit'=>1
					));
					$cae_marks = $caeArray[0]['InternalExam']['marks'];
				}
				//pr($caeArray);
				
				if ($cae_marks == 'a' || $cae_marks == 'A' || $cae_marks == "") $cae_marks=0;
				$total = $cae_marks+$ese_marks;
				$finalArray[$course_mapping_id]['cae'][$student_id]=$cae_marks;
				$finalArray[$course_mapping_id]['ese'][$student_id]=$ese_marks;
				$finalArray[$course_mapping_id]['total'][$student_id]=$total;
				
				if ($cae_marks >= $course_details[$course_mapping_id]['min_cae_mark']) {
					if ($ese_marks >= $course_details[$course_mapping_id]['min_ese_mark']) {
						if ($total >= $course_details[$course_mapping_id]['min_pass_mark']) {
							$status = "Pass";
							$computed_mark = round(($total/$course_details[$course_mapping_id]['course_max_marks'])*100);
							$grade_point = $this->grade_point($computed_mark);
							$grade = $this->grade($computed_mark);
						}
						else {
							$status = "Fail";
							$grade_point = 0;
							$grade = "RA";
						}
					}
					else {
						$status = "Fail";
						$grade_point = 0;
						$grade = "RA";
					}
				}
				else {
					$status = "Fail";
					$grade_point = 0;
					$grade = "RA";
				}
				$finalArray[$course_mapping_id]['status'][$student_id]=$status;
				$finalArray[$course_mapping_id]['grade_point'][$student_id]=$grade_point;
				$finalArray[$course_mapping_id]['grade'][$student_id]=$grade;
			}
	
		}
		return $finalArray;
	}
	
	public function projectCourseMappingArray($batch_id, $courseTypeIdArray, $program_id, $month_year_id, $course_mapping_array) {
		$conditions = array();
		$conditions['CourseMapping.batch_id'] = $batch_id;
		$conditions['CourseMapping.indicator'] = 0;
		$conditions['Course.course_type_id'] = $courseTypeIdArray;
		$conditions['CourseMapping.id'] = array_keys($course_mapping_array);
		
		if ($program_id > 0) {
			$conditions['CourseMapping.program_id'] = $program_id;
		}
		if ($month_year_id > 0) {
			$conditions['CourseMapping.month_year_id <='] = $month_year_id;
		}
		
		$cm_results = $this->CourseMapping->find('all', array(
				'conditions' => $conditions,
				'fields'=>array('CourseMapping.id'),
				'contain' => array(
						'Course' => array(
								'fields' => array('Course.id',),
						),
						'EseProject' => array(
								'conditions'=>array('EseProject.indicator'=>0),
								'fields'=>array('EseProject.id', 'EseProject.marks')
						),
						'CaeProject' => array(
								'conditions'=>array('CaeProject.indicator'=>0),
								'fields'=>array('CaeProject.id')
						)
				),
				'recursive'=>2
		));
		return $cm_results;
	}
	
	public function processProjectResults($cm_results, $course_details, $month_year_id, $courseTypeIdArray) {
		$finalArray = array();
		foreach ($cm_results as $key => $details) {
			if (isset($details['CaeProject'][0]['id']) && isset($details['CaeProject'][0]['id'])) {
				$cae_project_id = $details['CaeProject'][0]['id'];
				$ese_project_id = $details['EseProject'][0]['id'];
				$course_mapping_id = $details['CourseMapping']['id'];
				$project_max_ese_mark = $details['EseProject'][0]['marks'];
				//$course_mapping_array[$course_mapping_id] = $course_mapping_id;
				$ese_results = $this->ProjectViva->find('all', array(
						'conditions' => array('ProjectViva.month_year_id'=>$month_year_id,
								'ProjectViva.ese_project_id'=>$ese_project_id,
						),
						'fields'=>array('ProjectViva.id', 'ProjectViva.student_id', 'ProjectViva.marks',
								'ProjectViva.ese_project_id'),
				));
				//pr($ese_results);
				foreach ($ese_results as $key => $eseArray) {
					$student_id = $eseArray['ProjectViva']['student_id'];
					$ese_marks = $eseArray['ProjectViva']['marks'];
					$eseConvetTo = $course_details[$course_mapping_id]['max_ese_mark'];
					if ($ese_marks == 'a' || $ese_marks == 'A' || $ese_marks == "") $ese_marks=0;
					$ese_final_mark = round($eseConvetTo * $ese_marks / $project_max_ese_mark);
					//echo "</br>".$ese_final_mark;
					//$finalArray[$cm_id][$stu]
					$caeArray = $this->InternalProject->find('all', array(
							'conditions'=>array('InternalProject.course_mapping_id'=>$course_mapping_id,
									'InternalProject.student_id'=>$student_id
							),
							'fields'=>array('InternalProject.id', 'InternalProject.course_mapping_id',
									'InternalProject.marks', 'InternalProject.student_id', 'InternalProject.month_year_id'),
							'order'=>array('InternalProject.id DESC'),
							'limit'=>1
					));
					//pr($caeArray);
					$cae_marks=0;
					if (isset($caeArray[0]['InternalProject']['marks'])) {
						$cae_marks = $caeArray[0]['InternalProject']['marks'];
					}
					if ($cae_marks == 'a' || $cae_marks == 'A' || $cae_marks == "") $cae_marks=0;
					$total = $cae_marks+$ese_final_mark;
					$finalArray[$course_mapping_id]['cae'][$student_id]=$cae_marks;
					$finalArray[$course_mapping_id]['ese'][$student_id]=$ese_marks;
					$finalArray[$course_mapping_id]['total'][$student_id]=$total;
					//echo " *** ".$course_details[$course_mapping_id]['min_ese_mark'];
					if ($cae_marks >= $course_details[$course_mapping_id]['min_cae_mark']) {
						if ($ese_final_mark >= $course_details[$course_mapping_id]['min_ese_mark']) {
							if ($total >= $course_details[$course_mapping_id]['min_pass_mark']) {
								$status = "Pass";
								$computed_mark = round(($total/$course_details[$course_mapping_id]['course_max_marks'])*100);
								$grade_point = $this->grade_point($computed_mark);
								$grade = $this->grade($computed_mark);
							}
							else {
								$status = "Fail";
								$grade_point = 0;
								$grade = "RA";
							}
						}
						else {
							$status = "Fail";
							$grade_point = 0;
							$grade = "RA";
						}
					}
					else {
						$status = "Fail";
						$grade_point = 0;
						$grade = "RA";
					}
					$finalArray[$course_mapping_id]['status'][$student_id]=$status;
					$finalArray[$course_mapping_id]['grade_point'][$student_id]=$grade_point;
					$finalArray[$course_mapping_id]['grade'][$student_id]=$grade;
				}
			}	 
		}
		return $finalArray;
	}
	
	public function profTrainingCourseMappingArray($batch_id, $courseTypeIdArray, $program_id, $month_year_id, $course_mapping_array) {
		//pr(array_keys($course_mapping_array));
		/* pr($courseTypeIdArray);
		$conditions = array();
		$conditions['CourseMapping.batch_id'] = $batch_id;
		$conditions['CourseMapping.indicator'] = 0;
		$conditions['Course.course_type_id'] = $courseTypeIdArray;
	
		if ($program_id > 0) {
			$conditions['CourseMapping.program_id'] = $program_id;
		}
		if ($month_year_id > 0) {
			$conditions['CourseMapping.month_year_id <='] = $month_year_id;
		} */
		
		/* $tmpResult = $this->CourseMapping->find('list', array(
				'conditions' => array('CourseMapping.batch_id'=>$batch_id, 'CourseMapping.indicator'=>0),
				'fields'=>array('CourseMapping.id'),
				'contain' => array(
						'Course'=>array(
								'conditions'=>array('Course.course_type_id'=>$courseTypeIdArray)
						)
				),
		));
		pr($tmpResult);
		pr(array_keys($tmpResult));
		$tmpResult = implode(",", $tmpResult);
		echo $tmpResult; 
		$dbo = $this->CourseMapping->getDatasource();
		$logs = $dbo->getLog();
		$lastLog = end($logs['log']);
		echo $lastLog['query']; */
		/* $smResult = $this->StudentMark->query("
				SELECT StudentMark.course_mapping_id 
				FROM student_marks StudentMark JOIN students Student ON StudentMark.student_id = Student.id
				JOIN course_mappings CourseMapping ON CourseMapping.id=StudentMark.course_mapping_id  
				JOIN courses Course ON Course.id=CourseMapping.course_id 
				JOIN batches Batch ON Batch.id=Student.batch_id 
				JOIN programs Program ON Program.id = Student.program_id
				JOIN academics Academic ON Academic.id=Program.academic_id 
				WHERE Course.course_type_id = 5 and StudentMark.id
				IN (SELECT max( id ) FROM student_marks sm1 WHERE StudentMark.student_id = sm1.student_id AND
				sm1.month_year_id < $month_year_id	GROUP BY sm1.student_id, sm1.course_mapping_id ORDER BY sm1.id DESC)
				AND ((StudentMark.status = 'Fail' AND StudentMark.revaluation_status =0) OR
				(StudentMark.final_status = 'Fail'AND StudentMark.revaluation_status =1))
				AND StudentMark.month_year_id < $month_year_id AND StudentMark.course_mapping_id IN (".$tmpResult.")
				AND Student.discontinued_status=0 
				GROUP BY StudentMark.course_mapping_id ASC
				");
		pr($smResult); */
		/* $cm_results = $this->CourseMapping->find('all', array(
				'conditions' => $conditions,
				'fields'=>array('CourseMapping.id'),
				'contain' => array(
						'Course' => array(
								'fields' => array('Course.id',),
						),
						'CaePt' => array(
								'conditions'=>array('CaePt.indicator'=>0),
								'fields'=>array('CaePt.id', 'CaePt.marks'),
								'ProfessionalTraining' => array(
										'fields'=>array('ProfessionalTraining.id', 'ProfessionalTraining.month_year_id',
												'ProfessionalTraining.student_id', 'ProfessionalTraining.marks'
										),
								)
						),
				),
				'recursive'=>2
		)); */
		//pr(array_keys($course_mapping_array));
		$this->loadModel('CaePt');
		$caePtResult = $this->CaePt->find('list', array(
				'conditions' => array('CaePt.course_mapping_id'=>array_keys($course_mapping_array)),
				'fields'=>array('CaePt.id'),
				'contain' => false
		));
		//pr($caePtResult);
		
		$tmpResults = $this->ProfessionalTraining->find('all', array(
				'conditions' => array('ProfessionalTraining.month_year_id'=>$month_year_id,
						'ProfessionalTraining.cae_pt_id'=>array_keys($caePtResult),
				),
				'fields'=>array('ProfessionalTraining.id', 'ProfessionalTraining.cae_pt_id', 'ProfessionalTraining.marks',
						'ProfessionalTraining.student_id', 'ProfessionalTraining.month_year_id'
				),
				'contain' => array(
						'CaePt'=>array(
								'fields'=>array('CaePt.id', 'CaePt.marks'),
								'CourseMapping'=>array(
										'fields'=>array('CourseMapping.id'),
										//'conditions' => array_keys($course_mapping_array)
								)
						)
				),
				'recursive' => 2
		));
		//pr($tmpResults); 
		//die;
		return $tmpResults;
	}
	
	public function processProfTrainingResults($cm_results, $course_details, $month_year_id) {
		//pr($cm_results);
		$finalArray = array();
		//pr($course_details);
		foreach ($cm_results as $key => $details) {
			if (isset($details['CaePt']['id'])) {
				$cae_pt_id = $details['CaePt']['id'];
				$course_mapping_id = $details['CaePt']['CourseMapping']['id'];
				$prof_training_mark = $details['CaePt']['marks'];
				$student_id = $details['ProfessionalTraining']['student_id'];
				$total = $details['ProfessionalTraining']['marks'];
				if ($total == 'a' || $total == 'A' || $total == "") $total=0;
//if ($course_mapping_id == 1161) pr($course_details);
				$finalArray[$course_mapping_id]['total'][$student_id]=$total;
				if ($total >= $course_details[$course_mapping_id]['min_pass_mark']) {
					$status = "Pass";
					$computed_mark = round(($total/$course_details[$course_mapping_id]['course_max_marks'])*100);
					//echo $computed_mark;
					$grade_point = $this->grade_point($computed_mark);
					$grade = $this->grade($computed_mark);
				}
				else {
					$status = "Fail";
					$grade_point = 0;
					$grade = "RA";
				}
				$finalArray[$course_mapping_id]['status'][$student_id]=$status;
				$finalArray[$course_mapping_id]['grade_point'][$student_id]=$grade_point;
				$finalArray[$course_mapping_id]['grade'][$student_id]=$grade;
			}
		}
		//pr($finalArray);
		
		/* foreach ($cm_results as $key => $details) {
			if (isset($details['CaePt']['id'])) {
				$cae_pt_id = $details['CaePt']['id'];
				//$ese_project_id = $details['EseProject'][0]['id'];
				$course_mapping_id = $details['CourseMapping']['id'];
				$prof_training_mark = $details['CaePt']['marks'];
				//$course_mapping_array[$course_mapping_id] = $course_mapping_id;
				if (isset($details['CaePt']['ProfessionalTraining'])) {
					$ese_results = $details['CaePt']['ProfessionalTraining'];
					foreach ($ese_results as $key => $eseArray) {
						$student_id = $eseArray['student_id'];
						$total = $eseArray['marks'];
						if ($total == 'a' || $total == 'A' || $total == "") $total=0;
						
						$finalArray[$course_mapping_id]['total'][$student_id]=$total;
						if ($total >= $course_details[$course_mapping_id]['min_pass_mark']) {
							$status = "Pass";
							$computed_mark = round(($total/$course_details[$course_mapping_id]['course_max_marks'])*100);
							$grade_point = $this->grade_point($computed_mark);
							$grade = $this->grade($computed_mark);
						}
						else {
							$status = "Fail";
							$grade_point = 0;
							$grade = "RA";
						}
						$finalArray[$course_mapping_id]['status'][$student_id]=$status;
						$finalArray[$course_mapping_id]['grade_point'][$student_id]=$grade_point;
						$finalArray[$course_mapping_id]['grade'][$student_id]=$grade;
					}
				}
			}
		} */
		return $finalArray;
	}
	
	/* public function listCourseTypeIdsBasedOnMethod($method_name, $option) {
		$filterCondition="";
		SWITCH ($method_name) {
			case "theory":
				$filterCondition.= "`(CourseType`.`course_type` LIKE '%theory%')";
				break;
			case "practical":
				if ($option == "both") {
					$filterCondition.= "`(CourseType`.`course_type` LIKE '%practical%') OR (CourseType`.`course_type` LIKE 'studio%')";
				}
				else {
					$filterCondition.= "`(CourseType`.`course_type` LIKE 'practical%') OR (CourseType`.`course_type` LIKE 'studio%')";
				}
				break;
			case "project":
				$filterCondition.= "`(CourseType`.`course_type` LIKE '%project%')";
				break;
			case "PT":
				$filterCondition.= "`(CourseType`.`course_type` LIKE '%PT%')";
				break;
		}
		$courseTypes = $this->CourseType->find('list', array(
				'conditions' => array($filterCondition),
		));
		return $courseTypes;
	} */
	
	public function listCourseTypeIdsBasedOnMethod($method_name, $option) {
		$this->loadModel('CourseType');
		$filterCondition="";
		SWITCH ($method_name) {
			case "theory":
				$filterCondition.= "`(CourseType`.`course_type` LIKE '%theory%')";
				break;
			case "practical":
				if ($option == "both") {
					$filterCondition.= "`(CourseType`.`course_type` LIKE '%practical%') OR (CourseType`.`course_type` LIKE 'studio%')";
				}
				else {
					$filterCondition.= "`(CourseType`.`course_type` LIKE 'practical%') OR (CourseType`.`course_type` LIKE 'studio%')";
				}
				break;
			case "project":
				$filterCondition.= "`(CourseType`.`course_type` LIKE '%project%')";
				break;
			case "PT":
				$filterCondition.= "`(CourseType`.`course_type` LIKE '%PT%')";
				break;
		}


		$courseTypes = $this->CourseType->find('list', array(
				'conditions' => array($filterCondition),
		)); 
		$explodeCourseType = $this->explodeCourseTypeId($courseTypes);

	
		return $explodeCourseType;
	}
	
	public function explodeCourseTypeId($courseType) {
		$explodeCourseType = "";
		foreach($courseType as $key => $value) {
			$explodeCourseType.=$key."-";
		}
		$explodeCourseType = substr($explodeCourseType,0,strlen($explodeCourseType)-1);
		return $explodeCourseType;
	}
	public function getNumberOfSemestersForABatch($batch_id) {
		$results = $this->Batch->find('all', array(
				'conditions'=>array('Batch.id'=>$batch_id),
				'fields'=>array('Batch.id', 'Batch.batch_to', 'Batch.batch_from'),
				'contain'=>false
		));
		//pr($results);
		$num_of_semesters = ($results[0]['Batch']['batch_to']-$results[0]['Batch']['batch_from']) * 2;
		return $num_of_semesters;
	}
	
	//For timetable processing
	protected function getArrearDataBasedOnCourseType($exam_month_year_id, $course_type) {
		$results = $this->StudentMark->query("
				SELECT sm.id, sm.course_mapping_id, sm.month_year_id, sm.student_id, s.registration_number, c.course_code,
				c.course_type_id, s.batch_id, s.program_id, cm.batch_id, cm.program_id, cm.month_year_id, cm.semester_id, c.id, 
				b.batch_from, b.batch_to 
				FROM student_marks sm
				JOIN students s ON sm.student_id=s.id
				JOIN course_mappings cm ON sm.course_mapping_id=cm.id
				JOIN courses c ON cm.course_id=c.id 
				JOIN batches b ON b.id=cm.batch_id 
				WHERE sm.id IN
				(SELECT max( id ) FROM student_marks sm1 WHERE sm.student_id =sm1.student_id
				AND sm1.month_year_id < $exam_month_year_id
				GROUP BY course_mapping_id, sm1.student_id ORDER BY id DESC)
				AND ((sm.status='Fail' AND sm.revaluation_status=0) OR (sm.final_status='Fail' AND sm.revaluation_status=1))
				AND c.course_type_id in (".implode(Configure::read('CourseType.theory'), ',').")
				AND sm.month_year_id < $exam_month_year_id 
				AND s.discontinued_status = 0 
				AND cm.batch_id = 4 
				ORDER BY sm.course_mapping_id  ASC");
		//AND (cm.program_id = 18 OR cm.program_id = 22)  
		/* $dbo = $this->CourseMapping->getDatasource();
		$logs = $dbo->getLog();
		$lastLog = end($logs['log']);
		echo $lastLog['query']; */
		return $results;
	}
	
	protected function retriveCmIdCourseCodeFromArrearResults($results) {
		//pr($results);
		$finalArray = array();
		foreach ($results as $key => $value) {
			$finalArray[$value['sm']['course_mapping_id']] = array(
					'course_code'=>$value['c']['course_code'],
					'batch_id'=>$value['cm']['batch_id'],
					'program_id'=>$value['cm']['program_id'],
					'month_year_id'=>$value['cm']['month_year_id'],
			);
		}
		return $finalArray;
	}
	
	protected function retriveCourseCodeCmIdFromArrearResults($results) {
		/* $finalArray = array();
			foreach ($results as $key => $value) {
			$finalArray[trim($value['c']['course_code'])] = trim($value['c']['course_code']);
			}
			return $finalArray; */
	
		$finalArray = array();
		$courseCodeArray = array();
		$courseCodeDetails = array();
		foreach ($results as $key => $value) {
			$courseCodeArray[trim($value['c']['course_code'])] = trim($value['c']['course_code']);
			$courseCodeDetails[trim($value['c']['course_code'])][$value['sm']['course_mapping_id']] = array(
					'batch_duration'=>$value['b']['batch_to']-$value['b']['batch_from'],
					'batch_id'=>$value['cm']['batch_id'],
					'program_id'=>$value['cm']['program_id'],
					'month_year_id'=>$value['cm']['month_year_id'],
					'semester_id'=>$value['cm']['semester_id'],
					'course_id'=>$value['c']['id']
			);
		}
		$finalArray['course_code'] = $courseCodeArray;
		$finalArray['course_code_details'] = $courseCodeDetails;
		return $finalArray;
	
	}
	
	protected function getNonArrearDataBasedOnCourseType($exam_month_year_id, $course_type) {
		$this->loadModel('CourseStudentMapping');
		$results=$this->CourseStudentMapping->query("
				SELECT CourseStudentMapping.course_mapping_id, CourseStudentMapping.student_id,
				Student.registration_number, Student.name, Course.course_code, cm.batch_id, cm.program_id,
				cm.month_year_id, cm.semester_id, cm.course_id, b.batch_from, b.batch_to 
				FROM `course_student_mappings` CourseStudentMapping
				JOIN students Student ON CourseStudentMapping.student_id = Student.id
				JOIN course_mappings cm ON cm.id=CourseStudentMapping.course_mapping_id
				JOIN courses Course ON Course.id=cm.course_id 
				JOIN batches b ON b.id=cm.batch_id 
				WHERE Student.discontinued_status =0 
				AND CourseStudentMapping.new_semester_id =$exam_month_year_id
				AND Course.course_type_id in (".implode(Configure::read('CourseType.theory'), ',').")
				");
		/*
		 * AND cm.batch_id = 4 
				AND (cm.program_id = 18 OR cm.program_id = 22)  
		 * 
		 */
		/* $dbo = $this->CourseMapping->getDatasource();
		$logs = $dbo->getLog();
		$lastLog = end($logs['log']);
		echo $lastLog['query']; */
		return $results;
	}
	
	protected function retriveCmIdCourseCodeFromNonArrearResults($results) {
		//pr($results);
		$finalArray = array();
		foreach ($results as $key => $value) {
			$finalArray[$value['CourseStudentMapping']['course_mapping_id']] =  array(
					'course_code'=>$value['Course']['course_code'],
					'batch_id'=>$value['cm']['batch_id'],
					'program_id'=>$value['cm']['program_id'],
					'month_year_id'=>$value['cm']['month_year_id'],
			);
		}
		return $finalArray;
	}
	
	protected function retriveCourseCodeCmIdFromNonArrearResults($results) {
		//pr($results);
		$finalArray = array();
		$courseCodeArray = array();
		$courseCodeDetails = array();
		foreach ($results as $key => $value) {
			$courseCodeArray[trim($value['Course']['course_code'])] = trim($value['Course']['course_code']);
			$courseCodeDetails[trim($value['Course']['course_code'])][$value['CourseStudentMapping']['course_mapping_id']] = array(
					'batch_duration'=>$value['b']['batch_to']-$value['b']['batch_from'],
					'batch_id'=>$value['cm']['batch_id'],
					'program_id'=>$value['cm']['program_id'],
					'month_year_id'=>$value['cm']['month_year_id'],
					'semester_id'=>$value['cm']['semester_id'],
					'course_id'=>$value['cm']['course_id']
			);
		}
		$finalArray['course_code'] = $courseCodeArray;
		$finalArray['course_code_details'] = $courseCodeDetails;
		return $finalArray;
	}
	
	protected function getRegularDataBasedOnCourseType($exam_month_year_id, $course_type) {
		$regular = $this->CourseMapping->find('all', array(
				'conditions'=>array(
						'CourseMapping.indicator'=>0, 'CourseMapping.month_year_id'=>$exam_month_year_id,
				),
				'fields'=>array('CourseMapping.id', 'CourseMapping.month_year_id', 'CourseMapping.batch_id',
						'CourseMapping.program_id', 'CourseMapping.semester_id'
				),
				'contain'=>array(
						'Course'=>array(
								'conditions'=>array('Course.course_type_id'=>Configure::read('CourseType.theory')),
								'fields'=>array('Course.course_code', 'Course.id')
						),
						'Batch'=>array(
							'fields'=>array('Batch.batch_to', 'Batch.batch_from')
						)
				)
		));
		/*
		 * 'CourseMapping.batch_id'=>4,  
						'CourseMapping.program_id'=>array(18,22)
		 */
		//pr($regular);
		/* $dbo = $this->CourseMapping->getDatasource();
		$logs = $dbo->getLog();
		$lastLog = end($logs['log']);
		echo $lastLog['query']; */
		return $regular;
	}
	
	protected function retriveCmIdCourseCodeFromRegularResults($results) {
		$finalArray = array();
		foreach ($results as $key => $value) {
			if (isset($value['Course']['id'])) {
				$finalArray[$value['CourseMapping']['id']] =  array(
						'course_code'=>$value['Course']['course_code'],
						'batch_id'=>$value['CourseMapping']['batch_id'],
						'program_id'=>$value['CourseMapping']['program_id'],
						'month_year_id'=>$value['CourseMapping']['month_year_id'],
				);
			}
		}
		return $finalArray;
	}
	
	protected function retriveCourseCodeCmIdFromRegularResults($results) {
		//pr($results);
		$finalArray = array();
		$courseCodeArray = array();
		$courseCodeDetails = array();
		foreach ($results as $key => $value) {
			if (isset($value['Course']['id'])) {
				$courseCodeArray[trim($value['Course']['course_code'])] = trim($value['Course']['course_code']);
	
				$courseCodeDetails[trim($value['Course']['course_code'])][$value['CourseMapping']['id']]= array(
						'batch_duration'=>$value['Batch']['batch_to']-$value['Batch']['batch_from'],
						'batch_id'=>$value['CourseMapping']['batch_id'],
						'program_id'=>$value['CourseMapping']['program_id'],
						'month_year_id'=>$value['CourseMapping']['month_year_id'],
						'semester_id'=>$value['CourseMapping']['semester_id'],
						'course_id'=>$value['Course']['id']
				);
	
			}
		}
		$finalArray['course_code'] = $courseCodeArray;
		$finalArray['course_code_details'] = $courseCodeDetails;
		return $finalArray;
	}
	//Timetable processing ends here
	
	protected function checkIfArrearExistsInStudentMarkForACourse($cm_id) {
		$results = $this->StudentMark->query("SELECT distinct(StudentMark.course_mapping_id)
					FROM student_marks StudentMark join students Student on StudentMark.student_id=Student.id
					WHERE Student.discontinued_status=0 and StudentMark.id IN
					(SELECT max( id ) FROM `student_marks` where student_marks.course_mapping_id = StudentMark.course_mapping_id
					GROUP BY student_marks.course_mapping_id, student_marks.student_id ORDER BY student_marks.id DESC)
					AND ((StudentMark.status='Fail' AND StudentMark.revaluation_status=0)
					OR (StudentMark.final_status='Fail' AND StudentMark.revaluation_status=1))
					AND StudentMark.course_mapping_id=".$cm_id);
		return $results;
	}
	
	protected function checkIfArrearExistsinCSM($exam_month_year_id, $course_type) {
		$results = $this->CourseStudentMapping->query("
				SELECT CSM.id, CSM.course_mapping_id, Course.course_type_id FROM `course_student_mappings` CSM
				LEFT OUTER JOIN student_marks SM ON CSM.course_mapping_id=SM.course_mapping_id
				JOIN course_mappings CM ON CSM.course_mapping_id=CM.id
				JOIN courses Course ON Course.id=CM.course_id 
				WHERE CSM.new_semester_id IS NOT NULL
				AND CSM.new_semester_id < $exam_month_year_id
				AND Course.course_type_id IN (".implode($course_type, ',').")
				AND CSM.student_id NOT IN
				(select SM1.student_id from student_marks SM1 where SM.course_mapping_id=SM1.course_mapping_id)
				group by CSM.course_mapping_id"
				);
		$cm_id_array = array();
		foreach ($results as $key => $arr) {
			if (isset($arr['Course']['course_type_id']) && $arr['Course']['course_type_id'] > 0) {
				$cm_id = $arr['CSM']['course_mapping_id'];
				if (!empty($arr) && isset($arr['CSM']['course_mapping_id'])) {
					$cm_id_array[$arr['CSM']['course_mapping_id']] = $arr['CSM']['course_mapping_id'];
				}
			}
		}
		return $cm_id_array;
	}
	
	protected function listArrearStudents($exam_month_year_id, $cm_id) {
		$results = $this->StudentMark->query("
				SELECT StudentMark.id, StudentMark.course_mapping_id, StudentMark.month_year_id, StudentMark.student_id,
				Student.registration_number, Student.name, Student.id, Course.course_code, Course.course_name, 
				Program.short_code, Batch.batch_from, Batch.batch_to, Batch.academic, Academic.short_code   
				FROM student_marks StudentMark JOIN students Student ON StudentMark.student_id = Student.id
				JOIN course_mappings CourseMapping ON CourseMapping.id=StudentMark.course_mapping_id 
				JOIN courses Course ON Course.id=CourseMapping.course_id 
				JOIN batches Batch ON Batch.id=Student.batch_id 
				JOIN programs Program ON Program.id = Student.program_id
				JOIN academics Academic ON Academic.id=Program.academic_id 
				WHERE StudentMark.id
				IN (SELECT max( id ) FROM student_marks sm1 WHERE StudentMark.student_id = sm1.student_id AND
				sm1.month_year_id < $exam_month_year_id	GROUP BY sm1.student_id, sm1.course_mapping_id ORDER BY sm1.id DESC)
				AND ((StudentMark.status = 'Fail' AND StudentMark.revaluation_status =0) OR
				(StudentMark.final_status = 'Fail'AND StudentMark.revaluation_status =1))
				AND StudentMark.month_year_id < $exam_month_year_id AND StudentMark.course_mapping_id = $cm_id
				AND Student.discontinued_status=0 
				ORDER BY StudentMark.student_id ASC
				");
		return $results;
	}
	
	protected function listNonArrearStudents($exam_month_year_id, $cm_id) {
		$results = $this->CourseStudentMapping->query("
				SELECT CourseStudentMapping.id, CourseStudentMapping.course_mapping_id, CourseStudentMapping.student_id,
				Student.registration_number, Student.name, Student.id 
				FROM `course_student_mappings` CourseStudentMapping
				LEFT OUTER JOIN student_marks SM ON CourseStudentMapping.course_mapping_id=SM.course_mapping_id
				JOIN students Student ON CourseStudentMapping.student_id = Student.id
				WHERE CourseStudentMapping.new_semester_id IS NOT NULL 
				AND SM.indicator = 0
				AND CourseStudentMapping.new_semester_id <= $exam_month_year_id
				AND CourseStudentMapping.course_mapping_id = $cm_id
				AND Student.discontinued_status=0
				AND CourseStudentMapping.student_id NOT IN
				(select SM1.student_id from student_marks SM1 where SM.course_mapping_id=SM1.course_mapping_id AND
				SM1.indicator=0)
				group by CourseStudentMapping.course_mapping_id, CourseStudentMapping.student_id"
				);
		/* $dbo = $this->CourseMapping->getDatasource();
		$logs = $dbo->getLog();
		$lastLog = end($logs['log']);
		echo $lastLog['query']; */
		//pr($results);
		return $results;
	}
	
	public function getCourseNameCrseCodeCmnCodeFromCMId($cm_id) {
		$conditions= array();
		$conditions['CourseMapping.id']=$cm_id;
		$results = $this->CourseMapping->find("all", array(
				'conditions'=>$conditions,
				'fields'=>array('CourseMapping.id'),
				'contain'=>array(
						'Course'=>array(
								'fields'=>array('Course.course_code', 'Course.common_code', 'Course.course_name', )
						),
						'Batch'=>array(
								'fields'=>array('Batch.batch_from', 'Batch.batch_to', 'Batch.academic')
						),
						'Program'=>array(
								'fields'=>array('Program.program_name', 'Program.short_code'),
								'Academic'=>array(
										'fields'=>array('Academic.academic_name', 'Academic.short_code')
								),
						),
				)
		));
		//pr($results);
		return $results;
	}
	
	public function getCourseTypeIdFromCmId($cm_id) {
		$conditions= array();
		$conditions['CourseMapping.id']=$cm_id;
		$results = $this->CourseMapping->find("all", array(
			'conditions'=>$conditions,
			'fields'=>array('CourseMapping.id'),
			'contain'=>array(
				'Course'=>array(
					'fields'=>array('Course.course_type_id')
				),
			)
		));
		//pr($results);
		return $results;
	}
	
	public function getCourseNameCrseCodeFromCMId($cm_id) {
		$conditions= array();
		$conditions['CourseMapping.id']=$cm_id;
		$results = $this->CourseMapping->find("all", array(
				'conditions'=>$conditions,
				'fields'=>array('CourseMapping.id'),
				'contain'=>array(
						'Course'=>array(
								'fields'=>array('Course.course_code', 'Course.common_code', 'Course.course_name', 
										'Course.course_max_marks', 'Course.course_type_id'
								)
						),
				)
		));
		//pr($results);
		return $results;
	}
	
	public function examAbsentSearchExcel($results, $examMonth, $exam_type, $exam_session, $exam_date, $common_code) {
		if($exam_type == 'R') $exam_type="Regular"; else $exam_type = "Arrear";
		$month_year = $this->getExamMonthYearName($examMonth);
		
		$phpExcel = new PHPExcel();
		$phpExcel->setActiveSheetIndex(0);
		$sheet = $phpExcel->getActiveSheet();
		$sheet->getRowDimension('1')->setRowHeight('18');
		$sheet->setTitle("FINAL_CAE");
		
		$sheet->setCellValue("A1", "Exam MonthYear");
		$sheet->setCellValue("D1", "EXAM TYPE");
		$sheet->setCellValue("A2", "EXAM SESSION");
		$sheet->setCellValue("D2", "EXAM DATE");
		
		$sheet->setCellValue("B1", $month_year);
		$sheet->setCellValue("E1", $exam_type);
		$sheet->setCellValue("B2", $exam_session);
		$sheet->setCellValue("E2", $exam_date);
		
		
		$sheet->setCellValue("A4", "REGISTER NUMBER");
		$sheet->setCellValue("B4", "STUDENT NAME");
		$sheet->setCellValue("C4", "BATCH");
		$sheet->setCellValue("D4", "PROGRAM");
		$sheet->setCellValue("E4", "SPECIALISATION");
		$i=5;
		foreach ($results as $key => $result) {
			$ea_array = $result['ExamAttendance'];
			foreach ($ea_array as $value) {
				$sheet->getRowDimension($i)->setRowHeight('18');
				$sheet->setCellValue('A'.$i, $value['Student']['registration_number']);
				$sheet->setCellValue('B'.$i, $value['Student']['name']);
				$sheet->setCellValue('C'.$i, $result['CourseMapping']['Batch']['batch_period']);
				$sheet->setCellValue('D'.$i, $result['CourseMapping']['Program']['program_name']);
				$sheet->setCellValue('E'.$i, $result['CourseMapping']['Program']['Academic']['academic_name']);
				$i++;
			}
		}
		
		$download_filename="EXAM_ABSENTEES_".$common_code."_".date('d_M_Y h:i:s');
		header("Content-Type: application/vnd.ms-excel");
		header("Content-Disposition: attachment; filename=\"$download_filename.xls\"");
		header("Cache-Control: max-age=0");
		$objWriter = PHPExcel_IOFactory::createWriter($phpExcel, "Excel5");
		$objWriter->save("php://output");
		exit;
	}

	public function getMonthYearFromSemesterId($batch_id, $program_id, $semester_id) {
		$results = $this->CourseMapping->find('first', array(
				'conditions'=>array('CourseMapping.batch_id'=>$batch_id, 'CourseMapping.program_id'=>$program_id,
						'CourseMapping.indicator'=>0, 'CourseMapping.semester_id'=>$semester_id
				),
				'fields'=>array('CourseMapping.month_year_id'),
				'recursive'=>0
		));
		return $results;
	}
	
	public function getSemesterIdFromMonthYear($batch_id, $program_id, $month_year_id) {
		$results = $this->CourseMapping->find('first', array(
				'conditions'=>array('CourseMapping.batch_id'=>$batch_id, 'CourseMapping.program_id'=>$program_id,
						'CourseMapping.indicator'=>0, 'CourseMapping.month_year_id'=>$month_year_id
				),
				'fields'=>array('CourseMapping.semester_id'),
				'recursive'=>0
		));
		return $results;
	}
	
	public function maxMonthYearAndSemesterId($batch_id, $program_id) {
		$array=array();
		$maxMYResults = $this->CourseMapping->find('all', array(
				'conditions' => array('CourseMapping.batch_id' => $batch_id, 'CourseMapping.program_id' => $program_id,
						'CourseMapping.indicator' => 0
				),
				'fields' => array('MIN(CourseMapping.month_year_id) AS min_my_id',
									'MIN(CourseMapping.semester_id) AS min_sem_id',
									'MAX(CourseMapping.month_year_id) AS max_my_id', 
									'MAX(CourseMapping.semester_id) AS max_sem_id'),
				'contain' => false
		));
		//pr($maxMYResults);
		$max_month_year_id = $maxMYResults[0][0]['max_my_id'];
		$max_semester_id = $maxMYResults[0][0]['max_sem_id'];
		$min_month_year_id = $maxMYResults[0][0]['min_my_id'];
		$min_semester_id = $maxMYResults[0][0]['min_sem_id'];
		$array['month_year_id'] = $max_month_year_id;
		$array['semester_id'] = $max_semester_id;
		$array['start_month_year_id'] = $min_month_year_id;
		$array['start_semester_id'] = $min_semester_id;
		return $array;
	}
	
	public function retrieveMonthYearBeyondAMonthYear($month_year_id) {
		$monthYears = $this->MonthYear->find('all', array(
				'fields' => array('MonthYear.id','MonthYear.year','MonthYear.month_id','Month.month_name'),
				'conditions' => array('MonthYear.id >'=>$month_year_id),
				'order' => array('MonthYear.id')));
		$monthyears=array();
			
		foreach ($monthYears as $key => $value) {
			$monthyears[$value['MonthYear']['id']]=$value['Month']['month_name']."-".$value['MonthYear']['year'];
		}
		return $monthyears;
	}
	
	public function timetableReportExcel($results, $examMonth, $exam_type) {
	
		$month_year = $this->getExamMonthYearName($examMonth);
	
		$phpExcel = new PHPExcel();
		$phpExcel->setActiveSheetIndex(0);
		$sheet = $phpExcel->getActiveSheet();
		$sheet->getRowDimension('1')->setRowHeight('18');
		$sheet->setTitle("TIMETABLE_REPORT");
	
		$sheet->setCellValue("A1", "EXAM DATE");
		$sheet->setCellValue("B1", "EXAM SESSION");
		$sheet->setCellValue("C1", "EXAM TYPE");
		$sheet->setCellValue("D1", "COURSE CODE");
		$sheet->setCellValue("E1", "COURSE NAME");
		$sheet->setCellValue("F1", "BATCH");
		$sheet->setCellValue("G1", "PROGRAM");
		$sheet->setCellValue("H1", "SPECIALISATION");
		$sheet->setCellValue("I1", "STRENGTH");
	
		$i=2;
		foreach ($results as $key => $result) {
			//foreach ($ea_array as $value) {
			$sheet->getRowDimension($i)->setRowHeight('18');
			$sheet->setCellValue('A'.$i, $result['exam_date']);
			$sheet->setCellValue('B'.$i, $result['exam_session']);
			$sheet->setCellValue('C'.$i, $result['exam_type']);
			$sheet->setCellValue('D'.$i, $result['course_code']);
			$sheet->setCellValue('E'.$i, $result['course_name']);
			$sheet->setCellValue('F'.$i, $result['batch']);
			$sheet->setCellValue('G'.$i, $result['academic']);
			$sheet->setCellValue('H'.$i, $result['program']);
			$sheet->setCellValue('I'.$i, $result['total_strength']);
			$i++;
			//}
		}
	
		$download_filename="TIMETABLE_REPORT_".date('d_M_Y h:i:s');
		header("Content-Type: application/vnd.ms-excel");
		header("Content-Disposition: attachment; filename=\"$download_filename.xls\"");
		header("Cache-Control: max-age=0");
		$objWriter = PHPExcel_IOFactory::createWriter($phpExcel, "Excel5");
		$objWriter->save("php://output");
		exit;
	}
	
	public function processAbsResult($results, $currentBatchDuration, $currentProgramId, $month_year_id) {
		//pr($results);
		$finalArray = array();
		foreach ($results as $key => $array) {
			if ($array['Student']['Batch']['batch_duration']==$currentBatchDuration &&
					$array['Student']['program_id']==$currentProgramId &&
					$array['StudentAuthorizedBreak']['new_month_year_id']==$month_year_id) {
						//echo $array['StudentAuthorizedBreak']['student_id'];
						$finalArray[$array['StudentAuthorizedBreak']['student_id']]=array(
								'registration_number'=>$array['Student']['registration_number'],
								'name'=>$array['Student']['name'],
								'batch_id'=>$array['Student']['batch_id'],
								'program_id'=>$array['Student']['program_id'],
						);
					}
		}
		return $finalArray;
	}
	
	public function monthYearBetweenIds($start_my_id, $end_my_id) {
		$array =array();
		$month_years = $this->MonthYear->find("all", array(
				'conditions'=>array('MonthYear.id BETWEEN '.$start_my_id.' AND '.$end_my_id),
				'fields' => array('MonthYear.month_id','MonthYear.year','MonthYear.id'),
				'contain'=>array(
						'Month'=>array('fields' =>array('Month.month_name'))
				)
		));
		foreach ($month_years as $key => $value) {
			$array[$value['MonthYear']['id']] = $value['Month']['month_name']." - ".$value['MonthYear']['year'];
		}
		return $array;
	}
	
	public function studentDetailsWithBatchAndProgram($batchId, $programId) {
		
		$frm_register_no ="";$to_register_no ="";
		$stuCond=array();
		
		$stuCond['Student.discontinued_status'] = 0;
		//$stuCond['Student.picture !='] = '';
		//$stuCond['Student.registration_number'] = '3596001';
		
		//$stuCond['Student.registration_number'] = '3531016';
		if($batchId != '-'){
			$stuCond['Student.batch_id'] = $batchId;
		}
		if($programId != '-'){
			$stuCond['Student.program_id'] = $programId;
		}
		
		$res = array(
				'conditions' => array($stuCond,
				),
				'fields' =>array('Student.id','Student.registration_number','Student.name','Student.birth_date','Student.gender','Student.picture','Student.parent_id','Student.university_references_id'),
				'contain'=>array(
						'Academic' => array(
								'fields' => array('Academic.short_code','Academic.academic_name')
						),
						'Batch' => array(
								'fields' => array('Batch.batch_from', 'Batch.batch_to', 'Batch.academic')
						),
						'Program' => array('fields' => array('Program.program_name','Program.short_code'),
								'Academic' => array('fields' => array('Academic.academic_name'))
						),
						'CourseStudentMapping'=>array(
								'fields' => array('CourseStudentMapping.course_mapping_id','CourseStudentMapping.type'),
						),
						'StudentAuditCourse'=>array(
								'fields'=>array('StudentAuditCourse.id', 'StudentAuditCourse.student_id',
										'StudentAuditCourse.audit_course_id', 'StudentAuditCourse.marks'),
								'AuditCourse'=>array(
										'fields' => array('AuditCourse.id','AuditCourse.course_name',
												'AuditCourse.course_code', 'AuditCourse.total_min_pass_mark'),
								),
		
						),
						'StudentWithheld' => array(
								'fields'=>array('StudentWithheld.id', 'StudentWithheld.student_id',
										'StudentWithheld.withheld_id'),
								'Withheld'=>array(
										'fields'=>array('Withheld.withheld_type')
								)
						),
						'StudentWithdrawal' => array(
								'fields'=>array('StudentWithdrawal.id', 'StudentWithdrawal.student_id',
										'StudentWithdrawal.withdrawal'),
						),
						'StudentAuthorizedBreak' => array(
								'fields'=>array('StudentAuthorizedBreak.id', 'StudentAuthorizedBreak.student_id',),
						),
						'Practical' => array(
								'fields' => array('Practical.month_year_id', 'Practical.marks', 'Practical.student_id'),
								/* 'conditions' => array('Practical.month_year_id <=' => $examMonth), */
								'EsePractical' => array(
										'fields' => array('EsePractical.course_mapping_id')
								)
						),
						'EndSemesterExam' => array(
								'fields' => array('EndSemesterExam.month_year_id', 'EndSemesterExam.marks', 'EndSemesterExam.student_id', 'EndSemesterExam.course_mapping_id', 'EndSemesterExam.dummy_number','EndSemesterExam.revaluation_status'),
								/* 'conditions' => array('EndSemesterExam.month_year_id <=' => $examMonth) */
						),
						'ProjectViva' => array(
								'fields' => array('ProjectViva.month_year_id', 'ProjectViva.marks', 'ProjectViva.student_id'),
								/* 'conditions' => array('ProjectViva.month_year_id <=' => $examMonth), */
								'EseProject' => array(
										'fields' => array('EseProject.course_mapping_id')
								)
						),
						'StudentMark' => array(
								'fields' => array('StudentMark.id','StudentMark.month_year_id', 'StudentMark.marks', 'StudentMark.student_id', 'StudentMark.course_mapping_id','StudentMark.status','StudentMark.revaluation_status','StudentMark.final_marks','StudentMark.final_status','StudentMark.grade_point','StudentMark.grade'),
								/* 'conditions' => array('StudentMark.month_year_id <=' => $examMonth), */
								'CourseMapping'=>array(
										'fields'=>array(
												//'CourseMapping.id',
												'CourseMapping.semester_id',
												'CourseMapping.month_year_id',
										),
										'conditions'=>array('CourseMapping.indicator'=>0,
										),
										'Course' => array(
												'fields' => array('Course.id','Course.course_code','Course.course_name','Course.course_max_marks','Course.credit_point'),
												'CourseType' => array('fields' => array('CourseType.course_type'))
										),
										'MonthYear'=>array('fields' =>array('MonthYear.year','MonthYear.publishing_date'),
												'Month'=>array('fields' =>array('Month.month_name'))
										),
										'order'=>'CourseMapping.semester_id ASC',
								),/* 'order'=>'StudentMark.month_year_id ASC', */
								'MonthYear'=>array('fields' =>array('MonthYear.year','MonthYear.publishing_date'),
										'Month'=>array('fields' =>array('Month.month_name'))
								),
						),
						'RevaluationExam' =>array(
								'fields' => array('RevaluationExam.id','RevaluationExam.course_mapping_id','RevaluationExam.revaluation_marks','RevaluationExam.status'),
								/* 'conditions' => array('RevaluationExam.month_year_id <=' => $examMonth), */
								'order'=>'RevaluationExam.month_year_id ASC',
								'MonthYear'=>array('fields' =>array('MonthYear.year'),
										'Month'=>array('fields' =>array('Month.month_name'))
								),
						),
						'ParentGroup' => array(
								'fields' => array('ParentGroup.batch_id', 'ParentGroup.academic_id', 'ParentGroup.program_id'),
								'Batch' => array(
										'fields' => array('Batch.batch_from', 'Batch.batch_to', 'Batch.academic')
								),
								'Program' => array('fields' => array('Program.program_name','Program.short_code'),
										'Academic' => array('fields' => array('Academic.academic_name'))
								),
		
								'Practical' => array(
										'fields' => array('Practical.month_year_id', 'Practical.marks', 'Practical.student_id'),
										/* 'conditions' => array('Practical.month_year_id <=' => $examMonth), */
										'EsePractical' => array(
												'fields' => array('EsePractical.course_mapping_id')
										)
								),
		
								'EndSemesterExam' => array(
										'fields' => array('EndSemesterExam.month_year_id', 'EndSemesterExam.marks', 'EndSemesterExam.student_id', 'EndSemesterExam.course_mapping_id', 'EndSemesterExam.dummy_number','EndSemesterExam.revaluation_status'),
										/* 'conditions' => array('EndSemesterExam.month_year_id <=' => $examMonth) */
								),
								'ProjectViva' => array(
										'fields' => array('ProjectViva.month_year_id', 'ProjectViva.marks', 'ProjectViva.student_id'),
										/* 'conditions' => array('ProjectViva.month_year_id <=' => $examMonth), */
										'EseProject' => array(
												'fields' => array('EseProject.course_mapping_id')
										)
								),
								'StudentMark' => array(
										'fields' => array('StudentMark.id','StudentMark.month_year_id', 'StudentMark.marks', 'StudentMark.student_id', 'StudentMark.course_mapping_id','StudentMark.status','StudentMark.revaluation_status','StudentMark.final_marks','StudentMark.final_status','StudentMark.grade_point','StudentMark.grade'),
										/* 'conditions' => array('StudentMark.month_year_id <=' => $examMonth), */
										'CourseMapping'=>array('fields'=>array('CourseMapping.id','CourseMapping.semester_id'),
												'conditions'=>array('CourseMapping.indicator'=>0,
												),
												'Course' => array(
														'fields' => array('Course.id','Course.course_code','Course.course_name','Course.course_max_marks','Course.credit_point'),
														'CourseType' => array('fields' => array('CourseType.course_type'))
												),
												'MonthYear'=>array('fields' =>array('MonthYear.year','MonthYear.publishing_date'),
														'Month'=>array('fields' =>array('Month.month_name'))
												),
												'order'=>'CourseMapping.semester_id ASC',
										),'order'=>'StudentMark.month_year_id ASC',
										'MonthYear'=>array('fields' =>array('MonthYear.year','MonthYear.publishing_date'),
												'Month'=>array('fields' =>array('Month.month_name'))
										),
								),
								'RevaluationExam' =>array(
										'fields' => array('RevaluationExam.id','RevaluationExam.course_mapping_id','RevaluationExam.revaluation_marks','RevaluationExam.status'),
										/* 'conditions' => array('RevaluationExam.month_year_id <=' => $examMonth), */
										'order'=>'RevaluationExam.month_year_id ASC',
										'MonthYear'=>array('fields' =>array('MonthYear.year'),
												'Month'=>array('fields' =>array('Month.month_name'))
										),
								),
						)
				),
				'recursive'=>0
		);
		
		$results = $this->Student->find("all", $res);
		return $results;
	}
	
	public function processDegreeCertificateReport($results) {
		if ($results) { //pr($results);
			$studentArray = array();
			foreach ( $results as $k => $result ) { //pr($result);
				//Idividual User processing starts here
				//echo $result['Student']['id'];
				if (isset($result['StudentAuthorizedBreak'][0]['student_id'])) $abs=1;
				else $abs=0;
		
				if (isset($result['StudentWithdrawal'][0]['student_id'])) $withdrawal=1;
				else $withdrawal=0;
		
				if (isset($result['StudentAuditCourse'][0]['student_id'])) $sac = 1;
				else $sac = 0;
				
				if (isset($result['StudentWithheld'][0]['student_id'])) $withheld = 1;
				else $withheld = 0;
				
				$batch_id = $result['Batch']['id'];
				$program_id = $result['Program']['id'];
		
				$finalArray = array();
		
				if (isset($result['ParentGroup']['StudentMark']) && count($result['ParentGroup']['StudentMark'])>0) {
					for($p = 0; $p < count($result['ParentGroup']['StudentMark']); $p++) {
						$month_year_id = $result['ParentGroup']['StudentMark'][$p]['month_year_id'];
						if (isset($finalArray[$month_year_id])) {
							$finalArray[$month_year_id][] = $result['ParentGroup']['StudentMark'][$p];
						} else {
							$finalArray[$month_year_id] = array($result['ParentGroup']['StudentMark'][$p]);
						}
					}
				}
		
				//pr ($result['StudentMark']);
		
				for($p = 0; $p < count($result['StudentMark']); $p++) {
					$month_year_id = $result['StudentMark'][$p]['month_year_id'];
					if (isset($finalArray[$month_year_id])) {
						$finalArray[$month_year_id][] = $result['StudentMark'][$p];
					} else {
						$finalArray[$month_year_id] = array($result['StudentMark'][$p]);
					}
				}
				//pr($finalArray);
		
				$cgpa=0;
				$numerator=0;
				$denominator=0;
				$p=0;
		
				$cmArray = array();
				$csm_array = $result['CourseStudentMapping'];
				
				foreach ($csm_array as $key => $cmValue) {
					$cmArray[$cmValue['course_mapping_id']] = $cmValue['type'];
				}
				//echo "CSM array";
				//pr($cmArray);
		
				$audit_courses = array();
				if (!empty($result['StudentAuditCourse']) && count($result['StudentAuditCourse']) >0) {
					$audit_courses = $result['StudentAuditCourse'];
				}
				//echo "audit courses array";
				//pr($audit_courses);
		
				$totalCreditsGained = 0; $numerator=0;
				
				$stuArrearFinalMark = array();
				$stuArrearFinalStatus = array();
				$courseCreditArray = array();
				$gradePointArray = array();
				$stuFinalMark = array();
				$stuFinalStatus = array();
				$stuArrearFinalMark = array();
				$stuArrearFinalStatus = array(); 
				$semesterIdArray = array();
				$courseSemester = array();
				$creditsGained = array();
				$arrears = array();
				
				foreach ($finalArray as $month_year_id => $value){ //echo count($value);
					foreach ($value as $key => $array) {
						$bool = false;
						$courseMId = $array['course_mapping_id'];
						
						$details = $this->getArrayValues($array);
						
						if($array['revaluation_status'] == 0 && $array['status'] == 'Pass' && $array['status'] != 'Fail') {
							$stuFinalMark[$courseMId] = $array['marks'];
							$stuFinalStatus[$courseMId] = $array['status'];
							
							$bool = true;
		
						} else if($array['revaluation_status'] == 1 && $array['final_status'] == 'Pass' && $array['final_status'] != 'Fail'){
							$stuFinalMark[$courseMId] = $array['final_marks'];
							$stuFinalStatus[$courseMId] = $array['final_status'];
							//$details = $this->getArrayValues($array);
							$bool = true;
						}
						if (isset($details) && !empty($details) && $bool==true) {
							//$examMonthYear[$courseMId]=$details['examMonthYear'];
							$courseSemester[$courseMId]=$details['courseSemester'];
							$creditsGained[$courseMId]=$details['creditsGained'];
							$gradePointArray[$courseMId]=$details['gradePointArray'];
							$courseCreditArray[$courseMId]=$details['courseCreditArray'];
							$semesterIdArray[$courseMId]=$details['semesterIdArray'];
							$totalCreditsGained = $totalCreditsGained + $courseCreditArray[$courseMId];
							$numerator = $numerator + ($details['grade_point'] * $details['creditsGained']);
							//echo "</br>num :".$numerator;
						}
						
						$courseCreditArray[$courseMId]=$details['courseCreditArray'];
						
						if($array['revaluation_status'] == 0 && $array['status'] == 'Fail' && $array['status'] != 'Pass') {
							$stuArrearFinalMark[$courseMId] = $array['marks'];
							$stuArrearFinalStatus[$courseMId] = $array['status'];
						} else if($array['revaluation_status'] == 1 && $array['final_status'] == 'Fail' && $array['final_status'] != 'Pass'){
							$stuArrearFinalMark[$courseMId] = $array['final_marks'];
							$stuArrearFinalStatus[$courseMId] = $array['final_status'];
						}
						
					}
				}
				
				$creditsRegisteredUntilExamMonthYear = array_sum($courseCreditArray);
				//echo "arrear data ";
				//echo $result['Student']['id'];
				
				//pr($stuFinalMark);
				//pr($stuArrearFinalMark);
				
				$arrears = array_diff_key($stuArrearFinalMark, array_intersect_key($stuFinalMark, $stuArrearFinalMark));
				//pr($arrears);
				//pr($stuArrearFinalStatus);
				//echo count($stuArrearFinalMark);
				//echo "credits gained array";
				//pr($creditsGained);
		
				//echo "grades array";
				//pr($gradePointArray);
		
				//echo "actual crse credit array";
				//pr($courseCreditArray);
		
				//echo "courses appeared and cleared";
				//pr($stuFinalStatus);
		
				//echo "disparity in courses passed and enrolled";
				$diff_array = array_diff_key($cmArray, $stuFinalStatus);
				//pr($diff_array);
		
				//echo "first attempt details";
				$first_attempt = array_diff_assoc($courseSemester,array_intersect_assoc($courseSemester, $semesterIdArray));
				//pr($first_attempt);
		
				//echo "Total Credits Gained : ".$totalCreditsGained;
		
				$programArray = $this->programCredit($program_id);
				
				//if (empty($diff_array) && $totalCreditsGained >= $programArray[$program_id] && empty($mandatory_array)) {
					$CGPA = sprintf('%0.2f',round(($numerator/$totalCreditsGained),2));
					$AppHelper = new AppHelper(null);
					$degree_classification = $AppHelper->generateModeClass($CGPA, $abs, $withdrawal, $first_attempt);
					//echo "eligibility criteria";
					//pr($degree_classification);
					$class_classification_in_eng = $degree_classification['E'];
					$class_classification_in_tam = $degree_classification['T'];
				//}
				//$studentArray[$result['Student']['id']] = array();
				$studentArray[$result['Student']['id']]['reg_num'] = $result['Student']['registration_number'];
				$studentArray[$result['Student']['id']]['name'] = $result['Student']['name'];
				if (empty($diff_array)) $studentArray[$result['Student']['id']]['status'] = "Pass";
				else $studentArray[$result['Student']['id']]['status'] = "";
				$studentArray[$result['Student']['id']]['class_classification'] = $class_classification_in_eng;
				$studentArray[$result['Student']['id']]['cgpa'] = $CGPA;
				$studentArray[$result['Student']['id']]['program_credit'] = $programArray[$program_id];
				$studentArray[$result['Student']['id']]['credits_earned'] = $totalCreditsGained;
				$studentArray[$result['Student']['id']]['grades_earned'] = array_sum($gradePointArray);
				$studentArray[$result['Student']['id']]['no_of_arrears'] = count($arrears); 
				$studentArray[$result['Student']['id']]['current_credit_regd'] = $creditsRegisteredUntilExamMonthYear;
				$studentArray[$result['Student']['id']]['audit_course'] = $sac;
				$studentArray[$result['Student']['id']]['withheld'] = $withheld;
				$studentArray[$result['Student']['id']]['abs'] = $abs;
				$studentArray[$result['Student']['id']]['withdrawal'] = $withdrawal;
								
			}
		}
		return $studentArray;
	}
	
	public function getArrayValues($array) {
		$tmpArray = array();
		//pr($array);
		$tmpArray['examMonthYear'] = $array['MonthYear']['Month']['month_name']."-".$array['MonthYear']['year'];
		$tmpArray['publishing_date'] = $array['MonthYear']['publishing_date'];
		$tmpArray['courseSemester'] = $array['CourseMapping']['semester_id'];
		$tmpArray['creditsGained'] = $array['CourseMapping']['Course']['credit_point'];
		
		$tmpArray['gradePointArray'] = $array['grade_point'];
		$tmpArray['courseCreditArray'] = $array['CourseMapping']['Course']['credit_point'];	
		$tmpArray['semesterIdArray'] = $array['month_year_id'];
		$tmpArray['courseCodeArray'] = $array['CourseMapping']['Course']['course_code'];
		$tmpArray['courseNameArray'] = $array['CourseMapping']['Course']['course_name'];
		
		$tmpArray['creditsGained'] = $array['CourseMapping']['Course']['credit_point'];
		
		$tmpArray['semId'] = $array['CourseMapping']['semester_id'];
		$tmpArray['semester'] = "$".$array['CourseMapping']['semester_id']." ";
		$tmpArray['course_code'] = "$".$array['CourseMapping']['Course']['course_code']." ";
		$tmpArray['course_name'] = "$".$array['CourseMapping']['Course']['course_name']." ";
		$tmpArray['grade'] = "$".$array['grade']." ";
		$tmpArray['grade_point'] = $array['grade_point'];
		$tmpArray['monthYearOfPassing'] = "$".$array['MonthYear']['Month']['month_name']."-".$array['MonthYear']['year']." ";
		return $tmpArray;
	}
	
	public function programCredit($program_id) {
		$programArray = $this->Program->find('list', array(
				'conditions'=>array('Program.id'=>$program_id,
				),
				'fields'=>array('Program.credits')
		));
		return $programArray;
	}
	
	public function getArrearDetailsFromTimetableCourseStudentMapping($getCMId, $examMYId, $month_year_id) {
		//pr($examMYId);
		//pr($getCMId);
		
		//Arrear Start
		
		/* $results = $this->CourseMapping->find('all',  array(
				'conditions' => array('CourseMapping.indicator' => 0,'CourseMapping.id' => $getCMId,
						$examMYId),
				'fields' => array('CourseMapping.id', 'CourseMapping.batch_id', 'CourseMapping.program_id',
						'CourseMapping.month_year_id', 'CourseMapping.semester_id'),
				'contain' => array(
						'Course'=>array('fields' =>array('Course.course_code','Course.course_name'),
								'order'=>array('Course.common_code'),
						),
						'StudentMark'=>array(
								'conditions' => array(
										'OR' => array(
												array('StudentMark.revaluation_status' => "0", 'StudentMark.status' => 'Fail'),
												array('StudentMark.revaluation_status' => "1", 'StudentMark.final_status'=>'Fail'),
										),
										array(
												'NOT' => array('StudentMark.course_mapping_id' => NULL)
										),
										'StudentMark.indicator' => 0
								),
								'fields'=>array(
										'StudentMark.id', 'StudentMark.student_id', 'StudentMark.month_year_id'
								),
								'Student'=>array(
										'fields' => array('Student.id','Student.registration_number', 'Student.name'),
										'conditions' => array('Student.discontinued_status' => 0),
										'Program' => array(
												'fields' => array('Program.program_name', 'Program.short_code'),
												'Academic' => array(
														'fields' => array('Academic.academic_name', 'Academic.short_code')
												),
										),'order'=>array('Student.registration_number'),
								),
						),
						'CourseStudentMapping' =>array('fields' =>array('CourseStudentMapping.id','CourseStudentMapping.new_semester_id','CourseStudentMapping.student_id'),
								'conditions'=>array('CourseStudentMapping.new_semester_id' =>$this->request->data['EA']['monthyears']),
								'Student'=>array(
										'fields' => array('Student.id','Student.registration_number', 'Student.name'),
										'conditions' => array('Student.discontinued_status' => 0),
										'Program' => array(
												'fields' => array('Program.program_name', 'Program.short_code'),
												'Academic' => array(
														'fields' => array('Academic.academic_name', 'Academic.short_code')
												),
										),'order'=>array('Student.registration_number'),
								),
						),
							
				),
		)); */
		//pr($results);
		
		$resultsArray = array();
		foreach ($getCMId as $key => $cm_id) {
			$tmpResult = $this->listArrearStudents($month_year_id, $cm_id);
			//pr($tmpResult);
			if (count($tmpResult)>0) {
				foreach ($tmpResult as $key =>$tmpArray) {
					$resultsArray[$tmpArray['Student']['id']] =
					array(
							"registration_number" => $tmpArray['Student']['registration_number'],
							"name" => $tmpArray['Student']['name'],
							"course_code" => $tmpArray['Course']['course_code'],
							"course_name" => $tmpArray['Course']['course_name'],
							"course" => $tmpArray['Academic']['short_code'],
							"branch" => $tmpArray['Program']['short_code'],
					);
				}
				//echo count($resultsArray);
				//pr($resultsArray);
				
			}
			//pr($this->listNonArrearStudents($month_year_id, $cm_id)); die;
				
			$tmpResult = $this->CourseMapping->find('all',  array(
					'conditions' => array('CourseMapping.indicator' => 0,'CourseMapping.id' => $cm_id,
							$examMYId),
					'fields' => array('CourseMapping.id', 'CourseMapping.batch_id', 'CourseMapping.program_id',
							'CourseMapping.month_year_id', 'CourseMapping.semester_id'),
					'contain' => array(
							'Course'=>array('fields' =>array('Course.course_code','Course.course_name'),
									'order'=>array('Course.common_code'),
							),
							'CourseStudentMapping' =>array('fields' =>array('CourseStudentMapping.id','CourseStudentMapping.new_semester_id','CourseStudentMapping.student_id'),
									'conditions'=>array('CourseStudentMapping.new_semester_id' =>$this->request->data['EA']['monthyears']),
									'Student'=>array(
											'fields' => array('Student.id','Student.registration_number', 'Student.name'),
											'conditions' => array('Student.discontinued_status' => 0),
											'Program' => array(
													'fields' => array('Program.program_name', 'Program.short_code'),
													'Academic' => array(
															'fields' => array('Academic.academic_name', 'Academic.short_code')
													),
											),'order'=>array('Student.registration_number'),
									),
							),
		
					),
			));
			//pr($tmpResult);
			if (count($tmpResult)>0) {
				foreach ($tmpResult as $student){
					for($a=0;$a<count($student['CourseStudentMapping']);$a++){
						if(isset($student['CourseStudentMapping'][$a]['Student']['registration_number'])){
							$resultsArray[$student['CourseStudentMapping'][$a]['Student']['id']] =
							array(
									"registration_number" => $student['CourseStudentMapping'][$a]['Student']['registration_number'],
									"name" => $student['CourseStudentMapping'][$a]['Student']['name'],
									"course_code" => $student['Course']['course_code'],
									"course_name" => $student['Course']['course_name'],
									"course" => $student['CourseStudentMapping'][$a]['Student']['Program']['Academic']['short_code'],
									"branch" => $student['CourseStudentMapping'][$a]['Student']['Program']['short_code'],
							);
						}
					}
				}
			}
		}
		//pr($resultsArray);
		/* $resultsArray = array();
		foreach ($results as $student){
			for($a=0;$a<count($student['StudentMark']);$a++){
				if(isset($student['StudentMark'][$a]['Student']['registration_number'])){
						
					$resultsArray[$student['StudentMark'][$a]['Student']['id']] =
					array(
							"registration_number" => $student['StudentMark'][$a]['Student']['registration_number'],
							"name" => $student['StudentMark'][$a]['Student']['name'],
							"course_code" => $student['Course']['course_code'],
							"course_name" => $student['Course']['course_name'],
							"course" => $student['StudentMark'][$a]['Student']['Program']['Academic']['short_code'],
							"branch" => $student['StudentMark'][$a]['Student']['Program']['short_code'],
					);
				}
		
			}
			for($a=0;$a<count($student['CourseStudentMapping']);$a++){
				if(isset($student['CourseStudentMapping'][$a]['Student']['registration_number'])){
					$resultsArray[$student['CourseStudentMapping'][$a]['Student']['id']] =
					array(
							"registration_number" => $student['CourseStudentMapping'][$a]['Student']['registration_number'],
							"name" => $student['CourseStudentMapping'][$a]['Student']['name'],
							"course_code" => $student['Course']['course_code'],
							"course_name" => $student['Course']['course_name'],
							"course" => $student['CourseStudentMapping'][$a]['Student']['Program']['Academic']['short_code'],
							"branch" => $student['CourseStudentMapping'][$a]['Student']['Program']['short_code'],
					);
				}
			}
		} */
		$results = $this->array_orderby($resultsArray,'course_code', SORT_ASC, 'registration_number', SORT_ASC);
		
		$courseCodeCounts = array_count_values(array_column($results, 'course_code'));
		//pr($courseCodeCounts);
		arsort($courseCodeCounts);
		//pr($courseCodeCounts);
		
		$arrayVar = array();
		foreach($courseCodeCounts as $key=>$val){
			$arrayVar[] = $key;
		}
		//pr($arrayVar);
		
		$this->totWeight = $arrayVar;
		usort($results, array($this,'totStrength'));
		//pr($results);
		
		$results = $this->group_assoc($results, 'course_code');
		
		foreach ($results as $course_code => $group_result) {
			usort($group_result, array($this,'ExamRegSort'));
			$results[$course_code] = $group_result;
		}
		//pr($results);
		return $results;
	}
	
	public function group_assoc($array, $key) {
		$return = array();
		foreach($array as $v) {
			$return[$v[$key]][] = $v;
		}
		return $return;
	}
	
	public function foilCardExcelArrear($results) {
		
		$phpExcel = new PHPExcel();
		$phpExcel->setActiveSheetIndex(0);
		$sheet = $phpExcel->getActiveSheet();
		$sheet->getRowDimension('1')->setRowHeight('18');
		$sheet->setTitle("Attendance_Foil_Card_Arrear");
			
		$sheet->setCellValue("A1", "REGISTER NUMBER");
		$sheet->setCellValue("B1", "STUDENT NAME");
		$sheet->setCellValue("C1", "COURSE CODE");
		$sheet->setCellValue("D1", "COURSE NAME");
		$sheet->setCellValue("E1", "COURSE");
		$sheet->setCellValue("F1", "BRANCH");
		
		$i=2;
		foreach ($results as $course_code => $array) {
			foreach ($array as $key => $value) {
				$sheet->getRowDimension($i)->setRowHeight('18');
				$sheet->setCellValue('A'.$i, $value['registration_number']);
				$sheet->setCellValue('B'.$i, $value['name']);
				$sheet->setCellValue('C'.$i, $value['course_code']);
				$sheet->setCellValue('D'.$i, $value['course_name']);
				$sheet->setCellValue('E'.$i, $value['course']);
				$sheet->setCellValue('F'.$i, $value['branch']);
				$i++;
			}
		}
		
		$download_filename="Attendance_Sheet_Foil_Card_Arrear".date('d-M-Y h:i:s');
		header("Content-Type: application/vnd.ms-excel");
		header("Content-Disposition: attachment; filename=\"$download_filename.xls\"");
		header("Cache-Control: max-age=0");
		$objWriter = PHPExcel_IOFactory::createWriter($phpExcel, "Excel5");
		$objWriter->save("php://output");
		exit;
		
	}
	
	public function foilCardExcelRegular($results) {
		$phpExcel = new PHPExcel();
		$phpExcel->setActiveSheetIndex(0);
		$sheet = $phpExcel->getActiveSheet();
		$sheet->getRowDimension('1')->setRowHeight('18');
		$sheet->setTitle("Attendance_Foil_Card_Regular");
		
		$sheet->setCellValue("A1", "REGISTER NUMBER");
		$sheet->setCellValue("B1", "STUDENT NAME");
		$sheet->setCellValue("C1", "COURSE CODE");
		$sheet->setCellValue("D1", "COURSE NAME");
		$sheet->setCellValue("E1", "PROGRAM");
		$sheet->setCellValue("F1", "BATCH");
		$sheet->setCellValue("G1", "BRANCH");
		$sheet->setCellValue("H1", "COURSE");
	
		$i=2;
		foreach ($results as $key => $value) {
			$sheet->getRowDimension($i)->setRowHeight('18');
			$sheet->setCellValue('A'.$i, $value['registration_number']);
			$sheet->setCellValue('B'.$i, $value['name']);
			$sheet->setCellValue('C'.$i, $value['course_code']);
			$sheet->setCellValue('D'.$i, $value['course_name']);
			$sheet->setCellValue('E'.$i, $value['program']);
			$sheet->setCellValue('F'.$i, $value['batch']);
			$sheet->setCellValue('G'.$i, $value['branch']);
			$sheet->setCellValue('H'.$i, $value['course']);
			$i++;
		}
	
		$download_filename="Attendance_Sheet_Foil_Card_Regular_".date('d-M-Y h:i:s');
		header("Content-Type: application/vnd.ms-excel");
		header("Content-Disposition: attachment; filename=\"$download_filename.xls\"");
		header("Cache-Control: max-age=0");
		$objWriter = PHPExcel_IOFactory::createWriter($phpExcel, "Excel5");
		$objWriter->save("php://output");
		exit;
	}
	
	public function caeReportExcel($results, $cmIdArray, $monthYearId, $caeNo) {
		//pr($results); pr($cmIdArray);
		
		$courseMappingDetails = $this->CourseMapping->retrieveCourseDetails($cmIdArray, $monthYearId);
		//pr($courseMappingDetails);
		$txtMonthYear = $this->getMonthYearName($monthYearId);
		//$studentArray = $this->Student->getStudentsInfoWithBatchId($batchId);
		//pr($studentArray);
		
		$row = 1; // 1-based index
		$col = 0;
		$phpExcel = new PHPExcel();
		$phpExcel->setActiveSheetIndex(0);
		$sheet = $phpExcel->getActiveSheet();
		$sheet->getRowDimension('1')->setRowHeight('18');
		$sheet->setTitle("CAE_Mark_Data");
		
		$sheet->setCellValueByColumnAndRow($col, $row, "CAE NUMBER");$col++;
		$sheet->setCellValueByColumnAndRow($col, $row, "MONTH YEAR");$col++;
		$sheet->setCellValueByColumnAndRow($col, $row, "BATCH");$col++;
		$sheet->setCellValueByColumnAndRow($col, $row, "ACADEMIC");$col++;
		$sheet->setCellValueByColumnAndRow($col, $row, "PROGRAM");$col++;
		$sheet->setCellValueByColumnAndRow($col, $row, "REGISTER NUMBER");$col++;
		$sheet->setCellValueByColumnAndRow($col, $row, "STUDENT NAME");$col++;
		$sheet->setCellValueByColumnAndRow($col, $row, "COURSE CODE");$col++;
		$sheet->setCellValueByColumnAndRow($col, $row, "COURSE NAME");$col++;
		$sheet->setCellValueByColumnAndRow($col, $row, "CAE");$col++;
		$sheet->setCellValueByColumnAndRow($col, $row, "MAX COURSE MARK");$col++;
		$sheet->setCellValueByColumnAndRow($col, $row, "MAX CAE MARK");$col++;
		$row++;
		//pr($results);
		
		foreach ($results as $student_id => $array) { 
			foreach ($array as $cm_id => $marks) {
				$stu = $this->Student->studentDetails($student_id);
				$col = 0;
				$academic="";
				$batch=$stu[0]['Batch']['batch_from']."-".$stu[0]['Batch']['batch_to'];
				if ($stu[0]['Batch']['academic'] == "JUN") $batch=$batch." ".$academic;
				$sheet->getRowDimension($row)->setRowHeight('18');
				$sheet->setCellValueByColumnAndRow($col, $row, $caeNo);$col++;
				$sheet->setCellValueByColumnAndRow($col, $row, $txtMonthYear);$col++;
				$sheet->setCellValueByColumnAndRow($col, $row, $batch);$col++;
				$sheet->setCellValueByColumnAndRow($col, $row, $stu[0]['Academic']['short_code']);$col++;
				$sheet->setCellValueByColumnAndRow($col, $row, $stu[0]['Program']['short_code']);$col++;
				$sheet->setCellValueByColumnAndRow($col, $row, $stu[0]['Student']['registration_number']);$col++;
				$sheet->setCellValueByColumnAndRow($col, $row, $stu[0]['Student']['name']);$col++;
				$sheet->setCellValueByColumnAndRow($col, $row, $courseMappingDetails[$cm_id]['course_code']);$col++;
				$sheet->setCellValueByColumnAndRow($col, $row, $courseMappingDetails[$cm_id]['course_name']);$col++;
				$sheet->setCellValueByColumnAndRow($col, $row, $marks);$col++;
				$sheet->setCellValueByColumnAndRow($col, $row, $courseMappingDetails[$cm_id]['course_max_marks']);$col++;
				$sheet->setCellValueByColumnAndRow($col, $row, $courseMappingDetails[$cm_id]['max_cae_mark']);
				$row++;
				
			}
		}
		
		$download_filename="CAE_Mark_Data-".date('d-M-Y h:i:s');
		header("Content-Type: application/vnd.ms-excel");
		header("Content-Disposition: attachment; filename=\"$download_filename.xls\"");
		header("Cache-Control: max-age=0");
		$objWriter = PHPExcel_IOFactory::createWriter($phpExcel, "Excel5");
		$objWriter->save("php://output");
		exit;
	}
	
	/* SELECT StudentMark.id, StudentMark.course_mapping_id, Course.course_code, StudentMark.month_year_id,
	StudentMark.status, StudentMark.final_status, StudentMark.revaluation_status,
	StudentMark.marks, StudentMark.final_marks, CourseMapping.month_year_id, CourseMapping.semester_id
	FROM student_marks StudentMark JOIN students Student ON StudentMark.student_id = Student.id
	JOIN course_mappings CourseMapping ON StudentMark.course_mapping_id=CourseMapping.id
	JOIN courses Course ON CourseMapping.course_id=Course.id
	WHERE StudentMark.id
	IN (SELECT max( id ) FROM student_marks sm1 WHERE StudentMark.student_id = sm1.student_id AND
	sm1.month_year_id <= $month_year_id GROUP BY sm1.student_id, sm1.course_mapping_id ORDER BY sm1.id DESC)
	AND StudentMark.month_year_id <= $month_year_id AND StudentMark.student_id = $student_id
	AND Student.discontinued_status=0
	ORDER BY StudentMark.student_id ASC */
			
	protected function getUpToDateDataOFAStudent($student_id, $month_year_id) {
		$results = $this->StudentMark->query("SELECT distinct(StudentMark.course_mapping_id), StudentMark.id, StudentMark.student_id,  
				        Course.course_code, StudentMark.month_year_id, 
				        StudentMark.status, StudentMark.final_status, StudentMark.revaluation_status, 
				        StudentMark.marks, StudentMark.final_marks, CourseMapping.month_year_id, CourseMapping.semester_id
				        FROM student_marks StudentMark JOIN students Student ON StudentMark.student_id = Student.id 
				        JOIN course_mappings CourseMapping ON StudentMark.course_mapping_id=CourseMapping.id 
				        JOIN courses Course ON CourseMapping.course_id=Course.id 
				        WHERE StudentMark.id
				        IN (SELECT max( id ) FROM student_marks sm1 WHERE StudentMark.student_id = sm1.student_id AND
				        sm1.month_year_id <= $month_year_id GROUP BY sm1.student_id, sm1.course_mapping_id ORDER BY sm1.id DESC)
				        AND StudentMark.month_year_id <= $month_year_id AND StudentMark.student_id = $student_id
				        AND Student.discontinued_status=0 
				        ORDER BY StudentMark.student_id ASC
				");
		/* $dbo = $this->CourseMapping->getDatasource();
		$logs = $dbo->getLog();
		$lastLog = end($logs['log']);
		echo $lastLog['query']; */
		return $results;
	}
	
	public function getCourseIdFromCmId($cm_id) {
		$conditions= array();
		$conditions['CourseMapping.id']=$cm_id;
		$results = $this->CourseMapping->find("all", array(
				'conditions'=>$conditions,
				'fields'=>array('CourseMapping.id'),
				'contain'=>array(
						'Course'=>array(
								'fields'=>array('Course.id')
						),
				)
		));
		//pr($results);
		return $results;		
	}
	
	
}