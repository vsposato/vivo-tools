<?php
App::uses('AppController', 'Controller');
App::uses('Folder', 'Utility');
App::uses('File', 'Utility');
App::uses('PhpReader', 'Configure');

/**
 * Users Controller
 *
 * @property User $User
 */
class UsersController extends AppController {

/**
 * Helpers
 *
 * @var array
 */
	public $helpers = array('TwitterBootstrap.BootstrapHtml', 'TwitterBootstrap.BootstrapForm', 'TwitterBootstrap.BootstrapPaginator');
/**
 * Components
 *
 * @var array
 */
	public $components = array('Session');
/**
 * index method
 *
 * @return void
 */
	public function admin_index() {
		$this->User->recursive = 0;
		$this->set('users', $this->paginate());
	}

/**
 * view method
 *
 * @param string $id
 * @return void
 */
	public function view($id = null) {
		$this->User->id = $id;
		if (!$this->User->exists()) {
			throw new NotFoundException(__('Invalid %s', __('user')));
		}
        $this->set('user', $this->User->read(null, $id));
        $userFiles = $this->_getUserFileInformation($this->User->field('username'));
        $this->set('userFiles', $userFiles);
	}
    /**
     * view method
     *
     * @param string $id
     * @return void
     */
    public function admin_view($id = null) {
        $this->User->id = $id;
        if (!$this->User->exists()) {
            throw new NotFoundException(__('Invalid %s', __('user')));
        }
        $this->set('user', $this->User->read(null, $id));
    }

/**
 * add method
 *
 * @return void
 */
	public function admin_add() {
		if ($this->request->is('post')) {
			$this->User->create();
			if ($this->User->save($this->request->data)) {
				$this->Session->setFlash(__('%s saved.', __('User')),
						'alert',
						array(
							'plugin' => 'TwitterBootstrap',
							'class' => 'alert-success'
						)
				);
				$this->redirect(array('admin' => true, 'controller' => 'users', 'action' => 'index'));
			} else {
				$this->Session->setFlash(__('%s not saved.', __('User')),
					'alert',
					array(
						'plugin' => 'TwitterBootstrap',
						'class' => 'alert-error'
					)
				);
			}
		}
		$createdBies = $this->User->CreatedBy->find('list');
		$modifiedBies = $this->User->ModifiedBy->find('list');
		$this->set(compact('createdBies', 'modifiedBies'));
		$this->render('admin_add_edit');
	}

/**
 * edit method
 *
 * @param string $id
 * @return void
 */
	public function admin_edit($id = null) {
		$this->User->id = $id;
		if (!$this->User->exists()) {
			throw new NotFoundException(__('Invalid %s', __('user')));
		}
		if ($this->request->is('post') || $this->request->is('put')) {
			$this->request->data['User']['full_name'] = $this->request->data['User']['last_name'] . ", " . $this->request->data['User']['first_name'];
			if ($this->User->save($this->request->data)) {
				$this->Session->setFlash(__('The %s has been saved.', __('user')),
					'alert',
					array(
						'plugin' => 'TwitterBootstrap',
						'class' => 'alert-success'
					)
				);
				$this->redirect(array('admin' => true, 'controller' => 'users', 'action' => 'index'));
			} else {
				$this->Session->setFlash(__('%s not saved.', __('User')),
					'alert',
					array(
						'plugin' => 'TwitterBootstrap',
						'class' => 'alert-error'
					)
				);
			}
		} else {
			$this->request->data = $this->User->read(null, $id);
		}
		$this->render('admin_add_edit');
	}

/**
 * delete method
 *
 * @param string $id
 * @return void
 */
	public function admin_delete($id = null) {
		if (!$this->request->is('post')) {
			throw new MethodNotAllowedException();
		}
		$this->User->id = $id;
		if (!$this->User->exists()) {
			throw new NotFoundException(__('Invalid %s', __('user')));
		}
		if ($this->User->delete()) {
			$this->Session->setFlash(__('%s deleted', __('User')),
				'alert',
				array(
					'plugin' => 'TwitterBootstrap',
					'class' => 'alert-success'
				)
			);
			$this->redirect(array('admin' => true, 'controller' => 'users', 'action' => 'index'));
		}
		$this->Session->setFlash(__('%s was not deleted', __('User')),
			'alert',
			array(
				'plugin' => 'TwitterBootstrap',
				'class' => 'alert-error'
			)
		);
		$this->redirect(array('admin' => true, 'controller' => 'users', 'action' => 'index'));
	}


/**
 * login method
 *
 * @param string $id
 * @return void
 */
	public function login() {
		if ($this->request->is('post')) {
			if ($this->Auth->login()) {
				// Read the authenticated user_error
				$userSession = $this->Session->read('Auth.User');
				// Explode out the list of Full Access Administrative groups from the Constants
				$fullAccessGroups = explode(",", FULL_ACCESS_GROUPS);
				if ( in_array($userSession['group_id'], $fullAccessGroups) ) {
					$this->Session->write('FULL_ACCESS_GRANTED',true);
					$this->redirect($this->Auth->redirect());
					return;
				} else {
					$this->redirect($this->Auth->redirect());
					return;
				}

			} else {
				$this->Session->setFlash(__('Your %s or %s was incorrect.', __('username'), __('password')),
					'alert',
					array(
						'plugin' => 'TwitterBootstrap',
						'class' => 'alert-error'
					)
				);
			}
		}
		if (SHIBBOLETH_REQUIRED) {
			$this->render('login_shibboleth');
		} elseif (! SHIBBOLETH_REQUIRED) {
			$this->render('login_normal');
		}

	}
/**
 * logout method
 *
 * @param string $id
 * @return void
 */
	public function logout() {
		$this->Auth->logout();
		$this->redirect(array('admin' => false, 'controller' => 'users', 'action' => 'login'));

	}

