<?php
App::uses('Section', 'Model');

/**
 * Section Test Case
 */
class SectionTest extends CakeTestCase {

/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array(
		'app.section'
	);

/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();
		$this->Section = ClassRegistry::init('Section');
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		unset($this->Section);

		parent::tearDown();
	}

}
