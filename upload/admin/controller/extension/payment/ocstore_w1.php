<?php
/**
 * Платежная система Wallet One (Единая касса)
 * 
 * @cms       ocStore 2.3
 * @author    OcTeam
 * @support   https://opencartforum.com/user/3463-shoputils
 * @version   1.0
 * @copyright  Copyright (c) 2016 OcTeam (http://myopencart.com , http://opencartforum.com)
 */
class ControllerExtensionPaymentOcstoreW1 extends Controller {
	private $error = array();
	
	public function index() {
		$this->load->language('extension/payment/ocstore_w1');
		
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
			$this->load->model('setting/setting');

			$this->request->post['ocstore_w1_shop_id'] = trim($this->request->post['ocstore_w1_shop_id']);
			$this->request->post['ocstore_w1_sign'] = trim($this->request->post['ocstore_w1_sign']);

			$this->model_setting_setting->editSetting('ocstore_w1', $this->request->post);
			
			$this->session->data['success'] = $this->language->get('text_success');

			$this->response->redirect($this->url->link('extension/extension', 'type=payment&token=' . $this->session->data['token'], 'SSL'));
		}

		$this->document->setTitle($this->language->get('heading_title'));

		$data['heading_title'] = $this->language->get('heading_title');
		
		$data['button_save'] = $this->language->get('button_save');
		$data['button_cancel'] = $this->language->get('button_cancel');
		
		$data['text_enabled'] = $this->language->get('text_enabled');
		$data['text_disabled'] = $this->language->get('text_disabled');
		$data['text_all_zones'] = $this->language->get('text_all_zones');
		$data['text_setting'] = $this->language->get('text_setting');
		$data['text_info'] = $this->language->get('text_info');
		$data['text_register'] = $this->language->get('text_register');
		$data['text_instruction'] = $this->language->get('text_instruction');
		
		$data['entry_shop_id'] = $this->language->get('entry_shop_id');
		$data['entry_sign'] = $this->language->get('entry_sign');
		$data['entry_currency'] = $this->language->get('entry_currency');
		$data['entry_result_url'] = $this->language->get('entry_result_url');
		$data['entry_ecp'] = $this->language->get('entry_ecp');
		
		$data['entry_order_confirm_status'] = $this->language->get('entry_order_confirm_status');
		$data['entry_order_status'] = $this->language->get('entry_order_status');
		$data['entry_order_fail_status'] = $this->language->get('entry_order_fail_status');
		$data['entry_geo_zone'] = $this->language->get('entry_geo_zone');
		$data['entry_status'] = $this->language->get('entry_status');
		$data['entry_sort_order'] = $this->language->get('entry_sort_order');
		
