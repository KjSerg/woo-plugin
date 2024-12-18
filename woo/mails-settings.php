<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function register_product_notification_email( $email_classes ) {
	include_once __DIR__ . '/includes/class-product-notification-email.php';
	$email_classes['WC_Email_Product_Notification'] = new WC_Email_Product_Notification();

	return $email_classes;
}

add_action('woocommerce_loaded', function () {
	add_filter('woocommerce_email_classes', 'register_product_notification_email');
});
function trigger_product_notification_email( $product_id, $is_edit = false ) {
	if ( ! $product_id ) {
		return;
	}
	$emails = WC()->mailer()->get_emails();
	if ( ! empty( $emails['WC_Email_Product_Notification'] ) ) {
		$emails['WC_Email_Product_Notification']->trigger( $product_id, $is_edit );
	}
}

add_action( 'woocommerce_new_product', function ( $product_id ) {
	trigger_product_notification_email( $product_id, false );
}, 10, 1 );
add_action( 'woocommerce_update_product', function ( $product_id ) {
	trigger_product_notification_email( $product_id, true );
}, 10, 1 );