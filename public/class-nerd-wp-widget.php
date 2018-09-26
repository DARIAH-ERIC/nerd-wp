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
						$wiki_array[ $array[0] ] = $array[1];
					}
				}

				if ( array_key_exists( $CATEGORY_ID, $wiki_array ) ) {
					if( $used_tags == 0 ) {
						echo $args['before_widget'];
						echo $args["before_title"] . 'NERD Plugin' . $args["after_title"];
					}
					$used_tags = $used_tags + 1;
					echo '<a target="_blank" class="label" href="https://en.wikipedia.org/wiki?curid=' . $wiki_array[$CATEGORY_ID] . '">' . $tag->name . '</a> ';
				} else if ( array_key_exists( $WIKIPEDIA_ID, $wiki_array ) || array_key_exists( $WIKIDATA_ID, $wiki_array ) ) {
					if( $used_tags == 0 ) {
						echo $args['before_widget'];
						echo $args["before_title"] . 'NERD Plugin' . $args["after_title"];
					}
					$used_tags = $used_tags + 1;
					echo '<span class="label nerd_tags" id="' . explode( ";", $tag->description )[0] . '">' . $tag->name . '</span> ';
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
	}

	function nerd_wp_widget() {
		register_widget( $this );
	}
}

?>
