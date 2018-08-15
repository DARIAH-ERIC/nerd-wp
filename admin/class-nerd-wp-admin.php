<?php
/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @link       https://www.dariah.eu
 * @since      1.0.0
 *
 * @package    Nerd_Wp
 * @subpackage Nerd_Wp/admin
 * @author     Yoann <yoann.moranville@dariah.eu>
 */

use GuzzleHttp\Exception\RequestException;
class Nerd_Wp_Admin {
	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;
	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;
	/**
	 * This is the name of the submit button of the relaunch NERD meta box. We have it in a variable because it is very important for the logic and we only want to name it once.
	 *
	 * @since   1.0.0
	 * @access  private
	 * @var     string      $submit_btn_name    The name of the submit button.
	 */
	private $submit_btn_name = "relaunch-nerd";
	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {
		$this->plugin_name = $plugin_name;
		$this->version = $version;
	}
	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {
		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Nerd_Wp_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Nerd_Wp_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */
		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/nerd-wp-admin.css', array(), $this->version, 'all' );
	}
	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {
		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Nerd_Wp_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Nerd_Wp_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */
		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/nerd-wp-admin.js', array( 'jquery' ), $this->version, false );
	}

	/**
	 * Register the administration menu for this plugin into the WordPress Dashboard menu.
	 *
	 * @since    1.0.0
	 */
	public function add_plugin_admin_menu() {
		/*
		 * Add a settings page for this plugin to the Settings menu.
		 *
		 * NOTE:  Alternative menu locations are available via WordPress administration menu functions.
		 *
		 *        Administration Menus: http://codex.wordpress.org/Administration_Menus
		 *
		 */
		add_options_page( 'NERD Setup', 'NERD', 'manage_options', $this->plugin_name, array($this, 'display_plugin_setup_page')
		);
	}
	/**
	 * Add settings action link to the plugins page.
	 *
	 * @since    1.0.0
	 */
	public function add_action_links( $links ) {
		/*
		 *  Documentation : https://codex.wordpress.org/Plugin_API/Filter_Reference/plugin_action_links_(plugin_file_name)
		 */
		$settings_link = array(
			'<a href="' . admin_url( 'options-general.php?page=' . $this->plugin_name ) . '">' . __('Settings', $this->plugin_name) . '</a>',
		);
		return array_merge(  $settings_link, $links );
	}
	/**
	 * Render the settings page for this plugin.
	 *
	 * @since    1.0.0
	 */
	public function display_plugin_setup_page() {
		include_once( 'partials/nerd-wp-admin-display.php' );
	}
	/**
	 *  Save the plugin options
	 *
	 *
	 * @since    1.0.0
	 */
	public function options_update() {
		register_setting( $this->plugin_name, $this->plugin_name, array($this, 'validate', 'default' => array( "url_nerd_instance" => "", "category_weight" => "0.04", "entity_weight" => "0.7" ) ) );
	}
	/**
	 * Validate all options fields
	 *
	 * @since    1.0.0
	 */
	public function validate( $input ) {
		// All checkboxes inputs
		$valid = array();
		$valid['url_nerd_instance'] = (isset($input['url_nerd_instance']) && !empty($input['url_nerd_instance'])) ? sanitize_text_field($input['url_nerd_instance']) : '';
		if ( empty($valid['url_nerd_instance']) ) {
			add_settings_error(
				'url_nerd_instance', // Setting title
				'url_nerd_instance_texterror', // Error ID
				'Please enter a valid value '.$valid['url_nerd_instance'], // Error message
				'error' // Type of message
			);
		}
		$valid['category_weight'] = (isset($input['category_weight']) && !empty($input['category_weight'])) ? sanitize_text_field($input['category_weight']) : '';
		if ( empty($valid['category_weight']) ) {
			add_settings_error(
				'category_weight',
				'category_weight_texterror',
				'Please enter a valid value '.$valid['category_weight'],
				'error'
			);
		}
		$valid['entity_weight'] = (isset($input['entity_weight']) && !empty($input['entity_weight'])) ? sanitize_text_field($input['entity_weight']) : '';
		if ( empty($valid['entity_weight']) ) {
			add_settings_error(
				'entity_weight',
				'entity_weight_texterror',
				'Please enter a valid value '.$valid['entity_weight'],
				'error'
			);
		}
		return $valid;
	}

	function render_extra_fields() {
		$term_id = $_GET['tag_ID'];
		$term = get_term_by( 'id', $term_id, 'post_tag' );
		$meta = get_option( "taxonomy_{$term_id}" );
		//Insert HTML and form elements here
		return "<input type='text' name='Yoyo'>test</input>";
	}

