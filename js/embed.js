( function ( window, document ) {
	'use strict';

	if ( 'undefined' === typeof l10nThaimEmbed ) {
		return;
	}

	/**
	 * Toggles the content in English/French
	 *
	 * @param  {MouseEvent} e The click event.
	 * @return {void}
	 */
	document.getElementById( 'thaim-translate' ).addEventListener( 'click', function( e ) {
		e.preventDefault();

		var links     = document.getElementsByTagName( 'a' ), hrefAttribute,
		    inputWP   = document.getElementsByTagName( 'input' ),
		    inputHTML = document.getElementsByTagName( 'textarea' );

		for ( var i = 0 ; i < links.length ; i++ ) {
			hrefAttribute = links[i].getAttribute( 'href' );

			if ( hrefAttribute.match( /\?redirectto/ ) ) {
				links[i].setAttribute( 'href', hrefAttribute.replace( l10nThaimEmbed.permalink + '?redirectto', l10nThaimEmbed.permalink + '?locale=en_US&redirectto'  ) );
			} else if ( l10nThaimEmbed.permalink === hrefAttribute ) {
				links[i].setAttribute( 'href', l10nThaimEmbed.permalink + '?locale=en_US' );
			}
		}

		inputWP[0].setAttribute( 'value', l10nThaimEmbed.permalink + '?locale=en_US' );
		inputHTML[0].innerHTML = inputHTML[0].innerHTML.replace( l10nThaimEmbed.permalink, l10nThaimEmbed.permalink + '?locale=en_US' );
	}, false );

} )( window, document );
