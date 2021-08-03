"use strict";

define(
    [
        'underscore',
        'oro/translator',
        'pim/form',
        'spygarmagento2/templates/credential/locale-mapping',
        'oro/loading-mask',
        'pim/fetcher-registry',
        'pim/user-context',        
        'pim/initselect2'
    ],
    function(
        _,
        __,
        BaseForm,
        template,
        LoadingMask,
        FetcherRegistry,
        UserContext,     
        initSelect2,    
    ) {
        return BaseForm.extend({
            isGroup: true,
            label: __('spygar_shopify.locale.tab'),
            template: _.template(template),
            code: 'spygar_shopify_connector_locale',
            errors: [],
            events: {
                'change .AknFormContainer-associationMappings select': 'updateModel',
            },

            /**
             * {@inheritdoc}
             */
            configure: function () {
                this.trigger('tab:register', {
                    code: this.code,
                    label: this.label
                });
                
                return BaseForm.prototype.configure.apply(this, arguments);
            },
            akeneoLocals: null,
            /**
             * {@inheritdoc}
             */
            render: function () {
                var loadingMask = new LoadingMask();
                loadingMask.render().$el.appendTo(this.getRoot().$el).show();
                var self = this;
                
                var akeneoLocals;    
                if(self.akeneoLocals) {
                    akeneoLocals = self.akeneoLocals;
                } else {
                    akeneoLocals = FetcherRegistry.getFetcher('locale').fetchActivated();
                }
                
                Promise.all([akeneoLocals]).then(function(values) {
                    var fields = !_.isUndefined(self.getFormData().resources) ? self.getFormData().resources : [];
                    self.$el.html(self.template({
                        _:_,
                        locales: values[0],
                        fields: fields,
                        model: self.getFormData(),
                        currentLocale: UserContext.get('uiLocale'), 
                    }));
                    $('.select2').select2();
                    loadingMask.hide().$el.remove();                    
                });

                self.delegateEvents();

                return BaseForm.prototype.render.apply(this, arguments);
            },

            /**
             * Update model after value change
             *
             * @param {Event} event
             */
            
            updateModel: function (event) {
                var data = this.getFormData();
                var mappedField = $(event.target).attr('sylius-locale-code');
                var val =  $(event.target).select2('data');
                if (_.isUndefined(data.extras)) {
                    data.extras = {};
                }
                
                data.extras[mappedField] = val;
                
                console.log("data : ", data);
                this.setData(data);
            },

            /**
             * Sets errors
             *
             * @param {Object} errors
             */
            setValidationErrors: function (errors) {
                this.errors = errors.response;
                this.render();
            },

            /**
             * Resets errors
             */
            resetValidationErrors: function () {
                this.errors = {};
                this.render();
            }
        });
    }
);