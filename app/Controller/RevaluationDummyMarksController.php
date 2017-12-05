<?php
App::uses('AppController', 'Controller');
/**
 * RevaluationDummyMarks Controller
 *
 * @property RevaluationDummyMark $RevaluationDummyMark
 * @property PaginatorComponent $Paginator
 * @property FlashComponent $Flash
 * @property SessionComponent $Session
 */
class RevaluationDummyMarksController extends AppController {
	public $uses = array("RevaluationDummyMark", "DummyNumber", "DummyRangeAllocation", "DummyNumberAllocation", "Revaluation", 
			"MonthYear", "StudentMark", "Student", "EndSemesterExam", "CourseStudentMapping", "RevaluationExam", "InternalExam");
/**
 * Components
 *
 * @var array
 */
	public $components = array('Paginator', 'Flash', 'Session');

/**
 * index method
 *
 * @return void
 */
	public function index() {
		$this->RevaluationDummyMark->recursive = 0;
		$this->set('revaluationDummyMarks', $this->Paginator->paginate());
	}

/**
 * view method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function view($id = null) {
		if (!$this->RevaluationDummyMark->exists($id)) {
			throw new NotFoundException(__('Invalid revaluation dummy mark'));
		}
		$options = array('conditions' => array('RevaluationDummyMark.' . $this->RevaluationDummyMark->primaryKey => $id));
		$this->set('revaluationDummyMark', $this->RevaluationDummyMark->find('first', $options));
	}

/**
 * add method
 *
 * @return void
 */
	public function add() {
		if ($this->request->is('post')) {
			$this->RevaluationDummyMark->create();
			if ($this->RevaluationDummyMark->save($this->request->data)) {
				$this->Flash->success(__('The revaluation dummy mark has been saved.'));
				return $this->redirect(array('action' => 'index'));
			} else {
				$this->Flash->error(__('The revaluation dummy mark could not be saved. Please, try again.'));
			}
		}
		$dummyNumbers = $this->RevaluationDummyMark->DummyNumber->find('list');
		$this->set(compact('dummyNumbers'));
	}

/**
 * edit method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function edit($id = null) {
		if (!$this->RevaluationDummyMark->exists($id)) {
			throw new NotFoundException(__('Invalid revaluation dummy mark'));
		}
		if ($this->request->is(array('post', 'put'))) {
			if ($this->RevaluationDummyMark->save($this->request->data)) {
				$this->Flash->success(__('The revaluation dummy mark has been saved.'));
				return $this->redirect(array('action' => 'index'));
			} else {
				$this->Flash->error(__('The revaluation dummy mark could not be saved. Please, try again.'));
			}
		} else {
			$options = array('conditions' => array('RevaluationDummyMark.' . $this->RevaluationDummyMark->primaryKey => $id));
			$this->request->data = $this->RevaluationDummyMark->find('first', $options);
		}
		$dummyNumbers = $this->RevaluationDummyMark->DummyNumber->find('list');
		$this->set(compact('dummyNumbers'));
	}

/**
 * delete method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function delete($id = null) {
		$this->RevaluationDummyMark->id = $id;
		if (!$this->RevaluationDummyMark->exists()) {
			throw new NotFoundException(__('Invalid revaluation dummy mark'));
		}
		$this->request->allowMethod('post', 'delete');
		if ($this->RevaluationDummyMark->delete()) {
			$this->Flash->success(__('The revaluation dummy mark has been deleted.'));
		} else {
			$this->Flash->error(__('The revaluation dummy mark could not be deleted. Please, try again.'));
		}
		return $this->redirect(array('action' => 'index'));
	}
	
	public function marks() {
		$access_result = $this->checkPathAccess($this->Auth->user('id'));
		if (!$access_result) {
			$monthyears = $this->MonthYear->getAllMonthYears();
			$this->set(compact('monthyears'));
		}
		else {
			$this->render('../Users/access_denied');
		}
	}
	
	public function searchMarks($month_year_id) {
		$revaluationArray = array();
		$result = $this->EndSemesterExam->find('all', array(
			'conditions'=>array('EndSemesterExam.revaluation_status' => 1, 'EndSemesterExam.month_year_id'=>$month_year_id),
            'fields'=>array('EndSemesterExam.dummy_number_id', "COUNT('id') as revaluation_count"),
            'group'=>array('EndSemesterExam.dummy_number_id', 'EndSemesterExam.month_year_id')
        ));
		//pr($result);
		foreach ($result as $key => $value) {
			//$revaluationArray[$value['EndSemesterExam']['dummy_number_id']] = $value[0]['revaluation_count'];
			$dummy_number_id = $value['EndSemesterExam']['dummy_number_id'];
			$dummyResult = $this->DummyNumber->find('all', array(
				'conditions' => array('DummyNumber.id'=>$dummy_number_id),
				'fields' => array('DummyNumber.start_range', 'DummyNumber.end_range', 'DummyNumber.revaluation_sync_status'),
				'contain' => false
			));
			//pr($dummyResult);
			$start_range = $dummyResult[0]['DummyNumber']['start_range'];
			$end_range = $dummyResult[0]['DummyNumber']['end_range'];
			$reval_sync_status = $dummyResult[0]['DummyNumber']['revaluation_sync_status'];
			
			$revaluationDummyMarkEntryCount1 = $this->RevaluationDummyMark->find('count', array(
				'conditions' => array('RevaluationDummyMark.dummy_number_id'=>$dummy_number_id,
						'RevaluationDummyMark.mark_entry1 !='=>''
				)	
			));
			//pr($revaluationDummyMarkEntryCount1);
			$revaluationDummyMarkEntryCount2 = $this->RevaluationDummyMark->find('count', array(
					'conditions' => array('RevaluationDummyMark.dummy_number_id'=>$dummy_number_id,
							'RevaluationDummyMark.mark_entry2 !='=>'',
							'RevaluationDummyMark.created2 !='=>'0000-00-00 00:00:00'
					)
			));
			//pr($revaluationDummyMarkEntryCount2);
			
			$revaluationArray[$value['EndSemesterExam']['dummy_number_id']] = array(
				'startRange' => $start_range,
				'endRange' => $end_range,
				'totalCount' => $value[0]['revaluation_count'],
				'markEntry1' => $revaluationDummyMarkEntryCount1,
				'markEntry2' => $revaluationDummyMarkEntryCount2,
				'revalSyncStatus' => $reval_sync_status
			);
		}
		
		$this->set('revaluationDummyMarks', $revaluationArray);
		$this->set('month_year_id', $month_year_id);
		$this->layout=false;
	}
	
	
	public function addMark1($dummy_number_id, $month_year_id) {
		if($dummy_number_id) {
			$result = array(
					'conditions' => array('EndSemesterExam.dummy_number_id' => $dummy_number_id,
							'EndSemesterExam.revaluation_status' => 1
					),
					'fields'=>array('EndSemesterExam.id', 'EndSemesterExam.student_id', 'EndSemesterExam.course_mapping_id',
							'EndSemesterExam.dummy_number'
					),
					'order' => array('EndSemesterExam.dummy_number'),
					'contain' => array(
							'Revaluation' => array(
									'fields' => array('Revaluation.id'),
									'RevaluationDummyMark' => array(
											'fields' => array(
													'RevaluationDummyMark.revaluation_id', 'RevaluationDummyMark.mark_entry1',
													'RevaluationDummyMark.mark_entry2'
											)
									)
							),
					)
			);
			$results = $this->EndSemesterExam->find("all", $result);
		}
		$courseDetails = $this->DummyRangeAllocation->find('first', array(
				'conditions' => array('DummyRangeAllocation.dummy_number_id' => $dummy_number_id),
				'fields' => array('DummyRangeAllocation.id'),
				'contain' => array(
						'Timetable'=>array('fields' =>array('Timetable.id'),
								'CourseMapping'=>array('fields'=>array('CourseMapping.id'),
										'Course'=>array('fields' =>array('Course.course_code','Course.course_name','Course.max_ese_qp_mark')),
								),
						),
						'DummyNumber' => array(
								'fields' => array('DummyNumber.start_range', 'DummyNumber.end_range')
						)
				)
		));
	
		$month_year = $this->getMonthYear($month_year_id);
		$this->set('dummy_number_id',$dummy_number_id);
		$this->set('results', $results);
		$this->set('month_year', $month_year);
		$this->set('courseDetails', $courseDetails);
	}
	
	public function editMark1($dummy_number_id, $month_year_id) {
		if($dummy_number_id) {
			$result = array(
					'conditions' => array('EndSemesterExam.dummy_number_id' => $dummy_number_id,
							'EndSemesterExam.revaluation_status' => 1
					),
					'fields'=>array('EndSemesterExam.id', 'EndSemesterExam.student_id', 'EndSemesterExam.course_mapping_id',
							'EndSemesterExam.dummy_number'
					),
					'order' => array('EndSemesterExam.dummy_number'),
					'contain' => array(
							'Revaluation' => array(
									'fields' => array('Revaluation.id'),
									'RevaluationDummyMark' => array(
											'fields' => array(
													'RevaluationDummyMark.revaluation_id', 'RevaluationDummyMark.mark_entry1',
													'RevaluationDummyMark.mark_entry2'
											)
									)
							),
					)
			);
			$results = $this->EndSemesterExam->find("all", $result);
		}
		$courseDetails = $this->DummyRangeAllocation->find('first', array(
				'conditions' => array('DummyRangeAllocation.dummy_number_id' => $dummy_number_id),
				'fields' => array('DummyRangeAllocation.id'),
				'contain' => array(
						'Timetable'=>array('fields' =>array('Timetable.id'),
								'CourseMapping'=>array('fields'=>array('CourseMapping.id'),
										'Course'=>array('fields' =>array('Course.course_code','Course.course_name','Course.max_ese_qp_mark')),
								),
						),
						'DummyNumber' => array(
								'fields' => array('DummyNumber.start_range', 'DummyNumber.end_range')
						)
				)
		));
	
		$month_year = $this->getMonthYear($month_year_id);
		$this->set('dummy_number_id',$dummy_number_id);
		$this->set('results', $results);
		$this->set('month_year', $month_year);
		$this->set('courseDetails', $courseDetails);
	}
	
	public function addMark2($dummy_number_id, $month_year_id) {
		if($dummy_number_id) {
			$result = array(
					'conditions' => array('EndSemesterExam.dummy_number_id' => $dummy_number_id,
							'EndSemesterExam.revaluation_status' => 1
					),
					'fields'=>array('EndSemesterExam.id', 'EndSemesterExam.student_id', 'EndSemesterExam.course_mapping_id',
							'EndSemesterExam.dummy_number'
					),
					'order' => array('EndSemesterExam.dummy_number'),
					'contain' => array(
							'Revaluation' => array(
									'fields' => array('Revaluation.id'),
									'RevaluationDummyMark' => array(
											'fields' => array(
													'RevaluationDummyMark.revaluation_id', 'RevaluationDummyMark.mark_entry1',
													'RevaluationDummyMark.mark_entry2'
											)
									)
							),
					)
			);
			$results = $this->EndSemesterExam->find("all", $result);
		}
		$courseDetails = $this->DummyRangeAllocation->find('first', array(
				'conditions' => array('DummyRangeAllocation.dummy_number_id' => $dummy_number_id),
				'fields' => array('DummyRangeAllocation.id'),
				'contain' => array(
						'Timetable'=>array('fields' =>array('Timetable.id'),
								'CourseMapping'=>array('fields'=>array('CourseMapping.id'),
										'Course'=>array('fields' =>array('Course.course_code','Course.course_name','Course.max_ese_qp_mark')),
								),
						),
						'DummyNumber' => array(
								'fields' => array('DummyNumber.start_range', 'DummyNumber.end_range')
						)
				)
		));
	
		$month_year = $this->getMonthYear($month_year_id);
		$this->set('dummy_number_id',$dummy_number_id);
		$this->set('results', $results);
		$this->set('month_year', $month_year);
		$this->set('courseDetails', $courseDetails);
	}
	
	public function editMark2($dummy_number_id, $month_year_id) {
		if($dummy_number_id) {
			$result = array(
					'conditions' => array('EndSemesterExam.dummy_number_id' => $dummy_number_id,
							'EndSemesterExam.revaluation_status' => 1
					),
					'fields'=>array('EndSemesterExam.id', 'EndSemesterExam.student_id', 'EndSemesterExam.course_mapping_id',
							'EndSemesterExam.dummy_number'
					),
					'order' => array('EndSemesterExam.dummy_number'),
					'contain' => array(
							'Revaluation' => array(
									'fields' => array('Revaluation.id'),
									'RevaluationDummyMark' => array(
											'fields' => array(
													'RevaluationDummyMark.revaluation_id', 'RevaluationDummyMark.mark_entry1',
													'RevaluationDummyMark.mark_entry2'
											)
									)
							),
					)
			);
			$results = $this->EndSemesterExam->find("all", $result);
		}
		$courseDetails = $this->DummyRangeAllocation->find('first', array(
				'conditions' => array('DummyRangeAllocation.dummy_number_id' => $dummy_number_id),
				'fields' => array('DummyRangeAllocation.id'),
				'contain' => array(
						'Timetable'=>array('fields' =>array('Timetable.id'),
								'CourseMapping'=>array('fields'=>array('CourseMapping.id'),
										'Course'=>array('fields' =>array('Course.course_code','Course.course_name','Course.max_ese_qp_mark')),
								),
						),
						'DummyNumber' => array(
								'fields' => array('DummyNumber.start_range', 'DummyNumber.end_range')
						)
				)
		));
	
		$month_year = $this->getMonthYear($month_year_id);
		$this->set('dummy_number_id',$dummy_number_id);
		$this->set('results', $results);
		$this->set('month_year', $month_year);
		$this->set('courseDetails', $courseDetails);
	}
	
	public function getMonthYear($month_year_id) {
		$my = $this->MonthYear->find('first', array(
				'fields'=>array('MonthYear.id', 'MonthYear.year'),
				'conditions' => array('MonthYear.id' => $month_year_id),
				'contain' => array(
						'Month' => array(
								'fields' => array('Month.month_name')
						)
				)
		));
		//pr($my);
		$month_year = $my['Month']['month_name']."-".$my['MonthYear']['year'];
		return $month_year;
	}
	
	public function dnToM($level = null, $DNId = null, $DN = null, $marks = null, $autoId = null, $revId = null){
		//echo $level."*DNId = ".$DNId."*DN=".$DN."*Marks=".$marks."*autoId= ".$autoId;
		if(($level) && ($DNId) && ($DN) && ($marks)){
			$autoGenId = array();
			if($autoId != '-'){
				$autoGenId['RevaluationDummyMark.id'] = $autoId;
			}
				
			$data = array();
			$data['RevaluationDummyMark']['revaluation_id']	= $revId;
			$data['RevaluationDummyMark']['dummy_number_id']	= $DNId;
			$data['RevaluationDummyMark']['dummy_number']	  	= $DN;
			$data['RevaluationDummyMark']['mark_entry'.$level]	= $marks;
				
			if($level == 1){
				unset($data['RevaluationDummyMark']['mark_entry2']);
				$data['RevaluationDummyMark']['created']	= date('Y-m-d h:i:s');
				$data['RevaluationDummyMark']['mark'.$level.'_created_by'] = $this->Auth->user('id');
			}
			if($level == 2){
				unset($data['RevaluationDummyMark']['mark_entry1']);
				$data['RevaluationDummyMark']['created2']	= date('Y-m-d h:i:s');
				$data['RevaluationDummyMark']['mark'.$level.'_created_by'] = $this->Auth->user('id');
			}
			$chk =$this->RevaluationDummyMark->find('first',
					array('conditions' => array(
							'RevaluationDummyMark.dummy_number_id' => $DNId,
							'RevaluationDummyMark.dummy_number' =>$DN,$autoGenId
					),'recursive'=>1));
				
			if($chk){
				$data['RevaluationDummyMark']['id'] = $chk['RevaluationDummyMark']['id'];
			}else{
				$this->RevaluationDummyMark->create();
			} 
			try{
				if($this->RevaluationDummyMark->save($data)){
					//echo "Record saved";
				}
			}
			catch(Exception $e){
				echo "Invalid Mark Entry";die;//$e->getMessage();
			}
		}else{
			echo "Invalid Mark Entry Process.";die;
		}
	}
	
	public function approval() {
		$access_result = $this->checkPathAccess($this->Auth->user('id'));
		if (!$access_result) {
		$monthYears = $this->MonthYear->getAllMonthYears();
		$this->set(compact('monthYears'));
		
		if ($this->request->is('post')) {
			//pr($this->data);
			if ($this->request->data['RevaluationDummyApproval']['dummy_number']=="") {
				$this->Flash->error('Please enter the dummy number start range!');
			}
			else {
				//pr($this->data);
				$bool = false;
				$dummyDetails = $this->request->data['DummyApproval']['mark'];
				$dummy_number_id = $this->request->data['dummy_number_id'];
				foreach ($dummyDetails as $id => $marks) {
					$data = array();
					$data['RevaluationDummyMark']['id'] = $id;
					$data['RevaluationDummyMark']['mark_entry1'] = $marks;
					$data['RevaluationDummyMark']['mark_entry2'] = $marks;
					$data['RevaluationDummyMark']['modified_by'] = $this->Auth->user('id');
					$data['RevaluationDummyMark']['modified'] = date("Y-m-d H:i:s");
					$this->RevaluationDummyMark->save($data);
					$bool=true;
				}
				if($bool) {
					$start_range = $this->request->data['RevaluationDummyApproval']['dummy_number'];
					$month_year_id = $this->request->data['RevaluationDummyApproval']['month_year_id'];
					$this->moveDummyMarks($dummy_number_id);
					$this->Flash->success('Marks synchronized for dummy numbers!');
				}
			}			
		}
		}
		else {
			$this->render('../Users/access_denied');
		}
	}
	
	public function getDummyDiffMarks($month_year_id, $start_range, $ajax) {
	
		$dummy_result = $this->DummyNumber->getDummyNumberId($month_year_id, $start_range);
		//pr($dummy_result);
		
		$sync_status = $dummy_result['DummyNumber']['revaluation_sync_status'];
		$dummy_number_id = $dummy_result['DummyNumber']['id'];
		//echo $sync_status;
		//echo $moderate_status;
		/* 	if ($sync_status == 1) {
		$msg = "No more synchronization allowed as Dummy Marks are synchronized!!!";
		echo "<script>alert('".$msg."');</script>"; */
			
