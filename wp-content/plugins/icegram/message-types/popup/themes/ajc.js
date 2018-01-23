/**
 * BAKUP DONE BEFORE ANY CHANGES. MADE AT 2014-03-08 20:27.
 * I WILL USE THIS AS A CHECK POINT. 
 * 
 * If I don't complet the task, will revert the server back to this version.
 */

jQuery( document ).ready( function( $ ) {

	/**
	 * CONTENTS
	 *
	 * 1. Knockout Bindings
	 * 2. Facebook Integration
	 * 3. Miscellaneous cosmetics
	 */
	
	/**
	 * Knockout Bindings
	 */
	
	AJC = AJC || {};
	
	// initialise view
	(function() {

		if( $( '.main-shop' ).length ) {
			AJC.view = new DynamicView;
			AJC.view.getProducts();
			ko.applyBindings( AJC.view );
			return;
		}

		if( $( '.favourites-summary' ).length ) {
			AJC.view = new FavouriteProductsView();
			ko.applyBindings( AJC.view );
			return;
		}

		if( $( '.term-tabset' ).length ) {
			var tabs = $( '.term-tabset' );
			ko.applyBindings( new AJC_TermTabs(tabs) );
			return;
		}

		if( $( '.recommended-products' ).length ) {
			AJC.view = new AJC_ProductsView();
			if( AJC.genericProducts.products ) {
				AJC.view.addProducts( AJC.genericProducts.products );	
			}

			AJC.view.favouriteAll = function() {
				ko.utils.arrayMap( this.products(), function( p ) {
					p.favouriteButton.makeFavourite();
				} );
			}
			ko.applyBindings( AJC.view );
			return;
		}

		if( $( '.page-jewellery' ).length ) {
			AJC.view = {
				dropdown: new AJCDropdown
			};
			ko.applyBindings( AJC.view );
			return;
		}

	})();

	if( $( '#ajc_signup_form' ).length ) {
		AJC_SignupForm.init();
		ko.applyBindings( AJC_SignupForm, document.getElementById( 'ajc_signup' ) );
	}

	if( $( '.ajc_survey_form' ).length ) {
		window.AJC_SurveyForm = new AJC_SurveyForm;
		window.AJC_SurveyForm.init();
		ko.applyBindings( AJC_SurveyForm, document.getElementById( 'ajc_survey_form' ) );
	}

	if( $( '.sequence' ).length ) {
		window.signup = new AJC_SignupSequence( $( '.sequence' ) );
	}

	if( $( '#trackable' ).length ) {
		var orders = $( '#trackable .order-tracking' ).get();
		$( orders ).each( function( i, e ) {
			ko.applyBindings( new AJC_Tracking( $(e).data('id') ), e );
		} ) ;
	}

	/**
	 * Facebook Integration
	 */

	var populateFBSignup = function( facebookData, userinfo ) {
		AJC_SignupForm.connectedToFB( true );
		AJC_SignupForm.avatar( "http://graph.facebook.com/" + userinfo.username + "/picture?width=200&height=200" );
		AJC_SignupForm.at_fb_id( facebookData.authResponse.userID );
		AJC_SignupForm.at_fb_token( facebookData.authResponse.accessToken );
		AJC_SignupForm.first_name( userinfo.first_name );
		AJC_SignupForm.last_name( userinfo.last_name );
		AJC_SignupForm.email( userinfo.email );
		$( '#manual_signup' ).hide();
		AJC_SignupForm.show();
	}

	$( document ).on( 'ArchetypeFB_Signup', function( e, facebookResponse, userinfo ) {
		populateFBSignup( facebookResponse, userinfo );
		AJC_SignupForm.show();
	} );

	$( document ).on( 'ArchetypeFB_Login', function( e, response, userinfo ) {
		if( response.newUser ) {
			window.location = '/signup?failed_login=true';
			return false;
		}
		window.location = '/';
	} );

	/**
	 * FB AJAX loading animations etc
	 */
	$( document ).on( 'ArchetypeFB_AJAXstart', function( e, clicked ) {
		$( clicked ).append( $( '<span>' ).addClass( 'icon-refresh icon-spin space-left' ) );
		$( clicked ).addClass( 'loading' );
	});
	$( document ).on( 'ArchetypeFB_AJAXstop', function( e, clicked ) {
		$( clicked ).text( 'Connected to Facebook' ).parent().addClass( 'connected-text connected' );
		$( clicked ).append( $( '<span>' ).addClass( 'ion-checkmark space-left' ) );
		$( clicked ).removeClass( 'loading' );
		$( '.icon-refresh .icon-spin' ).remove();
	});

	/**
	 * Miscellaneous cosmetics
	 */
	$( '#loginform #user_login' ).attr( 'placeholder', 'Username' );
	$( '#loginform #user_pass' ).attr( 'placeholder', 'Password' );

	 /** use a range slider in the product search form */
	$( "#price-search-slider" ).slider({
		range: true,
		min: 0,
		step: 500,
		max: 5000,
		slide: function( event, ui ) {
			$( "#price-low" ).text( '£' + ui.values[ 0 ] );
			$( "#price-high" ).text( '£' + ui.values[ 1 ] );
		},
		values: [ 0, 5000 ],
		change: function( event, ui ) {
			$( 'input[name=_priceLow]' ).val( ui.values[0] );
			$( 'input[name=_priceHigh]' ).val( ui.values[1] );
		}
	});

	 /* Modals */

	/**
	 * Link .modal-triggers to requests for modal content
	 * add a data-modal="modalname" attr to the link 
	 * specifying which one to fetch
	 */
	$( '.modal-trigger' ).click( function(e) {
		$(e.target).addClass('loading');
		var modal = $(this).data( 'modal' );
		var args = $(this).data( 'modal-args' );
		AJC.modals = AJC.modals || new AJC_Modals;
		AJC.modals.showModal( modal, args, {
			callback: function() { 
				$(e.target).removeClass('loading');
			}
		} );
		return false;
	});

	/** Back to top **/
	(function($) {
	   if( $('#back_to_top').length == 0 )
		   return;
	   var scroll_timer;
	   var displayed = false;
	   var $back_to_top = $( '#back_to_top' );
	   var top = $(document.body).children(0).position().top;
	   $back_to_top.hide();
	   $( window ).scroll( function () {
			if($(window).scrollTop() <= top) {
				displayed = false;
				$back_to_top.fadeOut(300);
			}
			else if(displayed == false) {
				displayed = true;
				$back_to_top.stop(true, true).fadeIn( 300 );
			} 
		});

		$( '#back_to_top' ).click( function(e) {
		   e.preventDefault();
		   $( 'html, body' ).animate( {
			   scrollTop: 0
		   }, 500 );
		});    
	})( jQuery );
} );


