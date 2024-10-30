<?php
if (!defined('ABSPATH')) exit; // Exit if accessed directly

require_once(dirname(dirname(__DIR__)) . '/utils/env.php');

class Jika_Widgets_Elementor_Years_Range extends \Elementor\Control_Base_Multiple
{
	use JikaWidgetsEnv;

	public function enqueue()
	{
		$plugin_data = get_plugin_data(__FILE__);
		$plugin_version = $plugin_data['Version'];
		// Styles
		wp_register_style('elementor-years-range-control-style', plugins_url('assets/yearsRange.css', __DIR__), array(), $plugin_version, false);
		wp_enqueue_style('elementor-years-range-control-style');

		// Scripts
		$this->enqueue_env($plugin_version);
		wp_register_script(
			'elementor-jika-utils-script',
			plugins_url("assets/utils.js", __DIR__),
			array(),
			$plugin_version,
			false
		);
		wp_register_script(
			'elementor-years-range-control-script',
			plugins_url("assets/yearsRange.js", __DIR__),
			array('jquery', 'elementor-jika-utils-script'),
			$plugin_version,
			false
		);
		wp_enqueue_script('elementor-years-range-control-script');
		wp_localize_script(
			'elementor-years-range-control-script',
			'my_elementor_obj',
			array(
				'control_uid' => $this->get_control_uid(),
			)
		);
	}

	protected function get_default_settings()
	{

		return [
			'label' => ['From', 'To'],
		];
	}

	public function get_type()
	{
		return 'years-range';
	}

	public function get_default_value()
	{
		$current_year = intval(gmdate('Y'));
		return ['from' => $current_year - 5, 'to' => $current_year];
	}

	public function content_template()
	{
?>
		<div class="elementor-control-field elementor-control-type-select">
			<label for="<?php $this->print_control_uid('from'); ?>" class="elementor-control-title">{{{ data.label[0] }}}</label>
			<div class="elementor-control-input-wrapper">
				<select class="<?php $this->print_control_uid('from'); ?>">
				</select>
			</div>
			<label for="<?php $this->print_control_uid('to') ?>" class="elementor-control-title">{{{ data.label[1] }}}</label>
			<div class="elementor-control-input-wrapper">
				<select class="<?php $this->print_control_uid('to') ?>">
				</select>
			</div>
		</div>
		<# if ( data.description ) { #>
			<div class="elementor-control-field-description">{{{ data.description }}}</div>
			<# } #>
		<?php
	}
}
