<?php
/**
 * Theme functions and definitions
 *
 * @package HelloElementor
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

define( 'HELLO_ELEMENTOR_VERSION', '2.9.0' );

if ( ! isset( $content_width ) ) {
	$content_width = 800; // Pixels.
}

if ( ! function_exists( 'hello_elementor_setup' ) ) {
	/**
	 * Set up theme support.
	 *
	 * @return void
	 */
	function hello_elementor_setup() {
		if ( is_admin() ) {
			hello_maybe_update_theme_version_in_db();
		}

		if ( apply_filters( 'hello_elementor_register_menus', true ) ) {
			register_nav_menus( [ 'menu-1' => esc_html__( 'Header', 'hello-elementor' ) ] );
			register_nav_menus( [ 'menu-2' => esc_html__( 'Footer', 'hello-elementor' ) ] );
		}

		if ( apply_filters( 'hello_elementor_post_type_support', true ) ) {
			add_post_type_support( 'page', 'excerpt' );
		}

		if ( apply_filters( 'hello_elementor_add_theme_support', true ) ) {
			add_theme_support( 'post-thumbnails' );
			add_theme_support( 'automatic-feed-links' );
			add_theme_support( 'title-tag' );
			add_theme_support(
				'html5',
				[
					'search-form',
					'comment-form',
					'comment-list',
					'gallery',
					'caption',
					'script',
					'style',
				]
			);
			add_theme_support(
				'custom-logo',
				[
					'height'      => 100,
					'width'       => 350,
					'flex-height' => true,
					'flex-width'  => true,
				]
			);

			/*
			 * Editor Style.
			 */
			add_editor_style( 'classic-editor.css' );

			/*
			 * Gutenberg wide images.
			 */
			add_theme_support( 'align-wide' );

			/*
			 * WooCommerce.
			 */
			if ( apply_filters( 'hello_elementor_add_woocommerce_support', true ) ) {
				// WooCommerce in general.
				add_theme_support( 'woocommerce' );
				// Enabling WooCommerce product gallery features (are off by default since WC 3.0.0).
				// zoom.
				add_theme_support( 'wc-product-gallery-zoom' );
				// lightbox.
				add_theme_support( 'wc-product-gallery-lightbox' );
				// swipe.
				add_theme_support( 'wc-product-gallery-slider' );
			}
		}
	}
}
add_action( 'after_setup_theme', 'hello_elementor_setup' );

function hello_maybe_update_theme_version_in_db() {
	$theme_version_option_name = 'hello_theme_version';
	// The theme version saved in the database.
	$hello_theme_db_version = get_option( $theme_version_option_name );

	// If the 'hello_theme_version' option does not exist in the DB, or the version needs to be updated, do the update.
	if ( ! $hello_theme_db_version || version_compare( $hello_theme_db_version, HELLO_ELEMENTOR_VERSION, '<' ) ) {
		update_option( $theme_version_option_name, HELLO_ELEMENTOR_VERSION );
	}
}

if ( ! function_exists( 'hello_elementor_scripts_styles' ) ) {
	/**
	 * Theme Scripts & Styles.
	 *
	 * @return void
	 */
	function hello_elementor_scripts_styles() {
		$min_suffix = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '' : '.min';

		if ( apply_filters( 'hello_elementor_enqueue_style', true ) ) {
			wp_enqueue_style(
				'hello-elementor',
				get_template_directory_uri() . '/style' . $min_suffix . '.css',
				[],
				HELLO_ELEMENTOR_VERSION
			);
		}

		if ( apply_filters( 'hello_elementor_enqueue_theme_style', true ) ) {
			wp_enqueue_style(
				'hello-elementor-theme-style',
				get_template_directory_uri() . '/theme' . $min_suffix . '.css',
				[],
				HELLO_ELEMENTOR_VERSION
			);
		}
	}
}
add_action( 'wp_enqueue_scripts', 'hello_elementor_scripts_styles' );

if ( ! function_exists( 'hello_elementor_register_elementor_locations' ) ) {
	/**
	 * Register Elementor Locations.
	 *
	 * @param ElementorPro\Modules\ThemeBuilder\Classes\Locations_Manager $elementor_theme_manager theme manager.
	 *
	 * @return void
	 */
	function hello_elementor_register_elementor_locations( $elementor_theme_manager ) {
		if ( apply_filters( 'hello_elementor_register_elementor_locations', true ) ) {
			$elementor_theme_manager->register_all_core_location();
		}
	}
}
add_action( 'elementor/theme/register_locations', 'hello_elementor_register_elementor_locations' );

if ( ! function_exists( 'hello_elementor_content_width' ) ) {
	/**
	 * Set default content width.
	 *
	 * @return void
	 */
	function hello_elementor_content_width() {
		$GLOBALS['content_width'] = apply_filters( 'hello_elementor_content_width', 800 );
	}
}
add_action( 'after_setup_theme', 'hello_elementor_content_width', 0 );

