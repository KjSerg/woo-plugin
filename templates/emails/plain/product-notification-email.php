<?php
if (!defined('ABSPATH')) {
	exit;
}
?>
<?php printf(__('Продукт "%s" було %s.', 'product-notification-email'), $product->get_name(), $action); ?>
<?php printf(__('Редагувати продукт: %s', 'product-notification-email'), $edit_link); ?>
<?php printf(__('Автор продукту: %s', 'product-notification-email'), $author_link); ?>
