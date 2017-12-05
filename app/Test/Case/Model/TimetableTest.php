<?php
App::uses('Timetable', 'Model');

/**
 * Timetable Test Case
 */
class TimetableTest extends CakeTestCase {

/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array(
		'app.timetable'
	);

/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();
		$this->Timetable = ClassRegistry::init('Timetable');
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		unset($this->Timetable);

		parent::tearDown();
	}

}
