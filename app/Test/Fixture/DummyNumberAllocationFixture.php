<?php
/**
 * DummyNumberAllocation Fixture
 */
class DummyNumberAllocationFixture extends CakeTestFixture {

/**
 * Fields
 *
 * @var array
 */
	public $fields = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false, 'key' => 'primary'),
		'month_year_id' => array('type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false, 'key' => 'index'),
		'dummy_number' => array('type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false),
		'student_id' => array('type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false, 'key' => 'index'),
		'indicator' => array('type' => 'integer', 'null' => false, 'default' => null, 'length' => 4, 'unsigned' => false),
		'created_by' => array('type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false),
		'modified_by' => array('type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false),
		'created' => array('type' => 'timestamp', 'null' => false, 'default' => null),
		'modified' => array('type' => 'timestamp', 'null' => false, 'default' => '0000-00-00 00:00:00'),
		'indexes' => array(
			'PRIMARY' => array('column' => 'id', 'unique' => 1),
			'dummy_no_alloc' => array('column' => array('student_id', 'dummy_number'), 'unique' => 1),
			'fk_dma_my_id' => array('column' => 'month_year_id', 'unique' => 0)
		),
		'tableParameters' => array('charset' => 'latin1', 'collate' => 'latin1_swedish_ci', 'engine' => 'InnoDB')
	);

/**
 * Records
 *
 * @var array
 */
	public $records = array(
		array(
			'id' => 1,
			'month_year_id' => 1,
			'dummy_number' => 1,
			'student_id' => 1,
			'indicator' => 1,
			'created_by' => 1,
			'modified_by' => 1,
			'created' => 1167728399,
			'modified' => 1167728399
		),
	);

}
