<?php
class ControllerExtensionPaymentQiwiRest extends Controller {
	private $error = array();

	public function index() {
		$this->load->language('extension/payment/qiwi_rest');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('setting/setting');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
            $this->load->model('setting/setting');
            $this->model_setting_setting->editSetting('qiwi_rest', $this->request->post);
            $this->session->data['success'] = sprintf($this->language->get('text_success'), $this->language->get('heading_title'));
            $this->response->redirect($this->url->link('extension/extension', 'token=' . $this->session->data['token'] . '&type=payment', 'SSL'));
        }

		$data['qiwi_rest_version'] = '1.3 for OpenCart 2.x';	
		$data['text_edit'] = $this->language->get('text_edit');

		$data['text_no_results'] = $this->language->get('text_no_results');
            $data['entry_qiwi_rest_group_name'] = $this->language->get('entry_qiwi_rest_group_name');
		$data['entry_qiwi_rest_group_markup'] = $this->language->get('entry_qiwi_rest_group_markup');
		$data['entry_qiwi_rest_group_desc'] = $this->language->get('entry_qiwi_rest_group_desc');
		$data['entry_qiwi_rest_groups_settings'] = $this->language->get('entry_qiwi_rest_groups_settings');
		$data['help_qiwi_rest_groups_settings'] = $this->language->get('help_qiwi_rest_groups_settings');

		$data['entry_qiwi_rest_show_pay_now'] = $this->language->get('entry_qiwi_rest_show_pay_now');
		$data['help_qiwi_rest_show_pay_now'] = $this->language->get('help_qiwi_rest_show_pay_now');

		$data['entry_qiwi_rest_name'] = $this->language->get('entry_qiwi_rest_name');
		$data['help_qiwi_rest_name'] = $this->language->get('help_qiwi_rest_name');

		$data['entry_qiwi_rest_total'] = $this->language->get('entry_qiwi_rest_total');	
		$data['help_qiwi_rest_total'] = $this->language->get('help_qiwi_rest_total');	

		$data['customer_groups'] = array();

		if (file_exists(DIR_APPLICATION . '/model/sale/customer_group.php')) 
		{		
			$this->load->model('sale/customer_group');
			$customer_group_total = $this->model_sale_customer_group->getTotalCustomerGroups();
			$results = $this->model_sale_customer_group->getCustomerGroups();

		}
		else
		{
			$this->load->model('customer/customer_group');
			$customer_group_total = $this->model_customer_customer_group->getTotalCustomerGroups();
			$results = $this->model_customer_customer_group->getCustomerGroups();

		}	


	
		foreach ($results as $result) {
		
			$data['customer_groups'][] = array(
				'customer_group_id' => $result['customer_group_id'],
				'name'              => $result['name'] . (($result['customer_group_id'] == $this->config->get('config_customer_group_id')) ? $this->language->get('text_default') : null)
			);
		}	


		$data['heading_title'] = $this->language->get('heading_title');

		$data['entry_qiwi_rest_shop_id'] = $this->language->get('entry_qiwi_rest_shop_id');
		$data['help_qiwi_rest_shop_id'] = $this->language->get('help_qiwi_rest_shop_id');

		$data['entry_qiwi_rest_id'] = $this->language->get('entry_qiwi_rest_id');
		$data['help_qiwi_rest_id'] = $this->language->get('help_qiwi_rest_id');

		$data['entry_qiwi_rest_password'] = $this->language->get('entry_qiwi_rest_password');
		$data['help_qiwi_rest_password'] = $this->language->get('help_qiwi_rest_password');

		$data['entry_qiwi_rest_ccy_select'] = $this->language->get('entry_qiwi_rest_ccy_select');
		$data['help_qiwi_rest_ccy_select'] = $this->language->get('help_qiwi_rest_ccy_select');

		$data['entry_qiwi_rest_markup'] = $this->language->get('entry_qiwi_rest_markup');
		$data['help_qiwi_rest_markup'] = $this->language->get('help_qiwi_rest_markup');

		$data['entry_group_markup'] = $this->language->get('entry_group_markup');

		$data['entry_qiwi_rest_mode_select'] = $this->language->get('entry_qiwi_rest_mode_select');
		$data['help_qiwi_rest_mode_select'] = $this->language->get('help_qiwi_rest_mode_select');

