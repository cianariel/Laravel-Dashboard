(window["webpackJsonp"] = window["webpackJsonp"] || []).push([["/js/framework"],{

/***/ "./resources/js/framework.js":
/*!***********************************!*\
  !*** ./resources/js/framework.js ***!
  \***********************************/
/*! no static exports found */
/***/ (function(module, exports) {

function _typeof(obj) { "@babel/helpers - typeof"; if (typeof Symbol === "function" && typeof Symbol.iterator === "symbol") { _typeof = function _typeof(obj) { return typeof obj; }; } else { _typeof = function _typeof(obj) { return obj && typeof Symbol === "function" && obj.constructor === Symbol && obj !== Symbol.prototype ? "symbol" : typeof obj; }; } return _typeof(obj); }

/*
 |--------------------------------------------------------------------------
 | File:      Framework.js
 | Author:    Antonio Vargas <localhost.80@gmail.com>
 | Copyright: MDRepTime, LLC
 |--------------------------------------------------------------------------
 */
// phpcs:ignoreFile
if (!window.MDRepTime && window.jQuery && window.Cookies && window.axios) {
  // Namespace for framework
  //-----------------------------------//
  window.MDRepTime = function () {
    var instance; // Creates singleton instance
    //-----------------------------------//

    var createInstance = function createInstance(config) {
      var object = new Framework(config);
      return object;
    }; // Framework
    //-----------------------------------//


    var Framework = function Framework() {
      var config = arguments.length > 0 && arguments[0] !== undefined ? arguments[0] : {};
      // Vendor
      //--------------------------------//
      var $ = window.jQuery;
      var jQuery = window.jQuery;
      var Cookies = window.Cookies;
      var axios = window.axios;
      var _stripe = null; // Local Copies

      var dialog = window.dialog; // Properties
      //-----------------------------------//

      this.config = {
        locale: 'en',
        domain: null,
        site_url: null,
        csrf_token: null,
        current_url: null,
        previous_url: null,
        stripe_pk: null
      }; // Setup Ajax
      //-----------------------------------//

      var setupAjax = function setupAjax() {
        jQuery.ajaxSetup({
          headers: {
            'X-CSRF-TOKEN': config.csrf_token ? config.csrf_token : jQuery('meta[name="csrf-token"]').attr('content')
          }
        }); // Axios

        window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';
        window.axios.defaults.headers.common['X-CSRF-TOKEN'] = config.csrf_token ? config.csrf_token : jQuery('meta[name="csrf-token"]').attr('content');
      }; // Check if cookies enabled.
      //----------------------------------------//


      var isCookiesEnabled = function isCookiesEnabled() {
        if (navigator.cookieEnabled) {
          return true;
        }

        return false;
      }; // Mobile checking
      //----------------------------------------//
      // Checks if using android device


      var isAndroid = function isAndroid() {
        var nua = navigator.userAgent;
        return nua.indexOf('Mozilla/5.0') > -1 && nua.indexOf('Android ') > -1 && nua.indexOf('AppleWebKit') > -1 && nua.indexOf('Chrome') === -1;
      }; // Check if using iOS device


      var isIOS = function isIOS() {
        return !!navigator.platform && /iPad|iPhone|iPod/.test(navigator.platform);
      }; // Checks if using a mobile device.


      var isMobile = function isMobile() {
        if (isAndroid() || isIOS()) {
          return true;
        }

        return false;
      };

      var isTouchDevice = function isTouchDevice() {
        return 'ontouchstart' in window || navigator.maxTouchPoints > 0 || navigator.msMaxTouchPoints > 0;
      }; // Initializer
      //-----------------------------------//


      this.init = function (config) {
        if (_typeof(config) == 'object') {
          this.config = config;
        }

        setupAjax(); // Setup AJAX with CSRF Headers

        _stripe = Stripe(this.config.stripe_pk, {
          locale: this.config.locale
        });

        if (isMobile()) {
          jQuery('.body, body').addClass('mobile-device');
        }

        if (isTouchDevice()) {
          jQuery('.body, body').addClass('touch-device');
        }

        jQuery('html').removeClass('no-js').addClass('js');
      }; // Stripe instance
      //-----------------------------------//


      this.stripe = function () {
        return _stripe;
      }; // Url Hash


      this.getUriHash = function () {
        return window.location.hash;
      };

      this.setUriHash = function () {
        var hash = arguments.length > 0 && arguments[0] !== undefined ? arguments[0] : '';

        if (hash.length) {
          window.location.hash = hash;
        }
      }; // Dialogs


      var _dialog = function _dialog(title, message) {
        var buttons = arguments.length > 2 && arguments[2] !== undefined ? arguments[2] : false;
        var dialog_modal = $('#dialog-modal');
        var dialog_footer = dialog_modal.find('.modal-footer');
        var html = '<button id="btn-modal-close" type="button" class="btn btn-primary" data-dismiss="modal">Close</button>';

        if ($.type(buttons) == 'String') {
          if (buttons.length !== 0) {
            html = buttons;
          }

          dialog_modal.find('.modal-footer').html(html).promise().done(function () {
            dialog(title, message);
          });
        } else {
          dialog_modal.find('.modal-footer').html(html).promise().done(function () {
            dialog(title, message);
          });
        }
      }; // AJAX Requests Definitions
      //-----------------------------------//
      // AJAX GET


      var get = function get(url, data) {
        var dataType = arguments.length > 2 && arguments[2] !== undefined ? arguments[2] : 'json';
        var success = arguments.length > 3 && arguments[3] !== undefined ? arguments[3] : function (response) {};
        var error = arguments.length > 4 && arguments[4] !== undefined ? arguments[4] : function (error) {};
        var finally_cb = arguments.length > 5 && arguments[5] !== undefined ? arguments[5] : function () {};
        var timeout = arguments.length > 6 && arguments[6] !== undefined ? arguments[6] : 0;
        return request(url, 'get', data, dataType, success, error, finally_cb, timeout);
      }; // AJAX POST


      var post = function post(url, data) {
        var dataType = arguments.length > 2 && arguments[2] !== undefined ? arguments[2] : 'json';
        var success = arguments.length > 3 && arguments[3] !== undefined ? arguments[3] : function (response) {};
        var error = arguments.length > 4 && arguments[4] !== undefined ? arguments[4] : function (error) {};
        var finally_cb = arguments.length > 5 && arguments[5] !== undefined ? arguments[5] : function () {};
        var timeout = arguments.length > 6 && arguments[6] !== undefined ? arguments[6] : 0;
        return request(url, 'post', data, dataType, success, error, finally_cb, timeout);
      }; // AJAX PUT


      var put = function put(url, data) {
        var dataType = arguments.length > 2 && arguments[2] !== undefined ? arguments[2] : 'json';
        var success = arguments.length > 3 && arguments[3] !== undefined ? arguments[3] : function (response) {};
        var error = arguments.length > 4 && arguments[4] !== undefined ? arguments[4] : function (error) {};
        var finally_cb = arguments.length > 5 && arguments[5] !== undefined ? arguments[5] : function () {};
        var timeout = arguments.length > 6 && arguments[6] !== undefined ? arguments[6] : 0;
        return request(url, 'put', data, dataType, success, error, finally_cb, timeout);
      }; // AJAX DELETE


      var deleteRequest = function deleteRequest(url, data) {
        var dataType = arguments.length > 2 && arguments[2] !== undefined ? arguments[2] : 'json';
        var success = arguments.length > 3 && arguments[3] !== undefined ? arguments[3] : function (response) {};
        var error = arguments.length > 4 && arguments[4] !== undefined ? arguments[4] : function (error) {};
        var finally_cb = arguments.length > 5 && arguments[5] !== undefined ? arguments[5] : function () {};
        var timeout = arguments.length > 6 && arguments[6] !== undefined ? arguments[6] : 0;
        return request(url, 'delete', data, dataType, success, error, finally_cb, timeout);
      }; // AJAX Request


      var request = function request(url, method, data) {
        var dataType = arguments.length > 3 && arguments[3] !== undefined ? arguments[3] : 'json';
        var success = arguments.length > 4 && arguments[4] !== undefined ? arguments[4] : function (response) {};
        var error = arguments.length > 5 && arguments[5] !== undefined ? arguments[5] : function (error) {};
        var finally_cb = arguments.length > 6 && arguments[6] !== undefined ? arguments[6] : function () {};
        var timeout = arguments.length > 7 && arguments[7] !== undefined ? arguments[7] : 0;
        return axios({
          url: url,
          method: method,
          data: data,
          responseType: dataType,
          timeout: timeout
        }).then(success)["catch"](error)["finally"](finally_cb);
      }; // File upload


      var fileupload = function fileupload(url, method, data) {
        var dataType = arguments.length > 3 && arguments[3] !== undefined ? arguments[3] : 'json';
        var success = arguments.length > 4 && arguments[4] !== undefined ? arguments[4] : function (response) {};
        var error = arguments.length > 5 && arguments[5] !== undefined ? arguments[5] : function (error) {};
        var finally_cb = arguments.length > 6 && arguments[6] !== undefined ? arguments[6] : function () {};
        var onUploadProgress = arguments.length > 7 && arguments[7] !== undefined ? arguments[7] : function () {};
        var timeout = arguments.length > 8 && arguments[8] !== undefined ? arguments[8] : 0;
        return axios({
          headers: {
            'Content-Type': 'multipart/form-data',
            'X-Requested-With': 'XMLHttpRequest',
            'X-CSRF-TOKEN': config.csrf_token ? config.csrf_token : jQuery('meta[name="csrf-token"]').attr('content')
          },
          url: url,
          method: method,
          data: data,
          responseType: dataType,
          timeout: timeout
        }).then(success)["catch"](error)["finally"](finally_cb);
      };

      var postWithFile = function postWithFile(url, data) {
        var dataType = arguments.length > 2 && arguments[2] !== undefined ? arguments[2] : 'json';
        var success = arguments.length > 3 && arguments[3] !== undefined ? arguments[3] : function (response) {};
        var error = arguments.length > 4 && arguments[4] !== undefined ? arguments[4] : function (error) {};
        var finally_cb = arguments.length > 5 && arguments[5] !== undefined ? arguments[5] : function () {};
        var onUploadProgress = arguments.length > 6 && arguments[6] !== undefined ? arguments[6] : function () {};
        var timeout = arguments.length > 7 && arguments[7] !== undefined ? arguments[7] : 0;
        return axios({
          headers: {
            'Content-Type': 'multipart/form-data',
            'X-Requested-With': 'XMLHttpRequest',
            'X-CSRF-TOKEN': config.csrf_token ? config.csrf_token : jQuery('meta[name="csrf-token"]').attr('content')
          },
          url: url,
          method: 'post',
          data: data,
          responseType: dataType,
          timeout: timeout
        }).then(success)["catch"](error)["finally"](finally_cb);
      }; // Public Methods
      //-----------------------------------//


      this.dialog = _dialog;
      this.request = request;
      this.get = get;
      this.post = post;
      this["delete"] = deleteRequest;
      this.fileupload = fileupload;
      this.postWithFile = postWithFile;
      this.isAndroid = isAndroid;
      this.isIOS = isIOS;
      this.isMobile = isMobile;
      this.isTouchDevice = isTouchDevice;
      this.isCookiesEnabled = isCookiesEnabled; // Redirect to another URL

      this.redirect = function (url) {
        window.location.href = url;
      }; // Refresh page


      this.refresh = function () {
        window.location.reload(true);
      }; // Open in new tab


      this.open = function (url) {
        var target = arguments.length > 1 && arguments[1] !== undefined ? arguments[1] : '_blank';
        window.open(url, target);
      };
    }; // Return singleton instance


    return {
      getInstance: function getInstance(config) {
        if (!instance) {
          instance = createInstance(config);
        }

        return instance;
      }
    };
  }();
}

/***/ }),

/***/ 1:
/*!*****************************************!*\
  !*** multi ./resources/js/framework.js ***!
  \*****************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

module.exports = __webpack_require__(/*! /Volumes/500GB/www/app.mdreptime.com/resources/js/framework.js */"./resources/js/framework.js");


/***/ })

},[[1,"/js/manifest"]]]);