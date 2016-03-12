<?php
/**
 * Thaim Widgets
 */

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

/**
 * This widget extends the WordPress built in Tag Cloud
 * to allow the user to exclude some tags.
 *
 * @since  2.0.0
 */
class Thaim_Tag_Cloud extends WP_Widget_Tag_Cloud {
	/**
	 * Sets up an extended Tag Cloud widget instance.
	 *
	 * @since 2.0.0
	 * @access public
	 */
	public function __construct() {
		$widget_ops = array( 'description' => esc_html__( 'A cloud of your most used tags, with the ability to exclude some of your choice.', 'thaim' ) );
		WP_Widget::__construct( 'thaim_tag_cloud', __( '(Thaim) Tag Cloud', 'thaim' ), $widget_ops );
	}

	/**
	 * Register the widget
	 *
	 * @since 2.0.0
	 */
	public static function register_widget() {
		register_widget( 'Thaim_Tag_Cloud' );
	}

	public function widget( $args, $instance ) {
		add_filter( 'widget_tag_cloud_args', array( $this, 'exclude_tags' ), 10, 1 );

		parent::widget( $args, $instance );

		remove_filter( 'widget_tag_cloud_args', array( $this, 'exclude_tags' ), 10, 1 );
	}

	public function exclude_tags( $args = array() ) {
		$instances = $this->get_settings();

		if ( array_key_exists( $this->number, $instances ) ) {
			$instance = $instances[ $this->number ];
		}

		if ( empty( $instance['exclude'] ) ) {
			return $args;
		}

		$exclude = maybe_unserialize( $instance['exclude'] );

		if ( ! empty( $exclude ) ) {
			$args = array_merge( $args, array( 'exclude' => $exclude ) );
		}

		return $args;
	}

	public function update( $new_instance, $old_instance ) {
		$instance = parent::update( $new_instance, $old_instance );
		$instance['exclude'] = maybe_serialize( wp_parse_id_list( $new_instance['exclude'] ) );
		return $instance;
	}

	public function form( $instance ) {
		parent::form( $instance );

		$exlude = '';
		if ( ! empty( $instance['exclude'] ) ) {
			$exclude = join( ',', maybe_unserialize( $instance['exclude'] ) );
		}

		if ( 0 === (int) $exclude ) {
			$exclude = '';
		}

		$title_id = $this->get_field_id( 'exclude' );

		printf( '<p><label for="%1$s">%2$s</label><input type="text" class="widefat" id="%1$s" name="%3$s" value="%4$s" /></p>',
			$this->get_field_id( 'exclude' ),
			esc_html__( 'Comma separated list of Tag ids to exclude:', 'thaim' ),
			$this->get_field_name( 'exclude' ),
			esc_attr( $exclude )
		);
	}
}
add_action( 'widgets_init', array( 'Thaim_Tag_Cloud', 'register_widget' ), 10 );

/**
 * A widget to display category blocks
 *
 * @since  2.0.0
 */
class Thaim_Category_Boxes extends WP_Widget {

 	/**
	 * Sets up an extended Categories widget instance.
	 *
	 * @since 2.0.0
	 * @access public
	 */
	public function __construct() {
		$widget_ops = array( 'description' => esc_html__( 'Category boxes for your home page.', 'thaim' ) );
		parent::__construct( 'thaim_categories', esc_html__( '(Thaim) Category boxes', 'thaim' ), $widget_ops );
	}

	/**
	 * Register the widget
	 *
	 * @since 2.0.0
	 */
	public static function register_widget() {
		register_widget( 'Thaim_Category_Boxes' );
	}

	/**
	 * Displays the content of the widget
	 *
	 * @param  array  $args
	 * @param  array  $instance
	 */
	public function widget( $args = array(), $instance = array() ) {
		$categories = get_categories( array( 'orderby' => 'count', 'order' => 'DESC' ) );

		if ( empty( $categories ) ) {
			return;
		}

		$show_description = false;
		if ( isset( $instance['description'] ) )  {
			$show_description = (bool) $instance['description'];
		}

		$show_dashicon = false;
		if ( isset( $instance['dashicon'] ) )  {
			$show_dashicon = (bool) $instance['dashicon'];
		}

		$count = count( $categories );
		$last  = 0;

		echo $args['before_widget'];

		foreach( $categories as $category ) {
			$last += 1;

			$class = '';
			if ( $last === $count ) {
				$class = ' last';
			}

			$term_link = get_term_link( $category );
			$dashicon = false;

			if ( $show_dashicon ) {
				$dashicon = thaim_get_term_dashicon( $category->term_id );

				if ( $dashicon ) {
					$dashicon .= '&nbsp;';
				}
			}

			printf( '<div class="fourcol%s">', $class );

			printf( $args['before_title'], $category->slug );

			printf( '<a href="%1$s" title="%2$s">%3$s</a>',
				esc_url( $term_link ),
				esc_attr( $category->name ),
				$dashicon . esc_html( $category->name )
			);

			echo $args['after_title'];

			if ( $show_description && ! empty( $category->description ) ) {
				printf( '<p>%s</p>', esc_html( $category->description ) );
			}

			printf( '<p class="readmore"><a class="view-article" href="%1$s" title="%2$s">%3$s &rarr;</a></p>',
				esc_url( $term_link ),
				esc_attr( $category->name ),
				esc_html__( 'View Articles', 'thaim' )
			);

			echo '</div>';
		}

		echo $args['after_widget'];
	}

	public function update( $new_instance, $old_instance ) {
		$instance = array();

		$instance['description'] = 0;
		if ( ! empty( $new_instance['description'] ) ) {
			$instance['description'] = 1;
		}

		$instance['dashicon'] = 0;
		if ( ! empty( $new_instance['dashicon'] ) ) {
			$instance['dashicon'] = 1;
		}

		return $instance;
	}

	/**
	 * Display the form in Widgets Administration
	 *
	 * @since 2.0.0
	 *
	 * @param array $instance The Widget instance
	 */
	public function form( $instance = array() ) {
		//Defaults
		$instance = wp_parse_args( (array) $instance, array() );

		$description = false;
		if ( isset( $instance['description'] ) )  {
			$description = (bool) $instance['description'];
		}

		$dashicon = false;
		if ( isset( $instance['dashicon'] ) )  {
			$dashicon = (bool) $instance['dashicon'];
		}
		?>
		<p>
			<input type="checkbox" class="checkbox" id="<?php echo $this->get_field_id( 'description' ); ?>" name="<?php echo $this->get_field_name( 'description' ); ?>"<?php checked( $description ); ?> />
			<label for="<?php echo $this->get_field_id( 'description' ); ?>"><?php _e( 'Display category descriptions', 'thaim' ); ?></label><br />
		</p>
		<p>
			<input type="checkbox" class="checkbox" id="<?php echo $this->get_field_id( 'dashicon' ); ?>" name="<?php echo $this->get_field_name( 'dashicon' ); ?>"<?php checked( $dashicon ); ?> />
			<label for="<?php echo $this->get_field_id( 'dashicon' ); ?>"><?php _e( 'Display category icons', 'thaim' ); ?></label><br />
		</p>
		<?php
	}
}
add_action( 'widgets_init', array( 'Thaim_Category_Boxes', 'register_widget' ), 10 );
