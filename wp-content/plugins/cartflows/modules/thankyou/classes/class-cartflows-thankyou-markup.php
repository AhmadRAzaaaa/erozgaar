<?php
/**
 * Front end and markup
 *
 * @package CartFlows
 */

/**
 * Checkout Markup
 *
 * @since 1.0.0
 */
class Cartflows_Thankyou_Markup {

	/**
	 * Member Variable
	 *
	 * @var object instance
	 */
	private static $instance;

	/**
	 *  Initiator
	 */
	public static function get_instance() {
		if ( ! isset( self::$instance ) ) {
			self::$instance = new self;
		}
		return self::$instance;
	}

	/**
	 *  Constructor
	 */
	public function __construct() {

		/* Downsell Shortcode */
		add_shortcode( 'cartflows_order_details', array( $this, 'cartflows_order_details_shortcode_markup' ) );

		add_action( 'wp_enqueue_scripts', array( $this, 'thank_you_scripts' ), 21 );

		add_action( 'woocommerce_is_order_received_page', array( $this, 'set_order_received_page' ) );

		/* Set is checkout flag */
		add_filter( 'woocommerce_is_checkout', array( $this, 'woo_checkout_flag' ), 9999 );
	}

	/**
	 * Order shortcode markup
	 *
	 * @param array $atts attributes.
	 * @since 1.0.0
	 */
	function cartflows_order_details_shortcode_markup( $atts ) {

		$output = '';

		if ( _is_wcf_thankyou_type() ) {

			/* Remove order item link */
			add_filter( 'woocommerce_order_item_permalink', '__return_false' );

			if ( ! function_exists( 'wc_print_notices' ) ) {
				return '<p class="woocommerce-notice">' . __( 'WooCommerce functions are not exists. If you are in iframe, please reload the iframe', 'cartflows' ) . '</p>';
			}

			$order = false;

			if ( ! isset( $_GET['wcf-order'] ) && wcf()->flow->is_flow_testmode() ) {

				$args = array(
					'limit'     => 1,
					'order'     => 'DESC',
					'post_type' => 'shop_order',
					'status'    => array( 'completed', 'processing' ),
				);

				$latest_order = wc_get_orders( $args );

				$order_id = ( ! empty( $latest_order ) ) ? current( $latest_order )->get_id() : 0;

				if ( $order_id > 0 ) {

					$order = wc_get_order( $order_id );

					if ( ! $order ) {
						$order = false;
					}
				}
			} else {
				if ( ! isset( $_GET['wcf-order'] ) ) {
					return '<p class="woocommerce-notice">Order not found. You cannot access this page directly.</p>';
				}

				// Get the order.
				$order_id  = apply_filters( 'woocommerce_thankyou_order_id', empty( $_GET['wcf-order'] ) ? 0 : intval( $_GET['wcf-order'] ) );
				$order_key = apply_filters( 'woocommerce_thankyou_order_key', empty( $_GET['wcf-key'] ) ? '' : wc_clean( wp_unslash( $_GET['wcf-key'] ) ) ); // WPCS: input var ok, CSRF ok.

				if ( $order_id > 0 ) {

					$order = wc_get_order( $order_id );

					if ( ! $order || $order->get_order_key() !== $order_key ) {
						$order = false;
					}
				}
			}

			// Empty awaiting payment session.
			unset( WC()->session->order_awaiting_payment );

			if ( null !== WC()->session ) {

				if ( ! isset( WC()->cart ) || '' === WC()->cart ) {
					WC()->cart = new WC_Cart();
				}

				if ( ! WC()->cart->is_empty() ) {
					// wc_empty_cart();
					// Empty current cart.
					WC()->cart->empty_cart( true );

					wc_clear_notices();
				}

				wc_print_notices();
			}

			ob_start();
			echo "<div class='wcf-thankyou-wrap'>";
				wc_get_template( 'checkout/thankyou.php', array( 'order' => $order ) );
			echo '</div>';
			$output = ob_get_clean();
		}

		return $output;
	}

	/**
	 * Load Thank You scripts.
	 *
	 * @return void
	 */
	function thank_you_scripts() {

		if ( _is_wcf_thankyou_type() ) {

			do_action( 'cartflows_thank_you_scripts' );

			$style = $this->generate_thank_you_style();

			wp_add_inline_style( 'wcf-frontend-global', $style );
		}
	}

