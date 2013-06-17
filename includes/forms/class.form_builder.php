<?php

/**
 * HTML-forms generator. Each field is form_item object
 *
 * @author KronuS
 * @package form_builder
 */
class form_builder
{
    /**
     * Form components
     * @access private
     * @var array
     */
    private $_fields = array();
    /**
     * Submit method (post, get etc)
     * @access private
     * @var string
     */
    private $_method = 'post';
    /**
     * Form enctype
     * @access private
     * @var string
     */
    private $_enctype = '';
    /**
     * Where send form data
     * @access private
     * @var string
     */
    private $_action = '?';

    /**
     * Basic empty construct
     * @access public
     */
    public function __construct(){}

    /**
     * Set form submit method
     * @access public
     * @param string $method
     */
    public function set_method($method) {
        $this->_method = (string)$method;
    }

    /**
     * Get form submit method
     * @access public
     * @return string
     */
    public function get_method() {
        return $this->_method;
    }

    /**
     * Set form submit action
     * @access public
     * @param string $action
     */
    public function set_action($action) {
        $this->_action = (string)$action;
    }

    /**
     * Get form submit action
     * @access public
     * @return string
     */
    public function get_action() {
        return $this->_action;
    }

    /**
     * Set form enctype
     * @access public
     * @param string $enctype
     */
    public function set_enctype($enctype) {
        $this->_enctype = (string)$enctype;
    }

    /**
     * Get form enctype
     * @access public
     * @return string
     */
    public function get_enctype() {
        return $this->_enctype;
    }

    /**
     * Add new field to the form
     * @access public
     * @param form_item $field new form component
     */
    public function add_field(form_item $field) {
        $this->_fields[] = $field;
    }

    /**
     * Remove field from the form
     * @access public
     * @param form_item $field
     */
    public function remove_field(form_item $field) {
        foreach($this->_fields as $key=>$f) {
            if ($f == $field) {
                unset($this->_fields[$key]);
                return;
            }
        }
    }

    /**
     * Generate form html-code
     * @access public
     * @return string
     */
    public function compile() {
        $html = '';
        foreach($this->_fields as $field) {
            $html .= $field->compile();
        }
        $html = '<div class="kqc-form-wrapper"><form class="kqc-form" action="'.$this->_action.'" method="'.$this->_method.'" enctype="'.$this->_enctype.'"><fieldset>'.$html.'</fieldset></form></div>';
        return $html;
    }
}