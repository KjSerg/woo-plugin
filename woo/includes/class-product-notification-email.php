<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class WC_Email_Product_Notification extends WC_Email {

	private string $product_name;
	/**
	 * @var false
	 */
	private bool $is_edit;

	public function __construct() {
		$this->is_edit        = false;
		$this->id             = 'product_notification';
		$this->title          = __( 'Повідомлення про новий товар', CFE__PLUGIN_NAME );
		$this->description    = __( 'Відправляє повідомлення адміну при створенні або редагуванні продукту.', CFE__PLUGIN_NAME );
		$this->template_html  = 'emails/product-notification-email.php';
		$this->template_plain = 'emails/plain/product-notification-email.php';
		$this->template_base  = CFE__PLUGIN_DIR . '/templates/';
		$this->recipient      = get_option( 'admin_email' );
		$this->product_name = '';
		parent::__construct();
		$this->enabled = $this->get_option( 'enabled', 'yes' );
		add_action( 'woocommerce_email_header', [ $this, 'email_header' ], 10, 2 );
		add_action( 'woocommerce_email_footer', [ $this, 'email_footer' ], 10, 1 );
	}

	public function trigger( $product_id, $is_edit = false ) {
		$product = wc_get_product( $product_id );

		if ( ! $product ) {
			return;
		}
		$this->is_edit      = $is_edit;
		$this->product_name = $product->get_name();
		$this->author_url   = admin_url( 'user-edit.php?user_id=' . $product->get_post_data()->post_author );
		$this->edit_url     = admin_url( 'post.php?post=' . $product_id . '&action=edit' );

		$this->recipient = get_option( 'admin_email' );

		if ( ! $this->is_enabled() || ! $this->get_recipient() ) {
			return;
		}

		$this->send( $this->get_recipient(), $this->get_subject(), $this->get_content(), $this->get_headers(), $this->get_attachments() );
	}


	public function get_default_subject() {
		$action = $this->is_edit ? 'редаговано' :'додано';
		$product_name = $this->product_name;
		return "Продукт $action: $product_name";
	}

	public function get_default_heading() {
		return __( 'Новий продукт', CFE__PLUGIN_NAME );
	}


	public function get_content_html() {
		ob_start();
		wc_get_template(
			'emails/product-notification-email.php',
			[
				'email_heading' => $this->get_heading(),
				'product_name'  => $this->product_name,
				'author_url'    => $this->author_url,
				'edit_url'      => $this->edit_url,
				'email'         => $this,
			],
			'wp-my-product-webspark/',
			CFE__PLUGIN_DIR . '/templates/'
		);

		return ob_get_clean();
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