<?php
/*
Plugin Name:  Google Tag Manager
Plugin URI:   https://www.zamzamlab.com/
Description:  Inserts google tag manager code into all pages
Version:      1.0
Author:       Naili Concescu
Author URI:   https://www.zamzamlab.com
License:      GPL2
License URI:  https://www.gnu.org/licenses/gpl-2.0.html
Text Domain:  gotama
Domain Path:  /languages
*/

class Gotama {
	
	public function __construct(){
		if ( is_admin() ){ // admin actions
			add_action('admin_menu', array( $this, 'admin_settings_page_menu' ) );
			//call register settings function
			add_action( 'admin_init', array( $this, 'register_settings_fields' ) );
		}
		
		add_action('wp_head', array($this, 'head_code'), 1);
		add_action('wp_footer', array($this, 'footer_code'), 1);
	}
	
	/*
	 * Adds menu item to admin menu
	 */
	function admin_settings_page_menu(){
		//create settings-evel menu
		add_options_page(
			'Google Tag Manager', 
      'Google Tag Manager', 
      'manage_options', 
      'gotama-settings', 
      array( $this, 'admin_settings_page_content' )
    );
	}
	
	/*
	 * Content of the admin settings page
	 */
	function admin_settings_page_content(){
		?>
<div class="wrap">
<h1>Google Tag Manager Settings</h1>
<form method="post" action="options.php">
    <?php settings_fields('gotama'); ?>
    <?php do_settings_sections( 'gotama' ); ?>
    <table class="form-table">
        <tr valign="top">
        <th scope="row">Google Tag Manager ID</th>
        <td><input type="text" name="gotama_gtm_id" placeholder="Example: GTM-XXX" value="<?php echo esc_attr( get_option('gotama_gtm_id') ); ?>" /></td>
        </tr>
    </table>
    
    <?php submit_button(); ?>

</form>
</div>
		<?php
	}
	
	/*
	 * Settings field
	 */
	function register_settings_fields(){
		register_setting( 'gotama', 'gotama_gtm_id' );
	}
	
	/*
	 * Code which will be included in front-end head
	 */
	function head_code(){
		$gtm_id = get_option('gotama_gtm_id');
		if( ! $gtm_id ) return false;
		// The following JavaScript should be as close to the opening <head> tag as possible on every page of the website
		?>
<!-- Google Tag Manager -->
<script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
})(window,document,'script','dataLayer','<?php echo esc_attr($gtm_id); ?>');</script>
<!-- End Google Tag Manager -->
		<?php
	}
	
	/*
	 * Code which will be included in front-end body
	 */
	function footer_code(){
		$gtm_id = get_option('gotama_gtm_id');
		if( ! $gtm_id ) return false;
		// The following snippet should be immediately after the opening <body> tag on every page of the website
		?>
<!-- Google Tag Manager (noscript) -->
<noscript><iframe src="https://www.googletagmanager.com/ns.html?id=<?php echo esc_attr($gtm_id); ?>"
height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
<!-- End Google Tag Manager (noscript) -->
		<?php
	}
}

$gotama = new Gotama();