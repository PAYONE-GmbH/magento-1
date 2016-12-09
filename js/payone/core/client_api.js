/**
 * Namespaces
 * @type {Object}
 */
var PAYONE = {};
PAYONE.Handler = {};
PAYONE.Service = {};
PAYONE.Validation = {};

/**
 * A Gatewaay to send Requests to Payone
 *
 * @param data
 * @constructor
 */
PAYONE.Gateway = function (config, callback) {
    this.config = config;
    this.callback = callback;
    this.request = '';
    this.response = '';

    this.call = function (data) {

        this.initRequest(data);

        // Options
        var options = {
            callback_function_name:'PAYONE.Callback.invoke'
        };

        // AJAX Callback
        PAYONE.Callback.gateway = this;
        PAYONE.Callback.callback = this.callback;

        // Send Request to PAYONE
        var request = new PayoneRequest(this.request, options);
        request.checkAndStore();
    };

    this.initRequest = function (data) {
        this.request = data;

        // Add Default Parameters
        for (var key in this.config) {
            this.request[key] = this.config[key];
        }

        // init Request
        this.request.callback_method = 'PAYONE.Callback.invoke';
    }

    this.setResponse = function (response) {
        this.response = response;
    };

    this.getLastResponse = function () {
        return this.response;
    };

    this.getLastRequest = function () {
        return this.request;
    };

    this.setCallback = function (callback) {
        this.callback = callback;
    };
};

/**
 * A Callback Object that replaces default PAYONE Callback and improves Callback Handling
 *
 * @type {Object}
 */
PAYONE.Callback = {
    /**
     * @type {Object} reference to the Gateway Object
     */
    gateway:'',
    /**
     * @type callback should be a valid Callback
     */
    callback:'',

    /**
     * Callback entry method
     *
     * @param response
     */
    invoke:function (response) {
        this.gateway.setResponse(response);

        document.getElementsByTagName("body")[0].removeChild(payoneCallbackFunction.payoneScript);

        var callback = this.callback;
        callback(response);
    }
};
