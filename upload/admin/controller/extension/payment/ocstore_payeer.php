<?php
/**
 * Support:
 * https://opencartforum.com/user/3463-shoputils/
 * http://opencart.shoputils.ru/?route=information/contact
 * 
*/

class ControllerExtensionPaymentOcstorePayeer extends Controller {
    private $error = array();
    private $version = '2.3';
    private $payeer_currencies = array();
    const MAX_LAST_LOG_LINES = 500;
    const FILE_NAME_LOG = 'ocstore_payeer.log';

    public function __construct($registry) {
        parent::__construct($registry);
        $this->load->language('extension/payment/ocstore_payeer');
        $this->document->setTitle($this->language->get('heading_title'));
    }

    public function index() {
        if (($this->request->server['REQUEST_METHOD'] == 'POST') && ($this->validate())) {
            $this->_trimData(array(
                 'ocstore_payeer_shop_id',
                 'ocstore_payeer_sign_hash',
                 'ocstore_payeer_minimal_order',
                 'ocstore_payeer_maximal_order'
            ));

            $this->_replaceData(',', '.', array(
                 'ocstore_payeer_minimal_order',
                 'ocstore_payeer_maximal_order'
            ));

            $this->load->model('setting/setting');

            $this->model_setting_setting->editSetting('ocstore_payeer', $this->request->post);
            $this->session->data['success'] = sprintf($this->language->get('text_success'), $this->language->get('heading_title'));

            $this->response->redirect($this->makeUrl('extension/extension', 'type=payment'));
        }

        $this->load->model('localisation/currency');
        $this->load->model('localisation/language');
        $this->load->model('localisation/order_status');
        $this->load->model('localisation/geo_zone');

        $this->payeer_currencies = array(
            'RUB' => $this->language->get('text_currency_rub'),
            'USD' => $this->language->get('text_currency_usd'),
            'EUR' => $this->language->get('text_currency_eur')
        );
        $currencies = array_intersect_key($this->payeer_currencies, $this->model_localisation_currency->getCurrencies());
        if (!count($currencies)) {
            $this->error['warning'] = $this->error['error_currency'] = sprintf($this->language->get('error_currency'), implode(', ', array_keys($this->payeer_currencies)));
        }

        //CKEditor
        if ($this->config->get('config_editor_default')) {
            $this->document->addScript('view/javascript/ckeditor/ckeditor.js');
            $this->document->addScript('view/javascript/ckeditor/ckeditor_init.js');
        } else {
            $this->document->addScript('view/javascript/summernote/summernote.js');
            $this->document->addScript('view/javascript/summernote/lang/summernote-' . $this->language->get('lang') . '.js');
            $this->document->addScript('view/javascript/summernote/opencart.js');
            $this->document->addStyle('view/javascript/summernote/summernote.css');
        }

        $data = $this->_setData(array(
            'heading_title',
            'lang',
            'button_save',
            'button_cancel',
            'button_clear',

            'tab_general',
            'tab_emails',
            'tab_settings',
            'tab_log',
            'tab_information',

            'text_confirm',
            'text_enabled',
            'text_disabled',
            'text_all_zones',
            'text_yes',
            'text_no',
            'text_info',

            'entry_geo_zone',
            'entry_status',
            'entry_hide_mode',
            'entry_minimal_order',
            'entry_maximal_order',
            'entry_sort_order',
            'entry_order_status',
            'entry_order_confirm_status',
            'entry_order_fail_status',
            'entry_laterpay_mode',
            'entry_order_later_status',
            'entry_title',
            'entry_instruction',
            'entry_laterpay_button_lk',
            'entry_button_confirm',

            'entry_notify_customer_success',
            'entry_mail_customer_success_subject',
            'entry_mail_customer_success_content',
            'entry_notify_customer_fail',
            'entry_mail_customer_fail_subject',
            'entry_mail_customer_fail_content',
            'entry_notify_admin_success',
            'entry_mail_admin_success_subject',
            'entry_mail_admin_success_content',
            'entry_notify_admin_fail',
            'entry_mail_admin_fail_subject',
            'entry_mail_admin_fail_content',

            'entry_shop_id',
            'entry_sign_hash',
            'entry_currency',

            'entry_log',
            'entry_log_file',

            'entry_success_url',
            'entry_fail_url',
            'entry_status_url',

            'sample_button_confirm',
            'sample_mail_customer_success_content',
            'sample_mail_customer_success_subject',
            'sample_mail_customer_fail_subject',
            'sample_mail_customer_fail_content',
            'sample_mail_admin_success_subject',
            'sample_mail_admin_success_content',
            'sample_mail_admin_fail_subject',
            'sample_mail_admin_fail_content',

            'placeholder_instruction',

            'help_hide_mode',
            'help_minimal_order',
            'help_maximal_order',
            'help_order_confirm_status',
            'help_order_status',
            'help_order_fail_status',
            'help_laterpay_mode',
            'help_order_later_status',
            'help_title',
            'help_instruction',
            'help_laterpay_button_lk',
            'help_button_confirm',

            'help_notify_customer_success',
            'help_mail_customer_success_subject',
            'help_mail_customer_success_content',
            'help_notify_customer_fail',
            'help_mail_customer_fail_subject',
            'help_mail_customer_fail_content',
            'help_notify_admin_success',
            'help_mail_admin_success_subject',
            'help_mail_admin_success_content',
            'help_notify_admin_fail',
            'help_mail_admin_fail_subject',
            'help_mail_admin_fail_content',

            'help_shop_id',
            'help_sign_hash',
            'help_currency'            => sprintf($this->language->get('help_currency'), implode(', ', array_keys($this->payeer_currencies))),

            'help_log'                 => sprintf($this->language->get('help_log'), self::FILE_NAME_LOG),
            'help_log_file'            => sprintf($this->language->get('help_log_file'), self::MAX_LAST_LOG_LINES),
            'title_default'            => explode(',', $this->language->get('heading_title')),
            'text_copyright'           => sprintf($this->language->get('text_copyright'), $this->language->get('heading_title'), date('Y', time())),
            'list_helper'              => $this->getListHelper(),
            'action'                   => $this->makeUrl('extension/payment/ocstore_payeer'),
            'cancel'                   => $this->makeUrl('extension/extension', 'type=payment'),
            'clear_log'                => $this->makeUrl('extension/payment/ocstore_payeer/clearLog'),
            'ckeditor'                 => $this->config->get('config_editor_default'),
            'token'                    => isset($this->session->data['token']) ? $this->session->data['token'] : '',
            'error_warning'            => isset($this->error['warning']) ? $this->error['warning'] : '',
            'error_shop_id'            => isset($this->error['error_shop_id']) ? $this->error['error_shop_id'] : '',
            'error_sign_hash'          => isset($this->error['error_sign_hash']) ? $this->error['error_sign_hash'] : '',
            'error_currency'           => isset($this->error['error_currency']) ? $this->error['error_currency'] : '',
            'error_mail_customer_success_subject' => isset($this->error['error_mail_customer_success_subject']) ? $this->error['error_mail_customer_success_subject'] : '',
            'error_mail_customer_success_content' => isset($this->error['error_mail_customer_success_content']) ? $this->error['error_mail_customer_success_content'] : '',
            'error_mail_customer_fail_subject'    => isset($this->error['error_mail_customer_fail_subject']) ? $this->error['error_mail_customer_fail_subject'] : '',
            'error_mail_customer_fail_content'    => isset($this->error['error_mail_customer_fail_content']) ? $this->error['error_mail_customer_fail_content'] : '',
            'error_mail_admin_success_subject'    => isset($this->error['error_mail_admin_success_subject']) ? $this->error['error_mail_admin_success_subject'] : '',
            'error_mail_admin_success_content'    => isset($this->error['error_mail_admin_success_content']) ? $this->error['error_mail_admin_success_content'] : '',
            'error_mail_admin_fail_subject'       => isset($this->error['error_mail_admin_fail_subject']) ? $this->error['error_mail_admin_fail_subject'] : '',
            'error_mail_admin_fail_content'       => isset($this->error['error_mail_admin_fail_content']) ? $this->error['error_mail_admin_fail_content'] : '',
            'text_success_url'         => HTTPS_CATALOG . 'index.php?route=extension/payment/ocstore_payeer/success',
            'text_fail_url'            => HTTPS_CATALOG . 'index.php?route=extension/payment/ocstore_payeer/fail',
            'text_status_url'          => HTTPS_CATALOG . 'index.php?route=extension/payment/ocstore_payeer/status',
            'version'                  => $this->version,
            'log_lines'                => $this->readLastLines(DIR_LOGS . self::FILE_NAME_LOG, self::MAX_LAST_LOG_LINES),
            'log_filename'             => self::FILE_NAME_LOG,
            'geo_zones'                => $this->model_localisation_geo_zone->getGeoZones(),
            'order_statuses'           => $this->model_localisation_order_status->getOrderStatuses(),
            'currencies'               => $currencies,
            'oc_languages'             => $this->model_localisation_language->getLanguages()
        ));


        $data['breadcrumbs'] = array();

        $data['breadcrumbs'][] = array(
           'href'      => $this->makeUrl('common/dashboard'),
           'text'      => $this->language->get('text_home')
        );
        
        $data['breadcrumbs'][] = array(
           'href'      => $this->makeUrl('extension/extension', 'type=payment'),
           'text'      => $this->language->get('text_payment')
        );
        
        $data['breadcrumbs'][] = array(
           'href'      => $this->makeUrl('extension/payment/ocstore_payeer'),
           'text'      => $this->language->get('heading_title')
        );

        $data['logs'] = array(
            '0' => $this->language->get('text_log_off'),
            '1' => $this->language->get('text_log_short'),
            '2' => $this->language->get('text_log_full'),
        );

        $data = array_merge($data, $this->_updateData(
            array(
                 'ocstore_payeer_geo_zone_id',
                 'ocstore_payeer_sort_order',
                 'ocstore_payeer_status',
                 'ocstore_payeer_hide_mode',
                 'ocstore_payeer_minimal_order',
                 'ocstore_payeer_maximal_order',
                 'ocstore_payeer_laterpay_mode',
                 'ocstore_payeer_order_status_id',
                 'ocstore_payeer_order_fail_status_id',
                 'ocstore_payeer_order_confirm_status_id',
                 'ocstore_payeer_order_later_status_id',
                 'ocstore_payeer_laterpay_button_lk',
                 'ocstore_payeer_langdata',

                 'ocstore_payeer_notify_customer_success',
                 'ocstore_payeer_notify_customer_fail',
                 'ocstore_payeer_notify_admin_success',
                 'ocstore_payeer_mail_admin_success_subject',
                 'ocstore_payeer_mail_admin_success_content',
                 'ocstore_payeer_notify_admin_fail',
                 'ocstore_payeer_mail_admin_fail_subject',
                 'ocstore_payeer_mail_admin_fail_content',

                 'ocstore_payeer_shop_id',
                 'ocstore_payeer_sign_hash',
                 'ocstore_payeer_currency',

                 'ocstore_payeer_log'
            ),
            array()
        ));

        $data = array_merge($data, $this->_setData(
            array(
                 'header'       => $this->load->controller('common/header'),
                 'column_left'  => $this->load->controller('common/column_left'),
                 'footer'       => $this->load->controller('common/footer')
            )
        ));

        $this->response->setOutput($this->load->view('extension/payment/ocstore_payeer', $data));
    }

