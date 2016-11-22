<?php
/**
 * Support:
 * https://opencartforum.com/user/3463-shoputils/
 * http://opencart.shoputils.ru/?route=information/contact
 *
*/
class ControllerExtensionPaymentSberBankTransfer extends Controller {
    private $error = array();
    private $version = '2.0';

    public function index() {
        $this->load->language('extension/payment/sberbank_transfer');
        $this->load->model('localisation/language');

        if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
            $this->load->model('setting/setting');
            $this->model_setting_setting->editSetting('sberbank_transfer', $this->request->post);
            $this->session->data['success'] = sprintf($this->language->get('text_success'), $this->language->get('heading_title'));
            $this->response->redirect($this->makeUrl('extension/extension', 'type=payment'));
        }

        $this->load->model('localisation/order_status');
        $this->load->model('localisation/geo_zone');
        $this->document->setTitle($this->language->get('heading_title'));

        $languages = $this->model_localisation_language->getLanguages();

        $data['heading_title']        = $this->language->get('heading_title');

        $data['button_save']          = $this->language->get('button_save');
        $data['button_cancel']        = $this->language->get('button_cancel');

        $data['text_edit']            = $this->language->get('text_edit');
        $data['text_title_default']   = $this->language->get('text_title_default');
        $data['text_button_confirm_default']  = $this->language->get('text_button_confirm_default');
        $data['text_enabled']         = $this->language->get('text_enabled');
        $data['text_disabled']        = $this->language->get('text_disabled');
        $data['text_all_zones']       = $this->language->get('text_all_zones');

        $data['entry_bank']           = $this->language->get('entry_bank');
        $data['entry_inn']            = $this->language->get('entry_inn');
        $data['entry_rs']             = $this->language->get('entry_rs');
        $data['entry_bankuser']       = $this->language->get('entry_bankuser');
        $data['entry_bik']            = $this->language->get('entry_bik');
        $data['entry_ks']             = $this->language->get('entry_ks');
        $data['entry_title']          = $this->language->get('entry_title');
        $data['entry_button_confirm'] = $this->language->get('entry_button_confirm');
        $data['entry_maximal_order']  = $this->language->get('entry_maximal_order');
        $data['entry_minimal_order']  = $this->language->get('entry_minimal_order');
        $data['entry_order_status']   = $this->language->get('entry_order_status');
        $data['entry_geo_zone']       = $this->language->get('entry_geo_zone');
        $data['entry_status']         = $this->language->get('entry_status');
        $data['entry_sort_order']     = $this->language->get('entry_sort_order');

        $data['help_title']           = $this->language->get('help_title');
        $data['help_button_confirm']  = $this->language->get('help_button_confirm');
        $data['help_maximal_order']   = $this->language->get('help_maximal_order');
        $data['help_minimal_order']   = $this->language->get('help_minimal_order');

        $data['action']         = $this->makeUrl('extension/payment/sberbank_transfer');
        $data['cancel']         = $this->makeUrl('extension/extension', 'type=payment');
        $data['version']        = $this->version;
        $data['permission']     = $this->validatePermission();
        $data['order_statuses'] = $this->model_localisation_order_status->getOrderStatuses();
        $data['geo_zones']      = $this->model_localisation_geo_zone->getGeoZones();
        $data['languages']      = $languages;

        $data['error_warning'] = isset($this->error['warning']) ? $this->error['warning'] : '';

        foreach ($languages as $language) {
            $data['error_bank']      = isset($this->error['bank']) ? $this->error['bank'] : '';
            $data['error_inn']       = isset($this->error['inn']) ? $this->error['inn'] : '';
            $data['error_rs']        = isset($this->error['rs']) ? $this->error['rs'] : '';
            $data['error_bankuser']  = isset($this->error['bankuser']) ? $this->error['bankuser'] : '';
            $data['error_bik']       = isset($this->error['bik']) ? $this->error['bik'] : '';
            $data['error_ks']        = isset($this->error['ks']) ? $this->error['ks'] : '';
            $data['error_title']     = isset($this->error['title']) ? $this->error['title'] : '';
            $data['error_button_confirm'] = isset($this->error['button_confirm']) ? $this->error['button_confirm'] : '';
        }

