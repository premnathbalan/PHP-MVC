<?php
App::uses('FolioNumber', 'Model');

/**
 * FolioNumber Test Case
 */
class FolioNumberTest extends CakeTestCase {

/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array(
		'app.folio_number'
	);

/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();
		$this->FolioNumber = ClassRegistry::init('FolioNumber');
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		unset($this->FolioNumber);

		parent::tearDown();
	}

}
