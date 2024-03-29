<?php
/*
* Plugin Name: Kyano Forms
* Plugin URI: https://kyano.app/forms
* Description: Kyano Forms
* Version: 1.0.0
* Author: Kyano BV
* Text Domain: kyano-forms
* Domain Path: /languages
* Author URI: https://kyano.app
* Requires at least: 5.6
* Tested up to: 6.1
*/

if (!defined('ABSPATH')) exit;

define('KYANO_FORMS_CURRENT_VERSION', '1.0.0');
define('KYANO_FORMS_PLUGIN_URL', plugin_dir_url(__FILE__));
define('KYANO_FORMS_PLUGIN_PATH', plugin_dir_path(__FILE__));


add_action('admin_menu', 'kyano_forms_admin_settings_page');
add_shortcode('kyano_forms', 'kyano_forms_shortcode');
add_action('wp_footer', 'kyano_forms_assets', 100);

/**
 * Multilanguage function
 * @since 1.0.0
 */
if (!function_exists(' kyano_forms_load_textdomain')) {
	function kyano_forms_load_textdomain() {
		if (is_textdomain_loaded('kyano-forms')) {
			return;
		}

		load_theme_textdomain('kyano-forms', dirname(__FILE__) . '/languages');
	}
}



/**
 * Register admin page
 * @since 1.0.0
 */
if (!function_exists('kyano_forms_admin_settings_page')) {
	function kyano_forms_admin_settings_page() {
		add_menu_page(
			__('Forms', 'kyano-forms'),
			'Forms',
			'manage_options',
			'kyano-forms',
			'kyano_forms_settings_page_admin',
			'',
			10
		);
	}
}


/**
 * Adds styles
 * @since 1.0.0
 */
if (isset($_GET['page'])) {
	if ($_GET['page'] == 'kyano-forms') {
		add_action('admin_enqueue_scripts', 'kyano_forms_add_styles', 0);
	}
}

/**
 * Register admin page styles
 * @since 1.0.0
 */
if (!function_exists('kyano_forms_add_styles')) {
	function kyano_forms_add_styles() {
		$plugin_url = plugin_dir_url(__FILE__);

		wp_register_style('kyano-forms-admin-styles', $plugin_url . 'assets/css/app.min.css');
		wp_enqueue_style('kyano-forms-admin-styles');
	}
}

/**
 * Register forms settings in admin
 * @since 1.0.0
 */
add_action('admin_init',  'kyano_forms_register_settings_field');
if (!function_exists('kyano_forms_register_settings_field')) {
	function kyano_forms_register_settings_field() {
		register_setting('kyano_forms', 'kyano_forms_fa_active');
		register_setting('kyano_forms', 'kyano_forms_md_active');
		register_setting('kyano_forms', 'kyano_forms_beta_active');
	}
}

/**
 * Admin page
 * @since 1.0.0
 */
if (!function_exists('kyano_forms_settings_page_admin')) {
	function kyano_forms_settings_page_admin() { ?>
		<section class="kyano-forms-admin wrap">
			<div class="kyano-forms-container-small">
				<div class="kyano-forms-card">
					<div class="kyano-forms-container-small">
						<img class="kyano-forms-brand" src="https://cdn.kyano.app/img/kyano-forms-icon-logo-dark.svg" alt="Kyano Forms" title="Kyano Forms logo">
						<div class="text-center">
							<h2><?php echo __('Build beter contact forms.', 'kyano-forms'); ?></h2>
							<p><?php echo __('With Kyano Forms you can create forms for your WordPress website.
In our powerful form builder you have your own form within a few clicks.
Use our shortcode to display your form on your website.', 'kyano-forms'); ?></p>
						</div>
					</div>
				</div>
				<div class="kyano-forms-card">
					<h4><?php echo __('Settings', 'kyano-forms'); ?></h4>
					<form method="post" action="options.php">
						<?php settings_fields('kyano_forms');
						do_settings_sections('kyano_forms'); ?>
						<table>
							<tr>
								<td>Forms Beta </td>
								<td><label class="" for="kyano_forms_beta_active">
										<input name="kyano_forms_beta_active" type="checkbox" value="1" <?php checked('1', get_option('kyano_forms_beta_active')); ?> />
									</label>
								</td>
							</tr>
							<tr>
								<td>Fontawesome icons support</td>
								<td><label class="" for="kyano_forms_fa_active">
										<input name="kyano_forms_fa_active" type="checkbox" value="1" <?php checked('1', get_option('kyano_forms_fa_active')); ?> />
									</label>
								</td>
							</tr>
							<tr>
								<td>Material design icons support</td>
								<td><label class="" for="kyano_forms_md_active">
										<input name="kyano_forms_md_active" type="checkbox" value="1" <?php checked('1', get_option('kyano_forms_md_active')); ?> />
									</label>
								</td>
							</tr>
						</table>



						<?php submit_button(__('Save', 'kyano-sites'), 'uk-button button-primary uk-button-primary'); ?>

					</form>
				</div>
				<div class="kyano-forms-card">
					<div class="text-center py-40px">
						<h4><?php echo __('Show your form with a simple shortcode', 'kyano-forms'); ?></h4>
						<p><?php echo __('Place your API key of your form in the shortcode. This can be found in Kyano Forms builder. Dont use the dubble quotes in the shortcode.', 'kyano-forms'); ?></p>
						<input class="kyano-forms-shortcode-input" type="text" value="[kyano_forms api_key=form-api-key]">
						<a class="kf-btn-primary mt-30px" target="_blank" title="<?php echo __('New form', 'kyano-forms'); ?>" href="https://forms.kyano.app/"><?php echo __('New form', 'kyano-forms'); ?></a>
					</div>
				</div>
			</div>
		</section>
<?php
	}
}



/**
 * Kyano forms shortcode
 * @since 1.0.0
 */
if (!function_exists('kyano_forms_shortcode')) {
	function kyano_forms_shortcode($attr) {

		$args = shortcode_atts(
			array(
				'api_key' => '',
			),
			$attr
		);
		$r = '<show-form api_key=' . $args['api_key'] . '/>';
		return $r;
	}
}



/**
 * Load assets in front-end only where the kyano_forms shortcode is.
 * @since 1.0.0
 */
if (!function_exists('kyano_forms_assets')) {
	function kyano_forms_assets() {
		if (get_option('kyano_forms_beta_active') == 1) {
			echo '<link rel="stylesheet" href="https://cdn.kyano.app/product/frms/embedv2.min.css" />';
			echo '<script defer src="https://cdn.kyano.app/product/frms/embedv2.js"></script>';
		} else {
			echo '<link rel="stylesheet" href="https://cdn.kyano.app/product/frms/embed.min.css" />';
			echo '<script defer src="https://cdn.kyano.app/product/frms/embed.js"></script>';
		}

		if (get_option('kyano_forms_fa_active') == 1) {
			echo '<link rel="stylesheet" href="https://pro.fontawesome.com/releases/v5.15.4/css/all.css" />';
		}
		if (get_option('kyano_forms_md_active') == 1) {
			echo '<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@mdi/font@latest/css/materialdesignicons.min.css" />';
		}
	}
}
