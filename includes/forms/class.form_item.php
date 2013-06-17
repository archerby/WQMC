<?php

/**
 * Basic class for form components
 *
 * @author KronuS
 * @package form_builder
 */
abstract class form_item
{
    /**
     * HTML-template for current field
     * @access private
     * @var string
     */
    private $_template;
    /**
     * Array of field attributes (like id, class etc)
     * @access private
     * @var array
     */
    private $_attributes = array();
    /**
     * Field default value
     * @access private
     * @var mixed
     */
    private $_default_value;
    /**
     * Field label
     * @access private
     * @var string
     */
    private $_label;
    /**
     * Shows if field is required
     * @access private
     * @var bool
     */
    private $_required = false;
    /**
     * HTML-template for required field marker
     * @access private
     * @var string
     */
    private $_required_template;
    /**
     * Array of css classes to wrapper block
     * @access private
     * @var array
     */
    private $_wrapper_css_classes = array();
    /**
     * prefix for css-classes used on component's wrappers
     */
    const class_prefix = 'kqc-';

    /**
     * Basic construct
     */
    public function __construct() {
        $this->_required_template = '<span class="'.self::class_prefix.'required-field">*</span>';
    }

    /**
     * Set 'required' flag
     * @access public
     * @param bool $r true - required, false - not required
     * @return form_item self
     */
    public function set_required($r) {
        $this->_required = (bool)$r;
        return $this;
    }

    /**
     * Get 'required' flag
     * @access public
     * @return bool
     */
    public function get_required() {
        return $this->_required;
    }

    /**
     * Get template for required marker
     * @access public
     * @return string
     */
    public function get_required_template() {
        return $this->_required_template;
    }

    /**
     * Set new template for required marker
     * @access public
     * @param string $template new template
     * @return form_item self
     */
    public function set_required_template($template) {
        $this->_required_template = (string)$template;
        return $this;
    }

    /**
     * Get template for required marker
     * @access public
     * @return string
     */
    public function get_template() {
        return $this->_template;
    }

    /**
     * Set HTML-template for field and wrappers
     * @access public
     * @param string $template new template
     * @return form_item self
     */
    public function set_template($template) {
        $this->_template = (string)$template;
        return $this;
    }

    /**
     * Get field default value
     * @access public
     * @return mixed
     */
    public function get_default_value() {
        return $this->_default_value;
    }

    /**
     * Set new default value
     * @access public
     * @param mixed $value
     * @return form_item self
     */
    public function set_default_value($value) {
        $this->_default_value = $value;
        return $this;
    }

    /**
     * Add attribute to field
     * @param string $key attribute name
     * @param string $value attribute value
     * @return form_item self
     */
    public function set_attr($key, $value) {
        $this->_attributes[$key] = $value;
        return $this;
    }

    /**
     * Add array of attributes to field
     * @param array $attr_array
     * @return form_item
     */
    public function set_attr_array(array $attr_array){
        $this->_attributes = $attr_array + $this->_attributes ;
        return $this;
    }

    /**
     * Get field attribute value by name
     * @access public
     * @param string $key attribute name
     * @return mixed string - attribute exists, null - not exists
     */
    public function get_attr($key) {
        if (isset($this->_attributes[$key])) {
            return $this->_attributes[$key];
        }
        return null;
    }

    /**
     * Get field label
     * @access public
     * @return string
     */
    public function get_label() {
        return $this->_label;
    }

    /**
     * Set field label
     * @access public
     * @param string $label
     * @return form_item self
     */
    public function set_label($label) {
        $this->_label = (string)$label;
        return $this;
    }

    /**
     * Add css-class to wrapper block
     * @access public
     * @param string $class
     * @return form_item self
     */
    public function add_wrapper_class($class) {
        if (!in_array($class, $this->_wrapper_css_classes)) {
            $this->_wrapper_css_classes[] = $class;
        }
        return $this;
    }

    /**
     * Add css-classes to wrapper block
     * @access public
     * @param array $classes array of classes
     * @return form_item self
     */
    public function add_wrapper_class_array(array $classes) {
        $this->_wrapper_css_classes = $this->_wrapper_css_classes + $classes;
        return $this;
    }

    /**
     * Remove css-class from wrapper block
     * @access public
     * @param $class
     * @return form_item self
     */
    public function remove_wrapper_class($class) {
        foreach($this->_wrapper_css_classes as $key=>$class_name) {
            if ($class == $class_name) {
                unset($this->_wrapper_css_classes[$key]);
                break;
            }
        }
        return $this;
    }
    /**
     * Implode attributes array to string
     * @access protected
     * @return string
     */
    protected function get_attr_string() {
        $attr = '';
        foreach($this->_attributes as $key=>$value) {
            $attr .= $key.'="'.$value.'" ';
        }
        return rtrim($attr, ' ');
    }

    /**
     * Implode wrapper css classes to string
     * @access protected
     * @return string
     */
    protected function get_wrapper_classes_string() {
        return implode(' ', $this->_wrapper_css_classes);
    }
    /**
     * Generate field HTML-code. Method is redeclared for each subclass
     * @abstract
     * @access public
     */
    abstract public function compile();
}