<?php
if (!defined('ABSPATH')) exit; // Exit if accessed directly

require_once(dirname(__DIR__) . '/utils/env.php');

class JikaWidgetsAdminSettings
{
	use JikaWidgetsEnv;

	public string $paypal_client_id;
	public string $jika_widgets_url;
	public array $options;

	/**
	 * Class constructor
	 */
	public function __construct()
	{
		global $jika_widgets_env;
		if (array_key_exists('ENV', $jika_widgets_env) && $jika_widgets_env['ENV'] == 'development') {
			$this->paypal_client_id = "AQ4MgstfRbF6VA4y0b9Fz_TonyRwG_yFjfMbuX_kOUlpNFkXQT9YXDdeLGN6fCsR_zKNZohFX7mwL9m3";
		} else {
			$this->paypal_client_id = "AXRoCAm8a7PjKSVPd2yCTQjEPP8u0NEFjv7MiYl3I-thw9yrui0LXA0lIch5QsimvO9dj21xM_0-zIoM";
		}
		$this->jika_widgets_url = 'https://www.jika.io/widgets';
		add_action('admin_menu', array($this, 'jika_widgets_admin_menu'));
		add_action('admin_init', array($this, 'jika_widgets_page_init'));
		add_action('admin_notices', array($this, 'jika_widgets_display_notice'));
		add_action('wp_ajax_jika_widgets_auth_toggle', array($this, 'auth_toggle'));
		add_action('wp_ajax_jika_widgets_auth_submit', array($this, 'auth_submit'));
		add_action('wp_ajax_jika_widgets_after_paypal_subscription', array($this, 'after_paypal_subscription'));
		add_action('wp_ajax_jika_widgets_update_trademark', array($this, 'update_trademark'));
		add_action('wp_ajax_jika_widgets_update_domain', array($this, 'update_domain'));
	}

	public static function get_page_url()
	{

		$args = array('page' => 'jika-widgets-settings-overview');

		$url = add_query_arg($args, admin_url('admin.php'));

		return $url;
	}

	public function jika_widgets_get_options()
	{
		$this->options = get_option('jika_widgets_options', $this->jika_widgets_sanitize(array()));
	}

	public function jika_widgets_update_options()
	{
		update_option('jika_widgets_options', $this->options);
	}

	public function jika_widgets_ajax_action($new_options = array())
	{
		check_ajax_referer('jika_widgets_admin_action');
		if (count($new_options) > 0) {
			foreach ($new_options as $new_option) {
				$this->options["jika_widgets_" . $new_option] = sanitize_text_field($_POST[$new_option]);
			}
			$this->jika_widgets_update_options();
		}
		wp_die();
	}

	public function auth_submit()
	{
		check_ajax_referer('jika_widgets_admin_action');
		$new_options = array();
		if (array_key_exists('error', $_POST)) {
			$new_options = array('error');
		} else if (array_key_exists('token', $_POST)) {
			$new_options = array('email', 'domain', 'plan', 'trademark', 'token');
		} else if (array_key_exists('message', $_POST)) {
			$new_options = array('message');
		}
		if (count($new_options) > 0) {
			foreach ($new_options as $new_option) {
				$this->options["jika_widgets_" . $new_option] = sanitize_text_field($_POST[$new_option]);
			}
			$this->jika_widgets_update_options();
		}
		wp_die();
	}

	public function auth_toggle()
	{
		$this->jika_widgets_ajax_action(array('auth_type', 'token'));
	}

	public function after_paypal_subscription()
	{
		$this->jika_widgets_ajax_action(array('plan', 'token'));
	}

	public function update_trademark()
	{
		$this->jika_widgets_ajax_action(array('trademark', 'token'));
	}

	public function update_domain()
	{
		$this->jika_widgets_ajax_action(array('domain', 'token'));
	}

	public function jika_widgets_display_notice()
	{
		global $hook_suffix;
		$this->jika_widgets_get_options();
		if ($hook_suffix == 'plugins.php' && empty($this->options['jika_widgets_token'])) {
			include(plugin_dir_path(__FILE__) . 'admin_plugin_alert.php');
		}
	}

