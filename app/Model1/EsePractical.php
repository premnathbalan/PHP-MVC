<?php
App::uses('AppModel', 'Model');
/**
 * EsePractical Model
 *
 * @property CourseMapping $CourseMapping
 * @property Semester $Semester
 * @property Lecturer $Lecturer
 */
class EsePractical extends AppModel {

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
		'Practical' => array(
			'className' => 'Practical',
			'foreignKey' => 'ese_practical_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		),
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
							'EsePractical.course_mapping_id' => $cmId,
							'EsePractical.assessment_type' => 'ESE',
					),
					'fields' => array('EsePractical.*'),
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
			if($esePractical['EsePractical']['marks_status'] == "Entered") {
				$marks_status = $marks_status+1;
			}
			if($esePractical['EsePractical']['approval_status'] == 1) {
				$approval_status = $approval_status+1;
			}
			if($esePractical['EsePractical']['add_status'] == 1) {
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
