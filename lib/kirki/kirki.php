<?php
/*
Plugin Name: Kirki Framework
Plugin URI: http://kirki.org
Description: An options framework using and extending the WordPress Customizer
Author: Aristeides Stathopoulos
Author URI: http://wpmu.io/
Version: 0.3
*/

/**
* The main Kirki class
*/
if ( ! class_exists( 'Kirki' ) ) :
class Kirki {

	function __construct() {

		$this->options = apply_filters( 'kirki/config', array() );
		$options = $this->options;

		// Include files
		include_once( dirname( __FILE__ ) . '/includes/functions/color-functions.php' );

		if ( ! isset( $options['live_css'] ) || true == $options['live_css'] ) {
			include_once( dirname( __FILE__ ) . '/includes/functions/background-css.php' );
		}
		include_once( dirname( __FILE__ ) . '/includes/functions/required.php' );

		// Include the controls initialization script
		include_once( dirname( __FILE__ ) . '/includes/controls/controls-init.php' );

		// Include the fonts class
		include_once( dirname( __FILE__ ) . '/includes/functions/class-Kirki_Fonts.php' );

		add_action( 'customize_register', array( $this, 'include_files' ), 1 );
		add_action( 'customize_controls_print_styles', array( $this, 'styles' ) );
		add_action( 'customize_controls_print_styles', array( $this, 'googlefonts' ) );
		add_action( 'customize_controls_print_styles', array( $this, 'custom_css' ), 999 );
		add_action( 'customize_controls_print_scripts', array( $this, 'custom_js' ), 999 );

	}

	/**
	 * Include the necessary files
	 */
	function include_files() {

		include_once( dirname( __FILE__ ) . '/includes/controls/class-Kirki_Customize_Checkbox_Control.php' );
		include_once( dirname( __FILE__ ) . '/includes/controls/class-Kirki_Customize_Color_Control.php' );
		include_once( dirname( __FILE__ ) . '/includes/controls/class-Kirki_Customize_Image_Control.php' );
		include_once( dirname( __FILE__ ) . '/includes/controls/class-Kirki_Customize_Multicheck_Control.php' );
		include_once( dirname( __FILE__ ) . '/includes/controls/class-Kirki_Customize_Number_Control.php' );
		include_once( dirname( __FILE__ ) . '/includes/controls/class-Kirki_Customize_Radio_Control.php' );
		include_once( dirname( __FILE__ ) . '/includes/controls/class-Kirki_Customize_Sliderui_Control.php' );
		include_once( dirname( __FILE__ ) . '/includes/controls/class-Kirki_Customize_Text_Control.php' );
		include_once( dirname( __FILE__ ) . '/includes/controls/class-Kirki_Customize_Textarea_Control.php' );
		include_once( dirname( __FILE__ ) . '/includes/controls/class-Kirki_Customize_Upload_Control.php' );
		include_once( dirname( __FILE__ ) . '/includes/controls/class-Kirki_Select_Control.php' );
		include_once( dirname( __FILE__ ) . '/includes/controls/class-Kirki_Customize_Group_Title_Control.php' );

	}

	/**
	 * Enqueue the stylesheets and scripts required.
	 */
	function styles() {

		$options = apply_filters( 'kirki/config', array() );

		$kirki_url = isset( $options['url_path'] ) ? $options['url_path'] : plugin_dir_url( __FILE__ );

		wp_enqueue_style( 'kirki-customizer-css', $kirki_url . 'assets/css/customizer.css', NULL, '0.3' );
		wp_enqueue_style( 'kirki-customizer-ui',  $kirki_url . 'assets/css/jquery-ui-1.10.0.custom.css', NULL, '0.3' );

		wp_enqueue_script( 'kirki_customizer_js', $kirki_url . 'assets/js/customizer.js');
		wp_enqueue_script( 'tipsy', $kirki_url . 'assets/js/tooltipsy.min.js', array( 'jquery' ) );

	}

	/**
	 * Use the Roboto font on the customizer.
	 */
	function googlefonts() { ?>
		<link href='http://fonts.googleapis.com/css?family=Roboto:100,400|Roboto+Slab:700,400&subset=latin,cyrillic-ext,greek,vietnamese,latin-ext,cyrillic' rel='stylesheet' type='text/css'>
		<?php
	}

