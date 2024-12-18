<?php
if (!defined('ABSPATH')) {
	exit;
}
?>
<p><?php printf(__('Продукт "%s" було %s.', CFE__PLUGIN_DIR), $product->get_name(), $action); ?></p>
<p><?php printf(__('Редагувати продукт: <a href="%s">Редагувати</a>',CFE__PLUGIN_DIR), $edit_link); ?></p>
<p><?php printf(__('Автор продукту: <a href="%s">Перейти</a>', CFE__PLUGIN_DIR), $author_link); ?></p>