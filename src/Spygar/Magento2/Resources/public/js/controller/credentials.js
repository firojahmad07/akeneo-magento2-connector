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
            renderForm: function () {
                return FormBuilder.build('spygar-magento2-credentials')
                    .then((form) => {
                        form.setElement(this.$el).render();

                        return form;
                    });
            }
        });
    }
);
