<?php
/**
 * Profile Model Test
 *
 * @copyright		Copyright 2012, Passbolt.com
 * @package			app.Test.Case.Model.ProfileTest
 * @since			version 2.12.12
 * @license			http://www.passbolt.com/license
 */
App::uses('Profile', 'Model');

class ProfileTest extends CakeTestCase {

	public $fixtures = array('app.user','app.profile');

	public $autoFixtures = true;

	/**
	 * Setup
	 * @return void
	 */
	public function setup() {
		parent::setUp();
		$this->Profile = ClassRegistry::init('Profile');
		$this->User = ClassRegistry::init('User');
	}

	/**
	 * Test if the default profiles as set in the fixture
	 * @return void
	 */
	public function testFixtures() {
		$c = $this->Profile->find('first', array('conditions' => array('id' => String::Uuid())));
		$this->assertEqual(empty($c), true, 'Shouldnt find a profile that does not exist');
		$c = $this->Profile->find('first', array('conditions' => array('first_name' => 'kevin')));
		$this->assertEqual(is_array($c), true, 'Profile should be present in the database');
	}

	/**
	 * Test UserId Validation
	 * @return void
	 */
	public function testUserIdValidation() {
		$user = $this->User->findByUsername('utest@passbolt.com');
		$testcases = array(
			'' => false,
			'?!#' => false,
			'test' => false,
			'aaa00003-c5cd-11e1-a0c5-080027z!6c4c' => false,
			'zzz00003-c5cd-11e1-a0c5-080027796c4c' => false,
			'aaa00003-c5cd-11e1-a0c5-080027796c4c' => false,
			$user['User']['id'] => true,
		);
		foreach ($testcases as $testcase => $result) {
			$profile = array('Profile' => array('user_id' => $testcase));
			$this->Profile->set($profile);
			if($result) $msg = 'validation of the user_id with ' . $testcase . ' should validate';
			else $msg = 'validation of the user_id with ' . $testcase . ' should not validate';
			$validate = $this->Profile->validates(array('fieldList' => array('user_id')));
			$this->assertEqual($validate, $result, $msg);
		}
	}

	/**
	 * Test TagId Validation
	 * @return void
	 */
	public function testGenderValidation() {
		$testcases = array(
			'm' => true,
			'f' => true,
			'F' => false,
			'test' => false,
			'' => false,
		);
		foreach ($testcases as $testcase => $result) {
			$profile = array('Profile' => array('gender' => $testcase));
			$this->Profile->set($profile);
			if($result) $msg = 'profile on gender ' . $testcase . ' should be allowed';
			else $msg = 'profile on gender' . $testcase . ' should not be allowed';
			$this->assertEqual($this->Profile->validates(array('fieldList' => array('gender'))), $result, $msg);
		}
	}

	/**
	 * Test DateofBirth Validation
	 * @return void
	 */
	public function testDateOfBirthValidation() {
		$testcases = array(
			'1980-12-14' => true,
			'1980-14-35' => false,
			'1980-14' => false,
			'' => false,
		);
		foreach ($testcases as $testcase => $result) {
			$profile = array('Profile' => array('date_of_birth' => $testcase));
			$this->Profile->set($profile);
			if($result) $msg = 'profile on date_of_birth ' . $testcase . ' should be allowed';
			else $msg = 'profile on date_of_birth' . $testcase . ' should not be allowed';
			$this->assertEqual($this->Profile->validates(array('fieldList' => array('date_of_birth'))), $result, $msg);
		}
	}

	/**
	 * Test DateofBirth Validation
	 * @return void
	 */
	public function testTitleValidation() {
		$testcases = array(
			'Mr' => true,
			'Ms' => true,
			'Gnr' => false,
			'' => false,
		);
		foreach ($testcases as $testcase => $result) {
			$profile = array('Profile' => array('title' => $testcase));
			$this->Profile->set($profile);
			if($result) $msg = 'profile on title ' . $testcase . ' should be allowed';
			else $msg = 'profile on title' . $testcase . ' should not be allowed';
			$this->assertEqual($this->Profile->validates(array('fieldList' => array('title'))), $result, $msg);
		}
	}

	/**
	 * Test FirstName Validation
	 * @return void
	 */
	public function testFirstNameValidation() {
		$testcases = array(
			'Kevin' => true,
			'Georges1' => false,
			'123' => false,
			'' => false,
		);
		foreach ($testcases as $testcase => $result) {
			$profile = array('Profile' => array('first_name' => $testcase));
			$this->Profile->set($profile);
			if($result) $msg = 'profile on firstName ' . $testcase . ' should be allowed';
			else $msg = 'profile on firstName' . $testcase . ' should not be allowed';
			$this->assertEqual($this->Profile->validates(array('fieldList' => array('first_name'))), $result, $msg);
		}
	}

	/**
	 * Test FirstName Validation
	 * @return void
	 */
	public function testLastNameValidation() {
		$testcases = array(
			'Kevin' => true,
			'Georges1' => false,
			'123' => false,
			'' => false,
		);
		foreach ($testcases as $testcase => $result) {
			$profile = array('Profile' => array('last_name' => $testcase));
			$this->Profile->set($profile);
			if($result) $msg = 'profile on lasttName ' . $testcase . ' should be allowed';
			else $msg = 'profile on lastName' . $testcase . ' should not be allowed';
			$this->assertEqual($this->Profile->validates(array('fieldList' => array('last_name'))), $result, $msg);
		}
	}
}
