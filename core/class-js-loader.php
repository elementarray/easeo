<?php
/**
 * Provides a consistent way to enqueue all administrative-related stylesheets.
 *
 * Implements the Interface_Assets by defining the init function and the enqueue function.
 * @implements Interface_Assets
**/
namespace EASEO\Core;
use EASEO\Interfaces as Interfaces;
class JS_Loader implements Interfaces\Interface_Assets {
 
    // Registers the 'enqueue' function with the proper WordPress hook for registering stylesheets.
     
    	public static function init() {
 
        	add_action( 
			'admin_enqueue_scripts',
            		array( __CLASS__, 'backend_enqueue' )
        	);

        	add_action( 
			'wp_enqueue_scripts',
            		array( __CLASS__, 'frontend_enqueue' )
        	);
 
    	}


    	public function __construct(  ) { }

	public static function frontend_enqueue(){
        	wp_enqueue_script(
            		'frontend_custom_jq',
            		plugins_url( 'assets/js/frontend_custom_jq.js', dirname( __FILE__ ) ),
            		array('jquery'),
            		filemtime( plugin_dir_path( dirname( __FILE__ ) ) . 'assets/js/frontend_custom_jq.js' ),
			true
        	);
	}

    	// Defines the functionality responsible for loading the file.
    	public static function backend_enqueue() {
        	wp_enqueue_script(
            		'backend_custom_jq',
            		plugins_url( 'assets/js/backend_custom_jq.js', dirname( __FILE__ ) ),
            		array('jquery'),
            		filemtime( plugin_dir_path( dirname( __FILE__ ) ) . 'assets/js/backend_custom_jq.js' ),
			true
        	);
		wp_localize_script( 
			'backend_custom_jq', 
			'ajaxTest', 
			array( 'ajax_url' => admin_url( 'admin-ajax.php' ) ) 
		);
 
    	}
}
