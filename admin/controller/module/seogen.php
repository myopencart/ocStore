<?php
class ControllerModuleSeogen extends Controller {

	private $error = array();

	public function index() {

		$this->data += $this->load->language('module/seogen');

		$this->document->setTitle(strip_tags($this->language->get('heading_title')));

		$this->load->model('setting/setting');

		if(($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
			if (!isset($this->request->post['seogen']['seogen_status'])){
				$this->request->post['seogen']['seogen_status'] = 0;
			}
			if (!isset($this->request->post['seogen']['seogen_overwrite'])){
				$this->request->post['seogen']['seogen_overwrite'] = 0;
			}
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
		$seogen = array('seogen' => array(
			'seogen_status' => 1,
			'seogen_overwrite' => 1,
			'categories_template' => $this->language->get('text_categories_tags'),
			'products_template' => $this->language->get('text_products_tags'),
			'manufacturers_template' => $this->language->get('text_manufacturers_tags'),
		));
		$query = $this->db->query("DESC `" . DB_PREFIX . "category_description`");
		foreach($query->rows as $row) {
			if($row['Field'] == "seo_title") {
				$seogen['seogen']['categories_title_template'] = $this->language->get('text_categories_title_tags');
			} elseif($row['Field'] == "seo_h1") {
				$seogen['seogen']['categories_h1_template'] = $this->language->get('text_categories_h1_tags');
			} elseif($row['Field'] == "meta_description") {
				$seogen['seogen']['categories_meta_keyword_template'] = $this->language->get('text_categories_meta_keyword_tags');
			} elseif($row['Field'] == "meta_keyword") {
				$seogen['seogen']['categories_meta_description_template'] = $this->language->get('text_categories_meta_description_tags');
			}
		}

		$query = $this->db->query("DESC `" . DB_PREFIX . "product_description`");
		foreach($query->rows as $row) {
			if($row['Field'] == "seo_title") {
				$seogen['seogen']['products_title_template'] = $this->language->get('text_products_title_tags');
			} elseif($row['Field'] == "seo_h1") {
				$seogen['seogen']['products_h1_template'] = $this->language->get('text_products_h1_tags');
			} elseif($row['Field'] == "meta_description") {
				$seogen['seogen']['products_meta_keyword_template'] = $this->language->get('text_products_meta_keyword_tags');
			} elseif($row['Field'] == "meta_keyword") {
				$seogen['seogen']['products_meta_description_template'] = $this->language->get('text_products_meta_description_tags');
			}
		}

		$query = $this->db->query("SHOW TABLES LIKE '" . DB_PREFIX . "manufacturer_description'");
		if($query->num_rows) {
			$query = $this->db->query("DESC `" . DB_PREFIX . "manufacturer_description`");
			foreach($query->rows as $row) {
				if($row['Field'] == "seo_title") {
					$seogen['seogen']['manufacturers_title_template'] = $this->language->get('text_manufacturers_title_tags');
				} elseif($row['Field'] == "seo_h1") {
					$seogen['seogen']['manufacturers_h1_template'] = $this->language->get('text_manufacturers_h1_tags');
				} elseif($row['Field'] == "meta_description") {
					$seogen['seogen']['manufacturers_meta_keyword_template'] = $this->language->get('text_manufacturers_meta_keyword_tags');
				} elseif($row['Field'] == "meta_keyword") {
					$seogen['seogen']['manufacturers_meta_description_template'] = $this->language->get('text_manufacturers_meta_description_tags');
				}
			}
		}

		$this->model_setting_setting->editSetting('seogen', $seogen);
	}

	public function generate() {

		if($this->request->server['REQUEST_METHOD'] == 'POST' && isset($this->request->post['name']) && $this->validate()) {
			$this->load->language('module/seogen');
			$this->load->model('module/seogen');
			$name = $this->request->post['name'];
		    if($name == 'categories') {
				$this->model_module_seogen->generateCategories($this->request->post['seogen']);
			} elseif($name == 'products') {
				$this->model_module_seogen->generateProducts($this->request->post['seogen']);
			} elseif($name == 'manufacturers') {
				$this->model_module_seogen->generateManufacturers($this->request->post['seogen']);
			}
			$this->response->setOutput($this->language->get('text_success_generation'));
			$this->saveSettings($this->request->post['seogen']);
			$this->cache->delete("seo_pro");
		}
	}


	private function saveSettings($data) {
		$seogen = $this->config->get('seogen');
		foreach($data as $key => $val) {
			if(in_array($key, array_keys($seogen))) {
				$seogen[$key] = $val;
			}
		}
		$this->load->model('setting/setting');
		$this->model_setting_setting->editSetting('seogen', array('seogen' => $seogen));
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