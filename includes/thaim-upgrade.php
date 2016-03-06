<?php
/**
 * Upgrade routine
 */

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

function thaim_upgrade() {
	$current_version = '2.0.0';
	$db_version      = get_option( 'thaim_version', 0 );

	if ( version_compare( $db_version, $current_version, '<' ) ) {

		if ( 2.0 > (float) $db_version ) {
			$post_tags = get_terms( array(
				'taxonomy' => 'post_tag',
				'fields'   => 'ids',
			) );

			$categories = get_terms( array(
				'taxonomy' => 'category',
				'fields'   => 'ids',
			) );

			$post_terms = array_merge( $post_tags, $categories );

			foreach ( $post_terms as $post_term ) {
				$url = false;
				$tax_meta = get_option( 'tax_meta_' . $post_term );

				if ( empty( $tax_meta ) ) {
					continue;
				}

				if ( isset( $tax_meta['thaim_tax_image']['src'] ) ) {
					$url = parse_url( $tax_meta['thaim_tax_image']['src'], PHP_URL_PATH );

					if ( ! empty( $url ) ) {
						$url = '//ps.w.org' . $url;

						update_term_meta( $post_term, '_thaim_term_image', esc_url_raw( $url ) );
					}
				}

				delete_option( 'tax_meta_' . $post_term );
			}
		}
	}

	update_option( 'thaim_version', $current_version );
}
add_action( 'admin_init', 'thaim_upgrade', 900 );
