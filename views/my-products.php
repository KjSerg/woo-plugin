<?php
function my_account_my_products_content() {
	$user_id = get_current_user_id();
	$paged   = get_query_var( 'paged' ) ? get_query_var( 'paged' ) : 1;
	$args    = [
		'post_type'      => 'product',
		'post_status'    => [ 'publish', 'draft', 'pending' ],
		'posts_per_page' => 10,
		'paged'          => $paged,
		'author'         => $user_id,
	];
	$query   = new WP_Query( $args );
	if ( $query->have_posts() ) {
		echo '<div class="my-account-products-table-wrapper">';
		echo '<table class="shop_table my-account-products-table">';
		echo '<thead>';
		echo '<tr>';
		echo '<th>' . __( 'Назва товару', 'woocommerce' ) . '</th>';
		echo '<th>' . __( 'Кількість', 'woocommerce' ) . '</th>';
		echo '<th>' . __( 'Ціна', 'woocommerce' ) . '</th>';
		echo '<th>' . __( 'Статус', 'woocommerce' ) . '</th>';
		echo '<th>' . __( 'Дії', CFE__PLUGIN_NAME ) . '</th>';
		echo '</tr>';
		echo '</thead>';
		echo '<tbody>';

		while ( $query->have_posts() ) {
			$query->the_post();
			$product        = wc_get_product( get_the_ID() );
			$name           = $product->get_name();
			$stock_quantity = $product->get_stock_quantity() ?: __( 'Невідомо', 'woocommerce' );
			$price          = $product->get_price_html() ?: __( 'Не вказано', 'woocommerce' );
			$status         = get_post_status_object( get_post_status() )->label;
			$edit_url       = add_query_arg( [
				'edit_product' => get_the_ID(),
			], wc_get_account_endpoint_url( 'add-product' ) );
			$delete_url     = add_query_arg( [
				'action'     => 'delete_product',
				'product_id' => get_the_ID(),
				'_wpnonce'   => wp_create_nonce( 'delete_product_' . get_the_ID() ),
			], wc_get_account_endpoint_url( 'my-products' ) );

			echo '<tr>';
			echo '<td>' . esc_html( $name ) . '</td>';
			echo '<td>' . esc_html( $stock_quantity ) . '</td>';
			echo '<td>' . $price . '</td>';
			echo '<td>' . esc_html( $status ) . '</td>';
			echo '<td class="text-center">';
			echo '<a href="' . esc_url( $edit_url ) . '" class="button my-account-products-table__button edit-button">' . __( 'Редагувати', 'woocommerce' ) . '</a>';
			echo '<a href="' . esc_url( $delete_url ) . '" class="button my-account-products-table__button delete-product" onclick="return confirm(\'' . __( 'Ви впевнені, що хочете видалити цей товар?', 'woocommerce' ) . '\');">' . __( 'Видалити', 'woocommerce' ) . '</a>';
			echo '</td>';
			echo '</tr>';
		}
		echo '</tbody>';
		echo '</table>';
		echo '</div>';
		echo '<div class="pagination">';
		echo paginate_links( [
			'total'     => $query->max_num_pages,
			'current'   => $paged,
			'format'    => '?paged=%#%',
			'add_args'  => false,
			'prev_text' => __( '<', 'woocommerce' ),
			'next_text' => __( '>', 'woocommerce' ),
		] );
		echo '</div>';
	} else {
		echo '<p>' . __( 'У вас немає створених товарів.', 'woocommerce' ) . '</p>';
	}

	wp_reset_postdata();
}