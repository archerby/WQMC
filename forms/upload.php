<?php
/**
 * Upload file form. Shouldn't be called directly - only include to another page
 *
 * @author KronuS
 * @version 1.0
 */
?>
<h1>Upload file</h1>
<?php

$form = new form_builder();

$input = new form_item_input();
$input->set_type('file')->set_label('File')->set_attr_array(array('name'=>'file', 'id'=>'file'))->set_required(true);
$form->add_field($input);

$input = new form_item_input();
$input->set_type('submit')->set_default_value('Upload')->set_attr_array(array('name'=>'submit', 'id'=>'submit'));
$form->add_field($input);

$form->set_method('post');
$form->set_enctype('multipart/form-data');
$form->set_action('?cmd=admin_files');

echo $form->compile();