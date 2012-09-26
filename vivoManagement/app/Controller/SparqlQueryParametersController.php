<?php
App::uses('AppController', 'Controller');
/**
 * SparqlQueryParameters Controller
 *
 * @property SparqlQueryParameter $SparqlQueryParameter
 */
class SparqlQueryParametersController extends AppController {

/**
 *  Layout
 *
 * @var string
 */
	public $layout = 'bootstrap';

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
		$this->SparqlQueryParameter->recursive = 0;
		$this->set('sparqlQueryParameters', $this->paginate());
	}

/**
 * view method
 *
 * @param string $id
 * @return void
 */
	public function view($id = null) {
		$this->SparqlQueryParameter->id = $id;
		if (!$this->SparqlQueryParameter->exists()) {
			throw new NotFoundException(__('Invalid %s', __('sparql query parameter')));
		}
		$this->set('sparqlQueryParameter', $this->SparqlQueryParameter->read(null, $id));
	}

/**
 * add method
 *
 * @return void
 */
	public function add() {
		if ($this->request->is('post')) {
			$this->SparqlQueryParameter->create();
			if ($this->SparqlQueryParameter->save($this->request->data)) {
				$this->Session->setFlash(
					__('The %s has been saved', __('sparql query parameter')),
					'alert',
					array(
						'plugin' => 'TwitterBootstrap',
						'class' => 'alert-success'
					)
				);
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(
					__('The %s could not be saved. Please, try again.', __('sparql query parameter')),
					'alert',
					array(
						'plugin' => 'TwitterBootstrap',
						'class' => 'alert-error'
					)
				);
			}
		}
		$sparqlQueries = $this->SparqlQueryParameter->SparqlQuery->find('list');
		$createdBies = $this->SparqlQueryParameter->CreatedBy->find('list');
		$this->set(compact('sparqlQueries', 'createdBies'));
	}

/**
 * edit method
 *
 * @param string $id
 * @return void
 */
	public function edit($id = null) {
		$this->SparqlQueryParameter->id = $id;
		if (!$this->SparqlQueryParameter->exists()) {
			throw new NotFoundException(__('Invalid %s', __('sparql query parameter')));
		}
		if ($this->request->is('post') || $this->request->is('put')) {
			if ($this->SparqlQueryParameter->save($this->request->data)) {
				$this->Session->setFlash(
					__('The %s has been saved', __('sparql query parameter')),
					'alert',
					array(
						'plugin' => 'TwitterBootstrap',
						'class' => 'alert-success'
					)
				);
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(
					__('The %s could not be saved. Please, try again.', __('sparql query parameter')),
					'alert',
					array(
						'plugin' => 'TwitterBootstrap',
						'class' => 'alert-error'
					)
				);
			}
		} else {
			$this->request->data = $this->SparqlQueryParameter->read(null, $id);
		}
		$sparqlQueries = $this->SparqlQueryParameter->SparqlQuery->find('list');
		$createdBies = $this->SparqlQueryParameter->CreatedBy->find('list');
		$this->set(compact('sparqlQueries', 'createdBies'));
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
		$this->SparqlQueryParameter->id = $id;
		if (!$this->SparqlQueryParameter->exists()) {
			throw new NotFoundException(__('Invalid %s', __('sparql query parameter')));
		}
		if ($this->SparqlQueryParameter->delete()) {
			$this->Session->setFlash(
				__('The %s deleted', __('sparql query parameter')),
				'alert',
				array(
					'plugin' => 'TwitterBootstrap',
					'class' => 'alert-success'
				)
			);
			$this->redirect(array('action' => 'index'));
		}
		$this->Session->setFlash(
			__('The %s was not deleted', __('sparql query parameter')),
			'alert',
			array(
				'plugin' => 'TwitterBootstrap',
				'class' => 'alert-error'
			)
		);
		$this->redirect(array('action' => 'index'));
	}
}
