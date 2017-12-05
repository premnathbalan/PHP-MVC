<?php
App::uses('InternalProject', 'Model');

/**
 * InternalProject Test Case
 */
class InternalProjectTest extends CakeTestCase {

/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array(
		'app.internal_project'
	);

/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();
		$this->InternalProject = ClassRegistry::init('InternalProject');
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		unset($this->InternalProject);

		parent::tearDown();
	}

}