		/* if($this->RequestHandler->isAjax()) {
		 return new CakeResponse(array('body'=> json_encode(array('val'=>$msg)),'status'=>500));
		 } */
		/* }
			else { */
		$filterCondition= "`(RevaluationDummyMark`.`dummy_number_id` = ".$dummy_number_id.") AND ";
		$filterCondition.="(RevaluationDummyMark.mark_entry1 <> RevaluationDummyMark.mark_entry2)";
		$result = $this->RevaluationDummyMark->find('all', array(
				'conditions' => array($filterCondition),
				'fields' => array(
						'RevaluationDummyMark.id', 'RevaluationDummyMark.dummy_number_id', 
						'RevaluationDummyMark.mark_entry1', 'RevaluationDummyMark.mark_entry2',
						'RevaluationDummyMark.dummy_number', 'RevaluationDummyMark.mark1_created_by', 
						'RevaluationDummyMark.mark2_created_by',
						'RevaluationDummyMark.modified_by', 'RevaluationDummyMark.created', 
						'RevaluationDummyMark.created2', 'RevaluationDummyMark.modified'
				)
		));
		//pr($result);
		//die;
		if ($ajax) {
			//Store the mark_entry1 from dummy_marks table to DMA for the dummy range starting with $start_range
			if (count($result) == 0) {
				$this->moveDummyMarks($dummy_number_id);
			}
			$this->set(compact('result'));
		}
		else {
			if (isset($result) && count($result) > 0) {
				return $result;
			}
		}
		//}
		$this->layout=false;
	}
	
