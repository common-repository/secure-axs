<?php

/*
Plugin Name: Secure AXS
Plugin URI: https://wordpress.org/plugins/secure-axs/
Description: Block spam login, brute force attacks, and spam registration by changing default WordPress login URL and integrating Google reCAPTCHA. Secure AXS blocks access to default login url, generates a custom branded login panel (Which you can change colors and image).
Version: 1.3.4
Author: Motaz Elshazly
Author URI: http://twitter.com/motaz_shazly
License: GPL2
*/
/*
Copyright 2016  Motaz Elshazly (email : motazshazly@gmail.com)

This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License, version 2, as
published by the Free Software Foundation.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.
*/

if(!class_exists('SecureAxs')){

Class SecureAxs{

    public $perma_link;
    public $secure_axs;
    public $actual_link;
    public $users_can_register;
    public $axs_level;
    public $gcaptcha_key;
    public $gcaptcha_secret;

    public function __construct() {

        // WP Actions
        add_action( 'activated_plugin', array( $this, 'secure_axs_activate' ) );
        if( is_admin() ):
	      add_action( 'wp_enqueue_scripts', array( 'wp-color-picker' ) );
        endif;

        add_action( 'admin_menu', array( $this, 'add_menu' ) );
        add_action( 'init', array( $this, 'display_login' ) ); // Display Plugin's login panel when the defined url is called
        if( isset( $_POST['g-recaptcha-response'] ) && isset( $_POST['axs_key'])  && $_POST['axs_key'] == 'axs-login' ):
        add_action( 'init', array( $this, 'axs_signon' ) ); // Process login information
        endif;
        add_action( 'admin_enqueue_scripts', array( $this, 'register_plugin_scripts' ) );
        add_action( 'init', array( $this, 'block_default_login' ) ); // Block access to default WP login URLs.

        // Secure Register
        add_action( 'register_form', array( $this, 'axs_register_recaptcha') );
        add_filter( 'registration_errors', array( $this, 'axs_register_recaptcha_vaidation' ) );


        // Defining properties
        $this->perma_link = self::retrieve_settings('permalink_structure');
        if ( $this->perma_link != NULL ): $this->perma_link = NULL; else: $this->perma_link = '?'; endif;
        $this->secure_axs = self::retrieve_settings('secure_axs');
        $this->actual_link = self::current_url();
        $this->users_can_register = self::retrieve_settings('users_can_register');
        $this->gcaptcha_key = $this->secure_axs['gcaptcha_key'];
        $this->gcaptcha_secret = $this->secure_axs['gcaptcha_secret'];

        if( $this->actual_link == get_bloginfo('url') . '/'. $this->perma_link  . $this->secure_axs['axs_url'] . '/' || $this->actual_link == get_bloginfo('url') . '/' . $this->perma_link  . $this->secure_axs['axs_url'] ) {
        add_action( 'send_headers', 'no_cache_headers' );
        }

		if( $this->secure_axs['allow_editors'] == 'on' ):  $this->axs_level = 'publish_pages'; else:  $this->axs_level ='manage_options'; endif;

		if( $this->gcaptcha_key == NULL &&  $this->actual_link != get_bloginfo('url') . '/wp-admin/admin.php?page=secure_axs' ):
			add_action( 'admin_notices', array( $this, 'axs_activation_notice' ) );
		endif;

        if ( $this->secure_axs['axs-permalink'] !=  $this->perma_link ):
            add_action( 'admin_notices', array( $this, 'axs_permalink_notice' ) );
            $this->secure_axs['axs-permalink'] = $this->perma_link;
			update_option('secure_axs', $this->secure_axs);
        endif;

        if ( ( $this->gcaptcha_key == NULL || $this->gcaptcha_secret == NULL ) && ( $this->actual_link == get_bloginfo('url') . '/wp-admin/admin.php?page=secure_axs') ):
			add_action( 'admin_notices', array( $this, 'axs_gcaptcha_notice' ) );
        endif;

    }

    // Notices
    public function axs_activation_notice(){

        echo '<div class="notice notice-error">
            <p><strong>IMPORTANT:</strong> <a href="'.get_bloginfo('url').'/wp-admin/admin.php?page=secure_axs">Click here</a> to configure your new login URL now.</p>
            </div>';

    }

    public function axs_permalink_notice(){

        echo '<div class="update-nag notice is-dismissible">
            <p>You <strong>MUST</strong> check your new Secure AXS login after permalink structure change, <a href="'.get_bloginfo('url').'/wp-admin/admin.php?page=secure_axs">Click here to check your new login URL.</a></p>
            </div>';

    }

    public function axs_gcaptcha_notice(){

        echo '<div class="notice notice-error">
            <p>You <strong>MUST</strong> add your free google reCATCHA API keys to use Secure AXS, claim your API Keys now from <a href="https://www.google.com/recaptcha/admin" target="_blank">https://www.google.com/recaptcha/admin</a></p>
            </div>';

    }

    public function retrieve_settings( $optkey ){

        $secure_axs = get_option( $optkey );

        return $secure_axs;
    }

    public function axs_register_recaptcha() {

    //Get and set any values already sent
    $_POST['g-recaptcha-response'] = ( isset( $_POST['g-recaptcha-response'] ) ) ? $_POST['g-recaptcha-response'] : '';


    echo '<div class="g-recaptcha" data-sitekey="' . $this->gcaptcha_key . '" style="transform:scale(0.90);-webkit-transform:scale(0.90);transform-origin:0 0;-webkit-transform-origin:0 0;"></div>
         <script type="text/javascript"
                    src="https://www.google.com/recaptcha/api.js?hl=en">
         </script>';

	}

	function axs_register_recaptcha_vaidation( $errors, $sanitized_user_login, $user_email ) {

		if( isset($_POST['g-recaptcha-response']) ):
			require_once __DIR__ . '/gCaptcha/autoload.php';

			$recaptcha = new \ReCaptcha\ReCaptcha($this->gcaptcha_secret);
			$resp = $recaptcha->verify($_POST['g-recaptcha-response'], $_SERVER['REMOTE_ADDR']);
			if( !$resp->isSuccess() ):
				 $errors->add( 'reCAPTCHA Error', __( '<strong>reCAPTCHA ERROR</strong>: Bots are not allowed.', 'Secure-AXS' ) );
			endif;

		endif;

		return $errors;
	}

    // Send no-cache to login headers
    public function no_cache_headers(){

            header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
            header("Cache-Control: post-check=0, pre-check=0", false);
            header("Pragma: no-cache");

	}


    public function register_plugin_scripts() {

        // Load the script for dashboard use only.
        if ( is_admin () ):

        // Add the color picker css file
        wp_enqueue_style( 'wp-color-picker' );

        // Include our custom jQuery file with WordPress Color Picker dependency
        wp_enqueue_script( 'axs-script-handle', plugins_url( '/js/axs-scripts.js', __FILE__ ), array( 'wp-color-picker' ), '0.2', true );

        // Load upload engine
        wp_enqueue_media();
		      wp_enqueue_script('thickbox');
		      wp_enqueue_script('media-upload');
    	   wp_enqueue_style('thickbox');

        endif;
    }

    // Add_options_page
    public function add_menu(){

        add_menu_page('Secure Access', 'Secure AXS', $this->axs_level, 'secure_axs', array( $this, 'plugin_settings_page'), 'dashicons-shield' );
    }

    public function plugin_settings_page(){

        if(!current_user_can( $this->axs_level )):

            wp_die(__('You do not have sufficient permissions to access this page.', 'Secure_Axs'));
        else:

        // Render the settings template
        include(sprintf("%s/settings.php", dirname(__FILE__)));

        endif;
    }

    public function validate_settings( $fields ) {

                $sanitized_fields = array();

                foreach($fields as $key => $val):


                   if($key != "Submit"):

                            if($key == 'axs_url' && empty($val) ) :

                                // Set default value
                                $val = 'axs-login';

                                $val = sanitize_title_with_dashes( $val );
                                // Set the error message
                                add_settings_error( 'axs_settings_options', 'axs_bg_error', 'Axs URL cannot be empty "axs-login" was applies instead.', 'error' ); // $setting, $code, $message, $type


                            elseif ( $key == 'bg_color' &&  FALSE === self::check_color( $val ) ) :

                                // Set default value
                                $val = '#cccccc';

                                // Set the error message
                                add_settings_error( 'axs_settings_options', 'axs_bg_error', 'Insert a valid color for Background', 'error' ); // $setting, $code, $message, $type

                            elseif ( $key == 'text_color' &&  FALSE === self::check_color( $val ) ) :

                                // Set default value
                                $val = '#000000';

                                // Set the error message
                                add_settings_error( 'axs_settings_options', 'axs_bg_error', 'Insert a valid color for text', 'error' ); // $setting, $code, $message, $type

                            endif;

                            // Sanitize Value
                            $val = sanitize_text_field( $val );


                            // Saving value to array
                            $sanitized_fields[$key] = $val;

			                       endif;

                endforeach;


                update_option('secure_axs', $sanitized_fields);


    }


    public function check_color( $value ) {

		if ( preg_match( '/^#[a-f0-9]{6}$/i', $value ) ) { // if user insert a HEX color with #
			return true;
		}

		return false;
    }

    public function display_login(){

        if( $this->actual_link == get_bloginfo('url') . '/'. $this->perma_link  . $this->secure_axs['axs_url'] . '/' || $this->actual_link == get_bloginfo('url') . '/' . $this->perma_link  . $this->secure_axs['axs_url'] ) {

            wp_die( self::render_login(), get_bloginfo('name') . ' | Secure AXS', array('response' => '404') );
		}
    }

    public function render_login(){

		$site_key = $this->gcaptcha_key;
		$secure_axs = $this->secure_axs;
		$users_can_register = $this->users_can_register;
		include(sprintf("%s/login.php", dirname(__FILE__)));
		return $form_html;

    }

    public function axs_signon(){

		// Security Check
		if( isset($_POST['g-recaptcha-response']) ):
			require_once __DIR__ . '/gCaptcha/autoload.php';

			$recaptcha = new \ReCaptcha\ReCaptcha($this->gcaptcha_secret);
			$resp = $recaptcha->verify($_POST['g-recaptcha-response'], $_SERVER['REMOTE_ADDR']);
			if ( $resp->isSuccess() ) :

				// verified!
				$creds = array();
				$creds['user_login'] = sanitize_user( $_POST['axs_login'] );
				$creds['user_password'] = $_POST['axs_pass'];
				$creds['remember'] = FALSE;
				$axs_user = wp_signon( $creds );

					if (! is_wp_error($axs_user)):

						header("location: ". get_bloginfo('url') ."/wp-admin/") ;
						die();

					else:

						wp_die( self::render_login(), strip_tags( $axs_user->get_error_message() ), array('response' => '404') );

					endif;

			else:

				// Die
				wp_die( __('Security Check Failed. Please reload the login page and try again.', 'Secure AXS Failed'), array('response' => '404') );

			endif;

		endif; //isset($_POST['g-recaptcha-response'])



    }

    public function block_default_login(){

    global $pagenow;

    $allowed_logins = array("lostpassword", "logout", "register", "rp", "postpass", "resetpass");

        if( ( 'wp-login.php' == $pagenow ) && ( !in_array($_REQUEST['action'], $allowed_logins) ) ){

            wp_redirect( get_bloginfo('url') );
            exit();
        }

    }

    public function current_url(){

        if( ($_SERVER['HTTPS'] ) ): $http_protocol = 'https://'; else: $http_protocol = 'http://'; endif;
        $actual_link = $http_protocol . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];

        return $actual_link;
    }

    public function secure_axs_activate(){

        // Set new default login page.
        if( $this->secure_axs[axs_url] == NULL ):
        $default_settings = array('gcaptcha_key' => '6LdMZRkTAAAAAF30PS_p_J_LgAgrI4DWc_59fsng', 'gcaptcha_secret' => '6LdMZRkTAAAAAI9TEyM7Jm1KATxQT61fUNRDlKCC', 'axs_url' => 'axs-login', 'bg_color' => '#ffffff', 'text_color' => '#000000', 'axs-permalink' => $this->perma_link);
        update_option('secure_axs', $default_settings);
        endif;

    }


    public function deactivate(){
        // Do nothing
    }


    } // End of class.


	$SecureAxs = new SecureAxs();

} //if class exists check.

else{

    function secure_axs_terminated(){

    echo '<div class="error">

       <p>Secure AXS terminated to avoid conflicting with another instance using the same class. <a href="mailto:motaz@madeomedia.com">Contact support.<a/></p>

    </div>';

    }

    add_action('admin_notices', 'secure_axs_terminated');


}


?>
