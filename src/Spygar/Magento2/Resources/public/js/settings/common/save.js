'use strict';

define([
        'underscore',
        'jquery',
        'routing',
        'pim/form/common/save',
        'pim/template/form/save'
    ],
    function(
        _,
        $,
        Routing,
        SaveForm,
        template
    ) {
        return SaveForm.extend({
            config: [],
            template: _.template(template),
            currentKey: 'current_form_tab',
            saveUrl: null,
            events: {
                'click .save': 'save'
            },

            initialize: function (config) {
                
                this.config = config.config;
                this.url  = this.config.url;
            },

            /**
             * {@inheritdoc}
             */
            render: function () {
                this.$el.html(this.template({
                    label: _.__('pim_enrich.save.label')
                }));
                // var saveBtn = this.$('.save');
                // saveBtn.addClass('spygar-save-config');
                
            },

            /**
             * {@inheritdoc}
             */
            save: function () {
                this.getRoot().trigger('pim_enrich:form:entity:pre_save', this.getFormData());
                this.showLoadingMask();

                var data = this.stringify(this.getFormData());
                console.log("data : ", data, this.url);
                $.ajax({
                    method: 'POST',
                    url: Routing.generate(this.url),
                    contentType: 'application/json',
                    data: data
                })
                .then(this.postSave.bind(this))
                .fail(this.fail.bind(this))
                .always(this.hideLoadingMask.bind(this));
            },

            stringify: function(formData) {                           

                return JSON.stringify(formData);                
            },

            /**
             * {@inheritdoc}
             */
            postSave: function (data) {
                this.setData(data);
                this.getRoot().trigger('pim_enrich:form:entity:post_fetch', data);

                SaveForm.prototype.postSave.apply(this, arguments);
            }     
        });
    }
);
