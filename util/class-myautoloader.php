<?php
namespace EASEO;

class MyAutoloader {

    public static function test($name){
	if ( false === strpos( $name, 'EASEO' ) )  { return; }
	$file_parts = explode('\\',$name);			
    	$namespace = '';
    	for ( $i = count( $file_parts ) - 1; $i > 0; $i-- ) {	
		$current = strtolower( $file_parts[ $i ] );
		$current = str_ireplace( '_', '-', $current );
		if ( count( $file_parts ) - 1 === $i ) {
			if ( strpos( strtolower( $file_parts[ count( $file_parts ) - 1 ] ), 'interface' ) === 0 ) {
			       	$file_name = "$current.php";
				//echo "<br/>file_name = ".$file_name;
			} else { 
				$file_name = "class-$current.php"; 
				}				
		} else {  
			$namespace = '/' . $current . $namespace;
			//echo "<br/>NAMESPACE = ".$namespace."CURRENT = ".$current;
		}
	}

	$filepath  = __DIR__  .'/..'. $namespace ."/" ;
	//echo "<br/>filepath (before concatenation) = ".$filepath;
	$filepath .= $file_name; // make sure the file exists, if the so include it.
	//echo "<br/>filepath (after concatenation) = ".$filepath;
	if ( file_exists( $filepath ) ) { 
		//spl_autoload($filepath);
		$bar = include( $filepath );
		//echo $bar;
	}
	else{
		echo "file does not exist";
	}
    }
}
?>
