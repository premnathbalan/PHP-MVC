<?php
/**
 * UserRolesPath Fixture
 */
class UserRolesPathFixture extends CakeTestFixture {

/**
 * Fields
 *
 * @var array
 */
	public $fields = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false, 'key' => 'primary'),
		'user_role_id' => array('type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false, 'key' => 'index'),
		'path_id' => array('type' => 'biginteger', 'null' => false, 'default' => null, 'length' => 11, 'unsigned' => false, 'key' => 'index'),
		'indexes' => array(
			'PRIMARY' => array('column' => 'id', 'unique' => 1),
			'fk_dp_path_id' => array('column' => 'path_id', 'unique' => 0),
			'fk_dp_designation_id' => array('column' => 'user_role_id', 'unique' => 0)
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
			'user_role_id' => 1,
			'path_id' => ''
		),
	);

}
