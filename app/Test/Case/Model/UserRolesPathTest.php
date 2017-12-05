<?php
App::uses('UserRolesPath', 'Model');

/**
 * UserRolesPath Test Case
 */
class UserRolesPathTest extends CakeTestCase {

/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array(
		'app.user_roles_path'
	);

/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();
		$this->UserRolesPath = ClassRegistry::init('UserRolesPath');
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		unset($this->UserRolesPath);

		parent::tearDown();
	}

}
