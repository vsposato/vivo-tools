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
		$this->render('add_edit');
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
			// Check to determine if the user is allowed to edit this function
			if ( $sparqlQuery['QueryUserCreated']['id'] == AuthComponent::user('id') || $this->Session->read('FULL_ACCESS_GRANTED') == true ) {
				$this->Session->setFlash(
					__('The %s could not be accessed - permission denied', __('sparql query')),
					'alert',
					array(
						'plugin' => 'TwitterBootstrap',
						'class' => 'alert-error'
					)
				);
				// Redirect back to the index page
				$this->redirect(array('admin' => false, 'plugin' => '', 'controller' => 'sparql_queries', 'action' => 'index'));
			}

		}
		$this->render('add_edit');
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
}
