<?php
/**
 * Upgrade routines
 */

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

/**
 * This is specific to my website
 *
 * It uses an utility plugin to upgrade
 * various things on my site.
 *
 * @since  2.0.0
 *
 * @param  array  $items The Upgrade tasks to run
 * @return array         The Upgrade tasks to run
 */
function thaim_upgrader_tasks( $items = array() ) {
	$db_version = get_option( 'thaim_version', 0 );

	if ( version_compare( $db_version, thaim()->version, '<' ) ) {
		$items['thaim'] = array(
			'type'           => 'theme',
			'db_version'     => $db_version,
			'tasks'          => array(
				'2.0.0' => array(
					array(
						'callback'  => 'thaim_upgrade_term_metas',
						'count'     => 'thaim_get_term_meta_upgrade_count',
						'message'   => _x( 'Category and tag meta informations - %d item(s) to upgrade', 'Upgrader feedback message', 'thaim' ),
						'number'    => 1,
					),
					array(
						'callback'  => 'thaim_upgrade_stickies',
						'count'     => 'thaim_get_inslider_count',
						'message'   => _x( 'Stickies for the theme slider - %d item(s) to upgrade', 'Upgrader feedback message', 'thaim' ),
						'number'    => 1,
					),
					array(
						'callback'  => 'thaim_upgrade_thaim_code',
						'count'     => 'thaim_get_thaim_code_upgrade_count',
						'message'   => _x( 'Snippets in post content - %d item(s) to upgrade', 'Upgrader feedback message', 'thaim' ),
						'number'    => 10,
					),
					array(
						'callback'  => 'thaim_upgrade_thumbnails',
						'count'     => 'thaim_get_thumbnails_upgrade_count',
						'message'   => _x( 'Migrate imagesliders meta to post thumbnails - %d item(s) to upgrade', 'Upgrader feedback message', 'thaim' ),
						'number'    => 5,
					),
					array(
						'callback'  => 'thaim_upgrade_db_version',
						'count'     => 'thaim_get_db_version_upgrade_count',
						'message'   => _x( 'Theme version - %d item to upgrade', 'Upgrader feedback message', 'thaim' ),
						'number'    => 1,
					),
				),
			),
		);
	}

	return $items;
}
add_filter( 'imath_upgrader_tasks', 'thaim_upgrader_tasks' );

/**
 * Get the Tax Meta Options to migrate as real term metas
 *
 * @since  2.0.0
 *
 * @param  string $type Whether to return the array of tax meta or the count
 * @return int|array    The array of tax meta or the count
 */
function thaim_get_term_meta_upgrade_count( $type = '' ) {
	$post_tags = get_terms( array(
		'taxonomy' => 'post_tag',
		'fields'   => 'ids',
	) );

	$categories = get_terms( array(
		'taxonomy' => 'category',
		'fields'   => 'ids',
	) );

	$post_terms = array_merge( $post_tags, $categories );
	$options = array();

	foreach ( $post_terms as $post_term ) {
		$url = false;
		$tax_meta = get_option( 'tax_meta_' . $post_term );

		if ( empty( $tax_meta ) ) {
			continue;
		}

		$options[ $post_term ] = $tax_meta;
	}

	if ( 'array' === $type ) {
		return $options;
	}

	return count( $options );
}

/**
 * Term Metas migrate routine
 *
 * @since  2.0.0
 *
 * @param  int $number The number of items to upgrade
 * @return int         The number of items upgraded
 */
function thaim_upgrade_term_metas( $number = 1 ) {
	$tax_metas = thaim_get_term_meta_upgrade_count( 'array' );

	foreach ( $tax_metas as $tax_id => $tax_meta ) {
		if ( isset( $tax_meta['thaim_tax_image']['src'] ) ) {
			$url = parse_url( $tax_meta['thaim_tax_image']['src'], PHP_URL_PATH );

			if ( ! empty( $url ) ) {
				$url = '//ps.w.org' . $url;

				update_term_meta( $tax_id, '_thaim_term_image', esc_url_raw( $url ) );
			}
		}

		delete_option( 'tax_meta_' . $tax_id );
	}

	return count( $tax_metas );
}

/**
 * Number of posts to remove the thaim_code shortcode from
 *
 * @since  2.0.0
 *
 * @return int The number of posts to upgrade
 */
function thaim_get_thaim_code_upgrade_count() {
	global $wpdb;

	return $wpdb->get_var( $wpdb->prepare(
		"SELECT COUNT(post_id) FROM {$wpdb->postmeta} WHERE meta_key = %s",
		'prettifyed'
	) );
}

