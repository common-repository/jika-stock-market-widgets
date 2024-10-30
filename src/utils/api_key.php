<?php
if (!defined('ABSPATH')) exit; // Exit if accessed directly

add_action('enqueue_block_editor_assets', 'jika_widgets_enqueue_editor_assets');
add_action('wp_ajax_jika_widgets_refresh_api_key', 'jika_widgets_refresh_api_key');

add_action('elementor/editor/before_enqueue_scripts', 'jika_widgets_elementor_editor_scripts');
add_action('wp_ajax_jika_widgets_refresh_api_key_elementor', 'jika_widgets_refresh_api_key_elementor');

function jika_widgets_get_api_key()
{
	return get_option('jika_widgets_options_elementor', array('api_key' => ''));
}

function jika_widgets_enqueue_editor_assets()
{
	$jika_widgets_options = get_option('jika_widgets_options');
	if (is_array($jika_widgets_options) && array_key_exists('jika_widgets_token', $jika_widgets_options) && isset($jika_widgets_options['jika_widgets_token'])) {
		try {
			global $jika_widgets_wp_api;
			$jika_widgets_wp_api->get_user_api_key($jika_widgets_options['jika_widgets_token'], function ($data) {
				$jika_widgets_options_elementor = jika_widgets_get_api_key();
				$jika_widgets_options_elementor['api_key'] = $data['api_key'];
				update_option('jika_widgets_options_elementor', $jika_widgets_options_elementor);
				$js_editor_nonce = wp_create_nonce('jika_widgets_editor_action');
				wp_localize_script(
					'wp-editor',
					'jika_widgets_editor_obj',
					array(
						'ok' =>	true,
						'api_key' => $data['api_key'],
						'_ajax_nonce' => $js_editor_nonce,
						'ajax_url' => admin_url('admin-ajax.php'),
						'action' => 'jika_widgets_refresh_api_key'
					)
				);
				$plugin_data = get_plugin_data(__FILE__);
				$plugin_version = $plugin_data['Version'];
				wp_register_script("JikaWidgetsEditorJS", plugins_url('/assets/editor.js', __DIR__), array('wp-editor', 'jquery'), $plugin_version, false);
				wp_enqueue_script('JikaWidgetsEditorJS');
			});
		} catch (Exception $jika_widgets_exception) {
			wp_localize_script(
				'wp-editor',
				'jika_widgets_editor_obj',
				array(
					'ok' =>	false,
				)
			);
		}
	} else {
		wp_localize_script(
			'wp-editor',
			'jika_widgets_editor_obj',
			array(
				'ok' =>	false,
			)
		);
	}
}

function jika_widgets_refresh_api_key()
{
	check_ajax_referer('jika_widgets_editor_action');
	$jika_widgets_options = get_option('jika_widgets_options');
	if (is_array($jika_widgets_options) && array_key_exists('jika_widgets_token', $jika_widgets_options) && isset($jika_widgets_options['jika_widgets_token'])) {
		global $jika_widgets_wp_api;
		$jika_widgets_wp_api->get_user_api_key($jika_widgets_options['jika_widgets_token'], function ($data) {
			$jika_widgets_options_elementor = jika_widgets_get_api_key();
			$jika_widgets_options_elementor['api_key'] = $data['api_key'];
			update_option('jika_widgets_options_elementor', $jika_widgets_options_elementor);
			echo esc_html($data['api_key']);
		});
	}
	wp_die();
}

function jika_widgets_elementor_editor_scripts()
{
	$jika_widgets_options = get_option('jika_widgets_options');
	if (is_array($jika_widgets_options) && array_key_exists('jika_widgets_token', $jika_widgets_options) && isset($jika_widgets_options['jika_widgets_token'])) {
		try {
			global $jika_widgets_wp_api;
			$jika_widgets_wp_api->get_user_api_key($jika_widgets_options['jika_widgets_token'], function ($data) {
				$jika_widgets_options_elementor = jika_widgets_get_api_key();
				if (isset($data['api_key'])) {
					$jika_widgets_options_elementor['api_key'] = $data['api_key'];
				}
				update_option('jika_widgets_options_elementor', $jika_widgets_options_elementor);
				$js_editor_nonce = wp_create_nonce('jika_widgets_editor_action');
				$plugin_data = get_plugin_data(__FILE__);
				$plugin_version = $plugin_data['Version'];
				wp_register_script("JikaWidgetsEditorJS", plugins_url('/assets/editor.js', __DIR__), array('jquery'), $plugin_version, false);
				wp_localize_script(
					'JikaWidgetsEditorJS',
					'jika_widgets_editor_obj',
					array(
						'ok' =>	true,
						'_ajax_nonce' => $js_editor_nonce,
						'ajax_url' => admin_url('admin-ajax.php'),
						'action' => 'jika_widgets_refresh_api_key_elementor'
					)
				);
				wp_enqueue_script('JikaWidgetsEditorJS');
			});
		} catch (Exception $jika_widgets_exception) {
			wp_localize_script(
				'elementor-editor-document',
				'jika_widgets_editor_obj',
				array(
					'ok' =>	false,
				)
			);
		}
	} else {
		wp_localize_script(
			'elementor-editor-document',
			'jika_widgets_editor_obj',
			array(
				'ok' =>	false,
			)
		);
	}
}

function jika_widgets_refresh_api_key_elementor()
{
	check_ajax_referer('jika_widgets_editor_action');
	$jika_widgets_options = get_option('jika_widgets_options');
	if (is_array($jika_widgets_options) && array_key_exists('jika_widgets_token', $jika_widgets_options) && isset($jika_widgets_options['jika_widgets_token'])) {
		global $jika_widgets_wp_api;
		$jika_widgets_wp_api->get_user_api_key($jika_widgets_options['jika_widgets_token'], function ($data) {
			$jika_widgets_options_elementor = jika_widgets_get_api_key();
			$jika_widgets_options_elementor['api_key'] = $data['api_key'];
			update_option('jika_widgets_options_elementor', $jika_widgets_options_elementor);
		});
	}
	wp_die();
}
