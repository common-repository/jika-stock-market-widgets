<?php
if (!defined('ABSPATH')) exit; // Exit if accessed directly

require_once(dirname(dirname(__DIR__)) . '/utils/env.php');

class Jika_Widgets_Elementor_Async_Select extends \Elementor\Base_Data_Control
{
	use JikaWidgetsEnv;

	public function enqueue()
	{
		$plugin_data = get_plugin_data(__FILE__);
		$plugin_version = $plugin_data['Version'];
		// Styles
		wp_register_style('elementor-async-select-control-style', plugins_url('../../build/index.css', __DIR__), array('wp-components'), $plugin_version, false);
		wp_enqueue_style('elementor-async-select-control-style');

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
			'elementor-jika-store-script',
			plugins_url("assets/store.js", __DIR__),
			array('elementor-jika-utils-script'),
			$plugin_version,
			false
		);
		wp_register_script(
			'elementor-jika-kickstart-script',
			plugins_url("assets/kickstart.js", __DIR__),
			array('elementor-jika-utils-script', 'elementor-jika-store-script'),
			$plugin_version,
			false
		);
		wp_register_script(
			'elementor-async-select-control-script',
			plugins_url("assets/asyncSelect.js", __DIR__),
			array('react', 'react-dom', 'wp-components', 'elementor-jika-kickstart-script'),
			$plugin_version,
			false
		);
		wp_enqueue_script('elementor-async-select-control-script');
		wp_localize_script(
			'elementor-async-select-control-script',
			'my_elementor_obj',
			array(
				'control_uid' => $this->get_control_uid(),
			)
		);
	}

	protected function get_default_settings()
	{

		return [
			'label' => 'async-select',
		];
	}

	public function get_type()
	{
		return 'async-select';
	}

	public function content_template()
	{
		$control_uid = $this->get_control_uid();
?>
		<div class="elementor-control-field">
			<# if ( data.label ) {#>
				<label for="<?php echo esc_attr($control_uid); ?>" class="elementor-control-title">{{{ data.label }}}</label>
			<# } #>
			<div class="elementor-control-input-wrapper elementor-control-unit-5">
				<div class="elementor-control-async-select <?php echo esc_attr($control_uid) ?>"></div>
			</div>
		</div>
		<# if ( data.description ) { #>
			<div class="elementor-control-field-description">{{{ data.description }}}</div>
		<# } #>
<?php
	}
}
