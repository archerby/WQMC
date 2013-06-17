<?php
/**
 * Password change form. Shouldn't be called directly - only include to another page
 *
 * @author KronuS
 * @version 1.0
 */
?>
<h1>Change password</h1>
<?php
// $token variable should contains current user token
$form = new form_builder();

$input = new form_item_input();
$input->set_type('password')->set_attr_array(array('name'=>'old_password', 'id'=>'old_password'))->set_required(true)->set_label('Old password');;
$form->add_field($input);

$input = new form_item_input();
$input->set_type('password')->set_attr_array(array('name'=>'new_password', 'id'=>'new_password'))->set_required(true)->set_label('New password');
$form->add_field($input);

$input = new form_item_input();
$input->set_type('password')->set_attr_array(array('name'=>'new_password_c', 'id'=>'new_password_c'))->set_required(true)->set_label('New password confirm');
$form->add_field($input);

$input = new form_item_input();
$input->set_type('hidden')->set_attr_array(array('name'=>'token', 'id'=>'token'))->set_default_value(@$token);
$form->add_field($input);

$input = new form_item_input();
$input->set_type('submit')->set_default_value('Change')->set_attr_array(array('name'=>'submit', 'id'=>'submit'));
$form->add_field($input);

$form->set_method('post');
$form->set_action('?cmd=change_password');

echo $form->compile();