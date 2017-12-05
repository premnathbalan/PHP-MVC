<?php
App::uses('TypeOfCertification', 'Model');

/**
 * TypeOfCertification Test Case
 */
class TypeOfCertificationTest extends CakeTestCase {

/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array(
		'app.type_of_certification'
	);

/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();
		$this->TypeOfCertification = ClassRegistry::init('TypeOfCertification');
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		unset($this->TypeOfCertification);

		parent::tearDown();
	}

}
