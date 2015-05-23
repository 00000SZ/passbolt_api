<?php
/**
 * Categories Controller Tests
 *
 * @copyright    Copyright 2012, Passbolt.com
 * @package      app.Test.Case.Controller.CategoriesController
 * @since        version 2.12.7
 * @license      http://www.passbolt.com/license
 */
App::uses('AppController', 'Controller');
App::uses('CategoriesController', 'Controller');
App::uses('Category', 'Model');
App::uses('CategoryType', 'Model');
App::uses('User', 'Model');
App::uses('Role', 'Model');

// Uses sessions
// App::uses('CakeSession', 'Model/Datasource'); // doesn't work here
if (!class_exists('CakeSession')) {
	require CAKE . 'Model/Datasource/CakeSession.php';
}

class CategoriesControllerTest extends ControllerTestCase {

	public $fixtures = array(
		'app.category',
		'app.resource',
		'app.category_type',
		'app.categoriesResource',
		'app.user',
		'app.profile',
		'app.file_storage',
		'app.group',
		'app.groupsUser',
		'app.role',
		'app.permission',
		'app.permissions_type',
		'app.permission_view',
		'app.authenticationBlacklist',
		'core.cakeSession',
	);

	public function setUp() {
		$this->Category = new Category();
		$this->User = new User();
		$this->Category->useDbConfig = 'test';
		$this->User->useDbConfig = 'test';
		parent::setUp();

		// log the user as a manager to be able to access all categories
		$kk = $this->User->findByUsername('darth.vader@passbolt.com');
		$this->User->setActive($kk);
	}

	public function testIndexNoChildrenPermission() {
		// Test that users won't get top categories if they're not allowed.
		// Looking at the matrix of permission Jean rené should not be able to read the category 'Bolt Softwares Pvt. Ltd'
		$user = $this->User->findByUsername('jean-rene@test.com');
		$this->User->setActive($user);

		// test when no parameters are provided (default behaviour : children=false)
		$result = json_decode($this->testAction("/categories/index.json", array(
			'method' => 'get',
			'return' => 'contents'
		)), true);
		$debug = print_r($result, true);
		$this->assertEquals(Message::SUCCESS, $result['header']['status'], "/categories/index.json : The test should return success but is returning {$result['header']['status']} debug : $debug");
		$this->assertEmpty($this->Category->inNestedArray('Bolt Softwares Pvt. Ltd.', $result['body'], 'name'), '/categories/index.json : The server result should not contain Bolt Softwares Pvt. Ltd.');
		$this->assertNotEmpty($this->Category->inNestedArray('pv-jean_rene', $result['body'], 'name'), '/categories/index.json : The server result should contain pv-jean_rene');
	}

	public function testIndex() {
		// test when no parameters are provided (default behaviour : children=false)
		$result = json_decode($this->testAction("/categories/index.json", array(
			'method' => 'get',
			'return' => 'contents'
		)), true);
		$debug = print_r($result, true);
		$this->assertEquals(Message::SUCCESS, $result['header']['status'], "/categories/index.json : The test should return success but is returning {$result['header']['status']} debug : $debug");
		$this->assertNotEmpty($this->Category->inNestedArray('Bolt Softwares Pvt. Ltd.', $result['body'], 'name'), '/categories/index.json : The server result should contain Bolt Softwares Pvt. Ltd.');

		// test with children = true
		$result = json_decode($this->testAction("/categories.json", array(
			'method' => 'get',
			'return' => 'contents',
			'data' => array(
				'children' => 'true'
			)
		)), true);
		$this->assertEquals(Message::SUCCESS, $result['header']['status'], "/categories.json?children=true : The test should return success but is returning {$result['header']['status']}");
		$this->assertTrue($result['body'][0]['children'] > 0, "/categories.json?children=true : \$result['body'][0]['Category']['name'] should return 'Bolt Softwares Pvt. Ltd.' but is returning {$result['body'][0]['Category']['name']}");
	}

	public function testViewCategoryIdIsMissing() {
		// Unable to test missing id param because of route
	}

	public function testViewCategoryIdNotValid() {
		// test an error bad id
		$this->expectException('HttpException', 'The category id is invalid');
		$result = json_decode($this->testAction("/categories/badid.json?children=true", array(
			'method' => 'get',
			'return' => 'contents'
		)), true);
	}