/**
 * Cache modals in an object
 */
var AJC_Modals = function() {

	var $ = window.jQuery;

	var self = this;
	this.modals = {};

	/**
	 * Show a modal
	 * @param  {string} modalName    the name (ie the handle of the modal on the back end)
	 * @param  {object} args         args to pass to the back end when requesting the modal markup
	 * @param  {object} modalOptions additional options to customise the modal
	 * @return {void}              
	 */
	this.showModal = function( modalName, args, modalOptions ) {

		// make a unique name for the modal from its name and args
		var key = modalName + JSON.stringify( args );

		var modalOptions = $.extend( {
			callback: function(){},
			autoClose: false,
		}, modalOptions );

		if( modalOptions.autoClose ) {
			modalOptions.onOpen = function(dialog) { 
				dialog.overlay.show();
				dialog.container.show();
				dialog.data.show();
				window.setTimeout( function() {
					dialog.container.fadeOut( 100, function() {
						dialog.overlay.fadeOut( 100, function() {
							$.modal.close();
						} )
					});
				}, 100 );
			}
		};

		var modalObject = this.modals[key];
		var self = this;

		/* Try to get the modal out of cache, fetching it if it's not there */        
		if( modalObject === undefined ) {
			self.fetchModal( modalName, args, key, modalOptions ); // will call showModal on callback
			return;
		}

		$.modal( modalObject.markup, $.extend( {
			overlayClose: true,
			onOpen: function (dialog) {
				dialog.overlay.show(100, function () {
					dialog.data.show(1, function () {
						dialog.container.show();	 
					});
				});
			},
			onShow: function() {

				if( modalObject._type === 'product' && !modalObject.isSetup  ) {
					setupProductModal();
					modalObject.isSetup = true;
				} else if (  modalObject._type === 'not-logged-in' ) {
					setupNotloggedIn();
				}

				console.log('xsss');
				setTimeout( function() {
					if( $( '.datepicker' ).length ) { // horrible - sorry
						$('.datepicker').kalendae({
							format: 'dddd Do MMMM YYYY',
							blackout: function (date) {return [1,0,0,0,0,0,1][Kalendae.moment(date).day()];}
						});
					}					
				}, 1000 );

					$('#simplemodal-container')
						.css( {
							height: 'auto'
						} )
						.addClass( 'modal-' + modalName );
						this.setPosition();

				modalOptions.callback.call(this);

			}
		}, modalOptions ) );
	}

	var setupProductModal = function() {
		var modalNode = document.getElementById( 'product-modal' );
		var gallery = new productGallery('#simplemodal-container');
		var productData = $('.product-modal #product-json').data('json');
		ko.applyBindings( new AJC_Product( productData ), modalNode );
		gallery.init();
	}

	var setupNotloggedIn = function() {
		$( '.at_fb_login' ).on( 'click', AT_Facebook.contactFacebook );
	}

	this.fetchModal = function( modalName, args, key, modalOptions ) {
		Archetype.post( 'get_modal', { modal: modalName, args: args }, function( result ) {
			self.modals[key] = new AJC_Modal( result );
			self.showModal( modalName, args, modalOptions );
			return false;
		} );
	}

}

