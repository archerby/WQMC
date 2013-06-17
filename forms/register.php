<?php
/**
 * Registration form. Shouldn't be called directly - only include to another page
 *
 * @author KronuS
 * @version 1.0
 */
?>
<h1>Register</h1>
<?php

$form = new form_builder();

$input = new form_item_input();
$input->set_type('text')->set_label('Login')->set_attr_array(array('name'=>'login', 'id'=>'login'))->set_required(true);
$form->add_field($input);

$input = new form_item_input();
$input->set_type('password')->set_label('Password')->set_attr_array(array('name'=>'pass', 'id'=>'pass'))->set_required(true);
$form->add_field($input);

$input = new form_item_input();
$input->set_type('password')->set_label('Confirm password')->set_attr_array(array('name'=>'pass_c', 'id'=>'pass_c'))->set_required(true);
$form->add_field($input);

$input = new form_item_input();
$input->set_type('text')->set_label('Captcha')->set_attr_array(array('name'=>'captcha', 'id'=>'captcha'))->set_required(true);
$form->add_field($input);

$m = new form_item_markup();
$m->set_default_value('<p><img src="secpic.php" alt="" /></p>');
$form->add_field($m);

$input = new form_item_input();
$input->set_type('submit')->set_default_value('Register')->set_attr_array(array('name'=>'submit', 'id'=>'submit'));
$form->add_field($input);

$form->set_method('post');
$form->set_action('?cmd=register');

echo $form->compile();
?>