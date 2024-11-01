<?php
/** بسم الله الرحمن الرحيم 
*	الّلهُمَّ صَلِّ عَلی مُحَمَّد وَآلِ مُحَمَّد وَعَجِّل فَرَجَهُم 
*	اَلسَّلامُ عَلَى الْحُسَيْنِ وَ عَلى عَلِىِّ بْنِ الْحُسَيْنِ وَ عَلى اَوْلادِ الْحُسَيْنِ وَ عَلى اَصْحابِ الْحُسَيْنِ
*	Plugin Name: Ziarat Ashura
*	Author: bandekhoda 
*	Author URI: https://profiles.wordpress.org/bandekhoda
*	Version: 1.0.0
* 	Description: Ziarat ashura for English & Arbic & Persian languages.
*	Text Domain: za-lang
*	Domain Path: /lang/
*/
# This plugin is free :
# Please don't use this plugin for purposes of gain and sinful.
# Any misuse of this product violates moral principles of the author.
# این افزونه رایگان است لطفا در مقاصد سودجویانه و گناه آلود استفاده نکنید در غیراینصورت از شما راضی نخواهم بود و شما را به امام حسین و خدا واگذار خواهم کرد
if ( ! defined( 'ABSPATH' ) ) exit;
/**
* Load textdomain
*
* @author       	بنده خدا
*/
define( 'wp_za_lang' , 'za-lang' );
add_action( 'plugins_loaded', 'za_plugin_textdomain' );
function za_plugin_textdomain() {
	load_plugin_textdomain( 'za-lang', false, dirname( plugin_basename( __FILE__ ) ) . '/lang/' );
}	
$dummy_name = __( 'Ziarat Ashura', wp_za_lang );
$dummy_desc = __( 'Ziarat ashura for English & Arbic & Persian languages.', wp_za_lang );
$za_texts =  plugin_dir_path( __FILE__ ) . 'zashura-texts.php';
/**
* Register script and style
*
* @author       	بنده خدا
*/
if(is_file($za_texts)){
add_action('init', 'wp_za_enqueue_script');
function wp_za_enqueue_script(){
	wp_enqueue_script("za_js", plugins_url('js/za-js.js', __FILE__ ), array('jquery'), '1.0.0', true);
}
add_action( 'wp_footer', 'za_text_script' );
function za_text_script() {?>
<script>
jQuery(document).ready(function($){
	$('.za-text').marquee({
		direction: 'up',
		speed: <?php if(get_option('Eh_za_speed')){echo get_option('Eh_za_speed');}else{echo'12000';}?>,
		pauseOnHover: true
	});
});
</script>
<?php }
add_action('wp_enqueue_scripts', 'wp_za_enqueue_style');
function wp_za_enqueue_style() {
	wp_enqueue_style( 'za_css',  plugins_url('css/style.css', __FILE__ ) , '' , '1.0.0' , false);
	}
/**
* Add menu page
*
* @author       	بنده خدا
*/
add_action( 'admin_menu', 'register_za_option_page' );
function register_za_option_page(){
	add_menu_page(__('Ziarat ashura','za-lang'),__('Ziarat ashura','za-lang'),'administrator', basename(__FILE__), 'za_option_panel');
}
/**
* 	Add option panel
*	
*	@author       	بنده خدا
*/
$shortname = "Eh_";	
$ZA_option = array(
	array( 
	"id"	=>	$shortname.'za_width',
	"type"	=>	"text",
	),	
	array( 
	"id"	=>	$shortname.'za_height',
	"type"	=>	"text",
	),	
	array( 
	"id"	=>	$shortname.'za_fontsize',
	"type"	=>	"text",
	),	
	array( 
	"id"	=>	$shortname.'za_speed',
	"type"	=>	"text",
	),	
	array( 
	"id"	=>	$shortname.'za_lang',
	"type"	=>	"select",
	"options"	=> array(
	"en"	=> "English",
	"ar"	=> "Arabic",
	"fa"	=> "عربی + ترجمه فارسی",
	)
	),	
	array( 
	"id"	=>	$shortname.'za_player',
	"type"	=>	"checkbox",
	),	
	array( 
	"id"	=>	$shortname.'za_autoplay',
	"type"	=>	"checkbox",
	),
	array( 
	"id"	=>	$shortname.'za_logo',
	"type"	=>	"checkbox",
	),
);
add_action('admin_init', 'za_option_save_reset');
function za_option_save_reset(){
global $shortname , $ZA_option;
	if ( isset($_REQUEST['action']) && 'save' == $_REQUEST['action'] && check_admin_referer('save-button-nonce' , 'saveoptions') ){foreach ($ZA_option as $value){ update_option( $value['id'], $_POST[ $value['id'] ] ); }
	header("Location: admin.php?page=zashura.php&saved=true");die();} 
	elseif ( isset($_REQUEST['action']) && 'reset' == $_REQUEST['action'] && check_admin_referer('reset-button-nonce' , 'resetoptions') ){foreach ($ZA_option as $value){delete_option( $value['id'] ); }}
}
/**
* 	Add form style
*	
*	@author       	بنده خدا
*/
add_action('admin_head', 'za_option_panel_css');
function za_option_panel_css() {
$patch =  plugins_url('img/za-logo.png',__FILE__ );
?>
  <style>
  .za-ashura-title{margin:10px auto;width:350px;height:300px;}
  .za-ashura-logo{width:300px;height:300px;background:url('<?php echo $patch; ?>');background-repeat:no-repeat;margin:0 auto;}
  .za_option_wrap{margin:15px auto;width:350px;padding:15px;background:#FFFFFF;box-shadow:0 1px 8px rgba(0,0,0,0.3);overflow:hidden}
  .za_option_wrap label{<?php if(is_rtl()){echo'float:right';}else{echo'float:left';}?>;font:11px tahoma}
  .za_item{width:100%;overflow:hidden;margin-bottom:15px}
  .za_item input,.za_item select,.za_item input[type=checkbox]{<?php if(is_rtl()){echo'float:left !important';}else{echo'float:right !important';}?>;width:100px;font:11px tahoma}
  .za_item input[type=checkbox]{width:20px;height:20px;}
  input.button.za-save.button-primary{float:right;margin-top: 30px;}
  .za-reset{float:left;}
  </style>
<?php
}
/**
* 	Add form
*	
*	@author       	بنده خدا
*/
function za_option_panel() {
global $shortname, $ZA_option;
	echo'<div class="za-ashura-title"><div class="za-ashura-logo"></div></div><div class="za_option_wrap"><form method="post">';
	foreach($ZA_option as $value){
	if(get_option( $value['id'])){$opt = get_option( $value['id']);}else{$opt = '';}
	if($value['id'] == 'Eh_za_width'){$name =  __("Div width(px)",wp_za_lang); }
	elseif($value['id'] == 'Eh_za_height'){$name =  __("Div height(px)",wp_za_lang);}
	elseif($value['id'] == 'Eh_za_speed'){$name =  __("Reading speed - Example : 10000",wp_za_lang);}
	elseif($value['id'] == 'Eh_za_lang'){$name =  __("select languages",wp_za_lang);}
	elseif($value['id'] == 'Eh_za_player'){$name =  __("Show player(arabic)",wp_za_lang);}
	elseif($value['id'] == 'Eh_za_fontsize'){$name =  __("Font size(px)",wp_za_lang);}
	elseif($value['id'] == 'Eh_za_autoplay'){$name =  __("Auto play( player )",wp_za_lang);}
	elseif($value['id'] == 'Eh_za_logo'){$name =  __("Ashura logo",wp_za_lang);}
	switch($value['type']){
	case 'text':echo'<div class="za_item"><label for="' . $value['id'] . '>">'. $name .'</label><input name="' . $value['id'] .'" id="' . $value['id'] .'" type="' . $value['type'] .'" value="' . $opt . '"></div>';break;
	case 'select':echo'<div class="za_item"><label for="' . $value['id'] .'">'. $name .'</label><select name="' . $value['id'] .'" id="' . $value['id'] .'">';
	foreach ($value['options'] as $key => $option) {
	if (get_option( $value['id'] ) == $key) { $select = 'selected="selected"';}else{ $select = '';}
	echo'<option ' . $select . ' value="' . $key .'">' . $option . '</option>';} 
	echo'</select></div>';
	break;
	case "checkbox":
	if(get_option($value['id'])){$checked = "checked=\"checked\"";}else{ $checked = "";} 
	echo'<div class="za_item"><label for="' . $value['id'] .'">'. $name .'</label><input name="' . $value['id'] .'" value="0" type="hidden"><input type="checkbox" name="' . $value['id'] .'" id="' .$value['id'] .'" value="true"' . $checked .'></div>';
	break;
	}
	}
	echo '<br><input name="save" type="submit" class="button za-save button-primary" value="'.__('Save changes',wp_za_lang).'"><input type="hidden" name="action" value="save"><input type="hidden" name="saveoptions" value="'. wp_create_nonce('save-button-nonce') .'"></form>';
	echo '<form method="post" class="za-reset"><p class="submit"><input name="reset" type="submit" class="button" value="'. __('Reset',wp_za_lang) .'"><input type="hidden" name="action" value="reset"><input type="hidden" name="resetoptions" value="'. wp_create_nonce('reset-button-nonce') .'"></p></form></div>';
}
/**
* 	Include ziarat ashura texts
*	
*	@author       	بنده خدا
*/
function za_texts(){
	include plugin_dir_path( __FILE__ ) . 'zashura-texts.php';
}
add_shortcode( 'ziarat_ashura', 'za_texts' );
/**
* 	Redirect after active
*	
*	@author       	بنده خدا
*/
register_activation_hook(__FILE__, 'my_plugin_activate');
add_action('admin_init', 'za_plugin_redirect');
function my_plugin_activate() {
    add_option('za_plugin_do_activation_redirect', true);
}
function za_plugin_redirect() {
    if (get_option('za_plugin_do_activation_redirect', false)) {
        delete_option('za_plugin_do_activation_redirect');
        if(!isset($_GET['activate-multi'])) {wp_redirect("admin.php?page=zashura.php");}
    }
}
/**
* 	Ziarat ashura player
*	
*	@author       	بنده خدا
*/
function za_player(){
	$width	= get_option('Eh_za_width');
	$height = get_option('Eh_za_height');
	$auto	= get_option('Eh_za_autoplay');
	$url	= plugins_url('ziarat_ashura_farahmand.mp3',__FILE__ );
?>
	<div class="za-player" <?php if($width || $height){?>style="<?php if($width){?>width:<?php echo $width;?>px;<?php } if($height){?>height:<?php echo $height;?>px<?php }?>"<?php }?>>
	<audio controls <?php if($auto){echo'autoplay';}?>>
	<source src="<?php echo $url;?>" type="audio/mpeg">
	</audio>
	</div>
<?php
}
/**
* 	Ziarat ashura logo
*	
*	@author       	بنده خدا
*/
function za_logo(){
	if(get_option('Eh_za_logo')){
	echo '<div class="za-title"></div>';
	}else{
	echo'';
	}
}
}
?>