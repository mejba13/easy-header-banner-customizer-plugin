<?php

/*
Plugin Name: Easy Header Banner Customizer
Description: 
Display Page Header banners easily, banners widget, banners before or after post content automatically, banners between post content, and more!.
Plugin URI: https://mejba.makpie.com/
Version: 1.0.0
Author: Mejba Ahmed
Author URI: https://mejba.makpie.com/
Donate link: https://mejba.makpie.com/
Requires at least: 4.1
Tested up to: 5.0.3
Text Domain: easy-header
Copyright: � 2019 Mejba Plugins.
 */


//Exit if accessed directly .preventing any unauthorized access in this plugin
if( !defined('ABSPATH') ) {
	exit;
}

class custom_setting{

	public $options;
	public function __construct(){
		$this->options = get_option('section');
		$this->display_theme_panel_fields();
	}

	public static function add_theme_menu_item()
	{
		add_theme_page("Header banner", "Header banner", "manage_options", __FILE__, array('custom_setting',"theme_settings_page"),'dashicons-format-image',27);
	}

	public function theme_settings_page()
	{
	    ?>
		<div class="wrap">
			<h2 class="wp-menu-image dashicons-before dashicons-admin-settings" style="padding: 5px;"> 
				<?php esc_attr_e('Header bannger Setting');//Displays translated text ?>
			</h2>
			
			<form method="POST" action="options.php" enctype="multipart/form-data">
				<?php  settings_fields('section'); //settings group name  ?>
				<?php  do_settings_sections(__FILE__); // the slug name of the page, do_settings_sections = Prints out all settings sections added to a particular settings page.  ?>

				<p class="submit">
					<input name="submit" type="submit" class="button-primary" value="Save Changes" />
				</p>
			</form>
		</div>

		<?php
	}

	//register setting

	public function display_theme_panel_fields()
	{
		register_setting("section", "section",array($this,"handle_uploaded_banner")); // option group name, option name

		add_settings_section('custom_section','Header Banner Settings',array($this,'main_section_cb'),__FILE__); //id, title of section, cb, which page?
		
	   add_settings_field('header-banner','Header banner ',array($this,'custom_banner_setting'),__FILE__,'custom_section'); // id ,Title of the field,cd,which page,section
	}

	 public function main_section_cb(){
		// optional
	}
    
    public function handle_uploaded_banner($plugin_options){

    	if(!empty($_FILES['header-banner']['tmp_name'])){
    		$override = array('test_form' => false);
    		$file = wp_handle_upload($_FILES['header-banner'],$override);
    		$plugin_options['header-banner'] = $file['url'];

    	}else{
    		$plugin_options['header-banner'] = $this->options['header-banner'];
    	}
    	return $plugin_options;
    }

	public function custom_banner_setting() {
	    echo '<input type="file" name="header-banner" /> <br /> <br />' ;
	    if( isset($this->options['header-banner']) ){
	    	echo "<img src='{$this->options['header-banner']}' alt='' />";
	    }
	}
}

add_action('admin_menu',function(){
	custom_setting::add_theme_menu_item();  // static method use

});

// Object intinization
add_action('admin_init',function(){
	new custom_setting();
});


function header_banner_calback() {
	$header_section = get_option( 'section' );
	$header_banner  = $header_section['header-banner'];
	return $header_banner;
}
add_shortcode( 'header_banner_image', 'header_banner_calback');








