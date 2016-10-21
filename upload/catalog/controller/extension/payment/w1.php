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
  
  /**
   * The output form to send the payment system.
   * 
   * @return type
   */
  public function index() {
    $this->load->language('extension/payment/w1');
    $this->load->model('extension/payment/w1');
    $data['button_confirm'] = $this->language->get('button_confirm');
    $data['button_back'] = $this->language->get('button_back');

    $data['action'] = 'https://wl.walletone.com/checkout/checkout/Index';

    $this->load->model('checkout/order');
    $order_info = $this->model_checkout_order->getOrder($this->session->data['order_id']);
    
    $fields = array();
    $fields['CMS'] = 15;
    $fields["WMI_EXPIRED_DATE"] = '2019-12-31T23:59:59';
    $fields['WMI_MERCHANT_ID'] = $this->config->get('w1_merchant_id');
    $fields['WMI_PAYMENT_AMOUNT'] = number_format($order_info['total'], 2, '.', '');
    $fields['WMI_CURRENCY_ID'] = $this->config->get('w1_currency_id');
    $fields['WMI_PAYMENT_NO'] = $this->session->data['order_id'].'_'.$this->request->server['HTTP_HOST'];
    $fields['WMI_DESCRIPTION'] = 'BASE64:'.base64_encode(sprintf($this->language->get('text_desc'), $this->session->data['order_id']));
    $fields['WMI_SUCCESS_URL'] = $this->url->link('extension/payment/w1/resultpayment', '', 'SSL');
    $fields['WMI_FAIL_URL'] = $this->url->link('extension/payment/w1/resultpayment', '', 'SSL');
    
    if($this->config->get('w1_ptenabled')) {
      $fields['WMI_PTENABLED'] = $this->config->get('w1_ptenabled');
    }
    
    if($this->config->get('w1_ptdisabled')) {
      $fields['WMI_PTDISABLED'] = $this->config->get('w1_ptdisabled');
    }
    
    if(!empty($order_info['email'])){
      $fields['WMI_RECIPIENT_LOGIN'] = $order_info['email'];
      $fields['WMI_CUSTOMER_EMAIL'] = $order_info['email'];
    }
    
    if(!empty($order_info['firstname'])){
      $fields['WMI_CUSTOMER_FIRSTNAME'] = $order_info['firstname'];
    }
    
    if(!empty($order_info['lastname'])){
      $fields['WMI_CUSTOMER_LASTNAME'] = $order_info['lastname'];
    }

    foreach ($fields as $name => $val) {
      if (is_array($val)) {
        usort($val, "strcasecmp");
        $fields[$name] = $val;
      }
    }

    uksort($fields, "strcasecmp");
    $fieldValues = "";

    foreach ($fields as $value) {
      if (is_array($value)) {
        foreach ($value as $v) {
          $v = mb_convert_encoding($v, "Windows-1251", "UTF-8");
          $fieldValues .= $v;
        }
      }
      else {
        $value = mb_convert_encoding($value, "Windows-1251", "UTF-8");
        $fieldValues .= $value;
      }
    }
    
    $signature = base64_encode(pack("H*", md5($fieldValues . $this->config->get('w1_signature'))));
    $fields["WMI_SIGNATURE"] = $signature;
    $data = array_merge($data, $fields);
    return $this->load->view('extension/payment/w1', $data);
  }
  
  /**
   * Page after processing the return with result payment the buyer.
   * 
   * @return boolean
   */
  public function resultpayment(){
    $data['footer'] = $this->load->controller('common/footer');
		$data['header'] = $this->load->controller('common/header');
    
    $this->load->language('extension/payment/w1');
    $this->load->model('extension/payment/w1');
    
    if(empty($this->request->post)){
      $data['error'] = $this->language->get('error_post');
      $this->response->setOutput($this->load->view('extension/payment/w1_error', $data));
      return true;
    }
    
    if(!$this->checkSig($this->request->request, $this->config->get('w1_signature'))){
      $data['error'] = $this->language->get('error_signatue');
      $this->response->setOutput($this->load->view('extension/payment/w1_error', $data));
      return true;
    }
    
    $post = array();
    foreach ($this->request->request as $key => $val) {
      if($key == 'route') {
        continue;
      }
      if ($key != 'WMI_FAIL_URL' && $key != 'WMI_SUCCESS_URL' && $key != 'WMI_SIGNATURE'
          && $key != 'WMI_DESCRIPTION' && preg_match('/^[a-z0-9\._\-\: ]+$/ui', urldecode($val))) {
        $post[$key] = htmlentities(urldecode($val));
        if (strlen($post[$key]) > 250) {
          $post[$key] = substr($post[$key], 0, 250);
        }
      }
      elseif (($key == 'WMI_FAIL_URL' || $key == 'WMI_SUCCESS_URL') && preg_match('/^[a-z0-9\._\-\:\/\&\?=]+$/ui', urldecode($val))) {
        $post[$key] = htmlentities(urldecode($val));
        if (strlen($post[$key]) > 500) {
          $post[$key] = substr($post[$key], 0, 500);
        }
      }
      elseif ($key == 'WMI_SIGNATURE' && preg_match('/^[a-z0-9\._\-\+\=\/\\\]+$/ui', urldecode($val))) {
        $post[$key] = htmlentities(urldecode($val));
        if (strlen($post[$key]) > 500) {
          $post[$key] = substr($post[$key], 0, 500);
        }
      }
      elseif ($key == 'WMI_DESCRIPTION') {
        if(!empty($this->request->server['HTTP_USER_AGENT']) && preg_match('/^[a-zа-яё0-9№\. ]+$/ui', mb_convert_encoding(urldecode($val), "UTF-8", "Windows-1251"))) {
          $post[$key] = htmlentities(mb_convert_encoding(urldecode($val), "UTF-8", "Windows-1251"));
        }
        elseif(preg_match('/^[a-zа-яё0-9№\. ]+$/ui', mb_convert_encoding(urldecode($val), "UTF-8", "Windows-1251"))) {
          $post[$key] = htmlentities(mb_convert_encoding(urldecode($val), "UTF-8", "Windows-1251"));
        }
      }
    }
    
    if(empty($post)){
      $data['error'] = $this->language->get('error_post');
      $this->response->setOutput($this->load->view('extension/payment/w1_error', $data));
      return true;
    }
    
    $this->load->model('checkout/order');
    if($order_info = $this->model_checkout_order->getOrder(str_replace('_'.$this->request->server['HTTP_HOST'], '', $post['WMI_PAYMENT_NO']))){
      if($post['WMI_PAYMENT_AMOUNT'] != number_format($order_info['total'], 2, '.', '')){
        $data['error'] = $this->language->get('error_summ');
        $this->response->setOutput($this->load->view('extension/payment/w1_error', $data));
        return true;
      }
      
      $text = '';
      $status = mb_strtolower($post['WMI_ORDER_STATE']);
      switch ($status) {
        case "accepted":
          $text = $this->language->get('text_payment_completed');
          $data['order_status'] = $this->model_extension_payment_w1->getStatus('completed');
          break;
        case "created":
          $text = $this->language->get('text_payment_pending');
          $data['order_status'] = $this->model_extension_payment_w1->getStatus('pending');
          $this->model_checkout_order->addOrderHistory($order_info['order_id'], $this->config->get('w1_order_status_pending_id'));
          break;
        default:
          $text = $this->language->get('text_payment_fail');
          $data['order_status'] = $this->model_extension_payment_w1->getStatus('fail');
          $this->model_checkout_order->addOrderHistory($order_info['order_id'], $this->config->get('w1_order_status_fail_id'));
          break;
      }
      
      if (isset($this->session->data['order_id'])) {
        $this->cart->clear();

        // Add to activity log
        $this->load->model('account/activity');

        if ($this->customer->isLogged()) {
          $activity_data = array(
            'customer_id' => $this->customer->getId(),
            'name' => $this->customer->getFirstName() . ' ' . $this->customer->getLastName(),
            'order_id' => $this->session->data['order_id']
          );

          $this->model_account_activity->addActivity('order_account', $activity_data);
        }
        else {
          $activity_data = array(
            'name' => $this->session->data['guest']['firstname'] . ' ' . $this->session->data['guest']['lastname'],
            'order_id' => $this->session->data['order_id']
          );

          $this->model_account_activity->addActivity('order_guest', $activity_data);
        }

        unset($this->session->data['shipping_method']);
        unset($this->session->data['shipping_methods']);
        unset($this->session->data['payment_method']);
        unset($this->session->data['payment_methods']);
        unset($this->session->data['guest']);
        unset($this->session->data['comment']);
        unset($this->session->data['order_id']);
        unset($this->session->data['coupon']);
        unset($this->session->data['reward']);
        unset($this->session->data['voucher']);
        unset($this->session->data['vouchers']);
        unset($this->session->data['totals']);
      }

      $this->document->setTitle(sprintf($this->language->get('heading_title_1'), ''));

      $data['breadcrumbs'] = array();

      $data['breadcrumbs'][] = array(
        'text' => $this->language->get('text_home'),
        'href' => $this->url->link('common/home')
      );

      $data['breadcrumbs'][] = array(
        'text' => $this->language->get('text_basket'),
        'href' => $this->url->link('checkout/cart')
      );

      $data['breadcrumbs'][] = array(
        'text' => $this->language->get('text_checkout'),
        'href' => $this->url->link('checkout/checkout', '', true)
      );

      $data['breadcrumbs'][] = array(
        'text' => $this->language->get('text_success'),
        'href' => $this->url->link('checkout/success')
      );

      $data['heading_title'] = sprintf($this->language->get('heading_title_1'), $text);

      if ($this->customer->isLogged()) {
        $data['text_message'] = sprintf($this->language->get('text_customer'), $text, $this->url->link('account/account', '', true), $this->url->link('account/order', '', true), $this->url->link('account/download', '', true), $this->url->link('information/contact'));
      }
      else {
        $data['text_message'] = sprintf($this->language->get('text_guest'), $text, $this->url->link('information/contact'));
      }

      $data['button_continue'] = $this->language->get('button_continue');

      $data['continue'] = $this->url->link('common/home');

      $data['column_left'] = $this->load->controller('common/column_left');
      $data['column_right'] = $this->load->controller('common/column_right');
      $data['content_top'] = $this->load->controller('common/content_top');
      $data['content_bottom'] = $this->load->controller('common/content_bottom');
      $data['footer'] = $this->load->controller('common/footer');
      $data['header'] = $this->load->controller('common/header');

      $this->response->setOutput($this->load->view('extension/payment/w1_result', $data));
      return true;
    }
    else{
      $data['error'] = $this->language->get('error_order');
      $this->response->setOutput($this->load->view('extension/payment/w1_error', $data));
      return true;
    }
  }
  
  /**
   * Checked signature.
   * 
   * @param type $post
   * @param type $sign
   * @return boolean
   */
  private function checkSig($post, $sign) {
    foreach ($post as $key => $value) {
      if($key == 'route') {
        continue;
      }
      if ($key !== "WMI_SIGNATURE") {
        $params[$key] = urldecode($value);
      }
    }
    uksort($params, "strcasecmp");

    $values = "";
    foreach ($params as $name => $value) {
      $values .= $value;
    }
    $signature = base64_encode(pack("H*", md5($values . $sign)));
    if ($signature == urldecode($post['WMI_SIGNATURE'])) {
      return true;
    }
    return false;
  }
  
  /**
   * Page response for the payment system.
   * 
   * @return boolean
   */
  public function result() {
    $this->load->language('extension/payment/w1');
    $this->load->model('extension/payment/w1');
    if(!$this->checkSig($this->request->post, $this->config->get('w1_signature'))){
      ob_start();
      die('WMI_RESULT=RETRY&WMI_DESCRIPTION='.$this->language->get('error_signatue'));
    }
    
    $post = array();
    foreach ($this->request->request as $key => $val) {
      if($key == 'route') {
        continue;
      }
      if ($key != 'WMI_FAIL_URL' && $key != 'WMI_SUCCESS_URL' && $key != 'WMI_SIGNATURE'
          && $key != 'WMI_DESCRIPTION' && preg_match('/^[a-z0-9\._\-\: ]+$/ui', urldecode($val))) {
        $post[$key] = htmlentities(urldecode($val));
        if (strlen($post[$key]) > 250) {
          $post[$key] = substr($post[$key], 0, 250);
        }
      }
      elseif (($key == 'WMI_FAIL_URL' || $key == 'WMI_SUCCESS_URL') && preg_match('/^[a-z0-9\._\-\:\/\&\?=]+$/ui', urldecode($val))) {
        $post[$key] = htmlentities(urldecode($val));
        if (strlen($post[$key]) > 500) {
          $post[$key] = substr($post[$key], 0, 500);
        }
      }
      elseif ($key == 'WMI_SIGNATURE' && preg_match('/^[a-z0-9\._\-\+\=\/\\\]+$/ui', urldecode($val))) {
        $post[$key] = htmlentities(urldecode($val));
        if (strlen($post[$key]) > 500) {
          $post[$key] = substr($post[$key], 0, 500);
        }
      }
      elseif ($key == 'WMI_DESCRIPTION') {
        if(!empty($this->request->server['HTTP_USER_AGENT']) && preg_match('/^[a-zа-яё0-9№\. ]+$/ui', mb_convert_encoding(urldecode($val), "UTF-8", "Windows-1251"))) {
          $post[$key] = htmlentities(mb_convert_encoding(urldecode($val), "UTF-8", "Windows-1251"));
        }
        elseif(preg_match('/^[a-zа-яё0-9№\. ]+$/ui', mb_convert_encoding(urldecode($val), "UTF-8", "Windows-1251"))) {
          $post[$key] = htmlentities(mb_convert_encoding(urldecode($val), "UTF-8", "Windows-1251"));
        }
      }
    }
    
    if(empty($post)){
      ob_start();
      die('WMI_RESULT=RETRY&WMI_DESCRIPTION='.$this->language->get('error_post'));
    }
    
    $this->load->model('checkout/order');
    if($order_info = $this->model_checkout_order->getOrder(str_replace('_'.$this->request->server['HTTP_HOST'], '', $post['WMI_PAYMENT_NO']))){
      if($post['WMI_PAYMENT_AMOUNT'] != number_format($order_info['total'], 2, '.', '')){
        ob_start();
        die('WMI_RESULT=RETRY&WMI_DESCRIPTION='.$this->language->get('error_summ'));
      }
      $status = mb_strtolower($post['WMI_ORDER_STATE']);
      switch ($status) {
        case "accepted":
          $this->model_checkout_order->addOrderHistory($order_info['order_id'], $this->config->get('w1_order_status_completed_id'));
          break;
        case "created":
          $this->model_checkout_order->addOrderHistory($order_info['order_id'], $this->config->get('w1_order_status_pending_id'));
          break;
        default:
          $this->model_checkout_order->addOrderHistory($order_info['order_id'], $this->config->get('w1_order_status_fail_id'));
          break;
      }
      
      if (isset($this->session->data['order_id'])) {
        $this->cart->clear();

        // Add to activity log
        $this->load->model('account/activity');

        if ($this->customer->isLogged()) {
          $activity_data = array(
            'customer_id' => $this->customer->getId(),
            'name' => $this->customer->getFirstName() . ' ' . $this->customer->getLastName(),
            'order_id' => $this->session->data['order_id']
          );

          $this->model_account_activity->addActivity('order_account', $activity_data);
        }
        else {
          $activity_data = array(
            'name' => $this->session->data['guest']['firstname'] . ' ' . $this->session->data['guest']['lastname'],
            'order_id' => $this->session->data['order_id']
          );

          $this->model_account_activity->addActivity('order_guest', $activity_data);
        }

        unset($this->session->data['shipping_method']);
        unset($this->session->data['shipping_methods']);
        unset($this->session->data['payment_method']);
        unset($this->session->data['payment_methods']);
        unset($this->session->data['guest']);
        unset($this->session->data['comment']);
        unset($this->session->data['order_id']);
        unset($this->session->data['coupon']);
        unset($this->session->data['reward']);
        unset($this->session->data['voucher']);
        unset($this->session->data['vouchers']);
        unset($this->session->data['totals']);
      }
      if($status == 'ACCEPTED'){
        ob_start();
        echo 'WMI_RESULT=OK';
        ob_end_flush();
        die();
      }
      return true;
    }
    else{
      return false;
    }
    
    return true;
  }
}

?>