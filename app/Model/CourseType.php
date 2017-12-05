<?php
App::uses('AppModel', 'Model');
/**
 * CourseType Model
 *
 * @property Course $Course
 */
class CourseType extends AppModel {

/**
 * Display field
 *
 * @var string
 */
	public $displayField = 'course_type';
	public $validate = array(			'course_type' => array(				'nonEmpty' => array(					'rule' => array('notBlank'),					'message' => 'Choose a month',					'allowEmpty' => false				),			'rule1' => array(							'rule' => array('checkAvailability'),							'on' => 'create',							'message'=>"Record Already Exist"						),						'rule2' => array(							'rule' => array('updateCheckAvailability'),							'on' => 'update',							'message'=>"Record Already Exist"						)		),	);
			public $belongsTo = array(			'User' => array(				'className' => 'User',				'foreignKey' => 'created_by',				'conditions' => '',				'fields' => '',				'order' => ''			),		'ModifiedUser' => array(				'className' => 'ModifiedUser',				'foreignKey' => 'modified_by',				'conditions' => '',				'fields' => '',			'order' => ''			)		);
/**
 * hasMany associations
 *
 * @var array
 */
	public $hasMany = array(
		'Course' => array(
			'className' => 'Course',
			'foreignKey' => 'course_type_id',
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
	public function checkAvailability($data){		$course_type = $this->data['CourseType']['course_type'];		$results = $this->find("first",array('conditions'=>array("CourseType.course_type"=>$course_type),'recursive'=>-1,'fields'=>array("CourseType.id")));		if($results){			return false;		}else{			return true;		}		}			public function updateCheckAvailability($data){			$id = $this->data['CourseType']['id'];		$course_type= $this->data['CourseType']['course_type'];		$results = $this->find("first",array('conditions'=>array("CourseType.course_type"=>$course_type,"CourseType.id !="=>$id),'recursive'=>-1,'fields'=>array("CourseType.id")));		if($results){			return false;		}else{			return true;		}	}	public function findCourseTypeById($course_type_id) {		$results = $this->find("first",array(				'conditions'=>array(						"CourseType.id"=>$course_type_id,				),				'fields'=>array("CourseType.id", "CourseType.course_type"),				'recursive'=>-1		));		if($results){			//pr($results);			return $results;		}else{			return false;		}	}
}