if ( ! function_exists( 'hello_elementor_add_description_meta_tag' ) ) {
	/**
	 * Add description meta tag with excerpt text.
	 *
	 * @return void
	 */
	function hello_elementor_add_description_meta_tag() {
		if ( ! apply_filters( 'hello_elementor_description_meta_tag', true ) ) {
			return;
		}

		if ( ! is_singular() ) {
			return;
		}

		$post = get_queried_object();
		if ( empty( $post->post_excerpt ) ) {
			return;
		}

		echo '<meta name="description" content="' . esc_attr( wp_strip_all_tags( $post->post_excerpt ) ) . '">' . "\n";
	}
}
add_action( 'wp_head', 'hello_elementor_add_description_meta_tag' );

// Admin notice
if ( is_admin() ) {
	require get_template_directory() . '/includes/admin-functions.php';
}

// Settings page
require get_template_directory() . '/includes/settings-functions.php';

// Allow active/inactive via the Experiments
require get_template_directory() . '/includes/elementor-functions.php';

if ( ! function_exists( 'hello_elementor_check_hide_title' ) ) {
	/**
	 * Check whether to display the page title.
	 *
	 * @param bool $val default value.
	 *
	 * @return bool
	 */
	function hello_elementor_check_hide_title( $val ) {
		if ( defined( 'ELEMENTOR_VERSION' ) ) {
			$current_doc = Elementor\Plugin::instance()->documents->get( get_the_ID() );
			if ( $current_doc && 'yes' === $current_doc->get_settings( 'hide_title' ) ) {
				$val = false;
			}
		}
		return $val;
	}
}
add_filter( 'hello_elementor_page_title', 'hello_elementor_check_hide_title' );

/**
 * BC:
 * In v2.7.0 the theme removed the `hello_elementor_body_open()` from `header.php` replacing it with `wp_body_open()`.
 * The following code prevents fatal errors in child themes that still use this function.
 */
if ( ! function_exists( 'hello_elementor_body_open' ) ) {
	function hello_elementor_body_open() {
		wp_body_open();
	}
}

// Add your previous hooks and functions here

// Add the GST and updated total price to the WooCommerce session
add_filter('woocommerce_add_cart_item_data', 'add_custom_data_to_cart', 10, 3);
add_filter('woocommerce_add_cart_item_data', 'force_individual_cart_items', 10, 2);
function force_individual_cart_items($cart_item_data, $product_id) {
    $unique_cart_item_key = md5(microtime().rand());
    $cart_item_data['unique_key'] = $unique_cart_item_key;
    return $cart_item_data;
}

function add_custom_data_to_cart($cart_item_data, $product_id, $variation_id) {
    if(isset($_GET['hotel_fare'])) {
        $cart_item_data['hotel_fare'] = $_GET['hotel_fare'];
        $cart_item_data['hotel_name'] = $_GET['hotel_name'];
        $cart_item_data['room_type'] = $_GET['room_type'];
        $cart_item_data['price_per_room'] = $_GET['price_per_room'];
        $cart_item_data['tax_gst'] = $_GET['tax_gst'];
        $cart_item_data['number_of_rooms'] = $_GET['number_of_rooms'];
        $cart_item_data['checkin_date'] = $_GET['checkin_date'];
        $cart_item_data['checkout_date'] = $_GET['checkout_date'];
		if(isset($_GET['unique_id'])) {
            $cart_item_data['unique_id'] = $_GET['unique_id'];
        }
    }
    return $cart_item_data;
}

// Display the custom data in the cart
add_filter('woocommerce_get_item_data', 'display_custom_item_data', 10, 2);
function display_custom_item_data($item_data, $cart_item) {
    if(array_key_exists('hotel_fare', $cart_item)) {
        $item_data[] = array('name' => 'Hotel Name', 'value' => $cart_item['hotel_name']);
        $item_data[] = array('name' => 'Room Type', 'value' => $cart_item['room_type']);
        $item_data[] = array('name' => 'Price Per Room', 'value' => $cart_item['price_per_room']);
        $item_data[] = array('name' => 'Tax GST', 'value' => $cart_item['tax_gst']);
        $item_data[] = array('name' => 'Number of Rooms', 'value' => $cart_item['number_of_rooms']);
        $item_data[] = array('name' => 'Check-in Date', 'value' => $cart_item['checkin_date']);
        $item_data[] = array('name' => 'Check-out Date', 'value' => $cart_item['checkout_date']);
        $item_data[] = array('name' => 'Total Price', 'value' => $cart_item['hotel_fare']);
		if(array_key_exists('unique_id', $cart_item)) {
            $item_data[] = array('name' => 'Unique ID', 'value' => $cart_item['unique_id']);
        }
    }
    return $item_data;
}

