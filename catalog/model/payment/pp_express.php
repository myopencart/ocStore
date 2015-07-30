<?php
class ModelPaymentPPExpress extends Model {
	public function cleanReturn($data) {
		$data = explode('&', $data);

		$arr = array();

		foreach ($data as $k=>$v) {
			$tmp = explode('=', $v);
			$arr[$tmp[0]] = urldecode($tmp[1]);
		}

		return $arr;
	}

	public function call($data) {

		if ($this->config->get('pp_express_test') == 1) {
			$api_endpoint = 'https://api-3t.sandbox.paypal.com/nvp';
			$user = $this->config->get('pp_express_sandbox_username');
			$password = $this->config->get('pp_express_sandbox_password');
			$signature = $this->config->get('pp_express_sandbox_signature');
		} else {
			$api_endpoint = 'https://api-3t.paypal.com/nvp';
			$user = $this->config->get('pp_express_username');
			$password = $this->config->get('pp_express_password');
			$signature = $this->config->get('pp_express_signature');
		}

		$settings = array(
			'USER' => $user,
			'PWD' => $password,
			'SIGNATURE' => $signature,
			'VERSION' => '109.0',
			'BUTTONSOURCE' => 'OpenCart_2.0_EC',
		);

		$this->log($data, 'Call data');

		$defaults = array(
			CURLOPT_POST => 1,
			CURLOPT_HEADER => 0,
			CURLOPT_URL => $api_endpoint,
			CURLOPT_USERAGENT => "Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.8.1.1) Gecko/20061204 Firefox/2.0.0.1",
			CURLOPT_FRESH_CONNECT => 1,
			CURLOPT_RETURNTRANSFER => 1,
			CURLOPT_FORBID_REUSE => 1,
			CURLOPT_TIMEOUT => 0,
			CURLOPT_SSL_VERIFYPEER => 0,
			CURLOPT_SSL_VERIFYHOST => 0,
			CURLOPT_POSTFIELDS => http_build_query(array_merge($data, $settings), '', "&")
		);

		$ch = curl_init();

		curl_setopt_array($ch, $defaults);

		if (!$result = curl_exec($ch)) {
			$this->log(array('error' => curl_error($ch), 'errno' => curl_errno($ch)), 'cURL failed');
		}

		$this->log($result, 'Result');

		curl_close($ch);

		return $this->cleanReturn($result);
	}

	public function createToken($len = 32) {
		$base = 'ABCDEFGHKLMNOPQRSTWXYZabcdefghjkmnpqrstwxyz123456789';
		$max = strlen($base)-1;
		$activate_code = '';
		mt_srand((float)microtime()*1000000);

		while (strlen($activate_code)<$len+1) {
			$activate_code .= $base{mt_rand(0, $max)};
		}

		return $activate_code;
	}

	public function log($data, $title = null) {
		if ($this->config->get('pp_express_debug')) {
			$this->log->write('PayPal Express debug (' . $title . '): ' . json_encode($data));
		}
	}

	public function getMethod($address, $total) {
		$this->load->language('payment/pp_express');

		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "zone_to_geo_zone` WHERE `geo_zone_id` = '" . (int)$this->config->get('pp_express_geo_zone_id') . "' AND `country_id` = '" . (int)$address['country_id'] . "' AND (`zone_id` = '" . (int)$address['zone_id'] . "' OR `zone_id` = '0')");

		if ($this->config->get('pp_express_total') > $total) {
			$status = false;
		} elseif (!$this->config->get('pp_express_geo_zone_id')) {
			$status = true;
		} elseif ($query->num_rows) {
			$status = true;
		} else {
			$status = false;
		}

		$method_data = array();

		if ($status) {
			$method_data = array(
				'code'       => 'pp_express',
				'title'      => $this->language->get('text_title'),
				'terms'      => '',
				'sort_order' => $this->config->get('pp_express_sort_order')
			);
		}

