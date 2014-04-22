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
	}

	public function filter_author_link( $link = '', $author_id = 0, $author_nicename = '' ) {
		if( ! empty( $author_id ) )
			$link = bp_core_get_userlink( $author_id, false, true );

		return $link;
	}

	public function headline_title( $title ) {
		if ( is_buddypress() )
			$title = get_the_title();

		return $title;
	}
}

endif;

add_action( 'bp_init', array( 'Thaim_BuddyPress', 'start' ), 3 );
