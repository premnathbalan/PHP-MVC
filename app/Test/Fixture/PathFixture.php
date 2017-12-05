<?php
/**
 * Path Fixture
 */
class PathFixture extends CakeTestFixture {

/**
 * Fields
 *
 * @var array
 */
	public $fields = array(
		'id' => array('type' => 'biginteger', 'null' => false, 'default' => null, 'length' => 10, 'unsigned' => false, 'key' => 'primary'),
		'name' => array('type' => 'string', 'null' => false, 'default' => null, 'length' => 225, 'collate' => 'utf8_unicode_ci', 'charset' => 'utf8'),
		'path' => array('type' => 'string', 'null' => false, 'default' => null, 'length' => 225, 'collate' => 'utf8_unicode_ci', 'charset' => 'utf8'),
		'params' => array('type' => 'string', 'null' => true, 'default' => null, 'length' => 225, 'collate' => 'utf8_unicode_ci', 'charset' => 'utf8'),
		'indexes' => array(
			'PRIMARY' => array('column' => 'id', 'unique' => 1)
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
			'id' => '',
			'name' => 'Lorem ipsum dolor sit amet',
			'path' => 'Lorem ipsum dolor sit amet',
			'params' => 'Lorem ipsum dolor sit amet'
		),
	);

}
