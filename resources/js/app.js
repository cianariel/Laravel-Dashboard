/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

// phpcs:ignoreFile

try {
    require('./bootstrap');
} catch (e) {
}

try {
    window.Cookies = require('js-cookie');
} catch (e) {}

try {
    window.matchHeight = require('jquery-match-height');
} catch (e) {}

try {
    window.outSideEvents = require('jquery-outside-events');
} catch (e) {}

try {
    window.slick = require('slick-carousel');
} catch (e) {}

try {
    window.owlCarousel = require('owl.carousel');
} catch (e) {}

try {
    window.autosize = require('autosize');
} catch (e) {}

try {
    window.daterangepicker = require('daterangepicker');
} catch (e) {}

try {
    window.parsley = require('parsleyjs');
} catch (e) {}

try {
    require('inputmask');

    Inputmask.extendDefaults({
        autoUnmask: true,
        greedy: false,
        removeMaskOnSubmit: true
    });
} catch (e) {}

try {
    window.Chart = require('chart.js');
} catch (e) {}

try {
    require('@stripe/stripe-js');
} catch (e) {}

try {
    window.summernote = require('summernote');
} catch (e) {}


try {
    window.feather = require('feather-icons')
} catch (e) {}

try {
    window.moment = require('moment');
} catch (e) {}


// JQuery Extends
//-----------------------------------------------//
(function ($) {

    $.fn.autoCompleteFix = function (opt) {
        var ro = 'readonly', settings = $.extend({
            attribute : 'autocomplete',
            trigger : {
                disable : ["off"],
                enable : ["on"]
            },
            focus : function () {
                $(this).removeAttr(ro);
            },
            force : false
        }, opt);

        $(this).each(function (i, el) {
            el = $(el);

            if (el.is('form')) {
                var force = (-1 !== $.inArray(el.attr(settings.attribute), settings.trigger.disable))
                el.find('input[type="email"]').autoCompleteFix({force:force});
            } else {
                var disabled = -1 !== $.inArray(el.attr(settings.attribute), settings.trigger.disable);
                var enabled = -1 !== $.inArray(el.attr(settings.attribute), settings.trigger.enable);
                if (settings.force && !enabled || disabled) {
                    el.attr(ro, ro).focus(settings.focus).val("");
                }
            }
        });
    };

    // Class changed event
    (function ( func ) {
        $.fn.addClass = function (n) {
    // replace the existing function on $.fn
            this.each(function (i) {
    // for each element in the collection
                var $this = $(this); // 'this' is DOM element in this context
                var prevClasses = this.getAttribute('class'); // note its original classes
                var classNames = $.isFunction(n) ? n(i, prevClasses) : n.toString(); // retain function-type argument support
                $.each(classNames.split(/\s+/), function (index, className) {
    // allow for multiple classes being added
                    if ( !$this.hasClass(className) ) { // only when the class is not already present
                        func.call($this, className); // invoke the original function to add the class
                        $this.trigger('classAdded', className); // trigger a classAdded event
                    }
                });
                prevClasses != this.getAttribute('class') && $this.trigger('classChanged'); // trigger the classChanged event
            });
            return this; // retain jQuery chainability
        }
    })($.fn.addClass); // pass the original function as an argument

    (function ( func ) {
        $.fn.removeClass = function (n) {
            this.each(function (i) {
                var $this = $(this);
                var prevClasses = this.getAttribute('class');
                var classNames = $.isFunction(n) ? n(i, prevClasses) : n.toString();
                $.each(classNames.split(/\s+/), function (index, className) {
                    if ( $this.hasClass(className) ) {
                        func.call($this, className);
                        $this.trigger('classRemoved', className);
                    }
                });
                prevClasses != this.getAttribute('class') && $this.trigger('classChanged');
            });
            return this;
        }
    })($.fn.removeClass);


})(jQuery);



// Other
//-----------------------------------------------//
// Fix String Lines.
window.nl2br = function (str, is_xhtml) {
     var breakTag = (is_xhtml || typeof is_xhtml === 'undefined') ? '<br />' : '<br>';
     return (str + '').replace(/([^>\r\n]?)(\r\n|\n\r|\r|\n)/g, '$1' + breakTag + '$2');
}

// Positions (Top, Left, Right, Bottom)
//----------------------------------------------//
window.getPosition = function (el) {
    var element = el;
    var position = element.position();

    return {
        'top': position.top,
        'right': position.left + element.outerWidth(),
        'bottom': position.top + element.outerHeight(),
        'left': position.left,
    };
};

