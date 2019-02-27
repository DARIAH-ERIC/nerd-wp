<?php

class Nerd_Wp_Widget extends WP_Widget {

	function __construct() {
		parent::__construct( 'nerd_wp_widget', __( 'NERD WP Widget', 'nerd_wp_domain' ), array(
			'description' => __( 'NERD WP Widget', 'nerd_wp_domain' )
		) );
	}

	public function widget( $args, $instance ) {
		$WIKIPEDIA_ID = "wikipedia_id";
		$WIKIDATA_ID = "wikidata_id";
		$CATEGORY_ID = "category_id";
		$LANGUAGE = "language";

        $title = apply_filters( 'widget_title', $instance['title'] );

		if( is_single() ) {
			global $post;

			$used_tags = 0;
			$tags_names		= wp_get_post_tags( $post->ID );
			foreach( $tags_names as $tag ) {
				$ids = explode( ";", $tag->description );
				$wiki_array = array();
				foreach ( $ids as $value ) {
					if ( strpos( $value, ':' ) !== false ) {
						$array = explode( ':', $value );
						$wiki_array[ $array[0] ] = $array;
					}
				}

				if ( array_key_exists( $CATEGORY_ID, $wiki_array ) ) {
					if( $used_tags == 0 ) {
						echo $args['before_widget'];
						echo $args["before_title"] . $title . $args["after_title"];
					}
					$used_tags = $used_tags + 1;
                    $lang = "en";
                    if( sizeof( $wiki_array[$CATEGORY_ID] ) > 2 ) {
                        $lang = $wiki_array[$CATEGORY_ID][2];
                    }
					echo '<a target="_blank" class="label" href="https://' . $lang . '.wikipedia.org/wiki?curid=' .
                                                                           $wiki_array[$CATEGORY_ID][1] . '">' .
                         $tag->name . '</a> ';
				} else if ( array_key_exists( $WIKIPEDIA_ID, $wiki_array ) || array_key_exists( $WIKIDATA_ID, $wiki_array ) ) {
					if( $used_tags == 0 ) {
						echo $args['before_widget'];
						echo $args["before_title"] . $title . $args["after_title"];
					}
					$used_tags = $used_tags + 1;
					echo '<span class="label nerd_tags" id="' . explode( ";", $tag->description )[0] . '">' .
                         $tag->name . '<div class="info-sense-box waiting"><img src="' . plugin_dir_url( __FILE__ ) . 'images/ajax-loader.gif" alt="loading..."/></div></span> ';
				}
            }
            if( $used_tags > 0 ) {
                echo '<script type="text/javascript">
                        jQuery(document).ready(function($){
                            hoverEntity();
                        });
                      </script>';
	            echo $args['after_widget'];
            }
		}
	}

	/**
     * The form function is empty because we don't manage anything here
	 * @param array $instance
	 *
	 * @return string|void
	 */
	public function form( $instance ) {
        $title = ! empty( $instance['title'] ) ? $instance['title'] : __( 'Named entity-fishing', 'text_domain' );
        echo '<p><label for="' . $this->get_field_id( 'title' ) . '">' . _e( 'Title:' ) . '</label>
        <input class="widefat" id="' . $this->get_field_id( 'title' ) . '" name="' .
             $this->get_field_name( 'title' ) . '" type="text" value="' . esc_attr( $title ) . '" /></p>';
	}

    public function update( $new_instance, $old_instance ) {
        $instance = array();
        $instance['title'] = ( ! empty( $new_instance['title'] ) ) ? strip_tags( $new_instance['title'] ) : '';
        return $instance;
    }

	function nerd_wp_widget() {
		register_widget( $this );
	}
}
?>
