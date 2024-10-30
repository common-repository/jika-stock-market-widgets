<?php

/**
 * Plugin Name:       Jika Stock Market Widgets
 * Plugin URI:        https://www.jika.io/widgets
 * Description:       Stock Market Widgets for WordPress By Jika.io
 * Requires at least: 6.4
 * Requires PHP:      7.0
 * Version:           1.0.0
 * Author:            jika.io
 * Author URI:        https://www.jika.io
 * License:           GPL-2.0-or-later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 *
 * @package           jika-stock-market-widgets
 */

if (!defined('ABSPATH')) {
	exit; // Exit if accessed directly.
}

global $jika_widgets_env;

if (file_exists(__DIR__ . '/.env')) {
	$jika_widgets_env = parse_ini_file(__DIR__ . '/.env');
} else {
	$jika_widgets_env = array('ENV' => 'production');
}

add_action('init', 'jika_widgets_blocks_init');
add_action('init', 'jika_widgets_shortcode_init');

if (defined('ELEMENTOR_VERSION')) {
	if (version_compare(ELEMENTOR_VERSION, '3.5.0', '>=')) {
		add_action('elementor/controls/register', 'jika_widgets_register_elementor_controls');
		add_action('elementor/widgets/register', 'jika_widgets_register_elementor_elements');
	} else {
		add_action('elementor/controls/controls_registered', 'jika_widgets_register_elementor_controls');
		add_action('elementor/widgets/widgets_registered', 'jika_widgets_register_elementor_elements');
	}
}

require_once(__DIR__ . '/build/utils/wp_api.php');
require_once(__DIR__ . '/build/utils/api_key.php');

function jika_widgets_blocks_init()
{

	register_setting(
		'jika_widgets_option_group', // Option group
		'jika_widgets_options_elementor', // Option name
		array(
			'sanitize_callback' => function ($input) {
				if (!is_array($input)) {
					$input = array();
				}
				$new_input = array();
				$new_input['api_key'] = array_key_exists('api_key', $input) && isset($input['api_key']) ? $input['api_key'] : '';
				return $new_input;
			}
		)
	);

	require_once(__DIR__ . '/build/utils/header.php');
	jika_widgets_headers_config();

	require_once(__DIR__ . '/build/blocks/blocks.php');
	jika_widgets_register_blocks();

	if (is_admin()) {
		require_once(__DIR__ . '/build/admin/admin.php');
	}
}

function jika_widgets_shortcode_init()
{
	require_once(__DIR__ . '/build/shortcode/shortcode.php');
	jika_widgets_add_shortcodes();
}



function jika_widgets_register_elementor_elements($widgets_manager)
{
	// Icons
	wp_register_style('elementor-icons-style', plugins_url('build/elementor/assets/icons.css', __FILE__), array(), '1.0.0', false);
	wp_enqueue_style('elementor-icons-style');
	require_once(__DIR__ . '/build/elementor/real-time-stock-price-chart.php');
	$widgets_manager->register($jika_widgets_elementor_real_time_stock_price_chart);
	require_once(__DIR__ . '/build/elementor/advanced-stock-comparison-graph.php');
	$widgets_manager->register($jika_widgets_elementor_advanced_stock_comparison_graph);
	require_once(__DIR__ . '/build/elementor/company-financial-metrics-table.php');
	$widgets_manager->register($jika_widgets_elementor_company_financial_metrics_table);
	require_once(__DIR__ . '/build/elementor/stock-prediction-and-forecast-widget.php');
	$widgets_manager->register($jika_widgets_elementor_stock_prediction_and_forecast_widget);
	require_once(__DIR__ . '/build/elementor/stock-portfolio-performance-chart.php');
	$widgets_manager->register($jika_widgets_elementor_stock_portfolio_performance_chart);
}

function jika_widgets_register_elementor_controls($controls_manager)
{
	require_once(__DIR__ . '/build/elementor/custom-controls/user-input.php');
	$controls_manager->register(new \Jika_Widgets_Elementor_User_Input());
	require_once(__DIR__ . '/build/elementor/custom-controls/symbol-input.php');
	$controls_manager->register(new \Jika_Widgets_Elementor_Symbol_Input());
	require_once(__DIR__ . '/build/elementor/custom-controls/symbols-input.php');
	$controls_manager->register(new \Jika_Widgets_Elementor_Symbols_Input());
	require_once(__DIR__ . '/build/elementor/custom-controls/years-range.php');
	$controls_manager->register(new \Jika_Widgets_Elementor_Years_Range());
	require_once(__DIR__ . '/build/elementor/custom-controls/financial-keys.php');
	$controls_manager->register(new \Jika_Widgets_Elementor_Financial_Keys());
	require_once(__DIR__ . '/build/elementor/custom-controls/widget-height.php');
	$controls_manager->register(new \Jika_Widgets_Elementor_Jika_Widget_Height());
}