// Offset (Top, Left, Right, Bottom)
//----------------------------------------------//
window.getOffset = function (el) {
    var element = el;
    var offset = element.offset();

    return {
        'top': offset.top,
        'right': offset.left + element.outerWidth(),
        'bottom': offset.top + element.outerHeight(),
        'left': offset.left,
    };
};

// Dialog
//-----------------------------------------------//
window.dialog = function (title, message) {

    let dialog_modal = jQuery('#dialog-modal');

    if (dialog_modal.length !== 0) {
        dialog_modal.modal({
            backdrop: true,
            keyboard: true,
            focus: true,
            show: false
        });

        dialog_modal.find('#dialog-title').text(title);
        dialog_modal.find('#dialog-content').text(message);
        dialog_modal.modal('show');
    }
}


// Pretty Checkbox
//-----------------------------------------------//
window.mdPrettyCheckboxes = function ($) {
    var checkboxes = $('input[type=checkbox]');
    checkboxes.each(function (i) {
        var el = $(this);
        var checked = el.is(':checked');

        if (typeof el.attr('data-prettycheckbox') == 'undefined') {
            el.attr('data-prettycheckbox', 'true');

            if (checked) {
                el.wrap('<div class="md-checkbox" data-checked="true"><i class="fas fa-check-square"></i></div>');
            } else {
                el.wrap('<div class="md-checkbox" data-checked="false"><i class="far fa-square"></i></div>');
            }
        }

    }).promise().done(function () {
        $('.md-checkbox').on('click touchend', function (e) {
            e.preventDefault();
            e.stopPropagation();

            var el = $(this);
            var checked = el.data('checked');

            if (checked) {
                // uncheck
                el.data('checked', false);
                el.find('i').removeClass('fas').removeClass('fa-check-square');
                el.find('i').addClass('far').addClass('fa-square');
                el.find('input').prop('checked', false).trigger('change');
            } else {
                // check
                el.data('checked', true);
                el.find('i').addClass('fas').addClass('fa-check-square');
                el.find('i').removeClass('far').removeClass('fa-square');
                el.find('input').prop('checked', true).trigger('change');
            }
        });
    });
}

// Component Multiselect Buttons Handler
//-----------------------------------------------//
window.mdMultiSelectInputComponent = function ($) {

    let components = $('.md-multi-select');

    if (components.length != 0) {
        components.each(function (i) {

            let component = $(this);
            let form = component.closest('.form');
            let input = component.find('input[type="hidden"]');
            let options = component.find('.btn-option');
            let data = [];

            if (input.val().length != 0) {
                let values = input.val();
                    values = values.split(',');

                    options.each(function (index) {
                        let option = $(this);
                        let value = option.data('value');
                        let icon = option.find('.icon');

                        values.forEach(function (item, i) {

                            if (value == item) {
                                option.data('selected', true)
                                option.addClass('selected');
                                icon.removeClass('hidden');
                            }
                        });
                    });
            }


            options.on('click touchend', function (e) {

                let option = $(this);
                let icon = option.find('.icon');
                let selected = option.data('selected');
                data = [];

                if (selected == false) {
                    option.data('selected', true)
                    option.addClass('selected');
                    icon.removeClass('hidden');
                } else {
                    option.data('selected', false);
                    option.removeClass('selected');
                    icon.addClass('hidden');
                }

                options.each(function (index) {

                    let option = $(this);
                    let selected = option.data('selected');
                    let value = option.data('value');

                    if (selected) {
                        data.push(value)
                    }

                }).promise().done(function () {
                    if (data.length != 0) {
                        input.val(data.join(','));
                        form.trigger('change');
                    }
                });
            });
        });
    }
}

// Form Auto Submit Handler
//-----------------------------------------------//
window.mdFormAutoSubmitHandler = function ($) {

    if ($('.form-auto-submit').length !== 0) {
        let forms = $('.form-auto-submit');

        forms.change(function (e) {
            let form = $(this);
            form.submit();
        });
    }
}

// Fileupload Choose File Handler
//-----------------------------------------------//
window.mdChooseFileHandler = function ($) {
    $('.custom-file-input').change(function (e) {
        var filename = e.target.files[0].name;
        $(this).parent().find('.custom-file-label').text(filename);
    });
}

// Disable auto fill
//-----------------------------------------------//
window.mdDisableInputAutoFill = function ($) {
    //$('form').disableAutoFill();
}

// Remove input auto focus
//-----------------------------------------------//
window.mdRemoveInputAutoFocus = function ($) {
    $('input').removeAttr('autofocus');
}

