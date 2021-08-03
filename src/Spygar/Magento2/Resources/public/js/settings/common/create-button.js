'use strict';

/**
 * Create button
 *
 * @author    Firoj Ahmad <firoj.ahmad121@webkul.com>
 * @copyright 2020 Webkul Software Pvt Ltd (https://www.webkul.com)
 */
define(
    [
        'jquery',
        'underscore',
        'oro/translator',
        'pim/form',
        'pim/template/form/index/create-button',
        'routing',
        'pim/dialogform',
        'pim/form-builder'
    ],
    function (
        $,
        _,
        __,
        BaseForm,
        template,
        Routing,
        DialogForm,
        FormBuilder
    ) {
        return BaseForm.extend({
            template: _.template(template),
            dialog: null,

            /**
             * {@inheritdoc}
             */
            initialize: function (config) {
                this.config = config.config;
                BaseForm.prototype.initialize.apply(this, arguments);
            },
            getConfigDetails: function ()
            {
                return this.config;
            },
            /**
             * {@inheritdoc}
             */
            render: function () {
                this.$el.html(this.template({
                    title: __(this.config.title),
                    iconName: this.config.iconName,
                    url: this.config.url ? Routing.generate(this.config.url) : ''
                }));
               
                if (this.config.modalForm) {
                    this.$el.on('click', () => {
                        $.when( FormBuilder.build(this.config.modalForm.form),
                            this.getConfigDetails(),
                        ).then( (modal, config) => {
                            modal.setRoutes(config.modalForm);
                            modal.open();
                        });
                    });

                    return this;
                }

                // TODO-Remove the following line when all entities will be managed (TIP-730 completed)
                this.dialog = new DialogForm('#create-button-extension');

                return this;
            }
        });
    });
