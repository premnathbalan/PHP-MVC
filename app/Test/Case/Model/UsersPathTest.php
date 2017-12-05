<?php
App::uses('UsersPath', 'Model');

/**
 * UsersPath Test Case
 */
class UsersPathTest extends CakeTestCase {

/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array(
		'app.users_path'
	);

/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();
		$this->UsersPath = ClassRegistry::init('UsersPath');
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		unset($this->UsersPath);

		parent::tearDown();
	}

}
