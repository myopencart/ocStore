<?php 
class ModelExtensionPaymentOplata extends Model {
	public function getMethod($address, $total) {
		$this->load->language('extension/payment/oplata');

		$method_data = array(
				'code'       => 'oplata',
				'terms'      => '',
				'title'      => $this->language->get('text_title'),
				'sort_order' => $this->config->get('oplata_sort_order')
			);
		return $method_data;
	}
}
?>