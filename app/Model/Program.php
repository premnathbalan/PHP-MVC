<?php
App::uses('AppModel', 'Model');
/**
 * Program Model
 *
 * @property Academic $Academic
 * @property Course $Course
 * @property Student $Student
 */
class Program extends AppModel {

/**
 * Display field
 *
 * @var string
 */
	public $displayField = 'program_name';


	// The Associations below have been created with all possible keys, those that are not needed can be removed

/**
 * belongsTo associations
 *
 * @var array
 */
	public $belongsTo = array(
		'Academic' => array(
			'className' => 'Academic',
			'foreignKey' => 'academic_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		),
		'Semester' => array(
			'className' => 'Semester',
			'foreignKey' => 'semester_id',
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
		),			'Faculty' => array(								'className' => 'Faculty',								'foreignKey' => 'faculty_id',								'conditions' => '',								'fields' => '',								'order' => ''						)
	);

/**
 * hasMany associations
 *
 * @var array
 */
	public $hasMany = array(
		'Student' => array(
			'className' => 'Student',
			'foreignKey' => 'program_id',
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
	
	public $validate = array(
		'faculty_id' => array(
			'nonEmpty' => array(
					'rule' => array('notBlank'),
					'message' => 'Choose a name',
					'allowEmpty' => false
			),
		),
		'program_name' => array(
			'nonEmpty' => array(
					'rule' => array('notBlank'),
					'message' => 'Choose a name',
					'allowEmpty' => false
			),
		),
		'program_description' => array(
			'nonEmpty' => array(
					'rule' => array('notBlank'),
					'message' => 'Choose a name',
					'allowEmpty' => false
			),
		),
		'short_code' => array(
			'nonEmpty' => array(
					'rule' => array('notBlank'),
					'message' => 'Choose a name',
					'allowEmpty' => false
			),
		),
		'program_name_tamil' => array(
			'nonEmpty' => array(
					'rule' => array('notBlank'),
					'message' => 'Choose a name',
					'allowEmpty' => false
			),
		),
		'credits' => array(
			'nonEmpty' => array(
					'rule' => array('notBlank'),
					'message' => 'Choose a name',
					'allowEmpty' => false
			),
		),
		'alternate_name' => array(
			'nonEmpty' => array(
					'rule' => array('notBlank'),
					'message' => 'Choose a name',
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
	
	public function checkAvailability($data){
		$academic_id = $this->data['Program']['academic_id'];
		$program_name = $this->data['Program']['program_name'];
		$short_code = $this->data['Program']['short_code'];
		$program_name_tamil = $this->data['Program']['program_name_tamil'];
		$alternate_name = $this->data['Program']['alternate_name'];
		$results = $this->find("first",array('conditions'=>array("Program.academic_id"=>$academic_id,"Program.program_name"=>$program_name,"Program.short_code"=>$short_code,"Program.program_name_tamil"=>$program_name_tamil,"Program.alternate_name"=>$alternate_name),'recursive'=>-1,'fields'=>array("Program.id")));
		
		if($results){return false;}else{return true;}
	}
	
	public function updateCheckAvailability($data){
		$program_id  = $this->data['Program']['id'];
		$academic_id = $this->data['Program']['academic_id'];
		$program_name = $this->data['Program']['program_name'];
		$short_code = $this->data['Program']['short_code'];
		$program_name_tamil = $this->data['Program']['program_name_tamil'];
		$alternate_name = $this->data['Program']['alternate_name'];
		$results = $this->find("first",array('conditions'=>array("Program.academic_id"=>$academic_id,"Program.program_name"=>$program_name,"Program.short_code"=>$short_code,"Program.program_name_tamil"=>$program_name_tamil,"Program.alternate_name"=>$alternate_name,"Program.id !="=>$program_id),'recursive'=>-1,'fields'=>array("Program.id")));
		
		if($results){return false;}else{return true;}
		
	}		public function getProgram($program_id) {		$program_name = $this->find('all', array(				'conditions'=>array('Program.id' => $program_id),				'recursive' => 0		));		$programDetails['name'] = $program_name[0]['Program']['program_name'];		$programDetails['short_code'] = $program_name[0]['Program']['short_code'];		$programDetails['academic_short_code'] = $program_name[0]['Academic']['short_code'];		$programDetails['academic_name'] = $program_name[0]['Academic']['academic_name'];		return $programDetails;	}	public function getProgramDetailsFromProgramName($program_name, $academic_id) {		$programId = $this->find('first', array(					'conditions' => array('Program.program_name' => $program_name,										'Program.academic_id' => $academic_id					),					'fields' => array('Program.id', 'Program.short_code'),					'recursive' => 0			));		$programDetails['id'] = $programId['Program']['id'];		$programDetails['short_code'] = $programId['Program']['short_code'];		return $programDetails;			}	
}
