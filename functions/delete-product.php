<?php
add_action( 'init', function () {
	if ( isset( $_GET['action'] ) && $_GET['action'] === 'delete_product' && isset( $_GET['product_id'] ) ) {
		$product_id = intval( $_GET['product_id'] );
		$user_id    = get_current_user_id();
		if ( ! isset( $_GET['_wpnonce'] ) || ! wp_verify_nonce( $_GET['_wpnonce'], 'delete_product_' . $product_id ) ) {
			wp_die( __( 'Помилка безпеки. Спробуйте ще раз.', 'woocommerce' ) );
		}
		if ( get_post_field( 'post_author', $product_id ) != $user_id ) {
			wp_die( __( 'Ви не маєте права видаляти цей товар.', 'woocommerce' ) );
		}
		wp_trash_post( $product_id );
		wp_redirect( wc_get_account_endpoint_url( 'my-products' ) );
		exit;
	}
} );
