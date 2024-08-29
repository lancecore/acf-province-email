<?php
/**
 * Plugin Name: ACF Provincial Email Sender
 * Description: A plugin that generates a select box and a link to send emails based on ACF data for each Canadian province/territory. Use [acf_province_email context="parents"] shortcode.
 * Version: 1.5
 * Author: Lance Boer
 * Author URI: https://lanceboer.com
 * Plugin URI: https://github.com/lancecore/acf-province-email
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

// Ensure ACF is loaded
add_action('admin_menu', 'my_acf_options_page');

function my_acf_options_page() {
	if ( function_exists('acf_add_options_page') ) {
		acf_add_options_page(array(
			'page_title'    => 'Clean Air in Schools Campaign Email Settings',
			'menu_title'    => 'CAiS Emails',
			'menu_slug'     => 'cais-email-settings',
			'capability'    => 'manage_options',
			'redirect'      => false,
			'position'      => 90,
			'icon_url'      => 'dashicons-email-alt2',
		));
	}
}

// Enqueue scripts and styles
function acf_province_email_enqueue_scripts() {
	wp_enqueue_script( 'acf-province-email-js', plugin_dir_url( __FILE__ ) . 'acf-province-email.js', array( 'jquery' ), null, true );
}
add_action( 'wp_enqueue_scripts', 'acf_province_email_enqueue_scripts' );

// Shortcode to display the select box and link
function acf_province_email_shortcode( $atts ) {
	// Shortcode attributes
	$atts = shortcode_atts( array(
		'context' => 'parents', // Default context
	), $atts, 'acf_province_email' );

	// Determine the field group based on context
	$context = sanitize_text_field( $atts['context'] );
	$field_group = $context . '_data'; // E.g., 'parents_data' or 'teachers_data'

	ob_start();
	?>
	<select id="province-select-<?php echo esc_attr( $context ); ?>">
		<option value="">Select a Province/Territory</option>
		<?php
		// Fetch all provinces/territories and their data based on context
		if( have_rows($field_group, 'option') ) :
			while( have_rows($field_group, 'option') ) : the_row();
				$province_name = get_sub_field('province_territory');
				echo '<option value="' . esc_attr($province_name) . '">' . esc_html($province_name) . '</option>';
			endwhile;
		endif;
		?>
	</select>

	<a href="#" id="send-email-link-<?php echo esc_attr( $context ); ?>" style="display:none;">Send Email Now</a>

	<script>
		window.provinceData = window.provinceData || {};

		// Initialize 'parents' data
		<?php
		if (have_rows('parents_data', 'option')) :
			echo 'window.provinceData["parents"] = {};';
			while (have_rows('parents_data', 'option')) : the_row();
				$province_name = get_sub_field('province_territory');
				$emails = [];
				if (have_rows('email_addresses')) :
					while (have_rows('email_addresses')) : the_row();
						$emails[] = get_sub_field('email_address');
					endwhile;
				endif;
				$text_content = get_sub_field('text_content');
				$cc_address = get_sub_field('cc_address');
				$subject = get_sub_field('subject');
				?>
				window.provinceData["parents"]['<?php echo esc_js($province_name); ?>'] = {
					emails: <?php echo json_encode($emails); ?>,
					text: <?php echo json_encode($text_content); ?>,
					cc_address: <?php echo json_encode($cc_address); ?>,
					subject: <?php echo json_encode($subject); ?>
				};
				<?php
			endwhile;
		endif;
		?>

		// Initialize 'teachers' data
		<?php
		if (have_rows('teachers_data', 'option')) :
			echo 'window.provinceData["teachers"] = {};';
			while (have_rows('teachers_data', 'option')) : the_row();
				$province_name = get_sub_field('province_territory');
				$emails = [];
				if (have_rows('email_addresses')) :
					while (have_rows('email_addresses')) : the_row();
						$emails[] = get_sub_field('email_address');
					endwhile;
				endif;
				$text_content = get_sub_field('text_content');
				$cc_address = get_sub_field('cc_address');
				$subject = get_sub_field('subject');
				?>
				window.provinceData["teachers"]['<?php echo esc_js($province_name); ?>'] = {
					emails: <?php echo json_encode($emails); ?>,
					text: <?php echo json_encode($text_content); ?>,
					cc_address: <?php echo json_encode($cc_address); ?>,
					subject: <?php echo json_encode($subject); ?>
				};
				<?php
			endwhile;
		endif;
		?>
	</script>

	<?php
	return ob_get_clean();
}
add_shortcode( 'acf_province_email', 'acf_province_email_shortcode' );

?>
