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
		$this->owner_id = 1;
		define( 'BP_DEFAULT_COMPONENT', 'profile' );
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
		add_filter   ( 'xprofile_allowed_tags',               array( $this, 'profile_tags' ),     100, 1 );
		remove_filter( 'bp_get_the_profile_field_value',      'esc_html',                              8 );
		remove_filter( 'bp_get_the_profile_field_value',      'xprofile_filter_link_profile_data',  9, 2 );
		remove_filter( 'bp_get_the_profile_field_edit_value', 'wp_filter_kses',                        1 );
		add_filter   ( 'bp_get_the_profile_field_edit_value', 'xprofile_filter_kses',                  1 );

		// redirect from members to my profile
		add_action( 'bp_members_screen_index',  array( $this, 'redirect_owner' ) );

		// Add badges to member's header
		add_action( 'bp_before_member_header', array( $this, 'badges' ) );

		// redirect from activity to my activities
		add_action( 'bp_activity_screen_index', array( $this, 'redirect_owner' ) );

		// Thaim headline
		add_filter( 'thaim_headline_get_h1', array( $this, 'headline' ), 10 , 1 );
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
		if ( defined( 'BP_DEFAULT_COMPONENT' ) && 'profile' == BP_DEFAULT_COMPONENT )
			buddypress()->bp_nav['profile']['position'] = 1;
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
		if ( empty( $this->owner_id ) )
			return;

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
		// Bail if no on a user's page
		if ( ! bp_is_user() )
			return $headline;

		return esc_html__( 'About me!', 'thaim' );
	}

	public function badges() {
		?>
		<ul id="user-badges" class="item-list" role="main">
			<li class="odd private is-admin is-member">
				<div class="badge item dashicons badge-speaker dashicons-megaphone" title="WordCamp Speaker"></div>
			</li>
			<li class="even private is-admin">
				<div class="badge item dashicons badge-plugins dashicons-admin-plugins" title="Plugin Developer"></div>
			</li>
			<li class="even private is-admin">
				<div class="badge badge-documentation thaimicons" title="BuddyPress Codex" data-icon="&#xe000;"></div>
			</li>
		</ul>
		<?php
	}
}

endif;

add_action( 'bp_init', array( 'Thaim_BuddyPress', 'start' ), 3 );