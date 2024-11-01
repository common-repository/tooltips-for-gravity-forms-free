<?php
/**
 *
 * Plugin Name:       Easy Gravity Tooltip
 * Plugin URI:        https://neatma.com/gravity-forms-tooltips/
 * Description:       Easily attach tooltips to any field type in gravity forms.
 * Version:           1.1.0
 * Author:            Amin Nazemi
 * Author URI:        https://neatma.com
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       easy-gravity-tooltip
 * Domain Path:       /languages
 *
 */
 
define( 'GF_EASY_TOOlTIP_PRO_VERSION', '1.1.0' );

define( 'GF_EASY_TOOlTIP_PRO_URL', plugin_dir_url( __FILE__ ) );



add_action( 'gform_loaded', array( 'GF_ESAY_TOOLTIP_PRO_INIT', 'load' ), 5 );
add_action( 'plugins_loaded', 'gfeasytooltippro_load_lang_files');


	/**
	 * init plugin.
	 */
class GF_ESAY_TOOLTIP_PRO_INIT {


    public static function load() {

        if ( ! method_exists( 'GFForms', 'include_addon_framework' ) ) {
            return;
        }

        require_once( 'admin/class-gfeasytooltip.php' );

        GFAddOn::register( 'GFEasytooltippro' );
    }

}


	/**
	 * load language files.
	 */
function gfeasytooltippro_load_lang_files() {
    $plugin_rel_path = basename( dirname( __FILE__ ) ) . '/languages'; 
    load_plugin_textdomain( 'easy-gravity-tooltip', false, $plugin_rel_path );
}




function easy_gf_tp() {
	if ( ! class_exists( 'GFEasytooltippro' ) ) {
		return false;
	}

	return GFEasytooltippro::get_instance();
}



add_action ( 'admin_enqueue_scripts', function () {
    if (is_admin ())
	  wp_enqueue_script( 'tooltip-script',GF_EASY_TOOlTIP_PRO_URL . 'assests/js/media_script.js', array( 'jquery' ) );
        wp_enqueue_media ();
} );