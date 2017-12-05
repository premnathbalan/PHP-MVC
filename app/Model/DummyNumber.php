<?php
App::uses('AppModel', 'Model');
/**
 * DummyNumber Model
 *
 */
class DummyNumber extends AppModel {

/**
 * Display field
 *
 * @var string
 */
	public $displayField = 'id';

/**
 * Validation rules
 *
 * @var array
 */
	public $validate = array(
		'id' => array(
			'notBlank' => array(
				'rule' => array('notBlank'),
			),
		),
	);
	
	public $belongsTo = array(
	);
	
	public $hasMany = array(
		'DummyNumberAllocation' => array(
			'className' => 'DummyNumberAllocation',
			'foreignKey' => 'dummy_number_id',
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
		'DummyRangeAllocation' => array(
				'className' => 'DummyRangeAllocation',
				'foreignKey' => 'dummy_number_id',
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
		'DummyMark' => array(
			'className' => 'DummyMark',
			'foreignKey' => 'dummy_number_id',
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
		'DummyFinalMark' => array(
				'className' => 'DummyFinalMark',
				'foreignKey' => 'dummy_number_id',
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
    'EndSemesterExam' => array(
        'className' => 'EndSemesterExam',
        'foreignKey' => 'dummy_number_id',
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
    'RevaluationDummyMark' => array(
        'className' => 'RevaluationDummyMark',
        'foreignKey' => 'dummy_number_id',
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
	
	public function getDummyNumberId($month_year_id, $start_range) {
		$result = $this->find('first', array(
				'conditions' => array('DummyNumber.month_year_id' => $month_year_id,
						'DummyNumber.start_range LIKE' => "$start_range%"
				),
				'fields' => array('DummyNumber.id', 'DummyNumber.sync_status','DummyNumber.revaluation_sync_status', 'DummyNumber.start_range', 'DummyNumber.end_range'),
				'recursive' => 0
		));
		return $result;
	}

}
