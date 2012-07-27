<?php 
defined('C5_EXECUTE') or die("Access Denied.");

class DashboardSystemOptimizationClearCacheController extends DashboardBaseController {
	
	public $helpers = array('form'); 
	
	public function view(){
	}
	
	public function do_clear() {
		if ($this->token->validate("clear_cache")) {
			if ($this->isPost()) {
				if (Cache::flush()) {
					$this->redirect('/dashboard/system/optimization/clear_cache', 'cache_cleared');
				}
			}
		} else {
			$this->set('error', array($this->token->getErrorMessage()));
		}
	}

	public function cache_cleared() {
		$this->set('message', t('Cached files removed.'));	
		$this->view();
	}
	
	
}
