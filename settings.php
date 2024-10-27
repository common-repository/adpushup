<?php
/*
 *  Injects content into page at different places
 */

// Prevent direct access to file
defined('ABSPATH') or die("Cheatin' uh?");

class AdPushup_Settings {

    function __construct() {
	add_action('admin_menu', array($this, 'action_admin_menu'));
	add_action('admin_notices', array($this, 'action_admin_notices'));
    }

    function action_admin_menu() {
	    add_options_page(
		    'AdPushup Settings', 'AdPushup Settings', 'manage_options', 'adpushup_settings_page', 'adpushup_settings_page'
	    );
    }

    function action_admin_notices() {
	global $pagenow;

	$adpush_code = get_option('adpushup_site_id', '');
	$url = admin_url() . 'options-general.php?page=adpushup_settings_page&auto_close=yes';

	// Show error message everywhere except its setting page
	if (
		!('options-general.php' == $pagenow && isset($_GET['page']) && 'adpushup_settings_page' == $_GET['page']) &&
		empty($adpush_code)
	) {
	    echo "<script type='text/javascript'>
                    // Popup window code
                    function newPopup(url) {
                        popupWindow = window.open(
                            url,'popUpWindow','height=350,width=500,left=10,top=10,resizable=yes,scrollbars=yes,toolbar=no,menubar=no,location=no,directories=no,status=yes'
                        )

                    }
		    function AdPushupSettingResult(result) {
			if ('success' == result) {
			    jQuery('#adpushup_error_site_id')[0].outerHTML = \"<div class='updated'><p>AdPushup site ID updated successfully</p></div>\";
			}
		    }
                </script>
                
		<div class='error' id='adpushup_error_site_id'><p><b>Start optimizing your ads, Configure AdPushup plugin <a href='JavaScript:newPopup(\"$url\");'>here</a>.<b></p></div>
	    ";
	}
    }

}

new AdPushup_Settings();

function adpushup_settings_page() {
    $auto_close = isset($_REQUEST['auto_close']) && 'yes' == $_REQUEST['auto_close'];
    if (isset($_REQUEST['adpushup_site_id'])) {
	$adpushup_site_id = abs($_REQUEST['adpushup_site_id']);

	if (isset($_POST['_wpnonce']) && wp_verify_nonce($_POST['_wpnonce'], '_adpushup_site_id')) {
	    update_option('adpushup_site_id', $adpushup_site_id);
	    echo "<br/><div class='updated'><p>AdPushup site ID updated successfully</p></div>";
	    if ($auto_close) {
		echo "<script type='text/javascript'>window.opener.AdPushupSettingResult('success');window.close();</script>";
	    }
	}
    } else {
	$adpushup_site_id = get_option('adpushup_site_id', '');
    }
    ?>
          <h3>AdPushup Site ID</h3>
          <form method="POST" name="adpushup_submission_form">
    <?php wp_nonce_field('_adpushup_site_id') ?>
    	  <input type="number" name="adpushup_site_id" required size="50" value="<?php echo esc_attr($adpushup_site_id) ?>" />
    <?php submit_button('Save'); ?>
          </form>
		  Note : Your AdPushup Site ID can be found at <a href="http://app.adpushup.com/user/login" target="_blank">AdPushup Dashboard</a>.
<div class="bannerimage" style="margin-top:50px; margin-left: auto; margin-right: auto; text-align: center;">
<a href="http://app.adpushup.com/user/signup?utm_source=ap_wp_plugin&utm_medium=steps_banner&utm_campaign=AP_WP_Steps_Banner"><img src="../wp-content/plugins/adpushup/800x600 plugin banner.png"></a>
</div>
    <?php
}
