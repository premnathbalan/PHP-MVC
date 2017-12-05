<?php
/**
 * InternalProject Fixture
 */
class InternalProjectFixture extends CakeTestFixture {

/**
 * Fields
 *
 * @var array
 */
	public $fields = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false, 'key' => 'primary'),
		'month_year_id' => array('type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false, 'key' => 'index'),
		'course_mapping_id' => array('type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false, 'key' => 'index'),
		'student_id' => array('type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false, 'key' => 'index'),
		'marks' => array('type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false),
		'moderation_marks' => array('type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false),
		'moderation_date' => array('type' => 'datetime', 'null' => false, 'default' => null),
		'max_convert_to' => array('type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false),
		'created_by' => array('type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false),
		'modified_by' => array('type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false),
		'created' => array('type' => 'timestamp', 'null' => false, 'default' => null),
		'modified' => array('type' => 'timestamp', 'null' => false, 'default' => '0000-00-00 00:00:00'),
		'indexes' => array(
			'PRIMARY' => array('column' => 'id', 'unique' => 1),
			'uq_internal_projects' => array('column' => array('month_year_id', 'course_mapping_id', 'student_id'), 'unique' => 1),
			'fk_int_prj_crse_mapping_id' => array('column' => 'course_mapping_id', 'unique' => 0),
			'fk_int_prj_stu_id' => array('column' => 'student_id', 'unique' => 0),
			'fk_int_prj_month_year_id' => array('column' => 'month_year_id', 'unique' => 0)
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
			'student_id' => 1,
			'marks' => 1,
			'moderation_marks' => 1,
			'moderation_date' => '2016-07-30 16:04:15',
			'max_convert_to' => 1,
			'created_by' => 1,
			'modified_by' => 1,
			'created' => 1469874855,
			'modified' => 1469874855
		),
	);

}