/**
 * thaim_code migrate routine
 *
 * @since  2.0.0
 *
 * @param  int $number The number of posts to upgrade
 * @return int         The number of posts upgraded
 */
function thaim_upgrade_thaim_code( $number = 10 ) {
	global $wpdb;

	$migrate_thaim_codes = $wpdb->get_col( $wpdb->prepare(
		"SELECT post_id FROM {$wpdb->postmeta} WHERE meta_key = %s LIMIT %d",
		'prettifyed',
		$number
	) );

	$updated = 0;

	if ( ! empty( $migrate_thaim_codes ) ) {
		// Clean up post meta
		foreach( $migrate_thaim_codes as $migrate_thaim_code ) {
			$item = get_post( $migrate_thaim_code, ARRAY_A );

			$item['post_content'] = str_replace( array(
				'[thaim_code]',
				'[/thaim_code]',
				'[thaim_code linenums="1"]',
			), '', $item['post_content'] );

			wp_update_post( $item );

			// Clean up post meta
			delete_post_meta( $migrate_thaim_code, 'prettifyed' );

			$updated += 1;
		}
	}

	return $updated;
}

/**
 * Number of Slider's post meta to migrate as sticky posts
 *
 * @since  2.0.0
 *
 * @return int The number of Slider's post meta to upgrade
 */
function thaim_get_inslider_count() {
	global $wpdb;

	return $wpdb->get_var( $wpdb->prepare(
		"SELECT COUNT(post_id) FROM {$wpdb->postmeta} WHERE meta_key = %s",
		'inslider'
	) );
}

/**
 * Slider's post meta migrate routine
 *
 * @since  2.0.0
 *
 * @param  int $number The number of Slider's post metas to upgrade
 * @return int         The number of Slider's post metas upgraded
 */
function thaim_upgrade_stickies( $number = 1 ) {
	global $wpdb;

	$migrate_stickies = $wpdb->get_col( $wpdb->prepare(
		"SELECT post_id FROM {$wpdb->postmeta} WHERE meta_key = %s",
		'inslider'
	) );

	$updated = 0;

	if ( ! empty( $migrate_stickies ) ) {
		update_option( 'sticky_posts', array_values( $migrate_stickies ) );

		// Clean up post meta
		foreach( $migrate_stickies as $p_id ) {
			delete_post_meta( $p_id, 'inslider' );

			$updated += 1;
		}
	}

	return $updated;
}

/**
 * Number of Image Slider's post meta to migrate as Post thumbnails
 *
 * @since  2.0.0
 *
 * @return int The number of Image Slider's post meta to upgrade
 */
function thaim_get_thumbnails_upgrade_count() {
	global $wpdb;

	return $wpdb->get_var( $wpdb->prepare(
		"SELECT COUNT(post_id) FROM {$wpdb->postmeta} WHERE meta_key = %s",
		'imageslider'
	) );
}

/**
 * Image Slider's post meta migrate routine
 *
 * @since  2.0.0
 *
 * @param  int $number The number of Image Slider's post metas to upgrade
 * @return int         The number of Image Slider's post metas upgraded
 */
function thaim_upgrade_thumbnails( $number = 5 ) {
	global $wpdb;

	$migrate_images = $wpdb->get_results( $wpdb->prepare(
		"SELECT post_id, meta_value as imageslider FROM {$wpdb->postmeta} WHERE meta_key = %s LIMIT %d",
		'imageslider',
		$number
	) );

	$site_images = array();
	$updated = 0;

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

			$description = get_post_field( 'post_content', $thumbnail->post_parent );

			preg_match( '/\<em\>Illustration de l.article(.*?)\<\/em\>/u', $description, $matches );

			// Try to add the Credits to the thumbnail image.
			if ( ! empty( $matches[1] ) ) {
				$attachment = get_post( $thumbnail->ID, ARRAY_A );

				$attachment['post_content'] = 'Crédits Photo' . $matches[1];
				wp_update_post( $attachment );
			}

			// Clean up post meta
			delete_post_meta( $thumbnail->post_parent, 'imageslider' );
			$updated += 1;
		}
	}

	return $updated;
}

/**
 * There's only one db version to upgrade!
 *
 * @since  2.0.0
 *
 * @return int 1
 */
function thaim_get_db_version_upgrade_count() {
	return 1;
}

/**
 * Upgrade the theme db version
 *
 * @since  2.0.0
 *
 * @return int 1
 */
function thaim_upgrade_db_version() {
	update_option( 'thaim_version', thaim()->version );

	return 1;
}
