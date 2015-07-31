<?php
/**
 * Application Controller
 * Application-wide methods, all controllers inherit them
 * 
 * @copyright    copyright 2012 Passbolt.com
 * @package      app.Controller.AppController
 * @since        version 2.12.7
 * @license      http://www.passbolt.com/license
 */

App::uses('Controller', 'Controller');
App::uses('Purifier', 'HtmlPurifier.Lib');
App::import('Model','User');

/**
 * Application Controller
 *
 * Add your application-wide methods in the class below, your controllers
 * will inherit them.
 *
 * @package		app.Controller
 * @link		http://book.cakephp.org/2.0/en/controllers.html#the-app-controller
 */
class AppController extends Controller {

	/**
	 * @var $component application wide components
	 */
	public $components = array(
		'Session',
		'Paginator',
		'HtmlPurifier',
		'Cookie',
		'Auth',
		'Message',
		'Mailer',
		'IpAddress',
		'Blacklist'
	);

	public $helpers = array(
		'Html',
		'Form',
		'MyForm',
		'FileStorage.Image'
	);

	/**
	 * Called before the controller action.	You can use this method to configure and customize components
	 * or perform logic that needs to happen before each controller action.
	 * @link http://book.cakephp.org/2.0/en/controllers.html#request-life-cycle-callbacks
	 * @return void
	 */
	public function beforeFilter() {
		// Add a callback detector
		$this->request->addDetector('json', array('callback' => function ($request) {
			return (preg_match('/(.json){1,}$/', Router::url(null,true)) || $request->is('ajax'));
		}));

		// Set default layout
		if ($this->request->is('json')) {
			$this->layout = 'json';
			$this->view = '/Json/default';
		} else {
			// Get roles, to load in the layout js variables.
			// Only for admin and user.
			$Role = Common::getModel('Role');
			$this->set('roles', $Role->find('all', array(
				'conditions' => array(
					'name' => array(Role::ADMIN, Role::USER),
				),
			)));
			$this->layout = 'html5';
		}

		// Set active user Anonymous
		// or use what is in the session
		User::get();

		// Auth component initialization
		foreach (Configure::read('Auth') as $key => $authConf) {
			$this->Auth->{$key} = $authConf;
		}

		// @todo this will be remove via the initial auth check
		// User::set() will load default config
		if ($this->Session->read('Config.language') != null) {
			Configure::write('Config.language', $this->Session->read('Config.language'));
		} else {
			$this->Session->write('Config.language', Configure::read('Config.language'));
		}

		// Sanitize user input.
		$this->sanitize();
	}

	/**
	 * Authorization check main callback
	 * @link http://api20.cakephp.org/class/auth-component#method-AuthComponentisAuthorized
	 * @param mixed $user The user to check the authorization of. If empty the user in the session will be used.
	 * @return boolean True if $user is authorized, otherwise false
	 * @access public
	 */
	public function isAuthorized($user) {
		if ($this->isWhitelisted()) {
			return true;
		}
		if (User::isAnonymous()) {
			if ($this->request->is('Json')) {
				$this->Message->error(__('You need to login to access this location'), array('code' => 403));
				return true; // no need to redirect to login
			}
			return false;
		}
		return true;
	}

	/**
	 * Is the controller:action pair whitelisted in config? (see. App.auth.whitelist)
	 * @param string $controller, current is used if null
	 * @param string $action, current is used if null
	 * @return bool true if the controller action pair is whitelisted
	 * @access public
	 */
	public function isWhitelisted($controller=null, $action=null) {
		if ($controller == null) {
			$controller = strtolower($this->name);
		}
		if ($action == null) {
			$action = $this->action;
		}
		$whitelist = Configure::read('Auth.whitelist');
		return (isset($whitelist[$controller][$action]));
	}

	/**
	 * This perform the HTML sanitization of all user input
	 * @access public
	 */
	public function sanitize() {

		// Before sanitizing, keep the original data.
		$this->request->dataRaw = $this->request->data;
		//$this->request->queryRaw = $this->request->query;

		// Create a very restrictive configuration.
		Purifier::config('nohtml', array(
			'HTML.AllowedElements' => '',
			'Cache.SerializerPath' => APP . 'tmp' . DS . 'purifier',
		));

		// Sanitize any controller parameters.
		if (isset($this->request->params['pass']) && !empty($this->request->params['pass'])) {
			$this->request->params['pass'] = $this->HtmlPurifier->cleanRecursive($this->request->params['pass'], 'nohtml');
		}
		// Sanitize post data, except exceptions.
		if (isset($this->request->data) && !empty($this->request->data)) {
			$this->request->data = $this->HtmlPurifier->cleanRecursive($this->request->data, 'nohtml');
		}
		// Sanitize any get data.
		if (isset($this->request->query) && !empty($this->request->query)) {
			$this->request->query = $this->HtmlPurifier->cleanRecursive($this->request->query, 'nohtml');
		}
	}

}
