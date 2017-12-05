<?php
App::uses('AppModel', 'Model');
/**
 * MonthYear Model
 *
 * @property Month $Month
 */
class MonthYear extends AppModel {

/**
 * Display field
 *
 * @var string
 */
	public $displayField = 'month_id';
	
	public $virtualFields = array(
			'month_year' => 'CONCAT(MonthYear.month_id, " ", MonthYear.year)'
	);

	public $validate = array(
			'month_id' => array(
					'nonEmpty' => array(
							'rule' => array('notBlank'),
							'message' => 'Choose a month',
							'allowEmpty' => false
					),
			),
			'year' => array(
					'nonEmpty' => array(
							'rule' => array('notBlank'),
							'message' => 'A year is required',
							'allowEmpty' => false
					),
					'between' => array(
						'rule'    => array('between', 4, 4),
						'message' => 'Maximum 4 characters'
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
		'Month' => array(
			'className' => 'Month',
			'foreignKey' => 'month_id',
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
		$month = $this->data['MonthYear']['month_id'];
		$year = $this->data['MonthYear']['year'];
		$results = $this->find("first",array('conditions'=>array("MonthYear.month_id"=>$month,"MonthYear.year"=>$year),'recursive'=>-1,'fields'=>array("MonthYear.id")));
			return false;
		}else{
			return true;
		}
	}
	
	public function updateCheckAvailability($data){
		$id = $this->data['MonthYear']['id'];
		$month = $this->data['MonthYear']['month_id'];
		$year = $this->data['MonthYear']['year'];
		$results = $this->find("first",array('conditions'=>array("MonthYear.month_id"=>$month,"MonthYear.year"=>$year,"MonthYear.id !="=>$id),'recursive'=>-1,'fields'=>array("MonthYear.id")));
		if($results){
			return false;
		}else{
			return true;
		}
	}
}