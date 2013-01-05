<?php
// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit;

add_shortcode('thaim_code', 'thaim_handle_shortcode_code');

// Shortcode Thaim code <h2> tag
function thaim_handle_shortcode_code( $atts, $content = null)
{
	extract( shortcode_atts( array( 'linenums' => false, 'github_url'=> false, 'github_raw'=> false, 'github_from' => 'notset', 'github_to' => 'notset' ), $atts) );
	
	if( !empty( $linenums ) )
		$class = 'linenums';
	
	$output = '<pre class="prettyprint '. $class .'">';
	
    if( !empty( $content) ) {
		$content = str_replace('<pre>', '', $content );
		$content = str_replace('</pre>', '', $content );
		$code = $content ;
	} else {
		// we're looking for a gits or a git file...
		$github_request = new WP_Http;
		$github_result = $github_request->request( $github_raw, array('sslverify' => false) );
		
		if( !$github_result || $github_result->errors ) {
			$code = __('OOpsy! Github is unreachable :(', 'thaim');
		} else {
			
			$code = $github_result['body'];
			$code = htmlentities( $code );
			
			if( $github_from != 'notset' && $github_to != 'notset' ) {
				$lines = explode( "\n", $code );
				
				if( count( $lines ) > 1 ) {
					$code = "";
					
					for( $i = $github_from - 1 ; $i < $github_to ; $i++ ) {
						$code .= $lines[$i] ."\n";
					}
					
				}
				
			}
			
		}
	}
		
	$output .= $code . '</pre>';
	
	if( !empty( $code ) ) {
		
		if( !empty( $github_url ) )
			$output .= '<p class="wp-caption-text"><a href="'.$github_url.'" title="github file" target="_blank">'.__('View this code on github', 'thaim').'</a></p>';
		
		return $output;
	}
		
}

function thaim_add_quicktags() {
	$url = get_template_directory_uri() . '/includes/thaim-shortcode-editor.php';
	$window_title = __('Prettify your snippet !', 'thaim');
?>
    <script type="text/javascript">

	if ( typeof QTags != 'undefined' )
		QTags.addButton( 'eg_tcode', 'thaim code', my_callback );
		
	function my_callback() { 
		var url = "<?php echo $url;?>";
		tb_show("<?php echo $window_title;?>", url + '?type=thaim_code&amp;TB_iframe=true');
	}
    </script>
<?php
}


add_action( 'admin_print_footer_scripts', 'thaim_add_quicktags' );