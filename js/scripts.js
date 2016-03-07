// DOM Ready
jQuery( function( $ ) {

	// SVG Modernizr detect and PNG replace
	// toddmotto.com/mastering-svg-use-for-a-retina-web-fallbacks-with-png-script
	if ( ! Modernizr.svg ) {
	    $( 'img[src*="svg"]' ).attr( 'src', function () {
	        return $(this).attr( 'src' ).replace( '.svg', '.png' );
	    } );
	}

	// iPhone Safari URL bar hides itself on pageload
	if ( navigator.userAgent.indexOf( 'iPhone' ) != -1 ) {
	    addEventListener("load", function () {
	        setTimeout( hideURLbar, 0 );
	    }, false );
	}

	function hideURLbar() {
	    window.scrollTo( 0, 0 );
	}

	/** handling menu on small screens - Credit : _s theme **/
	var $header = $( '#thaim-site' ),
	    timeout = false;

	$.fn.smallMenu = function() {
		$header.find( '.site-navigation' ).removeClass( 'main-navigation' ).addClass( 'main-small-navigation' );
		$header.find( '.site-navigation h1' ).removeClass( 'assistive-text' ).addClass( 'menu-toggle' );
		$header.find( '#thaim-info' ).css( 'margin', '0 auto' );
		$header.find( '#thaim-info' ).css( 'text-align', 'center' );
		$header.find( '.wrapper' ).css( 'height', 'auto' );
		$header.css( 'background-position', 'bottom' );

		if ( $( '.thaim-hero-slide-container' ).length ) {
			$( '.thaim-hero-slide-container .sevencol' ).addClass( 'adapt' );

			if ( $( '.thaim-hero-slide-container .fivecol img' ).length ) {
				var newwidth = $( '.thaim-hero-slide-container .fivecol' ).width();

				if ( newwidth == 0 ) {
					newwidth = 320;
				}

				$( '.thaim-hero-slide-container .adapt' ).css( 'width', newwidth + 'px' );
			}

		}

		$( '.menu-toggle' ).unbind( 'click' ).click( function() {
			$( this ).toggleClass( 'toggled-on' );
			$( this ).parent( 'nav' ).toggleClass( 'toggled-on' );
		} );
	};

	// Check viewport width on first load.
	if ( $( window ).width() < 600 ) {
		$.fn.smallMenu();
	}

	// Check viewport width when user resizes the browser window.
	$( window ).resize( function() {
		var browserWidth = $( window ).width();

		if ( false !== timeout ) {
			clearTimeout( timeout );
		}

		timeout = setTimeout( function() {
			if ( browserWidth < 600 ) {
				$.fn.smallMenu();
			} else {
				$header.find( '.site-navigation' ).removeClass( 'main-small-navigation' ).addClass( 'main-navigation' );
				$header.find( '.site-navigation h1' ).removeClass( 'menu-toggle' ).addClass( 'assistive-text' );
				$header.find( '.menu' ).removeAttr( 'style' );
				$header.find( '#thaim-info' ).css( 'margin', '0' );
				$header.find( '#thaim-info' ).css( 'text-align', 'left' );

				if ( $( 'body' ).hasClass( 'custom-logo' ) ) {
					$header.find( '.wrapper' ).css( 'height', '120px' );
				} else {
					$header.find( '.wrapper' ).css( 'height', '88px' );
				}

				$header.css('background-position', 'top left');
				if( $( '.thaim-hero-slide-container' ).length ) {
					$( '.thaim-hero-slide-container .adapt' ).css( 'width', 'auto' );
					$( '.thaim-hero-slide-container .adapt' ).removeClass( 'adapt' );
					$( '.thaim-hero-slide-container' ).css( 'width', '100%' );
					$( '.thaim-hero-slide' ).css( 'width', '100%' );
				}
			}
		}, 200 );
	} );
} );