		if (isset($this->session->data['success'])) {
			$data['success'] = $this->session->data['success'];
			unset($this->session->data['success']);
		} else {
			$data['success'] = '';
		}

		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}
		
		if (isset($this->error['shop_id'])) {
			$data['error_shop_id'] = $this->error['shop_id'];
		} else {
			$data['error_shop_id'] = '';
		}
		
		if (isset($this->error['sign'])) {
			$data['error_sign'] = $this->error['sign'];
		} else {
			$data['error_sign'] = '';
		}
		
   	$data['breadcrumbs'][] = array(
      'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], 'SSL')
   	);

   	$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_extension'),
			'href' => $this->url->link('extension/extension', 'type=payment&token=' . $this->session->data['token'], 'SSL')
   	);

   	$data['breadcrumbs'][] = array(
      'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('extension/payment/ocstore_w1', 'token=' . $this->session->data['token'], 'SSL')
   	);
				
		$data['action'] = $this->url->link('extension/payment/ocstore_w1', 'token=' . $this->session->data['token'], 'SSL');
		$data['cancel'] = $this->url->link('extension/extension', 'type=payment&token=' . $this->session->data['token'], 'SSL');

		if (isset($this->request->post['ocstore_w1_shop_id'])) {
			$data['ocstore_w1_shop_id'] = $this->request->post['ocstore_w1_shop_id'];
		} else {
			$data['ocstore_w1_shop_id'] = $this->config->get('ocstore_w1_shop_id');
		}
		
		if (isset($this->request->post['ocstore_w1_sign'])) {
			$data['ocstore_w1_sign'] = $this->request->post['ocstore_w1_sign'];
		} else {
			$data['ocstore_w1_sign'] = $this->config->get('ocstore_w1_sign');
		}

		
		if (isset($this->request->post['ocstore_w1_currency'])) {
			$data['ocstore_w1_currency'] = $this->request->post['ocstore_w1_currency'];
		} else {
			$data['ocstore_w1_currency'] = $this->config->get('ocstore_w1_currency');
		}
		
		$this->load->model('localisation/currency');
		$data['currencies'] = $this->model_localisation_currency->getCurrencies();
		
		$server = isset($this->request->server['HTTPS']) && $this->request->server['HTTPS'] ? HTTPS_CATALOG : HTTP_CATALOG;

		$data['ocstore_w1_result_url'] = $server . 'index.php?route=extension/payment/ocstore_w1/callback';
		
		
		if (isset($this->request->post['ocstore_w1_order_confirm_status_id'])) {
			$data['ocstore_w1_order_confirm_status_id'] = $this->request->post['ocstore_w1_order_confirm_status_id'];
		} else {
			$data['ocstore_w1_order_confirm_status_id'] = $this->config->get('ocstore_w1_order_confirm_status_id'); 
		}

		if (isset($this->request->post['ocstore_w1_order_status_id'])) {
			$data['ocstore_w1_order_status_id'] = $this->request->post['ocstore_w1_order_status_id'];
		} else {
			$data['ocstore_w1_order_status_id'] = $this->config->get('ocstore_w1_order_status_id'); 
		}

		if (isset($this->request->post['ocstore_w1_order_fail_status_id'])) {
			$data['ocstore_w1_order_fail_status_id'] = $this->request->post['ocstore_w1_order_fail_status_id'];
		} else {
			$data['ocstore_w1_order_fail_status_id'] = $this->config->get('ocstore_w1_order_fail_status_id'); 
		}
		
		$this->load->model('localisation/order_status');
		
		$data['order_statuses'] = $this->model_localisation_order_status->getOrderStatuses();
		
		if (isset($this->request->post['ocstore_w1_geo_zone_id'])) {
			$data['ocstore_w1_geo_zone_id'] = $this->request->post['ocstore_w1_geo_zone_id'];
		} else {
			$data['ocstore_w1_geo_zone_id'] = $this->config->get('ocstore_w1_geo_zone_id'); 
		}
		
		$this->load->model('localisation/geo_zone');
		
		$data['geo_zones'] = $this->model_localisation_geo_zone->getGeoZones();
		
		if (isset($this->request->post['ocstore_w1_status'])) {
			$data['ocstore_w1_status'] = $this->request->post['ocstore_w1_status'];
		} else {
			$data['ocstore_w1_status'] = $this->config->get('ocstore_w1_status');
		}
		
		if (isset($this->request->post['ocstore_w1_sort_order'])) {
			$data['ocstore_w1_sort_order'] = $this->request->post['ocstore_w1_sort_order'];
		} else {
			$data['ocstore_w1_sort_order'] = $this->config->get('ocstore_w1_sort_order');
		}
		
		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');
		
		$this->response->setOutput($this->load->view('extension/payment/ocstore_w1.tpl', $data));
	}

	protected function validate() {
		if (!$this->validatePermission()) {
			$this->error['warning'] = $this->language->get('error_permission');
		}
		
		if (!trim($this->request->post['ocstore_w1_shop_id'])) {
			$this->error['warning'] = $this->error['shop_id'] = $this->language->get('error_shop_id');
		}
		
		if (!trim($this->request->post['ocstore_w1_sign'])) {
			$this->error['warning'] = $this->error['sign'] = $this->language->get('error_sign');
		}
		
		return !$this->error;
	}

  protected function validatePermission() {
    return $this->user->hasPermission('modify', 'extension/payment/ocstore_w1');
  }
}
?>