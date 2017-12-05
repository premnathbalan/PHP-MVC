<?php
App::uses('AppModel', 'Model');
/**
 * Designation Model
 *
 */
class Designation extends AppModel {

/**
 * Display field
 *
 * @var string
 */
	public $displayField = 'designation_name';

/**
 * Validation rules
 *
 * @var array
 */
	public $validate = array(
		'department_id' => array(
            'nonEmpty' => array(
                'rule' => array('notBlank'),
                'message' => 'Department is required',				
                'allowEmpty' => false
            )
        ),
		'designation_name' => array(
			'nonEmpty' => array(
					'rule' => array('notBlank'),
					'message' => 'Designation is required',
					'allowEmpty' => false
			),
			'rule1' => array(
				'rule' => array('checkAvailability'),
				'on' => 'create',
				'message'=>"Record Already Exist"
			),
			'rule2' => array(
				'rule' => array('updateCheckAvailability'),
				'on' => 'update',
				'message'=>"Record Already Exist"
			)
		),
	);
	
	// The Associations below have been created with all possible keys, those that are not needed can be removed
	
	/**
	 * belongsTo associations
	 *
	 * @var array
	 */
	public $belongsTo = array(
		'Department' => array(
			'className' => 'Department',
			'foreignKey' => 'department_id',
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
	
	public function checkAvailability($data){
		$designation_name = $this->data['Designation']['designation_name'];
		$department_id = $this->data['Designation']['department_id'];
		$results = $this->find("first",array('conditions'=>array(
				"Designation.designation_name"=>$designation_name,
				"Designation.department_id"=>$department_id				
				),'recursive'=>-1,'fields'=>array("Designation.id")));
		if($results){
			return false;
		}else{
			return true;
		}
	}
	
	public function updateCheckAvailability($data){
		$designation_id = $this->data['Designation']['id'];
		$designation_name = $this->data['Designation']['designation_name'];
		$department_id = $this->data['Designation']['department_id'];
		$results = $this->find("first",array('conditions'=>array(
				"Designation.designation_name"=>$designation_name,
				"Designation.department_id"=>$department_id,
				"Designation.id !="=>$designation_id
		),'recursive'=>-1,'fields'=>array("Designation.id")));
		if($results){
			return false;
		}else{
			return true;
		}
	}		
}
