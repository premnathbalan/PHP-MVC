<?php
App::uses('DesignationsPath', 'Model');

/**
 * DesignationsPath Test Case
 */
class DesignationsPathTest extends CakeTestCase {

/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array(
		'app.designations_path'
	);

/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();
		$this->DesignationsPath = ClassRegistry::init('DesignationsPath');
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		unset($this->DesignationsPath);

		parent::tearDown();
	}

}
