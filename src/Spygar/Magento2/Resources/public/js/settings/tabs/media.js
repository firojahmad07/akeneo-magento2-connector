"use strict";

define(
    [
        'underscore',
        'oro/translator',
        'pim/form',
        'spygarmagento2/templates/tab/media',
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
                // 'change .AknFormContainer-Mappings input:not(".view-only")': 'updateModel',
                // 'change .AknFormContainer-Mappings select:not(".view-only")': 'updateModel',
            },
            fields: null,
            attributes: null,
            variantAttributes: null,
            imageRoles : {
                'base_image' : 'base_image',
                'small_image' : 'small_image',
                'thumbnail_image' : 'thumbnail_image',
                
            },
            childImageRoles : {
                'child_base_image' : 'child_base_image',
                'child_small_image' : 'child_small_image',
                'child_thumbnail_image' : 'child_thumbnail_image',
            },

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

                var attributes;
                if(this.attributes) {
                    attributes = this.attributes;
                } else {
                    attributes = FetcherRegistry.getFetcher('attribute').fetchByTypes(['pim_catalog_image']);
                }

                var self = this;
                
                Promise.all([attributes]).then(function(values) {
                   
                    self.$el.html(self.template({
                        imageAttrs: values[0],
                        imageRoles: self.imageRoles,
                        currentLocale: UserContext.get('uiLocale'),
                    }));
                    
                    $('.select2').select2();
                    
                    self.$('*[data-toggle="tooltip"]').tooltip();
                    loadingMask.hide().$el.remove();
                });

                this.delegateEvents();

                return BaseForm.prototype.render.apply(this, arguments);
            },
            /**
             * Update model after value change
             *
             * @param {Event} event
             */
            updateModel: function (event) {
                var val;                
                var data = JSON.parse(JSON.stringify(this.getFormData()));            
                if($(event.target).hasClass('select2')) {
                    val = $(event.target).select2('data');
                    val = val.map(function(obj) { return obj.id });
                } else {
                    val = $(event.target).val();
                }
                var wrapper = $(event.target).attr('data-wrapper') ? $(event.target).attr('data-wrapper') : 'mapping' ;
                console.log("wrapper : val ", wrapper, val);
            }
        });
    }
);
