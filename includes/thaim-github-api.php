<?php
// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit;

/**
* Thaim_Github_API do not use oauth in this beta version
* but we'll do better in next one ;)
*/
class Thaim_Github_API
{
	protected $github_user;
	
	// i'm the author of this theme so by default you'll get my repos ;)
	function __construct( $user = 'imath' )
	{
		$this->github_user = $user;
		$this->get_repos();
	}
	
	function get_repos()
	{
		/* we use curl !*/
		add_filter( 'use_fsockopen_transport', '__return_false' );
		add_filter( 'use_fopen_transport', '__return_false' );
		add_filter( 'use_streams_transport', '__return_false' );
		add_filter( 'use_http_extension_transport', '__return_false' );
		
		// list all repos
		$url = 'https://api.github.com/users/' . $this->github_user . '/repos';
		
		$args = array(
			'method' => 'GET',
			'sslverify' => false
		);

		$github = wp_remote_request( $url, $args );
		
		// exit on error..
		if( !$github || $github->errors ) {
			return false;
		}
		
		$github_datas = $github['body'];

		$gits = json_decode( $github_datas );
		
		$git_repos = array();
		
		foreach( $gits as $git ) {
			$git_repos[] = array( 
							'name'        => $git->name,
							'description' => $git->description,
							'url'         => $git->html_url
							);
		}
		
		if( count( $git_repos ) >= 1 )
			update_option('thaim_github_repos', $git_repos );
	}
}