	/**
	 * Add options page
	 */
	public function jika_widgets_admin_menu()
	{
		$this->jika_widgets_get_options();
		// This page will be a new menu
		add_menu_page(
			'Jika Widgets Settings',
			'Jika Widgets',
			'manage_options',
			'jika-widgets-settings-overview',
			array($this, 'jika_widgets_create_admin_page')
		);
	}

	public function jika_widgets_create_plan_card($plan_name, $plan_label, $active)
	{
		// This card will be populated from admin.js
?>
		<button type="button" id="plan_<?php echo esc_attr($plan_name) ?>" class="jika-widgets-plan<?php echo $active ? ' jika-widgets-active jika-widgets-current' : '' ?>">
			<div class="jika-widgets-spinner"></div>
		</button>
	<?php
	}

	/**
	 * Options page callback
	 */
	public function jika_widgets_create_admin_page()
	{
		$this->jika_widgets_get_options();
		if (
			isset($this->options['jika_widgets_token']) &&
			!empty($this->options['jika_widgets_token']) &&
			isset($_GET) && isset($_GET['nonce']) &&
			wp_verify_nonce(sanitize_text_field(wp_unslash($_GET['nonce'])), 'jika_widgets_nav_nonce') &&
			isset($_GET['tab'])
		) {
			$active_tab = sanitize_text_field($_GET['tab']);
		} else {
			$active_tab = 'overview';
		}
		$jika_widgets_nav_nonce = wp_create_nonce('jika_widgets_nav_nonce')
	?>
		<div class="wrap">
			<h2>Jika Widgets Settings</h2>
			<?php
			if ($this->options['jika_widgets_token']) {
			?>
				<h2 class="nav-tab-wrapper">
					<a href="?page=jika-widgets-settings-overview&tab=overview&nonce=<?php echo esc_attr($jika_widgets_nav_nonce) ?>" class="nav-tab <?php echo $active_tab == 'overview' ? 'nav-tab-active' : ''; ?>">General</a>
					<a href="?page=jika-widgets-settings-overview&tab=plans&nonce=<?php echo esc_attr($jika_widgets_nav_nonce) ?>" class="nav-tab <?php echo $active_tab == 'plans' ? 'nav-tab-active' : ''; ?>">Plans</a>
					<a href="?page=jika-widgets-settings-overview&tab=contact&nonce=<?php echo esc_attr($jika_widgets_nav_nonce) ?>" class="nav-tab <?php echo $active_tab == 'contact' ? 'nav-tab-active' : ''; ?>">Contact</a>
				</h2>
			<?php
			}
			?>
			<div class="jika_widgets_form">
				<form method="post">
					<?php
					$plugin_data = get_plugin_data(__FILE__);
					$plugin_version = $plugin_data['Version'];
					settings_fields('jika_widgets_option_group');
					if ($active_tab == 'overview') {
						do_settings_sections('jika-widgets-settings-overview');
						print '<p style="color: red" id="auth_error"></p>';
						if (empty($this->options['jika_widgets_token'])) {
							if ($this->options['jika_widgets_auth_type'] == 'login') {
								print '
								<button id="auth_submit" type="button" class="button button-primary" name="login">Login</button>
								<div style="display: flex; align-items: center;">
									<p id="auth_toggle_text" style="padding: 0 5px 0 0;">Don\'t have an account?</p>
									<button type="button" id="auth_toggle" name="signup" class="button button-link">Sign Up Now</button>
								</div>
								';
							} else {
								print '
								<button id="auth_submit" type="button" class="button button-primary" name="signup">Sign Up</button>
								<div style="display: flex; align-items: center;">
									<p id="auth_toggle_text" style="padding: 0 5px 0 0;">Already have an account?</p>
									<button type="button" id="auth_toggle" name="login" class="button button-link">Login Now</button>
								</div>
								';
							}
						}
					} else if ($active_tab == 'plans') {
						print '<h3>Change Plan</h3>';
						print '<input type="text" readonly="true" id="plan" name="jika_widgets_options[jika_widgets_plan]" class="hidden">';
					?>
						<div class="jika-widges-plans">
							<?php
							$this->jika_widgets_create_plan_card("free", "Free", $this->options["jika_widgets_plan"] == "free");
							$this->jika_widgets_create_plan_card("business", "Business", $this->options["jika_widgets_plan"] == "business");
							$this->jika_widgets_create_plan_card("enterprise", "Enterprise", $this->options["jika_widgets_plan"] == "enterprise");
							?>
						</div>
					<?php
						// paypal script does not allow ver query arg
						wp_register_script("paypalJS", 'https://www.paypal.com/sdk/js?client-id=' . $this->paypal_client_id . '&components=buttons&vault=true&intent=subscription', array(), null, array('in_footer' => true));
						wp_enqueue_script('paypalJS');
					} else {
						do_settings_sections('jika-widgets-settings-overview');
						print '
						<button id="auth_submit" type="button" class="button button-primary" name="contact">Send</button>
						';
					}

					$admin_css_address = plugins_url("assets/admin.css", __DIR__);
					wp_register_style("JikaWidgetsAdminCSS", $admin_css_address, array(), $plugin_version, false);
					wp_enqueue_style('JikaWidgetsAdminCSS');

					wp_enqueue_script('jquery');

					$this->enqueue_env($plugin_version);
					$js_admin = plugins_url("assets/admin.js", __DIR__);
					$js_admin_title_nonce = wp_create_nonce('jika_widgets_admin_action');
					wp_register_script("JikaWidgetsAdminJS", $js_admin, array(), $plugin_version, false);
					wp_enqueue_script('JikaWidgetsAdminJS');
					wp_localize_script(
						'JikaWidgetsAdminJS',
						'my_ajax_obj',
						array(
							'ajax_url' => admin_url('admin-ajax.php'),
							'nonce' => $js_admin_title_nonce,
							'token' => $this->options['jika_widgets_token'],
						)
					);
					?>
				</form>
				<div class="jika_widgets_paypal_buttons"></div>
			</div>
		</div>
	<?php
	}

