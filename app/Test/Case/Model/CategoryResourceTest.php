<?php
/**
 * CategoryResource Model Test
 *
 * @copyright     Copyright 2012, Passbolt.com
 * @package       app.Test.Case.Model.CategoryResourceTest
 * @since         version 2.12.7
 * @license       http://www.passbolt.com/license
 */
App::uses('CategoryResource', 'Model');
App::uses('User', 'Model');

if (!class_exists('CakeSession')) {
	require CAKE . 'Model/Datasource/CakeSession.php';
}

class CategoryResourceTest extends CakeTestCase {

	public $fixtures = array(
		'app.category',
		'app.resource',
		'app.categories_resource',
		'app.user',
		'app.role',
		'app.profile',
		'app.gpgKey',
		'app.file_storage',
		'app.groups_user',
		'core.cakeSession'
	);

	public function setUp() {
		parent::setUp();
		$this->CategoryResource = ClassRegistry::init('CategoryResource');
		$this->CategoryResource->useDb = 'test';
		// Disable the permissionable behavior, we will test the permissionable behavior in an other unit test
		$this->CategoryResource->Category->Behaviors->disable('Permissionable');
		$this->CategoryResource->Resource->Behaviors->disable('Permissionable');
	}

/**
 * Test CategoryId Validation
 * @return void
 */
	public function testCategoryIdValidation() {		
		$testcases = array(
			'' => false,
			'?!#' => false,
			'test' => false,
			'4ff6111b-efb8-4a26-aab4-2184cbdd56c' => false,
			'50d77ff9-42d8-43d5-beee-1b63d7a10fce' => true
		);

		foreach ($testcases as $testcase => $result) {
			$cr = array(
				'CategoryResource' => array(
					'category_id' => $testcase,
					'resource_id' => '50d77ff9-c358-4dfb-be34-1b63d7a10fce' // resource_id is passed here because when we don't pass it test fails for obscure reasons
				)
			);
			$this->CategoryResource->create();
			$this->CategoryResource->set($cr);
			if ($result) {
				$msg = 'validation of the category_resource "category id" with "' . $testcase . '" should validate';
			} else {
				$msg = 'validation of the category_resource "category id" with "' . $testcase . '" should not validate';
			}
			$validation = $this->CategoryResource->validates(array('fieldList' => array('category_id')));
			if (!$validation) {
				$msg .= print_r($this->CategoryResource->invalidFields(), true);
			}
			$this->assertEqual($validation, $result, "$msg");
		}
	}

/**
 * Test ResourceId Validation
 * @return void
 */
	public function testResourceIdValidation() {
		$testcases = array(
			'' => false,
			'?!#' => false,
			'test' => false,
			'4ff6111b-efb8-4a26-aab4-2184cbdd56c' => false,
			'50d77ffb-afb4-4a73-85fd-1b63d7a10fce' => true
		);
		foreach ($testcases as $testcase => $result) {
			$cr = array(
				'CategoryResource' => array(
					'resource_id' => $testcase,
					'category_id' => '50d77ff9-42d8-43d5-beee-1b63d7a10fce'
				)
			);
			$this->CategoryResource->create();
			$this->CategoryResource->set($cr);
			if ($result) {
				$msg = 'validation of the category_resource "resource id" with "' . $testcase . '" should validate';
			} else {
				$msg = 'validation of the category_resource "resource id" with "' . $testcase . '" should not validate';
			}
			$validation = $this->CategoryResource->validates(array('fieldList' => array('resource_id')));
			if (!$validation) {
				$msg .= print_r($this->CategoryResource->invalidFields(), true);
			}
			$this->assertEqual($validation, $result, $msg);
		}
	}

/**
 * Test Duplicates
 * @return void
 */
	public function testDuplicatesValidation() {
		$cr = $this->CategoryResource->findById('1');
		$cr['CategoryResource']['id'] = '';
		// test duplicates
		$this->CategoryResource->create();
		$this->CategoryResource->set($cr);
		$validation = $this->CategoryResource->validates(array('fieldList' => array('category_id', 'resource_id')));
		$this->assertEqual($validation, false, print_r($this->CategoryResource->invalidFields(), true));
	}

}
