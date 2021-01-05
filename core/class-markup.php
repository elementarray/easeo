<?php
/**
**/
namespace EASEO\Core;
     
class Markup {
	private $options;
    	public function init() {
 
        	add_action( 
			'wp_head',
            		array( $this, 'write' )
        	);
 
    	}

    	public function __construct(Options $options){ 
		$this->options = $options; 
		$this->init(); 
	}

    	// add the markup to the head
    	public function write() {

		if(!is_admin()){
		?>

<!-- Organization -->
<script type="application/ld+json">
{
      "@context": "https://schema.org",
      "@type": "Organization",
      "url": "<?php echo get_home_url(); ?>",
      "logo": "<?php if( has_site_icon() ): site_icon_url( ); endif; ?>"
}
</script>
	
<!-- LocalBusiness -->	
<script type="application/ld+json">
{
      "@context": "https://schema.org",
      "@type": "LocalBusiness",
      "image": "<?php if( has_site_icon() ): site_icon_url( ); endif; ?>",
      "@id": "<?php echo get_home_url(); ?>",
      "name": "<?php echo get_bloginfo('name'); ?>",
      "address": {
        "@type": "PostalAddress",
<?php
//$PostalAddress = get_option('easeo');
?>
        "streetAddress": "<?php echo $this->options->get('streetAddress'); ?>",
        "addressLocality": "<?php echo $this->options->get('addressLocality'); ?>",
        "addressRegion": "<?php echo $this->options->get('addressRegion'); ?>",
        "postalCode": "<?php echo $this->options->get('postalCode'); ?>",
        "addressCountry": "<?php echo $this->options->get('addressCountry'); ?>"
      },
/**
      "review": {
        	"@type": "Review",
        	"reviewRating": {
          		"@type": "Rating",
          		"ratingValue": "5",
          		"bestRating": "5"
		}
        },
**/
      "geo": {
        "@type": "GeoCoordinates",
        "latitude": "<?php echo $this->options->get('geoLattitude'); ?>",
        "longitude": "<?php echo $this->options->get('geoLongitude'); ?>"
      },
      "url": "<?php echo get_home_url(); ?>",
      "telephone": "<?php echo $this->options->get('bizTelephone'); ?>",
      "priceRange": "<?php echo $this->options->get('priceRange'); ?>",
      "openingHoursSpecification": [

<?php
$daysofweek = array("monday","tuesday","wednesday","thursday","friday","saturday","sunday");
$checked_daysofweek = array();
foreach($daysofweek as $day){
	if( $this->options->get($day."_check") ){
		array_push($checked_daysofweek, $day);
	}
}
$lastElement = end($checked_daysofweek);
foreach($checked_daysofweek as $checked_day){
		echo'{"@type": "OpeningHoursSpecification",';
		echo '"dayOfWeek": "'.ucfirst($checked_day).'",';
		echo '"opens": "'.$this->options->get($checked_day."_opens").'",';
		echo '"closes": "'.$this->options->get($checked_day."_closes").'"';
		if($checked_day == $lastElement) { echo '}'.PHP_EOL; }else{ echo '},'.PHP_EOL; }
}
?>

      ]
    }
</script>
		<?php
		}
    	}
}
