<?php
class ControllerSettingStore extends Controller {
	private $error = array();

	public function index() {
		$this->load->language('setting/store');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('setting/store');

		$this->getList();
	}

	public function add() {
		$this->load->language('setting/store');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('setting/store');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
			$store_id = $this->model_setting_store->addStore($this->request->post);

			$this->load->model('setting/setting');

			$this->model_setting_setting->editSetting('config', $this->request->post, $store_id);

			$this->session->data['success'] = $this->language->get('text_success');

			$this->response->redirect($this->url->link('setting/store', 'token=' . $this->session->data['token'], 'SSL'));
		}

		$this->getForm();
	}

	public function edit() {
		$this->load->language('setting/store');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('setting/store');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
			$this->model_setting_store->editStore($this->request->get['store_id'], $this->request->post);

			$this->load->model('setting/setting');

			$this->model_setting_setting->editSetting('config', $this->request->post, $this->request->get['store_id']);

			$this->session->data['success'] = $this->language->get('text_success');

			$this->response->redirect($this->url->link('setting/store', 'token=' . $this->session->data['token'] . '&store_id=' . $this->request->get['store_id'], 'SSL'));
		}

		$this->getForm();
	}

	public function delete() {
		$this->load->language('setting/store');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('setting/store');

		$this->load->model('setting/setting');

		if (isset($this->request->post['selected']) && $this->validateDelete()) {
			foreach ($this->request->post['selected'] as $store_id) {
				$this->model_setting_store->deleteStore($store_id);

				$this->model_setting_setting->deleteSetting('config', $store_id);
			}

			$this->session->data['success'] = $this->language->get('text_success');

			$this->response->redirect($this->url->link('setting/store', 'token=' . $this->session->data['token'], 'SSL'));
		}

		$this->getList();
	}

	protected function getList() {
		$url = '';

		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], 'SSL')
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('setting/store', 'token=' . $this->session->data['token'], 'SSL')
		);

		$data['add'] = $this->url->link('setting/store/add', 'token=' . $this->session->data['token'], 'SSL');
		$data['delete'] = $this->url->link('setting/store/delete', 'token=' . $this->session->data['token'], 'SSL');

		$data['stores'] = array();

		$data['stores'][] = array(
			'store_id' => 0,
			'name'     => $this->config->get('config_name') . $this->language->get('text_default'),
			'url'      => HTTP_CATALOG,
			'edit'     => $this->url->link('setting/setting', 'token=' . $this->session->data['token'], 'SSL')
		);

		$store_total = $this->model_setting_store->getTotalStores();

		$results = $this->model_setting_store->getStores();

		foreach ($results as $result) {
			$data['stores'][] = array(
				'store_id' => $result['store_id'],
				'name'     => $result['name'],
				'url'      => $result['url'],
				'edit'     => $this->url->link('setting/store/edit', 'token=' . $this->session->data['token'] . '&store_id=' . $result['store_id'], 'SSL')
			);
		}

		$data['heading_title'] = $this->language->get('heading_title');

		$data['text_list'] = $this->language->get('text_list');
		$data['text_no_results'] = $this->language->get('text_no_results');
		$data['text_confirm'] = $this->language->get('text_confirm');

		$data['column_name'] = $this->language->get('column_name');
		$data['column_url'] = $this->language->get('column_url');
		$data['column_action'] = $this->language->get('column_action');

		$data['button_add'] = $this->language->get('button_add');
		$data['button_edit'] = $this->language->get('button_edit');
		$data['button_delete'] = $this->language->get('button_delete');

		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}

		if (isset($this->session->data['success'])) {
			$data['success'] = $this->session->data['success'];

			unset($this->session->data['success']);
		} else {
			$data['success'] = '';
		}

		if (isset($this->request->post['selected'])) {
			$data['selected'] = (array)$this->request->post['selected'];
		} else {
			$data['selected'] = array();
		}

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('setting/store_list.tpl', $data));
	}

	public function getForm() {
		$data['heading_title'] = $this->language->get('heading_title');

		$data['text_form'] = !isset($this->request->get['store_id']) ? $this->language->get('text_add') : $this->language->get('text_edit');
		$data['text_select'] = $this->language->get('text_select');
		$data['text_none'] = $this->language->get('text_none');
		$data['text_yes'] = $this->language->get('text_yes');
		$data['text_no'] = $this->language->get('text_no');
		$data['text_items'] = $this->language->get('text_items');
		$data['text_tax'] = $this->language->get('text_tax');
		$data['text_account'] = $this->language->get('text_account');
		$data['text_checkout'] = $this->language->get('text_checkout');
		$data['text_stock'] = $this->language->get('text_stock');
		$data['text_shipping'] = $this->language->get('text_shipping');
		$data['text_payment'] = $this->language->get('text_payment');

		$data['entry_url'] = $this->language->get('entry_url');
		$data['entry_ssl'] = $this->language->get('entry_ssl');
		$data['entry_name'] = $this->language->get('entry_name');
		$data['entry_owner'] = $this->language->get('entry_owner');
		$data['entry_address'] = $this->language->get('entry_address');
		$data['entry_geocode'] = $this->language->get('entry_geocode');
		$data['entry_email'] = $this->language->get('entry_email');
		$data['entry_telephone'] = $this->language->get('entry_telephone');
		$data['entry_fax'] = $this->language->get('entry_fax');
		$data['entry_image'] = $this->language->get('entry_image');
		$data['entry_open'] = $this->language->get('entry_open');
		$data['entry_comment'] = $this->language->get('entry_comment');
		$data['entry_location'] = $this->language->get('entry_location');
		$data['entry_meta_title'] = $this->language->get('entry_meta_title');
		$data['entry_meta_description'] = $this->language->get('entry_meta_description');
		$data['entry_meta_keyword'] = $this->language->get('entry_meta_keyword');
		$data['entry_layout'] = $this->language->get('entry_layout');
		$data['entry_template'] = $this->language->get('entry_template');
		$data['entry_country'] = $this->language->get('entry_country');
		$data['entry_zone'] = $this->language->get('entry_zone');
		$data['entry_language'] = $this->language->get('entry_language');
		$data['entry_currency'] = $this->language->get('entry_currency');
		$data['entry_product_limit'] = $this->language->get('entry_product_limit');
		$data['entry_product_description_length'] = $this->language->get('entry_product_description_length');
		$data['entry_tax'] = $this->language->get('entry_tax');
		$data['entry_tax_default'] = $this->language->get('entry_tax_default');
		$data['entry_tax_customer'] = $this->language->get('entry_tax_customer');
		$data['entry_customer_group'] = $this->language->get('entry_customer_group');
		$data['entry_customer_group_display'] = $this->language->get('entry_customer_group_display');
		$data['entry_customer_price'] = $this->language->get('entry_customer_price');
		$data['entry_account'] = $this->language->get('entry_account');
		$data['entry_cart_weight'] = $this->language->get('entry_cart_weight');
		$data['entry_checkout_guest'] = $this->language->get('entry_checkout_guest');
		$data['entry_checkout'] = $this->language->get('entry_checkout');
		$data['entry_order_status'] = $this->language->get('entry_order_status');
		$data['entry_stock_display'] = $this->language->get('entry_stock_display');
		$data['entry_stock_checkout'] = $this->language->get('entry_stock_checkout');
		$data['entry_ajax_cart'] = $this->language->get('entry_ajax_cart');
		$data['entry_logo'] = $this->language->get('entry_logo');
		$data['entry_icon'] = $this->language->get('entry_icon');
		$data['entry_image_category'] = $this->language->get('entry_image_category');
		$data['entry_image_thumb'] = $this->language->get('entry_image_thumb');
		$data['entry_image_popup'] = $this->language->get('entry_image_popup');
		$data['entry_image_product'] = $this->language->get('entry_image_product');
		$data['entry_image_additional'] = $this->language->get('entry_image_additional');
		$data['entry_image_related'] = $this->language->get('entry_image_related');
		$data['entry_image_compare'] = $this->language->get('entry_image_compare');
		$data['entry_image_wishlist'] = $this->language->get('entry_image_wishlist');
		$data['entry_image_cart'] = $this->language->get('entry_image_cart');
		$data['entry_image_location'] = $this->language->get('entry_image_location');
		$data['entry_width'] = $this->language->get('entry_width');
		$data['entry_height'] = $this->language->get('entry_height');
		$data['entry_secure'] = $this->language->get('entry_secure');

		$data['help_url'] = $this->language->get('help_url');
		$data['help_ssl'] = $this->language->get('help_ssl');
		$data['help_geocode'] = $this->language->get('help_geocode');
		$data['help_open'] = $this->language->get('help_open');
		$data['help_comment'] = $this->language->get('help_comment');
		$data['help_location'] = $this->language->get('help_location');
		$data['help_currency'] = $this->language->get('help_currency');
		$data['help_product_limit'] = $this->language->get('help_product_limit');
		$data['help_product_description_length'] = $this->language->get('help_product_description_length');
		$data['help_tax_default'] = $this->language->get('help_tax_default');
		$data['help_tax_customer'] = $this->language->get('help_tax_customer');
		$data['help_customer_group'] = $this->language->get('help_customer_group');
		$data['help_customer_group_display'] = $this->language->get('help_customer_group_display');
		$data['help_customer_price'] = $this->language->get('help_customer_price');
		$data['help_account'] = $this->language->get('help_account');
		$data['help_checkout_guest'] = $this->language->get('help_checkout_guest');
		$data['help_checkout'] = $this->language->get('help_checkout');
		$data['help_order_status'] = $this->language->get('help_order_status');
		$data['help_stock_display'] = $this->language->get('help_stock_display');
		$data['help_stock_checkout'] = $this->language->get('help_stock_checkout');
		$data['help_icon'] = $this->language->get('help_icon');
		$data['help_secure'] = $this->language->get('help_secure');

		$data['button_save'] = $this->language->get('button_save');
		$data['button_cancel'] = $this->language->get('button_cancel');

		$data['tab_general'] = $this->language->get('tab_general');
		$data['tab_store'] = $this->language->get('tab_store');
		$data['tab_local'] = $this->language->get('tab_local');
		$data['tab_option'] = $this->language->get('tab_option');
		$data['tab_image'] = $this->language->get('tab_image');
		$data['tab_server'] = $this->language->get('tab_server');

		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}

		if (isset($this->error['url'])) {
			$data['error_url'] = $this->error['url'];
		} else {
			$data['error_url'] = '';
		}

		if (isset($this->error['name'])) {
			$data['error_name'] = $this->error['name'];
		} else {
			$data['error_name'] = '';
		}

		if (isset($this->error['owner'])) {
			$data['error_owner'] = $this->error['owner'];
		} else {
			$data['error_owner'] = '';
		}

		if (isset($this->error['address'])) {
			$data['error_address'] = $this->error['address'];
		} else {
			$data['error_address'] = '';
		}

		if (isset($this->error['email'])) {
			$data['error_email'] = $this->error['email'];
		} else {
			$data['error_email'] = '';
		}

		if (isset($this->error['telephone'])) {
			$data['error_telephone'] = $this->error['telephone'];
		} else {
			$data['error_telephone'] = '';
		}

		if (isset($this->error['meta_title'])) {
			$data['error_meta_title'] = $this->error['meta_title'];
		} else {
			$data['error_meta_title'] = '';
		}

		if (isset($this->error['customer_group_display'])) {
			$data['error_customer_group_display'] = $this->error['customer_group_display'];
		} else {
			$data['error_customer_group_display'] = '';
		}

		if (isset($this->error['image_category'])) {
			$data['error_image_category'] = $this->error['image_category'];
		} else {
			$data['error_image_category'] = '';
		}

		if (isset($this->error['image_thumb'])) {
			$data['error_image_thumb'] = $this->error['image_thumb'];
		} else {
			$data['error_image_thumb'] = '';
		}

		if (isset($this->error['image_popup'])) {
			$data['error_image_popup'] = $this->error['image_popup'];
		} else {
			$data['error_image_popup'] = '';
		}

		if (isset($this->error['image_product'])) {
			$data['error_image_product'] = $this->error['image_product'];
		} else {
			$data['error_image_product'] = '';
		}

		if (isset($this->error['image_additional'])) {
			$data['error_image_additional'] = $this->error['image_additional'];
		} else {
			$data['error_image_additional'] = '';
		}

		if (isset($this->error['image_related'])) {
			$data['error_image_related'] = $this->error['image_related'];
		} else {
			$data['error_image_related'] = '';
		}

		if (isset($this->error['image_compare'])) {
			$data['error_image_compare'] = $this->error['image_compare'];
		} else {
			$data['error_image_compare'] = '';
		}

		if (isset($this->error['image_wishlist'])) {
			$data['error_image_wishlist'] = $this->error['image_wishlist'];
		} else {
			$data['error_image_wishlist'] = '';
		}

		if (isset($this->error['image_cart'])) {
			$data['error_image_cart'] = $this->error['image_cart'];
		} else {
			$data['error_image_cart'] = '';
		}

		if (isset($this->error['image_location'])) {
			$data['error_image_location'] = $this->error['image_location'];
		} else {
			$data['error_image_location'] = '';
		}

		if (isset($this->error['product_limit'])) {
			$data['error_product_limit'] = $this->error['product_limit'];
		} else {
			$data['error_product_limit'] = '';
		}

		if (isset($this->error['product_description_length'])) {
			$data['error_product_description_length'] = $this->error['product_description_length'];
		} else {
			$data['error_product_description_length'] = '';
		}

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], 'SSL')
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('setting/store', 'token=' . $this->session->data['token'], 'SSL')
		);

		if (!isset($this->request->get['store_id'])) {
			$data['breadcrumbs'][] = array(
				'text' => $this->language->get('text_settings'),
				'href' => $this->url->link('setting/store/add', 'token=' . $this->session->data['token'], 'SSL')
			);
		} else {
			$data['breadcrumbs'][] = array(
				'text' => $this->language->get('text_settings'),
				'href' => $this->url->link('setting/store/edit', 'token=' . $this->session->data['token'] . '&store_id=' . $this->request->get['store_id'], 'SSL')
			);
		}

		if (isset($this->session->data['success'])) {
			$data['success'] = $this->session->data['success'];

			unset($this->session->data['success']);
		} else {
			$data['success'] = '';
		}

		if (!isset($this->request->get['store_id'])) {
			$data['action'] = $this->url->link('setting/store/add', 'token=' . $this->session->data['token'], 'SSL');
		} else {
			$data['action'] = $this->url->link('setting/store/edit', 'token=' . $this->session->data['token'] . '&store_id=' . $this->request->get['store_id'], 'SSL');
		}

		$data['cancel'] = $this->url->link('setting/store', 'token=' . $this->session->data['token'], 'SSL');

		if (isset($this->request->get['store_id']) && ($this->request->server['REQUEST_METHOD'] != 'POST')) {
			$this->load->model('setting/setting');

			$store_info = $this->model_setting_setting->getSetting('config', $this->request->get['store_id']);
		}

		$data['token'] = $this->session->data['token'];

		if (isset($this->request->post['config_url'])) {
			$data['config_url'] = $this->request->post['config_url'];
		} elseif (isset($store_info['config_url'])) {
			$data['config_url'] = $store_info['config_url'];
		} else {
			$data['config_url'] = '';
		}

		if (isset($this->request->post['config_ssl'])) {
			$data['config_ssl'] = $this->request->post['config_ssl'];
		} elseif (isset($store_info['config_ssl'])) {
			$data['config_ssl'] = $store_info['config_ssl'];
		} else {
			$data['config_ssl'] = '';
		}

		if (isset($this->request->post['config_name'])) {
			$data['config_name'] = $this->request->post['config_name'];
		} elseif (isset($store_info['config_name'])) {
			$data['config_name'] = $store_info['config_name'];
		} else {
			$data['config_name'] = '';
		}

		if (isset($this->request->post['config_owner'])) {
			$data['config_owner'] = $this->request->post['config_owner'];
		} elseif (isset($store_info['config_owner'])) {
			$data['config_owner'] = $store_info['config_owner'];
		} else {
			$data['config_owner'] = '';
		}

		if (isset($this->request->post['config_address'])) {
			$data['config_address'] = $this->request->post['config_address'];
		} elseif (isset($store_info['config_address'])) {
			$data['config_address'] = $store_info['config_address'];
		} else {
			$data['config_address'] = '';
		}

		if (isset($this->request->post['config_geocode'])) {
			$data['config_geocode'] = $this->request->post['config_geocode'];
		} elseif (isset($store_info['config_geocode'])) {
			$data['config_geocode'] = $store_info['config_geocode'];
		} else {
			$data['config_geocode'] = '';
		}

		if (isset($this->request->post['config_email'])) {
			$data['config_email'] = $this->request->post['config_email'];
		} elseif (isset($store_info['config_email'])) {
			$data['config_email'] = $store_info['config_email'];
		} else {
			$data['config_email'] = '';
		}

		if (isset($this->request->post['config_telephone'])) {
			$data['config_telephone'] = $this->request->post['config_telephone'];
		} elseif (isset($store_info['config_telephone'])) {
			$data['config_telephone'] = $store_info['config_telephone'];
		} else {
			$data['config_telephone'] = '';
		}

		if (isset($this->request->post['config_fax'])) {
			$data['config_fax'] = $this->request->post['config_fax'];
		} elseif (isset($store_info['config_fax'])) {
			$data['config_fax'] = $store_info['config_fax'];
		} else {
			$data['config_fax'] = '';
		}

		if (isset($this->request->post['config_image'])) {
			$data['config_image'] = $this->request->post['config_image'];
		} elseif (isset($store_info['config_image'])) {
			$data['config_image'] = $store_info['config_image'];
		} else {
			$data['config_image'] = '';
		}

		$this->load->model('tool/image');

		if (isset($this->request->post['config_image']) && is_file(DIR_IMAGE . $this->request->post['config_image'])) {
			$data['thumb'] = $this->model_tool_image->resize($this->request->post['config_image'], 100, 100);
		} elseif (isset($store_info['config_image']) && is_file(DIR_IMAGE . $store_info['config_image'])) {
			$data['thumb'] = $this->model_tool_image->resize($store_info['config_image'], 100, 100);
		} else {
			$data['thumb'] = $this->model_tool_image->resize('no_image.png', 100, 100);
		}

		$data['placeholder'] = $this->model_tool_image->resize('no_image.png', 100, 100);

		if (isset($this->request->post['config_open'])) {
			$data['config_open'] = $this->request->post['config_open'];
		} elseif (isset($store_info['config_open'])) {
			$data['config_open'] = $store_info['config_open'];
		} else {
			$data['config_open'] = '';
		}

		if (isset($this->request->post['config_comment'])) {
			$data['config_comment'] = $this->request->post['config_comment'];
		} elseif (isset($store_info['config_comment'])) {
			$data['config_comment'] = $store_info['config_comment'];
		} else {
			$data['config_comment'] = '';
		}

		$this->load->model('localisation/location');

		$data['locations'] = $this->model_localisation_location->getLocations();

		if (isset($this->request->post['config_location'])) {
			$data['config_location'] = $this->request->post['config_location'];
		} elseif ($this->config->get('config_location')) {
			$data['config_location'] = $this->config->get('config_location');
		} else {
			$data['config_location'] = array();
		}

		if (isset($this->request->post['config_meta_title'])) {
			$data['config_meta_title'] = $this->request->post['config_meta_title'];
		} elseif (isset($store_info['config_meta_title'])) {
			$data['config_meta_title'] = $store_info['config_meta_title'];
		} else {
			$data['config_meta_title'] = '';
		}

		if (isset($this->request->post['config_meta_description'])) {
			$data['config_meta_description'] = $this->request->post['config_meta_description'];
		} elseif (isset($store_info['config_meta_description'])) {
			$data['config_meta_description'] = $store_info['config_meta_description'];
		} else {
			$data['config_meta_description'] = '';
		}

		if (isset($this->request->post['config_meta_keyword'])) {
			$data['config_meta_keyword'] = $this->request->post['config_meta_keyword'];
		} elseif (isset($store_info['config_meta_keyword'])) {
			$data['config_meta_keyword'] = $store_info['config_meta_keyword'];
		} else {
			$data['config_meta_keyword'] = '';
		}

		if (isset($this->request->post['config_layout_id'])) {
			$data['config_layout_id'] = $this->request->post['config_layout_id'];
		} elseif (isset($store_info['config_layout_id'])) {
			$data['config_layout_id'] = $store_info['config_layout_id'];
		} else {
			$data['config_layout_id'] = '';
		}

		$this->load->model('design/layout');

		$data['layouts'] = $this->model_design_layout->getLayouts();

		if (isset($this->request->post['config_template'])) {
			$data['config_template'] = $this->request->post['config_template'];
		} elseif (isset($store_info['config_template'])) {
			$data['config_template'] = $store_info['config_template'];
		} else {
			$data['config_template'] = '';
		}

		$data['templates'] = array();

		$directories = glob(DIR_CATALOG . 'view/theme/*', GLOB_ONLYDIR);

		foreach ($directories as $directory) {
			$data['templates'][] = basename($directory);
		}

		if (isset($this->request->post['config_country_id'])) {
			$data['config_country_id'] = $this->request->post['config_country_id'];
		} elseif (isset($store_info['config_country_id'])) {
			$data['config_country_id'] = $store_info['config_country_id'];
		} else {
			$data['config_country_id'] = $this->config->get('config_country_id');
		}

		$this->load->model('localisation/country');

		$data['countries'] = $this->model_localisation_country->getCountries();

		if (isset($this->request->post['config_zone_id'])) {
			$data['config_zone_id'] = $this->request->post['config_zone_id'];
		} elseif (isset($store_info['config_zone_id'])) {
			$data['config_zone_id'] = $store_info['config_zone_id'];
		} else {
			$data['config_zone_id'] = $this->config->get('config_zone_id');
		}

		if (isset($this->request->post['config_language'])) {
			$data['config_language'] = $this->request->post['config_language'];
		} elseif (isset($store_info['config_language'])) {
			$data['config_language'] = $store_info['config_language'];
		} else {
			$data['config_language'] = $this->config->get('config_language');
		}

		$this->load->model('localisation/language');

		$data['languages'] = $this->model_localisation_language->getLanguages();

		if (isset($this->request->post['config_currency'])) {
			$data['config_currency'] = $this->request->post['config_currency'];
		} elseif (isset($store_info['config_currency'])) {
			$data['config_currency'] = $store_info['config_currency'];
		} else {
			$data['config_currency'] = $this->config->get('config_currency');
		}

		$this->load->model('localisation/currency');

		$data['currencies'] = $this->model_localisation_currency->getCurrencies();

		if (isset($this->request->post['config_product_limit'])) {
			$data['config_product_limit'] = $this->request->post['config_product_limit'];
		} elseif (isset($store_info['config_product_limit'])) {
			$data['config_product_limit'] = $store_info['config_product_limit'];
		} else {
			$data['config_product_limit'] = '15';
		}

		if (isset($this->request->post['config_product_description_length'])) {
			$data['config_product_description_length'] = $this->request->post['config_product_description_length'];
		} elseif (isset($store_info['config_product_description_length'])) {
			$data['config_product_description_length'] = $store_info['config_product_description_length'];
		} else {
			$data['config_product_description_length'] = '100';
		}

		if (isset($this->request->post['config_tax'])) {
			$data['config_tax'] = $this->request->post['config_tax'];
		} elseif (isset($store_info['config_tax'])) {
			$data['config_tax'] = $store_info['config_tax'];
		} else {
			$data['config_tax'] = '';
		}

		if (isset($this->request->post['config_tax_default'])) {
			$data['config_tax_default'] = $this->request->post['config_tax_default'];
		} elseif (isset($store_info['config_tax_default'])) {
			$data['config_tax_default'] = $store_info['config_tax_default'];
		} else {
			$data['config_tax_default'] = '';
		}

		if (isset($this->request->post['config_tax_customer'])) {
			$data['config_tax_customer'] = $this->request->post['config_tax_customer'];
		} elseif (isset($store_info['config_tax_customer'])) {
			$data['config_tax_customer'] = $store_info['config_tax_customer'];
		} else {
			$data['config_tax_customer'] = '';
		}

		if (isset($this->request->post['config_customer_group_id'])) {
			$data['config_customer_group_id'] = $this->request->post['config_customer_group_id'];
		} elseif (isset($store_info['config_customer_group_id'])) {
			$data['config_customer_group_id'] = $store_info['config_customer_group_id'];
		} else {
			$data['config_customer_group_id'] = '';
		}

		$this->load->model('sale/customer_group');

		$data['customer_groups'] = $this->model_sale_customer_group->getCustomerGroups();

		if (isset($this->request->post['config_customer_group_display'])) {
			$data['config_customer_group_display'] = $this->request->post['config_customer_group_display'];
		} elseif (isset($store_info['config_customer_group_display'])) {
			$data['config_customer_group_display'] = $store_info['config_customer_group_display'];
		} else {
			$data['config_customer_group_display'] = array();
		}

		if (isset($this->request->post['config_customer_price'])) {
			$data['config_customer_price'] = $this->request->post['config_customer_price'];
		} elseif (isset($store_info['config_customer_price'])) {
			$data['config_customer_price'] = $store_info['config_customer_price'];
		} else {
			$data['config_customer_price'] = '';
		}

		if (isset($this->request->post['config_account_id'])) {
			$data['config_account_id'] = $this->request->post['config_account_id'];
		} elseif (isset($store_info['config_account_id'])) {
			$data['config_account_id'] = $store_info['config_account_id'];
		} else {
			$data['config_account_id'] = '';
		}

		$this->load->model('catalog/information');

		$data['informations'] = $this->model_catalog_information->getInformations();

		if (isset($this->request->post['config_cart_weight'])) {
			$data['config_cart_weight'] = $this->request->post['config_cart_weight'];
		} elseif (isset($store_info['config_cart_weight'])) {
			$data['config_cart_weight'] = $store_info['config_cart_weight'];
		} else {
			$data['config_cart_weight'] = '';
		}

		if (isset($this->request->post['config_checkout_guest'])) {
			$data['config_checkout_guest'] = $this->request->post['config_checkout_guest'];
		} elseif (isset($store_info['config_checkout_guest'])) {
			$data['config_checkout_guest'] = $store_info['config_checkout_guest'];
		} else {
			$data['config_checkout_guest'] = '';
		}

		if (isset($this->request->post['config_checkout_id'])) {
			$data['config_checkout_id'] = $this->request->post['config_checkout_id'];
		} elseif (isset($store_info['config_checkout_id'])) {
			$data['config_checkout_id'] = $store_info['config_checkout_id'];
		} else {
			$data['config_checkout_id'] = '';
		}

		if (isset($this->request->post['config_order_status_id'])) {
			$data['config_order_status_id'] = $this->request->post['config_order_status_id'];
		} elseif (isset($store_info['config_order_status_id'])) {
			$data['config_order_status_id'] = $store_info['config_order_status_id'];
		} else {
			$data['config_order_status_id'] = '';
		}

		$this->load->model('localisation/order_status');

		$data['order_statuses'] = $this->model_localisation_order_status->getOrderStatuses();

		if (isset($this->request->post['config_stock_display'])) {
			$data['config_stock_display'] = $this->request->post['config_stock_display'];
		} elseif (isset($store_info['config_stock_display'])) {
			$data['config_stock_display'] = $store_info['config_stock_display'];
		} else {
			$data['config_stock_display'] = '';
		}

		if (isset($this->request->post['config_stock_checkout'])) {
			$data['config_stock_checkout'] = $this->request->post['config_stock_checkout'];
		} elseif (isset($store_info['config_stock_checkout'])) {
			$data['config_stock_checkout'] = $store_info['config_stock_checkout'];
		} else {
			$data['config_stock_checkout'] = '';
		}

		if (isset($this->request->post['config_logo'])) {
			$data['config_logo'] = $this->request->post['config_logo'];
		} elseif (isset($store_info['config_logo'])) {
			$data['config_logo'] = $store_info['config_logo'];
		} else {
			$data['config_logo'] = '';
		}

		if (isset($this->request->post['config_logo']) && is_file(DIR_IMAGE . $this->request->post['config_logo'])) {
			$data['logo'] = $this->model_tool_image->resize($this->request->post['config_logo'], 100, 100);
		} elseif (isset($store_info['config_logo']) && is_file(DIR_IMAGE . $store_info['config_logo'])) {
			$data['logo'] = $this->model_tool_image->resize($store_info['config_logo'], 100, 100);
		} else {
			$data['logo'] = $this->model_tool_image->resize('no_image.png', 100, 100);
		}

		$data['placeholder'] = $this->model_tool_image->resize('no_image.png', 100, 100);

		if (isset($this->request->post['config_icon'])) {
			$data['config_icon'] = $this->request->post['config_icon'];
		} elseif (isset($store_info['config_icon'])) {
			$data['config_icon'] = $store_info['config_icon'];
		} else {
			$data['config_icon'] = '';
		}

		if (isset($this->request->post['config_icon']) && is_file(DIR_IMAGE . $this->request->post['config_icon'])) {
			$data['icon'] = $this->model_tool_image->resize($this->request->post['config_icon'], 100, 100);
		} elseif (isset($store_info['config_icon']) && is_file(DIR_IMAGE . $store_info['config_icon'])) {
			$data['icon'] = $this->model_tool_image->resize($store_info['config_icon'], 100, 100);
		} else {
			$data['icon'] = $this->model_tool_image->resize('no_image.png', 100, 100);
		}

		if (isset($this->request->post['config_image_category_height'])) {
			$data['config_image_category_height'] = $this->request->post['config_image_category_height'];
		} elseif (isset($store_info['config_image_category_height'])) {
			$data['config_image_category_height'] = $store_info['config_image_category_height'];
		} else {
			$data['config_image_category_height'] = 80;
		}

		if (isset($this->request->post['config_image_thumb_width'])) {
			$data['config_image_thumb_width'] = $this->request->post['config_image_thumb_width'];
		} elseif (isset($store_info['config_image_thumb_width'])) {
			$data['config_image_thumb_width'] = $store_info['config_image_thumb_width'];
		} else {
			$data['config_image_thumb_width'] = 228;
		}

		if (isset($this->request->post['config_image_thumb_height'])) {
			$data['config_image_thumb_height'] = $this->request->post['config_image_thumb_height'];
		} elseif (isset($store_info['config_image_thumb_height'])) {
			$data['config_image_thumb_height'] = $store_info['config_image_thumb_height'];
		} else {
			$data['config_image_thumb_height'] = 228;
		}

		if (isset($this->request->post['config_image_popup_width'])) {
			$data['config_image_popup_width'] = $this->request->post['config_image_popup_width'];
		} elseif (isset($store_info['config_image_popup_width'])) {
			$data['config_image_popup_width'] = $store_info['config_image_popup_width'];
		} else {
			$data['config_image_popup_width'] = 500;
		}

		if (isset($this->request->post['config_image_popup_height'])) {
			$data['config_image_popup_height'] = $this->request->post['config_image_popup_height'];
		} elseif (isset($store_info['config_image_popup_height'])) {
			$data['config_image_popup_height'] = $store_info['config_image_popup_height'];
		} else {
			$data['config_image_popup_height'] = 500;
		}

		if (isset($this->request->post['config_image_product_width'])) {
			$data['config_image_product_width'] = $this->request->post['config_image_product_width'];
		} elseif (isset($store_info['config_image_product_width'])) {
			$data['config_image_product_width'] = $store_info['config_image_product_width'];
		} else {
			$data['config_image_product_width'] = 80;
		}

		if (isset($this->request->post['config_image_product_height'])) {
			$data['config_image_product_height'] = $this->request->post['config_image_product_height'];
		} elseif (isset($store_info['config_image_product_height'])) {
			$data['config_image_product_height'] = $store_info['config_image_product_height'];
		} else {
			$data['config_image_product_height'] = 80;
		}

		if (isset($this->request->post['config_image_category_width'])) {
			$data['config_image_category_width'] = $this->request->post['config_image_category_width'];
		} elseif (isset($store_info['config_image_category_width'])) {
			$data['config_image_category_width'] = $store_info['config_image_category_width'];
		} else {
			$data['config_image_category_width'] = 80;
		}

		if (isset($this->request->post['config_image_additional_width'])) {
			$data['config_image_additional_width'] = $this->request->post['config_image_additional_width'];
		} elseif (isset($store_info['config_image_additional_width'])) {
			$data['config_image_additional_width'] = $store_info['config_image_additional_width'];
		} else {
			$data['config_image_additional_width'] = 74;
		}

		if (isset($this->request->post['config_image_additional_height'])) {
			$data['config_image_additional_height'] = $this->request->post['config_image_additional_height'];
		} elseif (isset($store_info['config_image_additional_height'])) {
			$data['config_image_additional_height'] = $store_info['config_image_additional_height'];
		} else {
			$data['config_image_additional_height'] = 74;
		}

		if (isset($this->request->post['config_image_related_width'])) {
			$data['config_image_related_width'] = $this->request->post['config_image_related_width'];
		} elseif (isset($store_info['config_image_related_width'])) {
			$data['config_image_related_width'] = $store_info['config_image_related_width'];
		} else {
			$data['config_image_related_width'] = 80;
		}

		if (isset($this->request->post['config_image_related_height'])) {
			$data['config_image_related_height'] = $this->request->post['config_image_related_height'];
		} elseif (isset($store_info['config_image_related_height'])) {
			$data['config_image_related_height'] = $store_info['config_image_related_height'];
		} else {
			$data['config_image_related_height'] = 80;
		}

		if (isset($this->request->post['config_image_compare_width'])) {
			$data['config_image_compare_width'] = $this->request->post['config_image_compare_width'];
		} elseif (isset($store_info['config_image_compare_width'])) {
			$data['config_image_compare_width'] = $store_info['config_image_compare_width'];
		} else {
			$data['config_image_compare_width'] = 90;
		}

		if (isset($this->request->post['config_image_compare_height'])) {
			$data['config_image_compare_height'] = $this->request->post['config_image_compare_height'];
		} elseif (isset($store_info['config_image_compare_height'])) {
			$data['config_image_compare_height'] = $store_info['config_image_compare_height'];
		} else {
			$data['config_image_compare_height'] = 90;
		}

		if (isset($this->request->post['config_image_wishlist_width'])) {
			$data['config_image_wishlist_width'] = $this->request->post['config_image_wishlist_width'];
		} elseif (isset($store_info['config_image_wishlist_width'])) {
			$data['config_image_wishlist_width'] = $store_info['config_image_wishlist_width'];
		} else {
			$data['config_image_wishlist_width'] = 50;
		}

		if (isset($this->request->post['config_image_wishlist_height'])) {
			$data['config_image_wishlist_height'] = $this->request->post['config_image_wishlist_height'];
		} elseif (isset($store_info['config_image_wishlist_height'])) {
			$data['config_image_wishlist_height'] = $store_info['config_image_wishlist_height'];
		} else {
			$data['config_image_wishlist_height'] = 50;
		}

		if (isset($this->request->post['config_image_cart_width'])) {
			$data['config_image_cart_width'] = $this->request->post['config_image_cart_width'];
		} elseif (isset($store_info['config_image_cart_width'])) {
			$data['config_image_cart_width'] = $store_info['config_image_cart_width'];
		} else {
			$data['config_image_cart_width'] = 80;
		}

		if (isset($this->request->post['config_image_cart_height'])) {
			$data['config_image_cart_height'] = $this->request->post['config_image_cart_height'];
		} elseif (isset($store_info['config_image_cart_height'])) {
			$data['config_image_cart_height'] = $store_info['config_image_cart_height'];
		} else {
			$data['config_image_cart_height'] = 80;
		}

		if (isset($this->request->post['config_image_location_width'])) {
			$data['config_image_location_width'] = $this->request->post['config_image_location_width'];
		} elseif (isset($store_info['config_image_location_width'])) {
			$data['config_image_location_width'] = $store_info['config_image_location_width'];
		} else {
			$data['config_image_location_width'] = 240;
		}

		if (isset($this->request->post['config_image_location_height'])) {
			$data['config_image_location_height'] = $this->request->post['config_image_location_height'];
		} elseif (isset($store_info['config_image_location_height'])) {
			$data['config_image_location_height'] = $store_info['config_image_location_height'];
		} else {
			$data['config_image_location_height'] = 180;
		}

		if (isset($this->request->post['config_secure'])) {
			$data['config_secure'] = $this->request->post['config_secure'];
		} elseif (isset($store_info['config_secure'])) {
			$data['config_secure'] = $store_info['config_secure'];
		} else {
			$data['config_secure'] = '';
		}

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('setting/store_form.tpl', $data));
	}

	protected function validateForm() {
		if (!$this->user->hasPermission('modify', 'setting/store')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		if (!$this->request->post['config_url']) {
			$this->error['url'] = $this->language->get('error_url');
		}

		if (!$this->request->post['config_name']) {
			$this->error['name'] = $this->language->get('error_name');
		}

		if ((utf8_strlen($this->request->post['config_owner']) < 3) || (utf8_strlen($this->request->post['config_owner']) > 64)) {
			$this->error['owner'] = $this->language->get('error_owner');
		}

		if ((utf8_strlen($this->request->post['config_address']) < 3) || (utf8_strlen($this->request->post['config_address']) > 256)) {
			$this->error['address'] = $this->language->get('error_address');
		}

		if ((utf8_strlen($this->request->post['config_email']) > 96) || !preg_match('/^[^\@]+@.*.[a-z]{2,15}$/i', $this->request->post['config_email'])) {
			$this->error['email'] = $this->language->get('error_email');
		}

		if ((utf8_strlen($this->request->post['config_telephone']) < 3) || (utf8_strlen($this->request->post['config_telephone']) > 32)) {
			$this->error['telephone'] = $this->language->get('error_telephone');
		}

		if (!$this->request->post['config_meta_title']) {
			$this->error['meta_title'] = $this->language->get('error_meta_title');
		}

		if (!empty($this->request->post['config_customer_group_display']) && !in_array($this->request->post['config_customer_group_id'], $this->request->post['config_customer_group_display'])) {
			$this->error['customer_group_display'] = $this->language->get('error_customer_group_display');
		}

		if (!$this->request->post['config_image_category_width'] || !$this->request->post['config_image_category_height']) {
			$this->error['image_category'] = $this->language->get('error_image_category');
		}

		if (!$this->request->post['config_image_thumb_width'] || !$this->request->post['config_image_thumb_height']) {
			$this->error['image_thumb'] = $this->language->get('error_image_thumb');
		}

		if (!$this->request->post['config_image_popup_width'] || !$this->request->post['config_image_popup_height']) {
			$this->error['image_popup'] = $this->language->get('error_image_popup');
		}

		if (!$this->request->post['config_image_product_width'] || !$this->request->post['config_image_product_height']) {
			$this->error['image_product'] = $this->language->get('error_image_product');
		}

		if (!$this->request->post['config_image_additional_width'] || !$this->request->post['config_image_additional_height']) {
			$this->error['image_additional'] = $this->language->get('error_image_additional');
		}

		if (!$this->request->post['config_image_related_width'] || !$this->request->post['config_image_related_height']) {
			$this->error['image_related'] = $this->language->get('error_image_related');
		}

		if (!$this->request->post['config_image_compare_width'] || !$this->request->post['config_image_compare_height']) {
			$this->error['image_compare'] = $this->language->get('error_image_compare');
		}

		if (!$this->request->post['config_image_wishlist_width'] || !$this->request->post['config_image_wishlist_height']) {
			$this->error['image_wishlist'] = $this->language->get('error_image_wishlist');
		}

		if (!$this->request->post['config_image_cart_width'] || !$this->request->post['config_image_cart_height']) {
			$this->error['image_cart'] = $this->language->get('error_image_cart');
		}

		if (!$this->request->post['config_image_location_width'] || !$this->request->post['config_image_location_height']) {
			$this->error['image_location'] = $this->language->get('error_image_location');
		}

		if (!$this->request->post['config_product_limit']) {
			$this->error['product_limit'] = $this->language->get('error_limit');
		}

		if (!$this->request->post['config_product_description_length']) {
			$this->error['product_description_length'] = $this->language->get('error_limit');
		}

		if ($this->error && !isset($this->error['warning'])) {
			$this->error['warning'] = $this->language->get('error_warning');
		}

		return !$this->error;
	}

	protected function validateDelete() {
		if (!$this->user->hasPermission('modify', 'setting/store')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		$this->load->model('sale/order');

		foreach ($this->request->post['selected'] as $store_id) {
			if (!$store_id) {
				$this->error['warning'] = $this->language->get('error_default');
			}

			$store_total = $this->model_sale_order->getTotalOrdersByStoreId($store_id);

			if ($store_total) {
				$this->error['warning'] = sprintf($this->language->get('error_store'), $store_total);
			}
		}

		return !$this->error;
	}

	public function template() {
		if ($this->request->server['HTTPS']) {
			$server = HTTPS_CATALOG;
		} else {
			$server = HTTP_CATALOG;
		}

		if (is_file(DIR_IMAGE . 'templates/' . basename($this->request->get['template']) . '.png')) {
			$this->response->setOutput($server . 'image/templates/' . basename($this->request->get['template']) . '.png');
		} else {
			$this->response->setOutput($server . 'image/no_image.jpg');
		}
	}

	public function country() {
		$json = array();

		$this->load->model('localisation/country');

		$country_info = $this->model_localisation_country->getCountry($this->request->get['country_id']);

		if ($country_info) {
			$this->load->model('localisation/zone');

			$json = array(
				'country_id'        => $country_info['country_id'],
				'name'              => $country_info['name'],
				'iso_code_2'        => $country_info['iso_code_2'],
				'iso_code_3'        => $country_info['iso_code_3'],
				'address_format'    => $country_info['address_format'],
				'postcode_required' => $country_info['postcode_required'],
				'zone'              => $this->model_localisation_zone->getZonesByCountryId($this->request->get['country_id']),
				'status'            => $country_info['status']
			);
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}
}