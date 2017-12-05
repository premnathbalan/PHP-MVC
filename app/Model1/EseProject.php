<?php
App::uses('AppModel', 'Model');
/**
 * EseProject Model
 *
 * @property MonthYear $MonthYear
 * @property CourseMapping $CourseMapping
 * @property Lecturer $Lecturer
 * @property ProjectViva $ProjectViva
 */
class EseProject extends AppModel {

/**
 * Display field
 *
 * @var string
 */
	public $displayField = 'course_mapping_id';


	// The Associations below have been created with all possible keys, those that are not needed can be removed

/**
 * belongsTo associations
 *
 * @var array
 */
	public $belongsTo = array(
		'MonthYear' => array(
			'className' => 'MonthYear',
			'foreignKey' => 'month_year_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		),
		'CourseMapping' => array(
			'className' => 'CourseMapping',
			'foreignKey' => 'course_mapping_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		),
		'Lecturer' => array(
			'className' => 'Lecturer',
			'foreignKey' => 'lecturer_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		)
	);

/**
 * hasMany associations
 *
 * @var array
 */
	public $hasMany = array(
		'ProjectViva' => array(
			'className' => 'ProjectViva',
			'foreignKey' => 'ese_project_id',
			'dependent' => false,
			'conditions' => '',
			'fields' => '',
			'order' => '',
			'limit' => '',
			'offset' => '',
			'exclusive' => '',
			'finderQuery' => '',
			'counterQuery' => ''
		)
	);

	public function checkIfEsesExists($courseMappingArray) {
		$eseFinalResult = array();
		$assessemnt_count = 0;
		$marks_status=0;
		$approval_status=0;
		$add_status=0;
		foreach ($courseMappingArray as $cmId => $courseCode) {
			$eseExists = $this->find('all', array(
					'conditions'=>array(
							'EseProject.course_mapping_id' => $cmId,
							'EseProject.assessment_type' => 'Viva',
					),
					'fields' => array('EseProject.*'),
					'recursive' => 0
			));
			$eseResult = $this->manipulateEseExists($eseExists);
			$assessemnt_count = $assessemnt_count+$eseResult['eseCount'];
			$marks_status = $marks_status+$eseResult['marks_status'];
			$approval_status = $approval_status+$eseResult['approval_status'];
			$add_status = $add_status+$eseResult['add_status'];
		}
		$eseFinalResult['assessment_count'] = $assessemnt_count;
		$eseFinalResult['marks_status'] = $marks_status;
		$eseFinalResult['approval_status'] = $approval_status;
		$eseFinalResult['add_status'] = $add_status;
		return $eseFinalResult;
	}
	
	public function manipulateEseExists($eseExists) {
		$eseResult = array();
		$eseCount = 0;
		$marks_status=0;
		$approval_status=0;
		$add_status=0;
		foreach ($eseExists as $key => $esePractical) {
			$eseCount = $eseCount+1;
			if($esePractical['EseProject']['marks_status'] == "Entered") {
				$marks_status = $marks_status+1;
			}
			if($esePractical['EseProject']['approval_status'] == 1) {
				$approval_status = $approval_status+1;
			}
			if($esePractical['EseProject']['add_status'] == 1) {
				$add_status = $add_status+1;
			}
		}
		$eseResult['eseCount'] = $eseCount;
		$eseResult['marks_status'] = $marks_status;
		$eseResult['approval_status'] = $approval_status;
		$eseResult['add_status'] = $add_status;
		return $eseResult;
	}
}
