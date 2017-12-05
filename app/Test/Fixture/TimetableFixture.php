<?php
/**
 * Timetable Fixture
 */
class TimetableFixture extends CakeTestFixture {

/**
 * Fields
 *
 * @var array
 */
	public $fields = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false, 'key' => 'primary'),
		'month_year_id' => array('type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false, 'key' => 'index'),
		'course_mapping_id' => array('type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false, 'key' => 'index'),
		'exam_date' => array('type' => 'date', 'null' => false, 'default' => null),
		'created_by' => array('type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false, 'key' => 'index'),
		'modified_by' => array('type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false, 'key' => 'index'),
		'created' => array('type' => 'timestamp', 'null' => false, 'default' => null),
		'modified' => array('type' => 'timestamp', 'null' => false, 'default' => '0000-00-00 00:00:00'),
		'indexes' => array(
			'PRIMARY' => array('column' => 'id', 'unique' => 1),
			'uk_tt_myid_cmid' => array('column' => array('month_year_id', 'course_mapping_id'), 'unique' => 1),
			'fk_tt_crse_mapping_id' => array('column' => 'course_mapping_id', 'unique' => 0),
			'fk_tt_month_year_id' => array('column' => 'month_year_id', 'unique' => 0),
			'fk_tt_created_by' => array('column' => 'created_by', 'unique' => 0),
			'fk_tt_modified_by' => array('column' => 'modified_by', 'unique' => 0)
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
			'course_mapping_id' => 1,
			'exam_date' => '2016-01-27',
			'created_by' => 1,
			'modified_by' => 1,
			'created' => 1453881420,
			'modified' => 1453881420
		),
	);

}