// Autosize Textarea boxes
//-----------------------------------------------//
window.mdTextAreaAutoSize = function ($) {

    if ($('textarea').length !== 0) {
        autosize($('textarea'));
    }
}

// WYSIWYG Editor
//-----------------------------------------------//
window.mdEditor = function ($) {

    let editor = $('.editor');

    if (editor) {
        editor.each(function (index) {

            let _editor = $(this);
            let id = _editor.parent().find('.md-editor-content').attr('id');
            let config = {
                tabsize: 2,
                height: 250,
                toolbar: [
                  ['style', ['style']],
                  ['font', ['bold', 'underline', 'clear']],
                  ['color', ['color']],
                  ['para', ['ul', 'ol', 'paragraph']],
                  ['table', ['table']],
                  ['insert', ['link', 'picture', 'video']],
                ]
            };

            let parent = _editor.closest('.md-editor');
            let form = parent.closest('.form');
            let input = parent.find('.md-editor-content');
            let toggler = parent.find('.md-editor-toggler');
                input.summernote(config);

            toggler.on('click touchend', function (e) {
                e.preventDefault();

                let btn = $(this);
                let enabled = btn.data('enabled');
                let icon = btn.find('.icon');
                let text = btn.find('.text');

                if (enabled === true) {
                    btn.data('enabled', false);
                    editor.addClass('hidden');
                    input.removeClass('hidden');
                    icon.removeClass('fa-toggle-on');
                    icon.addClass('fa-toggle-off');
                    text.text('Show Editor');
                    input.summernote('destroy');
                } else {
                    btn.data('enabled', true);
                    editor.removeClass('hidden');
                    input.addClass('hidden');
                    icon.removeClass('fa-toggle-off');
                    icon.addClass('fa-toggle-on');
                    text.text('Hide Editor');
                    input.summernote(config);
                }

            });

            toggler.trigger('click');

        });
    }
}

// State and Country Handler
//-----------------------------------------------//
window.mdStateCountryHandler = function ($) {

    if ($('#country') !== 0  && $('#usa-states') !== 0  && $('#other-state') !== 0 ) {
        let country = $('#country');
        let usa_states = $('#usa-states');
        let other_state = $('#other-state');

        // Country
        country.change(function (e) {
            let value = $(this).val();

            if (value == 'US') {
                usa_states.removeClass('hidden');
                usa_states.attr('name', 'state');
                other_state.addClass('hidden');
                other_state.removeAttr('name');
            } else {
                other_state.removeClass('hidden');
                other_state.attr('name', 'state');
                usa_states.addClass('hidden');
                usa_states.removeAttr('name');
            }

        });
    }
}

// Sidebar Menu
//-----------------------------------------------//
window.mdSideBarMenu = function ($) {
    let sideBar = $('.md-sidebar');
    let toggler = $('.md-sidebar-toggler');
    let card_group = $('.card-main-group');
    let card_main_content = card_group.find('.card-main-content');

    if (sideBar.length != 0 && toggler.length != 0) {
        // Toggeler Click Event
        toggler.on('click touchend', function (e) {
            e.preventDefault();

            let sideBar = $('.md-sidebar');
            let hidden = sideBar.data('hidden');


            if (hidden) {
                sideBar.removeClass('toggled');
                card_main_content.addClass('card-main-content-sidebar-open');
                hidden = false;
            } else {
                sideBar.addClass('toggled');
                card_main_content.removeClass('card-main-content-sidebar-open');
                hidden = true;
            }

            sideBar.data('hidden', hidden);
        });
    }
}

// Tables
//-----------------------------------------------//
window.mdTables = function ($) {

    if ($('.md-table').length !== 0) {
        let tables = $('.md-table');
        let tr = tables.find('tbody tr');
        let links = tables.find('a');
        let btns = tables.find('button[type="submit"]');

        tr.on('click touchend', function (e) {
            let el = $(this);
            if (typeof el.attr('data-redirect-url') !== undefined && el.attr('data-redirect-url')) {
                let url = el.data('redirect-url');
                window.location.href = url;
            }
        });

        links.on('click touchend', function (e) {
            e.preventDefault()
            e.stopPropagation();

            let targetAttr = $(this).attr('target');
            let url = $(this).attr('href');

            if (targetAttr == '_blank') {
                window.open(url, targetAttr);
            } else {
                window.location.href = url;
            }
        });

        btns.on('click touchend', function (e) {
            e.stopPropagation();
        });
    }
}

