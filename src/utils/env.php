<?php
if (!defined('ABSPATH')) exit; // Exit if accessed directly

trait JikaWidgetsEnv
{
	public function enqueue_env($plugin_version)
	{
		global $jika_widgets_env;
		wp_register_script(
			'jika-widgets-env-script',
			plugins_url("assets/env.js", __DIR__),
			array(),
			$plugin_version,
			false
		);
		wp_enqueue_script('jika-widgets-env-script');
		wp_localize_script(
			'jika-widgets-env-script',
			'wp_env_obj',
			$jika_widgets_env
		);
	}
}
