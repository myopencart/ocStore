<?php
class ControllerExtensionPaymentOcstoreYkCompanyAC extends Controller {
    public function index() {
        $this->response->redirect($this->url->link('extension/payment/ocstore_yk', 'token=' . $this->session->data['token'], 'SSL'));
    }
}