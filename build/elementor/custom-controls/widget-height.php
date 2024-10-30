<?php
if (!defined('ABSPATH')) exit; // Exit if accessed directly

require_once(dirname(dirname(__DIR__)) . '/utils/env.php');

class Jika_Widgets_Elementor_Jika_Widget_Height extends \Elementor\Control_Base_Multiple
{
	use JikaWidgetsEnv;

	public function enqueue()
	{
		$plugin_data = get_plugin_data(__FILE__);
		$plugin_version = $plugin_data['Version'];

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
			'elementor-height-control-script',
			plugins_url("assets/heightControl.js", __DIR__),
			array('jquery', 'elementor-jika-utils-script'),
			$plugin_version,
			false
		);
		wp_enqueue_script('elementor-height-control-script');
		wp_localize_script(
			'elementor-height-control-script',
			'my_elementor_obj',
			array(
				'control_uid' => $this->get_control_uid(),
			)
		);
	}

	protected function get_default_settings()
	{

		return [
			'label' => 'Height',
		];
	}

	public function get_type()
	{
		return 'jika-widget-height';
	}

	public function get_default_value()
	{
		return ['height' => '', 'auto' => true];
	}

	public function content_template()
	{
		$height_control_uid = $this->get_control_uid('height');
		$auto_control_uid = $this->get_control_uid('auto');
?>
		<div class="elementor-control-field">
			<# if ( data.label ) {#>
				<label for="<?php echo esc_attr($height_control_uid); ?>" class="elementor-control-title">{{{ data.label }}}</label>
			<# } #>
			<div class="elementor-control-input-wrapper elementor-control-unit-5 elementor-heihgt-control">
				<input type="text" class="<?php echo esc_attr($height_control_uid) ?>" value="{{data.controlValue.height}}" disabled="true"></input>
				<label for="<?php echo esc_attr($auto_control_uid); ?>" class="elementor-control-title">Auto</label>
				<# if ( data.controlValue.auto ) { #>
					<input type="checkbox" style="margin: 5px 0 0 0" class="<?php echo esc_attr($auto_control_uid) ?>" checked></input>
				<# } #>
				<# if ( !data.controlValue.auto ) { #>
					<input type="checkbox" style="margin: 5px 0 0 0" class="<?php echo esc_attr($auto_control_uid) ?>"></input>
				<# } #>
			</div>
		</div>
		<# if ( data.description ) { #>
			<div class="elementor-control-field-description">{{{ data.description }}}</div>
		<# } #>
<?php
	}
}