	/**
	 * Set thank you as a order received page.
	 *
	 * @param boolean $is_order_page order page.
	 * @return boolean
	 */
	function set_order_received_page( $is_order_page ) {

		if ( _is_wcf_thankyou_type() ) {

			$is_order_page = true;
		}

		return $is_order_page;
	}

	/**
	 * Generate Thank You Styles.
	 *
	 * @return string
	 */
	function generate_thank_you_style() {

		global $post;

		if ( _is_wcf_thankyou_type() ) {
			$thank_you_id = $post->ID;
		} else {
			$thank_you_id = _get_wcf_thankyou_id( $post->post_content );
		}

		CartFlows_Font_Families::render_fonts( $thank_you_id );

		$text_color          = wcf()->options->get_thankyou_meta_value( $thank_you_id, 'wcf-tq-text-color' );
		$text_font_family    = wcf()->options->get_thankyou_meta_value( $thank_you_id, 'wcf-tq-font-family' );
		$heading_text_color  = wcf()->options->get_thankyou_meta_value( $thank_you_id, 'wcf-tq-heading-color' );
		$heading_font_family = wcf()->options->get_thankyou_meta_value( $thank_you_id, 'wcf-tq-heading-font-family' );
		$heading_font_weight = wcf()->options->get_thankyou_meta_value( $thank_you_id, 'wcf-tq-heading-font-wt' );
		$container_width     = wcf()->options->get_thankyou_meta_value( $thank_you_id, 'wcf-tq-container-width' );
		$section_bg_color    = wcf()->options->get_thankyou_meta_value( $thank_you_id, 'wcf-tq-section-bg-color' );

		$show_order_review = wcf()->options->get_thankyou_meta_value( $thank_you_id, 'wcf-show-overview-section' );

		$show_order_details = wcf()->options->get_thankyou_meta_value( $thank_you_id, 'wcf-show-details-section' );

		$show_billing_details = wcf()->options->get_thankyou_meta_value( $thank_you_id, 'wcf-show-billing-section' );

		$show_shipping_details = wcf()->options->get_thankyou_meta_value( $thank_you_id, 'wcf-show-shipping-section' );

		$output = "
		.wcf-thankyou-wrap{
			color: {$text_color};
			font-family: {$text_font_family};
			max-width:{$container_width}px;
		}

		.woocommerce-order h2.woocommerce-column__title, 
		.woocommerce-order h2.woocommerce-order-details__title, 
		.woocommerce-order .woocommerce-thankyou-order-received,
		.woocommerce-order-details h2,
		.woocommerce-order h2.wc-bacs-bank-details-heading {
			color: {$heading_text_color};
			font-family: {$heading_font_family};
			font-weight: {$heading_font_weight};
		}

		.woocommerce-order ul.order_details,
		.woocommerce-order .woocommerce-order-details,
		.woocommerce-order .woocommerce-customer-details,
		.woocommerce-order .woocommerce-bacs-bank-details{
			background-color: {$section_bg_color}
		}
		img.emoji, img.wp-smiley {}
		";

		if ( 'no' == $show_order_review ) {
			$output .= '
			.woocommerce-order ul.order_details{
				display: none;
			}
			';
		}

		if ( 'no' == $show_order_details ) {
			$output .= '
			.woocommerce-order .woocommerce-order-details{
				display: none;
			}
			';
		}

		if ( 'no' == $show_billing_details ) {
			$output .= '
			.woocommerce-order .woocommerce-customer-details .woocommerce-column--billing-address{
				display: none;
			}
			';
		}

		if ( 'no' == $show_shipping_details ) {
			$output .= '
			.woocommerce-order .woocommerce-customer-details .woocommerce-column--shipping-address{
				display: none;
			}
			';
		}

		if ( 'no' == $show_billing_details && 'no' == $show_shipping_details ) {
			$output .= '
			.woocommerce-order .woocommerce-customer-details{
				display: none;
			}
			';
		}

		return $output;
	}

	/**
	 * Set as a checkout page if it is thank you page.
	 * Thank you page need to be set as a checkout page.
	 * Becauye ayment gateways will not load if it is not checkout.
	 *
	 * @param bool $is_checkout is checkout.
	 *
	 * @return bool
	 */
	function woo_checkout_flag( $is_checkout ) {

		if ( ! is_admin() ) {

			if ( _is_wcf_thankyou_type() ) {

				$is_checkout = true;
			}
		}

		return $is_checkout;
	}
}

/**
 *  Kicking this off by calling 'get_instance()' method
 */
Cartflows_Thankyou_Markup::get_instance();
