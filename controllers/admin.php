<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Cleaner
 *
 * Controller for the cleaner module
 * 
 * @author			cmfolio
 * @link					http://web.cmfolio.com
 * @package		Cleaner
 * @category		Modules
 */
class Admin extends Admin_Controller
{
	
	private $_cache_root;
	
	/**
	 * Constructor method
	 *
	 * @access public
	 * @return void
	 */
	public function __construct()
	{
		parent::__construct();
		
		$this->load->language('cleaner');

		$this->template->append_metadata( css('cleaner.css', 'cleaner') )
				->set_partial('shortcuts', 'admin/partials/shortcuts');
	}

	// --------------------------------------------------------------------------	
	
	/**
	 * Show different cleaning techs
	 *
	 * @access	public
	 */
	public function index()
	{
		$this->template->build('admin/index', $this->data);
	}

	// --------------------------------------------------------------------------	
	
	public function cache($action = null) {
		$this->load->helper('form');
		$this->load->helper('directory');
		$this->_cache_root = $this->config->item('cache_dir');
		
		// if we are cleaning, delete the files
		if ($action == 'clean') {
			$data = $this->input->post();
			$results = array('success' => '', 'error' => '');
			
			if (count($data) == 0) {
				redirect('admin/cleaner/cache');
			}
			
			unset($data['submit']);
			
			// delete; first try as a folder, then as a file
			foreach ($data as $key) {
				$path = $this->_cache_root . $key;
				if (is_dir($path)) {
					$results[$this->_delete_folder($path) ? 'success' : 'error'] .= ', ' . ucwords(str_replace('_m', '', $key));
				}
				if (is_file($path)) {
					$results[$this->_delete_file($path) ? 'success' : 'error'] .= ', ' . ucwords(str_replace('_m', '', $key));
				}
			}
			
			if ($results['success'] != '') {
				$this->session->set_flashdata('success', 'Successfully deleted <strong>' . trim($results['success'], ', ') . '</strong>.');
			}
			if ($results['error'] != '') {
				$this->session->set_flashdata('error', 'Failed to delete <strong>' . trim($results['error'], ', ') . '</strong>.');
			}
			redirect('admin/cleaner/cache');
		}
		
		// scan cache directory
		$files = directory_map($this->_cache_root, 2);
		
		// remove directories with no files and mask files as folders
		foreach ($files as $key => $value) {
			if (count($value) == 0) {
				unset($files[$key]);
			}
			else if (is_int($key)) {
				$files[$value] = '[file]';
				unset($files[$key]);
			}
		}
		
		$this->data['files'] = $files;
	
		$this->template->build('admin/cache', $this->data);
	}
	
	
	private function _delete_folder($path = null) {
		if (!is_null($path) && is_dir($path)) {
			$this->load->helper('file');
			
			return delete_files($path, TRUE);
		}
		return false;
	}
	
	
	private function _delete_file($path = null) {
		if (!is_null($path) && is_file($path)) {			
			return unlink($path);
		}
		return false;
	}

	
}

/* End of file admin.php */