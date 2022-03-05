<?php
/*
* Plugin Name: Kyano Forms 2022
* Plugin URI: https://kyano.app/forms
* Description: Kyano Forms
* Version: 1.0.0
* Author: Kyano BV
* Text Domain: kyano-forms
* Domain Path: /languages
* Author URI: https://kyano.app
* Requires at least: 5.6
* Tested up to: 5.7.1
*/

if (!defined('ABSPATH')) exit;

define('KYANO_FORMS_CURRENT_VERSION', '1.0.0');
define('KYANO_FORMS_PLUGIN_URL', plugin_dir_url(__FILE__));
define('KYANO_FORMS_PLUGIN_PATH', plugin_dir_path(__FILE__));


add_action('admin_menu', 'kyano_forms_admin_settings_page');
add_shortcode('kyano_forms', 'kyano_forms_shortcode');
add_action('wp_head', 'kyano_forms_assets');

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
 * Admin page
 * @since 1.0.0
 */
if (!function_exists('kyano_forms_settings_page_admin')) {
	function kyano_forms_settings_page_admin() { ?>
		<section class="kyano-forms-admin wrap">
			<div class="kyano-forms-card">
				<div class="kyano-forms-container-small">
					<img class="kyano-forms-brand" src="https://cdn.kyano.app/img/kyano-forms-icon-logo-dark.svg" alt="Kyano Forms" title="Kyano Forms logo">
					<div class="text-center">
						<h2><?php echo esc_html('Build beter contact forms.', 'kyano-forms'); ?></h2>
						<p><?php echo esc_html('Met Kyano Forms maak je formulieren voor op je WordPress website. 
In onze krachtige formulieren builder heb je binnen enkele klikken je eigen formulier.
Maak gebruik van onze shortcode om je formulier op je website te tonen.', 'kyano-forms'); ?></p>
					</div>

					<div class="text-center py-40px">
						<h4><?php echo esc_html('Toon je formulier met een simpele shortcode', 'kyano-forms'); ?></h4>
						<p><?php echo esc_html('Plaats je API key van je formulier in de shortcode. Dit is te vinden in Kyano Forms builder.', 'kyano-forms'); ?></p>
						<input class="kyano-forms-shortcode-input" type="text" value="[kyano_forms api_key=“form-api-key”]">
						<a class="kf-btn-primary mt-30px" target="_blank" title="<?php echo esc_html('New form', 'kyano-forms'); ?>" href="https://forms.kyano.app/"><?php echo esc_html('New form', 'kyano-forms'); ?></a>
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
		global $post;

		if (has_shortcode($post->post_content, 'kyano_forms')) {

			wp_register_style('kyano-forms-styles', 'https://cdn.kyano.app/product/frms/app.min.css');
			wp_enqueue_style('kyano-forms-styles');

			wp_register_script('kyano-forms-script', 'https://cdn.kyano.app/product/frms/app.js', '', '', true);
			wp_enqueue_scripts('kyano-forms-script');
		}
	}
}