public function moveDummyMarks($dummy_number_id) {
  
    $rdmResults = $this->DummyNumber->find('all', array(
        'conditions' => array('DummyNumber.id' => $dummy_number_id),
        'fields' => array('DummyNumber.month_year_id', 'DummyNumber.start_range', 'DummyNumber.end_range',
            'DummyNumber.mode'
        ),
        'contain' => array(
            'DummyNumberAllocation' => array('fields' => array(
                'DummyNumberAllocation.dummy_number_id', 'DummyNumberAllocation.student_id',
                'DummyNumberAllocation.dummy_number'
            )),
            'RevaluationDummyMark' => array('fields' => array(
                'RevaluationDummyMark.dummy_number_id', 'RevaluationDummyMark.dummy_number', 
                'RevaluationDummyMark.mark_entry1', 'RevaluationDummyMark.revaluation_id'
            )),
            'DummyRangeAllocation' => array(
                'fields' => array(
                    'DummyRangeAllocation.dummy_number_id', 'DummyRangeAllocation.timetable_id'),
                'Timetable' => array(
                    'fields' => array('Timetable.month_year_id', 'Timetable.course_mapping_id'),
                    'CourseMapping' => array(
                        'fields' => array('CourseMapping.course_id'),
                        'Batch' => array('fields' => array(
                            'Batch.batch_from', 'Batch.batch_to', 'Batch.academic'
                        )),
                        'Program' => array('fields' => array(
                            'Program.program_name'
                        ),
                            'Academic' => array('fields' => array(
                                'Academic.academic_name'
                            ))
                        ),
                        'Course' => array(
                            'fields' => array('Course.course_code', 'Course.common_code',
                                'Course.course_name', 'Course.course_max_marks',
                                'Course.max_ese_qp_mark', 'Course.max_ese_mark', 
                                'Course.min_ese_mark', 'Course.total_min_pass' 
                            )
                        )
                    )
                )
            ),
        )
    ));
    //pr($rdmResults);
    $month_year_id = $rdmResults[0]['DummyNumber']['month_year_id'];
    //echo $month_year_id;
    if (isset($rdmResults) && !empty($rdmResults) && count($rdmResults) > 0) {
      //echo "if"; 
      foreach ($rdmResults as $key => $result) {
        //pr($result);
        $drAllocation = $result['DummyRangeAllocation'];
        $courseMappingArray = array();
        $cmQpMark = array();
        foreach($drAllocation as $key => $drValue) {
          $courseMappingArray[] = $drValue['Timetable']['course_mapping_id'];
          $cmQpMark[$drValue['Timetable']['course_mapping_id']] = array(
              'max_qp_mark' => $drValue['Timetable']['CourseMapping']['Course']['max_ese_qp_mark'],
              'max_ese_mark' => $drValue['Timetable']['CourseMapping']['Course']['max_ese_mark'],
              'course_max_mark' => $drValue['Timetable']['CourseMapping']['Course']['course_max_marks'],
              'min_ese_pass_percent' => $drValue['Timetable']['CourseMapping']['Course']['min_ese_mark'],
              'total_min_pass' => $drValue['Timetable']['CourseMapping']['Course']['total_min_pass'],
          );
        }
      }
      //pr($courseMappingArray);
      //pr($cmQpMark);
      
      $dnAllocation = $result['DummyNumberAllocation'];
      $dna = array();
      foreach ($dnAllocation as $key => $dnaValue) {
        $dna[$dnaValue['dummy_number']] = $dnaValue['student_id'];
      }
      //pr($dna);
      
      $tmpRdm = $result['RevaluationDummyMark'];
      $rdm = array();
      $rdm_rev_id = array();
      foreach ($tmpRdm as $key => $rdmValue) {
        $rdm[$rdmValue['dummy_number']] = $rdmValue['mark_entry1'];
        $rdm_rev_id[$rdmValue['dummy_number']] = $rdmValue['revaluation_id'];
      }
      //pr($rdm);
      //pr($rdm_rev_id);

	  $intersect_dna = array_intersect_key($dna, $rdm);
      //pr($intersect_dna);
      $intersect_rev_id = array_intersect_key($dna, $rdm_rev_id);
      //pr($intersect_rev_id);
      
      $dummyCourseMappingArray = $this->getCourseMappingId($intersect_dna, $courseMappingArray);
      //pr($dummyCourseMappingArray);

      $convertedDmArray = array();
      foreach ($dummyCourseMappingArray as $dummy_number => $cm_id) {
        $courseMaxMark = $cmQpMark[$cm_id]['course_max_mark'];
        $maxQpMark = $cmQpMark[$cm_id]['max_qp_mark'];
        $maxEseMark = $cmQpMark[$cm_id]['max_ese_mark'];
        $minEsePassPercent = $cmQpMark[$cm_id]['min_ese_pass_percent'];
        $totalMinPass = $cmQpMark[$cm_id]['total_min_pass'];
        $dummyMark = $rdm[$dummy_number];
        $convertedMark = round($maxEseMark * $dummyMark / $maxQpMark);
        //echo "</br>".$maxQpMark." == ".$maxEseMark." == ".$dummyMark;
        //echo "</br>".$dummy_number." == ".$convertedMark;
        $convertedDmArray[$dummy_number] = $convertedMark;
      }
      //pr($convertedDmArray);
      
      foreach ($convertedDmArray as $dummy_number => $cMark) {
        $conditions = array('RevaluationExam.course_mapping_id' => $dummyCourseMappingArray[$dummy_number],
            'RevaluationExam.dummy_number_id' => $dummy_number_id,
            'RevaluationExam.student_id' => $dna[$dummy_number],
            'RevaluationExam.month_year_id' => $month_year_id,
            'RevaluationExam.dummy_number' => $dummy_number,
        );
        //pr($conditions);
        
        $ese_mark = $this->EndSemesterExam->find('first', array(
            'conditions' => array('EndSemesterExam.course_mapping_id' => $dummyCourseMappingArray[$dummy_number],
                'EndSemesterExam.dummy_number_id' => $dummy_number_id,
                'EndSemesterExam.student_id' => $dna[$dummy_number],
                'EndSemesterExam.month_year_id' => $month_year_id,
                'EndSemesterExam.dummy_number' => $dummy_number,
            ),
            'fields' => array(
                'EndSemesterExam.marks'
            )
        ));
        //pr($ese_mark);
        /* if ($ese_mark['EndSemesterExam']['marks'] > $cMark) {
          $final_marks = $ese_mark['EndSemesterExam']['marks'];
        }
        else 
          $final_marks = $cMark; */
        
        $student_id = $dna[$dummy_number];
        //echo $student_id."</br>";
        $internalResult = $this->InternalExam->find('first', array(
          'conditions'=>array('InternalExam.course_mapping_id' => $dummyCourseMappingArray[$dummy_number],
                    'InternalExam.student_id'=>$student_id
          ),
          'fields'=>array('InternalExam.marks'),
          'order'=>array('InternalExam.id DESC')
        ));
        //pr($internalResult); 
        if (isset($internalResult['InternalExam']['marks'])) {
          $internalMark = $internalResult['InternalExam']['marks'];
        }
        else {
          $internalMark = 0;
        }
        $total_mark = $cMark + $internalMark;
        
        $minEsePassMark = round($maxEseMark * $minEsePassPercent / 100);
        
        if ($cMark >= $minEsePassMark && $total_mark >= $totalMinPass) {
          $resStatus = "Pass";
        }
        else {
          $resStatus = "Fail";
        }
        //echo $cMark." ".$total_mark." ".$maxEseMark." ".$courseMaxMark." ".$minEsePassMark." new status".$resStatus."</br>";
        $cm_id = $dummyCourseMappingArray[$dummy_number];
        $revaluation_id = $rdm_rev_id[$dummy_number];
        //echo $internalMark." ".$final_marks." ".$total_mark."</br>";
        //Changing DFM to ESE
        if ($this->RevaluationExam->hasAny($conditions)){
          $this->RevaluationExam->query("UPDATE revaluation_exams set
                  marks='".$cMark."',
                  revaluation_marks='".$cMark."',
                  revaluation_id=".$revaluation_id.",
                  modified = '".date("Y-m-d H:i:s")."',
                  month_year_id = $month_year_id,
                  dummy_number = '".$dummy_number."',
				  total_marks = $total_mark,
                  status='".$resStatus."',
                  dummy_number_id = ".$dummy_number_id.",
                  modified_by = ".$this->Auth->user('id').",
                  reval_moderation_operator = '',
                  reval_moderation_marks = 0
                  WHERE dummy_number = ".$dummy_number." AND
                  dummy_number_id = ".$dummy_number_id
              );
          $bool = true;
        }
        else {
          $data=array();
          $data['RevaluationExam']['dummy_number_id'] = $dummy_number_id;
          $data['RevaluationExam']['revaluation_id'] = $revaluation_id;
          $data['RevaluationExam']['month_year_id'] = $month_year_id;
          $data['RevaluationExam']['dummy_number'] = $dummy_number;
          $data['RevaluationExam']['status'] = $resStatus;
          $data['RevaluationExam']['student_id'] = $dna[$dummy_number];
          $data['RevaluationExam']['course_mapping_id'] = $dummyCourseMappingArray[$dummy_number];
          $data['RevaluationExam']['marks'] = $cMark;
          $data['RevaluationExam']['revaluation_marks'] = $cMark;
          $data['RevaluationExam']['total_marks'] = $total_mark;
          $data['RevaluationExam']['created_by'] = $this->Auth->user('id');
          $this->RevaluationExam->create();
          $this->RevaluationExam->save($data);
          $bool = true;
        }
        /* $dbo = $this->RevaluationExam->getDatasource();
        $logs = $dbo->getLog();
        $lastLog = end($logs['log']);
        echo $lastLog['query']; */
      }
    }
  
    $data=array();
    $data['DummyNumber']['id'] = $dummy_number_id;
    $data['DummyNumber']['revaluation_sync_status'] = 1;
    $data['DummyNumber']['modified_by'] = $this->Auth->user('id');
    $data['DummyNumber']['modified'] = '".date("Y-m-d H:i:s")."';
    $this->DummyNumber->save($data);
  
  }
	
	public function getCourseMappingId($dna, $courseMappingArray) {
		$cmArray = array();
		foreach ($dna as $dummy_number => $student_id) {
			$cmid = $this->CourseStudentMapping->find('all', array(
					'conditions' => array('CourseStudentMapping.student_id' => $student_id,
							'CourseStudentMapping.course_mapping_id' => $courseMappingArray
					),
					'fields' => array('CourseStudentMapping.course_mapping_id')
			));
			//pr($cmid);
			$cmArray[$dummy_number] = $cmid[0]['CourseStudentMapping']['course_mapping_id'];
		}
		return $cmArray;
	}
	
}
