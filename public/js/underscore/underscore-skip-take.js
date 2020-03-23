_.mixin({
	
	skipTake: function(array, options) {

		options = _.extend({skip:0, take:0}, options || {});

		return _(array)
			.chain()
			.rest(options.skip)
			.first(options.take || array.length - options.skip)
			.value();
		}    	
});