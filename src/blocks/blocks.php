<?php
if (!defined('ABSPATH')) exit; // Exit if accessed directly

function jika_widgets_register_blocks()
{
	register_block_type(__DIR__ . '/real-time-stock-price-chart');
	register_block_type(__DIR__ . '/advanced-stock-comparison-graph');
	register_block_type(__DIR__ . '/company-financial-metrics-table');
	register_block_type(__DIR__ . '/stock-prediction-and-forecast-widget');
	register_block_type(__DIR__ . '/stock-portfolio-performance-chart');
}
