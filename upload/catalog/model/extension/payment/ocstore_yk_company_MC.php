<?php 
class ModelExtensionPaymentOcstoreYkCompanyMC extends Model {
    private static $_METHOD_CODE = 'ocstore_yk_company_MC';

    public function getMethod($address, $total) {
        $this->load->model('extension/payment/ocstore_yk');
        return $this->model_extension_payment_ocstore_yk->getMethodData($address, $total, self::$_METHOD_CODE);
    }
}
?>