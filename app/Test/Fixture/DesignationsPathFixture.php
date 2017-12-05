<?php
/**
 * DesignationsPath Fixture
 */
class DesignationsPathFixture extends CakeTestFixture {

/**
 * Fields
 *
 * @var array
 */
	public $fields = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false, 'key' => 'primary'),
		'designation_id' => array('type' => 'biginteger', 'null' => false, 'default' => null, 'length' => 11, 'unsigned' => false, 'key' => 'unique'),
		'path_id' => array('type' => 'biginteger', 'null' => false, 'default' => null, 'length' => 11, 'unsigned' => false, 'key' => 'unique'),
		'indexes' => array(
			'PRIMARY' => array('column' => 'id', 'unique' => 1),
			'fk_dp_department_id' => array('column' => 'designation_id', 'unique' => 1),
			'fk_dp_path_id' => array('column' => 'path_id', 'unique' => 1)
		),
		'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_unicode_ci', 'engine' => 'InnoDB')
	);

/**
 * Records
 *
 * @var array
 */
	public $records = array(
		array(
			'id' => 1,
			'designation_id' => '',
			'path_id' => ''
		),
	);

}