	public function testViewCategoryDoesNotExist() {
		// test when a wrong id is provided
		$this->expectException('HttpException', 'The category does not exist');
		$result = json_decode($this->testAction("/categories/4ff6111b-efb8-4a26-aab4-2184cbdd56ca.json", array(
			'method' => 'get',
			'return' => 'contents'
		)), true);
	}

	public function testViewAndPermission() {
		// Error : name is empty
		$cat = $this->Category->findByName('d-project1');

		// Looking at the matrix of permission Isma should not be able to read the category d-project1
		$user = $this->User->findByUsername('ismail@passbolt.com');
		$this->User->setActive($user);

		$this->expectException('HttpException', 'The category does not exist');
		$result = json_decode($this->testAction("/categories/{$cat['Category']['id']}.json", array(
			'method' => 'Get',
			'return' => 'contents'
		)), true);
	}

	public function testView() {
		$root = $this->Category->findByName('Bolt Softwares Pvt. Ltd.');
		$id = $root['Category']['id'];

		// test when no parameters are provided
		$result = json_decode($this->testAction("/categories.json", array(
			'method' => 'get',
			'return' => 'contents'
		)), true);
		$this->assertEquals(Message::SUCCESS, $result['header']['status'], "/categories.json : The test should return success but is returning {$result['header']['status']}");

		// test if the object returned is a success one
		$result = json_decode($this->testAction("/categories/$id.json", array(
			'method' => 'get',
			'return' => 'contents',
			'data' => array(
				'children' => 'true'
			)
		)), true);
		$this->assertEquals(Message::SUCCESS, $result['header']['status'], 'categories/view/' . $id . '.json?children=true should return success');

		// test it is the expected format
		$result = json_decode($this->testAction("/categories/$id.json", array(
			'method' => 'get',
			'return' => 'contents',
			'data' => array(
				'children' => 'true'
			)
		)), true);
		$this->assertInternalType('array', $result['body'], 'The url categories/view/' . $id . '.json?children=true should return a json object');

		// test that content returned is containing expect value
		$result = json_decode($this->testAction("/categories/$id.json", array(
			'method' => 'get',
			'return' => 'contents',
			'data' => array(
				'children' => 'true'
			)
		)), true);
		$accounts = $this->Category->findByName('accounts');
		$path = $this->Category->inNestedArray($accounts['Category']['id'], $result['body']);
		$this->assertTrue(!empty($path), 'The result should contain the category "accounts", but it is not found.');
		$this->assertEquals($path[0], $root['Category']['id']);
		$administration = $this->Category->findByName('administration');
		$this->assertEquals($path[1], $administration['Category']['id']);

		// test without children
		$result = json_decode($this->testAction("/categories/$id.json", array(
			'method' => 'get',
			'return' => 'contents'
		)), true);
		$this->assertFalse(empty($result['body']));
		$this->assertEquals('Bolt Softwares Pvt. Ltd.', $result['body']['Category']['name'], "Faileds testing that first child is Bolt Softwares Pvt. Ltd.. It returned '{$result['body']['Category']['name']}'");
	}

	public function testChildrenCategoryIdIsMissing() {
		$this->expectException('HttpException', 'The category id is missing');
		$this->testAction("/categories/children.json", array('method' => 'get', 'return' => 'contents'));
	}

	public function testChildrenCategoryDoesNotExist() {
		$this->expectException('HttpException', 'The category does not exist');
		$this->testAction("/categories/children/4ff6111b-efb8-4a26-aab4-2184cbdd56ca.json", array(
			'method' => 'get',
			'return' => 'contents'
		));
	}

	public function testChildrenCategoryIdNotValid() {
		$this->expectException('HttpException', 'The category id is invalid');
		$this->testAction("/categories/children/badid.json", array('method' => 'get', 'return' => 'contents'));
	}

