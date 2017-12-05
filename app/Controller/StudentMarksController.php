<?php
App::uses('AppController', 'Controller');
App::import('Controller', 'ContinuousAssessmentExams');
App::import('Vendor', 'PHPExcel', array('file' => 'PHPExcel'.DS.'IOFactory.php'));
/**
 * StudentMarks Controller
 *
 * @property StudentMark $StudentMark
 * @property PaginatorComponent $Paginator
 * @property FlashComponent $Flash
 * @property yComponent $y
 * @property SessionComponent $Session
 */
class StudentMarksController extends AppController {
	public $uses = array("StudentMark", "EndSemesterExam", "Program","Course", "Batch", "Month", "MonthYear", "Timetable", "CourseMapping",
			"Academic", "Batch", "MonthYear", "Student", "CourseStudentMapping","InternalExam"
	);
/**
 * Components
 *
 * @var array
 */
	public $components = array('Flash', 'Session');

/**
 * index method
 *
 * @return void
 */
	public function index() {
		$this->StudentMark->recursive = 0;
		$this->set('studentMarks');
	}

/**
 * view method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function view($id = null) {
		if (!$this->StudentMark->exists($id)) {
			throw new NotFoundException(__('Invalid student mark'));
		}
		$options = array('conditions' => array('StudentMark.' . $this->StudentMark->primaryKey => $id));
		$this->set('studentMark', $this->StudentMark->find('first', $options));
	}

/**
 * add method
 *
 * @return void
 */
	public function add() {
		if ($this->request->is('post')) {
			$this->StudentMark->create();
			if ($this->StudentMark->save($this->request->data)) {
				$this->Flash->success(__('The student mark has been saved.'));
				return $this->redirect(array('action' => 'index'));
			} else {
				$this->Flash->error(__('The student mark could not be saved. Please, try again.'));
			}
		}
		$monthYears = $this->StudentMark->MonthYear->find('list');
		$students = $this->StudentMark->Student->find('list');
		$courseMappings = $this->StudentMark->CourseMapping->find('list');
		$this->set(compact('monthYears', 'students', 'courseMappings'));
	}

/**
 * edit method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function edit($id = null) {
		if (!$this->StudentMark->exists($id)) {
			throw new NotFoundException(__('Invalid student mark'));
		}
		if ($this->request->is(array('post', 'put'))) {
			if ($this->StudentMark->save($this->request->data)) {
				$this->Flash->success(__('The student mark has been saved.'));
				return $this->redirect(array('action' => 'index'));
			} else {
				$this->Flash->error(__('The student mark could not be saved. Please, try again.'));
			}
		} else {
			$options = array('conditions' => array('StudentMark.' . $this->StudentMark->primaryKey => $id));
			$this->request->data = $this->StudentMark->find('first', $options);
		}
		$monthYears = $this->StudentMark->MonthYear->find('list');
		$students = $this->StudentMark->Student->find('list');
		$courseMappings = $this->StudentMark->CourseMapping->find('list');
		$this->set(compact('monthYears', 'students', 'courseMappings'));
	}

/**
 * delete method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function delete($id = null) {
		$this->StudentMark->id = $id;
		if (!$this->StudentMark->exists()) {
			throw new NotFoundException(__('Invalid student mark'));
		}
		$this->request->allowMethod('post', 'delete');
		if ($this->StudentMark->delete()) {
			$this->Flash->success(__('The student mark has been deleted.'));
		} else {
			$this->Flash->error(__('The student mark could not be deleted. Please, try again.'));
		}
		return $this->redirect(array('action' => 'index'));
	}
	
	public function failure() {
		$academics = $this->Program->Academic->find('list',array('order' => array('Academic.academic_name ASC')));
		$programs = $this->Program->find('list',array('order' => array('Program.program_name ASC')));
		$batches = $this->Batch->find('list', array('fields' => array('Batch.batch_period'),'order' => array('Batch.batch_to DESC')));
		$monthYears = $this->MonthYear->find('all', array('fields' => array('MonthYear.id','MonthYear.year','MonthYear.month_id','Month.month_name')));
		$monthyears=array();
		foreach ($monthYears as $key => $value) {
			$monthyears[$value['MonthYear']['id']]=$value['Month']['month_name']."-".$value['MonthYear']['year'];
		}
		$this->set(compact('academics','programs','batches','monthyears'));
	}
	
	public function failureInternalMarks($month_year_id) {
		$failureResults = $this->getFailureStudents($month_year_id);
		//pr($failureResults);
		
		$failures = array();
		foreach ($failureResults as $key => $failureResult) {
			$student_id = $failureResult['StudentMark']['student_id'];
			$smResults = $this->StudentMark->find('all', array(
								'fields' => array('StudentMark.month_year_id', 'StudentMark.student_id', 'StudentMark.course_mapping_id','StudentMark.status'),
								'conditions' => array('StudentMark.month_year_id' => $month_year_id, 'StudentMark.status'=>'Fail',
													'StudentMark.student_id'=>$student_id
								),
								'contain' => array(
									'CourseMapping'=>array('fields'=>array('CourseMapping.id'),
											'conditions'=>array('CourseMapping.indicator'=>0,
											),
											'Course' => array(
													'fields' => array('Course.course_type_id'),
													 /* 'CourseType' => array('fields' => array('CourseType.course_type')) */
											),
									)
								)
						)
				);
			pr($smResults);
			$cm_id = $smResults[0]['CourseMapping']['id'];
			$course_type_id = $smResults[0]['CourseMapping']['Course']['course_type_id'];
			$next_month_year_id = $month_year_id+1;
			SWITCH ($course_type_id) {
				CASE 1:
					$thResults = $this->InternalExam->find('all', array(
							'fields' => array('InternalExam.marks'),
							'conditions' => array('InternalExam.month_year_id'=>$month_year_id, 'InternalExam.student_id'=>$student_id, 
											'InternalExam.course_mapping_id'=>$cm_id,
							),
						)
					);
					pr($thResults);
					$cae = $thResults[0]['InternalExam']['marks'];
					$data = array();
					$data['InternalExam']['course_mapping_id']=$cm_id;
					$data['InternalExam']['month_year_id']=$next_month_year_id;
					$data['InternalExam']['student_id']=$student_id;
					$data['InternalExam']['marks']=$cae;
					$this->InternalExam->create();
					$this->InternalExam->save($data);
					break;
				CASE 2:
					$thResults = $this->CaePractical->find('all', array(
							'fields' => array('InternalExam.marks'),
							'conditions' => array('CaePractical.indicator'=>0, 'CaePractical.course_mapping_id'=>$cm_id,
							),
							'contain' => array(
								'InterPractical' => array(
									'fields' => array('InternalPractical.marks'),
									'conditions' => array('InternalPractical.student_id'=>$student_id),
								)
							)
						)
					);
					pr($thResults);
					/* $cae = $thResults[0]['InternalExam']['marks'];
					$data = array();
					$data['InternalExam']['course_mapping_id']=$cm_id;
					$data['InternalExam']['month_year_id']=$next_month_year_id;
					$data['InternalExam']['student_id']=$student_id;
					$data['InternalExam']['marks']=$cae;
					$this->InternalExam->create();
					$this->InternalExam->save($data); */
					break;
			}
			die;
		}
		
		$stuCond=array();
		$stuCond['Student.id'] = 4;

		$res = array(
				'conditions' => array('Student.id' => $failures
				),
				'fields' =>array('Student.id','Student.registration_number','Student.name'),
				'contain'=>array(
						'ExamAttendance'=>array('fields'=>array('ExamAttendance.id','ExamAttendance.attendance_status'),
								'Timetable'=>array('fields' =>array('Timetable.id', 'Timetable.course_mapping_id'),
										'conditions' => array('Timetable.indicator'=>0),
								)
						),
						'StudentWithheld'=>array('fields' =>array('StudentWithheld.student_id', 'StudentWithheld.withheld_id'),
								'Withheld'=>array('fields'=>array('Withheld.withheld_type'),
								),'conditions' => array('StudentWithheld.indicator' => 0,'StudentWithheld.month_year_id'=>$month_year_id),
						),
						'InternalPractical' => array(
								'fields' => array('InternalPractical.month_year_id', 'InternalPractical.marks', 'InternalPractical.student_id'),
								'conditions' => array('InternalPractical.month_year_id' => $month_year_id),
								'CaePractical' => array(
										'fields' => array('CaePractical.course_mapping_id')
								)
						),
						'Practical' => array(
								'fields' => array('Practical.month_year_id', 'Practical.marks', 'Practical.student_id'),
								'conditions' => array('Practical.month_year_id' => $month_year_id),
								'EsePractical' => array(
										'fields' => array('EsePractical.course_mapping_id')
								)
						),
						'InternalExam' => array(
								'fields' => array('InternalExam.month_year_id', 'InternalExam.marks', 'InternalExam.student_id', 'InternalExam.course_mapping_id'),
								'conditions' => array('InternalExam.month_year_id' => $month_year_id)
						),
						'EndSemesterExam' => array(
								'fields' => array('EndSemesterExam.month_year_id', 'EndSemesterExam.marks', 'EndSemesterExam.student_id', 'EndSemesterExam.course_mapping_id', 'EndSemesterExam.dummy_number'),
								'conditions' => array('EndSemesterExam.month_year_id' => $month_year_id)
						),
						'StudentMark' => array(
								'fields' => array('StudentMark.month_year_id', 'StudentMark.marks', 'StudentMark.student_id', 'StudentMark.course_mapping_id','StudentMark.status'),
								'conditions' => array('StudentMark.month_year_id' => $month_year_id, 'StudentMark.status'=>'Fail'),
								'CourseMapping'=>array('fields'=>array('CourseMapping.id'),
										'conditions'=>array('CourseMapping.indicator'=>0,
										),
										'Course' => array(
												'fields' => array('Course.id','Course.course_code','Course.course_max_marks'),
												 'CourseType' => array('fields' => array('CourseType.course_type'))
										),
								)
						)
				),
		);
	
		$results = $this->Student->find("all", $res);
		pr($results);
		
		die;
		$results = $this->getStudentMarksWithMonthYearId($month_year_id);
		pr($results);
		die;
		$this->set(compact('results', 'month_year_id'));
		$this->layout = false;
	}
	
	public function getFailureStudents($month_year_id) {
		$results = $this->StudentMark->find('all',array(
				'fields'=>array('DISTINCT(StudentMark.student_id)'),
				'conditions'=>array('StudentMark.status'=>'Fail'), 
				'order' => array('Student.id ASC')));
		return $results;
	}
	
	public function getStudentMarks($month_year_id) {
		
	}
	
	public function getStudentMarksWithMonthYearId($month_year_id) {
		$results = $this->StudentMark->find('all', array(
				'conditions' => array('StudentMark.month_year_id' => $month_year_id, 'StudentMark.status'=>'Fail',
										'StudentMark.student_id'=>4
				),
				'fields' => array('StudentMark.student_id', 'StudentMark.month_year_id', 'StudentMark.course_mapping_id',
						'StudentMark.marks'
						/* 'GROUP_CONCAT(StudentMark.course_mapping_id) as cm_id',
							'GROUP_CONCAT(StudentMark.marks) as marks',
		'GROUP_CONCAT(StudentMark.status) as status' */
				),
				'contain' => array(
						'CourseMapping' => array(
								'fields' => array('CourseMapping.id'),
								'Course' => array(
										'fields' => array('Course.course_code', 'Course.course_name'),
										'CourseType' => array(
												'fields' => array('CourseType.course_type')
										)
								),
								'CaePractical'=>array('fields' => array('CaePractical.course_mapping_id', 'CaePractical.marks'),
										'conditions'=>array('CaePractical.indicator'=>0),
										'InternalPractical' => array('fields'=>array('InternalPractical.student_id', 'InternalPractical.marks',))
								),
								'EsePractical'=>array('fields' => array('EsePractical.course_mapping_id', 'EsePractical.marks'),
										'conditions'=>array('EsePractical.indicator'=>0),
										'Practical' => array('fields'=>array('Practical.student_id', 'Practical.marks',))
								),
						),
						'InternalExam'=>array(
								'fields' => array('InternalExam.month_year_id', 'InternalExam.student_id',
										'InternalExam.marks'
								),
								'conditions' => array('InternalExam.month_year_id' => $month_year_id)
						),
/* 						'Student' => array(
								'fields' => array('Student.id', 'Student.registration_number', 'Student.name'),
						) */
				),
				/* 'order' => array('Student.registration_number') */
				/* 'group' => array('StudentMark.student_id') */
		));
		return $results;
	}
	
	public function cumulativeArrearReport() {
		$access_result = $this->checkPathAccess($this->Auth->user('id'));
		if (!$access_result) {
		if($this->request->is('post')) {
		if(isset($this->request->data['cumulativeArrearReport']['monthyears'])){
		$exam_month_year_id = $this->request->data['cumulativeArrearReport']['monthyears'];
		$results = $this->StudentMark->find('all', array(
				'conditions' => array(
						'OR' => array(
								array('StudentMark.revaluation_status' => "0", 'StudentMark.status' => 'Fail'),
								array('StudentMark.revaluation_status' => "1", 'StudentMark.final_status'=>'Fail'),
						),
						array(
								'NOT' => array('StudentMark.course_mapping_id' => NULL)
						),
						'StudentMark.month_year_id' => $exam_month_year_id,
						'StudentMark.indicator' => 0
				),
				'fields' => array('StudentMark.student_id', 'StudentMark.status', 'StudentMark.revaluation_status',
						'StudentMark.final_status'
				),
				'contain' => array(
						'CourseMapping' => array(
								'conditions' => array('CourseMapping.indicator' => 0),
								'fields' => array('CourseMapping.id', 'CourseMapping.batch_id', 'CourseMapping.program_id',
										'CourseMapping.month_year_id', 'CourseMapping.semester_id'),
								'Course' => array(
										'fields' => array('Course.course_code', 'Course.course_name'),
										'CourseType' => array(
												'fields' => array('CourseType.course_type')
										)
								),
						),
						'Student'=>array(
								'fields' => array('Student.registration_number', 'Student.name'),
								'conditions' => array('Student.discontinued_status' => 0),
								'Batch' => array(
										'fields' => array('Batch.batch_from', 'Batch.batch_to', 'Batch.academic')
								),
								'Program' => array(
										'fields' => array('Program.program_name', 'Program.short_code'),
										'Academic' => array(
												'fields' => array('Academic.academic_name', 'Academic.short_code')
													
										),
								),
						)
				)
		));$this->downloadCumulativeArrearReport($results);
		}}		
		
		$monthYears = $this->MonthYear->find('all', array(
				'fields' => array('MonthYear.id','MonthYear.year','MonthYear.month_id','Month.month_name'),
				'order' => array('MonthYear.id DESC'),
				'recursive'=>0
		));
		$monthyears = array();
		
		foreach ($monthYears as $key => $value) {
			$monthyears[$value['MonthYear']['id']] = $value['Month']['month_name']."-".$value['MonthYear']['year'];
		}
		$this->set(compact('monthyears'));
		}
		else {
			$this->render('../Users/access_denied');
		}
	}
	
	public function processStudentArrearFee() {
		$ContinuousAssessmentExamsController = new ContinuousAssessmentExamsController;
		$monthyears = $ContinuousAssessmentExamsController->findMonthYear();
		//pr($monthyears);
		$this->set(compact('monthyears'));
		if($this->request->is('post')) {
			if(!empty($this->request->data['StudentMark']['csv']['name'])) {
				move_uploaded_file($this->request->data['StudentMark']['csv']['tmp_name'], WWW_ROOT . '../sets_upload_received_file/' .$this->request->data['StudentMark']['csv']['name']);
				$filename = WWW_ROOT . '../sets_upload_received_file/'.$this->request->data['StudentMark']['csv']['name'];
				//echo $filename;
	
				$objPHPExcel = PHPExcel_IOFactory::load(WWW_ROOT . '../sets_upload_received_file/'.$this->request->data['StudentMark']['csv']['name']);
				$worksheet = $objPHPExcel->setActiveSheetIndex(0);
				//foreach ($objPHPExcel->getWorksheetIterator() as $worksheet) {
				$worksheetTitle     = $worksheet->getTitle();
				$highestRow         = $worksheet->getHighestRow(); // e.g. 10
				$highestColumn      = $worksheet->getHighestDataColumn(); // e.g 'F'
				$highestColumnIndex = PHPExcel_Cell::columnIndexFromString($highestColumn);
				$nrColumns = ord($highestColumn) - 64;
	
				//echo $worksheetTitle." ".$highestRow." ".$highestColumn." ".$highestColumnIndex." ".$nrColumns;
				$dataRow=2;
				for ($i=$dataRow; $i<=$highestRow; $i++) {
					$cell = $worksheet->getCellByColumnAndRow(0, $i);
					$regNumber = $cell->getValue();
					//echo $regNumber;
					$cell = $worksheet->getCellByColumnAndRow(1, $i);
					$arrear_fee_status = $cell->getValue();
					//echo $arrear_fee_status;
					$stu=$this->Student->find('first', array(
							'conditions' => array('Student.registration_number' => $regNumber, 'Student.discontinued_status' => 0),
							'fields' => array('Student.id'),
							'recursive' => 0
					));
					$student_id = $stu['Student']['id'];
					$student_arrear_data = $this->studentArrearDetail($student_id);
					if (is_array($student_arrear_data) && count($student_arrear_data)>0 && !empty($student_arrear_data)) {
						//	pr($student_arrear_data);
						foreach ($student_arrear_data as $key => $value) {
								
							$arrear_data_exists=$this->ArrearFeeStatus->find('first', array(
									'conditions' => array(
											'ArrearFeeStatus.course_mapping_id'=>$value['sm']['course_mapping_id'],
											'ArrearFeeStatus.month_year_id'=>$value['sm']['month_year_id'],
											'ArrearFeeStatus.student_id'=>$student_id,
									),
									'fields' => array('ArrearFeeStatus.id'),
									'recursive' => 0
							));
							//pr($arrear_data_exists);
							$data=array();
							$data['ArrearFeeStatus']['student_mark_id'] = $value['sm']['id'];
							$data['ArrearFeeStatus']['course_mapping_id'] = $value['sm']['course_mapping_id'];
							$data['ArrearFeeStatus']['month_year_id'] = $value['sm']['month_year_id'];
							$data['ArrearFeeStatus']['student_id'] = $student_id;
							$data['ArrearFeeStatus']['fee_status'] = $arrear_fee_status;
								
							if(isset($arrear_data_exists['ArrearFeeStatus']['id']) && $arrear_data_exists['ArrearFeeStatus']['id']>0) {
								$afs_id = $arrear_data_exists['ArrearFeeStatus']['id'];
								$data['ArrearFeeStatus']['id'] = $afs_id;
								$data['ArrearFeeStatus']['modified_by'] = $this->Auth->user('id');
								$data['ArrearFeeStatus']['modified'] = date("Y-m-d H:i:s");
							}
							else {
								$this->ArrearFeeStatus->create($data);
								$data['ArrearFeeStatus']['created_by'] = $this->Auth->user('id');
							}
							$this->ArrearFeeStatus->save($data);
						}
					}
				}
			}
		}
	}
	
}
