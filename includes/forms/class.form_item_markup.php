<?php

/**
 * Markup element
 *
 * @see form_item
 * @author KronuS
 * @package form_builder
 */
class form_item_markup extends form_item
{
    /**
     * Basic construct. Initialize current template
     * @access public
     */
    public function __construct() {
        parent::__construct();
        $template = '<div class="'.self::class_prefix.'form-element-wrapper '.self::class_prefix.'form-element-markup-wrapper %wrapper_classes%"><div class="'.self::class_prefix.'markup-wrapper">%value%</div></div>';
        $this->set_template($template);
    }

    /**
     * Get HTML-code for current markup
     * @access public
     * @return string
     */
    public function compile() {
        $template = $this->get_template();
        $template = str_replace('%value%', $this->get_default_value(), $template);
        $template = str_replace('%wrapper_classes%', $this->get_wrapper_classes_string(), $template);
        return $template;
    }
}