	public function testChildren() {
		$category = new Category();
		$category->useDbConfig = 'test';

		$root = $category->findByName('Bolt Softwares Pvt. Ltd.');
		$id = $root['Category']['id'];


		// test if the object returned is a success one
		$result = json_decode($this->testAction("/categories/children/$id.json", array(
			'method' => 'get',
			'return' => 'contents'
		)), true);
		//pr($result); die();
		$this->assertEquals(Message::SUCCESS, $result['header']['status'], "/categories/children/$id.json : The test should return success but is returning {$result['header']['status']}");

		// test it is the expected format
		$result = json_decode($this->testAction("/categories/children/$id.json", array(
			'method' => 'get',
			'return' => 'contents'
		)), true);
		$this->assertInternalType('array', $result['body'], "/categories/children/$id.json : The value returned should be an array but is " . print_r($result['body'], true));

		// test that content returned are correct
		$result = json_decode($this->testAction("/categories/children/$id.json", array(
			'method' => 'get',
			'return' => 'contents'
		)), true);
		$accounts = $this->Category->findByName('accounts');
		$path = $this->Category->inNestedArray($accounts['Category']['id'], $result['body']);
		$this->assertTrue(!empty($path), 'The result should contain the category "accounts", but it is not found.');
	}

	public function testAddNoDataProvided() {
		$this->expectException('HttpException', 'No data were provided');
		$this->testAction('/categories.json', array(
			'method' => 'post',
			'return' => 'contents'
		));
	}

	public function testAddInvalidDataProvided() {
		// Error : name is empty
		$this->expectException('HttpException', 'Could not validate category data');
		$result = json_decode($this->testAction('/categories/add.json', array(
			'data' => array(
				'Category' => array(
					'name' => ''
				)
			),
			'method' => 'Post',
			'return' => 'contents'
		)), true);
	}

	public function testAddAndPermission() {
		// Looking at the matrix of permission Cedric should be able to read but not to create into the category d-project1
		$user = $this->User->findByUsername('cedric@passbolt.com');
		$this->User->setActive($user);

		// Error : name is empty
		$this->expectException('HttpException', 'You are not authorized to create a category into the given parent category');
		$cat = $this->Category->findByName('d-project1');
		$result = json_decode($this->testAction('/categories/add.json', array(
			'data' => array(
				'Category' => array(
					'name' => 'testAddAndPermission Category',
					'parent_id' => $cat['Category']['id']
				)
			),
			'method' => 'Post',
			'return' => 'contents'
		)), true);
	}

	public function testAdd() {
		// check the response when a category is added (without parent_id)
		$result = json_decode($this->testAction('/categories.json', array(
			'data' => array(
				'Category' => array('name' => 'Aramboooool')
			),
			'method' => 'post',
			'return' => 'contents'
		)), true);

		$this->assertEquals(Message::SUCCESS, $result['header']['status'], "The test should return success but is returning {$result['header']['status']}");
		$this->assertEquals('Aramboooool', $result['body']['Category']['name'], "The test should return Aramboooool but is returning {$result['body']['Category']['name']}");

		// test insertion with parameter parent_id, and position 1
		$parent = $this->Category->findByName('Bolt Softwares Pvt. Ltd.');
		$parentId = $parent['Category']['id'];
		$result = json_decode($this->testAction('/categories.json', array(
			'data' => array(
				'Category' => array(
					'name' => 'category-test',
					'parent_id' => $parentId,
					'position' => 1
				)
			),
			'method' => 'post',
			'return' => 'contents'
		)), true);

		$catTest = $this->Category->findById($result['body']['Category']['id']);
		$this->assertEquals(Message::SUCCESS, $result['header']['status'], "The test should return success but is returning {$result['header']['status']}");
		$this->assertEquals($parent['Category']['lft'] + 1, $catTest['Category']['lft']);

		// test insertion with parameter parent_id, and position 2
		$result = json_decode($this->testAction('/categories.json', array(
			'data' => array(
				'Category' => array(
					'name' => 'category-test2',
					'parent_id' => $parentId,
					'position' => 2
				)
			),
			'method' => 'post',
			'return' => 'contents'
		)), true);
		$catTest2 = $this->Category->findById($result['body']['Category']['id']);
		$this->assertEquals(Message::SUCCESS, $result['header']['status'], "The test should return success but is returning {$result['header']['status']}");
		$this->assertEquals($catTest['Category']['lft'] + 2, $catTest2['Category']['lft']);

		// test insertion with parameter parent_id, and position 50 (doesnt exist)
		$result = json_decode($this->testAction('/categories.json', array(
			'data' => array(
				'Category' => array(
					'name' => 'Salvador Do Mundo',
					'parent_id' => $parentId,
					'position' => 50
				)
			),
			'method' => 'post',
			'return' => 'contents'
		)), true);
		$catTest2 = $this->Category->findById($result['body']['Category']['id']);
		$this->assertEquals(Message::SUCCESS, $result['header']['status'], "The test should return success but is returning {$result['header']['status']}");
		// $this->assertEquals(38, $catTest2['Category']['lft'], "Checking the lft attribute : should be 38 but is {$catTest2['Category']['lft']}");
	}

