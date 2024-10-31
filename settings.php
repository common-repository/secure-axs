<?php

// Block direct access.
defined('ABSPATH') or die("Play nice!");

if( isset(  $_POST['_axs_settings_nonce'] )  ):
 
   
    // Security Check
    if( ! wp_verify_nonce( sanitize_text_field( $_POST['_axs_settings_nonce'] ), '_save_axs_settings' ) ):
        
        // Display Error
        add_settings_error( 'axs_settings_options', 'axs_security_error', 'Security check failed.', 'error' ); // $setting, $code, $message, $type
    
        // Die
        wp_die( __('Security Check Failed. Click <a href="' . get_bloginfo('url'). '/wp-admin/options-general.php?page=secure_axs">here</a> to try again.', 'Secure AXS Settings'), array('response' => '500') );
        
    else:

         self::validate_settings( $_POST['secure_axs'] );
         
         // Retreive updated values after saving.
         $this->secure_axs = self::retrieve_settings('secure_axs');
         
         // Reload to pickup any warning or errors.
         echo '<script>location.reload();</script>';
         
    endif;

 
endif;




?>


<div class="wrap">
    <?php    echo "<h2>" . __( 'Access Settings', 'Secure_Axs' ) . "</h2>"; ?>
    <form name="secure_axs" id="secure_axs" method="post" action="">
        <?php wp_nonce_field( '_save_axs_settings', '_axs_settings_nonce' ) ?>
        <table class="form-table">		
            

            <tbody>
                
                <tr valign="top"><th scope="row"><h4><?php _e( 'Security Options', 'Secure_Axs' ); ?></h4></tr>
                
            	<tr valign="top">
				<th scope="row">
                                    <label for="secure_axs[axs_url]">
                                        <?php _e("Access URL (i.e axs-login):", "Secure_Axs"  ); ?>
                                    </label></th>
                                    <td>
                                        <input name="secure_axs[axs_url]" type="text" id="axs_url" value="<?php echo $this->secure_axs['axs_url']; ?>" class="regular-text">
                                        <p class="description">Alphanumeric and dash "-" are only allowed.</p>
                                        <p class="description">Your current secured access login is: <a href="<?php echo get_bloginfo('url') . '/' . $this->perma_link . $this->secure_axs['axs_url']; ?>" target="_blank"><?php echo get_bloginfo('url') . '/' . $this->perma_link . $this->secure_axs['axs_url']; ?></p>
                                    </td>
                </tr>
                           
            	<tr valign="top">
				<th scope="row"><label for="secure_axs[allow_editors]"><?php _e("Allow Editors to edit settings:", "Secure_Axs"  ); ?></label></th>
                                <td><input name="secure_axs[allow_editors]" type="checkbox" id="allow_editors" <?php if( $this->secure_axs['allow_editors'] == 'on' ): echo 'checked'; endif;?>>
                                    <p class="description">When Checked, Editors are able to access/change plugin settings.</p>
                                </td>
                </tr>
                
                
                
                
                
                <tr valign="top">
		<th scope="row"><label for="secure_axs[gcaptcha_key]"><?php _e("reCAPTCHA site key* ", "Secure_Axs"  ); ?></label></th>
                <td>
                    <input name="secure_axs[gcaptcha_key]" type="text" 
                           placeholder="" 
                           value="<?php echo ( $this->secure_axs['gcaptcha_key'] == NULL ? NULL : $this->secure_axs['gcaptcha_key'] ); ?>"
                           class="regular-text">
                    <p class="description">Claim your free reCAPTCHA site key and secret key from this link <a href="https://www.google.com/recaptcha/admin" target="_blabk">https://www.google.com/recaptcha/admin</a></p>
                </td>
		</tr>
		
		         <tr valign="top">
		<th scope="row"><label for="secure_axs[gcaptcha_secret]"><?php _e("reCAPTCHA secret key* ", "Secure_Axs"  ); ?></label></th>
                <td>
                    <input name="secure_axs[gcaptcha_secret]" type="text" 
                           placeholder="" 
                           value="<?php echo ( $this->secure_axs['gcaptcha_secret'] == NULL ? NULL : $this->secure_axs['gcaptcha_secret'] ); ?>"
                           class="regular-text">
                </td>
		</tr>
                           
                           
            </tbody>
            

            <tbody>
                
                <tr valign="top"><th scope="row"><h4><?php _e( 'Branding Options', 'Secure_Axs' ); ?></h4></tr>
                
            	<tr valign="top">
				<th scope="row">
                                    <label for="secure_axs[bg_color]">
                                        <?php _e("Background Color", "Secure_Axs" ); ?>
                                    </label></th>
                                    <td>
                                        <input name="secure_axs[bg_color]" type="text" id="bg_color" value="<?php echo $this->secure_axs['bg_color']; ?>" class="regular-text axs-colors">
                                    </td>
		</tr>
            	<tr valign="top">
				<th scope="row">
                                    <label for="secure_axs[text_color]">
                                        <?php _e("Text Color" , "Secure_Axs" ); ?>
                                    </label></th>
                                    <td>
                                        <input name="secure_axs[text_color]" type="text" id="text_color" value="<?php echo $this->secure_axs['text_color']; ?>" class="regular-text axs-colors">
                                    </td>
		</tr>
                <tr valign="top">
				<th scope="row">
                                    <label for="secure_axs[brand_logo]">
                                        <?php _e("Custom Logo" , "Secure_Axs" ); ?>
                                    </label></th>
                                    <td>
                                        
                                        <div id="axs_image_thumb">
										<?php if($this->secure_axs['axs_image'] != NULL): ?>
                                        <img src="<?php echo $this->secure_axs['axs_image']; ?>" style="max-height: 50px; width: auto;"><br>
                                        <?php endif; ?>
                                        </div>
                                     
                                        <input name="secure_axs[axs_image]" id="axs_image" type="text" size="36" value="<?php echo $this->secure_axs['axs_image']; ?>" class="regular-text logo">
                                        <input type="button" id="axs_image_button" class="button-secondary upload-img" value="Select Image">
                                    </td>
		</tr>
		</tr>
                <tr valign="top">
				<th scope="row">
                                    <label for="secure_axs[brand_bg]">
                                        <?php _e("Custom Background Image" , "Secure_Axs" ); ?>
                                    </label></th>
                                    <td>
                                        <div id="brand_bg_thumb">
										<?php if($this->secure_axs['brand_bg'] != NULL): ?>
                                        <img src="<?php echo $this->secure_axs['brand_bg']; ?>" style="max-height: 50px; width: auto;"><br>
                                        <?php endif; ?>
                                        </div>
                                        
                                        <input name="secure_axs[brand_bg]" id="brand_bg" type="text" size="36" value="<?php echo $this->secure_axs['brand_bg']; ?>" class="regular-text">
                                        <input type="button" id="brand_bg_button" class="button-secondary upload-img" value="Select Image">
                                    </td>
		</tr>
            </tbody>
            
        </table>
             <?php submit_button(); ?>
    </form>
