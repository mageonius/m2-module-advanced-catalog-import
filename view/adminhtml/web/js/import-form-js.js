/*
 * Copyright © mageOn, 2019.
 * https://github.com/mageonius
 */

define([
    'jquery'
], function ($) {
    'use strict';

    return function (data, element) {
        let defaultOptions = {
            'fieldsetId': '',
            'option2': '123'
        };

        $.extend({}, data, defaultOptions);

        try {
            $(element).change(function () {
                let value = $(this).val(),
                    fieldset = $('#' + data.fieldsetId);

                if (value === 'catalog_product') {
                    fieldset.removeClass('no-display');
                    fieldset.find('input, textarea', function () {
                        $(this).prop('disabled', 'disabled');
                    });
                } else {
                    fieldset.addClass('no-display');
                    $(this).removeProp('disabled');
                }
            });
        } catch (e) {
            console.error('Mageon_AdvancedCatalogImport caused an error: ', e);
        }
    };
});
