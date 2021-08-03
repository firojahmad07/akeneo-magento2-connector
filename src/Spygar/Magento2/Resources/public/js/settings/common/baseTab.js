"use strict";

define(
    [
        'underscore',
        'oro/translator',
        'pim/form',
        'routing'
    ],
    function(
        _,
        __,
        BaseForm,
        Routing
    ) {
        return BaseForm.extend({
            isGroup: true,
            label: null,
            code: null,          
            /**
             * {@inheritdoc}
             */
            initialize: function (extension) {
                this.code   = extension.config.code;
                this.label  = __(extension.config.label);
            },
            configure: function () {
                this.trigger('tab:register', {
                    code: this.code,
                    label: this.label
                });
                return BaseForm.prototype.configure.apply(this, arguments);
            },

            /**
             * {@inheritdoc}
             */
            render: function () {
                this.delegateEvents();
                return BaseForm.prototype.render.apply(this, arguments);
            },
        });
    }
);
