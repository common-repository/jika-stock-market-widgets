<?php
if (!defined('ABSPATH')) exit; // Exit if accessed directly

require_once(__DIR__ . '/elementor.php');

use Elementor\Widget_Base;

class JikaWidgetsElementorStockPredictionAndForecastWidget extends Widget_Base
{
	use JikaWidgetsElementor;

	public static $slug = 'stock-prediction-and-forecast-widget';
	public static $title = 'Stock Prediction and Forecast Widget';

	public function get_icon()
	{
		return $this->get_base_icon() . '-' . self::$slug;
	}

	protected function render()
	{
		$jika_widgets_symbol = $this->get_settings_for_display('widget_symbol');
		$jika_widgets_graph_color = $this->parse_color($this->get_settings_for_display('widget_graph_color'));
		$jika_widgets_text_color = $this->parse_color($this->get_settings_for_display('widget_text_color'));
		$jika_widgets_background_color = $this->parse_color($this->get_settings_for_display('widget_background_color'));
		$jika_widgets_height = $this->get_settings_for_display('widget_height');
		if (empty($jika_widgets_height['auto']) && intval($jika_widgets_height['height']) > 0) {
			$jika_widgets_height = $jika_widgets_height['height'];
		} else {
			$jika_widgets_height = null;
		}
		$jika_widgets_font_family = $this->get_settings_for_display('widget_font_family');
		$this->generate_iframe('forecast-price-target', [
			'symbol' => $jika_widgets_symbol,
			'graphColor' => $jika_widgets_graph_color,
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
			'widget_symbol',
			[
				'label' => 'Symbol',
				'type' => 'symbol-input',
				'default' => 'AAPL',
			]
		);
		$this->add_control(
			'widget_graph_color',
			[
				'label' => 'Graph Color',
				'type' => \Elementor\Controls_Manager::COLOR,
				'default' => '#1652f0',
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

$jika_widgets_elementor_stock_prediction_and_forecast_widget = new JikaWidgetsElementorStockPredictionAndForecastWidget();
