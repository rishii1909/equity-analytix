<?php

add_action( 'phpmailer_init', 'ea_phpmailer_init' );
add_filter( 'wp_mail_from_name', 'ea_wp_mailer_from_name' );

function ea_phpmailer_init( PHPMailer $mailer ) {
	$mailer->Host = 'smtp.gmail.com';
	$mailer->Port = 587;
	$mailer->Username = 'arstan.omurzakov@gmail.com';
	$mailer->Password = 'ubrkjvdfobljokjx';
	$mailer->SMTPAuth = true;
	$mailer->SMTPSecure = 'tls';

	$mailer->isSMTP();
}

function ea_wp_mailer_from_name() {
	return 'Equity Analytix';
}
