<?php 
/**
 * Schema
 * $ ./Console/cake schema create
 *
 * @copyright    copyright 2012 Passbolt.com
 * @license      http://www.passbolt.com/license
 * @package      app.Config.Schema.schema
 * @since        version 2.12.7
 */
App::uses('Category', 'Model');
App::uses('CategoryType', 'Model');
App::uses('Resource', 'Model');
App::uses('User', 'Model');
App::uses('Role', 'Model');

class AppSchema extends CakeSchema {
	
	static $created = array();
	
	public function before($event = array()) {
		$db = ConnectionManager::getDataSource($this->connection);
		$db->cacheSources = false;
		return true;
	}

	public function insertMainData ($categories, $parentCategory=null) {
		
		foreach ($categories as $categoryId => $subCategories){
			// Insert Category
			if ($categoryId != 'Resources') {
				$this->Category->create();
				$category = $this->Category->save(array(
					'Category' => array(
						'name' => $categoryId,
						'parent_id' => isset($parentCategory) ? $parentCategory['Category']['id'] : null
					)
				));
				$this->insertMainData ($subCategories, $category);
			} 
			// insert Resources
			else {
				$resources = $subCategories;
				foreach($resources as $value){
					$this->Resource->create();
					$resource = $this->Resource->save($value);
					$this->CategoryResource->create();
					$this->CategoryResource->save(array(
						'CategoryResource' => array( 'category_id' => $parentCategory['Category']['id'], 'resource_id' => $resource['Resource']['id'] )
					));
				}		
			}
		}
	}
	
	public function after($event = array()) {
	
		if (isset($event['create'])) {
			switch ($event['create']) {
				case 'categories':
					array_push(self::$created, 'categories');
					if(in_array('resources', self::$created)) {
						$this->Category = ClassRegistry::init('Category');
						$this->Resource = ClassRegistry::init('Resource');
						$this->CategoryResource = ClassRegistry::init('CategoryResource');
						$this->insertMainData($this->_getDefaultData());
					}
				break;
			
				case 'category_types' :
					array_push(self::$created, 'category_types');
					$categoryType = ClassRegistry::init('CategoryType');
					$type['name'] = "default";
					$categoryType->create();
					$categoryType->save($type);
					$type['name'] = "database";
					$categoryType->create();
					$categoryType->save($type);
					$type['name'] = "ssh";
					$categoryType->create();
					$categoryType->save($type);
					break;
				
				case 'users':
					array_push(self::$created, 'users');
					$user = ClassRegistry::init('User');
					$us = $this->_getDefaultUsers();
					foreach ($us as $u) {
						$user->create();
						$user->save($u);
					}
				break;
				
				case 'resources':
					array_push(self::$created, 'resources');
					if(in_array('categories', self::$created)) {
						$this->Category = ClassRegistry::init('Category');
						$this->Resource = ClassRegistry::init('Resource');
						$this->CategoryResource = ClassRegistry::init('CategoryResource');
						$this->insertMainData($this->_getDefaultData());
					}
				break;
				
				case 'roles':
					array_push(self::$created, 'roles');
					$role = ClassRegistry::init('Role');
					$rs = $this->_getDefaultRoles();
					foreach ($rs as $r) {
						$role->create();
						$role->save($r);
					}
				break;
			}
		}
	}

