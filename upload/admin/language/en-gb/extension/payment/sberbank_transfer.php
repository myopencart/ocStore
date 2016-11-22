<?php
/**
 * Support:
 * https://opencartforum.com/user/3463-shoputils/
 * http://opencart.shoputils.ru/?route=information/contact
 *
*/
// Heading
$_['heading_title']          = 'Receipt of Sberbank Russian Federation';

// Text
$_['text_payment']           = 'Payment';
$_['text_success']           = 'Module settings "%s" are updated!';
$_['text_sberbank_transfer'] = '<a style="cursor: pointer;" onclick="window.open(\'http://sberbank.ru\');"><img src="view/image/payment/sberbank_transfer.png" alt="Sberbank" title="Sberbank" style="border: 1px solid #EEEEEE;" /></a>';
$_['text_title_default']     = 'Receipt of Sberbank Russian Federation';
$_['text_button_confirm_default'] = 'Confirm Order';
$_['text_edit']              = 'Edit';

// Entry
$_['entry_bank']             = 'Name of the payee';
$_['entry_inn']              = 'Taxpayer identification number';
$_['entry_rs']               = 'Account number of the payee';
$_['entry_bankuser']         = 'Name of the bank of payment receiver';
$_['entry_bik']              = 'BIC';
$_['entry_ks']               = 'Correspondent bank account number of payment receiver';
$_['entry_order_status']     = 'Order Status after payment';
$_['entry_geo_zone']         = 'Geo Zone';
$_['entry_status']           = 'Status';
$_['entry_sort_order']       = 'Sort order';
$_['entry_title']            = 'Name of the payment method';
$_['entry_button_confirm']   = 'Name of the button "button_confirm"';
$_['entry_minimal_order']    = 'Minimum order value';
$_['entry_maximal_order']    = 'Maximum value of the order';

//Help
$_['help_title']             = 'Name of the payment method at the checkout page';
$_['help_button_confirm']    = 'Name of the button \'button_confirm\' at the final step of the checkout page';
$_['help_minimal_order']     = 'If the order amount is less than the specified amount, and the amount is not empty and not equal to zero, this method of payment will not be available when ordering.<br />Example: 190.90';
$_['help_maximal_order']     = 'If the order amount is more than the specified amount, and the amount is not empty and not equal to zero, this method of payment will not be available when ordering.<br />Example: 5000.01';

// Error
$_['error_permission']       = 'You have no rights to manage this module "%s"!';
$_['error_form']             = 'Field "%s" should be filled!';
?>