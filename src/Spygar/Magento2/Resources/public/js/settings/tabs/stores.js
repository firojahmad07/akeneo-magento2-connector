"use strict";

define(
    [
        'underscore',
        'oro/translator',
        'pim/form',
        'spygarmagento2/templates/tab/stores',
        'pim/router',
        'oro/loading-mask',
        'pim/fetcher-registry',
        'pim/user-context',
        'pim/initselect2',
        
    ],
    
    function(
        _,
        __,
        BaseForm,
        template,
        router,
        LoadingMask,
        FetcherRegistry,
        UserContext,
        initSelect2
    ) {
        return BaseForm.extend({
            isGroup: true,
            template: _.template(template),
            events: {
                "click .spygarMagento2Storeview-itemLabel": "activateStoreView",
                "change .spygarMagento2-storeview-channel": "updateActivateModel",
                "change .spygarStoreview-locale-currency": "updateLocaleAndCurrency",
                "click .save": "saveStoreViewData"
            },
            channels: null,
            attributes: null,
            variantAttributes: null,
            activeStoreView: null,
            activeChannle: null,
            activeLocale: null,
            activeCurrency: null,
            /**
             * {@inheritdoc}
             */
            configure: function () {
                return BaseForm.prototype.configure.apply(this, arguments);
            },

            /**
             * {@inheritdoc}
             */
            render: function () {
                var loadingMask = new LoadingMask();
                loadingMask.render().$el.appendTo(this.getRoot().$el).show();

                var channels;
                
                if(this.channels) {
                    channels = this.channels;
                } else {
                    channels = FetcherRegistry.getFetcher('channel').fetchAll();
                }

                var self = this;

                Promise.all([channels]).then(function(values) {
                    var formData = self.getFormData();
                    var defaultStoreView = _.first(formData.stores);
                    self.activeStoreView = _.isEmpty(self.activeStoreView) 
                                            ? _.findWhere(formData.storeViewMapping, {store: defaultStoreView.code}) 
                                            : self.activeStoreView; 

                    self.activeChannel   = _.isEmpty(self.activeChannel) && !_.isUndefined(self.activeStoreView) ? self.activeStoreView.channel: self.activeChannel;

                    var defaultChannelAndValues = _.findWhere(values[0], {code: self.activeChannel});

                    self.activeLocale    = _.isEmpty(self.activeLocale) && !_.isUndefined(defaultChannelAndValues) 
                                            ? _.first(defaultChannelAndValues.locales).code 
                                            : !_.isUndefined(_.findWhere(defaultChannelAndValues.locales, {code: self.activeLocale})) 
                                            ? self.activeLocale : _.first(defaultChannelAndValues.locales).code;

                    self.activeCurrency  = _.isEmpty(self.activeCurrency) && !_.isUndefined(defaultChannelAndValues) 
                                            ? _.first(defaultChannelAndValues.currencies) 
                                            : _.indexOf(defaultChannelAndValues.currencies, self.activeCurrency) != -1 
                                            ? self.activeCurrency : _.first(defaultChannelAndValues.currencies);
                    
                    self.$el.html(self.template({
                        stores: formData.stores,
                        storeViewMapping: formData.storeViewMapping,
                        activeStoreView: self.activeStoreView,
                        channels: values[0],
                        defaultChannelAndValues: defaultChannelAndValues,
                        activeDetails: {
                            channel: self.activeChannel,
                            locale: self.activeLocale,
                            currency: self.activeCurrency,
                        },
                        currentLocale: UserContext.get('uiLocale'),
                    }));
                    
                    $('.select2').select2();
                    
                    self.$('*[data-toggle="tooltip"]').tooltip();
                    loadingMask.hide().$el.remove();
                });

                this.delegateEvents();

                return BaseForm.prototype.render.apply(this, arguments);
            },
            activateStoreView: function(evt)
            {
                var storeCode = $(evt.target).attr('store-view');
                var formData = this.getFormData();
                this.activeStoreView = _.findWhere(formData.storeViewMapping, {store: storeCode});
                this.render();
            },
            updateActivateModel: function(event) {
                var val = $(event.target).select2('data');
                    val = !_.isEmpty(val) ? val.id: null;
                this.activeChannel = val;
                this.render();
            },
            updateLocaleAndCurrency: function(event) {
                var container = $(event.target).attr('data-container');
                var val = $(event.target).select2('data');
                    val = !_.isEmpty(val) ? val.id: null;
                
                if(container == 'locale') {
                    this.activeLocale = val;
                }
                if(container == 'currency') {
                    this.activeCurrency = val;
                }
            },
            saveStoreViewData: function() {
                var formData  = this.getFormData();
                var params = {
                    store: this.activeStoreView.store,
                    channel: this.activeChannel,
                    locale: this.activeLocale,
                    currency: this.activeCurrency,
                }

                var activeStoreViewIndex  = _.findLastIndex(formData.storeViewMapping, {store: this.activeStoreView.store});
                if(-1 != activeStoreViewIndex) {
                    formData.storeViewMapping[activeStoreViewIndex] = params
                }
                
                
                console.log("here params .",formData.storeViewMapping);
                // this.showLoadingMask();

                // var data = this.stringify();
                // console.log("data : ", data, this.url);
                // $.ajax({
                //     method: 'POST',
                //     url: Routing.generate('spygar_magento2_update_credential'),
                //     contentType: 'application/json',
                //     data: data
                // })
                // .then(this.postSave.bind(this))
                // .fail(this.fail.bind(this))
                // .always(this.hideLoadingMask.bind(this));
            }
        });
    }
);
