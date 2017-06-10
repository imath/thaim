( function ( window, document ) {
	'use strict';

	if ( 'undefined' === typeof l10nThaimEmbed ) {
		return;
	}

	/**
	 * Translates the Embedded page.
	 *
	 * @param  {string} locale The locale ID to translate into.
	 * @return {void}
	 */
	window.thaimTranslate = function( locale ) {
		var links     = document.getElementsByTagName( 'a' ), hrefAttribute, excerpt,
		    inputWP   = document.getElementsByTagName( 'input' ), locales = { 'fr_FR' : 'en_US', 'en_US' : 'fr_FR' },
		    inputHTML = document.getElementsByTagName( 'textarea' );

		for ( var i = 0 ; i < links.length ; i++ ) {
			hrefAttribute = links[i].getAttribute( 'href' );

			if ( hrefAttribute.match( /\?redirectto/ ) ) {
				links[i].setAttribute( 'href', hrefAttribute.replace( l10nThaimEmbed.link[ locales[ locale ] ], l10nThaimEmbed.link[ locale ] ) );
			} else if ( -1 !== hrefAttribute.indexOf( l10nThaimEmbed.link[ locales[ locale ] ] ) ) {
				links[i].setAttribute( 'href', l10nThaimEmbed.link[ locale ] );
			}
		}

		inputWP[0].setAttribute( 'value', l10nThaimEmbed.link[ locale ] );
		inputHTML[0].innerHTML = inputHTML[0].innerHTML.replace( l10nThaimEmbed.link[ locales[ locale ] ], l10nThaimEmbed.link[ locale ] );

		excerpt = document.getElementsByClassName( 'wp-embed-excerpt' );
		excerpt[0].innerHTML = l10nThaimEmbed.content[ locale ];

		// Update UI strings
		thaimGetStrings( l10nThaimEmbed.uiStrings[ locales[ locale ] ], l10nThaimEmbed.uiStrings[ locale ]  );
	};

	/**
	 * Get or Update the Sharing Dialog UI strings.
	 *
	 * @param  {Object} keys   The current locale object.
	 * @param  {Object} locale The locale to translate into object.
	 * @return {Object}        The current locale object.
	 */
	window.thaimGetStrings = function( keys, locale ) {
		var strings = {};

		if ( ! keys ) {
			return strings;
		}

		for ( var k in keys ) {
			var e = null;

			switch ( k ) {
				case 'wp-embed-share-dialog'       :
				case 'wp-embed-share-dialog-open'  :
				case 'wp-embed-share-dialog-close' :
					e = document.getElementsByClassName( k )[0];
					strings[ k ] = e.getAttribute( 'aria-label' );

					if ( !! locale && locale[ k ] ) {
						e.setAttribute( 'aria-label', locale[ k ] );
					}
					break;
				case 'wp-embed-share-tab-button-wordpress' :
				case 'wp-embed-share-tab-button-html'      :
					document.getElementsByClassName( k )[0].childNodes.forEach( function( child ) {
						if ( 'BUTTON' === child.nodeName ) {
							e = child;
						}
					} );

					strings[ k ] = e.innerHTML;

					if ( !! locale && locale[ k ] ) {
						e.innerHTML = locale[ k ];
					}
					break;
				default:
					e = document.getElementById( k );
					strings[ k ] = e.innerHTML.trim();

					if ( !! locale && locale[ k ] ) {
						e.innerHTML = locale[ k ];
					}
					break;
			}
		}

		return strings;
	};

	/**
	 * Toggles the content in English/French
	 *
	 * @param  {MouseEvent} e The click event.
	 * @return {void}
	 */
	document.getElementById( 'thaim-translate' ).addEventListener( 'click', function( e ) {
		var target, locale, spans;

		e.preventDefault();

		if ( 'SPAN' !== e.target.nodeName ) {
			target = e.target.parentElement;
		} else {
			target = e.target;
		}

		locale = target.dataset.locale;
		spans  = target.parentElement.getElementsByClassName( 'thaim-translate-emoji' );

		// Translate
		thaimTranslate( locale );

		for ( var i = 0 ; i < spans.length ; i++ ) {
			if ( -1 === spans[i].className.indexOf( 'hidden' ) ) {
				spans[i].setAttribute( 'class', spans[i].className + ' hidden' );
			} else {
				spans[i].setAttribute( 'class', spans[i].className.replace( ' hidden', '' ) );
			}
		}

	}, false );

	/**
	 * Populate current locale UI Strings.
	 *
	 * @return {void}
	 */
	window.addEventListener( 'load', function() {
		var t = Object.keys( l10nThaimEmbed.uiStrings )[0];

		l10nThaimEmbed.uiStrings[ l10nThaimEmbed.currentLocale ] = thaimGetStrings( l10nThaimEmbed.uiStrings[ t ] );
	}, false );

} )( window, document );
