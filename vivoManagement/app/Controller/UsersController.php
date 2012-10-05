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
	public $components = array(
        'Security' => array(
            'unlockedFields' => array(
                'User.current_password',
                'User.retype_password'
            )
        )
    );

    public function beforeFilter() {
        $this->Security->blackHoleCallback = 'blackhole';
    }

    public function blackhole($type) {
        debug($type);
    }
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
            if ( $this->request->data['User']['password'] == $this->request->data['User']['retype_password'] ){
                if ($this->User->validPassword($this->request->data['User']['password'])) {
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
                } else {
                    // Password didn't meet complexity requirements
                    $this->Session->setFlash(
                        __('The %s was did not meet password complexity requirements! Minimum 8 characters with at least 1 number or special character, 1 lowercase letter,  and 1 uppercase!', __('password')),
                        'alert',
                        array(
                            'plugin' => 'TwitterBootstrap',
                            'class' => 'alert-error'
                        )
                    );
                }
            } else {
                $this->Session->setFlash(
                    __('The %s could not be saved - your passwords did not match. Please, try again.', __('user')),
                    'alert',
                    array(
                        'plugin' => 'TwitterBootstrap',
                        'class' => 'alert-error'
                    )
                );
            }
		}
        if ($this->Session->read('FULL_ACCESS_GRANTED')) {
            $groups = $this->User->Group->find('list');
        } else {
            $groups = $this->Group->find('list', array(
                'conditions' => array(
                    'id >' => 1
                )
            ));
        }
        $this->set(compact('groups'));
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
        $this->Session->destroy();
		$this->redirect(array('admin' => false, 'controller' => 'users', 'action' => 'login'));

	}

    public function homePageFileOutput($id = null) {
        $this->User->id = $id;
        if (!$this->User->exists()) {
            throw new NotFoundException(__('Invalid %s', __('user')));
        }
        $this->set('user', $this->User->read(null, $id));
        $userFiles = $this->_getUserFileInformation($this->User->field('username'));
        return $userFiles;
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
        //debug($userFile);
        // Gather the filename, file size, file type, last modified date, and the full path to the file
        $returnArray['fileName'] = $userFile->name;
        $returnArray['fileSize'] = $userFile->size();
        $returnArray['fileType'] = $userFile->mime();
        $returnArray['fileModified'] = $userFile->lastChange();
        $returnArray['filePath'] = $userFile->path;
        $returnArray['fileExt'] = $userFile->ext();
        $returnArray['fileDir'] = $userFile->Folder->path;

        return $returnArray;
    }

    public function deleteUserFile() {
        $deleteUserFile = $this->request->query['deleteUserFile'];
        if (strpos($deleteUserFile, $this->Session->read('Auth.User.username'))) {
            if (unlink($deleteUserFile)) {
                $this->Session->setFlash(__('%s deleted', $deleteUserFile ),
                    'alert',
                    array(
                        'plugin' => 'TwitterBootstrap',
                        'class' => 'alert-success'
                    )
                );
                $this->redirect($this->referer());
            } else {
                $this->Session->setFlash(__('%s was not deleted', $deleteUserFile ),
                    'alert',
                    array(
                        'plugin' => 'TwitterBootstrap',
                        'class' => 'alert-error'
                    )
                );
                $this->redirect($this->referer());
            }
        }
    }
    public function change_password() {

        // The user id of the person who is changing their password
        $id = $this->Auth->user('id');

        if ($this->request->is('get')) {
            // We do not want to accept get passes as it is less secure
            throw new MethodNotAllowedException();
        }

        // Assign the passed user id into the User model
        $this->User->id = $id;

        if ($this->request->is('ajax')) {
            // Ajax does not need to render a view
            $this->autoRender=false;
        }

        if ( ! empty($this->request->data) ) {
            if ( AuthComponent::password($this->request->data['User']['current_password']) == $this->User->field('password')) {
                // The current password matches the provided current password so we will now process the rest of the call
                // We need to add the user id to the request data so that CakePHP will treat it as an update and not an add
                $this->request->data['User']['id'] = $id;

                if ($this->request->data['User']['new_password'] == $this->request->data['User']['retype_password']) {
                    // The new password matched the retype of the new password
                    if ($this->User->validPassword($this->request->data['User']['new_password'])) {
                        // The new password passes the strength test

                        $this->request->data['User']['password'] = $this->request->data['User']['new_password'];
                        $this->request->data['User']['modified_by'] = $this->Auth->user('id');

                        if ( $this->User->save($this->request->data, array('validate' => false)) ) {
                            if ($this->request->isAjax()) {
                                $this->Session->setFlash(
                                    __('The %s has been changed', __('password')),
                                    'alert',
                                    array(
                                        'plugin' => 'TwitterBootstrap',
                                        'class' => 'alert-success'
                                    )
                                );
                            } else {
                                $this->Session->setFlash(
                                    __('The %s has been changed', __('password')),
                                    'alert',
                                    array(
                                        'plugin' => 'TwitterBootstrap',
                                        'class' => 'alert-success'
                                    )
                                );
                            }
                        } else {
                            $this->Session->setFlash(
                                __('Your %s was not changed', __('password')),
                                'alert',
                                array(
                                    'plugin' => 'TwitterBootstrap',
                                    'class' => 'alert-error'
                                )
                            );
                        }
                    } else {
                        // New password didn't meet complexity requirements
                        $this->Session->setFlash(
                            __('Your %s was not changed - password complexity requirements not met. Minimum 8 characters with at least 1 number or special character, 1 lowercase letter,  and 1 uppercase!', __('password')),
                            'alert',
                            array(
                                'plugin' => 'TwitterBootstrap',
                                'class' => 'alert-error'
                            )
                        );
                    }
                } else {
                    // New password and retype password didn't match
                    $this->Session->setFlash(
                        __('Your %s was not changed - passwords provided didn\'t match', __('password')),
                        'alert',
                        array(
                            'plugin' => 'TwitterBootstrap',
                            'class' => 'alert-error'
                        )
                    );
                }
            } else {
                $this->Session->setFlash(
                    __('Your %s was not changed - current password incorrect', __('password')),
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
    }

}