	public function jika_widgets_sanitize($input)
	{
		if (!is_array($input)) {
			$input = array();
		}
		$new_input = array();
		$new_input['jika_widgets_email'] = array_key_exists('jika_widgets_email', $input) && isset($input['jika_widgets_email']) ? $input['jika_widgets_email'] : '';
		$new_input['jika_widgets_password'] = array_key_exists('jika_widgets_password', $input) && isset($input['jika_widgets_password']) ? $input['jika_widgets_password'] : '';
		$new_input['jika_widgets_token'] = array_key_exists('jika_widgets_token', $input) && isset($input['jika_widgets_token']) ? $input['jika_widgets_token'] : '';
		$new_input['jika_widgets_plan'] = array_key_exists('jika_widgets_plan', $input) && isset($input['jika_widgets_plan']) ? $input['jika_widgets_plan'] : 'free';
		$new_input['jika_widgets_error'] = array_key_exists('jika_widgets_error', $input) && isset($input['jika_widgets_error']) ? $input['jika_widgets_error'] : '';
		$new_input['jika_widgets_auth_type'] = array_key_exists('jika_widgets_auth_type', $input) && isset($input['jika_widgets_auth_type']) ? $input['jika_widgets_auth_type'] : 'signup';
		$new_input['jika_widgets_trademark'] = array_key_exists('jika_widgets_trademark', $input) && isset($input['jika_widgets_trademark']) ? $input['jika_widgets_trademark'] : '';
		$new_input['jika_widgets_domain'] = array_key_exists('jika_widgets_domain', $input) && isset($input['jika_widgets_domain']) ? $input['jika_widgets_domain'] : '';
		$new_input['jika_widgets_usage'] = array_key_exists('jika_widgets_usage', $input) && isset($input['jika_widgets_usage']) ? $input['jika_widgets_usage'] : '0';
		$new_input['jika_widgets_usage_reset'] = array_key_exists('jika_widgets_usage_reset', $input) && isset($input['jika_widgets_usage_reset']) ? $input['jika_widgets_usage_reset'] : '';
		return $new_input;
	}

