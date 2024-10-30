<?php
if (!defined('ABSPATH')) exit; // Exit if accessed directly

require_once(__DIR__ . '/elementor.php');

use Elementor\Widget_Base;

class JikaWidgetsElementorStockPortfolioPerformanceChart extends Widget_Base
{
	use JikaWidgetsElementor;

	public static string $slug = 'stock-portfolio-performance-chart';
	public static string $title = 'Stock Portfolio Performance Chart';

	public function get_icon()
	{
		return $this->get_base_icon() . '-' . self::$slug;
	}

	protected function render()
	{
		$jika_widgets_user_name = $this->get_settings_for_display('widget_user_name');
		$jika_widgets_background_color = $this->parse_color($this->get_settings_for_display('widget_background_color'));
		$jika_widgets_height = $this->get_settings_for_display('widget_height');
		if (empty($jika_widgets_height['auto']) && intval($jika_widgets_height['height']) > 0) {
			$jika_widgets_height = $jika_widgets_height['height'];
		} else {
			$jika_widgets_height = null;
		}
		$this->generate_iframe('user-portfolio', [
			'userName' => $jika_widgets_user_name,
			'backgroundColor' => $jika_widgets_background_color,
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
			'widget_user_name',
			[
				'label' => 'User Name',
				'type' => 'user-input',
				'default' => 'Noah Sebastian',
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
			'widget_height',
			[
				'type' => 'jika-widget-height',
			]
		);
		$this->end_controls_section();
	}
}

$jika_widgets_elementor_stock_portfolio_performance_chart = new JikaWidgetsElementorStockPortfolioPerformanceChart();
