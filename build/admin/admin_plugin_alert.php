<?php
if (!defined('ABSPATH')) exit; // Exit if accessed directly
?>
<div class="notice" style="padding: 0; margin: 0; border: none; background: none;">
	<form id="jika_widgets_activate" name="jika_widgets_activate" action="<?php echo esc_url(JikaWidgetsAdminSettings::get_page_url()); ?>" method="POST" style="padding: 15px 0; background: #1652F0;">
		<div class="jika_activate" style="display: flex; align-items: center;">
			<h3 style="color: white; margin: 0 0 0 35px">
				Jika Stock Market Widgets
			</h3>
			<div class="jika_link_container">
				<a class="button" href="#" onclick="document.jika_widgets_activate.submit();" style="color: 1652F0; padding: 5px 15px; margin: 0 0 0 15px">
					<strong>
						Create an account to activate your premium features
					</strong>
				</a>
			</div>
		</div>
	</form>
</div>
