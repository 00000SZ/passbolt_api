<?php
/**
 * Resource Model Test
 *
 * @copyright     Copyright 2012, Passbolt.com
 * @package       app.Test.Case.Model.ResourceTest
 * @since         version 2.12.7
 * @license       http://www.passbolt.com/license
 */
App::uses('Resource', 'Model');
App::uses('AppTestCase', 'Test');

class ResourceTest extends AppTestCase {

	public $fixtures = array(
		'app.resource',
		'app.user',
		'app.role',
		'app.profile',
		'app.gpgkey',
		'app.file_storage',
		'app.groupsUser',
		'app.group',
		'core.cakeSession'
	);

	public function setUp() {
		parent::setUp();
		$this->Resource = ClassRegistry::init('Resource');
	}

/**
 * Test Name Validation
 *
 * @return void
 */
	public function testNameValidation() {
		$len = 64;
		$testcases = array(
			// Not empty
			'' => false,
			// Email
			'test@test.com' => false,
			// too short
			'sh' => false,
			// too long
			'toolong' . self::randString($len - 6, self::getMask('alphaASCII')) => false,
			// Short but enough
			'sho' => true,
			// Long but not too long
			'long' . self::randString($len - 4, self::getMask('alphaASCII')) => true,
			// Languages
			'ASCII' . self::randString($len - 5, self::getMask('alphaASCII')) => true,
			'ASCIIUPPER' . self::randString($len - 10, self::getMask('alphaASCIIUpper')) => true,
			'ACCENT' . self::randString($len - 6, self::getMask('alphaAccent')) => true,
			'LATIN' . self::randString($len - 5, self::getMask('alphaLatin')) => true,
			'CHINESE' . self::randString($len - 7, self::getMask('alphaChinese')) => true,
			'ARABIC' . self::randString($len - 6, self::getMask('alphaArabic')) => true,
			'RUSSIAN' . self::randString($len - 7, self::getMask('alphaRussian')) => true,
			// Spaces
			'txt with spaces' => true,
			"txt\twith\ttabs" => false,
			"txt\nwith\nnew\nlines" => false,
			// Special characters
			',.-_([)]\'' => true,
			'?!#' => false,
			// Digit accepted
			'0123456789' => true,
			// Html
			'<strong>test</strong>' => false,
		);
		foreach ($testcases as $testcase => $result) {
			$resource = array('Resource' => array('name' => $testcase));
			$this->Resource->set($resource);
			if ($result) {
				$msg = 'validation of the resource name with "' . $testcase . '" should validate';
			} else {
				$msg = 'validation of the resource name with "' . $testcase . '" should not validate';
			}
			$this->assertEquals($this->Resource->validates(array('fieldList' => array('name'))), $result, $msg);
		}
	}

/**
 * Test Username Validation
 *
 * @return void
 */
	public function testUsernameValidation() {
		$len = 64;
		$testcases = array(
			// Not empty
			'' => false,
			// Email are not accepted
			'test@test.com' => false,
			// too short
			'sh' => false,
			// too long
			'toolong' . self::randString($len - 6, self::getMask('alphaASCII')) => false,
			// Short but enough
			'sho' => true,
			// Long but not too long
			'long' . self::randString($len - 4, self::getMask('alphaASCII')) => true,
			// Languages
			'ASCII' . self::randString($len - 5, self::getMask('alphaASCII')) => true,
			'ASCIIUPPER' . self::randString($len - 10, self::getMask('alphaASCIIUpper')) => true,
			'ACCENT' . self::randString($len - 6, self::getMask('alphaAccent')) => false,
			'LATIN' . self::randString($len - 5, self::getMask('alphaLatin')) => false,
			'CHINESE' . self::randString($len - 7, self::getMask('alphaChinese')) => false,
			'ARABIC' . self::randString($len - 6, self::getMask('alphaArabic')) => false,
			'RUSSIAN' . self::randString($len - 7, self::getMask('alphaRussian')) => false,
			// Spaces
			'txt with spaces' => false,
			"txt\twith\ttabs" => false,
			"txt\nwith\nnew\nlines" => false,
			// Special characters
			',.-_([)]\'' => false,
			'?!#' => false,
			// Digit accepted
			'0123456789' => true,
			// Html
			'<strong>test</strong>' => false,
		);
		foreach ($testcases as $testcase => $result) {
			$resource = array('Resource' => array('username' => $testcase));
			$this->Resource->set($resource);
			if ($result) {
				$msg = 'validation of the resource username with ' . $testcase . ' should validate';
			} else {
				$msg = 'validation of the resource username with ' . $testcase . ' should not validate';
			}
			$this->assertEquals($this->Resource->validates(array('fieldList' => array('username'))), $result, $msg);
		}
	}

/**
 * Test Username Validation
 *
 * @return void
 */
	public function testUriValidation() {
		// Quick test, the uri validator is part of the Cake core.
		$testcases = array(
			'' => true,
			'?!#' => false,
			'test' => false,
			'test@test.com' => false,
			'http://www.passbolt.com' => true,
			'192.168.10.3' => true
		);
		foreach ($testcases as $testcase => $result) {
			$resource = array('Resource' => array('uri' => $testcase));
			$this->Resource->set($resource);
			if ($result) {
				$msg = 'validation of the resource uri with ' . $testcase . ' should validate';
			} else {
				$msg = 'validation of the resource uri with ' . $testcase . ' should not validate';
			}
			$this->assertEquals($this->Resource->validates(array('fieldList' => array('uri'))), $result, $msg);
		}
	}

/**
 * Test expiry Date Validation
 *
 * @return void
 */
	public function testExpiryDateValidation() {
		$testcases = array(
			'' => true,
			'14 Decembre 1980' => false,
			'27-12-2006' => false,
			'2006-14-12' => false,
			'2024-12-24' => true
		);
		foreach ($testcases as $testcase => $result) {
			$resource = array('Resource' => array('expiry_date' => $testcase));
			$this->Resource->set($resource);
			if ($result) {
				$msg = 'validation of the resource expiry date with ' . $testcase . ' should validate';
			} else {
				$msg = 'validation of the resource expiry date with ' . $testcase . ' should not validate';
			}
			$this->assertEquals($this->Resource->validates(array('fieldList' => array('expiry_date'))), $result, $msg);
		}
	}

/**
 * Test Description Validation
 *
 * @return void
 */
	public function testDescriptionValidation() {
		$len = 255;
		$testcases = array(
			// Not empty
			'' => true,
			// Email are not accepted
			'test@test.com' => false,
			// too short
			'sh' => false,
			// too long
			'toolong' . self::randString($len - 6, self::getMask('alphaASCII')) => false,
			// Short but enough
			'sho' => true,
			// Long but not too long
			'long' . self::randString($len - 4, self::getMask('alphaASCII')) => true,
			// Languages
			'ASCII' . self::randString($len - 5, self::getMask('alphaASCII')) => true,
			'ASCIIUPPER' . self::randString($len - 10, self::getMask('alphaASCIIUpper')) => true,
			'ACCENT' . self::randString($len - 6, self::getMask('alphaAccent')) => true,
			'LATIN' . self::randString($len - 5, self::getMask('alphaLatin')) => true,
			'CHINESE' . self::randString($len - 7, self::getMask('alphaChinese')) => true,
			'ARABIC' . self::randString($len - 6, self::getMask('alphaArabic')) => true,
			'RUSSIAN' . self::randString($len - 7, self::getMask('alphaRussian')) => true,
			// Spaces
			'txt with spaces' => true,
			"txt\twith\ttabs" => false,
			"txt\nwith\nnew\nlines" => false,
			// Special characters
			',.-_([)]\'' => true,
			'?!#' => false,
			// Digit accepted
			'0123456789' => true,
			// Html
			'<strong>test</strong>' => false,
		);
		foreach ($testcases as $testcase => $result) {
			$resource = array('Resource' => array('description' => $testcase));
			$this->Resource->set($resource);
			if ($result) {
				$msg = 'validation of the resource description with ' . $testcase . ' should validate';
			} else {
				$msg = 'validation of the resource description with ' . $testcase . ' should not validate';
			}
			$this->assertEquals($this->Resource->validates(array('fieldList' => array('description'))), $result, $msg);
		}
	}
}
