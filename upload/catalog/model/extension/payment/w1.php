<?php

class ModelExtensionPaymentW1 extends Model {
  /**
   * The output of the method of payment in the list when ordering.
   * 
   * @param type $address
   * @return type
   */
  public function getMethod($address, $total) {
    $this->load->language('extension/payment/w1');

    if ($this->config->get('w1_status')) {
      $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "zone_to_geo_zone WHERE geo_zone_id = '" . (int) $this->config->get('w1_geo_zone_id') . "' AND country_id = '" . (int) $address['country_id'] . "' AND (zone_id = '" . (int) $address['zone_id'] . "' OR zone_id = '0')");

      if (!$this->config->get('w1_geo_zone_id')) {
        $status = TRUE;
      }
      elseif ($query->num_rows) {
        $status = TRUE;
      }
      else {
        $status = FALSE;
      }
    }
    else {
      $status = FALSE;
    }

    $method_data = array();

    if ($status) {
      $method_data = array(
        'code' => 'w1',
        'title' => $this->language->get('text_title'),
        'terms' => '',
        'sort_order' => ''
      );
    }

    return $method_data;
  }
  
  /**
   * Get the name of the selected order status of settings.
   * @param type $name
   * @return type
   */
  public function getStatus($name) {
    $status = $this->db->query("SELECT `name` FROM " . DB_PREFIX . "order_status WHERE  `order_status_id` = " . (int) $this->config->get('w1_order_status_'.$name.'_id'));
    return mb_strtolower($status->row['name']);
  }

}
?>