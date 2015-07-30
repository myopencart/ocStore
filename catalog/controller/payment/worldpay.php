<?php

class ControllerPaymentWorldpay extends Controller {

	public function index() {
		$this->load->language('payment/worldpay');

		$data['text_credit_card'] = $this->language->get('text_credit_card');
		$data['text_loading'] = $this->language->get('text_loading');
		$data['text_card_type'] = $this->language->get('text_card_type');
		$data['text_card_name'] = $this->language->get('text_card_name');
		$data['text_card_digits'] = $this->language->get('text_card_digits');
		$data['text_card_expiry'] = $this->language->get('text_card_expiry');
		$data['text_confirm_delete'] = $this->language->get('text_confirm_delete');

		$data['entry_card'] = $this->language->get('entry_card');
		$data['entry_card_existing'] = $this->language->get('entry_card_existing');
		$data['entry_card_new'] = $this->language->get('entry_card_new');
		$data['entry_card_save'] = $this->language->get('entry_card_save');
		$data['entry_cc_cvc'] = $this->language->get('entry_cc_cvc');
		$data['entry_cc_choice'] = $this->language->get('entry_cc_choice');

		$data['button_delete_card'] = $this->language->get('button_delete_card');
		$data['button_confirm'] = $this->language->get('button_confirm');

		$data['worldpay_script'] = 'https://cdn.worldpay.com/v1/worldpay.js';

		$data['worldpay_client_key'] = $this->config->get('worldpay_client_key');

		$data['form_submit'] = $this->url->link('payment/worldpay/send', '', 'SSL');

		if ($this->config->get('worldpay_card') == '1' && $this->customer->isLogged()) {
			$data['worldpay_card'] = true;
		} else {
			$data['worldpay_card'] = false;
		}

		$data['existing_cards'] = array();
		if ($this->customer->isLogged() && $data['worldpay_card']) {
			$this->load->model('payment/worldpay');
			$data['existing_cards'] = $this->model_payment_worldpay->getCards($this->customer->getId());
		}

		$recurring_products = $this->cart->getRecurringProducts();

		if (!empty($recurring_products)) {
			$data['recurring_products'] = true;
		}

		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/payment/worldpay.tpl')) {
			return $this->load->view($this->config->get('config_template') . '/template/payment/worldpay.tpl', $data);
		} else {
			return $this->load->view('default/template/payment/worldpay.tpl', $data);
		}
	}

	public function send() {
		$this->load->language('payment/worldpay');
		$this->load->model('checkout/order');
		$this->load->model('localisation/country');
		$this->load->model('payment/worldpay');

		$order_info = $this->model_checkout_order->getOrder($this->session->data['order_id']);

		$recurring_products = $this->cart->getRecurringProducts();

		if (empty($recurring_products)) {
			$order_type = 'ECOM';
		} else {
			$order_type = 'RECURRING';
		}

		$country_info = $this->model_localisation_country->getCountry($order_info['payment_country_id']);

		$billing_address = array(
			"address1" => $order_info['payment_address_1'],
			"address2" => $order_info['payment_address_2'],
			"address3" => '',
			"postalCode" => $order_info['payment_postcode'],
			"city" => $order_info['payment_city'],
			"state" => $order_info['payment_zone'],
			"countryCode" => $country_info['iso_code_2'],
		);

		$order = array(
			"token" => $this->request->post['token'],
			"orderType" => $order_type,
			"amount" => (int)($order_info['total'] * 100),
			"currencyCode" => $order_info['currency_code'],
			"name" => $order_info['firstname'] . ' ' . $order_info['lastname'],
			"orderDescription" => $order_info['store_name'] . ' - ' . date('Y-m-d H:i:s'),
			"customerOrderCode" => $order_info['order_id'],
			"billingAddress" => $billing_address
		);

		$this->model_payment_worldpay->logger($order);

		$response_data = $this->model_payment_worldpay->sendCurl('orders', $order);

		$this->model_payment_worldpay->logger($response_data);

		if (isset($response_data->paymentStatus) && $response_data->paymentStatus == 'SUCCESS') {
			$this->model_checkout_order->addOrderHistory($order_info['order_id'], $this->config->get('config_order_status_id'));

			$worldpay_order_id = $this->model_payment_worldpay->addOrder($order_info, $response_data->orderCode);

			$this->model_payment_worldpay->addTransaction($worldpay_order_id, 'payment', $order_info);

			if (isset($this->request->post['save-card'])) {
				$response = $this->model_payment_worldpay->sendCurl('tokens/' . $this->request->post['token']);

				$this->model_payment_worldpay->logger($response);

				$expiry_date = mktime(0, 0, 0, 0, (string)$response->paymentMethod->expiryMonth, (string)$response->paymentMethod->expiryYear);

				if (isset($response->paymentMethod)) {
					$card_data = array();
					$card_data['customer_id'] = $this->customer->getId();
					$card_data['Token'] = $response->token;
					$card_data['Last4Digits'] = (string)$response->paymentMethod->maskedCardNumber;
					$card_data['ExpiryDate'] = date("m/y", $expiry_date);
					$card_data['CardType'] = (string)$response->paymentMethod->cardType;
					$this->model_payment_worldpay->addCard($this->session->data['order_id'], $card_data);
				}
			}

			//loop through any products that are recurring items
			foreach ($recurring_products as $item) {
				$this->model_payment_worldpay->recurringPayment($item, $this->session->data['order_id'] . rand(), $this->request->post['token']);
			}

			$this->response->redirect($this->url->link('checkout/success', '', 'SSL'));
		} else {

			$this->session->data['error'] = $this->language->get('error_process_order');
			$this->response->redirect($this->url->link('checkout/checkout', '', 'SSL'));
		}
	}

	public function deleteCard() {
		$this->load->language('payment/worldpay');
		$this->load->model('payment/worldpay');

		if (isset($this->request->post['token'])) {
			if ($this->model_payment_worldpay->deleteCard($this->request->post['token'])) {
				$json['success'] = $this->language->get('text_card_success');
			} else {
				$json['error'] = $this->language->get('text_card_error');
			}

			if (count($this->model_payment_worldpay->getCards($this->customer->getId()))) {
				$json['existing_cards'] = true;
			}
		} else {
			$json['error'] = $this->language->get('text_error');
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	public function webhook() {
		if (isset($this->request->get['token']) && $this->request->get['token'] == $this->config->get('worldpay_secret_token')) {
			$this->load->model('payment/worldpay');
			$message = json_decode(file_get_contents('php://input'), true);

			if (isset($message['orderCode'])) {
				$order = $this->model_payment_worldpay->getWorldpayOrder($message['orderCode']);
				$this->model_payment_worldpay->logger($order);
				switch ($message['paymentStatus']) {
					case 'SUCCESS':
						$order_status_id = $this->config->get('worldpay_entry_success_status_id');
						break;
					case 'FAILED':
						$order_status_id = $this->config->get('worldpay_entry_failed_status_id');
						break;
					case 'SETTLED':
						$order_status_id = $this->config->get('worldpay_entry_settled_status_id');
						break;
					case 'REFUNDED':
						$order_status_id = $this->config->get('worldpay_refunded_status_id');
						break;
					case 'PARTIALLY_REFUNDED':
						$order_status_id = $this->config->get('worldpay_entry_partially_refunded_status_id');
						break;
					case 'CHARGED_BACK':
						$order_status_id = $this->config->get('worldpay_entry_charged_back_status_id');
						break;
					case 'INFORMATION_REQUESTED':
						$order_status_id = $this->config->get('worldpay_entry_information_requested_status_id');
						break;
					case 'INFORMATION_SUPPLIED':
						$order_status_id = $this->config->get('worldpay_entry_information_supplied_status_id');
						break;
					case 'CHARGEBACK_REVERSED':
						$order_status_id = $this->config->get('worldpay_entry_chargeback_reversed_status_id');
						break;
				}

				$this->model_payment_worldpay->logger($order_status_id);
				if (isset($order['order_id'])) {
					$this->load->model('checkout/order');
					$this->model_checkout_order->addOrderHistory($order['order_id'], $order_status_id);
				}
			}
		}

		$this->response->addHeader('HTTP/1.1 200 OK');
		$this->response->addHeader('Content-Type: application/json');
	}

	public function cron() {
		if ($this->request->get['token'] == $this->config->get('worldpay_cron_job_token')) {
			$this->load->model('payment/worldpay');

			$orders = $this->model_payment_worldpay->cronPayment();

			$this->model_payment_worldpay->updateCronJobRunTime();

			$this->model_payment_worldpay->logger($orders);
		}
	}

}
