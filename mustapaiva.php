<?php
/**
 * Plugin Name: Internetin musta päivä
 * Plugin URI: https://www.facebook.com/events/160986290729976/
 * Description: Lisää sivullesi SC5:en tekemän koodin jolla osallistut Internetin mustaan päivään. Voit huoletta asentaa ja aktivoida lisäosan jo nyt, se muuttaa sivustoasi vasta kampanjapäivänä 23.4. Muokkaa asetuksia 'Asetukset' -välilehdellä 
 * Version: 1.2
 * Author: Timi Wahalahti
 * Author URI: http://wahalahti.fi
 */


add_action('admin_init', 'mustapaiva_opt_init_fn' );
add_action('admin_menu', 'mustapaiva_opt_add_page_fn');

function mustapaiva_opt_init_fn(){
	register_setting('mustapaiva_options', 'mustapaiva_options', 'mustapaiva_options_validate' );
	add_settings_section('main_section', 'Asetukset', 'section_text_fn', __FILE__);
	add_settings_field('only', 'Näytä joka päivä:<br><small>Oletuksena näytetään vain 23.4.</small>', 'setting_string_only', __FILE__, 'main_section');
	add_settings_field('once', 'Näytä useammin kuin kerran:<br><small>Oletuksena näytetään vain kerran</small>', 'setting_string_once', __FILE__, 'main_section');
	add_settings_field('title', 'Otsikko:', 'setting_string_title', __FILE__, 'main_section');
	add_settings_field('big_text', 'Iso teksti:', 'setting_string_big_text', __FILE__, 'main_section');
	add_settings_field('small_text', 'Pieni teksti:', 'setting_string_small_text', __FILE__, 'main_section');
	add_settings_field('count_text', 'Allekirjoittajien määrä<br><small>Teksti joka lisätään ennen allekirjoittajien määrää</small>', 'setting_string_count_text', __FILE__, 'main_section');
	add_settings_field('close_text', 'Sulje -teksti:', 'setting_string_close_text', __FILE__, 'main_section');
	add_settings_field('coder', 'Näytä tekijät:<br><small>Näytä ikkunan alaosassa tekijät</small>', 'setting_string_coder', __FILE__, 'main_section');
}

function mustapaiva_opt_add_page_fn() {
	add_options_page('Internetin musta päivä', 'Internetin musta päivä', 'administrator', __FILE__, 'mustapaiva_opt_page_fn');
}

function  section_text_fn() {
	echo "<p>Oletusarvoisesti koodi aktivoituu vain kampanjapäivänä 23.4. ja näkyy jokaiselle sivuston kävijälle vain kerran.</p>";
	echo '<p>Jos haluat, voit muokata tekstejä jotka näkyy kampanjapäivänä.<br>Voit käyttää HTML-muotoilua, esimerkiksi linkki lisätään näin: <noscript><a href="https://www.kansalaisaloite.fi/fi/aloite/70" target="_blank">kansalaisaloite</a></noscript>. </p>';
	echo "<p>Jos muokkaat tekstejä, aktivoi 'Näytä joka päivä' ja tarkista muutokset.</p>";
}
function setting_string_only() {
	$options = get_option('mustapaiva_options');
	echo "<input type='checkbox' name='mustapaiva_options[only]'' value='1'" .checked( isset( $options['only'] ), true, false ). "/>";
}
function setting_string_once() {
	$options = get_option('mustapaiva_options');
	echo "<input type='checkbox' name='mustapaiva_options[once]'' value='1'" .checked( isset( $options['once'] ), true, false ). "/>";
}
function setting_string_title() {
	$options = get_option('mustapaiva_options');
	echo "<input id='title' name='mustapaiva_options[title]' size='40' type='text' value='{$options['title']}' />";
}
function setting_string_big_text() {
	$options = get_option('mustapaiva_options');
	echo "<input id='big_text' name='mustapaiva_options[big_text]' size='40' type='text' value='{$options['big_text']}' />";
}
function setting_string_small_text() {
	$options = get_option('mustapaiva_options');
	echo "<input id='small_text' name='mustapaiva_options[small_text]' size='40' type='text' value='{$options['small_text']}' />";
}
function setting_string_count_text() {
	$options = get_option('mustapaiva_options');
	echo "<input id='count_text' name='mustapaiva_options[count_text]' size='40' type='text' value='{$options['count_text']}' />";
}
function setting_string_close_text() {
	$options = get_option('mustapaiva_options');
	echo "<input id='close_text' name='mustapaiva_options[close_text]' size='40' type='text' value='{$options['close_text']}' />";
}
function setting_string_coder() {
	$options = get_option('mustapaiva_options');
	echo "<input type='checkbox' name='mustapaiva_options[coder]'' value='1'" .checked( isset( $options['coder'] ), true, false ). "/>";
}

function mustapaiva_opt_page_fn() {
?>
	<div class="wrap">
		<div class="icon32" id="icon-options-general"><br></div>
		<h2>Internetin musta päivä</h2>
		<form action="options.php" method="post">
		<?php settings_fields('mustapaiva_options'); ?>
		<?php do_settings_sections(__FILE__); ?>
		<p class="submit">
			<input name="Submit" type="submit" class="button-primary" value="<?php esc_attr_e('Save Changes'); ?>" />
		</p>
		</form>
	</div>
<?php
}

function mustapaiva_options_validate($input) {
	$input['title'] =  wp_kses($input['title']);
	$input['big_text'] =  wp_kses($input['big_text']);
	$input['small_text'] =  wp_kses($input['small_text']);
	$input['count_text'] =  wp_kses($input['count_text']);
	$input['close_text'] =  wp_kses($input['close_text']);	
	return $input;
}

function mustapaiva_add() {
$options = get_option('mustapaiva_options');
echo "<script type='text/javascript' src='" .plugins_url( 'mustapaiva.js' , __FILE__ ). "' charset='UTF-8'></script>";

if (isset($options['only'] )) {
	$jsopt = "onCampaignDayOnly: false, ";
}
if (isset( $options['once'] )) {
	$jsopt .= "showOnlyOnce: false, ";
}
if (isset( $options['coder'] )) {
	$jsopt .= "showCoders: true, ";
}
if (!empty($options['title'])) {
	$jsopt .= "title: '". $options['title']. "', ";
}
if (!empty($options['big_text'])) {
	$jsopt .= "bigText: '". $options['big_text']. "', ";
}
if (!empty($options['small_text'])) {
	$jsopt .= "smallText: '". $options['small_text']. "', ";
}
if (!empty($options['count_text'])) {
	$jsopt .= "countText: '". $options['count_text']. "', ";
}
if (!empty($options['close_text'])) {
	$jsopt .= "closeText: '". $options['close_text']. "'";
}

?>
<script>
copyrightCampaign({ <?php echo $jsopt; ?> });
</script>
<?php
}
add_action('wp_head', 'mustapaiva_add');
?>