		return $method_data;
	}

	public function addOrder($order_data) {
		/**
		 * 1 to 1 relationship with order table (extends order info)
		 */

		$this->db->query("INSERT INTO `" . DB_PREFIX . "paypal_order` SET
			`order_id` = '" . (int)$order_data['order_id'] . "',
			`date_added` = NOW(),
			`date_modified` = NOW(),
			`capture_status` = '" . $this->db->escape($order_data['capture_status']) . "',
			`currency_code` = '" . $this->db->escape($order_data['currency_code']) . "',
			`total` = '" . (float)$order_data['total'] . "',
			`authorization_id` = '" . $this->db->escape($order_data['authorization_id']) . "'");

		return $this->db->getLastId();
	}

	public function addTransaction($transaction_data) {
		/**
		 * 1 to many relationship with paypal order table, many transactions per 1 order
		 */

		$this->db->query("INSERT INTO `" . DB_PREFIX . "paypal_order_transaction` SET
			`paypal_order_id` = '" . (int)$transaction_data['paypal_order_id'] . "',
			`transaction_id` = '" . $this->db->escape($transaction_data['transaction_id']) . "',
			`parent_transaction_id` = '" . $this->db->escape($transaction_data['parent_transaction_id']) . "',
			`date_added` = NOW(),
			`note` = '" . $this->db->escape($transaction_data['note']) . "',
			`msgsubid` = '" . $this->db->escape($transaction_data['msgsubid']) . "',
			`receipt_id` = '" . $this->db->escape($transaction_data['receipt_id']) . "',
			`payment_type` = '" . $this->db->escape($transaction_data['payment_type']) . "',
			`payment_status` = '" . $this->db->escape($transaction_data['payment_status']) . "',
			`pending_reason` = '" . $this->db->escape($transaction_data['pending_reason']) . "',
			`transaction_entity` = '" . $this->db->escape($transaction_data['transaction_entity']) . "',
			`amount` = '" . (float)$transaction_data['amount'] . "',
			`debug_data` = '" . $this->db->escape($transaction_data['debug_data']) . "'");
	}

	public function paymentRequestInfo() {

		$data['PAYMENTREQUEST_0_SHIPPINGAMT'] = '';
		$data['PAYMENTREQUEST_0_CURRENCYCODE'] = $this->currency->getCode();
		$data['PAYMENTREQUEST_0_PAYMENTACTION'] = $this->config->get('pp_express_method');

		$i = 0;
		$item_total = 0;

		foreach ($this->cart->getProducts() as $item) {
			$data['L_PAYMENTREQUEST_0_DESC' . $i] = '';

			$option_count = 0;
			foreach ($item['option'] as $option) {
				if ($option['type'] != 'file') {
					$value = $option['value'];
				} else {
					$filename = $this->encryption->decrypt($option['value']);
					$value = utf8_substr($filename, 0, utf8_strrpos($filename, '.'));
				}

				$data['L_PAYMENTREQUEST_0_DESC' . $i] .= ($option_count > 0 ? ', ' : '') . $option['name'] . ':' . (utf8_strlen($value) > 20 ? utf8_substr($value, 0, 20) . '..' : $value);

				$option_count++;
			}

			$data['L_PAYMENTREQUEST_0_DESC' . $i] = substr($data['L_PAYMENTREQUEST_0_DESC' . $i], 0, 126);

			$item_price = $this->currency->format($item['price'], false, false, false);

			$data['L_PAYMENTREQUEST_0_NAME' . $i] = $item['name'];
			$data['L_PAYMENTREQUEST_0_NUMBER' . $i] = $item['model'];
			$data['L_PAYMENTREQUEST_0_AMT' . $i] = $item_price;

			$item_total += number_format($item_price * $item['quantity'], 2, '.', '');

			$data['L_PAYMENTREQUEST_0_QTY' . $i] = $item['quantity'];

			$data['L_PAYMENTREQUEST_0_ITEMURL' . $i] = $this->url->link('product/product', 'product_id=' . $item['product_id']);

			if ($this->config->get('config_cart_weight')) {
				$weight = $this->weight->convert($item['weight'], $item['weight_class_id'], $this->config->get('config_weight_class_id'));
				$data['L_PAYMENTREQUEST_0_ITEMWEIGHTVALUE' . $i] = number_format($weight / $item['quantity'], 2, '.', '');
				$data['L_PAYMENTREQUEST_0_ITEMWEIGHTUNIT' . $i] = $this->weight->getUnit($this->config->get('config_weight_class_id'));
			}

			if ($item['length'] > 0 || $item['width'] > 0 || $item['height'] > 0) {
				$unit = $this->length->getUnit($item['length_class_id']);
				$data['L_PAYMENTREQUEST_0_ITEMLENGTHVALUE' . $i] = $item['length'];
				$data['L_PAYMENTREQUEST_0_ITEMLENGTHUNIT' . $i] = $unit;
				$data['L_PAYMENTREQUEST_0_ITEMWIDTHVALUE' . $i] = $item['width'];
				$data['L_PAYMENTREQUEST_0_ITEMWIDTHUNIT' . $i] = $unit;
				$data['L_PAYMENTREQUEST_0_ITEMHEIGHTVALUE' . $i] = $item['height'];
				$data['L_PAYMENTREQUEST_0_ITEMHEIGHTUNIT' . $i] = $unit;
			}

			$i++;
		}

		if (!empty($this->session->data['vouchers'])) {
			foreach ($this->session->data['vouchers'] as $voucher) {
				$item_total += $this->currency->format($voucher['amount'], false, false, false);

				$data['L_PAYMENTREQUEST_0_DESC' . $i] = '';
				$data['L_PAYMENTREQUEST_0_NAME' . $i] = $voucher['description'];
				$data['L_PAYMENTREQUEST_0_NUMBER' . $i] = 'VOUCHER';
				$data['L_PAYMENTREQUEST_0_QTY' . $i] = 1;
				$data['L_PAYMENTREQUEST_0_AMT' . $i] = $this->currency->format($voucher['amount'], false, false, false);
				$i++;
			}
		}

		// Totals
		$this->load->model('extension/extension');

		$total_data = array();
		$total = 0;
		$taxes = $this->cart->getTaxes();

		// Display prices
		if (($this->config->get('config_customer_price') && $this->customer->isLogged()) || !$this->config->get('config_customer_price')) {
			$sort_order = array();

			$results = $this->model_extension_extension->getExtensions('total');

			foreach ($results as $key => $value) {
				$sort_order[$key] = $this->config->get($value['code'] . '_sort_order');
			}

			array_multisort($sort_order, SORT_ASC, $results);

			foreach ($results as $result) {
				if ($this->config->get($result['code'] . '_status')) {
					$this->load->model('total/' . $result['code']);

					$this->{'model_total_' . $result['code']}->getTotal($total_data, $total, $taxes);
				}

				$sort_order = array();

				foreach ($total_data as $key => $value) {
					$sort_order[$key] = $value['sort_order'];
				}

				array_multisort($sort_order, SORT_ASC, $total_data);
			}
		}

		foreach ($total_data as $total_row) {
			if (!in_array($total_row['code'], array('total', 'sub_total'))) {
				if ($total_row['value'] != 0) {
					$item_price = $this->currency->format($total_row['value'], false, false, false);

					$data['L_PAYMENTREQUEST_0_NUMBER' . $i] = $total_row['code'];
					$data['L_PAYMENTREQUEST_0_NAME' . $i] = $total_row['title'];
					$data['L_PAYMENTREQUEST_0_AMT' . $i] = $this->currency->format($total_row['value'], false, false, false);
					$data['L_PAYMENTREQUEST_0_QTY' . $i] = 1;

					$item_total = $item_total + $item_price;
					$i++;
				}
			}
		}

		$data['PAYMENTREQUEST_0_ITEMAMT'] = number_format($item_total, 2, '.', '');
		$data['PAYMENTREQUEST_0_AMT'] = number_format($item_total, 2, '.', '');

		$z = 0;

		$recurring_products = $this->cart->getRecurringProducts();

		if ($recurring_products) {
			$this->language->load('payment/pp_express');

			foreach ($recurring_products as $item) {
				$data['L_BILLINGTYPE' . $z] = 'RecurringPayments';

				if ($item['recurring']['trial']) {
					$trial_amt = $this->currency->format($this->tax->calculate($item['recurring']['trial_price'], $item['tax_class_id'], $this->config->get('config_tax')), false, false, false) * $item['quantity'] . ' ' . $this->currency->getCode();
					$trial_text =  sprintf($this->language->get('text_trial'), $trial_amt, $item['recurring']['trial_cycle'], $item['recurring']['trial_frequency'], $item['recurring']['trial_duration']);
				} else {
					$trial_text = '';
				}

				$recurring_amt = $this->currency->format($this->tax->calculate($item['recurring']['price'], $item['tax_class_id'], $this->config->get('config_tax')), false, false, false)  * $item['quantity'] . ' ' . $this->currency->getCode();
				$recurring_description = $trial_text . sprintf($this->language->get('text_recurring'), $recurring_amt, $item['recurring']['cycle'], $item['recurring']['frequency']);

				if ($item['recurring']['duration'] > 0) {
					$recurring_description .= sprintf($this->language->get('text_length'), $item['recurring']['duration']);
				}

				$data['L_BILLINGAGREEMENTDESCRIPTION' . $z] = $recurring_description;
				$z++;
			}
		}

		return $data;
	}

	public function getTransactionRow($transaction_id) {
		$qry = $this->db->query("SELECT * FROM `" . DB_PREFIX . "paypal_order_transaction` `pt` LEFT JOIN `" . DB_PREFIX . "paypal_order` `po` ON `pt`.`paypal_order_id` = `po`.`paypal_order_id`  WHERE `pt`.`transaction_id` = '" . $this->db->escape($transaction_id) . "' LIMIT 1");

		if ($qry->num_rows > 0) {
			return $qry->row;
		} else {
			return false;
		}
	}

	public function totalCaptured($paypal_order_id) {
		$qry = $this->db->query("SELECT SUM(`amount`) AS `amount` FROM `" . DB_PREFIX . "paypal_order_transaction` WHERE `paypal_order_id` = '" . (int)$paypal_order_id . "' AND `pending_reason` != 'authorization' AND `pending_reason` != 'paymentreview' AND (`payment_status` = 'Partially-Refunded' OR `payment_status` = 'Completed' OR `payment_status` = 'Pending') AND `transaction_entity` = 'payment'");

		return $qry->row['amount'];
	}

	public function totalRefundedOrder($paypal_order_id) {
		$qry = $this->db->query("SELECT SUM(`amount`) AS `amount` FROM `" . DB_PREFIX . "paypal_order_transaction` WHERE `paypal_order_id` = '" . (int)$paypal_order_id . "' AND `payment_status` = 'Refunded'");

		return $qry->row['amount'];
	}

	public function updateOrder($capture_status, $order_id) {
		$this->db->query("UPDATE `" . DB_PREFIX . "paypal_order` SET `date_modified` = now(), `capture_status` = '" . $this->db->escape($capture_status) . "' WHERE `order_id` = '" . (int)$order_id . "'");
	}

	public function recurringCancel($ref) {

		$data = array(
			'METHOD' => 'ManageRecurringPaymentsProfileStatus',
			'PROFILEID' => $ref,
			'ACTION' => 'Cancel'
		);

		return $this->call($data);
	}

	public function recurringPayments() {
		/*
		 * Used by the checkout to state the module
		 * supports recurring recurrings.
		 */
		return true;
	}
}
