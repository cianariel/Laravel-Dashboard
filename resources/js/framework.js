/*
 |--------------------------------------------------------------------------
 | File:      Framework.js
 | Author:    Antonio Vargas <localhost.80@gmail.com>
 | Copyright: MDRepTime, LLC
 |--------------------------------------------------------------------------
 */
// phpcs:ignoreFile
if(!window.MDRepTime && window.jQuery && window.Cookies && window.axios) {

    // Namespace for framework
    //-----------------------------------//
    window.MDRepTime = (function(){

        let instance;

        // Creates singleton instance
        //-----------------------------------//
        let createInstance = function (config) {

            let object = new Framework(config);
            return object;
        };

        // Framework
        //-----------------------------------//
        let Framework = function (config = {}) {

            // Vendor
            //--------------------------------//
            let $ = window.jQuery;
            let jQuery = window.jQuery;
            let Cookies = window.Cookies;
            let axios = window.axios;
            let _stripe = null;

            // Local Copies
            let dialog = window.dialog;

            // Properties
            //-----------------------------------//
            this.config = {
                locale: 'en',
                domain: null,
                site_url: null,
                csrf_token: null,
                current_url: null,
                previous_url: null,
                stripe_pk: null,
            };


            // Setup Ajax
            //-----------------------------------//
            let setupAjax = function() {
                jQuery.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': config.csrf_token? config.csrf_token : jQuery('meta[name="csrf-token"]').attr('content')
                    },
                });

                // Axios
                window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';
                window.axios.defaults.headers.common['X-CSRF-TOKEN'] = config.csrf_token? config.csrf_token : jQuery('meta[name="csrf-token"]').attr('content');
            };


            // Check if cookies enabled.
            //----------------------------------------//
            let isCookiesEnabled = function() {

                if(navigator.cookieEnabled) {
                    return true;
                }

                return false;
            }


            // Mobile checking
            //----------------------------------------//
            // Checks if using android device
            let isAndroid = function() {

                let nua = navigator.userAgent;
                return (nua.indexOf('Mozilla/5.0') > -1 && nua.indexOf('Android ') > -1 && nua.indexOf('AppleWebKit') > -1 && nua.indexOf('Chrome') === -1);
            }

            // Check if using iOS device
            let isIOS = function() {
                return !!navigator.platform && /iPad|iPhone|iPod/.test(navigator.platform);
            }

            // Checks if using a mobile device.
            let isMobile = function() {

                if(isAndroid() || isIOS()) {
                    return true;
                }

                return false;
            }

            let isTouchDevice = function() {

                return (('ontouchstart' in window)
                      || (navigator.maxTouchPoints > 0)
                      || (navigator.msMaxTouchPoints > 0));
            }

            // Initializer
            //-----------------------------------//
            this.init = function(config) {

                if(typeof config == 'object') {
                    this.config = config;
                }

                setupAjax(); // Setup AJAX with CSRF Headers

                _stripe = Stripe(this.config.stripe_pk, {locale: this.config.locale});

                if(isMobile()) {
                    jQuery('.body, body').addClass('mobile-device');
                }

                if(isTouchDevice()) {
                    jQuery('.body, body').addClass('touch-device');
                }

                jQuery('html').removeClass('no-js').addClass('js');
            };

            // Stripe instance
            //-----------------------------------//
            this.stripe = function() {
                return _stripe;
            }

            // Dialogs
            let _dialog = function(title, message, buttons=false) {
                let dialog_modal = $('#dialog-modal');
                let dialog_footer = dialog_modal.find('.modal-footer');
                let html = '<button id="btn-modal-close" type="button" class="btn btn-primary" data-dismiss="modal">Close</button>';

                if($.type(buttons) == 'String') {

                    if(buttons.length !== 0) {
                        html = buttons;
                    }

                    dialog_modal.find('.modal-footer').html(html).promise().done(function(){
                        dialog(title, message);
                    });
                } else {
                    dialog_modal.find('.modal-footer').html(html).promise().done(function(){
                        dialog(title, message);
                    });
                }
            }

            // AJAX Requests Definitions
            //-----------------------------------//

            // AJAX GET
            let get = function(url, data, dataType='json', success=function(response){}, error=function(error){}, finally_cb=function(){}, timeout=0) {
                return request(url, 'get', data, dataType, success, error, finally_cb, timeout);
            };

            // AJAX POST
            let post = function(url, data, dataType='json', success=function(response){}, error=function(error){}, finally_cb=function(){}, timeout=0) {
                return request(url, 'post', data, dataType, success, error, finally_cb, timeout);
            };

            // AJAX PUT
            let put = function(url, data, dataType='json', success=function(response){}, error=function(error){}, finally_cb=function(){}, timeout=0) {
                return request(url, 'put', data, dataType, success, error, finally_cb, timeout);
            };

            // AJAX DELETE
            let deleteRequest = function(url, data, dataType='json', success=function(response){}, error=function(error){}, finally_cb=function(){}, timeout=0) {
                return request(url, 'delete', data, dataType, success, error, finally_cb, timeout);
            };

            // AJAX Request
            let request = function(url, method, data, dataType='json', success=function(response){}, error=function(error){}, finally_cb=function(){}, timeout=0) {
                return axios({
                    url: url,
                    method: method,
                    data: data,
                    responseType: dataType,
                    timeout: timeout,
                }).then(success)
                  .catch(error)
                  .finally(finally_cb);
            };

            // File upload
            let fileupload = function(url, method, data, dataType='json', success=function(response){}, error=function(error){}, finally_cb=function(){},onUploadProgress=function(){}, timeout=0) {
                return axios({
                    headers: {
                      'Content-Type': 'multipart/form-data',
                      'X-Requested-With': 'XMLHttpRequest',
                      'X-CSRF-TOKEN': config.csrf_token? config.csrf_token : jQuery('meta[name="csrf-token"]').attr('content')
                    },
                    url: url,
                    method: method,
                    data: data,
                    responseType: dataType,
                    timeout: timeout,
                }).then(success)
                  .catch(error)
                  .finally(finally_cb);
            };

            let postWithFile = function(url, data, dataType='json', success=function(response){}, error=function(error){}, finally_cb=function(){},onUploadProgress=function(){}, timeout=0) {
                return axios({
                    headers: {
                      'Content-Type': 'multipart/form-data',
                      'X-Requested-With': 'XMLHttpRequest',
                      'X-CSRF-TOKEN': config.csrf_token? config.csrf_token : jQuery('meta[name="csrf-token"]').attr('content')
                    },
                    url: url,
                    method: 'post',
                    data: data,
                    responseType: dataType,
                    timeout: timeout,
                }).then(success)
                  .catch(error)
                  .finally(finally_cb);
            };

            // Public Methods
            //-----------------------------------//
            this.dialog = _dialog;
            this.request = request;
            this.get = get;
            this.post = post;
            this.delete = deleteRequest;
            this.fileupload = fileupload;
            this.postWithFile = postWithFile;
            this.isAndroid = isAndroid;
            this.isIOS = isIOS;
            this.isMobile = isMobile;
            this.isTouchDevice = isTouchDevice;
            this.isCookiesEnabled = isCookiesEnabled;

            // Redirect to another URL
            this.redirect = function(url)
            {
                window.location.href = url;
            };

            // Refresh page
            this.refresh = function()
            {
                window.location.reload(true);
            };

            // Open in new tab
            this.open = function(url, target='_blank') {
                window.open(url, target);
            }

        };

        // Return singleton instance
        return {
            getInstance: function (config) {
                if (!instance) {
                    instance = createInstance(config);
                }
                return instance;
            }
        };

    })();
}