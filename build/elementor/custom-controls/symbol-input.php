<?php
if (!defined('ABSPATH')) exit; // Exit if accessed directly

if (!class_exists('Elementor_Async_Select')) {
	require_once(__DIR__ . '/async-select.php');
}

class Jika_Widgets_Elementor_Symbol_Input extends Jika_Widgets_Elementor_Async_Select
{
	public function enqueue()
	{
		parent::enqueue();
		$plugin_data = get_plugin_data(__FILE__);
		$plugin_version = $plugin_data['Version'];

		// Scripts
		wp_register_script('elementor-symbol-input-control-script', plugins_url("assets/symbolInput.js", __DIR__), array('elementor-async-select-control-script'), $plugin_version, false);
		wp_enqueue_script('elementor-symbol-input-control-script');
	}

	public function get_type()
	{
		return 'symbol-input';
	}
}
