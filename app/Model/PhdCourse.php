<?php
App::uses('AppModel', 'Model');
/**
 * PhdCourse Model
 *
 */
class PhdCourse extends AppModel {

/**
 * Display field
 *
 * @var string
 */
	public $displayField = 'id';

	public $belongsTo = array(
			/* 'PhdCourseStudentMapping' => array(
					'className' => 'PhdCourseStudentMapping',
					'foreignKey' => 'phd_course_id',
					'conditions' => '',
					'fields' => '',
					'order' => ''
			), */
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
	
	public $hasAndBelongsToMany = array(
			'PhdStudent' => array(
					'className' => 'PhdStudent',
					'joinTable' => 'phd_course_student_mappings',
					'foreignKey' => 'phd_student_id',
					'associationForeignKey' => 'phd_course_id',
					'unique' => 'keepExisting',
					'conditions' => '',
					'fields' => '',
					'order' => '',
					'limit' => '',
					'offset' => '',
					'finderQuery' => '',
			)
	);
	
	public function retrievePhdCourseDetails($cm) {
		//pr($cm);
		//echo $month_year_id;
		$result = $this->find('all', array(
				'conditions' => array (
						'PhdCourse.id' => $cm
				),
				'fields' => array('PhdCourse.id', 'PhdCourse.course_name', 'PhdCourse.course_code', 'PhdCourse.course_max_marks',
				'PhdCourse.total_min_pass_percent'),
				'contain' => false
		) );
		//pr($result);
		return $result; 
	}
}