// Input Masking
//-----------------------------------------------//
window.mdInputMasks = function ($) {

    if ($('input').length !== 0) {
        if (typeof $('input').attr('data-inputmask') != undefined) {
            Inputmask().mask(document.querySelectorAll('input'));
        }
    }

    if ($('textarea').length !== 0) {
        if (typeof $('textarea').attr('data-inputmask') != undefined) {
             Inputmask().mask(document.querySelectorAll('textarea'));
        }
    }
}

// Forms
//-----------------------------------------------//
window.mdFormUpdateBtnHandler = function ($) {

    if ($('.form').length !== 0) {
        let forms = $('.form');

        forms.each(function (i) {

            let form = $(this);

            if (form.hasClass('no-form-update-handler') !== true) {
                form.change(function (e) {
                    let btn = $(this).find('button[type="submit"]');
                    btn.html('Save <i class="fas fa-check"></i>');
                });
            }
        });
    }
}

// Confirm Action Link
//-----------------------------------------//
window.mdConfirmedActionLink = function ($) {

    if ($('.md-confirm-action-link').length !== 0) {
        let btn = $('.md-confirm-action-link');
        let dialog_modal = $('#dialog-modal');
        let dialog_footer = dialog_modal.find('.modal-footer');
        let html = '';

        btn.on('click touchend', function (e) {
            e.preventDefault();
            e.stopImmediatePropagation();

            let btn = $(this);
            let type = btn.data('type');
            let title = btn.data('dialog-title');
            let message = btn.data('dialog-message');
            let response = false;
            let href = btn.attr('href');

            if (type == 'confirmed') {
                html = '<button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>';
                html += '<button id="btn-modal-confirmed" type="button" class="btn btn-primary">Confirm</button>';

                dialog_modal.find('.modal-footer').html(html).promise().done(function () {
                    let btn = dialog_modal.find('#btn-modal-confirmed');

                    if (btn.length !== 0) {
                        btn.on('click touchend', function (e) {
                            dialog_modal.modal('show');
                            response = true;

                            if (response == true) {
                                MD.redirect(href);
                            }
                        });
                    }
                });

                dialog(title, message);
            }

        });
    }
}

// Confirm Action Form
//-----------------------------------------//
window.mdConfirmedActionForm = function ($) {

    if ($('.md-confirm-action-form').length !== 0) {
        let forms = $('.md-confirm-action-form');
        let dialog_modal = $('#dialog-modal');
        let dialog_footer = dialog_modal.find('.modal-footer');
        let html = '';

        forms.find('button[type="submit"]').on('click touchend', function (e) {
            e.preventDefault();
            e.stopImmediatePropagation();

            let form = $(this).closest('.md-confirm-action-form');
            let type = form.data('type');
            let title = form.data('dialog-title');
            let message = form.data('dialog-message');
            let response = false;

            if (type == 'confirmed') {
                html =  '<button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>';
                html += '<button id="btn-modal-confirmed" type="button" class="btn btn-primary">Confirm</button>';

                dialog_modal.find('.modal-footer').html(html).promise().done(function () {

                    let btn = dialog_modal.find('#btn-modal-confirmed');

                    btn.on('click touchend', function (e) {
                            dialog_modal.modal('hide');
                            btn.prop('disabled', true);
                            form.submit();
                    });

                    dialog(title, message);
                });
            }
        });
    }
}

// Fixed Button Bar
//-----------------------------------------//
window.mdFixedButtonBar = function ($) {

    if ($('.md-btn-fixed-bar').length !== 0) {
        let btnBar = $('.md-btn-fixed-bar');

        $(window).scroll(function (e) {

            let current_position =  btnBar.position();

            if ($(window).scrollTop() > current_position.top) {
                btnBar.css('top','0px');
                btnBar.addClass('position-fixed');
                btnBar.addClass('md-btn-fixed-bar-scrolled');
            } else {
                btnBar.removeAttr('style');
                btnBar.removeClass('position-fixed');
                btnBar.removeClass('md-btn-fixed-bar-scrolled');
            }
        });
    }

    if ($('.md-btn-fixed-bar-alt').length !== 0) {
        let btnBar = $('.md-btn-fixed-bar-alt');
        let parent = btnBar.parent();

        console.log('exists');

        $('#card-tenants-main-content .card-body').scroll(function (e) {


            let current_position =  btnBar.position();

            if ($(this).scrollTop() > current_position.top) {
                btnBar.css('top', '0px');
                btnBar.css('height', $('#md-tenants-header').height() + 'px');
                btnBar.addClass('position-fixed');
                btnBar.addClass('md-btn-fixed-bar-scrolled-alt');
            } else {
                btnBar.removeAttr('style');
                btnBar.removeClass('position-fixed');
                btnBar.removeClass('md-btn-fixed-bar-scrolled-alt');
            }
        });
    }
}

