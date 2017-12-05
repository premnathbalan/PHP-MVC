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
	);	public $hasMany = array(			'Timetable' => array(					'className' => 'Timetable',					'foreignKey' => 'month_year_id',					'dependent' => false,					'conditions' => '',					'fields' => '',					'order' => '',					'limit' => '',					'offset' => '',					'exclusive' => '',					'finderQuery' => '',					'counterQuery' => ''			),			'StudentMark' => array(					'className' => 'StudentMark',					'foreignKey' => 'month_year_id',					'dependent' => false,					'conditions' => '',					'fields' => '',					'order' => '',					'limit' => '',					'offset' => '',					'exclusive' => '',					'finderQuery' => '',					'counterQuery' => ''			),			'StudentWithheld' => array(					'className' => 'StudentWithheld',					'foreignKey' => 'month_year_id',					'dependent' => false,					'conditions' => '',					'fields' => '',					'order' => '',					'limit' => '',					'offset' => '',					'exclusive' => '',					'finderQuery' => '',					'counterQuery' => ''			)	);
	
	public function checkAvailability($data){
		$month = $this->data['MonthYear']['month_id'];
		$year = $this->data['MonthYear']['year'];
		$results = $this->find("first",array('conditions'=>array("MonthYear.month_id"=>$month,"MonthYear.year"=>$year),'recursive'=>-1,'fields'=>array("MonthYear.id")));		if($results){
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
	}		public function chkPublishingDate($data){		$month = $this->data['MonthYear']['month_id'];		$year = $this->data['MonthYear']['year'];		$publishing_date = $this->data['MonthYear']['publishing_date'];		list($fYear,$fMonth,$fData) = explode("-",$publishing_date);			if((($fYear < $year) || ($fYear > $year+1)) || (($fYear == $year) && ($fMonth < $month))){					return false;				}else{					return true;				}	}		public function getMonthYear($month_year_id) {		$month_year = $this->find('all', array(				'conditions'=>array('MonthYear.id' => $month_year_id),				'recursive' => 0		));		$month_year = $month_year[0]['Month']['month_name']." - ".$month_year[0]['MonthYear']['year'];		return $month_year;	}		public function retrieveMonthYearIdFromMonthIdAndYear($month_id, $year) {		$monthYearId = $this->find('first', array(				'conditions' => array('MonthYear.month_id' => $month_id, 'MonthYear.year' => $year),				'fields' => array('MonthYear.id'),				'recursive' => 0		));		$monthYearId = $monthYearId['MonthYear']['id'];		return $monthYearId;	}		public function getAllMonthYears() {		$array =array();		$month_years = $this->find("all", array(				'fields' => array('MonthYear.month_id','MonthYear.year','MonthYear.id'),				'contain'=>array(						'Month'=>array('fields' =>array('Month.month_name'))				),				'order'=>array('MonthYear.id DESC')		));		//pr($month_years);		foreach ($month_years as $key => $value) {			$array[$value['MonthYear']['id']] = $value['Month']['month_name']." - ".$value['MonthYear']['year'];		}		return $array;	}
}