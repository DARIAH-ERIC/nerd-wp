<?php
namespace Nerd_Wp_Plugin_Lib\Utils;

use WP_Ajax_Response;

/**
 *
 * Notice from Yoann: This file has been downloaded from the PressForward tool (https://github.com/PressForward/pressforward/)
 *
 * ReadabilityForNerd stuff
 */

class PF_ReadabilityForNerd {

	public function __construct() {

}

	/**
	 * Abstract function to make everything readable.
	 *
	 * Potential arguments to base via array
	 *          $args = array(
	 *          'force'         => $force,
	 *          'descrip'       => $_POST['content'],
	 *          'url'           => $url,
	 *          'authorship'    => $_POST['authorship']
	 *      );
	 */
	public function get_readable_text( $args ) {
		set_time_limit( 0 );
		$readability_stat = $args['url'];
		// var_dump($args);
		$url = $readability_stat;
		// var_dump($url); die();
		$descrip = rawurldecode( $args['descrip'] );
		if ( get_magic_quotes_gpc() ) {
			$descrip = stripslashes( $descrip ); }

		if ( $args['authorship'] == 'aggregation' ) {
			$aggregated = true;
		} else {
			$aggregated = false;
		}
		$stripped_descrip = strip_tags( $descrip );
		if ( ( str_word_count( $stripped_descrip ) <= 150 ) || $aggregated || $args['force'] == 'force' ) {
			$itemReadReady = $this->readability_object( $url );
			// print_r(  wp_richedit_pre($itemReadReady));
			if ( $itemReadReady != 'error-secured' ) {
				if ( ! $itemReadReady ) {
					$read_status       = 'failed_readability';
					$readability_stat .= __( ' This content failed ReadabilityForNerd.', 'pf' );
					// $itemReadReady .= '<br />';
					$url = str_replace( '&amp;', '&', $url );
					// Try and get the OpenGraph description.
					$open_graph = new OpenGraphForNerd();
					$open_graph_fetched = $open_graph->fetch( $url );
					if ( $open_graph_fetched ) {
						$itemReadReady = $open_graph_fetched->description;
					} elseif ( '' != ( $contentHtml = @get_meta_tags( $url ) ) ) { //Note the @. This is because get_meta_tags doesn't have a failure state to check, it just throws errors. Thanks PHP...
						// Try and get the HEAD > META DESCRIPTION tag.
						$read_status = 'failed_readability_og';
						// $itemReadReady .= '<br />';
						$itemReadReady = $contentHtml['description'];

					} else {
						// Ugh... we can't get anything huh?
						$read_status = 'failed_readability_og_meta';
						// $itemReadReady .= '<br />';
						// We'll want to return a false to loop with.
						$itemReadReady = $descrip;

					}
					if ( strlen( $itemReadReady ) < strlen( $descrip ) ) {
						$itemReadReady     = $descrip;
						$readability_stat .= ' Retrieved text is less than original text.';
						$read_status       = 'already_readable';
					}
				} else {
					$read_status   = 'made_readable';
				}
			} else {
				$read_status   = 'secured';
				$itemReadReady = $descrip;
			}
		} else {
			$read_status   = 'already_readable';
			$itemReadReady = $descrip;
		}

		$return_args = array(
			'status'   => $read_status,
			'readable' => $itemReadReady,
			'url'      => $url,
		);
		// ob_end_flush();
		return $return_args;

	}

	/**
	 * Runs a URL through ReadabilityForNerd and hands back the stripped content
	 *
	 * @since 1.7
	 * @see http://www.keyvan.net/2010/08/php-readability/
	 * @param $url
	 */
	public function readability_object( $url ) {

		set_time_limit( 0 );
		$retrieveHttpContent = new RetrieveHttpContentForNerd();
		$request = $retrieveHttpContent->get_url_content( $url, 'wp_remote_get' );

		if ( is_wp_error( $request ) ) {
			$content = 'error-secured';
			return $content;
		}
		if ( ! empty( $request['body'] ) ) {
			$html = $request['body'];
		} elseif ( ! empty( $request ) && ( ! is_array( $request ) ) ) {
			$html = $request;
		} else {
			$content = false;
			return $content;
		}

		return $this->process_readability( $html, $url );
	}

