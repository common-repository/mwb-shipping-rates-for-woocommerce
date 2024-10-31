<?php
/**
 * Fired during plugin activation
 *
 * @link       https://makewebbetter.com/
 * @since      1.0.0
 *
 * @package    Mwb_Shipping_Rates_For_Woocommerce
 * @subpackage Mwb_Shipping_Rates_For_Woocommerce/includes
 */

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.0.0
 * @package    Mwb_Shipping_Rates_For_Woocommerce
 * @subpackage Mwb_Shipping_Rates_For_Woocommerce/includes
 */
class Mwb_Shipping_Rates_For_Woocommerce_Activator {

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */
	public static function mwb_shipping_rates_for_woocommerce_activate() {
		global $wpdb;
		if ( is_multisite() || $network_wide ) {
				$blogids = $wpdb->get_col( "SELECT blog_id FROM $wpdb->blogs" );

			foreach ( $blogids as $blog_id ) {
				switch_to_blog( $blog_id );
				update_option( 'msrfw_radio_switch_enable', 'on' );
				restore_current_blog();
			}
		} else {
			update_option( 'msrfw_radio_switch_enable', 'on' );

		}
	}

}
