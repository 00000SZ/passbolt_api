<?php
App::uses('ImageStorage', 'FileStorage.Model');

class ProfileAvatar extends ImageStorage {

/**
 * Details of use table
 *
 * @var array
 * @link http://book.cakephp.org/2.0/en/models/model-attributes.html
 */
	public $useTable = 'file_storage';

/**
 * Model behaviors
 *
 * @link http://api20.cakephp.org/class/model#
 */
	public $actsAs = array(
		'FileStorage.UploadValidator' => array(
			'allowedExtensions' => array(
				'jpg',
				'png',
			),
		),
	);

/**
 * beforeSave callback
 *
 * @param array $options
 * @return boolean true on success
 */
	public function beforeSave($options = array()) {
		if (!parent::beforeSave($options)) {
			return false;
		}

		// delete the previous avatar
		$this->deleteAll(array(
			'foreign_key' => $this->data['Avatar']['foreign_key']
		));

		return true;
	}

	/**
	 * After find callback.
	 *
	 * Is used to build an array of url for the images.
	 *
	 * @param mixed $results
	 * @param bool  $primary
	 *
	 * @return mixed
	 */
	public function afterFind($results, $primary = false) {
		$sizes = Configure::read('Media.imageSizes.ProfileAvatar');
		if (isset($results[0])) {
			foreach ($results as $key => $result) {
				$url = array();
				foreach($sizes as $size => $data) {
					$url[$size] = $this->imageUrl($result, $size);
				}
				$url['default'] = $this->imageUrl($results);
				$results[$key]['url'] = $url;
			}
		}
		else {
			$url = array();
			foreach($sizes as $size => $data) {
				$url[$size] = $this->imageUrl($results, $size);
			}
			$url['default'] = $this->imageUrl($results);
			$results['url'] = $url;
		}
		return $results;
	}

/**
 * Upload a file
 *
 * @param $foreignId
 * @param $data
 * @return mixed
 */
	public function upload($foreignId, $data) {
		// Check if an avatar has already been uploaded.
		$options['conditions']['Avatar.foreign_key'] = $foreignId;
		$avatar = $this->find('first', $options);

		// If an avatar exists, delete it and its versions.
		if (!empty($avatar)) {
			// Delete the versions of the file.
			$this->id = $avatar['Avatar']['id'];
			$operations = Configure::read('Media.imageSizes.ProfileAvatar');
			$Event = new CakeEvent('ImageVersion.removeVersion', $this, array(
				'record' => $avatar,
				'storage' => StorageManager::adapter('Local'),
				'operations' => $operations));
			CakeEventManager::instance()->dispatch($Event);

			// Delete the file.
			$imagePath = Configure::read('ImageStorage.basePath') . DS . $avatar['Avatar']['path'] . DS . $this->stripUuid($avatar['Avatar']['id']) . '.' . $avatar['Avatar']['extension'];

			$imagePath = $avatar['Avatar']['path'] . DS . $this->stripUuid($avatar['Avatar']['id']) . '.' . $avatar['Avatar']['extension'];
			StorageManager::adapter($avatar['Avatar']['adapter'])->delete($imagePath);

			// Delete the db resource.
			$this->delete($avatar['Avatar']['id']);
		}

		// Save the given avatar.
		$data[$this->alias]['adapter'] = 'Local';
		$data[$this->alias]['model'] = 'ProfileAvatar';
		$data[$this->alias]['extension'] = $this->fileExtension($data['Avatar']['file']['tmp_name']);
		$data[$this->alias]['foreign_key'] = $foreignId;
		$this->create();
		return $this->save($data);
	}

	/**
	 * Get Image Url for an entry.
	 *
	 * @param array $image
	 *   entry of the db
	 * @param string $version
	 *   version as defined in file storage configuration file.
	 * @param array $options
	 *
	 * @return bool
	 */
	public function imageUrl($image, $version = null, $options = array()) {
		if (empty($image) || empty($image['id'])) {
			return false;
		}

		if (!empty($version)) {
			$hash = Configure::read('Media.imageHashes.' . $image['model'] . '.' . $version);
			if (empty($hash)) {
				throw new \InvalidArgumentException(__d('file_storage', 'No valid version key (%s %s) passed!', @$image['model'], $version));
			}
		} else {
			$hash = null;
		}

		$Event = new CakeEvent('FileStorage.ImageHelper.imagePath', $this, array(
				'hash' => $hash,
				'image' => $image,
				'version' => $version,
				'options' => $options
			)
		);
		CakeEventManager::instance()->dispatch($Event);

		if ($Event->isStopped()) {
			return Configure::read('ImageStorage.publicPath') . $this->normalizePath($Event->data['path']);
		} else {
			return false;
		}
	}

	/**
	 * Turns the windows \ into / so that the path can be used in an url
	 *
	 * @param string $path
	 * @return string
	 */
	public function normalizePath($path) {
		return str_replace('\\', '/', $path);
	}

}