    public function clearLog() {
      $json = array();

      if ($this->validatePermission()) {
          if (is_file(DIR_LOGS . self::FILE_NAME_LOG)) {
              @unlink(DIR_LOGS . self::FILE_NAME_LOG);
          }
          $json['success'] = $this->language->get('text_clear_log_success');
      } else {
          $json['error'] = $this->language->get('error_clear_log');
      }

      $this->response->addHeader('Content-Type: application/json');
      $this->response->setOutput(json_encode($json));
    }

    protected function getListHelper() {
        $data = $this->_setData(array(
                             'text_order_id_ft',
                             'text_store_name_ft',
                             'text_logo_ft',
                             //'text_products_ft',
                             'text_total_ft',
                             'text_customer_firstname_ft',
                             'text_customer_lastname_ft',
                             'text_customer_group_ft',
                             'text_customer_email_ft',
                             'text_customer_telephone_ft',
                             'text_order_status_ft',
                             'text_comment_ft',
                             'text_ip_ft',
                             'text_date_added_ft',
                             'text_date_modified_ft'
                        ));

        return $this->load->view('extension/payment/ocstore_payeer_list_helper', $data);
    }

    protected function validate() {
        if (!$this->validatePermission()) {
            $this->error['warning'] = sprintf($this->language->get('error_permission'), $this->language->get('heading_title'));
        } else {
            if (!isset($this->request->post['ocstore_payeer_shop_id']) || !trim($this->request->post['ocstore_payeer_shop_id'])) {
                $this->error['warning'] = $this->error['error_shop_id'] = sprintf($this->language->get('error_form'),
                                                                                        $this->language->get('entry_shop_id'),
                                                                                        $this->language->get('tab_settings'));
            }
            if (!isset($this->request->post['ocstore_payeer_sign_hash']) || !trim($this->request->post['ocstore_payeer_sign_hash'])) {
                $this->error['warning'] = $this->error['error_sign_hash'] = sprintf($this->language->get('error_form'),
                                                                                        $this->language->get('entry_sign_hash'),
                                                                                        $this->language->get('tab_settings'));
            }

            $this->load->model('localisation/language');
            foreach ($this->model_localisation_language->getLanguages() as $language) {
              if (($this->request->post['ocstore_payeer_notify_customer_success']) && ((!isset($this->request->post['ocstore_payeer_langdata'][$language['language_id']]['mail_customer_success_subject']) || !trim($this->request->post['ocstore_payeer_langdata'][$language['language_id']]['mail_customer_success_subject'])))) {
                  $this->error['warning'] = $this->error['error_mail_customer_success_subject'] = sprintf($this->language->get('error_form'),
                                                                                                          $this->language->get('entry_mail_customer_success_subject'),
                                                                                                          $this->language->get('tab_emails'));
              }
              if (($this->request->post['ocstore_payeer_notify_customer_success']) && ((!isset($this->request->post['ocstore_payeer_langdata'][$language['language_id']]['mail_customer_success_content']) || !$this->request->post['ocstore_payeer_langdata'][$language['language_id']]['mail_customer_success_content']))) {
                  $this->error['warning'] = $this->error['error_mail_customer_success_content'] = sprintf($this->language->get('error_form'),
                                                                                                          $this->language->get('entry_mail_customer_success_content'),
                                                                                                          $this->language->get('tab_emails'));
              }
              if (($this->request->post['ocstore_payeer_notify_customer_fail']) && ((!isset($this->request->post['ocstore_payeer_langdata'][$language['language_id']]['mail_customer_fail_subject']) || !trim($this->request->post['ocstore_payeer_langdata'][$language['language_id']]['mail_customer_fail_subject'])))) {
                  $this->error['warning'] = $this->error['error_mail_customer_fail_subject'] = sprintf($this->language->get('error_form'),
                                                                                                          $this->language->get('entry_mail_customer_fail_subject'),
                                                                                                          $this->language->get('tab_emails'));
              }
              if (($this->request->post['ocstore_payeer_notify_customer_fail']) && ((!isset($this->request->post['ocstore_payeer_langdata'][$language['language_id']]['mail_customer_fail_content']) || !$this->request->post['ocstore_payeer_langdata'][$language['language_id']]['mail_customer_fail_content']))) {
                  $this->error['warning'] = $this->error['error_mail_customer_fail_content'] = sprintf($this->language->get('error_form'),
                                                                                                          $this->language->get('entry_mail_customer_fail_content'),
                                                                                                          $this->language->get('tab_emails'));
              }
            }

            if (($this->request->post['ocstore_payeer_notify_admin_success']) && ((!isset($this->request->post['ocstore_payeer_mail_admin_success_subject']) || !trim($this->request->post['ocstore_payeer_mail_admin_success_subject'])))) {
                $this->error['warning'] = $this->error['error_mail_admin_success_subject'] = sprintf($this->language->get('error_form'),
                                                                                                          $this->language->get('entry_mail_admin_success_subject'),
                                                                                                          $this->language->get('tab_emails'));
            }
            if (($this->request->post['ocstore_payeer_notify_admin_success']) && ((!isset($this->request->post['ocstore_payeer_mail_admin_success_content']) || !$this->request->post['ocstore_payeer_mail_admin_success_content']))) {
                $this->error['warning'] = $this->error['error_mail_admin_success_content'] = sprintf($this->language->get('error_form'),
                                                                                                          $this->language->get('entry_mail_admin_success_content'),
                                                                                                          $this->language->get('tab_emails'));
            }
            if (($this->request->post['ocstore_payeer_notify_admin_fail']) && ((!isset($this->request->post['ocstore_payeer_mail_admin_fail_subject']) || !trim($this->request->post['ocstore_payeer_mail_admin_fail_subject'])))) {
                $this->error['warning'] = $this->error['error_mail_admin_fail_subject'] = sprintf($this->language->get('error_form'),
                                                                                                          $this->language->get('entry_mail_admin_fail_subject'),
                                                                                                          $this->language->get('tab_emails'));
            }
            if (($this->request->post['ocstore_payeer_notify_admin_fail']) && ((!isset($this->request->post['ocstore_payeer_mail_admin_fail_content']) || !$this->request->post['ocstore_payeer_mail_admin_fail_content']))) {
                $this->error['warning'] = $this->error['error_mail_admin_fail_content'] = sprintf($this->language->get('error_form'),
                                                                                                          $this->language->get('entry_mail_admin_fail_content'),
                                                                                                          $this->language->get('tab_emails'));
            }
        }

        return !$this->error;
    }