	/**
	 * Register and add settings
	 */
	public function jika_widgets_page_init()
	{
		// delete_option('jika_widgets_options');
		// update_option('jika_widgets_options', $this->jika_widgets_sanitize(array()));
		register_setting(
			'jika_widgets_option_group', // Option group
			'jika_widgets_options', // Option name
			array(
				'sanitize_callback' => array($this, 'jika_widgets_sanitize'), // jika_widgets_sanitize
			)
		);
		$this->jika_widgets_get_options();
		if (!empty($this->options['jika_widgets_token'])) {
			try {
				$jika_widgets_sandbox_usage_reset = false;
				global $jika_widgets_env;
				if (
					array_key_exists('ENV', $jika_widgets_env) &&
					$jika_widgets_env['ENV'] == 'development' &&
					isset($_GET) &&
					isset($_GET['nonce']) &&
					wp_verify_nonce(sanitize_text_field(wp_unslash($_GET['nonce'])), 'jika_widgets_nav_nonce') &&
					isset($_GET['sandbox_usage_reset'])
				) {
					$jika_widgets_sandbox_usage_reset = true;
				}
				global $jika_widgets_wp_api;
				$jika_widgets_wp_api->get_user_usage($this->options['jika_widgets_token'], $jika_widgets_sandbox_usage_reset, function ($data) {
					if (isset($data)) {
						if (array_key_exists('usage', $data)) {
							$this->options['jika_widgets_usage'] = $data['usage'];
						} else {
							$this->options['jika_widgets_usage'] = 0;
						}
						if (array_key_exists('usage_reset', $data)) {
							$this->options['jika_widgets_usage_reset'] = $data['usage_reset'];
						}
						$this->jika_widgets_update_options();
					}
				});
			} catch (Exception $error) {
				if ($error->getCode() == 401) {
					$this->options = $this->jika_widgets_sanitize(array());
					$this->jika_widgets_update_options();
				}
			}
		}
		if (
			isset($this->options['jika_widgets_token']) &&
			isset($_GET) &&
			isset($_GET['nonce']) &&
			wp_verify_nonce(sanitize_text_field(wp_unslash($_GET['nonce'])), 'jika_widgets_nav_nonce') &&
			isset($_GET['tab'])
		) {
			$active_tab = sanitize_text_field($_GET['tab']);
		} else {
			$active_tab = 'overview';
		}
		if (empty($this->options['jika_widgets_token'])) {
			add_settings_section(
				'jika_widgets_setting_section_overview', // ID
				'', // Title
				array($this, 'jika_widgets_print_section_empty_app_key_info'), // Callback
				'jika-widgets-settings-overview' // Page
			);

			add_settings_field(
				'jika_widgets_email', // ID
				'Email', // Title
				array($this, 'jika_widgets_email_callback'), // Callback
				'jika-widgets-settings-overview', // Page
				'jika_widgets_setting_section_overview' // Section
			);
			add_settings_field(
				'jika_widgets_password', // ID
				'Password', // Title
				array($this, 'jika_widgets_password_callback'), // Callback
				'jika-widgets-settings-overview', // Page
				'jika_widgets_setting_section_overview', // Section
				array($this->options['jika_widgets_auth_type'] == 'signup')
			);
			if ($this->options['jika_widgets_auth_type'] == 'signup') {
				add_settings_field(
					'jika_widgets_domain', // ID
					'Domain', // Title
					array($this, 'jika_widgets_domain_callback'), // Callback
					'jika-widgets-settings-overview', // Page
					'jika_widgets_setting_section_overview' // Section
				);
			}
		} else {
			add_settings_section(
				'jika_widgets_setting_section_overview', // ID
				'', // Title
				array($this, 'jika_widgets_print_section_info'), // Callback
				'jika-widgets-settings-overview' // Page
			);

			if ($active_tab == 'overview') {
				add_settings_field(
					'jika_widgets_email', // ID
					'Email', // Title
					array($this, 'jika_widgets_email_callback'), // Callback
					'jika-widgets-settings-overview', // Page
					'jika_widgets_setting_section_overview', // Section,
					array(true)
				);
				add_settings_field(
					'jika_widgets_plan', // ID
					'Plan', // Title
					array($this, 'jika_widgets_plan_callback'), // Callback
					'jika-widgets-settings-overview', // Page
					'jika_widgets_setting_section_overview', // Section,
				);
				add_settings_field(
					'jika_widgets_domain', // ID
					'Domain', // Title
					array($this, 'jika_widgets_domain_callback'), // Callback
					'jika-widgets-settings-overview', // Page
					'jika_widgets_setting_section_overview' // Section
				);
				add_settings_field(
					'jika_widgets_usage', // ID
					'Usage', // Title
					array($this, 'jika_widgets_usage_callback'), // Callback
					'jika-widgets-settings-overview', // Page
					'jika_widgets_setting_section_overview', // Section,
				);
				add_settings_field(
					'jika_widgets_trademark', // ID
					'Trademark', // Title
					array($this, 'jika_widgets_trademark_callback'), // Callback
					'jika-widgets-settings-overview', // Page
					'jika_widgets_setting_section_overview', // Section,
				);
			} else if ($active_tab == 'contact') {
				add_settings_field(
					'jika_widgets_email', // ID
					'Email', // Title
					array($this, 'jika_widgets_email_callback'), // Callback
					'jika-widgets-settings-overview', // Page
					'jika_widgets_setting_section_overview', // Section,
				);
				add_settings_field(
					'jika_widgets_domain', // ID
					'Domain', // Title
					array($this, 'jika_widgets_domain_callback'), // Callback
					'jika-widgets-settings-overview', // Page
					'jika_widgets_setting_section_overview' // Section
				);
				add_settings_field(
					'jika_widgets_company_profile', // ID
					'Company Profile', // Title
					array($this, 'jika_widgets_company_profile_callback'), // Callback
					'jika-widgets-settings-overview', // Page
					'jika_widgets_setting_section_overview' // Section
				);
				add_settings_field(
					'jika_widgets_request_type', // ID
					'Request Type', // Title
					array($this, 'jika_widgets_request_type_callback'), // Callback
					'jika-widgets-settings-overview', // Page
					'jika_widgets_setting_section_overview' // Section
				);
				add_settings_field(
					'jika_widgets_note', // ID
					'How can we help you? (optional)', // Title
					array($this, 'jika_widgets_note_callback'), // Callback
					'jika-widgets-settings-overview', // Page
					'jika_widgets_setting_section_overview' // Section
				);
			}
		}
	}

