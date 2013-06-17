<?php

/**
 * Textarea element
 *
 * @see form_item
 * @author KronuS
 * @package form_builder
 */
class form_item_textarea extends  form_item
{
    /**
     * Basic construct. Initialize current template
     * @access public
     */
    public function __construct() {
        parent::__construct();
        $template = '<div class="'.self::class_prefix.'form-element-wrapper '.self::class_prefix.'form-element-textarea-wrapper %wrapper_classes%"><div class="'.self::class_prefix.'label-wrapper"><label for="%label_id%">%label%</label>%required%</div><div class="'.self::class_prefix.'textarea-wrapper"><textarea %attr%>%value%</textarea></div></div>';
        $this->set_template($template);
    }

    /**
     * Get HTML-code for current textarea
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
        $template = str_replace('%label%', $this->get_label(), $template);
        $template = str_replace('%value%', $this->get_default_value(), $template);
        $template = str_replace('%attr%', $this->get_attr_string(), $template);
        $template = str_replace('%wrapper_classes%', $this->get_wrapper_classes_string(), $template);
        return $template;
    }
}