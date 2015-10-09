<?php
class ModelPaymentSagepayDirect extends Model {
	public function install() {
		$this->db->query("
			CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . "sagepay_direct_order` (
			  `sagepay_direct_order_id` INT(11) NOT NULL AUTO_INCREMENT,
			  `order_id` INT(11) NOT NULL,
			  `VPSTxId` VARCHAR(50),
			  `VendorTxCode` VARCHAR(50) NOT NULL,
			  `SecurityKey` CHAR(50) NOT NULL,
			  `TxAuthNo` INT(50),
			  `date_added` DATETIME NOT NULL,
			  `date_modified` DATETIME NOT NULL,
			  `release_status` INT(1) DEFAULT NULL,
			  `void_status` INT(1) DEFAULT NULL,
			  `settle_type` INT(1) DEFAULT NULL,
			  `rebate_status` INT(1) DEFAULT NULL,
			  `currency_code` CHAR(3) NOT NULL,
			  `total` DECIMAL( 10, 2 ) NOT NULL,
			  PRIMARY KEY (`sagepay_direct_order_id`)
			) ENGINE=MyISAM DEFAULT COLLATE=utf8_general_ci;");

		$this->db->query("
			CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . "sagepay_direct_order_transaction` (
			  `sagepay_direct_order_transaction_id` INT(11) NOT NULL AUTO_INCREMENT,
			  `sagepay_direct_order_id` INT(11) NOT NULL,
			  `date_added` DATETIME NOT NULL,
			  `type` ENUM('auth', 'payment', 'rebate', 'void') DEFAULT NULL,
			  `amount` DECIMAL( 10, 2 ) NOT NULL,
			  PRIMARY KEY (`sagepay_direct_order_transaction_id`)
			) ENGINE=MyISAM DEFAULT COLLATE=utf8_general_ci;");

		$this->db->query("
			CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . "sagepay_direct_order_recurring` (
			  `sagepay_direct_order_recurring_id` INT(11) NOT NULL AUTO_INCREMENT,
			  `order_id` INT(11) NOT NULL,
			  `order_recurring_id` INT(11) NOT NULL,
			  `VPSTxId` VARCHAR(50),
			  `VendorTxCode` VARCHAR(50) NOT NULL,
			  `SecurityKey` CHAR(50) NOT NULL,
			  `TxAuthNo` INT(50),
			  `date_added` DATETIME NOT NULL,
			  `date_modified` DATETIME NOT NULL,
			  `next_payment` DATETIME NOT NULL,
			  `trial_end` datetime DEFAULT NULL,
			  `subscription_end` datetime DEFAULT NULL,
			  `currency_code` CHAR(3) NOT NULL,
			  `total` DECIMAL( 10, 2 ) NOT NULL,
			  PRIMARY KEY (`sagepay_direct_order_recurring_id`)
			) ENGINE=MyISAM DEFAULT COLLATE=utf8_general_ci;");

		$this->db->query("
			CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . "sagepay_direct_card` (
			  `card_id` INT(11) NOT NULL AUTO_INCREMENT,
			  `customer_id` INT(11) NOT NULL,
			  `order_id` INT(11) NOT NULL,
			  `token` VARCHAR(50) NOT NULL,
			  `digits` VARCHAR(4) NOT NULL,
			  `expiry` VARCHAR(5) NOT NULL,
			  `type` VARCHAR(50) NOT NULL,
			  PRIMARY KEY (`card_id`)
			) ENGINE=MyISAM DEFAULT COLLATE=utf8_general_ci;");
	}

	public function uninstall() {
		$this->db->query("DROP TABLE IF EXISTS `" . DB_PREFIX . "sagepay_direct_order`;");
		$this->db->query("DROP TABLE IF EXISTS `" . DB_PREFIX . "sagepay_direct_order_transaction`;");
		$this->db->query("DROP TABLE IF EXISTS `" . DB_PREFIX . "sagepay_direct_order_recurring`;");
		$this->db->query("DROP TABLE IF EXISTS `" . DB_PREFIX . "sagepay_direct_card`;");
	}

