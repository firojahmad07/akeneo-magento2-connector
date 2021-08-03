'use strict';
define(
    [
        'jquery',
        'underscore',
        'oro/translator',
        'mobikulconfiguration/template/form/fields/select',
        'pim/form',
        'pim/fetcher-registry',
        'pim/user-context',
        'jquery.select2',
        'pim/i18n'
    ],
    function (
        $,
        _,
        __,
        template,
        BaseForm,
        fetcherRegistry,
        UserContext,
        select2,
        i18n
    ) {
        return BaseForm.extend({
            config: {},
            className: 'AknFieldContainer akeneo-mobikul-fields',
            template: _.template(template),
            label: null, 
            types: [
                { code: "product", label: "Product"},
                { code: "category", label: "Category"},
            ],
            /**
             * Initializes configuration.
             *
             * @param {Object} config
             */
            initialize: function (config) {
                this.config = config.config;
                this.label  = this.config.label;
                return BaseForm.prototype.initialize.apply(this, arguments);
            },

            /**
             * Renders scopes dropdown.
             *
             * @return {Object}
             */
            render: function () {               
                if ( _.isEmpty(this.getScope())) {
                    this.setScope(_.first(this.types).code);
                }
                
                this.$el.html(
                    this.template({
                        isEditable: this.isEditable(),
                        __: __,
                        channels: this.types,
                        scope: this.getScope(),
                        fieldLabel: this.label,
                        errors: []
                    })
                );
                // errors: this.getParent().getValidationErrorsForField('scope')

                this.$('.select2')
                    .select2({minimumResultsForSearch: -1})
                    .on('change', this.updateState.bind(this));

                this.renderExtensions();

                return this;
            },

           

            /**
             * Returns whether this filter is editable.
             *
             * @returns {boolean}
             */
            isEditable: function () {
                   return true;
            },

            /**
             * Sets new scope on field change.
             *
             * @param {Object} event
             */
            updateState: function (event) {
                this.setScope(event.target.value);
            },

            /**
             * Sets specified scope into root model.
             *
             * @param {String} code
             */
            setScope: function (code) {
                var data = this.getFilters();
                var before = data.type;

                data.type = code;
                this.setData(data);

                if (before !== code) {
                    this.getRoot().trigger('channel:update:after', data.type);
                }
            },

            /**
             * Gets scope from root model.
             *
             * @returns {String}
             */
            getScope: function () {
                var formData = this.getFilters();

                if (_.isUndefined(formData)) {
                    return null;
                }
                
                return _.isUndefined(formData.type) ? null : formData.type;
            },

            /**
             * Get filters
             *
             * @return {object}
             */
            getFilters: function () {
                return this.getFormData();
            }
        });
    }
);
