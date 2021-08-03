'use strict';

define(
    [
        'underscore',
        'pim/controller/front',
        'pim/form-builder'
    ],
    function (_, BaseController, FormBuilder) {
        return BaseController.extend({
            initialize: function (options) {
                this.options = options;
            },

            /**
             * {@inheritdoc}
             */
            renderForm: function (route) {
                return $.when(
                    FormBuilder.build('akeneo-shopify-credential-edit-form'),
                    $.get(Routing.generate("spygar_shopify_get_credential", { id: route.params.id} ))
   
                ).then((form, response) => {
                    this.on('pim:controller:can-leave', function (event) {
                        form.trigger('pim_enrich:form:can-leave', event);
                    });
                    form.setData(response[0]);
                    form.setElement(this.$el).render();

                    return form;
                });
            }
        });
    }
);
