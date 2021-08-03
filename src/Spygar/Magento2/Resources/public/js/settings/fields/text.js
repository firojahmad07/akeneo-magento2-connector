'use strict';

define([
    'jquery',
    'underscore',
    'pim/form/common/fields/field',
    'pim/template/form/common/fields/text'
],
function (
    $,
    _,
    BaseField,
    template
) {
    return BaseField.extend({
        template: _.template(template),
        className: 'akeneo-sylius-fields',
        events: {
            'change input':  "updateModel",
        },
        updateModel: function (event) {
            var formData = this.getFormData();
            var value = $(event.target).val();
            this.errors = [];
            if(!_.isUndefined(formData[this.fieldName])) {
                formData[this.fieldName] = value;
            } else {
                formData[this.fieldName] = value;
            }
            
            this.setData(formData);
        },
        
        /**
         * {@inheritdoc}
         */
        renderInput: function (templateContext) {
            var formData = this.getFormData();
            return this.template(_.extend(templateContext, {
                value: !_.isUndefined(formData[this.fieldName]) ? formData[this.fieldName] : null
            }));
        },
    });
});
