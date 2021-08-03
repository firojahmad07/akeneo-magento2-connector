define([
    'jquery',
    'underscore',
    'oro/translator',
    'pim/form',
    'mobikulconfiguration/template/form/code'
], function($, _, __, BaseForm, template) {

    return BaseForm.extend({
        template: _.template(template),
        dialog: null,
        events: {
            'change input': 'updateModel'
        },

        initialize: function(config) {
            this.config = config.config;
            this.fieldName = this.config.fieldName || 'code';          

            BaseForm.prototype.initialize.apply(this, arguments);
        },

        updateModel: function(event) {
            this.getFormModel().set(this.fieldName, event.target.value || '');
        },

        /**
         * {@inheritdoc}
         */
        render: function() {
            if (!this.configured)
                this;

            var errors = !_.isEmpty(this.getRoot().validationErrors) ? this.getRoot().validationErrors : [];
           
            errors = !_.isArray(errors) ? [errors] : errors;
            this.$el.html(this.template({
                fieldName: this.fieldName,
                label: __('akeneo_mobikul.connector.code'),
                editable: true,
                requiredLabel: __('pim_common.required_label'),
                errors: errors,
                value: this.getFormData()[this.fieldName]
            }));

            this.delegateEvents();

            return this;
        }
    });
});