// Require If Form Input Show/Hide
//----------------------------------------//
window.mdRequireIfFormInput = function ($) {

    if ($('.md-required-if-input').length != 0) {
        let input = $('.md-required-if-input');
        let require_input = $('#'+input.data('required-if'));
        let require_value = input.data('required-if-value');


        if (require_input.val() == require_value) {
            input.removeClass('hidden');
        }

        require_input.change(function (e) {
            let value = $(this).val();

            if (value == require_value) {
                input.removeClass('hidden');
            } else {
                input.addClass('hidden');
            }
        });
    }
}

// Form Toggler
//-----------------------------------------//
window.mdFormInputToggler = function ($) {

    if ($('.md-toggler').length > 0) {
        let togglers = $('.md-toggler');
        let toggle_icon_on = 'fa-toggle-on';
        let toggle_icon_off = 'fa-toggle-off';

        togglers.each(function (i) {

            let toggler = $(this);
            let selected = toggler.data('selected');
            let id = toggler.data('id');
            let input = toggler.find('#' + id);
            let value = toggler.data('value');
            let icon = toggler.find('.icon');
            let form = toggler.closest('form');

            if (typeof selected == 'string') {
                selected = (selected == 'true')? true : false;
            }

            if (selected === true) {
                input.val(value);
                toggler.data('selected', true);
                icon.removeClass(toggle_icon_off);
                icon.addClass(toggle_icon_on);
            } else {
                input.val('');
                toggler.data('selected', false);
                icon.removeClass(toggle_icon_on);
                icon.addClass(toggle_icon_off);
            }

            form.trigger('change');

        }).promise().done(function () {

            togglers.on('click touchend', function (e) {
                e.preventDefault();

                let toggler = $(this);
                let selected = toggler.data('selected');
                let id = toggler.data('id');
                let input = toggler.find('#' + id);
                let value = toggler.data('value');
                let icon = toggler.find('.icon');
                let form = toggler.closest('form');

                if (typeof selected == 'string') {
                    selected = (selected == 'true')? true : false;
                }

                if (selected === true) {
                    input.val('');
                    toggler.data('selected', false);
                    icon.removeClass(toggle_icon_on);
                    icon.addClass(toggle_icon_off);
                } else {
                    input.val(value);
                    toggler.data('selected', true);
                    icon.removeClass(toggle_icon_off);
                    icon.addClass(toggle_icon_on);
                }

                form.trigger('change');
            });

        });
    }
}

// Feather Icons
window.mdFeatherIcons = function () {
    feather.replace();
}

// Vertical Tabs
window.mdVerticalTabs = function ($) {

    let nav = $('.md-nav-vertical-tabs');
        links = nav.find('.nav-link');
    let tabsContentBlocks = $('.md-nav-vertical-tabs-content .md-tab-block');

    if (links.length !== 0 && tabsContentBlocks.length !== 0) {
        links.on('click touchend', function (e) {
            e.preventDefault();

            let link = $(this);
            let id = link.attr('href');
            let block = $(id);

            if (id.length !== 0) {
                links.removeClass('active');
                tabsContentBlocks.addClass('hidden');
                link.addClass('active');

                block.removeClass('hidden')
            }
        });
    }
}


// On Document Ready
//-----------------------------------------//
jQuery(document).ready(function ($) {

    // PrettyCheckboxes
    mdPrettyCheckboxes($);

    // State/Country Handler
    mdStateCountryHandler($);

    // Textarea boxes
    mdTextAreaAutoSize($);

    // Side Bar Menu Handler
    mdSideBarMenu($);

    // File handler
    mdChooseFileHandler($);

    // Disable auto fill
    // mdDisableInputAutoFill($);

    // Remove auto focus
    mdRemoveInputAutoFocus($);

    // Multiselect input component
    mdMultiSelectInputComponent($);

    // Forms Auto Submit
    mdFormAutoSubmitHandler($);

    // Forms Update Button Handler
    mdFormUpdateBtnHandler($);

    // Confirmed Form
    mdConfirmedActionForm($);

    // Confirmed Dialog
    mdConfirmedActionLink($);

    // WYSIWYG Editor
    mdEditor($);

    // Tables
    mdTables($);

    // Input Masks
    mdInputMasks($);

    // Fixed Button Bar
    mdFixedButtonBar($);

    // Required if input
    mdRequireIfFormInput($);

    // Toggler
    mdFormInputToggler($);

    // Feather Icons
    mdFeatherIcons();

    mdVerticalTabs($);
});