	function save_extra_fields( $term_id ) {
		$form_field_1 = $_REQUEST['field-name-1'];
		$form_field_2 = $_REQUEST['field-name-2'];
		$meta['key_value_1'] = $form_field_1;
		$meta['key_value_2'] = $form_field_2;
//		update_option( "taxonomy_{$term_id}", $meta );
	}

	function add_post_tag_columns( $columns ) {
		$columns['wiki_id'] = 'Wiki ID';
		return $columns;
	}

	function add_post_tag_column_content( $content, $else, $term_id ) {
		$WIKIPEDIA_ID = "wikipedia_id";
		$WIKIDATA_ID = "wikidata_id";
		$CATEGORY_ID = "category_id";
		$term = get_term_by( 'id', $term_id, 'post_tag' );
		$return_links = "";
		$ids = explode( ";", $term->description );
		$wiki_array = array();
		foreach ( $ids as $value ) {
			if ( strpos( $value, ':' ) !== false ) {
				$array                   = explode( ':', $value );
				$wiki_array[$array[0]] = $array;
			}
		}
		foreach ( $wiki_array as $key => $array ) {
			if ( $key == $WIKIPEDIA_ID || $key == $CATEGORY_ID ) {
				$return_links .= '<a target="_blank" href="https://"' . ( $array[2] !== false ? "fr" : "en" ) . '".wikipedia.org/wiki?curid=' .
				                 $array[1] .
				                 '">' .
				                 $key . ":" . $value . '</a> ';
			} else if ( $key == $WIKIDATA_ID ) {
				$return_links .= '<a target="_blank" href="https://www.wikidata.org/wiki/' . $value . '">' . $key . ":" . $value . '</a> ';
			}
		}
		return $return_links;
	}

	function relaunch_nerd() {
		wp_nonce_field( basename( __FILE__ ), "meta-box-nonce" );
		echo "<div id=\"relaunch-nerd-action\"><input name=\"" . $this->submit_btn_name . "\" id=\"relaunch-nerd-post\" value=\"Relaunch Nerd\" class=\"button\" type=\"submit\">" .
		     "<span class=\"spinner\"></span></div>";
	}

	function nerd_meta_box() {
		add_meta_box("nerd-meta-box", "NERD WP", array($this, "relaunch_nerd"), "post", "side", "high", 0);
	}

	/**
	 * Checks the meta box input and relaunch NERD if necessary
	 */
	function nerd_meta_save( $post_id ) {
		// Checks save status
		$is_autosave = wp_is_post_autosave( $post_id );
		$is_revision = wp_is_post_revision( $post_id );
		$is_valid_nonce = ( isset( $_POST[ 'meta-box-nonce' ] ) && wp_verify_nonce( $_POST[ 'meta-box-nonce' ], basename( __FILE__ ) ) ) ? 'true' : 'false';
		// Exits script depending on save status
		if ( $is_autosave || $is_revision || !$is_valid_nonce ) {
			return;
		}
		$this->on_post_sent_to_draft( $post_id );
	}

