(function($){
    $.fn.pvalid = function(options) {
        var validator = {
            numchar: function(value){
                return /[0-9a-zA-Z ]+/.test(value);
            },
            number: function(value) {
                return /\d+/.test(value);
            },
            email: function(value) {

            },
            domain: function(value) {

            },
            url: function(value) {

            }
        };

        var messages = {
            numchar: 'This field should be numbers and characters only.',
            number: 'This field should be numbers only'
        };

        var valid = true;
        $('input', this).each(function(){
            var tags = $(this).attr('class'), value = $(this).val(), pass = true;
            if (!tags) return;
            var validators = tags.split(' ');
            for (var i in validators) {
                if (validator[validators[i]]) {
                    var ret = validator[validators[i]](value);
                    if (!ret) {
                        $(this).after('<span class="err-msg">' + messages[validators[i]] + '</span>');
                    } 
                    pass = pass && ret;
                }
            }   
            if (pass) {
                $('.err-msg', $(this).parent()).remove();
            }
            valid = valid && pass;

        });
        if (options.pass && valid) {
            options.pass.call(this);
        }
        if (options.unpass && !valid) {
            options.unpass.call(this);
        }
        return this;
    };
})(jQuery);

