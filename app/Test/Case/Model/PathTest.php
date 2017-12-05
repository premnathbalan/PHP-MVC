<?php
App::uses('Path', 'Model');

/**
 * Path Test Case
 */
class PathTest extends CakeTestCase {

/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array(
		'app.path'
	);

/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();
		$this->Path = ClassRegistry::init('Path');
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		unset($this->Path);

		parent::tearDown();
	}

}
