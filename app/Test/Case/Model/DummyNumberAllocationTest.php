<?php
App::uses('DummyNumberAllocation', 'Model');

/**
 * DummyNumberAllocation Test Case
 */
class DummyNumberAllocationTest extends CakeTestCase {

/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array(
		'app.dummy_number_allocation'
	);

/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();
		$this->DummyNumberAllocation = ClassRegistry::init('DummyNumberAllocation');
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		unset($this->DummyNumberAllocation);

		parent::tearDown();
	}

}
