<?php
// EASEO.php
/**
* Plugin Name: 		EASEO
* Plugin URI:        	https://elementarray.com/easeo/
* Description:       	Add JSON-LD schema markup to your website
* Version:           	1.0.0
* Author:            	Elementarray
* Author URI:        	https://elementarray.com/author/eaadmin/
* License:           	GPL-3.0
* License URI:       	http://www.gnu.org/licenses/gpl-3.0.txt
* Text Domain:       	ea
* Domain Path:       	/languages
**/
namespace EASEO;	

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) 	{ die; 	} // define( 'WPINC', 'wp-includes' );

// define constants
define( __NAMESPACE__ . '\NS', __NAMESPACE__ . '\\' );	// 'EASEO\\'
define( NS . 'PLUGIN_NAME', 'easeo' ); 		
define( NS . 'PLUGIN_VERSION', '0.0.1' );				
define( NS . 'PLUGIN_NAME_DIR', plugin_dir_path( __FILE__ ) );		
define( NS . 'PLUGIN_NAME_URL', plugin_dir_url( __FILE__ ) );		
define( NS . 'PLUGIN_BASENAME', plugin_basename( __FILE__ ) );		
define( NS . 'PLUGIN_TEXT_DOMAIN', 'ea' );				

// Autoload Classes
require_once( PLUGIN_NAME_DIR . 'util/class-myautoloader.php' );
// find the plugin class here...
spl_autoload_register(__NAMESPACE__ .'\MyAutoloader::test');

// the plugin
class EASEO { 

    public static function load() { 
	// get_option('easeo',array())
        $options = Core\Options::load();
        Admin\AdminPage::register($options);
	Core\CSS_Loader::init();
	Core\JS_Loader::init();
	$markup = new Core\Markup($options);
    }

} 
EASEO::load();


 