        $data['breadcrumbs'][] = array(
            'text'  => $this->language->get('text_home'),
            'href'  => $this->makeUrl('common/dashboard')
        );

        $data['breadcrumbs'][] = array(
            'text'  => $this->language->get('text_payment'),
            'href'  => $this->makeUrl('extension/extension', 'type=payment')
        );

        $data['breadcrumbs'][] = array(
            'text'  => $this->language->get('heading_title'),
            'href'  => $this->makeUrl('extension/payment/sberbank_transfer')
        );

        foreach ($languages as $language) {
            $data = array_merge($data, $this->_updateData(array(
                'sberbank_transfer_bank_' . $language['language_id'],
                'sberbank_transfer_bankuser_' . $language['language_id'],
                'sberbank_transfer_title_' . $language['language_id'],
                'sberbank_transfer_button_confirm_' . $language['language_id']
            )));
        }


        $data = array_merge($data, $this->_updateData(array(
            'sberbank_transfer_inn',
            'sberbank_transfer_rs',
            'sberbank_transfer_bik',
            'sberbank_transfer_ks',
            'sberbank_transfer_minimal_order',
            'sberbank_transfer_maximal_order',
            'sberbank_transfer_order_status_id',
            'sberbank_transfer_geo_zone_id',
            'sberbank_transfer_status',
            'sberbank_transfer_sort_order'
        )));

        $data['header']       = $this->load->controller('common/header');
        $data['column_left']  = $this->load->controller('common/column_left');
        $data['footer']       = $this->load->controller('common/footer');

        $this->response->setOutput($this->load->view('extension/payment/sberbank_transfer', $data));
    }

    protected function validate() {
        if (!$this->validatePermission()) {
          $this->error['warning'] = $this->language->get('error_permission');
        } else {

            if (!$this->request->post['sberbank_transfer_inn']) {
                $this->error['inn'] = $this->error['warning'] = sprintf($this->language->get('error_form'), $this->language->get('entry_inn'));
            }
            if (!$this->request->post['sberbank_transfer_rs']) {
                $this->error['rs'] = $this->error['warning'] = sprintf($this->language->get('error_form'), $this->language->get('entry_rs'));
            }
            if (!$this->request->post['sberbank_transfer_bik']) {
                $this->error['bik'] = $this->error['warning'] = sprintf($this->language->get('error_form'), $this->language->get('entry_bik'));
            }
            if (!$this->request->post['sberbank_transfer_ks']) {
                $this->error['ks'] = $this->error['warning'] = sprintf($this->language->get('error_form'), $this->language->get('entry_ks'));
            }

            foreach ($this->model_localisation_language->getLanguages() as $language) {
                if (!$this->request->post['sberbank_transfer_bank_' . $language['language_id']]) {
                    $this->error['bank'] = $this->error['warning'] = sprintf($this->language->get('error_form'), $this->language->get('entry_bank'));
                }
                if (!$this->request->post['sberbank_transfer_bankuser_' . $language['language_id']]) {
                    $this->error['bankuser'] = $this->error['warning'] = sprintf($this->language->get('error_form'), $this->language->get('entry_bankuser'));
                }
                if (!$this->request->post['sberbank_transfer_title_' . $language['language_id']]) {
                    $this->error['title'] = $this->error['warning'] = sprintf($this->language->get('error_form'), $this->language->get('entry_title'));
                }
                if (!$this->request->post['sberbank_transfer_button_confirm_' . $language['language_id']]) {
                    $this->error['button_confirm'] = $this->error['warning'] = sprintf($this->language->get('error_form'), $this->language->get('entry_button_confirm'));
                }
            }
        }

        return !$this->error;
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

    protected function validatePermission() {
        return $this->user->hasPermission('modify', 'extension/payment/sberbank_transfer');
    }

    protected function makeUrl($route, $url = '') {
        return str_replace('&amp;', '&', $this->url->link($route, $url.'&token=' . $this->session->data['token'], 'SSL'));
    }
}
?>