(function($){
    $.fn.dialogWizard = function() {
        
        return this.each(function(){
            //pick up all steps and hide them
            var $steps = $('.step', this).hide();
        
            for (var idx in $steps.get()) {

                var $dialog = $($steps[idx]).dialog({
                    'autoOpen':false
                });
            
                var buttons = {};

                (function(i){
                    if (idx == 0) {
                        console.log('set 0 buttons');
                        buttons = {
                            'Cancel':function(){
                                $($steps[i]).dialog('close');
                                $steps.dialog('destroy');
                            },
                            'Next':function(){
                                $($steps[i]).dialog('close');
                                $($steps[parseInt(i)+1]).dialog('open');
                            }
                        }
                    } else if (idx == $steps.length - 1) {
                        console.log('set last buttons');
                        buttons = {
                            'Prev' : function(){
                                $($steps[i]).dialog('close');
                                $($steps[parseInt(i)-1]).dialog('open');
                            },
                            'Submit' : function(){
                                //todo
                                $($steps[i]).dialog('close');
                                $steps.dialog('destroy');
                            }
                        }
                    } else {
                        console.log('set middle buttons');
                        buttons = {
                            'Prev' : function(){
                                $($steps[i]).dialog('close');
                                $($steps[parseInt(i)-1]).dialog('open');
                            },
                            'Next' : function(){
                                //todo
                                $($steps[i]).dialog('close');
                                $($steps[parseInt(i)+1]).dialog('open');
                            }
                        }
                    }
                })(idx);
                $dialog.dialog('option', 'buttons', buttons);
            }
        
            $($steps[0]).dialog('open');
        });
        
    }
})(jQuery);