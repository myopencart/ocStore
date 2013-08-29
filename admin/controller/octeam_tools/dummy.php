<?php
class ControllerOcteamToolsDummy extends Controller {
	
	private $error = array();
	private $version = "0.1.ocs1531";
	
	public function index() {
		$this->load->language('octeam_tools/dummy');
		
		$this->document->setTitle($this->language->get('heading_title'));
		$this->data['heading_title'] = $this->language->get('heading_title').sprintf($this->language->get('version'), $this->version);
		
		$this->data['button_save'] = $this->language->get('button_save');
		$this->data['button_cancel'] = $this->language->get('button_cancel');
		
		$route = explode("/", $this->request->get['route']);
		$this->data['tool'] = end($route);
		
		/* warning */
		if (isset($this->error['warning'])) {
			$this->data['error_warning'] = $this->error['warning'];
		} else {
			$this->data['error_warning'] = '';
		}
		
		/* breadcrumbs */
		$this->data['breadcrumbs'] = array();
		$this->data['breadcrumbs'][] = array(
			'text'      => $this->language->get('text_home'),
			'href'      => $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL'),
			'separator' => false
		);
		$this->data['breadcrumbs'][] = array(
			'text'      => $this->language->get('text_octeam_toolset'),
			'href'      => $this->url->link('octeam/toolset', 'token=' . $this->session->data['token'], 'SSL'),
			'separator' => ' :: '
		);
		$this->data['breadcrumbs'][] = array(
			'text'      => $this->language->get('heading_title'),
			'href'      => $this->url->link('octeam_tools/dummy', 'token=' . $this->session->data['token'], 'SSL'),
			'separator' => ' :: '
		);
		
		$this->data['action'] = $this->url->link('octeam_tools/dummy', 'token=' . $this->session->data['token'], 'SSL');
		$this->data['cancel'] = $this->url->link('octeam/toolset', 'token=' . $this->session->data['token'], 'SSL');
		
		$this->template = 'octeam_tools/dummy.tpl';
		$this->children = array(
			'common/header',
			'common/footer'
		);
		
		$this->response->setOutput($this->render(TRUE), $this->config->get('config_compression'));
	}
	
	private function validate() {
		if (!$this->user->hasPermission('modify', 'octeam_tools/dummy')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}
		
		if (!$this->error) {
			return TRUE;
		} else {
			return FALSE;
		}
	}
	
	public function getCurrentVersion() {
		return $this->version;
	}
}
?>