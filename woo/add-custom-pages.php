<?php
function my_account_add_custom_endpoints() {
	add_rewrite_endpoint('add-product', EP_PAGES);
	add_rewrite_endpoint('my-products', EP_PAGES);
}

function my_account_custom_menu_items($items) {
	$new_items = array(
		'add-product' => __('Додати товар', CFE__PLUGIN_NAME),
		'my-products' => __('Мої товари', CFE__PLUGIN_NAME),
	);
	$logout = array_slice($items, array_search('customer-logout', array_keys($items)), null, true);
	$items = array_slice($items, 0, array_search('customer-logout', array_keys($items)), true) + $new_items + $logout;
	return $items;
}

function my_account_flush_rewrite_rules() {
	my_account_add_custom_endpoints();
	flush_rewrite_rules();
}
