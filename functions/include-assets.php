<?php

function wmpw_scripts() {
	wp_enqueue_style( 'quill', 'https://cdn.jsdelivr.net/npm/quill@2.0.3/dist/quill.snow.css', array(), '1.0' );
	wp_enqueue_script( 'quill', 'https://cdn.jsdelivr.net/npm/quill@2.0.3/dist/quill.js', array(), '1.0', true );
	wp_enqueue_style( 'wmpw-styles', CFE__ASSETS_URL . '/css/wmpw-style.css', array(), '1.0' );
	wp_enqueue_script( 'wmpw-scripts', CFE__ASSETS_URL . '/js/wmpw-scripts.js', array( 'jquery' ), '1.0', true );
	wp_localize_script( 'ajax-script', 'AJAX', array( 'ajax_url' => admin_url( 'admin-ajax.php' ) ) );
}

add_action( 'wp_enqueue_scripts', 'wmpw_scripts' );