var AJC_Modal = function( data ) {
	this.isSetup = false;
	this.markup = data.markup;
	this._type = data._type;
}

var productGallery = function( el ) {

	var $ = window.jQuery,
		gallery = $( el ).find( '.product-images' ),
		mainView,
		self = this,
		gallerySetup = gallery.data( 'gallery-setup' ) || false;

	this.isNeeded = function() {
		return gallery && !gallerySetup; // are gallery els present, and does it need to setup bindings?
	}

	this.selectImage = function( link ) {
		var newMainImage = $(link).attr( 'href' );
		var newMainImageOriginal = $(link).attr( 'original-href' );
		mainView.attr( 'src', newMainImage );
		mainView.parent().attr('href', newMainImageOriginal);
	}

	this.init = function() {
		gallery.find( '.zoom' ).click( function() {
			self.selectImage( this );
			return false;
		});
		gallerySetup = true;
		gallery.data( 'gallery-setup', gallerySetup );
		mainView = gallery.find( '.wp-post-image' );
	}

}

/**
 * Spinners
 */

AJC.spinner = new Spinner({
	lines: 11, // The number of lines to draw
	length: 3, // The length of each line
	width: 3, // The line thickness
	radius: 7, // The radius of the inner circle
	corners: 1, // Corner roundness (0..1)
	rotate: 0, // The rotation offset
	direction: 1, // 1: clockwise, -1: counterclockwise
	color: '#999', // #rgb or #rrggbb
	speed: 2, // Rounds per second
	trail: 100, // Afterglow percentage
	shadow: false, // Whether to render a shadow
	hwaccel: false, // Whether to use hardware acceleration
	className: 'spinner', // The CSS class to assign to the spinner
	zIndex: 2e9, // The z-index (defaults to 2000000000)
	top: '0', // Top position relative to parent in px
	left: '0' // Left position relative to parent in px
});

AJC.showSpinner = function( element, callback ) {
	element = element || 'initial-spinner';
	el = document.getElementById( element );
	this.spinElement = el;
	jQuery( el ).addClass( 'spinning' );
	AJC.spinner.spin( el );
}

AJC.hideSpinner = function() {
	jQuery( this.spinElement ).removeClass( 'spinning' );
	AJC.spinner.stop();
}

/*
Knockout VM for the Shop view
 */

// filtermapping configuration
var fieldFilterMapping = {
	create: function( obj ) {
		return ko.observableArray( obj.data );
	}
}

var AJCDropdown = function() {

	var self = this;
	var $ = window.jQuery;
	this.open = ko.observable();

	this.showChildren = function(t, e) {
		var clicked = $(e.target);
		var children = clicked.siblings('.sub-menu')
		var p = $(e.target).siblings('.sub-menu').slideToggle('fast').promise();
		p.done( function(animated) {
			if( animated.is(':visible') )
				self.open(true)
			else 
				self.open(false);
		});
		return false;
	}

	this.DOMinit = function(el, d){
		this.open( $(el).data('current') );
	}
}


var AJC_ProductsView = function() {
	
	this.products = ko.observableArray([]);
	this.ready = ko.observable( false );

	var t = 0; //timers for show and hide delays
	this.showProduct = function( element ) {
		if (element.nodeType === 1) {
			jQuery(element).hide();
			setTimeout( function() {
				jQuery(element).fadeIn( 300 );
			}, t += 75 );
		}
	}

	this.hideProduct = function( element ) {
		jQuery( element ).remove();
	};

	this.ready.subscribe( function() {
		t = 0;
	});

}

AJC_ProductsView.prototype = jQuery.extend( AJC_ProductsView.prototype, {
	/*
	Create a batch of of AJC_Products from an array of raw objects
	And add them to the products() observable.
	 */
	addProducts: function( products ) {
		var self = this;
		this.products( _.map( products, function( product ) {
			return new AJC_Product( product );
		} ) );
	}
} );
 
var FavouriteProductsView = function() {

	var $ = window.jQuery;
	var self = this;

	var bindProduct = function( p ) {
		$(p).on('favourite-added', function(e, product) {
			self.primaryProductsView.products.push( p );
			product.favourite(true);
		});	
		$(p).on('favourite-removed', function(e, product) {
			//$(".tipsy").remove();
			self.primaryProductsView.products.remove( p );
			product.favourite(false);
		});
	}

	if( AJC.relatedProducts.products ) {
		this.relatedProductsView = new AJC_ProductsView();
		this.relatedProductsView.products( _.map( AJC.relatedProducts.products, function( product ) {
			var p = new AJC_Product( product );
			bindProduct( p );
			return p;
		} ) );
	}

	if( AJC.favouriteProducts.products ) {
		this.primaryProductsView = new AJC_ProductsView();
		this.primaryProductsView.products( _.map( AJC.favouriteProducts.products, function( product ) {
			var p = new AJC_Product( product );
			bindProduct( p );
			return p;
		} ) );
	}
}

