/*
 * jQuery UI Plugins: Wizard Dialog
 * 
 * Author: Charles <charlestang@foxmail.com>
 *
 * Depends:
 *  jquery.ui.dialog.js
 *	jquery.ui.core.js
 *	jquery.ui.widget.js
 *  jquery.ui.button.js
 *	jquery.ui.draggable.js
 *	jquery.ui.mouse.js
 *	jquery.ui.position.js
 *	jquery.ui.resizable.js
 */
(function($, undefined){ //the wrapper of the plugin

    $.widget('ui.wizardDialog', {
        /**
        * all the customizations can be put here
        */
        options: {
            prevCaption:'Prev',
            nextCaption:'Next',
            cancelCaption:'Cancel',
            doneCaption:'Submit',
            modal:true,
            width:600,
            autoOpen: false,
            errorcheck: true,
            leavemsg: true,
			isWizard: true,
            done: function(){
                $('form', this).submit();
            },
            buttons:[]
        },
        /**
        * the jui plugin is stateful, the _create function will be called only 
        * once before the widget is destroied.
        */
        _create: function() {
            var self = this, 
            e = self.element;
		    
			if (self.options.isWizard) {
            	var $steps = self.$steps = $('.step', e);
				if ($steps.length > 1) {
					$steps.hide();
				} else {
					self.options.isWizard = false;
					$steps = self.$steps = e.hide();
				}
			} else {
				var $steps = self.$steps = e.hide();
			}
           	var stepCount = self.stepCount = self.$steps.length,
            currentStep = self.currentStep = 0;

            if (self.options.errorcheck && $.fn.validate) {
                var $form = $('form', e);
                if ($form.length > 0){
                    $form.validate({
                        errorElement: 'div',
                        onfocusout:function(){}
                    });
                    var form_field_check = function(e, x, y){
                        var valid_result = $form.valid();
						return valid_result;
                    };
                    e.bind('wizarddialogbeforenext', form_field_check);
                    e.bind('wizarddialogbeforedone', form_field_check);
                } 
            }

            if (self.options.leavemsg && typeof onDialogOpen == 'function') {
                e.bind('wizarddialogopen', onDialogOpen);
                e.bind('wizarddialogbeforecancel', onDialogBeforeClose);
            }
        
            e.dialog({
                modal: self.options.modal,
                width: self.options.width,
                autoOpen: false,
                open: function(){
                    self._openDialog(this);
                }
            });
            $('.ui-dialog-titlebar-close', e.parent()).hide();
            
            if (self.options.isWizard && stepCount > 1) {
                $steps.each(function(i){
                    var $this = $(this);
                    $this.attr('title', e.dialog('option', 'title') + ' - ' + (i+1) + '/' + stepCount + ' ' + $this.attr('title'));
                });
            }
        
            var buttons = [
            {
                'text': self.options.cancelCaption,
                'class': 'wizard-cancel',
                click:function(event){
                    self.cancel(event);
                }
            },
            {
                'text': self.options.prevCaption,
                'class': 'wizard-prev',
                click:function(){
                    self.prev();
                }
            },
            {
                'text': self.options.nextCaption,
                'class': 'wizard-next',
                click:function(event){
                    self.next(event);
                }
            },
            {
                'text': self.options.doneCaption,
                'class': 'wizard-done',
                click:function(){
                    self.done();
                }
            }
            ];

            if (stepCount == 1){
                if ( self.options.buttons.length != 0) {
                    buttons = self.options.buttons;
                } else {
                    buttons = [
                    {
                        'text': self.options.doneCaption,
                        click: function(){
                            self.done();
                        }
                    },
                    {
                        'text': self.options.cancelCaption,
                        click: function(){
                            self.cancel();
                        }
                    }
                    ];
                }
            }
        
            e.dialog('option', 'buttons', buttons);
        },
        /**
     * when you call this plugin without any arguments or just give the options
     * this function will be called.
     * after the _create function this function will be called too.
     */
        _init: function() {
            this.currentStep = 0;
            if (this.options.autoOpen) {
                this.open();
            }
        },
        _openDialog: function(dialog) {
            var self = this, e = self.element, $container = $(dialog).parent();
            self.$steps.hide();
            e.dialog('option', 'title', $(self.$steps[self.currentStep]).show().attr('title'));
            $('button', $container).show();
            if (self.currentStep == 0) {
                $('.wizard-done:visible', $container).hide();
                $('.wizard-prev:visible', $container).button('option', 'disabled', true);
            } else if (self.currentStep == self.stepCount - 1) {
                $('.wizard-next:visible', $container).hide();
                $('.wizard-done', $container).show();
            } else {
                $('.wizard-done:visible', $container).hide();
                $('.wizard-prev:visible', $container).button('option', 'disabled', false);
            }
        },
        open: function(event){
            var self = this, e = self.element;
            e.dialog('open');
            self._trigger('open', event, e);
            return self;
        },
        prev: function() {
            var self = this, e = self.element;
            self.currentStep--;
            self._openDialog(e);
            self._trigger('prev', self.$steps[self.currentStep + 1]);
            return self;
        },
        next: function(event) {
            var self = this, e = self.element;
            if (false === self._trigger('beforenext', event, [self.currentStep, self.$steps[self.currentStep]])){
                return;
            }
            self.currentStep++;
            self._openDialog(e);
            self._trigger('next', self.$steps[self.currentStep - 1]);
            return self;
        },
        cancel: function(event) {
            var self = this, e = self.element;
            if (e.dialog('isOpen')) {
                if (false === self._trigger('beforecancel', event, e)){
                    return;
                }
                self.currentStep = 0;
                e.dialog('close');
                self._trigger('cancel');
            }
            return self;
        },
        done: function(event){
            var self = this, e = self.element;
            if (false === self._trigger('beforedone', event, [self.currentStep, self.$steps[self.currentStep]])){
                return;
            }
			global_formNavigate = true;
            self.currentStep = 0;
            self._trigger('done');
            e.dialog('close');
            return self;
        },
        _setOption: function(key, value) {
            var self = this;
            $.Widget.prototype._setOption.apply(self, arguments);
        },
        destroy: function() {
            $.Widget.prototype.destroy.call( this );
        }
    });

}(jQuery)); // end the wrapper