	public function testEditCategoryIdIsMissing() {
		$this->expectException('HttpException', 'The category id is missing');
		$this->testAction("/categories.json", array('method' => 'put', 'return' => 'contents'));
	}

	public function testEditCategoryIdNotValid() {
		$this->expectException('HttpException', 'The category id is invalid');
		$this->testAction("/categories/badid.json", array('method' => 'put', 'return' => 'contents'));
	}

	public function testEditNoDataProvided() {
		// Error : no data provided
		$cat = $this->Category->findByName('o-project1');
		$id = $cat['Category']['id'];

		// without parameters
		$this->expectException('HttpException', 'No data were provided');
		$this->testAction("/categories/$id.json", array('method' => 'put', 'return' => 'contents'));
	}

	public function testEditCategoryDoesNotExist() {
		$this->expectException('HttpException', 'The category does not exist');
		$this->testAction("/categories/4ff6111b-efb8-4a26-aab4-2184cbdd56ca.json", array(
			'method' => 'put',
			'return' => 'contents'
		));
	}

	public function testEditCategoryDoesNotExistAndPermission() {
		$adminCat = $this->Category->findByName('administration');

		// Looking at the matrix of permission should not be able to READ administration
		$user = $this->User->findByUsername('remy@passbolt.com');
		$this->User->setActive($user);

		$this->expectException('HttpException', 'The category does not exist');

		$id = $adminCat['Category']['id'];
		$result = json_decode($this->testAction("/categories/$id.json", array(
			'data' => array(
				'Category' => array(
					'name' => 'testEditCategoryDoesNotExistAndPermission Category'
				)
			),
			'method' => 'Put',
			'return' => 'contents'
		)), true);
	}

	public function testEditAndPermission() {
		// Looking at the matrix of permission Cedric should be able to read but not to edit the category d-project1
		$user = $this->User->findByUsername('cedric@passbolt.com');
		$this->User->setActive($user);

		$this->expectException('HttpException', 'You are not authorized to edit this category');
		$cat = $this->Category->findByName('d-project1');
		$id = $cat['Category']['id'];
		$result = json_decode($this->testAction("/categories/$id.json", array(
			'data' => array(
				'Category' => array(
					'name' => 'testEditAndPermission Category'
				)
			),
			'method' => 'Put',
			'return' => 'contents'
		)), true);
	}

	public function testEditAndParentCategoryPermission() {
		$adminCat = $this->Category->findByName('administration');

		// Looking at the matrix of permission Remy should be able to update "projects" but has no CREATE right for "administration"
		$user = $this->User->findByUsername('remy@passbolt.com');
		$this->User->setActive($user);

		$this->expectException('HttpException', 'You are not authorized to create a category into the given parent category');
		$projectsCat = $this->Category->findByName('projects');
		$id = $projectsCat['Category']['id'];
		$result = json_decode($this->testAction("/categories/$id.json", array(
			'data' => array(
				'Category' => array(
					'name' => 'testEditAndPermission Category',
					'parent_id' => $adminCat['Category']['id'],
				)
			),
			'method' => 'Put',
			'return' => 'contents'
		)), true);
	}

