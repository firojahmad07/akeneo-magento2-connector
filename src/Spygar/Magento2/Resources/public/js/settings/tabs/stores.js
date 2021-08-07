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
            },
            channels: null,
            attributes: null,
            variantAttributes: null,
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
                    console.log("channels : ", values[0]);                 
                    self.$el.html(self.template({
                        stores: formData.stores,
                        channels: values[0],
                        currentLocale: UserContext.get('uiLocale'),
                    }));
                    
                    $('.select2').select2();
                    
                    self.$('*[data-toggle="tooltip"]').tooltip();
                    loadingMask.hide().$el.remove();
                });

                this.delegateEvents();

                return BaseForm.prototype.render.apply(this, arguments);
            },

        });
    }
);
