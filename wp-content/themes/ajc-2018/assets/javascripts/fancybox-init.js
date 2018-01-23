;(function($){
	$(document).ready(function() {
		var productDetails = $( '#product-json' ).data('json');

		$('body').on('click', '.fancybox-thumb', function(e) {
			e.preventDefault();

			$('.zoom-x-large img').attr('src', $(this).attr('href'));
			$('.zoom-x-large').attr('data-thumb-index', $(this).attr('data-thumb-index'));
		});

		$('body').on('click', 'a.zoom-x-large', function(e) {
			e.preventDefault();

			var href_group = [];
			$(".fancybox-thumb").each(function(index) {
				if( $('#product-price').is(':first-child') )
					price = $('#product-price span.amount').html();
				else
					price = 'Sold';
				href_group.push({'href': $(this).attr('original-href'), 'title': $('#product-title').html()+ " (" + $('#product-sku').data('sku') + "), "  
					+ price});
			});

			$.fancybox.open(
				href_group,
				{
					live		: false,
					mouseWheel 	: false,
					nextEffect  : 'none',
			        prevEffect  : 'none',
					closeBtn	: true,
					index 		: $(this).attr('data-thumb-index'),
					beforeLoad: function() {
						$.fancybox.showLoading();
					},
					afterLoad: function() {
						$.fancybox.hideLoading();
					},
					helpers		: {
						thumbs		: {
							width	: 100,
							height	: 75,
						},
						title : {
				            type : 'outside'
				        },
				        source  : function(current) {
				            return $(current.element).data('product-small');
				        }
					}
				}
			);
		});

		// $( '.thumbnails .fancybox-thumb' ).unbind( 'click' );
		var gallery = new productGallery( $( '.product-single' ) );
		gallery.init();
	});
})(jQuery);