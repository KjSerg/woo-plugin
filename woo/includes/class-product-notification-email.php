<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class WC_Email_Product_Notification extends WC_Email {
	public function __construct() {
		$this->id             = 'product_notification';
		$this->title          = __( 'Повідомлення про новий товар', CFE__PLUGIN_NAME );
		$this->description    = __( 'Відправляє повідомлення адміну при створенні або редагуванні продукту.', CFE__PLUGIN_NAME );
		$this->template_html  = 'emails/product-notification-email.php';
		$this->template_plain = 'emails/plain/product-notification-email.php';
		$this->template_base  = CFE__PLUGIN_DIR . '/templates/';
		$this->recipient      = get_option( 'admin_email' );
		parent::__construct();
		$this->enabled = $this->get_option( 'enabled', 'yes' );
		add_action( 'woocommerce_email_header', [ $this, 'email_header' ], 10, 2 );
		add_action( 'woocommerce_email_footer', [ $this, 'email_footer' ], 10, 1 );
	}

	public function trigger( $product_id, $is_edit = false ) {
		if ( ! $this->is_enabled() ) {
			return;
		}

		$product = wc_get_product( $product_id );
		if ( ! $product ) {
			return;
		}
		$this->object                         = $product;
		$this->placeholders['{product_name}'] = $product->get_name();
		$this->placeholders['{edit_link}']    = admin_url( 'post.php?post=' . $product_id . '&action=edit' );
		$this->placeholders['{author_link}']  = admin_url( 'user-edit.php?user_id=' . get_author_id( $product_id ) );
		$this->placeholders['{action}']       = $is_edit ? __( 'редаговано', CFE__PLUGIN_NAME ) : __( 'створено', CFE__PLUGIN_NAME );

		$this->send( $this->get_recipient(), $this->get_subject(), $this->get_content(), $this->get_headers(), $this->get_attachments() );
	}

	public function get_default_subject() {
		return __( 'Продукт {action}: {product_name}', CFE__PLUGIN_NAME );
	}

	public function get_default_heading() {
		return __( 'Новий продукт', CFE__PLUGIN_NAME );
	}

	public function get_content_html() {
		return wc_get_template_html( $this->template_html, [
			'product'       => $this->object,
			'email_heading' => $this->get_heading(),
			'sent_to_admin' => true,
			'plain_text'    => false,
			'email'         => $this,
		] );
	}

	public function get_content_plain() {
		return wc_get_template_html( $this->template_plain, [
			'product'       => $this->object,
			'email_heading' => $this->get_heading(),
			'sent_to_admin' => true,
			'plain_text'    => true,
			'email'         => $this,
		] );
	}
}