var DynamicView = function() {

	AJC_ProductsView.call( this );

	var $ = window.jQuery;
	var self = this;
	var contentArea = $('.content');
	var pagePath = window.location.protocol+'//'+window.location.host+window.location.pathname;
	var _productCache;
	var lowPriceDefault = 0;
	var highPriceDefault = 5000;

	this.dropdown = new AJCDropdown;
	
	/**
	 * Get the url of the current page with query string
	 */
	var getURL = function() {
		var queryString = getQueryString();
		result = (pagePath + queryString); 
		return result;
	}

	var getSelectedSortOption = function() {
		var opt = self.selectedSortOption();
		if ( opt )
			return opt.value;
	}

	/**
	 * Get the query string with current filter state attached
	 */
	var getQueryString = function() {
		var queryString = '?';

		if( self.priceLow() !== lowPriceDefault || self.priceHigh() !== highPriceDefault ) {
			queryString += '_priceLow=' + self.priceLow();
			queryString += '&_priceHigh=' + self.priceHigh() + '&';
		}

		for( filter in self.publicFilters() ) {
			filter = self.publicFilters()[filter];
			queryString += '_' + filter.name + '=' + filter.value.join(',') + '&';
		}
		return queryString.slice(0, -1); // lose the last '&'
	}

	/*
	Set up the attrs for this shop view, like the current search term,
	any filters applied, and the array of products we're showing
	 */
	this.search = AJC.search || 0;
	this.publicFilters = ko.observableArray( [] );
	this.foundProducts = ko.observable();
	this.products = ko.observableArray( [] );
	this.noMoreProducts = ko.observable( false );
	this.selectedSortOption = ko.observable();
	ko.mapping.fromJS( { _filters: AJC.filter }, fieldFilterMapping, self );
	this._filters.ajc_p_status = ko.observableArray( AJC.productStatus ? [AJC.productStatus] : [] );
	this._filters.collection = ko.observableArray( [] );
	this.scrollInit = false; // so we dont init infinite scroll twice
	this.gettingMore = ko.observable( false );
	this.priceLow = ko.observable( AJC.priceRange[0] );
	this.priceHigh = ko.observable( AJC.priceRange[1] );

	this.sortOptions = ko.observableArray([
		{value: '', name : 'Date Added' },
		{value: '_price-high', name : 'Price, High to Low' }, 
		{value: '_price-low', name : 'Price, Low to High' },
		{value: '_favourited-high', name : 'Most Favourited' },
		{value: '_views-high', name : 'Most Viewed' },
	]);

	this.ready.subscribe( function( value ) {
		var view = $('.dynamic-products');
		var count = $('.search-results');
		if( value ) {
			AJC.hideSpinner();
			view.fadeIn( 'fast' );
			count.fadeIn( 'fast' );
			$('#sort-by .dropdown').css( 'display', 'block' );
			setTimeout( function() {
				if( !self.scrollInit )
					scrollInit();
			}, 600 );

		} else {
			AJC.showSpinner();			
			count.fadeOut( 'slow', function() {} );	
			view.fadeOut( 'slow', function() {
			});
		}
	});

	this.priceHighText = function() {
		var high = this.priceHigh();
		if( !high ) 
			return false;
		if( high === 5000 ) {
			return '5000+';
		} else {
			return high;
		}
	}

	/**
	 * Set up the slider and select dropdown
	 */
	$( document ).ready( function() {
		$( "#slider-range" ).slider({
			range: true,
			min: lowPriceDefault,
			step: 500,
			animate: false,
			max: highPriceDefault,
			slide: function( event, ui ) {
				$( "#price-low" ).text( '£' + ui.values[ 0 ] );
				$( "#price-high" ).text( '£' + ui.values[ 1 ] );
			},
			values: [ self.priceLow(), self.priceHigh() ],
			change: function( event, ui ) {
				self.priceLow( ui.values[0] );
				self.priceHigh( ui.values[1] );
				updateProducts();
			}
		});

		/**
		 * This is because we are using a <ul> instead of a <select> for selecting a value
		 */
		var selected = $( '#sort-option-selected' );
		var dropdownUl = $( '#sort-options .dropdown-inner' );
		_.map( self.sortOptions(), function( option ) {
			dropdownUl.append( "<li data-option='" + option.value + "'>" + option.name + '</li>' );
		} );
		dropdownUl.find( 'li' ).click( function() {
			self.selectedSortOption( {value: $(this).data( 'option' ) } );
			selected.text( $(this).text() );
		});

	});

	var isFiltering = function() {
		return getQueryString().length > 0;
	}

	this.allTitle = function() {
		if( isFiltering() )
			return 'Search Results';
		else
			return 'All';
	}

	this.foundProductsString = function() {
		var count = self.foundProducts();
		if( count > 1 ) {
			return count + ' items found';
		} else if ( count < 1 ) {
			return '';
		} else if ( count = 1 ) {
			return '1 item found';
		}
	}

	if( !self.products().length && typeof AJC.serverProducts !== 'undefined' ) {
		self.serverProducts = true;
		self.addProducts( AJC.serverProducts.products );
		if( AJC.serverProductsOptions ) {
			for( var option in AJC.serverProductsOptions ) {
				self._filters[option].push( AJC.serverProductsOptions[option] );
			}
		}
		self.foundProducts( parseInt( AJC.serverProducts.foundProducts ) );
	}

	this.filterChange = ko.computed( function () {
		for( var key in self._filters )  {
			var obj = self._filters[key]; 
			 if( ko.isObservable(obj))
				obj();                 
		}
	});

	/*
	Handle state changes in the filter and update the view manually
	 */
	var updateProducts = function() {
		//scroll( 0, 0 );
		resetScroll();
		self.getProducts();
		//$( '.dropdown-inner' ).hide();
		self.updatePublicFilterList();
		self.updateURL();
	};

	this.filterChange.subscribe( updateProducts );
	this.selectedSortOption.subscribe( updateProducts );

	this.removeFilter = function( key, value ) {
		self._filters[key].remove( value );
	}

	this.filtersToShow = function() {
		return self.search || self.publicFilters().length;
	}

	var getQueryAttributes = function() {
		return {
			filter: self._filters, 
			price_low: self.priceLow(), 
			price_high: self.priceHigh(), 
			search: self.search, 
			archive: AJC.archive,
			sort: getSelectedSortOption(),
			collection: (function() {
				var ex = new RegExp('/collection/([^/]+)/?'); // hubris
				var loc = window.location.toString();
				var matches = loc.match(ex);
				if( matches && matches[1] )
					return matches[1]; 
			})()
		}
	}

	this.getPublicFilterNicename = function( filterSlug ) {
		return $( 'label[for="' + filterSlug + '"]' ).text(); 
	}

	this.updatePublicFilterList = function() {
		self.publicFilters( [] );
		var value;

		//filters 
		for( var key in self._filters ) {
			if( key === 'type' ) // not public
				continue;
			value = self._filters[key]();
			if( value.length ) {
			   self.publicFilters.push( { name: key, value: value } );
			}
		}
	}

	this.clearSearch = function() {
		var url = getURL();
		var split = url.split( '?' );
		var composed = split[0] + 'shop/';
		if( split[1] )
			return composed + '?' + split[1];
		return composed;
	}

	this.updateURL = function() {
		history.replaceState( null, null, getURL() );
	}

	this.clearPublicFilters = function() {
		for( var f in self.publicFilters() ) {
			var filterKey = self.publicFilters()[0].name;
			self._filters[filterKey].removeAll();
		}
	}

	this.getProducts = function() {

		self.ready( false );
		self.noMoreProducts( false );

		Archetype.post( 'get_products', getQueryAttributes(), function( result ) {

			self.init = true;

			self.addProducts( result.products );

			self.ready( true );

			if ( self.products().length >= result.foundProducts ) {
				self.noMoreProducts( true );
			} else {
				self.noMoreProducts( false );
			}

			self.foundProducts( result.foundProducts );

		} );

	}

	this.getMoreProducts = function( callback ) {
		AJC.showSpinner( 'loading-more' );
		this.gettingMore(true);
		Archetype.post( 'get_products', 
			$.extend( getQueryAttributes(), { offset: self.products().length } ),
			function( result ) {
				AJC.hideSpinner();
				self.gettingMore(false);
				var freshProducts = _.map( result.products, function( product ) {
					return new AJC_Product( product );
				} );
				if ( ! freshProducts.length || self.products().length >= parseInt( result.foundProducts ) ) {
					self.noMoreProducts( true );
				}
				self.products( _.union( self.products(), freshProducts ) );
				scrollInit();
				callback();
			} );
	}

	jQuery( document ).ready( function() {
		$( '.lateral-link' ).click( function() {
			var oldUrl = $(this).attr( 'href' );
			$(this).attr( 'href', oldUrl + getQueryString() );
		});        
	});

	var resetScroll = function() {
		$('#loading-more').waypoint('destroy');
		$('#loading-more').remove();
		self.scrollInit = false;
	}

	var scrollInit = function() {

		if( $('#loading-more').length )
			resetScroll(); // trigger already present - eliminate it to start afresh
		
		if( self.noMoreProducts() ) {
			$( '_loader' ).fadeOut( function( loader ) {
				$(loader).remove();
			} );
			return; // no point
		}

		var scrollEl = '<div style="clear:both;" id="loading-more"></div>';
		$('._loader').append( scrollEl );

		$('#loading-more').waypoint( function(event, dir){ 
			self.getMoreProducts( function() {
				var scrollEl = '<div style="clear:both;" id="loading-more"></div>';
				$('._loader').append( scrollEl );
			} );
		}, {
			offset: window.outerHeight + 50
		});

		self.scrollInit = true;
	};

	this.updatePublicFilterList(); // show what filters are in effect on the front end
}

