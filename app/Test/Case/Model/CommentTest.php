<?php
/**
 * Comment Model Test
 *
 * @copyright		Copyright 2012, Passbolt.com
 * @package			app.Test.Case.Model.CommentTest
 * @since			version 2.12.12
 * @license			http://www.passbolt.com/license
 */
App::uses('Comment', 'Model');
App::uses('AppTestCase', 'Test');

class CommentTest extends AppTestCase {

	public $fixtures = array(
		'app.comment',
		'app.resource',
		'app.user',
		'app.role',
		'app.gpgkey',
		'app.group',
		'app.groupsUser',
		'app.profile',
		'app.file_storage',
		'core.cakeSession'
	);

	public $autoFixtures = true;

/**
 * Setup
 * @return void
 */
	public function setup() {
		parent::setUp();
		$this->Comment = ClassRegistry::init('Comment');
		$this->Comment->Resource->Behaviors->disable('Permissionable');
	}

/**
 * Test if the default comments as set in the fixture
 * @return void
 */
	public function testFixtures() {
		$c = $this->Comment->find('first', array('conditions' => array('id' => String::Uuid())));
		$this->assertEqual(empty($c), true, 'Shouldnt find a comment that does not exist');
		$c = $this->Comment->find('first', array('conditions' => array('content' => 'this is a short comment')));
		$this->assertEqual(is_array($c), true, 'Default comment should be present in the database');
	}

/**
 * Test TagId Validation
 * @return void
 */
	public function testIdValidation() {
		$testcases = array(
			'' => true, '?!#' => false, 'test' => false,
			'aaa00003-c5cd-11e1-a0c5-080027z!6c4c' => false,
			'zzz00003-c5cd-11e1-a0c5-080027796c4c' => false,
			'aaa00003-c5cd-11e1-a0c5-080027796c4c' => true,
			'aaa00000-cccc-11d1-a0c5-080027796c4c' => true,
		);
		foreach ($testcases as $testcase => $result) {
			$comment = array('Comment' => array('id' => $testcase));
			$this->Comment->set($comment);
			if($result) $msg = 'validation of the id with ' . $testcase . ' should validate';
			else $msg = 'validation of the id with ' . $testcase . ' should not validate';
			$this->assertEqual($this->Comment->validates(array('fieldList' => array('id'))), $result, $msg);
		}
	}

/**
 * Test TagId Validation
 * @return void
 */
	public function testParentIdValidation() {
		$testcases = array(
			'' => true, '?!#' => false, 'test' => false,
			'aaa00003-c5cd-11e1-a0c5-080027z!6c4c' => false,
			'zzz00003-c5cd-11e1-a0c5-080027796c4c' => false,
			'aaa00003-c5cd-11e1-a0c5-080027796c4c' => false,
			'aaa00000-cccc-11d1-a0c5-080027796c4c' => true,
		);
		foreach ($testcases as $testcase => $result) {
			$comment = array('Comment' => array('parent_id' => $testcase));
			$this->Comment->set($comment);
			if($result) $msg = 'validation of the parent_id with ' . $testcase . ' should validate';
			else $msg = 'validation of the parent_id with ' . $testcase . ' should not validate';
			$this->assertEqual($this->Comment->validates(array('fieldList' => array('parent_id'))), $result, $msg);
		}
	}

/**
 * Test TagId Validation
 * @return void
 */
	public function testForeignModelValidation() {
		$testcases = array(
			'Resource' => true, 'secret' => false, 'comment' => false, 'test' => false,
		);
		foreach ($testcases as $testcase => $result) {
			$comment = array('Comment' => array('foreign_model' => $testcase));
			$this->Comment->set($comment);
			if($result) $msg = 'comment on foreign model ' . $testcase . ' should be allowed';
			else $msg = 'comment on foreign model' . $testcase . ' should not be allowed';
			$this->assertEqual($this->Comment->validates(array('fieldList' => array('foreign_model'))), $result, $msg);
		}
	}

/**
 * Test TagId Validation
 * @return void
 */
	public function testForeignIdValidation() {
		// test with empty for foreign key check 
		// we do double check test id and model validity 
		$testcase = array(
			'Comment' => array(
				'foreign_id' => '',
				'foreign_model' => ''
			)
		);
		unset($this->Comment->validate['foreign_id']['uuid']);
		unset($this->Comment->validate['foreign_model']['inlist']);
		$this->Comment->set($testcase);
		$msg = 'comment on empty foreign_id and moel should not be allowed';
		$this->assertEqual($this->Comment->validates(array('fieldList' => array('foreign_id'))), false, $msg);

		$testcase = array(
			'Comment' => array(
				'foreign_id' => '',
				'foreign_model' => 'Resource'
			)
		);
		unset($this->Comment->validate['foreign_id']['uuid']);
		unset($this->Comment->validate['foreign_model']['inlist']);
		$this->Comment->set($testcase);
		$msg = 'comment on empty foreign_id and moel should not be allowed';
		$this->assertEqual($this->Comment->validates(array('fieldList' => array('foreign_id'))), false, $msg);

		// test with a good resource
		$testcase = array(
			'Comment' => array(
				'foreign_id' => '509bb871-5168-49d4-a676-fb098cebc04d',
				'foreign_model' => 'Resource'
			)
		);
		$this->Comment->set($testcase);
		$msg = 'comment on Ressource 509bb871-5168-49d4-a676-fb098cebc04d should be allowed';
		$this->assertEqual($this->Comment->validates(array('fieldList' => array('foreign_id'))), true, $msg);

		// test with a bad resource
		$testcase = array(
			'Comment' => array(
				'foreign_id' => '509bb871-0000-49d4-a676-fb098cebc04d',
				'foreign_model' => 'Resource'
			)
		);
		$this->Comment->set($testcase);
		$msg = 'comment on Ressource 509bb871-0000-49d4-a676-fb098cebc04d should be allowed';
		$this->assertEqual($this->Comment->validates(array('fieldList' => array('foreign_id'))), false, $msg);
	}

/**
 * Test TagId Validation
 * @return void
 */
	public function testContentValidation() {
		$len = 255;
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
			$comment = array('Comment' => array('content' => $testcase));
			$this->Comment->set($comment);
			if($result) $msg = 'comment with content ' . $testcase . ' should be allowed';
			else $msg = 'comment with content ' . $testcase . ' should not be allowed';
			$this->assertEqual($this->Comment->validates(array('fieldList' => array('content'))), $result, $msg);
		}
	}
}