	public function process_readability( $html, $url ) {
		// check if tidy exists to clean up the input.
		if ( function_exists( 'tidy_parse_string' ) ) {
			$tidy = tidy_parse_string( $html, array( 'wrap' => 0 ), 'UTF8' );
			$tidy->cleanRepair();
			$html = $tidy->value;
		}
		// give it to ReadabilityForNerd
		$readability = new ReadabilityForNerd( $html, $url );

		// print debug output?
		// useful to compare against Arc90's original JS version -
		// simply click the bookmarklet with FireBug's
		// console window open
		$readability->debug = false;

		// convert links to footnotes?
		$readability->convertLinksToFootnotes = false;

		// process it
		$result = $readability->init();

		if ( $result ) {
			$content = $readability->getContent()->innerHTML;
			// $content = $contentOut->innerHTML;
			// if we've got tidy, let's use it.
			if ( function_exists( 'tidy_parse_string' ) ) {
				$tidy = tidy_parse_string(
					$content,
					array(
						'indent'         => true,
						'show-body-only' => true,
						'wrap'           => 0,
					),
					'UTF8'
				);
				$tidy->cleanRepair();
				$content = $tidy->value;
			}

			$content    = balanceTags( $content, true );
			$content    = ent2ncr( $content );
			$content    = convert_chars( $content );
			$domRotated = 0;
			$dom        = new \domDocument( '1.0', 'utf-8' );

			$dom->preserveWhiteSpace = true;
			$dom->substituteEntities = true;
			$dom->resolveExternals   = true;
			$dom->loadXML( '<fullContent>' . $content . '</fullContent>' );
			$images = $dom->getElementsByTagName( 'img' );
			foreach ( $images as $image ) {
				$img = $image->getAttribute( 'src' );
				if ( ( ( strpos( $img, '/' ) ) === 0 ) || ( strpos( $img, 'http' ) != 0 ) ) {
					$urlArray = parse_url( $url );
					if ( ( strpos( $img, 'http' ) != 0 ) ) {
						$urlBase = 'http://' . $urlArray['host'] . '/';
					} else {
						$urlBase = 'http://' . $urlArray['host'];
					}
					if ( ! is_wp_error( wp_remote_head( $urlBase . $img ) ) ) {
						$image->setAttribute( 'src', $urlBase . $img );
						$domRotated++;
					} elseif ( ! is_wp_error( wp_remote_head( $url . $img ) ) ) {
						$image->setAttribute( 'src', $url . $img );
						$domRotated++;
					} else {
						$image->parentNode->removeChild( $image );
						$domRotated++;
					}
				}
			}
			if ( $domRotated > 0 ) {
				$content = $dom->saveXML();
				$rel     = '(<\\?xml version="1\\.0" encoding="utf-8"\\?>)';
				$content = preg_replace( '/' . $rel . '/is', ' ', $content );
				$rel     = '(<\\?xml version="1\\.0"\\?>)';
				$content = preg_replace( '/' . $rel . '/is', ' ', $content );
			}
			if ( 120 > strlen( $content ) ) {
				$content = false;}
			// $content = stripslashes($content);
			// print_r($content);
			// var_dump($content); die();
			// this will also output doctype and comments at top level
			// $content = "";
			// foreach($dom->childNodes as $node){
			// $content .= $dom->saveXML($node)."\n";
			// }
		} else {
			// If ReadabilityForNerd can't get the content, send back a FALSE to loop with.
			$content = false;
			// and let's throw up an error via AJAX as well, so we know what's going on.
			// print_r($url . ' fails ReadabilityForNerd.<br />');
		}
		if ( $content != false ) {
			$html_checker = new HTMLCheckerForNerd();
			$content    = $html_checker->closetags( $content );
		}

		return $content;
	}
}
