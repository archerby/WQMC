<?php
/**
 * Form for adding question or editing existed question. Shouldn't be called directly - only include to another page
 *
 * @author KronuS
 * @version 1.0
 */

// $data - array with question data (need if this form is used to update question info)
$form = new form_builder();

$input = new form_item_textarea();
$input->set_label('Task')->set_attr_array(array('name'=>'task', 'id'=>'task'))->set_required(true);
if (isset($data) && isset($data['task'])) {
    $input->set_default_value($data['task']);
}
$form->add_field($input);

$input = new form_item_input();
$input->set_type('text')->set_label('Answer')->set_attr_array(array('name'=>'answer', 'id'=>'answer'))->set_required(true);
if (isset($data) && isset($data['answer'])) {
    $input->set_default_value($data['answer']);
}
$form->add_field($input);

$input = new form_item_input();
$input->set_label('Hint')->set_attr_array(array('name'=>'hint', 'id'=>'hint'))->set_required(true);
if (isset($data) && isset($data['hint'])) {
    $input->set_default_value($data['hint']);
}
$form->add_field($input);

$input = new form_item_input();
$input->set_label('Order Id')->set_attr_array(array('name'=>'oid', 'id'=>'oid'))->set_required(true);
if (isset($data) && isset($data['oid'])) {
    $input->set_default_value($data['oid']);
}
$form->add_field($input);

$input = new form_item_input();
$input->set_type('submit')->set_attr_array(array('name'=>'submit', 'id'=>'submit'));
if (isset($data)) {
    $input->set_default_value('Edit question');
}
else {
    $input->set_default_value('Add question');
}
$form->add_field($input);

$input = new form_item_input();
$input->set_type('hidden')->set_attr_array(array('name'=>'token', 'id'=>'token'))->set_default_value(@$token);
$form->add_field($input);

$form->set_method('post');
if (!isset($data)) {
    $form->set_action('?cmd=admin_add_level&amp;qid='.$qid);
}
else {
    $form->set_action('?cmd=admin_update_level&amp;lid='.$data['lid']);
}

echo $form->compile();