 	public $category_types = array(
		'id' => array('type' => 'string', 'null' => false, 'default' => null, 'length' => 36, 'key' => 'primary', 'collate' => 'utf8_unicode_ci', 'charset' => 'utf8'),
		'name' => array('type' => 'string', 'null' => false, 'default' => null, 'collate' => 'utf8_unicode_ci', 'charset' => 'utf8'),
		'description' => array('type' => 'text', 'null' => true, 'default' => null, 'collate' => 'utf8_unicode_ci', 'charset' => 'utf8'),
		'indexes' => array('PRIMARY' => array('column' => 'id', 'unique' => 1)),
		'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_unicode_ci', 'engine' => 'InnoDB')
	);

	public $categories = array(
		'id' => array('type' => 'string', 'null' => false, 'default' => null, 'length' => 36, 'key' => 'primary', 'collate' => 'utf8_unicode_ci', 'charset' => 'utf8'),
		'parent_id' => array('type' => 'string', 'null' => true, 'default' => null, 'length' => 36, 'collate' => 'utf8_unicode_ci', 'charset' => 'utf8'),
		'lft' => array('type' => 'integer', 'null' => true, 'default' => null, 'length' => 10),
		'rght' => array('type' => 'integer', 'null' => true, 'default' => null, 'length' => 10),
		'name' => array('type' => 'string', 'null' => true, 'default' => null, 'collate' => 'utf8_unicode_ci', 'charset' => 'utf8'),
		'category_type_id' => array('type' => 'string', 'null' => true, 'default' => null, 'length' => 36, 'collate' => 'utf8_unicode_ci', 'comment' => 'type id of the category', 'charset' => 'utf8'),
		'deleted' => array('type' => 'boolean', 'null' => false, 'default' => '0'),
		'created' => array('type' => 'datetime', 'null' => false, 'default' => null),
		'modified' => array('type' => 'datetime', 'null' => false, 'default' => null),
		'indexes' => array('PRIMARY' => array('column' => 'id', 'unique' => 1)),
		'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_unicode_ci', 'engine' => 'InnoDB')
	);

	public $categories_resources = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'key' => 'primary'),
		'category_id' => array('type' => 'string', 'null' => false, 'default' => null, 'length' => 36, 'key' => 'index', 'collate' => 'utf8_unicode_ci', 'charset' => 'utf8'),
		'resource_id' => array('type' => 'string', 'null' => false, 'default' => null, 'length' => 36, 'collate' => 'utf8_unicode_ci', 'charset' => 'utf8'),
		'indexes' => array('PRIMARY' => array('column' => 'id', 'unique' => 1), 'category_id' => array('column' => array('category_id', 'resource_id'), 'unique' => 0)),
		'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_unicode_ci', 'engine' => 'InnoDB')
	);

	public $resources = array(
		'id' => array('type' => 'string', 'null' => false, 'default' => null, 'length' => 36, 'key' => 'primary', 'collate' => 'utf8_unicode_ci', 'charset' => 'utf8'),
		'name' => array('type' => 'string', 'null' => false, 'default' => null, 'length' => 64, 'collate' => 'utf8_unicode_ci', 'charset' => 'utf8'),
		'username' => array('type' => 'string', 'null' => false, 'default' => null, 'length' => 64, 'collate' => 'utf8_unicode_ci', 'charset' => 'utf8'),
		'expiry_date' => array('type' => 'timestamp', 'null' => true, 'default' => null),
		'uri' => array('type' => 'string', 'null' => false, 'default' => null, 'collate' => 'utf8_unicode_ci', 'charset' => 'utf8'),
		'description' => array('type' => 'text', 'null' => true, 'default' => null, 'collate' => 'utf8_unicode_ci', 'charset' => 'utf8'),
		'deleted' => array('type' => 'boolean', 'null' => false, 'default' => '0'),
		'created' => array('type' => 'datetime', 'null' => false, 'default' => null),
		'modified' => array('type' => 'datetime', 'null' => false, 'default' => null),
		'indexes' => array('PRIMARY' => array('column' => 'id', 'unique' => 1)),
		'tableParameters' => array('charset' => 'latin1', 'collate' => 'latin1_swedish_ci', 'engine' => 'InnoDB')
	);

	public $roles = array(
		'id' => array('type' => 'string', 'null' => false, 'default' => null, 'length' => 36, 'key' => 'primary', 'collate' => 'utf8_unicode_ci', 'charset' => 'utf8'),
		'name' => array('type' => 'string', 'null' => false, 'default' => null, 'length' => 50, 'key' => 'unique', 'collate' => 'utf8_unicode_ci', 'charset' => 'utf8'),
		'description' => array('type' => 'string', 'null' => true, 'default' => null, 'collate' => 'utf8_unicode_ci', 'charset' => 'utf8'),
		'created' => array('type' => 'datetime', 'null' => false, 'default' => null),
		'modified' => array('type' => 'datetime', 'null' => false, 'default' => null),
		'created_by' => array('type' => 'string', 'null' => false, 'default' => null, 'length' => 36, 'collate' => 'utf8_unicode_ci', 'charset' => 'utf8'),
		'modified_by' => array('type' => 'string', 'null' => false, 'default' => null, 'length' => 36, 'collate' => 'utf8_unicode_ci', 'charset' => 'utf8'),
		'indexes' => array('PRIMARY' => array('column' => 'id', 'unique' => 1), 'name' => array('column' => 'name', 'unique' => 1)),
		'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_unicode_ci', 'engine' => 'InnoDB')
	);

	public $users = array(
		'id' => array('type' => 'string', 'null' => false, 'default' => null, 'length' => 36, 'key' => 'primary', 'collate' => 'utf8_unicode_ci', 'charset' => 'utf8'),
		'role_id' => array('type' => 'string', 'null' => false, 'default' => null, 'length' => 36, 'collate' => 'utf8_unicode_ci', 'charset' => 'utf8'),
		'username' => array('type' => 'string', 'null' => false, 'default' => null, 'length' => 50, 'key' => 'unique', 'collate' => 'utf8_unicode_ci', 'charset' => 'utf8'),
		'password' => array('type' => 'string', 'null' => true, 'default' => null, 'length' => 60, 'collate' => 'utf8_unicode_ci', 'charset' => 'utf8'),
		'active' => array('type' => 'integer', 'null' => false, 'default' => '0', 'length' => 1),
		'created' => array('type' => 'datetime', 'null' => false, 'default' => null),
		'modified' => array('type' => 'datetime', 'null' => false, 'default' => null),
		'created_by' => array('type' => 'string', 'null' => false, 'default' => null, 'length' => 36, 'collate' => 'utf8_unicode_ci', 'charset' => 'utf8'),
		'modified_by' => array('type' => 'string', 'null' => false, 'default' => null, 'length' => 36, 'collate' => 'utf8_unicode_ci', 'charset' => 'utf8'),
		'indexes' => array('PRIMARY' => array('column' => 'id', 'unique' => 1), 'username' => array('column' => 'username', 'unique' => 1)),
		'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_unicode_ci', 'engine' => 'InnoDB')
	);

	public $gpgKeys = array(
		'id' => array('type' => 'string', 'null' => false, 'default' => null, 'length' => 36, 'key' => 'primary', 'collate' => 'utf8_unicode_ci', 'charset' => 'utf8'),
		'user_id' => array('type' => 'string', 'null' => false, 'default' => null, 'length' => 36, 'collate' => 'utf8_unicode_ci', 'charset' => 'utf8'),
		'key' => array('type' => 'string', 'null' => false, 'default' => null, 'length' => 4096, 'key' => 'unique', 'collate' => 'utf8_unicode_ci', 'charset' => 'utf8'),
		'bits' => array('type' => 'integer', 'null' => false, 'default' => '2048', 'length' => 11),
		'uid' => array('type' => 'string', 'null' => false, 'default' => null, 'length' => 128),
		'key_id' => array('type' => 'string', 'null' => false, 'default' => null, 'length' => 8),
		'fingerprint' => array('type' => 'string', 'null' => false, 'default' => null, 'length' => 51),
		'type' => array('type' => 'string', 'null' => false, 'default' => 'RSA', 'length' => 16),
		'parent_id' => array('type' => 'string', 'null' => true, 'default' => null, 'length' => 36),
		'created' => array('type' => 'datetime', 'null' => false, 'default' => null),
		'modified' => array('type' => 'datetime', 'null' => false, 'default' => null),
		'created_by' => array('type' => 'string', 'null' => false, 'default' => null, 'length' => 36, 'collate' => 'utf8_unicode_ci', 'charset' => 'utf8'),
		'modified_by' => array('type' => 'string', 'null' => false, 'default' => null, 'length' => 36, 'collate' => 'utf8_unicode_ci', 'charset' => 'utf8'),
		'indexes' => array('PRIMARY' => array('column' => 'id', 'unique' => 1), 'key_id' => array('column' => 'key_id', 'unique' => 1)),
		'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_unicode_ci', 'engine' => 'InnoDB')
	);

	protected function _getDefaultData() {
		$categories = array (
			'Projects' => array(
				'CakePHP' => array(
						'Passbolt'=>array(
							'Resources'=>array(
								array('Resource' => array( 'name' => 'htpasswd', 'username' => 'passbolt', 'expiry_date' => null, 'uri' => 'https://95.142.173.61/deploy', 'description' => 'this is a description test' )),
								array('Resource' => array( 'name' => 'test', 'username' => 'test@passbolt.com', 'expiry_date' => null, 'uri' => 'https://95.142.173.61/deploy', 'description' => 'this is a description test' ))
							)
						),
						'KeyBolt'=>array(
							'Resources'=>array(
								array('Resource' => array( 'name' => 'htpasswd', 'username' => 'passbolt', 'expiry_date' => null, 'uri' => 'https://95.142.173.61/deploy', 'description' => 'this is a description test' )),
								array('Resource' => array( 'name' => 'test', 'username' => 'test@keybolt.com', 'expiry_date' => null, 'uri' => 'https://95.142.173.61/deploy', 'description' => 'this is a description test' ))
							)
						)
				),
				'Drupal'=>array(
					'Ecpat'=>array(
						'Resources'=>array(
							array('Resource' => array( 'name' => 'ecpat', 'username' => 'admin', 'expiry_date' => null, 'uri' => 'http://ecpat.prod2.enova-tech.net/', 'description' => 'this is a description test' ))
						)
					),
					'Gt-sat'=>array(
						'Resources'=>array(
							array('Resource' => array( 'name' => 'gt-sat', 'username' => 'admin', 'expiry_date' => null, 'uri' => 'http://gt-sat.prod2.enova-tech.net/', 'description' => 'this is a description test' ))
						)
					)
				),
				'Magento'=>array()
			),
			'Administration' => array(
				'Jenkins'=>array(
					'Resources'=>array(
						array('Resource' => array( 'name' => 'cedric', 'username' => 'cedric@passbolt.com', 'expiry_date' => null, 'uri' => 'https://95.142.173.61/jenkins/job/Passbolt', 'description' => 'this is a description test' )),
						array('Resource' => array( 'name' => 'Isma', 'username' => 'isma@passbolt.com', 'expiry_date' => null, 'uri' => 'https://95.142.173.61/jenkins/job/Passbolt', 'description' => 'this is a description test' )),
						array('Resource' => array( 'name' => 'kevin', 'username' => 'kevin@passbolt.com', 'expiry_date' => null, 'uri' => 'https://95.142.173.61/jenkins/job/Passbolt', 'description' => 'this is a description test' )),
						array('Resource' => array( 'name' => 'Myriam', 'username' => 'myriam@passbolt.com', 'expiry_date' => null, 'uri' => 'https://95.142.173.61/jenkins/job/Passbolt', 'description' => 'this is a description test' )),
						array('Resource' => array( 'name' => 'Remy', 'username' => 'remy@passbolt.com', 'expiry_date' => null, 'uri' => 'https://95.142.173.61/jenkins/job/Passbolt', 'description' => 'this is a description test' ))
					)
				)
			),
			'Management' => array(
				'Jiira'=>array(
					'Resources'=>array(
						array('Resource' => array( 'name' => 'cedric', 'username' => 'https://passbolt.atlassian.net', 'expiry_date' => null, 'uri' => 'https://passbolt.atlassian.net', 'description' => 'this is a description test' )),
						array('Resource' => array( 'name' => 'Isma', 'username' => 'cedric@passbolt.com', 'expiry_date' => null, 'uri' => 'https://passbolt.atlassian.net', 'description' => 'this is a description test' )),
						array('Resource' => array( 'name' => 'kevin', 'username' => 'cedric@passbolt.com', 'expiry_date' => null, 'uri' => 'https://passbolt.atlassian.net', 'description' => 'this is a description test' )),
						array('Resource' => array( 'name' => 'Myriam', 'username' => 'cedric@passbolt.com', 'expiry_date' => null, 'uri' => 'https://passbolt.atlassian.net', 'description' => 'this is a description test' )),
						array('Resource' => array( 'name' => 'Remy', 'username' => 'cedric@passbolt.com', 'expiry_date' => null, 'uri' => 'http://www.enova-tech.net', 'description' => 'this is a description test' ))
					)
				)
			),
		);
		return $categories;
	}
	
	protected function _getDefaultUsers() {
		$us[] = array('User' => array(
			'id' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c',
			'username' => 'anonymous@passbolt.com',
			'role_id' => '0208f3a4-c5cd-11e1-a0c5-080027796c4c',
			'password' => 'we are legions',
			'active' => 1,
			'created' => '2012-07-04 13:39:25',
			'modified' => '2012-07-04 13:39:25',
			'created_by' => '0208f3a4-c5cd-11e1-a0c5-080027796c4c',
			'modified_by' => '0208f3a4-c5cd-11e1-a0c5-080027796c4c'
		));
		$us[] = array('User' => array(
			'id' => 'bbd56042-c5cd-11e1-a0c5-080027796c4e',
			'username' => 'test@passbolt.com',
			'role_id' => '0208f57a-c5cd-11e1-a0c5-080027796c4c',
			'password' => 'password',
			'active' => 1,
			'created' => '2012-07-04 13:39:25',
			'modified' => '2012-07-04 13:39:25',
			'created_by' => '0208f3a4-c5cd-11e1-a0c5-080027796c4c',
			'modified_by' => '0208f3a4-c5cd-11e1-a0c5-080027796c4c'
		));
		return $us;
	}

	protected function _getDefaultRoles() {
		$rs[] = array('Role' => array(
			'id' => '0208f3a4-c5cd-11e1-a0c5-080027796c4c',
			'name' => 'guest',
			'description' => 'Non logged-in user',
			'created' => '2012-07-04 13:39:25',
			'modified' => '2012-07-04 13:39:25',
			'created_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c',
			'modified_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c'
		));
		$rs[] = array('Role' => array(
			'id' => '0208f57a-c5cd-11e1-a0c5-080027796c4c',
			'name' => 'user',
			'description' => 'Logged in default user',
			'created' => '2012-07-04 13:39:25',
			'modified' => '2012-07-04 13:39:25',
			'created_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c',
			'modified_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c'
		));
		$rs[] = array('Role' => array(
			'id' => '142c1188-c5cd-11e1-a0c5-080027796c4c',
			'name' => 'admin',
			'description' => 'Organization administrator',
			'created' => '2012-07-04 13:39:25',
			'modified' => '2012-07-04 13:39:25',
			'created_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c',
			'modified_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c'
		));
		$rs[] = array('Role' => array(
			'id' => '142c1340-c5cd-11e1-a0c5-080027796c4c',
			'name' => 'root',
			'description' => 'Super Administrator',
			'created' => '2012-07-04 13:39:25',
			'modified' => '2012-07-04 13:39:25',
			'created_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c',
			'modified_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c'
		));
		return $rs;
	}
}
