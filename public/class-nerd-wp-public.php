<?php
/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @link       https://www.dariah.eu
 * @since      1.0.0
 * @package    Nerd_Wp
 * @subpackage Nerd_Wp/public
 * @author     Yoann Moranville <yoann.moranville@dariah.eu>
 */
use GuzzleHttp\Exception\RequestException;
class Nerd_Wp_Public {
	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string $plugin_name The ID of this plugin.
	 */
	private $plugin_name;
	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string $version The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 *
	 * @param      string $plugin_name The name of the plugin.
	 * @param      string $version The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {
		$this->plugin_name = $plugin_name;
		$this->version     = $version;
	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
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
		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/nerd-wp-public.css', array(), $this->version, 'all' );
	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
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
		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/nerd-wp-public.js', array( 'jquery' ), $this->version, false );
		wp_enqueue_script( $this->plugin_name . 'wiki2html', plugin_dir_url( __FILE__ ) . 'js/wiki2html.js', array( 'jquery' ), $this->version, false );

	}

    public function access_nerd_kb() {
	    $url = '/nerd_kb_service/?url=';
        if ( substr( $_SERVER['REQUEST_URI'], 0, strlen( $url ) ) === $url ) {
            $nerd_query = substr( $_SERVER['REQUEST_URI'], strlen( $url ) );

            $options = get_option( $this->plugin_name );
            $url_nerd_instance = $options['url_nerd_instance'];
            if( $url_nerd_instance ) {;
                if ( substr( $url_nerd_instance, - 1 ) != '/' ) { // In case the URL provided does not contain a ending slash, add it
                    $url_nerd_instance = $url_nerd_instance . "/";
                }
                $access_url = $url_nerd_instance . urldecode( $nerd_query );

                $client = new GuzzleHttp\Client();
                try {
                    $response = $client->request( 'GET', $access_url );
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
                wp_send_json( $response->getBody()->getContents() );
            }
        }
        return "";
    }
}
