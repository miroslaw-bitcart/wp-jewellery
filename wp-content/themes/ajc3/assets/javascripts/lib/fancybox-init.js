;(function($){

	$(document).ready(function() {

		var productDetails = $( '#product-json' ).data('json');

		$(".fancybox-thumb").fancybox({
			live		: false,
			mouseWheel 	: false,
			nextEffect  : 'none',
	        prevEffect  : 'none',
			closeBtn	: true,
			afterLoad: function() {
				this.title = '<p class="product-title">' + productDetails.name + '</span><span class="product-period">' +  productDetails.period + '</span><span class="product-price">' +  productDetails.price + '</span>';
			},
		});

		$( '.thumbnails .fancybox-thumb' ).unbind( 'click' );

		var gallery = new productGallery( $( '.product-single' ) );

		gallery.init();

	});

})(jQuery);