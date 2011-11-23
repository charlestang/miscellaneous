function onDialogOpen(event,ui) {
    var $form = $(this).find('form');
    $form.FormNavigate();
    var formdata = $form.serialize();
    formdata = trimHiddenField($form,formdata);
    $form.data('initialForm', formdata);
    //console.log(formdata);
}

function onDialogBeforeClose(event, ui) {
    var $form = $(this).find('form');
    var formdata = $form.serialize();
    formdata = trimHiddenField($form,formdata);
    //console.log(formdata);
    var $outer_dialog = $(this);

    if ($form.data('initialForm') == formdata) {
        return true;
    } else {
//        var msg = 'Do you really want to leave this page ? ';
//        var html = '<div><p>' + msg + '</p></div>';
//        $(html).dialog({            
//            resizable:false,
//            stack:true,
//            modal:false,
//            title:'Confirm?',
//            buttons:{
//                Ok:function () {
//                    $outer_dialog.unbind('dialogbeforeclose');
//                    $outer_dialog.dialog('close');
//                    global_formNavigate = true;
//                    $(this).dialog('close');
//                    $outer_dialog.bind('dialogbeforeclose', onDialogBeforeClose); 
//                },  
//                Cancel:function(){
//                    $(this).dialog('close');
//                }
//            }   
//        });
        if (confirm('Do you really want to leave this page ?')){
            //$outer_dialog.unbind('dialogbeforeclose');
            $outer_dialog.unbind('wizarddialogbeforecancel');
            $outer_dialog.dialog('close');
            global_formNavigate = true;
            //$outer_dialog.bind('dialogbeforeclose', onDialogBeforeClose);
            $outer_dialog.bind('wizarddialogbeforecancel', onDialogBeforeClose);
        } else {
        }
    }
    return false;
}
function trimHiddenField($form,formdata) {
    $('input:hidden', $form).each(function(){
        var name = $(this).attr('name');
        var value = $(this).attr('value');
        formdata=formdata.replace(name+'='+value, '');
        formdata=formdata.replace('&&','&');
        formdata=formdata.replace(/^&/,'');
    });
    return formdata;
}

var global_formNavigate = true;     // Js Global Variable for onChange Flag
(function($){
    $.fn.FormNavigate = function(message) {
        if (!message || message == '') {
            message = 'Do you really want to leave thie page ? ';
        }
        window.onbeforeunload = confirmExit;
        function confirmExit( event ) {
            if (global_formNavigate == true) {
                event.cancelBubble = true;
            } else  {
                return message;
            }
        }
        $(this+ ":input[type=text], :input[type='textarea'], :input[type='password'], :input[type='radio'], :input[type='checkbox'], :input[type='file'], select").change(function(){
            global_formNavigate = false;
        });
        //to handle back button
        $(this+ ":input[type='textarea']").keyup(function(){
            global_formNavigate = false;
        });
        $(this+ ":submit").click(function(){
            global_formNavigate = true;
        });
    }
})(jQuery);
