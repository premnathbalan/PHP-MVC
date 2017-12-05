<?php
App::uses('AppModel', 'Model');
/**
 * PhdStudent Model
 *
 * @property Faculty $Faculty
 * @property Thesi $Thesi
 * @property Discipline $Discipline
 * @property Supervisor $Supervisor
 * @property Month $Month
 * @property MonthYear $MonthYear
 */
class PhdStudent extends AppModel {

/**
 * Display field
 *
 * @var string
 */
	public $displayField = 'name';


	// The Associations below have been created with all possible keys, those that are not needed can be removed

/**
 * belongsTo associations
 *
 * @var array
 */
	public $belongsTo = array(
		/* 'Faculty' => array(
			'className' => 'Faculty',
			'foreignKey' => 'faculty_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		), */
		'Title' => array(
			'className' => 'Title',
			'foreignKey' => 'title_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		),
		'Area' => array(
			'className' => 'Area',
			'foreignKey' => 'area_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		),
		'Supervisor' => array(
			'className' => 'Supervisor',
			'foreignKey' => 'supervisor_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		), 
		/* 'Month' => array(
			'className' => 'Month',
			'foreignKey' => 'month_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		), */
		'MonthYear' => array(
			'className' => 'MonthYear',
			'foreignKey' => 'month_year_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		)
	);
	
	public $hasAndBelongsToMany = array(
			'PhdCourse' => array(
					'className' => 'PhdCourse',
					'joinTable' => 'phd_course_student_mappings',
					'foreignKey' => 'phd_course_id',
					'associationForeignKey' => 'phd_student_id',
					'unique' => 'keepExisting',
					'conditions' => '',
					'fields' => '',
					'order' => '',
					'limit' => '',
					'offset' => '',
					'finderQuery' => '',
			)
	);
	
	public $hasMany = array(
		'PhdCourseStudentMapping' => array(
			'className' => 'PhdCourseStudentMapping',
			'foreignKey' => 'phd_student_id',
			'dependent' => false,
			'conditions' => '',
			'fields' => '',
			'order' => '',
			'limit' => '',
			'offset' => '',
			'exclusive' => '',
			'finderQuery' => '',
			'counterQuery' => ''
		),
	);
	public function getStudentId($registration_number) {
		$options = array(
				'conditions' => array(
						array('PhdStudent.registration_number' => $registration_number,
								'PhdStudent.discontinued_status' => 0
						)
				),
				'contain'=>array(
						'Title'=>array(
								'fields'=>array('Title.name')
						),
						'Area'=>array(
								'fields'=>array('Area.name')
						),
						'PhdCourseStudentMapping'=>array(
								'fields'=>array('PhdCourseStudentMapping.id', 'PhdCourseStudentMapping.phd_student_id', 
										'PhdCourseStudentMapping.phd_course_id', 'PhdCourseStudentMapping.month_year_id',),
								'PhdCourse'=>array(
										'fields'=>array('PhdCourse.id', 'PhdCourse.course_name', 'PhdCourse.course_max_marks',
												'PhdCourse.total_min_pass_percent', 'PhdCourse.course_code'
										)
								),
								'PhdStudentMark'=>array(
										'fields'=>array('PhdStudentMark.id', 'PhdStudentMark.phd_course_student_mapping_id', 
												'PhdStudentMark.month_year_id', 'PhdStudentMark.marks',
												'PhdStudentMark.status',
										),
										'order'=>'PhdStudentMark.month_year_id DESC'
								)
						),
				)
		);
		$students = $this->find("all", $options);
		//pr($students);
		return $students;
	}
}
