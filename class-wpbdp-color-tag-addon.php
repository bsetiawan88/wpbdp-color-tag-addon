<?php
/**
 * Plugin Name: Business Directory Plugin - Color Tag Addon
 * Plugin URI: https://github.com/bsetiawan88
 * Author: Bagus
 * Version: 0.0.1
 * License: GPLv3 or later
 * Author URI: https://github.com/bsetiawan88
 *
 * @package wpbdp-tags
 **/

/**
 * Sends abandoned payment notifications to users.
 */
class Wpbdp_Color_Tag_Addon {

	/**
	 * Specify which tags to give the color to.
	 *
	 * @var array
	 */
	public $tags_to_replace = array(
		'Hot'  => '#FD8A8A',
		'New'  => '#F1F7B5',
		'Cool' => '#AEE2FF',
	);

	/**
	 * Constructor.
	 */
	public function __construct() {
		add_action( 'init', array( $this, 'init' ) );
	}

	/**
	 * Init.
	 */
	public function init() {
		if ( ! is_admin() ) {
			add_filter( 'wpbdp_form_field_display', array( $this, 'wpdbp_format_tag' ), 10, 4 );
			add_action( 'wp_enqueue_scripts', array( $this, 'add_inline_style' ) );
		}
	}

	/**
	 * Add inline style for tags.
	 *
	 * @return void
	 */
	public function add_inline_style() {
		$css = '[wpbdp-custom-tag] {padding:1px 10px;border-radius:10px;}';
		foreach ( $this->tags_to_replace as $label => $color ) {
			$css .= '[wpbdp-custom-tag="' . $label . '"]{background-color:' . $color . ';}';
		}

		wp_register_style( 'wpbdp-tag-addon', false, array(), '0.0.1' );
		wp_enqueue_style( 'wpbdp-tag-addon' );
		wp_add_inline_style( 'wpbdp-tag-addon', $css );
	}

	/**
	 * Filter to format tags.
	 *
	 * @param string $html the html for tags output.
	 * @param object $field the WPBDP_Form_Field object.
	 * @param string $display_context the display context. defaults to 'listing'.
	 * @param int    $listing_id the ID of the listing.
	 * @return string
	 */
	public function wpdbp_format_tag( $html, $field, $display_context, $listing_id ) {
		if ( 'listing_tags' === $field->get_shortname() ) {
			foreach ( $this->tags_to_replace as $label => $color ) {
				if ( false !== strpos( $html, '>' . $label . '<' ) ) {
					$html = str_replace( '>' . $label . '<', ' wpbdp-custom-tag="' . $label . '">' . $label . '<', $html );
				}
			}
		}
		return $html;
	}
}

new Wpbdp_Color_Tag_Addon();
