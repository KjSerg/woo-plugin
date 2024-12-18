<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title><?php echo esc_html( $email_heading ); ?></title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f5f5f5;
        }

        .email-container {
            max-width: 600px;
            margin: 20px auto;
            background: #ffffff;
            border: 1px solid #e0e0e0;
            border-radius: 5px;
            overflow: hidden;
        }

        .email-header {
            background-color: #0071a1;
            color: #ffffff;
            text-align: center;
            padding: 15px;
        }

        .email-body {
            padding: 20px;
            color: #333333;
        }

        .email-body p {
            margin: 0 0 10px;
            line-height: 1.5;
        }

        .email-footer {
            text-align: center;
            font-size: 12px;
            color: #999999;
            padding: 15px;
            background: #f9f9f9;
        }

        .button {
            display: inline-block;
            background-color: #0071a1;
            color: #ffffff;
            text-decoration: none;
            padding: 10px 20px;
            border-radius: 3px;
            margin: 10px 0;
        }

        .button:hover {
            background-color: #005b7f;
        }

        a {
            color: #0071a1;
            text-decoration: none;
        }
    </style>
</head>
<body>
<div class="email-container">
    <div class="email-header">
        <h1><?php echo esc_html( $email_heading ); ?></h1>
    </div>
    <div class="email-body">
        <p><strong>Назва продукту:</strong> <?php echo esc_html( $product_name ); ?></p>
        <p>
            <strong>Посилання на автора:</strong>
            <a href="<?php echo esc_url( $author_url ); ?>">Переглянути автора</a>
        </p>
        <p>
            <strong>Посилання на редагування:</strong>
            <a href="<?php echo esc_url( $edit_url ); ?>" class="button">Редагувати продукт</a>
        </p>
    </div>
    <div class="email-footer">
        <p>Це автоматичне повідомлення. Будь ласка, не відповідайте на нього.</p>
        <p><?php echo get_bloginfo( 'name' ); ?> &copy; <?php echo date( 'Y' ); ?>. Усі права захищені.</p>
    </div>
</div>
</body>
</html>

