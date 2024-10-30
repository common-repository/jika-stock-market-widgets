<?php
if (!defined('ABSPATH')) exit; // Exit if accessed directly

trait JikaWidgetsElementor
{

	public function get_name()
	{
		return self::$slug;
	}

	public function get_title()
	{
		return self::$title;
	}

	public function get_base_icon()
	{
		return 'elementor-jika-widgets-icon elementor-jika-widgets';
	}

	public function get_categories()
	{
		return ['general'];
	}

	protected function unique_array_join($array)
	{
		$unique_array = array();
		foreach ($array as $item) {
			$unique_array[$item] = $item;
		}
		return join(',', $unique_array);
	}

	protected function parse_color($color)
	{
		return str_replace('#', '', $color);
	}

	protected function get_url($url)
	{
		return $url['url'];
	}

	protected function calculate_iframe_height($base, $args)
	{
		$iframe_heights = [
			'user-portfolio' => 375,
			'forecast-price-target' => 570,
			'fundamentals-table' => 37,
			'area-chart' => 450,
			'fundamentals-chart' => 286.6,
		];
		$height = 0;
		$calculated_height = $iframe_heights[$base];
		if (array_key_exists('keys', $args)) {
			$keys = $args['keys'];
		}
		if (!isset($keys)) {
			$keys = 1;
		} else if (is_array($keys)) {
			$keys = count($keys);
		} else {
			$keys = count(explode(',', $keys));
		}
		switch ($base) {
			case "fundamentals-chart":
				$height = 65 + $calculated_height * $keys;
				break;
			case "fundamentals-table":
				$height = 105 + $calculated_height * $keys;
				if ($height > 510) {
					$height = 640;
				}
				break;
			default:
				$height = $calculated_height;
				break;
		}
		return $height;
	}

	protected function generate_iframe($base, $args, $echo = true)
	{
		global $jika_widgets_env;
		if (array_key_exists('EMBED_URL', $jika_widgets_env)) {
			$base_url = $jika_widgets_env['EMBED_URL'];
		} else if (array_key_exists('ENV', $jika_widgets_env) && $jika_widgets_env['ENV'] == 'development') {
			$base_url = 'https://www.jika.io/embed/sandbox/';
		} else {
			$base_url = 'https://www.jika.io/embed/';
		}
		$iframe = '
		class=wp-block-jika-widgets-iframe
		referrerPolicy=no-referrer-when-downgrade
		width=100%
		';
		$iframe_height = $this->calculate_iframe_height($base, $args);
		$iframe_src = $base_url . $base . '?';
		foreach ($args as $key => $value) {
			if ($key == 'height') {
				if (!empty($value)) {
					$iframe_height = $value;
				}
			} else if (!empty($value)) {
				$iframe_src .= $key . '=' . ($value) . '&';
			}
		}
		$iframe .= '
		height=' . $iframe_height . '
		';
		$iframe .= 'src=' . esc_url($iframe_src) . '&';
		if (defined('ELEMENTOR_VERSION')) {
			if (\Elementor\Plugin::$instance->editor->is_edit_mode()) {
				$iframe .= 'api_key=' . esc_attr(get_option('jika_widgets_options_elementor')['api_key']);
			}
		} else {
			$screen = get_current_screen();
			if ($screen->parent_base == 'edit') {
				$iframe .= 'api_key=' . esc_attr(get_option('jika_widgets_options_elementor')['api_key']);
			}
		}
		if (!$echo) {
			return '<iframe' . esc_attr($iframe) . ' style="background:#FFFFFF;padding:10px;border:none;border-radius:5px;box-shadow:0 2px 4px 0 rgba(0,0,0,.2);" ></iframe>';
		}
		echo '<div ' . esc_attr($this->get_render_attribute_string('wrapper')) . '>';
		echo '<iframe' . esc_attr($iframe) . ' style="background:#FFFFFF;padding:10px;border:none;border-radius:5px;box-shadow:0 2px 4px 0 rgba(0,0,0,.2);" ></iframe>';
		echo '</div>';
	}
}
