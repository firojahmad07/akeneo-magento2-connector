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
                'change .AknFormContainer-Mappings input:not(".view-only")': 'updateModel',
                'change .AknFormContainer-Mappings select:not(".view-only")': 'updateModel',
            },
            magento2StandardAttributes:[
                { code:"sku"}, { code:"name"}, { code:"description"}, { code:"short_description"}, { code:"price"}, 
                { code:"special_price"}, { code:"special_from_date"}, { code:"special_to_date"}, { code:"cost"}, 
                { code:"weight"}, { code:"manufacturer"}, { code:"meta_title"}, { code:"meta_keyword"},
                { code:"meta_description"}, { code:"news_from_date"}, { code:"news_to_date"}, { code:"status"},
                { code:"minimal_price"}, { code:"visibility"}, { code:"options_container"}, { code:"required_options"}, 
                { code:"has_options"}, { code:"image_label"}, { code:"small_image_label"}, { code:"thumbnail_label"}, 
                { code:"country_of_manufacture"}, { code:"quantity_and_stock_status"}, { code:"custom_layout"}, 
                { code:"price_type"}, { code:"sku_type"}, { code:"weight_type"}, { code:"price_view"}, { code:"shipment_type"}, 
                { code:"url_key"}, { code:"url_path"}, { code:"msrp"}, { code:"msrp_display_actual_price_type"}, 
                { code:"links_purchased_separately"}, { code:"samples_title"}, { code:"links_title"}, { code:"links_exist"},
                { code:"giftcard_amounts"}, { code:"allow_open_amount"}, { code:"open_amount_min"}, { code:"open_amount_max"}, 
                { code:"giftcard_type"}, { code:"is_redeemable"}, { code:"use_config_is_redeemable"}, { code:"lifetime"}, 
                { code:"use_config_lifetime"}, { code:"email_template"}, { code:"use_config_email_template"}, 
                { code:"allow_message"}, { code:"use_config_allow_message"}, { code:"related_tgtr_position_limit"}, 
                { code:"related_tgtr_position_behavior"}, { code:"upsell_tgtr_position_limit"},
                { code:"upsell_tgtr_position_behavior"}, { code:"tax_class_id"}, { code:"gift_message_available"}, 
                { code:"gift_wrapping_available"}, { code:"gift_wrapping_price"}, { code:"is_returnable"}, { code:"swatch_imag"}
            ],
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
                var formData = self.getFormData();
                Promise.all([fields, attributes, formData]).then(function(values) {
                    self.fields = values[0];
                    // self.attributes = self.sortByLabel(values[1]);
                    
                    self.$el.html(self.template({
                        fields: self.fields,
                        attributes: values[1],
                        formData: values[2],
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
                console.log('data : ', data);
                if($(event.target).hasClass('select2')) {
                    val = $(event.target).select2('data');
                    val = !_.isEmpty(val) ? val.id: null;
                } else {
                    val = $(event.target).val();
                }
                var wrapper = $(event.target).attr('data-wrapper') ? $(event.target).attr('data-wrapper') : 'parentAttributes';

                if(typeof(data[wrapper]) === 'undefined' 
                    || !data[wrapper] || typeof(data[wrapper]) !== 'object' 
                    || data[wrapper] instanceof Array)
                {
                    data[wrapper] = {};
                }

                data[wrapper][$(event.target).attr('name')] = val;
                console.log('data : ', data);
                this.setData(data);
                this.render();
            }
        });
    }
);
