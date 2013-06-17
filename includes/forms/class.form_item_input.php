<?php
/**
 * Input element
 *
 * @see form_item
 * @author KronuS
 * @package form_builder
 */
class form_item_input extends form_item
{
    /**
     * Field type (text - default)
     * @access private
     * @var string
     */
    private $_type = 'text';

    /**
     * Get field type
     * @access public
     * @return string
     */
    public function get_type() {
        return $this->_type;
    }

    /**
     * Set field type
     * @access public
     * @param string $type field type
     * @return form_item_input self
     */
    public function set_type($type) {
        $this->_type = (string)$type;
        return $this;
    }

    /**
     * Basic construct. Initialize current template
     * @access public
     */
    public function __construct() {
        parent::__construct();
        $template = '<div class="'.self::class_prefix.'form-element-wrapper '.self::class_prefix.'form-element-input-wrapper %wrapper_classes%"><div class="'.self::class_prefix.'label-wrapper"><label for="%label_id%">%label%</label>%required%</div><div class="'.self::class_prefix.'input-wrapper"><input type="%type%" %attr% %value% /></div></div>';
        $this->set_template($template);
    }

    /**
     * Get HTML-code for current input field
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
        $template = str_replace('%type%', $this->get_type(), $template);
        $template = str_replace('%label%', $this->get_label(), $template);
        if ($this->get_type() !== 'file') {
            $template = str_replace('%value%', 'value="'.$this->get_default_value().'"', $template);
        }
        else {
            $template = str_replace('%value%', '', $template);
        }
        $template = str_replace('%attr%', $this->get_attr_string(), $template);
        $template = str_replace('%wrapper_classes%', $this->get_wrapper_classes_string(), $template);
        return $template;
    }
}