		$data['entry_qiwi_rest_modes'][] = array('code'=>'qiwi_rest_mode_normal', 'code_text'=>$this->language->get('entry_qiwi_rest_mode_normal'));
		$data['entry_qiwi_rest_modes'][] = array('code'=>'qiwi_rest_mode_debug', 'code_text'=>$this->language->get('entry_qiwi_rest_mode_debug'));


		$data['entry_qiwi_rest_mode_show_picture'] = $this->language->get('entry_qiwi_rest_mode_show_picture');
		$data['help_qiwi_rest_mode_show_picture'] = $this->language->get('help_qiwi_rest_mode_show_picture');

		$data['entry_qiwi_rest_modes_show_picture'][] = array('code'=>'qiwi_rest_show_picture_on', 'code_text'=>$this->language->get('entry_qiwi_rest_show_picture_on'));
		$data['entry_qiwi_rest_modes_show_picture'][] = array('code'=>'qiwi_rest_show_picture_off', 'code_text'=>$this->language->get('entry_qiwi_rest_show_picture_off'));
	
		$data['entry_qiwi_rest_result_url'] = $this->language->get('entry_qiwi_rest_result_url');
		$data['help_qiwi_rest_result_url'] = $this->language->get('help_qiwi_rest_result_url');

		$data['text_enabled'] = $this->language->get('text_enabled');
		$data['text_disabled'] = $this->language->get('text_disabled');
		$data['text_all_zones'] = $this->language->get('text_all_zones');

		$data['entry_qiwi_rest_order_status_id'] = $this->language->get('entry_qiwi_rest_order_status_id');
		$data['help_qiwi_rest_order_status_id'] = $this->language->get('help_qiwi_rest_order_status_id');

		$data['entry_qiwi_rest_order_status_cancel_id'] = $this->language->get('entry_qiwi_rest_order_status_cancel_id');
		$data['help_qiwi_rest_order_status_cancel_id'] = $this->language->get('help_qiwi_rest_order_status_cancel_id');

		$data['entry_qiwi_rest_order_status_progress_id'] = $this->language->get('entry_qiwi_rest_order_status_progress_id');
		$data['help_qiwi_rest_order_status_progress_id'] = $this->language->get('help_qiwi_rest_order_status_progress_id');

		$data['entry_qiwi_rest_lifetime'] = $this->language->get('entry_qiwi_rest_lifetime');
		$data['help_qiwi_rest_lifetime'] = $this->language->get('help_qiwi_rest_lifetime');

		$data['entry_qiwi_rest_geo_zone_id'] = $this->language->get('entry_qiwi_rest_geo_zone_id');
		$data['help_qiwi_rest_geo_zone_id'] = $this->language->get('help_qiwi_rest_geo_zone_id');

		$data['entry_qiwi_rest_status'] = $this->language->get('entry_qiwi_rest_status');
		$data['help_qiwi_rest_status'] = $this->language->get('help_qiwi_rest_status');

		$data['entry_qiwi_rest_sort_order'] = $this->language->get('entry_qiwi_rest_sort_order');
		$data['help_qiwi_rest_sort_order'] = $this->language->get('help_qiwi_rest_sort_order');

		$data['button_save'] = $this->language->get('button_save');
		$data['button_cancel'] = $this->language->get('button_cancel');