	/**
	 * Add custom CSS rules to the head, applying our custom styles
	 */
	function custom_css() {
		
		// Get the active admin theme
		global $_wp_admin_css_colors;

		// Get the user's admin colors
		$color = get_user_option( 'admin_color' );
		// If no theme is active set it to 'fresh'
		if ( empty( $color ) || ! isset( $_wp_admin_css_colors[$color] ) ) {
			$color = 'fresh';
		}

		$color = (array) $_wp_admin_css_colors[$color];

		$admin_theme = get_user_meta( get_current_user_id(), 'admin_color', true ); //Find out which theme the user has selected.

		$options = apply_filters( 'kirki/config', array() );

		$color_font    = isset( $options['color_font'] )    ? $options['color_font']    : null;
		$color_active  = isset( $options['color_active'] )  ? $options['color_active']  : null;
		$color_light   = isset( $options['color_light'] )   ? $options['color_light']   : null;
		$color_select  = isset( $options['color_select'] )  ? $options['color_select']  : $color['colors'][2];
		$color_accent  = isset( $options['color_accent'] )  ? $options['color_accent']  : $color['icon_colors']['focus'];
		$color_content = isset( $options['color_content'] ) ? $options['color_content'] : null;
		$color_back    = isset( $options['color_back'] )    ? $options['color_back']    : null;

		?>

		<style>
			.wp-core-ui .button.tooltip {
				background: <?php echo $color_select; ?>;
				color: #fff;
			}

			.image.ui-buttonset label.ui-button.ui-state-active {
				background: <?php echo $color_select; ?>;
			}

			.wp-full-overlay-sidebar {
				background: <?php echo $color_back; ?>;
			}

			#customize-info .accordion-section-title, #customize-info .accordion-section-title:hover {
				background: <?php echo $color_back; ?>;
			}

			#customize-theme-controls .accordion-section-title {
				background: <?php echo $color_back; ?>;
			}

			#customize-theme-controls .accordion-section-title {
				border-bottom: 1px solid rgba(0,0,0,.1) ?>;
			}

			#customize-theme-controls .control-section .accordion-section-title {
				color: <?php echo $color_font; ?>;
				background: <?php echo $color_back; ?>;
			}

			#customize-theme-controls .control-section .accordion-section-title:focus,
			#customize-theme-controls .control-section .accordion-section-title:hover,
			#customize-theme-controls .control-section.open .accordion-section-title,
			#customize-theme-controls .control-section:hover .accordion-section-title {
				color: <?php echo $color_content; ?>;
				background: <?php echo $color_active; ?>;
			}

			#customize-theme-controls .accordion-section-content{
				background: <?php echo $color_content; ?>;
			}

			.wp-core-ui .button-primary {
				background: <?php echo $color_active; ?>;
			}

			.wp-core-ui .button-primary.focus,
			.wp-core-ui .button-primary.hover,
			.wp-core-ui .button-primary:focus,
			.wp-core-ui .button-primary:hover {
				background: <?php echo $color_select; ?>;
			}

			.wp-core-ui .button-primary-disabled,
			.wp-core-ui .button-primary.disabled,
			.wp-core-ui .button-primary:disabled,
			.wp-core-ui .button-primary[disabled] {
				background: <?php echo $color_light; ?> !important;
				color: <?php echo $color_select; ?> !important;
			}

			<?php if ( isset( $options['logo_image'] ) ) : ?>
				div.kirki-customizer {
					background: url("<?php echo $options['logo_image']; ?>") no-repeat left center;
				}
			<?php endif; ?>
		</style>
		<?php

	}

	/**
	 * If we've specified an image to be used as logo, replace the default theme description with a div that will have our logo as background.
	 */
	function custom_js() {

		$options = apply_filters( 'kirki/config', array() ); ?>

		<?php if ( isset( $options['logo_image'] ) ) : ?>
			<script>
				jQuery(document).ready(function($) {
					"use strict";

					$( 'div#customize-info' ).replaceWith( '<div class="kirki-customizer"></div>' );
				});
			</script>
		<?php endif;

	}

}

$kirki = new Kirki();
endif;
