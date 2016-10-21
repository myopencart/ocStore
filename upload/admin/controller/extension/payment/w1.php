<?php
/**
 * Платежная система Wallet One Единая касса
 * 
 * @cms    Opencart
 * @author     Wallet One
 * @version    1.0.1
 * @license    
 * @copyright  Copyright (c) 2016 Wallet One (http://www.walletone.com)
 * This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation; either version 2 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
 */

class ControllerExtensionPaymentW1 extends Controller {

  private $error = array();
  
  /**
   * Page output settings.
   */
  public function index() {
    $this->load->language('extension/payment/w1');
    
    if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
      $this->load->model('setting/setting');

      $this->model_setting_setting->editSetting('w1', $this->request->post);

      $this->session->data['success'] = $this->language->get('text_success');

      $this->response->redirect($this->url->link('extension/extension', 'type=payment&token=' . $this->session->data['token'], true));
    }

    $this->document->setTitle($this->language->get('heading_title'));
    $this->document->addStyle('view/stylesheet/w1.css');

    //standart settings
    $data['heading_title'] = $this->language->get('heading_title');

    $data['text_edit'] = $this->language->get('text_edit');
    $data['text_enabled'] = $this->language->get('text_enabled');
    $data['text_disabled'] = $this->language->get('text_disabled');
    $data['text_all_zones'] = $this->language->get('text_all_zones');
    $data['text_about'] = $this->language->get('text_about');
    $data['text_connect'] = $this->language->get('text_connect');
    $data['text_integration'] = $this->language->get('text_integration');
    $data['text_analityc'] = $this->language->get('text_analityc');
    $data['text_support'] = $this->language->get('text_support');
    $data['text_fast_setting'] = $this->language->get('text_fast_setting');
    $data['text_register'] = $this->language->get('text_register');
    $data['text_fill_fields'] = $this->language->get('text_fill_fields');
    $data['text_payment_methods'] = $this->language->get('text_payment_methods');
    
    $data['entry_pending_status'] = $this->language->get('entry_pending_status');
    $data['entry_completed_status'] = $this->language->get('entry_completed_status');
    $data['entry_failed_status'] = $this->language->get('entry_failed_status');
    $data['entry_processed_status'] = $this->language->get('entry_processed_status');
    
    $data['entry_return_url'] = $this->language->get('entry_return_url');
    
    $data['entry_geo_zone'] = $this->language->get('entry_geo_zone');
    $data['entry_status'] = $this->language->get('entry_status');
    $data['entry_sort_order'] = $this->language->get('entry_sort_order');

    $server = isset($this->request->server['HTTPS']) && $this->request->server['HTTPS'] ? HTTPS_CATALOG : HTTP_CATALOG;
    
    //api fields
    $fields_list = array('merchant_id', 'currency_id', 'signature', 'ptenabled', 'ptdisabled');
    foreach ($fields_list as $value) {
      $data['entry_'.$value] = $this->language->get('entry_'.$value);
    }
    
    $currency = array(643, 710, 840, 978, 980, 398, 974, 972);
    foreach ($currency as $value) {
      $data['currencies'][$value] = $this->language->get('entry_currency_'.$value);
    }
    
    $this->load->model('localisation/order_status');
		$data['order_statuses'] = $this->model_localisation_order_status->getOrderStatuses();
    
    $data['list_payments'] = $this->getPayments();
    $data['icon_path'] = 'view/image/payment/w1/icon/';

    $data['help_merchant_id'] = $this->language->get('help_merchant_id');
    $data['help_signature'] = $this->language->get('help_signature');

    $data['button_save'] = $this->language->get('button_save');
    $data['button_cancel'] = $this->language->get('button_cancel');
    
    //tabs
    $data['tab_info'] = $this->language->get('tab_info');
    $data['tab_api'] = $this->language->get('tab_api');
		$data['tab_dop_api'] = $this->language->get('tab_dop_api');
    $data['tab_order_status'] = $this->language->get('tab_order_status');
    
    //error processing
    if (isset($this->error['warning'])) {
      $data['error_warning'] = $this->error['warning'];
    }
    else {
      $data['error_warning'] = '';
    }
    
    if (isset($this->error['merchant_id'])) {
      $data['error_merchant_id'] = $this->error['merchant_id'];
    }
    else {
      $data['error_merchant_id'] = '';
    }
    
    if (isset($this->error['currency_id'])) {
      $data['error_currency_id'] = $this->error['currency_id'];
    }
    else {
      $data['error_currency_id'] = '';
    }
    
    if (isset($this->error['signature'])) {
      $data['error_signature'] = $this->error['signature'];
    }
    else {
      $data['error_signature'] = '';
    }
    

