<?php
if (!defined('ABSPATH')) exit; // Exit if accessed directly

require_once(__DIR__ . '/elementor.php');

use Elementor\Widget_Base;

class JikaWidgetsElementorAdvancedStockComparisonGraph extends Widget_Base
{
	use JikaWidgetsElementor;

	public static $slug = 'advanced-stock-comparison-graph';
	public static $title = 'Advanced Stock Comparison Graph';

	public function get_icon()
	{
		return $this->get_base_icon() . '-' . self::$slug;
	}

	protected function render()
	{
		$jika_widgets_symbols = $this->unique_array_join($this->get_settings_for_display('widget_symbols'));
		$jika_widgets_financial_keys = $this->unique_array_join($this->get_settings_for_display('widget_financial_keys'));
		$jika_widgets_reporting_period = $this->get_settings_for_display('widget_reporting_period');
		$jika_widgets_years_range = $this->get_settings_for_display('widget_years_range');
		$jika_widgets_from = $jika_widgets_years_range['from'];
		$jika_widgets_to = $jika_widgets_years_range['to'];
		$jika_widgets_text_color = $this->parse_color($this->get_settings_for_display('widget_text_color'));
		$jika_widgets_background_color = $this->parse_color($this->get_settings_for_display('widget_background_color'));
		$jika_widgets_font_family = $this->get_settings_for_display('widget_font_family');
		$jika_widgets_height = $this->get_settings_for_display('widget_height');
		if (empty($jika_widgets_height['auto']) && intval($jika_widgets_height['height']) > 0) {
			$jika_widgets_height = $jika_widgets_height['height'];
		} else {
			$jika_widgets_height = null;
		}
		$this->generate_iframe('fundamentals-chart', [
			'symbols' => $jika_widgets_symbols,
			'keys' => $jika_widgets_financial_keys,
			'reportingPeriod' => $jika_widgets_reporting_period,
			'from' => $jika_widgets_from,
			'to' => $jika_widgets_to,
			'textColor' => $jika_widgets_text_color,
			'backgroundColor' => $jika_widgets_background_color,
			'fontFamily' => $jika_widgets_font_family,
			'height' => $jika_widgets_height
		]);
	}

	protected function _register_controls()
	{
		$this->start_controls_section(
			'content_section',
			[
				'label' => 'Options',
				'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
			]
		);
		$this->add_control(
			'widget_symbols',
			[
				'label' => 'Symbols',
				'type' => 'symbols-input',
				'default' => ['AAPL', 'AMZN', 'META'],
			]
		);
		$this->add_control(
			'widget_financial_keys',
			[
				'label' => 'Metrics',
				'type' => 'financial-keys',
				'default' => ['Market Cap', 'Net Income'],
			]
		);
		$this->add_control(
			'widget_reporting_period',
			[
				'label' => 'Reporting Period',
				'type' => \Elementor\Controls_Manager::SELECT,
				'default' => 'quarter',
				'options' => [
					'quarter' => 'Quarter',
					'annual' => 'Annual',
				]
			]
		);
		$this->add_control(
			'widget_years_range',
			[
				'type' => 'years-range',
			]
		);
		$this->add_control(
			'widget_text_color',
			[
				'label' => 'Text Color',
				'type' => \Elementor\Controls_Manager::COLOR,
				'default' => '#161c2d',
			]
		);
		$this->add_control(
			'widget_background_color',
			[
				'label' => 'Background Color',
				'type' => \Elementor\Controls_Manager::COLOR,
				'default' => '#FFFFFF',
			]
		);
		$this->add_control(
			'widget_font_family',
			[
				'label' => 'Font Family',
				'type' => \Elementor\Controls_Manager::SELECT,
				'default' => 'Nunito',
				'options' => [
					'Nunito' => 'Nunito',
					'Arial' => 'Arial',
					'Helvetica'  => 'Helvetica',
					'Times New Roman' => 'Times New Roman',
					'Times' => 'Times',
					'Courier New' => 'Courier New',
					'Courier' => 'Courier',
					'Verdana' => 'Verdana',
					'Roboto' => 'Roboto',
					'Open Sans' => 'Open Sans',
					'Lato' => 'Lato',
					'Montserrat' => 'Montserrat',
					'Poppins' => 'Poppins',
				],
			]
		);
		$this->add_control(
			'widget_height',
			[
				'type' => 'jika-widget-height',
			]
		);
		$this->end_controls_section();
	}
}

$jika_widgets_elementor_advanced_stock_comparison_graph = new JikaWidgetsElementorAdvancedStockComparisonGraph();
