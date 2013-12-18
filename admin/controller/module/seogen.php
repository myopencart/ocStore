<?php
class ControllerModuleSeogen extends Controller {
	private $error = array();

	public function index() {

		$this->data += $this->load->language('module/seogen');

		$this->document->setTitle(strip_tags($this->language->get('heading_title')));

		$this->load->model('setting/setting');

		if(($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
			$this->model_setting_setting->editSetting('seogen', $this->request->post);

			$this->session->data['success'] = $this->language->get('text_success');

			$this->redirect($this->url->link('extension/module', 'token=' . $this->session->data['token'], 'SSL'));
		}

		if(isset($this->error['warning'])) {
			$this->data['error_warning'] = $this->error['warning'];
		} else {
			$this->data['error_warning'] = '';
		}

		$this->data['breadcrumbs'] = array();

		$this->data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL'),
			'separator' => false
		);

		$this->data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_module'),
			'href' => $this->url->link('extension/module', 'token=' . $this->session->data['token'], 'SSL'),
			'separator' => ' :: '
		);

		$this->data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('module/seogen', 'token=' . $this->session->data['token'], 'SSL'),
			'separator' => ' :: '
		);

		$this->data['action'] = $this->url->link('module/seogen', 'token=' . $this->session->data['token'], 'SSL');
		$this->data['generate'] = $this->url->link('module/seogen/generate', 'token=' . $this->session->data['token'], 'SSL');
		$this->data['cancel'] = $this->url->link('extension/module', 'token=' . $this->session->data['token'], 'SSL');

		$this->load->model('design/layout');

		$this->data['layouts'] = $this->model_design_layout->getLayouts();

		if(isset($this->request->post['seogen'])) {
			$this->data['seogen'] = $this->request->post['seogen'];
		} elseif($this->config->get('seogen')) {
			$this->data['seogen'] = $this->config->get('seogen');
		}

		$this->template = 'module/seogen.tpl';
		$this->children = array(
			'common/header',
			'common/footer'
		);

		$this->response->setOutput($this->render());
	}

	public function install() {
		$this->load->language('module/seogen');
		$this->load->model('setting/setting');
		$this->model_setting_setting->editSetting('seogen', array('seogen' => array('categories_template' => $this->language->get('text_categories_tags'),
																					'products_template' => $this->language->get('text_products_tags'),
																					'manufacturers_template' => $this->language->get('text_manufacturers_tags')
														  )));
	}

	public function generate() {

		if($this->request->server['REQUEST_METHOD'] == 'POST' && $this->validate()) {
			$this->load->language('module/seogen');
			$this->load->model('module/seogen');

			if(isset($this->request->post['seogen']['categories_template'])) {
				$this->model_module_seogen->generateCategories($this->request->post['seogen']['categories_template']);
				$this->saveSettings('categories_template');
				$this->response->setOutput($this->language->get('text_success_categories'));
			} elseif(isset($this->request->post['seogen']['products_template'])) {
				$this->model_module_seogen->generateProducts($this->request->post['seogen']['products_template']);
				$this->saveSettings('products_template');
				$this->response->setOutput($this->language->get('text_success_products'));
			} elseif(isset($this->request->post['seogen']['manufacturers_template'])) {
				$this->model_module_seogen->generateManufacturers($this->request->post['seogen']['manufacturers_template']);
				$this->saveSettings('manufacturers_template');
				$this->response->setOutput($this->language->get('text_success_manufacturers'));
			}
			$this->cache->delete("seo_pro");
		}
	}

	private function saveSettings($var) {
		$seogen = $this->config->get('seogen');
		if(in_array($var, array_keys($seogen))) {
			$seogen[$var] = $this->request->post['seogen'][$var];
			$this->load->model('setting/setting');
			$this->model_setting_setting->editSetting('seogen', array('seogen' => $seogen));
		}
	}

	private function validate() {
		if(!$this->user->hasPermission('modify', 'module/seogen')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		if(!$this->error) {
			return true;
		} else {
			return false;
		}
	}
}

?>