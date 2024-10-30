<?php
if (!defined('ABSPATH')) exit; // Exit if accessed directly

class Jika_Widgets_Elementor_Financial_Keys extends Jika_Widgets_Elementor_Async_Select_Multi
{
	public function enqueue()
	{
		parent::enqueue();
		$plugin_data = get_plugin_data(__FILE__);
		$plugin_version = $plugin_data['Version'];

		// Scripts

		wp_register_script(
			'elementor-financial_keys-control-script',
			plugins_url("assets/financialKeys.js", __DIR__),
			array('react', 'react-dom', 'wp-components', 'elementor-async-select-control-script'),
			$plugin_version,
			false
		);
		wp_enqueue_script('elementor-financial_keys-control-script');
		wp_localize_script(
			'elementor-financial_keys-control-script',
			'my_elementor_obj',
			array(
				'control_uid' => $this->get_control_uid(),
			)
		);
	}

	public function get_type()
	{
		return 'financial-keys';
	}
}
