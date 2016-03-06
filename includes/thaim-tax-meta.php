<?php
/**
 * Term Meta
 */

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

function thaim_post_terms_get_meta() {
	return array(
		'term_icon' => array(
			'id'          => 'term-icon',
			'label'       => __( 'Icon', 'thaim' ),
			'description' => sprintf( __( 'Choose the css code from available %s.', 'thaim' ), '<a href="https://developer.wordpress.org/resource/dashicons/" target="_blank">dashicons</a>' ),
		),
		'term_image' => array(
			'id'          => 'term-image',
			'label'       => __( 'Image', 'thaim' ),
			'description' => __( 'Insert the url to the image for the term.', 'thaim' ),
		),
	);
}

function thaim_post_terms_form_fields() {
	foreach ( thaim_post_terms_get_meta() as $key => $meta ) {
		printf(
			'<div class="form-field %1$s-wrap">
				<label for="%1$s">%2$s</label>
				<input name="_thaim[%3$s]" id="%1$s" type="text" value="" size="40" />
				<p>%4$s</p>
			</div>',
			esc_attr( $meta['id'] ),
			esc_html( $meta['label'] ),
			sanitize_key( $key ),
			wp_kses( $meta['description'], array( 'a' => array( 'target' => true, 'href' => true ) ) )
		);
	}
}
add_action( 'category_add_form_fields', 'thaim_post_terms_form_fields' );
add_action( 'post_tag_add_form_fields', 'thaim_post_terms_form_fields' );

function thaim_post_term_form_fields( $tag = null ) {
	if ( empty( $tag->term_id ) ) {
		return;
	}

	foreach ( thaim_post_terms_get_meta() as $key => $meta ) {
		$meta_value = get_term_meta( $tag->term_id, '_thaim_' . $key, true );

		if ( ! empty( $meta_value ) ) {
			if ( 'term_image' === $key ) {
				$meta_value = esc_url( $meta_value );
			} else {
				$meta_value = esc_attr( $meta_value );
			}
		}

		printf(
			'<tr class="form-field %1$s-wrap">
				<th scope="row"><label for="%1$s">%2$s</label></th>
				<td><input name="_thaim[%3$s]" id="%1$s" type="text" value="%5$s" size="40" />
				<p class="description">%4$s</p></td>
			</tr>',
			esc_attr( $meta['id'] ),
			esc_html( $meta['label'] ),
			sanitize_key( $key ),
			wp_kses( $meta['description'], array( 'a' => array( 'target' => true, 'href' => true ) ) ),
			$meta_value
		);
	}
}
add_action( 'edit_category_form_fields', 'thaim_post_term_form_fields', 10, 1 );
add_action( 'edit_tag_form_fields',      'thaim_post_term_form_fields', 10, 1 );

function thaim_post_terms_save_meta( $term_id, $tt_id, $taxonomy = '' ) {
	if ( empty( $taxonomy ) || ( 'post_tag' !== $taxonomy && 'category' !== $taxonomy ) || empty( $_POST['_thaim'] ) ) {
		return;
	}

	foreach ( array( 'term_icon', 'term_image' ) as $meta ) {
		if ( ! empty( $_POST['_thaim'][ $meta ] ) ) {
			if ( 'term_image' === $meta ) {
				$value = esc_url_raw( $_POST['_thaim'][ $meta ] );
			} else {
				$value = esc_html( $_POST['_thaim'][ $meta ] );
			}

			update_term_meta( $term_id, '_thaim_' . $meta, $value );
		} else {
			delete_term_meta( $term_id, '_thaim_' . $meta );
		}
	}
}
add_action( 'create_term', 'thaim_post_terms_save_meta', 10, 3 );
add_action( 'edit_term',   'thaim_post_terms_save_meta', 10, 3 );
