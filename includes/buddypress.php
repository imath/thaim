<?php
// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

if ( ! class_exists( 'Thaim_BuddyPress' ) ) :

class Thaim_BuddyPress {

	public function __construct() {
		$this->hooks();
	}

	public static function start() {
		$bp = buddypress();

		if( empty( $bp->theme_compat->theme->thaim ) ) {
			$bp->theme_compat->theme->thaim = new self;
		}

		return $bp->theme_compat->theme->thaim;
	}

	private function hooks() {
		// Author link now goes to my BuddyPress profile !
		add_filter( 'author_link', array( $this, 'filter_author_link' ), 10, 3 );

		// Changing the way headline is handled for BuddyPress pages
		add_filter( 'thaim_headline_get_h1', array( $this, 'headline_title' ) );

		// Menu items highligthing
		add_filter( 'page_css_class', array( $this, 'maybe_unhighlight_page' ), 20, 2 );
	}

	public function filter_author_link( $link = '', $author_id = 0, $author_nicename = '' ) {
		if( ! empty( $author_id ) ) {
			$link = bp_core_get_userlink( $author_id, false, true );
		}

		return $link;
	}

	public function headline_title( $title ) {
		if ( is_buddypress() ) {
			$title = get_the_title();
		}

		return $title;
	}

	/**
	 * Since BuddyPress 2.2, we need to do this
	 */
	public function maybe_unhighlight_page( $retval, $page ) {
		if ( ! is_buddypress() ) {
			return $retval;
		}

		// loop against all BP component pages
		foreach ( (array) buddypress()->pages as $component => $bp_page ) {
			// handles the majority of components
			if ( bp_is_current_component( $component ) ) {
				$page_id = (int) $bp_page->id;
			}

			// stop if not on a user page
			if ( ! bp_is_user() && ! empty( $page_id ) ) {
				break;
			}

			// members component requires an explicit check due to overlapping components
			if ( bp_is_user() && 'members' === $component ) {
				$page_id = (int) $bp_page->id;
				break;
			}
		}

		if ( empty( $page_id ) || empty( $page->ID ) || $page_id == $page->ID ) {
			return $retval;
		}

		// If we are here, we need to make sure no other pages are highlighted
		return array_diff( $retval, array(
			'current_page_parent',
			'current_page_item',
		) );
	}
}

endif;

add_action( 'bp_init', array( 'Thaim_BuddyPress', 'start' ), 3 );
