<?php
/**
 * Support:
 * https://opencartforum.com/user/3463-shoputils/
 * http://opencart.shoputils.ru/?route=information/contact
 * 
*/
class ControllerExtensionPaymentOcstoreYk extends Controller {
    private $error = array();
    private $version = '1.0.ocs';
    const MAX_LAST_LOG_LINES = 500;
    const FILE_NAME_LOG = 'ocstore_yk.log';

    public function __construct($registry) {
        parent::__construct($registry);
        $this->load->language('extension/payment/ocstore_yk');
    }

    public function index() {
        if (($this->request->server['REQUEST_METHOD'] == 'POST') && ($this->validate())) {
            if (!$this->request->post['ocstore_yk_company_enabled_methods'] && !$this->request->post['ocstore_yk_physical_enabled_methods']) {
                $this->request->post['ocstore_yk_status'] = '0';
            } else {
                $this->request->post['ocstore_yk_status'] = '1';
            }

            if ($this->request->post['ocstore_yk_type']) {
                $this->request->post['ocstore_yk_sort_order'] = $this->request->post['ocstore_yk_company_AC_sort_order'];
            } else {
                $this->request->post['ocstore_yk_sort_order'] = $this->request->post['ocstore_yk_physical_AC_sort_order'];
            }

            $this->_trimData(array(
                 'ocstore_yk_wallet',
                 'ocstore_yk_scid',
                 'ocstore_yk_shop_id',
                 'ocstore_yk_shop_password',
                 'ocstore_yk_minimal_order',
                 'ocstore_yk_maximal_order'
            ));

            $replace_data = array();

            foreach ($this->request->post['company_enabled_methods'] as $method => $text) {
                $replace_data[] = 'ocstore_yk_company_' . $method  . '_minimal_order';
                $replace_data[] = 'ocstore_yk_company_' . $method  . '_maximal_order';
            }

            foreach ($this->request->post['physical_enabled_methods'] as $method => $text) {
                $replace_data[] = 'ocstore_yk_physical_' . $method  . '_minimal_order';
                $replace_data[] = 'ocstore_yk_physical_' . $method  . '_maximal_order';
            }

            $this->_replaceData(',', '.', $replace_data);

            $this->load->model('setting/setting');
            $this->model_setting_setting->editSetting('ocstore_yk', $this->request->post);
            $this->session->data['success'] = sprintf($this->language->get('text_success_setting'), $this->language->get('heading_title'));
            $this->response->redirect($this->makeUrl('extension/extension', 'type=payment'));
        }

        $this->load->model('localisation/language');
        $this->load->model('localisation/order_status');
        $this->load->model('localisation/geo_zone');
        $this->document->addStyle('view/stylesheet/ocstore_yk.css');
        $this->document->setTitle($this->language->get('heading_title'));

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

        $server = isset($this->request->server['HTTPS']) && $this->request->server['HTTPS'] ? HTTPS_CATALOG : HTTP_CATALOG;

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
                             'text_title_default',
                             'text_all_zones',
                             'text_yes',
                             'text_no',
                             'text_physical_limits',
                             'text_company_limits',
                             'text_info_physical',
                             'text_info_company',
                             'text_ssl',

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
                             'entry_description',
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

                             'entry_type',
                             'entry_wallet',
                             'entry_scid',
                             'entry_shop_id',
                             'entry_shop_password',
                             'entry_enabled_methods',
                             'entry_payment_type_card',
                             'entry_payment_type_yandexmoney',
                             'entry_payment_type_webmoney',
                             'entry_test_mode',
                             'entry_comission',

                             'entry_log',
                             'entry_log_file',

                             'entry_callback_url',

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

                             'help_sort_order',
                             'help_hide_mode',
                             'help_minimal_order',
                             'help_maximal_order',
                             'help_order_confirm_status',
                             'help_order_status',
                             'help_order_fail_status',
                             'help_laterpay_mode',
                             'help_order_later_status',
                             'help_title',
                             'help_description',
                             'help_instruction',
                             'help_laterpay_button_lk',
                             'help_button_confirm',

                             'help_notify_customer_success',
                             'help_fast_template',
                             'help_notify_customer_fail',
                             'help_notify_admin_success',
                             'help_notify_admin_fail',

                             'help_wallet',
                             'help_scid',
                             'help_shop_id',
                             'help_shop_password',
                             'help_test_mode_company',
                             'help_test_mode_physical',
                             'help_comission',

                             'help_type'            => sprintf($this->language->get('help_type'), $this->language->get('text_dirka_ot_bublika_a_ne_nalogi'), $this->language->get('text_ya_plachu_nalogi')),
                             'help_log_file'        => sprintf($this->language->get('help_log_file'), self::MAX_LAST_LOG_LINES),
                             'help_log'             => sprintf($this->language->get('help_log'), self::FILE_NAME_LOG),
                             'action'               => $this->makeUrl('extension/payment/ocstore_yk'),
                             'cancel'               => $this->makeUrl('extension/extension', 'type=payment'),
                             'clear_log'            => $this->makeUrl('extension/payment/ocstore_yk/clearLog'),
                             'ckeditor'             => $this->config->get('config_editor_default'),
                             'list_helper'          => $this->getListHelper(),
                             'backend_currency'     => $this->config->get('config_currency'),
                             'error_warning'        => isset($this->error['warning']) ? $this->error['warning'] : '',
                             'error_mail_customer_success_subject' => isset($this->error['error_mail_customer_success_subject']) ? $this->error['error_mail_customer_success_subject'] : '',
                             'error_mail_customer_success_content' => isset($this->error['error_mail_customer_success_content']) ? $this->error['error_mail_customer_success_content'] : '',
                             'error_mail_customer_fail_subject'    => isset($this->error['error_mail_customer_fail_subject']) ? $this->error['error_mail_customer_fail_subject'] : '',
                             'error_mail_customer_fail_content'    => isset($this->error['error_mail_customer_fail_content']) ? $this->error['error_mail_customer_fail_content'] : '',
                             'error_mail_admin_success_subject'    => isset($this->error['error_mail_admin_success_subject']) ? $this->error['error_mail_admin_success_subject'] : '',
                             'error_mail_admin_success_content'    => isset($this->error['error_mail_admin_success_content']) ? $this->error['error_mail_admin_success_content'] : '',
                             'error_mail_admin_fail_subject'       => isset($this->error['error_mail_admin_fail_subject']) ? $this->error['error_mail_admin_fail_subject'] : '',
                             'error_mail_admin_fail_content'       => isset($this->error['error_mail_admin_fail_content']) ? $this->error['error_mail_admin_fail_content'] : '',
                             'error_wallet'         => isset($this->error['error_wallet']) ? $this->error['error_wallet'] : '',
                             'error_scid'           => isset($this->error['error_scid']) ? $this->error['error_scid'] : '',
                             'error_shop_id'        => isset($this->error['error_shop_id']) ? $this->error['error_shop_id'] : '',
                             'error_shop_password'  => isset($this->error['error_shop_password']) ? $this->error['error_shop_password'] : '',
                             'permission'           => $this->validatePermission(),
                             'version'              => $this->version,
                             'log_lines'            => $this->readLastLines(DIR_LOGS . self::FILE_NAME_LOG, self::MAX_LAST_LOG_LINES),
                             'log_filename'         => self::FILE_NAME_LOG,
                             'geo_zones'            => $this->model_localisation_geo_zone->getGeoZones(),
                             'order_statuses'       => $this->model_localisation_order_status->getOrderStatuses(),
                             'oc_languages'         => $this->model_localisation_language->getLanguages()
                        ));

        $data['types'] = array(
            '0'  => $this->language->get('text_dirka_ot_bublika_a_ne_nalogi'),
            '1'  => $this->language->get('text_ya_plachu_nalogi')
        );

        $data['physical_enabled_methods'] = array(
            'AC'  => $this->language->get('text_payment_type_card'),
            'PC'  => $this->language->get('text_payment_type_yandexmoney')
        );

        $data['company_enabled_methods'] = array(
            'AC'  => $this->language->get('text_payment_type_card'),
            'PC'  => $this->language->get('text_payment_type_yandexmoney'),
            'WM'  => $this->language->get('text_payment_type_webmoney'),
            'AB'  => $this->language->get('text_payment_type_alpha'),
            'SB'  => $this->language->get('text_payment_type_sbrf'),
            'GP'  => $this->language->get('text_payment_type_terminal'),
            'EP'  => $this->language->get('text_payment_type_erip'),
            'MC'  => $this->language->get('text_payment_type_mobile'),
            'MP'  => $this->language->get('text_payment_type_mpos'),
            'MA'  => $this->language->get('text_payment_type_masterpass'),
            'PB'  => $this->language->get('text_payment_type_psb'),
            'QW'  => $this->language->get('text_payment_type_qiwi')
        );

        $data['modes'] = array(
            '0' => $this->language->get('text_disabled'),
            '1' => $this->language->get('text_enabled')
        );

        $data['laterpay_modes'] = array(
            '0' => $this->language->get('text_laterpay_mode_0'),
            '1' => $this->language->get('text_laterpay_mode_1'),
            '2' => $this->language->get('text_laterpay_mode_2')
        );

        $data['logs'] = array(
            '0' => $this->language->get('text_log_off'),
            '1' => $this->language->get('text_log_short'),
            '2' => $this->language->get('text_log_full')
        );

        $data['informations'] = array(
            'physical' => array(
                $this->language->get('entry_physical_result_url')   => $server . 'index.php?route=extension/payment/ocstore_yk/status',
                $this->language->get('entry_physical_notification') => $this->language->get('text_checkbox_on')
            ),
            'company' => array(
                $this->language->get('entry_type_connection')     => $this->language->get('text_type_connection'),
                $this->language->get('entry_check_url')           => str_replace('http://', 'https://', $server) . 'index.php?route=extension/payment/ocstore_yk/check',
                $this->language->get('entry_result_url')          => str_replace('http://', 'https://', $server) . 'index.php?route=extension/payment/ocstore_yk/callback',
                //$this->language->get('entry_success_url')         => HTTPS_CATALOG . 'index.php?route=extension/payment/ocstore_yk/success',
                //$this->language->get('entry_fail_url')            => HTTPS_CATALOG . 'index.php?route=extension/payment/ocstore_yk/fail',
                $this->language->get('entry_dynamic_urls')        => $this->language->get('text_checkbox_on'),
                $this->language->get('entry_test_payments')       => $this->language->get('text_checkbox_off'),
                $this->language->get('entry_registry_email')      => $this->language->get('text_registry_email'),
                $this->language->get('entry_shop_password_info')  => sprintf($this->language->get('text_shop_password_info'),
                                                                             $this->language->get('tab_settings'),
                                                                             $this->language->get('entry_shop_password')),
            )
        );

        if (isset($this->session->data['success'])) {
            $data['success'] = $this->session->data['success'];
            unset($this->session->data['success']);
        } else {
            $data['success'] = '';
        }

        if (isset($this->session->data['warning'])) {
            $data['error_warning'] = $this->session->data['warning'];
            unset($this->session->data['warning']);
        }

        $data['breadcrumbs'] = array();

        $data['breadcrumbs'][] = array(
            'href' => $this->makeUrl('common/dashboard'),
            'text' => $this->language->get('text_home'),
            'separator' => false
        );

        $data['breadcrumbs'][] = array(
            'href' => $this->makeUrl('extension/extension', 'type=payment'),
            'text' => $this->language->get('text_payment'),
            'separator' => ' :: '
        );

        $data['breadcrumbs'][] = array(
            'href' => $this->makeUrl('extension/payment/ocstore_yk'),
            'text' => $this->language->get('heading_title'),
            'separator' => ' :: '

        );

        $data = array_merge($data, $this->_updateData(
            array(
                 'ocstore_yk_hide_mode',
                 'ocstore_yk_laterpay_mode',
                 'ocstore_yk_order_status_id',
                 'ocstore_yk_order_fail_status_id',
                 'ocstore_yk_order_confirm_status_id',
                 'ocstore_yk_order_later_status_id',
                 'ocstore_yk_laterpay_button_lk',
                 'ocstore_yk_langdata',

                 'ocstore_yk_notify_customer_success',
                 'ocstore_yk_notify_customer_fail',
                 'ocstore_yk_notify_admin_success',
                 'ocstore_yk_mail_admin_success_subject',
                 'ocstore_yk_mail_admin_success_content',
                 'ocstore_yk_notify_admin_fail',
                 'ocstore_yk_mail_admin_fail_subject',
                 'ocstore_yk_mail_admin_fail_content',

                 'ocstore_yk_type',
                 'ocstore_yk_wallet',
                 'ocstore_yk_scid',
                 'ocstore_yk_shop_id',
                 'ocstore_yk_shop_password',
                 'ocstore_yk_physical_enabled_methods',
                 'ocstore_yk_company_enabled_methods',
                 'ocstore_yk_test_mode',

                 'ocstore_yk_log',
            ), array(
                      'ocstore_yk_physical_enabled_methods' => array(),
                      'ocstore_yk_company_enabled_methods' => array()
                     )
        ));

        $parameters = array();

        foreach ($data['company_enabled_methods'] as $method => $text) {
            $parameters[] = 'ocstore_yk_company_' . $method  . '_comission';
            $parameters[] = 'ocstore_yk_company_' . $method  . '_minimal_order';
            $parameters[] = 'ocstore_yk_company_' . $method  . '_maximal_order';
            $parameters[] = 'ocstore_yk_company_' . $method  . '_sort_order';
            $parameters[] = 'ocstore_yk_company_' . $method  . '_description';
            $parameters[] = 'ocstore_yk_company_' . $method  . '_langdata';
            $parameters[] = 'ocstore_yk_company_' . $method  . '_geo_zone_id';
        }

        foreach ($data['physical_enabled_methods'] as $method => $text) {
            $parameters[] = 'ocstore_yk_physical_' . $method  . '_comission';
            $parameters[] = 'ocstore_yk_physical_' . $method  . '_minimal_order';
            $parameters[] = 'ocstore_yk_physical_' . $method  . '_maximal_order';
            $parameters[] = 'ocstore_yk_physical_' . $method  . '_sort_order';
            $parameters[] = 'ocstore_yk_physical_' . $method  . '_description';
            $parameters[] = 'ocstore_yk_physical_' . $method  . '_langdata';
            $parameters[] = 'ocstore_yk_physical_' . $method  . '_geo_zone_id';
        }

        $data = array_merge($data, $this->_updateData($parameters));
        
        $data = array_merge($data, $this->_setData(
            array(
                 'header'       => $this->load->controller('common/header'),
                 'column_left'  => $this->load->controller('common/column_left'),
                 'footer'       => $this->load->controller('common/footer')
            )
        ));

        $this->response->setOutput($this->load->view('extension/payment/ocstore_yk.tpl', $data));
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

    public function install() {
        $this->load->model('extension/extension');
        $this->load->model('user/user_group');
        
        $methods = array(
            'ocstore_yk_company_AC',
            'ocstore_yk_company_PC',
            'ocstore_yk_company_WM',
            'ocstore_yk_company_AB',
            'ocstore_yk_company_SB',
            'ocstore_yk_company_GP',
            'ocstore_yk_company_MC',
            'ocstore_yk_company_MP',
            'ocstore_yk_company_MA',
            'ocstore_yk_company_PB',
            'ocstore_yk_company_QW',
            'ocstore_yk_physical_AC',
            'ocstore_yk_physical_PC'
        );

        foreach ($methods as $method) {
            $this->model_extension_extension->install('payment', $method);
            $this->model_user_user_group->addPermission($this->user->getGroupId(), 'access', 'extension/payment/' . $method);
            $this->model_user_user_group->addPermission($this->user->getGroupId(), 'modify', 'extension/payment/' . $method);
        }

    }

    public function uninstall() {
        $this->load->model('extension/extension');
        
        $methods = array(
            'ocstore_yk_company_AC',
            'ocstore_yk_company_PC',
            'ocstore_yk_company_WM',
            'ocstore_yk_company_AB',
            'ocstore_yk_company_SB',
            'ocstore_yk_company_GP',
            'ocstore_yk_company_MC',
            'ocstore_yk_company_MP',
            'ocstore_yk_company_MA',
            'ocstore_yk_company_PB',
            'ocstore_yk_company_QW',
            'ocstore_yk_physical_AC',
            'ocstore_yk_physical_PC'
        );

        foreach ($methods as $method) {
            $this->model_extension_extension->uninstall('payment', $method);
        }

    }

    protected function getListHelper() {
        $data = $this->_setData(array(
                             'text_order_id_ft',
                             'text_store_name_ft',
                             'text_logo_ft',
                             'text_products_ft',
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

        return $this->load->view('extension/payment/ocstore_yk_list_helper.tpl', $data);
    }

    protected function validate() {
        if (!$this->validatePermission()) {
            $this->error['warning'] = sprintf($this->language->get('error_permission'), $this->language->get('heading_title'));
        } else {
            if (!$this->request->post['ocstore_yk_type'] && (!isset($this->request->post['ocstore_yk_wallet']) || !trim($this->request->post['ocstore_yk_wallet']))) {
                $this->error['warning'] = $this->error['error_wallet'] = sprintf($this->language->get('error_form'),
                                                                                        $this->language->get('entry_wallet'),
                                                                                        $this->language->get('tab_settings'));
            } elseif (!$this->request->post['ocstore_yk_type'] && !is_numeric(trim($this->request->post['ocstore_yk_wallet']))) {
                $this->error['warning'] = $this->error['error_wallet'] = sprintf($this->language->get('error_form_wallet'),
                                                                                        $this->language->get('entry_wallet'),
                                                                                        $this->language->get('tab_settings'));
            }
            if ($this->request->post['ocstore_yk_type'] && (!isset($this->request->post['ocstore_yk_scid']) || !trim($this->request->post['ocstore_yk_scid']))) {
                $this->error['warning'] = $this->error['error_scid'] = sprintf($this->language->get('error_form'),
                                                                                        $this->language->get('entry_scid'),
                                                                                        $this->language->get('tab_settings'));
            }
            if ($this->request->post['ocstore_yk_type'] && (!isset($this->request->post['ocstore_yk_shop_id']) || !trim($this->request->post['ocstore_yk_shop_id']))) {
                $this->error['warning'] = $this->error['error_shop_id'] = sprintf($this->language->get('error_form'),
                                                                                        $this->language->get('entry_shop_id'),
                                                                                        $this->language->get('tab_settings'));
            }
            if (!isset($this->request->post['ocstore_yk_shop_password']) || !trim($this->request->post['ocstore_yk_shop_password'])) {
                $this->error['warning'] = $this->error['error_shop_password'] = sprintf($this->language->get('error_form'),
                                                                                        $this->language->get('entry_shop_password'),
                                                                                        $this->language->get('tab_settings'));
            }

            $this->load->model('localisation/language');
            foreach ($this->model_localisation_language->getLanguages() as $language) {
              if (($this->request->post['ocstore_yk_notify_customer_success']) && ((!isset($this->request->post['ocstore_yk_langdata'][$language['language_id']]['mail_customer_success_subject']) || !trim($this->request->post['ocstore_yk_langdata'][$language['language_id']]['mail_customer_success_subject'])))) {
                  $this->error['warning'] = $this->error['error_mail_customer_success_subject'] = sprintf($this->language->get('error_form'),
                                                                                                          $this->language->get('entry_mail_customer_success_subject'),
                                                                                                          $this->language->get('tab_emails'));
              }
              if (($this->request->post['ocstore_yk_notify_customer_success']) && ((!isset($this->request->post['ocstore_yk_langdata'][$language['language_id']]['mail_customer_success_content']) || !$this->request->post['ocstore_yk_langdata'][$language['language_id']]['mail_customer_success_content']))) {
                  $this->error['warning'] = $this->error['error_mail_customer_success_content'] = sprintf($this->language->get('error_form'),
                                                                                                          $this->language->get('entry_mail_customer_success_content'),
                                                                                                          $this->language->get('tab_emails'));
              }
              if (($this->request->post['ocstore_yk_notify_customer_fail']) && ((!isset($this->request->post['ocstore_yk_langdata'][$language['language_id']]['mail_customer_fail_subject']) || !trim($this->request->post['ocstore_yk_langdata'][$language['language_id']]['mail_customer_fail_subject'])))) {
                  $this->error['warning'] = $this->error['error_mail_customer_fail_subject'] = sprintf($this->language->get('error_form'),
                                                                                                          $this->language->get('entry_mail_customer_fail_subject'),
                                                                                                          $this->language->get('tab_emails'));
              }
              if (($this->request->post['ocstore_yk_notify_customer_fail']) && ((!isset($this->request->post['ocstore_yk_langdata'][$language['language_id']]['mail_customer_fail_content']) || !$this->request->post['ocstore_yk_langdata'][$language['language_id']]['mail_customer_fail_content']))) {
                  $this->error['warning'] = $this->error['error_mail_customer_fail_content'] = sprintf($this->language->get('error_form'),
                                                                                                          $this->language->get('entry_mail_customer_fail_content'),
                                                                                                          $this->language->get('tab_emails'));
              }
            }

            if (($this->request->post['ocstore_yk_notify_admin_success']) && ((!isset($this->request->post['ocstore_yk_mail_admin_success_subject']) || !trim($this->request->post['ocstore_yk_mail_admin_success_subject'])))) {
                $this->error['warning'] = $this->error['error_mail_admin_success_subject'] = sprintf($this->language->get('error_form'),
                                                                                                          $this->language->get('entry_mail_admin_success_subject'),
                                                                                                          $this->language->get('tab_emails'));
            }
            if (($this->request->post['ocstore_yk_notify_admin_success']) && ((!isset($this->request->post['ocstore_yk_mail_admin_success_content']) || !$this->request->post['ocstore_yk_mail_admin_success_content']))) {
                $this->error['warning'] = $this->error['error_mail_admin_success_content'] = sprintf($this->language->get('error_form'),
                                                                                                          $this->language->get('entry_mail_admin_success_content'),
                                                                                                          $this->language->get('tab_emails'));
            }
            if (($this->request->post['ocstore_yk_notify_admin_fail']) && ((!isset($this->request->post['ocstore_yk_mail_admin_fail_subject']) || !trim($this->request->post['ocstore_yk_mail_admin_fail_subject'])))) {
                $this->error['warning'] = $this->error['error_mail_admin_fail_subject'] = sprintf($this->language->get('error_form'),
                                                                                                          $this->language->get('entry_mail_admin_fail_subject'),
                                                                                                          $this->language->get('tab_emails'));
            }
            if (($this->request->post['ocstore_yk_notify_admin_fail']) && ((!isset($this->request->post['ocstore_yk_mail_admin_fail_content']) || !$this->request->post['ocstore_yk_mail_admin_fail_content']))) {
                $this->error['warning'] = $this->error['error_mail_admin_fail_content'] = sprintf($this->language->get('error_form'),
                                                                                                          $this->language->get('entry_mail_admin_fail_content'),
                                                                                                          $this->language->get('tab_emails'));
            }
        }

        return !$this->error;
    }

    protected function validatePermission() {
        return $this->user->hasPermission('modify', 'extension/payment/ocstore_yk');
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
            } elseif ($this->config->get($key)) {
                $data[$key] = $this->config->get($key);
            } elseif (isset($info[$key])) {
                $data[$key] = $info[$key];
            } else {
                $data[$key] = null;
            }
        }
        return $data;
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

    protected function makeUrl($route, $url = '') {
        return str_replace('&amp;', '&', $this->url->link($route, $url . '&token=' . $this->session->data['token'], 'SSL'));
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