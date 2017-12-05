<?php
App::uses('AppModel', 'Model');
/**
 * Academic Model
 *
 * @property Program $Program
 */
class CourseStudentMapping extends AppModel {

	/**
	 * Display field
	 *
	 * @var string
	 */
	public $displayField = 'student_id';

	public $belongsTo = array(
			'Student' => array(
			 'className' => 'Student',
					'foreignKey' => 'student_id',
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
			'CourseMapping' => array(
					'className' => 'CourseMapping',
					'foreignKey' => 'course_mapping_id',
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

	// The Associations below have been created with all possible keys, those that are not needed can be removed

	/**
	 * hasMany associations
	 *
	 * @var array
	 */
	public $hasMany = array(
			
	);
	
	public function getStudents($cmId, $batch_id, $program_id) {
		$courseMappingStudentArray = array();
		$options=array(
				'joins' =>
				array(
						array(
								'table' => 'course_student_mappings',
								'alias' => 'CourseStudentMapping',
								'type' => 'right',
								'foreignKey' => false,
								'conditions'=> array('CourseStudentMapping.student_id = Student.id')
						),
				),
				'conditions' => array(
						array('Student.program_id' => $program_id, 'Student.batch_id' => $batch_id,
								'CourseStudentMapping.course_mapping_id' => $cmId,
						),
				),
				'fields' => array('CourseStudentMapping.student_id', 'Student.name', 'Student.registration_number'),
				'recursive' => 0
		);
		$stuList = $this->Student->find('all', $options);
		$courseMappingStudentArray = $this->courseStudentArray($stuList);
		return $courseMappingStudentArray;
	}
	
	public function courseStudentArray($stuList) {
		$tempArray = array();
		foreach ($stuList as $key => $studentArray) {
			$tempArray[$studentArray['Student']['id']] = array(
					'reg_num' => $studentArray['Student']['registration_number'],
					'name' => $studentArray['Student']['name'],
			);
		}
		return $tempArray;
	}

	public function getEnrolledCourses($student_id) {
		$results = $this->find("all", array(
				'conditions'=>array('CourseStudentMapping.student_id'=>$student_id),
				'fields' => array('CourseStudentMapping.student_id', 'CourseStudentMapping.course_mapping_id',
						'CourseStudentMapping.new_semester_id', 'CourseStudentMapping.indicator'),
				'recursive'=>0
		));
		//pr($results);
		$csmArray = array();
		foreach ($results as $key => $value) {
			$csmArray[$student_id][$value['CourseStudentMapping']['course_mapping_id']] = $value['CourseStudentMapping']['indicator'];
		}
		return $csmArray;
	}
	
}