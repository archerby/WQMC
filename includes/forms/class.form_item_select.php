<?php

/**
 * Select element
 *
 * @see form_item
 * @author KronuS
 * @package form_builder
 */
class form_item_select extends form_item
{
    /**
     * List of values
     * @access private
     * @var array
     */
    private $_options = array();

    /**
     * Add or edit option value in list
     * @param string $value value
     * @param string $text visible text in list
     * @return form_item_select self
     */
    public function set_option($value, $text) {
        $this->_options[$value] = $text;
        return $this;
    }

    /**
     * Add or edit array of values in list
     * @param array $options
     * @return form_item_select self
     */
    public function set_options_array(array $options) {
        $this->_options = $options + $this->_options;
        return $this;
    }

    /**
     * Get option from list by its value
     * @param $value
     * @return mixed string - if option exists, null - of not
     */
    public function get_option($value) {
        if (isset($this->_options[$value])) {
            return $this->_options[$value];
        }
        return null;
    }

    /**
     * Basic construct. Initialize current template
     * @access public
     */
    public function __construct() {
        parent::__construct();
        $template = '<div class="' . self::class_prefix .'form-element-wrapper '.self::class_prefix.'form-element-select-wrapper %wrapper_classes%"><div class="'.self::class_prefix.'label-wrapper"><label for="%label_id%">%label%</label>%required%</div><div class="'.self::class_prefix.'select-wrapper"><select %attr%>%options%</select></div></div>';
        $this->set_template($template);
    }

    /**
     * Get HTML-code for current selectbox
     * @access public
     * @return string
     */
    public function compile() {
        $template = $this->get_template();
        if (!is_null($this->get_attr('id'))) {
            $template = str_replace('%label_id%', $this->get_attr('id'), $template);
        }
        else {
            $template = str_replace('%label_id%', '', $template);
        }
        if ($this->get_required()) {
            $template = str_replace('%required%', $this->get_required_template(), $template);
        }
        else {
            $template = str_replace('%required%', '', $template);
        }
        $options = '';
        foreach($this->_options as $val=>$text) {
            if ($val == $this->get_default_value()) {
                $options .= '<option value="'.$val.'" selected="selected">'.$text.'</option>';
            }
            else {
                $options .= '<option value="'.$val.'">'.$text.'</option>';
            }
        }
        $template = str_replace('%options%', $options, $template);
        $template = str_replace('%label%', $this->get_label(), $template);
        $template = str_replace('%attr%', $this->get_attr_string(), $template);
        $template = str_replace('%wrapper_classes%', $this->get_wrapper_classes_string(), $template);
        return $template;
    }
}