	/**
	 * Action to do when a post is saved (draft or updated)
	 * @since    1.0.0
	 *
	 * @param $post_id int The identifier of the post just created or updated
	 */
	public function on_post_sent_to_draft( $post_id ) {
		error_log( "Start 'send to draft' hook" );

		if( ! isset( $_POST["pf_drafted_nonce"] ) && ! isset( $_POST[$this->submit_btn_name] ) ) {
			error_log( "We do not launch NERD, end of hook" );
			return;
		} else {  //If it comes directly from PressForward or we click "relaunch NERD"
			error_log( "Let's launch NERD !" );
		}

		$post = get_post( $post_id );
		if( get_post_meta( $post_id, 'item_link', true ) != false ) { //If it comes from PressForward
			$readArgs         = array(
				'force'      => false,
				'descrip'    => htmlspecialchars_decode( $post->post_content ),
				'url'        => get_post_meta( $post_id, 'item_link', true ),
				'authorship' => 'auto'
			);
			$pf_readability = new \Nerd_Wp_Lib\Utils\PF_ReadabilityForNerd();
			$item_content_obj = $pf_readability ->get_readable_text( $readArgs );
			$item_content = $item_content_obj['readable'];
		} else { //If it is a simple text written
			$item_content = $post->post_content;
		}


		// Retrieve the text from the URL

		$item_content     = strip_tags( htmlspecialchars_decode( $item_content ) );
		$item_content     = preg_replace( '/\s*$^\s*/m', "\n", $item_content );
		$item_content     = preg_replace( '/[\x00-\x1F\x7F]/u', ' ', $item_content );
		$item_content     = str_replace( "&#13;", ' ', $item_content );
		$item_content     = str_replace( "'", "\'", $item_content );
		$item_content     = preg_replace( '/[ \t]+/', ' ', $item_content );

		// Prepare the query to send to NERD
		$array_query = array(
			'text' => $item_content,
			'shortText' => '',
			'termVector' => array(

			),
//			'language' => array( //Language of text
//				'lang' => 'en'
//			),
			'entities' => array(

			),
			'onlyNER' => false,
			'resultLanguages' => array( //Language of wikipedia pages if those pages exist
				'de',
				'fr'
			),
			'nbest' => false,
			'sentence' => false,
			'customisation' => 'generic'
		);
		$query = json_encode( $array_query );

		// Sending request to NERD and retrieve its JSON response
		$client = new GuzzleHttp\Client();
		$options = get_option($this->plugin_name);
		$url_nerd_instance = $options['url_nerd_instance'];
		if( array_key_exists( 'category_weight', $options ) ) {
			$category_weight = $options['category_weight'];
		}
		if( array_key_exists( 'entity_weight', $options ) ) {
			$entity_weight = $options['entity_weight'];
		}

		if( ! $url_nerd_instance ) {
			return;
		}
		if( substr($url_nerd_instance, -1) != '/' ) { // In case the URL provided does not contain a ending slash, add it
			$url_nerd_instance = $url_nerd_instance . "/";
		}
		$response = false;
		try {
			// NERD only accepts multipart/form-data: http://nerd.readthedocs.io/en/latest/restAPI.html#post-disambiguate
			$response = $client->request( 'POST', $url_nerd_instance . 'service/disambiguate', [
					'multipart' => [
						[
							'name' => 'query',
							'contents' => $query
						]
					],
					'connect_timeout' => 0.1
				]
			);

			error_log( "Code: " . $response->getStatusCode() );
			if( $response->getStatusCode() != 200 ) {
				error_log( "Reason: " . $response->getReasonPhrase() );
			}

		} catch ( RequestException $e ) {
			if ( $e->hasResponse() ) {
				error_log( $e->getMessage() );
				error_log( $e->getResponse()->getBody()->getContents() );
			}
		} catch ( \GuzzleHttp\Exception\GuzzleException $e ) {
			error_log( $e->getMessage() );
		}

		if( $response ) {
			$nerd_response = json_decode( $response->getBody()->getContents(), true );
			error_log( "=== Global categories ===" );
			foreach ($nerd_response["global_categories"] as $category) {
				if( $category["weight"] > $category_weight) {
					error_log($category["category"] . " - page_id: " . $category["page_id"]);
//					wp_set_post_tags( $post_id, $category["category"], true );
					$this->add_taxonomy_with_wikipedia_id( $post_id, $category["category"], 'category', array( "category_id" => $category["page_id"] ) );
				}
			}
			error_log( "=== Named entities ===" );
			foreach ($nerd_response["entities"] as $entity) {
				if( $entity["nerd_selection_score"] > $entity_weight ) {
					error_log($entity["rawName"] . " - nerd_selection_score: " . $entity["nerd_selection_score"]);
//					wp_set_post_tags( $post_id, $category["category"], true );
					$wiki_array = array();
					if( array_key_exists( "wikipediaExternalRef", $entity ) ) {
						$wiki_array["wikipedia_id"] = $entity["wikipediaExternalRef"];
					}
					if( array_key_exists("wikidataId", $entity) ) {
						$wiki_array["wikidata_id"] = $entity["wikidataId"];
					}
					$this->add_taxonomy_with_wikipedia_id( $post_id, $entity["rawName"], 'entity', $wiki_array );
				}
			}
		}

		error_log( "End 'send to draft' hook" );
	}

	/**
	 * Adding the WIKIPEDIA ID to the taxonomy for this post, so we can use it later on the view page
	 *
	 * @param int $post_id The ID of the post
	 * @param array $wiki_id The IDs of the wikipedia/wikidata entries
	 *
	 * @return null Nothing
	 */
	public function add_taxonomy_with_wikipedia_id( $post_id, $term, $type, $wiki_id ) {
		$description = "";
		foreach( $wiki_id as $key => $value ) {
			if( $description != "" ) {
				$description .= ";";
			}
			$description .= $key . ':' . $value;
		}
		$term_args = array(
			'description' => $description,
			'parent'      => 0
		);
		$r = wp_insert_term( $term, 'post_tag', $term_args );
		if ( ! is_wp_error( $r ) && ! empty( $r['term_id'] ) ) {
			wp_set_object_terms( $post_id, intval( $r['term_id'] ), 'post_tag', true );
		} else if( is_wp_error( $r ) && $r->get_error_data('term_exists') ) {
			wp_set_object_terms( $post_id, intval( $r->get_error_data('term_exists') ), 'post_tag', true );
		} else {
			error_log( 'Failed making a new post_tag' );
			error_log( print_r( $r, true ) );
		}
		return;
	}
}
