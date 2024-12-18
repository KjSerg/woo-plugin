<?php
function my_account_add_product_content() {
	require_once ABSPATH . 'wp-admin/includes/image.php';
	require_once ABSPATH . 'wp-admin/includes/file.php';
	require_once ABSPATH . 'wp-admin/includes/media.php';
	$edit_product      = $_GET['edit_product'] ?? '';
	$title             = '';
	$price             = '';
	$qnt               = '';
	$description       = '';
	$gallery           = array();
	$edit_product_test = true;
	if ( $edit_product ) {
		$edit_product      = (int) $edit_product;
		$edit_product_test = get_post( $edit_product );
		if ( $edit_product_test ) {
			$edit_product_test = (int) get_author_id( $edit_product ) == get_current_user_id();
		}
		if ( ! $edit_product_test ) {
			$str = __( 'Виникла помилка: У вас немає доступу', CFE__PLUGIN_NAME );
			echo "<h2>$str</h2>";
			$edit_product_test = false;
		} else {
			$product     = new WC_Product( $edit_product );
			$title       = get_the_title( $edit_product );
			$price       = $product->get_price();
			$description = $product->get_description();
			$qnt         = $product->get_stock_quantity() ?? 0;
			$gallery_ids = $product->get_gallery_image_ids();
			$image_id    = $product->get_image_id();
			if ( $gallery_ids ) {
				if ( $image_id ) {
					$image_id = (int) $image_id;
					array_unshift( $gallery_ids, $image_id );
					$gallery = array_unique( $gallery_ids );
				}
			}
		}
	}
	if ( ! is_shop_manager() ) {
		$str = __( 'Виникла помилка: У вас немає доступу', CFE__PLUGIN_NAME );
		echo "<h2>$str</h2>";
	}
	if ( $edit_product_test ):
		?>
        <h3> <?php echo __( 'Додати товар', CFE__PLUGIN_NAME ); ?> </h3>
        <form action="<?php echo AJAX_URL; ?>"
              class="form  custom-form-js <?php echo $edit_product ? 'edit-product' : 'create-product'; ?>" id="create-product"
              method="post"
              novalidate=""
              enctype="multipart/form-data">
            <input type="hidden" name="action" value="create_woo_product">
            <input type="hidden" name="product_id"
                   value="<?php echo esc_attr( $edit_product ); ?>">
            <input type="hidden" name="old_gallery"
                   value="<?php echo $gallery ? esc_attr( implode( ',', $gallery ) ) : ''; ?>">
            <label class="form-label ">
                <span><?php echo __( 'Назва товару', CFE__PLUGIN_NAME ); ?></span>
                <input class="input_st "
                       type="text"
                       name="title"
                       value="<?php echo esc_attr( $title ) ?>"
                       placeholder="<?php echo __( 'Введіть значення', CFE__PLUGIN_NAME ); ?>" required="required">
            </label>
            <label class="form-label half">
                <span><?php echo __( 'Ціна товару', CFE__PLUGIN_NAME ); ?></span>
                <input class="input_st number-input"
                       type="text"
                       name="price"
                       value="<?php echo esc_attr( $price ) ?>"
                       placeholder="<?php echo __( 'Введіть значення', CFE__PLUGIN_NAME ); ?>" required="required">
            </label>
            <label class="form-label half">
                <span><?php echo __( 'Кількість одиниць товару', CFE__PLUGIN_NAME ); ?></span>
                <input class="input_st number-input"
                       type="text"
                       name="qnt"
                       value="<?php echo esc_attr( $qnt ) ?>"
                       placeholder="<?php echo __( 'Введіть значення', CFE__PLUGIN_NAME ); ?>" required="required">
            </label>
            <div class="form-label form-label--editor">
                <span><?php echo __( 'Опис товару', CFE__PLUGIN_NAME ); ?></span>
                <input type="hidden" required class="value-field"
                       value="<?php echo esc_attr( $description ) ?>"
                       name="description">
                <div class="editor-wrapper">
                    <div id="editor" class="editor">
						<?php echo $description ?>
                    </div>
                </div>
            </div>
            <label class="form-label">
                <span><?php echo __( 'Зображення товару', CFE__PLUGIN_NAME ); ?></span>
                <span class="file-preview"
                      data-text="<?php echo __( 'Клікніть щоб додати', CFE__PLUGIN_NAME ); ?>">
                    <?php if ( ! $gallery ) {
	                    echo __( 'Клікніть щоб додати', CFE__PLUGIN_NAME );
                    } else {
	                    foreach ( $gallery as $image ) {
		                    $img_url = wp_get_attachment_url( $image );
		                    echo "<img src='$img_url' alt=''>";
	                    }
                    } ?>
                </span>
                <input type="file"
					<?php echo ! $gallery ? 'required' : ''; ?>
                       style="display: none"
                       multiple
                       accept="image/*"
                       class="upload-files"
                       name="upfile[]"
                >
            </label>
            <button class="form-button button"><?php echo __( 'Створити', CFE__PLUGIN_NAME ); ?></button>
			<?php echo wp_nonce_field( 'create_woo_product', 'true_nonce', true, false ); ?>
        </form>
	<?php endif; ?>
    <div class="msg" style="display: none"></div>
    <script>
        var admin_ajax = '<?php echo AJAX_URL; ?>';
    </script>
	<?php
}