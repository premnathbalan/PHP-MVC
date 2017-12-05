<?php
App::uses('AppModel', 'Model');
/**
 * Cae Model
 *
 * @property CourseMapping $CourseMapping
 * @property Semester $Semester
 * @property MonthYear $MonthYear
 */
class CaeProject extends AppModel {

/**
 * Display field
 *
 * @var string
 */
	public $displayField = 'id';

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
		'Lecturer' => array(
			'className' => 'Lecturer',
			'foreignKey' => 'lecturer_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		),
		'User' => array(
				'className' => 'User',
				'foreignKey' => 'created_by',
				'conditions' => '',
				'fields' => '',
				'order' => ''
		),

		'ModifiedUser' => array(
				'className' => 'ModifiedUser',
				'foreignKey' => 'modified_by',
				'conditions' => '',
				'fields' => '',
				'order' => ''
		)
	);
	
	public $hasMany = array(		
		'ProjectReview' => array(
			'className' => 'ProjectReview',
			'foreignKey' => 'cae_project_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		),
	);
	
	public function checkIfCaesExists($courseMappingArray) {
		$caeFinalResult = array();
		$assessemnt_count = 0;
		$marks_status=0;
		$approval_status=0;
		$add_status=0;
		foreach ($courseMappingArray as $cmId => $courseCode) {
			$caeExists = $this->find('all', array(
					'conditions'=>array(
							'CaeProject.course_mapping_id' => $cmId,
							'CaeProject.assessment_type' => 'Review',
					),
					'fields' => array('CaeProject.*'),
					'recursive' => 0
			));
			$caeResult = $this->manipulateCaeExists($caeExists);
			$assessemnt_count = $assessemnt_count+$caeResult['caeCount'];
			$marks_status = $marks_status+$caeResult['marks_status'];
			$approval_status = $approval_status+$caeResult['approval_status'];
			$add_status = $add_status+$caeResult['add_status'];
		}
		$caeFinalResult['assessment_count'] = $assessemnt_count;
		$caeFinalResult['marks_status'] = $marks_status;
		$caeFinalResult['approval_status'] = $approval_status;
		$caeFinalResult['add_status'] = $add_status;
		return $caeFinalResult;
	}
	
	public function manipulateCaeExists($caeExists) {
		$caeResult = array();
		$caeCount = 0;
		$marks_status=0;
		$approval_status=0;
		$add_status=0;
		foreach ($caeExists as $key => $caePractical) {
			$caeCount = $caeCount+1;
			if($caePractical['CaeProject']['marks_status'] == "Entered") {
				$marks_status = $marks_status+1;
			}
			if($caePractical['CaeProject']['approval_status'] == 1) {
				$approval_status = $approval_status+1;
			}
			if($caePractical['CaeProject']['add_status'] == 1) {
				$add_status = $add_status+1;
			}
		}
		$caeResult['caeCount'] = $caeCount;
		$caeResult['marks_status'] = $marks_status;
		$caeResult['approval_status'] = $approval_status;
		$caeResult['add_status'] = $add_status;
		return $caeResult;
	}
}
