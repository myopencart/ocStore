<?php
/**
 * Support:
 * https://opencartforum.com/user/3463-shoputils/
 * http://opencart.shoputils.ru/?route=information/contact
 *
*/
class ModelExtensionPaymentSberBankTransfer extends Model {
    public function getMethod($address, $total) {
        $this->load->language('extension/payment/sberbank_transfer');

        if (($this->config->get('sberbank_transfer_status')) && ($total) &&
            (!$this->config->get('sberbank_transfer_minimal_order') || ($total >= (float)$this->config->get('sberbank_transfer_minimal_order'))) &&
            (!$this->config->get('sberbank_transfer_maximal_order') || ($total <= (float)$this->config->get('sberbank_transfer_maximal_order')))) {

            $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "zone_to_geo_zone WHERE geo_zone_id = '" . (int)$this->config->get('sberbank_transfer_geo_zone_id') . "' AND country_id = '" . (int)$address['country_id'] . "' AND (zone_id = '" . (int)$address['zone_id'] . "' OR zone_id = '0')");
              
            if (!$this->config->get('sberbank_transfer_geo_zone_id')) {
                $status = true;
            } elseif ($query->num_rows) {
                $status = true;
            } else {
                $status = false;
            }
        } else {
            $status = false;
        }

        $method_data = array();

        if ($status) {
              $method_data = array(
                  'code'       => 'sberbank_transfer',
                  'title'      => $this->config->get('sberbank_transfer_title_' . $this->config->get('config_language_id')),
                  'terms'      => '',
                  'sort_order' => $this->config->get('sberbank_transfer_sort_order')
              );
        }

        return $method_data;
    }
}
?>