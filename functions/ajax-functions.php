<?php
add_action( 'wp_ajax_nopriv_create_woo_product', 'create_woo_product' );
add_action( 'wp_ajax_create_woo_product', 'create_woo_product' );
function create_woo_product() {
	$res               = array( 'msg' => '' );
	$store             = array();
	$URL               = get_bloginfo( 'url' );
	$title             = $_POST['title'] ?? '';
	$description       = $_POST['description'] ?? '';
	$price             = $_POST['price'] ?? 0;
	$qnt               = $_POST['qnt'] ?? 0;
	$product_id        = $_POST['product_id'] ?? 0;
	$old_gallery       = $_POST['old_gallery'] ?? '';
	$user_id           = get_current_user_id();
	$shop_manager_test = $user_id && is_shop_manager( $user_id );
	$nonce_test        = isset( $_POST['true_nonce'] ) && wp_verify_nonce( $_POST['true_nonce'], 'create_woo_product' );
	$post_data_test    = $title && $description && $price && $qnt;
	if ( ! $post_data_test ) {
		$res['msg']  = 'Всі поля обовязкові!';
		$res['type'] = 'error';
		echo json_encode( $res );
		die();
	}
	if ( ! $nonce_test ) {
		$res['msg']  = 'Виникла помилка перезагрузіть сторінку та спробуйте ще раз!';
		$res['type'] = 'error';
		echo json_encode( $res );
		die();
	}
	if ( ! $shop_manager_test ) {
		$res['msg']  = __( 'Виникла помилка: У вас немає доступу', CFE__PLUGIN_NAME );
		$res['type'] = 'error';
		echo json_encode( $res );
		die();
	}
	$res['$qnt']   = $qnt;
	$res['$price'] = $price;
	$product_id    = (int) $product_id;
	$product       = $product_id ? new WC_Product( $product_id ) : new WC_Product_Simple();
	$product->set_name( $title );
	$product->set_description( $description );
	$product->set_stock_status( 'instock' );
	$product->set_manage_stock( true );
	$product->set_stock_quantity( $qnt );
	$product->set_status( 'pending' );
	$product->set_price( $price );
	$product->set_regular_price( $price );
	$files = $_FILES["upfile"];
	$arr   = array();
	foreach ( $files['name'] as $key => $value ) {
		if ( $files['name'][ $key ] ) {
			$file   = array(
				'name'     => $files['name'][ $key ],
				'type'     => $files['type'][ $key ],
				'tmp_name' => $files['tmp_name'][ $key ],
				'error'    => $files['error'][ $key ],
				'size'     => $files['size'][ $key ]
			);
			$_FILES = array( "file" => $file );
			foreach ( $_FILES as $file => $array ) {
				$arr[] = wmpw_handle_attachment( $file );
			}
			if ( $arr ) {
				$product->set_image_id( $arr[0] );
				$product->set_gallery_image_ids($arr );
			}
		}
	}
	$product->save();
	$res['id']   = $product->get_id();
	$res['name'] = $product->get_name();
	$res['$arr'] = $arr;
	$res['user'] = get_author_id( $product->get_id() );
	$res['type'] = 'success';
	$res['msg']  = __( 'Товар відправлено на погодження адміністратору магазина.', CFE__PLUGIN_NAME );
	if ( $arr ) {
		if ( $old_gallery ) {
			$old_gallery = explode( ',', $old_gallery );
			if ( $old_gallery ) {
				$res['$old_gallery'] = $old_gallery;
				foreach ( $old_gallery as $old_image ) {
					wp_delete_attachment( $old_image, true );
				}
			}
		}
	}
	echo json_encode( $res );
	die();
}

function wmpw_handle_attachment( $file_handler, $post_id = 0, $set_thu = false ) {

	if ( $_FILES[ $file_handler ]['error'] !== UPLOAD_ERR_OK ) {
		__return_false();
	}

	require_once( ABSPATH . "wp-admin" . '/includes/image.php' );
	require_once( ABSPATH . "wp-admin" . '/includes/file.php' );
	require_once( ABSPATH . "wp-admin" . '/includes/media.php' );

	return media_handle_upload( $file_handler, $post_id );
}