<?php
namespace EASEO\Core;
// use EA_Social_Networks_Widget as NS;

class Options {

    private $options;
    //private static $instance;
	// unserialize(urldecode())
    public static function load() {
        $options = get_option('easeo',array());
        return new self($options? $options : (array) null);
    }

    public function __construct(array $options = array()){ $this->options = $options; }

    public function get($name, $default = null) { 
	if (!$this->has($name)) { return $default; }
        return $this->options[$name];
    }

    public function has($name) { return isset($this->options[$name]); }

    public function set($name, $value) { $this->options[$name] = $value; }

    //public static function get_instance(){return self::$instance;}
}
