<?php
class ControllerOcteamToolset extends Controller {
	public function index() {
		$this->load->language('octeam/toolset');
		
		$this->document->setTitle($this->language->get('heading_title')); 
		
		$data['breadcrumbs'] = array();
		$data['breadcrumbs'][] = array(
			'text'      => $this->language->get('text_home'),
			'href'      => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], 'SSL')
		);
		$data['breadcrumbs'][] = array(
			'text'      => $this->language->get('heading_title'),
			'href'      => $this->url->link('octeam/toolset', 'token=' . $this->session->data['token'], 'SSL')
		);
		
		$data['heading_title'] = $this->language->get('heading_title');
		
		$data['text_no_results'] = $this->language->get('text_no_results');
		$data['text_confirm'] = $this->language->get('text_confirm');
		
		$data['column_name'] = $this->language->get('column_name');
		$data['column_description'] = $this->language->get('column_description');
		$data['column_action'] = $this->language->get('column_action');
		
		if (isset($this->session->data['success'])) {
			$data['success'] = $this->session->data['success'];
			unset($this->session->data['success']);
		} else {
			$data['success'] = '';
		}
		
		if (isset($this->session->data['error'])) {
			$data['error'] = $this->session->data['error'];
			unset($this->session->data['error']);
		} else {
			$data['error'] = '';
		}
		
		/*
		$this->load->model('setting/tool');
		$tools = $this->model_setting_tool->getInstalled('tool');
		
		foreach ($tools as $key => $value) {
			if (!file_exists(DIR_APPLICATION . 'controller/octeam_tools/' . $value . '.php')) {
				$this->model_setting_tool->uninstall('tool', $value);
				unset($tools[$key]);
			}
		}
		*/
		
		$data['tools'] = array();
		$files = glob(DIR_APPLICATION . 'controller/octeam_tools/*.php');
		
		if ($files) {
			foreach ($files as $file) {
				$tool = basename($file, '.php');
				$this->load->language('octeam_tools/'.$tool);
				
				$action = array();
				
				/*
				if (!in_array($tool, $tools)) {
					$action[] = array(
						'text' => $this->language->get('text_install'),
						'href' => $this->url->link('octeam/toolset/install', 'token=' . $this->session->data['token'] . '&tool=' . $tool, 'SSL')
					);
				} else {
					$action[] = array(
						'text' => $this->language->get('text_edit'),
						'href' => $this->url->link('octeam/toolset/' . $tool . '', 'token=' . $this->session->data['token'], 'SSL')
					);
					$action[] = array(
						'text' => $this->language->get('text_uninstall'),
						'href' => $this->url->link('octeam/toolset/uninstall', 'token=' . $this->session->data['token'] . '&tool=' . $tool, 'SSL')
					);
				}
				*/
				$action[] = array(
					//'text' => $this->language->get('text_install'),
					//'href' => $this->url->link('octeam/toolset/install', 'token=' . $this->session->data['token'] . '&tool=' . $tool, 'SSL')
					//'text' => $this->language->get('text_edit'),
					'text' => $this->language->get('text_open'),
					'href' => $this->url->link('octeam_tools/'.$tool, 'token='.$this->session->data['token'], 'SSL')
				);
				
				
				$text_link = $this->language->get('text_' . $tool);
				if ($text_link != 'text_' . $tool) {
					$link = $this->language->get('text_' . $tool);
				} else {
					$link = '';
				}
				
				require_once(DIR_APPLICATION . 'controller/octeam_tools/' . $tool . '.php');
				$class = 'ControllerOcteamTools' . str_replace('_', '', $tool);
				$class = new $class($this->registry);
				if (method_exists($class, 'getCurrentVersion')) {
					$version = sprintf($this->language->get('version'), $class->getCurrentVersion());
				} else {
					$version = '';
				}
				
				$data['tools'][] = array(
					'name'            => $this->language->get('heading_title').$version,
					'description'     => $this->language->get('text_description'),
					'link'            => $link,
					'action'          => $action
				);
			}
		}
		
		$data['header']       = $this->load->controller('common/header');
		$data['column_left']  = $this->load->controller('common/column_left');
		$data['footer']       = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('octeam/toolset.tpl', $data));
	}
	
}
?>