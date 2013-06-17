<?php
/**
 * Form for adding quest or editing existed quest. Shouldn't be called directly - only include to another page
 *
 * @author KronuS
 * @version 1.0
 */

// $data - array with quest data (need if this form is used to update quest info)
$form = new form_builder();

$input = new form_item_input();
$input->set_type('text')->set_label('Name')->set_attr_array(array('name'=>'name', 'id'=>'name'))->set_required(true);
if (isset($data) && isset($data['name'])) {
    $input->set_default_value($data['name']);
}
$form->add_field($input);

$input = new form_item_input();
$input->set_type('checkbox')->set_label('Quest is open')->set_attr_array(array('name'=>'is_open', 'id'=>'is_open'))->set_required(true);
if (isset($data) && isset($data['is_open'])) {
    if ($data['is_open']) {
        $input->set_attr('checked', 'checked');
    }
}
$form->add_field($input);

$select = new form_item_select();
$select->set_label('Theme')->set_options_array(app::get_themes())->set_attr_array(array('name'=>'theme', 'id'=>'theme'))->set_required(true);
if (isset($data) && isset($data['theme']) && in_array($data['theme'], app::get_themes())) {
    $select->set_default_value($data['theme']);
}
$form->add_field($select);

$input = new form_item_textarea();
$input->set_label('Intro')->set_attr_array(array('name'=>'intro', 'id'=>'intro'))->set_required(true);
if (isset($data) && isset($data['intro'])) {
    $input->set_default_value($data['intro']);
}
$form->add_field($input);

$input = new form_item_textarea();
$input->set_label('Outro')->set_attr_array(array('name'=>'outro', 'id'=>'outro'))->set_required(true);
if (isset($data) && isset($data['outro'])) {
    $input->set_default_value($data['outro']);
}
$form->add_field($input);

$input = new form_item_input();
$input->set_type('hidden')->set_attr_array(array('name'=>'token', 'id'=>'token'))->set_default_value(@$token);
$form->add_field($input);

$input = new form_item_input();
$input->set_type('submit')->set_attr_array(array('name'=>'submit', 'id'=>'submit'));
if (isset($data)) {
    $input->set_default_value('Edit quest');
}
else {
    $input->set_default_value('Add quest');
}
$form->add_field($input);

$form->set_method('post');
if (!isset($data)) {
    $form->set_action('?cmd=admin_add_quest');
}
else {
    $form->set_action('?cmd=admin_update_quest&amp;qid='.$data['qid']);
}

echo $form->compile();