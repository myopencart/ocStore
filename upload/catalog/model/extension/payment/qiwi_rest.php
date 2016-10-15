<?php 
class ModelExtensionPaymentQiwiRest extends Model {
  	public function getMethod($address, $total) {
		$this->load->language('extension/payment/qiwi_rest');

      	$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "zone_to_geo_zone WHERE geo_zone_id = '" . (int)$this->config->get('qiwi_rest_geo_zone_id') . "' AND country_id = '" . (int)$address['country_id'] . "' AND (zone_id = '" . (int)$address['zone_id'] . "' OR zone_id = '0')");

		if ($this->config->get('qiwi_rest_total') > $total) {
			$status = false;
		} elseif (!$this->config->get('qiwi_rest_geo_zone_id')) {
			$status = true;
		} elseif ($query->num_rows) {
			$status = true;
		} else {
			$status = false;
		}
		

		$method_data = array();
	
		if (isset($this->request->server['HTTPS']) && (($this->request->server['HTTPS'] == 'on') || ($this->request->server['HTTPS'] == '1'))) {
			$server = $this->config->get('config_ssl') . 'image/';
		} else {
			$server = $this->config->get('config_url') . 'image/';
		}	

		$this->load->model('account/customer');

		$text_title = $this->config->get('qiwi_rest_name');
		if ($this->customer->isLogged())
		{

			$customer_info = $this->model_account_customer->getCustomer($this->customer->getId());
			$customer_group_id = $customer_info['customer_group_id']; 

			foreach($this->config->get('qiwi_rest_group_desc') as $group_id => $group_desc)
			{
				if ($group_id == $customer_group_id) 
					if ($group_desc!="") $text_title = $group_desc;
			}
		}

		if ($text_title=="") $text_title = $this->language->get('text_title');

		if ($status) {  
      		$method_data = array( 
        		'code'       => 'qiwi_rest',
        		'title'      => $this->config->get('qiwi_rest_mode_show_picture')=='qiwi_rest_show_picture_on'?'<img src="'.$server.'payment/QIWI.png" style="vertical-align:middle;"> '.$text_title : $text_title,
			'terms'      => '',
			'sort_order' => $this->config->get('qiwi_rest_sort_order')
      		);
    	}
   
    	return $method_data;
  	}
}
