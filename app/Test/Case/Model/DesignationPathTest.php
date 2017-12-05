<?php
App::uses('DesignationPath', 'Model');

/**
 * DesignationPath Test Case
 */
class DesignationPathTest extends CakeTestCase {

/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array(
		'app.designation_path'
	);

/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();
		$this->DesignationPath = ClassRegistry::init('DesignationPath');
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		unset($this->DesignationPath);

		parent::tearDown();
	}

}
