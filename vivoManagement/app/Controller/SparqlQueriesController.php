<?php
App::uses('AppController', 'Controller');
/**
 * SparqlQueries Controller
 *
 * @property SparqlQuery $SparqlQuery
 */
class SparqlQueriesController extends AppController {

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
	public function index() {
		$this->SparqlQuery->recursive = 0;
		$this->set('sparqlQueries', $this->paginate());
	}

/**
 * view method
 *
 * @param string $id
 * @return void
 */
	public function view($id = null) {
		$this->SparqlQuery->id = $id;
		if (!$this->SparqlQuery->exists()) {
			throw new NotFoundException(__('Invalid %s', __('sparql query')));
		}
		$this->set('sparqlQuery', $this->SparqlQuery->read(null, $id));
	}

/**
 * add method
 *
 * @return void
 */
	public function add() {
		if ($this->request->is('post')) {
			$this->SparqlQuery->create();
			if ($this->SparqlQuery->save($this->request->data)) {
				$this->Session->setFlash(
					__('The %s has been saved', __('sparql query')),
					'alert',
					array(
						'plugin' => 'TwitterBootstrap',
						'class' => 'alert-success'
					)
				);
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(
					__('The %s could not be saved. Please, try again.', __('sparql query')),
					'alert',
					array(
						'plugin' => 'TwitterBootstrap',
						'class' => 'alert-error'
					)
				);
			}
		}
		$queryUserCreateds = $this->SparqlQuery->QueryUserCreated->find('list');
		$queryUserModifieds = $this->SparqlQuery->QueryUserModified->find('list');
		$createdBies = $this->SparqlQuery->CreatedBy->find('list');
		$modifiedBies = $this->SparqlQuery->ModifiedBy->find('list');
		$this->set(compact('queryUserCreateds', 'queryUserModifieds', 'createdBies', 'modifiedBies'));
	}

/**
 * edit method
 *
 * @param string $id
 * @return void
 */
	public function edit($id = null) {
		$this->SparqlQuery->id = $id;
		if (!$this->SparqlQuery->exists()) {
			throw new NotFoundException(__('Invalid %s', __('sparql query')));
		}
		if ($this->request->is('post') || $this->request->is('put')) {
			if ($this->SparqlQuery->save($this->request->data)) {
				$this->Session->setFlash(
					__('The %s has been saved', __('sparql query')),
					'alert',
					array(
						'plugin' => 'TwitterBootstrap',
						'class' => 'alert-success'
					)
				);
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(
					__('The %s could not be saved. Please, try again.', __('sparql query')),
					'alert',
					array(
						'plugin' => 'TwitterBootstrap',
						'class' => 'alert-error'
					)
				);
			}
		} else {
			$this->request->data = $this->SparqlQuery->read(null, $id);
		}
		$queryUserCreateds = $this->SparqlQuery->QueryUserCreated->find('list');
		$queryUserModifieds = $this->SparqlQuery->QueryUserModified->find('list');
		$createdBies = $this->SparqlQuery->CreatedBy->find('list');
		$modifiedBies = $this->SparqlQuery->ModifiedBy->find('list');
		$this->set(compact('queryUserCreateds', 'queryUserModifieds', 'createdBies', 'modifiedBies'));
	}

/**
 * delete method
 *
 * @param string $id
 * @return void
 */
	public function delete($id = null) {
		if (!$this->request->is('post')) {
			throw new MethodNotAllowedException();
		}
		$this->SparqlQuery->id = $id;
		if (!$this->SparqlQuery->exists()) {
			throw new NotFoundException(__('Invalid %s', __('sparql query')));
		}
		if ($this->SparqlQuery->delete()) {
			$this->Session->setFlash(
				__('The %s deleted', __('sparql query')),
				'alert',
				array(
					'plugin' => 'TwitterBootstrap',
					'class' => 'alert-success'
				)
			);
			$this->redirect(array('action' => 'index'));
		}
		$this->Session->setFlash(
			__('The %s was not deleted', __('sparql query')),
			'alert',
			array(
				'plugin' => 'TwitterBootstrap',
				'class' => 'alert-error'
			)
		);
		$this->redirect(array('action' => 'index'));
	}/**
 * admin_index method
 *
 * @return void
 */
	public function admin_index() {
		$this->SparqlQuery->recursive = 0;
		$this->set('sparqlQueries', $this->paginate());
	}

/**
 * admin_view method
 *
 * @param string $id
 * @return void
 */
	public function admin_view($id = null) {
		$this->SparqlQuery->id = $id;
		if (!$this->SparqlQuery->exists()) {
			throw new NotFoundException(__('Invalid %s', __('sparql query')));
		}
		$this->set('sparqlQuery', $this->SparqlQuery->read(null, $id));
	}

/**
 * admin_add method
 *
 * @return void
 */
	public function admin_add() {
		if ($this->request->is('post')) {
			$this->SparqlQuery->create();
			if ($this->SparqlQuery->save($this->request->data)) {
				$this->Session->setFlash(
					__('The %s has been saved', __('sparql query')),
					'alert',
					array(
						'plugin' => 'TwitterBootstrap',
						'class' => 'alert-success'
					)
				);
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(
					__('The %s could not be saved. Please, try again.', __('sparql query')),
					'alert',
					array(
						'plugin' => 'TwitterBootstrap',
						'class' => 'alert-error'
					)
				);
			}
		}
		$queryUserCreateds = $this->SparqlQuery->QueryUserCreated->find('list');
		$queryUserModifieds = $this->SparqlQuery->QueryUserModified->find('list');
		$createdBies = $this->SparqlQuery->CreatedBy->find('list');
		$modifiedBies = $this->SparqlQuery->ModifiedBy->find('list');
		$this->set(compact('queryUserCreateds', 'queryUserModifieds', 'createdBies', 'modifiedBies'));
	}

/**
 * admin_edit method
 *
 * @param string $id
 * @return void
 */
	public function admin_edit($id = null) {
		$this->SparqlQuery->id = $id;
		if (!$this->SparqlQuery->exists()) {
			throw new NotFoundException(__('Invalid %s', __('sparql query')));
		}
		if ($this->request->is('post') || $this->request->is('put')) {
			if ($this->SparqlQuery->save($this->request->data)) {
				$this->Session->setFlash(
					__('The %s has been saved', __('sparql query')),
					'alert',
					array(
						'plugin' => 'TwitterBootstrap',
						'class' => 'alert-success'
					)
				);
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(
					__('The %s could not be saved. Please, try again.', __('sparql query')),
					'alert',
					array(
						'plugin' => 'TwitterBootstrap',
						'class' => 'alert-error'
					)
				);
			}
		} else {
			$this->request->data = $this->SparqlQuery->read(null, $id);
		}
		$queryUserCreateds = $this->SparqlQuery->QueryUserCreated->find('list');
		$queryUserModifieds = $this->SparqlQuery->QueryUserModified->find('list');
		$createdBies = $this->SparqlQuery->CreatedBy->find('list');
		$modifiedBies = $this->SparqlQuery->ModifiedBy->find('list');
		$this->set(compact('queryUserCreateds', 'queryUserModifieds', 'createdBies', 'modifiedBies'));
	}

/**
 * admin_delete method
 *
 * @param string $id
 * @return void
 */
	public function admin_delete($id = null) {
		if (!$this->request->is('post')) {
			throw new MethodNotAllowedException();
		}
		$this->SparqlQuery->id = $id;
		if (!$this->SparqlQuery->exists()) {
			throw new NotFoundException(__('Invalid %s', __('sparql query')));
		}
		if ($this->SparqlQuery->delete()) {
			$this->Session->setFlash(
				__('The %s deleted', __('sparql query')),
				'alert',
				array(
					'plugin' => 'TwitterBootstrap',
					'class' => 'alert-success'
				)
			);
			$this->redirect(array('action' => 'index'));
		}
		$this->Session->setFlash(
			__('The %s was not deleted', __('sparql query')),
			'alert',
			array(
				'plugin' => 'TwitterBootstrap',
				'class' => 'alert-error'
			)
		);
		$this->redirect(array('action' => 'index'));
	}
}