DynamicView.prototype = Object.create( AJC_ProductsView.prototype );



/*
Knockout VM for the signup form
 */
var AJC_SignupForm = new function() {

	var form;
	var self = this;

	this.connectedToFB = ko.observable( false );
	this.formHidden = ko.observable( true );
	this.formHasErrors = ko.observable( false );
	this.avatar = ko.observable();

	this.show = function() {
		form.slideDown();
		this.formHidden( false );
	}

	this.formHasErrors.subscribe( function( state ) {
		if( state === true ) {
			self.show();
		}
	} );

	this.init = function( ) {
		form = jQuery( '#ajc_signup_form' );
		// map the passed keys onto the VM
		ko.utils.arrayMap( AJC.signup, function( el ) {
			var field = form.find( 'input[name=' + el + ']' )
			var existing = field.val();
			self[el] = ko.observable( existing );

			if( field.data( 'error' ) ) {
				self.formHasErrors( true );
			}

		} );

		self.fullName = ko.computed( function() {
			return self.first_name() + ' ' + self.last_name();
		});
	}
}

var AJC_TermTabs = function( el ) {

	var $ = window.jQuery;
	var self = this;
	var tabs = {};
	var pinterestLoaded = false;
	var productsBound = false;

	this.id = $(el).data('id');
	this.slug = $(el).data('term');

	this.currentTab = ko.observable( false );
	this.currentTabContent = ko.observable( '' ); 

	this.currentTab.subscribe( function( tab ) {
		self.showTab( tab );
		self.updateURL();
	} );

	this.showTab = function( tab ) {
		if( typeof tabs[tab] !== 'undefined' ) {
			$( '.tab-content' ).removeClass('active');
			$( '.tab-content.' + tab ).addClass('active');
			if( tab === 'collection' && !productsBound ) {
				// set up the shop view
				window.DynamicView = new DynamicView;
				DynamicView._filters.period( [self.slug] );
				DynamicView.getProducts();
				productsBound = true;
				ko.applyBindings( DynamicView, $( '.main-shop').get()[0] );
			}
		} else {
			self.fetchTab( tab );
			return false;
		}
	}

	this.updateURL = function() {
		
		var queryString, loc;

		queryString = '?tab=' + self.currentTab();
		loc = window.location.protocol+'//'+window.location.host+window.location.pathname;
		history.replaceState( null, null, loc + queryString );
	}

	this.fetchTab = function( tab, args ) {
		AJC.showSpinner( 'tab-loading' );
		args = $.extend( {}, args );
		Archetype.post( 'get_tab', { tab: tab, args: args, term_id: self.id }, function( result ) {
			tabs[tab] = true;
			$( '.tab-content.'+tab ).html( result.markup );
			if( tab === 'look-book' && !pinterestLoaded ) {
				// we need to reinitialize pinterest's JS so they can inject content into the placeholder tag they've given us
				$.ajax({ url: 'http://assets.pinterest.com/js/pinit.js', dataType: 'script', cache:true, success: function() {
					
				} });
			}
			AJC.hideSpinner( 'tab-loading' );
			self.showTab( tab );
		} );
	}

	if( AJC.currentTab !== '' )
		self.currentTab( AJC.currentTab );
	else
		self.currentTab( 'collection' );
}