// Update the total price when the cart is loaded from the session
add_action('woocommerce_before_calculate_totals', 'update_custom_price', 20, 1);
function update_custom_price($cart_obj) {
    if (is_admin() && !defined('DOING_AJAX')) return;

    foreach ($cart_obj->get_cart() as $key => $value) {
        if (isset($value['hotel_fare'])) {
            $price = $value['hotel_fare'];
            $value['data']->set_price((float) $price);
        }
    }
}

// Add the custom data to the order
add_action('woocommerce_checkout_create_order_line_item', 'custom_checkout_create_order_line_item', 20, 4);
function custom_checkout_create_order_line_item($item, $cart_item_key, $values, $order) {
    if(array_key_exists('hotel_fare', $values)) {
        $item->add_meta_data('Hotel Name', $values['hotel_name']);
        $item->add_meta_data('Room Type', $values['room_type']);
        $item->add_meta_data('Price Per Room', $values['price_per_room']);
        $item->add_meta_data('Tax GST', $values['tax_gst']);
        $item->add_meta_data('Number of Rooms', $values['number_of_rooms']);
        $item->add_meta_data('Check-in Date', $values['checkin_date']);
        $item->add_meta_data('Check-out Date', $values['checkout_date']);
        $item->add_meta_data('Total Price', $values['hotel_fare']);
    }
}

// Add the custom data to WooCommerce emails
add_filter('woocommerce_email_order_meta_fields', 'custom_email_order_meta_fields', 10, 3);
function custom_email_order_meta_fields($fields, $sent_to_admin, $order) {
    $fields['hotel_name'] = array(
        'label' => __('Hotel Name'),
        'value' => get_post_meta($order->get_id(), 'Hotel Name', true),
    );
    $fields['room_type'] = array(
        'label' => __('Room Type'),
        'value' => get_post_meta($order->get_id(), 'Room Type', true),
    );
    $fields['price_per_room'] = array(
        'label' => __('Price Per Room'),
        'value' => get_post_meta($order->get_id(), 'Price Per Room', true),
    );
    $fields['tax_gst'] = array(
        'label' => __('Tax GST'),
        'value' => get_post_meta($order->get_id(), 'Tax GST', true),
    );
    $fields['number_of_rooms'] = array(
        'label' => __('Number of Rooms'),
        'value' => get_post_meta($order->get_id(), 'Number of Rooms', true),
    );
    $fields['checkin_date'] = array(
        'label' => __('Check-in Date'),
        'value' => get_post_meta($order->get_id(), 'Check-in Date', true),
    );
    $fields['checkout_date'] = array(
        'label' => __('Check-out Date'),
        'value' => get_post_meta($order->get_id(), 'Check-out Date', true),
    );
    $fields['total_price'] = array(
        'label' => __('Total Price'),
        'value' => get_post_meta($order->get_id(), 'Total Price', true),
    );
    return $fields;
}
//add times to cart items

function add_times_to_cart_item_data( $cart_item_data, $product_id, $variation_id ) {
    if( isset( $_GET['checkin_time'] ) ) {
        $cart_item_data['checkin_time'] = sanitize_text_field( $_GET['checkin_time'] );
    }
    if( isset( $_GET['checkout_time'] ) ) {
        $cart_item_data['checkout_time'] = sanitize_text_field( $_GET['checkout_time'] );
    }
    return $cart_item_data;
}
add_filter( 'woocommerce_add_cart_item_data', 'add_times_to_cart_item_data', 10, 3 );

// / Display Times in Cart and Checkout
// 
function display_times_in_cart( $item_data, $cart_item ) {
    if ( isset( $cart_item['checkin_time'] ) ) {
        $item_data[] = array(
            'name' => 'Check-in Time',
            'value' => $cart_item['checkin_time']
        );
    }
    if ( isset( $cart_item['checkout_time'] ) ) {
        $item_data[] = array(
            'name' => 'Check-out Time',
            'value' => $cart_item['checkout_time']
        );
    }
    return $item_data;
}
add_filter( 'woocommerce_get_item_data', 'display_times_in_cart', 10, 2 );

//Pass Times to Order Details
//
function add_times_to_order_items( $item, $cart_item_key, $values, $order ) {
    if ( isset( $values['checkin_time'] ) ) {
        $item->add_meta_data( 'Check-in Time', $values['checkin_time'] );
    }
    if ( isset( $values['checkout_time'] ) ) {
        $item->add_meta_data( 'Check-out Time', $values['checkout_time'] );
    }
}
add_action( 'woocommerce_checkout_create_order_line_item', 'add_times_to_order_items', 10, 4 );

// Display Times in WooCommerce Admin and Emails
// 
function display_times_in_emails( $item, $order, $plain_text ) {
    echo '<p><strong>Check-in Time:</strong> ' . $item->get_meta( 'Check-in Time' ) . '</p>';
    echo '<p><strong>Check-out Time:</strong> ' . $item->get_meta( 'Check-out Time' ) . '</p>';
}
add_action( 'woocommerce_email_order_meta', 'display_times_in_emails', 10, 3 );