    $data['breadcrumbs'] = array();

    $data['breadcrumbs'][] = array(
      'text' => $this->language->get('text_home'),
      'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], 'SSL')
    );

    $data['breadcrumbs'][] = array(
      'text' => $this->language->get('text_payment'),
      'href' => $this->url->link('extension/extension', 'type=payment&token=' . $this->session->data['token'], 'SSL')
    );

    $data['breadcrumbs'][] = array(
      'text' => $this->language->get('heading_title'),
      'href' => $this->url->link('extension/payment/w1', 'token=' . $this->session->data['token'], true)
    );
    
    //buttons in right
    $data['action'] = $this->url->link('extension/payment/w1', 'token=' . $this->session->data['token'], true);
    $data['cancel'] = $this->url->link('extension/extension', 'type=payment&token=' . $this->session->data['token'], true);
    
    //api fields
    foreach ($fields_list as $value) {
      if (isset($this->request->post[$value])) {
        $data['w1_'.$value] = $this->request->post['w1_'.$value];
      }
      else {
        $data['w1_'.$value] = $this->config->get('w1_'.$value);
      }
    }

    $data['w1_return_url'] = $server . 'index.php?route=extension/payment/w1/result';
    
    //status success, error and waiting payment order
    if (isset($this->request->post['w1_order_status_pending_id'])) {
      $data['w1_order_status_pending_id'] = $this->request->post['w1_order_status_pending_id'];
    }
    else {
      $data['w1_order_status_pending_id'] = $this->config->get('w1_order_status_pending_id');
    }

    if (isset($this->request->post['w1_order_status_completed_id'])) {
      $data['w1_order_status_completed_id'] = $this->request->post['w1_order_status_completed_id'];
    }
    else {
      $data['w1_order_status_completed_id'] = $this->config->get('w1_order_status_completed_id');
    }
    
    if (isset($this->request->post['w1_order_status_failed_id'])) {
      $data['w1_order_status_failed_id'] = $this->request->post['w1_order_status_failed_id'];
    }
    else {
      $data['w1_order_status_failed_id'] = $this->config->get('w1_order_status_failed_id');
    }
    
    if (isset($this->request->post['w1_order_status_processed_id'])) {
      $data['w1_order_status_processed_id'] = $this->request->post['w1_order_status_processed_id'];
    }
    else {
      $data['w1_order_status_processed_id'] = $this->config->get('w1_order_status_processed_id');
    }
    
    
    if (isset($this->request->post['w1_geo_zone_id'])) {
      $data['w1_geo_zone_id'] = $this->request->post['w1_geo_zone_id'];
    }
    else {
      $data['w1_geo_zone_id'] = $this->config->get('w1_geo_zone_id');
    }
    $this->load->model('localisation/geo_zone');
    $data['geo_zones'] = $this->model_localisation_geo_zone->getGeoZones();

    if (isset($this->request->post['w1_status'])) {
      $data['w1_status'] = $this->request->post['w1_status'];
    }
    else {
      $data['w1_status'] = $this->config->get('w1_status');
    }

    if (isset($this->request->post['w1_sort_order'])) {
      $data['w1_sort_order'] = $this->request->post['w1_sort_order'];
    }
    else {
      $data['w1_sort_order'] = $this->config->get('w1_sort_order');
    }
    
    $data['header'] = $this->load->controller('common/header');
    $data['column_left'] = $this->load->controller('common/column_left');
    $data['footer'] = $this->load->controller('common/footer');

    $this->response->setOutput($this->load->view('extension/payment/w1', $data));
  }
  
  /*
   * Get all payment methods from file.
   */
  protected function getPayments() {
    $filename = DIR_APPLICATION . 'view/files/payment/w1/payments.json';
    if($this->config->get('config_language') != 'ru-ru'){
      $filename = DIR_APPLICATION . 'view/files/payment/w1/payments_en.json';
    }
    $list_payments = array();
    if (file_exists($filename)) {
      $text = file_get_contents($filename);
      $list_payments = json_decode($text);
    }
    
    return $list_payments;
  }
  
  /**
   * Validate settings before save.
   * 
   * @return type
   */
  protected function validate() {
    if (!$this->user->hasPermission('modify', 'extension/payment/w1')) {
      $this->error['warning'] = $this->language->get('error_permission');
    }
    
    $fields_list = array('merchant_id', 'currency_id', 'signature');
    foreach ($fields_list as $value) {
      if (!$this->request->post['w1_'.$value]) {
        $this->error['warning'] = $this->error[$value] = $this->language->get('error_'.$value);
      }
    }
    
    return !$this->error;
  }

}
