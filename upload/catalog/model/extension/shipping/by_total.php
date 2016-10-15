<?php 
class ModelExtensionShippingByTotal extends Model {
	public function getQuote($address) {
		$this->load->language('extension/shipping/by_total');

		$quote_data = array();

		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "geo_zone ORDER BY name");

		foreach ($query->rows as $result) {
			if ($this->config->get('by_total_' . $result['geo_zone_id'] . '_status')) {
				$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "zone_to_geo_zone WHERE geo_zone_id = '" . (int)$result['geo_zone_id'] . "' AND country_id = '" . (int)$address['country_id'] . "' AND (zone_id = '" . (int)$address['zone_id'] . "' OR zone_id = '0')");

				if ($query->num_rows) {
					$status = true;
				} else {
					$status = false;
				}
			} else {
				$status = false;
			}

			if ($status) {
				$cost = '';
				$cart_total = $this->cart->getTotal();

				$rates = explode(',', $this->config->get('by_total_' . $result['geo_zone_id'] . '_rate'));
				foreach ($rates as $rate) {
					$data = explode(':', $rate);

					if ($data[0] >= $cart_total) {
						if (isset($data[1])) {
							$cost = $data[1];
						}

						break;
					}
				}

				if ((string)$cost != '') {
					$quote_data['by_total_' . $result['geo_zone_id']] = array(
						'code'         => 'by_total.by_total_' . $result['geo_zone_id'],
						'title'        => $result['name'] . '  (' . $this->language->get('text_total') . ' ' . $this->currency->format($this->tax->calculate($cart_total, $this->config->get('by_total_tax_class_id'), $this->config->get('config_tax'))) . ')',
						'cost'         => $cost,
						'tax_class_id' => $this->config->get('by_total_tax_class_id'),
						'text'         => $this->currency->format($this->tax->calculate($cost, $this->config->get('by_total_tax_class_id'), $this->config->get('config_tax')))
					);
				}
			}
		}

		$method_data = array();

		if ($quote_data) {
			$method_data = array(
				'code'       => 'by_total',
				'title'      => $this->language->get('text_title'),
				'quote'      => $quote_data,
				'sort_order' => $this->config->get('by_total_sort_order'),
				'error'      => false
			);
		}

		return $method_data;
	}
}