    protected function validatePermission() {
        return $this->user->hasPermission('modify', 'extension/payment/ocstore_payeer');
    }

    protected function _setData($values) {
        $data = array();
        foreach ($values as $key => $value) {
            if (is_int($key)) {
                $data[$value] = $this->language->get($value);
            } else {
                $data[$key] = $value;
            }
        }
        return $data;
    }

    protected function _updateData($keys, $info = array()) {
        $data = array();
        foreach ($keys as $key) {
            if (isset($this->request->post[$key])) {
                $data[$key] = $this->request->post[$key];
            } elseif (isset($info[$key])) {
                $data[$key] = $info[$key];
            } else {
                $data[$key] = $this->config->get($key);
            }
        }
        return $data;
    }

    protected function makeUrl($route, $url = '') {
        return str_replace('&amp;', '&', $this->url->link($route, $url . '&token=' . $this->session->data['token'], 'SSL'));
    }

    protected function _trimData($values) {
        foreach ($values as $value) {
                if (isset($this->request->post[$value])) {
                    $this->request->post[$value] = trim($this->request->post[$value]);
                }
        }
    }

    protected function _replaceData($search, $replace, $values) {
        foreach ($values as $value) {
                if (isset($this->request->post[$value])) {
                    $this->request->post[$value] = str_replace($search, $replace, $this->request->post[$value]);
                }
        }
    }

    protected function readLastLines($filename, $lines) {
        if (!is_file($filename)) {
            return array();
        }
        $handle = @fopen($filename, "r");
        if (!$handle) {
            return array();
        }
        $linecounter = $lines;
        $pos = -1;
        $beginning = false;
        $text = array();

        while ($linecounter > 0) {
            $t = " ";

            while ($t != "\n") {
                /* if fseek() returns -1 we need to break the cycle*/
                if (fseek($handle, $pos, SEEK_END) == -1) {
                    $beginning = true;
                    break;
                }
                $t = fgetc($handle);
                $pos--;
            }

            $linecounter--;

            if ($beginning) {
                rewind($handle);
            }

            $text[$lines - $linecounter - 1] = fgets($handle);

            if ($beginning) {
                break;
            }
        }
        fclose($handle);

        return array_reverse($text);
    }
}
?>