<?php
// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

if ( ! class_exists( 'Thaim_BuddyPress' ) ) :

class Thaim_BuddyPress {

	public function __construct() {
		$this->setup_globals();
		$this->hooks();
	}

	public static function start() {
		$bp = buddypress();

		if( empty( $bp->theme_compat->theme->thaim ) ) {
			$bp->theme_compat->theme->thaim = new self;
		}

		return $bp->theme_compat->theme->thaim;
	}

	private function setup_globals() {
		// customize with your wordpress login.
		$this->owner_id = bp_core_get_userid( 'imath' );

		define( 'BP_DEFAULT_COMPONENT', 'profile' );
		$this->badges_field_id = '';
	}

	private function hooks() {
		// Default component to insall, this will only work if thaim is activated before BuddyPress !
		add_filter( 'bp_new_install_default_components', array( $this, 'default_component' ), 10, 1 );

		// disabling mentions
		add_filter( 'bp_activity_do_mentions', '__return_false' );

		// Now let's edit BP nav so that Profile is the first tab of the member's nav
		add_action( 'bp_xprofile_setup_nav', array( $this, 'profile_nav_position' ) );

		/**
		 * Customizing profile filters
		 */
		add_action   ( 'bp_screens',                          array( $this, 'define_badges_field' )      );
		add_filter   ( 'xprofile_allowed_tags',               array( $this, 'profile_tags' ),     100, 1 );
		remove_filter( 'bp_get_the_profile_field_value',      'esc_html',                              8 );
		remove_filter( 'bp_get_the_profile_field_value',      'xprofile_filter_link_profile_data',  9, 2 );
		remove_filter( 'bp_get_the_profile_field_edit_value', 'wp_filter_kses',                        1 );
		add_filter   ( 'bp_get_the_profile_field_edit_value', 'xprofile_filter_kses',                  1 );
		add_filter   ( 'bp_get_the_profile_field_value',      array( $this, 'format_badges' ),    100, 3 );

		// redirect from members to my profile
		add_action( 'bp_members_screen_index',  array( $this, 'redirect_owner' ) );

		// Add badges to member's header
		add_action( 'bp_before_member_header', array( $this, 'header_badges' ) );

		// redirect from activity to my activities
		add_action( 'bp_activity_screen_index', array( $this, 'redirect_owner' ) );

		// Thaim headline
		add_filter( 'thaim_headline_get_h2', array( $this, 'headline' ), 10 , 1 );

		// Author link now goes to my BuddyPress profile !
		add_filter( 'author_link', array( $this, 'filter_author_link' ), 10, 3 );
	}

	public function default_component( $default = array() ) {
		$default = array_intersect_key( array(
			'activity'      => 1,
			'members'       => 1,
			'xprofile'      => 1,
		), $default );

		return $default;
	}

	public function profile_nav_position() {
		if ( defined( 'BP_DEFAULT_COMPONENT' ) && 'profile' == BP_DEFAULT_COMPONENT ) {
			buddypress()->members->nav->edit_nav( array( 'position' => 1 ), 'profile' );
		}
	}

	public function define_badges_field() {
		// Bail if not displaying a user
		if ( ! bp_is_user() ) {
			return;
		}

		/**
		 * Create a xprofile field named "Badges"
		 *
		 * 1- Choose checkbox type
		 * 2- define these options
		 *   * wordpress-codex
		 *   * buddypress-codex
		 *   * plugin-developer
		 *   * theme-designer
		 *   * wordcamp-speaker
		 */
		$this->badges_field_id = xprofile_get_field_id_from_name( 'Badges' );
	}

	public function profile_tags( $allowed_tags = array() ) {
		$allowed_tags = array_merge( $allowed_tags,
			array(
				'h5' => array(
					'align' => true,
				),

				'hr' => array(
					'align' => true,
					'noshade' => true,
					'size' => true,
					'width' => true,
				),
			)
		);

		return $allowed_tags;
	}

	public function redirect_owner() {
		if ( empty( $this->owner_id ) ) {
			return;
		}

		$redirect = false;

		if ( bp_is_members_directory() ) {
			$redirect = trailingslashit( bp_core_get_user_domain( $this->owner_id ) );
		}

		if ( bp_is_activity_directory() && bp_is_active( 'activity' ) ) {
			$redirect = trailingslashit( bp_core_get_user_domain( $this->owner_id ) . bp_get_activity_slug() );
		}

		if ( ! empty( $redirect ) ) {
			bp_core_redirect( $redirect );
		}
	}

	public function headline( $headline = '' ) {
		// Bail if not on a user's page
		if ( ! bp_is_user() ) {
			return $headline;
		}

		return esc_html__( 'About me!', 'thaim' );
	}

	public function build_bagdes_output( $badges = array(), $css_id = 'user-badges' ) {
		if ( empty( $badges ) ) {
			return false;
		}

		$data_badges = array(
			'wordpress-core'          => array( 'title' => __( 'WordPress Core Contributor', 'thaim' ),  'icon' => 'editor-code'      ),
			'buddypress-core'         => array( 'title' => __( 'BuddyPress Core Contributor', 'thaim' ), 'icon' => 'buddypress'       ),
			'plugin-developer'        => array( 'title' => __( 'Plugin Developer', 'thaim' ),            'icon' => 'admin-plugins'    ),
			'community-team'          => array( 'title' => __( 'Community Team', 'thaim' ),              'icon' => 'groups'           ),
			'theme-designer'          => array( 'title' => __( 'Theme Designer', 'thaim' ),              'icon' => 'admin-appearance' ),
			'wordcamp-speaker'        => array( 'title' => __( 'WordCamp Speaker', 'thaim' ),            'icon' => 'megaphone'        ),
			'translation-contributor' => array( 'title' => __( 'Translation Contributor', 'thaim' ),     'icon' => 'translation'      ),
		);

		$output = '<ul id="' . $css_id . '" class="item-list" role="main">';

		foreach ( $badges as $badge ) {
			$badge = trim( $badge );

			if ( ! isset( $data_badges[ $badge ] ) ) {
				continue;
			}

			$output .= '<li><div class="badge badge-' . sanitize_html_class( $badge ) . ' dashicons dashicons-' . esc_attr( $data_badges[ $badge ]['icon'] ) .'" title="' . esc_attr( $data_badges[ $badge ]['title'] ) . '"></div></li>';
		}

		$output .= '</ul>';

		return $output;
	}

	public function format_badges( $badges = '', $type = '', $field_id = 0 ) {
		if ( ! empty( $badges ) && $this->badges_field_id == $field_id ) {
			$array_badges = explode( ',', wp_kses( $badges, array() ) );
			$badges = $this->build_bagdes_output( $array_badges, 'td-user-badges' );
		}

		return $badges;
	}

	public function header_badges() {
		$badges = xprofile_get_field_data( $this->badges_field_id, $this->owner_id );
		echo $this->build_bagdes_output( $badges );
	}

	public function filter_author_link( $link = '', $author_id = 0, $author_nicename = '' ) {
		if( ! empty( $author_id ) &&  (int) $this->owner_id == (int) $author_id ) {
			$link = bp_core_get_userlink( $author_id, false, true );
		}

		return $link;
	}
}

endif;

add_action( 'bp_init', array( 'Thaim_BuddyPress', 'start' ), 3 );
