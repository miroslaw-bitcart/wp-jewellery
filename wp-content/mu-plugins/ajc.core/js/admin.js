(function( $ ) {
	
	$( document ).ready( function() {
		$( '#newsletter-markup' ).click( function() {
			var self = this;
			var p = $.post( ajaxurl, {
				action: 'get_group_html',
				id: $( this ).data( 'id' )
			});
			p.then( function( result ) {
				var markup = ( $.parseJSON( result ).markup );
				$( self ).next( '#markup-output' ).text( markup );
			});
			return false;
		});
	});
})(jQuery)