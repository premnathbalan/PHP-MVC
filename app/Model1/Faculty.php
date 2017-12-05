<?php
App::uses('AppModel', 'Model');
/**
 * Faculty Model
 *
 */
class Faculty extends AppModel {

/**
 * Display field
 *
 * @var string
 */
	public $displayField = 'faculty_name';

/**
 * Validation rules
 *
 * @var array
 */
	public $validate = array(
		'faculty_name' => array(
			'notBlank' => array(
				'rule' => array('notBlank'),
				'message' => 'Choose Faculty Name'
			),
		),
		'faculty_name_tamil' => array(
			'notBlank' => array(
				'rule' => array('notBlank'),
				'message' => 'Choose Faculty Tamil Name',
			),
			'rule1' => array(
				'rule' => array('checkAvailability'),
				'on' =>"create",
				'message'=>"Record Already Exist"
			),
			'rule2' => array(
				'rule' => array('updateCheckAvailability'),
				'on' =>"update",
				'message'=>"Record Already Exist"
			)
		)		
	);
	
	public $belongsTo = array(	
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
	
	public function checkAvailability($data){
		$faculty_name 		= $this->data['Faculty']['faculty_name'];
		$faculty_name_tamil	= $this->data['Faculty']['faculty_name_tamil'];	
		$results = $this->find("first",array('conditions'=>array(
						"Faculty.faculty_name"=>$faculty_name,
						"Faculty.faculty_name_tamil"=>$faculty_name_tamil
						),'recursive'=>-1,'fields'=>array("Faculty.id"))
					);	
		if($results){
			return false;
		}else{
			return true;
		}
	}
	
	public function updateCheckAvailability($data){
		$faculty_id 		= $this->data['Faculty']['id'];
		$faculty_name 		= $this->data['Faculty']['faculty_name'];
		$faculty_name_tamil	= $this->data['Faculty']['faculty_name_tamil'];
		$results = $this->find("first",array('conditions'=>array(
				"Faculty.faculty_name"=>$faculty_name,
				"Faculty.faculty_name_tamil"=>$faculty_name_tamil,
				"Faculty.id !="=>$faculty_id
		),'recursive'=>-1,'fields'=>array("Faculty.id"))
		);
		if($results){
			return false;
		}else{
			return true;
		}
	}
}
