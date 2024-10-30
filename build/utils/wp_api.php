<?php
if (!defined('ABSPATH')) exit; // Exit if accessed directly

class JikaWidgetsWpApi
{
	protected string $base_url;

	public function __construct()
	{
		global $jika_widgets_env;
		if (array_key_exists('API_URL', $jika_widgets_env)) {
			$this->base_url = $jika_widgets_env['API_URL'];
		} else if (array_key_exists('ENV', $jika_widgets_env) && $jika_widgets_env['ENV'] == 'development') {
			$this->base_url = 'https://www.jika.io/api/sandbox';
		} else {
			$this->base_url = 'https://www.jika.io/api';
		}
	}
	protected function fetch_data($method, $url, $args, $data_callback)
	{
		if (isset($method)) {
			$args["method"] = $method;
		}
		$response = wp_remote_get($this->base_url . $url, $args);
		$body = json_decode(wp_remote_retrieve_body($response), true);
		if (isset($body["error"])) {
			if (!array_key_exists('message', $body)) {
				$error_message = "Something went wrong, please check your input and try again";
			} else {
				$error_message = $body["message"];
			}
			throw new Exception(esc_html($error_message), esc_html($response['response']['code']));
		} else {
			$data_callback($body);
		}
	}

	public function get_user_usage($token, $sandbox_usage_reset, $data_callback)
	{
		$args = array(
			'headers' => array(
				'Authorization' => "Bearer $token"
			),
		);
		if ($sandbox_usage_reset) {
			$this->fetch_data("GET", "/widget_user/usage?sandbox_usage_reset=1", $args, $data_callback);
		} else {
			$this->fetch_data("GET", "/widget_user/usage", $args, $data_callback);
		}
	}

	public function get_user_api_key($token, $data_callback)
	{
		$args = array(
			'headers' => array(
				'Authorization' => "Bearer $token"
			),
		);
		$this->fetch_data("GET", "/widget_user/api_key", $args, $data_callback);
	}
}

$GLOBALS['jika_widgets_wp_api'] = new JikaWidgetsWpApi();
