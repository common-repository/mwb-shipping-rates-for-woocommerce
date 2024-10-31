<?php
/**
 * The common functionality of the plugin.
 *
 * @link       https://makewebbetter.com/
 * @since      1.0.0
 *
 * @package    Mwb_Shipping_Rates_For_Woocommerce
 * @subpackage Mwb_Shipping_Rates_For_Woocommerce/common
 */

/**
 * The common functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the common stylesheet and JavaScript.
 * namespace mwb_shipping_rates_for_woocommerce_common.
 *
 * @package    Mwb_Shipping_Rates_For_Woocommerce
 * @subpackage Mwb_Shipping_Rates_For_Woocommerce/common
 */
class Mwb_Shipping_Rates_For_Woocommerce_Common {
	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string $plugin_name       The name of the plugin.
	 * @param      string $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version     = $version;
	}

	/**
	 * Register the stylesheets for the common side of the site.
	 *
	 * @since    1.0.0
	 */
	public function msrfw_common_enqueue_styles() {
		// Common CSS.
	}

	/**
	 * Register the JavaScript for the common side of the site.
	 *
	 * @since    1.0.0
	 */
	public function msrfw_common_enqueue_scripts() {
		$mwb_checking_cart_page = is_cart();
		wp_register_script( $this->plugin_name . 'common', MWB_SHIPPING_RATES_FOR_WOOCOMMERCE_DIR_URL . 'common/js/mwb-shipping-rates-for-woocommerce-common.js', array( 'jquery' ), $this->version, false );
		wp_localize_script(
			$this->plugin_name . 'common',
			'msrfw_common_param',
			array(
				'ajaxurl' => admin_url( 'admin-ajax.php' ),
				'mwb_cart_page' => $mwb_checking_cart_page,
			)
		);
		wp_enqueue_script( $this->plugin_name . 'common' );
	}

	/**
	 * Creating shipping method for WooCommerce.
	 *
	 * @param array $methods an array of shipping methods.
	 *
	 * @since 1.0.0
	 */
	public function mwb_shipping_rate_for_woocommerce_create_shipping_method( $methods ) {

		if ( ! class_exists( 'Mwb_Shipping_Rate_Method' ) ) {
			/**
			 * Custom shipping class for Shipping.
			 */

			require_once plugin_dir_path( __FILE__ ) . '/classes/class-mwb-shipping-rate-method.php'; // Including class file.
			new Mwb_Shipping_Rate_Method();

		}
	}
	/**
	 * Adding membership shipping method.
	 *
	 * @param array $methods an array of shipping methods.
	 * @return array
	 *
	 * @since 1.0.0
	 */
	public function mwb_shipping_rate_for_woocommerce_add_shipping_method( $methods ) {
		$methods['mwb_shipping_rate'] = 'Mwb_Shipping_rate_method';

		return $methods;

	}

	/**
	 * Adding coupon shipping method.
	 *
	 * @since 1.0.0
	 */
	public function msrfw_coupon_add_fun() {
		$applied_coupons = WC()->cart->get_applied_coupons();

		if ( empty( $applied_coupons ) ) {
			update_option( 'shipping_coupon', 'no' );
		}

		foreach ( $applied_coupons as $coupon_code ) {

			$coupon = new WC_Coupon( $coupon_code );

			if ( $coupon->get_free_shipping() ) {
				update_option( 'shipping_coupon', 'yes' );
			}
		}
	}

	 /**
	  * Removing coupon shipping method.
	  *
	  * @since 1.0.0
	  */
	public function msrfw_coupon_remove_fun() {
		$applied_coupons = WC()->cart->get_applied_coupons();

		if ( empty( $applied_coupons ) ) {
			update_option( 'shipping_coupon', 'no' );
		}

		foreach ( $applied_coupons as $coupon_code ) {

			$coupon = new WC_Coupon( $coupon_code );

			if ( $coupon->get_free_shipping() ) {
				update_option( 'shipping_coupon', 'yes' );
			} else {
				update_option( 'shipping_coupon', 'no' );
			}
		}
	}

	/**
	 * Checking Free shipping Coupon.
	 *
	 * @since 1.0.0
	 */
	public function mwb_free_shipping_coupon_checking() {
		if ( ! is_user_logged_in() ) {
			update_option( 'shipping_coupon', 'no' );
		}
	}

	/**
	 * Expected delivery  shipping method.
	 *
	 * @since 1.0.0
	 */
	public function expected_delivery_date_message() {
		global $date;
		$days_checker = get_option( 'expected_days' );
		if ( ! empty( $days_checker ) ) {
			$expec_date = gmdate( 'l jS \of F ', strtotime( $date . ' + ' . $days_checker . 'days' ) );
			esc_html_e( 'Expected to be delivered by ', 'mwb-shipping-rates-for-woocommerce' );
			echo esc_attr( $expec_date );
		}
	}

	/**
	 * Weighting display delivery  shipping method.
	 *
	 * @param array $item_data used to store data of item in cart.
	 * @param array $cart_item used to store info of item in cart.
	 * @since 1.0.0
	 */
	public function displaying_cart_items_weight( $item_data, $cart_item ) {
		$item_weight = $cart_item['data']->get_weight();
		$item_data[] = array(
			'key'       => __( 'Weight', 'mwb-shipping-rates-for-woocommerce' ),
			'value'     => $item_weight,
			'display'   => $item_weight . ' ' . get_option( 'woocommerce_weight_unit' ),
		);

		return $item_data;
	}

	/**
	 * Selecting categories of the product  shipping method.
	 *
	 * @since 1.0.0
	 */
	public function shipping_rates_categories() {
		$shipping_prod_cat = get_option( 'product_categories' );
		$shipping_ar       = explode( ',', $shipping_prod_cat[0] );
			$cat_count = 0;
		  $cat_in_cart = false;

		foreach ( WC()->cart->get_cart() as $cart_item_key => $cart_item ) {

			foreach ( $shipping_ar as $x => $val ) {
				if ( has_term( $val, 'product_cat', $cart_item['product_id'] ) ) {
					$cat_in_cart = true;
					$cat_count  += $cart_item['quantity'];
				}
			}
		}
		if ( $cat_in_cart ) {
			update_option( 'shipping_cart', 'yes' );
			update_option( 'cat_count', $cat_count );
		} else {
			update_option( 'shipping_cart', 'no' );
		}
		if ( 'No Categories Selected' === $shipping_prod_cat[0] ) {
			update_option( 'shipping_cart', 'no' );
		}
	}
}
