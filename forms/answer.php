<?php
/**
 * Form for level answer. Shouldn't be called directly - only include to another page
 *
 * @author KronuS
 * @version 1.0
 */

// $qid variable should be defined for valid form "action" property

$form = new form_builder();

$input = new form_item_input();
$input->set_type('text')->set_label('Answer')->set_attr_array(array('name'=>'answer', 'id'=>'answer'))->set_required(true);
$form->add_field($input);

$input = new form_item_input();
$input->set_type('submit')->set_default_value('Answer')->set_attr_array(array('name'=>'submit', 'id'=>'submit'));
$form->add_field($input);

$form->set_method('post');
$form->set_action('?cmd=quest&qid='.$qid);

echo $form->compile();