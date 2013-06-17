<?php
/**
 * Class-container for global data provided by user (not application config!)
 *
 * @package KQC
 * @author KronuS
 * @version 1.0
 */
class request {
    /**
     * Array of data provided via REQUEST array or argv
     * @access private
     * @var array
     */
    private $_properties;

    /**
     * Simple object initialize
     * @access public
     */
    public function __construct() {
		$this->init();
	}

    /**
     * Main method. Save provided data to inner array _properties
     * @access public
     */
    public function init() {
		if(isset($_SERVER['REQUEST_METHOD'])) {
			$this->_properties = $_REQUEST;
			return;
		}
		foreach($_SERVER['argv'] as $arg) {
			if (strpos($arg, '=')) {
				list($key, $val) = explode('=', $arg);
				
			}
		}
	}

    /**
     * Get property value by its key. Return null if property doesn't exist
     * @access public
     * @param string $key
     * @return mixed
     */
	public function get($key) {
		if (isset($this->_properties[$key])) {
			return $this->_properties[$key];
		}
		return null;
	}

    /**
     * Set property value. Can overwrite existed values!
     * @access public
     * @param string $key key
     * @param mixed $val value
     */
    public function set($key, $val) {
		$this->_properties[$key] = $val;
	}

    /**
     * Get all properties as assoc array
     * @access public
     * @return array
     */
    public function to_array() {
        return $this->_properties;
    }
	
}