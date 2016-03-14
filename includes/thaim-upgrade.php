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
		$site_url = site_url();

		// Only do this on my website :)
		if ( 2.0 > (float) $db_version && false !== strpos( $site_url, 'imathi.eu' ) ) {
			/** Create Term metas if needed **********************************/

			$post_tags = get_terms( array(
				'taxonomy' => 'post_tag',
				'fields'   => 'ids',
			) );

			$categories = get_terms( array(
				'taxonomy' => 'category',
				'fields'   => 'ids',
			) );

			$post_terms = array_merge( $post_tags, $categories );

			// Clean up options
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

			/** Migrate Slider Images meta to Post Thumbnails *****************/
			global $wpdb;

			$migrate_images = $wpdb->get_results( $wpdb->prepare(
				"SELECT post_id, meta_value as imageslider FROM {$wpdb->postmeta} WHERE meta_key = %s",
				'imageslider'
			) );

			$site_images = array();

			if ( ! empty( $migrate_images ) ) {
				foreach ( $migrate_images as $migrate_image ) {
					$is_site_image = false;

					if ( false !== strpos( $migrate_image->imageslider, $site_url ) ) {
						$site_images[ $migrate_image->imageslider ] = true;

					} else {
						$src = media_sideload_image( $migrate_image->imageslider, $migrate_image->post_id, null, 'src' );

						if ( ! empty( $src ) ) {
							$site_images[ $src ] = false;
						}
					}
				}
			}

			if ( ! empty( $site_images ) ) {
				$in = "('" . join( "','", array_map( 'esc_sql', array_keys( $site_images ) ) ) . "')";
				$thumbnails = $wpdb->get_results(
					"SELECT ID, post_parent, guid FROM {$wpdb->posts} WHERE guid IN {$in}"
				);
			}

			if ( ! empty( $thumbnails ) ) {
				foreach( $thumbnails as $thumbnail ) {
					if ( ! empty( $site_images[ $thumbnail->guid ] ) ) {
						delete_post_meta( $thumbnail->ID, '_wp_attachment_metadata' );

						wp_maybe_generate_attachment_metadata( get_post( $thumbnail->ID ) );
					}

					set_post_thumbnail( $thumbnail->post_parent, $thumbnail->ID );

					// Clean up post meta
					delete_post_meta( $thumbnail->post_parent, 'imageslider' );
				}
			}

			/** Migrate inslider meta to Post Stickies *****************/
			$migrate_stickies = $wpdb->get_col( $wpdb->prepare(
				"SELECT post_id FROM {$wpdb->postmeta} WHERE meta_key = %s",
				'inslider'
			) );

			if ( ! empty( $migrate_stickies ) ) {
				update_option( 'sticky_posts', array_values( $migrate_stickies ) );

				// Clean up post meta
				foreach( $migrate_stickies as $p_id ) {
					delete_post_meta( $p_id, 'inslider' );
				}
			}
		}
	}

	update_option( 'thaim_version', $current_version );
}
add_action( 'admin_init', 'thaim_upgrade', 900 );
