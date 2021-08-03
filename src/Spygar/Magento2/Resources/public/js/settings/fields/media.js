'use strict';
define([
        'jquery',
        'backbone',
        'pim/form',
        'underscore',
        'routing',
        'oro/loading-mask',
        'pim/common/property',
        'mobikulconfiguration/template/form/fields/media',
        'pim/dialog',
        'oro/messenger',
        'pim/media-url-generator',
        'pim/i18n',
        'pim/field',
        'pim/router',
        'jquery.slimbox'   
    ],
    function ($, Backbone, BaseForm, _, Routing, LoadingMask, propertyAccessor, fieldTemplate, Dialog, messenger, MediaUrlGenerator, i18n,Field, router,slimbox) {
        var FieldModel = Backbone.Model.extend({
            values: []
        });

        return BaseForm.extend({
            tagName: 'div',
            className: 'AknComparableFields field-container akeneo-mobikul-fields',
            fieldName: null,
            options: {},
            attributes: function () {
                return {
                    'data-attribute': this.options ? this.options.code : null
                };
            },
            attribute: null,
            context: {},
            model: FieldModel,
            elements: {},
            editable: true,
            ready: true,
            valid: true,
            locked: false,            
            imagesValue: {},
            fieldTemplate: _.template(fieldTemplate),

            /**
             * Initialize this field
             *
             * @param {Object} attribute
             *
             * @returns {Object}
             */
            initialize: function (meta) {
                this.config = meta.config;

                if (undefined === this.config.fieldName) {
                    throw new Error('This view must be configured with a field name.');
                } 

                this.fieldName = this.config.fieldName;
                this.readOnly = this.config.readOnly || false;
                this.errors = [];
                
                this.elements  = {};
                this.context   = {};
                this.attribute = {};
                this.attribute.field_type = this.attribute.type = 'pim_catalog_image';

                BaseForm.prototype.initialize.apply(this, arguments);
            },

            events: { 
                'change input[type="file"]' : 'updateModel',
                'click  .clear-field'       : 'clearField',
                'click  .open-media'        : 'previewImage',
            },

            uploadContext: {},
          
            /**
             * What to do after a save
             */
            postSave: function (response) { 
                messenger.notify(
                    'success',
                    'Visibility changed to ' + response.visibility + ' successfully.'
                );
                this.render();
            },

            renderInput: function (context) {
                return this.fieldTemplate(context);
            },

            getTemplateContext: function () {
                return Field.prototype.getTemplateContext.apply(this, arguments)
                    .then(function (templateContext) {
                        templateContext.inUpload          = !this.isReady();
                        templateContext.mediaUrlGenerator = MediaUrlGenerator;
                        templateContext.Routing = Routing;
                        templateContext.fieldName = this.config.fieldName;
                                                
                        if(this.getCurrentValue()) {
                            
                            templateContext.value = this.getCurrentValue();
                        }

                        return templateContext;
                    }.bind(this));
            },
            /**
             * On save fail
             *
             * @param {Object} response
             */
            fail: function (response, responseJSON) {
                var msg = "Can't change visibility make sure PutObjectAcl permission is given to bucket.";
                if(typeof(response.responseJSON.visibility) !== 'undefined' && response.responseJSON.visibility) {
                    msg += " Current visibility is " + response.responseJSON.visibility;
                }
                messenger.notify(
                    'error',
                     msg
                );
            },

            renderCopyInput: function (value) {
                return this.getTemplateContext()
                    .then(function (context) {
                        var copyContext = $.extend(true, {}, context);
                        copyContext.value = value;
                        copyContext.context.locale    = value.locale;
                        copyContext.context.scope     = value.scope;
                        copyContext.editMode          = 'view';
                        copyContext.mediaUrlGenerator = MediaUrlGenerator;
                        copyContext.Routing = Routing;

                        return this.renderInput(copyContext);
                    }.bind(this));
            },
            /**
             * Render this field
             *
             * @returns {Object}
             */
            render: function () {
                this.setEditable(!this.locked);
                this.setValid(true);
                this.elements = {};
                var promises  = [];
                $.when.apply($, promises)
                    .then(this.getTemplateContext.bind(this))
                    .then(function (templateContext) {
                        if (!_.isUndefined(templateContext.value) && !_.isUndefined(templateContext.value.data) ) {
                            templateContext.value = _.first(templateContext.value.data);
                        }
                        this.$el.html(this.fieldTemplate(templateContext));
                        this.renderElements();
                        this.postRender();
                        this.delegateEvents();
                    }.bind(this));

                return this;
            },          
            /**
             * Render elements of this field in different available positions
             */
            renderElements: function () {
                
                _.each(this.elements, function (elements, position) {
                    var $container = 'field-input' === position ?
                        this.$('.original-field .field-input') :
                        this.$('.' + position + '-elements-container');

                    $container.empty();

                    _.each(elements, function (element) {
                        if (typeof element.render === 'function') {
                            $container.append(element.render().$el);
                        } else {
                            $container.append(element);
                        }
                    }.bind(this));

                }.bind(this));
            },       
            /**
             * Is called after rendering the input
             */
            postRender: function () {},

            updateModel: function () { 
                if (!this.isReady()) {
                    Dialog.alert(_.__(
                        'pim_enrich.entity.product.info.already_in_upload',
                        {'locale': this.context.locale, 'scope': this.context.scope}
                    ));
                }               
                
                
                var input = this.$('input[type="file"]').get(0);    
                if (!input || 0 === input.files.length) {
                    return;
                }
                
                const loadingMask = new LoadingMask();
                this.$el.empty().append(loadingMask.render().$el.show());
                
                var allFiles = input.files;
                $.each(allFiles, function( index, imageFiles ) {
                    var imageFilesTypeArray = imageFiles.type.split('/');
                    var fileType = imageFilesTypeArray[0];
                    var invalidType = ['video', 'audio', 'text', 'application'];
                    if(invalidType.includes(fileType)){
                        messenger.notify(
                            'error',
                            imageFiles.type + ' type of file are not allowed to upload.'
                            );
                            return true;
                        }
                        
                    var formData = new FormData();
                    formData.append('file', imageFiles);
                    this.setReady(false);
                    this.uploadContext = {
                        'locale': this.context.locale,
                    };
                    
                    $.ajax({
                        url: Routing.generate('webkul_akeneo_mobikul_media_rest_post'),
                        type: 'POST',
                        data: formData,
                        contentType: false,
                        cache: false,
                        processData: false,
                        async:true,
                        xhr: function () {
                            var myXhr = $.ajaxSettings.xhr();
                            if (myXhr.upload) {
                                myXhr.upload.addEventListener('progress', this.handleProcess.bind(this), false);
                            }

                            return myXhr;
                        }.bind(this)
                    })
                    .done(function (data) { 
                        if(data) {
                            this.setUploadContextValue(data);
                            this.render();
                        }
                        loadingMask.remove();
                    }.bind(this))
                    .fail(function (xhr) {
                        var message = xhr.responseJSON && xhr.responseJSON.message ?
                            xhr.responseJSON.message :
                            _.__('pim_enrich.entity.product.error.upload');
                        messenger.enqueueMessage('error', message);
                    })
                    .always(function () {
                        this.$('> .akeneo-media-uploader-field .progress').css({opacity: 0});
                        this.setReady(true);
                        this.uploadContext = {};
                    }.bind(this));
                }.bind(this));
                $(".AknProgress-bar").html('');
            }, 

            clearField: function (e) {
                
                var index = $(e.target).closest("div.AknMediaField-info").find("input[name='asset_index']").val();
                
                this.clearCurrentValue(index);

                this.render();

            },
            /**
             * Set as valid
             *
             * @param {boolean} valid
             */
            setValid: function (valid) {
                this.valid = valid;
            },

            /**
             * Return whether is valid
             *
             * @returns {boolean}
             */
            isValid: function () {
                return this.valid;
            },

            /**
             * Set the focus on the input of this field
             */
            setFocus: function () {
                this.$('input:first').focus();
            },

            /**
             * Set this field as editable
             *
             * @param {boolean} editable
             */
            setEditable: function (editable) {
                this.editable = editable;
            },

            /**
             * Set this field as locked
             *
             * @param {boolean} locked
             */
            setLocked: function (locked) {
                this.locked = locked;
            },

            /**
             * Return whether this field is editable
             *
             * @returns {boolean}
             */
            isEditable: function () {
                return this.editable;
            },

            /**
             * Set this field as ready
             *
             * @param {boolean} ready
             */
            setReady: function (ready) {
                this.ready = ready;
            },

            /**
             * Return whether this field is ready
             *
             * @returns {boolean}
             */
            isReady: function () {
                return this.ready;
            },

            /**
             * Get the current edit mode (can be 'edit' or 'view')
             *
             * @returns {string}
             */
            getEditMode: function () {
                if (this.editable) {
                    return 'edit';
                } else {
                    return 'view';
                }
            },

            /**
             * Return whether this field can be seen
             *
             * @returns {boolean}
             */
            canBeSeen: function () {
                return true;
            },

            getCurrentValue: function () {
                const value = propertyAccessor.accessProperty(
                    this.getFormData(),
                    this.fieldName
                );
                if(value && typeof(value.data) === 'undefined' && (value.length !== 0) ) {
                    var dataValue = value;
                    if(Array.isArray(dataValue) === false) {
                        dataValue = [value];
                    }
                    return { 'data': dataValue };
                }
                
                return null === value || (typeof(value.data) === 'object' && value.data === 0) ? undefined : value;
            },

            /**
             * Set current model value for this field
             *
             * @param {*} value
             */
            setCurrentValue: function (value) {
                const dataModel = this.getFormData();
               
                if(value) {
                    var imagesValue = {};
                    // if(_.isUndefined(dataModel.[this.fieldName])) {
                    //     dataModel.[this.fieldName] = null;
                    // }

                    imagesValue = dataModel[this.fieldName] = value;

                    propertyAccessor.updateProperty(dataModel, this.fieldName, imagesValue);

                    this.setData(dataModel);
                }
                
            },

            clearCurrentValue: function() {
                const dataModel = this.getFormData();

                // if(typeof(dataModel.[this.fieldName]) === 'undefined') {
                //     dataModel.[this.fieldName] = null;
                // }

                var imagesValue =  dataModel[this.fieldName] = null;

                propertyAccessor.updateProperty(dataModel, this.fieldName, imagesValue);

                this.setData(dataModel);
            },

            /**
             * Get the label of this field (default is code surrounded by brackets)
             *
             * @returns {string}
             */
            getLabel: function () {
                return _.__('File');
            },

            handleProcess: function (e) {
                if (this.uploadContext.locale === this.context.locale &&
                    this.uploadContext.scope === this.context.scope
                ) {
                    this.$('> .akeneo-media-uploader-field .progress').css({opacity: 1});
                    this.$('> .akeneo-media-uploader-field .progress .bar').css({
                        width: ((e.loaded / e.total) * 100) + '%'
                    });
                }
            },
            previewImage: function () {
                if ( !_.isUndefined(this.getFormData()[this.fieldName]) ) {
                    var mediaUrl = MediaUrlGenerator.getMediaShowUrl(this.getFormData()[this.fieldName].filePath, 'preview');                    
                    if (mediaUrl) {
                        $.slimbox(mediaUrl, '', {overlayOpacity: 0.3});
                    }
                }
            },

            setUploadContextValue: function (value) {
                this.setCurrentValue(value);
            },
            /**
             * Recursively search for the first tab ancestor if any, and returns its code. Sorry.
             *
             * @returns {String}
             */
            getTabCode() {
                let parent = this.getParent();
                while (!(parent instanceof Tab)) {
                    parent = parent.getParent();
                    if (null === parent) {
                        return null;
                    }
                }

                return parent.code;
            }       
        });
    }
);