var AJC_FavouriteButton = function(id, status, product) {

	var self = this; 
	var $ = window.jQuery;

	this.favourite = status; // link to parent observable
	this.favcount = product.favcount; // link to parent observable
	this.id = id;

	this.toggle = function( ) {

		if( !Archetype.isUserLoggedIn() ) {
			AJC.modals.showModal( 'not-logged-in', { referer: product.permalink } );
			return;
		}

		Archetype.post( 'toggle_favourite', { product_id: self.id }, function( result ) {
			self.favcount( result.favcount );   
			self.favourite( result.favourite );
			if( self.favourite() ) {
				AJC.modals.showModal( 'added-favourite', { product: self.id }, { autoClose: true } );
				$(product).trigger( 'favourite-added', [product] );
			} else {
				$(product).trigger( 'favourite-removed', [product] );
			}
		});
	}	

	this.makeFavourite = function() {
		Archetype.post( 'toggle_favourite', { product_id: self.id, force: true }, function( result ) {
			self.favourite( result.favourite );
			self.favcount( result.favcount );   
			if( self.favourite() ) {
				AJC.modals.showModal( 'added-favourite', { product: self.id }, { autoClose: true } );
				$(product).trigger( 'favourite-added', [product] );
			} else {
				$(product).trigger( 'favourite-removed', [product] );
			}
		});
	}

	this.favouriteTitle = ko.computed( function() {
		var fav = self.favourite();
		return fav ? 'Remove item from your favourites' : 'Add to your favourites';
	});

	var dominit;

	this.DOMInit = function(el) {

		if( dominit )
			return;

		this.favcount( $(el).data('_favcount') );
		this.favourite( $(el).data('_favourite') );
		dominit = true;
	}
}

/**
 * Data structure for the AJC Products we will get from the API
 * @param Object data product details from the API
 */