		$data['tab_general'] = $this->language->get('tab_general');

 		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}

 		if (isset($this->error['shop_id'])) {
			$data['error_qiwi_rest_shop_id'] = $this->error['shop_id'];
		} else {
			$data['error_qiwi_rest_shop_id'] = '';
		}

		if (isset($this->error['rest_id'])) {
			$data['error_qiwi_rest_id'] = $this->error['rest_id'];
		} else {
			$data['error_qiwi_rest_id'] = '';
		}


		if (isset($this->error['password'])) {
			$data['error_qiwi_rest_password'] = $this->error['password'];
		} else {
			$data['error_qiwi_rest_password'] = '';
		}

		if (isset($this->error['lifetime'])) {
			$data['error_qiwi_rest_lifetime'] = $this->error['lifetime'];
		} else {
			$data['error_qiwi_rest_lifetime'] = '';
		}

		$data['breadcrumbs'] = array();

   		$data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('text_home'),
			'href'      => $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL'),
      		'separator' => false
   		);

   		$data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('text_payment'),
			'href'      => $this->url->link('extension/extension', 'token=' . $this->session->data['token'] . '&type=payment', 'SSL'),
      		'separator' => ' :: '
   		);

   		$data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('heading_title'),
			'href'      => $this->url->link('extension/payment/qiwi_rest', 'token=' . $this->session->data['token'], 'SSL'),
      		'separator' => ' :: '
   		);

        $data['action']         = $this->url->link('extension/payment/qiwi_rest', 'token=' . $this->session->data['token'], 'SSL');
        $data['cancel']         = $this->url->link('extension/extension', 'token=' . $this->session->data['token'] . '&type=payment', 'SSL');

		$this->load->model('localisation/currency');
		$data['currencies'] = $this->model_localisation_currency->getCurrencies();

		// Номер магазина
		if (isset($this->request->post['qiwi_rest_shop_id'])) {
			$data['qiwi_rest_shop_id'] = $this->request->post['qiwi_rest_shop_id'];
		} else {
			$data['qiwi_rest_shop_id'] = $this->config->get('qiwi_rest_shop_id');
		}

		if (isset($this->request->post['qiwi_rest_id'])) {
			$data['qiwi_rest_id'] = $this->request->post['qiwi_rest_id'];
		} else {
			$data['qiwi_rest_id'] = $this->config->get('qiwi_rest_id');
		}


		if (isset($this->request->post['qiwi_rest_password'])) {
			$data['qiwi_rest_password'] = $this->request->post['qiwi_rest_password'];
		} else {
			$data['qiwi_rest_password'] = $this->config->get('qiwi_rest_password');
		}

		// URL

		if (isset($this->request->server['HTTPS']) && (($this->request->server['HTTPS'] == 'on') || ($this->request->server['HTTPS'] == '1'))) {
			$data['qiwi_rest_result_url'] = HTTPS_CATALOG . 'index.php?route=extension/payment/qiwi_rest/callback'; 
		} else {
			$data['qiwi_rest_result_url'] = HTTP_CATALOG . 'index.php?route=extension/payment/qiwi_rest/callback'; 
		}


		if (isset($this->request->post['qiwi_rest_order_status_id'])) {
			$data['qiwi_rest_order_status_id'] = $this->request->post['qiwi_rest_order_status_id'];
		} else {
			$data['qiwi_rest_order_status_id'] = $this->config->get('qiwi_rest_order_status_id');
		}

		if (isset($this->request->post['qiwi_rest_order_status_cancel_id'])) {
			$data['qiwi_rest_order_status_cancel_id'] = $this->request->post['qiwi_rest_order_status_cancel_id'];
		} else {
			$data['qiwi_rest_order_status_cancel_id'] = $this->config->get('qiwi_rest_order_status_cancel_id');
		}

		if (isset($this->request->post['qiwi_rest_order_status_progress_id'])) {
			$data['qiwi_rest_order_status_progress_id'] = $this->request->post['qiwi_rest_order_status_progress_id'];
		} else {
			$data['qiwi_rest_order_status_progress_id'] = $this->config->get('qiwi_rest_order_status_progress_id');
		}

		if (isset($this->request->post['qiwi_rest_lifetime'])) {
			$data['qiwi_rest_lifetime'] = (int)$this->request->post['qiwi_rest_lifetime'];
		} elseif( $this->config->get('qiwi_rest_lifetime') ) {
			$data['qiwi_rest_lifetime'] = (int)$this->config->get('qiwi_rest_lifetime');
		} else {
			$data['qiwi_rest_lifetime'] = 24;
		}


		if (isset($this->request->post['qiwi_rest_markup'])) {
			$data['qiwi_rest_markup'] = $this->request->post['qiwi_rest_markup'];
		} elseif( $this->config->get('qiwi_rest_markup') ) {
			$data['qiwi_rest_markup'] = $this->config->get('qiwi_rest_markup');
		} else {
			$data['qiwi_rest_markup'] = 0.0;
		}

		if (isset($this->request->post['qiwi_rest_group_markup'])) {
			$data['qiwi_rest_group_markup'] = $this->request->post['qiwi_rest_group_markup'];
		} elseif( $this->config->get('qiwi_rest_group_markup') ) {
			$data['qiwi_rest_group_markup'] = $this->config->get('qiwi_rest_group_markup');
		} else {
			$data['qiwi_rest_group_markup'] = 0.0;
		}

		if (isset($this->request->post['qiwi_rest_group_desc'])) {
			$data['qiwi_rest_group_desc'] = $this->request->post['qiwi_rest_group_desc'];
		} elseif( $this->config->get('qiwi_rest_group_desc') ) {
			$data['qiwi_rest_group_desc'] = $this->config->get('qiwi_rest_group_desc');
		} else {
			$data['qiwi_rest_group_desc'] = 0.0;
		}


		if (isset($this->request->post['qiwi_rest_ccy_select'])) {
			$data['qiwi_rest_ccy_select'] = $this->request->post['qiwi_rest_ccy_select'];
		} elseif( $this->config->get('qiwi_rest_ccy_select') ) {
			$data['qiwi_rest_ccy_select'] = $this->config->get('qiwi_rest_ccy_select');
		} else {
			$data['qiwi_rest_ccy_select'] = 'RUB';
		}

		if (isset($this->request->post['qiwi_rest_show_pay_now'])) {
			$data['qiwi_rest_show_pay_now'] = $this->request->post['qiwi_rest_show_pay_now'];
		} elseif( $this->config->get('qiwi_rest_show_pay_now') ) {
			$data['qiwi_rest_show_pay_now'] = $this->config->get('qiwi_rest_show_pay_now');
		} else {
			//$data['qiwi_rest_show_pay_now'] = '1';
		}

		if (isset($this->request->post['qiwi_rest_name'])) {
			$data['qiwi_rest_name'] = $this->request->post['qiwi_rest_name'];
		} elseif( $this->config->get('qiwi_rest_name') ) {
			$data['qiwi_rest_name'] = $this->config->get('qiwi_rest_name');
		} else {
			$data['qiwi_rest_name'] = $this->language->get('qiwi_rest_name');
		}


		if (isset($this->request->post['qiwi_rest_total'])) {
			$data['qiwi_rest_total'] = $this->request->post['qiwi_rest_total'];
		} elseif( $this->config->get('qiwi_rest_total') ) {
			$data['qiwi_rest_total'] = $this->config->get('qiwi_rest_total');
		} else {
			$data['qiwi_rest_total'] = 0.0;
		}


		$this->load->model('localisation/order_status');

		$data['order_statuses'] = $this->model_localisation_order_status->getOrderStatuses();

		if (isset($this->request->post['qiwi_rest_geo_zone_id'])) {
			$data['qiwi_rest_geo_zone_id'] = $this->request->post['qiwi_rest_geo_zone_id'];
		} else {
			$data['qiwi_rest_geo_zone_id'] = $this->config->get('qiwi_rest_geo_zone_id');
		}

		$this->load->model('localisation/geo_zone');

		$data['geo_zones'] = $this->model_localisation_geo_zone->getGeoZones();

		if (isset($this->request->post['qiwi_rest_status'])) {
			$data['qiwi_rest_status'] = $this->request->post['qiwi_rest_status'];
		} else {
			$data['qiwi_rest_status'] = $this->config->get('qiwi_rest_status');
		}

		if (isset($this->request->post['qiwi_rest_sort_order'])) {
			$data['qiwi_rest_sort_order'] = $this->request->post['qiwi_rest_sort_order'];
		} else {
			$data['qiwi_rest_sort_order'] = $this->config->get('qiwi_rest_sort_order');
		}

		if (isset($this->request->post['qiwi_rest_mode_select'])) {
			$data['qiwi_rest_mode_select'] = $this->request->post['qiwi_rest_mode_select'];
		} else {
			$data['qiwi_rest_mode_select'] = $this->config->get('qiwi_rest_mode_select');
		}

		if (isset($this->request->post['qiwi_rest_mode_show_picture'])) {
			$data['qiwi_rest_mode_show_picture'] = $this->request->post['qiwi_rest_mode_show_picture'];
		} else {
			$data['qiwi_rest_mode_show_picture'] = $this->config->get('qiwi_rest_mode_show_picture');
		}


		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('extension/payment/qiwi_rest.tpl', $data));

	}

	private function validate() {
		if (!$this->user->hasPermission('modify', 'extension/payment/qiwi_rest')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}


		// TODO проверку на валидность номера!
		if (!$this->request->post['qiwi_rest_shop_id']) {
			$this->error['shop_id'] = $this->language->get('error_shop_id');
		}

		if (!$this->request->post['qiwi_rest_id']) {
			$this->error['rest_id'] = $this->language->get('error_rest_id');
		}

		if (!$this->request->post['qiwi_rest_password']) {
			$this->error['password'] = $this->language->get('error_password');
		}

		if (!$this->request->post['qiwi_rest_lifetime']) {
			$this->error['lifetime'] = $this->language->get('error_lifetime');
		}

		if (!$this->error) {
			return TRUE;
		} else {
			return FALSE;
		}
	}
}
