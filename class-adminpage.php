<?php
namespace EASEO\Admin;
use EASEO\Core as Core;
use EASEO as NS;

class AdminPage {

    private $options;
    private $plugin_name;
    private $version;
    private $plugin_text_domain;
    
    public static function register( Core\Options $options ){
        $page = new self( $options );

/** "... register this function ...using the admin_menu action hook... **/
        add_action( "admin_menu", array( $page, "add_easeo_items" ) );

/** "... register_setting() as well as the above mentioned add_settings_*() functions should all be called from a 'admin_init' action hook callback function..." **/
        add_action( "admin_init", array( $page, "easeo_fields" ) );

	// Processing the Request
	add_action( "wp_ajax_save_order", array( $page,  "ajax_save_order" ) );
	add_action( "wp_ajax_nopriv_save_order", array( $page,  "ajax_save_order" ) );
    }

    public function __construct(Core\Options $options){ 
	$this->options = $options; 
	$this->plugin_name = NS\PLUGIN_NAME;
	$this->version = NS\PLUGIN_VERSION;
	$this->plugin_text_domain = NS\PLUGIN_TEXT_DOMAIN;
    }

    public function ajax_save_order() {
	// server response
	echo '<pre>';
	//print_r($_POST);					
	$mydata = $_POST['ajax_form_data']; // from the javascript ajax
	//print_r(explode("&", urldecode($mydata)));
  	parse_str(urldecode($mydata), $arr);
  	//print_r($arr);
	$easeo_array = $arr['easeo'];
	foreach($easeo_array as $item=>$values){
     		echo "item:".$item.", value:".$values."</br>";
		$this->options->set($item,$values);
    	}
	echo '</pre>';

	// add to the page
		
	wp_die();
    }
/**
// https://codex.wordpress.org/Administration_Menus
Every Plot Needs a Hook
To add an administration menu, you must do three things:

Create a function that contains the menu-building code
Register the above function using the admin_menu action hook. (If you are adding an admin menu for the Network, use network_admin_menu instead).
Create the HTML output for the page (screen) displayed when the menu item is clicked
**/
    	public function add_easeo_items(){
		add_menu_page(
			__("EASEO Items", "ea" ),
			__("EASEO Items Menu", "ea" ),
			"manage_options", 
			"easeo-menu-page", 
			array($this, "easeo_settings_page" ), 
			null, 
			99
		);
	}

    /**
     * Sanitize each setting field as needed
     *
     * @param array $input Contains all settings fields as array keys
     */
    public function sanitize( $input ){
        $new_input = array();
        if( isset( $input['id_number'] ) )
            $new_input['id_number'] = absint( $input['id_number'] );

        if( isset( $input['title'] ) )
            $new_input['title'] = sanitize_text_field( $input['title'] );

        return $new_input;
    }

/**
// https://codex.wordpress.org/Settings_API
////////////////////////////////////////////////////////////////
Adding Settings Sections
//////////////////////////////////////////////////////////////////
Settings Sections are the groups of settings you see on WordPress settings pages with a shared heading. In your plugin you can add new sections to existing settings pages rather than creating a whole new page. This makes your plugin simpler to maintain and creates fewer new pages for users to learn. You just tell them to change your setting on the relevant existing page.

add_settings_section( $id, $title, $callback, $page )
$callback - Function that fills the section with the desired content. The function should echo its output.
///////////////////////////////////////////////////////////////////////////////////
Adding Setting Fields
/////////////////////////////////////////////////////////////////////////////////////
You can add new settings fields (basically, an option in the wp_options database table but totally managed for you) to the existing WordPress pages using this function. Your callback function just needs to output the appropriate HTML input and fill it with the old value; the saving will be done behind the scenes. You can create your own sections on existing pages using add_settings_section() as described below.

NOTE: You MUST register any options you use with add_settings_field() or they won't be saved and updated automatically. See below for details and an example.

add_settings_field( $id, $title, $callback, $page, $section = 'default', $args = array() )
$callback - Function that fills the field with the desired inputs as part of the larger form. Name and id of the input should match the $id given to this function. The function should echo its output.

///////////////////////////////////////////////
Registering Settings
///////////////////////////////////////////////
NOTE: register_setting() as well as the above mentioned add_settings_*() functions should all be called from a 'admin_init' action hook callback function. 

register_setting( $option_group, $option_name, $args )
unregister_setting( $option_group, $option_name )

///////////////////////////////////////////////////////////////
Options Form Rendering
//////////////////////////////////////////////////////////////
When using the API to add settings to existing options pages, you do not need to be concerned about the form itself, as it has already been defined for the page. When you define a new page from scratch, you need to output a minimal form structure that contains a few tags that in turn output the actual sections and settings for the page.

To display the hidden fields and handle security of your options form, the Settings API provides the settings_fields() function. 
settings_fields( $option_group );

$option_group
(string) (required) A settings group name. This must match the group name used in register_setting(), which is the page slug name on which the form is to appear.
Default: None
To display the sections assigned to the page and the settings contained within, the Settings API provides the do_settings_sections() function. 
do_settings_sections( $page );
**/
    	public function easeo_fields(){
		add_settings_section(
			"easeo-settings-section", 
			__("EASEO Settings"), 
			array($this,"easeo_options_callback"), 
			"theme-options"
		);
		
		// add here one by one... into array...
		$easeo = array(
				"streetAddress" => "default address",
				"addressLocality" => "default locality",
				"addressRegion" => "default region",
				"postalCode" => "default zip",
				"addressCountry" => "default country",
				"geoLattitude" => "default lattitude",
				"geoLongitude" => "default longitude",
				"bizTelephone" => "bizTelephone",
				"priceRange" => "priceRange"
		);
		foreach($easeo as $key => $value) {
			add_settings_field(
				$key, 
				__( $key, "ea" ), 
				array($this,"easeo_textbox_callback"), 
				"theme-options",
				"easeo-settings-section",
				 array($key => $value)
			);
		    	register_setting("easeo-settings-section", "easeo", array($key => $value));
			/**
				register_setting(
				    'my_option_group', // Option group
				    'my_option_name', // Option name
				    array( $this, 'sanitize' ) // Sanitize
				);
			**/
		}
// add_settings_field( string $id, string $title, callable $callback, string $page, string $section = 'default', array $args = array() )
			$time = "openingHoursSpecification";
			add_settings_field(
				$time, 
				__( $time, "ea" ), 
				array($this,"easeo_time_callback"), 
				"theme-options",
				"easeo-settings-section"
				 //array($time)
			);
			register_setting("easeo-settings-section", "easeo");
	}