function AJC_Product( data ) {

	data = data || {};
	var $ = window.jQuery;
	var self = this;
	var altImageSrc = data.alternativeImageSrc || '';

	AJC.modals = AJC.modals || new AJC_Modals;

	this.name = data.name || 'name';
	this.id = data.id || 0;
	this.permalink = data.permalink || '';
	this.imageSrc = ko.observable( data.imageSrc || '' );
	this.price = data.price || '';
	this.ollysPick = data.ollysPick || false;
	this.status = data.status || 'available';
	this.favcount = ko.observable( data.favcount || 0 );
	this.show = ko.observable( true );
	this.dateAdded = ko.observable( data.dateAdded || '' );
	this.favourite = ko.observable( data.favourite );

	this.favouriteButton = new AJC_FavouriteButton(this.id, this.favourite, this);

	this.link = function() {
		return '<a href="' + self.permalink + '">' + self.name + '</a>';
	}

	this.getPrice = function() {
		if( self.status == 'sold' )
			return 'Sold';
		if( self.status == 'on_hold' )
			return 'On Hold';
		return self.price;
	}

	this.getStatus = function () {
		return self.status;
	}

	this.quickView = function() {
		AJC.modals.showModal( 'product', { 'product': this.id } );
		return false;
	}

	// Flip images on hover
	var imagesFlipped, flipping = false; 

	this.originalImage = function() {
		clearTimeout( flipping );
		if( imagesFlipped ) 
			flipImage( 100 );
	}

	// switch the current imagesrc with the alternative.
	this.altImage = function() {
		if( imagesFlipped )
			return;
		flipImage( 100 );
	}

	var flipImage = function( delay ) {
		var temp = self.imageSrc();
		flipping = setTimeout( function() {
			self.imageSrc( altImageSrc );
			altImageSrc = temp;
			imagesFlipped = !imagesFlipped;
		}, delay );
	}
} 

/*
Knockout VM for the Survey form
 */
var AJC_SurveyForm = function() {

	var $ = window.jQuery;
	var self = this;
	var form;

	this.init = function() {

		// create an element for each survey field
		ko.utils.arrayMap( AJC.survey, function( el ) {
			self[el] = ko.observableArray( [] );
		} );

		form = jQuery( '#ajc_survey_form' );

		// set up all the LIs in each of the survey pages
		// in the self.pictureLIs hash 
		self.pictureLIs = (function() {

			obj = {};

			for( var q in AJC.survey ) {
				obj[AJC.survey[q]] = ko.utils.arrayMap( form.find( 'li[data-group='+AJC.survey[q]+']' ), function( el ) { 
					return new AJC_PictureLI( el );
				} );
			}

			return obj;
		   
		})();

		// make a computed observable that responds to changes in each survey page
		self.values = ko.computed( function() {
			return (function() {
				var obj = {};
				for( var q in AJC.survey ) {
					obj[AJC.survey[q]] = self[AJC.survey[q]]();
				}
				return obj;
			})();
		});

		// when the survey page changes update the view state (the pictureLIs)
		self.values.subscribe( function( updated ) { 
			for ( var group in updated ) {
				var values = updated[group];
				var LIs = self.pictureLIs[group];
				ko.utils.arrayForEach( LIs, function( li ) {
					li.setStatus( inArray( li.id, values ) );
				} );
				self[group].allSelected = false;
			}

		} );
	}

	this.selectAll = function( group ) {

		if( self[group].allSelected === true ) {
			self.deselectAll( group );
			self[group].allSelected = false;
			return;
		}

		var els = [];
		var LIs = self.pictureLIs[group];
		ko.utils.arrayForEach( LIs, function( li ) {
			els.push( li.id.toString() /* match type with added ids, or observableArray won't work properly */ ); 
		} );
		self[group]( els );
		self[group].allSelected = true; // make a note that this group has been selectAll'd
	}

	this.deselectAll = function( group ) {
		self[group]( [] );
	}

};

var AJC_SignupSequence = function( steps ) {

	var self = this; 
	var maxsteps = steps.length;

	this.current = -1;
	this.steps = steps;

	this.next = function() {
		if( self.current < maxsteps ) {
			self.current++;
		}
		self.refresh( self.current );
	}

	this.prev = function() {
		if( self.current > 0 ) {
			self.current--;
		}
		self.refresh( self.current );
	}

	this.refresh = function( current ) {
		jQuery( self.steps ).hide();
		jQuery( self.steps[current] ).show();
	}

	this.getCurrentStep = function() {
		if( self.steps[self.current] )        
			return self.steps[self.current];
	}

	this.init = function( $ ) {   
		$( self.steps ).hide();
		self.next(); // set up the first step
	}( jQuery );

}

/**
 * The objects that handle clicks on an li to select a checkbox 
 * @param element el 
 */
var AJC_PictureLI = function( el ) {

	var self = this; 

	this.el = jQuery( el );
	this.id = this.el.data( 'id' );
	this.group = this.el.data( 'group' );
	this.input = this.el.find( 'input' );

	this.setStatus = function( val ) {
		this.el.toggleClass( 'selected', val );     
	}

	this.el.click( function( e ) {
		self.input.attr( 'checked', !self.input.attr( 'checked' ) );
		self.input.triggerHandler( 'click' );
		return false;
	} );
}

