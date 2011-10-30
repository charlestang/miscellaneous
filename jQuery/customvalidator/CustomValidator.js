(function($){
//the 1st custom validator
$.validator.addMethod(
	"numberchar", 
	function(value, element) {
		return this.optional(element) || /(\w|\d)+/.test(value);
	},
	"The input value should be number or letter"
);

//the 2nd custom validator
$.validator.addMethod(
);

}(jQuery));