    	public function opens_closes($str) {  
		echo '<label for="opens">Opens<input type="time" id="opens" name="easeo['.$str.'_opens]" min="00:00" max="23:59"  value="'.$this->options->get($str."_opens","00:00").'"></label>';
		echo '<label for="closes">Closes<input type="time" id="closes" name="easeo['.$str.'_closes]" min="00:00" max="23:59"  value="'.$this->options->get($str."_closes","00:00").'"></label>';
	}

    	public function easeo_time_callback() {  
	// https://stackoverflow.com/questions/1010941/html-input-arrays
	?>
	<p>Weekly Operations By Day</p>

		<label for="monday">Monday<input type="checkbox" id="monday" name="easeo[monday_check]" <?php checked($this->options->get("monday_check"),"on"); ?> ></label>
		<?php $this->opens_closes("monday"); ?></br>
		<label for="tuesday">Tuesday<input type="checkbox" id="tuesday" name="easeo[tuesday_check]" <?php checked($this->options->get("tuesday_check"),"on"); ?> ></label>
		<?php $this->opens_closes("tuesday"); ?></br>
		<label for="wednesday">Wednesday<input type="checkbox" id="wednesday" name="easeo[wednesday_check]" <?php checked($this->options->get("wednesday_check"),"on"); ?> ></label>
		<?php $this->opens_closes("wednesday"); ?></br>
		<label for="thursday">Thursday<input type="checkbox" id="thursday" name="easeo[thursday_check]" <?php checked($this->options->get("thursday_check"),"on"); ?> ></label>
		<?php $this->opens_closes("thursday"); ?></br>
		<label for="friday">Friday<input type="checkbox" id="friday" name="easeo[friday_check]" <?php checked($this->options->get("friday_check"),"on"); ?> ></label>
		<?php $this->opens_closes("friday"); ?></br>
		<label for="saturday">Saturday<input type="checkbox" id="saturday" name="easeo[saturday_check]" <?php checked($this->options->get("saturday_check"),"on"); ?> ></label>
		<?php $this->opens_closes("saturday"); ?></br>
		<label for="sunday">Sunday<input type="checkbox" id="sunday" name="easeo[sunday_check]" <?php checked($this->options->get("sunday_check"),"on"); ?> ></label>
		<?php $this->opens_closes("sunday"); ?></br>
	<?php
	}

    	public function easeo_settings_page(){
	?>
		<div class="container-fluid px-0 mx-0">
			<h1><?php _e('EASEO Items Menu', 'ea'); ?></h1>
            <form id="easeo_form" method="post" action="options.php">
			<?php
			settings_fields("easeo-settings-section");
			do_settings_sections("theme-options");      
			submit_button(); 
			?>          
			</form>
		</div>
	<?php
	}

    	public function easeo_options_callback($args) { 
		// Section Callback
		?>
		<section id="ea_ajax_feedback"></section>
		<section id="ea_form_feedback"></section>
		<?php
/**
    		echo "<pre>easeo_options_callback(args)";
		print_r($args);
		echo "</pre>";  
**/
	}

    	public function easeo_textbox_callback($args) {  
		//Reset the array back to the start.
		$v = reset($args);
		//Fetch the key from the current element.
		$k = key($args);
	?>
		<label for="<?php 
				echo $k; 
				?>"
		><?php echo $k; ?>
    		<input type="text" 
			id="<?php 
				echo $k; 
				?>" 
			name="<?php 
				echo "easeo[".$k."]"; 
				?>" 
			value="<?php 
				echo $this->options->get($k,$v); 
				?>" 
		/>

		</label>
	</br>
	<?php
/**
    		echo "<pre>easeo_textbox_callback(args)";
		print_r($args);
		echo "</pre>";  
**/
	?>

	<?php
    	}
}