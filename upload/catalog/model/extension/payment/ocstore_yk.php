<?php 
class ModelExtensionPaymentOcstoreYk extends Model {
    private static $_METHOD_CODE = 'ocstore_yk';

    public function getMethod($address, $total) {
        return array();
    }

    public function getMethodData($address, $total, $method_code) {
        if (!$this->config->get('ocstore_yk_type') && !in_array($method_code, array('ocstore_yk_physical_AC', 'ocstore_yk_physical_PC'))) {
            return array();
        }

        if ($this->config->get('ocstore_yk_type') && in_array($method_code, array('ocstore_yk_physical_AC', 'ocstore_yk_physical_PC'))) {
            return array();
        }

        if (($this->config->get($method_code . '_status')) && ($total) &&
            (!$this->config->get($method_code . '_minimal_order') || ($total >= (float)$this->config->get($method_code . '_minimal_order'))) &&
            (!$this->config->get($method_code . '_maximal_order') || ($total <= (float)$this->config->get($method_code . '_maximal_order')))) {
              $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "zone_to_geo_zone WHERE geo_zone_id = '" . (int)$this->config->get($method_code . '_geo_zone_id') . "' AND country_id = '" . (int)$address['country_id'] . "' AND (zone_id = '" . (int)$address['zone_id'] . "' OR zone_id = '0')");
          
            if (!$this->config->get($method_code . '_geo_zone_id')) {
                $status = true;
            } elseif ($query->num_rows) {
                $status = true;
            } else {
                $status = false;
            }
          //hide_mode
          if ($this->config->get('ocstore_yk_hide_mode')) {
              $this->user = new Cart\User($this->registry);
              if (!$this->user->isLogged()) {
                  $status = false;
              }
          }
      } else {
          $status = false;
        }

/*        if (!$this->config->get('ocstore_yk_type') && !$this->config->get('ocstore_yk_physical_enabled_methods')) {
            $status = false;
        }
        if ($this->config->get('ocstore_yk_type') && !$this->config->get('ocstore_yk_company_enabled_methods')) {
            $status = false;
        }
*/
        $method_data = array();

        if ($status) {
            $title = $this->config->get($method_code . '_langdata');

            $method_data = array(
                'code'        => $method_code,
                'title'       => $title[$this->config->get('config_language_id')]['title'],
                'description' => $this->makeDescriptions($method_code),
                'terms'       => '',
                'sort_order'  => $this->config->get($method_code . '_sort_order')
            );
        }

        return $method_data;
    }

    public function checkLaterpay($order_id) {
        $order_info = $this->getOrder($order_id);
        
        $result =  array(
            'onpay'         => $this->isLaterpayButtonLK($order_info) || $this->isLaterpayMode($order_info),
            'payment_code'  => $order_info ? $order_info['payment_code'] : ''
         );
         
         return $result;
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

    protected function isLaterpayButtonLK($order_info) {
        //Laterpay Button LK Enabled?
        if ($this->config->get('ocstore_yk_laterpay_button_lk')) {            
            if (!$order_info || (strpos($order_info['payment_code'], self::$_METHOD_CODE) === false) || !$this->config->get($order_info['payment_code'] . '_status')) {
                return false;
            }

            return ($order_info['order_status_id'] == $this->config->get('ocstore_yk_order_confirm_status_id')) || ($order_info['order_status_id'] == $this->config->get('ocstore_yk_order_fail_status_id'));
        }

        return false;
    }

    protected function isLaterpayMode($order_info) {
        //Mode Laterpay Enabled?
        if ($this->config->get('ocstore_yk_laterpay_mode') && ($this->config->get('ocstore_yk_order_later_status_id') != $this->config->get('ocstore_yk_order_confirm_status_id'))) {
            if (!$order_info || (strpos($order_info['payment_code'], self::$_METHOD_CODE) === false) || !$this->config->get($order_info['payment_code'] . '_status')) {
                return false;
            }
              
            return $order_info['order_status_id'] == $this->config->get('ocstore_yk_order_later_status_id');
        }

        return false;
    }

    protected function makeDescriptions($method_code) {
        if (!$this->config->get($method_code . '_description')) {
            return '';
        }

        $this->load->language('extension/payment/ocstore_yk');

        $server = isset($this->request->server['HTTPS']) && $this->request->server['HTTPS'] ? $this->config->get('config_ssl') : $this->config->get('config_url');
        $path = $server . 'catalog/view/theme/default/image/payment/ocstore_yk/';
        $title = $this->config->get($method_code . '_langdata');

        $icons = array(
            'ocstore_yk_company_AC' => sprintf('<img src="%1$scard-visa.png" title="Visa" alt="Visa"/> <img src="%1$scard-mastercard.png" title="Mastercard" alt="Mastercard"/> <img src="%1$scard-maestro.png" title="Maestro" alt="Maestro"/> ', $path),
            'ocstore_yk_company_PC' => sprintf('<img src="%syandex-money.png" title="Yandex.Money" alt="Yandex.Money"/> ', $path),
            'ocstore_yk_company_WM' => sprintf('<img src="%swebmoney.png" title="Webmoney" alt="Webmoney"/> ', $path),
            'ocstore_yk_company_AB' => sprintf('<img src="%salpha-click.png" title="Alpha-Click" alt="Alpha-Click"/> ', $path),
            'ocstore_yk_company_SB' => sprintf('<img src="%ssbrf.png" title="Sberbank-Online" alt="Sberbank-Online"/> ', $path),
            'ocstore_yk_company_GP' => sprintf('<img src="%sterminal.png" title="Terminal" alt="Terminal"/> ', $path),
            'ocstore_yk_company_EP' => sprintf('<img src="%serip.png" title="AIS ERIP RB" alt="AIS ERIP RB"/> ', $path),
            'ocstore_yk_company_MC' => sprintf('<img src="%1$smegafon.png" title="Megafon" alt="Megafon"/> <img src="%1$sbeeline.png" title="Beeline" alt="Beeline"/> <img src="%1$smts.png" title="MTS" alt="MTS"/> <img src="%1$stele2.png" title="Tele2" alt="Tele2"/> ', $path),
            'ocstore_yk_company_MP' => sprintf('<img src="%smpos.png" title="mPOS" alt="mPOS"/> ', $path),
            'ocstore_yk_company_MA' => sprintf('<img src="%smasterpass.png" title="MasterPass" alt="MasterPass"/> ', $path),
            'ocstore_yk_company_PB' => sprintf('<img src="%spsb.png" title="Promsvyasbank" alt="Promsvyasbank"/> ', $path),
            'ocstore_yk_company_QW' => sprintf('<img src="%sqiwi.png" title="QIWI Wallet" alt="QIWI Wallet"/> ', $path),
            'ocstore_yk_physical_AC' => sprintf('<img src="%1$scard-visa.png" title="Visa" alt="Visa"/> <img src="%1$scard-mastercard.png" title="Mastercard" alt="Mastercard"/> <img src="%1$scard-maestro.png" title="Maestro" alt="Maestro"/> ', $path),
            'ocstore_yk_physical_PC' => sprintf('<img src="%syandex-money.png" title="Yandex.Money" alt="Yandex.Money"/> ', $path)
        );

        $result = $this->language->get('text_description');

        $result .= $icons[$method_code];

        return $result . $this->language->get('text_description_end');
    }
}
?>