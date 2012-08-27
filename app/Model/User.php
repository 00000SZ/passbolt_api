<?php
/**
 * User Model
 *
 * @copyright		 Copyright 2012, Passbolt.com
 * @license			 http://www.passbolt.com/license
 * @package			 app.Model.user
 * @since				 version 2.12.7
 */
App::uses('AuthComponent', 'Controller/Component');
App::uses('BcryptFormAuthenticate', 'Controller/Component/Auth');
App::uses('Common', 'Controller/Component');
App::uses('Role', 'Model');
class User extends AppModel {

/**
 * Model Name
 * @access public
 */
	public $name   = 'User';

/**
 * Model behaviors
 * @access public
 */
	public $actsAs = array('Trackable');

/**
 * Details of belongs to relationships
 *
 * @var array
 * @link http://book.cakephp.org/2.0/en/models/associations-linking-models-together.html#belongsto
 */
	public $belongsTo = array('Role');

/**
 * They are legions
 */
	const ANONYMOUS = 'anonymous@passbolt.com';

/**
 * Get the validation rules upon context
 *
 * @param string context
 * @return array validation rules
 * @throws exception if case is undefined
 * @access public
 */
	public static function getValidationRules($case = 'default') {
		$default = array(
			'username' => array(
				'required' => array(
					'required' => true,
					'allowEmpty' => false,
					'rule' => array('notEmpty'),
					'message' => __('A username is required')
				),
				'email' => array(
					'rule' => array('email'),
					'message' => __('The username should be a valid email address')
				)
			),
			'password' => array(
				'required' => array(
					'required' => true,
					'allowEmpty' => false,
					'rule' => array('notEmpty'),
					'message' => __('A password is required')
				),
				'minLength' => array(
					'rule' => array('minLength',5),
					'message' => __('Your password should be at least composed of 5 characters')
				)
			)
		);
		switch ($case) {
			default:
			case 'default' :
				$rules = $default;
		}
		return $rules;
	}

/**
 * Before Save callback
 *
 * @link http://api20.cakephp.org/class/app-model#method-AppModel__construct
 * @return bool, if true proceed with save
 * @access public
 */
	public function beforeSave() {
		// encrypt the password
		// @todo use bcrypt instead #PASSBOLT-157
		if (isset($this->data['User']['password'])) {
			//$this->data[$this->alias]['password'] = AuthComponent::password($this->data[$this->alias]['password']);
    	$this->data['User']['password'] = BcryptFormAuthenticate::hash($this->data['User']['password']);
		}
		return true;
	}

/**
 * Get the current user
 *
 * @return array the current user or an anonymous user, false if error
 * @param string field
 * @access public
 */
	public static function get($path = null) {
		// Get the user from the session
		Common::getModel('Role');
		$u = &AuthComponent::user();
		// otherwise use a anonymous / guest one
		if ($u == null) {
			$u = &User::setActive(User::ANONYMOUS);
		}
		// truth is a land without path
		if (!isset($path)) {
			return $u;
		}
		// trying to find the path in u
		$path = str_replace('.', '/', $path);
		if (strpos($path, '/') === false) {
			$path = sprintf('User/%s', $path);
		}
		$path = '/' . $path;
		$value = Set::extract($path, $u);
		if (!$value) {
			return false;
		}
		return $value[0];
	}

/**
 * Set the user as current
 * It always perform a search on id to avoid abuse (such as using a crafted/fake user)
 *
 * @param mixed UUID, User::ANONYMOUS, or user array with id specified
 * @return array the desired user or an ANONYMOUS user, false if error in find
 * @access public
 */
	public static function setActive($user = null) {
		// Instantiate the mode are we are in a static/singleton context
		$_this = Common::getModel('User');
		$u = array();

		// If user is unspecified or ANONYMOUS is requested
		if ($user == null || $user == User::ANONYMOUS) {
			$u = $_this->find('first', User::getFindOptions(User::ANONYMOUS));
		} else {
			// if the user is specified and have a valid ID find it
			if (is_string($user) && Common::isUuid($user)) {
				$user = array('User' => array('id' => $user));
			}
			$u = $_this->find('first', User::getFindOptions('userActivation',$user) );
		}

		if (empty($u)) {
			return false;
		}

		// Store current user data in session
		App::import('Model', 'CakeSession');
		$Session = new CakeSession();
		$Session->renew();
		$Session->write(AuthComponent::$sessionKey, $u);

		return $u;
	}

/**
 * Check if user is an admin (use role)
 *
 * @return bool true if role is admin
 * @access public
 */
	public static function isAdmin() {
		Common::getModel('Role');
		$user = User::get();
		return $user['Role']['name'] == Role::ADMIN;
	}

/**
 * Check if user is admin role
 *
 * @return bool true if role is admin
 * @access public
 */
	public static function isAnonymous() {
		$user = User::get();
		return $user['User']['username'] == User::ANONYMOUS;
	}

/**
 * Check if user is a guest - Shortcut Method
 *
 * @return bool true if role is guest
 * @access public
 */
	public static function isGuest() {
		Common::getModel('Role');
		$user = User::get();
		return $user['Role']['name'] == Role::GUEST;
	}

/**
 * Return the find options to be used
 *
 * @param string context
 * @return array
 * @access public
 */
	public static function getFindOptions($case,&$data = null) {
		return array_merge(
			User::getFindConditions($case,&$data),
			User::getFindFields($case)
		);
	}

/**
 * Return the conditions to be used for a given context
 * for example if you want to activate a User session
 *
 * @param $context string{guest or id}
 * @param $data used in find conditions (such as User.id)
 * @return $condition array
 * @access public
 */
	public static function getFindConditions($case = User::ANONYMOUS, &$data = null) {
		$conditions = array();
		switch ($case) {
			/*
			case 'login':
				$conditions = array(
				 'conditions' => array(
					 'User.password' => $data['User']['password'],
					 'User.username' => $data['User']['username']
				 )
			 );
			break;
			case 'forgotPassword':
				$conditions = array(
				 'conditions' => array(
					 'User.username' => $data['User']['username']
				 )
				);
			break;
			case 'resetPassword':
			*/
			case 'userActivation':
				$conditions = array(
					'conditions' => array(
						'User.id' => $data['User']['id']
						//,'User.active' => 1
					)
				);
			break;
			case User::ANONYMOUS:
			case 'userView':
			default:
				$conditions = array(
					'conditions' => array(
						'User.username' => User::ANONYMOUS,
						'User.active' => 1
					)
				);
			break;
			default:
				$conditions = array(
					'conditions' => array()
				);
		}
		return $conditions;
	}

/**
 * Return the list of field to fetch for given context
 *
 * @param string $case context ex: login, activation
 * @return $condition array
 * @access public
 */
	public static function getFindFields($case = User::ANONYMOUS) {
		switch ($case) {
			//case 'resetPassword':
			//case 'forgotPassword':
			case User::ANONYMOUS:
			case 'userView':
			case 'userIndex':
			default:
				$fields = array(
					'fields' => array(
						'User.id', 'User.username', 'User.role_id'
						//, 'User.active'
					),
					'contain' => array(
						'Role(id,name)'
					)
				);
			break;
			/* case 'login': */
			case 'userActivation':
				$fields = array(
					'contain' => array(
						'Role(id,name)',
						//'Timezone(id,name)',
						//'Language(id,name,ISO_639-2-alpha2,ISO_639-2-alpha1)',
						//'Settings(*)',
						//'Person(id,firstname,lastname)'
						//'Office(name,acronym,region,type)',
					),
					'fields' => array(
						'User.id', 'User.username'
						//'User.active','User.permissions',
					)
				);
			break;
		}
		return $fields;
	}

}