	public function void($order_id) {
		$sagepay_direct_order = $this->getOrder($order_id);

		if (!empty($sagepay_direct_order) && $sagepay_direct_order['release_status'] == 0) {

			$void_data = array();

			if ($this->config->get('sagepay_direct_test') == 'live') {
				$url = 'https://live.sagepay.com/gateway/service/void.vsp';
				$void_data['VPSProtocol'] = '3.00';
			} elseif ($this->config->get('sagepay_direct_test') == 'test') {
				$url = 'https://test.sagepay.com/gateway/service/void.vsp';
				$void_data['VPSProtocol'] = '3.00';
			} elseif ($this->config->get('sagepay_direct_test') == 'sim') {
				$url = 'https://test.sagepay.com/Simulator/VSPServerGateway.asp?Service=VendorVoidTx';
				$void_data['VPSProtocol'] = '2.23';
			}

			$void_data['TxType'] = 'VOID';
			$void_data['Vendor'] = $this->config->get('sagepay_direct_vendor');
			$void_data['VendorTxCode'] = $sagepay_direct_order['VendorTxCode'];
			$void_data['VPSTxId'] = $sagepay_direct_order['VPSTxId'];
			$void_data['SecurityKey'] = $sagepay_direct_order['SecurityKey'];
			$void_data['TxAuthNo'] = $sagepay_direct_order['TxAuthNo'];

			$response_data = $this->sendCurl($url, $void_data);

			return $response_data;
		} else {
			return false;
		}
	}

	public function updateVoidStatus($sagepay_direct_order_id, $status) {
		$this->db->query("UPDATE `" . DB_PREFIX . "sagepay_direct_order` SET `void_status` = '" . (int)$status . "' WHERE `sagepay_direct_order_id` = '" . (int)$sagepay_direct_order_id . "'");
	}

	public function release($order_id, $amount) {
		$sagepay_direct_order = $this->getOrder($order_id);
		$total_released = $this->getTotalReleased($sagepay_direct_order['sagepay_direct_order_id']);

		if (!empty($sagepay_direct_order) && $sagepay_direct_order['release_status'] == 0 && ($total_released + $amount <= $sagepay_direct_order['total'])) {
			$release_data = array();

			if ($this->config->get('sagepay_direct_test') == 'live') {
				$url = 'https://live.sagepay.com/gateway/service/release.vsp';
				$release_data['VPSProtocol'] = '3.00';
			} elseif ($this->config->get('sagepay_direct_test') == 'test') {
				$url = 'https://test.sagepay.com/gateway/service/release.vsp';
				$release_data['VPSProtocol'] = '3.00';
			} elseif ($this->config->get('sagepay_direct_test') == 'sim') {
				$url = 'https://test.sagepay.com/Simulator/VSPServerGateway.asp?Service=VendorReleaseTx';
				$release_data['VPSProtocol'] = '2.23';
			}

			$release_data['TxType'] = 'RELEASE';
			$release_data['Vendor'] = $this->config->get('sagepay_direct_vendor');
			$release_data['VendorTxCode'] = $sagepay_direct_order['VendorTxCode'];
			$release_data['VPSTxId'] = $sagepay_direct_order['VPSTxId'];
			$release_data['SecurityKey'] = $sagepay_direct_order['SecurityKey'];
			$release_data['TxAuthNo'] = $sagepay_direct_order['TxAuthNo'];
			$release_data['Amount'] = $amount;

			$response_data = $this->sendCurl($url, $release_data);

			return $response_data;
		} else {
			return false;
		}
	}

	public function updateReleaseStatus($sagepay_direct_order_id, $status) {
		$this->db->query("UPDATE `" . DB_PREFIX . "sagepay_direct_order` SET `release_status` = '" . (int)$status . "' WHERE `sagepay_direct_order_id` = '" . (int)$sagepay_direct_order_id . "'");
	}

	public function rebate($order_id, $amount) {
		$sagepay_direct_order = $this->getOrder($order_id);

		if (!empty($sagepay_direct_order) && $sagepay_direct_order['rebate_status'] != 1) {

			$refund_data = array();

			if ($this->config->get('sagepay_direct_test') == 'live') {
				$url = 'https://live.sagepay.com/gateway/service/refund.vsp';
				$refund_data['VPSProtocol'] = '3.00';
			} elseif ($this->config->get('sagepay_direct_test') == 'test') {
				$url = 'https://test.sagepay.com/gateway/service/refund.vsp';
				$refund_data['VPSProtocol'] = '3.00';
			} elseif ($this->config->get('sagepay_direct_test') == 'sim') {
				$url = 'https://test.sagepay.com/Simulator/VSPServerGateway.asp?Service=VendorRefundTx';
				$refund_data['VPSProtocol'] = '2.23';
			}

			$refund_data['TxType'] = 'REFUND';
			$refund_data['Vendor'] = $this->config->get('sagepay_direct_vendor');
			$refund_data['VendorTxCode'] = $sagepay_direct_order['sagepay_direct_order_id'] . rand();
			$refund_data['Amount'] = $amount;
			$refund_data['Currency'] = $sagepay_direct_order['currency_code'];
			$refund_data['Description'] = substr($this->config->get('config_name'), 0, 100);
			$refund_data['RelatedVPSTxId'] = $sagepay_direct_order['VPSTxId'];
			$refund_data['RelatedVendorTxCode'] = $sagepay_direct_order['VendorTxCode'];
			$refund_data['RelatedSecurityKey'] = $sagepay_direct_order['SecurityKey'];
			$refund_data['RelatedTxAuthNo'] = $sagepay_direct_order['TxAuthNo'];

			$response_data = $this->sendCurl($url, $refund_data);

			return $response_data;
		} else {
			return false;
		}
	}

