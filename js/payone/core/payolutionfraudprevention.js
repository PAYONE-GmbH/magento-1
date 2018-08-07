var PayolutionFraudPrevention = Class.create();

PayolutionFraudPrevention.prototype = {
    payments: [
        'payone_payolution',
        'payone_payolution_invoicing',
        'payone_payolution_debit',
        'payone_payolution_installment'
    ],
    iframeBlock: null,
    options: {
        onlineMetrixUrl: '',
        onlineMetrixJsUrl: '',
        onlineMetrixIframeUrl: ''
    },

    //init method
    initialize: function(options) {
        this.options = options;
        this.clearIframeBlock();
        if (this.payolutionPaymentChosen()) {
            this.addScriptToHeader();
            this.addFraudPreventionIFrame();
        }
    },

    addScriptToHeader: function() {
        var $head = $$('head').first();

        $head.insert(this.createScript());
    },

    createScript: function() {
        var $script = new Element(
            'script',
            {
                'src': this.options.onlineMetrixJsUrl,
                'type': 'text/javascript'
            }
        );

        return $script;
    },

    addFraudPreventionIFrame: function() {
        var $iframeBlock = this.getIframeBlock();
        $$('body').first().insert($iframeBlock);
    },

    getIframeBlock: function() {
        if (this.iframeBlock === null) {
            this.createIframeBlock();
        }

        return this.iframeBlock;
    },

    clearIframeBlock: function() {
        if (this.iframeBlock === null) {
            $$('iframe[src="' + this.options.onlineMetrixUrl + '"]').each(function($element) {
                $element.up().remove();
            });
            return;
        }
        this.iframeBlock.remove();
        this.iframeBlock = null;
    },

    createIframeBlock: function() {
        var $iframe = this.createIframe();
        this.iframeBlock = new Element('noscript');
        this.iframeBlock.update($iframe);
    },

    createIframe: function() {
        var $iframe = new Element(
            'iframe',
            {
                'src': this.options.onlineMetrixUrl
            }
        );

        return $iframe.setStyle({
            width: '100px',
            height: '100px',
            border: 0,
            position: 'absolute',
            top: '5000px'
        });
    },

    payolutionPaymentChosen: function() {
        var isPayolution = false;
        this.payments.each(function(payolutionPayment) {
            if (payolutionPayment == payment.currentMethod) {
                isPayolution = true;
                throw $break;
            }
        });

        return isPayolution;
    }
};
