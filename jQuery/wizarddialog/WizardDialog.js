/*
 * jQuery UI Plugins: Wizard Dialog
 * 
 * Author: Charles <charlestang@foxmail.com>
 *
 * Depends:
 *      jquery.ui.dialog.js
 *	jquery.ui.core.js
 *	jquery.ui.widget.js
 *      jquery.ui.button.js
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
        autoOpen: true
    },
    /**
     * the jui plugin is stateful, the _create function will be called only 
     * once before the widget is destroied.
     */
    _create: function() {
        var self = this, 
            e = self.element,
            $steps = self.$steps = $('.step', e).hide(),
            stepCount = self.stepCount = self.$steps.length,
            currentStep = self.currentStep = 0;
        
        e.dialog({
            modal: self.options.modal,
            width: self.options.width,
            autoOpen: false,
            open: function(){self._openDialog(this);},
            close: function(){self.cancel();}
        });
        
        $steps.each(function(i){
            var $this = $(this);
            $this.attr('title', e.dialog('option', 'title') + ' - ' + (i+1) + '/' + stepCount + ' ' + $this.attr('title'));
        });
        
        var buttons = [
            {
                'text': self.options.cancelCaption,
                'class': 'wizard-cancel',
                click:function(){
                    self.cancel();
                }
            },
            {
                'text': self.options.prevCaption,
                'class': 'wizard-prev',
                click:function(){self.prev();}
            },
            {
                'text': self.options.nextCaption,
                'class': 'wizard-next',
                click:function(){self.next();}
            },
            {
                'text': self.options.doneCaption,
                'class': 'wizard-done',
                click:function(){
                    self.done();
                }
            }
        ];
        
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
    open: function(){
        var self = this, e = self.element;
        e.dialog('open');
        self._trigger('open');
        return self;
    },
    prev: function() {
        var self = this, e = self.element;
        self.currentStep--;
        self._openDialog(e);
        self._trigger('prev');
        return self;
    },
    next: function() {
        var self = this, e = self.element;
        self.currentStep++;
        self._openDialog(e);
        self._trigger('next');
        return self;
    },
    cancel: function() {
        var self = this, e = self.element;
        self.currentStep = 0;
        if (e.dialog('isOpen')) {
            e.dialog('close');
        }
        self._trigger('cancel');
        return self;
    },
    done: function(){
        var self = this, e = self.element;
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