<?php
/**
 * Gpgkeys controller
 * This file will define how gpg keys are managed
 *
 * @copyright    Copyright 2012, Passbolt.com
 * @license      http://www.passbolt.com/license
 * @package      app.Controller.GpgkeysController
 * @since        version 2.12.7
 */

class GpgkeysController extends AppController {

	/**
	 * @var $component application wide components
	 */
	public $components = array(
		'Filter'
	);

	/**
	 * Index entry point.
	 */
	public function index() {
		$filter = $this->Filter->fromRequest($this->request->query);
		$data = array();
		// if keywords provided build the model request with
		if (isset($filter['modified_after']) && !empty($filter['modified_after'])) {
			$data['modified_after'] = date('Y-m-d H:i:s', $filter['modified_after']);
		}
		$o = $this->Gpgkey->getFindOptions('index', User::get('Role.name'), $data);
		$returnVal = $this->Gpgkey->find('all', $o);
		if (empty($returnVal)) {
			$this->Message->notice(__('There is no gpg keys to display'));
			return;
		}

		$this->Message->success();
		$this->set('data', $returnVal);
	}

	/**
	 * View
	 *
	 * @param $id UUID of the user
	 *
	 * @access public
	 */
	public function view($id = null) {
		// check if the id is provided
		if (!isset($id)) {
			$this->Message->error(__('The user id is missing'));
			return;
		}

		// check if the id is valid
		if (!Common::isUuid($id)) {
			$this->Message->error(__('The user id is invalid'));
			return;
		}

		$data = array('Gpgkey.user_id' => $id);
		$o = $this->Gpgkey->getFindOptions('view', User::get('Role.name'), $data);
		$gpgkey = $this->Gpgkey->find('first', $o);
		if (!$gpgkey) {
			$this->Message->error(__('The user id is invalid'), array('code' => 404));
			return;
		}
		$this->set('data', $gpgkey);
		$this->Message->success();
	}

	/**
	 * gpgkey add entry point
	 */
	public function add() {
		// check the HTTP request method
		if (!$this->request->is('post')) {
			$this->Message->error(__('Invalid request method, should be POST'));
			return;
		}
		// check if data was provided
		if (!isset($this->request->data['Gpgkey'])) {
			$this->Message->error(__('No data were provided'));
			return;
		}

		$userId = User::get('id');

		$this->Gpgkey->begin();
		// Check if a key already exists for the given user.
		$existingKey = $this->Gpgkey->find('first', array(
				'conditions' => array(
					'Gpgkey.deleted' => 0,
					'Gpgkey.user_id' => $userId,
				)
			));
		// If key already exists, soft delete them.
		if ($existingKey) {
			$this->Gpgkey->updateAll(
				array('Gpgkey.deleted' => 1),
				array(
					'Gpgkey.user_id' => $userId,
					'Gpgkey.deleted' => 0
				)
			);
		}

		// set the data for validation and save
		$gpgkeyData = $this->request->data;
		// Modify key with raw data. We do not want a sanitized version.
		$gpgkeyData['Gpgkey']['key'] = $this->request->dataRaw['Gpgkey']['key'];

		// Only the key owner can change his key.
		// Force the user id of the user. We are not concerned about what was given.
		$gpgkeyData = $this->Gpgkey->buildGpgkeyDataFromKey($gpgkeyData['Gpgkey']['key']);
		$gpgkeyData['Gpgkey']['user_id'] = $userId;
		// Set data.
		$this->Gpgkey->set($gpgkeyData);

		// Get fields.
		$fields = $this->Gpgkey->getFindFields('save', User::get('Role.name'));
		// Check if the data is valid.
		if (!$this->Gpgkey->validates()) {
			$this->Gpgkey->rollback();
			$this->Message->error(__('Could not validate gpgkey data'));
			return;
		}

		$gpgkey= $this->Gpgkey->save($gpgkeyData, false, $fields['fields']);

		if ($gpgkey == false) {
			$this->Gpgkey->rollback();
			$this->Message->error(__('The gpgkey could not be saved'));
			return;
		}
		$this->Gpgkey->commit();
		$data = array('Gpgkey.user_id' => $gpgkeyData['Gpgkey']['user_id']);
		$options = $this->Gpgkey->getFindOptions('view', User::get('Role.name'), $data);
		$gpgkey = $this->Gpgkey->find('first', $options);

		$this->Message->success(__("The gpgkey has been saved successfully"));
		$this->set('data', $gpgkey);

		return;
	}
}
