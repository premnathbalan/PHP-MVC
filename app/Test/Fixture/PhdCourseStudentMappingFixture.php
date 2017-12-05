<?php
/**
 * PhdCourseStudentMapping Fixture
 */
class PhdCourseStudentMappingFixture extends CakeTestFixture {

/**
 * Fields
 *
 * @var array
 */
	public $fields = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false, 'key' => 'primary'),
		'phd_student_id' => array('type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false, 'key' => 'index'),
		'phd_course_id' => array('type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false),
		'marks' => array('type' => 'string', 'null' => false, 'default' => null, 'length' => 10, 'collate' => 'latin1_swedish_ci', 'charset' => 'latin1'),
		'indicator' => array('type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false),
		'created_by' => array('type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false),
		'modified_by' => array('type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false),
		'created' => array('type' => 'timestamp', 'null' => false, 'default' => null),
		'modified' => array('type' => 'timestamp', 'null' => false, 'default' => '0000-00-00 00:00:00'),
		'indexes' => array(
			'PRIMARY' => array('column' => 'id', 'unique' => 1),
			'uq_phd_crse_stu_mapping' => array('column' => array('phd_student_id', 'phd_course_id'), 'unique' => 1)
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
			'phd_student_id' => 1,
			'phd_course_id' => 1,
			'marks' => 'Lorem ip',
			'indicator' => 1,
			'created_by' => 1,
			'modified_by' => 1,
			'created' => 1502084750,
			'modified' => 1502084750
		),
	);

}
