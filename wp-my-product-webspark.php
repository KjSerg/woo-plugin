<?php
/*
Plugin Name: wp my product webspark
Description: Розширення функцій WooCommerce
Version: 1.0
Author: Каланджій Сергій
Author URI: https://github.com/KjSerg/woo-plugin/
Plugin URI: https://github.com/KjSerg/woo-plugin/
*/

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'CFE__PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
define( 'CFE__SITE_URL', site_url() );
define( 'AJAX_URL', site_url() . '/wp-admin/admin-ajax.php' );
define( 'CFE__ASSETS_URL', CFE__SITE_URL . '/wp-content/plugins/wp-my-product-webspark/assets' );
define( 'CFE__PLUGIN_NAME', 'wp-my-product-webspark' );

require_once( CFE__PLUGIN_DIR . 'functions/include-assets.php' );
require_once( CFE__PLUGIN_DIR . 'functions/helpers.php' );
require_once( CFE__PLUGIN_DIR . 'functions/ajax-functions.php' );
require_once( CFE__PLUGIN_DIR . 'functions/delete-product.php' );
require_once( CFE__PLUGIN_DIR . 'woo/add-custom-pages.php' );
require_once( CFE__PLUGIN_DIR . 'woo/mails-settings.php' );
require_once( CFE__PLUGIN_DIR . 'views/add-product.php' );
require_once( CFE__PLUGIN_DIR . 'views/my-products.php' );


add_action( 'init', 'my_account_add_custom_endpoints' );
add_action( 'plugins_loaded', 'wp_my_product_webspark_init' );
add_action( 'woocommerce_account_add-product_endpoint', 'my_account_add_product_content' );
add_action( 'woocommerce_account_my-products_endpoint', 'my_account_my_products_content' );
add_filter( 'woocommerce_account_menu_items', 'my_account_custom_menu_items' );
register_activation_hook( __FILE__, 'my_account_flush_rewrite_rules' );
register_deactivation_hook( __FILE__, 'flush_rewrite_rules' );

