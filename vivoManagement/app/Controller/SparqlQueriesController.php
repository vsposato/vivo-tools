<?php
App::uses('AppController', 'Controller');
App::uses('PhpReader', 'Configure');
App::uses('Folder', 'Utility');
App::uses('File', 'Utility');

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
	public $helpers = array('TwitterBootstrap.BootstrapHtml', 'TwitterBootstrap.BootstrapForm', 'TwitterBootstrap.BootstrapPaginator', 'Number');
/**
 * Components
 *
 * @var array
 */
	public $components = array('Session', 'Sparql');
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
            if (! $this->request->data['SparqlQuery']['parameterized'] ) {
                // If we are not parameterizing the query then we need to remove validation
                $this->SparqlQuery->validator()->remove('parameter');
                $this->SparqlQuery->validator()->remove('parameter_type');
            }
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
            //debug($this->request->data);
            if (! $this->request->data['SparqlQuery']['parameterized'] ) {
                // If we are not parameterizing the query then we need to remove validation
                $this->SparqlQuery->validator()->remove('parameter');
                $this->SparqlQuery->validator()->remove('parameter_type');
            }
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
			if ( ! $this->request->data['QueryUserCreated']['id'] == AuthComponent::user('id') && ! $this->Session->read('FULL_ACCESS_GRANTED') == true ) {
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
		$this->set(compact('id'));
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
	}

	public function execute($id = null) {
		if ($id == null && ($this->request->is('post') || $this->request->is('put')))  {
			$id = $this->request->data['SparqlQuery']['id'];
		}
		$this->SparqlQuery->id = $id;
		if (!$this->SparqlQuery->exists()) {
			throw new NotFoundException(__('Invalid %s', __('sparql query')));
		}

		if ($this->request->is('post') || $this->request->is('put')) {
			//die(debug($this->request->data));
			switch ($this->request->data['Execute']['outputFormat']) {
				case 'csv':
                    // Create the core filename for use later
                    $filename = $this->_generateFileDownloadName($this->request->data['SparqlQuery']['name'], '.csv');
					// Create the name of the file we will be saving
					$fullFileName = $this->_generateFileDownloadDirectory() . $filename;
                    if ($this->request->data['Execute']['parameter_file']) {
                        //debug($this->request->data['Execute']['parameter_file']['tmp_name']);
                        // There are parameters for this we need to process
                        $parameterData = $this->_readParameterFile($this->request->data['Execute']['parameter_file']['tmp_name']);
                        array_unshift($parameterData, array(0 => $this->request->data['SparqlQuery']['parameter']), array(0 => $this->request->data['SparqlQuery']['parameter_type']));
                        // Retrieve the filename from the SPARQL query
                        $resultFile = $this->Sparql->generateResults($this->request->data['SparqlQuery']['sparql_query'], $fullFileName, 'csv', true, $parameterData);
                    } else {
                        // Retrieve the filename from the SPARQL query
                        $resultFile = $this->Sparql->generateResults($this->request->data['SparqlQuery']['sparql_query'], $fullFileName, 'csv');
                    }
					// If it was a success, download the file
					if ($resultFile) {
						$this->Session->setFlash(
							__('The %s completed successfully. Please check your downloads directory!', __('sparql query')),
							'alert',
							array(
								'plugin' => 'TwitterBootstrap',
								'class' => 'alert-success'
							)
						);
                        $this->sendFileDownload($filename,$this->_generateFileDownloadDirectory(), '.csv');
					} elseif (! $resultFile) {
						$this->Session->setFlash(
							__('The %s was not completed successfully!', __('sparql query')),
							'alert',
							array(
								'plugin' => 'TwitterBootstrap',
								'class' => 'alert-error'
							)
						);
					}
				break;
				case 'rdf':
                    // Create the core filename for use later
                    $filename = $this->_generateFileDownloadName($this->request->data['SparqlQuery']['name'], '.xml');
					// Create the name of the file we will be saving
					$fullFileName = $this->_generateFileDownloadDirectory() . $filename;
                    if ($this->request->data['Execute']['parameter_file']) {
                        //debug($this->request->data['Execute']['parameter_file']['tmp_name']);
                        // There are parameters for this we need to process
                        $parameterData = $this->_readParameterFile($this->request->data['Execute']['parameter_file']['tmp_name']);
                        array_unshift($parameterData, array(0 => $this->request->data['SparqlQuery']['parameter']), array(0 => $this->request->data['SparqlQuery']['parameter_type']));
                        // Retrieve the filename from the SPARQL query
                        $resultFile = $this->Sparql->generateResults($this->request->data['SparqlQuery']['sparql_query'], $fullFileName, 'rdf', true, $parameterData);
                    } else {
                        // Retrieve the filename from the SPARQL query
                        $resultFile = $this->Sparql->generateResults($this->request->data['SparqlQuery']['sparql_query'], $fullFileName, 'rdf');
                    }
                    // If it was a success, download the file
					if ($resultFile) {
						$this->Session->setFlash(
							__('The %s completed successfully. Please check your downloads directory!', __('sparql query')),
							'alert',
							array(
								'plugin' => 'TwitterBootstrap',
								'class' => 'alert-success'
							)
						);
                        $this->sendFileDownload($filename,$this->_generateFileDownloadDirectory(), '.xml');
					} elseif (! $resultFile) {
						$this->Session->setFlash(
							__('The %s was not completed successfully!', __('sparql query')),
							'alert',
							array(
								'plugin' => 'TwitterBootstrap',
								'class' => 'alert-error'
							)
						);
					}
				break;
				case 'tsv':
                    // Create the core filename for use later
                    $filename = $this->_generateFileDownloadName($this->request->data['SparqlQuery']['name'], '.tsv');
                    // Create the name of the file we will be saving
					$fullFileName = $this->_generateFileDownloadDirectory() . $filename;
					// Retrieve the filename from the SPARQL query
					$resultFile = $this->Sparql->generateResults($this->request->data['SparqlQuery']['sparql_query'], $fullFileName, 'tsv');
					// If it was a success, download the file
					if ($resultFile) {
						$this->Session->setFlash(
							__('The %s completed successfully. Please check your downloads directory!', __('sparql query')),
							'alert',
							array(
								'plugin' => 'TwitterBootstrap',
								'class' => 'alert-success'
							)
						);
						$this->sendFileDownload($filename,$this->_generateFileDownloadDirectory(), '.tsv');
					} elseif (! $resultFile) {
						$this->Session->setFlash(
							__('The %s was not completed successfully!', __('sparql query')),
							'alert',
							array(
								'plugin' => 'TwitterBootstrap',
								'class' => 'alert-error'
							)
						);
					}
				break;
				case 'array':

				break;
			}

		} else {
			$this->request->data = $this->SparqlQuery->read(null, $id);

		}

		$constructStatement = stripos($this->request->data['SparqlQuery']['sparql_query'], 'CONSTRUCT');
		if ($constructStatement === false) {
			$this->set('construct', false);
		} elseif ($constructStatement !== false) {
			$this->set('construct', true);
		}

		$parameterizedQuery = $this->request->data['SparqlQuery']['parameterized'] ? true : false;
		if ($parameterizedQuery) {
			$this->set('parameterized', $parameterizedQuery);
		} elseif (! $parameterizedQuery) {
			$this->set('parameterized', $parameterizedQuery);
		}
		$this->set('sparqlQuery', $this->request->data);
	}

	private function _generateFileDownloadName($queryName = null, $extension = '.csv') {
		if ($queryName == null) {
			$queryName = 'NotProvided';
		} else {
			$queryName = str_replace(' ','',$queryName);
		}

		$user = $this->Session->read('Auth.User');
		$user_name = $user['username'];
		$date = date('YmdHis');

		return ($user_name . '_' . $queryName . '_' . $date . $extension);

	}

	public function sendFileDownload($filename = null, $directory = null, $extension = null) {
        if ($this->request->is('get')) {
            if (! $filename) {
                $filename = $this->request->query['filename'];
            }
            if (! $directory) {
                $directory = $this->request->query['directory'] . "/";
            }
            if (! $extension) {
                $extension = '.' . $this->request->query['extension'];
            }
        }
		$this->viewClass = 'Media';

		$parameters = array(
			'id' => $filename,
			'download' => true,
			'extension' => $extension,
			'path' => $directory
		);

		$this->set($parameters);
	}

	private function _generateFileDownloadDirectory() {
		// Setup configuration reader
		Configure::config('default', new PhpReader());
		// Now we need to load a configuration file for SPARQL
		Configure::load('sparql', 'default', false);
		// Load the base save directory into memory
		$baseDirectory = Configure::read('sparqlBaseDir');
		// Need to see if user has a folder
		// Get user information from the session
		$user = $this->Session->read('Auth.User');
		// Get the username from the user record - this is the name of their directory
		$user_name = $user['username'];
		// Define the full folder name
		$fullFolderName = $baseDirectory . $user_name . '/';
		// Check to see if it exists
		$folder = new Folder($fullFolderName, true, 0755);

		if ($folder) {
			// The folder exists, or it did not and we created it
			return $fullFolderName;
		} elseif (! $folder) {
			// The folder didn't exist and we couldn't create it
			return false;
		}
	}

    private function _readParameterFile($parameterFile) {
        // First check to see if the file exists
        if (! file_exists($parameterFile)) {
            // If it doesn't exist then error out
            echo "Parameter file does not exists - $$parameterFile";
            return false;
        }
        try {
            // Open the Parameter file
            $parameterFileHandle = fopen($parameterFile, 'r');

            // Initialize a blanke SPARQL query string
            $parameterArray = array();

            while (! feof($parameterFileHandle) ) {
                // Get a row of data from the CSV parameter file and add it to the numeric indexed array
                $parameterArray[] = fgetcsv($parameterFileHandle);
            }
            // Return the SPARQL query back to the calling function
            return $parameterArray;

        } catch (Exception $e) {
            // Something happened and we couldn't complete the read of the parameter CSV file
            echo "Exception in readParameterFile function - $e";
            print_r($e);
            exit;
        }
    }

}
