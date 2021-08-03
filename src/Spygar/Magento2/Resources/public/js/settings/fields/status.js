'use strict';

define([
    'jquery',
    'underscore',
    'oro/translator',
    'pim/form/common/fields/field',
    'pim/template/form/common/fields/boolean',
    'bootstrap.bootstrapswitch'
],
function (
    $,
    _,
    __,
    BaseField,
    template
) {
    return BaseField.extend({
        className: 'akeneo-mobikul-fields',
        template: _.template(template),
        events: {
            'change input': "updateModel"
        },
        updateModel: function(event) {
            var formData = this.getFormData();
            var value = $(event.target).prop('checked') ? true : false;
            if (!_.isUndefined(formData[this.fieldName])) {
                formData[this.fieldName] = value;
            }
            
            formData[this.fieldName] = value;

            this.setData(formData);
        },
        /**
         * {@inheritdoc}
         */
        renderInput: function (templateContext) {
            var formData = this.getFormData();
            if (undefined === this.getModelValue() && _.has(this.config, 'defaultValue')) {
                this.updateModel(this.config.defaultValue);
                formData[this.fieldName] = ('on' == this.config.defaultValue) ? true : false;
                this.setData(formData);
            }

            return this.template(_.extend(templateContext, {
                value: !_.isUndefined(formData[this.fieldName]) ? formData[this.fieldName] : this.config.defaultValue,
                labels: {
                    on: __('pim_common.yes'),
                    off: __('pim_common.no')
                }
            }));
        },

        /**
         * {@inheritdoc}
         */
        postRender: function () {
            this.$('.switch').bootstrapSwitch();
        },

        /**
         * {@inheritdoc}
         */
        getFieldValue: function (field) {
            return $(field).is(':checked');
        }
    });
});
