<?php
App::uses('DummyNumber', 'Model');

/**
 * DummyNumber Test Case
 */
class DummyNumberTest extends CakeTestCase {

/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array(
		'app.dummy_number'
	);

/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();
		$this->DummyNumber = ClassRegistry::init('DummyNumber');
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		unset($this->DummyNumber);

		parent::tearDown();
	}

}