	public function testEdit() {
		$cat = $this->Category->findByName('o-project1');
		$id = $cat['Category']['id'];
		// Modify the name of the category
		$catNewName = 'o-project1-transformed';
		$cat['Category']['name'] = $catNewName;
		$params = array(
			'data' => $cat,
			'method' => 'put',
			'return' => 'contents'
		);
		$result = json_decode($this->testAction("/categories/$id.json", $params), true);
		$this->assertEquals(Message::SUCCESS, $result['header']['status'], "test edit with data should return success but is returning {$result['header']['status']}");

		// test that the category has been modified properly in db
		$cat = $this->Category->findById($id);
		$this->assertEquals($cat['Category']['name'], $catNewName, "test edit : name should be updated to $catNewName but it returned {$cat['Category']['name']}");
	}

	public function testDeleteCategoryIdIsMissing() {
		$this->expectException('HttpException', 'The category id is missing');
		$this->testAction("/categories.json", array('method' => 'delete', 'return' => 'contents'));
	}

	public function testDeleteCategoryIdNotValid() {
		$this->expectException('HttpException', 'The category id is invalid');
		$this->testAction("/categories/badid.json", array('method' => 'delete', 'return' => 'contents'));
	}

	public function testDeleteCategoryDoesNotExist() {
		$this->expectException('HttpException', 'The category does not exist');
		$id = '50d77ff7-bdad-4c03-8687-1b63d7a10fce';
		$result = json_decode($this->testAction("/categories/$id.json", array(
			'method' => 'Delete',
			'return' => 'contents'
		)), true);
	}

	public function testDeleteCategoryDoesNotExistAndPermission() {
		$adminCat = $this->Category->findByName('administration');

		// Looking at the matrix of permission should not be able to READ administration
		$user = $this->User->findByUsername('remy@passbolt.com');
		$this->User->setActive($user);

		$this->expectException('HttpException', 'The category does not exist');

		$id = $adminCat['Category']['id'];
		$result = json_decode($this->testAction("/categories/$id.json", array(
			'method' => 'Delete',
			'return' => 'contents'
		)), true);
	}

	public function testDeleteAndPermission() {
		// Looking at the matrix of permission Jean-René should be able to create but not delete the category jean rené private
		$user = $this->User->findByUsername('jean-rene@test.com');
		$this->User->setActive($user);

		$this->expectException('HttpException', 'You are not authorized to delete this category');
		$cat = $this->Category->findByName('pv-jean_rene');

		$id = $cat['Category']['id'];
		$result = json_decode($this->testAction("/categories/$id.json", array(
			'method' => 'Delete',
			'return' => 'contents'
		)), true);
	}

	public function testDelete() {
		$catName = 'cp-project2';
		$cat = $this->Category->findByName($catName);
		$id = $cat['Category']['id'];

		$result = json_decode($this->testAction("/categories/$id.json", array(
			'method' => 'delete',
			'return' => 'contents'
		)), true);
		$this->assertEquals(Message::SUCCESS, $result['header']['status'], "/categories/delete/$id : The test should return success but is returning {$result['header']['status']}");
		// check that the category was properly deleted
		$cat = $this->Category->findByName($catName);
		$this->assertTrue(empty($cat), "The category Drug places should have been deleted but is not");
	}

	public function testMoveCategoryIdIsMissing() {
		$this->expectException('HttpException', 'The category id is missing');
		$this->testAction("/categories/move.json", array('method' => 'put', 'return' => 'contents'));
	}

	public function testMoveCategoryIdNotValid() {
		$this->expectException('HttpException', 'The category id is invalid');
		$this->testAction("/categories/move/badid.json", array('method' => 'put', 'return' => 'contents'));
	}

	public function testMoveCategoryDoesNotExist() {
		$this->expectException('HttpException', 'The category does not exist');
		$this->testAction("/categories/move/4ff6111b-efb8-4a26-aab4-2184cbdd56ca.json", array(
			'method' => 'put',
			'return' => 'contents'
		));
	}

	public function testMoveParentCategoryIdNotValid() {
		$hr = $this->Category->findByName('human resource');
		$this->expectException('HttpException', 'The parent category id invalid');
		$this->testAction("/categories/move/{$hr['Category']['id']}/1/badParentId.json", array(
			'method' => 'put',
			'return' => 'contents'
		));
	}

