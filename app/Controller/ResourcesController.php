<?php
/**
 * Resources Controller
 *
 * @copyright     Copyright 2012 Passbolt.com
 * @license       http://www.passbolt.com/license
 * @package       app.Controller.ResourcesController
 * @since         version 2.12.7
 */

App::uses('Category', 'Model');
App::uses('CategoryResource', 'Model');

class ResourcesController extends AppController {
/**
 * Get a resource
 * Renders a json object of the resource
 *
 * @param uuid $id the id of the resource
 * @return void
 */

	public function view($id=null) {
		// check if the category id is provided
		if (!isset($id)) {
			$this->Message->error(__('The resource id is missing'));
			return;
		}
		// check if the id is valid
		if (!Common::isUuid($id)) {
			$this->Message->error(__('The resource id invalid'));
			return;
		}
		// check if it exists
		$data = array(
			'Resource.id' => $id
		);
		$options = $this->Resource->getFindOptions('view', $data);
		$resources = $this->Resource->find('all', $options);
		if (!count($resources)) {
			$this->Message->error(__('The resource does not exist'));
			return;
		}
		$this->set('data', $resources[0]);
		$this->Message->success();
	}

/**
 * Get all resources in a category id
 * Renders a json object of the resources
 *
 * @param uuid $categoryId the id of the category
 * @param bool recursive, whether we want also the resources of all subcategories
 * @return void
 */
	public function viewByCategory($categoryId=null, $recursive=false) {
			// check if the category id is provided
		if (!isset($categoryId)) {
			$this->Message->error(__('The category id is missing'));
			return;
		}
			// check if the id is valid
		if (!Common::isUuid($categoryId)) {
			$this->Message->error(__('The category id invalid'));
			return;
		}

		// check if the category exists
		$category = $this->Resource->CategoryResource->Category->findById($categoryId);
		if (!$category) {
			$this->Message->error(__('The category doesn\t exist'));
			return;
		}

		if ($recursive == false) {
			$data = array('CategoryResource.category_id' => $categoryId);
		} else {
			$cats = $this->Resource->CategoryResource->Category->find(
				'all',
				array(
					'conditions' => array(
						'Category.lft >=' => $category['Category']['lft'],
						'Category.rght <=' => $category['Category']['rght']
						),
					'order' => array(
						'Category.lft' => 'ASC'
						)
				)
			);
			foreach ($cats as $cat) {
				$data['CategoryResource.category_id'][] = $cat['Category']['id'];
			}
		}
		$this->Resource->bindModel(array('hasOne' => array('CategoryResource')));
		$options = $this->Resource->getFindOptions('viewByCategory', $data);
		$resources = $this->Resource->find('all', $options);
		//pr($resources); die();

		if (!$resources) {
			$resources = array();
		}

		$this->set('data', $resources);
		$this->Message->success();
	}

/**
 * Delete a resource
 * @param uuid id the id of the resource to delete
 */
	public function delete($id = null) {
		// check if the category id is provided
		if (!isset($id)) {
			$this->Message->error(__('The resource id is missing'));
			return;
		}
		// check if the id is valid
		if (!Common::isUuid($id)) {
			$this->Message->error(__('The resource id invalid'));
			return;
		}
		$resource = $this->Resource->findById($id);
		if (!$resource) {
			$this->Message->error(__('The resource doesn\'t exist'));
			return;
		}
		$resource['Resource']['deleted'] = '1';
		if (!$this->Resource->save($resource)) {
			$this->Message->error(__('Error while deleting'));
			return;
		}
		$this->Message->success(__('The resource was sucessfully deleted'));
	}

/**
 * Add a resource
 */
	public function add() {
		// check the HTTP request method
		if (!$this->request->is('post')) {
			$this->Message->error(__('Invalid request method, should be POST'));
			return;
		}
		// check if data was provided
		if (!isset($this->request->data['Resource'])) {
			$this->Message->error(__('No data were provided'));
			return;
		}

		// set the data for validation and save
		$resourcepost = $this->request->data;
		$this->Resource->set($resourcepost);

		// check if the data is valid
		if (!$this->Resource->validates()) {
			$this->Message->error(__('Could not validate resource data'));
			return;
		}

		$this->Resource->bindModel(array('hasAndBelongsToMany' => array('Category')));
		$this->Resource->contain(array('Category'));
		$resource = $this->Resource->save($resourcepost);
		if ($resource === false) {
			$this->Message->error(__('The resource could not be saved'));
			return;
		}

		// Save the relations
		foreach ($resourcepost['Category'] as $cat) {
				$crdata = array(
					'CategoryResource' => array(
						'category_id' => $cat['id'],
						'resource_id' => $resource['Resource']['id']
					)
				);
			// check if the data is valid
			$this->Resource->CategoryResource->set($crdata);
			if (!$this->Resource->CategoryResource->validates()) {
				$this->Message->error(__('Could not validate CategoryResource'));
				return;
			}
			// if validation passes, then save the data
			$res = $this->Resource->CategoryResource->save();
			if (!$res) {
				$this->Message->error(__('Could not save the association'));
				return;
			}
		}
		$this->Message->success(__('The resource was sucessfully saved'));
		
		$data = array(
			'Resource.id' => $resource['Resource']['id']
		);
		$options = $this->Resource->getFindOptions('add', $data);
		$resources = $this->Resource->find('all', $options);
		$this->set('data', $resources[0]);
	}

/**
 * Update a resource
 */
	public function update() {
		// check the HTTP request method
		if (!$this->request->is('post')) {
			$this->Message->error(__('Invalid request method, should be POST'));
			return;
		}
		// check if data was provided
		if (!isset($this->request->data['Resource'])) {
			$this->Message->error(__('No data were provided'));
			return;
		}

		$resourcepost = $this->request->data;
		// check if the id is valid
		if (!Common::isUuid($resourcepost['Resource']['id'])) {
			$this->Message->error(__('The resource id invalid'));
			return;
		}

		// get the resource id
		$resource = $this->Resource->findById($resourcepost['Resource']['id']);
		if (!$resource) {
			$this->Message->error(__('The resource doesn\'t exist'));
			return;
		}

		$fields = $this->Resource->getFindFields('view');
		$mask = '/([a-zA-Z]*)\.([a-zA-Z_]*)/i';
		foreach ($fields['fields'] as $f) {
			preg_match($mask, $f, $matches);
			$model = $matches[1];
			$key = $matches[2];
			$resource[$model][$key] = $resourcepost[$model][$key];
		}
		$save = $this->Resource->save($resource);
		if (!$save) {
			$this->Message->error(__('The resource could not be updated'));
			return;
		}
		$this->Message->success(__('The resource was sucessfully updated'));
	}
}

