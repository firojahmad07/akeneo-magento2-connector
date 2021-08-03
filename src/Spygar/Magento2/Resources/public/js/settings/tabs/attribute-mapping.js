"use strict";

define(
    [
        'underscore',
        'oro/translator',
        'pim/form',
        'spygarmagento2/templates/tab/mapping',
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
            fields: null,
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

                var fields;
                var attributes;
                if(this.fields && this.attributes) {
                    fields = this.fields;
                    attributes = this.attributes;
                } else {
                    fields = FetcherRegistry.getFetcher('sylius-fields').fetchAll();
                    attributes = FetcherRegistry.getFetcher('attribute').search({options: {'page': 1, 'limit': 1000 } });
                }

                var self = this;
                
                Promise.all([fields, attributes]).then(function(values) {
                    self.fields = values[0];
                    // self.attributes = self.sortByLabel(values[1]);
                    
                    self.$el.html(self.template({
                        fields: self.fields,
                        attributes: values[1],
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