	public function testMoveParentCategoryDoesNotExist() {
		$hr = $this->Category->findByName('human resource');
		$this->expectException('HttpException', 'The parent category does not exist');
		$this->testAction("/categories/move/{$hr['Category']['id']}/1/4ff6111b-efb8-4a26-aab4-2184cbdd56ca.json", array(
			'method' => 'put',
			'return' => 'contents'
		));
	}

	public function testMove() {
		$hr = $this->Category->findByName('human resource');
		$administration = $this->Category->findByName('administration');
		$cakephp = $this->Category->findByName('cakephp');

		$testCases = array(
			'firstPosition' => array('id' => $hr['Category']['id'], 'position' => '1'),
			'moveDown' => array('id' => $hr['Category']['id'], 'position' => '2'),
			'lastPosition' => array('id' => $hr['Category']['id'], 'position' => '4'),
			'positionMiddle' => array('id' => $hr['Category']['id'], 'position' => '3'),
			'minusPosition' => array('id' => $hr['Category']['id'], 'position' => '-1'),
			'differentParent' => array(
				'id' => $hr['Category']['id'],
				'position' => '1',
				'parent_id' => $cakephp['Category']['id']
			)
		);

		$this->Category->Behaviors->disable('Permissionable');
		// insert 2 more categories for this specific test
		$this->Category->create();
		$this->Category->save(array(
			'Category' => array(
				'name' => 'cat-test1',
				'parent_id' => $administration['Category']['id']
			)
		));
		$this->Category->create();
		$this->Category->save(array(
			'Category' => array(
				'name' => 'cat-test2',
				'parent_id' => $administration['Category']['id']
			)
		));
		// $this->Category->Behaviors->enable('Permissionable');

		//		// test firstPosition
		//		$url = "/categories/move/{$testCases['firstPosition']['id']}/{$testCases['firstPosition']['position']}.json";
		//		$result = json_decode($this->testAction($url, array('method' => 'put', 'return' => 'contents')), true);
		//		$this->assertEquals(Message::SUCCESS, $result['header']['status'], "$url : The test should return success but is returning {$result['header']['status']}"); // test if response is a success
		//		// test if the category is at the right place at present
		//		$afterSave = $this->Category->findById($hr['Category']['id']);
		//		$this->assertEquals($afterSave['Category']['lft'], $administration['Category']['lft'] + 1, "$url : Test failed to verify that Mapusa is first child of Disco Places");
		//
		//		// test moving down
		//		$url = "/categories/move/{$testCases['moveDown']['id']}/{$testCases['moveDown']['position']}.json";
		//		$result = json_decode($this->testAction($url, array('method' => 'put', 'return' => 'contents')), true);
		//		$this->assertEquals(Message::SUCCESS, $result['header']['status'], "The test should return success but is returning {$result['header']['status']}"); // test if response is an error
		//		// test if the category is at the right place after moving down
		//		$afterSave = $this->Category->findById($hr['Category']['id']);
		//		$this->assertEquals($afterSave['Category']['lft'], $administration['Category']['lft'] + 3, "$url : Test failed to verify that Mapusa is now at the 2nd position from top");
		//
		//		// test lastPosition
		//		$url = "/categories/move/{$testCases['lastPosition']['id']}/{$testCases['lastPosition']['position']}.json";
		//		$result = json_decode($this->testAction($url, array('method' => 'put', 'return' => 'contents')), true);
		//		$this->assertEquals(Message::SUCCESS, $result['header']['status'], "$url : The test should return success but is returning {$result['header']['status']}"); // test if response is a success
		//		// test if the category is at the right place at present
		//		$afterSave = $this->Category->findById($hr['Category']['id']);
		//		$this->assertEquals($afterSave['Category']['lft'], $administration['Category']['lft'] + 7, "$url : Test failed to verify that Mapusa is now at the last position");
		//
		//		// test positionMiddle
		//		$url = "/categories/move/{$testCases['positionMiddle']['id']}/{$testCases['positionMiddle']['position']}.json";
		//		$result = json_decode($this->testAction($url, array('method' => 'put', 'return' => 'contents')), true);
		//		$this->assertEquals(Message::SUCCESS, $result['header']['status'], "$url : The test should return error but is returning {$result['header']['status']}"); // test if response is a success
		//		// test if the category is at the right place at present
		//		$afterSave = $this->Category->findById($hr['Category']['id']);
		//		$this->assertEquals($afterSave['Category']['lft'], $administration['Category']['lft'] + 5, "$url : The test failed to verify that Mapusa is now positionne the middle. lft should be " . ($administration['Category']['lft'] + 5) . " but is {$afterSave['Category']['lft']}");
		//
		//		// test differentParent
		//		$url = "/categories/move/{$testCases['differentParent']['id']}/{$testCases['differentParent']['position']}/{$testCases['differentParent']['parent_id']}.json";
		//		$result = json_decode($this->testAction($url, array('method' => 'put', 'return' => 'contents')), true);
		//		$this->assertEquals(Message::SUCCESS, $result['header']['status'], "$url : The test should return success but is returning {$result['header']['status']}"); // test if response is a success
		//		// test if the category is at the right place at present
		//		$afterSave = $this->Category->findById($hr['Category']['id']);
		//		$cakephp = $this->Category->findById($cakephp['Category']['id']);
		//		$this->assertEquals($afterSave['Category']['lft'], $cakephp['Category']['lft'] + 1, "$url : Failed to test that 'hr' is now first child of 'cakephp'");
	}