	public function jika_widgets_print_section_empty_app_key_info()
	{
		if ($this->options['jika_widgets_auth_type'] == 'signup') {
			print '<h3>Sign Up</h3>';
		} else {
			print '<h3>Login</h3>';
		}
	}

	public function jika_widgets_print_section_info()
	{
		print '';
	}

	public function jika_widgets_email_callback($readonly = [false])
	{
		printf(
			'<input type="email" id="email" name="jika_widgets_options[jika_widgets_email]" value="%s" placeholder="your@email.com" required=required %s />',
			esc_attr($this->options['jika_widgets_email']),
			array_key_exists(0, $readonly) && $readonly[0] ? 'readonly="true"' : ''
		);
	}
	public function jika_widgets_password_callback($validate = [false])
	{
		printf(
			'<input type="password" id="password" name="jika_widgets_options[jika_widgets_password]" value="%s" placeholder="Password" required=required %s />',
			esc_attr($this->options['jika_widgets_password']),
			array_key_exists(0, $validate) && $validate[0] ? 'minlength="8" pattern="^(?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?!.*\s).*$"' : ''
		);
	}
	public function jika_widgets_usage_callback()
	{
		switch ($this->options['jika_widgets_plan']) {
			case 'enterprise':
				$jika_widgets_usage_limit = 'Unlimited';
				break;
			case 'business':
				$jika_widgets_usage_limit = 5000;
				break;
			case 'free':
			default:
				$jika_widgets_usage_limit = 500;
				break;
		}
		if ($jika_widgets_usage_limit == 'Unlimited') {
			$jika_widgets_usage_calc = 0;
		} else {
			$jika_widgets_usage_calc = intval($this->options['jika_widgets_usage']) / $jika_widgets_usage_limit * 100;
		}
		if ($jika_widgets_usage_calc > 75) {
			$jika_widgets_usage_status = "warn";
		} else {
			$jika_widgets_usage_status = "ok";
		}
	?>
		<div class="jika_widgets_options_usage" data-usage-status="<?php echo esc_attr($jika_widgets_usage_status) ?>">
			<div>
				<input type="text" id="usage" name="jika_widgets_options[jika_widgets_usage]" value="<?php echo esc_attr($this->options['jika_widgets_usage']) ?> out of <?php echo esc_attr($jika_widgets_usage_limit) ?>" readonly="true" data-usage="<?php echo esc_attr($jika_widgets_usage_calc) ?>%" />
				<?php
				if ($jika_widgets_usage_status == "warn") {
				?>
					<p>Usage reset at <?php echo esc_html($this->options['jika_widgets_usage_reset']) ?></p>
				<?php
				}
				?>
			</div>
			<?php
			global $jika_widgets_env;
			if (array_key_exists('ENV', $jika_widgets_env) && $jika_widgets_env['ENV'] == 'development') {
			?>
				<a class="jika_widgets_reset_usage" href="?page=jika-widgets-settings-overview&tab=overview&sandbox_usage_reset=1&nonce=<?php echo esc_attr(wp_create_nonce('jika_widgets_nav_nonce')) ?>">Reset Usage</a>
			<?php
			}
			if ($jika_widgets_usage_status == "warn") {
			?>
				<a href="?page=jika-widgets-settings-overview&tab=plans&nonce=<?php echo esc_attr(wp_create_nonce('jika_widgets_nav_nonce')) ?>">Upgrade Plan</a>
			<?php
			}
			?>
		</div>
<?php
	}

