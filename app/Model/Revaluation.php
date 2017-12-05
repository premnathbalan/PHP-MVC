<?php
App::uses('AppModel', 'Model');
/**
 * Revaluation Model
 *
 * @property CourseMapping $CourseMapping
 * @property Student $Student
 * @property MonthYear $MonthYear
 */
class Revaluation extends AppModel {

/**
 * Display field
 *
 * @var string
 */
	public $displayField = 'student_id';


	// The Associations below have been created with all possible keys, those that are not needed can be removed

/**
 * belongsTo associations
 *
 * @var array
 */
	public $belongsTo = array(
		'CourseMapping' => array(
			'className' => 'CourseMapping',
			'foreignKey' => 'course_mapping_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		),
		'Student' => array(
			'className' => 'Student',
			'foreignKey' => 'student_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		),
		'MonthYear' => array(
			'className' => 'MonthYear',
			'foreignKey' => 'month_year_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		),
		'EndSemesterExam' => array(
				'className' => 'EndSemesterExam',
				'foreignKey' => 'ese_id',
				'conditions' => '',
				'fields' => '',
				'order' => ''
		),			
	);
	
	public $hasMany = array(
		'RevaluationDummyMark' => array(
				'className' => 'RevaluationDummyMark',
				'foreignKey' => 'revaluation_id',
				'dependent'    => true,
		)
	);
	
	public $hasOne = array(
		'RevaluationExam' => array(
			'className' => 'RevaluationExam',
			'foreignKey' => 'revaluation_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		)
	);
	
	public function getRevaluationData($monthYearId=NULL, $batchId=NULL) {
		
		if ($batchId != "-") $cmCond['CourseMapping.batch_id'] = $batchId;
		else $cmCond['CourseMapping.batch_id >'] = 0;
		
		$results = $this->find('all', array(
				'conditions' => array('Revaluation.month_year_id' => $monthYearId,
						'Revaluation.indicator' => 0
				),
				'fields' => array('Revaluation.board', 'Revaluation.id', 'Revaluation.student_id'),
				'contain' => array(
						'CourseMapping'=>array('fields'=>array('CourseMapping.id','CourseMapping.batch_id'),
								'conditions'=>array('CourseMapping.indicator'=>0)
								/* 'Batch' => array(
									'fields' => array('Batch.batch_from','Batch.batch_to', 'Batch.id'),
								), */
						),
				),
				'order'=>array('Revaluation.board'=>'ASC','Revaluation.course_code'=> 'ASC','Revaluation.dummy_number'=> 'ASC')
		));
		return $results;
	}
}