	public function testTypeCategoryIdIsMissing() {
		$this->expectException('HttpException', 'The category id is missing');
		$this->testAction("/categories/type.json", array('method' => 'put', 'return' => 'contents'));
	}

	public function testTypeCategoryIdNotValid() {
		$this->expectException('HttpException', 'The category id is invalid');
		$this->testAction("/categories/type/badid.json", array('method' => 'put', 'return' => 'contents'));
	}

	public function testTypeCategoryDoesNotExist() {
		$this->expectException('HttpException', 'The category does not exist');
		$this->testAction("/categories/type/4ff6111b-efb8-4a26-aab4-2184cbdd56ca.json", array(
			'method' => 'put',
			'return' => 'contents'
		));
	}

	public function testTypeNameDoesNotExist() {
		$root = $this->Category->findByName('Bolt Softwares Pvt. Ltd.');
		$this->expectException('HttpException', 'The type does not exist');
		$this->testAction("/categories/type/{$root['Category']['id']}/badname.json", array(
			'method' => 'put',
			'return' => 'contents'
		));
	}

	public function testTypeAndPermission() {
		// Looking at the matrix of permission Jean-René should be able to create but not delete the category jean rené private
		$user = $this->User->findByUsername('jean-rene@test.com');
		$this->User->setActive($user);

		$this->expectException('HttpException', 'You are not authorized to change the type of this category');
		$cat = $this->Category->findByName('pv-jean_rene');

		$id = $cat['Category']['id'];
		$result = json_decode($this->testAction("/categories/type/$id/default.json", array(
			'method' => 'put',
			'return' => 'contents'
		)), true);
	}

	public function testType() {
		$root = $this->Category->findByName('Bolt Softwares Pvt. Ltd.');
		$id = $root['Category']['id'];

		$result = json_decode($this->testAction("/categories/type/$id/default.json", array(
			'method' => 'put',
			'return' => 'contents'
		)), true);
		$this->assertEquals(Message::SUCCESS, $result['header']['status'], "/categories/type/$id/default.json : The test should return success but returned {$result['header']['status']}");

		$root = $this->Category->findByName('Bolt Softwares Pvt. Ltd.');
		$this->assertEquals("0234f3a4-c5cd-11e1-a0c5-080027796c4c", $root['Category']['category_type_id'], "The category type id should be 50bda570-9364-4c41-9504-a7c58cebc04d but it is {$root['Category']['category_type_id']}");
	}

	public function testXSS() {
		// check the response when a category is added (without parent_id)
		$result = json_decode($this->testAction('/categories.json', array(
			'data' => array(
				'Category' => array('name' => '<script>alert("xss");</script>')
			),
			'method' => 'post',
			'return' => 'contents'
		)), true);
		$this->assertEquals(Message::SUCCESS, $result['header']['status'], "The test should return success but is returning {$result['header']['status']}");
		$this->assertEquals($result['body']['Category']['name'], '&lt;script&gt;alert(&quot;xss&quot;);&lt;/script&gt;', "Html should be striped down");
	}
}