	public function jika_widgets_trademark_callback()
	{
		print '<div class="jika_widgets_trademark">';
		if (!empty($this->options['jika_widgets_trademark'])) {
			printf(
				'<img class="jika_widgets_trademark_preview" src="%s" alt="" height=35 width=35>',
				esc_attr($this->options['jika_widgets_trademark'])
			);
		}
		printf(
			'
			<button type="button" id="trademark-alias" class="button button-primary">Choose File</button>
			<input hidden type="file" id="trademark" name="jika_widgets_options[jika_widgets_trademark]" value="" accept="image/*" />
			</div>',
		);
	}
	public function jika_widgets_domain_callback()
	{
		printf(
			'<input type="text" id="domain" name="jika_widgets_options[jika_widgets_domain]" value="%s" required=required />',
			!empty($this->options['jika_widgets_domain']) ? esc_attr($this->options['jika_widgets_domain']) : esc_attr(explode('/', preg_replace('/https?:\/\//', '', site_url()))[0])
		);
	}
	public function jika_widgets_plan_callback()
	{
		$plans = array(
			'free' => 'Free',
			'business' => 'Business',
			'enterprise' => 'Enterprise'
		);
		printf(
			'<input type="text" id="plan" name="jika_widgets_options[jika_widgets_plan]" value="%s" readonly="true" />
			<a href="?page=jika-widgets-settings-overview&tab=plans&nonce=%s">Change Plan</a>',
			esc_attr($plans[isset($this->options['jika_widgets_plan']) ? $this->options['jika_widgets_plan'] : 'free']),
			esc_attr(wp_create_nonce('jika_widgets_nav_nonce'))
		);
	}

	public function jika_widgets_company_profile_callback()
	{
		print '<select id="company-profile" name="jika_widgets_options[jika_widgets_company_profile]" required="true">
			<option value="" disabled selected>select...</option>
			<option value="financial service">Financial Service</option>
			<option value="public company">Public Company</option>
			<option value="institutional">Institutional</option>
			<option value="media">Media</option>
			<option value="academy">Academy</option>
			<option value="tech provider">Tech Provider</option>
			<option value="blog">Blog</option>
			<option value="ecommerce">Ecommerce</option>
			<option value="other">Other</option>
		</select>';
	}

	public function jika_widgets_request_type_callback()
	{
		print '<select id="request-type" name="jika_widgets_options[jika_widgets_request_type]" placeholder="select..." required="true">
			<option value="" disabled selected>select...</option>
			<option value="enterprise pricing">Enterprise Pricing</option>
			<option value="feature request">Feature Request</option>
			<option value="branding customization">Branding Customization</option>
			<option value="usage limitaions">Usage Limitaions</option>
			<option value="bug report">Bug Report</option>
			<option value="refund request">Refund Request</option>
			<option value="other">Other</option>
		</select>';
	}

	public function jika_widgets_note_callback()
	{
		print '<textarea id="note" name="jika_widgets_options[jika_widgets_note]">
		</textarea>';
	}
}

if (is_admin())
	$jika_widgets_admin_settings = new JikaWidgetsAdminSettings();
