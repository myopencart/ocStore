
<div style="display: none" id="checkout">
    <div id="checkout_wrapper" ></div>
</div>       
<script type="text/javascript">
var checkoutStyles = {
<?php echo $styles; ?>
	};

    function checkoutInit(url) {
        $ipsp('checkout').scope(function() {
            //this.setModal(true);
			this.setCssStyle(checkoutStyles);
            this.setCheckoutWrapper('#checkout_wrapper');
            this.addCallback(__DEFAULTCALLBACK__);
            this.action('decline',function(data,type){
                console.log(data);
            });
            this.action('show', function(data) {
                $('#checkout_loader').remove();
                $('#checkout').show();
            });
            this.action('hide', function(data) {
                $('#checkout').hide();
            });
            this.action('resize', function(data) {
                $('#checkout_wrapper').height(data.height);
            });
            this.loadUrl(url);
        });
    };
    var button = $ipsp.get("button");
    button.setMerchantId(<?php echo $oplata_args['merchant_id']; ?>);
    button.setAmount(<?php echo $oplata_args['amount']; ?>, '<?php echo $oplata_args['currency']; ?>', true);
    button.setHost('api.fondy.eu');
    button.addParam('order_desc','<?php echo $oplata_args['order_desc']; ?>');
    button.addParam('order_id','<?php echo $oplata_args['order_id']; ?>');
    button.addParam('lang','<?php echo $oplata_args['lang']; ?>');
    button.addParam('server_callback_url','<?php echo $oplata_args['server_callback_url']; ?>');
    button.addParam('sender_email','<?php echo $oplata_args['sender_email']; ?>');
    button.setResponseUrl('<?php echo $oplata_args['response_url'] ?>');
        checkoutInit(button.getUrl());
</script>