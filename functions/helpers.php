<?php
function wp_my_product_webspark_check_woocommerce() {
	if ( ! class_exists( 'WooCommerce' ) ) {
		add_action( 'admin_notices', function () {
			echo '<div class="notice notice-error"><p><strong>wp my product webspark:</strong> Для роботи цього плагіна потрібно активувати WooCommerce.</p></div>';
		} );

		return false;
	}

	return true;
}

function wp_my_product_webspark_init() {
	if ( ! wp_my_product_webspark_check_woocommerce() ) {
		return;
	}
	add_action( 'admin_notices', function () {
		echo '<div class="notice"><p><strong>wp my product webspark:</strong> плагін працює</p></div>';
	} );
}

function is_shop_manager( $user_id = false ) {
	$user_id = $user_id ?: get_current_user_id();
	$user_meta = get_userdata($user_id);
	$user_roles = $user_meta->roles;
	return in_array('shop_manager', $user_roles) || in_array('administrator', $user_roles);
}

function get_author_id($post_id) {
	return get_post_field( 'post_author', $post_id );
}