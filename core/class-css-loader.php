<?php
/**
 * Provides a consistent way to enqueue all administrative-related stylesheets.
 *
 * Implements the Interface_Assets by defining the init function and the enqueue function.
 * @implements Interface_Assets
**/
namespace EASEO\Core;
use EASEO\Interfaces as Interfaces;
class CSS_Loader implements Interfaces\Interface_Assets {
 
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

    	// Defines frontend (visitor) loading the css style file.
	public static function frontend_enqueue(){
        	wp_enqueue_style(
            		'easeo_frontend_css',
            		plugins_url( 'assets/css/easeo.min.css', dirname( __FILE__ ) ),
            		array(),
            		filemtime( plugin_dir_path( dirname( __FILE__ ) ) . 'assets/css/easeo.min.css' )
        	);
	}

    	// Defines backend (admin) loading the css style file.
    	public static function backend_enqueue() {
 
        	wp_enqueue_style(
            		'easeo_backend_seo',
            		plugins_url( 'assets/css/easeo_backend.min.css', dirname( __FILE__ ) ),
            		array(),
            		filemtime( plugin_dir_path( dirname( __FILE__ ) ) . 'assets/css/easeo_backend.min.css' )
        	);
 
    	}
}