    private function _getUserFileInformation($username) {
        // Setup configuration reader
        Configure::config('default', new PhpReader());
        // Now we need to load a configuration file for SPARQL
        Configure::load('sparql', 'default', false);
        // Load the base save directory into memory
        $baseDirectory = Configure::read('sparqlBaseDir');
        $this->set('baseDirectory', $baseDirectory);

        $userDirectory = new Folder($baseDirectory . $username);
        $userFiles = $userDirectory->find('.*', true);

        $fileArray = array();
        foreach ($userFiles as $userFile) {
            $displayFile = new File($baseDirectory . $username . '/' . $userFile);

            $fileArray[] = $this->_createFileArrayRow($displayFile);
        }

        return $fileArray;
    }

    private function _createFileArrayRow(File $userFile) {
        // Initialize new files array
        $returnArray = array();

        // Gather the filename, file size, file type, last modified date, and the full path to the file
        $returnArray['fileName'] = $userFile->name();
        $returnArray['fileSize'] = $userFile->size();
        $returnArray['fileType'] = $userFile->mime();
        $returnArray['fileModified'] = $userFile->lastChange();
        $returnArray['filePath'] = $userFile->path;
        $returnArray['fileExt'] = $userFile->ext();
        $returnArray['fileDir'] = $userFile->Folder->path;

        return $returnArray;
    }

    public function deleteUserFile() {
        $deleteUserFile = $this->passedArgs['deleteUserFile'];
        debug($deleteUserFile);
        if (strpos($deleteUserFile, $this->Session->read('Auth.User.username'))) {
            if (unlink($deleteUserFile)) {
                $this->Session->setFlash(__('%s deleted', __('User')),
                    'alert',
                    array(
                        'plugin' => 'TwitterBootstrap',
                        'class' => 'alert-success'
                    )
                );
                $this->redirect(array('admin' => true, 'controller' => 'users', 'action' => 'index'));
            }
        }
    }

}
