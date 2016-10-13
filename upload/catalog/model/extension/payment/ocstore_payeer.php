<?php 
/**
 * Support:
 * https://opencartforum.com/user/3463-shoputils/
 * http://opencart.shoputils.ru/?route=information/contact
 * 
*/

class ModelExtensionPaymentOcstorePayeer extends Model {
		private static $_METHOD_CODE = 'ocstore_payeer';

    public function getMethod($address, $total) {
        $this->load->language('extension/payment/ocstore_payeer');

        if (($this->config->get('ocstore_payeer_status')) && ($total) &&
            (!$this->config->get('ocstore_payeer_minimal_order') || ($total >= (float)$this->config->get('ocstore_payeer_minimal_order'))) &&
            (!$this->config->get('ocstore_payeer_maximal_order') || ($total <= (float)$this->config->get('ocstore_payeer_maximal_order')))) {

            $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "zone_to_geo_zone WHERE geo_zone_id = '" . (int) $this->config->get('ocstore_payeer_geo_zone_id') . "' AND country_id = '" . (int) $address['country_id'] . "' AND (zone_id = '" . (int) $address['zone_id'] . "' OR zone_id = '0')");

            if (!$this->config->get('ocstore_payeer_geo_zone_id')) {
                $status = true;
            } elseif ($query->num_rows) {
                $status = true;
            } else {
                $status = false;
            }

            //hide_mode
            if ($this->config->get('ocstore_payeer_hide_mode')) {
                $this->user = new Cart\User($this->registry);
                if (!$this->user->isLogged()) {
                    $status = false;
                }
            }
        } else {
            $status = false;
        }

        $method_data = array();

        if ($status) {
            $server = isset($this->request->server['HTTPS']) && $this->request->server['HTTPS'] ? $this->config->get('config_ssl') : $this->config->get('config_url');

            $title = $this->config->get('ocstore_payeer_langdata');
            $method_data = array(
                'code'        => self::$_METHOD_CODE,
                'title'       => $title[$this->config->get('config_language_id')]['title'],
                'description' => sprintf($this->language->get('text_description'), $server),
                'terms'       => '',
                'sort_order'  => $this->config->get('ocstore_payeer_sort_order')
            );
        }

        return $method_data;
    }

    public function checkLaterpay($order_id) {
        return $this->isLaterpayButtonLK($order_id) || $this->isLaterpayMode($order_id);
    }

    public function getOrderStatusById($order_status_id, $language_id = false) {
        if (!$language_id) {
          $language_id = (int)$this->config->get('config_language_id');
        }
        $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "order_status WHERE order_status_id = '" . (int)$order_status_id . "' AND language_id = '" . $language_id . "'");
        return $query->num_rows ? $query->row['name'] : '';
    }

    public function getCustomerGroup($customer_group_id) {
        $query = $this->db->query("SELECT DISTINCT * FROM " . DB_PREFIX . "customer_group cg LEFT JOIN " . DB_PREFIX . "customer_group_description cgd ON (cg.customer_group_id = cgd.customer_group_id) WHERE cg.customer_group_id = '" . (int)$customer_group_id . "' AND cgd.language_id = '" . (int)$this->config->get('config_language_id') . "'");
        return $query->num_rows ? $query->row['name'] : '';
    }

    public function getOrder($order_id) {
        $query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "order` WHERE order_id = '" . (int)$order_id . "'");
        return $query->num_rows ? $query->row : false;
    }

    protected function isLaterpayButtonLK($order_id) {
        //Laterpay Button LK Enabled?
        if ($this->config->get('ocstore_payeer_laterpay_button_lk')) {
            if (!isset($this->_order_info)) {
                $this->_order_info = $this->getOrder($order_id);
            }
            
            if (!$this->_order_info || ($this->_order_info['payment_code'] != self::$_METHOD_CODE) || !$this->config->get('ocstore_payeer_status')) {
                return false;
            }

            return ($this->_order_info['order_status_id'] == $this->config->get('ocstore_payeer_order_confirm_status_id')) || ($this->_order_info['order_status_id'] == $this->config->get('ocstore_payeer_order_fail_status_id'));
        }

        return false;
    }

    protected function isLaterpayMode($order_id) {
        //Mode Laterpay Enabled?
        if ($this->config->get('ocstore_payeer_laterpay_mode') && ($this->config->get('ocstore_payeer_order_later_status_id') != $this->config->get('ocstore_payeer_order_confirm_status_id'))) {
            if (!isset($this->_order_info)) {
                $this->_order_info = $this->getOrder($order_id);
            }

            if (!$this->_order_info || ($this->_order_info['payment_code'] != self::$_METHOD_CODE) || !$this->config->get('ocstore_payeer_status')) {
                return false;
            }
              
            return $this->_order_info['order_status_id'] == $this->config->get('ocstore_payeer_order_later_status_id');
        }

        return false;
    }
}
?>