<?php
App::uses('AppModel', 'Model');
/**
 * Batch Model
 *
 * @property BatchMode $BatchMode
 * @property DummyNumberAllocation $DummyNumberAllocation
 * @property DummyNumber $DummyNumber
 * @property MonthYear $MonthYear
 * @property PacketNumber $PacketNumber
 * @property Student $Student
 */
class Batch extends AppModel {

/**
 * Display field
 *
 * @var string
 */
	public $displayField = 'batch_from';
	public $virtualFields = array(
			'batch_period' => "IF(Batch.academic = 'JUN', CONCAT(Batch.batch_from, '-', Batch.batch_to, ' [A]'), CONCAT(Batch.batch_from, '-', Batch.batch_to))",
	);

	// The Associations below have been created with all possible keys, those that are not needed can be removed

/**
 * belongsTo associations
 *
 * @var array
 */
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

/**
 * hasMany associations
 *
 * @var array
 */
	public $hasMany = array(
		'Student' => array(
			'className' => 'Student',
			'foreignKey' => 'batch_id',
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
		'batch_mode_id' => array(
			'nonEmpty' => array(
				'rule' => array('notBlank'),
				'message' => 'Choose a batch mode',
				'allowEmpty' => false
			),
		),
		'batch_from' => array(
			'nonEmpty' => array(
					'rule' => array('notBlank'),
					'message' => 'A year is required',
					'allowEmpty' => false
			)
		),
		'batch_to' => array(
			'nonEmpty' => array(
					'rule' => array('notBlank'),
					'message' => 'A year is required',
					'allowEmpty' => false
			)
		),
		'consolidated_pub_date' => array(
				'nonEmpty' => array(
						'rule' => array('notBlank'),
						'message' => 'Select date of publishing the result',
						'allowEmpty' => false
				),
		),
		'academic' => array(
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
		)
			
	);
	
	public function checkAvailability($data){
	public function updateCheckAvailability($data){
		$batch_id 	= $this->data['Batch']['id'];
		$batch_from = $this->data['Batch']['batch_from'];
		$batch_to 	= $this->data['Batch']['batch_to'];
		$academic 	= $this->data['Batch']['academic'];
		$results 	= $this->find("first",array('conditions'=>array("Batch.batch_from"=>$batch_from,"Batch.batch_to"=>$batch_to,"Batch.academic"=>$academic,"Batch.id !="=>$batch_id),'recursive'=>-1,'fields'=>array("Batch.id")));
}