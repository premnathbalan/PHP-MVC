<?php
/**
 * PhdStudentMark Fixture
 */
class PhdStudentMarkFixture extends CakeTestFixture {

/**
 * Fields
 *
 * @var array
 */
	public $fields = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false, 'key' => 'primary'),
		'phd_course_student_mapping_id' => array('type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false),
		'month_year_id' => array('type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false),
		'marks' => array('type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false),
		'created_by' => array('type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false),
		'created' => array('type' => 'timestamp', 'null' => false, 'default' => null),
		'modified_by' => array('type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false),
		'modified' => array('type' => 'timestamp', 'null' => false, 'default' => '0000-00-00 00:00:00'),
		'indexes' => array(
			'PRIMARY' => array('column' => 'id', 'unique' => 1)
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
			'phd_course_student_mapping_id' => 1,
			'month_year_id' => 1,
			'marks' => 1,
			'created_by' => 1,
			'created' => 1502357377,
			'modified_by' => 1,
			'modified' => 1502357377
		),
	);

}
