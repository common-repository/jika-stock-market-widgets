<?php
if (!defined('ABSPATH')) exit; // Exit if accessed directly

if (!trait_exists("JikaWidgetsElementor")) {
	require_once(dirname(__DIR__) . '/elementor/elementor.php');
}

class JikaWidgetsShortcode
{

	use JikaWidgetsElementor;

	public static string $slug = 'jika-stock-widget';
	public static string $title = 'Jika Stock Widget';

	public static $widget_types = array(
		'stock-portfolio-performance-chart' => 'user-portfolio',
		'stock-prediction-and-forecast-widget' => 'forecast-price-target',
		'company-financial-metrics-table' => 'fundamentals-table',
		'real-time-stock-price-chart' => 'area-chart',
		'advanced-stock-comparison-graph' => 'fundamentals-chart',
	);

	public function render($jika_widgets_atts)
	{
		if (!array_key_exists($jika_widgets_atts['type'], self::$widget_types)) {
			throw new Exception('Invalid widget type');
		}
		$jika_widgets_type = self::$widget_types[$jika_widgets_atts['type']];
		$jika_widgets_parsed_atts = array();
		foreach ($jika_widgets_atts as $jika_widgets_att_k => $jika_widgets_att_v) {
			if (preg_match("/color/i", $jika_widgets_att_k)) {
				$jika_widgets_parsed_atts[$jika_widgets_att_k] = $this->parse_color($jika_widgets_att_v);
			} else if (preg_match("/(symbols|keys)/i", $jika_widgets_att_k)) {
				$jika_widgets_parsed_atts[$jika_widgets_att_k] = $this->unique_array_join(explode(',', $jika_widgets_att_v));
			} else if ($jika_widgets_att_k != 'type') {
				$jika_widgets_parsed_atts[$jika_widgets_att_k] = $jika_widgets_att_v;
			}
		}
		return $this->generate_iframe($jika_widgets_type, $jika_widgets_parsed_atts, false);
	}
}

function jika_widgets_add_shortcodes()
{
	global $jika_widgets_shortcode_class;
	$jika_widgets_shortcode_class = new JikaWidgetsShortcode();
	add_shortcode('jika_stock_widget', 'jika_widgets_shortcode');
}

function jika_widgets_parse_underscore_to_camel_case($args)
{
	$new_args = array();
	foreach ($args as $key => $value) {
		$new_key = '';
		$key_part_index = 0;
		foreach (explode('_', $key) as $key_part) {
			if ($key_part_index == 0) {
				$new_key .= $key_part;
			} else {
				$new_key .= ucfirst($key_part);
			}
			$key_part_index += 1;
		}
		$new_args[$new_key] = $value;
	}
	return $new_args;
}

function jika_widgets_shortcode($atts = [], $content = null, $tag = '')
{
	$atts = array_change_key_case((array) $atts, CASE_LOWER);

	if (!array_key_exists('type', $atts)) {
		$atts['type'] = 'real-time-stock-price-chart';
	}

	switch ($atts['type']) {
		case 'stock-portfolio-performance-chart':
			$default_atts = array(
				'type' => 'stock-portfolio-performance-chart',
				'user_name' => 'Noah Sebastian',
				'background_color' => '#FFFFFF'
			);
			break;
		case 'company-financial-metrics-table':
			$default_atts = array(
				'type' => 'company-financial-metrics-table',
				'symbols' => 'AAPL,AMZN,META',
				'keys' => 'Market Cap,Net Income',
				'reporting_period' => 'quarter',
				'from' => intval(gmdate('Y')) - 5,
				'to' => intval(gmdate('Y')),
				'sort_method' => 'years',
				'text_color' => '#161c2d',
				'background_color' => '#FFFFFF',
				'font_family' => 'Nunito'
			);
			break;
		case 'advanced-stock-comparison-graph':
			$default_atts = array(
				'type' => 'advanced-stock-comparison-graph',
				'symbols' => 'AAPL,AMZN,META',
				'keys' => 'Market Cap,Net Income',
				'reporting_period' => 'quarter',
				'from' => intval(gmdate('Y')) - 5,
				'to' => intval(gmdate('Y')),
				'text_color' => '#161c2d',
				'background_color' => '#FFFFFF',
				'font_family' => 'Nunito'
			);
			break;
		case 'stock-prediction-and-forecast-widget':
			$default_atts = array(
				'type' => 'stock-prediction-and-forecast-widget',
				'symbol' => 'AAPL',
				'graph_color' => '#1652f0',
				'text_color' => '#161c2d',
				'background_color' => '#FFFFFF',
				'font_family' => 'Nunito'
			);
			break;
		case 'real-time-stock-price-chart':
			$default_atts = array(
				'type' => 'real-time-stock-price-chart',
				'symbol' => 'AAPL',
				'selection' => 'one_year',
				'close_key' => 'close',
				'graph_color' => '#1652f0',
				'text_color' => '#161c2d',
				'background_color' => '#FFFFFF',
				'font_family' => 'Nunito'
			);
			break;
	}

	$jika_widgets_atts = shortcode_atts(
		$default_atts,
		$atts,
		$tag
	);

	$jika_widgets_atts = jika_widgets_parse_underscore_to_camel_case($jika_widgets_atts);

	$o = '<div>';

	try {
		global $jika_widgets_shortcode_class;
		$o .= $jika_widgets_shortcode_class->render($jika_widgets_atts);
	} catch (Exception $error) {
		$o .= '<p style="color:red;">Jika Stock Widget Error - ' . $error->getMessage() . '</p>';
	}

	$o .= '</div>';

	return $o;
}
