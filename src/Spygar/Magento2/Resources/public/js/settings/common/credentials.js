'use strict';

define(
    [
        'jquery',
        'underscore',
        'oro/translator',
        'spygarmagento2/templates/credential/lists',
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
            className: 'AknFieldContainer',
            template: _.template(template),

            /**
             * Initializes configuration.
             *
             * @param {Object} config
             */
            initialize: function (config) {
                this.config = config.config;

                return BaseForm.prototype.initialize.apply(this, arguments);
            },

            /**
             * Renders scopes dropdown.
             *
             * @return {Object}
             */
            render: function () {
                if (!this.configured) {
                    return this;
                }

                fetcherRegistry.getFetcher('magento2-credentials').fetchAll().then(function (credentials) {
                    console.log('credentials :' ,credentials);                   
                    var templateData = {
                        __: __,
                        credentials: credentials,
                        activeCredential: this.getActiveCredential(),
                        errors: []
                    };

                    this.$el.html(this.template(templateData));

                    this.$('.select2')
                        .select2({minimumResultsForSearch: -1})
                        .on('change', this.updateState.bind(this));

                    this.$('[data-toggle="tooltip"]').tooltip();

                    this.renderExtensions();
                }.bind(this));

                return this;
            },

            /**
             * Sets new scope on field change.
             *
             * @param {Object} event
             */
            updateState: function (event) {
                var data = this.getFormData();                
                var credential = event.target.value;
                if (!_.isEmpty(credential)) {
                    data.configuration.credential = credential;    
                    this.setData(data);
                }
                // this.getRoot().trigger('job:update-credentials:after', data.configuration.credential);
            },
            getActiveCredential: function() {
                return this.getFormData().configuration.credential;  
            }
        });
    }
);