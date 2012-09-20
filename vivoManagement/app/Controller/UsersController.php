<?php
App::uses('AppController', 'Controller');
/**
 * Users Controller
 *
 * @property User $User
 */
class UsersController extends AppController {

/**
 *  Layout
 *
 * @var string
 */
//	public $layout = 'bootstrap';

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
				$this->redirect($this->Auth->redirect());
				return;
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

}