	public function updateRebateStatus($sagepay_direct_order_id, $status) {
		$this->db->query("UPDATE `" . DB_PREFIX . "sagepay_direct_order` SET `rebate_status` = '" . (int)$status . "' WHERE `sagepay_direct_order_id` = '" . (int)$sagepay_direct_order_id . "'");
	}

	public function getOrder($order_id) {

		$qry = $this->db->query("SELECT * FROM `" . DB_PREFIX . "sagepay_direct_order` WHERE `order_id` = '" . (int)$order_id . "' LIMIT 1");

		if ($qry->num_rows) {
			$order = $qry->row;
			$order['transactions'] = $this->getTransactions($order['sagepay_direct_order_id']);

			return $order;
		} else {
			return false;
		}
	}

	private function getTransactions($sagepay_direct_order_id) {
		$qry = $this->db->query("SELECT * FROM `" . DB_PREFIX . "sagepay_direct_order_transaction` WHERE `sagepay_direct_order_id` = '" . (int)$sagepay_direct_order_id . "'");

		if ($qry->num_rows) {
			return $qry->rows;
		} else {
			return false;
		}
	}

	public function addTransaction($sagepay_direct_order_id, $type, $total) {
		$this->db->query("INSERT INTO `" . DB_PREFIX . "sagepay_direct_order_transaction` SET `sagepay_direct_order_id` = '" . (int)$sagepay_direct_order_id . "', `date_added` = now(), `type` = '" . $this->db->escape($type) . "', `amount` = '" . (float)$total . "'");
	}

	public function getTotalReleased($sagepay_direct_order_id) {
		$query = $this->db->query("SELECT SUM(`amount`) AS `total` FROM `" . DB_PREFIX . "sagepay_direct_order_transaction` WHERE `sagepay_direct_order_id` = '" . (int)$sagepay_direct_order_id . "' AND (`type` = 'payment' OR `type` = 'rebate')");

		return (float)$query->row['total'];
	}

	public function getTotalRebated($sagepay_direct_order_id) {
		$query = $this->db->query("SELECT SUM(`amount`) AS `total` FROM `" . DB_PREFIX . "sagepay_direct_order_transaction` WHERE `sagepay_direct_order_id` = '" . (int)$sagepay_direct_order_id . "' AND 'rebate'");

		return (float)$query->row['total'];
	}

	public function sendCurl($url, $payment_data) {
		$curl = curl_init($url);

		curl_setopt($curl, CURLOPT_PORT, 443);
		curl_setopt($curl, CURLOPT_HEADER, 0);
		curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($curl, CURLOPT_FOLLOWLOCATION, false);
		curl_setopt($curl, CURLOPT_FORBID_REUSE, 1);
		curl_setopt($curl, CURLOPT_FRESH_CONNECT, 1);
		curl_setopt($curl, CURLOPT_POST, 1);
		curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($payment_data));

		$response = curl_exec($curl);

		curl_close($curl);

		$response_info = explode(chr(10), $response);

		foreach ($response_info as $string) {
			if (strpos($string, '=') && isset($i)) {
				$parts = explode('=', $string, 2);
				$data['RepeatResponseData_' . $i][trim($parts[0])] = trim($parts[1]);
			} elseif (strpos($string, '=')) {
				$parts = explode('=', $string, 2);
				$data[trim($parts[0])] = trim($parts[1]);
			}
		}
		return $data;
	}

	public function logger($message) {
		if ($this->config->get('sagepay_direct_debug') == 1) {
			$log = new Log('sagepay_direct.log');
			$log->write($message);
		}
	}
}