/**
 * Object to expose results from the tracking API
 */
var AJC_Tracking = (function( $ ) {

	return function( id, element ) {

		this.el = $( element );
		this.id = id;
		var self = this;

		// observables for the order data we will display
		this.lastCheckpointTime = ko.observable( false );
		this.lastPosition = ko.observable( false );
		this.status = ko.observable( false );
		this.message = ko.observable( false );
		this.error = ko.observable( false );
		this.hasTrackingData = ko.observable( false );
		this.noDataYet = ko.observable( false );

		this.data = false;

		this.getStatus = function() {
			//get latest data
			Archetype.post( 'get_tracking_info', { order_id: self.id }, function( result ) {
				if( result.status == true ) {
					self.data = new AfterShip_Status( result.data );
					self.hasTrackingData( true );
					if( self.data.getLatestPosition() !== false ) {
						//populate the observables with new data
						var position = self.data.getLatestPosition();
						self.lastPosition( position.country_name );
						self.status( position.tag === null ? false : position.tag );
						self.message( position.message );
						self.lastCheckpointTime( new Date( position.checkpoint_time ).toString() );
					} else {
						self.noDataYet( true );
					}
				} else { // no data
					self.error( 'No tracking data is available at present' );
				}
			} );
		}
	}

})(jQuery);


var AfterShip_Status = function( aftership_hash ) {
	
	var self = this;
	this.hash = aftership_hash;

	this.getLatestPosition = function() {
		if ( !self.hash.checkpoints.length )
			return false;
		return self.hash.checkpoints[self.hash.checkpoints.length - 1];
	}

}

function inArray(needle, haystack) {
	var length = haystack.length;
	for(var i = 0; i < length; i++) {
		if(haystack[i] == needle) return true;
	}
	return false;
}

(function() {

	var $ = window.jQuery;

	// store the slider in a local variable
	var $window =   $(window),
					flexslider;

	if( !$('.flexslider') || !AJC.flexslider )
		return;

	// tiny helper function to add breakpoints
	function getGridSize() {
		return (window.innerWidth < 600) ? 2 :
			(window.innerWidth < 900) ? 4 : 6;
	}

	$window.load(function() {

		$('.flexslider.products-slider').flexslider({
			animation: "slide",
			animationSpeed: 200,
			controlNav: false,
			directionNav: true,
			slideshow: true,
			itemMargin: 32,
			itemWidth: 206,
			pauseOnHover: true, 
			move: 6, 
			randomize: true, 
			minItems: 1,
			maxItems: 6,  
			start: function() {
				$('.products-slider li').show();
			}
		});

		jQuery('.flexslider.main-slider').flexslider({
			randomize: true,
			pauseOnHover: true,
			controlNav: false,
			directionNav: false,
			slideshowSpeed: 6000,
			animationSpeed: 800,   
			slideshow: true,
			start: function(slider) {
			        slider.removeClass('loading');
			}
		});

		//jQuery('.flexslider.dashboard').flexslider({
		//	randomize: true,
		//	pauseOnHover: true,
		//	controlNav: false,
		//});
	});

	// check grid size on resize event
	$window.resize(function() {
		var gridSize = getGridSize();
		if( flexslider ) {
			flexslider.vars.minItems = gridSize;
			flexslider.vars.maxItems = gridSize;
		}
	});
}());

String.prototype.capitalize = function() {
	return this.charAt(0).toUpperCase() + this.slice(1);
}

/*!
 *  Google map on modal fix
 */

// Resize map to show on a Bootstrap's modal
$('#viewing').on('shown.bs.modal', function() {
	var currentCenter = map.getCenter();  // Get current center before resizing
	google.maps.event.trigger(map, "resize");
	map.setCenter(currentCenter); // Re-set previous center
});

/*!
 *  Autocomplete
 */

jQuery( ".account .autocomplete" ).autocomplete({
source: function( request, response ) {
    Archetype.post( 'ajc_search',
            { query: request },
            response );
},
select: function (event, ui) {
    window.location = ui.item.url;
}
});

jQuery( ".autocomplete" ).autocomplete({
	source: function( request, response ) {
		Archetype.post( 'ajc_search', 
			{ query: request }, 
			response );
	},
	select: function (event, ui) {
		window.location = ui.item.url;
	}
});

/*!
 *  Mobile Nav
 */

var $ = window.jQuery;
$('#search-toggle').click(function(){
	$('.search-toggle').toggle();
	$('.nav-toggle').hide();
	$('.ion-search').addClass('active');
	return false;
});
$('#nav-toggle').click(function(){
	$('.nav-toggle').toggle();
	$('.search-toggle').hide();
	$('.ion-navicon').addClass('active');
	return false;
});
$( '.toggle .close-toggle' ).click( function() {
	$(this).closest('.toggle').toggle();
});