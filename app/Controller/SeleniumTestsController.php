<?php
/**
 * SeleniumTests Controller
 *
 * @copyright		Copyright 2012, Passbolt.com
 * @license			http://www.passbolt.com/license
 * @package			app.Controller.SeleniumTestsController
 * @since			version 2.13.3
 */

// Uses EmailQueue.
App::uses('EmailQueue', 'Plugin/EmailQueue/Model');

/**
 * Class SeleniumTestsController
 */
class SeleniumTestsController extends AppController {

	public $uses = array(
		'User',
		'EmailQueue',
	);

	// Configuration key to check if selenium entry points are configured.
	private $configKey = 'App.selenium.active';

	/**
	 * Check if the selenium entry point is allowed by the configuration.
	 * @return bool
	 */
	private function __isSeleniumAllowed() {
		$seleniumAllowed = Configure::read($this->configKey) === true
			&& Configure::read('debug') > 0;
		return $seleniumAllowed;
	}

	/**
	 * beforeFilter().
	 */
	function beforeFilter() {
		// If Selenium mode is not activated, we redirect to home page.
		$allowed = $this->__isSeleniumAllowed();
		//throw new HttpException(print_r(array($allowed ? 'allowed' : 'not allowed'), true));
		if (!$allowed) {
			return $this->redirect('/');
		}
		// If selenium entry point is activated, we proceed.
		parent::beforeFilter();
		// Allow ShowLastEmail entry point.
		$this->Auth->allow(
			'showLastEmail'
		);
		// Use table email_queue. (seems that cakephp refuses to take the default of the class).
		$this->EmailQueue->useTable = 'email_queue';
	}

	/**
	 * Show last email sent to a particular user.
	 * @param string $username
	 *
	 * @throws Exception
	 */
	public function showLastEmail($username = null) {
		// If Selenium mode is not activated, we redirect to home page.
		if (!$this->__isSeleniumAllowed()) {
			return $this->redirect('/');
		}
		// If username is null, we return an error.
		if ($username == null) {
			throw new HttpException(__('Username not correct'));
		}
		// If username doesn't exist, we return an error.
		$u = $this->User->findByUsername($username);
		if (!$u) {
			throw new HttpException(__('The username doesn\'t exist'));
		}
		// If email is not found, we return an error.
		$email = $this->EmailQueue->findByTo($username);
		if (!$email) {
			throw new HttpException(__('No email was sent to this user'));
		}
		// Get template used.
		$template = $email['EmailQueue']['template'];
		// Get vars.
		$vars = is_array($email['EmailQueue']['template_vars']) ?
			$email['EmailQueue']['template_vars'] : json_decode($email['EmailQueue']['template_vars'], true);
		// Get subject.
		$title = $email['EmailQueue']['subject'];
		// Get format.
		$format = $email['EmailQueue']['format'];

		// List variables.
		foreach ($vars as $varName => $varValue) {
			// Set variables to the view.
			$this->set($varName, $varValue);
		}
		// Set layout title same as email title.
		$this->set('title_for_layout', $title);
		// Uses the email layout.
		$this->layout = "Emails/$format/default";
		// Renders the view.
		$this->render("/Emails/$format/" . $template);
	}
}