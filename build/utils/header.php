<?php
if (!defined('ABSPATH')) exit; // Exit if accessed directly

function jika_widgets_headers_config()
{

	add_action('send_headers', function () {
		header('Referrer-Policy: no-referrer-when-downgrade', true);
	});

	add_filter('wp_headers', function ($headers) {
		$headers['Referrer-Policy'] = 'no-referrer-when-downgrade';
		return $headers;
	});

	apply_filters('admin_referrer_policy', 'no-referrer-when-downgrade');
}
